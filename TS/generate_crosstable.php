<?
$basesdir=getcwd();
include "settings.php";
//define("DBName","matrix");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "�� ���� ����������� � �����".DBName."!<br>";
echo mysql_error();
exit;
}

//mysql_query("USE matrix;");
mysql_select_db(DBName);
$sgid=5;
//$r=mysql_query("select * from allnames");

// !!!�������� � �������� �������!!!!
// mysql_query("DROP TABLE IF EXISTS matrixseries;");
// mysql_query("CREATE TABLE matrixseries(matrixnum int, seriesnum int, position int,c int, firstdate DATE, firsttime TIME, lastdate DATE, lasttime TIME)");


//$r=mysql_query("select dataseries.name as ticker, count(allrecords.tickernum) as c, seriesgroupsconn.seriesid as seriesid from dataseries,seriesgroupsconn,allrecords where ((seriesgroupsconn.seriesgroupid=$sgid) and (dataseries.id=allrecords.tickernum) and (allrecords.tickernum=seriesgroupsconn.seriesid)) group by ticker,seriesid order by c desc;");
// 20130110
// select seriesid from seriesgroupsconn where seriesgroupid=16;
// ���������� ���� ������: ����� � �������
mysql_query("drop table if exists tmpnames");
mysql_query("create table tmpnames select dataseries.name as ticker, seriesgroupsconn.seriesid as seriesid from seriesgroupsconn,dataseries where (seriesgroupsconn.seriesgroupid=$sgid) and (dataseries.id=seriesgroupsconn.seriesid);");
// ����� � ������ ������� ����� ��������� ���-�� ������� � �������� ����, � ����� ����� ��� �������
mysql_query("drop table if exists tmpnumbers");
mysql_query("create table tmpnumbers select tickernum, count(tickernum) as c from allrecords group by tickernum order by c desc;");
// ���������� ����� ������� - �� ������� ���-�� ���� ������� � �������, ��� ��������� ������.
$r=mysql_query("select tmpnames.ticker as ticker,tmpnames.seriesid as seriesid, tmpnumbers.c as c from tmpnames,tmpnumbers where tmpnames.seriesid=tmpnumbers.tickernum order by c desc;");


 if (!$r)
 { echo "\n<br>������ ���������� �������:";
   echo mysql_error();
   echo "<br>\n";
   echo getcwd();
 }

//$basesdir="F:\!diskd\work_matlab\Matrix_get\"
$num_res=mysql_num_rows($r);

$namearray=array();
$idarray=array();
mysql_query("insert into matrixdata (name, temptablename) values ('sp','sp_matrix')");
// � ���������� ����� ����� �� ����� ������ ��� �����������.
$r1=mysql_query("select max(id) as maxid from matrixdata");
$f1=mysql_fetch_array($r1);

if (array_key_exists('maxid',$f1))
$matrnum=$f1[maxid];
else $matrnum=1;
$matrnumold=$matrnum;
for($i=1; $i<=$num_res; $i++)
{ $f=mysql_fetch_array($r);
// $curname=$f[name];
 $curname=$f[ticker];
 $num=$f[seriesid];
 $curname=substr($curname,0,strlen($curname));
 $curname=str_replace(".","_",$curname);
 $curname=str_replace("-","_",$curname);
 $c=$f[c];
 // array_push($namearray,$curname);
 $namearray[$i-1]=$curname;
 $idarray[$i-1]=$num;
 mysql_query("insert into matrixseries(matrixnum, seriesnum, position,c) values ($matrnum,$num,$i,$c)");
} 

//�� �� ����� ��������� � ��������
//mysql_query("drop table if exists matrixqueries;");
//mysql_query("create table matrixqueries(id int not null auto_increment primary key, querytext varchar(2000), querystate char(10));");

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
$query=$query." WHERE t_".$namearray[0].".tickernum='".$idarray[0]."' AND ";
$query=$query." t_".$namearray[1].".tickernum='".$idarray[1]."' ;";

echo $query;
$query2=rawurlencode($query);
mysql_query("insert into matrixqueries(querytext , querystate) values ('$query2','wait');");
//$r=mysql_query($query);

for($i=2; $i<$num_res; $i++)
 {$query="CREATE TABLE tmp1\n SELECT tmp.*, ";
  $query=$query."t_".$namearray[$i].".close AS ".$namearray[$i]."\n";
  $query=$query."FROM tmp left join allrecords as t_".$namearray[$i]." on tmp.date=t_".$namearray[$i].".date\n";
  $query=$query."WHERE  t_".$namearray[$i].".tickernum='".$idarray[$i]."' ;";
  echo "\n$query \n";
  $query2=rawurlencode($query);
  mysql_query("insert into matrixqueries(querytext , querystate) values ('$query2','wait');");

  //  $r=mysql_query($query);
  //  echo "\n$namearray[$i] done\n";
  $query="DROP TABLE IF EXISTS tmp;";
  echo "$query \n";
  $query2=rawurlencode($query);
  mysql_query("insert into matrixqueries(querytext , querystate) values ('$query2','wait');");

  //  $r=mysql_query($query);
  $query="ALTER TABLE tmp1 RENAME TO tmp;";
  echo "$query \n";
  $query2=rawurlencode($query);
  mysql_query("insert into matrixqueries(querytext , querystate) values ('$query2','wait');");
 if (($i==27)||($i==46))
  {
    $query="CREATE TABLE tmp$i SELECT * FROM tmp;";
  echo "$query \n";
  $query2=rawurlencode($query);
  mysql_query("insert into matrixqueries(querytext , querystate) values ('$query2','wait');");
  // ����� ����� �������� ���������� ����� � ������� �������� ������ ��...
  if (1)
   { mysql_query("insert into matrixdata (name, temptablename) values ('sp$i','sp$i_matrix')");
   $r2=mysql_query("select max(id) as maxid from matrixdata");
   $f2=mysql_fetch_array($r2);
   if (array_key_exists('maxid',$f2))
   $matrnum2=$f2[maxid];
   else $matrnum2=1;
   mysql_query("insert into matrixseries(matrixnum, seriesnum, position)  select $matrnum2 as matrixnum, seriesnum, position from matrixseries where matrixnum=$matrnumold)");
   }
  }
 
  //  $r=mysql_query($query);
 }
?>