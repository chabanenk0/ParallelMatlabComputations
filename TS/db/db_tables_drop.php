<?php
include "../settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Íå ìîãó ñîåäèíèòüñÿ ñ áàçîé".DBName."!<br>";
echo mysql_error();
exit;
}

mysql_select_db(DBName);

mysql_query("drop table if exists datasources;");
mysql_query("drop table if exists dataseries;");
mysql_query("drop table if exists allrecords");
mysql_query("drop table if exists allrecords_tick");
mysql_query("drop table if exists seriesgroups");
mysql_query("drop table if exists seriesgroupsconn");
mysql_query("drop table if exists groupsmethods");
mysql_query("drop table if exists added_tasks");
mysql_query("drop table if exists matrixdata");
mysql_query("drop table if exists matrixseries");
mysql_query("drop table if exists matrixqueries");
mysql_query("drop table if exists resultseries");
mysql_query("drop table if exists resultseriesdata");
