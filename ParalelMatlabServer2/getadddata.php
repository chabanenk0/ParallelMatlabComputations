<?php
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
$taskgroupid=$_REQUEST['taskgroupid'];
mysql_select_db(DBName);
$r=mysql_query("select * from tasksgroups where id=$taskgroupid");
$f=mysql_fetch_array($r);
$dataresultid=$f[dataresultid];
echo "$dataresultid\n";
?>