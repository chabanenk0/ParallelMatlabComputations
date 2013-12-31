<?
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
//mysql_query("USE matrix;");
mysql_select_db(DBName2);
$r=mysql_query("select * from allnames");
//$basesdir="F:\!diskd\work_matlab\Matrix_get\"
$num_res=mysql_num_rows($r);
$namearray=array();
for($i=0; $i<$num_res; $i++)
{ $f=mysql_fetch_array($r);
 $curname=$f[name];
 $curname=substr($curname,0,strlen($curname)-1);
 // array_push($namearray,$curname);
 $namearray[$i]=$curname;
} 
//$namearray=array("A","AA","AAPL","BAC","C");
$num_res=40;
echo "USE matrix;\nSELECT ";
echo "t_".$namearray[0].".date";
for($i=0; $i<$num_res; $i++)
 {echo ", \nt_".$namearray[$i].".close as ".$namearray[$i];
 }
echo "\nFROM allrecords as t_".$namearray[0];
for($i=1; $i<$num_res; $i++)
 {echo " left join allrecords as t_".$namearray[$i]." on t_".$namearray[0].".date=t_".$namearray[$i].".date\n";
 }

echo "WHERE ";
for($i=0; $i<$num_res-1; $i++)
 {echo " t_".$namearray[$i].".ticker='".$namearray[$i]."' and \n";
 }
echo " t_".$namearray[$num_res-1].".ticker='".$namearray[$num_res-1]."';";

//SELECT t_A.date, t_A.close as A,t_AA.close as AA, t_AAPL.close as AAPL
//FROM allrecords as t_A left join allrecords as t_AA on t_A.date=t_AA.date
//left join allrecords as t_AAPL on t_A.date=t_AAPL.date
//where t_A.ticker='A' and t_AA.ticker='AA' and t_AAPL.ticker='AAPL';


?>