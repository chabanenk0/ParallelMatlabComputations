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

$id=$_REQUEST['id'];
$descr=$_REQUEST['descr'];
$r=mysql_query("select * from methods where id=$id");
$f=mysql_fetch_array($r);

$name=rawurldecode($f['name']);
//$descr=$f['descr'];
$folder=$f['folder'];
$userid=$f['userid'];
$toolboxid=$f['toolboxid'];
$platformid=$f['platformid'];
$command=rawurldecode($f['command']);


if (array_key_exists('version',$_REQUEST))
{

if (isset($_COOKIE['uid']))
{
$uid_cookie=$_COOKIE['uid'];
$uhash_cookie=$_COOKIE['uhash'];

$r=mysql_query("select login,pass from users where id=$userid");
$f=mysql_fetch_array($r);
$username=$f[login];
$userpass=$f[login];


include "bcrypt.php";
$bcrypt = new Bcrypt(15);

//$hash = $bcrypt->hash('password');
$auth = $bcrypt->verify($username.$userpass, $uhash_cookie);
//echo "auth result - $auth";
if ($f['type']==1)$adminauth=1;else $adminauth=0;
}
else {$auth=0;$uid_cookie=0;}

if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
//header('Location: userlist.php');
//echo "попало сюда!!! adminauth=$adminauth";
echo "Вы не имеете права обновлять исходники этого метода. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}

$r=mysql_query("select max(id) as maxid from methods");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res=$f['maxid'];
   else $num_res=0;
 }
$num_res=$num_res+1;
$r=mysql_query("select id, max(version) as maxver from methods where id=$id");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $version=$f['maxver'];
   else $version=0;
 }
$version=$version+1;
$r=mysql_query("select login from users where id=$userid");
$f=mysql_fetch_array($r);
$username=$f[login];
//echo "username=$username <p>";

//echo 'Script rabotaet!!!';
//print_r($_FILES);
$tmpFile=$_FILES['filetool']['tmp_name'];
//echo "<p>tmpname=$tmpFile<p>";
$uploaddir=$uploaddirroot.$username."/methods/";
$folder=$uploaddir.$_FILES['filetool']['name'];

if (!is_dir($folder))
 {
  if (!is_dir($uploaddirroot))
   {mkdir($uploaddirroot);
   }
  if  (!is_dir($uploaddirroot."/".$username))
   { mkdir($uploaddirroot."/".$username);
     mkdir($uploaddir);
   }

 }
$fp=fopen($tmpFile,'rb');
if (!$fp)
{    print "ErrorOpeningFile !";
}

//$fp=fopen($tmpFile,'rb');

$corrected_filename=str_replace(".rar","_v$version.rar",$_FILES['filetool']['name']);
$corrected_filename=str_replace(".zip","_v$version.zip",$corrected_filename);
$outfile= $uploaddir.$corrected_filename;
$folder2=$username."/methods/".$corrected_filename;
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
       echo "insert into methods(id,name, descr, folder, userid, version, platformid,command,toolboxid) values($num_res,'$name', '$descr', '$folder2', $userid, $version, $platformid,'$command',$toolboxid)";
mysql_query("insert into methods(id,name, descr, folder, userid, version, platformid,command,toolboxid) values($num_res,'$name', '$descr', '$folder2', $userid, $version, $platformid,'$command',$toolboxid)");
header('Location: methodslist.php');
exit;
}
?>

<?php
include "head_all.php";
?>
<title> Обновление исходников метода </title>
</head>
<body>
<h1>  Обновление исходников метода </h1>
<?php
include "header.php";
if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
//header('Location: userlist.php');
//echo "попало сюда!!! adminauth=$adminauth";
echo "Вы не имеете права обновлять исходники этого метода. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}
?>
<form enctype="multipart/form-data" action=methodsarchreplace.php method=post>
Название метода : 
<?
echo "<input type=hidden name='id' value='$id'>\n";
echo "<input type=hidden name='version' value='2'>\n";
echo "$name ";
echo "<a href=methodedit.php?id=$id>Редактировать детали</a><p>";
?>
Добавьте архив (*.rar, *.zip) с новыми исходниками этого метода для системы 
<?php
$r=mysql_query("select * from platforms where id=$platformid");
$f=mysql_fetch_array($r);
$platformname=$f['name'];
echo $platformname;

?>: <p>
Архив програм:<input type=file name='filetool'><p><p>
<textarea name='descr'>
...Впишите сюда краткое описание новой добавляемой версии...
</textarea>
<p>
Команда (используйте текст #infilename, #outfilename для обозначения имени входного и выходного файла соответственно).<p>
<?php
echo '<input type=text name=command value="';
echo $command;
echo '"><p>';
echo "\n";

?>
<input type=submit value="Добавить">


</form>
</body>
</html>
