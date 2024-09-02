<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Writing Section</title> 

    <?php require 'bootstrap.php'; ?>

    <script src="/assets/js/custom.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Writing Section</h1>
        <p>Time left: <span id="timer">10:00</span></p>
        <form id="exam-form" action="/writing" method="post">
            <!-- Example Question -->
            <?php include 'questions/writing_questions.php'; ?>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <a href="/logout" class="btn btn-danger d-none">Logout</a>
    </div>
</body>
</html>
