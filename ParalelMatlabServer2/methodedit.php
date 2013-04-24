<?php
include "head_all.php";
?>
<title>Редактирование метода</title>
</head>
<body>
<h1> Редактирование метода </h1>
<?php
include "header.php";
$id=$_REQUEST['id'];
$r=mysql_query("select userid from methods  where id=$id");
$f=mysql_fetch_array($r);
$userid=$f['userid'];

if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
//header('Location: userlist.php');
//echo "попало сюда!!! $uid_cookie ";
echo "Вы не имеете права редактировать настройки этого метода. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}
?>

<form enctype="multipart/form-data" action=methodedit1.php method=post>
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
$r=mysql_query("select * from methods where id=$id");
$f=mysql_fetch_array($r);
$name=rawurldecode($f['name']);
$descr=rawurldecode($f['descr']);
$folder=$f['folder'];
$userid=$f['userid'];
$platformid=$f['platformid'];
$command=rawurldecode($f['command']);
echo "<input type=hidden name='id' value=$id>\n";
echo "Название метода <input type=text name='name' value=$name><p>\n";
echo "Краткое описание <p><textarea name='descr'>$descr</textarea><p>\n";
echo "Архив <input type=text name='folder' value=$folder><p>\n";
echo "Добавил\n <select name=userid>\n";
$r=mysql_query("select * from users");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
 if ($userid==$f['id']) $selected=" selected "; else $selected="";
// echo "<option $selected value=$f['id']> $f['login'] </option>\n";
 echo "<option $selected value=$f[id]> $f[login] </option>\n";
}
echo"</select><p>\n";
echo "Платформа\n<select name=platformid>\n";
$r=mysql_query("select * from platforms");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
  $selected="";
if ($f['id']==$platformid) $selected="selected"; else $selected="";;
// echo "<option $selected value=$f['id']> $f['login'] </option>\n";
 echo "<option $selected value=$f[id]> $f[name] </option>\n";
}
echo "</select><p>\n";
//$f['userid'];
echo "Команда (используйте текст #infilename, #outfilename для обозначения имени входного и выходного файла соответственно).<p>\n";
echo '<input type=text name=command value="';
echo $command;
echo '"><p>';
echo "\n";
?>
<!-- //Архив програм:<input type=file name='filetool'><p>-->
<p> 
<a href=methodsarchreplace.php>Заменить архив программы</a><p>
<input type=submit value="Сохранить">
</form>

<?php
include "footer.php";
?>
