<?php $isCompleted = isset($exam) && $exam->status_id == 2; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        video {
            width: 320px;
            height: 240px;
            border: 1px solid #ddd;
        }
        .ready-test {
            display: none; /* Hide ready-test section initially */
        }
    </style>
</head>
<body>
    <div class="container mt-5"> 
        <div class="row">
            <div class="col-sm">
                <h1>Welcome, <em><?= htmlspecialchars($_SESSION['name']) ?></em>!</h1>
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
            </div>
            <div class="col-sm">
                <a href="/logout" class="btn btn-danger float-right">Logout</a>
            </div>    
        </div>

        <?php if ($_SESSION['role_id'] === 1): ?>
            <h2>Admin Dashboard</h2>
            <!-- Admin-specific content -->
            <p>Manage users, view reports, and configure settings here.</p>
        <?php else: ?>
            
            <h2>Student Dashboard</h2>
            <!-- Student-specific content -->

            <div class="pre-test-hardware" <?php echo $isCompleted ? 'style="display:none;"' : ''; ?>>
                <h3 class="mt-5">Exam Preliminaries: Webcam and Speaker Test</h3>
        
                <!-- Webcam Test Section -->
                <div class="mt-3">
                    <h4>Webcam Test</h4>
                    <video id="webcam" autoplay></video>
                    <div>
                        <input type="checkbox" id="webcamCheck" required>
                        <label for="webcamCheck">I can see my webcam feed clearly</label>
                    </div>
                </div>

                <!-- Speaker Test Section -->
                <div class="mt-3">
                    <h4>Speaker Test</h4>
                    <audio id="audioTest" controls>
                        <source src="https://www.soundjay.com/buttons/button-1.wav" type="audio/wav">
                        Your browser does not support the audio element.
                    </audio>
                    <div>
                        <input type="checkbox" id="speakerCheck" required>
                        <label for="speakerCheck">I can hear the audio clearly</label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button id="submitpretest" class="btn btn-success mt-5" disabled>Selanjutnya</button>
            </div>

            <div class="ready-test">
                <p>Ready to start the TOEFL exam? Click the button below to begin.</p>
                <form action="/start-exam" method="post">
                    <button type="submit" class="btn btn-primary">Start TOEFL Exam</button>
                </form>
            </div>
        <?php endif; ?>
       
    </div>

    <script>
        // Webcam access
        async function startWebcam() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                document.getElementById('webcam').srcObject = stream;
            } catch (error) {
                alert('Webcam access is required for this test.');
                console.error('Error accessing webcam:', error);
            }
        }

        // Enable submit button only if both checkboxes are checked
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const webcamChecked = document.getElementById('webcamCheck').checked;
                const speakerChecked = document.getElementById('speakerCheck').checked;
                document.getElementById('submitpretest').disabled = !(webcamChecked && speakerChecked);
            });
        });

        // Show ready-test and hide pre-test-hardware on submit
        document.getElementById('submitpretest').addEventListener('click', function() {
            document.querySelector('.pre-test-hardware').style.display = 'none';
            document.querySelector('.ready-test').style.display = 'block';
        });

        // Initialize the webcam on page load
        window.onload = startWebcam;
    </script>
</body>
</html>
