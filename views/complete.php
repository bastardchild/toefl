<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completion</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm">
                <h1>Selamat, <em><?= htmlspecialchars($_SESSION['name']) ?></em>!</h1>
                Anda Telah menyelesaikan tes, Nilai TOEFL Anda adalah: <br>
                <h1><?php echo htmlspecialchars($toefl_score); ?></h1>
            </div>
            <div class="col-sm">
                <a href="/logout" class="btn btn-danger float-right">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
