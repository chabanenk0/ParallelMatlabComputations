<?php
include "head_all.php";
?>
<title>Работа с пакетами данных </title>
</head>
<body>
<h1> Работа с пакетами данных </h1>
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

if (array_key_exists('firstpos',$_REQUEST))
$firstpos=$_REQUEST['firstpos'];
else
$firstpos=0;
if (array_key_exists('numrecords',$_REQUEST))
$numrecords=$_REQUEST['numrecords'];
else
$numrecords=100;
$r3=mysql_query("select count(id) as cnt from datafolder;");
$f3=mysql_fetch_array($r3);
$num_total=$f3['cnt'];
//echo "f=$firstpos,n=$numrecords";
for ($i=0;$i<$num_total;$i=$i+$numrecords)
 echo "<a href=datalist.php?firstpos=$i&numrecords=$numrecords>$i</a>\n";

$r=mysql_query("select * from datafolder");
$num_res=mysql_num_rows($r);
echo '<table border=2><tr><td>#</td><td>Описание</td><td>Папка</td><td>Добавил</td><td>Результаты</td><td>Редактировать</td><td>Загр. пакет.</td><td>удалить</td></tr>';
for($i=0; $i<$num_res; $i++)            
{ $f=mysql_fetch_array($r);
echo "<tr><td>$f[id]</td><td>$f[descr]</td><td>$f[folder]</td><td>$f[userid]</td><td><a href=datareslist.php?id=$f[id]>Просмотреть</a></td>";
if (($uid_cookie>0)&&(($adminauth==1)||($f[userid]==$uid_cookie)))
echo "<td><a href=dataedit.php?id=$f[id]>Редактировать</a></td><td><a href=dataget.php?id=$f[id]>Загрузить</a></td><td><a href=datadelete.php?id=$f[id]>Удалить</a></td></tr>\n";
else 
echo "<td>--</td><td><a href=dataget.php?id=$f[id]>Загрузить</a></td><td>--</td></tr>\n";
}
echo '</table>';
//(id int, platformid int, methodid int, dataid int, filename char(50), command char(200), state char(30), done int, outfilename char(50), IP char(20), adduserid int, calcuserid int, processid int, begcalcdate date, begcalctime time , predictminutes int ,  endcalcdate date,  endcalctime time)
echo "1) <a href=tasklist.php>Просмотр текущих заданий</a><p>";
if ($auth==1)
echo "2) <a href=dataadd.php>Добавить новый пакет данных</a><p>";

include "footer.php";
?>
