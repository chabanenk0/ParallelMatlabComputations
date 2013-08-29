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
// if ((isset($_COOKIE['uid']))&&($_COOKIE['uid']==1))
// {
// include "bcrypt.php";
// $uid_cookie=$_COOKIE['uid'];
// $uhash_cookie=$_COOKIE['uhash'];


// $r=mysql_query("select * from users where id=$uid_cookie");
// $f=mysql_fetch_array($r);
// $username=$f['login'];
// $userpass=$f['pass'];
// $bcrypt = new Bcrypt(15);

// //$hash = $bcrypt->hash('password');
// $auth = $bcrypt->verify($username.$userpass, $uhash_cookie);
// //echo "auth result - $auth";
// if ($f['type']==1)$adminauth=1;else $adminauth=0;
// }
// else 
// {
// $auth=0;
// $uid_cookie=0;
// header('Location: userlist.php');
// echo "You don't have rights for clearing all the database.<p>\nPlease <a href=login.php>log in</a> as admin";

// exit;
// }


echo "Creating database TS";
$r=mysql_query("drop database if exists TS");
echo "Deleting database. Result=";
if ($r) 
echo "ok"; 
else 
echo "err";
echo "<p>";
$r=mysql_query("create database TS");
echo "Creating database. Result=";
if ($r) 
echo "ok"; 
else 
echo "err";
echo "<p>";

mysql_select_db(DBName);
echo "Creating tables";

// Итак, таблицы:
// 1) Источники :
// - номер по порядку
// - имя 
// - номер
// - группа
// - шаблон урл для обновления
// (в нем должна быть возможность вставить имя (тикер), номер, дату начало (ГМД), дату конца
// - последнее обновление
// - вид (опен клоуз или поток сделок (тиковый источник)
// - строка импорта (задает порядок строк, пропуски строк в исходнике)... Буду вручную задавать сначала.
mysql_query("drop table if exists datasources");
mysql_query("create table datasources(id int not null auto_increment primary key, name char(20), number int, groupnum int, urltemplate varchar (200), importquery varchar(200))");
mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'Yahoo finance',1,1,'http://ichart.yahoo.com/table.csv?s=@NAME@&a=@DayBeg@&b=@MonthBeg@&c=@YearBeg@&d=@DayEnd@&e=@MonthEnd@&f=@YearEnd@&g=d&ignore=.csv','')");
//wget  --proxy=on  -O LLTC.txt "http://ichart.yahoo.com/table.csv?s=LLTC&a=00&b=1&c=1900&d=19&e=6&f=2099&g=d&ignore=.csv"

mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX days',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=1440&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','')");

mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX Hours',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=60&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','')");

mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX minutes',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=1&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','')");

mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX 5 minutes',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=5&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','')");

mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX 15 minutes',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=15&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','')");

mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX 30 minutes',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=30&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','')");

//wget  --proxy=on  -ehttp_proxy=http://192.168.0.10:3128 -O BAVL.txt "mdata.ux.ua/qdata.aspx?code=BAVL&pb=01012009&pe=08072099&p=1440&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1"

if(0)
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

// из старых примеров:
//CREATE TABLE allnames(name VARCHAR(10), nrec DOUBLE, sname VARCHAR(10),lname VARCHAR(30),sector VARCHAR(20),color varchar(5));

// 2) Данные (опен клоуз)
// - Нормер ряда (ссылка на источники)
// - дата
// - время
// - опен
// - мин
// - макс
// - клоуз
// - объем
// - адж клоуз
if(0)
{
mysql_query("drop table if exists allrecords");
mysql_query("CREATE TABLE allrecords(id int not null auto_increment primary key,tickernum int,name char(20), discr char (20),date DATE,time TIME, open DOUBLE,low DOUBLE,high DOUBLE,close DOUBLE, volume DOUBLE, adjclose DOUBLE, dumbfield1 char(10),dumbfield2 char(10), dumbfield3 char(10))");
}
// 3) Данные (тик)
// - номер ряда (ссылка на источники)
// - дата
// - время
// - цена
// - кол-во 
// - направление (бай/селл) агрессивное
mysql_query("drop table if exists allrecords_tick");
mysql_query("CREATE TABLE allrecords_tick(tickernum int,date DATE, time TIME, datetimeminutes long, price DOUBLE, number DOUBLE, direction char (1))");

