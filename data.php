<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{paddig: 10px; font-size: 16px;}</style>
  <title>データ登録画面</title>
</head>
<body>
  <!-- FORM(start) -->
  <form method="post" action="insert.php">
    <div class="jumbotron">
      <fieldset>
        <legend>データ登録</legend>
          <label>name: <input type="text" name="name"></label><br>
          <label>email: <input type="text" name="email"></label><br>
          <label>naiyou:<br><textArea name="naiyou" cols="30" rows="4"></textArea></label><br>
          <input type="submit" value="データ登録">
          <a href="index.php" class="btn btn-success btn-sm">戻る</a>
      </fieldset>
    </div>
  </form>
  <!-- FORM(終了) -->

</body>
</html>