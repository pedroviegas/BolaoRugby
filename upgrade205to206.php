<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 13th September 2004
 * File  : upgrade205to206.php
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

  function setInitialWeekValues() {
  }

  // Add SHOWWEEKLY
  setValueIfNotExists('SHOWWEEKLY',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','SHOWWEEKLY','$ShowWeeklyStats','Show the weekly standings and winners.')");

  // Add SHOWWEEKLY
  setValueIfNotExists('MWPOINTS',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','MWPOINTS','$monthlyWinner','Points for the Monthly winner.')");

  // Add Show icon
  setValueIfNotExists('SHOWICON',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','SHOWICON','$ShowIconInStandings','Show the icon in the standings table.')");

  // Add number of monthly winners
  setValueIfNotExists('NUMMONTHLYWIN',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','NUMMONTHLYWIN','$NumMonthlyWinners','Number of users to display in monthly winners.')");

  // Add the display position to the standings
  runQuery("alter table $dbaseMatchData add column week smallint default 0");

  // Add the display position to the standings
  runQuery("alter table $dbaseStandings add column displaypos smallint not null default 0");

  // Remove the weekend time and day entries.
  runQuery("delete from $dbaseConfigData WHERE lid='$leagueID' AND (param='WEDAY' OR param='WETIME')");

  // Add the monthdate to the standings table
  runQuery("alter table $dbaseStandings add column monthdate int not null default 0");
  runQuery("alter table $dbaseStandings add column standtype enum ('w','m') default 'w' not null");
  runQuery("alter table $dbaseStandings add column prevmpts int default 0 not null");
  runQuery("alter table $dbaseStandings add column prevwpts int default 0 not null");
  runQuery("alter table $dbaseStandings drop primary key");
  runQuery("alter table $dbaseStandings add primary key (lid,userid,week,monthdate)");
  $dbaseMonthlyWinner = 'plmonthlywinner';
  runQuery("drop table $dbaseMonthlyWinner");

  // Add the current version to the config table
  runQuery("update $dbaseConfigData set value='2.06' where lid='$leagueID' and param='VERSION'");

  // Set the week based on the start date. This is a one off to set some initial values.
  setInitialWeekValues();
   
  echo "The tables have been updated to version 2.06. Check your configuration values as some may have changed. For security purposes remove this file from your server!!!!!<br>";

?>
