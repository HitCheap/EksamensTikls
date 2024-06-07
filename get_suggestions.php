<?php
include 'database.php';

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
    $sql .= "lietotājvārds LIKE '%$keyword%'";
}
$sql .= " LIMIT 5";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Make the suggestions clickable
        echo "<div class='suggestion' onclick=\"openProfile('{$row['lietotājvārds']}')\">" . $row['lietotājvārds'] . "</div>";
    }
} else {
    echo "No suggestions found";
}

$conn->close();
?>
