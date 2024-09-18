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

    <div class="container mt-3 main-content mb-3">
        <h3>Selamat, <em><?= htmlspecialchars($_SESSION['name']) ?></em>!</h3>
        <p>Anda Telah menyelesaikan tes, Nilai TOEFL Anda adalah:</p>

        <div class="row mb-5">
            <div class="col-sm">            
                <div class="ttl-score">
                    <i class="bi bi-trophy" style="font-size: 35px;"></i><br>
                    Total Score<br>
                    <span><?php echo htmlspecialchars($toefl_score); ?></span>
                </div>  
            </div>

            <div class="col-sm" style="font-size: 20px;">
                Nama: <strong><?= $first_name ?> <?= $middle_name ?> <?= $last_name ?></strong><br>
                Tanggal Ujian: <strong><?= $createdAtExam ?></strong>
            </div>

            <div class="col-sm">            
                
            </div>
            
        </div>

        <div class="row">
            <div class="col-sm">                
                <?php echo htmlspecialchars($mapped_listening_score); ?>
                <?php echo htmlspecialchars($mapped_writing_score); ?>
                <?php echo htmlspecialchars($mapped_reading_score); ?>
            </div>
        </div>
    </div>
    <footer>
        <div class="container copyr mb-3">Copyright 2024 © Universitas Merdeka Malang</div>
    </footer>
</body>
</html>
