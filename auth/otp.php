<?php
include('../config/config.php'); 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = trim($_POST['otp']);
    $user_id = $_SESSION['user_id'];

    try {
      
        $stmt = $connection->prepare("SELECT otp, otp_expiry FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['otp'] === $entered_otp && new DateTime() <= new DateTime($user['otp_expiry'])) {
          
            unset($_SESSION['otp']);

          
            header('Location: index.php');
            exit();
        } else {
            $error = "Invalid or expired OTP.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP</title>
    <style>
   
    </style>
</head>
<body>
    <form method="post">
        <h2>OTP Verification</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <label for="otp">Enter OTP:</label>
        <input type="text" id="otp" name="otp" required>
        
        <button type="submit">Verify OTP</button>
    </form>
</body>
</html>
