<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
</head>

<body>
<?php

//↓githubアップロードのためDB接続情報削除
$dsn = 'データベース名';
$user = 'ユーザー名';
$passwordtocreatetb = 'パスワード';
$pdo = new PDO($dsn,$user,$passwordtocreatetb);//データベースに接続

$createtable = "CREATE TABLE newsubmissions"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"//主キーとは行を識別するために選択された列　重複する値なし 欠番は飛ばして最大値から(削除時のカウントのズレは起きない！)
."name TEXT,"
."comment TEXT,"
."datetime TEXT,"
."password TEXT"
.");";
$stmtofcreation = $pdo->query($createtable);//↑ここまでテーブル作成


		/*
		$deletealldataoftable = "truncate table newsubmissions";
		$stmtofdeletion = $pdo->query($deletealldataoftable); //データ全削除用の処理
		*/


//↓投稿削除の処理
$deletenumber = $_POST['deletenumber'];
$passfordelete = $_POST['passfordelete'];

	if(!empty($deletenumber)){
	$select = "SELECT password FROM newsubmissions where id = '$deletenumber'";//idが入力された$deletenumberの行を指定 passwordを選
	$resulttofindpass = $pdo ->query($select);
	$setpassfordelete = $resulttofindpass -> fetchcolumn();
		if($setpassfordelete == $passfordelete){
			$select = "DELETE FROM newsubmissions where id = '$deletenumber'";
			$result =$pdo -> query($select);
		}else{
		$caution = "パスワードが違います。";
		}
	}else{
	}
//ここまで投稿削除の処理


//↓投稿編集の処理
//↓編集する投稿内容をテキストボックスに表示させる処理
$editnumber = $_POST['editnumber'];
$passforedit = $_POST['passforedit'];

	if(!empty($editnumber)){
	$select = "SELECT password FROM newsubmissions where id = '$editnumber'";
	$resulttofindpass = $pdo -> query($select);
	$setpassforedit = $resulttofindpass -> fetchcolumn();
		if($setpassforedit == $passforedit){
		$valueofname = $result['name'];
		$valueofcomment = $result['comment'];
		$editnumberifpassed = $editnumber;
		}else{
		$caution = "パスワードが違います。";
		$typeofpointednumber = 'hidden';
		}
	}else{
	$typeofpointednumber = 'hidden';
	}//ここまで編集モードにする処理

//autoincrementで勝手にカウントしてくれるのでidの変数は無し
$name = $_POST['name'];
$comment = $_POST['comment'];
$password = $_POST['password'];
$datetime = date('Y/m/d H:i:s');

$pointednumber = $_POST['pointednumber'];

	if(!empty($pointednumber)){//編集モードの時の処理
		if(!empty($name) or !empty($comment)){
		$update = "UPDATE newsubmissions set id = '$pointednumber', name = '$name', comment = '$comment', datetime = '$datetime', password = '$password' where id = $pointednumber";
		$result = $pdo -> query($update);
		}else{
		}
	}else{//↓通常モードの時の処理
		if(!empty($name) or !empty($comment)){
		$insertion = $pdo->prepare("INSERT INTO newsubmissions (id, name, comment, datetime, password) VALUES (null, :name, :comment, :datetime, :password)");
		//$insertion -> bindParam(':id', $id, PDO::PARAM_INT);←autoincrementで勝手に入力 不要
		$insertion -> bindParam(':name', $name, PDO::PARAM_STR);
		$insertion -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$insertion -> bindParam(':datetime', $datetime, PDO::PARAM_STR);
		$insertion -> bindParam(':password', $password, PDO::PARAM_STR);
		$insertion -> execute();
		}else{
		}//テーブルへの書き込み
	}





//↓投稿・編集用フォーム
echo $caution;

echo "<form method='post' action='mission_4-1.php'>
<input type='text' name='name' placeholder='名前' value='$valueofname'><br>
<input type='text' name='comment' placeholder='コメント' value='$valueofcomment'><br>
<input type='text' name='password' placeholder='パスワード'><br>
<input type='$typeofpointednumber' name='pointednumber' value='$editnumberifpassed'>
<input type='submit' value='送信'>
</form><br>";



echo "<form method='post' action='mission_4-1.php'>
<input type='text' name='passfordelete' placeholder='パスワード'><br>
<input type='number' name='deletenumber' placeholder='削除指定番号'>
<input type='submit' value='送信'>
</form><br>";


echo "<form method='post' action='mission_4-1.php'>
<input type='text' name='passforedit' placeholder='パスワード'><br>
<input type='number' name='editnumber' placeholder='編集指定番号'>
<input type='submit' value='送信'>
</form>";



$selectfordisplay = 'SELECT * FROM newsubmissions';//submissionsというテーブルを全選択
$resultsfordisplay = $pdo -> query($selectfordisplay);//実行
foreach($resultsfordisplay as $row){
echo $row['id'].' ';
echo $row['name'].' ';
echo $row['comment'].' ';
echo $row['datetime'].'<br>';
}




echo "<hr>";//↓確認用

$showtables = "SHOW TABLES";
$resulttoshowtables = $pdo -> query($showtables);
foreach($resulttoshowtables as $row){
echo $row[0];
echo "<br>";
}

echo "<hr>";


$showtable = "SHOW CREATE TABLE newsubmissions";
$resulttoshowtable = $pdo -> query($showtable);
foreach($resulttoshowtable as $row){
print_r($row);
}



?>


</body>
</html>