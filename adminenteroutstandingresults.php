<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 22nd July 2003 (c)
 * File  : adminenteroutstandingresults.php
 * This page allows an administrator to enter all
 * the outstanding results.
 ********************************************************/
require "systemvars.php";
require "configvalues.php";
require "sortfunctions.php";
require "security.php";

// If the user is not an admin user then they can't see this page.
if ($User->usertype < 4) {
  logMsg("Unauthorised user $User->username trying enter results.");
  // Go back to the prediction index page.
  forward("index.php");
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>">
<title>
Result Administration
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">

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
<td valign="top">
<?php 
  require "menus.php";
?>
</td>
<td valign="TOP">
  <?php GetOutstandingResults(); ?>
</td>
        <!-- Right Column -->
        <td class="RIGHTCOL" align="RIGHT">
          <!-- Show the login panel if required -->
          <?php require "loginpanel.php" ?>

          <!-- Show the Prediction stats for the next match -->
          <?php ShowPredictionStatsForNextMatch(); ?>
          
          <!-- Competition Prize -->
          <?php require "prize.html"?>
</td>
</tr>
</table>
</body>
</html>
