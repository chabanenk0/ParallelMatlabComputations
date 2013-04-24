<?php
$conn = odbc_connect("DRIVER={Microsoft Access Driver (*.mdb)};Dbq=C:\Users\Андрей\ux_trades_db\db2.mdb",   "username", "password");
//$neededInstr="UX-3.13 [FOUX: Фьючерсы]"
//$sql = "SELECT Date, Время as Time, first(Цена) as open, min(Цена) as Low, max(Цена) as High, last(Цена) as Close, sum(Объем) as Volume FROM ВсеСделки20120513 where Бумага='UX-3.13 [FOUX: Фьючерсы]' group by Date, Время order by Date asc, Время asc";
//$sql = "SELECT  Бумага, (Hour(Время)+Minute(Время)*60) as Time FROM ВсеСделки20120513 where Бумага='UX-3.13 [FOUX: Фьючерсы]'";
$sql = "SELECT Время, Date, (60*Hour(Время)+Minute(Время)+24*60*Day(Date) +31*24*60*Month(Date)+365*24*60*Year(Date)) as CurTime , Цена, Колво, Операция  FROM ВсеСделки20120513 where Бумага='UX-3.13 [FOUX: Фьючерсы]'";
echo $sql;
$rs = odbc_exec($conn,$sql);
while (odbc_fetch_row($rs))
{
echo odbc_result($rs,"CurTime");
echo " ";
//echo odbc_result($rs,"Время");
//echo " ";
//echo odbc_result($rs,"Date");
//echo " ";
echo odbc_result($rs,"Цена");

echo "<p>\n";
}
//echo "\nTest\n” . odbc_result($rs,"test") . "\n";
?>
