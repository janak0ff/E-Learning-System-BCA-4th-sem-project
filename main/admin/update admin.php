<!DOCTYPE html>
<html>

<head>
    <title>Edit record</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 300px;
            background-color: #f0f0f0;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 300px;
            background-color: #f0f0f0;
            border-radius: 10px;
            padding: 20px;
            width: 75%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        label {
            margin-bottom: 8px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
</head>

<body>
    <?php
    // Connect to the MySQL database
    $conn = mysqli_connect("localhost", "root", "", "medicalhealth");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve the user record with the specified ID
    $id = mysqli_real_escape_string($conn, $_GET["id"]);
    $query = "SELECT * FROM words_collection WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Close the MySQL connection
    mysqli_close($conn);
    ?>
    <form method="post" action="update_action_admin.php">
        <h1>Edit Record</h1>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row["id"]); ?>">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($row["title"]); ?>">
        <label>Description:</label>
        <input type="text" name="description" value="<?php echo htmlspecialchars($row["description"]); ?>">
        <input type="submit" value="Save Changes">
    </form>
</body>

</html>