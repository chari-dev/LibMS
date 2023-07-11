<?php
session_start();

if (!isset($_SESSION['member_number'])) {
    header("Location: ../unauthorized.php"); // Redirect to signup page
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
        }

        .left-section {
            flex: 1;
        }

        .right-section {
            flex: 0.5;
            margin-left: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .welcome {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .logout {
            color: #4caf50;
            text-decoration: none;
            font-size: 14px;
        }

        .quick-search {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-input {
            width: 70%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
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

        .borrowing-history {
            text-align: center;
        }

        .borrowing-history h2 {
            margin-top: 0;
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

        .search-item {
            background-color: #f7f7f7;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .search-item .title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .search-item p {
            margin: 0;
        }

        .search-item .borrow-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }

        .search-item .borrow-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>Library Dashboard</h1>
            <div class="header">
                <?php
                if(isset($_SESSION['name'])) {
                    $member_name = $_SESSION['name'];
                    echo "<div class='welcome'>Hello, $member_name </div>";
                }
                ?>
                <a class="logout" href="../logout.php">Logout</a>
            </div>
            
            <form method="POST" class="quick-search" action="">
                <input type="text" id="quicksearch" name="quicksearch" class="search-input" placeholder="Quick Search" required>
                <input class="button" type="submit" value="Search">
            </form>
            

            <div class="borrowed-books">
            <?php
                if (isset($_GET['bookfound'])) {
                    if ($_GET['bookfound'] == "false") {
                        echo '<p>An error occared while borrowing book</p>';
                    } else if ($_GET['bookfound'] == "true") {
                        echo '<p>book added to borrowed books</p>';
                    }
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    require '../dbh.inc.php';

                    $booksearch = $_POST['quicksearch'];

                    $errors = [];

                    if (empty($_SESSION['member_number'])) {
                        $errors[] = "Member number or Username is required.";
                    }

                    if (count($errors) === 0) {
                        $searchQuery = "%$booksearch%";
                        $sql = "SELECT * FROM book WHERE 
                            CAST(book_ID AS CHAR) LIKE ? OR 
                            book_Name LIKE ? OR 
                            book_Author LIKE ?";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            $errors[] = "Error Reaching Server; Refresh the page!";
                        } else {
                            mysqli_stmt_bind_param($stmt, "sss", $searchQuery, $searchQuery, $searchQuery);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<div class="search-item">';
                                    echo '<p class="title">' . $row['book_Name'] . '</p>';
                                    echo '<p>Author: ' . $row['book_Author'] . '</p>';
                                    echo '<p>Publication date: ' . $row['publication_date'] . '</p>';
                                    echo '<p>Branch ID: ' . $row['branch_ID'] . '</p>';
                                    echo '<p>Available copies: ' . $row['available_copies'] . '</p>';
                                    if ($row['available_copies'] > 0){
                                        echo '<a class="borrow-button" href="borrowingrequest.php?memberid='. $_SESSION['member_number'] .'&bookid='. $row['book_ID'] .'&branchid='. $row['branch_ID'] .'">Borrow</a>';
                                    } else {
                                        echo '<a class="borrow-button">OUT OF STOCK</a>';
                                    }
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No search results found.</p>';
                            }
                        }
                    }
                    // Output the errors
                    foreach ($errors as $error) {
                        echo '<p>' . $error . '</p>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="right-section">
            <div class="borrowed-books">
                <h2>Current Borrowed Books</h2>
                <?php
                    require '../dbh.inc.php';
                    $id = $_SESSION['member_number'];
                    $sql = "SELECT * FROM borrowed WHERE id = ? AND book_returned_date > CURDATE()";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        $errors[] = "Error Reaching Server; Refresh the page!";
                    } else {
                        mysqli_stmt_bind_param($stmt, "i", $id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if (mysqli_num_rows($result) > 0) {
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
                                    }
                                    echo '</div>';
                                }
                            }
                        } else {
                            echo '<p>No Books Borrowed.</p>';
                        }
                    }
                ?>
            </div>

            <div class="borrowing-history">
                <h2>Borrowing History</h2>
                <a class="history-button" href="history.php">View History</a>
            </div>
        </div>
    </div>
</body>
</html>
