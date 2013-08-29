<?php
/*
***************************************************************************
*   Copyright (C) 2007 by Cesar D. Rodas                                  *
*   cesar@sixdegrees.com.br                                               *
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
require("../dbal/dbal.php");
/* connect */
$dbh = new dbal("mysql://root@localhost/foobar");
/* set cache, directory*/
$dbh->setCacheDir("./cache");
/* execute an SQL */
$dbh->execute("create table foo(bar integer, xFoo varchar(250))");
/* compile an SQL */
$dbh->compile("insert into foo values(:bar, :xFoo)");
/* Execute SQL, passing parameters */
for($i=0;$i<50; $i++)
    $dbh->execute( array('bar'=>$i, 'xFoo'=>"xfoo vale $i") );
    
/* now query*/
    /* first method */

    $dbh->compile("select * from foo where bar between :min and :max");
    /* the result is cache for 60 seconds */
    $record = $dbh->query(array('min'=>5, 'max' => 40), 60); 
    /**
     *    Equivalent to:
     *    $record = $dbh->query("select * from foo where bar between :min and :max",array('min'=>5, 'max' => 40), 60); 
     */
    if (!$record) {
        echo $dbh->getLastError();
    } else {
        /* bind parameters */
        $bar  = & $record->bindColumn('bar');
        $xFoo = & $record->bindColumn('xFoo');
        /* also the result is saved on $f*/
        while ( $f = $record->getNext() ) {
            echo "$bar = $xFoo<br>";
            print_r($f);
            echo "<hr>";
        }
    }
?> 