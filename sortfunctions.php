<?php 
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 9th December 2001
 * File  : sortfunctions.php
 ********************************************************/

require_once "gamestatsclass.php";
require_once "datefunctions.php";
require_once "autopredict.php";
require_once "statsfunctions.php";
require_once ("lang/lang.english.php");
require_once ("lang/".GetLangFile());

  /////////////////////////////////////////////
  // Sorting functions for the prediction data.
  // These functions also cause the output to
  // be written.
  /////////////////////////////////////////////
  
  /****************************************************
   * Search the Match table for the next game.
   ***************************************************/
  function getNextGame() {
    global $dbaseMatchData, $SID, $Next_Match,$No_Matches_Scheduled,$leagueID;
    global $dbase;

    $todaysdate = date("Y-m-d H:i:s");
    $tz = date("T");
    
    // Search for the next date in the dbase.
    // If the matches are ordered, then the first should be the next game.
    $query = "select * from $dbaseMatchData where lid='$leagueID' and matchdate>='$todaysdate' order by matchdate";
    $result = $dbase->query($query);

    $count = mysql_num_rows($result);
    if ($count == 0) {
      $nextmatch = "<b>$No_Matches_Scheduled</b>";
    } else {
      $line = mysql_fetch_array($result, MYSQL_ASSOC);
      $matchid = $line["matchid"];
      $matchdate = $line["matchdate"];
      $textdate = convertDatetimeToScreenDate($matchdate);
      $hometeam = stripslashes($line["hometeam"]);
      $awayteam = stripslashes($line["awayteam"]);
      $nextmatch = "<b>$Next_Match: <a href=\"index.php?sid=$SID&cmd=matchpreds&matchid=$matchid&date=$matchdate\">$hometeam v $awayteam </a></b> $textdate";
    }

    return $nextmatch;
  }

  /**********************************************************
   * Get and Display the user predictions.
   * Display the predictions from the users prediction table
   * entries and also any games from the MatchTable that
   * have no entry in the Users Prediction data.
   **********************************************************/
  function GetUserMissingPredictions($user) {
    global $dbasePredictionData, $dbaseMatchData, $reverseUserPredictions, $SID, $User;
    global $P, $W, $POS, $D, $D, $L, $Away, $Home, $F, $A, $GD, $User_Name,$PTS,$Date;
    global $dbase, $Txt_v, $Predictions, $Predict, $leagueID,$Result, $Predictions_For;
    global $Txt_Week;

    // At this point this is only used in the table header.
    //$date = GetDateFromDatetime(date("Y-m-d"));
    $now = date("YmdHis");
    
    // Select the fixtures from both tables. 
    $userquery = "SELECT a.matchdate,a.hometeam,a.awayteam, a.matchid, a.bonuspoints,week FROM $dbaseMatchData AS a LEFT JOIN $dbasePredictionData AS b ON a.matchid=b.matchid AND b.userid='$user' and a.lid=b.lid WHERE a.matchdate > '$now' AND a.lid='$leagueID' AND b.homescore IS NULL ORDER BY a.matchdate";
    if ($reverseUserPredictions == "TRUE") {
      $userquery = "$userquery desc";
    }
    $userresult = $dbase->query($userquery);

    // Display the username as a header.
?>
    <form method="POST" action="index.php?sid=<?php echo $SID; ?>&cmd=changepreds">
    <table class="STANDTB">
    <tr>
    <td class="TBLHEAD" colspan="8" align="center">
    <font class="TBLHEAD">
    <?php echo "$Predictions_For $User->username"?>
    </font>
    </td>
    </tr>
    <tr>
    <td colspan="8" class="TBLROW" align="CENTER">
    <input type="SUBMIT" name="$Predict" value="Predict">
    </td>
    </tr>
    <tr>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Txt_Week; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Date; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Home; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $F; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD">&nbsp;</font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $A; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Away; ?></font></td>
    </tr>
<?php
    $count = 1;
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_BOTH)) {
      // For each user display all their predictions.
      // against the actual result.
      $hometeam = stripslashes($userline["hometeam"]);
      $awayteam = stripslashes($userline["awayteam"]);
      $matchid = $userline["matchid"];
      $bonus = $userline["bonuspoints"];
      $date = $userline["matchdate"];
      $week = $userline["week"];
      $datestr = GetDateFromDateTime($date);
      $timestr = GetTimeFromDateTime($date);

      echo "<tr>\n";
      echo "<td class='TBLROW'><font class='TBLROW'>$week</font></td>\n";

      echo "<td class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "<a href=\"index.php?sid=$SID&cmd=matchpreds&matchid=$matchid&date=$date\">$datestr</a>\n";
      echo "<br><small>$timestr</small>\n";
      echo "</font>\n";
      echo "</td>\n";

      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>\n";

?>
      <td class='TBLROW' align='CENTER'>
      <font class="TBLROW">
      <input type="HIDDEN" name="MATCHID<?php echo $count; ?>" value="<?php echo $matchid; ?>">
      <input type="HIDDEN" name="MATCHDATE<?php echo $count; ?>" value="<?php echo $date; ?>">
      <br><input type="TEXT" size="1" name="GFOR<?php echo $count;?>">
      </font>
      </td>
      <td class="TBLROW" align="CENTER"><font class="TBLROW">v</font></td>
      <td class="TBLROW" align="CENTER"><font class="TBLROW"><br><input type="TEXT" size="1" name="GAGAINST<?php echo $count; ?>"></font></td>
      <td class="TBLROW"><font class="TBLROW"><?php echo $awayteam; ?></font></td>
<?php

      echo "</tr>\n";
      $count++;
    }
    echo "<tr>\n";
    echo "<td colspan=\"8\" class=\"TBLROW\" align=\"CENTER\">\n";
    echo "<input type=\"SUBMIT\" name=\"$Predict\" value=\"Predict\">\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "<input type=\"HIDDEN\" name=\"NUMROWS\" value=\"$count\">\n";
    echo "</form>\n";
  }

  /**********************************************************
   * Get and Display the user predictions.
   * Display the predictions from the users prediction table
   * entries and also any games from the MatchTable that
   * have no entry in the Users Prediction data.
   **********************************************************/
  function GetUserPredictions($user) {
    global $dbasePredictionData, $dbaseMatchData, $reverseUserPredictions, $SID, $User, $Predictions, $Predict, $leagueID;
    global $P, $W, $POS, $D, $D, $L, $Away, $Home, $F, $A, $GD, $User_Name,$PTS,$Date,$Result, $Predictions_For;
    global $dbase, $Txt_v, $Txt_Bonus, $Txt_Week, $Predictions, $_GET;

    // At this point this is only used in the table header.
    $date = GetDateFromDatetime(date("Y-m-d"));
    $week = GetThisWeek();
    if (array_key_exists("week", $_GET)) {
      $week = $_GET["week"];
    }
    
    // Select the fixtures from both tables. 
    $userquery = "SELECT $dbaseMatchData.week,$dbaseMatchData.matchdate,$dbaseMatchData.hometeam,$dbaseMatchData.awayteam,$dbaseMatchData.homescore as mhs,$dbaseMatchData.awayscore as mas,$dbasePredictionData.homescore as phs,$dbasePredictionData.awayscore as pas, $dbaseMatchData.matchid, bonuspoints FROM $dbaseMatchData LEFT JOIN $dbasePredictionData ON $dbasePredictionData.matchid=$dbaseMatchData.matchid AND userid = \"$user\" AND $dbaseMatchData.lid=$dbasePredictionData.lid WHERE week='$week' AND $dbaseMatchData.lid='$leagueID' ORDER BY week, $dbaseMatchData.matchdate";
    if ($reverseUserPredictions == "TRUE") {
      $userquery = "$userquery desc";
    }
    $userresult = $dbase->query($userquery);

    // Display the username as a header.
?>
    <form method="POST" action="index.php?week=<?php echo $week;?>&sid=<?php echo $SID; ?>&cmd=changepreds">
    <table class="STANDTB">
    <tr>
    <td class="TBLHEAD" colspan="10" align="center">
    <font class="TBLHEAD">
    <?php echo "$Predictions_For $User->username "?>
    <small> [<?php echo "$Txt_Week"; echo GetWeeksAsSelect($week, "mypreds", true);?>]</small>
    </font>
    </td>
    </tr>
    <tr>
    <td colspan="10" class="TBLROW" align="CENTER">
    <input type="SUBMIT" name="$Predict" value="Predict">
    </td>
    </tr>
    <tr>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Txt_Week; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Date; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Home; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $F; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD">&nbsp;</font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $A; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Away; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Result; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $PTS; ?></font></td>
    <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Txt_Bonus; ?></font></td>
    </tr>
<?php
    $count = 1;
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_BOTH)) {
      // For each user display all their predictions.
      // against the actual result.
      $hometeam = stripslashes($userline["hometeam"]);
      $awayteam = stripslashes($userline["awayteam"]);
      $homeresult = $userline["mhs"];
      $awayresult = $userline["mas"];
      $homescore = $userline["phs"];
      $awayscore = $userline["pas"];
      $matchid = $userline["matchid"];
      $week = $userline["week"];
      $date = $userline["matchdate"];
      $bonus = $userline["bonuspoints"];
      $datestr = GetDateFromDateTime($date);
      $timestr = GetTimeFromDateTime($date);

      echo "<tr>\n";
      echo "<td class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "<small>\n";
      echo $week;
      echo "</small>\n";
      echo "</font>\n";
      echo "</td>\n";
      echo "<td class=\"TBLROW\">\n";
      echo "<font class=\"TBLROW\">\n";
      echo "<a href=\"index.php?sid=$SID&cmd=matchpreds&matchid=$matchid&date=$date\">$datestr</a>\n";
      echo "<br><small>$timestr</small>\n";
      echo "</font>\n";
      echo "</td>\n";

      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>\n";

      // User has not made a prediction
      if ($homescore == null) {
        $homescore = "";
        $awayscore = "";
      } 
      if (CompareDatetime($date) > 0) {

        echo "<td class=\"TBLROW\" align=\"CENTER\">\n";
        echo "<font class=\"TBLROW\">\n";
        echo "<input type=\"HIDDEN\" name=\"MATCHID$count\" value=\"$matchid\">\n";
        echo "<input type=\"HIDDEN\" name=\"MATCHDATE$count\" value=\"$date\">\n";
        echo "$homescore<br><input type=\"TEXT\" size=\"1\" name=\"GFOR$count\" value=\"$homescore\">\n";
        echo "</font>\n";
        echo "</td>\n";

        echo "<td class=\"TBLROW\" align=\"CENTER\"><font class=\"TBLROW\">$Txt_v</font></td>\n";
        echo "<td class=\"TBLROW\" align=\"CENTER\"><font class=\"TBLROW\">$awayscore<br><input type=\"TEXT\" size=\"1\" name=\"GAGAINST$count\" value=\"$awayscore\"></font></td>\n";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>\n";
        echo "<td class=\"TBLROW\" align=\"CENTER\">\n";
        echo "<font class=\"TBLROW\">\n";
        echo "-\n";
        echo "</font>\n";
        echo "</td>\n";
        echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\"></font></td>\n";
      } else {
        $points = 0;
        if ($homescore != "" and $homeresult != "") {
          $points = GameStats::CalculatePoints($homescore, $awayscore, $homeresult, $awayresult, $bonus);
        }
        echo "<td class=\"TBLROW\" align=\"CENTER\"><font class=\"TBLROW\">$homescore</font></td>\n";
        echo "<td class=\"TBLROW\" align=\"CENTER\"><font class=\"TBLROW\">$Txt_v</font></td>\n";
        echo "<td class=\"TBLROW\" align=\"CENTER\"><font class=\"TBLROW\">$awayscore</font></td>\n";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>\n";
        echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$homeresult - $awayresult</font></td>\n";
        echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$points</font></td>\n";
      }
      echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$bonus</font></td>\n";

      echo "</tr>\n";
      $count++;
    }
    echo "<tr>\n";
    echo "<td colspan='10' class='TBLROW' align='CENTER'>\n";
    echo "<input type=\"SUBMIT\" name=\"$Predict\" value=\"Predict\">\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "<input type=\"HIDDEN\" name=\"NUMROWS\" value=\"$count\">\n";
    echo "</form>\n";
  }

  /**********************************************************
   * Display the user predictions.
   * Display the predictions from the users prediction table
   * entries and also any games from the MatchTable that
   * have no entry in the Users Prediction data.
   **********************************************************/
  function ShowUserPredictions($userid, $week) {
    global $dbasePredictionData, $dbaseUserData, $dbaseMatchData, $SID, $leagueID,$User;
    global $P, $W, $POS, $D, $D, $L, $Away, $Home, $F, $A, $GD, $User_Name,$PTS,$Date,$Result,$Predictions, $ViewUserPredictions;
    global $dbase, $Txt_v, $Result;
    
    $userquery = "SELECT username FROM $dbaseUserData WHERE lid='$leagueID' and userid=\"$userid\"";
    $unameres = $dbase->query($userquery);
    $usernameln = mysql_fetch_row($unameres);
    $username = stripslashes($usernameln[0]);
    
    $userquery = "SELECT bonuspoints, hometeam,awayteam,$dbasePredictionData.homescore,$dbasePredictionData.awayscore,$dbaseMatchData.matchid,matchdate, $dbaseMatchData.homescore as homeresult, $dbaseMatchData.awayscore as awayresult FROM $dbasePredictionData INNER JOIN $dbaseUserData on $dbasePredictionData.userid=$dbaseUserData.userid and $dbaseUserData.lid=$dbasePredictionData.lid inner join $dbaseMatchData on $dbasePredictionData.matchid=$dbaseMatchData.matchid and $dbaseMatchData.lid=$dbasePredictionData.lid WHERE $dbasePredictionData.userid=\"$userid\" and $dbaseMatchData.lid='$leagueID' order by $dbaseMatchData.matchdate";
    $userresult = $dbase->query($userquery);

    // Display the username as a header.
?>
    <table width='100%' border='0'>
    <tr>
    <td class="TBLHEAD" colspan="8" align="center">
      <font class="TBLHEAD"><?php echo "$Predictions [$username]";?></font>
    </td>
    </tr>
    <tr>
    <td align="center" class="TBLHEAD">
      <font class="TBLHEAD"><?php echo $Date; ?></font>
    </td>
    <td align="center" class="TBLHEAD">
      <font class="TBLHEAD"><?php echo $Home; ?></font>
    </td>
    <td align="center" class="TBLHEAD">
      <font class="TBLHEAD">&nbsp;</font>
    </td>
    <td align="center" class="TBLHEAD">
      <font class="TBLHEAD">&nbsp;</font>
    </td>
    <td align="center" class="TBLHEAD">
      <font class="TBLHEAD">&nbsp;</font>
    </td>
    <td align="center" class="TBLHEAD">
      <font class="TBLHEAD"><?php echo $Away; ?></font>
    </td>
    <td align="center" class="TBLHEAD">
      <font class="TBLHEAD"><?php echo $PTS; ?></font>
    </td>
    <td align="center" class="TBLHEAD">
      <font class="TBLHEAD"><?php echo $Result; ?></font>
    </td>
    </tr>
<?php
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      // For each user display all their predictions.
      // against the actual result.
      $hometeam = stripslashes($userline["hometeam"]);
      $awayteam = stripslashes($userline["awayteam"]);
      $homescore = $userline["homescore"];
      $awayscore = $userline["awayscore"];
      $homeresult = $userline["homeresult"];
      $awayresult = $userline["awayresult"];
      $bonus = $userline["bonuspoints"];
      $points = 0;
      if ($homescore != "" and $homeresult != "") {
        $points = GameStats::CalculatePoints($homescore, $awayscore, $homeresult, $awayresult, $bonus);
      }
      $matchid = $userline["matchid"];
      $date = $userline["matchdate"];
      $datetext = GetDateFromDatetime($date);
      $time = GetTimeFromDatetime($date);

      if ($ViewUserPredictions == "TRUE" || (CompareDatetime($date) < 0) || ($username == $User->username)) {
?>
        <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            <a href='index.php?<?php echo "sid=$SID&cmd=matchpreds&matchid=$matchid&date=$date";?>'><?php echo $datetext;?></a> <?php echo $time;?>
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW"><?php echo $hometeam; ?></font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW"><?php echo $homescore; ?></font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW"><?php echo $Txt_v; ?></font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW"><?php echo $awayscore; ?></font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW"><?php echo $awayteam; ?></font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW"><?php echo $points; ?></font>
        </td>
        <td align="CENTER" class="TBLROW">
          <font class="TBLROW"><?php echo "$homeresult-$awayresult"; ?></font>
        </td>
        </tr>
<?php
      }
    }
    echo "</table>";
  }

  /*****************************************************************
   * Get the predictions for the given date.
   * @param the date for the game in the same format as the dbase.
   *****************************************************************/
  function GetPredictionsForMatch($matchid, $date) {
    global $dbasePredictionData, $dbaseMatchData, $dbaseUserData, $SID, $User, $leagueID;
    global $P, $W, $POS, $D, $D, $L, $Away, $Home, $F, $A, $GD, $User_Name,$PTS,$Date;
    global $Result,$Predictions, $ViewUserPredictions, $dbase;

    $userquery = "select $dbasePredictionData.userid, $dbaseUserData.username, $dbaseMatchData.hometeam, $dbaseMatchData.awayteam, $dbasePredictionData.homescore, $dbasePredictionData.awayscore, $dbaseMatchData.matchdate from $dbasePredictionData inner join $dbaseMatchData on $dbasePredictionData.matchid=$dbaseMatchData.matchid and $dbasePredictionData.lid=$dbaseMatchData.lid inner join $dbaseUserData on $dbasePredictionData.userid=$dbaseUserData.userid and $dbasePredictionData.lid=$dbaseUserData.lid where $dbaseMatchData.matchid=\"$matchid\" and $dbaseMatchData.lid='$leagueID' order by matchdate";

    $userresult = $dbase->query($userquery);

    // Display the username as a header.
    $datetext = convertDatetimeToScreenDate($date);
    echo "<table class=\"STANDTB\">";
    echo "<tr>";
    echo "<td class=\"TBLHEAD\" colspan=\"7\" align=\"center\"><font class=\"TBLHEAD\">$Predictions [$datetext]</font></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">$Date</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">$User_Name</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">$Home</font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"></font></td>";
    echo "<td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\">$Away</font></td>";
    echo "</tr>";
    // First loop. Used to get all the users.
    while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      // For each user display all their predictions.
      // against the actual result.
      $userid = $userline["userid"];
      $username = stripslashes($userline["username"]);
      $hometeam = stripslashes($userline["hometeam"]);
      $awayteam = stripslashes($userline["awayteam"]);
      $homescore = $userline["homescore"];
      $awayscore = $userline["awayscore"];
      $date = $userline["matchdate"];

      // The date is in datetime format YYYY-MM-DD HH:MM:SS , pull off date
      $datetext = GetDateFromDatetime($date);

      if ($ViewUserPredictions == "TRUE" || (CompareDatetime($date) < 0) || ($username == $User->username)) {
        echo "<tr>";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$datetext</font></td>";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"index.php?sid=$SID&cmd=userpreds&user=$userid\">$username</a></font></td>";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>";
        echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$homescore</font></td>";
        echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
        echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">$awayscore</font></td>";
        echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>";
        echo "</tr>";
      }
    }
    echo "</table>";
  }

  /***********************************************************************
   * Encode parameters.
   * The parameters passed as GET values cannot contain spaces. This
   * must be replaced with a + .
   ***********************************************************************/
  function EncodeParam($param) {
    return str_replace(" ","+",$param);
  }

  /*****************************************************************
   * Forward to the given address
   * @url the address to go to.
   *****************************************************************/
  function forward($url) {
    /* Redirect browser */
    header("Location: $url"); 
    /* Make sure that code below does not get executed when we redirect. */
    exit; 
  }
  
