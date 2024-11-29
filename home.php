<?php
// Set the title and include the header
$title = 'Retrofee | Menu';
include './view/header.php';
?>
<div class="intro">
    <div class="intro-box">
        <img src="img/rb_assis.svg" alt="Assistant">
        <h2 class="intro-question">Checking up on you today!</h2>
        <p class="intro-p">Click Go to Start.</p>
        <button id="start">Go</button>
    </div>
</div>

<div id="emotion-popup" class="popup hidden">
    <button id="exit"><i class="fa fa-times" aria-hidden="true"></i></button>
    <div class="popup-content">
        <div class="emotion-buttons">
        <h2>What is your feeling today?</h2>
            <button class="emotion-btn happy" onclick="setEmotion('Happy')">Happy</button>
            <button class="emotion-btn sad" onclick="setEmotion('Sad')">Sad</button>
            <button class="emotion-btn angry" onclick="setEmotion('Angry')">Angry</button>
            <button class="emotion-btn excited" onclick="setEmotion('Excited')">Excited</button>
            <button class="emotion-btn love" onclick="setEmotion('Love')">Love</button>
        </div>

        <div id="intensity-rating" class="hidden">
            <h3>How strong is your emotion today? (Rate 1-10)</h3>
            <input type="range" id="intensity" name="intensity" min="1" max="10" value="5" oninput="updateIntensity()">
            <span id="intensity-value">5</span>
            <button class="btn-rate" onclick="nextStep()">Next</button>
        </div>

        <div id="optional-text" class="hidden">
            <h3>Optional: Add any additional comments</h3>
            <textarea id="notes" placeholder="Your thoughts..."></textarea>
            <button class="btn-text" onclick="submitEmotion()">Submit</button>
        </div>
    </div>
</div>

<script>
    const userId = <?php echo json_encode($userId ?? null); ?>;
</script>
<script src="js/popup.js"></script>
</body>
</html>