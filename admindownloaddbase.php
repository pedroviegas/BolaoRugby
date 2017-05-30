<?php
/*********************************************************
 * Author: John Astill
 * Date  : 2nd July 2003 (C)
 * File  : admindownloaddbase.php
 * Desc  : Dump the dbase contents to the local computer.
 *       : The file name will be a cobination of the 
 *       : database name and the date. The table structure
 *       : and data will be saved. 
 ********************************************************/
  require "systemvars.php";
  header("Content-type: text/plain");

  $link = OpenConnection();

  // Describe the tables

  // Describe the data
  echo "#\n# Dumping the User Data\n#\n";
  $query = "select * from $dbaseUserData where lid='$leagueID'";
  $result = mysql_query($query)
    or die("Unable to run query $query<br>".mysql_error());
  while($line = mysql_fetch_array($result)) {
    echo "insert into $dbasePredictionData () values ();\n";
  }

  echo "#\n# Dumping the Prediction Data\n#\n";
  $query = "select * from $dbasePredictionData where lid='$leagueID'";
  $result = mysql_query($query)
    or die("Unable to run query $query<br>".mysql_error());
  while($line = mysql_fetch_array($result)) {
    echo "insert into $dbasePredictionData () values ();\n";
  }

  echo "#\n# Dumping the Match Data\n#\n";
  $query = "select * from $dbaseMatchData where lid='$leagueID'";
  $result = mysql_query($query)
    or die("Unable to run query $query<br>".mysql_error());
  while($line = mysql_fetch_array($result)) {
    echo "insert into $dbasePredictionData (lid) values ('$leagueID');\n";
  }

  echo "#\n# Dumping the Config Data\n#\n";
  $query = "select * from $dbaseMatchData";
  $result = mysql_query($query)
    or die("Unable to run query $query<br>".mysql_error());
  while($line = mysql_fetch_array($result)) {
    echo "insert into $dbasePredictionData (lid) values ('$leagueID');\n";
  }

  echo "#\n# Dumping the Message Data\n#\n";
  $query = "select * from $dbaseMatchData where lid='$leagueID'";
  $result = mysql_query($query)
    or die("Unable to run query $query<br>".mysql_error());
  while($line = mysql_fetch_array($result)) {
    echo "insert into $dbasePredictionData (lid) values ('$leagueID');\n";
  }

?>
