<?php
  include('../app/func.php');
  // GETで受け取り
  $purchase_id = $_GET['purchase_id'];
  $sql = "UPDATE purchase SET flg = 2 WHERE purchase_id = $purchase_id";
  $pdo = pdo();
  $stmt  = $pdo->prepare($sql);
  $stmt->execute();
?>
