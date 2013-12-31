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
require_once "head_all.php"; 
require_once "header.php"; 

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
    'groupid' => array(CAPTION => 'groupid', TABLE => "seriesgroups", ID => "id", TEXT => "name", SHOWCOLUMN=>true),
    'methodid' => array(CAPTION => 'methodid', TABLE => "methods", ID => "id", TEXT => "name", SHOWCOLUMN=>true, DATABASE => "matlab2"),
    'type_precommand' => array(CAPTION => 'type_precommand', SHOWCOLUMN=>true ), 
    'precommands' => array(CAPTION => 'precommands', SHOWCOLUMN=>true, ENCODE=>true ),
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
    EDIT_LINK => "?action=update&id=%id",
    DELETE_LINK => "?action=delete&id=%id"
);
$crud = new crud("mysql://".UserName."@".HostName."/".DBName2,"groupsmethods",$info);
?>
<h1>CRUD for GroupsMethods table</h1>
<h2><a href='?action=new'>Add a new GroupMethods connection record</a> | <a href='?'>View</a></h2>

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
