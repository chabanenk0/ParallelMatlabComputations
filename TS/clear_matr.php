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
echo "Creating tables 4 matr";

// из предыдущего скрипта clear.php
mysql_query("drop table if exists allrecords");
mysql_query("CREATE TABLE allrecords(id int not null auto_increment primary key,tickernum int,name char(20), discr char (20),date DATE,time TIME, open DOUBLE,low DOUBLE,high DOUBLE,close DOUBLE, volume DOUBLE, adjclose DOUBLE, dumbfield1 char(10),dumbfield2 char(10), dumbfield3 char(10))");

if(1)
{
mysql_query("drop table if exists dataseries");
mysql_query("create table dataseries(id int not null auto_increment primary key, name char(20), number int, groupnum int, source int, upddate date , updtime time, type int, discretization int,seriesname char(30))");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization,seriesname,upddate,updtime ) values( 'UX',1,1,2,1,1440,'UX_D','2009-01-01','00:00')");

mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX-C',1,1,2,1,1440,'UXC_D','2009-01-01','00:00')");

mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX',1,1,3,1,60,'UX_H','2009-01-01','00:00')");

mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX-C',1,1,3,1,60,'UXC_H','2009-01-01','00:00')");

mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX',1,1,4,1,1,'UX_M','2013-01-07','00:00')");

mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX-C',1,1,4,1,1,'UXC_M','2013-01-07','00:00')");
}



// Добавление в список записей для БД (дакс, сп, ух...)

mysql_query("drop table if exists dataseries2");
mysql_query("create table dataseries2(id int not null auto_increment primary key, name char(20), number int, groupnum int, source int, upddate date , updtime time, type int, discretization int,seriesname char(30), sector char(20), color int)");
mysql_query("insert into dataseries2 (name, number,groupnum, source,upddate,updtime, type, discretization,seriesname) select name, number,groupnum, source,upddate,updtime, type, discretization,seriesname from dataseries;");
mysql_query("drop table dataseries;");
mysql_query("alter table dataseries2 rename to dataseries;");

mysql_query("create table tmp_dataseries(name char(20), number int,seriesname char(30), sector char(20), color int)");
$res=mysql_query("LOAD DATA LOCAL INFILE '/home/localhost/www/TS/sp.txt' INTO TABLE tmp_dataseries;");
 if (!$res)
 { echo "\n<br>Ошибка выполнения запроса:";
   echo mysql_error();
   echo "<br>\n";
   echo getcwd();
 }

mysql_query("insert into dataseries (name, number,seriesname,sector,color) select * from tmp_dataseries;");
//mysql_query("drop table tmp_dataseries;");

mysql_query("insert into seriesgroups(name , description) values( 'sp matr','SP matrix')");

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