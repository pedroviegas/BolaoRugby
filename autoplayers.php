<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 14th July 2003
 * File  : autoplayer.php
 * Desc  : The implementation of the automatic player.
 *       : Can this fella do better than you?
 *       : This guy is only as bright as the previous 
 *       : results. If there is no result in the league
 *       : for the current team then it is purely guess 
 *       : work for the computer.
 *       : This feature can also be enabled by a normal
 *       : user. In this scenario, the users predictions
 *       : are made for them if no prediction already
 *       : exists.
 ********************************************************/
require "systemvars.php";

// Global var to store the teams records. This would be better as 
// a static var in the AutoPlayer class if possible.
$teams = array();

// This is a utility class used by the autoplayer
class Team {
  var $teamname;
  var $goalsfor;
  var $goalsagainst;

  var $gamesplayed = 0;
  var $gameswon = 0;
  var $gamesdrawn = 0;
  var $gameslost = 0;

/////////////////////////////////////////////////////////
// Constructor for the team
/////////////////////////////////////////////////////////
  function Team($name) {
    global $dbaseMatchData;
    $this->teamname = $name;

    // Get the record for this team
    $query = "select * from $dbaseMatchData where awayteam=\"$name\" or hometeam=\"$name\"";
    $result = mysql_query($query)
      or die("unable to get match data for autoplayer :$query<br>\n".mysql_error());
  
    $this->gamesplayed = 0 + mysql_num_rows($result);

    while ($match = mysql_fetch_array($result)) {
      if ($name == $match["awayteam"]) {
        $against += $match["homescore"];
        $for += $match["awayscore"];
      } else {
        $for += $match["homescore"];
        $against += $match["awayscore"];
      }
      $this->goalsfor = $this->goalsfor + $for;
      $this->goalsagainst += $against;
      if ($for > $against) {
        $this->gameswon ++;
      } else if ($for < $against) {
        $this->gameslost ++;
      } else {
        $this->gamesdrawn ++;
      }
    }
    echo "Created $this->teamname<br>";
    echo "For $this->goalsfor ";
    echo "Against $this->goalsagainst<br>";
    echo "Played $this->gamesplayed<br>";
    echo "Won $this->gameswon<br>";
    echo "Drawn $this->gamesdrawn<br>";
    echo "Lost $this->gameslost<br>";
  }

  function GetStats() {
    
  }
}
////////////////////////////////////////////////////////////////
// The auto player class
////////////////////////////////////////////////////////////////
class AutoPlayer {
  // The ID of this player.
  var $pid;

  //var $teams;

  // Constructor for the AutoPlayer.
  function AutoPlayer($pid) {
    $this->pid = $pid;

    // This array will store the teams records as they are created.
    //$teams = array();
  }

  // Make a prediction for all the outstanding matches. Once the user
  // has made a manual prediction for a game, auto prediction is not 
  // possible. 
  // Get the results and position for each team
  // along with the average number of goals scored.
  function MakePredictions() {
    global $plPredictionData, $plMatchData;
 
    $now = date("Y-m-d H:i:s");

    $link = OpenConnection();
    $query  = "select * from $dbaseMatchData where matchdate>\"$now\"";
    $result = mysql_query($query) 
      or die("Unable to read the match data: $query<br>\n".mysql_error());

    // Go through each match and make a prediction.
    while ($match = mysql_fetch_array()) {
      $matchid = $match["matchid"];
      $hometeam = $match["hometeam"];
      $awayteam = $match["awayteam"];
      $this->MakePrediction($matchid, $hometeam, $awayteam);
    }
  }

  // Make a prediction for the given game.
  function MakePrediction($matchid, $hometeam, $awayteam) {
    global $teams, $leagueID;

    $hs = 0; // Home score
    $as = 0; // Away score

    // Get the record and mumber of goals for each team.
    
    // If we have already calculated the teams records lets save time and not do it again.
    $home = $teams[$hometeam];
    $away = $teams[$awayteam];
    if ($home == null) {
      $home = new Team($hometeam);
      $teams[$hometeam] = $home;
    }
    if ($away == null) {
      $away = new Team($awayteam);
      $teams[$awayteam] = $away;
    }
    
    // If both teams have no records
    if ($home->gamesplayed == 0 and $away->gamesplayed == 0) {
      // We know nothing, so lets pick something at random.
      $hs = GetRandomScore();
      $as = GetRandomScore();
    } else if ($home->gamesplayed == 0) {
      // Home team has no records
      if ($away->gameswon > $as->gameslost) {
        $as = ceil($away->goalsfor/$away->goalsagainst);
        // Make the home score dependent on avrage goals against the away team
        // Choose floor as the away team has a winning record.
        $hs = floor($away->goalsfor/$away->goalsagainst);
      } else {
        $as = floor($away->goalsfor/$away->goalsagainst);
        // Make the home score dependent on avrage goals against the away team
        // Choose floor as the away team has a winning record.
        $hs = ceil($away->goalsfor/$away->goalsagainst);
      }
    } else {
      // Away team has no records
    }

    InsertPrediction($matchid, $hs, $as);
  }

  // Insert the prediction into the prediction table.
  function InsertPrediction($mid, $hs, $as) {
    $query = "replace into $dbasePredictionData (lid) values ('$leagueID','$this->pid','$mid','$hs','$as')";
  }

  // Get a random score for the game. This is usually used when there is 
  // no prediction data to base it on.
  function GetRandomScore() {
    return floor(rand(0,3));
  }
  
}

$teams = array();
$link = OpenConnection();
$query = "select distinct hometeam from $dbaseMatchData";
$result = mysql_query($query) or die("unable to get distinct team : $query<br>\n".mysql_error());
while ($team = mysql_fetch_array($result)) {
  $team = $team["hometeam"];
  $teams[$team] = new Team($team);
}
$query = "select distinct awayteam from $dbaseMatchData";
$result = mysql_query($query) or die("unable to get distinct team : $query<br>\n".mysql_error());
while ($team = mysql_fetch_array($result)) {
  $team = $team["awayteam"];
  $teams[$team] = new Team($team);
}
$auto = new AutoPlayer();
echo $auto->GetRandomScore();
CloseConnection($link);
?>
