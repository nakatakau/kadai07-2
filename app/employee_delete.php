<?php
include('func.php');
session_start(); //セッションの開始
login_check(); //ログイン状態のチェック
$uid = (int) $_GET['uid'];
$change = 3;
$sql = "UPDATE employee SET flg = $change WHERE uid = $uid;";
$pdo = pdo();
$stmt = $pdo->prepare($sql);
$stmt->execute();
