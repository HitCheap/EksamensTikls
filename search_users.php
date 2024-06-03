<?php
// Include database connection code here
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <style>
       /* Add custom CSS to adjust the position */
       .search-container {
            position: fixed;
            top: 150px; /* Adjust this value to set the distance from the bottom */
            left: 10%;
            transform: translateX(-50%);
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        #suggestions {
            max-height: 200px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid #ccc;
            border-top: none;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            margin-top: 5px; /* Adjust this value to set the distance between input and results */
        }

        #suggestions p {
            padding: 5px;
            cursor: pointer;
        }

        #suggestions p:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<div class="search-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <input type="text" name="query" id="searchInput" placeholder="Search for users" onkeyup="showSuggestions(this.value)">
        <div id="suggestions"></div>
        <button type="submit">Search</button>
    </form>

    <?php
    // Check if search query is set
    if (isset($_GET['query'])) {
        // Sanitize search query
        $searchQuery = htmlspecialchars($_GET['query']);

        // Perform database query to search for users
        $sql = "SELECT * FROM lietotaji WHERE lietotājvārds LIKE ? LIMIT 10";
        $stmt = $conn->prepare($sql);
        $searchParam = "%{$searchQuery}%";
        $stmt->bind_param('s', $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display search results
        if ($result->num_rows > 0) {
            echo '<div id="searchResults">';
            while ($row = $result->fetch_assoc()) {
                echo '<p onclick="openProfile(\'' . $row['lietotājvārds'] . '\')">' . $row['lietotājvārds'] . '</p>';
            }
            echo '</div>';
        } else {
            echo '<p>No users found.</p>';
        }

        $stmt->close();
    }
    ?>
</div>

<!-- JavaScript to Fetch Suggestions -->
<script>
function showSuggestions(str) {
    if (str.length === 0) {
        document.getElementById("suggestions").innerHTML = "";
        return;
    } else {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById("suggestions").innerHTML = this.responseText;
            }
        };
        xhr.open("GET", "get_suggestions.php?q=" + str, true);
        xhr.send();
    }
}

function openProfile(username) {
    // Redirect to profile.php with the selected username
    window.location.href = "profile.php?username=" + username;
}
</script>
</body>
</html>
