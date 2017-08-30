<?php
$num = $_GET['num'];
$aindex = $_GET['aindex'];
$con = mysql_connect("localhost","root","");

if (!$con){
 die('Could not connect: ' . mysql_error());
}

mysql_select_db("goodplus", $con);

$sql0s = "SELECT * FROM `good` where `id` = ".$aindex;
$sql0 = mysql_query($sql0s);

if($_GET['flag'] == 0){
 while($row = mysql_fetch_array($sql0)){
 echo $row['value'];
 }
}else if($_GET['flag'] == 1){
 $sql="UPDATE `goodplus`.`good` SET `value` = '".$num."' WHERE `good`.`id` = ".$aindex;

 if (!mysql_query($sql,$con)){
 die('Error: ' . mysql_error());
 }
 echo $num;
}
mysql_close($con)
?>
