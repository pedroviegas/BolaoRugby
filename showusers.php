<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 23rd January 2002 (c)
 * File  : showusers.php
 * Desc  : Display a table with all the user details 
 *       : except the password.
 ********************************************************/
require "systemvars.php";
require "configvalues.php";
require "sortfunctions.php";
require "userfunctions.php";
require "security.php";

CheckAUserLoggedIn($User,"Attempt to view user data by someone not logged in.","index.php");

// If the user is not an admin user then they can't see this page.
if ($User->usertype < 4) {
  // Go back to the prediction index page.
  forward("index.php");
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset?>">
<title>
<?php echo "Users\n"?>
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">

<?php echo $HeaderRow ?>
<table class="MAINTB">
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
<td class="LEFTCOL">
<?php require "menus.php"?>
</td>
<td class="CENTERCOL">
<?php
  ShowUsers();
?>
</td>
<td class="RIGHTCOL">
  <?php require "loginpanel.php"?>

  <!-- Show the Prediction stats for the next match -->
  <?php ShowPredictionStatsForNextMatch(); ?>
  
  <!-- Competition Prize -->
  <?php require "prize.html"?>
</td>
</tr>
</table>

</body>
</html>
