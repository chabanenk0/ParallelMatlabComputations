<?php
include "head_all.php";
?>
<title>Паралельное вычисление в Матлаб</title>
</head>
<body>
<script type="text/javascript">

var mainform=0;
var renewingelement; //0 - methods, 1 - versions, 2 - commands
var $commandtemplate="";
function initRequest() {
    if (window.XMLHttpRequest) {
        if (navigator.userAgent.indexOf('MSIE') != -1) {
            isIE = true;
        } 
        return new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        isIE = true;
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
}
function addCommand2list(newname)
{
$newcommand=$commandtemplate.replace(new RegExp("#infile",'g'),newname);
mainform.tasks.value=mainform.tasks.value+$newcommand+"\n";
}

function parseMessages(responseXML) {

     if (responseXML == null) {
        alert ("ResponseXML is null!");
        return false;
    } else {

        var recvqueries=responseXML.childNodes[0].getElementsByTagName("item")
        //alert (recvqueries.length);
        if (recvqueries.length > 0) {

            for (loop = 0; loop < recvqueries.length; loop++) {
                var somequery = recvqueries[loop];
                //alert (somequery.getElementsByTagName("name")[0].childNodes[0].nodeValue);
                var querytoolboxid = somequery.getElementsByTagName("value")[0].childNodes[0].nodeValue;
                var queryname = somequery.getElementsByTagName("name")[0].childNodes[0].nodeValue;
                if (renewingelement==0) addMethod(querytoolboxid,queryname);
                else if (renewingelement==1) addVersion(querytoolboxid,queryname);
                else if (renewingelement==4) addCommand2list(queryname);
            }
        }
    }
}
function clearMethods()
{mainform.methodid2.options.length=0;
}

function addMethod(toolboxid,name)
{i=mainform.methodid2.options.length;
mainform.methodid2.options.selectedIndex=0;
//alert (toolboxid);
mainform.methodid2.options[i] = new Option(name, toolboxid);
}

function clearVersions()
{mainform.methodid.options.length=0;
}

function addVersion(toolboxid,name,loop)
{i=mainform.methodid.options.length;
mainform.methodid.options.selectedIndex=0;
mainform.methodid.options[i] = new Option(name, toolboxid);
}


function callback() {
//alert("Callback!");
//alert("Callback"+req.responseText);

    if (req.readyState == 4) {
        if (req.status == 200) {
            parseMessages(req.responseXML);
            if (renewingelement==0) {MethodChange(mainform); VersionChange(mainform);}
            if (renewingelement==1) {VersionChange(mainform);}
			}
    }
}
function PlatformChange(form)
{       mainform=form;
        renewingelement=0;
        clearMethods();
        clearVersions();
        var $platformid=form.platformid.value;
        var url = "methodsxml.php?platformid="+$platformid;
        //alert (url);
        req = initRequest();
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send();
}


function MethodChange(form)
{       mainform=form;
        renewingelement=1;
        clearVersions();
        var $toolboxid=form.methodid2.value;
        var $platformid=form.platformid.value;
        var url = "versionsxml.php?platformid="+$platformid+"&toolboxid="+$toolboxid;
        //alert (url);
        req = initRequest();
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send();
}

function callback2() {
//alert("Callback!");
//alert("Callback"+req.responseText);

    if (req.readyState == 4) {
        if (req.status == 200) {
            mainform.commandedit.value=req.responseText;
            //parseMessages(req.responseXML);
            //if (renewingelement==0) {MethodChange(mainform); VersionChange(mainform);}
        }
    }
}

function VersionChange(form)
{       mainform=form;
        renewingelement=3;
        mainform.commandedit.text="";
        var $toolboxid=form.methodid2.value;
        var $platformid=form.platformid.value;
        var $version=form.methodid.value;
        var url = "commandxml.php?platformid="+$platformid+"&toolboxid="+$toolboxid+"&version="+$version;
        //alert (url);
        req = initRequest();
        req.open("GET", url, true);
        req.onreadystatechange = callback2;
        req.send();
}


function CommandChange(form)
{       mainform=form;
        renewingelement=4;
        mainform.tasks.value="";
        var $command=mainform.commandedit.value;
        $commandtemplate=$command;
        var $dataid=form.archiveid.value;
        var url = "listxml.php?dataid="+$dataid;
        //alert (url);
        req = initRequest();
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send();
}

</script>

<h1>Добавление нескольких команд</h1>
<?php
include "header.php";
if ($auth==0)
{
//header('Location: userlist.php');
echo "Вы не имеете права добавлять задачи, не пройдя авторизацию. <p>\nПожалуйста,  <a href=login.php>войдите</a> в систему или <a href=userreg.php>зарегистируйтесь</a>.<p>";
include "footer.php";
exit;
}

?>


<form method=post action=taskaddmany1.php>
<!--Добавил
<select name=userid> --!>
<?
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
mysql_select_db(DBName);
//mysql_query("use tasks");


echo "<input type=hidden name=userid value=$userid>\n";

// перенесу в принимающий файл от греха подальше. 
//$r=mysql_query("select max(taskgroupid) as maxtaskid from tasks");
//$f=mysql_fetch_array($r);
//$maxtaskid=$f['maxtaskid'];
//echo "<input type=hidden name=taskgroupid value=$maxtaskid>\n";
 
echo "Архив данных<select name=archiveid>\n";
$r=mysql_query("select * from datafolder");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
  $selected="";
 echo "<option $selected value=".$f['id']."> ".$f['folder']." </option>\n";//$f['id']  $f['folder']
}
echo "</select><p>\n";

