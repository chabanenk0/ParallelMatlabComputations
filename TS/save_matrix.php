<?php
$basesdir=getcwd();
include "../ParalelMatlabServer2/settings.php";
//define("DBName","matrix");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
set_time_limit(6000); 
//mysql_query("USE matrix;");
mysql_select_db(DBName2);
$mnum=$_GET['mnum'];//16; Matrix number (from matrixseries table)

// формирование массива имен заголовков...
$r=mysql_query("select * from matrixdata where id=$mnum;");
$f=mysql_fetch_array($r);
$matrixname=$f['name'];
$mtablename=$f['temptablename'];
$mtablename='tmp'; //!!! to be corrected!!!
$r=mysql_query("SELECT matrixseries.matrixnum as matrixnum, matrixseries.seriesnum as seriesnum, matrixseries.position as position, dataseries.name as name, dataseries.sector as sector, dataseries.color as color FROM matrixseries,dataseries WHERE matrixseries.matrixnum=$mnum and dataseries.id=matrixseries.seriesnum order by position");
$num_series=mysql_num_rows($r);

$basefilename=$matrixname;//'sp_073';// !!!  to be corrected!!!
$nmfilename=$basefilename.'.nm';
$dtfilename=$basefilename.'.dt';
$txtfilename=$basefilename.'.txt';
$demofilename=$basefilename.'_demo2.txt';

// Сохранение нм.файла
$fp=fopen($nmfilename,"w");
$masnames=array();
$masnames2=array();
$mascolors=array();

for ($i=0;$i<$num_series;$i++)
{
 $f=mysql_fetch_array($r);
 $matrixnum=$f['matrixnum'];
 $seriesnum=$f['seriesnum'];
 $seriesname=$f['seriesname'];
 $position=$f['position'];
 $name=$f['name'];
 $sector=$f['sector'];
 $color=$f['color'];
 $masnames[$i]=$name;
 $masnames2[$i]=$name;
 $mascolors[$i]=$color;
 echo "name=$name<br>\t";
 echo "sector=$sector<br>\n";
 fprintf($fp,"%s\t%s\n",$name,$sector);
}
fclose($fp);

// сохранение тхт и дт-файла
echo "SELECT * from $mtablename ORDER BY date ASC;";
$r=mysql_query("SELECT * from $mtablename ORDER BY date ASC;");
$n=mysql_num_rows($r);
echo "\nn=$n<br>\n";

$fp=fopen($txtfilename,"w");
$fp1=fopen($dtfilename,"w");
for ($j=0;$j<$n;$j++)
{
 $f=mysql_fetch_array($r);
 $cnt=0;
 foreach ($f as $i)
  {if ($cnt==0)
    fprintf($fp1,"%s\n",str_replace("-","",$i));
   else
    if (($cnt>2)&&($cnt % 2==1))
    {fprintf($fp,"%lf\t",$i);
	}
	$cnt++;
	
  }
  fprintf($fp,"\n");
}
fclose($fp);
fclose($fp1);

$fp=fopen($demofilename,"w");
$flag_first=1;
for ($i=0;$i<$num_series;$i++)
{
$sname=$masnames[$i];
$sname=str_replace(".","_",$sname);
$sname=str_replace("-","_",$sname);
 
echo "sname=$sname<br>\n";
echo "SELECT  date, $sname from $mtablename ORDER BY date ASC;";
$r2=mysql_query("SELECT  date, $sname from $mtablename ORDER BY date ASC;");

$n2=mysql_num_rows($r2);
if ($flag_first)
   {fprintf($fp,"%d %d\n",$num_series,$n2);
    $flag_first=0;
   }

 for ($k=0;$k<$n2;$k++)
 {
  $f2=mysql_fetch_array($r2);
  $val=$f2[1];// date is first, the second is price value
  fprintf($fp,"%lf\t",$val);
 }
  fprintf($fp,"\n");
}
for ($j=0;$j<$num_series;$j++)
{
 fprintf($fp,"%s\n",$masnames[$j]);
}
for ($j=0;$j<$num_series;$j++)
{
 fprintf($fp,"%d\n",$mascolors[$j]);
}
fclose($fp);
?>