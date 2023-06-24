<?php require_once "controllerUserData.php"; ?>
<?php
// Retrieve the email address from the session
$email = $_SESSION['email'];

if ($email != false) {
    header('Location: main/index.php');
} else {
    header('Location: login-user.php');
} ?>