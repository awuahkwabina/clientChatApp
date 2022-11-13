<?php
session_start();
//check if the user is logged in
if(isset($_SESSION['username'])){ 
 #only when the chatwith and the message is set
 if(isset($_POST['to_id']) && isset($_POST['message'])){
 #including the database file
 include "../db.conn.php";
    #get data from xhr and store them in var
    $message = $_POST['message'];
    $to_id = $_POST['to_id'];  
    //Get the logged in user's username from the session
    $from_id = $_SESSION['user_id'];
    //insert the user data and the message into the database chat table.
    $sql = "INSERT INTO chats(from_id, to_id, message) VALUES (?,?,?)";
    $res = $conn->prepare($sql);
    $res->execute([$from_id, $to_id, $message]);
    #check if this is the first conversation between the users
    if($res){
        $sql2 = "SELECT * FROM conversations WHERE user_1=? AND user_2=? OR user_2=? AND user_1=?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$from_id, $to_id, $from_id, $to_id]);
        #setting up the timezone and that depends on your personal computer
        #inserting the chat_id's into the conversation              
        define('TIMEZONE', 'Africa/Accra');
        date_default_timezone_set(TIMEZONE);

        $time = date("h:i:s a");

        if($stmt2->rowCount() == 0){
            $sql3 = "INSERT INTO conversations(user_1, user_2) VALUES (?,?)";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->execute([$from_id, $to_id]);
        }
        ?>
        <p class="r-text align-self-end border rounded p-2 mb-1">
         <?=$message?>
            <small class="d-block"><?=$time?></small>
        </p>
        <?php
    }    
}
}else{
    header("Location: index.php");
    exit;
}
?>