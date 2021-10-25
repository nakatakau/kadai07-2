<?php
include("../app/func.php");
session_start(); //セッションの開始
login_check(); //ログイン状態のチェック

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
      <!-- ディレクトリバー -->
      <?php include("../parts/directory.php") ?>
      <!-- コンテンツ部分 -->
      <div id="Product_registration">
        <form action="../app/product.php" method="post" id="product_postform" enctype="multipart/form-data">
          <p class="title">商品情報を入力</p>
          <div class="product_info">
            <div class="info">
              <select name="gender" required>
                <option value="" disabled selected>性別</option>
                <option value="mens">メンズ</option>
                <option value="mens_and_ladies">ウィメンズ</option>
                <option value="ladies">レディース</option>
              </select>
              <select name="big_category" id="bigcategory" required>
                <option value="" disabled selected>大分類を選択</option>
                <option value="tops">トップス</option>
                <option value="bottoms">ボトムス</option>
                <option value="bag">バッグ</option>
                <option value="accessories">アクセサリー</option>
              </select>
              <select name="small_category" id="smallcategory" required>
                <option value='' disabled selected>--------------</option>
              </select>
            </div>
            <div class="info">
              <input type="text" placeholder="商品コード（例:AA0000）" name="itemcode" required pattern="(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{6,}">
              <input type="text" placeholder="商品名" name="name" required>
              <input type="text" placeholder="売値（半角数字）" name="price" required pattern="[0-9]+">
            </div>
          </div>
          <p class="title">カラーバリエーション</p>
          <div id="img_flex">
            <div class="upload_area">
              <img id="preview1" src="../img/display-none.png" name="preview1">
              <select name="color1" class="color" required>
                <option value="" disabled selected>色を選択</option>
                <option value="black">black</option>
                <option value="gray">gray</option>
                <option value="blue">blue</option>
                <option value="white">white</option>
                <option value="yellow">yellow</option>
                <option value="red">red</option>
                <option value="pink">pink</option>
                <option value="orange">orange</option>
                <option value="brown">brown</option>
                <option value="purple">purple</option>
                <option value="green">green</option>
              </select>
              <input type="file" name="upload1" accept=".png, .jpg, .jpeg, .pdf, .doc" class="upload" required>
            </div>
            <div class="upload_area">
              <img id="preview2" src="../img/display-none.png" name="preview2">
              <select name="color2" class="color">
                <option value="" disabled selected>色を選択</option>
                <option value="black">black</option>
                <option value="gray">gray</option>
                <option value="blue">blue</option>
                <option value="white">white</option>
                <option value="yellow">yellow</option>
                <option value="red">red</option>
                <option value="pink">pink</option>
                <option value="orange">orange</option>
                <option value="brown">brown</option>
                <option value="purple">purple</option>
                <option value="green">green</option>
              </select>
              <input type="file" name="upload2" accept=".png, .jpg, .jpeg, .pdf, .doc" class="upload">
            </div>
            <div class="upload_area">
              <img id="preview3" src="../img/display-none.png" name="preview3">
              <select name="color3" class="color">
                <option value="" disabled selected>色を選択</option>
                <option value="black">black</option>
                <option value="gray">gray</option>
                <option value="blue">blue</option>
                <option value="white">white</option>
                <option value="yellow">yellow</option>
                <option value="red">red</option>
                <option value="pink">pink</option>
                <option value="orange">orange</option>
                <option value="brown">brown</option>
                <option value="purple">purple</option>
                <option value="green">green</option>
              </select>
              <input type="file" name="upload3" accept=".png, .jpg, .jpeg, .pdf, .doc" class="upload">
            </div>
            <div class="upload_area">
              <img id="preview4" src="../img/display-none.png" name="preview4">
              <select name="color4" class="color">
                <option value="" disabled selected>色を選択</option>
                <option value="black">black</option>
                <option value="gray">gray</option>
                <option value="blue">blue</option>
                <option value="white">white</option>
                <option value="yellow">yellow</option>
                <option value="red">red</option>
                <option value="pink">pink</option>
                <option value="orange">orange</option>
                <option value="brown">brown</option>
                <option value="purple">purple</option>
                <option value="green">green</option>
              </select>
              <input type="file" name="upload4" accept=".png, .jpg, .jpeg, .pdf, .doc" class="upload">
            </div>
            <div class="upload_area">
              <img id="preview5" src="../img/display-none.png" name="preview5">
              <select name="color5" class="color" placeholder="色を選択">
                <option value="" disabled selected>色を選択</option>
                <option value="black">black</option>
                <option value="gray">gray</option>
                <option value="blue">blue</option>
                <option value="white">white</option>
                <option value="yellow">yellow</option>
                <option value="red">red</option>
                <option value="pink">pink</option>
                <option value="orange">orange</option>
                <option value="brown">brown</option>
                <option value="purple">purple</option>
                <option value="green">green</option>
              </select>
              <input type="file" name="upload5" accept=".png, .jpg, .jpeg, .pdf, .doc" class="upload">
            </div>
            <div class="upload_area">
              <img id="preview6" src="../img/display-none.png" name="preview6">
              <select name="color6" class="color">
                <option value="" disabled selected>色を選択</option>
                <option value="black">black</option>
                <option value="gray">gray</option>
                <option value="blue">blue</option>
                <option value="white">white</option>
                <option value="yellow">yellow</option>
                <option value="red">red</option>
                <option value="pink">pink</option>
                <option value="orange">orange</option>
                <option value="brown">brown</option>
                <option value="purple">purple</option>
                <option value="green">green</option>
              </select>
              <input type="file" name="upload6" accept=".png, .jpg, .jpeg, .pdf, .doc" class="upload">
            </div>
          </div>
          <p class="title"></p>
          <input type="submit" id="product_submit" class="submit_btn" value="商品登録" onclick="return itemCheck()">
        </form>
      </div>
    </main>
  </div>


  <!-- スクリプトはココに入れる -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <script>
    // --------------------------------------------
    //画像のアップロード
    // --------------------------------------------
    const upload = document.getElementsByClassName('upload');
    let uploads = Array.from(upload);
    uploads.forEach((target) => {
      target.addEventListener('change', (e) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const preview = target.previousElementSibling.previousElementSibling; //2つ前の要素を取得
          console.log(preview);
          preview.src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
      });
    });
    // --------------------------------------------
    //フォーム入力のアクション
    // --------------------------------------------
    // 入力されたら色をつける（inputタグ）
    const input = document.querySelectorAll('input[type="text"]');
    input.forEach((target) => {
      target.addEventListener('change', e => {
        e.target.style.color = "black";
        e.target.style.borderColor = "black";
      });
    })
    // 入力されたら色をつける（selectタグ）
    const select = document.querySelectorAll('select');
    select.forEach((target) => {
      target.addEventListener('change', e => {
        e.target.style.color = "black";
        e.target.style.borderColor = "black";
      });
    })
    const big_category = document.getElementById('bigcategory');
    const small_category = document.getElementById('smallcategory');
    // 大分類の値によって小分類を選択
    big_category.addEventListener('change', () => {
      let option = "<option value='' disabled selected>小分類を選択</option>"
      if (big_category.value == "tops") {
        option += "<option value='jacket'>ジャケット</option>";
        option += "<option value='down'>ダウンジャケット</option>";
        option += "<option value='coat'>コート</option>";
        option += "<option value='down'>ダウンジャケット</option>";
        option += "<option value='shirt'>シャツ</option>";
        option += "<option value='sweat'>スウェット</option>";
        option += "<option value='fleece'>フリース</option>";
      } else if (big_category.value == "bottoms") {
        option += "<option value='l_pants'>ロングパンツ</option>";
        option += "<option value='s_pants'>ショートパンツ</option>";
      } else if (big_category.value == "bag") {
        option += "<option value='backpack'>バックパック</option>";
        option += "<option value='shoulder_bag'>ショルダーバック</option>";
        option += "<option value='totebag'>トートバック</option>";
        option += "<option value='other'>その他</option>";
      } else if (big_category.value == "accessories") {
        option += "<option value='cap'>キャップ</option>";
        option += "<option value='hat'>ハット</option>";
        option += "<option value='grove'>グローブ</option>";
        option += "<option value='towel'>タオル</option>";
        option += "<option value='other'>その他</option>";
      }
      small_category.innerHTML = "";
      small_category.innerHTML = option;
    })
    const color = document.querySelectorAll('.color');
    color.forEach((target) => {
      target.addEventListener('change', e => {
        console.log(e.target.value);
        e.target.style.backgroundColor = e.target.value;
      });
    })

    // --------------------------------------------
    //フォーム送信時のチェック
    // --------------------------------------------
    const form = document.getElementById('product_postform');
  </script>
</body>

</html>
