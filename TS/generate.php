<?

include "../ParalelMatlabServer2/settings.php";
echo "Creating matrix\n";
$basesdir=getcwd();
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName2);
$userar=0;
if (array_key_exists('gmid',$_REQUEST))
$groupmethodid=$_REQUEST['gmid'];
else
$groupmethodid=2;

echo "groupmethodid=$groupmethodid<p>";
$osstr=php_uname();
$osstr=substr($osstr,0,3);
echo "osstr=$osstr <p>\n";

//mysql_query("USE matrix;");
$path='tmpfolder';
//$precommands='global limitdt;limitdt=5;global limitdt_min;limitdt_min=0;global type_prirash;type_prirash=1;';

$r_1=mysql_query("select * from groupsmethods where id=$groupmethodid;");
$num_res_meth=mysql_num_rows($r_1);
for($j=0; $j<$num_res_meth; $j++)
{ 
mkdir($path);
$f_1=mysql_fetch_array($r_1);
 
$sgroupid=$f_1[groupid]; // ид группы рядов, для которых требуется посчитать прогноз.
$smethodid=$f_1[methodid];
$precommands=rawurldecode($f_1[precommands]);
$type_precommand=$f_1[type_precommand];

//mysql_select_db("matlab2");
mysql_select_db(DBName);
$r_2=mysql_query("select * from methods where id=$smethodid;");
$f_2=mysql_fetch_array($r_2);
$command_template=$f_2[command];
mysql_select_db(DBName2);
$r_2=mysql_query("select * from seriesgroupsconn where seriesgroupid=$sgroupid;");
$num_res=mysql_num_rows($r_2);
$names_array=array();
for($i=0; $i<$num_res; $i++)
{
 $f_2=mysql_fetch_array($r_2);
 $seriesid=$f_2[seriesid];
 $r=mysql_query("select * from dataseries where id=$seriesid");
 //$basesdir="F:\!diskd\work_matlab\Matrix_get\"
 $f=mysql_fetch_array($r);
 $curname=$f[name];
 $curnum=$f[id];
 $curname=substr($curname,0,strlen($curname));
 echo "curnum=$curnum <p>\n";
 $query3="SELECT close FROM allrecords WHERE tickernum=$curnum order by date,time asc;"; 
 $r3=mysql_query($query3);
 $type=$f[type];
 $discr=$f[discretization];
 $curname2=$curname."_".$type."_".$discr."_close.txt";
 array_push($names_array,$curname2);
 echo "Creating file $curname2 <p>\n";
 if (strcmp($osstr,"Win")==0) 
 $fp=fopen($path.'\\'.$curname2,"w");
 else 
 $fp=fopen($path.'/'.$curname2,"w");
 $num_res3=mysql_num_rows($r3);
 echo "num_res3=$num_res3";
 for ($jj=0;$jj<$num_res3;$jj++)
 {$f3=mysql_fetch_array($r3);
 $curclose=$f3[close];
 fprintf($fp,"%lf\n",$curclose);
 }
 fclose($fp);
 // нужно определить макс. дату и сохранить как дату апдейта...
} 
$curdate4filename=date("_dmY_Gis");
if ($userar)
$archpath=$uploaddirroot."Admin/data/packet".$curdate4filename.".rar";
else
$archpath=$uploaddirroot."Admin/data/packet".$curdate4filename.".zip";

if (strcmp($osstr,"Win")==0) 
{
$archpath2arh=str_replace('/','\\',$archpath);
}
else
$archpath2arh=$archpath;
$path2arh=$path;
if ($userar)
$command="rar a -ep $archpath2arh $path2arh/*.*";
else
{chdir($path);
echo "command = cd $path <p>\n";
$command="zip -D $archpath2arh *.*";
}
echo "command= $command<p>\n";
system($command);
if (!$userar)
chdir("..");
system ("rm -rf tmpfolder");
mysql_select_db(DBName);
$r=mysql_query("select max(id) as maxid from datafolder");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res=$f['maxid'];
   else $num_res=0;
 }
$num_res=$num_res+1;
$userid=1;
$descr="none";

if ($userar)
$folder2="Admin/data/packet".$curdate4filename.".rar";
else
$folder2="Admin/data/packet".$curdate4filename.".zip";
 mysql_query("insert into datafolder(id, descr, folder, userid) values($num_res, '$descr', '$folder2', $userid)");
 echo "query=insert into datafolder(id, descr, folder, userid) values($num_res, '$descr', '$folder2', $userid)";
 $r3=mysql_query($query3);

$userid=1;
$platformid=1;
$dataid=$num_res;// ИД данных, которые добавленны архивом...
$methodid=1;// ИД метода цепей Маркова
$processid=0;

mysql_query("insert into taskgroups(name , description) values( 'NewGroup','group')");
$r3=mysql_query("select max(id) as maxtaskid from taskgroups");
$f3=mysql_fetch_array($r3);
$taskgroupid=$f3['maxtaskid'];

foreach ($names_array as $i) //генерация заданий
{
$filename=$i;
 $r=mysql_query("select max(id) as maxid from tasks");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res2=$f['maxid'];
   else $num_res2=0;
 }
$begcalcdate=date("y.m.d");
$begcalctime=date("H:i:s");

$id=$num_res2;
//while ($tok) // сюда потом можно поставить цикл по всем методам, которыми нужно обработать
{   $id++;
    if (type_precommand==0)
     {$command_template=$precommands;
       $precommands2="";
     }
     else $precommands2=$precommands;
    
    $tok=rawurldecode($command_template);
	//"global limitdt;limitdt=5;global limitdt_min;limitdt_min=0;MarkovChainsCmd('".$i."',2500,'".str_replace(".txt","_MarkovPrognoz.txt",$i)."')";
	$seriesname=str_replace(".txt","",$i);
	$tok=str_replace("#infile",$seriesname,$tok);
	$tok=str_replace("#outfile",$seriesname."_MarkovPrognoz",$tok);
    echo "Word=$tok<br />";
	$predictminutes=60;
    $command=rawurlencode($tok);
	$command=$precommands2.$command;//20130115 добавил прекоманды для настройки.
	$query_text="insert into tasks(id, command,filename,state,done,IP,adduserid,platformid,dataid,methodid,processid,begcalcdate,begcalctime,predictminutes,calcuserid,endcalcdate,  endcalctime,taskgroupid) values($id, '$command','$filename','wait',0,'0.0.0.0',$userid,$platformid,$dataid,$methodid,$processid,'$begcalcdate','$begcalctime',$predictminutes,0,'0000-00-00','00:00:00',$taskgroupid)";
    $res=mysql_query($query_text);
	mysql_query("insert into taskgroupsconn(taskid, taskgroupid) values($id,$taskgroupid)");
    echo "query=$query_text";
    mysql_select_db(DBName2);
    $query_text="insert into added_tasks( seriesid,methodid,dataid,adddate,addtime,taskid) values ($seriesid,$smethodid,$dataid,'$begcalcdate','$begcalctime',$id)";
    $res=mysql_query($query_text);
    mysql_select_db(DBName);
    
}
}
}
?>
