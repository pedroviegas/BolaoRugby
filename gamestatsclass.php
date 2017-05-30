<?php 
/*********************************************************
 * Author: John Astill (c) 2003
 * Date  : 31st January 2003
 * File  : gamestatsclass.php
 ********************************************************/

 /////////////////////////////////////////////////////////
 // Class for holding the results/stats for each entrant.
 /////////////////////////////////////////////////////////
 class GameStats {

   // Userid of entrant
   var $userid;

   // The number of exact results predicted.
   var $won;

   // The number of correct winning team predictions.
   var $drawn;

   // The number of entries where the result is incorrect.
   var $lost;

   // The number of correct score per team predictions. The value is the sum
   // of the individual scores.
   var $for;

   // The number of goals incorrectly predicted.
   var $against;

   // Goal difference.
   var $diff;

   // Points
   var $points;

   // Predictions
   var $predictions;

   // Percentage
   var $percentage;

   // Previous position.
   var $prevpos;

   // Previous week points
   var $prevWeekPoints;

   // Previous month points
   var $prevMonthPoints;

   /************************************************************
    * Constructor used for creating a reset gameStat for a user.
    * @param user the name of the entrant.
    ************************************************************/
   function GameStats($user) {
     $this->predictions = 0;
     $this->userid = $user;
     $this->won = 0;
     $this->drawn = 0;
     $this->lost = 0;
     $this->for = 0;
     $this->against = 0;
     $this->diff = 0;
     $this->points = 0;
     $this->prevWeekPoints = 0;
     $this->prevMonthPoints = 0;
     $this->percentage = 0;
     $this->prevpos = 1; // Everyone is in first place at the start
   }

   /************************************************************
    * Set the previous position value
    ************************************************************/
   function setPreviousPosition($prev) {
     $this->prevpos = $prev;
   }

   /************************************************************
    * Get the number of games played
    ************************************************************/
   function getPlayed() {
     $played = $this->won + $this->drawn + $this->lost;
     return $played;
   }

   /************************************************************
    * Get the points
    ************************************************************/
   function getPoints() {
     return $this->points;
   }

   /************************************************************
    * Get the users percentage for predictions
    ************************************************************/
   function getPercentage() {
     return $this->percentage;
   }
   
   /************************************************************
    * Update for the week.
    ************************************************************/
    function updateForWeek() {
      $this->prevWeekPoints = $this->points;
    }

   /************************************************************
    * Update for the month.
    ************************************************************/
    function updateForMonth() {
      $this->prevMonthPoints = $this->points;
    }

    function getPreviousMonthPoints() {
      return $this->prevMonthPoints;
    }

   /************************************************************
    * Function used to calculate points and goal differences.
    * The scoring is determined as follows:
    *   For an exact prediction
    *    o $corrScore points
    *    o for is incremented by the number of goals scored
    *      by each team.
    *   For a correct prediction
    *    o $corrResult points
    *    o for is incremented by the number of goals scored
    *      by each team that is predicted correctly.
    *    o against is incremented by the number of goals 
    *      incorrectly predicted. e.g if a result is 1-1 and
    *      the predicted score was 2-1, against would be incremented
    *      by 1.
    *   For an incorrect prediction
    *    o 0 points
    *    o for is incremented by the number of goals scored
    *      by each team that is predicted correctly.
    *    o against is incremented by the number of goals 
    *      incorrectly predicted. e.g if a result is 1-1 and
    *      the predicted score was 2-1, against would be incremented
    *      by 1.
    * @param predHome   The predicted home number of goals
    * @param predAway   The predicted away number of goals
    * @param actualHome The actual home number of goals
    * @param actualAway The actual away number of goals
    ************************************************************/
   function UpdateStats($predHome, $predAway, $actualHome, $actualAway, $bonuspoints) {
     global $corrScore, $incorrResult, $corrResult, $corrHomeScore, $corrAwayScore, $corrOneScore;
     // Increment the number of predictions.
     $this->predictions++;

     $correctScore = false;
     $orig = $this->points;
     // Determine if the correct score is predicted.
     if ($predHome == $actualHome && $predAway == $actualAway) {
       $correctScore = true;
       $this->won += 1;
       $this->points += $corrScore + $bonuspoints;
       $this->diff = $this->for - $this->against;
     }

     // Determine if the correct result is predicted. i.e. the correct
     // winning team or draw.
     if (($predHome > $predAway) && ($actualHome > $actualAway) ||
         ($predHome < $predAway) && ($actualHome < $actualAway) ||
         ($predHome == $predAway) && ($actualHome == $actualAway)) {
       $this->points += $corrResult;
       if ($correctScore == false) {
         $this->drawn += 1;
       }
     } else {
       $this->lost += 1;
       $this->points += $incorrResult;
     }

     // Now add points for getting the correct one score.
     if ($predAway == $actualAway || $predHome == $actualHome) {
       $this->points += $corrOneScore;
     }

     // Calculate the for and against values.
     if ($predHome == $actualHome) {
       $this->for += $predHome;
       $this->points += $corrHomeScore;
     } else {
       if ($predHome > $actualHome) {
         $this->against += $predHome - $actualHome;
       } else {
         $this->against += $actualHome - $predHome;
       }
     }

     if ($predAway == $actualAway) {
       $this->for += $predAway;
       $this->points += $corrAwayScore;
     } else {
       if ($predAway > $actualAway) {
         $this->against += $predAway - $actualAway;
       } else {
         $this->against += $actualAway - $predAway;
       }
     }

     $this->diff = $this->for - $this->against;

     $this->percentage = $this->points/$this->predictions;
   }

   /************************************************************
    * Calculate the points for the given game.
    ************************************************************/
   function CalculatePoints($predHome, $predAway, $actualHome, $actualAway, $bonus) {
     global $incorrResult, $corrScore, $corrResult, $corrHomeScore, $corrAwayScore, $corrOneScore;

     $points = 0;

     // Determine if the correct score is predicted.
     // This order is important as it makes correct score and
     // correct result exclusive.
     if ($predHome == $actualHome && $predAway == $actualAway) {
       $points = $corrScore + $bonus;
     } 

     // Determine if the correct result is predicted. i.e. the correct
     // winning team or draw.
     if (($predHome > $predAway) && ($actualHome > $actualAway) ||
         ($predHome < $predAway) && ($actualHome < $actualAway) ||
         ($predHome == $predAway) && ($actualHome == $actualAway)) {
       $points += $corrResult;
     } else {
       $points += $incorrResult;
     }

     // Now add points for getting the correct one score.
     if ($predAway == $actualAway || $predHome == $actualHome) {
       $points += $corrOneScore;
     }

     // Calculate the for and against values.
     if ($predHome == $actualHome) {
       $points += $corrHomeScore;
     } 

     if ($predAway == $actualAway) {
       $points += $corrAwayScore;
     } 

     return $points; 
   }
   
 }
/*************************************************************
* END OF CLASS
*************************************************************/
?>
