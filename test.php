<?php
session_start();
include("functions.php");
$pdo = connect_to_db();

// check_session_id();
$sql = 'SELECT * FROM send_user_table';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
  $error = $stmt->errorInfo();
  exit('sqlError:' . $error[2]);
} else {
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $output = "";
  foreach ($result as $record) {
    $output .= "<div class='west'>";
    $output .= "<div class='test'>";
    $output .= "<p>{$record["user_name"]}</p>";
    $output .= "<p>{$record["message"]}</p>";
    $output .= "</div>";
    $output .= "</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
  <link rel="stylesheet" href="css/captture.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/change.css">
  <link rel="icon" href="img/icon.ico">
  <title>YOSEGAKI</title>
</head>

<body>
  <header>
    <img class="logo" src="img/top_logo.gif">
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
      <div class='r_wrap'>

        <div class='b_round'></div>
        <div class='s_round'>
          <div class='s_arrow'></div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="flip_box">
        <div class="input">
          <p id="name"></p>
          <p id="photo"></p>
          <form method="post" action="message_create_file.php" enctype="multipart/form-data">
            <fieldset>
              <div>
                <div class="input_item">
                  <p class="color"></p>名前
                </div>
                <input class="input_text" type="text" name="user_name" value="" placeholder="ありりん"><br>
                <div class="input_item">
                  <p class="color"></p>写真
                </div>
                <input type="file" name="upfile" accept="image/*" capture="camera"><br>
                <div class="input_item">
                  <p class="color"></p>メッセージ
                </div>
                <textarea name="message" rows="4" cols="32" placeholder="例）今までありがとう！"></textarea>
              </div>
              <div>
                <button class="send_btn">送　信</button>
              </div>
            </fieldset>
          </form>
        </div>


        <div class="print">
          <div class="output">

            <div id="target" style=" height: 550px;">
              <!-- この中にHTML要素を入れると
            下のボタンを押したときにここの範囲が印刷されます
            横幅などはタグ内にwidthで設定してみてね -->
              <h1 id="taro">タロ先生</h1>
              <div class="boxContainer">

                <?= $output ?>


              </div>

              <div class="boxContainer">
                <div class="box">
                  <p>名前</p>
                  <P>テキスト</P>
                </div>
                <div class="box">
                  <p>名前</p>
                  <P>テキスト</P>
                </div>
                <div class="box">
                  <p>名前</p>
                  <P>テキスト</P>
                </div>
              </div>
            </div>
            <h3>↓ボタンを押すと上のHTMLを画像に変換したものが表示される</h3>

            <div id='div' style="display:none">
              <!-- <img src="" id="result" class="test" /> -->

              <canvas id="board" width="1000px" height="10">
                <img src="" id="result" class="test" style="width: 100%;" />
              </canvas>
            </div>



            <button type="submit" class="button">上のHTMLを画像変換してプリンターに画像データを送るボタン</button>

            <script>
              const result = document.getElementById('result');

              document.getElementsByClassName('button')[0].onclick = function() {

                const board = document.querySelector("#board");
                console.log(board);

                var img_element = document.createElement('img');
                img_element.src = result.src;

                document.getElementById('div').appendChild(img_element);
                let data = result.src; // 渡したいデータ
                $.ajax({
                    type: "POST", //　GETでも可
                    url: "printSample.php", //　送り先
                    data: {
                      'src': data
                    }, //　渡したいデータをオブジェクトで渡す
                    dataType: "json", //　データ形式を指定
                    scriptCharset: 'utf-8' //　文字コードを指定
                  })
                  .then(
                    function(param) { //　paramに処理後のデータが入って戻ってくる
                      // console.log(param); //　帰ってきたら実行する処理
                    },
                    function(XMLHttpRequest, textStatus, errorThrown) {
                      console.log(errorThrown); //　エラー表示
                    });
              }
            </script>


            <!-- <a href="" id="ss" download="html_ss.png">スクリーンショット(document.body全体)をダウンロード</a> -->

            <!-- <hr>
  <h3>注意</h3>
  <ul>
    <li>実際にはスクリーンショットを撮っているわけではない</li>
    <li>html2canvasは、HTML内のDOMやCSSを解釈してCanvas上に描画するライブラリ</li>
    <li>つまり、レンダリングエンジンに近い動作をする</li>
    <li>そのため、ブラウザと異なる表示がされる場合がある</li>
    <li>flashやapplet,iframe（別URL）はうまくキャプチャできない</li>
  </ul>
  </div> -->



            <script>
              //ロードされた際の処理として実施：
              window.onload = function() {

                //HTML内に画像を表示
                html2canvas(document.getElementById("target"), {
                  onrendered: function(canvas) {
                    //imgタグのsrcの中に、html2canvasがレンダリングした画像を指定する。

                    var imgData = canvas.toDataURL();
                    console.log(imgData);
                    document.getElementById("result").src = imgData;
                  }
                });

                // //ボタンを押下した際にダウンロードする画像を作る
                // html2canvas(document.body, {
                //   onrendered: function(canvas) {
                //     //aタグのhrefにキャプチャ画像のURLを設定
                //     var imgData = canvas.toDataURL();
                //     document.getElementById("ss").href = imgData;
                //   }
                // });

              }
            </script>




          </div>
          <div>
            <button class="print_btn">印　刷</button>
          </div>
        </div>
      </div>
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
        $('#name').html(`<span class="member_name">${classes[0].name}</span>　へメッセージを送ろう`);
        $('#photo').html(`<img class="member_photo" id="photo_image" src="${classes[0].photo_code}" width="120px" height="auto">`)
      });
      $('#m_2').on('click', function() {
        $('#name').html(`<span class="member_name">${classes[1].name}</span>　へメッセージを送ろう`);
        $('#photo').html(`<img class="member_photo" id="photo_image" src="${classes[1].photo_code}" width="120px" height="auto">`)
      });
      $('#m_3').on('click', function() {
        $('#name').html(`<span class="member_name">${classes[2].name}</span>　へメッセージを送ろう`);
        $('#photo').html(`<img class="member_photo" id="photo_image" src="${classes[2].photo_code}" width="120px" height="auto">`)
      });
      $('#m_4').on('click', function() {
        $('#name').html(`<span class="member_name">${classes[3].name}</span>　へメッセージを送ろう`);
        $('#photo').html(`<img class="member_photo" id="photo_image" src="${classes[3].photo_code}" width="120px" height="auto">`)
      });
    })
  </script>


  <script>
    $(document).ready(function() {

      var s_round = '.s_round';

      $(s_round).hover(function() {
        $('.b_round').toggleClass('b_round_hover');
        return false;
      });

      $(s_round).click(function() {
        $('.flip_box').toggleClass('flipped');
        $(this).addClass('s_round_click');
        $('.s_arrow').toggleClass('s_arrow_rotate');
        $('.b_round').toggleClass('b_round_back_hover');
        return false;
      });

      $(s_round).on('transitionend', function() {
        $(this).removeClass('s_round_click');
        $(this).addClass('s_round_back');
        return false;
      });
    });
  </script>


</body>

</html>