/*********************************************************
 * Determine the language file to use.
 * If the user is logged in, use their language selection
 * else use the default from systemvars.php $languageFile.
 ********************************************************/
function GetLangFile() {
  global $languageFile,$User;

  if ($User->loggedIn == TRUE && $User->lang != "") {
    return "lang.".$User->lang.".php";
  }
  return $languageFile;
}

/*********************************************************
 * Determine the available languages and offer to the user.
 * The available languages are determined by looking in
 * the lang directory.
 ********************************************************/
function GetLanguageOptions($currlang) {
  global $languageFile;
  $f = opendir("lang");
  if ($f == FALSE) {
    // TODO Change this to use real error handling.
    echo "<option>Cant open lang dir</option>";
    exit;
  }

  if (strlen($currlang) == 0) {
    $currlang = StripLang($languageFile);
  }

  $arr = array();
  
  $file = readdir($f); 
  while (false != $file) { 
    if ($file != "." && $file != ".." && $file != "CVS") { 
      $nm = StripLang($file);
      $arr[$nm] = $nm;
    } 
    $file = readdir($f); 
  }
  while(list($key,$val) = each($arr)) {
    $selected = "";
    if ($key == $currlang) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    echo "<option $selected>".$key."</option>\n"; 
  }
  closedir($f);
}

