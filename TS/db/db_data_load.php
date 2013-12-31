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

mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'Yahoo finance',1,1,'http://ichart.finance.yahoo.com/table.csv?s=@NAME@&a=@DayBeg@&b=@MonthBeg@&c=@YearBeg@&d=@DayEnd@&e=@MonthEnd@&f=@YearEnd@&g=d&ignore=.csv','(date, open,high,low,close,volume,adjclose)')");
//wget  --proxy=on  -O LLTC.txt "http://ichart.yahoo.com/table.csv?s=LLTC&a=00&b=1&c=1900&d=19&e=6&f=2099&g=d&ignore=.csv"
mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX days',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=1440&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','(name, discr, date, time, open,low,high,close,volume)')");
mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX Hours',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=60&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','(name, discr, date, time, open,low,high,close,volume)')");
mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX minutes',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=1&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','(name, discr, date, time, open,low,high,close,volume)')");
mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX 5 minutes',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=5&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','(name, discr, date, time, open,low,high,close,volume)')");
mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX 15 minutes',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=15&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','(name, discr, date, time, open,low,high,close,volume)')");
mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX 30 minutes',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=30&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','(name, discr, date, time, open,low,high,close,volume)')");


mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization,seriesname,upddate,updtime ) values( 'UX',1,1,2,1,1440,'UX_D','2009-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX-C',1,1,2,1,1440,'UXC_D','2009-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX',1,1,3,1,60,'UX_H','2009-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX-C',1,1,3,1,60,'UXC_H','2009-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX',1,1,4,1,1,'UX_M','2013-01-07','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX-C',1,1,4,1,1,'UXC_M','2013-01-07','00:00')");

mysql_query("insert into seriesgroups(name , description) values( 'UX','UX for forecasting')");
mysql_query("insert into seriesgroups(name , description) values( 'UX_H','UX hour for forecasting')");
mysql_query("insert into seriesgroups(name , description) values( 'UX_M','UX Minutes for forecasting')");


mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 1,1)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 2,1)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 3,2)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 4,2)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 5,3)");
mysql_query("insert into seriesgroupsconn(seriesid, seriesgroupid ) values( 6,3)");


// 20130320 ;  global   type_raspr;  type_raspr = 5 ; global   limitdt ; limitdt = 5 ; global   limitdt _ min ; limitdt _ min = 0 ;  '  )  ;
//  global limitdt;limitdt=5;global limitdt_min;limitdt_min=0;
// äîáàâëåíèå äòìèí, äòìàêñ...
$commandnew="MarkovChainsUsredn('#infile.txt',[],500,50,'',[],1,'global type_prirash;type_prirash=1;global type_raspr;type_raspr=5;global limitdt;limitdt=5;global limitdt_min;limitdt_min=0;');";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(1,12,0,'$commandnew')");
$commandnew="MarkovChainsUsredn('#infile.txt',[],2000,250,'',[],1,'global type_prirash;type_prirash=1;global type_raspr;type_raspr=5;global limitdt;limitdt=5;global limitdt_min;limitdt_min=0;');";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(2,12,0,'$commandnew')");
// 20130320 Äîáàâèë íå 500, à 1500 ìèíóò. 
$commandnew="global limitdt;limitdt=260;global limitdt_min;limitdt_min=3;global type_prirash;type_prirash=1;MarkovChainsCmd('#infile.txt',1500,'#infile_MarkovPrognoz.txt');";
$commandnew=rawurlencode($commandnew);

mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(3,6,1,'$commandnew')");

$commandnew="sintrend_prognoz('#infile.txt','#infile_SintrendPrognoz.txt', 4, 0, 1);";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(2,8,1,'$commandnew')");

$commandnew="autoregr_prediction_cmd('#infile.txt','#infile_Autoregr.txt',100, 10);";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(2,9,1,'$commandnew')");

$commandnew="neural_predict_ret('#infile.txt',250,4,10,3);";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(2,7,1,'$commandnew')");

$commandnew="gritzuk_prediction_cmd('#infile.txt','#infile_GritzukForecast.txt',1000,5,0);";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(2,11,1,'$commandnew')");

$commandnew="gmdhdemo_autoregr('#infile.txt','#infile_autoregr.txt'1,1,10,250);";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values(2,13,1,'$commandnew')");
