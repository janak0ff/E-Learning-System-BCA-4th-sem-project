<?php require "../controllerUserData.php"; ?>

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
		// $id = $fetch_info['id'];
		// Store the user ID in the session
		$_SESSION['id'] = $fetch_info['id'];

		// Retrieve the id value from the session
		$id = $_SESSION['id'];

		if ($status == "verified") {
			if ($code != 0) {
				header('Location: reset-code.php');
			}
		}
		// else {header('Location: user-otp.php');}
	}
} else {
	header('Location: ../login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Medical Health :
		<?php echo $fetch_info['name']; ?>
	</title>
	<link rel="shortcut icon" href="./images/favicon.webp" />
	<link rel="stylesheet" type="text/css" href="./css/styles.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Itim&family=Pangolin&display=swap"
		rel="stylesheet">
</head>

<body style="font-family: 'Itim', cursive;">

	<!-- ----------------scroll indicator------------ -->
	<div title="Scroll Indicator" class="headertoop">
		<div class="progress-bar" id="myBar"></div>
	</div>
	<!-- -------------end------------ -->

	<!-- ------------------- pop up windows box on Right click -------------- -->
	<?php if (isset($_POST['submitContact'])) {
		// Check if the submit button was clicked
		$name = $_POST['name'];
		$email = $_POST['email'];
		$message = $_POST['comment'];

		// Check if the database connection is successful
		if (!$con) {
			die("Connection failed: " . mysqli_connect_error());
		} else {
			$insert_data = "INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$message')";
			mysqli_query($con, $insert_data);
			echo '<script>alert("Send successfully!");</script>';
		}
	} ?>
	<div class="window-containerpop" draggable="true">
		<div class="top" onclick="this.parentElement.style.display='none';">
			<span class="dot" style="background:#ED594A;"></span>
			<span class="dot" style="background:#FDD800;"></span>
			<span class="dot" style="background:#5AC05A;"></span>
			<span class="close-btns" onclick="this.parentElement.parentElement.style.display='none';"> &cross;</span>
		</div>
		<div class="content">
			<div class="card">
				<span class="title">Don't be shy.</span>
				<form action="" class="form" method="post">
					<div class="group">
						<input type="text" id="name" name="name" value="<?php echo $fetch_info['name']; ?>"
							required="" />
						<label for="name">Name</label>
					</div>
					<div class="group">
						<input type="email" value="<?php echo $fetch_info['email']; ?>" id="email" name="email"
							required="" />
						<label for="email">Email</label>
					</div>
					<div class="group">
						<textarea id="comment" name="comment" rows="5" required=""></textarea>
						<label for="comment">Message</label>
					</div>
					<input id="buttonsub" name="submitContact" type="submit" value="submit">
				</form>
			</div>
		</div>
	</div>
	<!-- ----------------- end --------------  -->

	<!-- google taranslate  -->
	<script type="text/javascript">
		function googleTranslateElementInit() {
			new google.translate.TranslateElement({
				pageLanguage: 'en'
			}, 'google_translate_element');
		}
	</script>
	<script type="text/javascript"
		src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

	<!-- Header -->
	<header id="main-header">
		<nav class="navbar">
			<a style="font-size: 3em;font-weight: 900;color: #b5b3b3;padding: 0 0 0 3em;" href="./index.php">
				Medical Health
			</a>

			<ul>
				<?php
				$userRole = $fetch_info['role'];
				if ($userRole == 'admin') {
					echo '<a style="padding: 12px;color: #c5bfbf;background: #665050;font-size: 20px;margin-right: 46px;border-radius: 8px;" href="./admin/admin.php">Admin Dashboard</a>';
				} elseif ($userRole == 'creator') {
					echo '<a style="padding: 12px;color: #c5bfbf;background: #665050;font-size: 20px;margin-right: 46px;border-radius: 8px;" href="./admin/creators.php">Contribute </a>';
				} else {
					echo null;
				}
				?>
				<li>
					<a href="#">
						<img loading="lazy" src="./images/language.png" height="38" width="38" alt="lang" />
						<div id="google_translate_element"></div>
					</a>
				</li>

				<li>
					<a onclick="openDropDown()" class="dropbtn" href="#">
						<?php
						$image_url = $fetch_info['photo'];
						if (strpos($image_url, 'https://') === 0) {
							echo '<img class="dropbtn" alt="user"  src="' . $image_url . '">';
						} else {
							echo '<img class="dropbtn" alt="user" src="../uploaded_img/' . $image_url . '">';
						}
						?>
					</a>
					<div id="myDropdown" class="dropdown-content">
						<?php if ($userRole == 'admin') {
							echo null;
						} else {
							echo '<a href="../update_profile.php">Update Profile</a>';
						} ?>
						<?php if ($userRole == 'admin') {
							echo null;
						} else {
							echo '<a href="#" onclick="confirmDelete()">Delete Account</a>';
						} ?>

						<a href="../logout-user.php">Logout</a>
						<?php
						$userRole = $fetch_info['role'];
						if ($userRole == 'admin') {
							echo '<a href="./admin/admin.php">Admin Dashboard</a>';
						} elseif ($userRole == 'creator') {
							echo '<a href="./admin/creators.php">Contribute </a>';
						} else {
							echo null;
						}
						?>
					</div>
				</li>
			</ul>
		</nav>
	</header>
	<!-- Header End -->

	<main style="padding-top: 80px;">
		<center style=" font-family: 'Bruno Ace', cursive;">
			<h1 style="font-size: 20px; width: 80%; padding: 40px 0px; color: rgb(73 71 71); line-height: 24px;">
			This is a great reference for anyone who wants to learn medical terminology, covering all the important terms.
			</h1>
			<h1><b style="color: rgb(73 71 71); text-transform: capitalize; font-size:30px">Medical Health Dictionary :
					<?= "<b>" . mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM words_collection"))["total"] . " words</b>"; ?>
				</b></h1>
		</center>
		<div class="containerfaq">
			<input type="text" id="search-bar" placeholder="Search here..." />

			<?php
			// Connect to the MySQL database
			$con = mysqli_connect("localhost", "root", "", "elearn");

			// Sanitize and set the sorting column
			$sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : 'title';

			// Sanitize and set the sorting order
			$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

			// Set the number of records to display per page
			$records_per_page = 200;

			// Get the current page number from the query string
			$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

			// Calculate the offset for the SQL query
			$offset = ($current_page - 1) * $records_per_page;

			// Generate the sorting links/buttons
			echo '<div style="padding-bottom: 20px; text-align: center;">';
			echo '<p style="font-size: 20px; border: 2px solid green; display: inline-block; border-radius: 8px; padding: 10px; margin: 0;">';
			echo 'Sort by:';
			echo '<a style="padding: 10px;" href="?sort=likes&order=desc&page=' . $current_page . '">Likes(&uarr;)</a>|';
			echo '<a style="padding: 10px;" href="?sort=views&order=desc&page=' . $current_page . '">Views(&uarr;)</a>|';
			echo '<a style="padding: 10px;" href="?sort=title&order=desc&page=' . $current_page . '">Name(&uarr;)</a>|';
			echo '<a style="padding: 10px;" href="?sort=date&order=asc&page=' . $current_page . '">Date(&darr;)</a>';
			echo '<a style="padding: 10px;" href="?sort=date&order=desc&page=' . $current_page . '">Date(&uarr;)</a>|';
			echo '</p>';
			echo '</div>';

			echo '<style>@media (max-width: 768px) { p { font-size: 16px; } a { display: block; padding: 5px; } }</style>';

			// Build the SQL query using the selected sorting method and order
			$sql = "SELECT * FROM words_collection ORDER BY $sort $order LIMIT $offset, $records_per_page";

			// Execute the SQL query
			$result = mysqli_query($con, $sql);

			// Initialize the $index variable
			$index = ($current_page - 1) * $records_per_page + 1;

			// Output HTML elements dynamically based on data
			echo '<div class="dictionary">';
			while ($row = mysqli_fetch_assoc($result)) {
				// Display the item with the unique id
				$html = '<div class="dictionary-item" id="title-' . $row["id"] . '">';

				$html .= '<button aria-expanded="false" onclick="incrementViews(' . $row["id"] . ')">';
				$html .= '<p class="title" >';
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

				// Fetch all comments for the current row
				$sqlc = "SELECT * FROM comments WHERE id = " . $row["id"];
				$resultc = mysqli_query($con, $sqlc);

				while ($rowc = mysqli_fetch_assoc($resultc)) {
					if ($rowc["descriptionc"] != "") {
						$html .= '<hr><div><span style="background: #a6a6ad;padding: 2px;font-size:18px;">By: ' . $rowc['namec'] . '</span> <p style="font-size:18px;display:inline;"> :- ' . $rowc["descriptionc"] . '</p></div>';
					}
				}

				$html .= '</h1>';

				// Display the form to add a new comment
				$html .= '<hr><center><form id="commentForm-' . $row["id"] . '" onsubmit="event.preventDefault();">';
				$html .= '<input id="titleid-' . $row["id"] . '" name="titleid" hidden value="' . $row["id"] . '"/>';
				$html .= '<input id="namec-' . $row["id"] . '" type="text" name="namec" hidden value="' . $fetch_info['name'] . '"/>';
				$html .= '<input id="titlec-' . $row["id"] . '" type="text" name="titlec" hidden value="' . $row['title'] . '"/>';
				$html .= '<textarea id="descriptionc-' . $row["id"] . '" rows="10" style="width: 50%;" type="text" name="descriptionc" placeholder="Add your comment..." required></textarea>';
				$html .= '<button id="submitComments-' . $row["id"] . '" style="width: 150px !important;padding: 10px;color: white;border-radius: 8px;margin-bottom: 10px;background: blue !important;text-align: center;" type="button" onclick="submitComment(' . $row["id"] . ')">Send Comment</button>';
				$html .= '</form></center>';

				$html .= '</div>';
				$html .= '</div>';

				echo $html;

				// Increment the index after each iteration
				$index++;
			}

			echo '</div>';

			// Build the pagination links
			$sql = "SELECT COUNT(*) AS count FROM words_collection";
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_assoc($result);
			$total_records = $row["count"];
			$total_pages = ceil($total_records / $records_per_page);

			echo '<div class="pagination">';
			if ($current_page > 1) {
				echo '<a href="' . $_SERVER["PHP_SELF"] . '?sort=' . $sort . '&order=' . $order . '&page=' . ($current_page - 1) . '">&laquo; Prev</a>';
			}

			$start_page = max(1, $current_page - 2);
			$end_page = min($start_page + 5, $total_pages);

			if ($start_page > 1) {
				echo '<a href="' . $_SERVER["PHP_SELF"] . '?sort=' . $sort . '&order=' . $order . '&page=1" class="pagination-link">1</a>';
				if ($start_page > 3) {
					echo '<span class="pagination-dots">&hellip;</span>';
				}
			}

			for ($i = $start_page; $i <= $end_page; $i++) {
				if ($i == $current_page) {
					echo '<span class="current">' . $i . '</span>';
				} else {
					echo '<a href="' . $_SERVER["PHP_SELF"] . '?sort=' . $sort . '&order=' . $order . '&page=' . $i . '" class="pagination-link">' . $i . '</a>';
				}
			}

			if ($end_page < $total_pages) {
				if ($end_page < $total_pages - 1) {
					echo '<span class="pagination-dots">&hellip;</span>';
				}
				echo '<a href="' . $_SERVER["PHP_SELF"] . '?sort=' . $sort . '&order=' . $order . '&page=' . $total_pages . '" class="pagination-link">' . $total_pages . '</a>';
			}

			if ($current_page < $total_pages) {
				echo '<a href="' . $_SERVER["PHP_SELF"] . '?sort=' . $sort . '&order=' . $order . '&page=' . ($current_page + 1) . '">Next &raquo;</a>';
			}

			echo '</div>';


			echo '</div>';
			?>

		</div>
	</main>
	<!-- ------------------- scroll up btn -----------------  -->
	<div id="scrollup" title="Go to top" onclick="document.documentElement.scrollTop = 0;">&xutri;</div>
	<!-- ------------ end -------------  -->

	<script src="./js/mycustom.js"></script>
</body>

</html>