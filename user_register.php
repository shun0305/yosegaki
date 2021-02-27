<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザ登録画面</title>
</head>

<body>
  <form action="user_register_act.php" method="POST" enctype="multipart/form-data">
    <fieldset>
      <legend>ユーザ登録画面</legend>
      <div>
        名前: <input type="text" name="username">
      </div>
      <div>
        パスワード: <input type="text" name="password">
      </div>
      <div>
        画像: <input type="file" name="upfile" accept="image/*" capture="camera">
      </div>
      <div>
        <button>登録</button>
      </div>
      <a href="login.php">ログイン</a>
    </fieldset>
  </form>

</body>

</html>