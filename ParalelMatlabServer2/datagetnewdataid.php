<?php
//pid=18&dataid=0&taskid=1

include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");

$uid=$_REQUEST['uid'];
//Also need the dataname
if(!mysql_connect(HostName,UserName,Password))
{ echo "Ia iiao niaaeieouny n aacie".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("use tasks");
$r=mysql_query("select max(id) as maxid from datafolder");
$f=mysql_fetch_array($r);
$num=$f[maxid]+1;
echo $num;
mysql_query("insert into datafolder(id,userid,descr,folder) values($num,$uid,'descr','')");

?>