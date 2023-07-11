<?php
session_start();

require '../dbh.inc.php';

if (!isset($_SESSION['member_number'])) {
    header("Location: ../unauthorized.php"); // Redirect to signup page
    exit;
}

if (isset($_GET['bookid']) && isset($_GET['branchid'])) {
    $memberNumber = $_GET['memberid'];
    $bookid = $_GET['bookid'];
    $branchid = $_GET['branchid'];
    $sql = "INSERT INTO `borrowed` (`id`, `book_ID`, `branch_ID`, `book_borrowed_date`, `book_returned_date`) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: dashboard.php?bookfound=false");
        exit();
    } else {
        $currentDate = date('Y-m-d');
        $newDate = date('Y-m-d', strtotime($currentDate . ' +7 days'));
        mysqli_stmt_bind_param($stmt, "iiiss", $memberNumber, $bookid, $branchid, $currentDate, $newDate);
        mysqli_stmt_execute($stmt);

        // Update available_copies
        $updateSql = "UPDATE `book` SET `available_copies` = `available_copies` - 1 WHERE `book_ID` = ? AND `branch_ID` = ?";
        $updateStmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($updateStmt, $updateSql)) {
            header("Location: dashboard.php?bookfound=true&updateerror=true");
            exit();
        } else {
            mysqli_stmt_bind_param($updateStmt, "ii", $bookid, $branchid);
            mysqli_stmt_execute($updateStmt);
            header("Location: dashboard.php?bookfound=true");
            exit();
        }

        mysqli_stmt_close($updateStmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
