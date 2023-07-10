<?php

session_start();


if (!isset($_SESSION['employee_ID'])) {
    
    header("Location: login.php");
    exit();
}


$employeeID = $_SESSION['employee_ID'];




$stmt = $conn->prepare("SELECT * FROM employee WHERE employee_ID = ?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows === 1) {
    $employee = $result->fetch_assoc();
} else {
  
}


$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo $employee['employee_Name']; ?>!</h1>
    <p>Employee ID: <?php echo $employee['employee_ID']; ?></p>
    <p>Email: <?php echo $employee['employee_email']; ?></p>
    <p>Branch: <?php echo $employee['employee_branch']; ?></p>

    

    <a href="logout.php">Logout</a> 

    <script src="script.js"></script>
</body>
</html>