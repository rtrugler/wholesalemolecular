<?php
header('Content-Type: application/json');

// Replace with your email
$to_email = 'rtrugler@gmail.com';

// Get form data
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Validate required fields
if (!$name || !$company || !$email || !$message) {
    echo json_encode([
        'success' => false,
        'message' => 'Please fill in all required fields.'
    ]);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);
    exit;
}

// Prepare email content
$subject = "New Contact Form Submission from $name";
$email_content = "Name: $name\n";
$email_content .= "Company: $company\n";
$email_content .= "Email: $email\n";
$email_content .= "Phone: $phone\n\n";
$email_content .= "Message:\n$message";

// Email headers
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
try {
    if (mail($to_email, $subject, $email_content, $headers)) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for your message. We will get back to you soon!'
        ]);
    } else {
        throw new Exception('Failed to send email');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error sending your message. Please try again later.'
    ]);
}
?>