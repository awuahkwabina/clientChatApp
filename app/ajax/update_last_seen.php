<?php
session_start();


// we start the seesion and update the timestamp in the database.
if(isset($_SESSION['username'])){
// connect the database
include "../db.conn.php";
# get the logged user_id using the seesion
$id = $_SESSION['user_id'];
$sql = "UPDATE users SET last_seen = NOW() WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);



}else{
    header("Location: ../../index.php");
}
