<?php
include "settings.php";
include "bcrypt.php";
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);

if (isset($_COOKIE['uid']))
{
$uid_cookie=$_COOKIE['uid'];
$uhash_cookie=$_COOKIE['uhash'];


$r=mysql_query("select * from users where id=$uid_cookie");
$f=mysql_fetch_array($r);
$username=$f['login'];
$userpass=$f['pass'];
$bcrypt = new Bcrypt(15);

//$hash = $bcrypt->hash('password');
$auth = $bcrypt->verify($username.$userpass, $uhash_cookie);
//echo "auth result - $auth";
if ($f['type']==1)$adminauth=1;else $adminauth=0;
}
else {$auth=0;$uid_cookie=0;}

$uname=$f['name']." ".$f['surname'];
if (strcmp($uname,"")==0)$uname=$f['login'];
if ($auth==1)
echo "Hello, $uname!!!<a href=logout.php>Logout</a><p>\n";
else echo "<a href=login.php>Login</a> <a href=userreg.php>New user</a>  <a href=userforget.php>Forget password?</a><p>";
?>

<table border=1>
<tr>
<td><a href=userlist.php>Пользователи</a></td>
<td><a href=processlist.php>Рабочие станции</a></td>
<td><a href=methodslist.php>Методы прогнозирования</a></td>
<td><a href=datalist.php>Данные</a></td>
<td><a href=tasklist2.php>Задания</a></td>
<td><a href=datareslist.php>Результаты</a></td>
</tr>
</table><p>
