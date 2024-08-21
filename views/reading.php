<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Section</title> 

    <?php require 'bootstrap.php'; ?>

    <script src="/assets/js/custom.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Reading Section</h1>
        <p>Time left: <span id="timer">10:00</span></p>
        <form id="exam-form" action="/reading" method="post">
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
</body>
</html>
