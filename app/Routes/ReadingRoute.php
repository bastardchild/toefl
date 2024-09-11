<?php
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Models\ExamResult;
use App\Models\Exam;

$app->get('/reading', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'reading') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/../../views/reading.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});

$app->post('/reading', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'reading') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    $examId = $_SESSION['exam_id'];

    if (!$examId) {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    $correctAnswers = ['question1' => 'C'];
    $userAnswers = $request->getParsedBody();
    $totalCorrect = 0;
    foreach ($correctAnswers as $question => $correctAnswer) {
        if (isset($userAnswers[$question]) && $userAnswers[$question] === $correctAnswer) {
            $totalCorrect++;
        }
    }

    $userId = $_SESSION['user_id'];
    $db = new ExamResult();
    $examResult = $db->where('user_id', $userId)->where('exam_id', $examId)->first();

    if ($examResult) {
        $examResult->reading_score = $totalCorrect;
        $examResult->save();
    } else {
        $db->create([
            'user_id' => $userId,
            'exam_id' => $examId,
            'reading_score' => $totalCorrect,
        ]);
    }

    $exam = Exam::where('user_id', $userId)
                ->where('status_id', 1)
                ->first();

    if ($exam) {
        $exam->status_id = 2; // Completed
        $exam->save();
    }

    unset($_SESSION['current_section']);

    return $response
        ->withHeader('Location', '/complete')
        ->withStatus(302);
});
