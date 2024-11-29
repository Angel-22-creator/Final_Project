<?php
// fetch_emotions.php
require 'conn.php';

$user_id = $_SESSION['id'];

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

if ($user_id <= 0) {
    echo json_encode(["error" => "Invalid or missing user_id"]);
    exit;
}

// Query to fetch emotions
$sql = "
   SELECT 
       DATE(createdAt) AS date, 
       feeling_id, 
       category, 
       description, 
       intensity
   FROM 
       tblfeeling
   WHERE 
       user_id = $user_id AND 
       DATE(createdAt) IN (
           SELECT DATE(createdAt)
           FROM tblfeeling
           WHERE createdAt >= CURDATE() - INTERVAL 7 DAY
           GROUP BY DATE(createdAt)
       )
       AND intensity = (
           SELECT MAX(intensity)
           FROM tblfeeling AS t2
           WHERE DATE(t2.createdAt) = DATE(tblfeeling.createdAt)
       )
   ORDER BY 
       date DESC, createdAt;";

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $days = [];
    while ($row = $result->fetch_assoc()) {
        $colorMap = [
            "Love" => "rgb(244, 33, 103)",
            "Happy" => "#ffd21f",
            "Sad" => "gray",
            "Angry" => "#c12700",
            "Excited" => "#ff9500",
        ];

        $days[] = [
            "day" => date("D", strtotime($row["date"])), // Corrected from "day"
            "color" => $colorMap[$row["category"]] ?? "gray"
        ];
    }
    echo json_encode($days);
} else {
    // Return an empty array if no results are found
    echo json_encode([]);
}

$conn->close();
?>
