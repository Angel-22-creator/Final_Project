<?php
require 'conn.php';

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];

    // Check if username or email already exists
    $duplicate = $conn->prepare("SELECT * FROM tbluser WHERE username = ? OR email = ?");
    $duplicate->bind_param("ss", $username, $email);
    $duplicate->execute();
    $result = $duplicate->get_result();

    if ($result->num_rows > 0) {
        echo "<script> alert('Username or Email Has Already Taken'); </script>";
    } else {
        if ($password === $confirmpassword) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user into the database
            $query = $conn->prepare("INSERT INTO tbluser (username, email, password) VALUES (?, ?, ?)");
            $query->bind_param("sss", $username, $email, $hashedPassword);
            $query->execute();

            echo "<script> alert('Registration Successful'); </script>";
            header("Location: login.php");
            exit;
        } else {
            echo "<script> alert('Password Does Not Match'); </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Signup</title>
    <link rel="stylesheet" href="css/loginSignup.css">
</head>
<body>
<img src="img/rb_assis.svg" alt="" class="rb-sign">
    <div class="sign-up-container">
    <img src="img/logo_a3.png" class="logo">
    <div class="form-container">
        <h2>Signup</h2>
        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirmpassword" placeholder="Confirm Password" required>
            <button type="submit" name="submit" class="submitbtn">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
    </div>
</body>
</html>