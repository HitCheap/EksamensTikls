<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'majaslapa';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Get the search query
$q = $_GET['q'];

// Split the search query into separate words
$keywords = explode(' ', $q);

// Construct the SQL query dynamically to search for each keyword separately
$sql = "SELECT * FROM lietotaji WHERE ";
foreach ($keywords as $key => $keyword) {
    if ($key > 0) {
        $sql .= " OR ";
    }
    $sql .= "vards LIKE '%$keyword%' OR uzvards LIKE '%$keyword%'";
}
$sql .= " LIMIT 5";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Make the suggestions clickable
        echo "<div class='suggestion' onclick=\"openProfile('{$row['vards']} {$row['uzvards']}')\">" . $row['vards'] . ' ' . $row['uzvards'] . "</div>";
    }
} else {
    echo "No suggestions found";
}

$conn->close();
?>

<script>
    // Function to open profile page when suggestion is clicked
    function openProfile(username) {
        window.location.href = 'profile.php?username=' + encodeURIComponent(username);
    }
</script>
