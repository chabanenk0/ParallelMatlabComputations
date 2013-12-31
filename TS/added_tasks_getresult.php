<?php
include "../ParalelMatlabServer2/settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
$id = $_REQUEST['id'];
mysql_select_db(DBName2);
$r=mysql_query("select * from added_tasks where id=$id");

//echo "Deleting database. Result=";
if ($r) 
{//echo "ok"; 
}
else 
{echo "err";
die ("no record $id in added_tasks table");
}
$f=mysql_fetch_array($r);
$taskid=$f['taskid'];
$taskresid=$f['taskresid'];
//echo "trid=$taskresid";
if ($taskresid>0)
 $taskid=$taskresid;
mysql_select_db(DBName);
//echo "taskid=$taskid";
$r=mysql_query("select done,taskgroupid  from tasks where id=$taskid");
if ($r) 
{
//echo "ok"; 
}
else 
{echo "err";
die ("no record $taskid in tasks table");
}
$f=mysql_fetch_array($r);

if ($f['done']!=100)
{
echo "the task $taskid is not already done.";
die("error: task is not done");
}

$taskgroupid=$f['taskgroupid'];
//echo "tgid=$taskgroupid";
$r=mysql_query("select max(id) as maxid from dataresult where taskgroupid=$taskgroupid;");
if ($r) 
{
//echo "ok"; 
}
else 
{echo "err";
die ("no record $taskid in tasks table");
}
$f=mysql_fetch_array($r);
$dataresid=$f['maxid'];
$redirecturl="http://prognoz.ck.ua/ParalelMatlabServer2/dataresunpack.php?id=$dataresid";
//echo "<br>redir=$redirecturl<br>";
// need to update the bookmark link
header("location: $redirecturl");

?>
