<?php
include "settings.php";
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("use tasks");
$command=$_REQUEST['command'];
$command=str_replace("\\","",$command);
$command=rawurlencode($command);
$platformid=$_REQUEST['platformid'];
$dataid=$_REQUEST['dataid'];
$filename=$_REQUEST['filename'];
$status=$_REQUEST['status'];
$done=$_REQUEST['done'];
$IP=$_REQUEST['IP'];
$id=$_REQUEST['id'];
$adduserid=$_REQUEST['adduserid'];
$calcuserid=$_REQUEST['calcuserid'];
$processid=$_REQUEST['processid'];
$begcalcdate=$_REQUEST['begcalcdate'];
$begcalctime=$_REQUEST['begcalctime'];
$endcalcdate=$_REQUEST['endcalcdate'];
$endcalctime=$_REQUEST['endcalctime'];
$predictminutes=$_REQUEST['predictminutes'];

$r=mysql_query("select login,pass,type from users where id=$adduserid");
$f=mysql_fetch_array($r);
$username=$f[login];
$userpass=$f[pass];

if (isset($_COOKIE['uid']))
{
$uid_cookie=$_COOKIE['uid'];
$uhash_cookie=$_COOKIE['uhash'];

include "bcrypt.php";
$bcrypt = new Bcrypt(15);

//$hash = $bcrypt->hash('password');
$auth = $bcrypt->verify($username.$userpass, $uhash_cookie);
//echo "auth result - $auth";
if ($f['type']==1)$adminauth=1;else $adminauth=0;
//echo "adminauth result - $adminauth";
}
else {$auth=0;$uid_cookie=0;}
//echo "uid_cookie = $uid_cookie,userid_old = $userid";
//if (($adminauth==0)&&($auth==1)&&($uid_cookie!=$id))
if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$adduserid))))
{
//header('Location: userlist.php');
//echo "попало сюда!!! adminauth=$adminauth";
echo "Вы не имеете права редактировать настройки этого метода. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}


//try
 {//echo $command;
//if exists($command)
$done=0;
if ($status=='1') $state='wait';
else if ($status=='2') $state='calc';
else {$state='done'; $done=100;}
//echo $state;
//echo '    ';
//if ($active==1)
 mysql_query("update tasks set command='$command' where id=$id");
 mysql_query("update tasks set state='$state' where id=$id");
 mysql_query("update tasks set platformid='$platformid' where id=$id");
 mysql_query("update tasks set dataid='$dataid' where id=$id");
 mysql_query("update tasks set filename='$filename' where id=$id");
 mysql_query("update tasks set done=$done where id=$id");
 mysql_query("update tasks set IP='$IP' where id=$id");
 mysql_query("update tasks set adduserid='$adduserid' where id=$id");
 mysql_query("update tasks set calcuserid='$calcuserid' where id=$id");
 mysql_query("update tasks set processid='$processid' where id=$id");
 mysql_query("update tasks set begcalcdate='$begcalcdate' where id=$id");
 mysql_query("update tasks set begcalctime='$begcalctime' where id=$id");
 mysql_query("update tasks set endcalcdate='$endcalcdate' where id=$id");
 mysql_query("update tasks set endcalctime='$endcalctime' where id=$id");
 mysql_query("update tasks set predictminutes='$predictminutes' where id=$id");
 header('Location: tasklist.php');
 //echo "update tasks set command=$command, state=$state, done=$done, IP='$IP' where id=$id";
  }
?>