// таблица групп рядов (чтобы задавать задание прогнозировать для всей группы.
mysql_query("drop table if exists seriesgroups");
mysql_query("create table seriesgroups(id int not null auto_increment primary key, name char(20), descrition varchar(200))");
mysql_query("insert into seriesgroups(name , description) values( 'UX','UX for forecasting')");

mysql_query("insert into seriesgroups(name , description) values( 'UX_H','UX hour for forecasting')");

mysql_query("insert into seriesgroups(name , description) values( 'UX_M','UX Minutes for forecasting')");

// таблица связи таблицы рядов dataseries и таблицы групп seriesgroups
mysql_query("drop table if exists seriesgroupsconn");
mysql_query("create table seriesgroupsconn(id int not null auto_increment primary key, seriesid int, seriesgroupid int)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 1,1)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 2,1)");

mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 3,2)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 4,2)");

mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 5,3)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 6,3)");


// таблица связи групп рядов и методов прогнозирования (отношение "спрогнозировать", связь группы рядов с таблицей методов в БД matlab2.
mysql_query("drop table if exists groupsmethods");
mysql_query("create table groupsmethods(id int not null auto_increment primary key, groupid int, methodid int,type_precommand int, precommands varchar(1500))");
// type_precommand - tip komandy. 0 - eto novy shablon (vmesto starogo is methods) 1 - stary shablon, a komanda dobavlyaetsya v nachalo...

$commandnew="MarkovChainsUsredn('#infile.txt',[],500,50,'',[],1,'global type_prirash;type_prirash=1;global type_raspr;type_raspr=5;');";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(1,12,0,'$commandnew')");
$commandnew="MarkovChainsUsredn('#infile.txt',[],2000,250,'',[],1,'global type_prirash;type_prirash=1;global type_raspr;type_raspr=5;');";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(2,12,0,'$commandnew')");
$commandnew="global limitdt;limitdt=260;global limitdt_min;limitdt_min=3;global type_prirash;type_prirash=1;MarkovChainsCmd('#infile.txt',500,'#infile_MarkovPrognoz.txt');";
$commandnew=rawurlencode($commandnew);

mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(3,12,1,'$commandnew')");

//20130115 Creating new table for prediction or calculation tasks
// id
// seriesid
// methodid
// dataid
// adddate
// addtime
// taskid
// taskresid
// 
mysql_query("drop table if exists added_tasks");
mysql_query("create table added_tasks(id int not null auto_increment primary key, seriesid int, methodid int, dataid int, adddate date, addtime time,taskid int, taskresid int)");



// 4) данные о матрице:
// - номер матрицы
// - имя
// - имя временной таблицы 
// - имя БД, где временная таблица
// - начальная дата
// - конечная дата
mysql_query("CREATE TABLE matrixdata(id int not null auto_increment primary key,name char (20), temptablename char(20), databasename char (20), begdate DATE, begtime TIME, enddate DATE, endtime TIME)");

// 5) Таблица связи рядов с матрицами...
// - номер матрицы (связь с таблицей матрицы)
// - номер ряда (связь с таблицей опен-клоуз)
// - номер ряда в матрице (целое число)
mysql_query("CREATE TABLE matrixseries(matrixnum int, seriesnum int, position int)");

// 6) и другие - временные таблицы
// - дата
// - ряд1 (клоуз из ряда1)
// - ряд2 (клоуз из ряда2)...
// - и т.д.

?>