<?php
include "head_all.php";
?>
<title>�������� ������ ������ �� ������</title>
</head>
<body>
<h1> �������� ������ ������ �� ������ </h1>
<?php
include "header.php";
?>
<p><a href=db/db_recreate_all.php> ������� �� </a></p>
<p><a href=update.php?g=2> ���������� �� </a></p>
<p><a href=generate.php?gmid=1> ��������� ������ ������ � ������ �������� </a></p>
<?php
include "indexupdgen.php";
?>
<p><a href=UxStatOdbc.php> ���������� �������� �������� �� �������� �� (�� ���� ������ �� ����)</a></p>
<p><a href=calctasklist.php>Tasks list</a></p>
<p><a href=datasources.php>CRUD for datasources</a></p>
<p><a href=dataseries.php>CRUD for dataseries</a></p>
<p><a href=seriesgroups.php>CRUD for seriesgroups</a></p>
<p><a href=seriesgroupsconn.php>CRUD for seriesgroupsconn</a></p>
<p><a href=groupsmethods.php>CRUD for groupsmethods connection table</a></p>
<p><a href=matrixdata.php>CRUD for matrixdata</a></p>
<p><a href=matrixseries.php>CRUD for matrixseries</a></p>
<p><a href=allrecords.php>CRUD for allrecords</a></p>
<p><a href=allrecords_tick.php>CRUD for allrecords_tick</a></p>
<p><a href=resultseries.php>CRUD for resultseries</a></p>
<p><a href=resultseriesdata.php>CRUD for resultseriesdata</a></p>
<?php
include "index_matrix_prepare.php";
?>

<?php
include "footer.php";
?>
