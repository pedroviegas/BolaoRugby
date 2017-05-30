<?php
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 19th March 2004
 * File  : fixturefunctions.php
 ********************************************************/

/********************************************************
 * Get the months as a select option
 ********************************************************/
  function GetMonthsAsSelect($curmonth, $cmd) {
    global $dbase, $dbaseStandings, $leagueID, $SID, $Months;

    $sel = "";
    // If all months is true then show all the months, else just show the weeks when the game is
    // in the past
    $query = "SELECT DISTINCT monthdate FROM $dbaseStandings WHERE lid='$leagueID' ORDER BY monthdate DESC";
    $res = $dbase->query($query);
    $url = "index.php?cmd=$cmd&sid=$SID&month=";
  ?>
  <!-- <form id="onsel" name="onsel" method="GET" action="<?php echo $url; ?>">  -->
  <select onChange="document.location.href='<?php echo $url;?>'+document.getElementById('onselm').value" id="onselm" name="onselm">
  <?php 
    while ($line = mysql_fetch_array($res)) {
      $date = $line["monthdate"];
      $month = $date % 12;
      if ($month < 1 || $month > 12) {
        continue;
      }
      if ($month < 10) {
        $month = "0".$month;
      }
      $year = floor($date/12);

      if (isset($curmonth) && $curmonth == $date) {
        $sel = "SELECTED";
      }
      echo "<option value='$date' $sel>$Months[$month] $year</option>\r\n";
      $sel = "";
    }
  ?>
  </select>
  <!-- </form> -->
  <?php
  }

/********************************************************
 * Get the configured weeks as a select option.
 ********************************************************/
  function GetWeeksAsSelect($curweek, $cmd, $allWeeks) {
    global $dbase, $dbaseMatchData, $leagueID, $SID;

    $sel = "";
    // If all weeks is true then show all the weeks, else just show the weeks when the game is
    // in the past
    $query = "SELECT DISTINCT week FROM $dbaseMatchData WHERE lid='$leagueID' ORDER BY week DESC";
    if ($allWeeks == false) {
      $today = date("Y-m-d H:i:s");
      $query = "SELECT DISTINCT week FROM $dbaseMatchData WHERE lid='$leagueID' AND matchdate<'$today' ORDER BY week DESC";
    }
    $res = $dbase->query($query);
    $url = "index.php?cmd=$cmd&sid=$SID&week=";
  ?>
  <!-- <form id="onsel" name="onsel" method="GET" action="<?php echo $url; ?>">  -->
  <select onChange="document.location.href='<?php echo $url;?>'+document.getElementById('onselc').value" id="onselc" name="onselc">
  <?php 
    while ($line = mysql_fetch_array($res)) {
      $week = $line["week"];
      if (isset($curweek) && $week == $curweek) {
        $sel = "SELECTED";
      }
      echo "<option value='$week' $sel>$week</option>\r\n";
      $sel = "";
    }
  ?>
  </select>
  <!-- </form> -->
  <?php
  }

/********************************************************
 * Add a new competition
 ********************************************************/
function AddCompetition() {
  global $dbase, $dbaseComps, $leagueID;
  $name = $_POST["COMPNAME"];
  $dbase->query("INSERT INTO $dbaseComps (lid,name) values ('$leagueID','$name')");
}

/********************************************************
 * Show the form for adding a new competition
 ********************************************************/
function ShowAddCompetition() {
  global $Txt_Competition_Name,$Txt_Add_Competition,$Txt_Add;
// Each league is a competition.
?>
<form method="POST" action="index.php?cmd=addcomp&sid=<?php echo $SID; ?>">
<table class="CENTER">
<tr>
<td class="TBLHEAD">
<font class="TBLHEAD">
<?php echo $Txt_Add_Competition;?>
</font>
</td>
</tr>
<tr>
<td class="TBLROW">
<font class="TBLROW">
<input type="text" name="COMPNAME"><?php echo $Txt_Competition_Name;?>
</font>
</td>
</tr>
<tr>
<td class="TBLROW" align="CENTER">
<font class="TBLROW">
<input type="submit" name="SUBMIT" value="<?php echo $Txt_Add;?>">
</font>
</td>
</tr>
</table>
</form>
<?php
}

/********************************************************
 * Show the competitions
 ********************************************************/
