<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
</head>

<body>
<?php

//��github�A�b�v���[�h�̂���DB�ڑ����폜
$dsn = '�f�[�^�x�[�X��';
$user = '���[�U�[��';
$passwordtocreatetb = '�p�X���[�h';
$pdo = new PDO($dsn,$user,$passwordtocreatetb);//�f�[�^�x�[�X�ɐڑ�

$createtable = "CREATE TABLE newsubmissions"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"//��L�[�Ƃ͍s�����ʂ��邽�߂ɑI�����ꂽ��@�d������l�Ȃ� ���Ԃ͔�΂��čő�l����(�폜���̃J�E���g�̃Y���͋N���Ȃ��I)
."name TEXT,"
."comment TEXT,"
."datetime TEXT,"
."password TEXT"
.");";
$stmtofcreation = $pdo->query($createtable);//�������܂Ńe�[�u���쐬


		/*
		$deletealldataoftable = "truncate table newsubmissions";
		$stmtofdeletion = $pdo->query($deletealldataoftable); //�f�[�^�S�폜�p�̏���
		*/


//�����e�폜�̏���
$deletenumber = $_POST['deletenumber'];
$passfordelete = $_POST['passfordelete'];

	if(!empty($deletenumber)){
	$select = "SELECT password FROM newsubmissions where id = '$deletenumber'";//id�����͂��ꂽ$deletenumber�̍s���w�� password��I
	$resulttofindpass = $pdo ->query($select);
	$setpassfordelete = $resulttofindpass -> fetchcolumn();
		if($setpassfordelete == $passfordelete){
			$select = "DELETE FROM newsubmissions where id = '$deletenumber'";
			$result =$pdo -> query($select);
		}else{
		$caution = "�p�X���[�h���Ⴂ�܂��B";
		}
	}else{
	}
//�����܂œ��e�폜�̏���


//�����e�ҏW�̏���
//���ҏW���铊�e���e���e�L�X�g�{�b�N�X�ɕ\�������鏈��
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
		$caution = "�p�X���[�h���Ⴂ�܂��B";
		$typeofpointednumber = 'hidden';
		}
	}else{
	$typeofpointednumber = 'hidden';
	}//�����܂ŕҏW���[�h�ɂ��鏈��

//autoincrement�ŏ���ɃJ�E���g���Ă����̂�id�̕ϐ��͖���
$name = $_POST['name'];
$comment = $_POST['comment'];
$password = $_POST['password'];
$datetime = date('Y/m/d H:i:s');

$pointednumber = $_POST['pointednumber'];

	if(!empty($pointednumber)){//�ҏW���[�h�̎��̏���
		if(!empty($name) or !empty($comment)){
		$update = "UPDATE newsubmissions set id = '$pointednumber', name = '$name', comment = '$comment', datetime = '$datetime', password = '$password' where id = $pointednumber";
		$result = $pdo -> query($update);
		}else{
		}
	}else{//���ʏ탂�[�h�̎��̏���
		if(!empty($name) or !empty($comment)){
		$insertion = $pdo->prepare("INSERT INTO newsubmissions (id, name, comment, datetime, password) VALUES (null, :name, :comment, :datetime, :password)");
		//$insertion -> bindParam(':id', $id, PDO::PARAM_INT);��autoincrement�ŏ���ɓ��� �s�v
		$insertion -> bindParam(':name', $name, PDO::PARAM_STR);
		$insertion -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$insertion -> bindParam(':datetime', $datetime, PDO::PARAM_STR);
		$insertion -> bindParam(':password', $password, PDO::PARAM_STR);
		$insertion -> execute();
		}else{
		}//�e�[�u���ւ̏�������
	}





//�����e�E�ҏW�p�t�H�[��
echo $caution;

echo "<form method='post' action='mission_4-1.php'>
<input type='text' name='name' placeholder='���O' value='$valueofname'><br>
<input type='text' name='comment' placeholder='�R�����g' value='$valueofcomment'><br>
<input type='text' name='password' placeholder='�p�X���[�h'><br>
<input type='$typeofpointednumber' name='pointednumber' value='$editnumberifpassed'>
<input type='submit' value='���M'>
</form><br>";



echo "<form method='post' action='mission_4-1.php'>
<input type='text' name='passfordelete' placeholder='�p�X���[�h'><br>
<input type='number' name='deletenumber' placeholder='�폜�w��ԍ�'>
<input type='submit' value='���M'>
</form><br>";


echo "<form method='post' action='mission_4-1.php'>
<input type='text' name='passforedit' placeholder='�p�X���[�h'><br>
<input type='number' name='editnumber' placeholder='�ҏW�w��ԍ�'>
<input type='submit' value='���M'>
</form>";



$selectfordisplay = 'SELECT * FROM newsubmissions';//submissions�Ƃ����e�[�u����S�I��
$resultsfordisplay = $pdo -> query($selectfordisplay);//���s
foreach($resultsfordisplay as $row){
echo $row['id'].' ';
echo $row['name'].' ';
echo $row['comment'].' ';
echo $row['datetime'].'<br>';
}




echo "<hr>";//���m�F�p

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