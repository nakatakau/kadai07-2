<?php
include("../app/func.php");
session_start(); //セッションの開始
login_check(); //ログイン状態のチェック

// POOの開始
$pdo = pdo();
$sql =
"SELECT purchase_id,userid,name,count(itemcode) as items, sum(quantity) as item_num, sum(price * quantity) as total_price,purchase_date,flg,post_num, address, email
          FROM purchase INNER JOIN registration ON purchase.userid = registration.id
          WHERE flg = 1
          GROUP BY purchase_id,userid,name,purchase_date,flg,post_num, address, email
          ORDER BY purchase_id DESC;";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$array = array();
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $array[$i] = $row;
  $i++;
}
$json = json_encode($array, JSON_UNESCAPED_UNICODE);

// SQLの追加
$sql2  = "SELECT * FROM purchase ORDER BY purchase_id DESC";
$pdo2 = pdo();
$stmt2 = $pdo2->prepare($sql2);
$stmt2->execute();
$array2 = array();
$i = 0;
while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
  $array2[$i] = $row2;
  $i++;
}
$json2 = json_encode($array2, JSON_UNESCAPED_UNICODE);

// POOの開始
$pdo3 = pdo();
$sql3 = "SELECT purchase_id,userid,name,count(itemcode) as items,sum(quantity) as item_num,sum(price * quantity) as total_price,purchase_date,flg,post_num, address, email
          FROM purchase INNER JOIN registration ON purchase.userid = registration.id
          WHERE flg = 2
          GROUP BY purchase_id,userid,name,purchase_date,flg,post_num, address, email
          ORDER BY purchase_id DESC;";
