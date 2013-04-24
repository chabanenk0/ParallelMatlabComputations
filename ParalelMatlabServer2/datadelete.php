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
$id=$_REQUEST['id'];
//mysql_query("use tasks");
// $id=$num_res+1;

if (isset($_COOKIE['uid']))
{
$uid_cookie=$_COOKIE['uid'];
$uhash_cookie=$_COOKIE['uhash'];


$r=mysql_query("select * from users where id=$uid_cookie");
$f=mysql_fetch_array($r);
$username=$f['login'];
$userpass=$f['pass'];

include "bcrypt.php";
$bcrypt = new Bcrypt(15);

//$hash = $bcrypt->hash('password');
$auth = $bcrypt->verify($username.$userpass, $uhash_cookie);
//echo "auth result - $auth";
if ($f['type']==1)$adminauth=1;else $adminauth=0;
}
else {$auth=0;$uid_cookie=0;}

$r= mysql_query("select userid from datafolder where id=$id");
$f=mysql_fetch_array($r);
$userid=$f['userid'];

if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
echo "adminauth=$adminauth, auth=$auth, uid_cookie=$uid_cookie, userid=$userid\n<p>";
//header('Location: userlist.php');
//echo "попало сюда!!! adminauth=$adminauth";
echo "Вы не имеете права удалить этот пакет данных. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}


$r= mysql_query("select folder from datafolder where id=$id");
$f=mysql_fetch_array($r);
//$folder=$f[folder];
$folder=$uploaddirroot.$f[folder];
//echo $folder;
if ($id!=1)
{
unlink($folder);
mysql_query("delete from datafolder where id=$id");
}
header('Location: datalist.php');
exit;

?>
