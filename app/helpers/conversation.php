<?php

function getConversation($user_id, $conn){
    #Getting all the conversation for the current (the logged in) user
    $sql =  "SELECT * FROM conversations WHERE user_1=? OR user_2=? ORDER BY conversation_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $user_id]);
  
    if($stmt->rowCount() > 0){
        $conversations = $stmt->fetchAll();

        #creating an empty array to store the user conversation
        $userData = [];
        #looping through the conversation array
        foreach ($conversations as $conversation) {
            #if the conversation row user_1 is equal to the user_id
            if($conversation['user_1'] == $user_id){
                $sql2 = "SELECT name, username, p_p, last_seen FROM users WHERE user_id=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$conversation['user_2']]);
            }else{
                #in this case we assume the user_2 logged in so we get the user_1 infomation.
                $sql2 = "SELECT name, username, p_p, last_seen FROM users WHERE user_id=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$conversation['user_1']]);
            }
            #getting all the conversation from the statement for looping through
            $allConversations = $stmt2->fetchAll();
            #pushing the data into the array
            array_push($userData, $allConversations[0]);
            
        }
        return $userData;
    }else{
        $conversations = [];
        return $conversations;
    }

}