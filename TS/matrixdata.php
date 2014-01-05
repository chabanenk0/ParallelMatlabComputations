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
    'name' => array(CAPTION => 'name', SHOWCOLUMN=>true ),
    'temptamblename' => array(CAPTION => 'temptamblename', SHOWCOLUMN=>true ),
    'databasename' => array(CAPTION => 'databasename', SHOWCOLUMN=>true ),
    'begdate' => array(CAPTION => 'begdate', SHOWCOLUMN=>true ),
    'enddate' => array(CAPTION => 'enddate', SHOWCOLUMN=>true ),
    'begtime' => array(CAPTION => 'begtime', SHOWCOLUMN=>true ),
    'endtime' => array(CAPTION => 'endtime', SHOWCOLUMN=>true ),
    /*
     *
     *
     */
    // 'country' => array(CAPTION => 'Contry', TABLE => "table_2", ID => "countryId", TEXT => "countryName", SHOWCOLUMN=>true),
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
	CSV_TEXT => "Download",
	VIEW_TEXT => "Edit series",
    EDIT_LINK => "?action=update&id=%id",
    DELETE_LINK => "?action=delete&id=%id",
	CSV_LINK => "save_matrix.php?mnum=%id",
	VIEW_LINK => "matrixseries.php?filter=matrixnum=%id"
);
$crud = new crud("mysql://".UserName."@".HostName."/".DBName2,"matrixdata",$info);
require_once "head_all.php"; 
require_once "header.php"; 

?>
<h1>CRUD for MatrixData table</h1>
<h2><a href='?action=new'>Add a new matrix </a> | <a href='?'>View</a></h2>

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
        $crud->read();
        break;
}
require_once "footer.php"; 
?>
