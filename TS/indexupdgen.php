<?php
//include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
//echo DBName;
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName2);
$r=mysql_query("select * from seriesgroups;");
$num_res=mysql_num_rows($r);
echo "<p>Обновление данных:</p>\n<ul>\n";
for($i=0; $i<$num_res; $i++)            
{ $f=mysql_fetch_array($r);
echo "<li>$f[id]. <a href='update.php?g=$f[id]'>$f[name]</a></li>\n";

}
echo "</ul>\n";
$r=mysql_query("select * from groupsmethods;");
$num_res=mysql_num_rows($r);
echo "<p>Генерация заданий:</p>\n<ul>\n";
for($i=0; $i<$num_res; $i++)            
{ $f=mysql_fetch_array($r);
echo "<li><a href='generate.php?gmid=$f[id]'>GroupMethod$f[id]</a></li>\n";

}
echo "</ul>\n";

?>