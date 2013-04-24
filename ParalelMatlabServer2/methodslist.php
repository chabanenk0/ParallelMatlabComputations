<?php
include "head_all.php";
?>
<title>Работа с методами прогнозирования</title>
</head>
<body>
<h1> Работа с  методами прогнозирования</h1>
<?php
include "header.php";
?>

<?php
include "settings.php";
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
$r=mysql_query("select id,toolboxid,name,descr,folder,userid,platformid,command,version as maxver from methods group by toolboxid");
$num_res=mysql_num_rows($r);
echo '<table border=2><tr><td>#</td><td>Метод</td><td>Описание</td><td>Папка</td><td>Добавил</td><td>Платформа</td><td>Команда</td><td>Версии</td><td>Редактировать</td><td>Загр. исх.</td><td>Обн. исх.</td><td>удалить</td></tr>';
for($i=0; $i<$num_res; $i++)
{ $f=mysql_fetch_array($r);
 $name=rawurldecode($f[name]);
 $descr=rawurldecode($f[descr]);
 $command=rawurldecode($f[command]);
  echo "<tr><td>$f[toolboxid]</td><td>$name</td><td>$descr</td><td>$f[folder]</td><td>$f[userid]</td><td>$f[platformid]</td><td>$command</td><td><a href=methodsverlist.php?id=$f[toolboxid]>смотреть</a></td>";
if (($uid_cookie>0)&&(($adminauth==1)||($f[userid]==$uid_cookie)))
  echo "<td><a href=methodedit.php?id=$f[id]>Редактировать</a></td><td><a href=methodget.php?id=$f[id]>Загрузить</a><td><a href=methodsarchreplace.php?id=$f[id]>Обновить</a></td></td><td><a href=methoddelete.php?id=$f[id]>Удалить</a></td></tr>\n";
else if ($uid_cookie>0)
  echo "<td><a href=methodcopymy.php?id=$f[id]>Создать личную копию</a></td><td><a href=methodget.php?id=$f[id]>Загрузить</a><td>--</td></td><td>--</td></tr>\n";
  else echo "<td>--</td><td><a href=methodget.php?id=$f[id]>Загрузить</a><td>--</td></td><td>--</td></tr>\n";
}
echo '</table>';
//(id int, platformid int, methodid int, dataid int, filename char(50), command char(200), state char(30), done int, outfilename char(50), IP char(20), adduserid int, calcuserid int, processid int, begcalcdate date, begcalctime time , predictminutes int ,  endcalcdate date,  endcalctime time)
?>
Обратите внимание на колонку "Версии". Можно просмотреть версии каждого метода, используя гиперссылку в этой колонке в соответствующей строке метода.<p>

<?php
if ($auth==1)
echo "1) <a href=methodadd.php>Добавить новый метод</a><p>";
include "footer.php";
?>
