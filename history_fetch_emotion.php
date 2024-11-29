<?php
// fetch_emotions.php
require 'conn.php';

$user_id = $_SESSION['id'];

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Get the current month and year
$currentDate = new DateTime();
$currentMonth = $currentDate->format('m');
$currentYear = $currentDate->format('Y');

// Fetch emotion data for the current month
$sql = "
   SELECT 
       DATE(createdAt) AS day,  -- Format as YYYY-MM-DD for daily mapping
       feeling_id, 
       category, 
       description, 
       intensity
   FROM 
       tblfeeling
   WHERE 
       user_id = $user_id AND 
       DATE_FORMAT(createdAt, '%Y-%m') = '$currentYear-$currentMonth'
       AND intensity = (
           SELECT MAX(intensity)
           FROM tblfeeling AS t2
           WHERE DATE(t2.createdAt) = DATE(tblfeeling.createdAt)
       )
   ORDER BY 
       day ASC, createdAt;
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Initialize an empty array to store results
    $days = [];
    
    // Create a color map for the emotions
    $colorMap = [
        "Love" => "rgb(244, 33, 103)",
        "Happy" => "#ffd21f",
        "Sad" => "#4d50ff",
        "Angry" => "#ff3f0f",
        "Excited" => "rgb(255, 149, 0)",
    ];

    // Fetch each row and map it to the corresponding day
    while ($row = $result->fetch_assoc()) {
        $dayOfWeek = date("D", strtotime($row["day"]));  // Day of the week (Sun, Mon, etc.)
        $color = $colorMap[$row["category"]] ?? "gray"; // Default to gray if no match

        $days[$row["day"]] = [
            "day" => $dayOfWeek,
            "color" => $color,
            "date" => $row["day"]
        ];
    }
    
    // Add any missing days in the month with a default gray color
    $numDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
    for ($i = 1; $i <= $numDaysInMonth; $i++) {
        $date = "$currentYear-$currentMonth-" . str_pad($i, 2, "0", STR_PAD_LEFT); // Format as 'YYYY-MM-DD'
        
        if (!array_key_exists($date, $days)) {
            $days[$date] = [
                "day" => date("D", strtotime($date)),
                "color" => "gray", // Default to gray for days with no data
                "date" => $date
            ];
        }
    }

    // Sort days by date
    ksort($days);

    // Return the resulting days array as JSON
    echo json_encode(array_values($days));
} else {
    // Return an empty array if no results are found
    echo json_encode([]);
}

$conn->close();
?>
