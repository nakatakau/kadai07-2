<?php
include("../app/func.php");
session_start(); //セッションの開始
login_check(); //ログイン状態のチェック
// 日毎の売上集計
$pdo = pdo();
$sql = "SELECT DATE_FORMAT(purchase_date, '%Y-%m-%d') AS time, sum(price * quantity) AS earnings FROM purchase GROUP BY DATE_FORMAT(purchase_date, '%Y-%m-%d') ORDER BY time";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$array = array();
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $array[$i] = $row;
  $i++;
}
$date = json_encode($array, JSON_UNESCAPED_UNICODE);

// 受注件数
$pdo = pdo();
$sql = "SELECT count(DISTINCT purchase_id) as count FROM purchase WHERE flg = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$count = json_encode($row, JSON_UNESCAPED_UNICODE);

// 登録者数 ;
$pdo = pdo();
$sql = "SELECT gender, count(id) as count FROM `registration` GROUP BY gender";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$array = array();
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $array[$i] = $row;
  $i++;
};
$gender = json_encode($array, JSON_UNESCAPED_UNICODE);

// 受注件数
$pdo = pdo();
$sql = "SELECT sum(quantity) as total FROM `stock`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total = json_encode($row, JSON_UNESCAPED_UNICODE);

// 受注件数
$pdo = pdo();
$sql = "SELECT count(keyname) as count FROM `stock`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$items = json_encode($row, JSON_UNESCAPED_UNICODE);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- スタイルシートはココに入れる -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/main.css">
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
      <!-- コンテンツ部分 -->
      <p class="ab">ダッシュボード</p>
      <div class="flex2">
        <div id="content1">
          <p class="title">受注件数</p>
          <p class="sub" id="b">○件</p>
          <p class="title">サイト登録者数</p>
          <p class="sub" id="men">○人</p>
          <p class="sub" id="women">○人</p>
          <p class="title">商品管理数</p>
          <p class="sub" id="c">商品数: ○</p>
          <p class="sub" id="d">在庫数: ○</p>
        </div>
        <div id="graph1">
          <p class="graph-title" id="a"></p>
          <canvas id="myBarChart"></canvas>
        </div>
      </div>
    </main>
  </div>


  <!-- スクリプトはココに入れる -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.js" integrity="sha512-CAv0l04Voko2LIdaPmkvGjH3jLsH+pmTXKFoyh5TIimAME93KjejeP9j7wSeSRXqXForv73KUZGJMn8/P98Ifg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    const json = JSON.stringify(<?= $date ?>);
    const date_earnings = JSON.parse(json);
    const json2 = JSON.stringify(<?= $count ?>);
    const count = JSON.parse(json2);
    console.log(count);
    const b = document.getElementById('b');
    b.textContent = count.count + " 件";
    const json3 = JSON.stringify(<?= $gender ?>);
    const gender = JSON.parse(json3);
    const men = document.getElementById('men');
    const women = document.getElementById('women');
    for (let i = 0; i < gender.length; i++) {
      if (gender[i].gender == "男") {
        men.textContent = "男性 ： " + gender[i].count + " 人"
      } else if (gender[i].gender == "女") {
        women.textContent = "女性 ： " + gender[i].count + " 人"
      }
    }
    const d = document.getElementById('d');
    const json4 = JSON.stringify(<?= $total ?>);
    const total = JSON.parse(json4);
    console.log(total);
    d.textContent = "在庫数 ： " + Number(total.total).toLocaleString();
    const json5 = JSON.stringify(<?= $items ?>);
    const items = JSON.parse(json5);
    const c = document.getElementById('c');
    c.textContent = "商品数 ： " + Number(items.count).toLocaleString();
    console.log(items)
    let date = [];
    // 日付をYYYY-MM-DDの書式で返すメソッド
    function formatDate(dt, i) {
      var y = dt.getFullYear();
      var m = ('00' + (dt.getMonth() + 1)).slice(-2);
      var d = ('00' + (dt.getDate() - i)).slice(-2);
      return (y + '-' + m + '-' + d);
    }
    for (let i = 0; i < 7; i++) {
      let day = formatDate(new Date(), i);
      date[i] = day;
    }
    let earnings = []
    for (let i = 0; i < 7; i++) {
      for (let j = 0; j < date_earnings.length; j++) {
        if (date[i] == date_earnings[j].time) {
          earnings[i] = Number(date_earnings[j].earnings);
        }
      }
    }
    const ctx = document.getElementById("myBarChart");
    const myBarChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [date[6], date[5], date[4], date[3], date[2], date[1], date[0]],
        datasets: [{
          label: '売上',
          data: [earnings[6], earnings[5], earnings[4], earnings[3], earnings[2], earnings[1], earnings[0]],
          backgroundColor: "#66FFCC"
        }]
      },
      options: {
        title: {
          display: true,
          text: '1日当たりの売上'
        },
        scales: {
          yAxes: [{
            ticks: {
              suggestedMax: 50,
              suggestedMin: 0,
              stepSize: 10,
            }
          }]
        },
      }
    });
    const graph_title = document.getElementById(a);
    a.textContent = "日次売上（期間：" + date[6] + " 〜 " + date[0] + "）";
  </script>
</body>

</html>
