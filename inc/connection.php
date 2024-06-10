<?php
    $dbserver = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "smart_parking";

    $conn = new mysqli($dbserver,$dbuser,$dbpass,$dbname);

    //error cheking

    if($conn->connect_error){
        die('Database conection failed'.$conn->connect_error);
    }else{
        // echo "Connection successful.";
    }
?>


