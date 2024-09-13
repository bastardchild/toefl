<?php
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Models\ExamResult;

$app->get('/listening', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'listening') {
        return $response
            ->withHeader('Location', '/dashboard')
            ->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/../../views/listening.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});

$app->post('/listening', function (Request $request, Response $response, array $args) {
    if (!isset($_SESSION['user_id']) || $_SESSION['current_section'] !== 'listening') {
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

    $correctAnswers = ['question1' => 'D', 'question2' => 'D', 'question3' => 'A', 'question4' => 'D', 'question5' => 'C', 'question6' => 'D', 'question7' => 'A', 'question8' => 'C', 'question9' => 'B', 'question10' => 'A', 'question11' => 'A', 'question12' => 'A', 'question13' => 'C', 'question14' => 'D', 'question15' => 'C', 'question16' => 'B', 'question17' => 'A', 'question18' => 'C', 'question19' => 'B', 'question20' => 'B', 'question21' => 'D', 'question22' => 'D', 'question23' => 'A', 'question24' => 'D', 'question25' => 'A', 'question26' => 'C', 'question27' => 'C', 'question28' => 'D', 'question29' => 'D', 'question30' => 'B', 'question31' => 'B', 'question32' => 'C', 'question33' => 'A', 'question34' => 'D', 'question35' => 'A', 'question36' => 'D', 'question37' => 'B', 'question38' => 'C', 'question39' => 'B', 'question40' => 'C', 'question41' => 'D', 'question42' => 'D', 'question43' => 'B', 'question44' => 'B', 'question45' => 'B', 'question46' => 'A', 'question47' => 'D', 'question48' => 'B', 'question49' => 'D', 'question50' => 'B'];
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
        $examResult->listening_score = $totalCorrect;
        $examResult->save();
    } else {
        $db->create([
            'user_id' => $userId,
            'exam_id' => $examId,
            'listening_score' => $totalCorrect,
        ]);
    }

    $_SESSION['current_section'] = 'writing';

    return $response
        ->withHeader('Location', '/writing')
        ->withStatus(302);
});
