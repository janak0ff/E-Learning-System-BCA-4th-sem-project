<!-- update_action.php -->
<?php require "../../controllerUserData.php"; ?>
<?php
// Connect to the MySQL database
$conn = mysqli_connect("localhost", "root", "", "medicalhealth");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Update the user record with the specified ID
$id = $_POST["id"];
$title = $_POST["title"];
$description = $_POST["description"];
$query = "UPDATE medical_health SET title = '$title', description = '$description' WHERE id = '$id'";
mysqli_query($conn, $query);

// Close the MySQL connection
mysqli_close($conn);

// Redirect back to the index page
header("Location: admin.php");
exit();
?>