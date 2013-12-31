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
<p><a href=allrecords.php>CRUD for allrecords</a></p>
<p><a href=allrecords_tick.php>CRUD for allrecords_tick</a></p>
<p><a href=resultseries.php>CRUD for resultseries</a></p>
<p><a href=resultseriesdata.php>CRUD for resultseriesdata</a></p>

<?php
include "footer.php";
?>
