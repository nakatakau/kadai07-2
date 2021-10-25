<?php
include("../app/func.php");
session_start(); //セッションの開始
login_check(); //ログイン状態のチェック
$sql = "SELECT * FROM employee WHERE flg = 1 OR flg = 2 ORDER BY uid ASC";
$pdo = pdo();
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
      <div id="input_area">
        <p class="title1">ユーザー新規作成</p>
        <div class="flex_input">
          <p>ユーザー名</p>
          <input type="text" placeholder="例：山田太郎" id="user_name" required>
          <p>パスワード</p>
          <form>
            <input type="password" id="pass" required>
          </form>
          <p>管理者権限</p>
          <select name="user" id="authority">
            <option value="1">管理者</option>
            <option value="2">一般</option>
          </select>
          <button id="registration">登録</button>
        </div>
      </div>
      <div id="user">
        <p class="title">管理者一覧</p>
        <table id="userlist">
        </table>
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
    console.log(data);
    const userlist = document.getElementById('userlist');
    const label = [
      "社員番号",
      "名前",
      "管理権限",
      "更新",
      "削除"
    ]
    const tr1 = document.createElement('tr');
    userlist.appendChild(tr1);
    for (let i = 0; i < label.length; i++) {
      const th = document.createElement('th');
      th.textContent = label[i];
      th.className = "cell";
      tr1.appendChild(th);
    }
    for (let i = 0; i < data.length; i++) {
      const tr2 = document.createElement('tr');
      userlist.appendChild(tr2);
      const label = [
        data[i].uid,
        data[i].uname,
        data[i].flg,
        null,
        null
      ]
      for (let j = 0; j < 5; j++) {
        if (j == 2) {
          const select = document.createElement('select');
          select.id = "change";
          const option = [
            "管理者",
            "一般"
          ]
          for (let k = 0; k < option.length; k++) {
            const op = document.createElement('option');
            op.value = k + 1;
            op.textContent = option[k];
            select.appendChild(op)
          }
          const td = document.createElement('td');
          td.className = "cell";
          td.appendChild(select);
          tr2.appendChild(td);
        } else if (j == 3) {
          const button = document.createElement('button');
          button.textContent = "権限更新";
          button.dataset.id = label[0];
          button.addEventListener('click', (e) => {
            if (confirm('権限を修正しますか？')) {
              const change = document.getElementById('change');
              const uid = e.target.dataset.id;
              const data = {
                uid: uid,
                change: change.value
              }
              axios.get("../app/employee_change.php", {
                params: data
              }).then(function(response) {
                alert('修正が完了しました。');
                window.location.href = "employee.php";
              }).catch(function(error) {
                alert(error);
              })
            }
          })
          const td = document.createElement('td');
          td.className = "cell";
          td.appendChild(button);
          tr2.appendChild(td);
        } else if (j == 4) {
          const button = document.createElement('button');
          button.dataset.id = label[0];
          button.textContent = "削除";
          button.className = "red"
          button.addEventListener('click', (e) => {
            if (confirm('本当に削除をしますか？')) {
              const uid = e.target.dataset.id;
              const data = {
                uid: uid,
              }
              axios.get("../app/employee_delete.php", {
                params: data
              }).then(function(response) {
                alert('削除が完了しました。');
                window.location.href = "employee.php";
              }).catch(function(error) {
                alert(error);
              })
            }
          })
          const td = document.createElement('td');
          td.className = "cell";
          td.appendChild(button);
          tr2.appendChild(td);
        } else if (j == 1) {
          const td = document.createElement('td');
          td.className = "cell";
          let name;
          if (label[2] == 1) {
            name = "管理者"
          } else {
            name = "一般"
          }
          td.textContent = name + " / " + label[j];
          tr2.appendChild(td);
        } else {
          const td = document.createElement('td');
          td.className = "cell";
          td.textContent = label[j];
          tr2.appendChild(td);
        }
      }
    }
    const user_name = document.getElementById('user_name');
    const authority = document.getElementById('authority');
    const pass = document.getElementById('pass');
    const registration = document.getElementById('registration');
    registration.addEventListener('click', () => {
      const data = {
        user_name: user_name.value,
        pass: pass.value,
        authority: authority.value
      }
      console.log(data);
      axios.get('../app/employee_success.php', {
        params: data
      }).then(function(response) {
        alert('登録が完了しました');
        window.location.href = "employee.php";
      }).catch(function(error) {
        alert(error);
      })
    })
  </script>

</body>

</html>
