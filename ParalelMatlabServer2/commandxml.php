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
$platformid=$_REQUEST['platformid'];
$toolboxid=$_REQUEST['toolboxid'];
$version=$_REQUEST['version'];

$r=mysql_query("select id, toolboxid,version,command from methods where id=$version");
$f=mysql_fetch_array($r);
echo rawurldecode($f['command']);
?>
