
<?php
include "head_all.php";
?>
<title>Загрузка исходников метода на  сервер</title>
</head>
<body>
<h1> Загрузка исходников метода на сервер </h1>
<?php
include "header.php";
if ($auth==0)
{
//header('Location: userlist.php');
echo "Вы не имеете права добавлять методы, не пройдя авторизацию. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему или <a href=userreg.php>зарегистируйтесь</a>.<p>";
include "footer.php";
exit;
}
?>

<form enctype="multipart/form-data" action=methodadd1.php method=post>
Название метода <input type=text name='name' value='Новый метод'><p>
Краткое описание <p>
<textarea name='descr'>
Впишите сюда краткое описание метода
</textarea>
<p>
<!-- Архив <input type=text name='folder'><p> --!>
<!-- Добавил <select name=userid> --!>
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
$r=mysql_query("select * from methods");
$r=mysql_query("select * from users");
$num_res=mysql_num_rows($r);
//for ($i=0;$i<$num_res;$i++)
//{$f=mysql_fetch_array($r);
// //if ($userid==$f['id']) $selected=" selected "; else 
//  $selected="";
// echo "<option $selected value=$f['id']> $f['login'] </option>\n";
// echo "<option $selected value=$f[id]> $f[login] </option>\n";
//}
//echo "</select><p>\n";
echo "<input type=hidden name=userid value=$uid_cookie>";
echo "Платформа\n<select name=platformid>\n";
$r=mysql_query("select * from platforms");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
  $selected="";
// echo "<option $selected value=$f['id']> $f['login'] </option>\n";
 echo "<option $selected value=$f[id]> $f[name] </option>\n";
}
echo "</select><p>\n";
?>
Архив програм:<input type=file name='filetool'><p><p>
Команда (используйте текст #infile для обозначения имени входного файла без расширения). При автоматической генерации команды для прогнозирования, каждому файлу из папки входного пакета будет применен этот шаблон.<p>
<input type=text name=command value="cmd('#infile.txt','#infile_result.txt');"><p>
<input type=submit value="Добавить">


</form>

<?php
include "footer.php";
?>
