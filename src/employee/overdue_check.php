<?php
session_start();

require '../dbh.inc.php';

if (!isset($_SESSION['employee_ID'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrowed Books History - Employee View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
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

        .empty-message {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
        }

        .button {
            background-color: #4caf50;
            color: #fff;
            padding: 5px 5px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            width: 15%;
        }

        .borrowed-books {
            text-align: center;
            margin-bottom: 20px;
        }

        .book-item {
            background-color: #f4f4f4;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .book-item p {
            margin: 0;
        }

        .book-item .title {
            font-weight: bold;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        a {
            color: #4caf50;
        }

        .fee {
            color: #e05858;
        }

        .total-fee {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="borrowed-books">
            <h1>Borrowed Books History - Employee View</h1>
            <?php
                require '../dbh.inc.php';

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_fine'])) {
                    $borrowedID = $_POST['borrowed_id'];

                    // Clear the fine for the selected borrowed book
                    $sql_clear_fine = "UPDATE borrowed SET fine_amount = 0 WHERE borrowed_ID = ?";
                    $stmt_clear_fine = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt_clear_fine, $sql_clear_fine)) {
                        $errors[] = "Error reaching the server; Please refresh the page!";
                    } else {
                        mysqli_stmt_bind_param($stmt_clear_fine, "i", $borrowedID);
                        mysqli_stmt_execute($stmt_clear_fine);
                        if ($stmt_clear_fine->affected_rows > 0) {
                            echo '<p class="success">Fine cleared successfully.</p>';
                        } else {
                            echo '<p class="error">Failed to clear the fine.</p>';
                        }
                    }
                }

                $sql = "SELECT m.id AS member_ID, b.book_ID, b.book_Name, b.book_Author, br.book_returned_date, (DATEDIFF(CURDATE(), br.book_returned_date) * 1) AS fine_amount FROM member m JOIN borrowed br ON m.id = br.id JOIN book b ON br.book_ID = b.book_ID WHERE br.book_returned_date < CURDATE();";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $totalFee = 0;

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="book-item">';
                        echo '<p class="title">' . $row['book_Name'] . '</p>';
                        echo '<p>Member ID: ' . $row['member_ID'] . '</p>';
                        echo '<p>Book Author: ' . $row['book_Author'] . '</p>';
                        echo '<p>Due Date: ' . $row['book_returned_date'] . '</p>';
                        echo '<p class="fee">Fine: $' . $row['fine_amount'] . '</p>';
                        echo '<br><a class="button" href="clearfines.php?member_ID='. $row['member_ID'] .'&book_ID='. $row['book_ID'] .'">Clear Fine</a>';
                        echo '</div>';

                        $totalFee += $row['fine_amount'];
                    }

                    echo '<p class="total-fee">Total Overdue Fees: $' . $totalFee . '</p>';
                } else {
                    echo '<p>No overdue books found.</p>';
                }
            ?>
        </div>
        <p>Back to the Dashboard <a href="dashboard.php">Click here</a>.</p>
    </div>
</body>
</html>
