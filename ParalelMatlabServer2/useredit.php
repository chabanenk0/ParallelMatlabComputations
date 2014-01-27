<?php
include "settings.php";

$login=rawurlencode($_REQUEST['login']);
$pass=rawurlencode($_REQUEST['pass']);
$pass2=rawurlencode($_REQUEST['pass2']);
$email=$_REQUEST['email'];
$name=rawurlencode($_REQUEST['name']);
$surname=rawurlencode($_REQUEST['surname']);
$loaddata=$_REQUEST['loaddata'];
$loadtool=$_REQUEST['loadtool'];
$type=$_REQUEST['type'];
$id=$_REQUEST['id'];
if ($id=="") $id=1;



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


if (strcmp($loaddata,"on")==0) $loaddata=1;else $loaddata=0;
if (strcmp($loadtool,"on")==0) $loadtool=1;else $loadtool=0;
}
$errorflag=0;
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
$login_err="";
$pass_err="";
$email_err="";
$flags_err="";

if (array_key_exists('login',$_REQUEST))// the validation condition expected. This is the test 
{
//mysql_query("use tasks");


if (empty($login))
 {$login_err="Login missed!";$errorflag=1;}
if (strcmp($pass,$pass2)!=0)
 {$pass_err="Passwords don't match!";$errorflag=1; }
if (preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $email)==0)
 {$email_err="Email is not correct!";$errorflag=1; }
if (!is_numeric($type))
 {$flags_err="Wrong checkbox values!";$errorflag=1; }
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
}
else {$auth=0;$uid_cookie=0;}
//if (($adminauth==0)&&($auth==1)&&($uid_cookie!=$id))
if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
//header('Location: userlist.php');
//echo "попало сюда!!! adminauth=$adminauth";
echo "Вы не имеете права редактировать данные этого пользователя. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}


if (!$errorflag){
$r=mysql_query("update users set login='$login' where id=$id");
mysql_query("update users set pass='$pass' where id=$id");
mysql_query("update users set email='$email' where id=$id");
mysql_query("update users set name='$name' where id=$id");
mysql_query("update users set surname='$surname' where id=$id");
mysql_query("update users set loaddata='$loaddata' where id=$id");
mysql_query("update users set loadtool='$loadtool' where id=$id");
mysql_query("update users set type='$type' where id=$id");

// redirect to users table
header('Location: userlist.php');
exit;
}
}
else
{
$r=mysql_query("select * from users where id=$id");
//echo "select * from users where id=$id<p>\n";
$f=mysql_fetch_array($r);
$login=$f['login'];
$pass=$f['pass'];
$pass2=$pass;
$email=$f['email'];
$name=$f['name'];
$surname=$f['surname'];
$loaddata=$f['loaddata'];
$loadtool=$f['loadtool'];
$type=$f['type'];

$errorflag=1;
// and then the form is displaying:
}
?>

<?php
include "head_all.php";
?>
<title>Регистрация нового пользователя</title>
</head>
<body>
<h1> Редактирование данных пользователя </h1>
<?php
include "header.php";
//if (($adminauth==0)&&($auth==1)&&($uid_cookie!=$id))
if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$id))))
{
//header('Location: userlist.php');
//echo "uid_cookie=$uid_cookie";
echo "Вы не имеете права редактировать данные этого пользователя. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему с нужного аккаунта.<p>";
include "footer.php";
exit;
}

?>
<h1> Редактирование данных пользователя </h1>

<form method=get action=useredit.php>
<?php
echo "<input type=hidden name='id' value=$id><p>";
?>
Логин: <input type=text name='login' 
<?php
echo "value='$login' >"; 
echo "$login_err";
?>
<p>
<?php
echo "pass: <input type='password' name='pass' value='$pass'><p>";
echo "pass2: <input type='password' name='pass2' value='$pass2'> <p>";
echo "$pass_err";
?>
<p>
e-mail: <input type=text name='email' 
<?php 
echo "value='$email'>"; 
echo "$email_err";
?>
<p>
Имя: <input type=text name='name' 
<?php 
echo "value='$name' >"; 
?>
<p>
Фамилия: <input type=text name='surname' 
<?php 
echo "value='$surname'>"; 
?>
<p>
<label>

<?php 
if ($loaddata==1)
echo "<input type='checkbox' name='loaddata' checked='on'>";
else 
echo "<input type='checkbox' name='loaddata'>";
?>
Соглассен загружать данные <br />
</label>
<label>
<?php
if ($loadtool==1)
echo "<input type='checkbox' name='loadtool' checked='on'>"; 
else
echo "<input type='checkbox' name='loadtool'>"; 
?>
Соглассен загружать тулбоксы<br />
</label>
<?php
echo "<b><r>$flags_err </r></b>"; 
if ($type==1) {$typeselect1="selected";$typeselect2="";}
else {$typeselect1="";$typeselect2="selected";}
?>

<select name='type'>
<?php
echo "<option value=1 $typeselect1 >администратор</option>";
echo "<option value=0 $typeselect2 >Обычный пользователь</option>";
?>
</select>
<p>
<input type=submit value='Сохранить'> <input type=reset value='Очистить'></form><p><p>
1) <a href=tasklist2.php>Просмотр текущих заданий</a><p>
2) <a href=taskaddmany.php>Добавление задания</a><p>
3) <a href=clear.php>Очистка списка заданий</a><p>

<?php
include "footer.php";
?>
