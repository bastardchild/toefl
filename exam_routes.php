<?php
// exam_routes.php

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Models\Exam; // Adjust the namespace according to your folder structure

// Start Exam route
$app->post('/start-exam', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id'])) {
        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    $userId = $_SESSION['user_id'];

    // Check if an exam record already exists for the user
    $existingExam = Exam::where('user_id', $userId)->first();

    if ($existingExam) {
        // If an existing exam is found, redirect to a page showing an error message
        $_SESSION['error_message'] = 'You have already started an exam.';
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    // Create a new exam record with status 'In Progress'
    $exam = new Exam();
    $exam->user_id = $userId;
    $exam->status_id = 1; // In Progress
    $exam->save();

    $_SESSION['current_section'] = 'listening';

    return $response
        ->withHeader('Location', '/listening')
        ->withStatus(302);
});

// Listening section route (GET)
$app->get('/listening', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'listening') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/views/listening.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});

// Handle Listening section submission (POST)
$app->post('/listening', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'listening') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    // Process Listening section data
    $_SESSION['current_section'] = 'writing'; // Move to Writing section

    return $response
        ->withHeader('Location', '/writing')
        ->withStatus(302);
});

// Writing section route (GET)
$app->get('/writing', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'writing') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/views/writing.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});

// Handle Writing section submission (POST)
$app->post('/writing', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'writing') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    // Process Writing section data
    $_SESSION['current_section'] = 'reading'; // Move to Reading section

    return $response
        ->withHeader('Location', '/reading')
        ->withStatus(302);
});

// Reading section route (GET)
$app->get('/reading', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'reading') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/views/reading.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});

// Handle Reading section submission (POST)
$app->post('/reading', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'reading') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Find the exam record for the current user
    $exam = Exam::where('user_id', $userId)
                ->where('status_id', 1) // Ensure the exam is in Progress
                ->first();

    if ($exam) {
        // Update the exam status to Completed
        $exam->status_id = 2; // Completed
        $exam->save();
    }

    // Clear the current_section from the session
    unset($_SESSION['current_section']);

    // Redirect to a completion page
    return $response
        ->withHeader('Location', '/complete')
        ->withStatus(302);
});