/*********************************************************
 * Strip the lang. from the start and the .php from the
 * end.
 ********************************************************/
function StripLang($fn) {
  return substr($fn,5,strrpos($fn,".php")-5);
}

/*******************************************************
* Function to create an indexed array from the database
* table holding the fixtures.
*******************************************************/
function GetCurrentFixtures($modify) {
  // Array holding the current fixtures.
  global $dbaseMatchData, $leagueID, $SID, $dbase;
  global $Date, $Home, $Txt_Time, $Away, $Txt_Week;
  global $Txt_Delete_Fixture,$Txt_Are_You_Sure_Fixt_Delete,$Txt_Modify_Fixture, $Delete, $Txt_Modify;

  $matchquery = "SELECT * FROM $dbaseMatchData where lid='$leagueID' order by week, matchdate";
  $matchresult = $dbase->query($matchquery);

  $colspan = 10;
  if ($modify == true) {
    $colspan = 11;
  }
?>
  <table class="STANDTB">
  <tr>
  <td align="center" class="TBLHEAD" colspan="<?php echo $colspan; ?>"><font class="TBLHEAD">Current Fixtures</font></td>
  </tr>
  <tr>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Txt_Week; ?></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Date; ?></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Txt_Time; ?></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Home; ?></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">&nbsp;</font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">&nbsp;</font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">&nbsp;</font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><?php echo $Away; ?></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">&nbsp;</font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">&nbsp;</font></td>
<?php
  if ($modify == true) {
?>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD">&nbsp;</font></td>
<?php
  }
?>
  </tr>
<?php
  while ($matchdata = mysql_fetch_array($matchresult,MYSQL_ASSOC)) {
    $week = $matchdata["week"];
    $matchid = $matchdata["matchid"];
    $matchdate = $matchdata["matchdate"];
    $hometeam = $matchdata["hometeam"];
    $awayteam = $matchdata["awayteam"];
    $bonus = $matchdata["bonuspoints"];
    // Get the date and time in user friendly format.
    $oldserverdate = $matchdate;
    $rawdate = GetRawDateFromDateTime($matchdate);
    $date = GetDateFromDateTime($matchdate);
    $time = GetTimeFromDateTime($matchdate);
    $time24hr = GetTimeFromDateTime24hr($matchdate);
    echo "<tr>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">".$week."</font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">".$date."</font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">".$time."</font></td>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$hometeam</font></td>\n";
    echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">".$matchdata["homescore"]."</font></td>\n";
    echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">".$matchdata["awayscore"]."</font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$awayteam</font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">$bonus</font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a onMouseOver=\"top.window.status='$Txt_Delete_Fixture';return true\" href=\"index.php?sid=$SID&cmd=deletefixture&matchid=$matchid\" onclick=\"return confirm('$Txt_Are_You_Sure_Fixt_Delete');\"><small>$Delete</small></a></font></td>\n";
if ($modify == true) {
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a onMouseOver=\"top.window.status='$Txt_Modify_Fixture';return true\" href=\"javascript:void modifyfixture('$matchid','$oldserverdate','$week','$rawdate','$time24hr','".addslashes($hometeam)."','".addslashes($awayteam)."','$bonus', '$SID');\"><small>$Txt_Modify</small></a></font></td>\n";
}
    echo "</tr>\n";
  }
  echo "</table>\n";
}

