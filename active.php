<?php
require 'conn.php';

header('Content-Type: application/json');

$user_id = $_SESSION['id'];

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Validate user_id
if (empty($user_id)) {
    die(json_encode(["error" => "Invalid or missing user_id"]));
}

// Fetch data for the specific user, grouped by week
$sql = "
    SELECT 
        WEEK(createdAt) AS week, 
        COUNT(*) AS count 
    FROM tblfeeling 
    WHERE user_id = $user_id  -- Filter by user_id
    GROUP BY WEEK(createdAt)
    ORDER BY WEEK(createdAt)
";

$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'week' => 'Week ' . $row['week'],
            'count' => $row['count']
        ];
    }
}

$conn->close();

// Return the data as JSON
echo json_encode($data);
?>