function ShowCompetitions() {
  global $dbase, $dbaseComps, $leagueID, $Txt_Competitions;

  $res = $dbase->query("SELECT * FROM $dbaseComps WHERE lid='$leagueID' ORDER BY name");
// Each league is a competition.
?>
<table class="CENTER">
<tr>
<td class="TBLHEAD" colspan="2">
<font class="TBLHEAD">
<?php echo $Txt_Competitions;?>
</font>
</td>
</tr>
<?php
while ($line = mysql_fetch_array($res)) {
  $cid = $line["comp"];
  $name = $line["name"];
?>
<tr>
<td class="TBLROW">
<font class="TBLROW">
<?php echo $cid; ?>
</font>
</td>
<td class="TBLROW" width="100%">
<font class="TBLROW">
<?php echo $name; ?>
</font>
</td>
</tr>
<?php
}
?>
</table>
<?php
}

/*******************************************************
 * Manage competitions.
 * User should be able to create/delete competitions.
 * Games can be assigned to compeitions.
 *******************************************************/
function ManageCompetitions() {
  ShowAddCompetition();
  ShowCompetitions();
}

/*******************************************************
 * Add a fixture
 *******************************************************/
function AddFixture() {
  global $_POST, $dbase, $dbaseMatchData, $leagueID;

  $week = $_POST["WEEK"];
  $date = $_POST["DATE"];
  $time = $_POST["TIME"];
  if ($time == "") {
    $time = "00:00:00";
  }
  $datetime="$date $time";

  // Now allow for timezone offset.
  $datetime = RevTimeZoneOffsetNo24Hour($datetime);

  $hometeam = addslashes($_POST["HOMETEAM"]);
  $awayteam = addslashes($_POST["AWAYTEAM"]);
  $bonus = addslashes($_POST["BONUS"]);

  if ($hometeam == "") {
    ErrorRedir("The fixture was not correct, please enter a hometeam.","index.php?sid=$SID&cmd=enterfixture");
    exit;
  }

  if ($awayteam == "") {
    ErrorRedir("The fixture was not correct, please enter an awayteam.","index.php?sid=$SID&cmd=enterfixture");
    exit;
  }

  // If the post is a success, go to the adminenterresult page.
  $query = "insert into $dbaseMatchData (lid,matchdate,hometeam,awayteam,bonuspoints,week) VALUES ('$leagueID','$datetime','$hometeam','$awayteam','$bonus', '$week')";
  $result = $dbase->query($query);

  // Log the addition
  LogMsg("Added fixture $date $time $hometeam $awayteam.\n$query");
}

/************************************************************
 * Post the result to the database.
 ************************************************************/
function PostResult() {
  global $_POST,$dbase, $dbaseMatchData, $leagueID;

  $mid = $_POST["MID"];
  $homescore = $_POST["HOMESCORE"];
  $awayscore = $_POST["AWAYSCORE"];
  $homepen = "";
  $awaypen = "";
  if (array_key_exists("HOMEPEN", $_POST)) {
    $homepen = $_POST["HOMEPEN"];
    $awaypen = $_POST["AWAYPEN"];
  }
  $query = "update $dbaseMatchData SET homescore='$homescore', awayscore='$awayscore', homepen='$homepen', awaypen='$awaypen' where matchid = '$mid' and lid='$leagueID'";
  $result = $dbase->query($query);

  // Update the standings table.
  UpdateStandingsTable();
}

/*****************************************************************
 * Add multiple fixtures to the league
 *****************************************************************/
function AddMultipleFixtures() {
  global $dbase, $NumMultFixts, $_POST, $dbaseMatchData, $leagueID;

  for ($i=0; $i<$NumMultFixts; $i++) {

    $week = $_POST["WEEK$i"];
    $date = $_POST["DATE$i"];
    $time = $_POST["TIME$i"];
    if ($time == "") {
      $time = "00:00:00";
    }
    $datetime="$date $time";
    // Now allow for timezone offset.
    $datetime = RevTimeZoneOffsetNo24Hour($datetime);

    $hometeam = $_POST["HOMETEAM$i"];
    $awayteam = $_POST["AWAYTEAM$i"];
    $bonusgame = 'N';
    $bonuspoints = $_POST["BONUSPOINTS$i"];
    if ($bonuspoints != "") {
      $bonusgame = 'Y';
    }

    if ($datetime == "" or $hometeam == "" or $awayteam == "") {
      continue;
    }

    // If the post is a success, go to the adminenterresult page.
    $query = "insert into $dbaseMatchData (lid,matchdate,hometeam,awayteam,bonuspoints,week) VALUES ($leagueID,\"$datetime\",\"$hometeam\",\"$awayteam\",'$bonuspoints','$week')";
    $result = $dbase->query($query);

    // Log the addition
    LogMsg("Added fixture $date $time $hometeam $awayteam.\n$query");
  }
}

