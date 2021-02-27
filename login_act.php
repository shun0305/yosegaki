<?php
// var_dump($_POST);
// exit();
session_start();

// 外部ファイル読み込み
include("functions.php");

// DB接続します
$pdo = connect_to_db();

$user_name = $_POST["user_name"];
$password = $_POST["password"];

// データ取得SQL作成&実行
$sql = 'SELECT * FROM user_list_table WHERE user_name=:user_name AND password=:password';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$status = $stmt->execute();

// SQL実行時にエラーがある場合
if ($status == false) {
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
}

// うまくいったらデータ（1レコード）を取得
$val = $stmt->fetch(PDO::FETCH_ASSOC);

// ユーザ情報が取得できない場合はメッセージを表示
if (!$val) {
  echo "<p>ログイン情報に誤りがあります．</p>";
  echo '<a href="login.php">login</a>';
  exit();
} else {
  $_SESSION = array();
  $_SESSION["session_id"] = session_id();
  $_SESSION["is_admin"] = $val["is_admin"];
  $_SESSION["user_name"] = $val["user_name"];
  $_SESSION["id"] = $val["id"];
  header("Location:message_input.php");
  exit();
}
