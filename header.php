<?php
require 'conn.php';

if (!isset($_SESSION['id'])) {
    // Redirect to login page
    header("Location: login.php");
    exit(); // Ensure no further code is executed
}

$userId = $_SESSION['id'];
$query = "SELECT username, email FROM tbluser WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <ul class="top-nav">
        <li class="user-nav dropdown">
                <a href="#user" class="mb-hide"><i class="fa fa-user"></i></a>
                <div class="dropdown-content">
                    <a href="#"><?php echo htmlspecialchars($username); ?></a>
                    <a href="#"><?php echo htmlspecialchars($email); ?></a>
                    
                    <a href="history.php">History</a>
                    <a href="logout.php">Logout</a>
                </div>
            </li>
            <li><a href="./analytic.php">Analytics</a></li>
            <li><a href="./home.php">Log Emotion</a></li>

        <li><img src="img/logo_a3.png" class="logo"></li>
    </ul>