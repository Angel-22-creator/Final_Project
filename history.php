<?php
// Set the title and include the header
$title = 'Retrofee | Menu';
include './view/header.php';

// Ensure that user_id exists in session
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    die('User not authenticated.');
}

$user_id = $_SESSION['id']; // Get the user ID from session

// Query to get the latest feeling for the specific user
$sql = "SELECT * FROM tblfeeling WHERE user_id = $user_id ORDER BY createdAt DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the row
    $row = $result->fetch_assoc();
    $currentFeeling =  $row['category'];
    $datetime =  $row['createdAt'];
    $formattedDatetime = date("Y-m-d h:i A", strtotime($datetime));

    // Set the message and emoji based on the feeling
    switch ($currentFeeling) {
        case "Happy":
            $messageQuote = '"Happiness depends upon ourselves. Nobody else can create it for us" - Aristotle.';
            $emoji = "&#128516;";
            break;
        case "Sad":
            $messageQuote = '"It is always by way of pain one arrives at pleasure" - Marquis de Sade.';
            $emoji = "&#128546;";
            break;
        case "Angry":
            $messageQuote = '“Whatever begins in anger, ends in shame” - Benjamin Franklin.';
            $emoji = "&#128545;";
            break;
        case "Excited":
            $messageQuote = '"It is the greatest source of so much in life that makes life worth living." - David Attenborough.';
            $emoji = "&#128512;";
            break;
        case "Love":
            $messageQuote = '“If I know what love is, it is because of you” - Hermann Hesse.';
            $emoji = "&#128525;";
            break;
        default:
            $messageQuote = '';
            $emoji = "";
            break;
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
        <div class="column" id="left-column">
            <h1 class="inten-history">History Weekly Emotion</h1>
            <div class="inten-container-history" id="emotionContainer">
                <!-- Boxes will be dynamically created -->
            </div>
            <ul class="bullet-history">
                <li><p class="Happy">Happy</p></li>
                <li><p class="Sad">Sad</p></li>
                <li><p class="Excited">Excited</p></li>
                <li><p class="Angry">Angry</p></li>
                <li><p class="Love">Love</p></li>
            </ul>
        </div>
        
        <div class="column" id="right-column">
            <div class="container">
                <h1 class="high">Highest Emotion</h1>
                <canvas id="emotionBar2Chart"></canvas> <!-- Canvas to render the bar chart -->
            </div>
        </div>
        
        <div class="column" id="right-column">
            <div class="piecontainer">
                <h1>Emotion Opposites</h1>
                <canvas id="emotionDoughnutChart"></canvas>
            </div>
        </div>
        
        <div class="container-act">
            <h1>Active Weekly Emotion</h1>
            <canvas id="categoryChart"></canvas>
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

<script src="js/activie.js"></script>
<script src="js/history-pie.js"></script>
<script src="js/history_dayemotion.js"></script>
<script src="js/history-barchart.js"></script>

</body>
</html>