$stmt3 = $pdo3->prepare($sql3);
$stmt3->execute();
$array3 = array();
$i = 0;
while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
  $array3[$i] = $row3;
  $i++;
}
$json3 = json_encode($array3, JSON_UNESCAPED_UNICODE);

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
      <div id="shipment">
      </div>
      <div id="shipment-history">
      </div>
      <!-- モーダルウィンドウ -->
      <div id="background" class="hidden">
        <div id="close">
          <i class="las la-window-close"></i>
        </div>
        <div id="modal">
        </div>
      </div>
    </main>
  </div>


  <!-- スクリプトはココに入れる -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <!-- axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <script>
    // -------------------------------------------------------------------
    // 基本情報
    // -------------------------------------------------------------------
    const json = JSON.stringify(<?= $json ?>);
    const data = JSON.parse(json);
    const json2 = JSON.stringify(<?= $json2 ?>);
    const data2 = JSON.parse(json2);
    const json3 = JSON.stringify(<?= $json3 ?>);
    const data3 = JSON.parse(json3);
    console.log(data.length);
    console.log(data2);
    console.log(data3);
    // 出荷待ちのテーブル
    const name = "shipment"
    const name2 = "shipment-history"
    create_table(name, "出荷待ち", data, null, "table1");
    create_table(name2, "出荷完了", data3, "b", "table2");

    function create_table(ele, text_title, array, classname, table_id_name) {
      const shipment = document.getElementById(ele);
      const shipment_title = document.createElement('p');
      shipment_title.className = "title";
      shipment_title.classList.add(classname);
      shipment_title.textContent = text_title;
      shipment.appendChild(shipment_title);
      const table = document.createElement('table');
      table.className = "shipment_data";
      table.id = table_id_name;
      shipment.appendChild(table);
      const tr = document.createElement('tr');
      tr.className = "table_title"
      table.appendChild(tr);
      const th_title = [
        "注文番号",
        "購入日",
        "顧客",
        "住所",
        "商品数",
        "金額",
        "出荷状態"
      ]
      for (let i = 0; i <= 6; i++) {
        const th = document.createElement('th');
        th.className = "cell";
        th.textContent = th_title[i];
        tr.appendChild(th);
      }
      if (array.length == 0) {
        const tr1 = document.createElement('tr');
        tr1.className = "table_none"
        const td1 = document.createElement('td');
        td1.colSpan = 7;
        td1.className = "none_cell"
        td1.textContent = "現在、未出荷商品はございません。";
        tr1.appendChild(td1);
        table.appendChild(tr1);
      }
    }
    // -------------------------------------------------------------------
    // 未出荷データの取得
    // -------------------------------------------------------------------
    create_cell(data, 1);
    create_cell(data3, 2);

    function create_cell(data, ck) {
      let table;
      if (ck == 1) {
        table = document.getElementById('table1')
      } else {
        table = document.getElementById('table2');
      }
      for (let i = 0; i < data.length; i++) {
        const tr = document.createElement('tr');
        tr.className = "data_tr"
        const data_array = [
          data[i].purchase_id,
          data[i].purchase_date,
          data[i].name,
          data[i].address,
          data[i].item_num,
          Number(data[i].total_price).toLocaleString(),
          null
        ]
        for (let j = 0; j <= 6; j++) {
          const td = document.createElement('td');
          td.className = "cell";
          if (j == 6) {
            if (ck == 1) {
              const btn = document.createElement('button');
              btn.className = "shipment_ck";
              btn.dataset.purchase_id = data_array[0];
              btn.dataset.name = data_array[2];
              btn.dataset.address = data_array[3];
              btn.textContent = "出荷確認";
              btn.addEventListener('click', (e) => {
                if (confirm("出荷処理を行いますか？\n購入番号：" + e.target.dataset.purchase_id + "\nお客様：" + e.target.dataset.name + "\n住所：" + e.target.dataset.address)) {
                  const background = document.getElementById('background');
                  background.classList.remove('hidden');
                  const modal = document.getElementById('modal');
                  modal.innerHTML = "";
                  let id;
                  // ----------------------------------
                  for (let i = 0; i < data2.length; i++) {
                    if (data2[i].purchase_id == e.target.dataset.purchase_id) {
                      const purchase_item = document.createElement('div');
                      purchase_item.className = "purchase_item";
                      modal.appendChild(purchase_item);
                      id = data2[i].purchase_id;
                      // カートエリアの作成
                      const cart_box = document.createElement('div');
                      cart_box.className = "purchase_box";
                      cart_box.id = "num" + i;
                      purchase_item.appendChild(cart_box);
                      // 画像の表示
                      const img = document.createElement('img');
                      img.className = "cart_img";
                      // imgのタイトルを取得
                      let img_name = data2[i].img;
                      // 空白は%20に変換
                      img_name = img_name.replace(" ", "%20");
                      // severによってルートの切替
                      let root = "../img/";
                      const url = root + img_name;
                      img.src = url;
                      img.dataset.fname = data2[i].img;
                      cart_box.appendChild(img);
                      // テキストメニュー
                      const cart_text_box = document.createElement('div');
                      cart_text_box.className = "cart_text_box";
                      cart_box.appendChild(cart_text_box);
                      // テキスト（商品名、カラー、サイズ）
                      const cart_text1 = document.createElement('div');
                      cart_text1.className = "purchase_text1";
                      cart_text_box.appendChild(cart_text1);
                      // 商品名、カラー、サイズの追加
                      const title1 = document.createElement('p');
                      title1.textContent = "商品情報";
                      title1.className = "title1";
                      cart_text1.appendChild(title1);
                      const cart_text_title = document.createElement('p');
                      cart_text_title.className = "cart_text_title";
                      cart_text_title.dataset.name = data2[i].name;
                      cart_text_title.dataset.itemcode = data2[i].itemcode;
                      cart_text_title.textContent = data2[i].itemcode;
                      cart_text1.appendChild(cart_text_title);
                      const color = document.createElement('p');
                      color.className = "color1";
                      color.dataset.color = data2[i].color;
                      color.textContent = "カラー：" + data2[i].color;
                      cart_text1.appendChild(color);
                      const size = document.createElement('p');
                      size.className = "size";
                      size.dataset.size = data2[i].size;
                      size.textContent = "サイズ：" + data2[i].size;
                      cart_text1.appendChild(size);
                      // テキスト（金額、数量）
                      const cart_text2 = document.createElement('div');
                      cart_text2.className = "purchase_text2";
                      cart_text_box.appendChild(cart_text2);
                      // 金額、数量の追加
                      const title2 = document.createElement('p');
                      title2.textContent = "購入数";
                      title2.className = "title2";
                      cart_text2.appendChild(title2);
                      const flex = document.createElement('div');
                      flex.className = "flex";
                      cart_text2.appendChild(flex);
                      const price = document.createElement('p');
                      price.className = "price";
                      price.dataset.price = data2[i].price;
                      const money = Number(data2[i].price).toLocaleString();
                      price.textContent = "¥  " + money + "  (税込)";
                      flex.appendChild(price);
                      const quantity = document.createElement('p');
                      quantity.dataset.quantity = data2[i].quantity
                      quantity.className = "quantity";
                      quantity.textContent = data2[i].quantity;
                      flex.appendChild(quantity);
                      const p = document.createElement('p');
                      p.className = "ko";
                      p.textContent = "個";
                      flex.appendChild(p);
                      const flex_second = document.createElement('div');
                      flex_second.className = "flex a";
                      cart_text2.appendChild(flex_second);
                      const check = document.createElement('p');
                      check.className = "check_text";
                      check.textContent = "出荷準備完了"
                      flex_second.appendChild(check);
                      const check_box = document.createElement('input');
                      check_box.type = "checkbox";
                      check_box.className = "check_btn";
                      check_box.dataset.id = "num" + i;
                      check_box.addEventListener('change', (e) => {
                        if (e.target.checked == true) {
                          const target = document.getElementById(e.target.dataset.id);
                          target.style.border = "2px #08bdfee5 solid";
                          target.style.padding = "1px";
                        } else {
                          const target = document.getElementById(e.target.dataset.id);
                          target.style.border = "";
                          target.style.padding = "3px";
                        }
                      })
                      flex_second.appendChild(check_box);
                    }
                  }
                  const btn_area = document.createElement('div');
                  btn_area.className = "btn_area";
                  modal.appendChild(btn_area);
                  const post_btn = document.createElement('button');
                  post_btn.id = "post_btn";
                  post_btn.textContent = "出荷完了処理";
                  post_btn.addEventListener('click', (e) => {
                    const check_btn = document.querySelectorAll('.check_btn');
                    for (let l = 0; l < check_btn.length; l++) {
                      if (check_btn[l].checked == false) {
                        alert('出荷完了にチェックをつけてください。');
                        exit();
                      }
                    }
                    const purchase_id = {
                      purchase_id: id
                    };
                    axios.get('../app/shipment_complete.php', {
                      params: purchase_id
                    }).then(function() {
                      alert('処理が完了しました。')
                      window.location.href = "shipment.php";
                    }).catch(function(error) {
                      alert('エラー:' + error)
                      exit();
                    })
                  })
                  btn_area.appendChild(post_btn);
                }
              });
              td.appendChild(btn);
            } else {
              td.textContent = "作業完了";
              td.classList.add ("in_line");
            }
          } else {
            td.textContent = data_array[j];
          }
          tr.appendChild(td);
        }
        table.appendChild(tr);
      }
    }
    const close = document.getElementById('close');
    close.addEventListener('click', (e) => {
      setTimeout(() => {
        background.classList.add('hidden');
      }, 100);
    })
  </script>
</body>

</html>
