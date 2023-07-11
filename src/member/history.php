<?php
session_start();

require '../dbh.inc.php';

if (!isset($_SESSION['member_number'])) {
    header("Location: ../unauthorized.php"); // Redirect to signup page
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrowed Books History</title>
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
                padding: 7px 5px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="borrowed-books">
            <h1> Borrowed Books History</h1>
            <?php
                require '../dbh.inc.php';

                $id = $_SESSION['member_number'];
                $sql = "SELECT * FROM borrowed WHERE id = ? AND book_returned_date < CURDATE()";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    $errors[] = "Error Reaching Server; Refresh the page!";
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if (mysqli_num_rows($result) > 0) {
                        $totalFine = 0; // Variable to store the total fine amount
                        while ($row = mysqli_fetch_assoc($result)) {
                            $sql_book = "SELECT * FROM book WHERE book_id = ?";
                            $stmt_book = mysqli_stmt_init($conn); 
                            if (!mysqli_stmt_prepare($stmt_book, $sql_book)) { 
                                $errors[] = "Error Reaching Server; Refresh the page!";
                            } else {
                                mysqli_stmt_bind_param($stmt_book, "i", $row['book_ID']);
                                mysqli_stmt_execute($stmt_book);
                                $result_book = mysqli_stmt_get_result($stmt_book); 
                                echo '<div class="book-item">';
                                while ($book_row = mysqli_fetch_assoc($result_book)) { 
                                    echo '<p class="title">' . $book_row['book_Name'] . '</p>';
                                    echo '<p>' . $book_row['book_Author'] . '</p>';
                                    echo '<p>Due Date: ' . $row['book_returned_date'] . '</p>';

                                    // Calculate fine for each book
                                    $dueDate = strtotime($row['book_returned_date']);
                                    $currentDate = strtotime(date('Y-m-d'));
                                    $daysOverdue = floor(($currentDate - $dueDate -1) / (60 * 60 * 24));
                                    $fineAmount = $daysOverdue * 1; // Assuming $1 fine per day
                                    echo '<p class="fee">Fine: $' . $fineAmount . '</p>';

                                    $totalFine += $fineAmount; // Update the total fine amount
                                }
                                echo '</div>';
                            }
                        }
                        echo '<p class="fee">Total Overdue Fee: $' . $totalFine . '</p>'; // Display the total fine amount
                    } else {
                        echo '<p>No Books overdue.</p>';
                    }
                }
            ?>
        </div>
        <p>back to the Dashboard <a href="dashboard.php">Click here</a>.</p>
    </div>
</body>
</html>
