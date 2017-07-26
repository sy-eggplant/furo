<html>

<head>
  <title>風呂掲示板</title>
  <style type=text/css>
    div#header{background-color:#ffffff;}
  </style>
</head>

<body>
<div class="container">
<div id="header">

<h1>入部希望やお問い合わせはこちらから</h1>

<h2>投稿</h2>
<form action="bbs.php" method="post" role="form">
 <div class="form-group">
    <label class="control-label">名前</label>
    <input type="text" name="user_name" class="form-control" placeholder="名前"/>
  </div>
  <div class="form-group">
    <label class="control-label">投稿内容</label>
    <input type="text" name="content" class="form-control" placeholder="投稿内容"/>
  </div>
  <button type="submit" class="btn btn-primary">送信</button>
</form>

<h2>投稿リスト</h2>

<!-- bootstrap CDN -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<?php
// データベースへ接続
$dbs = "mysql:host=127.0.0.1;dbname=lesson;charset=utf8";
$db_user = "root";
$db_pass = "root";
$pdo = new PDO($dbs, $db_user, $db_pass);

// 変数の設定
$content = $_POST["content"];
$user_name = $_POST["user_name"];
$delete_id= $_POST["delete_id"];


// データベースへのデータの挿入
$sql  = "INSERT INTO bbs (content, updated_at,user_name) VALUES (:content, NOW(), :user_name);";
$stmt = $pdo->prepare($sql);
$stmt -> bindValue(":content", $content, PDO::PARAM_STR);
$stmt -> bindValue(":user_name", $user_name, PDO::PARAM_STR);
$stmt -> execute();

// データベースのデータの削除
$sql = "DELETE FROM bbs WHERE id = :delete_id;";
$stmt = $pdo->prepare($sql);
$stmt -> bindValue(":delete_id", $delete_id, PDO::PARAM_INT);
$stmt -> execute();

// データベースからのデータの取得
$sql = "SELECT * FROM bbs ORDER BY updated_at $order;";
$stmt = $pdo->prepare($sql);
$stmt -> execute();

// 取得したデータをテーブルで表示
?>
<table class="table">
<tr><th>No.</th><th>名前</th><th>日時</th><th>投稿内容</th><th></th></tr>
<?php
while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
  $i++;
  if ($i % 2 == 1) {
?>
    <tr bgcolor="#cccccc">
<?php
  } else {
?>
    <tr>
<?php
  }
?>
    <td><?php echo $i; ?>
    <td><?php echo "$row[user_name]"; ?>
    <td><?php echo "$row[updated_at]"; ?></td>
    <td><?php echo "$row[content]"; ?></td>
    <td>
      <form action="bbs.php" method="post" role="form">
        <button type="submit" class="btn btn-danger">削除</button>
        <div class="form-group">
          <input type="hidden" name="delete_id" value="<?php echo $row[id]; ?>" class="form-control"/>
        </div>
      </form>
    </tr>
<?php
}
?>
</table>
</div>
</div>
</body>
</html>