/***********************************************************
 * Delete the fixtures
 * This will also delete all the predictions and reset the
 * standings table.
 ***********************************************************/
function DeleteFixture() {
  global $dbase,$dbasePredictionData,$dbaseMatchData,$leagueID;

  $matchid = $_GET["matchid"];

  // Remove the fixture from the match data.
  $query = "delete from $dbaseMatchData where lid='$leagueID' and matchid='$matchid'";
  $result = $dbase->query($query);

  // Log the changes
  $rows = mysql_affected_rows();
  LogMsg("Removed fixture.\nUsed $query.\n$rows affected.");
  
  // Remove the fixture from the users prediction data
  $query = "delete from $dbasePredictionData where lid='$leagueID' and matchid='$matchid'";
  $result = $dbase->query($query);

  // Log the changes
  $rows = mysql_affected_rows();
  LogMsg("Removed user predictions.\nUsed $query.\n$rows affected.");
}

/***********************************************************
 * Delete all the fixtures
 * This will also delete all the predictions and empty the
 * standings table.
 ***********************************************************/
function DeleteAllFixtures() {
  global $User, $dbase, $SID, $dbaseMatchData, $dbaseMonthlyWinner;
  global $dbasePredictionData, $dbaseStandings, $leagueID;

  logMsg($User->getUsername()." deleted all the fixtures.");

  $query = "delete from $dbaseMatchData where lid='$leagueID'";
  $dbase->query($query);
  
  $query = "delete from $dbasePredictionData where lid='$leagueID'";
  $dbase->query($query);
  
  $query = "delete from $dbaseStandings where lid='$leagueID'";
  $dbase->query($query);
  
  $query = "delete from $dbaseMonthlyWinner where lid='$leagueID'";
  $dbase->query($query);
}

/***********************************************************
 * Modify a single fixture
 ***********************************************************/
function ModifyFixture() {
  global $_POST, $dbase, $SID, $dbaseMatchData, $leagueID;

  $date = $_POST["DATE"];
  $time = $_POST["TIME"];
  if ($time == "") {
    $time = "00:00:00";
  }
  $datetime="$date $time";

  // Now allow for timezone offset.
  $datetime = RevTimeZoneOffset($datetime);

  $hometeam = $_POST["HOMETEAM"];
  $awayteam = $_POST["AWAYTEAM"];
  $bonus = $_POST["BONUS"];
  $matchid = $_POST["MATCHID"];
  $week = $_POST["WEEK"];

  if ($hometeam == "") {
    ErrorRedir("The fixture was not correct, please enter a hometeam.","index.php?sid=$SID&cmd=enterfixture");
    exit;
  }

  if ($awayteam == "") {
    ErrorRedir("The fixture was not correct, please enter an awayteam.","index.php?sid=$SID&cmd=enterfixture");
    exit;
  }

  // change the fixture in the match data.
  $query = "update $dbaseMatchData set matchdate=\"$datetime\", hometeam=\"$hometeam\", awayteam=\"$awayteam\", bonuspoints='$bonus', week='$week' WHERE matchid=\"$matchid\" AND lid='$leagueID'";
  $result = $dbase->query($query);

  // Log the changes
  $rows = mysql_affected_rows();
  LogMsg("Modified fixture.\nUsed $query.\n$rows affected.");
  
  /* Redirect browser to PHP web site */
  forward("index.php?sid=$SID&cmd=enterfixture"); 
}

