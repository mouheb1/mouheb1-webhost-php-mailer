<?php
require 'sendEmail.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    $nom = isset($data['nom']) ? htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8') : '';
    $prenom = isset($data['prenom']) ? htmlspecialchars($data['prenom'], ENT_QUOTES, 'UTF-8') : '';
    $telephone = isset($data['telephone']) ? htmlspecialchars($data['telephone'], ENT_QUOTES, 'UTF-8') : '';
    $email = isset($data['email']) ? filter_var($data['email'], FILTER_VALIDATE_EMAIL) : '';
    $message = isset($data['message']) ? htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8') : '';
    $acceptTerms = isset($data['acceptTerms']) ? (bool)$data['acceptTerms'] : false;

    if (!$email) {
        http_response_code(400);
        echo "Invalid email address.";
        exit;
    }

    // Email to recipient
    $to = "recipient_email@example.com";
    $subject = "Contact Form Submission";
    $fromEmail = 'no-reply@stumav.com';
    $fromName = 'no-reply';

    $emailContent = "
    <html>
    <body>
      <h2>Contact Form Submission</h2>
      <p><strong>Nom:</strong> $nom</p>
      <p><strong>Prenom:</strong> $prenom</p>
      <p><strong>Telephone:</strong> $telephone</p>
      <p><strong>Email:</strong> $email</p>
      <p><strong>Message:</strong> $message</p>
      <p><strong>Accept Terms:</strong> " . ($acceptTerms ? 'Yes' : 'No') . "</p>
    </body>
    </html>";

    // Email to sender
    $confirmationSubject = "Thank you for contacting us";
    $confirmationContent = "Thank you for reaching out. We have received your message and will get back to you within 48 hours.";

    // Send emails
    $recipientEmailSent = sendEmail($to, $subject, $emailContent, $fromEmail, $fromName);
    $confirmationEmailSent = sendEmail($email, $confirmationSubject, $confirmationContent, $fromEmail, $fromName);

    if ($recipientEmailSent && $confirmationEmailSent) {
        http_response_code(200);
        echo "Message sent successfully.";
    } else {
        http_response_code(500);
        echo "Failed to send message.";
    }
} else {
    http_response_code(403);
    echo "Forbidden.";
}
?>

