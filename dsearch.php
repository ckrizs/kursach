<?php
 session_start();
 if(!$_SESSION["userID"])
 {
   header("Location:doctor.login.php");
 }
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="180x180" href="Resource/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="Resource/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="Resource/favicon/favicon-16x16.png">
    <link rel="manifest" href="Resource/favicon/site.webmanifest">
    <link rel="stylesheet" type="text/css" href="css/psearch_style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />
<style>
	.navigation-bar a{
		font-size: 16px;
	}
  
  </style>
    <title>Search</title>
  </head>
  <body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>

    <body>
      <div class="top_img"><img src="Resource/land2.png"></div>
      <div class="navigation-bar" style="text-align: center">
        <a href="ddashboard.php" >Домашня</a>
        <a href="drecords.php">Записи</a>
        <a href="dsearch">Пошук</a>
        <a href="dinsert.php">Додати</a>
        <a class='logout' href="logout.php">Вихід</a>
      </div>

    <?php
      if(isset($_GET["error"]))
      {
        if ($_GET["error"]=="emptyfields")
        {
          echo "<div class='welcome'>
            <div class='welcome_mssg' style='color:#D61A3C'>
              Please Fill Out Patient ID Field
            </div>

          </div>";
        }
      }
      else {
        echo "<div class='welcome'>
          <div class='welcome_mssg'>
            Заповніть ідентифікатор пацієнта та інші поля для пошуку
          </div>

        </div>";
      }

     ?>

  <div class="form-box">
    <div class="form">
    <form class="search-form" action="dsearch.php" method="post">
        <label for="did"><span class="content-name">Ідентифікатор пацієнта</span></label>
        <input type="text" name="did" placeholder="Введіть ідентифікатор пацієнта"><br>
        <label for="date"><span class="content-name">Дата</span></label>
        <input type="text" id="date" name="date" placeholder="Введіть дійсну дату"><br>
      <script>
      jQuery(function($) {
          $("#date").datepicker();
      });
    </script>
        <label for="date-time"><span class="content-name">Час</span></label>
        <input type="text" id="time" name="time" placeholder="Введіть дійсний час"><br>
      <script>
      jQuery(function($) {
          $("#time").timepicker({
            timeFormat: "hh:mm tt"
          });
      });
    </script>
        <label for="ref"><span class="content-name">Довідка</span></label>
        <input type="text" name="ref" placeholder="Введіть дійсний контрольний номер"><br>
        <input type="submit" name="search-submit" value="Пошук">

    </form>
  </div>
  </div>

