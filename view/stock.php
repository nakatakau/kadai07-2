<?php
include("../app/func.php");
session_start(); //セッションの開始
login_check(); //ログイン状態のチェック

// POOの開始
$pdo = pdo();
$sql = "SELECT keyname, itemlist.itemcode, itemlist.name, big_category, small_category, gender, price, img, itemlist.color, size, quantity
          FROM itemlist LEFT JOIN stock ON itemlist.itemcode = stock.itemcode AND itemlist.color = stock.color;";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$array = array();
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $array[$i] = $row;
  $i++;
}
$json = json_encode($array, JSON_UNESCAPED_UNICODE);
?>
<!-- ここからHTMLとJS -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- スタイルシートはココに入れる -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/modal.css">
</head>

<body>
  <!-- ヘッダー部分 -->
  <?php include('../parts/header.php') ?>
  <!-- メイン部分 -->
  <div class="container-fluid">
    <!-- サイドバー -->
    <?php include("../parts/sidebar.php") ?>
    <!-- メインフィールド -->
    <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 py-4">
      <!-- ディレクトリバー -->
      <?php include("../parts/directory.php") ?>
      <!-- コンテンツ部分 -->
      <p class="title">在庫一覧</p>
      <table id="item_list">
      </table>
    </main>
  </div>


  <!-- スクリプトはココに入れる -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <!-- axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    const json = JSON.stringify(<?= $json ?>);
    const data = JSON.parse(json);
    console.log(data);

    // テーブルにthを差し込む
    const item_list = document.getElementById('item_list');
    const tr = document.createElement('tr');
    item_list.appendChild(tr);
    for (let i = 0; i < 7; i++) {
      const label = [
        "商品画像",
        "商品名",
        "カテゴリー",
        "販売価格",
        "カラー",
        "サイズ",
        "在庫数",
      ]
      const th = document.createElement('th');
      th.textContent = label[i];
      th.className = "cell";
      if (i == 1) {
        th.classList.add('font_small');
      }
      tr.appendChild(th);
    }
    for (let i = 0; i < data.length; i++) {
      const tr = document.createElement('tr');
      item_list.appendChild(tr);
      const label = [
        data[i].img,
        data[i].itemcode + " / " + data[i].name,
        data[i].big_category + " / " + data[i].small_category,
        "¥"+Number(data[i].price).toLocaleString(),
        data[i].color,
        data[i].size,
        Number(data[i].quantity).toLocaleString()
      ]
      for (j = 0; j < 7; j++) {
        const td = document.createElement('td');
        if (j == 0) {
          const img = document.createElement('img');
          img.className = "list_img"
          img.src = "../img/" + label[j];
          td.appendChild(img);
        } else {
          if (label[j] == null) {
            td.textContent = "サイズ未入荷"
            tr.style.background = "rgb(255, 210, 210)";
          } else {
            td.textContent = label[j];
          }
        }
        td.className = "cell";
        if (j == 6) {
          if (Number(data[i].quantity) < 3) {
            tr.style.background = "rgb(255, 210, 210)";
          }
        }
        if (j == 1) {
          td.classList.add('font_small');
        }
        tr.appendChild(td);
      }
    }
  </script>
</body>

</html>
