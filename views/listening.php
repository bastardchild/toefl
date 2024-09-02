<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listening Section</title>
    <?php require 'bootstrap.php'; ?>
</head>
<body>
    <div class="container mt-5">
        <h1>Listening Section</h1>

        <!-- Audio element that plays automatically -->
        <audio id="listeningAudio" src="https://www.soundjay.com/transportation/airplane-fly-over-02a.mp3" autoplay></audio>

        <form id="listeningForm" action="/listening" method="post">
            <!-- Example Question -->
            
            <?php include 'questions/listening_questions.php'; ?>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <a href="/logout" class="btn btn-danger d-none">Logout</a>
    </div>

    <script>
        // JavaScript to handle audio ended event
        document.getElementById('listeningAudio').addEventListener('ended', function() {
            // Automatically submit the form when the audio ends
            document.getElementById('listeningForm').submit();
        });
    </script>
</body>
</html>
