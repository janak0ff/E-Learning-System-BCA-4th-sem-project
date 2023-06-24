<?php
session_start();
require "connection.php";
require "mail.php";
$email = "";
$name = "";
$errors = array();


///////////////////////////////////////


// Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

// Make object of Google API Client for call Google API
$google_client = new Google_Client();

// // Set the OAuth 2.0 Client ID
// $google_client->setClientId('387724901413-9j0prtm3u750vnte2v8evc4ot5hha1ch.apps.googleusercontent.com');

// // Set the OAuth 2.0 Client Secret key
// $google_client->setClientSecret('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');

// Set the OAuth 2.0 Client ID
$google_client->setClientId('448418349961-tt988rmtkhekrevia5jkmtc47i2u3ea7.apps.googleusercontent.com');

// Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

// Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/medical%20health/');

// Set the OAuth 2.0 Redirect URI
// $google_client->setRedirectUri('http://localhost/LoginSign/');

// Set the scopes to get the email and profile
$google_client->addScope('email profile');


// Check if the user is logging in with Google
if (isset($_GET["code"])) {
    // Fetch the access token with the authorization code
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    // If there is no error in the token
    if (!isset($token['error'])) {
        // Set the access token
        $google_client->setAccessToken($token['access_token']);

        // Store the access token in a session variable
        $_SESSION['access_token'] = $token['access_token'];

        // Create an instance of the Google OAuth2 service
        $google_service = new Google_Service_Oauth2($google_client);

        // Get the user information
        $data = $google_service->userinfo->get();

        // Sanitize and validate user data
        $name = filter_var($data['given_name'] . ' ' . $data['family_name'], FILTER_SANITIZE_STRING);
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $photo = filter_var($data['picture'], FILTER_SANITIZE_URL);


        // Connect to the database
        $mysqli = mysqli_connect("localhost", "root", "", "medicalhealth");

        // Check if the user already exists in the database
        $result = mysqli_query($mysqli, "SELECT id FROM usertable WHERE email = '$email'");

        if (mysqli_num_rows($result) > 0) {
            // Update the user's existing record
            mysqli_query($mysqli, "UPDATE usertable SET name = '$name', status = 'verified', photo = '$photo' WHERE email = '$email'");
        } else {
            // Save user data in database
            mysqli_query($mysqli, "INSERT INTO usertable (status, name, email, photo) VALUES ('verified', '$name', '$email', '$photo') ON DUPLICATE KEY UPDATE name = '$name', email = '$email', photo = '$photo'");
        }

        // Close the database connection
        mysqli_close($mysqli);

        // Store the user's information in the session
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        // $_SESSION['photo'] = $photo;
        header('location: main/index.php');
    }
}

if (!isset($_SESSION['access_token'])) {
    $login_button = $google_client->createAuthUrl();
}

///////////////////////////////////////////



//if user signup 
// if (isset($_POST['signup'])) {
//     $name = mysqli_real_escape_string($con, $_POST['name']);
//     $email = mysqli_real_escape_string($con, $_POST['email']);
//     $password = mysqli_real_escape_string($con, $_POST['password']);
//     $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

//     if ($password !== $cpassword) {
//         $errors['password'] = "Confirm password not matched!";
//     }
//     $email_check = "SELECT * FROM usertable WHERE email = '$email'";
//     $res = mysqli_query($con, $email_check);
//     if (mysqli_num_rows($res) > 0) {
//         $errors['email'] = "Email that you have entered is already exist!";
//     }
//     if (count($errors) === 0) {
//         $encpass = password_hash($password, PASSWORD_BCRYPT);
//         $code = rand(9999, 1111);
//         $status = "notverified";
//         $expire = (time() + (60 * 1));
//         // $time = time();
//         $insert_data = "INSERT INTO usertable (name, email, password, code, status, expire)
//                         values('$name', '$email', '$encpass', '$code', '$status', '$expire')";
//         $data_check = mysqli_query($con, $insert_data);
//         if ($data_check) {
//             // $sender = "From: janak123g@gmail.com";
//             // $message = "Your verification code is $code";
//             $subject = "Email Verification Code";
//             $recipient = $email;
//             $message = 'Dear ' . "<b>" . $name . "</b>" . ',<br><br>' . 'Your OTP code is: ' . "<b>" . $code . "</b>" . '.<br><br>OR<br><br>Please confirm your registration by clicking on the following link:<b><a href="http://localhost/medical%20health/emailVerifyLink.php?email=' . $email . '&code=' . $code . '">click here</a></b><br><br><b>You have only 60 seconds to use the OTP and verification link.</b>';
//             // send_mail($recipient, $subject, $message);
//             if (send_mail($recipient, $subject, $message)) {
//                 $info = "We've sent a four digit OTP code/ Activation link to your email <b>- $email </b>.Enter the code below to confirm your email address.";
//                 $_SESSION['info'] = $info;
//                 $_SESSION['email'] = $email;
//                 $_SESSION['password'] = $password;
//                 header('location: user-otp.php');
//                 exit();
//             } else {
//                 $errors['otp-error'] = "Failed while sending code!";
//             }
//         } else {
//             $errors['db-error'] = "Failed while inserting data into database!";
//         }
//     }
// }

