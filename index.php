<?php
// 設定項目
$api_key = "vtcabYflxSTPQnrScq3m7bfX4" ;	// API Key
$api_secret = "AxXNR3latdyz50LScBm2BgQLwP0MCuB00dZW4yFnrJHDTToexW" ;	// API Secret
$callback_url = "https://b106827.sakura.ne.jp/work_10/insert.php" ;	// Callback URL (このプログラムのURLアドレス)

// 認証画面のURLを[.../authenticate]にした時にリダイレクトループを防ぐ処理
if ( isset( $_GET['oauth_token'] ) || isset($_GET["oauth_verifier"]) ) {
	echo "認証画面から帰ってきました。" ;
	exit ;
}

/*** [手順1] リクエストトークンの取得 ***/

// [アクセストークンシークレット] (まだ存在しないので「なし」)
$access_token_secret = "" ;

// エンドポイントURL
$request_url = "https://api.twitter.com/oauth/request_token" ;

// リクエストメソッド
$request_method = "POST" ;

// キーを作成する (URLエンコードする)
$signature_key = rawurlencode( $api_secret ) . "&" . rawurlencode( $access_token_secret ) ;

// パラメータ([oauth_signature]を除く)を連想配列で指定
$params = array(
	"oauth_callback" => $callback_url ,
	"oauth_consumer_key" => $api_key ,
	"oauth_signature_method" => "HMAC-SHA1" ,
	"oauth_timestamp" => time() ,
	"oauth_nonce" => microtime() ,
	"oauth_version" => "1.0" ,
) ;

// 各パラメータをURLエンコードする
foreach( $params as $key => $value ) {
	// コールバックURLはエンコードしない
	if( $key == "oauth_callback" ) {
			continue ;
	}

	// URLエンコード処理
	$params[ $key ] = rawurlencode( $value ) ;
}

// 連想配列をアルファベット順に並び替える
ksort( $params ) ;

// パラメータの連想配列を[キー=値&キー=値...]の文字列に変換する
$request_params = http_build_query( $params , "" , "&" ) ;
 
// 変換した文字列をURLエンコードする
$request_params = rawurlencode( $request_params ) ;
 
// リクエストメソッドをURLエンコードする
$encoded_request_method = rawurlencode( $request_method ) ;
 
// リクエストURLをURLエンコードする
$encoded_request_url = rawurlencode( $request_url ) ;
 
// リクエストメソッド、リクエストURL、パラメータを[&]で繋ぐ
$signature_data = $encoded_request_method . "&" . $encoded_request_url . "&" . $request_params ;

// キー[$signature_key]とデータ[$signature_data]を利用して、HMAC-SHA1方式のハッシュ値に変換する
$hash = hash_hmac( "sha1" , $signature_data , $signature_key , TRUE ) ;

// base64エンコードして、署名[$signature]が完成する
$signature = base64_encode( $hash ) ;

// パラメータの連想配列、[$params]に、作成した署名を加える
$params["oauth_signature"] = $signature ;

// パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
$header_params = http_build_query( $params , "" , "," ) ;

// リクエスト用のコンテキストを作成する
$context = array(
	"http" => array(
		"method" => $request_method , // リクエストメソッド (POST)
		"header" => array(			  // カスタムヘッダー
			"Authorization: OAuth " . $header_params ,
		) ,
	) ,
) ;

// cURLを使ってリクエスト
$curl = curl_init() ;
curl_setopt( $curl, CURLOPT_URL , $request_url ) ;	// リクエストURL
curl_setopt( $curl, CURLOPT_HEADER, true ) ;	// ヘッダーを取得する
curl_setopt( $curl, CURLOPT_CUSTOMREQUEST , $context["http"]["method"] ) ;	// メソッド
curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER , false ) ;	// 証明書の検証を行わない
curl_setopt( $curl, CURLOPT_RETURNTRANSFER , true ) ;	// curl_execの結果を文字列で返す
curl_setopt( $curl, CURLOPT_HTTPHEADER , $context["http"]["header"] ) ;	// リクエストヘッダーの内容
curl_setopt( $curl, CURLOPT_TIMEOUT , 5 ) ;	// タイムアウトの秒数
$res1 = curl_exec( $curl ) ;
$res2 = curl_getinfo( $curl ) ;
curl_close( $curl ) ;

// 取得したデータ
$response = substr( $res1, $res2["header_size"] ) ;	// 取得したデータ(JSONなど)
$header = substr( $res1, 0, $res2["header_size"] ) ;	// レスポンスヘッダー (検証に利用したい場合にどうぞ)

// [cURL]ではなく、[file_get_contents()]を使うには下記の通り
// $response = @file_get_contents( $request_url , false , stream_context_create( $context ) ) ;

// リクエストトークンを取得できなかった場合
if( !$response ) {
	echo "<p>リクエストトークンを取得できませんでした…。$api_keyと$callback_url、そしてTwitterのアプリケーションに設定しているCallbackURLを確認して下さい。</p>" ;
	exit ;
}

// $responseの内容(文字列)を$query(配列)に直す
// aaa=AAA&bbb=BBB → [ "aaa"=>"AAA", "bbb"=>"BBB" ]
$query = [] ;
parse_str( $response, $query ) ;




// セッション[$_SESSION["oauth_token_secret"]]に[oauth_token_secret]を保存する
session_start() ;
session_regenerate_id( true ) ;
$_SESSION["oauth_token_secret"] = $query["oauth_token_secret"] ;

/*** [手順2] ユーザーを認証画面へ飛ばす ***/

// ユーザーを認証画面へ飛ばす (毎回ボタンを押す場合)
// header( "Location: https://api.twitter.com/oauth/authorize?oauth_token=" . $query["oauth_token"] ) ;

// ユーザーを認証画面へ飛ばす (二回目以降は認証画面をスキップする場合)
// header( "Location: https://api.twitter.com/oauth/authenticate?oauth_token=" . $query["oauth_token"] ) ;
$url = "";
$url .= '<a class="btn btn-lg btn-block btn-twitter" href="https://api.twitter.com/oauth/authorize?oauth_token=' . $query["oauth_token"] . '">';
$url .= 'Twitterアカウントでログインする';
$url .= '</a>　';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/index.css">
  <title>WORK_10</title>
</head>
<body>
   <!-- twitterログイン(start) -->
   <div class="box">
    <div class="login-box">
      <?=$url?>
      <p>サイトをご利用頂くにはTwitterアカウントでのログインをお願いしておりますm(_ _)m<br>
      許可頂くとサイト利用及びタイムラインの反映(非公開の方は反映されません)が可能となります。</p>
    </div>
  </div>
  <!-- twitterログイン(end) -->
</body>
</html>