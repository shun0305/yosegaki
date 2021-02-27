<?php
// UpdateDate：2021/01/27
header('Content-type: application/json; charset=utf-8'); // ヘッダ（JSON指定など）
$imgData = filter_input(INPUT_POST, 'src'); // 送ったデータを受け取る

$param = $imgData;  //　やりたい処理

echo json_encode($param); //　echoするとデータを返せる（JSON形式に変換して返す）

// JSから送られたデータ
$data = $param;

// Base64をバイナリにデコードする
$data = str_replace('data:image/png;base64,', '', $data);  // 冒頭の部分を削除
$data = str_replace(' ', '+', $data);  // 空白を'+'に変換
$image = base64_decode($data);

// ファイルへ保存
$file2 = sprintf('%s.png', uniqid());    //ファイル名を作成
file_put_contents($file2, $image);

// exit();

ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

// https://api.epsonconnect.com/api/1/printing/{%resource%}
$host = 'api.epsonconnect.com'; // You will receive it when the license is issued.
$accept = 'application/json;charset=utf-8';
$protocol = '1.1';

//--------------------------------------------------------------------------------
// 1. Authentication

$auth_uri = 'https://' . $host . '/api/1/printing/oauth2/auth/token?subject=printer';
$client_id = 'ed885df26160443096ce87f14fb3e62b';
$secret = '45nvXhluym85hTXfZVidwT2voVDB7zpXASgQXOVL0i1UAWys7JNHWZ4SkVyQRtvS';
$device = 'gsa3942185n9g3@print.epsonconnect.com';

$auth = base64_encode("$client_id:$secret");

$query_param = array(
  'grant_type' => 'password',
  'username' => $device,
  'password' => ''
);
$query_string = http_build_query($query_param, '', '&');

$options = array(
  'http' => array(
    'method' => 'POST',
    'header' => 'Host: ' . $host . "\r\n" .
      'Accept: ' . $accept . "\r\n" .
      'Authorization: Basic ' . $auth . "\r\n" .
      'Content-Length: ' . strlen($query_string) . "\r\n" .
      'Content-Type: application/x-www-form-urlencoded; charset=utf-8' . "\r\n",
    'content' => $query_string,
    'request_fulluri' => true,
    'protocol_version' => $protocol,
    'ignore_errors' => true
  )
);

$http_response_header = null;
$response = @file_get_contents($auth_uri, false, stream_context_create($options));

$auth_result = array();
$auth_result['Response']['Header'] = $http_response_header;
$auth_result['Response']['Body'] = json_decode($response, true);

var_dump('1', $auth_uri, $query_string, $auth_result);

$matches = null;
preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $auth_result['Response']['Header'][0], $matches);

if ($matches[1] !== '200') {
  exit(1);
}

//--------------------------------------------------------------------------------
// 2. Create print job

$subject_id = $auth_result['Response']['Body']['subject_id'];
$access_token = $auth_result['Response']['Body']['access_token'];

$job_uri = 'https://' . $host . '/api/1/printing/printers/' . $subject_id . '/jobs';

$data_param = array(
  'job_name' => 'SampleJob1',
  'print_mode' => 'photo'
);
$data = json_encode($data_param);

$options = array(
  'http' => array(
    'method' => 'POST',
    'header' => 'Host: ' . $host . "\r\n" .
      'Accept: ' . $accept . "\r\n" .
      'Authorization: Bearer ' . $access_token . "\r\n" .
      'Content-Length: ' . strlen($data) . "\r\n" .
      'Content-Type: application/json;charset=utf-8' . "\r\n",
    'content' => $data,
    'request_fulluri' => true,
    'protocol_version' => $protocol,
    'ignore_errors' => true
  )
);

$http_response_header = null;
$response = @file_get_contents($job_uri, false, stream_context_create($options));

$job_result = array();
$job_result['Response']['Header'] = $http_response_header;
$job_result['Response']['Body'] = json_decode($response, true);

var_dump('2', $job_uri, $data, $job_result);

$matches = null;
preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $job_result['Response']['Header'][0], $matches);

if ($matches[1] !== '201') {
  exit(1);
}

//--------------------------------------------------------------------------------
// 3. Upload print file

$job_id = $job_result['Response']['Body']['id'];
$base_uri = $job_result['Response']['Body']['upload_uri'];

// $local_file_path = './Sample.jpg';
$local_file_path = './' . $file2;
$content_type = 'application/octet-stream';

$file_name = '1.' . end(explode('.', $local_file_path));
$upload_uri = $base_uri . '&File=' . $file_name;

$data = file_get_contents($local_file_path);

$options = array(
  'http' => array(
    'method' => 'POST',
    'header' => 'Host: ' . $host . "\r\n" .
      'Accept: ' . $accept . "\r\n" .
      'Content-Length: ' . strlen($data) . "\r\n" .
      'Content-Type: ' . $content_type . "\r\n",
    'content' => $data,
    'request_fulluri' => true,
    'protocol_version' => $protocol,
    'ignore_errors' => true
  )
);

$http_response_header = null;
$response = @file_get_contents($upload_uri, false, stream_context_create($options));

$upload_result = array();
$upload_result['Response']['Header'] = $http_response_header;
$upload_result['Response']['Body'] = json_decode($response, true);

var_dump('3', $upload_uri, $upload_result);

$matches = null;
preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $upload_result['Response']['Header'][0], $matches);

if ($matches[1] !== '200') {
  exit(1);
}

//--------------------------------------------------------------------------------
// 4. Execute print

$print_uri = 'https://' . $host . '/api/1/printing/printers/' . $subject_id . '/jobs/' . $job_id . '/print';

$options = array(
  'http' => array(
    'method' => 'POST',
    'header' => 'Host: ' . $host . "\r\n" .
      'Accept: ' . $accept . "\r\n" .
      'Authorization: Bearer ' . $access_token . "\r\n",
    'request_fulluri' => true,
    'protocol_version' => $protocol,
    'ignore_errors' => true
  )
);

$http_response_header = null;
$response = @file_get_contents($print_uri, false, stream_context_create($options));

$print_result = array();
$print_result['Response']['Header'] = $http_response_header;
$print_result['Response']['Body'] = json_decode($response, true);

var_dump('4', $print_uri, $print_result);