//if user signup 
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role = mysqli_real_escape_string($con, $_POST['role']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $errors = array();

    // Check if photo was uploaded
    if (!isset($_FILES['photo'])) {
        $errors['photo'] = "Please upload profile picture";
    } else {
        $photo = $_FILES['photo']['name'];
        $photo_size = $_FILES['photo']['size'];
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_folder = 'uploaded_img/' . $photo;

        // Check if file was uploaded successfully
        if (!is_uploaded_file($photo_tmp_name)) {
            $errors['photo'] = "Please upload profile picture";
        }

        // Check if photo size is too large
        if ($photo_size > 2000000) {
            $errors['photo'] = 'Image size is too large!';
        }
    }

    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    }

    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);

    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = "Email that you have entered is already exist!";
    }

    if (count($errors) === 0) {
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(9999, 1111);
        $status = "notverified";
        $expire = (time() + (60 * 1));
        $insert_data = "INSERT INTO usertable (name, email, role, password, code, status, expire, photo)
                        values('$name', '$email', '$role', '$encpass', '$code', '$status', '$expire', '$photo')";
        $data_check = mysqli_query($con, $insert_data);
        if ($data_check) {
            if (move_uploaded_file($photo_tmp_name, $photo_folder)) {
                $subject = "Email Verification Code";
                $recipient = $email;
                $message = 'Dear ' . "<b>" . $name . "</b>" . ',<br><br>' . 'Your OTP code is: ' . "<b>" . $code . "</b>" . '.<br><br>OR<br><br>Please confirm your registration by clicking on the following link:<b><a href="http://localhost/medical%20health/emailVerifyLink.php?email=' . $email . '&code=' . $code . '">click here</a></b><br><br><b>You have only 60 seconds to use the OTP and verification link.</b>';

                if (send_mail($recipient, $subject, $message)) {
                    $info = "We've sent a four digit OTP code/ Activation link to your email <b>- $email </b>.Enter the code below to confirm your email address.";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
                    $_SESSION['password'] = $password;
                    header('location: user-otp.php');
                    exit();
                } else {
                    $errors['otp-error'] = "Failed while sending code!";
                }
            } else {
                $errors['photo'] = "Failed to upload photo!";
            }
        } else {
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }
}




