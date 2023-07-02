<?php
include 'controllerUserData.php';
// Retrieve the id value from the session
$id = $_SESSION['id'];

$select = mysqli_query($con, "SELECT * FROM `usertable` WHERE id = '$id'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch_info = mysqli_fetch_assoc($select);
}

if (isset($_POST['update_profile'])) {

    $update_name = mysqli_real_escape_string($con, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($con, $_POST['update_email']);
    $update_role = mysqli_real_escape_string($con, $_POST['role']);

    mysqli_query($con, "UPDATE `usertable` SET name = '$update_name', email = '$update_email', role = '$update_role' WHERE id = '$id'") or die('query failed');

    //Reset OAuth access token and cookeis, secisson
    // if ($update_email != $fetch_info['email']) {
    //     mysqli_query($con, "UPDATE `usertable` SET email = '$update_email' WHERE id = '$id'") or die('query failed');
    //    //Reset OAuth access token // logout
    //    session_unset();
    //    session_destroy();
    //    $google_client->revokeToken();
    //    header('location: login-user.php');
    // }
    $update_photo = $_FILES['update_photo']['name'];
    $update_photo_size = $_FILES['update_photo']['size'];
    $update_photo_tmp_name = $_FILES['update_photo']['tmp_name'];
    $update_photo_folder = 'uploaded_img/' . $update_photo;

    if (!empty($update_photo)) {
        if ($update_photo_size > 2000000) {
            $message[] = 'photo is too large';
        } else {
            $photo_update_query = mysqli_query($con, "UPDATE `usertable` SET photo = '$update_photo' WHERE id = '$id'") or die('query failed');
            if ($photo_update_query) {
                move_uploaded_file($update_photo_tmp_name, $update_photo_folder);
            }
            $message[] = 'Update succssfully!';
        }
    }
    //Reset OAuth access token // logout
    session_unset();
    session_destroy();
    $google_client->revokeToken();
    header('location: login-user.php');
}

// if (isset($_POST['update_password'])) {
// }



if (isset($_POST['update_password'])) {

    // $old_pass = $fetch_info['password'];
    // $update_pass = mysqli_real_escape_string($con, password_hash($_POST['update_pass'], PASSWORD_BCRYPT));
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    if (!empty($new_pass) && !empty($confirm_pass)) {
        if ($new_pass == $confirm_pass) {

            // Check password strength
            if (strlen($confirm_pass) < 6 || !preg_match("#[0-9]+#", $confirm_pass) || !preg_match("#[a-z]+#", $confirm_pass) || !preg_match("#[A-Z]+#", $confirm_pass) || !preg_match("#\W+#", $confirm_pass)) {
                $message[] = "Password must be at least 6 characters and contain at least one number, one lowercase letter, one uppercase letter, and one special character.";
            } else {

                // Escape the password strings to prevent SQL injection
                $new_pass = mysqli_real_escape_string($con, $new_pass);
                $confirm_pass = mysqli_real_escape_string($con, $confirm_pass);

                // Hash the password
                $hashed_pass = password_hash($confirm_pass, PASSWORD_BCRYPT);

                // Update the user's password in the database
                $query = "UPDATE `usertable` SET password = '$hashed_pass' WHERE id = '$id'";
                $result = mysqli_query($con, $query);
                if (!$result) {
                    die('Query failed: ' . mysqli_error($con));
                }

                // Store updated password in cookies
                setcookie("password", $confirm_pass, time() + (86400 * 30), "/"); // cookie expires after 30 days

                $message[] = 'Password updated successfully!';
            }
        } else {
            $message[] = 'Confirm password not matched!';
        }
    } else {
        $message[] = 'Password required';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./main/images/favicon.webp" />
    <title>update profile</title>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="./style.css">

</head>

<body>

    <div class="update-profile">

        <form action="" method="post" enctype="multipart/form-data">
            <center>
                <h4>Update Your Profile Details</h4>
            </center>
            <?php
            $image_url = $fetch_info['photo'];
            if (strpos($image_url, 'https://') === 0) {
                echo '<img align="center" src="' . $image_url . '">';
            } else {
                echo '<img align="center" src="uploaded_img/' . $image_url . '">';
            }

            if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="message">' . $message . '</div>';
                }
            }
            ?>
            <div class="flex">
                <div class="inputBox">
                    <span>Name :</span>
                    <input type="text" name="update_name" value="<?php echo $fetch_info['name']; ?>" class="box">
                    <span>Email :</span>
                    <input type="email" name="update_email" value="<?php echo $fetch_info['email']; ?>" class="box">
                    <div style="margin:12px 0 12px 0">
                        <label style="margin-right:5px">Role:-</label>
                        <?php
                        $userRole = $fetch_info['role'];
                        if ($userRole == 'creator') {
                            echo ' <input type="radio" name="role" value="reader">
                            <label style="margin-right:5px">Reader</label>
    
                            <input type="radio" name="role" value="creator" checked>
                            <label style="margin-right:5px">Creator</label>';
                        } else {
                            echo ' <input type="radio" name="role" value="reader" checked>
                            <label style="margin-right:5px">Reader</label>
    
                            <input type="radio" name="role" value="creator">
                            <label style="margin-right:5px">Creator</label>';
                        }
                        ?>
                    </div>
                    <div style="display: flex;">
                        <label>Profile pic:
                            <input style="width: 60%;margin-left: 8px;" type="file" name="update_photo"
                                accept="photo/jpg, photo/jpeg, photo/png">
                        </label>
                    </div>
                    <input type="submit" value="Update" name="update_profile" class="btn">
                </div>

                <div class="inputBox">
                    <!-- <input type="text" name="old_pass" value="<?php echo $fetch_info['password']; ?>"> -->
                    <!-- <span>Old password :</span>
                    <input type="password" name="update_pass" placeholder="enter previous password" class="box"> -->
                    <span>New password :</span>
                    <input type="password" name="new_pass" placeholder="enter new password" class="box">
                    <span>Confirm password :</span>
                    <input type="password" name="confirm_pass" placeholder="confirm new password" class="box">
                    <input type="submit" value="Update" name="update_password" class="btn">
                </div>
            </div>
            <div style="margin: 10px auto;">
                <a style="padding: 10px;margin: 10px;background: #d13232;color: white;border-radius: 8px;" href="#"
                    onclick="confirmDelete()">Permanently Delete Account</a>
                <a style="padding: 10px;margin: 10px;background: #3298d1;color: white;border-radius: 8px;"
                    href="main/index.php">Go Home</a>
            </div>
        </form>

    </div>
    <style>
        /* popup dialog box  */

        .alertcenter {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #aa8c8c;
            color: #333;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }

        .alertcenter p {
            color: #fff;
            font-weight: 600;
            font-size: 25px;
        }

        .alertcenter button {
            padding: 6px;
            margin: 5px;
            font-size: 20px;
        }
    </style>
    <script>

        // account delete conform box 

        function confirmDelete() {
            var dialogBox = document.createElement('div');
            dialogBox.className = 'alertcenter';

            var message = document.createElement('p');
            message.textContent = 'Are you sure you want to permanently delete your account? This action cannot be undone.';

            var confirmBtn = document.createElement('button');
            confirmBtn.textContent = 'Delete';
            confirmBtn.onclick = function () {
                window.location = './delete-user.php';
            };

            var cancelBtn = document.createElement('button');
            cancelBtn.textContent = 'Cancel';
            cancelBtn.onclick = function () {
                document.body.removeChild(dialogBox);
            };

            dialogBox.appendChild(message);
            dialogBox.appendChild(confirmBtn);
            dialogBox.appendChild(cancelBtn);

            document.body.appendChild(dialogBox);
        }

    </script>
</body>

</html>