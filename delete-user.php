<?php
// session_start();
require_once "controllerUserData.php";

$email = $_SESSION['email'];

// Delete the user's record from the usertable
$query = "DELETE FROM usertable WHERE email='$email'";
$result = mysqli_query($con, $query);

if ($result) {
    // Clear the session variables and destroy the session
    session_unset();
    session_destroy();
    header('Location: login-user.php');
} else {
    echo 'Error deleting account: ' . mysqli_error($con);
}
