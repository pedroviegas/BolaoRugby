<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 17th March 2004
 * File  : mainview.php
 ********************************************************/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="<?php echo $direction; ?>">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>">
    <title>
    <?php echo $PredictionLeagueTitle ?>
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>
  <body class="MAIN">
    <table class="MAINTB">
      <tr>
        <td colspan="3" align="center">
          <?php echo $HeaderRow; ?>
        </td>
      </tr>
<!--
      <tr>
        <td colspan="3" bgcolor="red" align="center">
          <font class="TBLHEAD">
          <?php 
            //$MsgList->displayCurrentMessages(); TODO
          ?>
          </font>
        </td>
      </tr>
-->
  <?php if ($ErrorCode != "") { ?>
      <tr>
        <td colspan="3" bgcolor="red" align="center">
          <!-- Error Message Row -->
          <font class="TBLHEAD">
          <?php 
            echo $ErrorCode; 
            ClearErrorCode();
          ?>
          </font>
        </td>
      </tr>
  <?php 
    }
  ?>

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
        <td class="LEFTCOL">
          <!-- Show the login panel if required -->
          <?php require_once "loginpanel.php" ?>

          <!-- Menu -->
          <?php require_once "menus.php"?>
          <!-- End Menu -->
        </td>
        <!-- Central Column -->
        <td align="CENTER" class="CENTERCOL">
          <!-- Central Column -->
          <?php
            $controller->display();
          ?>
        </td>
        <!-- Right Column -->
        <td class="RIGHTCOL" align="RIGHT">
          <?php
            $week = 0;
            if (array_key_exists("week", $_GET)) {
              $week = $_GET["week"];
            }
            $month = 0;
            if (array_key_exists("month", $_GET)) {
              $month = $_GET["month"];
            }
          ?>
          <?php ShowPredictionStatsForNextMatch(); ?>
          <?php ShowWeeklyWinners($week); ?>
          <?php ShowUpsAndDowns($week); ?>
          <?php ShowMonthlyWinners($month); ?>
          <?php ShowShoutbox($week); ?>
          
          <!-- Competition Prize -->
          <?php require_once "prize.html"?>
        </td>
      </tr>
      <tr>
        <td align="center" colspan="3">
          <font class="TBLROW">
          <small>
          <?php 
            // Simple attempt at showing logged in users
            GetUsersLoggedIn(); 
          ?>
          </small>
          </font>
        </td>
      </tr>
      <tr>
        <td align="center" colspan="3">
<!-- The display of powered by prediction football must not be removed without explicit permission. 
     Unauthorized removal or modification of the powered by prediction football will require that
     the associated payment is made. -->
          <font class="VERSION">
            <small>Powered by </small><a class="VERSION" href="http://www.predictionfootball.com/">
            <small>Prediction Football </a><?php echo VERSION ?></small>
</font>
      <br>
      <font class="TBLROW">
        <small>
<?php 
  $endtime = time();
  $dur = $endtime - $starttime;
  echo $Txt_This_Page_Took.($dur+1).$Txt_Seconds_To_Create;
?>
        </small>
      </font>
        </td>
      </tr>
    </table>
  </body>
</html>

