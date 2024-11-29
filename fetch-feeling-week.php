<?php
// fetch_weekly_feelings.php
require 'conn.php';
$user_id = $_SESSION['id'];

header('Content-Type: application/json');

// Check database connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Validate user_id
if (empty($user_id)) {
    die(json_encode(["error" => "Invalid or missing user_id"]));
}

// Get the current date and calculate the start and end of the week
$currentDate = date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

// Prepare the SQL query with placeholders to prevent SQL injection
$sql = "
    SELECT category, COUNT(*) AS count
    FROM tblfeeling
    WHERE user_id = ? 
    AND createdAt BETWEEN ? AND ? 
    GROUP BY category
";

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode(["error" => "SQL query preparation failed"]));
}

$stmt->bind_param('iss', $user_id, $startOfWeek, $endOfWeek); // 'i' for integer, 's' for string (dates)

$stmt->execute();
$result = $stmt->get_result();

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
    "negative" => $negativeCount,
    "week_start" => $startOfWeek,
    "week_end" => $endOfWeek
];

// Output the response as JSON
echo json_encode($data);

// Close the prepared statement and connection
$stmt->close();
$conn->close();
?>
