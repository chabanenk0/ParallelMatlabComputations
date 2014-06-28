<?php
$basesdir=getcwd();
include "../ParalelMatlabServer2/settings.php";
//define("DBName","matrix");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
set_time_limit(60000); 
//mysql_query("USE matrix;");
mysql_select_db(DBName2);
$count=100;
while ($count>1)
{ 
 $r=mysql_query("select * from matrixqueries where querystate='wait' order by id asc");
 
 $count=mysql_num_rows($r);
 for ($i=0;$i<$count;$i++)
 {
  
  $f=mysql_fetch_array($r);
  $id=$f[id];
  $query=$f[querytext];
  $query=rawurldecode($query);
  echo "executing query:<br>\n $query <br>\n";
  mysql_query("update matrixqueries set querystate='calc' where id=$id");
  $res2=mysql_query($query);
  if (!$res2)
   { echo "\n<br>Ошибка выполнения запроса:";
     echo mysql_error();
     echo "<br>\n";
	 mysql_query("update matrixqueries set querystate='err' where id=$id");
   }
   else
   mysql_query("update matrixqueries set querystate='done' where id=$id");
 }
}

?>