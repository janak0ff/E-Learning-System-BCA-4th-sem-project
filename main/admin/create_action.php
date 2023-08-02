<!-- create_action.php -->
<?php require "../../controllerUserData.php"; ?>
<?php
$email = $_SESSION['email'];

$sql = "SELECT * FROM usertable WHERE email = '$email'";
$run_Sql = mysqli_query($con, $sql);
$fetch_info = mysqli_fetch_assoc($run_Sql);


if (isset($_POST["title"]) && isset($_POST["description"])) {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $creators = $fetch_info['name'];
    $email = $_SESSION['email'];
    $date = date('Y-m-d');

    // Insert record into database
    $sql = "INSERT INTO words_collection (title, description, creators, email, date) VALUES ('$title', '$description', '$creators', '$email', '$date')";

    mysqli_query($con, $sql);

}

// Redirect back to the index page
header("Location: creators.php");
exit();

?>