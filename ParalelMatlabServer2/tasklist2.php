<?php
include "head_all.php";
?>
<title>������ � ���������</title>
</head>
<body>
<h1> ������ � ���������</h1>
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
$r3=mysql_query("select count(id) as cnt from tasks;");
$f3=mysql_fetch_array($r3);
$num_total=$f3['cnt'];
//echo "f=$firstpos,n=$numrecords";
for ($i=0;$i<$num_total;$i=$i+$numrecords)
 echo "<a href=tasklist2.php?firstpos=$i&numrecords=$numrecords>$i</a>\n";
 
$r=mysql_query("select * from tasks order by id limit $firstpos,$numrecords;");
$num_res=mysql_num_rows($r);
//echo "numres=$num_res";
echo '<table border=2><tr><td>#</td><td>���������</td><td>�����</td><td>����� ������</td><td>����� ������</td><td>��� �����</td><td>�������</td><td>���������</td><td>���������</td><td>IP</td><td>�������</td><td>���������</td><td>�� ��������</td><td>���� ����������</td><td>���������� �����, ���.</td><td>���� ���������</td><td>�������������</td><td>�������</td></tr>';
for($i=0; $i<$num_res; $i++)
{ $f=mysql_fetch_array($r);
 $command=rawurldecode($f[command]);
echo "<tr><td>$f[id]</td><td>$f[platformid]</td><td>$f[methodid]</td><td>$f[dataid]</td><td>$f[taskgroupid]</td><td>$f[filename]</td><td>$command</td><td>$f[state]</td><td>$f[done]</td><td>$f[IP]</td><td>$f[adduserid]</td><td>$f[calcuserid]</td><td>$f[processid]</td><td>$f[begcalcdate] $f[begcalctime]</td><td>$f[predictminutes]</td><td>$f[endcalcdate] $f[endcalctime]</td>";
//if (($uid_cookie>0)&&(($adminauth==1)||($f[userid]==$uid_cookie)))
if ((($adminauth==1)||(($auth==1)&&($uid_cookie==$f[adduserid]))))
echo "<td><a href=taskedit.php?id=$f[id]>edit<a></td><td><a href=taskdelete.php?id=$f[id]>delete<a></td></tr>\n";
else
echo "<td>--</td><td>--</td></tr>\n";
}
echo '</table>';
//(id int, platformid int, methodid int, dataid int, filename char(50), command char(200), state char(30), done int, outfilename char(50), IP char(20), adduserid int, calcuserid int, processid int, begcalcdate date, begcalctime time , predictminutes int ,  endcalcdate date,  endcalctime time)
echo "1) <a href=tasklist2.php>�������� ������� �������</a><p>";
if ($auth==1)
{
echo "2) <a href=taskaddmany.php>���������� ���������� �������</a><p>";
echo "3) <a href=restartall.php?id=$uid_cookie>���������� ���� �������</a><p>";
echo "4) <a href=restart.php?id=$uid_cookie>���������� �������, ��������� ������</a><p>";
echo "5) <a href=restartzav.php?id=$uid_cookie>���������� �������� �������</a><p>";
echo "6) <a href=taskclear.php?id=$uid_cookie>������� ������ �������</a><p>";
if ($adminauth==1)
 {
  echo "7) <a href=restartall.php>���������� ���� ������� ���� �������������</a><p>";
  echo "8) <a href=restart.php>���������� ������� ���� �������������, ��������� ������</a><p>";
  echo "9) <a href=restartzav.php>���������� �������� ������� ���� �������������</a><p>";
  echo "10) <a href=taskclear.php>������� ������ ������� ���� �������������</a><p>";
 }
}
include "footer.php";
?>
