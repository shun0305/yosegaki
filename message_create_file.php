<?php
// var_dump($_POST);
// exit();

session_start();
include("functions.php");
// check_session_id();

if (
  !isset($_POST['user_name']) || $_POST['user_name'] == '' ||
  !isset($_POST['message']) || $_POST['message'] == ''
) {
  // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

// 受け取ったデータを変数に入れる
$user_name = $_POST['user_name'];
$message = $_POST['message'];


// ここからファイルアップロード&DB登録の処理を追加しよう！！！

if (!isset($_FILES['upfile']) && $_FILES['upfile']['error'] != 0) {
  // 送られていない,エラーが発生,などの場合
  exit('Error:画像が送信されていません');
} else {
  $uploaded_file_name = $_FILES['upfile']['name']; //ファイル名の取得
  $temp_path = $_FILES['upfile']['tmp_name']; //tmpフォルダの場所
  $directory_path = 'upload/'; //アップロード先ォルダ

  $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
  $unique_name = date('YmdHis')  . md5(session_id()) . "." . $extension;
  $filename_to_save = $directory_path . $unique_name;



  if (!is_uploaded_file($temp_path)) {
    exit('Error:画像がありません'); // tmpフォルダにデータがない
  } else { // ↓ここでtmpファイルを移動する
    if (!move_uploaded_file($temp_path, $filename_to_save)) {
      exit('Error:ロードできませんでした'); // 画像の保存に失敗
    } else {
      chmod($filename_to_save, 0644); // 権限の変更
      //$img = '<img src="' . $filename_to_save . '" >'; // imgタグを設定
    }
  }
}

// DB接続
$pdo = connect_to_db();

// データ登録SQL作成
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
$sql = 'INSERT INTO send_user_table(id, user_id, user_name, image, message, to_user, created_at, updated_at) VALUES(NULL, 1, :user_name, :image, :message, 1, sysdate(), sysdate())';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_name', $user_name, PDO::PARAM_STR);
$stmt->bindValue(':message', $message, PDO::PARAM_STR);
$stmt->bindValue(':image', $filename_to_save, PDO::PARAM_STR);
$status = $stmt->execute();

// var_dump($temp_path);
// exit();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header("Location:message_input.php");
  exit();
}
