<?php
/*
***************************************************************************
*   Copyright (C) 2007-2008 by Sixdegrees                                 *
*   cesar@sixdegrees.com.br                                               *
*   "Working with freedom"                                                *
*   http://www.sixdegrees.com.br                                          *
*                                                                         *
*   Permission is hereby granted, free of charge, to any person obtaining *
*   a copy of this software and associated documentation files (the       *
*   "Software"), to deal in the Software without restriction, including   *
*   without limitation the rights to use, copy, modify, merge, publish,   *
*   distribute, sublicense, and/or sell copies of the Software, and to    *
*   permit persons to whom the Software is furnished to do so, subject to *
*   the following conditions:                                             *
*                                                                         *
*   The above copyright notice and this permission notice shall be        *
*   included in all copies or substantial portions of the Software.       *
*                                                                         *
*   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,       *
*   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF    *
*   MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.*
*   IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR     *
*   OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, *
*   ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR *
*   OTHER DEALINGS IN THE SOFTWARE.                                       *
***************************************************************************
*/
error_reporting( ~ E_NOTICE & E_ALL );
require("class/crud.php");
require("pChart/pChart/pData.class");
require("pChart/pChart/pChart.class");
require_once "../ParalelMatlabServer2/settings.php";

$info = array(
    /**
     *  Show column => visible on read
     *  Insert hide => autoincrement value, the form doesn't display on create
     *  update read only => this value couldn't be update
     */
    'id' => array(CAPTION => 'id', SHOWCOLUMN => true, INSERT_HIDE =>true, UPDATE_READ_ONLY => true),
    /*
     *
     */
    /*
     *
     *
     */
     //'id' => array(CAPTION => 'id', SHOWCOLUMN=>true),
     'resultid' => array(CAPTION => 'resultid', TABLE => "resultseries", ID => "id", TEXT => "name", SHOWCOLUMN=>true),
     'position' => array(CAPTION => 'position', SHOWCOLUMN=>true),
     'c1' => array(CAPTION => 'c1', SHOWCOLUMN=>true),
     'c2' => array(CAPTION => 'c2', SHOWCOLUMN=>true),
     'c3' => array(CAPTION => 'c3', SHOWCOLUMN=>true),
    /*
     *
     *
     */
   //    'age' => array(CAPTION => 'Age', SHOWCOLUMN=>true,SELECT => range(1,99) ),
    /*
     *
     *
     */
   // 'email' => array(CAPTION => 'E-mail' ),

    EDIT_TEXT => "Edit",
    DELETE_TEXT => "Delete",
    EDIT_LINK => "?action=update&id=%id",
    DELETE_LINK => "?action=delete&id=%id"
);
$crud = new crud("mysql://".UserName."@".HostName."/".DBName2,"resultseriesdata",$info);
if (($_GET['action']=='new')&&(array_key_exists('c1',$_POST)))
            {
$new_id=($crud->create());
echo "$new_id";
return 0;
}

if ($_GET['action']=='csv') {
    $filename=$_GET['filter'];
    $filename=str_replace('resultid=','',$filename);
    $filename=$filename.".txt";
    header('Content-type:file/binary');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $crud->read_csv($_GET['filter'],$_GET['columnname']);
    //echo "$new_id";
    return 0;
}
require_once "head_all.php"; 
require_once "header.php"; 

if ($_GET['action']=='plot') {
    $filename=$_GET['filter'];
    $filename=str_replace('resultid=','',$filename);
    $filename=$filename.".png";
    //header('Content-type:file/binary');
    //header("Content-Disposition: attachment; filename=\"$filename\"");
    $data=$crud->read_data_array($_GET['filter'],$_GET['columnname']);
    $DataSet = new pData;
    $DataSet->AddPoint($data,"Serie1");
    $DataSet->AddAllSeries();
    // Initialise the graph   
    $Test = new pChart(700,230);
    $Test->setFontProperties("pChart/Fonts/tahoma.ttf",8);   
    $Test->setGraphArea(70,30,680,200);   
    $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);   
    $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);   
    $Test->drawGraphArea(255,255,255,TRUE);
    $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);   
    $Test->drawGrid(4,TRUE,230,230,230,50);
    // Draw the 0 line   
    $Test->setFontProperties("pChart/Fonts/tahoma.ttf",6);   
    $Test->drawTreshold(0,143,55,72,TRUE,TRUE);   
    // Draw the line graph
    $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());   
    $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);   
    // Finish the graph   
    $Test->setFontProperties("pChart/Fonts/tahoma.ttf",8);   
    $Test->drawLegend(75,35,$DataSet->GetDataDescription(),255,255,255);   
    $Test->setFontProperties("pChart/Fonts/tahoma.ttf",10);   
    $Test->drawTitle(60,22,"example 1",50,50,50,585);   
    $Test->Render($filename);
	echo "<img src=$filename>";
    return 0;
}
?>
<h1>CRUD for resultseries (data) table</h1>
<h2><a href='?action=new'>Add a new result series data record </a> | <a href='?'>View</a></h2>

<?php
switch ( $_GET['action'] ) {
    case 'new':
        if ( $crud->create() ) {
            echo " A new data was added";
        }
        break;
    case 'delete';
        if ( $crud->delete(array('id' => $_GET['id'])) == true)
            echo "A data was deleted";
        break;
    case 'update':
        if ( $crud->update(array('id' => $_GET['id']) ) == true)
            echo "A data was updated";
        break;
    default:
        $crud->read($_GET['filter']);
        break;
}
require_once "footer.php"; 
?>
