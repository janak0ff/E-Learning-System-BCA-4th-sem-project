<?php //require_once "connection.php"; ?>
<?php require_once "controllerUserData.php";
?>
<?php
//if user clicks the check reset otp button
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['email']) && isset($_GET['code'])) {
    $_SESSION['info'] = "";

    $email = $_GET['email'];
    $otp_code = $_GET['code'];

    $check_code = "SELECT * FROM usertable WHERE code = '$otp_code' AND email = '$email'";
    $code_res = mysqli_query($con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $expire = $fetch_data['expire'];
        $time = time();
        if ($expire > $time) {
            $fetch_code = $fetch_data['code'];
            $code = 0;
            $update_otp = "UPDATE usertable SET code = $code WHERE code = $fetch_code";
            $update_res = mysqli_query($con, $update_otp);
            $email = $fetch_data['email'];
            $_SESSION['email'] = $email;
            $info = "Please create a new password that you don't use on any other site.";
            $_SESSION['info'] = $info;
            header('location: new-password.php');
            exit();
        } else {
            $errors['otp-error'] = "Code expired";
        }
    } else {
        $errors['otp-error'] = "OTP already used.";
    }
} else {
    $errors['otp-error'] = "Your verification link is invalid..!";
}
// header('location: login-user.php');
// exit();
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>create password via link</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./main/images/favicon.webp" />
    
    <link rel="stylesheet" href="style.css">
</head>

<body style="background: #b4b4e2;">
    <center style="top: 40vh; position: relative;">
        <h1><?php echo isset($errors['otp-error']) ? $errors['otp-error'] : 'Link expired..!' ?></h1>
        <button style="font-size: 30px;">
            <a style="text-decoration: none;" href="./login-user.php">Login</a>
        </button>
    </center>

</body>

</html>