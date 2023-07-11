<?php
        session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Book</title>
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
        <h1>Add New Book</h1>
        <?php
        require '../dbh.inc.php';

        if (!isset($_SESSION['employee_ID'])) {
            header("Location: ../unauthorized.php"); 
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $book_ID = $_POST['book_ID'];
            $book_Name = $_POST['book_Name'];
            $branch_ID = $_POST['branch_ID'];
            $publication_date = $_POST['publication_date'];
            $book_Author = $_POST['book_Author'];
            $available_copies = $_POST['available_copies'];

            $publication_date = date('Y-m-d', strtotime($publication_date));
            $sql = "INSERT INTO book (book_ID, book_Name, branch_ID, publication_date, book_Author, available_copies) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isisss", $book_ID, $book_Name, $branch_ID, $publication_date, $book_Author, $available_copies);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo '<p class="success">Book added successfully.</p>';
                echo '<p class="success"><a href="dashboard.php">Go To Dashboard</a></p>';
            } else {
                echo '<p class="error">Failed to add the book.</p>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>
        <form method="POST" action="">

            <label for="book_ID">Book ID:</label>
            <input type="text" id="book_ID" name="book_ID" required>

            <label for="book_Name">Book Name:</label>
            <input type="text" id="book_Name" name="book_Name" required>

            <label for="branch_ID">Branch ID:</label>
            <select name="branch_ID" id="branch_ID" required>
                <option value="100001">Northridge LibMS</option>
                <option value="100002">Lakeword LibMS</option>
                <option value="100003">Willford LibMS</option>
                <option value="100004">Timmer LibMS</option>
                <option value="100005">Lifton LibMS</option>
            </select>

            <label for="publication_date">Publication Date:</label>
            <input type="text" id="publication_date" name="publication_date" placeholder="0000-00-00" required>

            <label for="book_Author">Book Author:</label>
            <input type="text" id="book_Author" name="book_Author" required>

            <label for="available_copies">Available Copies:</label>
            <input type="text" id="available_copies" name="available_copies" required>

            <input type="submit" value="Add Book">
        </form>
    </div>
</body>
</html>
