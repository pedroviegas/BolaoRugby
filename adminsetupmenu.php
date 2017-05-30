<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 20th March 2003
 * File  : adminsetupmenu.php
 *********************************************************/
?>
<table width="140">
<tr>
<td class="TBLROW">
<font class="TBLROW">
<small>These steps must be followed for the creation of the league:</small>
</td>
</tr>
<td class="TBLROW">
<font class="TBLROW">
<small>
<ol>
<li>Change the database and table names in the configuration file systemvars.php . Choose the default language. Set the database username and password. This requires you to edit the file systemvars.php.
<li><a href="adminphptest.php">Check</a><br>the PHP version and MySQL support.
<li><a href="admincreatetables.php">Create</a> <br>the database, tables and setup the initial config data.
<li><a href="admincreateadminuser.php">Create</a> <br>the admin user.
<li><a href="adminshowtimeinfo.php">Check</a> <br>the server is running the correct time.
<li><a href="adminconfig.php">Configure</a> <br>the game parameters.
<li><a href="index.php">Login</a> <br> to the game.
<hr>
<li><a href="adminemptydbase.php">Empty</a> <br> the tables. This will delete all users, predictions and standings.
<li><a href="admindeletedbasetables.php">Delete</a> <br> the database and/or tables. This will remove the Prediction League database and tables from your database. Be very careful using this to delete databases as it will delete the database and all tables contained within. Make sure you understand what you are doing. Backup your database prior to using this command.
</ol>
</small>
</td>
</tr>
</table>
