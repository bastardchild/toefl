<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.png">
    <title>Reading Comprehension</title> 
    <?php require 'bootstrap.php'; ?>    
</head>
<body class="exam-area">
    <div class="container">
        <div class="row">
            <div class="col-sm"><div class="branding"><img src="/assets/img/logopb.jpeg" alt=""></div></div>            
        </div>        
    </div>
    <div class="container mt-3 main-content mb-5">      
        <form id="exam-form" action="/reading" method="post">
            <!-- Example Question -->
            <?php include 'questions/reading_questions.php'; ?>

            <button type="submit" class="btn btn-success">Submit Answers <i class="bi bi-arrow-up-right-square"></i></button>
        </form>
        <a href="/logout" class="btn btn-danger d-none">Logout</a>
    </div>

    <div class="exam-timer">Time left: <span id="timer">55:00</span></div>
    <video id="webcam" class="cam-exam" autoplay></video>
    <script>        
        // Webcam access
        async function startWebcam() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                document.getElementById('webcam').srcObject = stream;
            } catch (error) {
                alert('Webcam access is required for this test.');
                console.error('Error accessing webcam:', error);
            }
        }
        // Call the function when the page loads
        window.onload = startWebcam;
    </script>
    <script src="/assets/js/custom-reading.js"></script>
</body>
</html>
