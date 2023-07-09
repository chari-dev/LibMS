<?php

// Retrieve the form data
$email = $_POST['email'];
$password = $_POST['password'];


$stmt = $conn->prepare("SELECT * FROM employee WHERE employee_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

// Get the result
$result = $stmt->get_result();


if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $storedPassword = $row['employee_password'];

    // Verify the password
    if (password_verify($password, $storedPassword)) {
        
        echo "Login successful! Welcome, " . $row['employee_Name'] . ".";
      
    } else {
        
        echo "Incorrect password.";
       
    }
} else {
   
    echo "No user found with that email address.";
   
}


$stmt->close();
$conn->close();
?>