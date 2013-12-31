<?php
include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Ќе могу соединитьс€ с базой".DBName."!<br>";
echo mysql_error();
exit;
}
 if (1)//(isset($_COOKIE['uid']))&&($_COOKIE['uid']==1))
 {
 include "bcrypt.php";
 $uid_cookie=$_COOKIE['uid'];
 $uhash_cookie=$_COOKIE['uhash'];


 $r=mysql_query("select * from users where id=$uid_cookie");
 $f=mysql_fetch_array($r);
 $username=$f['login'];
 $userpass=$f['pass'];
 $bcrypt = new Bcrypt(15);

  $hash = $bcrypt->hash('password');
 $auth = $bcrypt->verify($username.$userpass, $uhash_cookie);
// echo "auth result - $auth";
 if ($f['type']==1)$adminauth=1;else $adminauth=0;
 }
 else 
 {
 $auth=0;
 $uid_cookie=0;
// echo "auth result - $auth";
 header('Location: userlist.php');
 echo "You don't have rights for clearing all the database.<p>\nPlease <a href=login.php>log in</a> as admin";

 exit;
 }


echo "Creating databases";
$r=mysql_query("drop database if exists matlab2");
echo "Deleting database. Result=";
if ($r) 
echo "ok"; 
else 
echo "err";
echo "<p>";
$r=mysql_query("create database ".DBName);
echo "Creating database. Result=";
if ($r) 
echo "ok"; 
else 
echo "err";
echo "<p>";

mysql_select_db(DBName);
echo "Creating tables";
mysql_query("drop table if exists users");
mysql_query("create table users(id int, login char(20), pass char(30), email char(30), name varchar (50), surname varchar (50), loadtool int, loaddata int, type int, rndnum int)");
mysql_query("drop table if exists datafolder");
mysql_query("create table datafolder(id int, userid int, descr varchar(100), folder varchar(100))");
mysql_query("drop table if exists methods");
mysql_query("create table methods(id int, name char (20), descr varchar(100), folder varchar(100),userid int, toolboxid int,  version int,platformid int, command varchar(1000))");
mysql_query("drop table if exists methodsusers");
mysql_query("create table methodsusers(id int, userid int, methodid int)");
mysql_query("drop table if exists datausers");
mysql_query("create table datausers(id int, userid int, dataid int, state char (10))");
mysql_query("drop table if exists processes");
mysql_query("create table processes(id int, regdate date , regtime time ,  userid int,  processid int, platformid int,ip char(20))");
mysql_query("drop table if exists platforms");
mysql_query("create table platforms(id int, name char(30)  ,description varchar(20))");
mysql_query("drop table if exists tasks");
mysql_query("create table tasks(id int, platformid int, methodid int, dataid int, taskgroupid int, filename char(50), command varchar(1500), state char(30), done int, outfilename char(50), IP char(20), adduserid int, calcuserid int, processid int, begcalcdate date, begcalctime time , predictminutes int ,  endcalcdate date,  endcalctime time, taskgroupdata int default 0)");
// 20130114 добавлено поле taskgroupdata int, в котором сохран€етс€ идентификатор группы заданий (таблица), результат рассчета которой будет входными данными дл€ данного задани€, кроме уже включенного
mysql_query("drop table if exists dataresult");
mysql_query("create table dataresult(id int, dataid int, taskid int, workerid int, userid int, folder varchar(100),taskgroupid int)");
// забыл про комменты. Ќужно наверное их добавить и дл€ методов (обсуждени€), и дл€ пользователей (лс), и, возможно, дл€ пакетов данных
//mysql_query("use tasks");
mysql_query("insert into users(id , login , pass , email , name , surname , loadtool , loaddata, type) values(1, 'Admin','Admin','chdn6026@mail.ru','Admin','',1,1,1)");
mysql_query("insert into platforms(id ,name , description) values(1, 'Matlab','MatlabForWindows')");
mysql_query("insert into platforms(id ,name , description) values(2, 'Octave','GNU Octave')");
mysql_query("insert into platforms(id ,name , description) values(3, 'Python','Python')");
mysql_query("insert into platforms(id ,name , description) values(4, 'C++','gcc c++')");
mysql_query("insert into platforms(id ,name , description) values(4, 'Pascal','Free Pascal')");
$commandMarkov=rawurlencode("Cmd('#infile.txt','#infile_MarkovPrognoz.txt');");
mysql_query("insert into methods(id ,name , descr , folder ,userid,platformid,command,toolboxid, version) values(1, 'NoMethod','NoMethods','empty.rar',1,1,'$commandMarkov',1,1)");
mysql_query("insert into methodsusers(id ,userid , methodid) values(1, 1, 1)");
mysql_query("INSERT INTO `methods` (`id`, `name`, `descr`, `folder`, `userid`, `toolboxid`, `version`, `platformid`, `command`) VALUES (2, 'MarkovChains', 'Latest%20version%20of%20Markov%20Chains%20toolbox%0D%0A', 'user/methods/MarkovChains.rar', 2, 2, 1, 1, 'MarkovChainsCmd%28%27%23infile.txt%27%2C5000%2C%27%23infile_MarkovPrognoz.txt%27%29%3B'), (3, 'tir', 'Time%20Irreversibility%20research%20by%20Elena%20Rybchinska', 'Admin/methods/tir.rar', 1, 3, 1, 1, 'tir_command_updown%28%27%27%2C%27%23infile_close.txt%27%2C250%2C%27%23infile_closeCumulativeA.txt%27'), (4, 'Autoregression', 'Autoregression%20forecasting%20method%0D%0A', 'Admin/methods/Autoregr.rar', 1, 4, 1, 1, 'autoregr_prediction_cmd%28%27%23infile.txt%27%2C%27%23infile_Autoregr.txt%27%2C100%2C%2010%29%3B'),(5, 'ARMA_GARCH', 'ARMA_GARCH%0D%0A', 'Admin/methods/garch.rar', 1, 5, 1, 1, 'garchpred_try%28%27%23infile.txt%27%2C%27%23infile_garch.txt%27%2C20%2C5%29%3B'), (6, 'MarkovChainsFigs', 'MarkovChains%20with%20html%20report%0D%0A', 'Admin/methods/MarkovChains_fig.rar', 1, 6, 1, 1, 'MarkovChainsCmd%28%27%23infile.txt%27%2C5000%2C%27%23infile_MarkovPrognoz.txt%27%29%3B'), (7, 'Neural', 'Neural%20network%20models%0D%0A', 'Admin/methods/neural.rar', 1, 7, 1, 1, 'neural_predict_ret%28%27%23infile.txt%27%2C250%2C4%2C10%2C3%29'), (8, 'Sintrend', 'Discrete%20Fourier%20forecasting%0D%0A', 'Admin/methods/SintrendAbsNew20120330.rar', 1, 8, 1, 1, 'sintrend_prognoz%28%27%23infile.txt%27%2C%27%23infile_SintrendPrognoz.txt%27%2C%204%2C%200%2C%201%29'), (9, 'Autoregression2', 'Aregr%202%0D%0A', 'Admin/methods/Autoregr2.rar', 1, 9, 1, 1, 'autoregr_prediction_cmd%28%27%23infile.txt%27%2C%27%23infile_Autoregr.txt%27%2C100%2C%2010%29%3B'), (10, 'ARMA_GARCH2', 'ARMA_GARCH%20from%20garch%20toolbox', 'Admin/methods/garch2.rar', 1, 10, 1, 1, 'garchpred_try%28%27%23infile.txt%27%2C%27%23infile_garch.txt%27%2C20%2C5%29%3B'), (11, 'Gritzuk', 'Nonlinear%20garmonic%20anallysis%20by%20P.%20Gritzuk%0D%0A', 'Admin/methods/Gritzuk.rar', 1, 11, 1, 1, 'gritzuk_prediction_cmd%28%27%23infile.txt%27%2C%27%23infile_GritzukForecast.txt%27%2C1000%2C5%2C0%29');");
mysql_query("INSERT INTO `methodsusers` (`id`, `userid`, `methodid`) VALUES (2, 1, 3), (3, 1, 4), (4, 1, 5), (5, 1, 6), (6, 1, 7), (7, 1, 8), (8, 1, 9), (9, 1, 10),(10, 1, 11);");
$commandMarkov=rawurlencode("MarkovChainsUsredn('#infile.txt',[],500,250,'',[],1,'global type_prirash;type_prirash=1;global type_raspr;type_raspr=5;');");
$querytext="insert into methods(id, name , descr , folder ,userid,platformid,command,toolboxid, version) values(12, 'MarkovChainsUsredn','MarkovChainsUsredn','Admin/methods/MarkovChainsUsredn.rar',1,1,'$commandMarkov',12,1)";
echo $querytext;
$r=mysql_query($querytext);
if ($r) 
echo " ok"; 
else 
echo " err".mysql_error();
echo " <p>";
mysql_query("insert into methodsusers(id ,userid , methodid) values(11, 1, 12)");

