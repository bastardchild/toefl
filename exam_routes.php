<?php
// exam_routes.php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

// Start Exam route
$app->post('/start-exam', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id'])) {
        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    $_SESSION['current_section'] = 'listening'; // Set the section to Listening

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

    // Process Reading section data
    return $response
        ->withHeader('Location', '/dashboard')
        ->withStatus(302);
});
