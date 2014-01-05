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
$r=mysql_query("select max(id) as maxid from methods");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res=$f['maxid'];
   else $num_res=0;
 }
$num_res=$num_res+1;
$r=mysql_query("select max(toolboxid) as maxtid from methods");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_tool=$f['maxtid'];
   else $num_tool=0;
 }
$num_tool=$num_tool+1;
$name=rawurlencode($_REQUEST['name']);
$descr=rawurlencode($_REQUEST['descr']);
//$folder=$_REQUEST['folder'];
$userid=(int)$_REQUEST['userid'];
$platformid=(int)$_REQUEST['platformid'];
$command=rawurlencode($_REQUEST['command']);
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
echo "Вы не имеете права добавлять метод от имени этого пользователя. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
echo "<a href='userlist.php'>Вернуться на главную</a>";
//include "footer.php";
exit;
}



//echo 'Script rabotaet!!!';
//print_r($_FILES);
if (array_key_exists('filetool',$_FILES))
{
$tmpFile=$_FILES['filetool']['tmp_name'];
//echo "<p>tmpname=$tmpFile<p>";
$uploaddir=$uploaddirroot.$username."/methods/";
$folder=$uploaddir.$_FILES['filetool']['name'];
$folder2=$username."/methods/".$_FILES['filetool']['name'];
if (!is_dir($folder))
 {
  if (!is_dir($uploaddirroot))
   {mkdir($uploaddirroot);
   }
  if  (!is_dir($uploaddirroot."/".$username))
   { mkdir($uploaddirroot."/".$username);
   }
   if (!is_dir($uploaddir))
   mkdir($uploaddir);

 }
$fp=fopen($tmpFile,'rb');
if (!$fp)
{    print "ErrorOpeningFile !";
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
}
else
 $folder="";
mysql_query("insert into methods(id,name, descr, folder, userid, version, platformid,command,toolboxid) values('$num_res','$name', '$descr', '$folder2', '$userid', 1, '$platformid','$command',$num_tool)");

$q1=mysql_query("select max(id) as maxid from methodsusers");
$f1=mysql_fetch_array($q1);
$nextid=$f1['maxid']+1;
mysql_query("insert into methodsusers(id ,userid , methodid) values($nextid, $userid, $num_res)");

//echo        "insert into methods(id,name, descr, folder, userid, version, platformid,command,toolboxid) values('$num_res','$name', '$descr', '$folder2', '$userid', 1, '$platformid','$command',$num_tool)";
header('Location: methodslist.php');
exit;

//echo "\n insert into methods(id,name, descr, folder, userid, platformid,command) values('$num_res','$name', '$descr', '$folder', '$userid', '$platformid','$command')";
//echo "Done.";
?>