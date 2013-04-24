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
//mysql_query("use tasks");
$tasks=$_REQUEST['tasks'];
$tasks=str_replace("\\","",$tasks);
//echo $tasks;
$tok = strtok($tasks, "\n");
$userid=$_REQUEST['userid'];
$platformid=$_REQUEST['platformid'];
$dataid=$_REQUEST['archiveid'];
$methodid=$_REQUEST['methodid'];
$processid=$_REQUEST['processid'];
// 20130114 правка про группы
//curgroupname - имя группы заданий, что добавляется
//taskgroupdata - идентифиактор группы заданий, от которой зависит данное и необходимо 

$curgroupname=$_REQUEST['curgroupname'];
$taskgroupdata=$_REQUEST['taskgroupdata'];
//echo "taskgroupdata=$taskgroupdata<p>\n";

if (isset($_COOKIE['uid']))
{
$uid_cookie=$_COOKIE['uid'];
$uhash_cookie=$_COOKIE['uhash'];
$userid=$uid_cookie;
$r=mysql_query("select login,pass from users where id=$uid_cookie");
$f=mysql_fetch_array($r);
$username=$f[login];
$userpass=$f[pass];
//echo "username=$username <p>";

include "bcrypt.php";
$bcrypt = new Bcrypt(15);

//$hash = $bcrypt->hash('password');
//echo "name+pass=$username.$userpass";
$auth = $bcrypt->verify($username.$userpass, $uhash_cookie);
//echo "auth result - $auth";
if ($f['type']==1)$adminauth=1;else $adminauth=0;
}
else {$auth=1;$uid_cookie=$userid;}
//echo "$auth";
if ($auth==0)
{
//header('Location: userlist.php');
echo "Вы не имеете права добавлять задания от имени этого пользователя. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
echo "<a href='userlist.php'>Вернуться на главную</a>";
//include "footer.php";
exit;
}


$r=mysql_query("select max(id) as maxid from tasks");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res=$f['maxid'];
   else $num_res=0;
 }
$begcalcdate=date("y.m.d");
$begcalctime=date("H:i:s");
$predictminutes=$_REQUEST['predictminutes'];
$filename=$_REQUEST['infile'];
$id=$num_res;

mysql_query("insert into taskgroups(name , description) values( '$curgroupname','group')");

// нужно как-то возвращать номер группы заданий, которая созданна, а интерфейса нет. Потом видимо нужно делать запрос на самую последнюю группу (с макс. ид)
//$r=mysql_query("select max(taskgroupid) as maxtaskid from tasks");
$r=mysql_query("select max(id) as maxtaskid from taskgroups");
$f=mysql_fetch_array($r);
if (array_key_exists('maxtaskid',$f))
$maxtaskid=$f['maxtaskid'];
else 
$maxtaskid=1;

if ($taskgroupdata==0) $begstate='wait';else $begstate='waitdata';

while ($tok) 
{   $id++;
    //echo "Word=$tok<br />";
    $command=rawurlencode($tok);
	//$query_text="insert into tasks(id, command,filename,state,done,IP,adduserid,platformid,dataid,methodid,processid,begcalcdate,begcalctime,predictminutes,calcuserid,endcalcdate,  endcalctime,taskgroupid) values($id, '$command','$filename','wait',0,'0.0.0.0',$userid,$platformid,$dataid,$methodid,$processid,'$begcalcdate','$begcalctime',$predictminutes,0,'0000-00-00','00:00:00',$maxtaskid)";
	$query_text="insert into tasks(id, command,filename,state,done,IP,adduserid,platformid,dataid,methodid,processid, begcalcdate,begcalctime,predictminutes,calcuserid,endcalcdate,  endcalctime,taskgroupid,taskgroupdata) values($id, '$command','$filename','$begstate',0,'0.0.0.0',$userid,$platformid,$dataid,$methodid,0 ,'0000-00-00','00:00:00',$predictminutes,0,'0000-00-00','00:00:00',$maxtaskid, $taskgroupdata)";
    $res=mysql_query($query_text);
     // echo "query=$query_text";
	mysql_query("insert into taskgroupsconn(taskid, taskgroupid) values($id,$maxtaskid)");
    $tok = strtok("\n");
}
//for ($i=$id;$i<$id+$num_new;i++)
//  mysql_query("insert into tasks(id, command,state,done,IP) values($id, '$command','wait',0,'0.0.0.0')");
 //header('Location: tasklist.php');
echo $num_res+1;
echo "\n$maxtaskid";
exit;

?>
