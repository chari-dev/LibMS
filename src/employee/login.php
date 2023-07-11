<?php
session_start();
if (isset($_SESSION['employee_ID'])) {
    header("Location: dashboard.php"); // Redirect to signup page
    exit();
}

// Function to validate login
function validateLogin($conn)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require '../dbh.inc.php';

        $employee_ID = $_POST['employee_ID'];
        $password = $_POST['password'];

        // Validate and sanitize the form data (you can add more validation as needed)
        $errors = [];

        // Member number validation (you can customize this validation as needed)
        if (empty($employee_ID)) {
            $errors[] = "Member number or Username is required.";
        }

        // Password validation (you can customize this validation as needed)
        if (empty($password)) {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password should be at least 6 characters long.";
        }


       if (count($errors) === 0) {
            $sql = "SELECT employee_ID, employee_password FROM employee WHERE employee_ID = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                $errors[] = "Error Reaching Server; Refresh the page!";
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "i", $employee_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($result)){
                    $pwdCheck = password_verify($password, $row['employee_password']);
                    if ($pwdCheck == false) {
                        $errors[] = "Invalid login credentials.";
                        exit();
                    } else if ($pwdCheck == true){
                        $_SESSION['employee_ID'] = $row['employee_ID'];
                        header('Location: dashboard.php');
                        exit();
                    }
                }
        }
    }
        return $errors; // Return the errors array
    }
}

require '../dbh.inc.php';

$errors = validateLogin($conn); // Call the function and assign the errors array
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Login</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Library Employee Login</h1>
        <?php
        require '../dbh.inc.php';

        $errors = validateLogin($conn); // Call the function and assign the errors array
        if (!empty($errors)) {
            echo '<div class="error">';
            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>'; // Display each error message
            }
            echo '</div>';
        }
        ?>
        <form method="POST" action="">
            <label for="employee_ID">Employee Number: </label>
            <input type="text" id="employee_ID" name="employee_ID" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>

        <p>Not an Employee <a href="../index.php">Click here to Login as a member</a>.</p>
    </div>
</body>
</html>
