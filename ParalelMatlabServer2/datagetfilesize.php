<?php
//pid=18&dataid=0&taskid=1

include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");

$pid=$_REQUEST['pid'];
$dataid=$_REQUEST['dataid'];
//$taskid=$_REQUEST['taskid'];
$taskgroupid=$_REQUEST['taskgroupid'];
// need to determine username in order to cd to the required folder. 
//Also need the dataname
if(!mysql_connect(HostName,UserName,Password))
{ echo "Ia iiao niaaeieouny n aacie".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("use tasks");
$r=mysql_query("select * from datafolder where id=$dataid");
$f=mysql_fetch_array($r);

//$folder=$f['folder'];
$folder=$uploaddirroot.$f[folder];
$i=strlen($folder);
$flag=0;
while ($i>0)
 {
 if ($folder[$i]=='/')
   {$flag=1; break;
   }
  $i=$i-1;
  }

$j=strlen($folder);
$flagj=0;
while ($j>0)
 {
 if ($folder[$j]=='.')
   {$flagj=1; break;
   }
  $j=$j-1;
  }

$realfolder=$folder;
$dataname  =$folder;
if ($flag)
 { $realfolder=substr($folder,0,$i+1);
   if ($flagj)
   {$dataname=substr($folder,$i+1,($j-$i-1));}
   else 
   {$dataname=substr($folder,$i+1,strlen($folder));}

 }

//echo "i=$i, j=$j, flagj=$flagj<p>";
$userid=$f['userid'];
//2012_2013_computed_uid1_pid_18.rar
$fullname=$dataname."_computed_pid_".$pid."_tgid".$taskgroupid.".rar";
//echo "fullname=$fullname <p>";
$fullpathname=$realfolder.$fullname;
//echo "fullpathname=$fullpathname <p>";

if (is_file($fullpathname))
{
if ($fd=fopen($fullpathname,"rb"))
{
fseek($fd,0,3);
$len=ftell($fd);
echo $len;
}
else echo "0";
}
else echo "0";
?>