//verification code submit button-new
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['otp1']) && isset($_POST['otp2']) && isset($_POST['otp3']) && isset($_POST['otp4'])) {

    //  $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp1'] . $_POST['otp2'] . $_POST['otp3'] . $_POST['otp4']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    $time = time();
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $expire = $fetch_data['expire'];
        if ($expire > $time) {
            $fetch_code = $fetch_data['code'];
            $email = $fetch_data['email'];
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
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//verification code submit button- old
// if(isset($_POST['check'])){
//     $_SESSION['info'] = "";
//     $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
//     $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
//     $code_res = mysqli_query($con, $check_code);
//     if(mysqli_num_rows($code_res) > 0){
//         $fetch_data = mysqli_fetch_assoc($code_res);
//         $fetch_code = $fetch_data['code'];
//         $email = $fetch_data['email'];
//         $code = 0;
//         $status = 'verified';
//         $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
//         $update_res = mysqli_query($con, $update_otp);
//         if($update_res){
//             $_SESSION['name'] = $name;
//             $_SESSION['email'] = $email;
//             header('location: home.php');
//             exit();
//         }else{
//             $errors['otp-error'] = "Failed while updating code!";
//         }
//     }else{
//         $errors['otp-error'] = "You've entered incorrect code!";
//     }
// }




//if user clicks login button
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Check if the user has checked the "Remember Me" checkbox
    $remember_me = isset($_POST['remember']) ? $_POST['remember'] : '';

    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if (mysqli_num_rows($res) > 0) {
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if (password_verify($password, $fetch_pass)) {
            $_SESSION['email'] = $email;
            $status = $fetch['status'];
            if ($status == 'verified') {
                $_SESSION['name'] = $fetch['name'];
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;

                // Check if the user has checked the "Remember Me" checkbox
                $remember_me = isset($_POST['remember']) ? $_POST['remember'] : '';

                if ($remember_me == 'on') {
                    // If "Remember Me" checkbox is checked, set cookies for email and password
                    setcookie('email', $email, time() + (30 * 24 * 60 * 60), "/");
                    setcookie('password', $password, time() + (30 * 24 * 60 * 60), "/");
                } else {
                    // If "Remember Me" checkbox is not checked, remove cookies for email and password
                    setcookie('email', '', time() - 3600, "/");
                    setcookie('password', '', time() - 3600, "/");
                }

                header('location: main/index.php');
            } else {
                $info = "It's look like you haven't still verify your email - $email";
                $_SESSION['info'] = $info;
                header('location: user-otp.php');
            }
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
    }
}
// if (isset($_POST['login'])) {
//     $email = mysqli_real_escape_string($con, $_POST['email']);
//     $password = mysqli_real_escape_string($con, $_POST['password']);

//     // Check if the user has checked the "Remember Me" checkbox
//     $remember_me = isset($_POST['remember']) ? $_POST['remember'] : '';

//     if ($email == 'admin@admin.com' && $password == 'health') {
//         // If the user is the admin, set the session variables and redirect to index.php
//         $_SESSION['email'] = $email;
//         $_SESSION['password'] = $password;
//         header('location: main/admin/admin.php');
//     } else {
//         $check_email = "SELECT * FROM usertable WHERE email = '$email'";
//         $res = mysqli_query($con, $check_email);
//         if (mysqli_num_rows($res) > 0) {
//             $fetch = mysqli_fetch_assoc($res);
//             $fetch_pass = $fetch['password'];
//             if (password_verify($password, $fetch_pass)) {
//                 $_SESSION['email'] = $email;
//                 $status = $fetch['status'];
//                 if ($status == 'verified') {
//                     $_SESSION['email'] = $email;
//                     $_SESSION['password'] = $password;

//                     // Check if the user has checked the "Remember Me" checkbox
//                     $remember_me = isset($_POST['remember']) ? $_POST['remember'] : '';

//                     if ($remember_me == 'on') {
//                         // If "Remember Me" checkbox is checked, set cookies for email and password
//                         setcookie('email', $email, time() + (30 * 24 * 60 * 60), "/");
//                         setcookie('password', $password, time() + (30 * 24 * 60 * 60), "/");
//                     } else {
//                         // If "Remember Me" checkbox is not checked, remove cookies for email and password
//                         setcookie('email', '', time() - 3600, "/");
//                         setcookie('password', '', time() - 3600, "/");
//                     }

//                     header('location: main/index.php');
//                 } else {
//                     $info = "It's look like you haven't still verify your email - $email";
//                     $_SESSION['info'] = $info;
//                     header('location: user-otp.php');
//                 }
//             } else {
//                 $errors['email'] = "Incorrect email or password!";
//             }
//         } else {
//             $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
//         }
//     }
// }




//if user click continue button in forgot password form to send mail
if (isset($_POST['check-email'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM usertable WHERE email='$email'";
    $run_sql = mysqli_query($con, $check_email);
    if (mysqli_num_rows($run_sql) > 0) {
        $codeL = rand(9999, 1111);
        $expire = (time() + (60 * 1));
        $insert_code = "UPDATE usertable SET code = $codeL, expire = $expire WHERE email = '$email'";
        $run_query = mysqli_query($con, $insert_code);

        $name_query = "SELECT name FROM usertable WHERE email = '$email'";
        $name_result = mysqli_query($con, $name_query);
        $name_row = mysqli_fetch_assoc($name_result);
        $name = $name_row['name'];
        if ($run_query) {
            $subject = "Password Reset Code";
            $recipient = $email;
            $message = 'Dear ' . "<b>" . $name . "</b>" . ',<br><br>' . 'Your OTP code is: ' . "<b>" . $codeL . "</b>" . '.<br><br>OR<br><br>Please confirm your registration by clicking on the following link:<b><a href="http://localhost/medical%20health/new-passwordLink.php?email=' . $email . '&code=' . $codeL . '">click here</a></b><br><br><b>You have only 60 seconds to use the OTP and verification link.</b>';
            if (send_mail($recipient, $subject, $message)) {
                $info = "We've sent a password reset otp to your email - $email";
                //  $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['email'] = "This email address does not exist!";
    }
}

//check reset password otp 
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['otpc1']) && isset($_POST['otpc2']) && isset($_POST['otpc3']) && isset($_POST['otpc4']) && isset($_POST['check-reset-otp'])) {
    //  $_SESSION['info'] = "";
    $otp = $_POST['otpc1'] . $_POST['otpc2'] . $_POST['otpc3'] . $_POST['otpc4'];
    $otp_code = mysqli_real_escape_string($con, $otp);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
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
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click change password button
if (isset($_POST['change-password'])) {
    //  $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    } else {
        $code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
        $run_query = mysqli_query($con, $update_pass);
        if ($run_query) {
            $info = "Your password changed. Now you can login with your new password.";
            $_SESSION['info'] = $info;
            header('Location: password-changed.php');
        } else {
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}

//if login now button click
if (isset($_POST['login-now'])) {
    header('Location: login-user.php');
}