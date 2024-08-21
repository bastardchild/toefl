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
        <?php if (isset($_SESSION['user_id'])): ?>
            <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>

            <?php if ($_SESSION['role_id'] == 1): // Admin ?>
                <div class="admin-content">
                    <h2>Admin Dashboard</h2>
                    <p>Here is some admin content.</p>
                    <!-- Add more admin-specific content here -->
                </div>
            <?php elseif ($_SESSION['role_id'] == 2): // User ?>
                <div class="user-content">
                    <h2>User Dashboard</h2>
                    <p>Here is some user content.</p>
                    <!-- Add more user-specific content here -->
                </div>
            <?php endif; ?>

            <a href="/logout" class="btn btn-danger">Logout</a>
        <?php else: ?>
            <h1>Please log in to access the dashboard.</h1>
            <a href="/" class="btn btn-primary">Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
