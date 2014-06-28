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

// Èòàê, òàáëèöû:
// 1) Èñòî÷íèêè :
// - íîìåð ïî ïîðÿäêó
// - èìÿ 
// - íîìåð
// - ãðóïïà
// - øàáëîí óðë äëÿ îáíîâëåíèÿ
// (â íåì äîëæíà áûòü âîçìîæíîñòü âñòàâèòü èìÿ (òèêåð), íîìåð, äàòó íà÷àëî (ÃÌÄ), äàòó êîíöà
// - ïîñëåäíåå îáíîâëåíèå
// - âèä (îïåí êëîóç èëè ïîòîê ñäåëîê (òèêîâûé èñòî÷íèê)
// - ñòðîêà èìïîðòà (çàäàåò ïîðÿäîê ñòðîê, ïðîïóñêè ñòðîê â èñõîäíèêå)... Áóäó âðó÷íóþ çàäàâàòü ñíà÷àëà.
// Èìïîðòêâåðè  ñîäåðæèò òîëüêî ïîñëåäíþþ ñòðîêó çàïðîñà, îòâå÷àþùóþ çà ïîñëåäîâàòåëüíîñòü ïîëåé...
mysql_query("create table datasources(id int not null auto_increment primary key, name char(20), number int, groupnum int, urltemplate varchar (200), importquery varchar(200), dateformat varchar(30), delimiter varchar(1))");

// old 20131105 
//mysql_query("create table dataseries(id int not null auto_increment primary key, name char(20), number int, groupnum int, source int, upddate date , updtime time, type int, discretization int, seriesname char(30))");

mysql_query("create table dataseries(id int not null auto_increment primary key, name char(20), number int, groupnum int, source int, upddate date , updtime time, type int, discretization int, seriesname char(70), sector char(20), color int)");


mysql_query("create table seriesgroupsconn(id int not null auto_increment primary key, seriesid int, seriesgroupid int)");
mysql_query("create index seriesindex on seriesgroupsconn (seriesid)");
mysql_query("create index seriesgroupindex on seriesgroupsconn (seriesgroupid)");
// èç ñòàðûõ ïðèìåðîâ:
//CREATE TABLE allnames(name VARCHAR(10), nrec DOUBLE, sname VARCHAR(10),lname VARCHAR(30),sector VARCHAR(20),color varchar(5));

// 2) Äàííûå (îïåí êëîóç)
// - Íîðìåð ðÿäà (ññûëêà íà èñòî÷íèêè)
// - äàòà
// - âðåìÿ
// - îïåí
// - ìèí
// - ìàêñ
// - êëîóç
// - îáúåì
// - àäæ êëîóç

mysql_query("CREATE TABLE allrecords(id int not null auto_increment primary key,tickernum int,name char(20), discr char (20),date DATE,time TIME, open DOUBLE,low DOUBLE,high DOUBLE,close DOUBLE, volume DOUBLE, adjclose DOUBLE, dumbfield1 char(10),dumbfield2 char(10), dumbfield3 char(10)) engine='myisam'");
mysql_query("create index dateindex on allrecords (date,time)");
// mysql_query("create fulltext index nameindex on allrecords (name)");
mysql_query("create index tickernumindex on allrecords (tickernum)");
echo mysql_error();
// 3) Äàííûå (òèê)
// - íîìåð ðÿäà (ññûëêà íà èñòî÷íèêè)
// - äàòà
// - âðåìÿ
// - öåíà
// - êîë-âî 
// - íàïðàâëåíèå (áàé/ñåëë) àãðåññèâíîå

mysql_query("CREATE TABLE allrecords_tick(id int not null auto_increment primary key,tickernum int,date DATE, time TIME, datetimeminutes long, price DOUBLE, number DOUBLE, direction char (1))");
mysql_query("create index dateindex on allrecords_tick (date,time)");

// òàáëèöà ãðóïï ðÿäîâ (÷òîáû çàäàâàòü çàäàíèå ïðîãíîçèðîâàòü äëÿ âñåé ãðóïïû.
mysql_query("create table seriesgroups(id int not null auto_increment primary key, name char(20), description varchar(200))");

// òàáëèöà ñâÿçè òàáëèöû ðÿäîâ dataseries è òàáëèöû ãðóïï seriesgroups


// òàáëèöà ñâÿçè ãðóïï ðÿäîâ è ìåòîäîâ ïðîãíîçèðîâàíèÿ (îòíîøåíèå "ñïðîãíîçèðîâàòü", ñâÿçü ãðóïïû ðÿäîâ ñ òàáëèöåé ìåòîäîâ â ÁÄ matlab2.
mysql_query("create table groupsmethods(id int not null auto_increment primary key, groupid int, methodid int,type_precommand int, precommands varchar(1500))");
// type_precommand - tip komandy. 0 - eto novy shablon (vmesto starogo is methods) 1 - stary shablon, a komanda dobavlyaetsya v nachalo...


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

mysql_query("create table added_tasks(id int not null auto_increment primary key, seriesid int, methodid int, dataid int, adddate date, addtime time,taskid int, taskresid int)");



// 4) äàííûå î ìàòðèöå:
// - íîìåð ìàòðèöû
// - èìÿ
// - èìÿ âðåìåííîé òàáëèöû 
// - èìÿ ÁÄ, ãäå âðåìåííàÿ òàáëèöà
// - íà÷àëüíàÿ äàòà
// - êîíå÷íàÿ äàòà
mysql_query("CREATE TABLE matrixdata(id int not null auto_increment primary key,name char (20), temptablename char(20), databasename char (20), begdate DATE, begtime TIME, enddate DATE, endtime TIME)");

// 5) Òàáëèöà ñâÿçè ðÿäîâ ñ ìàòðèöàìè...
// - íîìåð ìàòðèöû (ñâÿçü ñ òàáëèöåé ìàòðèöû)
// - íîìåð ðÿäà (ñâÿçü ñ òàáëèöåé îïåí-êëîóç)
// - íîìåð ðÿäà â ìàòðèöå (öåëîå ÷èñëî)
mysql_query("CREATE TABLE matrixseries(id int not null auto_increment primary key, matrixnum int, seriesnum int, position int,c int, firstdate DATE, firsttime TIME, lastdate DATE, lasttime TIME)");

mysql_query("create table matrixqueries(id int not null auto_increment primary key, querytext varchar(2000), querystate char(10));");

// 6) è äðóãèå - âðåìåííûå òàáëèöû
// - äàòà
// - ðÿä1 (êëîóç èç ðÿäà1)
// - ðÿä2 (êëîóç èç ðÿäà2)...
// - è ò.ä.

mysql_query("CREATE TABLE resultseries(id int not null auto_increment primary key,lastdate DATE, lasttime TIME, name char(20), type int)");

mysql_query("CREATE TABLE resultseriesdata(id int not null auto_increment primary key,resultid int, position int, c1 double,c2 double,c3 double)");


?>
