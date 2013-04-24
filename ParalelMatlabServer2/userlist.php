<?php
include "head_all.php";
?>
<title>Работа с пользователями</title>
</head>
<body>
<h1> Работа с пользователями</h1>
<?php
include "header.php";
?>

<?php
include "settings.php";
//include "bcrypt.php";


//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
//echo DBName;
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
$r=mysql_query("select * from users");
$num_res=mysql_num_rows($r);
echo '<table border=2><tr><td>#</td><td>login</td><td>email</td><td>Имя</td><td>Фамилия</td><td>Соглассен загружать модели</td><td>Соглассен загружать данные</td><td>Тип пользователя</td><td>Редактировать</td><td>удалить</td></tr>';
for($i=0; $i<$num_res; $i++)
{ $f=mysql_fetch_array($r);
 $command=rawurldecode($f[command]);
 $email=rawurldecode($f[email]);
echo "<tr><td>$f[id]</td><td>$f[login]</td><td>$email</td><td>$f[name]</td><td>$f[surname]</td><td>$f[loadtool]</td><td>$f[loaddata]</td><td>$f[type]</td>";
if (($uid_cookie>0)&&(($adminauth==1)||($f[id]==$uid_cookie)))
 echo "<td><a href=useredit.php?id=$f[id]>Редактировать</a></td><td><a href=userdelete.php?id=$f[id]>Удалить</a></td></tr>\n";
else
 echo "<td>--</td><td>--</td></tr>\n";
}
echo '</table>';
//(id int, platformid int, methodid int, dataid int, filename char(50), command char(200), state char(30), done int, outfilename char(50), IP char(20), adduserid int, calcuserid int, processid int, begcalcdate date, begcalctime time , predictminutes int ,  endcalcdate date,  endcalctime time)
//if ($uid_cookie>0)
echo "1) <a href=userreg.php>Добавить нового пользователя</a><p>";
if ($adminauth==1)
echo "2) <a href=clear.php>Очистка всей базы данных (!Осторожно, все записи удалятся!)</a><p>";
?>

<?php
include "footer.php";
?>

