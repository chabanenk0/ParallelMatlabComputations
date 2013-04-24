<?php
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
$userar=1; //1 - use rar, 0 - use zip
set_time_limit(600);
$id=$_REQUEST['id'];
$result=$_REQUEST['result'];
echo "Done. Id=$id";
mysql_select_db(DBName);
$r=mysql_query("select * from tasks where state='wait'");

//for($i=0; $i<10; $i++)
//%$f=mysql_fetch_array($r);
echo "$id\n";
//%echo "$f[command]\n";
$endcalcdate=date("y.m.d");
$endcalctime=date("H:i:s");
	$osstr=php_uname();
	$osstr=substr($osstr,0,3);

if ($result==1)
 {
 $r=mysql_query("update tasks set state='done' where id=$id");
 $r=mysql_query("update tasks set endcalcdate='$endcalcdate' where id=$id");
 $r=mysql_query("update tasks set endcalctime='$endcalctime' where id=$id");
 $r=mysql_query("update tasks set done=100 where id=$id");
 $r=mysql_query("select * from taskgroupsconn where taskid=$id");
$n=mysql_num_rows($r);
for ($i=0;$i<$n;$i++)
 {$f=mysql_fetch_array($r);
 $taskgroupid=$f[taskgroupid];
 echo "taskgroupid=$taskgroupid, n=$n<p>\n";
 $r1=mysql_query("select count(tasks.id) as c from taskgroupsconn,tasks where (taskgroupsconn.taskid=tasks.id) and (taskgroupsconn.taskgroupid=$taskgroupid)and(not(tasks.state='done'))");
 $f1=mysql_fetch_array($r1);
 $n1=$f1[c];
 echo "n1=$n1";
 if ($n1<1)// группа досчитана.
  {mysql_query("update taskgroups set state='done' where id=$taskgroupid");
   mysql_query("update taskgroups set done=100 where id=$taskgroupid");
   // 20130115 делаю объединение посчитанных результатов в один файл...
   $r1=mysql_query("select * from dataresult where taskgroupid=$taskgroupid");
   $n1=mysql_num_rows($r1);
   $resultdir="result";
   chdir($uploaddirroot);
   mkdir ($resultdir);
   mkdir ("indexhtmldir");
   $resultdir=$uploaddirroot.$resultdir;
   $indexhtmldir=$uploaddirroot."indexhtmldir";

   for ($j=0;$j<$n1;$j++)
    {$f1=mysql_fetch_array($r1);
	 $num=$f1[id];
	 $location=dataresunpack($num,$uploaddirroot);
	 // перекопировать все файлы из каталога $location в каталог главного архива
	 $command="cp $location/*.* $resultdir";// linux
	 //$command="copy /Y $location/*.* $resultdir/*.*";//win
	 echo "command= $command<p>\n";
	 system($command);
	 system("cat nul >> $indexhtmldir/index.html");
	 // дополнить индекс.хтмл
	 if (strcmp($osstr,"Win")==0) // первые 3 буквы имени ОС Win - значит винда...
	 $command="copy \"$indexhtmldir/index.html\"+\"$location/index.html\" \"$indexhtmldir/index.html\"";//win
	 else
	 $command="cat $resultdir/index.html >> $indexhtmldir/index.html";//win
	 //$command="copy $resultdir/index.html+$location/index.html+ $resultdir/index.html";//win
	 
	 echo "command= $command<p>\n";
	 system($command);
	 // очистить папку $location
	 $command="unlink $location/*.*";
	 echo "command= $command<p>\n";
	 //system($command);
	 //rmdir($location);
	}
	// запаковать результирующую папку
	// из матлаба:system(['"' path_to_rar 'rar" a ' filename ' ' addfiles ]);
	$command="cp $indexhtmldir/index.html $resultdir/index.html";// linux
	echo "command= $command<p>\n";
	system($command);
	$folder=$location;
	// убрал, потому что путь должен присутствовать
	//$ii=strlen($folder);
	//$flag=0;
	//while ($ii>0)
	//{
	//	if ($folder[$ii]=='/')
	//	{	$flag=1; break;
	//	}
	//$ii=$ii-1;
	//}
	$ii=-1;// чтобы при вырезании $ii+1 равнялся нулю
	$flag=1;

	$jj=strripos($folder,"_computed_");// поиск последнего вхождения
	if ($jj>0)
 	$flagj=1;
	else $flagj=0;
	$realfolder=$folder;
	$dataname=$folder;
	if ($flag)
	{ $realfolder=substr($folder,0,$ii+1);
	if ($flagj)
	{$dataname=substr($folder,$ii+1,($jj-$ii-1));}
	else 
	{$dataname=substr($folder,$ii+1,strlen($folder));}
	}
	if ($userar)
	$fullname=$dataname."_computed_tgid_".$taskgroupid.".rar";
	else
	$fullname=$dataname."_computed_tgid_".$taskgroupid.".zip";
	$fullname2arh=$fullname;
	$resultdir2arh=$resultdir."/*.*";
	if (strcmp($osstr,"Win")==0) // первые 3 буквы имени ОС Win - значит винда...
	{
	$fullname2arh=str_replace('/','\\',$fullname2arh);
	$resultdir2arh=str_replace('/','\\',$resultdir2arh);
    }

	if ($userar)
	$command="rar a -ep $fullname2arh $resultdir2arh";
	else
	{chdir($resultdir);
	echo "command = cd $resultdir <p>\n";
	$command="zip -D $fullname2arh *.*";
	}
	echo "command= $command<p>\n";
	system($command);
	$r2=mysql_query("select max(id) as maxid from datafolder");
	if ($r2)
	{
	$f2=mysql_fetch_array($r2);
	if ($f2) $num_res=$f2['maxid'];
	else $num_res=0;
	}
	$num_res=$num_res+1;
	$r2=mysql_query("select adduserid from tasks where id=$id");
	$f2=mysql_fetch_array($r2);
	$userid=$f2[adduserid];
	$fullname4db=substr($fullname,strlen($uploaddirroot),strlen($fullname));
	echo "insert into datafolder(id, descr, folder, userid) values($num_res, 'NoDescr', '$fullname4db', $userid)";
	mysql_query("insert into datafolder(id, descr, folder, userid) values($num_res, 'NoDescr', '$fullname4db', $userid)");
	$r2=mysql_query("select max(id) as maxid from dataresult");
	if ($r2)
	{
	$f2=mysql_fetch_array($r2);
	if ($f2) $num_res2=$f2['maxid'];
	else $num_res2=0;
	}
	$num_res2=$num_res2+1;
	mysql_query("insert into dataresult(id, dataid, workerid, userid,folder,taskgroupid) values($num_res2, 0,0, $userid,'$fullname4db', $taskgroupid)");
	mysql_query("update taskgroups set dataresultid=$num_res2 where id=$taskgroupid");
   chdir($uploaddirroot);
   if (strcmp($osstr,"Win")==0) // первые 3 буквы имени ОС Win - значит винда...
   {
   system("del /q result\*.*");
   system("del /q indexhtmldir\*.*");
   }else
   {
   system("unlink result/*.*");
   system("unlink indexhtmldir/*.*");
   }   
   rmdir ("result");
   rmdir ("indexhtmldir");
	
	//$r1=mysql_query("select tasks.id as id from taskgroupsconn,tasks where (taskgroupsconn.taskid=tasks.id) and (taskgroupsconn.taskgroupid=$taskgroupid)");
   $r1=mysql_query("select id from tasks where taskgroupdata=$taskgroupid");
   $n2=mysql_num_rows($r1);
   for ($j3=0;$j3<$n2;$j3++)
    {$f1=mysql_fetch_array($r1);
	 $taskid=$f1[id];
	 mysql_query("update tasks set state='wait' where id=$taskid");
    }

}
}
} else
{ $r=mysql_query("update tasks set state='err ' where id=$id");
 $r=mysql_query("update tasks set done=0 where id=$id");}
 
?>