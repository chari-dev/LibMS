<?php
session_start();

// Function to validate login
function validateLogin($conn)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require 'dbh.inc.php';

        $memberNumber = $_POST['member_number'];
        $password = $_POST['password'];

        // Validate and sanitize the form data (you can add more validation as needed)
        $errors = [];

        // Member number validation (you can customize this validation as needed)
        if (empty($memberNumber)) {
            $errors[] = "Member number is required.";
        } elseif (!preg_match('/^\d+$/', $memberNumber)) {
            $errors[] = "Member number should contain only digits.";
        }

        // Password validation (you can customize this validation as needed)
        if (empty($password)) {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password should be at least 6 characters long.";
        }

        if (count($errors) === 0) {
            // Login successful (you can query the database to validate the login credentials)
            $sql = "SELECT 'id','password' FROM `member` WHERE `id` = '$memberNumber' AND `password` = '$password'";
            $query = mysqli_query($conn, $sql);

            if ($query && mysqli_num_rows($query) === 1) {
                // Valid login credentials
                $_SESSION['member_number'] = $memberNumber;
                header('Location: member/dashboard.php'); // Redirect to the member dashboard
                exit();
            } else {
                $errors[] = "Invalid login credentials.";
            }
        }

        return $errors; // Return the errors array
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Login</title>
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
        <h1>Library Login</h1>
        <?php
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
            <label for="member_number">Member Number:</label>
            <input type="text" id="member_number" name="member_number" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>

        <p>Not a member yet? <a href="member/registration.php">Click here to become a member</a>.</p>
    </div>
</body>
</html>
