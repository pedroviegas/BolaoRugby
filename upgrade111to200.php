<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 8th January 2004
 * File  : upgrade111to200.php
 * This is a utility for upgrading the database from version 1.10
 * to version 1.11 
 *
 * 1 Backup your current files, especially your systemvars.php and
 *   common.css
 * 2 Backup your database data. This utility should not cause any problems
 *   but it does not hurt to play safe.
 * 3 Install the new files in the same directory as your original files
 * 4 Modify systemvars.php and common.css to match your configuration.
 * 5 Open this file in your browser to make the database changes.
 * 6 Remove this file from the server and any other file where the
 *   name starts with "upgrade" and ends with "php".
 *********************************************************/
   require_once "systemvars.php";
   require_once "dbasedata.php";

   // Create the table for storing ip addresses
   $query = "create table if not exists $dbaseName.$dbaseAddrData (lid int not null default 0, userid int not null , ipaddr varchar(32) not null, port varchar(8), ts timestamp not null, primary key(lid,userid, ipaddr));";
   $result = $dbase->query($query);

   // Create the table for groups
    $query = "create table if not exists $dbaseName.$dbaseGroups (lid int not null default 0, grp int not null, team varchar(30) not null, primary key(lid,grp,team));";
   $result = $dbase->query($query);

   // Create the table for group standings
    $query = "create table if not exists $dbaseName.$dbaseGroupStandings (lid int not null default 0, grp char not null, team varchar(30) not null, w int, d int, l int, f int, a int, pld int, pts int, primary key(lid,grp,team));";
   $result = $dbase->query($query);


   // Create the table for storing monthly winners
    $query = "create table if not exists $dbaseName.$dbaseMonthlyWinner (lid int not null default 0, userid int not null, month int not null, points int not null, pct int not null, primary key(lid,userid, month));";
   $result = $dbase->query($query);

   // Alter the match data to include penalties
   $query = "alter table $dbaseMatchData add column homepen smallint default 0";
   $result = $dbase->query($query);
   $query = "alter table $dbaseMatchData add column awaypen smallint default 0";
   $result = $dbase->query($query);
   $query = "alter table $dbaseMatchData add column gametype char default 'L'";
   $result = $dbase->query($query);
   $query = "alter table $dbaseMatchData add column competition int";
   $result = $dbase->query($query);

   ///////////////////////////////////////////////////////////
   // Change the standings table by adding the week, weekdate,
   // previous position and changing the primary key.
   ///////////////////////////////////////////////////////////
   $query = "alter table $dbaseStandings add column prevpos smallint default 0";
   $result = $dbase->query($query);
   $query = "alter table $dbaseStandings add column week smallint default 0 after userid";
   $result = $dbase->query($query);
   $query = "alter table $dbaseStandings add column weekdate datetime after week";
   $result = $dbase->query($query);
   $query = "alter table $dbaseStandings drop primary key";
   $result = $dbase->query($query);
   $query = "alter table $dbaseStandings add primary key (lid, userid, week)";
   $result = $dbase->query($query);

   // Change the text for the 24 hour clock to be correct.
   $query = "replace into $dbaseConfigData (lid,param,value,descr,ro) values ($leagueID,\"USE24HR\",'$Use24Hr',\"Display the time using the 12 hour clock, TRUE = 12 or FALSE = 24\",'N')";
   $result = $dbase->query($query);
   
   // Add the end of the week values.
   $query = "replace into $dbaseConfigData (lid,grp,param,value,descr,ro) values ($leagueID,3,'WEDAY','$WeekEndDay','The day that the week ends on. Used to calculate weekly stats. Sunday = 0.','N')";
   $result = $dbase->query($query);
   $query = "replace into $dbaseConfigData (lid,grp,param,value,descr,ro) values ($leagueID,3,'WETIME','$WeekEndTime','The time that the week ends on. Used to calculate weekly stats HH:MM:SS.','N')";
   $result = $dbase->query($query);
   
   // Add the bonus points to the match data.
   $query = "alter table $dbaseMatchData add column bonuspoints int default 0";
   $result = $dbase->query($query);

   // Add the last IP address for the user.
   $query = "alter table $dbaseUserData add column lastip varchar(20) default 0";
   $result = $dbase->query($query);

   // Add the last IP port for the user.
   $query = "alter table $dbaseUserData add column lastport varchar(8) default 0";
   $result = $dbase->query($query);

   // Add the last login time for the user.
   $query = "alter table $dbaseUserData add column lastlogin datetime";
   $result = $dbase->query($query);

   // Add the allow email flag for the user.
   $query = "alter table $dbaseUserData add column allowemail enum('Y','N') default 'N'";
   $result = $dbase->query($query);

   // Add the points for the user for the game
   $query = "alter table $dbasePredictionData add column points smallint default 0";
   $result = $dbase->query($query);

   // Add the current version to the config table
   $query = "update $dbaseConfigData set value='2.00', descr='The currently running version of the Prediction League scripts', ro='Y' where lid='$leagueID' and param='VERSION'";
   $result = $dbase->query($query);
   
   echo "The tables have been updated to version 2.00, for security purposes remove this file from your server!!!!!<br>";
?>
