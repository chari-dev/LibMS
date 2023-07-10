<?php
// Assuming you have a database connection established

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve the form data
    $memberID = $_POST['memberID'];
    $memberName = $_POST['memberName'];
    $memberAddress = $_POST['memberAddress'];
    $contactDetails = $_POST['contactDetails'];
    $membershipType = $_POST['membershipType'];

    // Prepare and execute the query to update member information
    $stmt = $conn->prepare("UPDATE LibraryMember SET member_Name = ?, member_Address = ?, contact_Details = ?, membership_Type = ? WHERE member_ID = ?");
    $stmt->bind_param("ssssi", $memberName, $memberAddress, $contactDetails, $membershipType, $memberID);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Member information updated successfully.";
    } else {
        echo "Failed to update member information.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Member Information</title>
    <!-- Add your CSS styling or include CSS file -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Update Member Information</h1>

    <!-- Add your update form here -->
    <form method="POST" action="memberupdate.php">
        <label for="memberID">Member ID:</label>
        <input type="text" name="memberID" id="memberID" required><br>
        <label for="memberName">Member Name:</label>
        <input type="text" name="memberName" id="memberName" required><br>
        <label for="memberAddress">Member Address:</label>
        <input type="text" name="memberAddress" id="memberAddress" required><br>
        <label for="contactDetails">Contact Details:</label>
        <input type="text" name="contactDetails" id="contactDetails" required><br>
        <label for="membershipType">Membership Type:</label>
        <input type="text" name="membershipType" id="membershipType" required><br>
        <input type="submit" name="submit" value="Update">
    </form>

    <!-- Add additional HTML content or scripts as needed -->
</body>
</html>