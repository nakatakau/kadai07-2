<?php
include('func.php');
//POSTパラメータを取得
$itemcode   = $_GET["itemcode"];
$pdo = pdo();
// カラーがセットされていればimgを取得、セットされていなければカラーを取得
$sql = "SELECT color,img FROM itemlist WHERE itemcode = :itemcode";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':itemcode', $itemcode);
$status = $stmt -> execute();
$array = array();
$i = 0;
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
  $array[$i] = $row;
  $i ++;
}
$json = json_encode($array,JSON_UNESCAPED_UNICODE);
//作成したJSON文字列をリクエストしたファイルに返す
echo $json;
exit;
