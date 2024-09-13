<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.png">
  <title>Listening Section</title>
  <?php require 'bootstrap.php'; ?>
</head>
<body>
  <div class="container">
        <div class="row">
            <div class="col-sm"><div class="branding"><img src="/assets/img/logopb.jpeg" alt=""></div></div>            
        </div>        
    </div>
  <div class="container mt-3 main-content mb-5">
    <h1>Listening Section</h1>

    <audio id="listeningAudio" src="/assets/audio/audio-test.mp3"></audio>

    <button id="startButton" class="btn btn-primary">Start Listening</button>

    <form id="listeningForm" action="/listening" method="post">
      <?php include 'questions/listening_questions.php'; ?>

      <button type="submit" class="btn btn-success">Submit Answers</button>
    </form>
    <a href="/logout" class="btn btn-danger d-none">Logout</a>
  </div>

  <video id="webcam" class="cam-exam" autoplay></video>
  <script>
    const audio = document.getElementById('listeningAudio');
    const lastPositionKey = 'audioLastPosition'; // Key for local storage

    // Load the last played position from local storage
    const lastPosition = localStorage.getItem(lastPositionKey);
    if (lastPosition) {
      audio.currentTime = parseFloat(lastPosition);
    }

    // Update local storage with the current position whenever the audio is played
    audio.addEventListener('timeupdate', () => {
      localStorage.setItem(lastPositionKey, audio.currentTime);
    });

    // Save audio playback position before the page unloads
    window.addEventListener('beforeunload', function() {
      if (audio) {
        localStorage.setItem(lastPositionKey, audio.currentTime);
        console.log('Saved playback time:', audio.currentTime);
      }
    });

    // Start audio on button click
    document.getElementById('startButton').addEventListener('click', function() {
      if (audio) {
        console.log('Current Time Before Play:', audio.currentTime);
        audio.play().then(() => {
          // Hide the button once audio starts playing
          this.style.display = 'none';
          console.log('Audio is now playing');
        }).catch(error => {
          console.error('Error playing audio:', error);
        });
      }
    });

    // JavaScript to handle audio ended event
    audio.addEventListener('ended', function() {
      // Clear saved playback time and submit the form when the audio ends
      localStorage.removeItem(lastPositionKey);
      document.getElementById('listeningForm').submit();
    });

    document.getElementById('listeningForm').addEventListener('submit', function(event) {
        // Remove the localStorage item
        localStorage.removeItem('audioLastPosition');
        console.log('Removed localStorage item on form submit: audioLastPosition');    
    });

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
  </script>
</body>
</html>