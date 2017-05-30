<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 8th July 2004
 * File  : upgrade200to201.php
 * This is a utility for upgrading the database from version 2.00
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

  // Make sure that the missing config values are included
  // Add CORR_ONE_SCORE
  setValueIfNotExists('CORR_ONE_SCORE',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','CORR_ONE_SCORE','$corrOneScore','The number of points awarded for predicting one correct score')");

  // Add SHOWDIVISIONS
  setValueIfNotExists('SHOWDIVISIONS',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','SHOWDIVISIONS','$ShowDivisions','Show the tabs as divisions instead of numbering.')");

  // Add USERDELACC
  setValueIfNotExists('USERDELACC',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','USERDELACC','$UserDeleteAccount','Can a user delete their own account.')");

  // Add VIEWFUTURE
  setValueIfNotExists('VIEWFUTURE',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','VIEWFUTURE','$ViewFutureStats','Should the statistics for future games be displayed.')");

  // Add WEDAY
  setValueIfNotExists('WEDAY',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','WEDAY','$WeekEndDay','The day that the week ends on. Used to calculate weekly stats. Sunday = 0.')");

  // Add WETIME
  setValueIfNotExists('WETIME',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','WETIME','$WeekEndTime','The time that the week ends on. Used to calculate weekly stats. HH:MM:SS.')");

  // Rem CORR_MARGIN
  runQuery("delete from $dbaseConfigData where lid='$leagueID' and param='CORR_MARGIN'");

   // Add the current version to the config table
  runQuery("update $dbaseConfigData set value='2.01' where lid='$leagueID' and param='VERSION'");
   
  echo "The tables have been updated to version 2.01. Check your configuration values as some may have changed. For security purposes remove this file from your server!!!!!<br>";

?>
