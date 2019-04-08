<!DOCTYPE html>
<html lang = "ja">
<head>
<meta http-equiv="content-type" charset="UTF-8">
</head>

<body>
<?php
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
try{
	$pdo = new PDO($dsn,$user,$password);
}catch(PDOException $e){
	echo "接続エラー:". $e -> getMessage();
}

//テーブル作成
$sql = "CREATE TABLE mission_4_niu(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), comment TEXT, time DATETIME, pass INT);";
$stmt = $pdo -> query($sql);

//データベースに書き込み
if(!empty($_POST['name']) and !empty($_POST['comment']) and empty($_POST['editing']) and !empty($_POST['pass']))
{
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$time = date("Y/m/d H:i:s");
	$pass = $_POST['pass'];
	$sql = $pdo -> query("INSERT INTO mission_4_niu(name,comment,time,pass) VALUES('$name', '$comment', '$time', '$pass')");
	echo "書き込み完了！";
}

//削除機能
	$delete_pass = $_POST['delete_pass'];
if(ctype_digit($_POST['delete']))
{	$delete_num = $_POST['delete'];
	$results = $pdo -> query("SELECT * FROM mission_4_niu ORDER BY id");
	foreach($results as $row){
		if($row['pass'] == $delete_pass)
		{
			$delete = $pdo -> query("DELETE FROM mission_4_niu WHERE id=$delete_num");
			echo "削除完了！";
		}elseif($row['id'] == $delete_num and $row['pass'] != $delete_pass){
			echo "パスワードが違います。";
		}
	}
}

//編集機能(編集モード)
if(!empty($_POST['editor']) and ctype_digit($_POST['editor']))
{
	$edit_num = $_POST['editor'];
	$edit_pass = $_POST['edit_pass'];
	$results = $pdo -> query("SELECT * FROM mission_4_niu ORDER BY id");
	foreach($results as $row){
		if($row['id'] == $edit_num and $row['pass'] == $edit_pass)
		{
			$ed_num = $row['id'];
			$back_name = $row['name'];
			$back_comment = $row['comment'];
			echo "編集準備中...";
		}elseif($row['id'] == $edit_num and $row['pass'] != $edit_pass){
			echo "パスワードが違います。";
		}
	}
}
//編集機能(編集操作)
if(!empty($_POST['name']) and !empty($_POST['comment']) and !empty($_POST['editing']) and ctype_digit($_POST['editing']))
{	$edit_name = $_POST['name'];
	$edit_comment = $_POST['comment'];
	$edit_id = $_POST['editing'];
	$edit = $pdo -> query("UPDATE mission_4_niu SET name='$edit_name', comment='$edit_comment' WHERE id=$edit_id");
	echo "編集完了！";
}

?>


<form action = "mission_4_niu.php" method="post">
<input type="text" name="name" value="<?php echo $back_name; ?>" placeholder="名前">
<br>
<input type="text" name="comment" value="<?php echo $back_comment; ?>" placeholder="コメント">
<br>
<input type="password" name="pass" placeholder="パスワード">
<input type="hidden" name="editing"  value="<?php echo $edit_num; ?>">
<input type="submit"  value="送信" />
<br>
<br>
<input type="text" name="delete" placeholder="削除対象番号"><br>
<input type="password" name="delete_pass"  placeholder="パスワード">
<input type="submit"  value="削除" />
<br>
<br>
<input type="text" name="editor" placeholder="編集対象番号"><br>
<input type="password" name="edit_pass"  placeholder="パスワード">
<input type="submit"  value="編集" />
<br>
</form>

<?php
//連番の更新
$reset = $pdo -> query("ALTER TABLE mission_4_niu AUTO_INCREMENT=1");
$set =$pdo -> query("SET @i:=0");
$update = $pdo -> query("UPDATE mission_4_niu SET id=(@i:=@i+1)");


//データの表示
$results = $pdo -> query("SELECT * FROM mission_4_niu ORDER BY id");
foreach($results as $row){
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['time'].'<br>';
}
?>