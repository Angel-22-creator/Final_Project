<?php
// Set the title and include the header
$title = 'Retrofee | Menu';
include './view/header.php';

if ($userId <= 0) {
    echo '<div class="center-no">';
    echo '<h2 class="no-data-h2">Invalid User ID</h2>';
    echo '</div>';
    exit; // Exit to prevent further execution
}

// Query to get the latest mood for the specific user
$sql = "SELECT * FROM tblfeeling WHERE user_id = $userId ORDER BY createdAt DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the row
    $row = $result->fetch_assoc();
    $currentFelling =  $row['category'];
    $datetime =  $row['createdAt'];
    $formattedDatetime = date("Y-m-d h:i A", strtotime($datetime));
    if ($currentFelling === "Happy") {
        $messageQuote = '"Happiness depends upon ourselves. Nobody else can create it for us" - Aristotle.';
        $emoji = "&#128516;";
    } elseif ($currentFelling === "Sad") {
        $messageQuote = '"It is always by way of pain one arrives at pleasure" - Marquis de Sade.';
        $emoji = "&#128546;";
    } elseif ($currentFelling === "Angry") {
        $messageQuote = '“Whatever begins in anger, ends in shame” - Benjamin Franklin.';
        $emoji = "&#128545;";
    } elseif ($currentFelling === "Excited") {
        $messageQuote = '"It is the greatest source of so much in life that makes life worth living." - David Attenborough.';
        $emoji = "&#128512;";
    } elseif ($currentFelling === "Love") {
        $messageQuote = '“If I know what love is, it is because of you” - Hermann Hesse.';
        $emoji = "&#128525;";
    }
} else {
    // No data found, display an image
    $noDataImage = 'img/rb_assis.svg'; // Path to your fallback image
    echo '<div class="center-no">';
    echo '<h2 class="no-data-h2">No Mood Data Found</h2>';
    echo '<img src="' . $noDataImage . '" alt="No Data Found" class="no-data-image">';
    echo '</div>';
    exit; // Exit to prevent further execution
}
?>
<div class="center">
<div class="grid-container">
    <div class="column moodtoday" id="left-column">
      <h2>Your Mood for Today!</h2>
      <p class="<?php echo $currentFelling?> em"><?php echo $currentFelling, $emoji?></p>
      <p class="dt"><?php echo $formattedDatetime ?></p>
      <i class="mq"><?php echo $messageQuote ?></i>
    </div>
    <div class="column" id="right-column">
    <div class="container">
        <h1 class="high">Highest Emotion</h1>
        <canvas id="emotionBarChart"></canvas> <!-- Canvas to render the bar chart -->
    </div>
    </div>
    <div class="column" id="left-column">
    <h1 class="inten">Weekly Emotion Tracker</h1>
    <div class="inten-container" id="emotionContainer">
        <!-- Boxes will be dynamically created -->
    </div>
    <ul class="bullet">
        <li><p class="Happy-p">Happy</p></li>
        <li><p class="Sad-p">Sad</p></li>
        <li><p class="Excited-p">Excited</p></li>
        <li><p class="Angry-p">Angry</p></li>
        <li><p class="Love-p">Love</p></li>
    </ul>
    </div>
    <div class="column" id="right-column">
    <div class="piecontainer">
        <h1>Emotion Opposites</h1>
        <canvas id="emotionPieChart"></canvas>
    </div>
  </div>
</div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
  const leftColumn = document.getElementById("left-column");
  const rightColumn = document.getElementById("right-column");

  console.log("Left Column:", leftColumn.innerHTML);
  console.log("Right Column:", rightColumn.innerHTML);
});
    </script>

    <script src="js/piechart.js"></script>
    <script src="js/dayemotion.js"></script>
    <script src="js/barchart.js"></script>
</body>
</html>
