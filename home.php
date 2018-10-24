<?php
include("functions.php");

//1.DB接続
$pdo = db_con();

//2.データセレクト
$stmt = $pdo->prepare("SELECT * FROM user ");
$status = $stmt->execute();

//3.データ表示
$view="";
if($status==false){
	queryError($stmt);
}else{
	// select数だけ自動でループ
	while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
		$view .= '<li>';
		$view .= '<img src="'.$result["icon"].'" height="40px" width="40px">';
		$view .= $result["screen_name"];
		$view .= '</li>';
	}
}

// 設定項目
$api_key = "vtcabYflxSTPQnrScq3m7bfX4" ;	// API Key
$api_secret = "AxXNR3latdyz50LScBm2BgQLwP0MCuB00dZW4yFnrJHDTToexW" ;	// API Secret
$callback_url = "https://b106827.sakura.ne.jp/work_10/insert.php" ;	 //Callback URL (このプログラムのURLアドレス)




?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/home.css">
  <title>WORK_10</title>
</head>
<body>
  <div class="main">
    <!-- ヘッダー(start) -->
    <header>
      <h1>WORK_10</h1>
    </header>
    <!-- ヘッダー(end) -->

    <!-- 掲示板スレッド一覧(start) -->
    <div class="jumbotron">
        <ul>
          <p>〜登録者〜</p>
		  <?=$view?>
        </ul>
    </div>
    <!-- 掲示板スレッド一覧(終了) -->
  </div>


  <!-- JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>
</html>