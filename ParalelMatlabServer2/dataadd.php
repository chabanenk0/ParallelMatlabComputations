<?php
include "head_all.php";
?>
<title>Загрузка архива данных на сервер</title>
</head>
<body>
<h1> Загрузка архива данных на сервер </h1>
<?php
include "header.php";
if ($auth==0)
{
//header('Location: userlist.php');
echo "Вы не имеете права добавлять данные, не пройдя авторизацию. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему или <a href=userreg.php>зарегистируйтесь</a>.<p>";
include "footer.php";
exit;
}

?>

<form enctype="multipart/form-data" action=dataadd1.php method=post>
Название <input type=text name='descr' value='Unnamed001'><p>
<!-- Архив <input type=text name='folder'><p> --!>
Добавил
<select name=userid>
<?
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
$r=mysql_query("select * from users");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
 if ($uid_cookie==$f['id']) $selected=" selected "; else 
  $selected="";
// echo "<option $selected value=$f['id']> $f['login'] </option>\n";
 echo "<option $selected value=$f[id]> $f[login] </option>\n";
}
?>
</select><p>
Архив данных:<input type=file name='filetool'><p><p>
<input type=submit value="Добавить">


</form>
<?php
include "footer.php";
?>
