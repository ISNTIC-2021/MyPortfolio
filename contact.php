<?php
// contact.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $errors = [];

    if (strlen($name) < 4) $errors[] = "Name must be at least 4 characters long.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Please enter a valid email address.";
    if (strlen($phone) < 10) $errors[] = "Please enter a valid phone number.";
    if (strlen($subject) < 4) $errors[] = "Subject must be at least 4 characters long.";
    if (empty($message)) $errors[] = "Please write a message.";

    if (empty($errors)) {
        $to = "your-email@example.com"; // Replace with your email
        $email_subject = "New Contact Form Submission: $subject";

        // HTML Email Template
        $email_body = "
        <html>
        <head>
          <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
            h1 { color: #007bff; }
            .details { margin-top: 20px; }
            .details p { margin: 10px 0; }
            .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
          </style>
        </head>
        <body>
          <div class='container'>
            <h1>New Contact Form Submission</h1>
            <div class='details'>
              <p><strong>Name:</strong> $name</p>
              <p><strong>Email:</strong> $email</p>
              <p><strong>Phone:</strong> $phone</p>
              <p><strong>Subject:</strong> $subject</p>
              <p><strong>Message:</strong><br>$message</p>
            </div>
            <div class='footer'>
              <p>This email was sent from your website's contact form.</p>
            </div>
          </div>
        </body>
        </html>
        ";

        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $email" . "\r\n";
        $headers .= "Reply-To: $email" . "\r\n";

        if (mail($to, $email_subject, $email_body, $headers)) {
            echo json_encode(["status" => "success", "message" => "Your message has been sent. Thank you!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Unable to send message. Please try again later."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => $errors]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>