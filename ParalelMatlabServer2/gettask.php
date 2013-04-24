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

mysql_select_db(DBName);
$taskgroupid=$_REQUEST['taskgroupid'];

if ($taskgroupid==0)
   {$r=mysql_query("select * from tasks where state='wait'");}
else {
   $r=mysql_query("select * from tasks where ((taskgroupid=$taskgroupid) and (state='wait'))");//20130114  в запросе заменен на taskgroupid
   }
//for($i=0; $i<10; $i++)
$f=mysql_fetch_array($r);
$command=rawurldecode($f[command]);
$taskid=$f[id];
$taskgroupid=$f[taskgroupid];
$taskgroupdata=$f[taskgroupdata];
if ($taskid)
{

echo "$taskid \n";
echo "$command \n";

$ip=$_SERVER["REMOTE_ADDR"]; 
//echo $ip;
if ($taskgroupid==0)
   $r=mysql_query("select * from tasks where state='wait'");
else
   $r=mysql_query("select * from tasks where ((taskgroupid=$taskgroupid) and (state='wait'))");//20130114 dataid в запросе заменен на taskgroupid

$f=mysql_fetch_array($r);
$dataid=$f['dataid'];
$workerid=$f['workerid'];
if ($workerid=='')$workerid=1;

$r=mysql_query("select folder from datafolder where id=$dataid");
$f=mysql_fetch_array($r);
$datafile=$f['folder'];

$begcalcdate=date("y.m.d");
$begcalctime=date("H:i:s");

echo "$datafile \n";
echo "$dataid \n";
echo "$taskgroupid \n";
echo "$taskgroupdata \n";

$r=mysql_query("update tasks set state='calc' where id=$taskid");
$r=mysql_query("update tasks set processid='$workerid' where id=$taskid");
$r=mysql_query("update tasks set begcalcdate='$begcalcdate' where id=$taskid");
$r=mysql_query("update tasks set begcalctime='$begcalctime' where id=$taskid");
$r=mysql_query("update tasks set IP='$ip' where id=$taskid");
$r=mysql_query("update tasks set calcuserid='$workerid' where id=$taskid");
//$r=mysql_query("update tasks set IP='' where id=$taskid");
}
?>