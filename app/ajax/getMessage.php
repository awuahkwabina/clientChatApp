<?php
session_start();
if(isset($_SESSION['username'])){ 
 if(isset($_POST['id_2'])){
    #including the database
 include "../db.conn.php";
 #getting the xml data and store them into variables
 $id_1 = $_SESSION['user_id'];
 $id_2 = $_POST['id_2'];  
 $opened = 0;
 //getting the data arrays and store for an update.
$sql = "SELECT * FROM chats WHERE to_id=? AND from_id=? ORDER BY chat_id ASC";
//oposite of the insert statement, the to_id and the from_id switch position and only the current message only get display
// from_id = chatWith['user_id'];
// to_id = $_SESSION['user_id'];
$stmt = $conn->prepare($sql);
$stmt->execute([$id_1, $id_2]);
if($stmt->rowCount() > 0){
    $chats = $stmt->fetchAll();
    //looping through the chats
    foreach($chats as $chat)  { 
        if($chat['opened'] == 0){
            $opened = 1;
            $chat_id = $chat['chat_id'];
            //Where the chat_id is the current one
            $sql2 = "UPDATE chats SET opened=? WHERE chat_id=?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute([$opened, $chat_id]); ?>
            <!-- for every half a second the getMessage page auto reloads, updating the database and
            appending this ajax data into the chatBox for final css alteration -- $("#chatBox").append(data);-->
            <p class="l-text border rounded p-2 mb-1">
             <?=$chat['message'] ?>
             <small class="d-block"><?=($chat['created_at'])?></small>
            </p> 
            <?php        
              }
         }
    }
 }

}else{
    header("Location: index.php");
    exit;
}