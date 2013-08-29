<?
$basesdir=getcwd();
define("DBName","matrix");
define("HostName","localhost");
define("UserName","root");
define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}

mysql_query("USE matrix;");
//$r=mysql_query("select * from allnames");
$r=mysql_query("select ticker ,count(ticker) as c from allrecords group by ticker order by c desc;");
//$basesdir="F:\!diskd\work_matlab\Matrix_get\"
$num_res=mysql_num_rows($r);

$namearray=array();
for($i=0; $i<$num_res; $i++)
{ $f=mysql_fetch_array($r);
// $curname=$f[name];
 $curname=$f[ticker];
 $curname=substr($curname,0,strlen($curname));
 // array_push($namearray,$curname);
 $namearray[$i]=$curname;
} 
//$namearray=array("A","AA","AAPL","BAC","C");
//$num_res=40;
//CREATE TABLE tmp
//SELECT t_A.date, 
//t_A.close as A, 
//t_AA.close as AA
//FROM allrecords as t_A left join allrecords as t_AA on t_A.date=t_AA.date
//WHERE  t_A.ticker='A' and 
// t_AA.ticker='AA';
$query= "DROP TABLE IF EXISTS tmp;";
$r=mysql_query($query);
$query= "DROP TABLE IF EXISTS tmp1;";
$r=mysql_query($query);

$query= "CREATE TABLE tmp SELECT ";
$query=$query."t_".$namearray[0].".date, t_".$namearray[0].".close as ".$namearray[0];
$query=$query.",t_".$namearray[1].".close as ".$namearray[1]."\n";
$query=$query." FROM allrecords AS t_".$namearray[0]."\n";
$query=$query." LEFT JOIN allrecords AS t_".$namearray[1];
$query=$query." ON t_".$namearray[0].".date=t_".$namearray[1].".date \n";
$query=$query." WHERE t_".$namearray[0].".ticker='".$namearray[0]."' AND ";
$query=$query." t_".$namearray[1].".ticker='".$namearray[1]."' ;";

echo $query;
//$r=mysql_query($query);

for($i=2; $i<$num_res; $i++)
 {$query="CREATE TABLE tmp1\n SELECT tmp.*, ";
  $query=$query."t_".$namearray[$i].".close AS ".$namearray[$i]."\n";
  $query=$query."FROM tmp left join allrecords as t_".$namearray[$i]." on tmp.date=t_".$namearray[$i].".date\n";
  $query=$query."WHERE  t_".$namearray[$i].".ticker='".$namearray[$i]."' ;";
  echo "\n$query \n";
  //$r=mysql_query($query);
//  echo "\n$namearray[$i] done\n";
  $query="DROP TABLE IF EXISTS tmp;";
  echo "$query \n";
  //$r=mysql_query($query);
  $query="ALTER TABLE tmp1 RENAME TO tmp;";
  echo "$query \n";
  //$r=mysql_query($query);
 }
?>