<?php
include "../settings.php";
include "db_data_load_matr_fun.php";
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
$src=1;
db_data_load_matr('sp_matr', 'sp matrix','/home/localhost/www/TS/db/sp.txt',$src);
db_data_load_matr('dax_matr', 'dax matrix','/home/localhost/www/TS/db/DAX.txt',$src);
db_data_load_matr('FTSE_matr', 'dax matrix','/home/localhost/www/TS/db/FTSE.txt',$src);
$src=2;
db_data_load_matr('ux_matr', 'UX matrix','/home/localhost/www/TS/db/ux.txt',$src);
?>