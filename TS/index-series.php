<?php
include "head_all.php";
?>
<title>Загрузка архива данных на сервер</title>
</head>
<body>
<h1> Загрузка архива данных на сервер </h1>
<?php
include "header.php";
?>
<p><a href=db/db_recreate_all.php> очистка БД </a></p>
<p><a href=update.php?g=2> Обновление БД </a></p>
<p><a href=generate.php?gmid=1> Генерация архива файлов с ценами закрытия </a></p>
<?php
include "indexupdgen.php";
?>
<p><a href=UxStatOdbc.php> Статистика минутной торговли по фьючерсу УХ (из базы аксесс по ОДБС)</a></p>
<p><a href=calctasklist.php>Tasks list</a></p>
<p><a href=datasources.php>CRUD for datasources</a></p>
<p><a href=dataseries.php>CRUD for dataseries</a></p>
<p><a href=seriesgroups.php>CRUD for seriesgroups</a></p>
<p><a href=seriesgroupsconn.php>CRUD for seriesgroupsconn</a></p>
<p><a href=groupsmethods.php>CRUD for groupsmethods connection table</a></p>

<?php
include "footer.php";
?>
