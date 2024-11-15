<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'config/database.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use App\Models\User;
use App\Models\ExamResult;

session_start();

$app = AppFactory::create(); // Create Slim app

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Include routes after app is created
require 'app/Routes/DashboardRoute.php';
require 'app/Routes/StartExamRoute.php';
require 'app/Routes/ListeningRoute.php';
require 'app/Routes/WritingRoute.php';
require 'app/Routes/ReadingRoute.php';
require 'app/Routes/CompleteRoute.php';
require 'app/Routes/UploadCsvRoute.php';


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

$app->run();
