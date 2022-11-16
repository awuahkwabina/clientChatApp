<?php
session_start();
if(isset($_SESSION['username'])){ 
 #including the database file
    include 'app/db.conn.php';   
    include 'app/helpers/user.php';   
    include 'app/helpers/chat.php';   
    include 'app/helpers/timeAgo.php';   
    include 'app/helpers/opened.php';   

if(!isset($_GET['user'])){
    header("Location: home.php");
    exit;
}

// Getting the user information

$chatWith =  getUser($_GET['user'],  $conn);
if(empty($chatWith)){
header("Location: home.php");
exit;
}

$chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);

opened($chatWith['user_id'], $conn, $chats);
// foreach($chats as $chat)

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatApp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="icon" href="img/logo.png">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .online {
            width: 10px;
            height: 10px;
            background: green;
            border-radius: 50%;
        }

        .w-15 {
            width: 15%;
        }

        .fs-sm {
            font-size: 1.4rem;
        }

        small {
            color: #bbb;
            font-size: 0.7rem;
            text-align: right;
        }

        .chat-box {
            overflow-y: auto;
            overflow-x: hidden;
            max-height: 50vh;

        }

        .r-text {
            width: 65%;
            background: #f8f9fa;
            color: #444;
        }

        .l-text {
            width: 65%;
            background: #3289c8;
            color: #fff;
        }

        /* width */
        *::-webkit-scrollbar {
            width: 3px;
        }

        /* track */
        *::-webkit-scrollbar-track {
            background: #f1f1f1'

        }

        /* handle */
        *::-webkit-scrollbar-thumb {
            background: #aaa;
        }

        /* handle on hover */
        *::-webkit-scrollbar-thumb:hover {
            background: #3289c8;
        }

        textarea {
            resize: none;

        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="w-400 shadow p-4 rounded">
        <a href="home.php" class="fs-4 link-dark">&#8592;</a>
        <div class="d-flex align-items-center">
            <img src="uploads/<?=$chatWith['p_p']?>" alt="" class="w-15 rounded-circle">
            <h3 class="display-4 fs-sm m-2">
                <?=$chatWith['name']?> <br>
                <?php
                $pre = timeAgo($chatWith['last_seen']);
                if($pre == "online"){
                ?>
                <div class="d-flex align-items-center" title="online">
                    <div class="online"></div>
                    <small class="d-block p-1">Online</small>
                </div>
                <?php
                }else{ ?>
                <div class="d-flex align-items-center" title="online">
                    <div class=""></div>
                    <small class="d-block p-1">Last seen: <?=$chatWith['last_seen']?></small>
                </div>
                <?php
                }
                ?>
            </h3>
        </div>
        <div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
            <?php
                if(!empty($chats)){  
                    foreach($chats as $chat)
                    if($chat['from_id'] == $_SESSION['user_id']){ ?>
             <p class="r-text align-self-end border rounded p-2 mb-1">
                     <?=$chat['message'] ?>
                    <small class="d-block"><?=($chat['created_at'])?></small>
            </p>
            
           <?php } else { ?>
            <p class="l-text border rounded p-2 mb-1">
                <?=$chat['message'] ?>
                <small class="d-block"><?=($chat['created_at'])?></small>
            </p>
            <?php } ?>
            <?php } else{ ?>
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-comments d-block fs-big"></i>
                    No messages yet, Start the conversation!
                </div>
                <?php } ?>
        </div>
        <div class="input-group mb-3">
            <textarea name="" id="message" cols="3" class="form-control"></textarea>
            <button class="btn btn-primary" id="sendBtn">
                <i class="fa fa-paper-plane"></i>
            </button>

        </div>
    </div>
    <!-- ajax calling when the window reloads -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        var scrolldown = function () {
            let chatBox = document.getElementById('chatBox');
            chatBox.scrollTop = chatBox.scrollHeight;
        }
        scrolldown();
        $(document).ready(function () {
            $("#sendBtn").on('click', function(){
                message = $("#message").val();
                if(message == "") return;
                $.post("app/ajax/insert.php",
                {
                    message: message,
                    to_id: <?=$chatWith['user_id']?>
                },
                function(data, status){
                    $("#message").val("");
                    $("#chatBox").append(data);
                    scrolldown();
                });
            });
            // auto update last seen for the logged in user
                let lastSeenUpdate = function () {
                $.get("app/ajax/update_last_seen.php");
            }
            lastSeenUpdate()
            /* update last_seen every 10 seconds */
            setInterval(lastSeenUpdate, 20000);
            // auto refresh and reload
            let fetchData = function(){
                $.post("app/ajax/getMessage.php",
                {
                    id_2: <?=$chatWith['user_id']?>
                },
                function(data, status){
                    $("#chatBox").append(data);
                    if(data != "") scrolldown();
                });
            }
            fetchData();
            //auto update the message every 0.5 seconds.
            setInterval(fetchData, 500);
        });
    </script>
</body>
<?php
}else{
    header("Location: index.php");
    exit;
}
?>