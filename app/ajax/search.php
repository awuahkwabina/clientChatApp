<?php
session_start();
if(isset($_SESSION['username'])){ 
 #including the database file
 if(isset($_POST['key'])){
# including the database file
include "../db.conn.php";

$key = "%{$_POST['key']}%";

$sql = "SELECT * FROM users WHERE username LIKE ? OR name LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$key, $key]);

if($stmt->rowCount() > 0){
    $users = $stmt->fetchAll();
    foreach ($users as $user) { 
        if($user['user_id'] == $_SESSION['user_id']) continue;
        ?>
<li class="list-group-item">
    <a href="chat.php?user=<?=$user['username']?>" class="d-flex align-items-center p-2">
        <div class="d-flex align-items-center">
            <img src="uploads/<?=$user['p_p'];?>" class="w-10 rounded-circle" alt="" srcset="">
            <h3 class=" d-flex fs-xs m-2 align-items-center "><?=$user['name'];?></h3>
        </div>
    </a>
</li>
<?php } }else{ ?>
<div class="alert alert-info text-center"><i class="fa fa-comments d-block fs-big">
        The user "<?=htmlspecialchars($_POST['key']);?>" is not found.
    </i></div>
<?php }
 }







}else{
    header("Location: index.php");
    exit;
}
?>