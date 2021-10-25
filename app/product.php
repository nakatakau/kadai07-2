<?php
  require('func.php');
  // ---------------------------------
  // 1.DBへ接続
  // ---------------------------------
  $pdo = pdo();
  // ---------------------------------
  // 2.POSTデータの取得
  // ---------------------------------
  $gender         = $_POST["gender"];
  $big_category   = $_POST['big_category'];
  $small_category = $_POST['small_category'];
  $itemcode       = $_POST['itemcode'];
  $name           = $_POST['name'];
  $price          = $_POST['price'];
  $colors         = array();
  $images         = array();
  // colorがセットされていれば配列に格納
  for($i = 1; $i<=6; $i++){
    if (isset($_POST['color'.$i])) {
      $colors[$i-1] = $_POST['color'.$i];
    }
  }
  // imgがセットされていれば配列に格納
  for ($i = 1; $i <= 6; $i++) {
    if (!($_FILES['upload'.$i]['name']) == "") {
      $images[$i-1] = $_FILES['upload'.$i]['name'];
      // imgのアップロード処理
      $upload = "../img/";
      if(move_uploaded_file($_FILES['upload'.$i]['tmp_name'],$upload.$images[$i-1])){
        // file:upload OK
      } else {
        echo "upload failed";
        echo $_FILES['fname']['error'];
      }
    }
  }

  // ---------------------------------
  // 3.SQLサーバーへ送信
  // ---------------------------------
  $sql = "INSERT INTO itemlist (itemcode, name, big_category, small_category, gender, price, img, color, date)
            VALUES (:itemcode, :name, :big_category, :small_category, :gender, :price, :img, :color, sysdate())";
  $stmt = $pdo->prepare($sql);
  // 画像のファイル分送信処理を実行
  $i = 0;
  foreach($images as $img){
    $stmt->bindValue(':itemcode', h($itemcode), PDO::PARAM_STR);
    $stmt->bindValue(':name', h($name), PDO::PARAM_STR);
    $stmt->bindValue(':big_category', h($big_category), PDO::PARAM_STR);
    $stmt->bindValue(':small_category', h($small_category), PDO::PARAM_STR);
    $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindValue(':price', $price, PDO::PARAM_INT);
    $stmt->bindValue(':img', $img, PDO::PARAM_STR);
    // colorにデータがあれば
    if(isset($colors[$i])){
      $stmt->bindValue(':color', $colors[$i], PDO::PARAM_STR);
    } else {
      $stmt->bindValue(':color', 'none', PDO::PARAM_STR);
    }
    $status = $stmt->execute();
    $i++ ;
  }

  // ---------------------------------
  // 4.送信チェック
  // ---------------------------------
  if($status == false){
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
  }else{
    header("Location: ../view/item.php");
    exit;
  }
