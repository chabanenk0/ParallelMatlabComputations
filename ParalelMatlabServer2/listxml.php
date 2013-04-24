<?php
include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
//echo DBName;
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}

mysql_select_db(DBName);
$dataid=$_REQUEST['dataid'];

$r=mysql_query("select id, folder from datafolder where id=$dataid");
$num_res=mysql_num_rows($r);
$f=mysql_fetch_array($r);
$folder=$uploaddirroot.$f[folder];

$n=strlen($folder);
$arh_letters=substr($folder,$n-3,3);
$osstr=php_uname();
$osstr=substr($osstr,0,3);
if (strcmp($osstr,"Win")==0) // первые 3 буквы имени ОС Win - значит винда...
	{
	$folder2arh=str_replace('/','\\',$folder);
	}


if (strcmp($arh_letters,"rar")==0)
$commandstring="unrar lb ".$folder2arh." > dir.txt";
else if (strcmp($arh_letters,"zip")==0)
$commandstring="unzip -l ".$folder2arh." > dir.txt";
else 
$folder=$uploaddirroot.$folder;
//$commandstring=str_replace("/","\\",$commandstring);
//echo $commandstring;
system($commandstring);
//system("dir /b >dir.txt");
//echo $commandstring." > dir.txt";

//system($commandstring);

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";

echo "<items>\n";
$fp=fopen("dir.txt","r");
$i=0;
while (!feof($fp))
{ 
  $i++;
  $name=fgets($fp);
  $nameendpos=strrpos($name,".txt");
  if ($nameendpos === false)
  continue;
  if (strcmp($arh_letters,"zip")==0)
   {
    $namebegpos=strrpos($name," ");
    if ($namebegpos === false)
     continue;
    $namebegpos++;
   }
  else
     $namebegpos=0;
  $name=substr($name,$namebegpos,($nameendpos-$namebegpos));
  echo "<item>\n<value>$i</value>\n<name>$name</name>\n</item>";
}
echo '</items>';
//unlink("dir.txt");
?>
