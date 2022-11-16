<?php
error_reporting("0");
session_start();
if(isset($_SESSION['username'])){ 
 #including the database file
    include 'app/db.conn.php';
    include 'app/helpers/user.php';
    include 'app/helpers/conversation.php';
    include 'app/helpers/timeAgo.php';
    include 'app/helpers/getChat.php';
   $user = getUser($_SESSION['username'], $conn);
   #get user data
   $conversations = getConversation($user['user_id'], $conn);
    // getting all the users where the 
   
   
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatApp - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="icon" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .fs-xs {
            font-size: 1rem;
            margin-left: 1px;
            padding-right: 8rem;
        }

        .w-10 {
            width: 10%;
        }

        a {
            text-decoration: none;
        }

        .fs-big {
            text-decoration: none;
        }

        .online {
            width: 10px;
            height: 10px;
            margin-left: 1rem;
            background: green;
            border-radius: 50%;
        }

        .timeago {
            font-size: 13px;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="p-2 w-400 rounded shadow">
        <div class="d-flex mb-3 p-3 bg-light justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="uploads/<?=$user['p_p']?>" alt="Profile Picture" class="w-25 rounded-circle">
                <h3 class="fs-xs m-2"><?=$user['name'];?></h3>
            </div>
            <a href="logout.php" class="btn btn-dark">LOGOUT</a>
        </div>
        <div class="input-group mb-3">
            <input type="text" placeholder="search" id="searchText"class="form-control">
            <button class="btn btn-primary" id="searchBtn">
                <i class="fa fa-search"></i>
            </button>
        </div>
        <ul id="chatlist" class="list-group mvh-50 overflow-auto">
            <?php if(!empty($conversations)){ ?>  
        <?php foreach($conversations as $conversation){ ?>
            <li class="list-group-item">
                <a href="chat.php?user=<?=$conversation['username']?>" class="d-flex align-items-center p-2">
                    <div class="d-flex align-items-center">
                        <img src="uploads/<?=$conversation['p_p'];?>" class="w-10 rounded-circle" alt="" srcset="">
                        <h3 class=" d-flex fs-xs m-2 align-items-center "><?=$conversation['name'];?>
                        <small>
                        <?php 	
                        echo lastChat($_SESSION['user_id'], $conversation['user_id'], $conn);						 
                        ?>
                      </small>
                </h3>
                        <?php
                        if(timeAgo($conversation['last_seen']) == "online") { ?>
                        <div title="online">
                            <div class="online"></div>
                        </div>
                        <?php }else{ $timenow = timeAgo($conversation['last_seen']);?>
                        <div class="timeago"><?php echo ($timenow); ?>
                        </div>
                        <?php } ?>
                    </div>
                </a>
            </li>
            <?php } ?>
            <?php }else{ ?>
        </ul>
        <div class="alert alert-info" role="alert">
            <i class="fa fa-comments d-block fs-big"></i>
            No messages yet, Start the conversation!
        </div>
        <?php } ?>
    </div>

    <!-- ajax calling when the window reloads -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
 
        $("#searchText").on("input", function(){
            var searchText = $(this).val()
            if(searchText == " ")  return;
               $.post("app/ajax/search.php",
               {
                key: searchText
               },
               function(data, status){
                $("#chatlist").html(data);
               });
        });
            // search using the button
        $("#searchBtn").on("click", function(){
            var searchText = $("searchText").val()
                if(searchText == " ")  return;
               $.post("app/ajax/search.php",
               {
                key: searchText
               },
               function(data, status){
                $("#chatlist").html(data);
               });
        });  

            // auto update last seen for the logged in user
            let lastSeenUpdate = function () {
                $.get("app/ajax/update_last_seen.php");
            }

            lastSeenUpdate()
            /*
            update last_seen every 10 seconds
            */
            setInterval(lastSeenUpdate, 20000);

        });
    </script>
</body>

</html>
<?php
}else{
    header("Location: index.php");
    exit;
}
?>