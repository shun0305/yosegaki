<?php
session_start();
include("functions.php");
// check_session_id();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="img/icon.ico">
  <title>YOSEGAKI</title>
</head>

<body>
  <header>
    <img class="logo" src="img/top_logo.png">
  </header>
  <div class="start">
    <p><img src="img/top_logo.png"></p>
  </div>

  <main class="message_screen">
    <div class="menu">
      <h2>３年２組</h2>
      <ul>
        <li class="menu_btn" id="m_1">
          <div>
            <img class="member_photo" src="img/photo_taro.jpg" width="60px" height="auto">
          </div>
          <div>
            <p>タロ先生</p>
          </div>
        </li>
        <li class="menu_btn" id="m_2">
          <div>
            <img class="member_photo" src="img/photo_mat.jpg" width="60px" height="auto">
          </div>
          <div>
            <p>まっちゃん</p>
          </div>
        </li>
        <li class="menu_btn" id="m_3">
          <div>
            <img class="member_photo" src="img/photo_kanomi.jpg" width="60px" height="auto">
          </div>
          <div>
            <p>かのみ</p>
          </div>
        </li>
        <li class="menu_btn" id="m_4">
          <div>
            <img class="member_photo" src="img/photo_azu.jpg" width="60px" height="auto">
          </div>
          <div>
            <p>あずあず</p>
          </div>
        </li>
      </ul>
    </div>

    <div class="input">
      <p id="name"></p>
      <p id="photo"></p>
      <form method="post" action="message_create_file.php" enctype="multipart/form-data">
        <fieldset>
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
            <button class="send_btn">送　信</button>
          </div>
        </fieldset>
      </form>
    </div>
  </main>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(function() {
      setTimeout(function() {
        $('.start p').fadeIn(1600);
      }, 500);
      setTimeout(function() {
        $('.start').fadeOut(500);
      }, 2500);
    });

    $(function() {
      const classes = [{
          name: 'タロ先生',
          photo_code: 'img/photo_taro.jpg'
        },
        {
          name: 'まっちゃん',
          photo_code: 'img/photo_mat.jpg'
        },
        {
          name: 'かのみ',
          photo_code: 'img/photo_kanomi.jpg'
        },
        {
          name: 'あずあず',
          photo_code: 'img/photo_azu.jpg'
        },
      ];

      $('#m_1').on('click', function() {
        $('#name').html(classes[0].name + '　へ　メッセージを送ろう');
        $('#photo').html(`<img class="member_photo" id="photo_image" src="${classes[0].photo_code}" width="120px" height="auto">`)
      });
      $('#m_2').on('click', function() {
        $('#name').html(classes[1].name + '　へ　メッセージを送ろう');
        $('#photo').html(`<img class="member_photo" id="photo_image" src="${classes[1].photo_code}" width="120px" height="auto">`)
      });
      $('#m_3').on('click', function() {
        $('#name').html(classes[2].name + '　へ　メッセージを送ろう');
        $('#photo').html(`<img class="member_photo" id="photo_image" src="${classes[2].photo_code}" width="120px" height="auto">`)
      });
      $('#m_4').on('click', function() {
        $('#name').html(classes[3].name + '　へ　メッセージを送ろう');
        $('#photo').html(`<img class="member_photo" id="photo_image" src="${classes[3].photo_code}" width="120px" height="auto">`)
      });
    })
  </script>
</body>

</html>