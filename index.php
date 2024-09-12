<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'config/database.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamResult;

session_start();

$app = AppFactory::create(); // Create Slim app

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Middleware to restrict access to Firefox on PC/Laptop only
$app->add(function ($request, $handler) {
    $userAgent = $request->getHeaderLine('User-Agent');

    // Check if the browser is Firefox
    $isFirefox = strpos($userAgent, 'Firefox') !== false;

    // Detect mobile devices based on common User-Agent substrings
    $isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod|Windows Phone/i', $userAgent);

    // Block if the browser is not Firefox or if it's a mobile device
    if (!$isFirefox || $isMobile) {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write('Access restricted: Only Firefox on PC/Laptop is allowed');
        return $response->withStatus(403); // Forbidden
    }

    // Proceed to the next middleware or route
    return $handler->handle($request);
});

// Include routes after app is created
require 'app/Routes/DashboardRoute.php';
require 'app/Routes/StartExamRoute.php';
require 'app/Routes/ListeningRoute.php';
require 'app/Routes/WritingRoute.php';
require 'app/Routes/ReadingRoute.php';
require 'app/Routes/CompleteRoute.php';
require 'app/Routes/UploadCsvRoute.php';
require 'app/Routes/DownloadCsvRoute.php';

// Render login view as the homepage
$app->get('/', function ($request, $response, $args) {
    if (isset($_SESSION['user_id'])) {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/views/login.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});

// Handle login form submission
$app->post('/', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    $username = $data['username'];
    $password = $data['password'];

    $user = User::where('username', $username)->first();

    if ($user && $user->password === $password) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role_id'] = $user->role_id;
        $_SESSION['name'] = $user->name;

        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    } else {
        $error = 'Invalid username or password';
        ob_start();
        require __DIR__ . '/views/login.php';
        $output = ob_get_clean();
        $response->getBody()->write($output);
    }

    return $response;
});

// Logout route
$app->get('/logout', function ($request, $response, $args) {
    session_destroy();
    return $response
        ->withHeader('Location', '/')
        ->withStatus(302);
});

// Route to handle image upload
$app->post('/upload-image', function ($request, $response, $args) {
    // Get parsed body and files
    $data = $request->getParsedBody();
    
    // Check if image data is set
    if (!isset($data['image'])) {
        $response->getBody()->write('Image data is missing');
        return $response->withStatus(400);
    }
    
    $image = $data['image'];
    
    // Ensure image data is not null
    if ($image === null) {
        $response->getBody()->write('Invalid image data');
        return $response->withStatus(400);
    }
    
    // Decode the base64 image
    $image = str_replace('data:image/png;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $imageData = base64_decode($image);

    if (strlen($imageData) === 0) {
        $response->getBody()->write('Decoded image data is empty');
        return $response->withStatus(400);
    }
    
    // Generate a unique filename
    $filename = uniqid() . '.png';

    // Save image to uploads folder
    $uploadDir = __DIR__ . '/uploads/';
    $filePath = $uploadDir . $filename;

    // Ensure the directory exists and is writable
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        $response->getBody()->write('Failed to create upload directory');
        return $response->withStatus(500);
    }

    // Save the image
    if (file_put_contents($filePath, $imageData) === false) {
        $response->getBody()->write('Failed to save image');
        return $response->withStatus(500);
    }

    // Retrieve user_id from the session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        $response->getBody()->write('User not authenticated');
        return $response->withStatus(403);
    }
    $userId = $_SESSION['user_id'];

    // Save the file path to the database
    $user = User::find($userId);
    $user->cam_image = $filename; // Save to 'cam_image' column
    $user->save();

    $response->getBody()->write('Image uploaded successfully');
    return $response->withStatus(200);
});

// reset exam
$app->get('/reset-exam/{id}', function ($request, $response, $args) {
    $userId = $args['id'];

    // Fetch the exam and exam results based on user_id
    $exam = Exam::where('user_id', $userId)->first();
    $examResults = ExamResult::where('user_id', $userId)->get();
    $user = User::find($userId); // Fetch the user

    if ($exam) {
        // Delete all exam results for the user
        foreach ($examResults as $result) {
            $result->delete();
        }

        // Delete the exam record for the user
        $exam->delete();

        // Mark the user as needing a reset (set reset_required to true)
        if ($user) {
            $user->reset_required = true;
            $user->save();
        }

        // Set a success message
        $_SESSION['message_notification'] = "Exam for user ID {$userId} has been reset.";
    } else {
        // Set an error message if no exam found
        $_SESSION['message_notification'] = "No exam found for user ID {$userId}.";
    }

    // Redirect back to the dashboard with a success or error message
    return $response->withHeader('Location', '/dashboard')->withStatus(302);
});

$app->get('/get-reset-flag', function ($request, $response, $args) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        return $response->withStatus(403); // Unauthorized
    }

    $userId = $_SESSION['user_id'];
    $user = User::find($userId);

    if ($user) {
        // Prepare JSON response
        $data = ['reset_required' => $user->reset_required];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    return $response->withStatus(404); // Not Found
});

$app->post('/clear-reset-flag', function ($request, $response, $args) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        return $response->withStatus(403); // Unauthorized
    }

    $userId = $_SESSION['user_id'];
    $user = User::find($userId);
    if ($user) {
        $user->reset_required = false; // Reset the flag
        $user->save();

        // Prepare JSON response
        $data = ['message' => 'Reset flag cleared'];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    return $response->withStatus(404); // Not Found
});


$app->run();