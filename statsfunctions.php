<?php 
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 9th December 2001
 * File  : statsfunctions.php
 ********************************************************/
require_once "playerstandingclass.php";

   /******************************************************
   * Compare the two classes passed.
   * The order of attributes compared is as follows:
   *   Percentage
   *   Games Played
   *   Goal Difference
   * @param a
   * @param b
   * @return <0 if a < b
   *          0
   *         >0
   ******************************************************/
   function comparePercentage($a, $b) {
     $lessthan = -1;
     $morethan = 1;

     $aperc = $a->getPercentage();
     $bperc = $b->getPercentage();
     if ($aperc < $bperc) {
       return $morethan;
     }
     if ($aperc > $bperc) {
       return $lessthan;
     }

     if ($a->predictions < $b->predictions) {
       return $morethan;
     }
     if ($a->predictions > $b->predictions) {
       return $lessthan;
     }

     if ($a->diff < $b->diff) {
       return $morethan;
     }
     if ($a->diff > $b->diff) {
       return $lessthan;
     }

     // Goal diff must be equal
     // a draw
     return 0;
   }

   /******************************************************
   * Compare the two classes passed.
   * The order of attributes compared is as follows:
   *   Points
   *   Goal Difference
   *   Games Played
   * @param a
   * @param b
   * @return <0 if a < b
   *          0
   *         >0
   ******************************************************/
   function comparePoints($a, $b) {
     $lessthan = -1;
     $morethan = 1;

     if ($a->points < $b->points) {
       return $morethan;
     }
     if ($a->points > $b->points) {
       return $lessthan;
     }

     // Points must be equal
     if ($a->pld == 0 && $b->pld != 0) {
       return $morethan;
     }

     if ($a->pld != 0 && $b->pld == 0) {
       return $lessthan;
     }

     // a draw
     return 0;
   }

   /******************************************************
   * Compare the two classes passed.
   * The order of attributes compared is as follows:
   *   Points
   *   Goal Difference
   *   Games Played
   * @param a
   * @param b
   * @return <0 if a < b
   *          0
   *         >0
   ******************************************************/
   function compare($a, $b) {
     $lessthan = -1;
     $morethan = 1;

//allow for 0 points in the sort!!!!!!!!!!

     if ($a->points < $b->points) {
       return $morethan;
     }
     if ($a->points > $b->points) {
       return $lessthan;
     }

     // Points must be equal
     if ($a->predictions == 0 && $b->predictions != 0) {
       return $morethan;
     }

     if ($a->predictions != 0 && $b->predictions == 0) {
       return $lessthan;
     }

     if ($a->diff < $b->diff) {
       return $morethan;
     }
     if ($a->diff > $b->diff) {
       return $lessthan;
     }

     if ($a->predictions < $b->predictions) {
       return $morethan;
     }
     if ($a->predictions > $b->predictions) {
       return $lessthan;
     }


     // Goal diff must be equal

     // a draw
     return 0;
   }

