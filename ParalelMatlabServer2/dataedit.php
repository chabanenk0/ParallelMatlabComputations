<?php
include "head_all.php";
?>
<title>Редактирование данных</title>
</head>
<body>
<h1> Редактирование данных </h1>
<?php
include "header.php";
$id=$_REQUEST['id'];
$r=mysql_query("select userid from datafolder where id=$id");
$f=mysql_fetch_array($r);
$userid=$f['userid'];

if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
//header('Location: userlist.php');
//echo "попало сюда!!! $uid_cookie ";
echo "Вы не имеете права редактировать настройки этого пакета данных. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}
?>

<form enctype="multipart/form-data" action=dataedit1.php method=post>
<?php
$id=$_REQUEST['id'];
include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("use tasks");
$r=mysql_query("select * from datafolder where id=$id");
$f=mysql_fetch_array($r);
//$name=$f['name'];
$descr=rawurldecode($f['descr']);
$folder=$f['folder'];
$userid=$f['userid'];

//echo "Имя пакета данных <input type=text name='name' value='$name'><p>\n";
echo "<input type=hidden name='id' value=$id>\n"; 
echo "Имя пакета данных <input type=text name='descr' value='$descr'><p>\n"; // было описание...
echo "Архив <input type=text name='folder' value='$folder'><p>\n";
echo "Добавил\n <select name=userid>\n";
$r=mysql_query("select * from users");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
 if ($userid==$f['id']) $selected=" selected "; else $selected="";
// echo "<option $selected value=$f['id']> $f['login'] </option>\n";
 echo "<option $selected value=$f[id]> $f[login] </option>\n";
}
?>
</select><p>
<!-- //Архив програм:<input type=file name='filetool'><p>-->
<p> 
<!-- <a href=methodsarchreplace.php>Заменить архив данных</a><p> -->
<input type=submit value="Сохранить">
</form>

<?php
include "footer.php";
?>
