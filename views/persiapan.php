<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webcam and Speaker Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .test-section {
            margin-bottom: 20px;
        }
        video {
            width: 320px;
            height: 240px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Exam Preliminaries: Webcam and Speaker Test</h1>
    
    <!-- Webcam Test Section -->
    <div class="test-section">
        <h2>Webcam Test</h2>
        <video id="webcam" autoplay></video>
        <div>
            <input type="checkbox" id="webcamCheck" required>
            <label for="webcamCheck">I can see my webcam feed clearly</label>
        </div>
    </div>

    <!-- Speaker Test Section -->
    <div class="test-section">
        <h2>Speaker Test</h2>
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
    <button id="submitBtn" disabled>Submit</button>

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
                document.getElementById('submitBtn').disabled = !(webcamChecked && speakerChecked);
            });
        });

        // Initialize the webcam on page load
        window.onload = startWebcam;
    </script>
</body>
</html>