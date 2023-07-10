<?php

session_start();
if (!isset($_SESSION['employee_ID'])) {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

$employeeID = $_SESSION['employee_ID'];


$stmt = $conn->prepare("SELECT b.branch_ID, b.branch_Name, b.branch_Address 
                        FROM employee_branches eb 
                        INNER JOIN branch b ON eb.branch_ID = b.branch_ID 
                        WHERE eb.employee_ID = ?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if any branches are managed by the employee
if ($result->num_rows > 0) {
    // Loop through the results and display branch information
    while ($row = $result->fetch_assoc()) {
        echo "Branch ID: " . $row['branch_ID'] . "<br>";
        echo "Branch Name: " . $row['branch_Name'] . "<br>";
        echo "Branch Address: " . $row['branch_Address'] . "<br>";
        echo "<hr>";
    }
} else {
    echo "No branches managed by this employee.";
}


$stmt->close();
$conn->close();
?>