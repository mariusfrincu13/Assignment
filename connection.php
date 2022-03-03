<?php

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "assignment";

    $con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

    if(!$con){
        die("Failed to connect!");
    }

?>