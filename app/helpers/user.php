<?php

function getUser($username,  $conn){
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);
    # check weather the user exist in the database
    if($stmt->rowCount() == 1){
        $user = $stmt->fetch();
        return $user;
    }else{
    #return an empty arrays of users if the there isn't any user available
        $user = [];
        return $user;
    }
}

?>