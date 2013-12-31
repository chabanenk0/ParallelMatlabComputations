<?php
include "../ParalelMatlabServer2/settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}

mysql_select_db(DBName2);
echo "Updating tables for FTSE matrix\n";

mysql_query("drop table if exists tmp_dataseries;");
mysql_query("create table tmp_dataseries(name char(20), number int,seriesname char(30), sector char(20), color int)");
$res=mysql_query("LOAD DATA LOCAL INFILE '/home/localhost/www/TS/ftse.txt' INTO TABLE tmp_dataseries;");
 if (!$res)
 { echo "\n<br>Ошибка выполнения запроса:";
   echo mysql_error();
   echo "<br>\n";
   echo getcwd();
 }

mysql_query("insert into dataseries (name, number,seriesname,sector,color) select * from tmp_dataseries;");
//mysql_query("drop table tmp_dataseries;");

mysql_query("insert into seriesgroups(name , description) values( 'FTSE matr','FTSE matrix')");

$r=mysql_query("select max(id) as maxid from seriesgroups");
$f=mysql_fetch_array($r);
if (array_key_exists('maxid',$f))
$gn=$f['maxid'];
else $gn=1;


mysql_query("update dataseries set groupnum=$gn where groupnum is NULL");

mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) select id as seriesid, $gn as seriesgroupid from dataseries where groupnum=$gn;");

$src=1;
mysql_query("update dataseries set source=$src where source is NULL");
mysql_query("update dataseries set upddate='1950-01-01' where upddate is NULL");
mysql_query("update dataseries set updtime='00:00:00' where updtime is NULL");
mysql_query("update dataseries set type=1 where type is NULL");
mysql_query("update dataseries set discretization=1440 where discretization is NULL");


?>