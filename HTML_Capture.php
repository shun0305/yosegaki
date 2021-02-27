<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>JavaScriptで撮るスクリーンショット</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
</head>

<body>

  <hr>
  <div style="background-color : #AAEEDD">
    <h1>JavaScriptで撮るスクリーンショット</h1>
  </div>

  <h3>HTMLの範囲（ここが色紙になる id targetにの中に入れること）</h3>
  <div id="target" style="background-color: pink;">
    この中にHTML要素を入れると
    下のボタンを押したときにここの範囲が印刷されます
    横幅などはタグ内にwidthで設定してみてね
  </div>
  <h3>HTMLの範囲ここまで</h3>


  <br>
  <h3>↓ボタンを押すと上のHTMLを画像に変換したものが表示される</h3>

  <div id='div'>
    <!-- <img src="" id="result" class="test" /> -->

    <canvas id="board" width="1000px" height="10">
      <img src="" id="result" class="test" style="width: 100%;" />
    </canvas>
  </div>


  <!-- <img src="" id="result" class="test" type="file" name="img" />
  <img src="./Sample.jpg"  id="board"  /> -->

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

</body>

</html>