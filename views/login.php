<!-- views/login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.png">
    <title>Login</title>    
    <?php require 'bootstrap.php'; ?>
    
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <img src="/assets/img/logopb.jpeg">               
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mt-3">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <div class="box-login">
                    <h5 class="text-center mb-4">Silahkan Login Dengan Akun Anda</h5>
                    <form action="/" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
