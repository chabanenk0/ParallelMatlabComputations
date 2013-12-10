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
//mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) 
//                          values( 'UX-C',  1,       1,       2,        1, 1440,           'UXC_D',   '2009-01-01','00:00')");
//$listfilename='~/prognoz.ck.ua/TS/db/yahoo_get_list_all20111101.txt';
//$listfilename='../../../home/dmitry/prognoz_ts_db/yahoo_get_list_all20111101.txt';
//$listfilename='../../../tmp/yahoo_get_list_all20111101.txt';
//$res=mysql_query("LOAD DATA INFILE '$listfilename' INTO TABLE dataseries(name);");
// if (!$res)
// { echo "\n<br>Error loading dataseries:";
//   echo mysql_error();
//   echo "<br>\n";
//   echo getcwd();
// }



mysql_query("insert into seriesgroups(name , description) values( 'yahoo','VN list from yahoo get')");

$r=mysql_query("select max(id) as maxid from seriesgroups");
$f=mysql_fetch_array($r);
if (array_key_exists('maxid',$f))
$gr=$f['maxid'];
else $gr=1;

// $gr=4;

//mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, seriesname,upddate,updtime) values( 'UX-C',
//                                            1,       $gr,       1,      1, 1440,                   '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^AEX1', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^AORD', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^BFX', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^BSESN', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^BVSP', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^DJBHD', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^DJI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^FCHI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^FTSE', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^GDAXI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^GSPC', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^GSPTSE', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^HSI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^IBEX', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^ISEQ', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^IXIC', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^JKSE', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^KS11', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^MERV', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^MXX', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^N225', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^NYA', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^NZ50', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^STI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^TA100', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^XNG', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^XOI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '000001.SS', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '0939.HK', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '1288.HK', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '1398.HK', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '3988.HK', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'AAL.L', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'ACA.PA', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'AIG', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'BAC', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'BARC.L', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'BCS', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'BK', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'BNP.PA', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'C', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'CBK.DE', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'CMA.CA', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'CS', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'DB', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'DBK.F', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'DPB.DE', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'EGX30.CA', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'ESI500000000.MA', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'FTSEMIB.MI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'GD.AT', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'GLE.PA', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'GS', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'HBC', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'HBOS', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'HSBA.L', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'ING', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'ITUB', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'JPM', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'LLOY.L', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'MFG', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'MS', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'MTG', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'MTU', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'PSI20.NX', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'RBS.L', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'SCGLF.PK', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'SCGLY.PK', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'STD', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'STI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'UBS', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'UCGR.MI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'WFC', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( 'OMXC20.co', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");
mysql_query("insert into dataseries(name , number,groupnum, source,  type,discretization, upddate,updtime) values( '^SSMI', 1, $gr, 1, 1, 1440, '1950-01-01','00:00')");


$res=mysql_query("update dataseries set seriesname=name where seriesname is null;");
 if (!$res)
 { echo "\n<br>Error loading dataseries upd dataseries:";
   echo mysql_error();
   echo "<br>\n";
   echo getcwd();

 }

$res=mysql_query("insert into seriesgroupsconn (seriesid, seriesgroupid) (select id as seriesid, $gr as seriesgroupid from dataseries where groupnum=$gr);");
 if (!$res)
 { echo "\n<br>Error loading dataseries insert into:";
   echo mysql_error();
   echo "<br>\n";
   echo getcwd();
 }


$commandnew="global limitdt;limitdt=260;global limitdt_min;limitdt_min=3;global type_prirash;type_prirash=1;MarkovChainsCmd('#infile.txt',2000,'#infile_MarkovPrognoz.txt');";
$commandnew=rawurlencode($commandnew);
mysql_query("insert into groupsmethods(groupid, methodid,type_precommand,precommands) values($gr,6,1,'$commandnew')");

// нужно добавить (date, open, high, low, close, volume, adjclose) как записьв importquery поле таблицы datasource

