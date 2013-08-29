<?

include "settings.php";
echo "Creating matrix\n";
$basesdir=getcwd();
echo "cwd=$basesdir<br>\n";
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("USE matrix;");
set_time_limit(6000); 
$sgroupid=$_REQUEST['g']; // ид группы рядов, для которых требуется посчитать прогноз.
//if (isempty($sgroupid)) 
//$sgroupid=1;
echo "sgroupid=$sgroupid<p>\n";
$r_=mysql_query("select * from seriesgroupsconn where seriesgroupid=$sgroupid");
//$basesdir="F:\!diskd\work_matlab\Matrix_get\"
$num_res=mysql_num_rows($r_);
echo "<p> Working with $num_res series...<p>\n";
for($j=0; $j<$num_res; $j++)
{ 
 $f_=mysql_fetch_array($r_);
 $seriesid=$f_[seriesid];
 $r=mysql_query("select * from dataseries where id=$seriesid");
 $f=mysql_fetch_array($r);
 $curname=$f[name];
 $curnum=$f[id];
 $curname=substr($curname,0,strlen($curname));
 $cursource=$f[source];
 $r1=mysql_query("select * from datasources where id=$cursource");
 $f1=mysql_fetch_array($r1);
 $url4query=$f1['urltemplate'];
 $importquery=$f1['importquery'];
 $upddate=$f[upddate];
 $updtime=$f[updtime];
 $updday=substr($upddate,8,2);
 $updmonth=substr($upddate,5,2);
 $updyear=substr($upddate,0,4);
 
 $updhour=substr($upddate,1,2);
 $updmin=substr($upddate,4,2);
 $updsec=substr($upddate,7,2);

// mysql_query("insert into datasources(name , number,groupnum, urltemplate,  importquery) values( 'UX',1,1,'mdata.ux.ua/qdata.aspx?code=@NAME@&pb=@DayBeg@@MonthBeg@@YearBeg@&pe=@DayEnd@@MonthEnd@@YearEnd@&p=1440&mk=2&ext=0&sep=2&div=2&df=5&tf=2&ih=1','')");

 $url4query=str_replace('@NAME@',$curname,$url4query);
 $url4query=str_replace('@DayBeg@',$updday,$url4query);
 $url4query=str_replace('@MonthBeg@',$updmonth,$url4query);
 $url4query=str_replace('@YearBeg@',$updyear,$url4query);

 $endDay=$updday;
 $endMonth=$updmonth+1;
 $endYear=$updyear;
 if ($endMonth>12)
  {$endMonth=1;$endYear=$endYear+1;}
 $endDay='01';
 $endMonth='01';
 $endYear='2099';
 
 $url4query=str_replace('@DayEnd@',$endDay,$url4query);
 $url4query=str_replace('@MonthEnd@',$endMonth,$url4query);
 $url4query=str_replace('@YearEnd@',$endYear,$url4query);
 echo "Downloading data from url: $url4query<p>\n";
 $command="wget  --proxy=on  -ehttp_proxy=http://192.168.0.10:3128 -O \"$curname.txt\" \"$url4query\"";
 //$command="wget -O \"$curname.txt\" \"$url4query\"";
// rem set path=%path%;E:\softSince20090830\wget\wget-1.10.2b\
//set path=%path%;I:\20120618_bases\wget-1.10.2b
 system("set path=%path%;D:\\dshared\\FromF\\wget\\wget-1.10.2b\\");
 echo "command=$command<p>\n";
 system($command);
 //$query1="LOAD DATA LOCAL INFILE \"$curname.txt\" INTO TABLE allrecords FIELDS TERMINATED BY ',' (name, discr, date, time, open,low,high,close,volume);";
 // for finance_yahoo_com
 $fpath='/home/localhost/www/TS/';
 $query1="LOAD DATA LOCAL INFILE \"".$fpath.$curname.".txt\" INTO TABLE allrecords FIELDS TERMINATED BY ',' $importquery ;"; // $importquery  содержит список имен полей в скобках обычным текстом. Для разных источников можно поменять...
 echo  $query1."\n";
 $res=mysql_query($query1);
 if (!$res)
 { echo "Ошибка выполнения запроса:";
   echo mysql_error();
   echo "<br>\n";
 }

 $query2="UPDATE allrecords set tickernum=$curnum WHERE tickernum is NULL;";
 echo  $query2."\n";
 mysql_query($query2);
 $query3="DELETE FROM allrecords WHERE date='0000-00-00';";
 echo  $query3."\n";
 mysql_query($query3);
 $query3="DELETE FROM allrecords WHERE date='0000-00-00';";
 echo  $query3."\n";
 mysql_query($query3);
 $query3="drop table if exists tbl001;"; 
 echo  $query3."<p>\n";
 mysql_query($query3);

 $query3="create temporary table tbl001 select id, count(time) as c from allrecords where tickernum=$curnum group by date,time;"; 
 echo  $query3."<p>\n";
 mysql_query($query3);
 $query3="select id from tbl001 where c>1;"; 
 echo  $query3."<p>\n";
 $r4=mysql_query($query3);
 $num_del_rows=mysql_num_rows($r4);
 echo "deleting $num_del_rows duplicates:<p>\n";
 for($i=0;$i<$num_del_rows;$i++)
 {
 $f4=mysql_fetch_array($r4);
 $id4delete=$f4[id];
 $query3="delete from allrecords where id=$id4delete;"; 
 echo  $query3."<p>\n";
 mysql_query($query3);
 }
 $query3="drop table tbl001;"; 
 echo  $query3."<p>\n";
 mysql_query($query3);

 $query3="SELECT max(date) as maxdate,max(time) as maxtime FROM allrecords WHERE tickernum=$curnum"; 
 echo  $query3."<p>\n";
 $r3=mysql_query($query3);
 //echo "r3=$r3<p>\n";
 $f3=mysql_fetch_array($r3);
 //echo $f3;
 $maxdate=$f3['maxdate'];
 $maxtime=$f3['maxtime'];
 $query3="UPDATE dataseries set upddate='$maxdate' WHERE id=$curnum;";
 echo  $query3."<p>\n";
 mysql_query($query3);
 $query3="UPDATE dataseries set updtime='$maxtime' WHERE id=$curnum;";
 echo  $query3."<p>\n";
 mysql_query($query3);
 // нужно определить макс. дату и сохранить как дату апдейта...
 //system("unlink $curname.txt");
} 

?>