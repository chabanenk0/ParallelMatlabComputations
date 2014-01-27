<?php
require_once "../ParalelMatlabServer2/settings.php";
require_once "../ParalelMatlabServer2/bcrypt.php";
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
 mysql_select_db(DBName2);
?>


    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Паралельное прогнозирование временных рядов</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
		<li><a href="index-series.php">Временные ряды</a></li>
		<li><a href="index-data.php">Данные рядов</a></li>
		<li><a href="index-matrix.php">Матричные БД</a></li>
		<li><a href="index-result.php">Результаты</a></li>
		<li><a href="../ParalelMatlabServer2/">Подсистема ParallelCalc</a></li>
		<?php
		if ($auth==1)
			echo "<li>Hello, $uname!!!<a href=logout.php>Logout</a></li>\n";
		else echo "<li><a href=login.php>Login</a></li> <li><a href=userreg.php>New user</a></li> <li><a href=userforget.php>Forget password?</a></li>";
?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

   <div class="container">

      <div class="starter-template">