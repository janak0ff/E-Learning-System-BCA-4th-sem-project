<?php //header('Location: main/index.php'); ?>

<?php require_once "controllerUserData.php"; ?>
<?php
// Retrieve the email address from the session
$email = $_SESSION['email'];

if ($email != false) {
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if ($run_Sql) {
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];

        // Query the database to get the user ID
        $userIDQuery = "SELECT id FROM usertable WHERE email = '$email'";
        $userID = mysqli_query($con, $userIDQuery);

        // Fetch the user ID from the database result
        $row = mysqli_fetch_assoc($userID);
        $id = $row['id'];

        // Store the user ID in the session
        $_SESSION['id'] = $id;

        // Retrieve the id value from the session
        $id = $_SESSION['id'];

        if ($status == "verified") {
            if ($code != 0) {

                header('Location: reset-code.php');
            }
        }
        // else {
        //     header('Location: user-otp.php');
        // }
    }
} else {
    header('Location: login-user.php');
}
// $email = $_SESSION['email'];
// $password = $_SESSION['password'];
// if ($email != false && $password != false) {
//     $sql = "SELECT * FROM usertable WHERE email = '$email'";
//     $run_Sql = mysqli_query($con, $sql);
//     if ($run_Sql) {
//         $fetch_info = mysqli_fetch_assoc($run_Sql);
//         $status = $fetch_info['status'];
//         $code = $fetch_info['code'];
//         if ($status == "verified") {
//             if ($code != 0) {
//                 header('Location: reset-code.php');
//             }
//         } else {
//             header('Location: user-otp.php');
//         }
//     }
// } else {
//     header('Location: login-user.php');
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $fetch_info['name'] ?> | Home</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style.css">
    <script src="./script.js"></script>
    <style>
        nav {
            padding-left: 100px !important;
            padding-right: 100px !important;
            background: #6665ee;
        }

        button {
            padding: 20px;
            border-radius: 8px;
            color: black !important;
        }

        h1 {
            font-size: 50px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <button type="button" id="delete-user-btn">Delete Account</button>
        <button type="button"><a href="logout-user.php">Logout</a></button>
        <button><a href="update_profile.php">update profile</a></button>
    </nav>


    <div align='center' class="profile">
        <?php
        $image_url = $fetch_info['photo'];
        if (strpos($image_url, 'https://') === 0) {
            echo '<img src="' . $image_url . '">';
        } else {
            echo '<img src="uploaded_img/' . $image_url . '">';
        }
        ?>
        <h1>Email: <?php echo $fetch_info['email'] ?></h1>
        <h1>Name: <?php echo $fetch_info['name'] ?></h1>
        <?php echo "The user's ID is: " . $id; ?>
    </div>




    <script>
        document.getElementById('delete-user-btn').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the button from submitting the form

            // Confirm with the user before deleting the account
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                // Send an AJAX request to the PHP script that will handle the delete action
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete-user.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Redirect the user to the login page after deleting the account
                        window.location.href = 'login-user.php';
                    } else {
                        // Display an error message to the user
                        alert('Error: ' + xhr.statusText);
                    }
                };
                xhr.send(); // Send the AJAX request without any parameters
            }
        });
    </script>
</body>

</html>



<?php
			// Fetch data from MySQL table
			// Sanitize and set the sorting column
			$sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : 'title';

			// Sanitize and set the sorting order
			$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

			// Generate the sorting links/buttons
			echo '<center style="padding-bottom: 20px;">';
			echo '<p style="font-size: 20px;border: 2px solid green;display: inline;border-radius: 8px;padding: 10px;">Sort by: ';
			echo '<a style="padding: 10px;" href="?sort=title&order=asc">Name(&darr;)</a> | ';
			echo '<a style="padding: 10px;" href="?sort=title&order=desc">Name(&uarr;)</a> | ';
			echo '<a style="padding: 10px;" href="?sort=date&order=asc">Date(&darr;)</a> | ';
			echo '<a style="padding: 10px;" href="?sort=date&order=desc">Date(&uarr;)</a></p>';
			echo '</center>';

			// Set up the SQL query with LEFT OUTER JOIN and RIGHT OUTER JOIN
			$sql = "SELECT * FROM medical_health 
        LEFT OUTER JOIN comments ON medical_health.id = comments.id 
        UNION 
        SELECT * FROM medical_health 
        RIGHT OUTER JOIN comments ON medical_health.id = comments.id 
        WHERE medical_health.id IS NULL 
        ORDER BY $sort $order";

			// Execute the SQL query
			$result = $con->query($sql);

			// Build the SQL query using the selected sorting method and order
			// $sql = "SELECT * FROM medical_health ORDER BY $sort $order";
			
			// $sql = "SELECT * FROM medical_health ORDER BY title ASC";
			// $sql = "SELECT * FROM medical_health ORDER BY title DESC";
			// $sql = "SELECT * FROM medical_health ORDER BY date DESC";
			

			// Output HTML elements dynamically based on data
			echo '<div class="dictionary">';
			for ($index = 1; ($row = $result->fetch_assoc()); $index++) {
				// Display the item with the unique id
				$html = '<div class="dictionary-item">';
				$html .= '<button aria-expanded="false" onclick="incrementViews(' . $row["id"] . ')">';
				$html .= '<p class="title" id="title-' . $row["id"] . '">';
				$html .= '<span class="bandage">' . $index . '</span>' . $row["title"];
				$html .= '<span class="janakdate">Date: ' . $row["date"] . '</span>';
				$html .= '<span class="janakcreators">By: ' . $row["creators"] . '</span>';
				$html .= '<span class="janakviews">Views: ' . $row["views"] . '</span>';
				$html .= '<span title="Please like it" onclick="incrementLikes(' . $row["id"] . ')" class="janaklikes">';
				$html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(32, 9, 216, 1);transform: ;msFilter:;"><path d="M4 21h1V8H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2zM20 8h-7l1.122-3.368A2 2 0 0 0 12.225 2H12L7 7.438V21h11l3.912-8.596L22 12v-2a2 2 0 0 0-2-2z"></path></svg> ' . $row["likes"] . '</span>';
				$html .= '</p>';
				$html .= '<span class="iconplus"></span>';
				$html .= '</button>';
				$html .= '<div class="WDescription">';
				$html .= '<h1>' . $row["description"];
				$html .= '<hr><div><span style="background: #a6a6ad;padding: 2px;font-size:18px;">By: ' . $row['namec'] . '</span> <br><p style="margin: 0 0 0 44px;font-size:18px;">  ' . $row["descriptionc"] . '</p></div>';
				$html .= '</h1>';
				$html .= '<center><form action="comments.php" method="post">';
				$html .= '<input type="text"  name="id" hidden value="' . $row["id"] . '"/>';
				$html .= '<input type="text"  name="namec" hidden value="' . $fetch_info['name'] . '"/>';
				$html .= '<input type="text"  name="titlec" hidden value="' . $row['title'] . '"/>';
				$html .= '<textarea rows="10" style="width: 50%;" type="text" name="descriptionc" placeholder="Add your comment..."></textarea>';
				$html .= '<button style="width: 89px !important;padding: 10px;color: white;border-radius: 8px;margin-bottom: 10px;background: blue !important;text-align: center;" type="submit">Submit</button>';
				$html .= '</form></center>';
				$html .= '</div>';
				$html .= '</div>';

				echo $html;
			}
			echo '</div>';
			?>