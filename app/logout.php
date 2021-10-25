<?php
session_start();
$_SESSION = array(); //セッション配列を空にする

// Cookieに保存してある"SessionIDの保存期間を過去にして破棄
if(isset($_COOKIE["session_name"])){
  setcookie(session_name(),"", time()-42000, "/");
}

// セッションは廃棄
session_destroy();
header("Location: ../view/login.php");
