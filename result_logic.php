<?php
// Mapping number correct to converted score for each section
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

// Function to calculate total TOEFL score
function calculate_toefl_score($correct_section1, $correct_section2, $correct_section3) {
    global $section1_scores, $section2_scores, $section3_scores;
    
    // Get converted scores based on the number correct
    $converted_score1 = isset($section1_scores[$correct_section1]) ? $section1_scores[$correct_section1] : 0;
    $converted_score2 = isset($section2_scores[$correct_section2]) ? $section2_scores[$correct_section2] : 0;
    $converted_score3 = isset($section3_scores[$correct_section3]) ? $section3_scores[$correct_section3] : 0;

    // Sum the converted scores
    $total_converted_score = $converted_score1 + $converted_score2 + $converted_score3;

    // Calculate the average of the converted scores
    $average_converted_score = $total_converted_score / 3;

    // Final TOEFL score
    $toefl_score = $average_converted_score * 10;

    return $toefl_score;
}

