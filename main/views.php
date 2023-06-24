<?php require "../controllerUserData.php"; ?>
<?php
// Increment views count for the corresponding item
if (isset($_GET["id"])) {
	$id = $_GET["id"];
	$sql = "UPDATE medical_health SET views=views+1 WHERE id=$id";
	$con->query($sql);

	// Get updated views count
	$sql = "SELECT views FROM medical_health WHERE id=$id";
	$result = $con->query($sql);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		echo $row["views"];
	}
}
?>