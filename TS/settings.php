<?php
if (1) //local
{
define("DBName","TS");
define("DBName2","prognoz2");
define("HostName","localhost");
define("UserName","root");
define("Password","");
$uploaddirroot = '/home/localhost/www/uploads/';
}
else
{
define("DBName","chabane_TS");
define("DBName2","chabane_prognoz2");
define("HostName","localhost");
define("UserName","chabane_site2");
define("Password","prognoz");
$uploaddirroot = '/hsphere/local/home/chabanenko/prognoz.ck.ua/uploads/';
}
//echo DBName;

?>