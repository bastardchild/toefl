<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>       
        
        <?php if ($_SESSION['role_id'] === 1): ?>
            <h2>Admin Dashboard</h2>
            <!-- Admin-specific content -->
            <p>Manage users, view reports, and configure settings here.</p>
        <?php else: ?>
            <h2>Student Dashboard</h2>
            <!-- Student-specific content -->
            <p>Ready to start the TOEFL exam? Click the button below to begin.</p>
            <form action="/start-exam" method="post">
                <button type="submit" class="btn btn-primary">Start TOEFL Exam</button>
            </form>
        <?php endif; ?>

        <a href="/logout" class="btn btn-danger mt-3">Logout</a>
    </div>
</body>
</html>