<?php
  if(isset($_POST["search-submit"])){
  require "connection.php";

  $uid=$_SESSION["userID"];

  #Консультація
  if (!empty($_POST["ref"])) {
      $rfr=$_POST["ref"];

      $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN, Complains, Findings, Treatments, Allergies FROM consultation c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN AND CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k'))=?";
      $stmt= mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt,$sql)) {
        header("Location:dsearch.php?error=sqlerror1");
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $rfr);
        mysqli_stmt_execute($stmt);
        $result= mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result)>0)
        {
          echo "<div class='welcome'><h2 class='mssg'> Записи консультацій </h2></div>
          <div class='table_box'>
            <table class='content-table'>
              <thead>
              <tr>
              <th>Дата, час</th>
              <th>Ім'я лікаря</th>
              <th>ID лікаря</th>
              <th>Ім'я пацієнта</th>
              <th>ID пацієнта</th>
              <th>Скарги</th>
              <th>Висновки</th>
              <th>Лікування</th>
              <th>Алергії</th>
              <th>Довідковий номер</th>
              </tr>
              </thead>

          ";

        while ($row = mysqli_fetch_assoc($result)) {
          $ref=$row["Reference"];
          $dt=$row["Date_Time"];
          $dfullname=$row["doctor_fullname"];
          $pfullname=$row["patient_fullname"];
          $comp=$row["Complains"];
          $find=$row["Findings"];
          $treat=$row["Treatments"];
          $alg=$row["Allergies"];
          $dssn=$row["Doctor_SSN"];
          $pssn=$row["Patient_SSN"];

          echo "
          <tbody>
          <tr>
            <td>$dt</td>
            <td>$dfullname</td>
            <td>$dssn</td>
            <td>$pfullname</td>
            <td>$pssn</td>
            <td>$comp</td>
            <td>$find</td>
            <td>$treat</td>
            <td>$alg</td>
            <td>$ref</td>
          </tr>
          ";

        }
        echo "</tbody></table>

        </div>";
        }
        else if (mysqli_num_rows($result)==0) {
          echo "<div class='welcome'><h2 class='mssg'> No Консультація Records Found </h2></div>";
        }
      }
    }
    else{
          if (empty($_POST["did"])) {
            header("Location:dsearch.php?error=emptyfields");
          }
          elseif (empty($_POST["date"]) || empty($_POST["time"])) {
            $did=$_POST["did"];
            {
              if(!empty($_POST["date"])){
              $newdate=date_Create($_POST["date"]);
              $date=date_format($newdate,"Y-m-d");
              }
              else {
                $date= NULL;
              }
            }

            {
              if(!empty($_POST["time"])){
              $newtime=date_Create($_POST["time"]);
              $time=date_format($newtime,"H:i:s");
              }
              else {
                $time=NULL;
              }
            }
            $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN, Complains, Findings, Treatments, Allergies FROM consultation c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN  AND (Patient_SSN=? OR DATE_FORMAT(Date_Time,'%Y-%m-%d')=? OR DATE_FORMAT(Date_Time,'%H:%i:%S')=?)";
            $stmt= mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt,$sql)) {
              header("Location:dsearch.php?error=sqlerror");
            }
            else {
              mysqli_stmt_bind_param($stmt, "sss", $did, $date, $time);
              mysqli_stmt_execute($stmt);
              $result= mysqli_stmt_get_result($stmt);
              if (mysqli_num_rows($result)>0)
              {
                echo "<div class='welcome'><h2 class='mssg'> Записи консультацій </h2></div>
                <div class='table_box'>
                  <table class='content-table'>
                    <thead>
                    <tr>
                    <th>Дата, час</th>
                    <th>Ім'я лікаря</th>
                    <th>ID лікаря</th>
                    <th>Ім'я пацієнта</th>
                    <th>ID пацієнта</th>
                    <th>Скарги</th>
                    <th>Висновки</th>
                    <th>Лікування</th>
                    <th>Алергії</th>
                    <th>Довідковий номер</th>
                    </tr>
                    </thead>

                ";

              while ($row = mysqli_fetch_assoc($result)) {
                $ref=$row["Reference"];
                $dt=$row["Date_Time"];
                $dfullname=$row["doctor_fullname"];
                $pfullname=$row["patient_fullname"];
                $comp=$row["Complains"];
                $find=$row["Findings"];
                $treat=$row["Treatments"];
                $alg=$row["Allergies"];
                $dssn=$row["Doctor_SSN"];
                $pssn=$row["Patient_SSN"];

                echo "
                <tbody>
                <tr>
                  <td>$dt</td>
                  <td>$dfullname</td>
                  <td>$dssn</td>
                  <td>$pfullname</td>
                  <td>$pssn</td>
                  <td>$comp</td>
                  <td>$find</td>
                  <td>$treat</td>
                  <td>$alg</td>
                  <td>$ref</td>
                </tr>
                ";

              }
              echo "</tbody></table>

              </div>";
              }
              else if (mysqli_num_rows($result)==0) {
                echo "<div class='welcome'><h2 class='mssg'> No Консультація Records Found </h2></div>";
              }
            }
          }
          else {
            $did=$_POST["did"];
            $newdate=date_Create($_POST["date"]);
            $date=date_format($newdate,"Y-m-d");
            $newtime=date_Create($_POST["time"]);
            $time=date_format($newtime,"H:i:s");

            $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN, Complains, Findings, Treatments, Allergies FROM consultation c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN AND Patient_SSN=? AND DATE_FORMAT(Date_Time,'%Y-%m-%d')=? AND DATE_FORMAT(Date_Time,'%H:%i:%S')=? ";
            $stmt= mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt,$sql)) {
              header("Location:dsearch.php?error=sqlerror");
            }
            else {
              mysqli_stmt_bind_param($stmt, "sss", $did, $date, $time);
              mysqli_stmt_execute($stmt);
              $result= mysqli_stmt_get_result($stmt);
              if (mysqli_num_rows($result)>0)
              {
                echo "<div class='welcome'><h2 class='mssg'> Записи консультацій </h2></div>
                <div class='table_box'>
                  <table class='content-table'>
                    <thead>
                    <tr>
                    <th>Дата, час</th>
                    <th>Ім'я лікаря</th>
                    <th>ID лікаря</th>
                    <th>Ім'я пацієнта</th>
                    <th>ID пацієнта</th>
                    <th>Скарги</th>
                    <th>Висновки</th>
                    <th>Лікування</th>
                    <th>Алергії</th>
                    <th>Довідковий номер</th>
                    </tr>
                    </thead>

                ";

              while ($row = mysqli_fetch_assoc($result)) {
                $ref=$row["Reference"];
                $dt=$row["Date_Time"];
                $dfullname=$row["doctor_fullname"];
                $pfullname=$row["patient_fullname"];
                $comp=$row["Complains"];
                $find=$row["Findings"];
                $treat=$row["Treatments"];
                $alg=$row["Allergies"];
                $dssn=$row["Doctor_SSN"];
                $pssn=$row["Patient_SSN"];

                echo "
                <tbody>
                <tr>
                  <td>$dt</td>
                  <td>$dfullname</td>
                  <td>$dssn</td>
                  <td>$pfullname</td>
                  <td>$pssn</td>
                  <td>$comp</td>
                  <td>$find</td>
                  <td>$treat</td>
                  <td>$alg</td>
                  <td>$ref</td>
                </tr>
                ";

              }
              echo "</tbody></table>

              </div>";
              }
              else if (mysqli_num_rows($result)==0) {
                echo "<div class='welcome'><h2 class='mssg'> No Консультація Records Found </h2></div>";
              }
            }
          }

        }

        #Surgery
        if (!empty($_POST["ref"])) {
            $rfr=$_POST["ref"];

            $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN, Description, Complications, Allergies FROM operation c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN AND CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k'))=?";
            $stmt= mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt,$sql)) {
              header("Location:dsearch.php?error=sqlerror1");
            }
            else {
              mysqli_stmt_bind_param($stmt, "s", $rfr);
              mysqli_stmt_execute($stmt);
              $result= mysqli_stmt_get_result($stmt);
              if (mysqli_num_rows($result)>0)
              {
                echo "<div class='welcome'><h2 class='mssg'> Хірургічні записи </h2></div>
                <div class='table_box'>
                  <table class='content-table'>
                    <thead>
                    <tr>
                    <th>Дата, час</th>
                    <th>Ім'я лікаря</th>
                    <th>ID лікаря</th>
                    <th>Ім'я пацієнта</th>
                    <th>ID пацієнта</th>
                    <th>Опис</th>
                    <th>Ускладнення</th>
                    <th>Алергії</th>
                    <th>Довідковий номер</th>
                    </tr>
                    </thead>

                ";

              while ($row = mysqli_fetch_assoc($result)) {
                $ref=$row["Reference"];
                $dt=$row["Date_Time"];
                $dfullname=$row["doctor_fullname"];
                $pfullname=$row["patient_fullname"];
                $desc=$row["Description"];
                $compl=$row["Complications"];
                $alg=$row["Allergies"];
                $dssn=$row["Doctor_SSN"];
                $pssn=$row["Patient_SSN"];

                echo "
                <tbody>
                <tr>
                  <td>$dt</td>
                  <td>$dfullname</td>
                  <td>$dssn</td>
                  <td>$pfullname</td>
                  <td>$pssn</td>
                  <td>$desc</td>
                  <td>$compl</td>
                  <td>$alg</td>
                  <td>$ref</td>
                </tr>
                ";

              }
              echo "</tbody></table>

              </div>";
              }
              else if (mysqli_num_rows($result)==0) {
                echo "<div class='welcome'><h2 class='mssg'> No Хірургічні записи Found </h2></div>";
              }
            }
          }
          else{
                if (empty($_POST["did"])) {
                  header("Location:dsearch.php?error=emptyfields");
                }
                elseif (empty($_POST["date"]) || empty($_POST["time"])) {
                  $did=$_POST["did"];
                  {
                    if(!empty($_POST["date"])){
                    $newdate=date_Create($_POST["date"]);
                    $date=date_format($newdate,"Y-m-d");
                    }
                    else {
                      $date= NULL;
                    }
                  }

                  {
                    if(!empty($_POST["time"])){
                    $newtime=date_Create($_POST["time"]);
                    $time=date_format($newtime,"H:i:s");
                    }
                    else {
                      $time=NULL;
                    }
                  }
                  $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN, Description, Complications, Allergies FROM operation c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN  AND (Patient_SSN=? OR DATE_FORMAT(Date_Time,'%Y-%m-%d')=? OR DATE_FORMAT(Date_Time,'%H:%i:%S')=?)";
                  $stmt= mysqli_stmt_init($conn);
                  if (!mysqli_stmt_prepare($stmt,$sql)) {
                    header("Location:dsearch.php?error=sqlerror");
                  }
                  else {
                    mysqli_stmt_bind_param($stmt, "sss", $did, $date, $time);
                    mysqli_stmt_execute($stmt);
                    $result= mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result)>0)
                    {
                      echo "<div class='welcome'><h2 class='mssg'> Хірургічні записи </h2></div>
                      <div class='table_box'>
                        <table class='content-table'>
                          <thead>
                          <tr>
                          <th>Дата, час</th>
                          <th>Ім'я лікаря</th>
                          <th>ID лікаря</th>
                          <th>Ім'я пацієнта</th>
                          <th>ID пацієнта</th>
                          <th>Опис</th>
                          <th>Ускладнення</th>
                          <th>Алергії</th>
                          <th>Довідковий номер</th>
                          </tr>
                          </thead>

                      ";

                    while ($row = mysqli_fetch_assoc($result)) {
                      $ref=$row["Reference"];
                      $dt=$row["Date_Time"];
                      $dfullname=$row["doctor_fullname"];
                      $pfullname=$row["patient_fullname"];
                      $desc=$row["Description"];
                      $compl=$row["Complications"];
                      $alg=$row["Allergies"];
                      $dssn=$row["Doctor_SSN"];
                      $pssn=$row["Patient_SSN"];

                      echo "
                      <tbody>
                      <tr>
                        <td>$dt</td>
                        <td>$dfullname</td>
                        <td>$dssn</td>
                        <td>$pfullname</td>
                        <td>$pssn</td>
                        <td>$desc</td>
                        <td>$compl</td>
                        <td>$alg</td>
                        <td>$ref</td>
                      </tr>
                      ";

                    }
                    echo "</tbody></table>

                    </div>";
                    }
                    else if (mysqli_num_rows($result)==0) {
                      echo "<div class='welcome'><h2 class='mssg'> No Хірургічні записи Found </h2></div>";
                    }
                  }
                }
                else {
                  $did=$_POST["did"];
                  $newdate=date_Create($_POST["date"]);
                  $date=date_format($newdate,"Y-m-d");
                  $newtime=date_Create($_POST["time"]);
                  $time=date_format($newtime,"H:i:s");

                  $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN, Description, Complications, Allergies FROM operation c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN AND Patient_SSN=? AND DATE_FORMAT(Date_Time,'%Y-%m-%d')=? AND DATE_FORMAT(Date_Time,'%H:%i:%S')=? ";
                  $stmt= mysqli_stmt_init($conn);
                  if (!mysqli_stmt_prepare($stmt,$sql)) {
                    header("Location:dsearch.php?error=sqlerror");
                  }
                  else {
                    mysqli_stmt_bind_param($stmt, "sss", $did, $date, $time);
                    mysqli_stmt_execute($stmt);
                    $result= mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result)>0)
                    {
                      echo "<div class='welcome'><h2 class='mssg'> Записи консультацій </h2></div>
                      <div class='table_box'>
                        <table class='content-table'>
                          <thead>
                          <tr>
                          <th>Дата, час</th>
                          <th>Ім'я лікаря</th>
                          <th>ID лікаря</th>
                          <th>Ім'я пацієнта</th>
                          <th>ID пацієнта</th>
                          <th>Опис</th>
                          <th>Ускладнення</th>
                          <th>Алергії</th>
                          <th>Довідковий номер</th>
                          </tr>
                          </thead>

                      ";

                    while ($row = mysqli_fetch_assoc($result)) {
                      $ref=$row["Reference"];
                      $dt=$row["Date_Time"];
                      $dfullname=$row["doctor_fullname"];
                      $pfullname=$row["patient_fullname"];
                      $desc=$row["Description"];
                      $compl=$row["Complications"];
                      $alg=$row["Allergies"];
                      $dssn=$row["Doctor_SSN"];
                      $pssn=$row["Patient_SSN"];

                      echo "
                      <tbody>
                      <tr>
                        <td>$dt</td>
                        <td>$dfullname</td>
                        <td>$dssn</td>
                        <td>$pfullname</td>
                        <td>$pssn</td>
                        <td>$desc</td>
                        <td>$compl</td>
                        <td>$alg</td>
                        <td>$ref</td>
                      </tr>
                      ";

                    }
                    echo "</tbody></table>

                    </div>";
                    }
                    else if (mysqli_num_rows($result)==0) {
                      echo "<div class='welcome'><h2 class='mssg'> No Хірургічні записи Found </h2></div>";
                    }
                  }
                }

              }

      #diagnosis
      if (!empty($_POST["ref"])) {
          $rfr=$_POST["ref"];

          $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN,c.Diagnosis_Name, Description, Complications, Allergies FROM diagnosis c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN AND CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k'))=?";
          $stmt= mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt,$sql)) {
            header("Location:dsearch.php?error=sqlerror1");
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $rfr);
            mysqli_stmt_execute($stmt);
            $result= mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result)>0)
            {
              echo "<div class='welcome'><h2 class='mssg'> Записи про діагностику </h2></div>
              <div class='table_box'>
                <table class='content-table'>
                  <thead>
                  <tr>
                  <th>Дата, час</th>
                  <th>Ім'я лікаря</th>
                  <th>ID лікаря</th>
                  <th>Ім'я пацієнта</th>
                  <th>ID пацієнта</th>
                  <th>Діагноз</th>
                  <th>Опис</th>
                  <th>Ускладнення</th>
                  <th>Алергії</th>
                  <th>Довідковий номер</th>
                  </tr>
                  </thead>

              ";

            while ($row = mysqli_fetch_assoc($result)) {
              $ref=$row["Reference"];
              $dt=$row["Date_Time"];
              $dfullname=$row["doctor_fullname"];
              $pfullname=$row["patient_fullname"];
              $dn=$row["Diagnosis_Name"];
              $desc=$row["Description"];
              $compl=$row["Complications"];
              $alg=$row["Allergies"];
              $dssn=$row["Doctor_SSN"];
              $pssn=$row["Patient_SSN"];

              echo "
              <tbody>
              <tr>
                <td>$dt</td>
                <td>$dfullname</td>
                <td>$dssn</td>
                <td>$pfullname</td>
                <td>$pssn</td>
                <td>$dn</td>
                <td>$desc</td>
                <td>$compl</td>
                <td>$alg</td>
                <td>$ref</td>
              </tr>
              ";

            }
            echo "</tbody></table>

            </div>";
            }
            else if (mysqli_num_rows($result)==0) {
              echo "<div class='welcome'><h2 class='mssg'> No Записи про діагностику Found </h2></div>";
            }
          }
        }
        else{
              if (empty($_POST["did"])) {
                header("Location:dsearch.php?error=emptyfields");
              }
              elseif (empty($_POST["date"]) || empty($_POST["time"])) {
                $did=$_POST["did"];
                {
                  if(!empty($_POST["date"])){
                  $newdate=date_Create($_POST["date"]);
                  $date=date_format($newdate,"Y-m-d");
                  }
                  else {
                    $date= NULL;
                  }
                }

                {
                  if(!empty($_POST["time"])){
                  $newtime=date_Create($_POST["time"]);
                  $time=date_format($newtime,"H:i:s");
                  }
                  else {
                    $time=NULL;
                  }
                }
                $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN, c.Diagnosis_Name, Description, Complications, Allergies FROM diagnosis c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN  AND (Patient_SSN=? OR DATE_FORMAT(Date_Time,'%Y-%m-%d')=? OR DATE_FORMAT(Date_Time,'%H:%i:%S')=?)";
                $stmt= mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt,$sql)) {
                  header("Location:dsearch.php?error=sqlerror");
                }
                else {
                  mysqli_stmt_bind_param($stmt, "sss", $did, $date, $time);
                  mysqli_stmt_execute($stmt);
                  $result= mysqli_stmt_get_result($stmt);
                  if (mysqli_num_rows($result)>0)
                  {
                    echo "<div class='welcome'><h2 class='mssg'> Записи про діагностику </h2></div>
                    <div class='table_box'>
                      <table class='content-table'>
                        <thead>
                        <tr>
                        <th>Дата, час</th>
                        <th>Ім'я лікаря</th>
                        <th>ID лікаря</th>
                        <th>Ім'я пацієнта</th>
                        <th>ID пацієнта</th>
                        <th>Діагноз</th>
                        <th>Опис</th>
                        <th>Ускладнення</th>
                        <th>Алергії</th>
                        <th>Довідковий номер</th>
                        </tr>
                        </thead>

                    ";

                  while ($row = mysqli_fetch_assoc($result)) {
                    $ref=$row["Reference"];
                    $dt=$row["Date_Time"];
                    $dfullname=$row["doctor_fullname"];
                    $pfullname=$row["patient_fullname"];
                    $dn=$row["Diagnosis_Name"];
                    $desc=$row["Description"];
                    $compl=$row["Complications"];
                    $alg=$row["Allergies"];
                    $dssn=$row["Doctor_SSN"];
                    $pssn=$row["Patient_SSN"];

                    echo "
                    <tbody>
                    <tr>
                      <td>$dt</td>
                      <td>$dfullname</td>
                      <td>$dssn</td>
                      <td>$pfullname</td>
                      <td>$pssn</td>
                      <td>$dn</td>
                      <td>$desc</td>
                      <td>$compl</td>
                      <td>$alg</td>
                      <td>$ref</td>
                    </tr>
                    ";

                  }
                  echo "</tbody></table>

                  </div>";
                  }
                  else if (mysqli_num_rows($result)==0) {
                    echo "<div class='welcome'><h2 class='mssg'> No Записи про діагностику Found </h2></div>";
                  }
                }
              }
              else {
                $did=$_POST["did"];
                $newdate=date_Create($_POST["date"]);
                $date=date_format($newdate,"Y-m-d");
                $newtime=date_Create($_POST["time"]);
                $time=date_format($newtime,"H:i:s");

                $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS doctor_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Doctor_SSN,Patient_SSN, c.Diagnosis_Name, Description, Complications, Allergies FROM diagnosis c,patient p, doctor d WHERE Patient_SSN=p.SSN AND Doctor_SSN=d.SSN AND Patient_SSN=? AND DATE_FORMAT(Date_Time,'%Y-%m-%d')=? AND DATE_FORMAT(Date_Time,'%H:%i:%S')=? ";
                $stmt= mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt,$sql)) {
                  header("Location:dsearch.php?error=sqlerror2");
                }
                else {
                  mysqli_stmt_bind_param($stmt, "sss", $did, $date, $time);
                  mysqli_stmt_execute($stmt);
                  $result= mysqli_stmt_get_result($stmt);
                  if (mysqli_num_rows($result)>0)
                  {
                    echo "<div class='welcome'><h2 class='mssg'> Записи про діагностику </h2></div>
                    <div class='table_box'>
                      <table class='content-table'>
                        <thead>
                        <tr>
                        <th>Дата, час</th>
                        <th>Ім'я лікаря</th>
                        <th>ID лікаря</th>
                        <th>Ім'я пацієнта</th>
                        <th>ID пацієнта</th>
                        <th>Діагноз</th>
                        <th>Опис</th>
                        <th>Ускладнення</th>
                        <th>Алергії</th>
                        <th>Довідковий номер</th>
                        </tr>
                        </thead>

                    ";

                  while ($row = mysqli_fetch_assoc($result)) {
                    $ref=$row["Reference"];
                    $dt=$row["Date_Time"];
                    $dfullname=$row["doctor_fullname"];
                    $pfullname=$row["patient_fullname"];
                    $dn=$row["Diagnosis_Name"];
                    $desc=$row["Description"];
                    $compl=$row["Complications"];
                    $alg=$row["Allergies"];
                    $dssn=$row["Doctor_SSN"];
                    $pssn=$row["Patient_SSN"];

                    echo "
                    <tbody>
                    <tr>
                      <td>$dt</td>
                      <td>$dfullname</td>
                      <td>$dssn</td>
                      <td>$pfullname</td>
                      <td>$pssn</td>
                      <td>$dn</td>
                      <td>$desc</td>
                      <td>$compl</td>
                      <td>$alg</td>
                      <td>$ref</td>
                    </tr>
                    ";

                  }
                  echo "</tbody></table>

                  </div>";
                  }
                  else if (mysqli_num_rows($result)==0) {
                    echo "<div class='welcome'><h2 class='mssg'> No Записи про діагностику Found </h2></div>";
                  }
                }
              }

            }

    #Medical_Dosage

    if (!empty($_POST["ref"])) {
        $rfr=$_POST["ref"];

        $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS staff_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Staff_SSN,Patient_SSN,d.Designation, c.Description, c.Complication,c.Medicine, c.Allergies FROM medical_adminstration c,patient p, medical_staff d WHERE Patient_SSN=p.SSN AND Staff_SSN=d.SSN AND CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k'))=?";
        $stmt= mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt,$sql)) {
          header("Location:dsearch.php?error=sqlerror1");
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $rfr);
          mysqli_stmt_execute($stmt);
          $result= mysqli_stmt_get_result($stmt);
          if (mysqli_num_rows($result)>0)
          {
            echo "<div class='welcome'><h2 class='mssg'> Медичні записи про дозування </h2></div>
            <div class='table_box'>
              <table class='content-table'>
                <thead>
                <tr>
                <th>Дата, час</th>
                <th>Ім'я персоналу</th>
                <th>Staff ID</th>
                <th>Позначення</th>
                <th>Ім'я пацієнта</th>
                <th>ID пацієнта</th>
                <th>Опис</th>
                <th>Ускладнення</th>
                <th>Ліки</th>
                <th>Алергії</th>
                <th>Довідковий номер</th>
                </tr>
                </thead>

            ";

          while ($row = mysqli_fetch_assoc($result)) {
            $ref=$row["Reference"];
            $dt=$row["Date_Time"];
            $dfullname=$row["staff_fullname"];
            $pfullname=$row["patient_fullname"];
            $dn=$row["Designation"];
            $desc=$row["Description"];
            $compl=$row["Complication"];
            $med=$row["Medicine"];
            $alg=$row["Allergies"];
            $dssn=$row["Staff_SSN"];
            $pssn=$row["Patient_SSN"];

            echo "
            <tbody>
            <tr>
              <td>$dt</td>
              <td>$dfullname</td>
              <td>$dssn</td>
              <td>$pfullname</td>
              <td>$pssn</td>
              <td>$dn</td>
              <td>$desc</td>
              <td>$compl</td>
              <td>$med</td>
              <td>$alg</td>
              <td>$ref</td>
            </tr>
            ";

          }
          echo "</tbody></table>

          </div>";
          }
          else if (mysqli_num_rows($result)==0) {
            echo "<div class='welcome'><h2 class='mssg'>No Медичні записи про дозування Found </h2></div>";
          }
        }
      }
      else{
            if (empty($_POST["did"])) {
              header("Location:dsearch.php?error=emptyfields");
            }
            elseif (empty($_POST["date"]) || empty($_POST["time"])) {
              $did=$_POST["did"];
              {
                if(!empty($_POST["date"])){
                $newdate=date_Create($_POST["date"]);
                $date=date_format($newdate,"Y-m-d");
                }
                else {
                  $date= NULL;
                }
              }

              {
                if(!empty($_POST["time"])){
                $newtime=date_Create($_POST["time"]);
                $time=date_format($newtime,"H:i:s");
                }
                else {
                  $time=NULL;
                }
              }
              $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS staff_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Staff_SSN,Patient_SSN,d.Designation, c.Description, c.Complication,c.Medicine, c.Allergies FROM medical_adminstration c,patient p, medical_staff d WHERE Patient_SSN=p.SSN AND Staff_SSN=d.SSN  AND (Patient_SSN=? OR DATE_FORMAT(Date_Time,'%Y-%m-%d')=? OR DATE_FORMAT(Date_Time,'%H:%i:%S')=?)";
              $stmt= mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt,$sql)) {
                header("Location:dsearch.php?error=sqlerror");
              }
              else {
                mysqli_stmt_bind_param($stmt, "sss", $did, $date, $time);
                mysqli_stmt_execute($stmt);
                $result= mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result)>0)
                {
                  echo "<div class='welcome'><h2 class='mssg'> Medical Records </h2></div>
                  <div class='table_box'>
                    <table class='content-table'>
                      <thead>
                      <tr>
                      <th>Дата, час</th>
                      <th>Ім'я персоналу</th>
                      <th>Staff ID</th>
                      <th>Позначення</th>
                      <th>Ім'я пацієнта</th>
                      <th>ID пацієнта</th>
                      <th>Опис</th>
                      <th>Ускладнення</th>
                      <th>Ліки</th>
                      <th>Алергії</th>
                      <th>Довідковий номер</th>
                      </tr>
                      </thead>

                  ";

                while ($row = mysqli_fetch_assoc($result)) {
                  $ref=$row["Reference"];
                  $dt=$row["Date_Time"];
                  $dfullname=$row["staff_fullname"];
                  $pfullname=$row["patient_fullname"];
                  $dn=$row["Designation"];
                  $desc=$row["Description"];
                  $compl=$row["Complication"];
                  $med=$row["Medicine"];
                  $alg=$row["Allergies"];
                  $dssn=$row["Staff_SSN"];
                  $pssn=$row["Patient_SSN"];

                  echo "
                  <tbody>
                  <tr>
                    <td>$dt</td>
                    <td>$dfullname</td>
                    <td>$dssn</td>
                    <td>$pfullname</td>
                    <td>$pssn</td>
                    <td>$dn</td>
                    <td>$desc</td>
                    <td>$compl</td>
                    <td>$med</td>
                    <td>$alg</td>
                    <td>$ref</td>
                  </tr>
                  ";


                }
                echo "</tbody></table>

                </div>";
                }
                else if (mysqli_num_rows($result)==0) {
                  echo "<div class='welcome'><h2 class='mssg'> No Медичні записи про дозування Found </h2></div>";
                }
              }
            }
            else {
              $did=$_POST["did"];
              $newdate=date_Create($_POST["date"]);
              $date=date_format($newdate,"Y-m-d");
              $newtime=date_Create($_POST["time"]);
              $time=date_format($newtime,"H:i:s");

              $sql = "SELECT CONCAT(p.SSN,d.SSN,DATE_FORMAT(c.Date_Time,'%Y%m%d%s%i%k')) AS Reference, DATE_FORMAT(c.Date_Time,'%M %D %Y %r') AS Date_Time, CONCAT(d.F_Name,' ',d.L_Name) AS staff_fullname,CONCAT(p.F_Name,' ',p.L_Name) AS patient_fullname,Staff_SSN,Patient_SSN,d.Designation, c.Description, c.Complication,c.Medicine, c.Allergies FROM medical_adminstration c,patient p, medical_staff d WHERE Patient_SSN=p.SSN AND Staff_SSN=d.SSN AND Patient_SSN=? AND DATE_FORMAT(Date_Time,'%Y-%m-%d')=? AND DATE_FORMAT(Date_Time,'%H:%i:%S')=? ";
              $stmt= mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt,$sql)) {
                header("Location:dsearch.php?error=sqlerror2");
              }
              else {
                mysqli_stmt_bind_param($stmt, "sss", $did, $date, $time);
                mysqli_stmt_execute($stmt);
                $result= mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result)>0)
                {
                  echo "<div class='welcome'><h2 class='mssg'> Медичні записи про дозування </h2></div>
                  <div class='table_box'>
                    <table class='content-table'>
                      <thead>
                      <tr>
                      <th>Дата, час</th>
                      <th>Ім'я персоналу</th>
                      <th>Staff ID</th>
                      <th>Позначення</th>
                      <th>Ім'я пацієнта</th>
                      <th>ID пацієнта</th>
                      <th>Опис</th>
                      <th>Ускладнення</th>
                      <th>Ліки</th>
                      <th>Алергії</th>
                      <th>Довідковий номер</th>
                      </tr>
                      </thead>

                  ";

                while ($row = mysqli_fetch_assoc($result)) {
                  $ref=$row["Reference"];
                  $dt=$row["Date_Time"];
                  $dfullname=$row["staff_fullname"];
                  $pfullname=$row["patient_fullname"];
                  $dn=$row["Designation"];
                  $desc=$row["Description"];
                  $compl=$row["Complication"];
                  $med=$row["Medicine"];
                  $alg=$row["Allergies"];
                  $dssn=$row["Staff_SSN"];
                  $pssn=$row["Patient_SSN"];

                  echo "
                  <tbody>
                  <tr>
                    <td>$dt</td>
                    <td>$dfullname</td>
                    <td>$dssn</td>
                    <td>$pfullname</td>
                    <td>$pssn</td>
                    <td>$dn</td>
                    <td>$desc</td>
                    <td>$compl</td>
                    <td>$med</td>
                    <td>$alg</td>
                    <td>$ref</td>
                  </tr>
                  ";


                }
                echo "</tbody></table>

                </div>";
                }
                else if (mysqli_num_rows($result)==0) {
                  echo "<div class='welcome'><h2 class='mssg'> No Медичні записи про дозування Found </h2></div>";
                }
              }
            }

          }


}

?>

<div class="footer">
</div>



  </body>
</html>
