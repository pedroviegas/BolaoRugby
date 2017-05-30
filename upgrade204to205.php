<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 26th July 2004
 * File  : upgrade204to205.php
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

  /**************************************************************
   * Set a value if it does not already exist
   **************************************************************/
  function setValueIfNotExists($value,$query) {
    global $dbase, $leagueID, $dbaseConfigData;
    $result = $dbase->query("select * from $dbaseConfigData where lid='$leagueID' and param='$value'");
    if ($dbase->getNumberOfRows() > 0) {
      return;
    }
    $result = $dbase->query($query);
  }

  /**************************************************************
   * Run a simple query.
   **************************************************************/
  function runQuery($query) {
    global $dbase;
    $result = $dbase->query($query);
  }

  // Add SHOWDIVISIONS
  setValueIfNotExists('SHOWDIVISIONS',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','SHOWDIVISIONS','$ShowDivisions','Show the tabs as divisions instead of numbering.')");

  // Add SHOWWEEKLY
  setValueIfNotExists('SHOWWEEKLY',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','SHOWWEEKLY','$ShowWeeklyStats','Show the weekly standings and winners.')");

  // Add the current version to the config table
  runQuery("update $dbaseConfigData set value='2.05' where lid='$leagueID' and param='VERSION'");
   
  echo "The tables have been updated to version 2.05. Check your configuration values as some may have changed. For security purposes remove this file from your server!!!!!<br>";

?>
