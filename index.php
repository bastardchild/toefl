<?php
require 'vendor/autoload.php';
require 'config/database.php';

use Slim\Factory\AppFactory;
use App\Models\User;

session_start();

$app = AppFactory::create(); // Create Slim app

// Include exam routes after app is created
require 'exam_routes.php';

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

// Dashboard route
$app->get('/dashboard', function ($request, $response, $args) {
    if (!isset($_SESSION['user_id'])) {
        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/views/dashboard.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});

// Logout route
$app->get('/logout', function ($request, $response, $args) {
    session_destroy();
    return $response
        ->withHeader('Location', '/')
        ->withStatus(302);
});

$app->run();
