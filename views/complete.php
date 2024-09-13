<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.png">
  <title>Complete</title>
  <?php require 'bootstrap.php'; ?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm"><div class="branding"><img src="/assets/img/logopb.jpeg" alt=""></div></div>
            <div class="col-sm"><a href="/logout" class="btn btn-danger float-end mt-25px">Logout</a></div>
        </div>        
    </div>

    <div class="container mt-3 main-content">
        <h1>Selamat, <em><?= htmlspecialchars($_SESSION['name']) ?></em>!</h1>
        <p>Anda Telah menyelesaikan tes, Nilai TOEFL Anda adalah:</p>

        <div class="row mb-5">
            <div class="col-sm">            
                <div class="ttl-score">
                    <i class="bi bi-trophy" style="font-size: 35px;"></i><br>
                    Total Score<br>
                    <span><?php echo htmlspecialchars($toefl_score); ?></span>
                </div>  
            </div>

            <div class="col-sm">            
                 
            </div>

            <div class="col-sm">            
                
            </div>

            <div class="col-sm">            
                  
            </div>
        </div>
    </div>
</body>
</html>
