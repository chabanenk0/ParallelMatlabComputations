<html>
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
//include "settings.php";
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

mysql_select_db(DBName2);
$r=mysql_query("select * from added_tasks;");
$num_res=mysql_num_rows($r);
echo '<table 
border=2><tr><td>#</td><td>seriesidе</td><td>methodid</td><td>dataid</td><td>adddate</td><td>addtime</td><td>taskid</td><td>taskresid</td><td>view 
result</td> </tr>';
for($i=0; $i<$num_res; $i++)            
{ $f=mysql_fetch_array($r);
echo 
"<tr><td>$f[id]</td><td>$f[seriesid]</td><td>$f[methodid]</td><td>$f[dataid]</td><td>$f[adddate]</td><td>$f[addtime]</td><td>$f[taskid]</td><td>$f[taskresid]</td><td><a 
href=added_tasks_getresult.php?id=$f[id]>$f[id]</a></td></tr> \n";

}
echo '</table>';
include "footer.php";

?>
