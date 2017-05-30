<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 3rd January 2003
 * File  : adminconfig.php
 *********************************************************/
require "systemvars.php";
require "dbasedata.php";
require "configfunctions.php";
require "configvalues.php";
require "security.php";
require "sortfunctions.php";

// By now the admin user should be logged in.
if ($User->loggedIn == FALSE) {
  echo "Only an Admin User can perform this function";
  exit;
}
if (!CheckAdmin($User->usertype)) {
  echo "Only an Admin User can perform this function";
  exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>
Prediction Football Configuration
</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>

<body class="MAIN">
  <table class="MAINTB">
    <tr>
      <!-- Left column -->
      <td class="TBLHEAD" colspan="2" align="CENTER">
        <font class="TBLHEAD">
        Configure the game parameters [LeagueID <?php echo $leagueID;?>]
        </font>
      </td>
    </tr>
    <tr>
      <!-- Left column -->
      <td class="LEFTCOL">
        <!-- Menu -->
        <?php require "adminsetupmenu.php"?>
        <!-- End Menu -->
      </td>
      <!-- Central Column -->
      <td align="LEFT" class="CENTERCOL">
        <!-- Central Column -->
        <?php
          GetCurrentConfig();
        ?>
      </td>
      <!-- Right Column -->
      <td class="RIGHTCOL" align="RIGHT">
      </td>
    </tr>
  </table>
</body>
</html>
