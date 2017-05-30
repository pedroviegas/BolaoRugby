<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : March 20th 2003
 * File  : adminconfigleague.php
 *********************************************************/
require_once "systemvars.php";
require_once "dbasedata.php";
require_once "configvalues.php";
require_once "security.php";
require_once "sortfunctions.php";

// By now the admin user should be logged in.
if ($User->loggedIn == FALSE) {
  echo "Only an Admin User can perform this function";
  exit;
}
if (!CheckAdmin($User->usertype)) {
  echo "Only an Admin User can perform this function";
  exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>">
<title>
Prediction Football Configuration
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">

<?php
function GetCurrentConfig() {
  global $dbaseConfigData, $leagueID;

  $link = OpenConnection();

  $query = "select * from $dbaseConfigData where lid='$leagueID' order by grp";
  $result = mysql_query($query)
    or die("Unable to read config data: $query");

  echo "<form action=\"setconfigparams.php\" method=\"POST\">\n";
  echo "<table>\n";
  while ($line = mysql_fetch_array($result)) {
    $grp = $line["grp"];
    $param = $line["param"];
    $descr = $line["descr"];
    $value = $line["value"];
    $ro = $line["ro"];

    echo "<tr class=\"TBLROW\">\n";
    echo "<td class=\"TBLROW\">\n";
    echo "<font class=\"TBLROW\">\n";
    echo "$descr\n";
    echo "</font>\n";
    echo "</td>\n";
    echo "<td class=\"TBLROW\">\n";
    echo "<font class=\"TBLROW\">\n";
    if ($ro == 'Y') {
      echo "$value\n";
    } else {
      echo "<input type=\"TEXT\" name=\"$param\" size=\"40\" value=\"$value\">\n";
    }
    echo "</font>\n";
    echo "</td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n";
  echo "<input type=\"HIDDEN\" name=\"REFERER\" value=\"adminconfigleague.php\">\n";
  echo "<input type=\"SUBMIT\" name=\"Change\" value=\"Change\">\n";
  echo "<input type=\"SUBMIT\" name=\"Default\" value=\"Default\">\n";
  echo "<input type=\"RESET\" name=\"Reset\" value=\"Reset\">\n";
  echo "</form>\n";

  CloseConnection($link);
}


?>
  <?php echo $HeaderRow ?>
  <table class="MAINTB">
      <!-- Display the next game -->
      <tr>
        <td colspan="3" align="center" class="TBLROW">
          <font class="TBLROW">
            <?php echo getNextGame() ?>
          </font>
        </td>
      </tr>
    <tr>
      <!-- Left column -->
      <td class="TBLHEAD" colspan="3" align="CENTER">
        <font class="TBLHEAD">
        Configure the game parameters [LeagueID <?php echo $leagueID;?>]
        </font>
      </td>
    </tr>
    <tr>
      <!-- Left column -->
      <td class="LEFTCOL">
        <!-- Menu -->
        <?php require_once "menus.php"?>
        <!-- End Menu -->
      </td>
      <!-- Central Column -->
      <td align="LEFT" class="CENTERCOL">
        <!-- Central Column -->
        <?php
          GetCurrentConfig();
        ?>
      </td>
      <!-- Right Column -->
      <td class="RIGHTCOL" align="RIGHT">
          <?php ShowPredictionStatsForNextMatch(); ?>
      </td>
    </tr>
  </table>
</body>
</html>
