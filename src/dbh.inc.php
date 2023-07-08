<?php
    //this is the Database Handler file

    $serverName = "mysql_db";
    $dbUserName = "root";
    $dbPassword = "root";
    $dbName = "lib";

    $conn = mysqli_connect($serverName,$dbUserName,$dbPassword,$dbName);
    
    if (!$conn) {
        die("Connection Failed: " .mysqli_connect_error());
    }
