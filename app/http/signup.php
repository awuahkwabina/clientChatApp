<?php
// check weather the name, username, password and profile picture is submitted by using the isset function
if(isset($_POST['username'])&&
isset($_POST['password'])&&
isset($_POST['name'])){

    #including the Database connection file

    include'../db.conn.php';

    // get data from the post request and store them in var
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
   

    #making url data format
    $data = 'name='.$name.'&username='.$username;

    #simple form validation
     if(empty($name)){
        #assigning error message
        $em = "Name is required";
        #redirect the page to signup.php and passing error message and data
        header("Location: ../../signup.php?error=$em&$data");
        exit;
     } elseif (empty($username)) {
        #error message
        $em = "username is required";
         #redirect the page to signup.php and passing error message and data
         header("Location: ../../signup.php?error=$em&$data");
        exit;
     } elseif (empty($password)) {
        #error message
        $em = "password is required";
        #redirect the page to signup.php and parsing error message.
        header("Location: ../../signup.php?error=$em&$data");
        exit;
     }else{
        #checking the database if the user already exist
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([ $username]);

        if($stmt->rowCount() > 0){
            $em = "The username ($username) is taken";
            header("Location: ../../signup.php?error=$em&$data");
            exit;
        }else{
            #profile picture uploading
            if(isset($_FILES['pp'])){
                #get the data and store them in var
                $img_name = $_FILES['pp']['name'];
                $tmp_name = $_FILES['pp']['tmp_name'];
                $error = $_FILES['pp']['error'];
                #if there is no errors occured during the uploading of the file
                if($error === 0){
                    #get the image extention and store it in var.
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    #Convert the image extension into lower case and store it in var.
                    $img_ex_lc = strtolower($img_ex);
                    #creating an array that allowed to upload image extension
                    $allow_img_exs = array("jpg", "jpeg", "png", "webp");
                    #Check if the image extension is present in the allowed exts.
                    if(in_array($img_ex_lc, $allow_img_exs)){
                        #renaming the image name with user's username, like username.$img_ex_lc
                        $new_img_name = $username. '.' .$img_ex_lc;
                        #creating an upload path on the server's root directly
                        $img_upload_path = '../../uploads/'.$new_img_name;
                        #moving uploaded image to uploaded folder
                        move_uploaded_file($tmp_name, $img_upload_path);

                    }else{
                        $em = "You can't upload image image of this type";
                        header("Location: ../../signup.php?error=$em&$data");
                        exit;
                    }

                }else{
                    $em = "An unknown error occured while uploading the image file.";
                    header("Location: ../../signup.php?error=$em&$data");
                    exit;
                }

            }
            #hasing the password and stores in a var
            $password = password_hash($password, PASSWORD_DEFAULT);
            #if the user uploads a new profile picture
            if($new_img_name){
                #insert data into the database
                $sql = "INSERT INTO users (name, username, password, p_p) VALUES(?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password, $new_img_name]);
            }else{
                #inserting the data without the profile picture
                $sql = "INSERT INTO users (name, username, password) VALUES(?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password]);
            }
            #rediect to index.php and parsing a success message
            $sm = "Account is created successfully";
            header("Location:  ../../index.php?success=$sm");
        }
     }
    }
    else{
        header("location: ../../signup.php");
    }
