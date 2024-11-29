<?php
// fetch_feelings.php
require 'conn.php';

$user_id = $_SESSION['id'];

header('Content-Type: application/json');

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Validate user_id
if (empty($user_id)) {
    die(json_encode(["error" => "Invalid or missing user_id"]));
}

// Query to fetch the emotion data for the specific user
$sql = "
    SELECT category, COUNT(*) AS count
    FROM tblfeeling
    WHERE user_id = $user_id  -- Filter by user_id
    GROUP BY category";

$result = $conn->query($sql);

// Arrays for categorizing emotions
$positiveCategories = ["Happy", "Love", "Excited"];
$negativeCategories = ["Sad", "Angry"];

$positiveCount = 0;
$negativeCount = 0;

while ($row = $result->fetch_assoc()) {
    if (in_array($row['category'], $positiveCategories)) {
        $positiveCount += $row['count'];
    } elseif (in_array($row['category'], $negativeCategories)) {
        $negativeCount += $row['count'];
    }
}

// Prepare the data response
$data = [
    "positive" => $positiveCount,
    "negative" => $negativeCount
];

echo json_encode($data);

$conn->close();
?>
