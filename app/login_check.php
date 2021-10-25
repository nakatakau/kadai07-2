<?php
  session_start();
  // -----------------------------------------------------
  // 1.pdoの実行
  // -----------------------------------------------------
  include("func.php");
  $pdo = pdo();
  $sql = "SELECT * FROM employee WHERE password = :password";
  $stmt = $pdo -> prepare($sql);
  $stmt -> bindValue(":password", h($_POST["password"]), PDO::PARAM_STR);
  $status = $stmt -> execute();
  $val = $stmt->fetch();

  // -----------------------------------------------------
  // 2.戻り値の確認
  // -----------------------------------------------------
  if($status == false || $val == false || $val['flg'] == 3){
    header("Location: ../view/login.php");
  } else {
    // ログインが成功したならば
    $_SESSION["chk_ssid"]  = session_id();
    $_SESSION["user"]     = $val['uname'];
    $_SESSION["flg"]       = $val["flg"];
    header("Location: ../view/index.php");
  }
