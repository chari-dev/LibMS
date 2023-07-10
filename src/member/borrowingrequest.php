<?php
session_start();

if (!isset($_SESSION['member_number'])) {
    header("Location: ../unauthorized.php"); // Redirect to signup page
    exit;
}

if (isset($_GET['bookid'])) {

    $sql = "INSERT INTO `borrowed` (`book_borrowed_date`, `book_ID`, `book_returned_date`, `borrowing_ID`, 'Branch_ID') VALUES (?, ?, ?, ?, ?)"; //('$memberNumber', '$name', '$phone', '$email', '$password')


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





    header("Location: dashboard.php?booknotfound=true");
}