<?php
include "head_all.php";
?>
<title>������ � ������������ �������� </title>
</head>
<body>
<h1> ������ �  ������������ ��������</h1>
<?php
include "header.php";
?>

<?php
include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
//echo DBName;
if(!mysql_connect(HostName,UserName,Password))
{ echo "�� ���� ����������� � �����".DBName."!<br>";
echo mysql_error();
exit;
}

mysql_select_db(DBName);

if (array_key_exists('firstpos',$_REQUEST))
$firstpos=$_REQUEST['firstpos'];
else
$firstpos=0;
if (array_key_exists('numrecords',$_REQUEST))
$numrecords=$_REQUEST['numrecords'];
else
$numrecords=100;
$r3=mysql_query("select count(id) as cnt from dataresult;");
$f3=mysql_fetch_array($r3);
$num_total=$f3['cnt'];
//echo "f=$firstpos,n=$numrecords";
for ($i=0;$i<$num_total;$i=$i+$numrecords)
 echo "<a href=datareslist.php?firstpos=$i&numrecords=$numrecords>$i</a>\n";

$r=mysql_query("select * from dataresult");
$num_res=mysql_num_rows($r);
echo '<table border=2><tr><td>#</td><td>dataid</td><td>taskid</td><td>workerid</td><td>userid</td><td>folder</td><td>����</td><td>�������</td></tr>';
for($i=0; $i<$num_res; $i++)            
{ $f=mysql_fetch_array($r);
  echo "<tr><td>$f[id]</td><td>$f[dataid]</td><td>$f[taskid]</td><td>$f[workerid]</td><td>$f[userid]</td><td><a href=dataresunpack.php?id=$f[id]>$f[folder]</a></td><td><a href=dataresget.php?id=$f[id]>���������</a></td><td><a href=dataresdelete.php?id=$f[id]>�������</a></td></tr>\n";
}
echo '</table>';
//(id int, platformid int, methodid int, dataid int, filename char(50), command char(200), state char(30), done int, outfilename char(50), IP char(20), adduserid int, calcuserid int, processid int, begcalcdate date, begcalctime time , predictminutes int ,  endcalcdate date,  endcalctime time)
?>

1) <a href=tasklist.php>�������� ������� �������</a><p>
2) <a href=dataadd.php>�������� ����� ����� ������</a><p>

<?php
include "footer.php";
?>
