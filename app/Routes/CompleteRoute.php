<?php
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Models\ExamResult;

// complete route
$app->get('/complete', function ($request, $response, $args) {
    if (!isset($_SESSION['user_id'])) {
        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }

    // Retrieve the user's exam results
    $examResult = \App\Models\ExamResult::where('user_id', $_SESSION['user_id'])->first();

    if ($examResult) {
        $listening_score = $examResult->listening_score;
        $writing_score = $examResult->writing_score;
        $reading_score = $examResult->reading_score;
    } else {
        $listening_score = 0;
        $writing_score = 0;
        $reading_score = 0;
    }

    // Score mappings for each section
    $section1_scores = [
        50 => 68, 49 => 67, 48 => 66, 47 => 65, 46 => 63, 45 => 62, 44 => 61, 43 => 60, 42 => 59,
        41 => 58, 40 => 57, 39 => 56, 38 => 55, 37 => 54, 36 => 54, 35 => 53, 34 => 52, 33 => 52,
        32 => 51, 31 => 51, 30 => 50, 29 => 50, 28 => 49, 27 => 49, 26 => 48, 25 => 48, 24 => 47,
        23 => 47, 22 => 46, 21 => 45, 20 => 45, 19 => 44, 18 => 43, 17 => 43, 16 => 42, 15 => 41,
        14 => 41, 13 => 40, 12 => 39, 11 => 39, 10 => 38, 9  => 37, 8  => 36, 7  => 35, 6  => 34,
        5  => 33, 4  => 32, 3  => 31, 2  => 29, 1  => 26, 0  => 25
    ];

    $section2_scores = [
        40 => 68, 39 => 66, 38 => 65, 37 => 63, 36 => 62, 35 => 61, 34 => 60, 33 => 58, 32 => 57,
        31 => 56, 30 => 55, 29 => 54, 28 => 54, 27 => 53, 26 => 51, 25 => 50, 24 => 50, 23 => 49,
        22 => 48, 21 => 48, 20 => 47, 19 => 47, 18 => 46, 17 => 46, 16 => 45, 15 => 45, 14 => 44,
        13 => 44, 12 => 43, 11 => 43, 10 => 42, 9  => 41, 8  => 41, 7  => 40, 6  => 39, 5  => 39,
        4  => 38, 3  => 37, 2  => 36, 1  => 34, 0  => 20
    ];

    $section3_scores = [
        50 => 67, 49 => 66, 48 => 65, 47 => 63, 46 => 61, 45 => 60, 44 => 59, 43 => 58, 42 => 57,
        41 => 55, 40 => 54, 39 => 54, 38 => 53, 37 => 52, 36 => 51, 35 => 51, 34 => 50, 33 => 50,
        32 => 49, 31 => 48, 30 => 48, 29 => 47, 28 => 46, 27 => 46, 26 => 45, 25 => 44, 24 => 44,
        23 => 43, 22 => 43, 21 => 42, 20 => 41, 19 => 40, 18 => 39, 17 => 38, 16 => 37, 15 => 36,
        14 => 35, 13 => 34, 12 => 33, 11 => 32, 10 => 31, 9  => 30, 8  => 28, 7  => 27, 6  => 26,
        5  => 25, 4  => 24, 3  => 23, 2  => 22, 1  => 21, 0  => 21
    ];

    // Convert raw scores to mapped scores
    $mapped_listening_score = isset($section1_scores[$listening_score]) ? $section1_scores[$listening_score] : 0;
    $mapped_writing_score = isset($section2_scores[$writing_score]) ? $section2_scores[$writing_score] : 0;
    $mapped_reading_score = isset($section3_scores[$reading_score]) ? $section3_scores[$reading_score] : 0;

    // Sum the converted scores
    $total_converted_score = $mapped_listening_score + $mapped_writing_score + $mapped_reading_score;

    // Calculate the average of the converted scores
    $average_converted_score = $total_converted_score / 3;

    // Final TOEFL score (rounded to the nearest integer)
    $toefl_score = round($average_converted_score * 10);

    // Pass the mapped scores and final TOEFL score to the complete.php view
    ob_start();
    require __DIR__ . '/../../views/complete.php';
    $output = ob_get_clean();
    
    $response->getBody()->write($output);
    return $response;
});