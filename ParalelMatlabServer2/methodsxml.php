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

$r=mysql_query("select distinct toolboxid,name,platformid from methods where platformid=$platformid");
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
//echo "<response>\n";

$num_res=mysql_num_rows($r);
echo "<items>\n";
for($i=0; $i<$num_res; $i++)
{ $f=mysql_fetch_array($r);
 $name=rawurldecode($f[name]);
 echo "<item>\n<value>$f[toolboxid]</value>\n<name>$name</name>\n</item>\n";
}
echo '</items>';
//(id int, platformid int, methodid int, dataid int, filename char(50), command char(200), state char(30), done int, outfilename char(50), IP char(20), adduserid int, calcuserid int, processid int, begcalcdate date, begcalctime time , predictminutes int ,  endcalcdate date,  endcalctime time)
?>
