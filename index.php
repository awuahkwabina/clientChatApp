<?php
session_start();
if(!isset($_SESSION['username'])){ 
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chatting App</title>
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="img/logo.png">
</head>

<body class = "d-flex justify-content-center align-items-center vh-100">
  <div class="w-400 p-5 shadow rounded">
    <form method="POST" action="app/http/auto.php">
      <div class = "d-flex justify-content-center align-items-center flex-column">
      <img src="img/user-account-box.webp" class="w-25" alt="">
        <h3 class="display-4 fs-1 text-center">LOGIN</h3>
      </div>
      <?php if(isset($_GET['error'])) {?>
            <div class="alert alert-warning" role="alert">
                    <?php echo htmlspecialchars($_GET['error']);?>
                </div>
                <?php }?>
        <?php if(isset($_GET['success'])) {?>
            <div class="alert alert-warning" role="alert">
                    <?php echo htmlspecialchars($_GET['success']);?>
                </div>
<?php }  ?>
      <div class="mb-3">
        <label for="" class="form-label">Username</label>
        <input type="text" class="form-control" name="username">
      </div>
      <div class="mb-3">
        <label for="" class="form-label">Password</label>
        <input type="text" class="form-control" name="password">
      </div>
      <div class="mb-3">
        <button type="submit" class="btn btn-primary mb-3">LOGIN</button>
        <a href="signup.php">SIGN UP</a>
      </div>
    </form>
  </div>
</body>
</html>

<?php
}else{
    header("Location: home.php");
    exit;
}
?>