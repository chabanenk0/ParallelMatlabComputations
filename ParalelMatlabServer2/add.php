<?php
include "settings.php";
if(!mysql_connect(HostName,UserName,Password))
{ echo "�� ���� ����������� � �����".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("use tasks");
$command=$_REQUEST['command'];
try
 {echo $command;
  $command= rawurlencode($command);
  echo "<p>\n";
   echo $command;


//if exists($command)
$r=mysql_query("select max(id) as maxid from tasks");
if ($r)
 {
  $f=mysql_fetch_array($r);
   if ($f) $num_res=$f['maxid'];
   else $num_res=0;
 }
 $id=$num_res+1;
  mysql_query("insert into tasks(id, command,state,done,IP) values($id, 'command','wait',0,'0.0.0.0')");
  mysql_query("update tasks set command='$command' where id=$id");
 }
catch (Exception $e){}
?>
<html>
<head>
<title>���������� ������ �������</title>
</head>
<body>
<h1> ���������� ������ ������� </h1>
<form action=add.php method=get>
�������:<input type=text name=command>
<input type=submit value="��������">
</form>
1) <a href=tasklist2.php>�������� ������� �������</a><p>
2) <a href=add.php>���������� �������</a><p>
3) <a href=clear.php>������� ������ �������</a><p>
4) <a href=../>Homepage</a><p>
</body>
</html>