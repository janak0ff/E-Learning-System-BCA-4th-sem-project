<?php require_once "controllerUserData.php"; ?>
<?php
$email = $_SESSION['email'];
if ($email == false) {
    header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
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
                <form action="new-password.php" method="POST" autocomplete="off">
                    <h2 class="text-center">Create New Password</h2>
                    <?php
                    if (isset($_SESSION['info'])) {
                    ?>
                        <div style="color: black;text-align: center;background: #6dc2d4b3;border-radius: 5px;padding: 6px;">
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
                    <div class="field input-field">
                        <input type="password" name="password" placeholder="Create new password" required>
                    </div>
                    <div class="field input-field">
                        <input type="password" name="cpassword" placeholder="Confirm your password" required>
                    </div>
                    <div class="field input-field">
                        <input class="form-control button" type="submit" name="change-password" value="Change">
                    </div>
                </form>
            </div>
        </div>
    </section>

</body>

</html>