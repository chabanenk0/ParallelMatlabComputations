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
     'tickernum' => array(CAPTION => 'tickernum', TABLE => "dataseries", ID => "id", TEXT => "name", SHOWCOLUMN=>true),
     'name' => array(CAPTION => 'name', SHOWCOLUMN=>true),
     'discr' => array(CAPTION => 'discr', SHOWCOLUMN=>true),
     'date' => array(CAPTION => 'date', SHOWCOLUMN=>true),
     'time' => array(CAPTION => 'time', SHOWCOLUMN=>true),
     'open' => array(CAPTION => 'open', SHOWCOLUMN=>true),
     'low' => array(CAPTION => 'low', SHOWCOLUMN=>true),
     'high' => array(CAPTION => 'high', SHOWCOLUMN=>true),
     'close' => array(CAPTION => 'close', SHOWCOLUMN=>true),
     'volume' => array(CAPTION => 'volume', SHOWCOLUMN=>true),
     'adjclose' => array(CAPTION => 'adjclose', SHOWCOLUMN=>true),
     'dumbfield1' => array(CAPTION => 'dumbfield1', SHOWCOLUMN=>true),
     'dumbfield2' => array(CAPTION => 'dumbfield2', SHOWCOLUMN=>true),
     'dumbfield3' => array(CAPTION => 'dumbfield3', SHOWCOLUMN=>true),
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
$crud = new crud("mysql://root@localhost/TS","allrecords",$info);
?>
<h1>CRUD for allrecords table</h1>
<h2><a href='?action=new'>Add a new record </a> | <a href='?'>View</a></h2>

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
?>