/*******************************************************
* Function to create an indexed array from the database
* table holding the fixtures.
*******************************************************/
function GetCurrentFixturesForResults() {
  // Array holding the current fixtures.
  global $dbaseMatchData, $dbase, $leagueID, $SID, $Date, $Home, $Away;
  global $Txt_Current_Fixtures;

  $matchquery = "SELECT * FROM $dbaseMatchData where lid='$leagueID' order by matchdate desc";
  $matchresult = $dbase->query($matchquery);
?>
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
  while ($matchdata = mysql_fetch_array($matchresult,MYSQL_ASSOC)) {
    $mid = $matchdata["matchid"];
    $matchdate = $matchdata["matchdate"];
    $hometeam = $matchdata["hometeam"];
    $awayteam = $matchdata["awayteam"];
    $gametype = $matchdata["gametype"];
    $hs = $matchdata["homescore"];
    $as = $matchdata["awayscore"];
    echo "<tr>";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">";
    echo "<a href='index.php?sid=$SID&cmd=postresult&mid=$mid&matchdate=$matchdate&hometeam=".EncodeParam($hometeam)."&awayteam=".EncodeParam($awayteam)."&hs=$hs&hp=".$matchdata['homepen']."&as=".$as."&ap=".$matchdata['awaypen']."&gametype=$gametype'>";
    echo convertDatetimeToScreenDate($matchdate);
    echo "</a></font></td>";
    echo "<td align=\"CENTER\" class=\"TBLROW\"><font class=\"TBLROW\">".$hometeam."</font></td>";
    if ($gametype == 'L' && ($hs != $as)) {
      echo "<td align='CENTER' class=\"TBLROW\"><font class=\"TBLROW\">".$hs."</font></td>";
      echo "<td align='CENTER' class=\"TBLROW\"><font class=\"TBLROW\">v</font></td>";
      echo "<td align='CENTER' class=\"TBLROW\"><font class=\"TBLROW\">".$as."</font></td>";
    } else {
      $hp = $matchdata['homepen'];
      $ap = $matchdata['awaypen'];
      echo "<td align=\"CENTER\" class='TBLROW'><font class=\"TBLROW\">".$hs." [".$hp."]</font></td>";
      echo "<td align=\"CENTER\" class='TBLROW'><font class=\"TBLROW\">v</font></td>";
      echo "<td align='CETNER' class='TBLROW'><font class=\"TBLROW\">".$as." [".$ap."]</font></td>";
    }
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\">".$awayteam."</font></td>";
    echo "</tr>";
  }
  echo "</table>";
}
?>
