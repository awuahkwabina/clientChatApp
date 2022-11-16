<?php
session_start();
// check weather the  username, password is submitted by using the isset function
if(isset($_POST['username'])&&
isset($_POST['password'])){

    #including the Database connection file

    include'../db.conn.php';

    // get data from the post request and store them in var
    $username = $_POST['username'];
    $password = $_POST['password'];

    #simple form validation
    if(empty($username)){
        #assigning error message
        $em = "username is required";
        #redirect the page to index.php and passing error message and data
        header("Location: ../../index.php?error=$em");
        exit;
     } elseif (empty($password)) {
        #error message
        $em = "password is required";
         #redirect the page to index.php and passing error message and data
         header("Location: ../../index.php?error=$em");
        exit;

}
else{
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);

    # check weather the username is already exist
    if($stmt->rowCount() === 1){
    #initialize and empty array.
   $user = $stmt->fetch();
   # if the usernames are strictly equal
   if($user['username'] === $username){
    #verify password and encrypting.
    if(password_verify($password, $user['password'])){
        # authentication success
        #start and create a session
        $_SESSION['username'] = $user['username'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['user_id'] = $user['user_id'];
        #redirect to home.php
        header("Location: ../../home.php");
      
    }
   }else{
    $em = "incorrect username and password";
    #redirect the page to index.php and passing error message and data
    header("Location: ../../index.php?error=$em");
   exit;
            }
        } else{
            $em = "incorrect username and password";
            #redirect the page to index.php and passing error message and data
            header("Location: ../../index.php?error=$em");
           exit;
        }
    }

 
}else{
    header("location: ../../index.php");
}