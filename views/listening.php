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
        <audio id="listeningAudio" src="https://www.soundjay.com/buttons/button-1.wav" autoplay></audio>

        <form action="/listening" method="post">
            <!-- Example Question -->
            <div class="form-group">
                <label for="question1">1. What is the answer?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="question1" value="A" id="question1A">
                    <label class="form-check-label" for="question1A">A. Option A</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="question1" value="B" id="question1B">
                    <label class="form-check-label" for="question1B">B. Option B</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="question1" value="C" id="question1C">
                    <label class="form-check-label" for="question1C">C. Option C</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="question1" value="D" id="question1D">
                    <label class="form-check-label" for="question1D">D. Option D</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <a href="/logout" class="btn btn-danger">Logout</a>
    </div>

    <script>
        // Redirect to the next section after the audio ends
        document.getElementById('listeningAudio').addEventListener('ended', function() {
            window.location.href = '/writing';
        });
    </script>
</body>
</html>
