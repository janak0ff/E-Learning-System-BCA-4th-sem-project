<?php require_once "connection.php"; ?>
<?php
//if user click verification link then verify
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['email']) && isset($_GET['code'])) {

    // $_SESSION['info'] = "";
    $email = $_GET['email'];
    $otp_code = $_GET['code'];
    $time = time();
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code AND email = '$email'";
    $code_res = mysqli_query($con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $expire = $fetch_data['expire'];
        if ($expire > $time) {
            $fetch_code = $fetch_data['code'];
            $code = 0;
            $status = 'verified';
            $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($con, $update_otp);
            if ($update_res) {
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                header('location: main/index.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while updating code!";
            }
        } else {
            $errors['otp-error'] = "Code expired";
        }
    } else {
        $errors['otp-error'] = "You have already used your OTP.";
    }
}
// header('location: login-user.php');
// exit();
?>

<html>

<head>
    <title>Verify email via link</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./main/images/favicon.webp" />
</head>

<body style="background: #b4b4e2;">
    <center style="top: 40vh; position: relative;">
        <h1><?php echo isset($errors['otp-error']) ? $errors['otp-error'] : 'Link expired..!' ?></h1>
        <button style="font-size: 30px;">
            <a style="text-decoration: none;" href="main/index.php">Home</a>
        </button>
    </center>

</body>

</html>