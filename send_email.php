<?php
require 'vendor/autoload.php';  // Path to the PHPMailer autoload.php file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch email data from the database
require 'conn.php';
$sql = "SELECT username, email FROM tbluser";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Get current time
    $currentHour = date('H');  // Hour in 24-hour format
    
    // Check if it's one of the required times (8 AM, 12 PM, 3 PM, or 9 PM)
    if (in_array($currentHour, [8, 12, 15, 21])) {
        while ($row = $result->fetch_assoc()) {
            $username = $row['username'];
            $email = $row['email'];

            // Create an instance of PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Use your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = '';  // Your Gmail email
                $mail->Password = '';  // Your Gmail app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;  // Gmail SMTP port

                // Recipients
                $mail->setFrom('no-reply@yourdomain.com', 'Journal');
                $mail->addAddress($email, $username);  // Add recipient email

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Checking up on you today!';
                $mail->Body    = "
                    <html>
                    <head>
                        <title>Checking up on you today!</title>
                    </head>
                    <body>
                        <p>Hi $username,</p>
                        <p>We just wanted to check in on you today. Click the link below to visit our website:</p>
                        <p><a href='http://localhost/appA/home.php'>Visit Website</a></p>
                    </body>
                    </html>
                ";

                // Send the email
                if ($mail->send()) {
                    echo "Email successfully sent to $email<br>";
                } else {
                    echo "Failed to send email to $email<br>";
                }
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "Not the correct time to send emails.";
    }
} else {
    echo "No users found or query failed.";
}

$conn->close();
?>
