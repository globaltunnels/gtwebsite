<?php
require '/Users/kqjf/workspace/php/phpmailer/class.phpmailer.php'; // Adjust the path to your Composer's autoload.php
require '/Users/kqjf/workspace/php/phpmailer/class.smtp.php';

// Create instance of PHPMailer
$mail = new PHPMailer(true);

// Configuration for SMTP — credentials come from the server environment,
// never from this file (this repository is public).
$mail->SMTPDebug = 0; //SMTP::DEBUG_SERVER;
$mail->isSMTP();
$mail->Host       = getenv('SMTP_HOST') ?: 'getunnel.com';
$mail->SMTPAuth   = true;
$mail->Username   = getenv('SMTP_USERNAME');
$mail->Password   = getenv('SMTP_PASSWORD');
$mail->SMTPSecure = 'tls';
$mail->Port       = (int)(getenv('SMTP_PORT') ?: 587);
if (!$mail->Username || !$mail->Password) {
    die('Mail service is not configured.');
}

// Validate email format
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die('Invalid email format.');
}

// Sender and recipient
$mail->setFrom('donotreply@getunnel.com', 'Global Tunnels');
$mail->addAddress('jfu@globaltunnels.com', 'Business Development');

// Email subject with default text
$defaultSubjectText = "Website Inquiry - "; // Default text to prepend
$mail->Subject = $defaultSubjectText . $_POST['subject'];

// Email body
$mail->Body    = "Name: " . $_POST['name'] . "\n";
$mail->Body   .= "Telephone: " . $_POST['tel'] . "\n";
$mail->Body   .= "Email: " . $_POST['email'] . "\n";
$mail->Body   .= "Message: " . $_POST['message'];

// Check if email is sent
try {
    $mail->send();
	echo ""
    // Success message
    echo '<h3>The following message has been sent successfully:</h3>';
    echo '<p><strong>Subject:</strong> ' . htmlspecialchars($mail->Subject) . '</p>';
    echo '<p><strong>Message:</strong> ' . nl2br(htmlspecialchars($mail->Body)) . '</p>';
    echo '<p>Click <a href="/">here</a> to go back to the home page.</p>';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
