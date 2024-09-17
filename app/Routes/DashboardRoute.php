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

// New route for DataTable server-side processing
$app->get('/api/users', function (Request $request, Response $response) {
    $draw = $request->getQueryParams()['draw'] ?? 1;
    $start = $request->getQueryParams()['start'] ?? 0;
    $length = $request->getQueryParams()['length'] ?? 10;
    $search = $request->getQueryParams()['search']['value'] ?? '';
    $orderColumn = $request->getQueryParams()['order'][0]['column'] ?? 1;
    $orderDir = $request->getQueryParams()['order'][0]['dir'] ?? 'asc';
    $examCode = $request->getQueryParams()['exam_code'] ?? '';

    $columns = [
        1 => 'users.name',
        2 => 'users.username',
        3 => 'exams.status_id',
        4 => 'users.cam_image',
        5 => 'users.exam_code'
    ];

    $query = User::leftJoin('exams', 'users.id', '=', 'exams.user_id')
        ->select(
            'users.name',
            'users.middle_name',
            'users.last_name',
            'users.username',
            'exams.status_id as exam_status_id',
            'users.cam_image',
            'users.exam_code',
            'users.id'
        )
        ->where('users.role_id', '!=', 1);

    // Apply search
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('users.name', 'LIKE', "%{$search}%")
              ->orWhere('users.username', 'LIKE', "%{$search}%")
              ->orWhere('users.exam_code', 'LIKE', "%{$search}%");
        });
    }

    // Apply exam_code filter
    if (!empty($examCode)) {
        $query->where('users.exam_code', $examCode);
    }

    $totalRecords = $query->count();

    // Apply ordering
    if (isset($columns[$orderColumn])) {
        $query->orderBy($columns[$orderColumn], $orderDir);
    }

    // Apply pagination
    $users = $query->skip($start)->take($length)->get();

    $data = [];
    foreach ($users as $index => $user) {
        $data[] = [
            $start + $index + 1, // Numbered rows
            strtoupper($user->name . ' ' . $user->middle_name . ' ' . $user->last_name), // Full name in uppercase
            $user->username,
            $user->exam_status_id,
            $user->cam_image,
            $user->exam_code ?? 'N/A',
            $user->id // For reset link
        ];
    }

    $result = [
        "draw" => intval($draw),
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data" => $data
    ];

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/exam-codes', function (Request $request, Response $response) {
    // Retrieve unique exam codes from the database
    $examCodes = User::select('exam_code')
        ->whereNotNull('exam_code')
        ->distinct()
        ->pluck('exam_code');

    // Convert the collection to an array
    $examCodesArray = $examCodes->toArray();

    // Return the response as JSON
    $response->getBody()->write(json_encode($examCodesArray));
    return $response->withHeader('Content-Type', 'application/json');
});