<?php 
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 9th December 2001
 * File  : autopredict.php
 ********************************************************/

  /*****************************************************************
   * Make the auto predictions.
   * This is only used if enabled, it will cause predictions to be 
   * made for all users that have chosen to enable this feature and
   * have not yet made a prediction for a fixture.
   *****************************************************************/
  function MakeAutoPredictions() {
    global $autoPredict,$leagueID;
  
    // If auto predictions are disabled then do nowt.
    if ($autoPredict == "FALSE") {
      return;
    }

    $now = date("Y-m-d H:i:s");

    // Unplayed Matches with no predictions for players or predictions with 
    // auto flag set.

    // for each player that has enabled auto predictions get a list of 
    // missing predictions and create an auto prediction.
    $query = "select * from $dbaseUserData where lid='$leagueID' and isauto='Y'";
    $players = mysql_query($query)
      or die("Unable to determine auto players $query<br>\n".mysql_error());
    while ($player = mysql_fetch_array($players)) {
      // For this player get the outstanding predictions. This is 
      // matches where the player has not predictions or the predictions
      // are already predicted automatically
      $uid = $player["UserID"];
      $q2 = "select * from $dbaseMatchData where matchdate>'$now' and lid='$leagueID'";
      $r2 = mysql_query()
        or die ("Unable to get matches: $q2<br>\n".mysql_error());
      while ($match = mysql_fetch_array($r2)) {
        $mid = $match["matchid"];
        $q3 = "select * from $dbasePredictionData where matchid='$mid' and userid='$uid'";
        $r3 = mysql_query(  )
          or die("Unable to get predictions: $q3<br>\n".mysql_error());
        if (mysql_num_rows($r3) == 0) {
          // No predictions, therefore make one.
        } else {
          // If the prediction is auto, then make one
          $predict = $mysql_fetch_array($r3);
          $isauto = $predict["isauto"];
          if ($isauto == 'Y') {
            // Make prediction
          }
        }
      }
    }
  }
?>
