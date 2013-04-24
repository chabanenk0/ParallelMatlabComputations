<?php
include "settings.php";


$login=rawurlencode($_REQUEST['login']);
$pass=rawurlencode($_REQUEST['pass']);
$errorflag=0;
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
$login_err="";
$pass_err="";
if (array_key_exists('login',$_REQUEST))// the validation condition expected. This is the test 
{
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}

mysql_select_db(DBName);
//mysql_query("use tasks");
include "bcrypt.php";
if (empty($login))
 {$login_err="Login missed!";$errorflag=1;}

// redirect to users table
//echo "select * from users where login='$login'";
$r=mysql_query("select * from users where login='$login'");
$f=mysql_fetch_array($r);
$username=$f['login'];
$userpass=$f['pass'];
//echo "userpass=$userpass";
if (strcmp($userpass,$pass)==0)
{ //echo "right password";
SetCookie("uid",$f[id]);
//echo "Setting cookie uid...";
$bcrypt = new Bcrypt(15);
//echo "creation Bcrypt object...";
$hash = $bcrypt->hash($username.$userpass);
SetCookie("uhash",$hash);
//echo "id=$id, hash=$hash";
//$auth = $bcrypt->verify($username.$userpass, $hash);
}

header('Location: userlist.php');
exit;

}
else
{$errorflag=1;
$loaddata=1;
$loadtool=1;

// and then the form is displaying:
}
?>

<?php
include "head_all.php";
?>
<title>Вход для пользователей</title>
</head>
<body>
<h1> Вход для пользователей </h1>
<?php
include "header.php";
?>

<form method=get action=login.php>
Логин: <input type=text name='login' 
<?php
echo "value='$login' >"; 
echo "$login_err";
?>

<p>
Пароль: <input type="password" name='pass'><p>
<?php
echo "$pass_err";
?>
<p>
<input type=submit value='Войти'> <input type=reset value='Очистить'></form><p><p>

<?php
include "footer.php";
?>

