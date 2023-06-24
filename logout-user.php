<?php
session_start();
session_unset();
session_destroy();

include('controllerUserData.php');
//Reset OAuth access token
$google_client->revokeToken();
header('location: login-user.php');
?>