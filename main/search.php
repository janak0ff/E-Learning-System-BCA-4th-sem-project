<?php
// Connect to the MySQL database
$con = mysqli_connect("localhost", "root", "", "medicalhealth");

// Sanitize and set the search query
$q = isset($_POST['q']) ? mysqli_real_escape_string($con, $_POST['q']) : '';

// Build the SQL query to search for pages with the given title
$sql = "SELECT id FROM medical_health WHERE title LIKE '%$q%'";

// Execute the SQL query
$result = mysqli_query($con, $sql);

// Build an array of the IDs of the matching pages
$ids = array();
while ($row = mysqli_fetch_assoc($result)) {
    $ids[] = $row['id'];
}

// Return the array as a JSON response
header('Content-Type: application/json');
echo json_encode($ids);
?>

<script>
    function searchDictionary() {
        const query = searchBar.value.toLowerCase();
        const dictionaryItems = document.querySelectorAll('.dictionary-item');

        if (query.length > 2) {
            // Send a POST request to search.php to search for pages with the given title
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'search.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const results = JSON.parse(xhr.responseText);

                    // Display the search results
                    dictionaryItems.forEach(item => {
                        const id = item.getAttribute('id').substr(6);
                        const title = item.querySelector('.title').textContent.toLowerCase();

                        if (results.includes(id) || title.includes(query)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }
            };
            xhr.send('q=' + encodeURIComponent(query));
        } else {
            // Display all items if the query is too short
            dictionaryItems.forEach(item => {
                item.style.display = 'block';
            });
        }
    }
</script>