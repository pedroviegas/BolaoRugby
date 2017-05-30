<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : March 19th 2004
 * File  : configview.php
 *********************************************************/
function SetParam($param, $val) {
  global $dbase, $dbaseConfigData, $leagueID;

  $query = "update $dbaseConfigData set value=\"$val\" where param=\"$param\" and lid='$leagueID'";
  $result = $dbase->query($query);
}
  
function SetConfig() {
  global $_POST;
  if (array_key_exists("Default",$_POST)) {
    if ($_POST["Default"] == "Default") {
      PopulateConfigTable();
    }
  } else {
    while(list($param,$val) = each($_POST)) {
      if ($param == "REFERER") {
        $referer = $val;
      }
      if ($param == "Change" || $param == "Default") {
        continue;
      }
      SetParam($param, $val);
    }
  }
}

/****************************************************************
 * Get the current config values and display them.
 ****************************************************************/
function GetCurrentConfig() {
  global $dbase, $dbaseConfigData, $leagueID, $SID;

  $query = "select * from $dbaseConfigData where lid='$leagueID' order by grp";
  $result = $dbase->query($query);

  echo "<form action='index.php?sid=$SID&cmd=changeconfig' method=\"POST\">\n";
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
}
?>
