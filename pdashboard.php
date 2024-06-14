<?php
 session_start();
 if(!$_SESSION["userID"])
 {
   header("Location:patient.login.php");
 }
 ?>



<!DOCTYPE html>
<html>
<head>
  <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
  <link rel="manifest" href="Resource/favicon/site.webmanifest">
  <link rel="stylesheet" type="text/css" href="css/pdash_style.css">
  <title> Кабінет </title>
</head>
<body>
  <div class="top_img"><img src="Resource/land2.png"></div>
  <div class="navigation-bar" style="text-align: center">
    <a href="pdashboard.php" >Домашня</a>
    <a href="precords.php">Записи</a>
    <a href="psearch.php">Пошук</a>
    <a class='logout' href="logout.php">Вихід</a>
  </div>

  <?php
    require "connection.php";
    $uid=$_SESSION["userID"];
    $sql="SELECT SSN, F_Name, CONCAT(F_Name,' ',L_Name) AS Full_name, Address, Contact_No, Email, Date_Format(Date_Of_Birth,'%M %D %Y') AS Date_Of_Birth, Gender, DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(Date_Of_Birth, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(Date_Of_Birth, '00-%m-%d')) AS age FROM patient WHERE SSN=?";
    $stmt= mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
      header("Location:pdashboard.php?error=sqlerror");
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $uid);
      mysqli_stmt_execute($stmt);
      $result= mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result))
      {
        $ssn=$row["SSN"];
        $fname=strtoupper($row["F_Name"]);
        $fullname=$row["Full_name"];
        $address=$row["Address"];
        $cont=$row["Contact_No"];
        $mail=$row["Email"];
        $dob=$row["Date_Of_Birth"];
        $gen=$row["Gender"];
        $age=$row["age"];
        echo "
          <div class='welcome'><h2 class='welcome_mssg'> ВІТАЄМО $fname</h2></div>

 <div class='pi_box'>
 <div class='pi_table'>
 <h3> Особиста інформація</h3>
 <table>
 <tr><th>ПІБ</th>
 <td>$fullname</td></tr>
 <tr><th>ДЕНЬ НАРОДЖЕННЯ</th>
 <td>$dob</td></tr>
 <tr><th>ВІК</th>
 <td>$age</td></tr>
 <tr><th>СТАНДАРТ</th>
 <td>$gen</td></tr>
 </table>
 </div>
 </div>

 <div class='ci_box'>
 <div class='ci_table'>
 <h3> Контактна інформація</h3>
 <table>
 <tr><th>АДРЕСА</th>
 <td>$address</td></tr>
 <tr><th>КОНТАКТНИЙ НОМЕР</th>
 <td>$cont</td></tr>
 <tr><th>ЕЛЕКТРОННА ПОШТА</th>
 <td>$mail</td></tr>
 </table>
 <a href='patient_info_edit.php'>Редагувати</a>
 </div>
 </div>
 <div class='res_div'><a class='res' href='p_res_pass.php'>Скинути пароль</a></div>
 <div class='footer'></div>
        ";
      }
    }
   ?>




</body>
</html>
