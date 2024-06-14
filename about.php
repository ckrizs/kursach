<html>
<head>
  <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
  <link rel="manifest" href="Resource/favicon/site.webmanifest">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <link rel="stylesheet" type="text/css" href="css/about_style.css">
	<title> Про нас </title>

</head>

<body style="background: black;">
<div class="navigation-bar">
 <img src="Resource/land.png" alt="logo">
  <?php
    session_start();
  if(isset($_SESSION["userID"]) && $_SESSION["uc"]=="1")
  {
      echo "<a href='logout.php'>Вихід</a>
      <a href='pdashboard.php'>Профіль </a>
      <a href='index.php'> Домашня</a>";
  }
  elseif (isset($_SESSION["userID"]) && $_SESSION["uc"]=="2") {
      echo "<a href='logout.php'>Вихід</a>
      <a href='ddashboard.php'>Профіль </a>
      <a href='index.php'> Домашня</a>";
  }
  elseif (isset($_SESSION["userID"]) && $_SESSION["uc"]=="3") {
      echo "<a href='logout.php'>Вихід</a>
      <a href='sdashboard.php'>Профіль </a>
      <a href='index.php'> Домашня</a>";
  }
  else {
      echo " <div class='dropdown'>
    <button class='dropbtn'> Вхід <i class='fa fa-caret-down'></i>
    </button>
    <div class='dropdown-content'>
      <a href='patient.login.php'>Вхід пацієнта</a>
      <a href='doctor.login.php'>Вхід доктора</a>
      <a href='staff.login.php'>Вхід персоналу</a>
    </div>
  </div>
  <a href='about.php'>Про нас </a>
  <a href='index.php'> Домашня</a>";
    }
   ?>
</div>

<div class="wrapper">
  <div class="container">
    <p>За допомогою системи керування записами пацієнтів ви можете відстежувати записи пацієнтів із мінімальними зусиллями</p><p>Лікарі можуть переглядати історію пацієнтів одним клацанням миші</p><p>Хірурги можуть переглядати дані пацієнтів перед критичною операцією</ p> <p>Вся ця інформація доступна лікарям незалежно від того, чи вони працюють у різних лікарнях! </p>
  </div>
</div>



</body>
</html>
