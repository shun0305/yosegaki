<?php

// var_dump($_POST);
// exit();

// 関数ファイル読み込み
include('functions.php');

// データ受け取り
$username = $_POST["username"];
$password = $_POST["password"];
// $icon    = $_POST["upfile"];

// ここからファイルアップロード&DB登録の処理を追加しよう！！！

if (!isset($_FILES['upfile']) && $_FILES['upfile']['error'] != 0) {
  // 送られていない,エラーが発生,などの場合
  exit('Error:画像が送信されていません');
} else {
  $uploaded_file_name = $_FILES['upfile']['name']; //ファイル名の取得
  $temp_path = $_FILES['upfile']['tmp_name']; //tmpフォルダの場所
  $directory_path = 'upload/'; //アップロード先フォルダ

  $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
  // $unique_name = date('YmdHis')  . md5(session_id()) . "." . $extension;
  $unique_name = $username . "_icon" . "." . $extension;
  $filename_to_save = $directory_path . $unique_name;

  // var_dump($temp_path);
  // exit();

  if (!is_uploaded_file($temp_path)) {
    exit('Error:画像がありません'); // tmpフォルダにデータがない
  } else { // ↓ここでtmpファイルを移動する
    if (!move_uploaded_file($temp_path, $filename_to_save)) {
      exit('Error:ードできませんでした'); // 画像の保存に失敗
    } else {
      chmod($filename_to_save, 0644); // 権限の変更
      //$img = '<img src="' . $filename_to_save . '" >'; // imgタグを設定
    }
  }
}

// DB接続関数
$pdo = connect_to_db();

// ユーザ存在有無確認
$sql = 'SELECT COUNT(*) FROM user_list_table WHERE user_name=:username';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
}

if ($stmt->fetchColumn() > 0) {
  // usernameが1件以上該当した場合はエラーを表示して元のページに戻る
  // $count = $stmt->fetchColumn();
  echo "<p>すでに登録されているユーザです．</p>";
  echo '<a href="login.php">login</a>';
  exit();
}

// ユーザ登録SQL作成
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
$sql = 'INSERT INTO user_list_table(id, user_id, user_name, password, nickname, icon, is_admin, created_at, updated_at) VALUES(NULL, 1, :username, :password, "nickname", :icon, 1, sysdate(), sysdate())';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$stmt->bindValue(':icon', $unique_name, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header("Location:login.php");
  exit();
}
