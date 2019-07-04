<?php session_start();?>
<?php require_once("functions/connection.php");?>
<?php include("functions/logs.php")?>
<?php

	if(isset($_SESSION["username"])){
  	header("Location: manager.php");
    exit;
	}

	if(isset($_POST["login"])){

  	if(!empty($_POST['username']) && !empty($_POST['password'])) {
    	$username = htmlspecialchars($_POST['username']);
    	$password = md5(htmlspecialchars($_POST['password']));

      if($username == 'admin' && $password == md5('admin')){
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit;
      }

    	$query = $mysqli->query("SELECT * FROM Managers WHERE username='$username' AND password='$password'");
    	$numrows = $query->num_rows;
    	if($numrows!=0){
        while($row= $query->fetch_assoc())
        {
					$manager_id=$row['ID'];
        	$dbusername=$row['username'];
          $dbpassword=$row['password'];
        }
        if($username == $dbusername && $password == $dbpassword){
          $_SESSION['username'] = $username;
					$_SESSION['manager_id'] = $manager_id;
					add_log($manager_id, "Авторизовался");
          header("Location: manager.php");
          exit;
      	}
  	  } else {
  	    echo  "Invalid username or password!";
      }
  	} else {
      $message = "All fields are required!";
  	}
	}
?>
<?php include("modules/header.php"); ?>
  <div class="container">

    <form class="form-signin" method="POST" role="form">
      <div class="form-group">
        <label for="username">Имя пользователя</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Введите Имя пользователя">
      </div>
      <div class="form-group">
        <label for="password">Пароль</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль">
      </div>
      <button type="submit" name="login" class="btn btn-block btn-primary">Войти</button>
    </form>

  </div> <!-- /container -->
<?php include("modules/footer.php"); ?>