$commandMarkov=rawurlencode("gmdhdemo_autoregr('#infile.txt','#infile_autoregr.txt'1,1,10,250);");
$querytext="insert into methods(id, name , descr , folder ,userid,platformid,command,toolboxid, version) values(13, 'MGUA','MGUA','Admin/methods/MGUA.rar',1,1,'$commandMarkov',13,1)";
echo $querytext;
$r=mysql_query($querytext);
if ($r) 
echo " ok"; 
else 
echo " err".mysql_error();
echo " <p>";
mysql_query("insert into methodsusers(id ,userid , methodid) values(12, 1, 13)");


mysql_query("insert into datafolder(id, userid, descr, folder ) values(1, 1, 'No data', 'empty.rar')");

for($i=0; $i < 1; $i++)
{ $id=$i+1;
 mysql_query("insert into tasks(id, platformid, methodid, dataid, filename, command,state,done,IP,outfilename, adduserid,taskgroupid) values($id, 1, 1, 1, 'dj.txt','MarkovChainsCmd','wait',0,'0.0.0.0','dj_mp.txt',1,1)");
}
// таблица групп р€дов (чтобы задавать задание прогнозировать дл€ всей группы.
mysql_query("drop table if exists taskgroups");
mysql_query("create table taskgroups(id int not null auto_increment primary key, name char(20), description varchar(200),state char(30), done int,dataresultid int)");
mysql_query("insert into taskgroups(name , description) values( 'group1','testgroup')");

// таблица св€зи таблицы р€дов dataseries и таблицы групп seriesgroups
mysql_query("drop table if exists taskgroupsconn");
mysql_query("create table taskgroupsconn(id int not null auto_increment primary key, taskid int, taskgroupid int)");
mysql_query("insert into taskgroupsconn(taskid, taskgroupid) values(1,1)");
mysql_query("insert into taskgroupsconn(taskid, taskgroupid) values(2,1)");

$r=mysql_query("select * from tasks");

for($i=0; $i<1; $i++)
{ $f=mysql_fetch_array($r);
echo "$f[id]. $f[command]<br>\n";
}

?>
1) <a href=tasklist.php>ѕросмотр текущих заданий</a><p>
2) <a href=add.php>ƒобавление задани€</a><p>
3) <a href=clear.php>ќчистка списка заданий</a><p>
