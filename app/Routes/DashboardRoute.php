<?php
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Models\Exam;
use App\Models\User;


// Dashboard route
$app->get('/dashboard', function ($request, $response, $args) {
    if (!isset($_SESSION['user_id'])) {
        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    // Check if the user has status_id = 2
    $exam = \App\Models\Exam::where('user_id', $_SESSION['user_id'])->where('status_id', 2)->first();
    $isCompleted = $exam ? true : false;

    // If status_id = 2, redirect to complete route
    if ($exam) {
        return $response
            ->withHeader('Location', '/complete')
            ->withStatus(302);
    }
    
    // Fetch distinct exam codes from users table
    $examCodes = User::select('exam_code')->distinct()->whereNotNull('exam_code')->get();

    // Fetch user data for the DataTable     
    $users = \App\Models\User::leftJoin('exams', 'users.id', '=', 'exams.user_id')
    ->select('users.*', 'exams.status_id as exam_status_id')
    ->where('users.role_id', '!=', 1) // Exclude users with role_id = 1
    ->get();

    $message = $_SESSION['message_notification'] ?? null;
    unset($_SESSION['message_notification']);  // Clear the message after displaying

    // Otherwise, render the dashboard view
    ob_start();
    require __DIR__ . '/../../views/dashboard.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});