/*******************************************************
* Get all the results with no values that are passed 
* the current time + 1.5
*******************************************************/
function GetOutstandingResults() {
  // Array holding the current fixtures.
  global $dbaseMatchData, $Enter_Result, $SID, $leagueID, $dbase;
  global $Date, $Home, $Away, $Txt_Current_Fixtures, $Txt_No_Outstanding_Fixtures;

  // Add 90m mins to the kickoff time.
  $offs = 60*90;
  $date = date("Y-m-d H:i:s",time()-$offs);
   
  $matchquery = "SELECT * FROM $dbaseMatchData WHERE lid='$leagueID' AND matchdate<=\"$date\" AND homescore IS NULL ORDER BY matchdate DESC";
  $matchresult = $dbase->query($matchquery);
?>
  <form method="POST" action="index.php?sid=<?php echo $SID; ?>&cmd=eorv">
  <table class="STANDTB">
  <tr>
  <td align="center" class="TBLHEAD" colspan="6"><font class="TBLHEAD"><?php echo $Txt_Current_Fixtures; ?></font></td>
  </tr>
  <tr>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Date; ?></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Home; ?></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Away; ?></font></td>
<?php
  if ($dbase->getNumberOfRows() < 1) {
?>
      <tr>
      <td colspan="6" align="CENTER" class="TBLROW">
      <font class="TBLROW">
      <?php echo  $Txt_No_Outstanding_Fixtures; ?>
      </a>
      </font>
      </td>
      </tr>
<?php   
  } else {
    $count = 0;
    while ($matchdata = mysql_fetch_array($matchresult,MYSQL_ASSOC)) {
      $matchdate = $matchdata["matchdate"];
      $matchid = $matchdata["matchid"];
      $hometeam = stripslashes($matchdata["hometeam"]);
      $awayteam = stripslashes($matchdata["awayteam"]);
      echo "\n<tr>\n";
      echo "<td class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "<a href=\"index.php?sid=$SID&cmd=postresult&matchdate=$matchdate&hometeam=".EncodeParam($hometeam)."&awayteam=".EncodeParam($awayteam)."\">";
      echo convertDatetimeToScreenDate($matchdate);
      echo "</a>\n";
      echo "</font>\n";
      echo "</td>\n";
      echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "$hometeam\n";
      echo "</font>\n";
      echo "</td>\n";
      echo "<td align=\"CENTER\" class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "<input type=\"HIDDEN\" name=\"MID$count\" value=\"$matchid\">\n";
      echo "<input type=\"text\" size=\"2\" name=\"GF$count\">\n";
      echo "</font>\n";
      echo "</td>\n";
      echo "<td align=\"CENTER\" class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "v\n";
      echo "</font>\n";
      echo "</td>\n";
      echo "<td class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "<input type=\"text\" size=\"2\" name=\"GA$count\">\n";
      echo "</font>\n";
      echo "</td>\n";
      echo "<td class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "$awayteam\n";
      echo "</font>\n";
      echo "</td>\n";
      echo "</tr>\n";
      $count++;
    }
  }
?>
  <tr>
  <td class="TBLROW" colspan="6" align="CENTER">
  <font class="TBLROW">
<?php
  if ($dbase->getNumberOfRows() > 0) {
?>
  <input type="HIDDEN" name="COUNT" value="<?php echo $count; ?>">
  <input type="submit" name="Submit" value="<?php echo $Enter_Result; ?>">
<?php } ?>
  </font>
  </td>
  </table>
  </form>
<?php
}

  /****************************************************************
   * Get and display the results in a table format.
   ****************************************************************/
  function GetResults() {
    global $dbaseMatchData, $SID, $leagueID, $Txt_Bonus_Points, $_GET;
    global $P, $W, $POS, $D, $D, $L, $Away, $Home, $F, $A, $GD, $User_Name,$PTS;
    global $dbase, $Txt_v,$Date,$Result, $FixturesResults, $Txt_Week;

    $week = GetThisWeek();
    if (array_key_exists("week",$_GET)) {
      $week = $_GET["week"];
    }

    // If we are showing by week
    $userquery = "SELECT * FROM $dbaseMatchData WHERE lid='$leagueID' AND week='$week' ORDER BY matchdate";
    $userresult = $dbase->query($userquery);
    // Display the username as a header.
?>
    <table class="STANDTB">
    <tr>
    <td class="TBLHEAD" colspan="7" align="center"><font class="TBLHEAD"><?php echo $FixturesResults;?><small> [<?php echo "$Txt_Week"; echo GetWeeksAsSelect($week, "matchres",true);?>]</small></font></td>
    </tr>
    <tr>
    <td width="80" align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Date;?></font></td>
    <td width="150" align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Home;?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLROW">G</font></td>
    <td align="center" class="TBLHEAD"><font class="TBLROW"><?php echo $Txt_v;?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLROW">G</font></td>
    <td width="150" align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Away;?></font></td>
    <td width="60" align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Txt_Bonus_Points;?></font></td>
    </tr>
<?php
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      // For each user display all their predictions.
      // against the actual result.
      $matchid = $userline["matchid"];
      $hometeam = stripslashes($userline["hometeam"]);
      $awayteam = stripslashes($userline["awayteam"]);
      $homescore = $userline["homescore"];
      $homepen = $userline["homepen"];
      if ($homescore == null) {
        $homescore = "&nbsp;";
      }
      $awayscore = $userline["awayscore"];
      $awaypen = $userline["awaypen"];
      if ($awayscore == null) {
        $awayscore = "&nbsp;";
      }
      $date = $userline["matchdate"];
      $bonus = $userline["bonuspoints"];
      $gametype = $userline["gametype"];
      $week = $userline["week"];
      $datetext = GetDateFromDatetime($date);

      // Knockout game specific
      if ($homescore == $awayscore && $gametype != 'L') {
          $homescore = "$homescore [$homepen]";
          $awayscore = "$awayscore [$awaypen]";
      }
?>
      <tr>
      <td class="TBLROW"><font class="TBLROW" nowrap>
        <a href='index.php?sid=<?php echo "$SID&cmd=matchpreds&matchid=$matchid&date=$date"; ?>'>
          <small><?php echo $datetext; ?></small></a></font></td>
      <td class="TBLROW"><font class="TBLROW"><?php echo $hometeam; ?></font></td>
      <td align="CENTER" class="TBLROW"><font class="TBLROW"><?php echo $homescore; ?></font></td>
      <td align="CENTER" class="TBLROW"><font class="TBLROW"><?php echo $Txt_v; ?></font></td>
      <td align="CENTER" class="TBLROW"><font class="TBLROW"><?php echo $awayscore; ?></font></td>
      <td class="TBLROW"><font class="TBLROW"><?php echo $awayteam; ?></font></td>
<?php if ($bonus == 0) $bonus = "";?>
      <td class="TBLROW"><font class="TBLROW"><?php echo $bonus; ?></font></td>
      </tr>
<?php
    }
    echo "</table>\n";
  }

  /****************************************************************
   * Allow the admin to modify all the fixtures in one go.
   ****************************************************************/
  function ModifyAllFixtures() {
    global $NumMultFixts, $SID, $leagueID, $dbase, $dbaseMatchData;
    global $Txt_Change_Fixtures, $Txt_Reset, $Txt_Fixture_Administration, $Txt_Week;
    global $Date, $Txt_Time, $Txt_24hr, $Txt_YYYYMMDD, $Txt_HHMMSS;
    global $Txt_Home_Team, $Txt_Away_Team, $Txt_Bonus_Points;
?>
<table class="STANDTB">
<tr>
<td id="forms" valign="top">
  <form id="addform" name="AddFixture" method="POST" action="index.php?sid=<?php echo $SID; ?>&cmd=modmultfixt">
  <table class="STANDTB">
  <tr>
  <td colspan="7" align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Fixture_Administration;?>
  </font>
  </td>
  </tr>

  <tr>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Week;?>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Date; ?><br><small><?php echo $Txt_YYYYMMDD; ?></small>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Time;?> <small><?php echo $Txt_24hr;?></small><br><small><?php echo $Txt_HHMMSS;?></small>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Home_Team; ?>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Away_Team; ?>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Bonus_Points; ?>
  </font>
  </td>
  </tr>
  <!-- Content Rows -->
<?php 
  $query = "SELECT * FROM $dbaseMatchData WHERE lid='$leagueID' ORDER BY matchdate";
  $result = $dbase->query($query);
  $numFixts = $dbase->getNumberOfRows();
  while($fixt = mysql_fetch_array($result)) {
    $i = $fixt["matchid"];
    $week = $fixt["week"];
    $matchdate = $fixt["matchdate"];
    $time = GetTimeFromDateTime($matchdate);
    $date = GetRawDateFromDateTime($matchdate);
    $hometeam = $fixt["hometeam"];
    $awayteam = $fixt["awayteam"];
    $bonus = $fixt["bonuspoints"];
?>
  <tr>
  <td class="TBLROW" align="CENTER">
  <font id="formcount" class="TBLROW">
  <?php echo $i; ?>
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formweek" class="TBLROW">
  <input type="hidden" name="MATCHID<?php echo $i; ?>" value="<?php echo $i;?>">
  <input type="text" name="WEEK<?php echo $i; ?>" value="<?php echo $week;?>" size="2">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formdate" class="TBLROW">
  <input type="text" name="DATE<?php echo $i; ?>" value="<?php echo $date;?>" size="10">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formtime" class="TBLROW">
  <input type="text" name="TIME<?php echo $i; ?>" value="<?php echo $time;?>" size="8">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formhome" class="TBLROW">
  <input type="text" name="HOMETEAM<?php echo $i; ?>" value="<?php echo $hometeam;?>" size="15">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formaway" class="TBLROW">
  <input type="text" name="AWAYTEAM<?php echo $i; ?>" value="<?php echo $awayteam;?>" size="15">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formbonus" class="TBLROW">
  <input type="text" name="BONUSPOINTS<?php echo $i; ?>" value="<?php echo $bonus;?>" size="2">
  </font>
  </td>
</tr>
<?php
  }
?>
<tr>
  <td id="formsubmit" align="center" class="TBLROW" colspan="7">
  <input type="hidden" name="numfixts" value="<?php echo $numfixts;?>">
  <input id="formsubmitinput" type="submit" name="CHANGE" value="<?php echo $Txt_Change_Fixtures;?>">
  <input id="formresetinput" onclick="addfixture()" type="reset" name="RESET" value="<?php echo $Txt_Reset;?>">
  </td>
  </tr>
  </table>
  </form>
</td>
</tr>
</table>
<?php
  }

