<?php
// Start session
session_start();

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../dbh.inc.php';

    $memberNumber = $_POST['member_number'];
    $phone = $_POST['phone'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate and sanitize the form data (you can add more validation as needed)
    $errors = [];

    // Member number validation (you can customize this validation as needed)
    if (empty($memberNumber)) {
        $errors[] = "Member number is required.";
    } elseif (!preg_match('/^\d+$/', $memberNumber)) {
        $errors[] = "Member number should contain only 6 digits.";
    }

    // Name validation (you can customize this validation as needed)
    if (empty($name)) {
        $errors[] = "Username is required.";
    }
    
    // Member phone validation (you can customize this validation as needed)
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match('/^\d+$/', $phone) && strlen($phone) < 7) {
        $errors[] = "Phone number should contain only 6 digits.";
    }
    
    // Email validation (you can customize this validation as needed)
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    // Password validation (you can customize this validation as needed)
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password should be at least 6 characters long.";
    }

    if (count($errors) === 0) {
        // Registration successful (you can process the data and store it in a database)
        // In this example, we are storing the data in a session variable
        $sql_nodouble = "SELECT 'id' FROM `member` WHERE `id` = ? OR 'name' = ?"; //`id` = '$memberNumber'";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql_nodouble)) {
            $errors[] = "Error Reaching Server; Refresh the page!";
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "is", $memberNumber, $name);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck === 1) {
                $errors[] = "Member Number already exists.";
                exit();
            } 
            else {
                $sql = "INSERT INTO `member` (`id`, `name`, `phone`, `email`, `password`) VALUES (?, ?, ?, ?, ?)"; //('$memberNumber', '$name', '$phone', '$email', '$password')
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    $errors[] = "Error Reaching Server; Refresh the page!";
                    exit();
                } else {
                    $hashedpwd = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "isiss", $memberNumber, $name, $phone, $email, $hashedpwd);
                    mysqli_stmt_execute($stmt);
                    $_SESSION['member_number'] = $memberNumber;
                    $_SESSION['name'] = $name;
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
    <title>Library Registration</title>
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
        <h1>Library Registration</h1>

        <?php if (isset($errors) && count($errors) > 0): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="member_number">Member Number:</label>
            <input type="text" id="member_number" name="member_number" required>

            <label for="name">Username:</label>
            <input type="text" id="name" name="name" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
        
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>


            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>

            <input type="submit" value="Register">
        </form>

        <p>Already a member? <a href="../index.php">Click here to LogIn</a>.</p>
    </div>
</body>
</html>
