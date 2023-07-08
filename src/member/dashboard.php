<?php
session_start();

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
            max-width: 600px;
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
            width: 50%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 48%;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Library Dashboard</h1>
            <?php
                if(isset($_SESSION['name'])) {
                    $member_name = $_SESSION['name'];
                    echo "<div class='welcome'>Hello, $member_name </div>";
                }
            ?>
            <a class="logout" href="logout.php">Logout</a>
        </div>

        <div class="quick-search">
            <input type="text" class="search-input" placeholder="Quick Search">
            <a class="button" href="quicksearch.php">Quick Search</a>
        </div>

        <div class="borrowed-books">
            <h2>Current Borrowed Books</h2>
            <div class="book-item">
                <p class="title">Book Title</p>
                <p>Author: Author Name</p>
                <p>Due Date: 2023-07-31</p>
            </div>
            <div class="book-item">
                <p class="title">Book Title</p>
                <p>Author: Author Name</p>
                <p>Due Date: 2023-08-15</p>
            </div>
        </div>

        <div class="borrowing-history">
            <h2>Borrowing History</h2>
            <a class="history-button" href="borrowinghistory.php">View History</a>
        </div>
    </div>
</body>
</html>
