<?php
 session_start();
 if (isset($_SESSION["userID"])) {
   header("Location:admin_panel.php");
 }
 ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
  <link rel="manifest" href="Resource/favicon/site.webmanifest">
  <link rel="stylesheet" type="text/css" href="css/login_style.css">
  <title> Вхід </title>
</head>

<body style="background-image: url(Resource/I.jpg); background-size: cover;">


 <div class="login_box">
   <form class="login_form" action="admin_login.process.php" method="post">
     <img src="Resource/logo.ECE.png">
     <h2>Вхід</h2>
     <?php
     if(isset($_GET["error"]))
     {
         if ($_GET["error"]=="emptyfields") {
             echo "<p class=login_error>Заповніть усі поля</p>";
         }
         else if ($_GET["error"]=="wrongpass") {
             echo "<p class=login_error>Пароль не збігається</p>";
         }
         else if ($_GET["error"]=="nouser") {
             echo "<p class=login_error>Користувача не знайдено</p>";
         }
     }
     else if (isset($_GET["login"])) {
         if ($_GET["login"]=="success") {
             echo "<p class=login_success>Успішний вхід!</p>";
         }
     }
      ?>

       <label for="userID">User ID</label>
       <input type="text" name="userID" placeholder="Введіть свій номер соціального страхування або номер паспорта">
       <label for="pass">Пароль</label>
       <input type="password" name="pass" placeholder="Введіть ваш пароль">
       <input type="submit" name="login-submit" value="Вхід">
   </form>


   </div>
 </div>


 </div>


</body>
