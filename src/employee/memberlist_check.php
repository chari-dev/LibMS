<?php

// Prepare and execute the query to fetch all members
$stmt = $conn->prepare("SELECT * FROM LibraryMember");
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
    echo "No members found.";
}

// Close the database connection
$stmt->close();
$conn->close();
?>