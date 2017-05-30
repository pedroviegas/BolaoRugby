<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 8th July 2004
 * File  : upgrade200to201.php
 * This is a utility for upgrading the database from version E.08
 * to version 2.01
 *
 * 1 Backup your current files, especially your config.php and
 *   common.css
 * 2 Backup your database data. This utility should not cause any problems
 *   but it does not hurt to play safe.
 * 3 Install the new files in the same directory as your original files
 * 4 Modify config.php and common.css to match your configuration.
 * 5 Open this file in your browser to make the database changes.
 * 6 Remove this file from the server and any other file where the
 *   name starts with "upgrade" and ends with "php".
 *********************************************************/
   require_once "systemvars.php";
   require_once "dbasedata.php";

   // Add the current version to the config table
   $query = "update $dbaseConfigData set value=".VERSION.", descr='The currently running version of the Prediction League scripts', ro='Y' where lid='$leagueID' and param='VERSION'";
   $result = $dbase->query($query);
   
   echo "The tables have been updated to version ".VERSION.", for security purposes remove this file from your server!!!!!<br>";
?>
