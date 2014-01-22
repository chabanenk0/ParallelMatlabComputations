<?php
include "head_all.php";
?>
<title>Редактирование задания</title>
</head>
<body>
<h1> Редактирование задания </h1>
<?php
include "header.php";
$id=$_REQUEST['id'];
$r=mysql_query("select adduserid from tasks where id=$id");
$f=mysql_fetch_array($r);
$userid=$f['adduserid'];

if (!(($adminauth==1)||(($auth==1)&&($uid_cookie==$userid))))
{
//header('Location: userlist.php');
//echo "попало сюда!!! $uid_cookie ";
echo "Вы не имеете права редактировать настройки этого метода. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему под другим логином.<p>";
//include "footer.php";
exit;
}

?>

<?php
include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");
if(!mysql_connect(HostName,UserName,Password))
{ echo "Не могу соединиться с базой".DBName."!<br>";
echo mysql_error();
exit;
}
$id=$_REQUEST['id'];
mysql_select_db(DBName);
//mysql_query("use tasks");

echo "<form method=post action=taskedit1.php>";
//try  
{
 //$ccc=$HTTP_GET_VARS['command'];
 // echo $command;
 }
//catch(Exception $e)  
{
$r=mysql_query("select * from tasks where id=$id");
$f=mysql_fetch_array($r);
//echo "$f[id]";
echo "number:<input type=text name=id value=$f[id]><p>";
echo "Платформа:<input type=text name=platformid value=$f[platformid]><p>";
// треба зробити запит по платформам, цикл виведення опцый... Все подобавляю, когда сделаю вывод соотв. баз данных.
echo "Метод:<input type=text name=methodid value=$f[methodid]><p>";
echo "Пакет данных:<input type=text name=dataid value='$f[dataid]'><p>";
echo "Имя файла:<input type=text name=filename value='$f[filename]'><p>";
$command=rawurldecode($f[command]);
echo 'Команда:<input type=text name=command value="'.$command.'"> ';

echo "<p>Состояние:";
echo "<input type=RADIO name=status value=1 checked=";
if ($f['state']=='wait') echo "1"; else echo "0"; 
echo ">wait</input>  ";
echo "<input type=RADIO name=status value=2 checked=";
if ($f['state']=='calc') echo "1"; else echo "0"; 
echo ">calc</input>  ";
echo "<input type=RADIO name=status value=3 checked=";
if ($f['state']=='done') echo "1"; else echo "0"; 
echo ">done</input><p>";
echo "Завершено:<input type=checkbox name=active value=1><p>";
echo "IP: <input type=text name=IP value='$f[IP]'><p>";
echo "Добавил: <input type=text name=adduserid value='$f[adduserid]'><p>";
echo "Вычислитель: <input type=text name=calcuserid value='$f[calcuserid]'><p>";
echo "ИД процесса: <input type=text name=processid value='$f[processid]'><p>";
echo "Дата/время начала вычислений: <input type=text name=begcalcdate value=$f[begcalcdate]> <input type=text name=begcalctime value='$f[begcalctime]'><p>";
echo "Прогнозируемое время вычисления, мин.: <input type=text name=predictminutes value='$f[predictminutes]'><p>";
echo "Дата/время окончания вычислений: <input type=text name=endcalcdate value='$f[endcalcdate]'> <input type=text name=endcalctime value='$f[endcalctime]'><p>";

echo "<input type=submit value='Сохранить'> <input type=reset value='Очистить'><p><p>";
}
?>

<?php
include "footer.php";
?>
