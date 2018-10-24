# TwitterOAuth  
ツイッターアカウントでのユーザー登録機能  
  
## 内容  
TwitterOAuthライブラリ(https://github.com/abraham/twitteroauth)  
を使用してユーザーにTwitterアカウントでログインしてもらい、そのアカウント情報を取得  
  
## URL  
https://b106827.sakura.ne.jp/work_10  
  
## PHP  
  
index.php  
+ データ操作フローを各リンク毎にまとめた。  
↓  
data.php  
+ formタグを用意し、insert.phpにデータをPOSTで送信。  
↓  
insert.php  
+ 3行目  
  + ポストデータのチェック。  
+ 12行目  
  + ポストデータを変数に格納。  
+ 17行目  
  + mysqlへの接続情報(functions.php)読み込み。その後接続。
  + functions.phpではPDOを用いてDBとの接続を行なっている。  
+ 21行目  
  + SQL文(INSERT)の格納。SQLインジェクションを防ぐためにバインド変数を使用している。  
↓  
sql.php  
+ 指定DBからデータを取得しfetchメソッドで1行ずつデータを取り出す。  
↓  
select.php  
+ 指定DBからidが1,3,5のデータを抽出(SELECT)  
↓  
select1.php  
+ 指定DBからidが4以上8以下のデータを抽出(SELECT  
↓  
select2.php  
+ 指定DBの「email」カラムから「test1」という文字列にヒットするものをあいまい検索で取得  
↓  
sort.php  
+ 指定DBのindate(日時)カラムを新しい順に並び替える(SORT)  
↓  
data1.php  
+ formタグを用意し、insert1.phpにデータをPOSTで送信  
↓  
insert1.php  
+ 「age」カラムを追加し、新たにテストデータ登録し直し  
↓  
sql1.php  
+ 指定DBからデータを取得しfetchメソッドで1行ずつデータを取り出す。  
↓  
select3.php  
+ 指定DBの「age」カラムが20かつ「indate」カラムが「2018-06-02%」のデータを抽出。  
↓  
select4.php  
+ 指定DBの「indate」カラムから新しい順にLIMIT5つまで抽出。  
↓  
select5.php  
+ 指定DBから「age」カラムの10,20,30,40のデータがそれぞれ何個あるかカウント(GROUP BYとCOUNT())