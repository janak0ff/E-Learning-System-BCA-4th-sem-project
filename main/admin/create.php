<!-- create.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Add New Record</title>
</head>

<body>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Add New Record</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            form {
                display: flex;
                flex-direction: column;
                align-items: left;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                background-color: #f8f8f8;
                box-shadow: 0px 0px 10px #ccc;
            }

            input[type="text"],
            input[type="file"],
            input[type="submit"] {
                margin: 10px;
                padding: 5px;
                border-radius: 5px;
                border: 1px solid #ccc;
                font-size: 16px;
                width: 100%;
                max-width: 500px;
                box-sizing: border-box;
            }

            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <form method="post" action="create_action.php">
            <h1>Add New Record</h1>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>
            <label for="description">Description:</label>
            <textarea type="text" rows="10" name="description" id="description" required></textarea>
            <input type="submit" value="Add Record">
        </form>
    </body>

    </html>
</body>

</html>