echo "Группа заданий, источних данных: <select name=taskgroupdata>\n";
echo "<option selected value=0> Без зависимостей </option>\n";
$r=mysql_query("select * from taskgroups");
$num_res=mysql_num_rows($r);
for ($i=1;$i<=$num_res;$i++)
{$f=mysql_fetch_array($r);
  $selected="";
 echo "<option $selected value=".$f['id']."> ".$f['name']." </option>\n";//$f['id']  $f['folder']
}
echo "</select><p>\n";


echo "Платформа\n<select name=platformid onChange='PlatformChange(this.form)'>\n";
$r=mysql_query("select * from platforms");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
  $selected="";
// echo "<option $selected value=$f['id']> $f['login'] </option>\n";
 echo "<option $selected value=$f[id]> $f[name] </option>\n";
}
echo "</select><p>\n";

echo "Метод<select name=methodid2 onChange='MethodChange(this.form)'>\n";
$r=mysql_query("select * from methods group by toolboxid");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
  $selected="";
 echo "<option $selected value=".$f['toolboxid']."> ".$f['name']." </option>\n";//$f['id']  $f['folder']
}
echo "</select><p>\n";
echo "Версия<select name=methodid onChange='VersionChange(this.form)'>\n";
$toolboxid22=$f['toolboxid'];
$r=mysql_query("select * from methods where toolboxid=$toolboxid22");
$num_res=mysql_num_rows($r);
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
  $selected="";
 echo "<option $selected value=".$f['id']."> ".$f['version']." </option>\n";//$f['id']  $f['folder']
}
echo "</select><p>\n";

?>
Шаблон команды:<p>
<input type=text name=commandedit value="cmd('#infile.txt',500,'#infile_res.txt');"><p>
<input type=button value="Сгенерировать команды задания" onClick='CommandChange(this.form)'><p>
Список команд:<p>
<textarea name=tasks rows=10,cols=300>
</textarea><p>
Имя данной группы заданий <input type=text name=curgroupname value="Безымянная группа"><p>
Имя файла для обработки: <input type=text name=infile value="dj.txt"><p>

<?php

echo "Процесс, для которого предназначено <select name=processid>\n";
$r=mysql_query("select * from processes");
$num_res=mysql_num_rows($r);
 echo "<option selected value=0> Любой </option>\n";//$f['id']  $f['folder']
for ($i=0;$i<$num_res;$i++)
{$f=mysql_fetch_array($r);
  $selected="";
 echo "<option $selected value=".$f['id']."> ".$f['userid']." ".$f['platformid']." ".$f['ip']." </option>\n";//$f['id']  $f['folder']
}
echo "</select><p>\n";


//Архив програм:<input type=file name='filetool'><p><p>
?>

Прогнозное время рассчетов (в мин.):<input type="text" name='predictminutes' value = "60"><p><p>

<input type=submit value='Добавить'>  
<input type=reset value='Очистить'><p>
</form>
<?php
include "footer.php";
?>
