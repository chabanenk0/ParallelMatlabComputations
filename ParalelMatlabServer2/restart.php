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
if(array_key_exists('id',$_REQUEST))
{
$id=$_REQUEST['id'];
}
else 
$id=-1;
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

$r= mysql_query("select userid from methods where id=$id");
$f=mysql_fetch_array($r);
$userid=$f['userid'];

if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
//header('Location: userlist.php');
//echo "попало сюда!!! adminauth=$adminauth";
echo "Вы не имеете права перезапускать задачи. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}


if ($id>=0) $r=mysql_query("select * from tasks where state='err' and adduserid=$id");
else
$r=mysql_query("select * from tasks where state='err'");
$num_res=mysql_num_rows($r);
//echo '<h1>Следующие записи завершились с ошибкой и будут перезапущены:';
//echo '<table border=2><tr><td>N</td><td> Command</td><td>State</td><td>done</td><td>IP</td><td>edit</td><td>delete</td></tr>';
for($i=0; $i<$num_res; $i++)
{ $f=mysql_fetch_array($r);
//echo "<tr><td>$f[id]</td><td>$f[command]</td><td>$f[state]</td><td>$f[done]</td><td>$f[IP]</td><td><a href=edit.php?id=$f[id]>edit</a></td><td><a href=delete.php?id=$f[id]>delete<a></td></tr>\n";
  mysql_query("update tasks set state='wait' where id=$f[id]");
}
//echo '</table>';
header('Location: tasklist2.php');
?>
