<?php
/***************************************************
 * Utility to take a day off all the matches in the
 * matchdata database
 ***************************************************/

 $dbaseName = "predictionleague2004";
 $dbaseMatchData = "plmatchdata";
 $host = "localhost";
 $username = "username";
 $password = "password";
 $lid = 0;

 $link = mysql_connect("$host","$username","$password");
 
 $res = mysql_query("use $dbaseName") 
          or die ("Unable to select database: $dbaseName<br>".mysql_error());


 $res = mysql_query("select * from $dbaseMatchData where lid='$lid' order by matchdate")
          or die ("Unable to select matchdata: $dbaseName.$dbaseMatchData<br>".mysql_error());


 while($line = mysql_fetch_array($res)) {
   $mid = $line["matchid"];
   $mdate = $line["matchdate"];
   echo "$mid $mdate<br>";

   $time = strtotime($mdate);
   $time -= (3600* 24);
   $newdate = date("Y-m-d H:i:s",$time);

   $query = "update $dbaseMatchData set matchdate='$newdate' where matchid='$mid' and lid='$lid'";
   echo "$query<br>";
   $succ = mysql_query($query)
          or die ("Unable to update matchdata: $query<br>".mysql_error());

 }
?>
