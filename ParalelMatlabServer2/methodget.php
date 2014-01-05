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
$num=$_REQUEST['id'];
mysql_select_db(DBName);
//mysql_query("use tasks");
$r=mysql_query("select folder from methods where id=$num");
$f=mysql_fetch_array($r);
$filename=$uploaddirroot.$f[folder];
//echo "filename is = $filename<p>";
//header('Content-type: file/binary');
$onlyfilename=$filename;
$i=0;
$firstsymbol=substr($onlyfilename,0,1);
//echo "f[1]=$firstsymbol ;";
if (strcmp(substr($onlyfilename,0,1),"/")==0)
 {$onlyfilename=substr($onlyfilename,1);
//  echo "first slash!!!";
 }
//echo $onlyfilename;
while (!(strpos($onlyfilename,'/')==FALSE))
{$pos=strpos($onlyfilename,'/');
//echo "pos=$pos";
$onlyfilename=substr($onlyfilename,$pos+1);
//echo $onlyfilename;
$i++;
if ($i>100) break;
}
header("Content-Disposition: attachment; filename=\"$onlyfilename\"");

$osstr=php_uname();
$osstr=substr($osstr,0,3);
if (strcmp($osstr,"Win")==0) // первые 3 буквы имени ОС Win - значит винда...
{
//$filename=str_replace('/','\\',$filename);
//echo "winsystem $filename <p>";
}
$fp1=fopen($filename,'rb');
fseek($fp1,0,SEEK_END);
$fsize=ftell($fp1);


if ( isset($_SERVER['HTTP_RANGE']) ) 
{
   preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
   $offset = intval($matches[1]);
   $length = intval($matches[2]) - $offset;
   header('HTTP/1.1 206 Partial Content');
   header('Content-Range: bytes ' . $offset . '-' . ($offset + $length) . '/' . $fsize);
}
else 
{  $offset = 0;
}
header("Content-Length: ".$fsize); 
header('Accept-Ranges: bytes');

fseek($fp1,$offset,SEEK_SET);
if (!$fp1)
 {echo "Ошибка передачи файла $filename. Возможно, он слишком большой. Файлы больше 1 мб не принимаются";
 }
else
{
//echo feof($fp);
while (!feof($fp1))
{ // echo "<p>writing block<p>";
$data= fread($fp1,1000); 
echo $data;
}
fclose($fp1);
}
?>
