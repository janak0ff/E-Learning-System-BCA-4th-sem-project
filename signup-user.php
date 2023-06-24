<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Signup Form</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./main/images/favicon.webp" />

    <link rel="stylesheet" href="style.css">
    <script src="./script.js"></script>
</head>

<body>
    <section class="container forms">
        <div class="form signup">
            <div class="form-content">
                <header>Signup</header>
                <form action="signup-user.php" method="POST" enctype="multipart/form-data">
                    <p class="text-center">It's quick and easy.</p>
                    <?php
                    if (count($errors) == 1) {
                        ?>
                        <div style="color: black;text-align: center;background: #ecb1b1;border-radius: 5px;padding: 6px;">
                            <?php
                            foreach ($errors as $showerror) {
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    } elseif (count($errors) > 1) {
                        ?>
                        <div style="color: black;text-align: center;background: #ecb1b1;border-radius: 5px;padding: 6px;">
                            <?php
                            foreach ($errors as $showerror) {
                                ?>
                                <li>
                                    <?php echo $showerror; ?>
                                </li>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="field input-field">
                        <input type="text" name="name" placeholder="Full Name" required value="<?php echo $name ?>">
                    </div>
                    <div class="field input-field">
                        <input type="email" class="input" name="email" placeholder="Email Address" required
                            value="<?php echo $email ?>">
                    </div>

                    <div class="field input-field">
                        <input type="password" placeholder="Password" name="password" required class="password">
                    </div>

                    <div class="field input-field">
                        <input type="password" placeholder="Confirm password" name="cpassword" class="password"
                            required>
                        <i class='bx bx-hide eye-icon'></i>
                    </div>

                    <div style="padding:8px 0 0 5px">
                        <label style="margin-right:5px">Role:-</label>
                        <input type="radio" name="role" value="reader" checked>
                        <label style="margin-right:5px">Reader</label>

                        <input type="radio" name="role" value="creator">
                        <label style="margin-right:5px">Creator</label>

                        <input type="radio" name="role" value="admin">
                        <label style="margin-right:5px">Admin</label>
                    </div>

                    <div class="field input-field" style="display:flex;">
                        <label style="margin-right:5px">Profile pic:
                            <input style="width: 65%;" type="file" name="photo" accept="photo/jpg, photo/jpeg, photo/png">
                        </label>
                    </div>

                    <div class="field button-field">
                        <input class="form-control button" type="submit" name="signup" value="Signup">
                    </div>
                </form>

                <div class="form-link">
                    <span>Already have an account? <a href="login-user.php" class="link login-link">Login
                            here</a></span>
                </div>
            </div>
        </div>
    </section>

</body>

</html>