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
$taskid=$_REQUEST['taskid'];
$taskresid=$_REQUEST['taskresid'];
mysql_select_db(DBName);
$r=mysql_query("update added_tasks set taskresid=$taskresid where taskid=$taskid");
echo "update added_tasks set taskresid=$taskresid where taskid=$taskid";
//echo "Deleting database. Result=";
if ($r) 
echo "ok"; 
else 
echo "err";
echo "<p>";


?>