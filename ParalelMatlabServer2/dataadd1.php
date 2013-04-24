<?
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
//$name=rawurlencode($_REQUEST['name']);
$descr=rawurlencode($_REQUEST['descr']);
$folder=$_REQUEST['folder'];
$userid=$_REQUEST['userid'];
$platformid=$_REQUEST['platformid'];
$command=$_REQUEST['command'];
$r=mysql_query("select login,pass from users where id=$userid");
$f=mysql_fetch_array($r);
$username=$f[login];
$userpass=$f[pass];
//echo "username=$username <p>";
if (isset($_COOKIE['uid']))
{
$uid_cookie=$_COOKIE['uid'];
$uhash_cookie=$_COOKIE['uhash'];

include "bcrypt.php";
$bcrypt = new Bcrypt(15);

//$hash = $bcrypt->hash('password');
//echo "name+pass=$username.$userpass";
$auth = $bcrypt->verify($username.$userpass, $uhash_cookie);
//echo "auth result - $auth";
if ($f['type']==1)$adminauth=1;else $adminauth=0;
}
else {$auth=0;$uid_cookie=0;}
//echo "$auth";
if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
//header('Location: userlist.php');
echo "Вы не имеете права добавлять пакет данных от имени этого пользователя. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
echo "<a href='userlist.php'>Вернуться на главную</a>";
//include "footer.php";
exit;
}



//echo 'Script rabotaet!!!';
//print_r($_FILES);
$tmpFile=$_FILES['filetool']['tmp_name'];
//echo "<p>tmpname=$tmpFile<p>";
//$uploaddirroot = '/home/localhost/www/uploads/';
//$uploaddirroot = '/hsphere/local/home/chabanenko/prognoz.ck.ua/uploads/';

$uploaddir=$uploaddirroot.$username."/data/";
$folder=$uploaddir.$_FILES['filetool']['name'];
$folder2=$username."/data/".$_FILES['filetool']['name'];
if (!is_dir($uploaddir))
 { //print "Creating FOLDER $uploaddirroot <p>\n";
  if (!is_dir($uploaddirroot))
   {mkdir($uploaddirroot);
   }
  if  (!is_dir($uploaddirroot."/".$username))
   {
     //print "<p>Creating dirFOLDER $uploaddir <p>\n";
     mkdir($uploaddirroot."/".$username);
   }
   if (!is_dir($uploaddir))
     {//print "<p>Creating UPLOADDIR $uploaddir <p>\n";
     //print "<p>Creating UPLOADDIR $uploaddir <p>\n";
     mkdir($uploaddir);
	 }
 }
$fp=fopen($tmpFile,'rb');
if (!$fp)
{    print "ErrorOpeningFile !";
     exit;
}

//$fp=fopen($tmpFile,'rb');
$outfile= $uploaddir.$_FILES['filetool']['name'];
//echo "<p>$outfile <p>";
$fp1=fopen($outfile,'wb');
if (!$fp1)
 {echo "Ошибка передачи файла. Возможно, он слишком большой. Файлы больше 1 мб не принимаются";
 }
else
{
//echo feof($fp);
while (!feof($fp))
{ // echo "<p>writing block<p>";
$data= fread($fp,$_FILES['filetool']['size']); 
fwrite($fp1,$data);
}
fclose($fp1);
}
fclose($fp);
$r=mysql_query("select max(id) as maxid from datafolder");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res=$f['maxid'];
   else $num_res=0;
 }
$num_res=$num_res+1;

mysql_query("insert into datafolder(id, descr, folder, userid) values($num_res, '$descr', '$folder2', $userid)");
//echo "insert into datafolder(id, descr, folder, userid) values($num_res, '$descr', '$folder2', $userid)";
//echo "Done.";
header('Location: datalist.php');
exit;

?>
