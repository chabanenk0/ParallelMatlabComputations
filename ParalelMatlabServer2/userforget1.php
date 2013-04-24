<?php
include "settings.php";

$pass=rawurlencode($_REQUEST['pass']);
$pass2=rawurlencode($_REQUEST['pass2']);
$id=$_REQUEST['id'];
$rndnum=$_REQUEST['rnd'];
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
$errorflag=0;
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
$pass_err="";

if (array_key_exists('pass2',$_REQUEST))// the validation condition expected. This is the test 
{
//mysql_query("use tasks");


if (strcmp($pass,$pass2)!=0)
 {$pass_err="Passwords don't match!";$errorflag=1; }
$r=mysql_query("select * from users where id = '$id'");
$f=mysql_fetch_array($r);
if ($rndnum!=$f['rndnum'])$errorflag=0;

if (!$errorflag){
mysql_query("update users set pass='$pass' where id=$id");
$random_number=rand(1,32765);
mysql_query("update users set rndnum=$random_number where id=$id");
// redirect to users table
header('Location: userlist.php');
exit;
}
}
else
{
$pass='';
$pass2=$pass;

$errorflag=1;
// and then the form is displaying:
}
?>

<?php
include "head_all.php";
?>
<title>Восстановление забытого пароля</title>
</head>
<body>
<h1> Восстановление забытого пароля </h1>
<?php
include "header.php";
?>
<form method=get action=userforget1.php>
<?php
echo "<input type=hidden name='id' value=$id><p>";
echo "<input type=hidden name='rndnum' value=$rndnum><p>";
echo "pass: <input type='password' name='pass' value='$pass'><p>";
echo "pass2: <input type='password' name='pass2' value='$pass2'> <p>";
echo "$pass_err";
?>
<p>
<input type=submit value='Сохранить'> <input type=reset value='Очистить'></form><p><p>
<?php
include "footer.php";
?>
