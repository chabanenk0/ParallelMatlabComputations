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
$uid=$_REQUEST['uid'];
$platformid=$_REQUEST['platformid'];
$regdate=date("y.m.d");
$regtime=date("H:i:s"); 
mysql_select_db(DBName);
if (array_key_exists('pid',$_REQUEST))
{$flag_new_process=0;
$id=$_REQUEST['pid'];
$r=mysql_query("select id as maxid from processes where id=$id");
$f=mysql_fetch_array($r);
if ($f)
 {
 $num_res=$f['maxid'];
 $id=$num_res;
 //echo "test id=$id <p>\n";
} 
}
else
{
$flag_new_process=1;
$r=mysql_query("select max(id) as maxid from processes");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res=$f['maxid'];
   else $num_res=0;
 }
$id=$num_res+1;
}

$ip=$ip=$_SERVER["REMOTE_ADDR"]; 
if ($flag_new_process)
$r=mysql_query("insert into processes(id, regdate, regtime,  userid,  processid, platformid,ip) values($id, '$regdate','$regtime',$uid,$id,$platformid,'$ip')");

//          echo "insert into processes(id, regdate, regtime,  userid,  processid, platformid,ip) values($id, '$regdate','$regtime',$uid,$id,$platformid,'$ip')";
$r2=mysql_query("select loadtool from users where id=$uid");
$f2=mysql_fetch_array($r2);
if ($f2['loadtool'])// vse methody zagruzhat 
 $r=mysql_query("select id as methodid, name from methods");
else
 $r=mysql_query("select * from methodsusers where userid=$uid");
print "$id \n";
$num_res=mysql_num_rows($r);
echo  "$num_res \n";
for($i=0; $i<$num_res; $i++)
{$f=mysql_fetch_array($r);
 $methodid=$f[methodid];
// echo  "$methodid \n";
if ($f2['loadtool'])// vse methody zagruzhat 
 $methodname=$f['name'];
else
{
 $r1=mysql_query("select name from methods where id=$methodid");
 $f1=mysql_fetch_array($r1);
 $methodname=$f1['name'];
}
 echo  "$methodid $methodname\n";
}

?>