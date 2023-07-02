<?php require_once "controllerUserData.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Form</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./main/images/favicon.webp" />
    
    <link rel="stylesheet" href="style.css">
    <script src="./script.js"></script>
</head>

<body>
    <section class="container forms">
        <div class="form login">
            <div class="form-content">
                <header>Login</header>
                <form action="login-user.php" method="POST">
                    <p class="text-center">Login with your email and password.</p>
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
                    <div class="field input-field">
                        <input type="email" name="email" placeholder="Email Address" required
                            value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>" placeholder="Email"
                            class="input">
                    </div>

                    <div class="field input-field">
                        <input type="password" placeholder="Password" name="password" placeholder="Password" required
                            value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>"
                            class="password">
                        <i class='bx bx-hide eye-icon'></i>
                    </div>

                    <div class="form-link" style="display: flex;">
                        <div>
                            <input type="checkbox" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <div style="position: absolute;right: 30px;">
                            <a href="forgot-password.php">Forgot password?</a>
                        </div>
                    </div>

                    <div class="field button-field">
                        <input class="form-control button" type="submit" name="login" value="Login">
                    </div>
                </form>

                <div style="margin-top: 20px;" class="form-link">
                    <span>Don't have an account? <a href="signup-user.php" class="link signup-link">Register</a></span>
                </div>
            </div>

            <div class="line"></div>

            <div class="media-options">
                <!-- login with google  -->
                <?php //if ($login_button != '') : 
                ?>
                <? //= $login_button 
                ?>
                <?php //endif; 
                ?>
                <!-- login with google end -->

                <a href="<?= $google_client->createAuthUrl() ?>" class="field google">
                    <svg class="google-img" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg"
                        version="1.1" width="512" height="512" viewBox="0 0 512 512">
                        <g transform="matrix(1.5838926,0,0,1.5838926,18.416107,18.416107)" fill="none">
                            <path
                                d="m293 153.4c0-10.6-0.9-20.7-2.7-30.5l-140.3 0 0 57.6 80.2 0c-3.4 18.6-13.9 34.4-29.7 45l0 37.4 48.2 0c28.2-25.9 44.4-64.1 44.4-109.5z"
                                fill="#4285f4" />
                            <path
                                d="m150 299c40.2 0 74-13.3 98.6-36.1l-48.1-37.4c-13.3 8.9-30.4 14.2-50.5 14.2-38.8 0-71.7-26.2-83.4-61.4l-49.8 0 0 38.6C41.4 265.6 91.8 299 150 299Z"
                                fill="#34a853" />
                            <path
                                d="m66.6 178.3c-3-8.9-4.7-18.5-4.7-28.3 0-9.8 1.7-19.4 4.7-28.3l0-38.6-49.8 0C6.8 103.2 1 126 1 150c0 24 5.8 46.8 15.9 66.9l49.8-38.6z"
                                fill="#fbbc05" />
                            <path
                                d="m150 60.3c21.9 0 41.5 7.5 57 22.3L249.7 39.8C223.9 15.8 190.2 1 150 1 91.8 1 41.4 34.4 16.9 83.1l49.8 38.6C78.3 86.5 111.2 60.3 150 60.3Z"
                                fill="#ea4335" />
                            <path d="M1 1 299 1 299 299 1 299 1 1Z" />
                        </g>
                    </svg>
                    <span>Login with Google</span>
                </a>
            </div>
        </div>
    </section>


<script src="./script.js"></script>



</body>

</html>