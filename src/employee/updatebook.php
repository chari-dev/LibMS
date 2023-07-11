<?php
session_start();

require '../dbh.inc.php';

if (!isset($_SESSION['employee_ID'])) {
    header("Location: ../unauthorized.php"); 
    exit;
}

$book_name = $_GET['bookname'];
$book_ID = $_GET['bookid'];
$branch_ID = $_GET['branchid'];
$publication_date = $_GET['publication_date'];
$book_Author = $_GET['book_Author'];
$available_copies = $_GET['available_copies'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Book Information</title>
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

        input[type="text"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Book Information</h1>
        <?php
        require '../dbh.inc.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the updated values from the form
            $book_Name = $_POST['book_Name'];
            $book_ID = $_POST['book_ID'];
            $branch_ID = $_POST['branch_ID'];
            $publication_date = $_POST['publication_date'];
            $book_Author = $_POST['book_Author'];
            $available_copies = $_POST['available_copies'];

            // Update the book information in the database
            $sql = "UPDATE book SET branch_ID = ?, publication_date = ?, book_Author = ?, available_copies = ?, book_Name = ? WHERE book_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issisi", $branch_ID, $publication_date, $book_Author, $available_copies, $book_Name, $book_ID);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo '<p class="success">Book information updated successfully.</p>';
                echo '<p class="success"><a href="dashboard.php">Go To Dashboard</a></p>';
            } else {
                echo '<p class="error">Failed to update book information.</p>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>
        <form method="POST" action="">
            <label for="book_Name">Book Name:</label>
            <input type="text" name="book_Name" value="<?php echo $book_name; ?>">

            <!-- <label for="book_ID">Book ID:</label> -->
            <input type="hidden" name="book_ID" value="<?php echo $book_ID; ?>">

            <label for="branch_ID">Branch ID:</label>
            <input type="text" id="branch_ID" name="branch_ID" value="<?php echo $branch_ID; ?>" required>

            <label for="publication_date">Publication Date:</label>
            <input type="text" id="publication_date" name="publication_date" value="<?php echo $publication_date; ?>" required>

            <label for="book_Author">Book Author:</label>
            <input type="text" id="book_Author" name="book_Author" value="<?php echo $book_Author; ?>" required>

            <label for="available_copies">Available Copies:</label>
            <input type="text" id="available_copies" name="available_copies" value="<?php echo $available_copies; ?>" required>

            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
