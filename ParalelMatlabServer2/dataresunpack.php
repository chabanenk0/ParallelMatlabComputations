<?
include "settings.php";
include "dataresunpack_fun.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
$num=$_REQUEST['id'];
$location=dataresunpack($num,$uploaddirroot);
header("Location: $location");

?>
