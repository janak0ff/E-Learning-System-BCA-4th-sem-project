<?php require_once "controllerUserData.php"; ?>
<?php
if ($_SESSION['info'] == false) {
    header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./main/images/favicon.webp" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section class="container forms">
        <div class="form login">
            <div class="form-content">
                <?php
                if (isset($_SESSION['info'])) {
                ?>
                    <div class="alert alert-success text-center">
                        <?php echo $_SESSION['info']; ?>
                    </div>
                <?php
                }
                ?>
                <form action="login-user.php" method="POST">
                    <div class="field input-field">
                        <input class="form-control button" type="submit" name="login-now" value="Login Now">
                    </div>
                </form>
            </div>
        </div>
    </section>

</body>

</html>