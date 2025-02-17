<?php
 session_start();
 if(!$_SESSION["userID"])
 {
   header("Location:staff.login.php");
 }
 ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
  <link rel="manifest" href="Resource/favicon/site.webmanifest">
  <link rel="stylesheet" type="text/css" href="css/ddash_style.css">
  <title> Кабінет </title>
</head>
<body>
  <div class="top_img"><img src="Resource/land2.png"></div>
  <div class="navigation-bar" style="text-align: center">
    <a href="sdashboard.php" >Домашня</a>
    <a href="srecords.php">Записи</a>
    <a href="sinsert.php">Додати</a>
    <a class='logout' href="logout.php">Вихід</a>
  </div>

  <?php
    require "connection.php";
    $uid=$_SESSION["userID"];
    $sql="SELECT SSN, F_Name, CONCAT(F_Name,' ',L_Name) AS Full_name, Contact_No, d.Email,h.name, d.Address, Department, Designation FROM medical_staff d, hospital h WHERE d.Hospital_ID=h.ID AND SSN=?";
    $stmt= mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)) {
      header("Location:sdashboard.php?error=sqlerror");
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
        $hname=$row["name"];
        $dep=$row["Department"];
        $desg=$row["Designation"];

        echo "
          <div class='welcome'><h2 class='welcome_mssg'> ВІТАЄМО $fname</h2></div>

 <div class='pi_box'>
 <div class='pi_table'>
 <h3> Особиста інформація</h3>
 <table>
 <tr><th>ПІБ</th>
 <td>$fullname</td></tr>
 <tr><th>НАЗВА ЛІКАРНІ</th>
 <td>$hname</td></tr>
 <tr><th>ВІДДІЛ</th>
 <td>$dep</td></tr>
 <tr><th>ПОЗНАЧЕННЯ</th>
 <td>$desg</td></tr>
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
 <a href='staff_info_edit.php'>Редагувати</a>
 </div>
 </div>
 <div class='res_div'><a class='res' href='s_res_pass.php'>Скинути пароль</a></div>
 <div class='footer'></div>
        ";
      }
    }
   ?>

</body>
</html>
