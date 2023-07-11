<?php
session_start();
if (!isset($_SESSION['employee_ID'])) {
    header("Location: ../index.php");
    exit();
}

require '../dbh.inc.php';

// Initialize variables
$memberNumber = "";
$name = "";
$phone = "";
$email = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $memberNumber = $_POST['search'];

        // Retrieve the user's information from the database
        $sql = "SELECT * FROM `member` WHERE `id` = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            // Handle error
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "i", $memberNumber);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            // Populate the input fields with the user's information
            if ($row) {
                $name = $row['name'];
                $phone = $row['phone'];
                $email = $row['email'];
            }
        }
    }

    // Handle form submission
    if (isset($_POST['update'])) {
        $memberNumber1 = $_POST['member_number'];
        $phone = $_POST['phone'];
        $name = $_POST['name'];
        $email = $_POST['email'];

        // Validate and sanitize the form data (you can add more validation as needed)
        $errors = [];

        if (count($errors) === 0) {
            // Update the user's information in the database
            $sql = "UPDATE `member` SET `name` = ?, `phone` = ?, `email` = ? WHERE `id` = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                // Handle error
                exit();
            } else {
                // $hashedpwd = password_hash($password, PASSWORD_DEFAULT);

                mysqli_stmt_bind_param($stmt, "sssi", $name, $phone, $email, $memberNumber1);
                mysqli_stmt_execute($stmt);
                if ($stmt->affected_rows > 0) {
                    echo '<p class="success">Member info successfully updated.</p>';
                } else {
                    echo '<p class="error">Failed to update info.</p>';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User Information</title>
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
        <h1>Update User Information</h1>

        <?php if (isset($errors) && count($errors) > 0): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="search">Search User (ID):</label>
            <input type="text" id="search" name="search" value="<?php echo $memberNumber; ?>" required>
            <input type="submit" value="Search">
        </form>
        <form method="POST" action="">
            <!-- <label for="member_number">Member Number:</label> -->
            <input type="hidden" id="member_number" name="member_number" value="<?php echo $memberNumber; ?>">

            <label for="name">Username:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
        
            <!-- <label for="password">Password:</label>
            <input type="password" id="password" name="password" required> -->


            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo $email; ?>" required>

            <input type="submit" name="update" value="Update">
        </form>

        <p>Go back to <a href="dashboard.php">Dashboard</a>.</p>
    </div>
</body>
</html>
