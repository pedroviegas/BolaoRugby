<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : showpredictionsfordate.php
 * Desc  : Display the predictions for the given date.
 *       : the GET parameters date contains the date.
 ********************************************************/
require_once "systemvars.php";
require_once "dbasedata.php";
require_once "configvalues.php";
require_once "sortfunctions.php";
require_once "security.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>">
<title>
<?php
  $matchid = $_GET["matchid"];
  $date = $_GET["date"];
?>
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
<td class="LEFTCOL">
<?php require_once "loginpanel.php"?>
<?php require_once "menus.php"?>
</td>
<td class="CENTERCOL">
<?php
  GetPredictionsForMatch($matchid,$date);
?>
</td>
<td class="RIGHTCOL">

<!-- Show the Prediction stats for the next match -->
<?php 
  ShowPredictionStatsForMatch($matchid); 
?>

<!-- Competition Prize -->
<?php require_once "prize.html"?>
</td>
</tr>
</table>

</body>
</html>


