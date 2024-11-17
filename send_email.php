<?php
// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Include the PHP files using require 
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Function to sanitize and validate input
function sanitizeInput($data) {
    // Trim whitespace
    $data = trim($data);
    // Convert special characters to HTML entities
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Ensure the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';

    // Array to collect validation errors
    $errors = [];

    // Validate required fields
    if (empty($name)) {
        $errors[] = "The Name field is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "The Email address is invalid.";
    }

    if (empty($message)) {
        $errors[] = "The Message field is required.";
    }

    // Return errors as a JSON response if any
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }

    // Configure PHPMailer to send the email
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration (optional, fallback to mail() if you don’t want SMTP)
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // Your SMTP username
        $mail->Password = 'your_password';         // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('your_email@example.com', 'Your Name'); // Replace with your email
        $mail->addAddress('example@example.com'); // Recipient email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Message from $name";
        $mail->Body    = "
            <h1>New message from the contact form</h1>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        ";
        $mail->AltBody = "Name: $name\nEmail: $email\nMessage: $message";

        // Send the email
        if ($mail->send()) {
            echo json_encode([
                'success' => true,
                'message' => 'Email sent successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send email.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => "Error: {$mail->ErrorInfo}"
        ]);
    }
} else {
    // Block non-POST requests
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>