///////////////////////////////////////////////////
// Get the statistics for the given match 
///////////////////////////////////////////////////
  function getStatsForMatch($results, $matchid) {
    global $dbasePredictionData, $dbaseMatchData, $SID, $Prediction_Stats,$leagueID,$Games_Menu;
    global $ViewFutureStats, $Previous,$Next, $dbase;

    // If the matches are ordered, then the first should be the next game.
    $query = "select $dbaseMatchData.matchid, matchdate, hometeam, awayteam, $dbasePredictionData.homescore, $dbasePredictionData.awayscore from $dbasePredictionData inner join $dbaseMatchData on $dbasePredictionData.matchid=$dbaseMatchData.matchid and $dbasePredictionData.lid=$dbaseMatchData.lid where $dbaseMatchData.matchid='$matchid' and $dbaseMatchData.lid='$leagueID'";
    $result = $dbase->query($query);

    if (mysql_num_rows($result) == 0) {
      $nextmatch = "No match ";
    } else {
      // Get the date of the next game.
      $line = mysql_fetch_array($result, MYSQL_ASSOC);
      $matchid = $line["matchid"];
      $matchdate = $line["matchdate"];
      $hometeam = stripslashes($line["hometeam"]);
      $awayteam = stripslashes($line["awayteam"]);
      $homescore = $line["homescore"];
      $awayscore = $line["awayscore"];

      // If the game is in the future and viewing others users predictions is
      // disabled then do not show the stats.
      if ($ViewFutureStats == "FALSE" && (CompareDatetime($matchdate) > 0)) {
        return $results;
      }

      $key = "$homescore-$awayscore";
      if (array_key_exists($key,$results)) {
        $results[$key] += 1;
      } else {
        $results[$key] = 1;
      }
              
      $count = 1;

      // Loop through the rest of the results, just taking the next games results.
      while ($line  = mysql_fetch_array($result, MYSQL_ASSOC)) {
        if ($matchid == $line["matchid"]) {
          $homescore = $line["homescore"];
          $awayscore = $line["awayscore"];
          $key = "$homescore-$awayscore";
          if (array_key_exists($key,$results)) {
            $results[$key] += 1;
          } else {
            $results[$key] = 1;
          }
          $count++;
        }
      }
    }
    return $results;
  }
 
  /***********************************************
   * Show the ups and downs
   **********************************************/
  function ShowUpsAndDowns($week) {
    global $dbaseUserData, $dbaseStandings, $SID, $Prediction_Stats,$leagueID,$Games_Menu;
    global $Previous,$Next, $dbase, $UpsAndDownsCount, $Txt_Week, $Txt_Ups_And_Downs;
    global $ShowWeeklyStats;

    if ($ShowWeeklyStats != "TRUE") {
      return;
    }

    // Get the last week.
    $query = "SELECT week FROM $dbaseStandings WHERE lid='$leagueID' AND standtype='w' ORDER BY week DESC LIMIT 1";
    $result = $dbase->query($query);

    // Make sure that there are some entries.
    if ($dbase->getNumberOfRows() < 1) {
      return;
    }

    $line = mysql_fetch_array($result);
    $latestweek = $line["week"];

    if ($week == 0) {
      $week = $latestweek;
    }

    // If the latest week is week 1 then there are no stats;
    if ($latestweek == 1) {
      return;
    }

    // If there are more games this week we want to see last week.
    if (OutstandingFixturesForWeek($week) == true) {
      $week--;
    }

    $query = "SELECT username, position, prevpos FROM $dbaseStandings INNER JOIN $dbaseUserData ON $dbaseStandings.userid=$dbaseUserData.userid WHERE $dbaseUserData.lid='$leagueID' AND standtype='w' AND week='$week' ORDER by prevpos - position desc LIMIT $UpsAndDownsCount";
    $res = $dbase->query($query);
    if ($dbase->getNumberOfRows() == 0) {
      return;
    }
    $table = array();

    while ($line = mysql_fetch_array($res)) {
      $username = $line["username"];
        
      $pos = $line["position"];
      $prev = $line["prevpos"];
      $change = $prev - $pos;
      if ($change < 0) continue;

      $table[$username] = $change;
    }

    $query = "SELECT username, position, prevpos FROM $dbaseStandings INNER JOIN $dbaseUserData ON $dbaseStandings.userid=$dbaseUserData.userid WHERE $dbaseUserData.lid='$leagueID' AND standtype='w' AND week='$week' AND pld>0 ORDER BY prevpos - position LIMIT $UpsAndDownsCount";
    $res = $dbase->query($query);

    while ($line = mysql_fetch_array($res)) {
      $username = $line["username"];
        
      $pos = $line["position"];
      $prev = $line["prevpos"];
      $change = $prev - $pos;
      if ($change > 0) continue;

      $table[$username] = $change;
    }

    arsort($table);

?>
    <table id="WWTAB" class="PREDTB">
      <tr>
        <td align="CENTER" class="TBLHEAD" colspan="2">
          <font class="TBLHEAD">
            <?php echo $Txt_Ups_And_Downs. "[$Txt_Week $week]"; ?>
          </font>
        </td>
      </tr>

    <?php
      while (list($username, $change) = each($table)) {
        $class = "TBLROW";
        if ($change > 0) {
          $class = "UADUP";
          $change = "+$change";
        } else if ($change < 0) {
          $class = "UADDOWN";
        }
        
    ?>
      <tr>
        <td class="<?php echo $class; ?>">
          <font class="<?php echo $class; ?>">
            <?php echo $username; ?>
          </font>
        </td>
        <td class="<?php echo $class;?>">
          <font class="<?php echo $class; ?>">
            <?php echo $change; ?>
            </font>
          </td>
        </tr>
    <?php
      }
    ?>
    </table>
<?php
  }

  /***********************************************
   * Show the Monthly winners.
   **********************************************/
  function ShowMonthlyWinners($month) {
    global $dbaseStandings, $dbaseUserData, $SID, $leagueID, $Games_Menu,$Previous,$Next, $dbase;
    global $Txt_Monthly_Winner, $Username_txt, $PTS, $Months, $NumMonthlyWinners;

   if ($month == 0) {
     $month = (date("Y")*12) + date("m");
   }
   $query = "SELECT * FROM $dbaseStandings INNER JOIN $dbaseUserData ON $dbaseStandings.lid=$dbaseUserData.lid AND $dbaseStandings.userid=$dbaseUserData.userid  WHERE $dbaseStandings.lid='$leagueID' AND standtype='m' AND monthdate<='$month' ORDER BY monthdate DESC, points-prevmpts DESC";
   $result = $dbase->query($query);
   if ($dbase->getNumberOfRows() < 1) {
     return;
   }
?>
      <table id="WWTAB" class="PREDTB">
      <tr>
      <td align="CENTER" class="TBLHEAD" colspan="2">
      <font class="TBLHEAD">
      <?php echo $Txt_Monthly_Winner; ?><br>
      <?php GetMonthsAsSelect($month,"table"); ?>
      </font>
      </td>
      </tr>

<?php
  $count = 1;
  while ($line = mysql_fetch_array($result)) {
   $username = $line["username"];
   $points = $line["points"];
   $prevmpts = $line["prevmpts"];
?>
        <tr>
          <td class="TBLROW">
            <font class="TBLROW">
<?php
          echo $username;
?>
            </font>
          </td>
          <td class='TBLROW'>
            <font class="TBLROW">
<?php
          echo $points-$prevmpts;
?>
            </font>
          </td>
        </tr>
<?php 
    $count++;
    if ($count > $NumMonthlyWinners) {
      break;
    }
  }
?>
      </table>
<?php
  }

  /***********************************************
   * Show the weekly stats
   * The standings table stores the data as
   * cumulative values. Need to delete the 
   * previous weeks points if this is not week 1.
   **********************************************/
  function ShowWeeklyWinners($week) {
    global $dbaseStandings, $dbaseUserData, $SID, $Prediction_Stats,$leagueID,$Games_Menu,$Previous;
    global $Txt_Weekly_Winners, $Txt_Week, $WeeklyWinnersCount, $Username_txt, $PTS,$Next, $dbase;
    global $ShowWeeklyStats;

    if ($ShowWeeklyStats != "TRUE") {
      return;
    }

    // Get the latest week.
    $query = "SELECT MAX(week) AS week FROM $dbaseStandings WHERE lid='$leagueID' AND standtype='w'";
    $result = $dbase->query($query);
    $line = mysql_fetch_array($result);
    $latestweek = $line["week"];

    if ($week == 0) {
      $week = $latestweek;
    }

    // If the latest week is week 1 then there are no stats;
    if ($latestweek == 1) {
      return;
    }

    // If there are more games this week we want to see last week.
    if (OutstandingFixturesForWeek($week) == true) {
      $week--;
    }

   $standings = array();
   $query = "SELECT a.*, b.username FROM $dbaseStandings AS a INNER JOIN $dbaseUserData AS b ON a.lid=b.lid AND a.userid=b.userid WHERE a.lid='$leagueID' AND week='$week' AND standtype='w' ORDER BY position";
   $result = $dbase->query($query);

   if ($dbase->getNumberOfRows() == 0) {
     return;
   }

   while ($line = mysql_fetch_array($result)) {
     $userid = $line["userid"];
     $ps = new PlayerStanding($line);
     $standings["$userid"] = $ps;
   }

    // If we are not looking at week1 we need to subtract the previous weeks values.
   if ($week > 1) {
     $latestweek = $week - 1;
     $query = "SELECT * FROM $dbaseStandings WHERE lid='$leagueID' AND standtype='w' AND week='$latestweek'";
     $res = $dbase->query($query);
     while ($line = mysql_fetch_array($res)) {
       $userid = $line["userid"];
       if (array_key_exists($userid, $standings)) {
         $standings["$userid"]->subtract($line);
       }
     }
   }
   uasort($standings,"comparePoints");
   $standings = array_slice($standings, 0, $WeeklyWinnersCount);

?>
      <table id="WWTAB" class="PREDTB">
      <tr>
      <td align="CENTER" class="TBLHEAD" colspan="2">
      <font class="TBLHEAD">
      <?php echo $Txt_Weekly_Winners."<br>[$Txt_Week $week]";?>
      </font>
      </td>
      </tr>
      <tr>
      <td align="CENTER" class="TBLHEAD" colspan="1">
      <font class="TBLHEAD">
      <?php echo $Username_txt; ?>
      </font>
      </td>
      <td align="CENTER" class="TBLHEAD" colspan="1">
      <font class="TBLHEAD">
      <?php echo $PTS; ?>
      </font>
      </td>
      </tr>

<?php
        while(list($key,$val) = each($standings)) {
?>
        <tr>
          <td class="TBLROW">
            <font class="TBLROW">
<?php
          echo $val->username;
?>
            </font>
          </td>
          <td class='TBLROW'>
            <font class="TBLROW">
<?php
          echo $val->points;
?>
            </font>
          </td>
        </tr>
<?php
        }
?>
      </table>
<?php
  }

  /***********************************************
   * Show the prediction statistics for the match
   * on the given matchid.
   * @param matchid
   **********************************************/
  function ShowPredictionStatsForMatch($matchid) {
    global $dbasePredictionData, $dbaseMatchData, $SID, $Prediction_Stats,$leagueID,$Games_Menu,$Previous,$Next, $dbase;

    $results = Array();
    $results = getStatsForMatch($results, $matchid);
    arsort($results);
?>
<script type="text/javascript">
/***********************************************************
 * Set the statistics for the match.
 ***********************************************************/
function setStats(matchid,matchdate,hometeam,awayteam,count,scores,scorescount,numpreds) {

  // Now add the stats
  // Loop through the stats and create the rows.
  var oldstats = document.getElementById("STATSTAB");

  var statsdiv = document.getElementById("STATS");
  // Now create a table
  var table = document.createElement("table");
  table.setAttribute("id","STATSTAB");
  table.className = "PREDTB";

  var thead = document.createElement("thead");
  var row = document.createElement("tr");
  
  var cell = document.createElement("td");
  cell.className = "TBLHEAD";
  cell.colSpan = 2;
  cell.align = "center";
  
  var font = document.createElement("font");
  font.className = "TBLHEAD";
  var text = document.createTextNode("<?php echo $Prediction_Stats; ?>");
  font.appendChild(text);

  var br = document.createElement("br");
  font.appendChild(br);

  // Previous link
  var a = document.createElement("a");
  a.className = "PRED";
  a.setAttribute("target","MATCHSTATS");
  a.setAttribute('href',"getmatchstats.php?sid=<?php echo $SID; ?>&matchid="+matchid+"&matchdate="+matchdate+"&cmd=prev");
  text = document.createTextNode("<?php echo " $Previous"; ?>");
  a.appendChild(text);
  font.appendChild(a);

  // Next link
  a = document.createElement("a");
  a.className = "PRED";
  a.setAttribute("target","MATCHSTATS");
  a.setAttribute('href',"getmatchstats.php?sid=<?php echo $SID; ?>&matchid="+matchid+"&matchdate="+matchdate+"&cmd=next");
  text = document.createTextNode("<?php echo " $Next"; ?>");
  a.appendChild(text);
  font.appendChild(a);

  br = document.createElement("br");
  font.appendChild(br);

  // Stats/fixture link
  a = document.createElement("a");
  a.className = "PRED";
  a.setAttribute('href',"index.php?sid=<?php echo $SID; ?>&cmd=matchpreds&matchid="+matchid+"&date="+matchdate);
  text = document.createTextNode(hometeam+" v "+awayteam);
  a.appendChild(text);
  font.appendChild(a);

  cell.appendChild(font);
  row.appendChild(cell);
  thead.appendChild(row);
  table.appendChild(thead);

  var tbody = document.createElement("tbody");
  tbody.setAttribute("id","STATS_ROWS");
  
  var predtext;
  var row1;
  var cell1;
  var cell2;
  var font1;
  var img;
  var pct = 0;
  for (i=0; i<count; i++) {
    predtext = document.createTextNode(scores[i]);
    cell1 = document.createElement("td");
    cell1.className = "TBLROW";

    font1 = document.createElement("font");
    font1.className = "TBLROW";
    font1.appendChild(predtext);

    cell1.appendChild(font1);
    row1 = document.createElement("tr");
    row1.appendChild(cell1);

    img = document.createElement("img");
    img.setAttribute("src","percentbar.gif");
    img.setAttribute("alt","Percentage");
    img.height= "10";
    pct = (scorescount[i])/numpreds;
    img.width = pct*40;
    pct = Math.floor(pct * 1000)/10;
    predtext = document.createTextNode(" "+pct+"% ["+scorescount[i]+"]");

    font1 = document.createElement("font");
    font1.className = "TBLROW";
    font1.appendChild(img);
    font1.appendChild(predtext);
    
    cell2 = document.createElement("td");
    cell2.className = "TBLROW";
    cell2.appendChild(font1);

    row1.appendChild(cell2);

    tbody.appendChild(row1);
  }
  table.appendChild(tbody);
  statsdiv.replaceChild(table,oldstats);
}
 </script>

      <iframe id="MATCHSTATS" name="MATCHSTATS" style="width:0px; height:0px;border:0px" src="getgametime.php"></iframe>
      
      <div id="STATS">
      <table id="STATSTAB" class="PREDTB">
      <tr>
      <td align="CENTER" class="TBLHEAD" colspan="2">
      <font class="TBLHEAD">
<?php
      $query = "select * from $dbaseMatchData where matchid=$matchid and lid=$leagueID";
      $result = $dbase->query($query);

      $line = mysql_fetch_array($result);
      $hometeam = $line["hometeam"];
      $awayteam = $line["awayteam"];
      $matchdate = $line["matchdate"];

      echo " $Prediction_Stats";
      echo "<br><a id='A_PREV' target=\"MATCHSTATS\" href=\"getmatchstats.php?sid=$SID&matchid=$matchid&matchdate=$matchdate&cmd=prev\" class=\"PRED\">";
      echo " $Previous   ";
      echo "</a>";
      echo "<a id='A_NEXT' target=\"MATCHSTATS\" href=\"getmatchstats.php?sid=$SID&matchid=$matchid&matchdate=$matchdate&cmd=next\" class=\"PRED\">";
      echo " $Next";
      echo "</a>";
      echo "<br><a id='A_FIXT' href=\"index.php?sid=$SID&cmd=matchpreds&matchid=$matchid&date=$matchdate\" class=\"PRED\">";
      echo "<div id='A_FIXT_TEXT'>";
      echo "$hometeam v $awayteam";
      echo "</div>";
      echo "</a>";
?>
      </font>
      </td>
      </tr>

      <div id='STATS_ROWS_DIV'>
      <tbody id='STATS_ROWS'>
<?php
      $sum = array_sum($results);

      $count = count($results);
      // Cycle through the array and print the results.
      while (list($key,$val) = each($results)) {
?>
        <tr>
        <td align="CENTER" class="TBLROW">
        <font class="TBLROW">
<?php
        echo $key;
?>
        </font>
        </td>
        <td width="100" class="TBLROW">
        <font class="TBLROW">
<?php
        $percentage = floor($val*1000/$sum)/10;
        echo "<img width=\"".($percentage*0.4)."\" height=\"10\" src=\"percentbar.gif\" alt=\"Percentage\"> ";
        echo $percentage."% [$val]";
?>
        </font>
        </td>
        </tr>
<?php
      }
?>
      </tbody>
      </div>
      </table>
      </div>
<?php
  }

  /***********************************************
   * Show the stats for the next match
   **********************************************/
  function ShowPredictionStatsForNextMatch() {
    global $dbasePredictionData, $dbaseMatchData,$leagueID, $dbase;

    $todaysdate = date("Y-m-d H:i:s");
    
    // If the matches are ordered, then the first should be the next game. If there are two at the same time, 
    // only one is shown (using the rand() function).
    $query = "SELECT matchid FROM $dbaseMatchData WHERE matchdate>=\"$todaysdate\" AND $dbaseMatchData.lid='$leagueID' ORDER BY matchdate, rand()";

    $result = $dbase->query($query);

    if (mysql_num_rows($result) == 0) {
      $nextmatch = "No matches scheduled";
    } else {
      // Get the date of the next game.
      $line = mysql_fetch_array($result, MYSQL_ASSOC);
      $matchid = $line["matchid"];

      ShowPredictionStatsForMatch($matchid);
    }
  }

  /***********************************************
   * Show the players in order of the ones with 
   * the best percentage on predictions.
   **********************************************/
  function ShowPercentageLeaders() {
    global $Username_txt, $Txt_Average_Points, $Txt_Points, $Txt_Played, $Txt_Avg;
    global $Txt_No_Stats_Available;

    // Array holding users
    $gameStats = GetGameStats();
    $users = GetUsers();
?>
  <table class="CENTER">
<?php
    // Sort in descending order. Keep the keys intact.
    // The compare can be a class method in PHP 4.1.1, but will not work with 4.0.3
    if (sizeof($gameStats) > 0) {
      uasort($gameStats, "comparePercentage");
  ?>
  <tr>
    <td class="TBLHEAD" colspan="5">
      <font class="TBLHEAD">
        <?php echo $Txt_Average_Points; ?>
      </font>
    </td>
  </tr>
  <tr>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        &nbsp;
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo $Username_txt; ?>
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo $Txt_Played; ?>
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo $Txt_Points; ?>
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo $Txt_Avg; ?>
      </font>
    </td>
  </tr>
  <?php
  $count = 1;
  while ((list($key,$val) = each($gameStats))) {
    $userid = $key;
    $played = $val->won + $val->lost + $val->drawn;
    if (!array_key_exists($userid,$users)) {
      LogMsg("Unable to find username for userid $userid in statistics.");
      continue;
    }
  ?>
  <tr>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $count; ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $users[$userid]; ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $val->GetPlayed(); ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo round($val->getPoints(),3); ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo round($val->getPercentage(),3); ?>
      </font>
    </td>
  </tr>
  <?php
   
        $count++;
      }  
    } else {
?>
  <tr>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $Txt_No_Stats_Available; ?>
      </font>
    </td>
  </tr>
<?php
    }
?>
</table>
<?php
  }

  /***********************************************
   * 
   **********************************************/
  function ShowStatistics() {
    ShowPercentageLeaders();
  }

  /***********************************************
   * Get an array of the statistics for all the 
   * users.
   **********************************************/
  function GetGameStats() {
      // Allow access to the global table name.
      global $dbaseUserData, $dbasePredictionData, $dbaseMatchData, $hideNoPredictions, $leagueID, $dbase;

      /////////////////////////////////////////////////////////////////	
      // Calculate the new standings
      /////////////////////////////////////////////////////////////////	

      // Update the league right after the game has started. The null result value is checked for.
      $todaysdate = date("Y-m-d H:i:s");

      // Array holding users
      $gameStats = Array();
      
      // Get a table containing the userid, predicted scores and actual scores. This will not actually show users with 0 
      // predictions
      $predquery = "SELECT bonuspoints, $dbasePredictionData.userid, $dbaseMatchData.homescore as mhs, $dbaseMatchData.awayscore as mas, $dbasePredictionData.homescore as phs, $dbasePredictionData.awayscore as pas FROM $dbasePredictionData inner join $dbaseMatchData on $dbasePredictionData.matchid=$dbaseMatchData.matchid and $dbasePredictionData.lid=$dbaseMatchData.lid where $dbaseMatchData.matchdate<=\"$todaysdate\" and $dbaseMatchData.lid='$leagueID' order by matchdate";

      $predresult = $dbase->query($predquery);

      $num_rows = mysql_num_rows($predresult);
      if ($num_rows > 0) {
            
        // Getting the individual predictions.
        while ($predline = mysql_fetch_array($predresult, MYSQL_BOTH)) {
          reset ($predline); // TODO?? Is this needed?
          $userid = $predline["userid"];
          if (array_key_exists($userid,$gameStats) == false) {
            $gameStats[$userid] = new GameStats($userid);
          }
          $predictedHome = $predline["phs"];
          $predictedAway = $predline["pas"];
          $actualHome = $predline["mhs"];
          $actualAway = $predline["mas"];
          $bonuspoints = $predline["bonuspoints"];
          if ($predictedHome != "" and $actualHome != "") {
            $gameStats[$userid]->UpdateStats($predictedHome,
                                             $predictedAway,
                                             $actualHome,
                                             $actualAway,
                                             $bonuspoints);
          }
        }
        
        // If the display of users with 0 predictions is disabled and this user has no predictions
        // then continue.
        /*
        if ($hideNoPredictions == "FALSE") {
          $userquery = "select * from $dbaseUserData where lid='$leagueID'";

          $userresult = $dbase->query($userquery);
          while ($user = mysql_fetch_array($userresult)) {
            $userid = $user["userid"];

            if (!array_key_exists($userid,$gameStats)) {
              $gameStats[$userid] = new GameStats($userid);
            }
          }
        }
        */
    
        mysql_free_result($predresult);
      }

    return $gameStats;
  }

  /*************************************************************
   * Write the monthly winner.
   * The points should reflect the points for this month only
   *************************************************************/
  function WriteMonthlyStandings(&$gameStats, $months) {
    global $dbaseStandings, $dbase, $leagueID;
    $count = 1;
    $disppos = 1;
    $prevpoints = 0;
    $prevfor = 0;
    $prevagainst = 0;

    uasort($gameStats, "compare");

    while ((list($key,$val) = each($gameStats))) {
      $userid = $key;
      $points = $gameStats[$key]->points;
      $won = $gameStats[$key]->won;
      $drawn = $gameStats[$key]->drawn;
      $lost = $gameStats[$key]->lost;
      $gfor = $gameStats[$key]->for;
      $gagainst = $gameStats[$key]->against;
      $diff = $gameStats[$key]->diff;
      $prevm = $gameStats[$key]->prevMonthPoints;
      $prevw = $gameStats[$key]->prevWeekPoints;
      $pct = "";
      $position = $count;

      // Set the position for the user. Position can be equal.
      if ($count == 1 || $prevpoints != $points || $prevfor != $gfor || $prevagainst != $gagainst) {
        $disppos = $count;
      }

      $query = "REPLACE INTO $dbaseStandings (lid, userid,position,displaypos,monthdate,points,won,drawn,lost,gfor,gagainst,diff, prevmpts,prevwpts,standtype) VALUES ('$leagueID','$userid','$position','$disppos','$months','$points','$won','$drawn','$lost','$gfor','$gagainst','$diff','$prevm','$prevw','m')";
      $result = $dbase->query($query);
      $prevpoints = $points;
      $prevfor = $gfor;
      $prevagainst = $gagainst;
 
      $gameStats[$key]->updateForMonth();
      $count ++;
    }
  }

  /*************************************************************
   * Write the standings to the database.
   *************************************************************/
  function WriteStandings(&$gameStats, $week) {
    global $dbaseStandings, $dbase, $leagueID, $hideNoPredictions;
    $count = 1;
    $position = 1;
    $disppos = 1;
    $prevpoints = 0;
    $prevfor = 0;
    $prevagainst = 0;

    uasort($gameStats, "compare");

    while ((list($key,$val) = each($gameStats))) {
      $userid = $key;
      $pld = $gameStats[$key]->predictions;
      if ($hideNoPredictions == "TRUE" && $pld == 0) {
        continue;
      }
      $won = $gameStats[$key]->won;
      $drawn = $gameStats[$key]->drawn;
      $lost = $gameStats[$key]->lost;
      $gfor = $gameStats[$key]->for;
      $gagainst = $gameStats[$key]->against;
      $diff = $gameStats[$key]->diff;
      $points = $gameStats[$key]->points;
      $weekdate = "";
      $prevpos = $gameStats[$key]->prevpos;
      $prevWeek = $gameStats[$key]->prevWeekPoints;
      $prevMonth = $gameStats[$key]->prevMonthPoints;

      // Set the position for the user. Position can be equal.
      if ($count == 1 || $prevpoints != $points || $prevfor != $gfor || $prevagainst != $gagainst) {
        $disppos = $count;
      }

      $prevpoints = $points;
      $prevfor = $gfor;
      $prevagainst = $gagainst;
 
      // Update the standings table
      $query = "REPLACE INTO $dbaseStandings (lid, userid, week, weekdate, position, prevpos, pld, won, drawn, lost, gfor, gagainst, diff, points, displaypos,prevwpts,prevmpts,standtype) VALUES ('$leagueID','$userid', '$week', '$weekdate', '$position', '$prevpos', '$pld', '$won', '$drawn', '$lost', '$gfor', '$gagainst', '$diff', '$points','$disppos','$prevWeek','$prevMonth','w')";
      $result = $dbase->query($query);

      $gameStats[$key]->setPreviousPosition($count);
      $gameStats[$key]->updateForWeek();
      $position ++;
      $count ++;
    }  
  }

  /********************************************************************
   * Get the matchdate for the first game.
   ********************************************************************/
  function GetFirstGameDate() {
    global $dbaseUserData, $dbasePredictionData, $dbaseMatchData, $hideNoPredictions;
    global $leagueID, $dbase, $timezoneOffset;

    $res = $dbase->query("SELECT UNIX_TIMESTAMP(matchdate) as md FROM $dbaseMatchData WHERE lid='$leagueID' ORDER BY matchdate ASC LIMIT 1");
    $line = mysql_fetch_array($res);
    $md = $line["md"];

    return $md;
  }

  /********************************************************************
   * Get the matchdate for the last game.
   ********************************************************************/
  function GetLastGameDate() {
    global $dbaseUserData, $dbasePredictionData, $dbaseMatchData, $dbaseStandings, $hideNoPredictions;
    global $leagueID, $dbase, $timezoneOffset;

    $res = $dbase->query("SELECT UNIX_TIMESTAMP(matchdate) as md FROM $dbaseMatchData WHERE lid='$leagueID' ORDER BY matchdate DESC LIMIT 1");
    $line = mysql_fetch_array($res);
    $md = $line["md"];

    return $md;
  }

  /********************************************************************
   * Determine if there are any more games after the given date and
   * before todays date. This indicates if there are any more points to
   * calculate for predictions.
   ********************************************************************/
  function MoreGamesPredicted($startdate) {
    // Allow access to the global table name.
    global $dbasePredictionData, $dbaseMatchData, $hideNoPredictions;
    global $leagueID, $dbase;

    $todaysdate = date("Y-m-d H:i:s");

    $predquery = "SELECT $dbasePredictionData.userid, $dbaseMatchData.homescore as mhs, $dbaseMatchData.awayscore as mas, $dbasePredictionData.homescore as phs, $dbasePredictionData.awayscore as pas, matchdate, bonuspoints, UNIX_TIMESTAMP(matchdate) as uts FROM $dbasePredictionData INNER JOIN $dbaseMatchData on $dbasePredictionData.matchid=$dbaseMatchData.matchid and $dbasePredictionData.lid=$dbaseMatchData.lid where $dbaseMatchData.matchdate>='$startdate' AND $dbaseMatchData.matchdate<'$todaysdate' and $dbaseMatchData.lid='$leagueID' order by matchdate";

    $predresult = $dbase->query($predquery);
    $num_rows = mysql_num_rows($predresult);

    return ($num_rows > 0);
  }

  /********************************************************************
   * Determine if there are any preidctions valid for todays date
   ********************************************************************/
  function ValidPredictionsExist() {
    // Allow access to the global table name.
    global $dbasePredictionData, $dbaseMatchData;
    global $leagueID, $dbase;

    $todaysdate = date("Y-m-d H:i:s");

    $predquery = "SELECT $dbasePredictionData.userid, $dbaseMatchData.homescore as mhs, $dbaseMatchData.awayscore as mas, $dbasePredictionData.homescore as phs, $dbasePredictionData.awayscore as pas, matchdate, bonuspoints, UNIX_TIMESTAMP(matchdate) as uts FROM $dbasePredictionData INNER JOIN $dbaseMatchData on $dbasePredictionData.matchid=$dbaseMatchData.matchid and $dbasePredictionData.lid=$dbaseMatchData.lid WHERE $dbaseMatchData.matchdate<'$todaysdate' and $dbaseMatchData.lid='$leagueID' order by matchdate";

    $predresult = $dbase->query($predquery);
    $num_rows = mysql_num_rows($predresult);

    return ($num_rows > 0);
  }

  /********************************************************************
   * Update the standings table to take into account the weekly 
   * positions
   ********************************************************************/
  function UpdateStandingsTable() {

    // Allow access to the global table name.
    global $dbaseUserData, $dbasePredictionData, $dbaseMatchData, $dbaseStandings;
    global $leagueID, $dbase, $timezoneOffset;

    /////////////////////////////////////////////////////////////////	
    // Calculate the new standings
    /////////////////////////////////////////////////////////////////	
    // Remove the current standings
    $query = "DELETE FROM $dbaseStandings WHERE lid='$leagueID'";
    $dbase->query($query);
 
    // Array holding users
    $gameStats = Array();

    // End date is now
    $now = date("Y-m-d H:i:s");

    // Get all the users
    $userquery = "select * from $dbaseUserData where lid='$leagueID'";
    $userresult = $dbase->query($userquery);
    while ($user = mysql_fetch_array($userresult)) {
      $userid = $user["userid"];
      if (!array_key_exists($userid,$gameStats)) {
        $gameStats[$userid] = new GameStats($userid);
      }
    }

    // If there are no valid predictions just show all the users if configured that way.
    if (ValidPredictionsExist() == false) {
      WriteStandings($gameStats,1);
      $dbase->freeResult();
      return $gameStats;
    }

    // Get this weeks week number, it is possible that there are no predictions in this week
    $query = "SELECT DISTINCT week FROM $dbaseMatchData WHERE lid='$leagueID' AND matchdate<'$now' ORDER BY week DESC";
    $result = $dbase->query($query);
    $weeks = mysql_fetch_array($result);
    $thisWeek = $weeks["week"];

    // Get all the predictions up to todays date and sort them by week
    $predquery = "SELECT week, $dbasePredictionData.userid, $dbaseMatchData.homescore as mhs, $dbaseMatchData.awayscore as mas, $dbasePredictionData.homescore as phs, $dbasePredictionData.awayscore as pas, matchdate, bonuspoints, UNIX_TIMESTAMP(matchdate) as uts FROM $dbasePredictionData INNER JOIN $dbaseMatchData on $dbasePredictionData.matchid=$dbaseMatchData.matchid and $dbasePredictionData.lid=$dbaseMatchData.lid WHERE $dbaseMatchData.matchdate<'$now' and $dbaseMatchData.lid='$leagueID' ORDER BY week, matchdate";

    $predresult = $dbase->query($predquery);
    $num_rows = mysql_num_rows($predresult);

    $lastWeek = -1;
    $lastMonth = -1;
    $month = -1;

    // Calculate the points for the week
    while ($predline = mysql_fetch_array($predresult, MYSQL_BOTH)) {
      $week = $predline["week"];

      // The month part is the first 7 digits YYYY-MM
      $date = $predline["matchdate"];
      $year = substr($date,0,4);
      $month = substr($date,5,2);
      $month = ($year*12) + $month;


      // If we have crossed a week, write the details.
      if (($lastWeek != -1 && $lastWeek != $week)) {
        // Update the points for the week. If there is a gap between the weeks
        // fill it in with the current standings.
        while ($lastWeek < $week) {
          WriteStandings($gameStats,$lastWeek);
          $lastWeek = $week;
        }
      }

      // If we have crossed a month, write the details.
      if (($lastMonth != -1 && $lastMonth != $month)) {
        WriteMonthlyStandings($gameStats,$lastMonth);
        $lastMonth = $month;
      }

      $userid = $predline["userid"];
      if (array_key_exists($userid,$gameStats) == false) {
        $gameStats["$userid"] = new GameStats($userid);
      }
      $bonuspoints = $predline["bonuspoints"];
      $predictedHome = $predline["phs"];
      $predictedAway = $predline["pas"];
      $actualHome = $predline["mhs"];
      $actualAway = $predline["mas"];
      if ($predictedHome != "" and $actualHome != "") {
        $gameStats["$userid"]->UpdateStats($predictedHome,
                                         $predictedAway,
                                         $actualHome,
                                         $actualAway,
                                         $bonuspoints);
      }

      $lastWeek = $week;
      $lastMonth = $month;
    }
    while ($lastWeek <= $thisWeek) {
      WriteStandings($gameStats,$lastWeek++);
    }

    // Now check against current month.
    $currentMonth = GetThisMonth();
    if ($lastMonth != -1 && $lastMonth < $currentMonth) {
      WriteMonthlyStandings($gameStats,$lastMonth);
    }

    $dbase->freeResult();
  }

  /*****************************************************************
   * Show the current standings for the user.
   *****************************************************************/
  function ShowStandingsTableForUser() {
    global $leagueID, $dbaseStandings, $dbase, $User;

    $week = 0;
    $pos = 1;
    $uid = $User->getUserId();
    // Get the users position for the latest week;
    $query = "SELECT * FROM $dbaseStandings WHERE lid='$leagueID' AND standtype='w' AND userid='$uid' ORDER BY week DESC";
    $res = $dbase->query($query);
    if ($dbase->getNumberOfRows() > 0) {
      $line = mysql_fetch_array($res);
      $pos = $line['position'];
      $week = $line['week'];
    }

    DisplayStandings($week, $pos);
  }

  /*****************************************************************
   * Show the current standings. If the Standings table is enabled
   * use it, else calculate the standings on the fly.
   *****************************************************************/
  function ShowStandingsTableForWeek($week, $pos) {
    DisplayStandings($week, $pos);
  }

  /*****************************************************************
   * Show the current standings. If the Standings table is enabled
   * use it, else calculate the standings on the fly.
   *****************************************************************/
  function ShowStandingsTable($pos) {
    global $dbaseStandings, $leagueID, $dbase;
    
    // If the week is not set, get the max week.
    $week = 1;
    if (array_key_exists("week",$_GET)) {
      $week = $_GET["week"];
    } else {
      $query = "SELECT MAX(week) AS week FROM $dbaseStandings WHERE lid='$leagueID' AND standtype='w'";
      $res = $dbase->query($query);
      $line = mysql_fetch_array($res);
      $week = $line['week'];
    }
    DisplayStandings($week, $pos);
  }

  /*****************************************************************
   * Display the standings for the given week.
   *****************************************************************/
  function DisplayStandings($week, $pos) {
    global $dbaseUserData, $dbasePredictionData, $dbaseMatchData, $dbaseStandings, $_GET, $SID, $usersPerPage, $Prediction_League_Standings, $Send_Msg, $User, $useMessaging, $User;
    global $P, $W, $POS, $D, $D, $L, $Away, $Home, $F, $A, $GD, $User_Name,$PTS,$Date,$Result,$leagueID;
    global $ShowDivisions, $ShowWeeklyStats, $Division, $dbase, $Txt_Week, $ShowIconInStandings;

    $page = 0;
    if (array_key_exists("page",$_GET)) {
      $page = $_GET["page"];
    }

    if ($week == '') {
      $week = GetThisWeek();
    }
    $offset = $page * $usersPerPage;

    $query = "SELECT count(userid) as usercount FROM $dbaseUserData WHERE $dbaseUserData.lid='$leagueID'";
    $userresult = $dbase->query($query);
    $userline = mysql_fetch_array($userresult);
    $numUsers = $userline["usercount"];

    // Select only the results from the latest week;
    // Limit results to requested page.
    $query = "SELECT * FROM $dbaseStandings INNER JOIN $dbaseUserData ON $dbaseStandings.userid=$dbaseUserData.userid AND $dbaseUserData.lid=$dbaseStandings.lid WHERE $dbaseUserData.lid='$leagueID' AND standtype='w' AND WEEK='$week' AND position>='$pos' ORDER BY position LIMIT $offset,$usersPerPage";
    $result = $dbase->query($query);
?>
<table class="STANDTB">
<?php 
  if ($numUsers > $usersPerPage) {
?>
<tr>
  <td colspan="12" align="left" class="TBLROW">
    <table>
    <tr>
    <font class="TBLROW">
<?php
  for ($i=0,$j=0; $i<$numUsers; $i+=$usersPerPage,$j++) {
    echo "<td width='80' class='TBLHEAD'>";
    echo "<a href=\"index.php?sid=$SID&cmd=table&week=$week&page=$j\">";
    if ($ShowDivisions == "TRUE") {
      echo "$Division ".($j+1);
    } else {
      echo $i+1;
      echo "-";
      echo $i+$usersPerPage;
    }
    echo "</a>";
    echo "</td>";
  }
?>
    </font>
    </tr>
    </table>
  </td>
</tr>
<?php
  }
?>
<tr>
  <td colspan="12" align="center" class="TBLHEAD">
    <font class="TBLHEAD">
      <?php 
        echo "$Prediction_League_Standings ";
        if ($ShowWeeklyStats == "TRUE") {
          echo $Txt_Week;
          GetWeeksAsSelect($week, "tableweek",false);
        }
      ?>
            
    </font>
  </td>
</tr>
<tr>
  <td class="TBLPOS">
    <font class="TBLHEAD"><?php echo $POS; ?></font>
  </td>
  <?php if ($ShowWeeklyStats == "TRUE") { ?>
  <td class="TBLPOS">
    <font class="TBLHEAD"></font>
  </td>
  <?php } ?>
  <?php if ($User->loggedIn == TRUE && $useMessaging == "TRUE") { ?>
  <td class="TBLPOS">
    <font class="TBLHEAD"></font>
  </td>
  <?php } ?>
  <td class="TBLUSER">
    <font class="TBLHEAD"><?php echo $User_Name; ?></font>
  </td>
  <td class="TBLPLAYED">
    <font class="TBLHEAD"><?php echo $P; ?></font>
  </td>
  <td class="TBLWON">
    <font class="TBLHEAD"><?php echo $W; ?></font>
  </td>
  <td class="TBLDRAWN">
    <font class="TBLHEAD"><?php echo $D; ?></font>
  </td>
  <td class="TBLLOST">
    <font class="TBLHEAD"><?php echo $L; ?></font>
  </td>
  <td class="TBLFOR">
    <font class="TBLHEAD"><?php echo $F; ?></font>
  </td>
  <td class="TBLAGAINST">
    <font class="TBLHEAD"><?php echo $A; ?></font>
  </td>
  <td class="TBLGD">
    <font class="TBLHEAD"><?php echo $GD; ?></font>
  </td>
  <td class="TBLPTS">
    <font class="TBLHEAD"><b><?php echo $PTS; ?></b></font>
  </td>
</tr>

<?php
    // Display the table for people with results.
    $count = 1;
    $oldpoints = 0;
    $olddiff = 0;
    $oldfor = 0;
    $oldagainst = 0;

    while($line = mysql_fetch_array($result)) {
      $username = stripslashes($line["username"]);
      $userid = $line["userid"];
      $icon = $line["icon"];
      $pld = $line["pld"];
      $won = $line["won"];
      $drawn = $line["drawn"];
      $lost = $line["lost"];
      $gfor = $line["gfor"];
      $gagainst = $line["gagainst"];
      $diff = $line["diff"];
      $points = $line["points"];
      $position = $line["position"];
      $disppos = $line["displaypos"];
      $prevpos = $line["prevpos"];
      $change = $prevpos - $disppos;

      $oldpoints = $points;
      $olddiff = $diff;
      $oldfor = $gfor;
      $oldagainst = $gagainst;

     echo " <tr>\n";

     $class = "TBLROW";
     if ($count == 1) {
       $class="LEADER";
     }
?>
        <td align="CENTER" nowrap class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php 
              echo "$disppos ";
            ?>
          </font>
        </td>
        <?php if ($ShowWeeklyStats == "TRUE") { ?>
        <td align="LEFT" nowrap class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php 
              $img = "nochange12x12_001.gif";
              if ($change>0) {
                $change = "+$change";
                $img = "up12x12_001.gif";
              } else if ($change < 0) {
                $img = "down12x12_001.gif";
              }
              echo "<img src='$img' width='12' height='12'>";
              echo "<small>$change</small>";
            ?>
          </font>
        </td>
        <?php } ?>
        <?php if ($User->loggedIn == TRUE && $useMessaging == "TRUE") { ?>
        <td class="<?php echo $class?>" align="CENTER" valign="MIDDLE">
          <font class="<?php echo $class?>">
<?php if ($userid != $User->userid) { ?>
            <a href="index.php?<?php echo "sid=$SID&cmd=createmsg&userid=$userid"?>">
              <img src="img/emailiconold18x12.gif" alt="<?php echo $Send_Msg.$username?>" height="12" width="18" border="0">
            </a>
<?php } ?>
          </font>
        </td>
        <?php } ?>
        <td class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <a href="index.php?<?php echo "sid=$SID&cmd=userpreds&user=$userid"?>">
              <?php if ($ShowIconInStandings == "TRUE") {?>
              <img border="0" alt="<?php echo $icon?>" src="<?php echo $icon?>" width="18">
              <?php } ?>
              <?php echo $username?></a>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <!-- Played -->
            <?php echo $pld?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <!-- Won -->
            <?php echo $won; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <!-- Drawn -->
            <?php echo $drawn; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <!-- Lost -->
            <?php echo $lost; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php echo $gfor; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php echo $gagainst; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <?php echo $diff; ?>
          </font>
        </td>
        <td align="CENTER" class="<?php echo $class?>">
          <font class="<?php echo $class?>">
            <b>
              <?php echo $points?>
            </b>
          </font>
        </td>
      </tr>
<?php
    $count++;
    }
    echo "</table>";
    // Display a table of the new users. Uncomment the next line to 
    // show the users.
    //showNewUsers($newUsers);
  }
?>
