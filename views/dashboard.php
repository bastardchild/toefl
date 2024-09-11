<?php $isCompleted = isset($exam) && $exam->status_id == 2; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.png">
    <title>Dashboard</title>
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
        <!-- error exam -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?> 

        <?php if ($_SESSION['role_id'] === 1): ?>
            <!-- admin view -->
            <?php require 'admin-area.php'; ?>   

        <?php else: ?>   
            <div class="col-md-8">  
            <h3 class="mb-5">Selamat Datang, <em><?= htmlspecialchars($_SESSION['name']) ?></em>!</h3>

            <div class="pre-test-hardware" <?php echo $isCompleted ? 'style="display:none;"' : ''; ?>>
                <h2 class="mt-5">Persiapan Ujian: Tes Webcam dan Speaker</h2>
                <hr>
                <!-- Webcam Test Section -->
                <div class="mt-3">
                    <h4>Tes Webcam</h4>
                    <video id="webcam" autoplay></video>
                    <div>
                        <input type="checkbox" id="webcamCheck" required>
                        <label for="webcamCheck">Saya dapat melihat tampilan webcam dengan jelas.</label>
                    </div>
                </div>                
                <hr>

                <!-- Speaker Test Section -->
                <div class="mt-3">
                    <h4>Speaker Test</h4>
                    <audio id="audioTest" controls>
                        <source src="/assets/audio/audio-test.mp3" type="audio/mp3">
                        Your browser does not support the audio element.
                    </audio>
                    <div>
                        <input type="checkbox" id="speakerCheck" required>
                        <label for="speakerCheck">Saya dapat mendengar audio dengan jelas.</label>
                    </div>
                </div>
                <hr>
                <button id="submitpretest" class="btn btn-success mt-5" disabled>Next Step</button>
            </div>

            <!-- Webcam Capture Section -->
            <div class="webcam-capture" style="display:none;">
                <h4>Webcam Capture</h4>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Webcam video and capture controls -->
                        <div>
                            <video id="webcamCapture" autoplay></video>
                            <canvas id="canvasCapture" style="display:none;"></canvas>
                        </div>
                        <div>
                            <button id="takeScreenshot" class="btn btn-primary mt-3">Take Screenshot</button>                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Screenshot preview -->
                        <div>
                            <img id="screenshot" alt="Webcam Screenshot" style="max-width:100%;display:none;">
                            <button id="retakeScreenshot" class="btn btn-secondary mt-3" style="display:none;">Retake Screenshot</button>
                        </div>                       
                    </div>
                </div>

                <form id="uploadForm" action="/upload-image" method="post" enctype="multipart/form-data" style="display:none;">
                    <input type="file" id="fileInput" name="image" accept="image/png">
                    <button type="submit" id="uploadButton">Upload Image</button>
                </form>
                <button id="submitWebcamCapture" class="btn btn-success mt-5" disabled>Next Step</button>

            </div>

            <!-- Ready to Start Exam Section -->
            <div class="ready-test" style="display:none;">
                <?php require 'aturan.php'; ?>                                
                <p class="mt-5">Ready to start the TOEFL exam? Click the button below to begin.</p>
                <form action="/start-exam" method="post">
                    <input type="checkbox" id="termCondition" required>
                    <label for="termCondition">Saya setuju dan sudah membaca peraturan pelaksanaan ujian.</label><br>
                    <button type="submit" class="btn btn-primary mt-3" id="startTest" disabled>Start TOEFL Exam</button>
                </form>
            </div>
            <script src="/assets/js/dashboard.js"></script>
        <?php endif; ?>
        </div>       
    </div>
</body>
</html>
