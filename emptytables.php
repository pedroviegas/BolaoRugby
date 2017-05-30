<?php
/*********************************************************
 * Author: John Astill
 * Date  : 2nd July 2003 (c)
 * File  : emptytables.php
 * Desc  : Empty the configured tables. The table
 *       : names are taken from system vars.
 ********************************************************/
require "systemvars.php";
?>
<html>
  <head>
    <title>
      Emptied database tables <?php echo $dbname; ?>
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">

<table>
<tr>
<td>
  <?php require "adminsetupmenu.php"; ?>
</td>
<td colspan="1" align="LEFT" valign="TOP" class="TBLROW">
<font class="TBLROW">
<?php
  $url = $HTTP_POST_VARS["URL"];
  $dbname = $HTTP_POST_VARS["DBASENAME"];
  $username = $HTTP_POST_VARS["USERNAME"];
  $password = $HTTP_POST_VARS["PASSWORD"];
  $userDataTblName = $dbaseUserData;
  $predDataTblName = $dbasePredictionData;
  $matchDataTblName = $dbaseMatchData;
  $standingsTblName = $dbaseStandings;

  // Connect to the host.
  $link = mysql_connect($url, $username, $password)
      or die("Could not connect to $url");

  echo "Emptying $dbname.$dbaseUserData<br>";
  $query = "delete from $dbname.$dbaseUserData where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  echo "Emptying $dbname.$dbasePredictionData<br>";
  $query = "delete from $dbname.$dbasePredictionData where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  echo "Emptying $dbname.$dbaseMatchData<br>";
  $query = "delete from $dbname.$dbaseMatchData where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  echo "Emptying $dbname.$dbaseStandings<br>";
  $query = "delete from $dbname.$dbaseStandings where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  echo "Emptying $dbname.$dbaseMsgData<br>";
  $query = "delete from $dbname.$dbaseMsgData where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  echo "Emptying $dbname.$dbaseAddrData<br>";
  $query = "delete from $dbname.$dbaseAddrData where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  echo "Emptying $dbname.$dbaseGroups<br>";
  $query = "delete from $dbname.$dbaseGroups where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  echo "Emptying $dbname.$dbaseGroupStandings<br>";
  $query = "delete from $dbname.$dbaseGroupStandings where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

  echo "Emptying $dbname.$dbaseMonthlyWinner<br>";
  $query = "delete from $dbname.$dbaseMonthlyWinner where lid='$leagueID'";
  $userresult = mysql_query($query)
      or die("Query failed: $query");

?>
<br>

All the tables except the config table has been emptied.<br>You will need to recreate your admin user to be able to login.
</font>
</td>
</tr>
</table>

  </body>
</html>

