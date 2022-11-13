<?php

#server name
$sName = "localhost";
#username
$uName = "root";
#user password
$pass = "";
#the database name
$db_name = "mychatapp_db";

//creating the database connection using the PDO object in try statement.

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection failed : " . $e->getMessage();
}

?>