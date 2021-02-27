<?php
session_start();
include("functions.php");
// check_session_id();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>yosegaki（入力画面）</title>
</head>

<body>
  <form method="post" action="message_create_file.php" enctype="multipart/form-data">
    <fieldset>
      <legend>yosegaki（入力画面）</legend>
      <!-- <a href="yosegaki_read.php">一覧画面</a> -->
      <!-- <a href="yosegaki_logout.php">logout</a> -->
      <div>
        名前: <input type="text" name="user_name">
      </div>
      <div>
        <input type="file" name="upfile" accept="image/*" capture="camera">
      </div>
      <div>
        メッセージ: <input type="textarea" name="message">
      </div>
      <div>
        <button>送信</button>
      </div>
    </fieldset>
  </form>

</body>

</html>