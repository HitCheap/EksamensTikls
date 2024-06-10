<?php
// Include database connection code here
include 'datubaze.php';
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
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
        $sql = "SELECT * FROM lietotaji WHERE lietotājvārds LIKE ? AND statuss != 'Deaktivizēts' LIMIT 10";
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
