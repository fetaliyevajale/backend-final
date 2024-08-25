<?php
function sendOtpEmail($to, $otp) {
    $subject = 'Your OTP Code';
    $message = "Your OTP code is: $otp\n\nThis code is valid for 10 minutes.";
    $headers = 'From: no-reply@example.com' . "\r\n" .
               'Reply-To: no-reply@example.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}

?>