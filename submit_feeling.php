<?php
require 'conn.php';
header('Content-Type: application/json'); // Ensure JSON response
$response = ['success' => false, 'message' => ''];

try {
    // Check connection
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    // Retrieve POST data
    $emotion = $_POST['emotion'] ?? null;
    $intensity = $_POST['intensity'] ?? null;
    $notes = $_POST['notes'] ?? null;
    $userId = $_POST['user_id'] ?? null; // Retrieve user_id

    if (!$emotion || !$intensity || !$userId) {
        throw new Exception('Required data missing.');
    }

    // Set timestamps
    $createdAt = date('Y-m-d H:i:s');

    // Insert data into `tblfeeling`
    $stmt = $conn->prepare(
        "INSERT INTO tblfeeling (category, intensity, description, createdAt, user_id) 
        VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sissi", $emotion, $intensity, $notes, $createdAt, $userId);

    if ($stmt->execute()) {
        
        $response['success'] = true;
        $response['message'] = 'Emotion logged successfully.';
        
    } else {
        throw new Exception('Failed to insert data: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Return JSON response
echo json_encode($response);
?>