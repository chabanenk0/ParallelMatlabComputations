<?php

function hex_encode($data,$fid2)
{ echo "in the function hex_encode()\n";
$n=strlen($data)/2;
//echo "n=$n \n";
$data_enc="";
for ($i=0;$i<$n;$i++)
 {
  //sscanf($data,"%02x",$new_char);
  
  $new_char1=$data{$i*2};
  $new_char2=$data{$i*2+1};
  if (is_numeric($new_char1))
    $new_char1=ord($new_char1)-ord('0');
  else 
    $new_char1=ord($new_char1)-ord('a')+10;
  if (is_numeric($new_char2))
    $new_char2=ord($new_char2)-ord('0');
  else 
    $new_char2=ord($new_char2)-ord('a')+10;
  $new_char=$new_char1*16+$new_char2;
  //$data_enc=$data_enc.chr(($new_char+0));
  $data_enc=chr(($new_char+0));
  //  echo  $data{$i*2};
  //  echo  $data{$i*2+1};
  //  echo  " ";
  //echo "c=$new_char ($new_char1 $new_char2, ), data_enc_char = $data_enc \n";
  //$data=substr($data,2,strlen($data));
  fwrite($fid2,$data_enc);
 }
 return $data_enc;
}

include "settings.php";
//define("DBName","matlab2");
//define("HostName","localhost");
//define("UserName","root");
//define("Password","");

//$uid=$_REQUEST['uid'];
//$platformid=$_REQUEST['platformid'];
$data=$_REQUEST['data'];
$pos=$_REQUEST['pos'];
$userid=$_REQUEST['userid'];
//$num_task=$_REQUEST['num_task'];
//params = {'userid',num2str(userid), 'descr',descr,'dataid',dataid,'data',hexencode3(block),'pos',num2str(filelen),'archfilename',archfilename}; % 20130116 пробую без таскайди


//echo "uid=$uid<p>";
//echo "platformid=$platformid<p>";
//echo "data=$data<p>";
//echo "pos=$pos<p>";
//echo "num_task=$num_task<p>";



$dataid=$_REQUEST['dataid'];
$dataname=$_REQUEST['archfilename'];
$descr=$_REQUEST['descr'];
//$num_task=$taskid;
// need to determine username in order to cd to the required folder. 
//Also need the dataname
if(!mysql_connect(HostName,UserName,Password))
{ echo "Ia iiao niaaeieouny n aacie".DBName."!<br>";
echo mysql_error();
exit;
}
mysql_select_db(DBName);
//mysql_query("use tasks");
//$r1=mysql_query("select dataid from tasks where id=$taskid");
//$f1=mysql_fetch_array($r1);
//$dataid=$f1[dataid];


//echo "i=$i, j=$j, flagj=$flagj<p>";

$r=mysql_query("select * from users where id=$userid");
$f=mysql_fetch_array($r);

$username=$f['login'];

$realfolder=$username."/data/";
//2012_2013_computed_uid1_pid_18.rar
$fullname=$dataid."_uid_".$userid."_".$dataname;
//echo "fullname=$fullname <p>";
$fullpathname=$realfolder.$fullname;
echo "fullpathname=$fullpathname <p>\n";

$fullpathname2=$fullpathname;
$fullpathname=$uploaddirroot.$fullpathname;
if (is_file($fullpathname))
{
if ($fid2=fopen($fullpathname,"ab"))
{ echo "opening file for w\n";
  $data_enc=hex_encode($data,$fid2);
  //fseek($fid2,$pos,0);// закомментированно, бо запись перенесена в hex_encode
  //fwrite($fid2,$data_enc);
  fclose($fid2);
}
else 
 {//$fid2=fopen($fullpathname,"rwb"); // закомментированно, бо запись перенесена в hex_encode
  //$data_enc=hex_encode($data);
  //frewind($fid2,$pos,0);
  //fwrite($fid2,$data_enc);
  //fclose($fid2);
  echo "Error appending file\n";
  }
}
else 
 {echo "creating and opening file for w\n";
  $fid2=fopen($fullpathname,"wb");
  if ($fid2)
  {
    //echo "data=$data \n";
    $data_enc=hex_encode($data,$fid2);
    //echo "data_enc=$data_enc \n";// закомментированно, бо запись перенесена в hex_encode
    //frewind($fid2,$pos,0);
    //fwrite($fid2,$data_enc);
    fclose($fid2);
  }
  else
   {echo "error appending file in this mode\n";
   }
 }
if ($pos==0)
{
mysql_query("update datafolder set folder='$fullpathname2' where id=$dataid");
mysql_query("update datafolder set descr='$descr' where id=$dataid");
}
?>

