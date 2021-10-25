<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/login.css">
  <title>ログイン</title>
</head>

<body>
  <main>
    <div class="login_check">
      <h2 class="login_check_title">ログイン</h2>
      <form action="../app/login_check.php" method="post" id="login_check_area">
        <input type="text" placeholder="ユーザー名" class="login_check_input" name="user">
        <input type="password" placeholder="パスワード" class="login_check_input" name="password">
        <input type="submit" value="送信">
      </form>
    </div>
  </main>
</body>

</html>