/*****************************************************************
 * Modify multiple fixtures in the league
 *****************************************************************/
function ModifyMultipleFixtures() {
  global $dbase, $_POST, $dbaseMatchData, $leagueID;

  $numFixts = $_POST["numfixts"];

  while(list($key, $val) = each($_POST)) {

    if (strstr($key,"MATCHID") == false) {
      continue;
    }
    
    $mid = substr($key,7);
    $week = $_POST["WEEK$mid"];
    $date = $_POST["DATE$mid"];
    $time = $_POST["TIME$mid"];
    if ($time == "") {
      $time = "00:00:00";
    }
    $datetime="$date $time";
    // Now allow for timezone offset.
    $datetime = RevTimeZoneOffsetNo24Hour($datetime);

    $hometeam = $_POST["HOMETEAM$mid"];
    $awayteam = $_POST["AWAYTEAM$mid"];
    $bonusgame = 'N';
    $bonuspoints = $_POST["BONUSPOINTS$mid"];
    if ($bonuspoints != "") {
      $bonusgame = 'Y';
    }

    if ($datetime == "" or $hometeam == "" or $awayteam == "") {
      continue;
    }

    // If the post is a success, go to the adminenterresult page.
    $query = "UPDATE $dbaseMatchData set matchdate='$datetime',hometeam='$hometeam',awayteam='$awayteam',bonuspoints='$bonuspoints',week='$week' WHERE lid='$leagueID' and matchid='$mid'";
    $result = $dbase->query($query);

    // Log the addition
    LogMsg("Added fixture $date $time $hometeam $awayteam.\n$query");
  }
}

function OutstandingFixturesForWeek($week) {
  global $dbase, $leagueID, $dbaseMatchData;
  $now = getTodaysDate();
  $query = "SELECT * FROM $dbaseMatchData WHERE week='$week' and lid='$leagueID' AND matchdate >= '$now'";
  $res = $dbase->query($query);

  if ($dbase->getNumberOfRows() > 0) {
    return true;
  }

  // Check for outstanding results
  $query = "SELECT * FROM $dbaseMatchData WHERE week='$week' and lid='$leagueID' AND homescore is null";
  $res = $dbase->query($query);

  if ($dbase->getNumberOfRows() > 0) {
    return true;
  }
  return false;
}
?>
