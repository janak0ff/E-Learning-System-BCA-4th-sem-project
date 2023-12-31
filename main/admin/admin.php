<!-- admin.php -->
<?php require "../../controllerUserData.php"; ?>

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
    header('Location: ../../login-user.php');
}

if ($fetch_info['role'] != 'admin') {
    header('Location: ../../login-user.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pannel - Medical Health</title>
    <link rel="shortcut icon" href="../images/favicon.webp" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Itim&family=Pangolin&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Itim', cursive;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #999;
        }

        th:last-child,
        td:last-child {
            border-right: none;
        }

        th {
            background-color: #f2f2f2;
            border-right: 1px solid #999;
        }

        .actions {
            white-space: nowrap;
        }

        .edit-btn {
            background-color: blue;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 8px;
        }

        .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .add-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .add-btn:hover {
            background-color: #3e8e41;
        }

        .row-count {
            font-size: 18px;
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: #f1f1f1;
            margin-left: 8px;
            width: 200px;
        }

        .search-bar input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
            background-color: white;
        }
    </style>

</head>

<body>
    <div
        style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding: 15px;">
        <h1>Medical Health Admin Panel</h1>
        <a href="../index.php"
            style="margin-left: auto; background-color: #4CAF50; font-size: 25px; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 4px; border: none;">Go
            Home</a>
    </div>
    <center>
        <h1>Members List</h1>
    </center>
    <div class="header">
        <div class="row-count">
            <?php
            // Connect to the MySQL database
            $conn = mysqli_connect("localhost", "root", "", "elearn");

            // Retrieve the total number of rows from the "usertable" table
            $query = "SELECT COUNT(*) as total FROM usertable";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            echo "<p>Total Members: <b>" . $row["total"] . "</b></p>";

            // Close the MySQL connection
            mysqli_close($conn);
            ?>
        </div>
        <div class="search-bar">
            <input type="text" id="search-inputm" placeholder="Search...">
        </div>
    </div>


    <br>
    <table id="tablem">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to the MySQL database
            $conn = mysqli_connect("localhost", "root", "", "elearn");

            // Retrieve data from the "usertable" table
            // $query = "SELECT * FROM usertable";
            // $result = mysqli_query($conn, $query);
            // if (mysqli_num_rows($result) > 0) {
            //     while ($row = mysqli_fetch_assoc($result)) {
            //         echo "<tr>";
            //         echo "<td>" . $row["id"] . "</td>";
            //         echo "<td>" . $row["name"] . "</td>";
            //         echo "<td>" . $row["email"] . "</td>";
            //         echo "<td>" . $row["status"] . "</td>";
            //         echo "<td class='actions'>";
            //         echo "<form action='deleteMember.php' method='post' style='display: inline-block;'>";
            //         echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
            
            //         echo "<button type='submit' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</button>";
            //         echo "</form>";
            //         echo "</td>";
            //         echo "</tr>";
            //     }
            // } else {
            //     echo "No records found.";
            // }
            //////////////////////////
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                // Retrieve data from the "usertable" table
                $query = "SELECT * FROM usertable";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td class='actions'>";

                        // Check if the user has the permission to edit or delete the record
                        if ($email != $row["email"]) {
                            echo "<form action='deleteMember.php' method='post' style='display: inline-block;'>";
                            echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                            echo "<button type='submit' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</button>";
                            echo "</form>";
                        } else {
                            echo "You can't delete yourself, <br> <b style='color:red;font-size:20px;'>" . $row["name"] . '</b>  from here..';
                        }

                        echo "</td>";
                        echo "</tr>";


                    }
                } else {
                    echo "No records found.";
                }
            } else {
                echo "Please log in to view this page.";
            }

            // Close the MySQL connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
    <script>
        function confirmDeleteUser() {
            if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                window.location = "../delete-user.php";
            }
        }
    </script>
    <br><br><br>
    <hr>
    <br><br><br>











    <center>
        <h1>contacts</h1>
    </center>
    <div class="header">
        <div class="row-count">
            <?php
            // Connect to the MySQL database
            $conn = mysqli_connect("localhost", "root", "", "elearn");

            // Retrieve the total number of rows from the "usertable" table
            $query = "SELECT COUNT(*) as total FROM contact";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            echo "<p>Total contact: <b>" . $row["total"] . "</b></p>";

            // Close the MySQL connection
            mysqli_close($conn);
            ?>
        </div>
        <div class="search-bar">
            <input type="text" id="search-inputc" placeholder="Search...">
        </div>
    </div>


    <br>
    <table id="tablec">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to the MySQL database
            $conn = mysqli_connect("localhost", "root", "", "elearn");

            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                // Retrieve data from the "usertable" table
                $query = "SELECT * FROM contact";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td class='actions'>";
                        echo "<form action='deleteContact.php' method='post' style='display: inline-block;'>";
                        echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                        echo "<button type='submit' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "<td>" . $row["message"] . "</td>";
                        echo "</tr>";


                    }
                } else {
                    echo "No records found.";
                }
            } else {
                echo "Please log in to view this page.";
            }

            // Close the MySQL connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>









