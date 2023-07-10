<?php
// Assuming you have a database connection established

// Check if the form is submitted
if (isset($_POST['search'])) {
    // Retrieve the search query from the form
    $searchQuery = $_POST['searchQuery'];

    // Prepare and execute the query to search for members
    $stmt = $conn->prepare("SELECT * FROM LibraryMember WHERE member_Name LIKE ? OR member_ID LIKE ?");
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();
    
    // Check if any results are found
    if ($result->num_rows > 0) {
        // Loop through the results and display member information
        while ($row = $result->fetch_assoc()) {
            echo "Member ID: " . $row['member_ID'] . "<br>";
            echo "Name: " . $row['member_Name'] . "<br>";
            echo "Address: " . $row['member_Address'] . "<br>";
            echo "Contact Details: " . $row['contact_Details'] . "<br>";
            echo "Membership Type: " . $row['membership_Type'] . "<br>";
            echo "Borrowing History: " . $row['borrowing_History'] . "<br>";
            echo "<hr>";
        }
    } else {
        echo "No results found.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Member Search</title>
    <!-- Add your CSS styling or include CSS file -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Member Search</h1>

    <!-- Add your search form here -->
    <form method="POST" action="membersearch.php">
        <input type="text" name="searchQuery" placeholder="Enter search query">
        <input type="submit" name="search" value="Search">
    </form>

    <!-- Add your search results display area here -->
</body>
</html>