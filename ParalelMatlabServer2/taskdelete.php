<?php
include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "�� ���� ����������� � �����".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("use tasks");
//$r=mysql_query("select * from tasks where id=$id");
//$num_res=mysql_num_rows($r);
// $id=$num_res+1;
$id=$_REQUEST['id'];
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

if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$id))))
{
//header('Location: userlist.php');
//echo "������ ����!!! adminauth=$adminauth";
echo "�� �� ������ ����� ������� ����� ������������. <p>\n����������,  <a href=login.php>�������</a> � ������� ��� ������ �������.<p>";
//include "footer.php";
exit;
}

  mysql_query("delete from tasks where id=$id");
header('Location: tasklist2.php');
?>
