<?php
//include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");

function db_data_load_matr($name, $description,$listfilename,$src)
{
	// if(!mysql_connect(HostName,UserName,Password))
	// 	{ 
	// 		echo "Íå ìîãó ñîåäèíèòüñÿ ñ áàçîé".DBName."!<br>";
	// 		echo mysql_error();
	// 		exit;
	// 	}
	// mysql_select_db(DBName);

	// mysql_query("drop table if exists dataseries2");
	// mysql_query("create table dataseries2(id int not null auto_increment primary key, name char(20), number int, groupnum int, source int, upddate date , updtime time, type int, discretization int,seriesname char(30), sector char(20), color int)");
	// mysql_query("insert into dataseries2 (name, number,groupnum, source,upddate,updtime, type, discretization,seriesname) select name, number,groupnum, source,upddate,updtime, type, discretization,seriesname from dataseries;");
	// mysql_query("drop table dataseries;");
	// mysql_query("alter table dataseries2 rename to dataseries;");
	mysql_query("create table tmp_dataseries(name char(20), number int,seriesname char(30), sector char(20), color int)");
	$res=mysql_query("LOAD DATA LOCAL INFILE '$listfilename' INTO TABLE tmp_dataseries;");
	 if (!$res)
	  {
	  	  echo "\n<br>Îøèáêà âûïîëíåíèÿ çàïðîñà:";
	  	  echo mysql_error();
	  	  echo "<br>\n";
	  	  echo getcwd();
	  }
	mysql_query("insert into dataseries (name, number,seriesname,sector,color) select * from tmp_dataseries;");
	mysql_query("drop table tmp_dataseries;");

	mysql_query("insert into seriesgroups(name , description) values( '$name','$description')");
	$r=mysql_query("select max(id) as maxid from seriesgroups");
	$f=mysql_fetch_array($r);
	if (array_key_exists('maxid',$f))
		$gn=$f['maxid'];
	else $gn=1;

	mysql_query("update dataseries set groupnum=$gn where groupnum is NULL");
	mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) select id as seriesid, $gn as seriesgroupid from dataseries where groupnum=$gn;");

	//$src=1;
	mysql_query("update dataseries set source=$src where source is NULL");
	mysql_query("update dataseries set upddate='1950-01-01' where upddate is NULL");
	mysql_query("update dataseries set updtime='00:00:00' where updtime is NULL");
	mysql_query("update dataseries set type=1 where type is NULL");
	mysql_query("update dataseries set discretization=1440 where discretization is NULL");
	echo "everything is done adding $name.";
}


?>