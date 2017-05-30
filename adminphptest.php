<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--
  Author: John Astill (c) 2002
-->
<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 20th March 2003
 * File  : adminphptest.php
 *********************************************************/
?>
<html>
<head>
<title>PHP Test</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>
<body class="MAIN">

  <table class="MAINTB">
    <tr>
      <!-- Left column -->
      <td class="TBLHEAD" colspan="2" align="CENTER">
        <font class="TBLHEAD">
        Check the PHP and MySQL configuration
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
      <td align="LEFT" class="CENTERCOL" width="500">
        <!-- Central Column -->
        <font class="TBLROW">
          If your ISP enabled the display of PHP Info, it will be displayed below.
          <p>
          <?php phpinfo() ?>
        </font>
      </td>
      <!-- Right Column -->
      <td class="RIGHTCOL" align="RIGHT">
      </td>
    </tr>
  </table>
</body>
</html>
