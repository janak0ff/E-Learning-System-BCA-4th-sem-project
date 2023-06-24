<?php require_once "controllerUserData.php"; ?>
<?php
$email = $_SESSION['email'];
if ($email == false) {
    header('Location: login-user.php');
}


// Resend OTP code
require_once "mail.php";
if (isset($_POST['resend_otp']) && $_POST['resend_otp'] == 'true') {
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Generate new OTP code and send email
        $code = mt_rand(1000, 9999); // Generate new OTP code
        $expiration_time = time() + 60; // Set expiration time to 60 seconds from now

        // Update OTP code and expiration time in database
        $query = "UPDATE usertable SET code = '$code', expire = '$expiration_time' WHERE email = '$email'";
        mysqli_query($con, $query);
        //for name
        $name_query = "SELECT name FROM usertable WHERE code = '$code'";
        $name_result = mysqli_query($con, $name_query);
        $name_row = mysqli_fetch_assoc($name_result);
        $name = $name_row['name'];
        // Send OTP code email
        $subject = "Email Verification Code";
        $sender = "From: janak123g@gmail.com";
        $recipient = $email;
        $message = 'Dear ' . $name . ',<br><br>' . 'Your OTP code is: ' . "<b>" . $code . "</b><br><br><b>You have only 60 seconds to use the OTP.</b>";

        if (send_mail($recipient, $subject, $message)) {
            $info = "We've sent a verification code/ Activation link to your email - $email";
            $_SESSION['info'] = $info;
            header('Location: user-otp.php');
            exit();
        } else {
            $errors['otp-error'] = "Failed while sending code!";
        }
    } else {
        // Display an error message if the user is not logged in
        echo 'You are not logged in.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Code Verification</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="./script.js"></script>
</head>

<body>
    <section class="container forms">
        <div class="form login">
            <div class="form-content">
                <form action="reset-code.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Code Verification</h2>
                    <p class="text-center">Enter your 4 digits OTP code</p>
                    <?php
                    if (isset($_SESSION['info'])) {
                    ?>
                        <div style="color: black;text-align: center;background: #6dc2d4b3;border-radius: 5px;padding: 6px;" style="padding: 0.4rem 0.4rem">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if (count($errors) > 0) {
                    ?>
                        <div style="color: black;text-align: center;background: #ecb1b1;border-radius: 5px;padding: 6px;">
                            <?php
                            foreach ($errors as $showerror) {
                                echo $showerror;
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- <div>
                        <input type="number" name="otp" placeholder="Enter code" required>
                    </div>
                    <div>
                        <input type="submit" name="check-reset-otp" value="Submit">
                    </div> -->
                    <div class="form" style="text-align: center;">
                        <div class="fields_input">
                            <input type="number" name="otpc1" class="otp_field" placeholder="0" min="0" max="9" required onpaste="return false">
                            <input type="number" name="otpc2" class="otp_field" placeholder="0" min="0" max="9" required onpaste="return false">
                            <input type="number" name="otpc3" class="otp_field" placeholder="0" min="0" max="9" required onpaste="return false">
                            <input type="number" name="otpc4" class="otp_field" placeholder="0" min="0" max="9" required onpaste="return false">
                        </div>
                        <div class="submit">
                            <a style="margin-bottom: 10px;" href="#" id="resend-otp-link">Resend OTP</a>
                            <input type="submit" name="check-reset-otp" value="Verify" class="button">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        // resend otp
        document.getElementById('resend-otp-link').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the link from reloading the page

            // Make an AJAX request to the same PHP script that generates and sends the OTP code
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true); // Send the request to the same page
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    let msg = 'We have successfully resent the OTP, please check your e-mail!'
                    // Display a success message to the user
                    alert(msg);
                    // location.reload();
                }
            };
            xhr.send('resend_otp=true'); // Send a parameter to the PHP script to indicate that the OTP should be resent
        });

        // Initially focus first input
        const otp = document.querySelectorAll('.otp_field');
        otp[0].focus();
        otp.forEach((field, index) => {
            field.addEventListener('keydown', (e) => {
                if (e.key >= 0 && e.key <= 9) {
                    otp[index].value = "";
                    setTimeout(() => {
                        otp[index + 1].focus();
                    }, 4);
                } else if (e.key === 'Backspace') {
                    setTimeout(() => {
                        otp[index - 1].focus();
                    }, 4);
                }
            });
        });
    </script>
</body>

</html>








