<?php


/*
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
$num=$_REQUEST['id'];*/
function dataresunpack($num,$uploaddirroot)//$location=
{
mysql_select_db(DBName);
//mysql_query("use tasks");
$r=mysql_query("select folder from dataresult where id=$num");
$f=mysql_fetch_array($r);
//$filename=$f[folder];

//echo "uploaddirroot=$uploaddirroot<p>\n";
$filename=$uploaddirroot.$f[folder];
//echo "filename is = $filename<p>";
//header('Content-type: file/binary');
$onlyfilename=$filename;
$i=0;
$firstsymbol=substr($onlyfilename,0,1);
//echo "f[1]=$firstsymbol ;";
$onlypath="";
if (strcmp(substr($onlyfilename,0,1),"/")==0)
 {$onlyfilename=substr($onlyfilename,1);
   $onlypath="/";
//  echo "first slash!!!";
 }
//echo $onlyfilename;


while (!(strpos($onlyfilename,'/')==FALSE))
{$pos=strpos($onlyfilename,'/');
//echo "pos=$pos";
$onlypath=$onlypath.substr($onlyfilename,0,$pos+1);
$onlyfilename=substr($onlyfilename,$pos+1);
//echo $onlyfilename;
$i++;
if ($i>100) break;
}


//echo "<p>onlyfilename=$onlyfilename<p>";
//echo "<p>onlypath=$onlypath<p>";
chdir($onlypath);
$n=strlen($onlyfilename);
$arh_letters=substr($onlyfilename,$n-3,3);
$newfolder=substr($onlyfilename,0,$n-4);
if (!is_dir($newfolder))
   mkdir($newfolder);
$newfolder=$newfolder."/";

// for windows the next line is usful, for Unix it is unneed   
//$newfolder=str_replace('/','\\',$newfolder);

//echo "<p>newfolder=$newfolder<p>";
   

$newfolder2extract=$onlypath.$newfolder;
// for win, not linux
$osstr=php_uname();
$osstr=substr($osstr,0,3);
if (strcmp($osstr,"Win")==0) // первые 3 буквы имени ОС Win - значит винда...
{
$newfolder2extract=str_replace('/','\\',$newfolder2extract);
$filename=str_replace('/','\\',$filename);
}
if (strcmp($arh_letters,"rar")==0)
//$commandstring="unrar x -y ".$filename." *.* ".$newfolder2extract." >nul";
$commandstring="unrar x -y ".$filename." ".$newfolder2extract." >nul";
else if (strcmp($arh_letters,"zip")==0)
$commandstring="unzip ".$filename." -d ".$newfolder2extract." >nul";
//echo "<p>$commandstring<p>";
system($commandstring);

$location="../uploads/".str_replace(".".$arh_letters,"",$f[folder]);
$location2=$uploaddirroot.str_replace(".".$arh_letters,"",$f[folder]);
//echo $location;
//header("Location: $location");
return $location2;
}
?>
