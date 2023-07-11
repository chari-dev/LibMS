<?php
// Start session
session_start();
if ($_SESSION['employee_ID'] == 123456) {
} elseif (isset($_SESSION['employee_ID'])){
    header("Location: dashboard.php"); 
    exit();
}
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../dbh.inc.php';

    $employee_ID = $_POST['employee_ID'];
    $employee_Name = $_POST['employee_Name'];
    $employee_phone = $_POST['employee_phone'];
    $employee_email = $_POST['employee_email'];
    $employee_password = $_POST['employee_password'];
    $employee_branch = $_POST['employee_branch'];

    $errors = [];

    if (count($errors) === 0) {
        $sql_nodouble = "SELECT 'employee_ID' FROM `employee` WHERE `employee_ID` = ?"; //`id` = '$memberNumber'";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql_nodouble)) {
            $errors[] = "Error Reaching Server; Refresh the page!";
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "i", $employee_ID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck === 1) {
                $errors[] = "Member Number already exists.";
                exit();
            } 
            else {
                $sql = "INSERT INTO `employee` (`employee_ID`, `employee_Name`, `employee_phone`, `employee_email`, `employee_password`, `employee_branch`) VALUES (?, ?, ?, ?, ?, ?)"; //('$memberNumber', '$name', '$employee_Name', '$email', '$password')
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    $errors[] = "Error Reaching Server; Refresh the page!";
                    exit();
                } else {
                    $hashedpwd = password_hash($employee_password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "issssi", $employee_ID, $employee_Name, $employee_phone, $employee_email, $hashedpwd, $employee_branch);
                    mysqli_stmt_execute($stmt);
                    $_SESSION['employee_ID'] = $employee_ID;
                    $_SESSION['employee_Name'] = $employee_Name;
                    header('Location: dashboard.php');
                    exit();
                }
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
    }
}
// else {
//     header('Location: ../index.php');
//     exit();
// }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: #f00;
            margin-top: 10px;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: #4caf50;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .history-button {
            display: inline-block;
            background-color: #4caf50;
            color: #fff;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create an employee account</h1>

        <?php if (isset($errors) && count($errors) > 0): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="employee_ID">Employee ID:</label>
            <input type="text" id="employee_ID" name="employee_ID" required>

            <label for="employee_Name">Employee Name:</label>
            <input type="text" id="employee_Name" name="employee_Name" required>

            <label for="employee_phone">Employee phone:</label>
            <input type="text" id="employee_phone" name="employee_phone" required>
        
            <label for="employee_password">Employee Password:</label>
            <input type="password" id="employee_password" name="employee_password" required>

            <label for="employee_email">Employee Email:</label>
            <input type="text" id="employee_email" name="employee_email" required>

            <label for="employee_branch">Employee Branch:</label>
            <select name="employee_branch" id="employee_branch" required>
                <option value="100001">Northridge LibMS</option>
                <option value="100002">Lakeword LibMS</option>
                <option value="100003">Willford LibMS</option>
                <option value="100004">Timmer LibMS</option>
                <option value="100005">Lifton LibMS</option>
            </select>
            <br>
            <input type="submit" value="Register">
        </form>
        <a class="history-button" href="dashboard.php">Go To Employee Dashboard</a>
    </div>
</body>
</html>
