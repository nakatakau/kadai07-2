<?php
include("../app/func.php");
session_start(); //セッションの開始
login_check(); //ログイン状態のチェック
// ------------------------------------------------
// SQLの実行
// ------------------------------------------------
$pdo = pdo();
$sql = "SELECT DISTINCT itemcode FROM itemlist";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
// 商品コードを1行ずつ取得
$array = array();
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $array[$i] = $row;
  $i++;
}
$json = json_encode($array, JSON_UNESCAPED_UNICODE);
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
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
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
      <form action="../app/in_stock_post.php" method="post" id="in_stock_form">
        <input type="hidden" name="counter" id="counter">
        <div id="stock"></div>
      </form>
    </main>
  </div>


  <!-- スクリプトはココに入れる -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <script src="../js/func.js"></script>
  <!-- axios -->
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
    // DOM
    // ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
    // formの作成
    // ストックへのアクセス
    const stock = document.getElementById('stock');
    // 切り替え用のタブを設置
    const ul = ele("ul", "tab_list", "tab_list");
    const li1 = ele("li", "tab_menu tab_active", "", "入荷確定");
    // タブをクリックした時にtab_activeクラスを追加
    li1.addEventListener('click', (e) => {
      if (!e.target.classList.contains("tab_active")) {
        e.target.classList.add("tab_active");
        const next = e.target.nextElementSibling;
        next.classList.remove("tab_active");
      }
    })
    const li2 = ele("li", "tab_menu", "", "在庫確認");
    // タブをクリックした時にtab_activeクラスを追加
    li2.addEventListener('click', (e) => {
      if (!e.target.classList.contains("tab_active")) {
        e.target.classList.add("tab_active");
        const pre = e.target.previousElementSibling;
        pre.classList.remove("tab_active");
      }
    })
    stock.appendChild(ul);
    ul.appendChild(li1);
    ul.appendChild(li2);
    // 追加カウント用
    let counter = 1;
    const count = document.getElementById('counter');
    count.value = counter;
    // 商品入荷予定の入力画面を追加
    const in_stock_area = ele('div', 'in_stock_area', 'in_stock_area' + counter);
    ul.after(in_stock_area);
    // イメージエリアの作成
    const img = ele('img', 'item_img', "item_img");
    img.src = "../img/display-none.png";
    in_stock_area.appendChild(img);
    // 商品名のセレクトボックス
    const item_area = ele('div', 'item_area');
    in_stock_area.appendChild(item_area);
    const select_area = ele('div', 'select_area', "itemcode");
    item_area.appendChild(select_area);
    const text = ele('p', 'item_code_text', '', '商品名の選択');
    const select = ele('select', 'item_code_select');
    select.name = "itemcode" + counter;
    let select_data;
    // axiosイベントを追加
    select.addEventListener('change', (e) => {
      const select = e.target;
      // valueデータのセット
      const data = {
        itemcode: select.value
      }
      axios.get('../app/color.php', {
        params: data
      }).then(function(response) {
        select_data = response.data;
        const color = document.getElementById('color');
        color.innerHTML = "";
        for (let i = 0; i < select_data.length; i++) {
          if (i == 0) {
            let option = ele('option');
            option.text = "選択してください";
            color.appendChild(option);
          }
          let option = ele('option');
          option.value = select_data[i].color;
          option.text = select_data[i].color;
          color.appendChild(option);
        }
      }).catch(function(error) {
        console.log(error); //通信Error
      });
    })
    // オプションに商品コードを追加
    const json = JSON.stringify(<?= $json ?>);
    const itemcode = JSON.parse(json);
    for (let i = 0; i < itemcode.length; i++) {
      if (i == 0) {
        let option = ele('option');
        option.text = "選択してください";
        option.value = "";
        select.appendChild(option);
      }
      let option = ele('option');
      option.value = itemcode[i].itemcode;
      option.text = itemcode[i].itemcode;
      select.appendChild(option);
    }
    select_area.appendChild(text);
    select_area.appendChild(select);
    // 色のセレクトボックス
    const select_area2 = ele('div', 'select_area');
    item_area.appendChild(select_area2);
    const text2 = ele('p', 'item_code_text', '', 'カラーの選択');
    const select2 = ele('select', 'item_code_select', "color");
    select2.name = "color" + counter;
    const option2 = ele('option');
    option2.text = "--------------";
    option2.value = "";
    select2.addEventListener('change', (e) => {
      const color = e.target.value;
      console.log(color);
      for (let i = 0; i < select_data.length; i++) {
        const target = document.getElementById('item_img');
        if (select_data[i].color == color) {
          const img = select_data[i].img;
          const url = "../img/";
          target.src = url + img;
        } else if (color == "選択してください") {
          target.src = "../img/display-none.png";
        }
      }
    })
    select2.appendChild(option2);
    select_area2.appendChild(text2);
    select_area2.appendChild(select2);
    // サイズと入荷量のチェック
    const size_area = ele('div', 'size_area');
    in_stock_area.appendChild(size_area);
    for (let i = 1; i <= 4; i++) {
      const size_box = ele('div', 'size_box');
      size_area.appendChild(size_box);
      const size_text = ele('p', 'size_text');
      size_text.textContent = "サイズを選択"
      size_box.appendChild(size_text);
      const size_sel = ele('select', 'size_select');
      size_sel.name = "size_sel" + i + counter;
      console.log(size_sel.name);
      const size_array = ["S", "M", "L", "XL", "FREE"];
      for (let i = 0; i < size_array.length; i++) {
        if (i == 0) {
          let option = ele('option');
          option.text = "";
          option.value = "";
          size_sel.appendChild(option);
        }
        let option = ele('option');
        option.value = size_array[i];
        option.text = size_array[i];
        size_sel.appendChild(option);
      }
      size_box.appendChild(size_sel);
      const quantity_text = ele('p', 'quantity_text');
      quantity_text.textContent = "入荷数量"
      size_box.appendChild(quantity_text);
      const quantity = ele('input', 'quantity_text');
      quantity.type = "text";
      quantity.name = "quantity" + i + counter;
      quantity.pattern = "[0-9]+";
      size_box.appendChild(quantity);
    }
    // 追加ボタン
    const add = ele('div', 'add_btn')
    add.addEventListener('click', () => {
      add_cont();
    })
    const icon = ele('i', 'las la-chevron-circle-down');
    const add_text = ele('p', 'add_text');
    add_text.textContent = "追加";
    add.appendChild(icon);
    add.appendChild(add_text);
    in_stock_area.after(add);
    // 送信ボタン
    const btn = ele('input', 'submit_btn', 'stock_submit_btn');
    btn.type = "submit";
    btn.value = "送信";
    const form = document.getElementById('in_stock_form');
    form.appendChild(btn);
    // ---------------------------------------------------------------------
    // 追加用 関数
    // ---------------------------------------------------------------------
    function add_cont() {
      const target1 = document.getElementById('in_stock_area' + counter);
      const url = target1.children[0].src.split('/');
      if (url[5] == "display-none.png") {
        alert(counter + '番目の商品が選択されていません。')
      } else {
        counter++;
        // hiddenに追加
        count.value = counter;
        // 商品入荷予定の入力画面を追加
        const in_stock_area = ele('div', 'in_stock_area', 'in_stock_area' + counter);
        target1.after(in_stock_area);
        // イメージエリアの作成
        const img = ele('img', 'item_img', "item_img" + counter);
        img.src = "../img/display-none.png";
        in_stock_area.appendChild(img);
        // 商品名のセレクトボックス
        const item_area = ele('div', 'item_area');
        in_stock_area.appendChild(item_area);
        const select_area = ele('div', 'select_area', "itemcode");
        item_area.appendChild(select_area);
        const text = ele('p', 'item_code_text', '', '商品名の選択');
        const select = ele('select', 'item_code_select');
        select.name = "itemcode" + counter;
        let select_data;
        // axiosイベントを追加
        select.addEventListener('change', (e) => {
          const select = e.target;
          // valueデータのセット
          const data = {
            itemcode: select.value
          }
          axios.get('../app/color.php', {
            params: data
          }).then(function(response) {
            select_data = response.data;
            const color = document.getElementById('color' + counter);
            color.innerHTML = "";
            for (let i = 0; i < select_data.length; i++) {
              if (i == 0) {
                let option = ele('option');
                option.text = "選択してください";
                color.appendChild(option);
              }
              let option = ele('option');
              option.value = select_data[i].color;
              option.text = select_data[i].color;
              color.appendChild(option);
            }
          }).catch(function(error) {
            console.log(error); //通信Error
          });
        })
        // オプションに商品コードを追加
        const json = JSON.stringify(<?= $json ?>);
        const itemcode = JSON.parse(json);
        for (let i = 0; i < itemcode.length; i++) {
          if (i == 0) {
            let option = ele('option');
            option.text = "選択してください";
            option.value = "";
            select.appendChild(option);
          }
          let option = ele('option');
          option.value = itemcode[i].itemcode;
          option.text = itemcode[i].itemcode;
          select.appendChild(option);
        }
        select_area.appendChild(text);
        select_area.appendChild(select);
        // 色のセレクトボックス
        const select_area2 = ele('div', 'select_area');
        item_area.appendChild(select_area2);
        const text2 = ele('p', 'item_code_text', '', 'カラーの選択');
        const select2 = ele('select', 'item_code_select', "color" + counter);
        select2.name = "color" + counter;
        const option2 = ele('option');
        option2.text = "";
        option2.value = "";
        select2.addEventListener('change', (e) => {
          const color = e.target.value;
          for (let i = 0; i < select_data.length; i++) {
            const target = document.getElementById('item_img' + counter);
            if (select_data[i].color == color) {
              const img = select_data[i].img;
              const url = "../img/";
              target.src = url + img;
            } else if (color == "選択してください") {
              target.src = "../img/display-none.png";
            }
          }
        })
        select2.appendChild(option2);
        select_area2.appendChild(text2);
        select_area2.appendChild(select2);
        // サイズと入荷量のチェック
        const size_area = ele('div', 'size_area');
        in_stock_area.appendChild(size_area);
        for (let i = 1; i <= 4; i++) {
          const size_box = ele('div', 'size_box');
          size_area.appendChild(size_box);
          const size_text = ele('p', 'size_text');
          size_text.textContent = "サイズを選択"
          size_box.appendChild(size_text);
          const size_sel = ele('select', 'size_select');
          size_sel.name = "size_sel" + i + counter;
          const size_array = ["S", "M", "L", "XL", "FREE"];
          for (let i = 0; i < size_array.length; i++) {
            if (i == 0) {
              let option = ele('option');
              option.text = "";
              option.value = "";
              size_sel.appendChild(option);
            }
            let option = ele('option');
            option.value = size_array[i];
            option.text = size_array[i];
            size_sel.appendChild(option);
          }
          size_box.appendChild(size_sel);
          const quantity_text = ele('p', 'quantity_text');
          quantity_text.textContent = "入荷数量"
          size_box.appendChild(quantity_text);
          const quantity = ele('input', 'quantity_text');
          quantity.type = "text";
          quantity.name = "quantity" + i + counter;
          quantity.pattern = "[0-9]+";
          size_box.appendChild(quantity);
        }
      }
    }
  </script>
</body>

</html>
