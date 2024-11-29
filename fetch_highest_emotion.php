<?php
require 'conn.php';

$user_id = $_SESSION['id'];

header('Content-Type: application/json');

// Create an empty response array
$response = ['success' => false, 'message' => '', 'data' => []];

try {
    // Check the database connection
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    // Validate `user_id`
    if (empty($user_id)) {
        throw new Exception('Invalid user ID.');
    }

    // Query to fetch the emotion data and calculate frequency for each category
    $sql = "
        SELECT 
            category, 
            COUNT(*) AS frequency 
        FROM 
            tblfeeling 
        WHERE 
            user_id = $user_id AND  -- Filter by user ID
            YEARWEEK(createdAt, 1) = YEARWEEK(CURDATE(), 1)  -- Filter for the current week
        GROUP BY 
            category
        ORDER BY 
            frequency DESC
        LIMIT 5"; // Limit to top 5 most frequent emotions

    $result = $conn->query($sql);

    // Check if data exists
    if ($result->num_rows > 0) {
        $data = [];

        // Fetch all data from the result set
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'user_id' => $user_id,         // Include user_id in the response
                'emotion' => $row['category'],
                'frequency' => $row['frequency']
            ];
        }

        $response['success'] = true;
        $response['data'] = $data;
    } else {
        $response['message'] = 'No data found.';
    }

    $conn->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Output the response as JSON
echo json_encode($response);
?>
