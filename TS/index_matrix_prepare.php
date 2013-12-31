//index_matrix_prepare.php
<?php
// Run sequence:
// ++generate_crosstable.php
// ++exec_queries.php
// --generate_export_query.php // old version. Prints sql queries to the output
// save_matrix.php
//include "settings.php";

if(!mysql_connect(HostName,UserName,Password))
{ echo "Unable to connect to the database ".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName2);
$r=mysql_query("select * from seriesgroups;");
$num_res=mysql_num_rows($r);
echo "<p>Choose the group to create a matrix (generating queries):</p>\n<ul>\n";
for($i=0; $i<$num_res; $i++)            
{ $f=mysql_fetch_array($r);
echo "<li>$f[id]. <a href='generate_crosstable.php?sgid=$f[id]'>$f[name]</a></li>\n";

}
echo "</ul>\n";
echo "<p><a href='exec_queries.php'>Execute generated queries </a> </p>\n";
$r=mysql_query("select distinct matrixnum as id from matrixseries;");
if (!$r)
{
	echo "Matrixes are not ready.<br>\n";
}
else
{
	$num_res=mysql_num_rows($r);
	echo "<p>Choose the matrix to download (export results):</p>\n<ul>\n";
	for($i=0; $i<$num_res; $i++)            
		{ 
			$f=mysql_fetch_array($r);
			echo "<li>$f[id]. <a href='save_matrix.php?mnum=$f[id]'>$f[id]</a></li>\n";
		}
	echo "</ul>\n";
}

?>