<br><br>
    <center>
        <h1>Contents</h1>
    </center>
    <div class="header">
        <a href="create admin.php" class="add-btn">Add New Record</a>
        <div class="row-count">
            <?php
            // Connect to the MySQL database
            $conn = mysqli_connect("localhost", "root", "", "elearn");


            // Retrieve the total number of rows from the "words_collection" table
            $query = "SELECT COUNT(*) as total FROM words_collection";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            echo "<p>Total Rrecords: <b>" . $row["total"] . "</b></p>";

            // Close the MySQL connection
            mysqli_close($conn);
            ?>
        </div>
        <div class="search-bar">
            <input type="text" id="search-input-tableContent" placeholder="Search...">
        </div>
    </div>


    <br>
    <table id="tableContent">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Create by</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to the MySQL database
            $conn = mysqli_connect("localhost", "root", "", "elearn");


            // Retrieve data from the "words_collection" table
            $query = "SELECT * FROM words_collection";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td>" . $row["creators"] . "</td>";
                    echo "<td class='actions'>";
                    echo "<form action='update admin.php' method='get' style='display: inline-block'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<button type='submit' class='edit-btn'>Edit</button>";
                    echo "</form>";
                    echo "<form action='delete admin.php' method='post' style='display: inline-block;'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    // echo "<button type='submit' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</button>";
                    echo "<button type='submit' class='delete-btn'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "No records found.";
            }

            // Close the MySQL connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <script>
        // Get the search input element
        const searchInput = document.getElementById('search-input-tableContent');
        // Get the table element
        const table = document.getElementById('tableContent');
        // Get all the rows in the table body
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        // Add an event listener to the search input element
        searchInput.addEventListener('input', function (event) {
            const searchText = event.target.value.toLowerCase();
            for (let i = 0; i < rows.length; i++) {
                const title = rows[i].getElementsByTagName('td')[1].textContent.toLowerCase();
                if (title.includes(searchText)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    </script>
    <script>
        // Get the search input element
        const searchInputm = document.getElementById('search-inputm');
        // Get the table element
        const tablem = document.getElementById('tablem');
        // Get all the rows in the table body
        const rowsm = tablem.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        // Add an event listener to the search input element
        searchInputm.addEventListener('input', function (event) {
            const searchText = event.target.value.toLowerCase();
            for (let i = 0; i < rowsm.length; i++) {
                const title = rowsm[i].getElementsByTagName('td')[1].textContent.toLowerCase();
                if (title.includes(searchText)) {
                    rowsm[i].style.display = '';
                } else {
                    rowsm[i].style.display = 'none';
                }
            }
        });
    </script>
        <script>
        // Get the search input element
        const searchInputC = document.getElementById('search-inputc');
        // Get the table element
        const tableC = document.getElementById('tablec');
        // Get all the rows in the table body
        const rowsc = tableC.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        // Add an event listener to the search input element
        searchInputC.addEventListener('input', function (event) {
            const searchText = event.target.value.toLowerCase();
            for (let i = 0; i < rowsc.length; i++) {
                const title = rowsc[i].getElementsByTagName('td')[1].textContent.toLowerCase();
                if (title.includes(searchText)) {
                    rowsc[i].style.display = '';
                } else {
                    rowsc[i].style.display = 'none';
                }
            }
        });
    </script>
</body>

</html>