<?php
include('func.php');
$counter  = $_POST['counter'];
// postデータの取得
for ($i = 1; $i <= $counter; $i++) {
  if (isset($_POST['itemcode' . $i])) {
    if ($_POST['itemcode' . $i] != "") {
      $itemcodecolor[]  = $_POST['itemcode' . $i] . $_POST['color' . $i];
      $itemcode[] = $_POST['itemcode' . $i];
      $color[]    = $_POST['color' . $i];
      $size_quantity[]    = [
        $_POST['size_sel1' . $i] . "/" . $_POST['quantity1' . $i],
        $_POST['size_sel2' . $i] . "/" . $_POST['quantity2' . $i],
        $_POST['size_sel3' . $i] . "/" . $_POST['quantity3' . $i],
        $_POST['size_sel4' . $i] . "/" . $_POST['quantity4' . $i],
      ];
    }
  }
}

// PDOの開始
$pdo = pdo();

// SQl
$sql = "INSERT INTO stock (keyname, itemcode, color, size, quantity, updatetime)
          VALUES (:keyname,:itemcode, :color, :size, :quantity, sysdate())
          ON  DUPLICATE KEY UPDATE
              keyname  = :keyname,
          		itemcode = :itemcode,
              color    = :color,
              size     = :size,
              quantity = quantity + :quantity,
              updatetime = sysdate();";

$stmt = $pdo->prepare($sql);
$i = 0;
foreach ($size_quantity as $array) {
  foreach ($array as $items) {
    $item = explode("/", $items);
    if(!$item[0] == ""){
      $size = $item[0];
      $quantity = $item[1];
      // echo $i;
      // echo "<br>";
      // echo "---------------------------------------------";
      // echo "<br>";
      // echo "サイズ：" . $size . "数量：" . $quantity ."<br>";
      $keyname = $itemcodecolor[$i].$size;
      $itemcode_ele = $itemcode[$i];
      $color_ele    = $color[$i];
      // echo "キー :".$keyname."商品コード:".$itemcode_ele."カラー：".$color_ele."<br>";
      // echo "---------------------------------------------";
      // echo "<br>";
      $stmt -> bindValue(':keyname',$keyname,PDO::PARAM_STR);
      $stmt -> bindValue(':itemcode',$itemcode_ele,PDO::PARAM_STR);
      $stmt -> bindValue(':color',$color_ele,PDO::PARAM_STR);
      $stmt -> bindValue(':size',$size,PDO::PARAM_STR);
      $stmt -> bindValue(':quantity',$quantity,PDO::PARAM_INT);
      $status = $stmt->execute();
    }
  }
  $i++;
}

header("Location: ../view/in_stock.php");
