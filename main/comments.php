<?php require "../controllerUserData.php"; ?>
<?php

$id = $_POST["titleid"];
$descriptionc = $_POST["descriptionc"];
$namec = $_POST["namec"];
$titlec = $_POST["titlec"];

// Insert the new comment into the database 
$sql = "INSERT INTO comments (id, namec, titlec, descriptionc) VALUES ('$id', '$namec', '$titlec', '$descriptionc')";
$con->query($sql);

// Redirect back to the index page 
header("Location: index.php");
exit();
?>