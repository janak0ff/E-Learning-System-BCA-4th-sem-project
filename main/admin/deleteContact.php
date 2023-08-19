<!-- delete.php -->
<?php require "../../controllerUserData.php"; ?>
<?php
$email = $_SESSION['email'];
// Connect to the MySQL database
$conn = mysqli_connect("localhost", "root", "", "elearn");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Delete the user record with the specified ID except id 1 (admin)
$id = $_POST["id"]; // Retrieve the id parameter from HTTP POST

$query = "DELETE FROM contact WHERE id = '$id'";
mysqli_query($conn, $query);

// $sql = "SELECT * FROM usertable WHERE email = '$email'";
// $run_Sql = mysqli_query($con, $sql);
// if ($run_Sql) {
//     $fetch_info = mysqli_fetch_assoc($run_Sql);
//     if (isset($_SESSION['email'])) {
//         $email = $_SESSION['email'];
//         if ($email != $fetch_info['email']) {
//             $query = "DELETE FROM usertable WHERE id = '$id'";
//             mysqli_query($conn, $query);
//         }
//     }
// }



// Close the MySQL connection
mysqli_close($conn);

// Redirect back to the index page
header("Location: admin.php");
exit();
?>