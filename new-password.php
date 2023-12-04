<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $to = $email;
    $subject = "Welcome to Our Website";
    $message = "Hello $username,\n\nThank you for registering on our website!";
    $headers = "From: your@example.com"; // Change this to a valid sender email address
    if (mail($to, $subject, $message, $headers)) {
        $message = "Registration successful! An email has been sent to your email address.";
    } else {
        $message = "Registration successful! However, there was an issue sending the email.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Registration Form with Email</title></head>
<body>
<h2>Registration Form</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" name="register" value="Register">
</form>
<p><?php echo isset($message) ? $message : ""; ?></p>
</body></html>
