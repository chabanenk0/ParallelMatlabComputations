<?php
if (1) //local
{
define("DBName","TS"); //Назва бази даних часових рядів
define("DBName2","prognoz2"); // назва бази даних задач прогнозів
define("HostName","localhost"); // сервер, де знаходиться БД MySQL
define("UserName","root"); // логын користувача
define("Password",""); // пароль користувача
$uploaddirroot = '/home/localhost/www/uploads/';// папка для завантаження результатів
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