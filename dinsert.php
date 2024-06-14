<?php
 session_start();
 if(!$_SESSION["userID"])
 {
   header("Location:doctor.login.php");
 }
 ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
  <link rel="manifest" href="Resource/favicon/site.webmanifest">
  <link rel="stylesheet" type="text/css" href="css/dinsert_style.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />
<style>
	.navigation-bar a{
		font-size: 16px;
	}
  
  </style>
  <title> Insert </title>
</head>
<body>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>

  <div class="top_img"><img src="Resource/land2.png"></div>
  <div class="navigation-bar" style="text-align: center">
    <a href="ddashboard.php" >Домашня</a>
    <a href="drecords.php">Записи</a>
    <a href="dsearch.php">Пошук</a>
    <a href="dinsert.php">Додати</a>
    <a class='logout' href="logout.php">Вихід</a>
  </div>

  <script>
  jQuery(function($) {
      $("#date").datepicker();
  });
  </script>

  <script>
  jQuery(function($) {
      $("#time").timepicker({
        timeFormat: "hh:mm tt"
      });
  });
</script>



  <?php
  if (isset($_GET["error"]))
   {
    if($_GET["error"]=="wronguser")
    {
      echo "<div class='welcome' style='color: #D61A3C'><h2 class='welcome_mssg'> Wrong Patient ID </h2></div>";
    }
  }
  elseif (isset($_GET["login"])) {
      if ($_GET["login"]=="success") {
        echo "<div class='welcome' style='color: #97DC21'><h2 class='welcome_mssg'> Record Saved Successfully </h2></div>";
      }
    }
  elseif(!isset($_POST["choice-submit"])){
      echo "<div class='welcome'><h2 class='welcome_mssg'> Виберіть категорію</h2></div>
      <div class='choice_form_box'>
        <form class='choice_form' action='dinsert.php' method='post'>
          <label for='ch'>Консультація</label>
          <input type='checkbox' name='ch' value='1'><br>
          <label for='ch'>Хірургія</label>
          <input type='checkbox' name='ch' value='2'><br>
          <label for='ch'>Діагностика</label>
          <input type='checkbox' name='ch' value='3'><br>
          <input type='submit' name='choice-submit' value='Далі'>
        </form>
      </div>";
    }

    else {
      if ($_POST["ch"]=="1") {
        echo "<div class='welcome'><h2 class='welcome_mssg'> Форма консультації</h2></div>
  <div class='input-form-box'>
    <form class='input-form' action='dinsert_con.php' method='post'>
      <label for='pssn'>ID пацієнта</label>
      <input type='text' name='pssn' placeholder='Введіть дійсний ідентифікатор пацієнта' required><br>

      <label for='date'>Дата</label>
      <input type='text' name='date' id='date' placeholder='Введіть дату' required><br>


      <label for='time'>Час</label>
      <input type='text' name='time' id='time' placeholder='Введіть час' required><br>


      <label for='complains'>Скарги</label>
      <textarea name='complains' placeholder='Напишіть скарги' required ></textarea><br>

      <label for='findings'>Висновки</label>
      <textarea name='findings' placeholder='Напишіть висновки' required></textarea><br>

      <label for='treatments'>Лікування</label>
      <textarea name='treatments' placeholder='Лікування якщо є'></textarea><br>

      <label for='meds'>Ліки</label>
      <textarea name='meds' placeholder='Ліки якщо є'></textarea><br>

      <label for='allerigies'>Алергії</label>
      <textarea name='allergies' placeholder='Алергії, якщо є'></textarea><br>

      <input type='submit' name='input-submit' value='Зберегти'>
    </form>

  </div>";
      }
      elseif ($_POST["ch"]=="2") {
          echo "  <div class='welcome'><h2 class='welcome_mssg'> Форма хурургії</h2></div>
  <div class='input-form-box'>
    <form class='input-form' action='dinsert_sur.php' method='post'>
      <label for='pssn'>ID пацієнта</label>
      <input type='text' name='pssn' placeholder='Введіть дійсний ідентифікатор пацієнта' required><br>

      <label for='date'>Дата</label>
      <input type='text' name='date' id='date' placeholder='Введіть дату' required><br>

      <label for='time'>Час</label>
      <input type='text' name='time' id='time' placeholder='Введіть час' required><br>

      <label for='description'>Опис</label>
      <textarea name='description' placeholder='Опис операції' required ></textarea><br>

      <label for='complication'>Ускладнення</label>
      <textarea name='complication' placeholder='Хірургічні ускладнення'></textarea><br>

      <label for='allerigies'>Алергії</label>
      <textarea name='allergies' placeholder='Алергії, якщо є'></textarea><br>

      <input type='submit' name='input-submit' value='Зберегти'>
    </form>

  </div>";
      }

      elseif ($_POST["ch"]=="3") {
        echo "  <div class='welcome'><h2 class='welcome_mssg'> Форма діагностики</h2></div>
  <div class='input-form-box'>
    <form class='input-form' action='dinsert_diag.php' method='post'>
      <label for='pssn'>ID пацієнта</label>
      <input type='text' name='pssn' placeholder='Введіть дійсний ідентифікатор пацієнта' required><br>

      <label for='date'>Дата</label>
      <input type='text' name='date' id='date' placeholder='Введіть дату' required><br>

      <label for='time'>Час</label>
      <input type='text' name='time' id='time' placeholder='Введіть час' required><br>

      <label for='diagname'>Назва діагностики</label>
      <input type='text' name='diagname' placeholder='Назва діагнозу' required><br>

      <label for='description'>Опис</label>
      <textarea name='description' placeholder='Опис діагнозу' required ></textarea><br>

      <label for='complication'>Ускладнення</label>
      <textarea name='complication' placeholder='Діагностика ускладнення за наявності'></textarea><br>

      <label for='allerigies'>Алергії</label>
      <textarea name='allergies' placeholder='Алергії, якщо є'></textarea><br>

      <input type='submit' name='input-submit' value='Зберегти'>
    </form>

  </div>";
      }
    }
 ?>



</body>
</html>
