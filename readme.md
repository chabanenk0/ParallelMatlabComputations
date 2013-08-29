This project is a web-based system for parallel computations. The project consists of the server side (php and mysql) and client side (matlab m-script)

Installing instructions
I. Install the server side software.
1.1. Requirements:
 - php v. 4.2 or higher
 - mysql
1.2. Unpack and copy php scripts to the http server 
 Create a new folder and copy the following folders there:
ParalelMatlabServer2 
TS
1.3. Create two new databases: one is for paralelMatlabServer and other is for TS
Create users with read/write access to these databases. It can be the same user.
1,4. Correct the files 
ParalelMatlabServer2\settings.php 
and 
TS\settings.php
putting the database names and user/pass information
1.5. Create empty databases
- correct ParalelMatlabServer2/clear.php change the row  if ((isset($_COOKIE['uid']))&&($_COOKIE['uid']==1)) to  if (1)//(isset($_COOKIE['uid']))&&($_COOKIE['uid']==1))
- run ParalelMatlabServer2/clear.php
- run TS/clear.php

Go to the browser, request the folder 
