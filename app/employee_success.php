<?php
  include('func.php');
  session_start(); //セッションの開始
  login_check(); //ログイン状態のチェック
  $user_name = $_GET['user_name'];
  $pass  = $_GET['pass'];
  $authority= $_GET['authority'];
  $sql = "INSERT INTO employee(uname,password,flg) VALUES(:uname,:password,:flg);";
  $pdo = pdo();
  $stmt = $pdo -> prepare($sql);
  $stmt->bindValue(':uname',$user_name);
  $stmt->bindValue(':password',$pass);
  $stmt->bindValue(':flg',$authority);
  $stmt->execute();
