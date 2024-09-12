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

    $correctAnswers = ['question1' => 'A', 'question2' => 'D', 'question3' => 'B', 'question4' => 'B', 'question5' => 'B', 'question6' => 'C', 'question7' => 'A', 'question8' => 'A', 'question9' => 'B', 'question10' => 'D', 'question11' => 'C', 'question12' => 'D', 'question13' => 'C', 'question14' => 'B', 'question15' => 'A', 'question16' => 'B', 'question17' => 'C', 'question18' => 'C', 'question19' => 'B', 'question20' => 'A', 'question21' => 'D', 'question22' => 'C', 'question23' => 'D', 'question24' => 'D', 'question25' => 'B', 'question26' => 'A', 'question27' => 'A', 'question28' => 'A', 'question29' => 'A', 'question30' => 'D', 'question31' => 'C', 'question32' => 'B', 'question33' => 'C', 'question34' => 'B', 'question35' => 'A', 'question36' => 'D', 'question37' => 'D', 'question38' => 'C', 'question39' => 'B', 'question40' => 'C', 'question41' => 'A', 'question42' => 'C', 'question43' => 'C', 'question44' => 'D', 'question45' => 'B', 'question46' => 'B', 'question47' => 'C', 'question48' => 'A', 'question49' => 'D', 'question50' => 'C'];

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
