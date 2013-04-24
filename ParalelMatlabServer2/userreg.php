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

if (strcmp($loaddata,"on")==0) $loaddata=1;else $loaddata=0;
if (strcmp($loadtool,"on")==0) $loadtool=1;else $loadtool=0;

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
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("use tasks");

if (empty($login))
 {$login_err="Login missed!";$errorflag=1;}
else
 {$r=mysql_query("select login from users where login='$login'");
  if (mysql_num_rows($r)>0)
   {$login_err="Login is busy!";$errorflag=1;}
  }
if (strcmp($pass,$pass2)!=0)
 {$pass_err="Passwords don't match!";$errorflag=1; }
if (preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $email)==0)
 {$email_err="Email is not correct!";$errorflag=1; }
if (!is_numeric($type))
 {$flags_err="Wrong checkbox values!";$errorflag=1; }
if (!$errorflag){
$r=mysql_query("select max(id) as maxid from users");
//$num_res=mysql_num_rows($r);
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res=$f['maxid'];
   else $num_res=0;
 }
$num_res=$num_res+1;
mysql_query("insert into users(id,name, surname, login, pass, loaddata, loadtool,type,email) values('$num_res','$name', '$surname', '$login', '$pass', '$loaddata', '$loadtool','$type','$email')");
// redirect to users table
header('Location: userlist.php');
exit;
}
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
<title>Регистрация нового пользователя</title>
</head>
<body>
<h1> Регистрация нового пользователя </h1>
<?php
include "header.php";
?>

<form method=get action=userreg.php>
Логин: <input type=text name='login' 
<?php
echo "value='$login' >"; 
echo "$login_err";
?>

<p>
Пароль: <input type="password" name='pass'><p>
Пароль: <input type="password" name='pass2'>
<?php
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
<input type=submit value='Сохранить'> <input type=reset value='Очистить'></form><p><p>
1) <a href=tasklist.php>Просмотр текущих заданий</a><p>
2) <a href=taskaddmany.php>Добавление задания</a><p>
3) <a href=clear.php>Очистка списка заданий</a><p>

<?php
include "footer.php";
?>

