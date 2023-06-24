<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password</title>
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
                <header>Forgot Password</header>
                <form action="forgot-password.php" method="POST">
                    <p class="text-center">Enter your email address</p>
                    <?php
                    if (count($errors) > 0) {
                    ?>
                        <div style="color: black;text-align: center;background: #ecb1b1;border-radius: 5px;padding: 6px;">
                            <?php
                            foreach ($errors as $error) {
                                echo $error;
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>

                    <div class="field input-field">
                        <input type="email" name="email" placeholder="Enter email address" required value="<?php echo $email ?>">
                    </div>

                    <div class="field button-field">
                        <input class="form-control button" type="submit" name="check-email" value="Continue">
                    </div>
                </form>
            </div>
        </div>
    </section>

</body>

</html>