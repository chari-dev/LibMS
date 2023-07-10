<?php
// Assuming you have a database connection established

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve the form data
    $bookID = $_POST['bookID'];
    $bookName = $_POST['bookName'];
    $bookAuthor = $_POST['bookAuthor'];
    $publicationDate = $_POST['publicationDate'];
    $availableCopies = $_POST['availableCopies'];

    // Prepare and execute the query to update book information
    $stmt = $conn->prepare("UPDATE Book SET book_Name = ?, book_Author = ?, publication_date = ?, available_copies = ? WHERE book_ID = ?");
    $stmt->bind_param("sssii", $bookName, $bookAuthor, $publicationDate, $availableCopies, $bookID);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Book information updated successfully.";
    } else {
        echo "Failed to update book information.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Book Information</title>
    <!-- Add your CSS styling or include CSS file -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Update Book Information</h1>

    <!-- Add your update form here -->
    <form method="POST" action="updatebook.php">
        <label for="bookID">Book ID:</label>
        <input type="text" name="bookID" id="bookID" required><br>
        <label for="bookName">Book Name:</label>
        <input type="text" name="bookName" id="bookName" required><br>
        <label for="bookAuthor">Book Author:</label>
        <input type="text" name="bookAuthor" id="bookAuthor" required><br>
        <label for="publicationDate">Publication Date:</label>
        <input type="text" name="publicationDate" id="publicationDate" required><br>
        <label for="availableCopies">Available Copies:</label>
        <input type="text" name="availableCopies" id="availableCopies" required><br>
        <input type="submit" name="submit" value="Update">
    </form>

    <!-- Add additional HTML content or scripts as needed -->
</body>
</html>