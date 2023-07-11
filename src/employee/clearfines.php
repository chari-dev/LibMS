<?php
session_start();

require '../dbh.inc.php';

if (!isset($_SESSION['employee_ID'])) {
    header("Location: ../index.php");
    exit();
}

$memberID = $_GET['member_ID'];
$bookID = $_GET['book_ID'];

$sql = "DELETE FROM borrowed WHERE id = ? AND book_ID = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    exit();
}
mysqli_stmt_bind_param($stmt, "ii", $memberID, $bookID);
mysqli_stmt_execute($stmt);
if (mysqli_stmt_affected_rows($stmt) > 0) {
    $sql_update_book = "UPDATE book SET available_copies = available_copies + 1 WHERE book_ID = ?";
    $stmt_update_book = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt_update_book, $sql_update_book)) {
        exit();
    }
    mysqli_stmt_bind_param($stmt_update_book, "i", $row_select_book['book_ID']);
    mysqli_stmt_execute($stmt_update_book);
    header('Location: overdue_check.php');
    exit();
} else {
    exit();
}