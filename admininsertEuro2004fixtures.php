<?php
/*******************************************************
 * First round fixtures for Euro 2004
 *******************************************************/
require_once "systemvars.php";
require_once "dbasefunctions.php";

$link = OpenConnection();

$Txt_Portugal = "Portugal";
$Txt_Greece = "Greece";
$Txt_Spain = "Spain";
$Txt_Russia = "Russia";
$Txt_Switzerland = "Switzerland";
$Txt_France = "France";
$Txt_England = "England";
$Txt_Croatia = "Croatia";
$Txt_Denmark = "Denmark";
$Txt_Italy = "Italy";
$Txt_Sweden = "Sweden";
$Txt_Bulgaria = "Bulgaria";
$Txt_Netherlands = "Holland";
$Txt_Germany = "Germany";
$Txt_Latvia = "Latvia";
$Txt_CzechRepublic = "Czech Republic";


// Create the groups table
//$query = "create table if not exists $dbaseGroups (lid int not null default 0, grp char not null, team varchar(30), primary key(lid, grp, team))";
//$res = mysql_query($query) or die ("Dead : $query.".mysql_error());
$res = TRUE;

if ($res != FALSE) {
  // Set the groups
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'A', '$Txt_Portugal')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'A', '$Txt_Greece')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'A', '$Txt_Spain')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'A', '$Txt_Russia')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'B', '$Txt_Switzerland')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'B', '$Txt_France')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'B', '$Txt_England')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'B', '$Txt_Croatia')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'C', '$Txt_Denmark')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'C', '$Txt_Italy')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'C', '$Txt_Sweden')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'C', '$Txt_Bulgaria')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'D', '$Txt_Netherlands')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'D', '$Txt_Germany')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'D', '$Txt_Latvia')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
  $query = "insert into $dbaseGroups (lid, grp, team) values ($leagueID, 'D', '$Txt_CzechRepublic')";
  mysql_query($query) or die ("Dead : $query.".mysql_error());
}

$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 1,'$Txt_Portugal','$Txt_Greece','20040612 17:00:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 2,'$Txt_Spain','$Txt_Russia','20040612 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 3,'$Txt_Greece','$Txt_Spain','20040616 17:00:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 4,'$Txt_Russia','$Txt_Portugal','20040616 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 5,'$Txt_Spain','$Txt_Portugal','20040620 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 6,'$Txt_Russia','$Txt_Greece','20040620 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());

$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 7,'$Txt_Switzerland','$Txt_Croatia','20040613 17:00:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 8,'$Txt_France','$Txt_England','20040613 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 9,'$Txt_England','$Txt_Switzerland','20040617 17:00:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 10,'$Txt_Croatia','$Txt_France','20040617 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 11,'$Txt_Croatia','$Txt_England','20040621 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 12,'$Txt_Switzerland','$Txt_France','20040621 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());

$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 13,'$Txt_Denmark','$Txt_Italy','20040614 17:00:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 14,'$Txt_Sweden','$Txt_Bulgaria','20040614 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 15,'$Txt_Bulgaria','$Txt_Denmark','20040618 17:00:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (lid, matchid, hometeam, awayteam, matchdate) values ($leagueID, 16,'$Txt_Italy','$Txt_Sweden','20040618 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate) values (17,'$Txt_Italy','$Txt_Bulgaria','20040622 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate) values (18,'$Txt_Denmark','$Txt_Sweden','20040622 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());

$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate) values (19,'$Txt_Germany','$Txt_Netherlands','20040615 17:00:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate) values (20,'$Txt_CzechRepublic','$Txt_Latvia','20040615 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate) values (21,'$Txt_Latvia','$Txt_Germany','20040619 17:00:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate) values (22,'$Txt_Netherlands','$Txt_CzechRepublic','20040619 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate) values (23,'$Txt_Netherlands','$Txt_Latvia','20040623 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate) values (24,'$Txt_Germany','$Txt_CzechRepublic','20040623 19:45:00')";
mysql_query($query) or die ("Dead : $query.".mysql_error());

// Knockout stages
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate, gametype) values (25,'Winner Group A','Runner Up Group B','20040624 19:45:00', 'K')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate, gametype) values (26,'Winner Group B','Runner Up Group A','20040625 19:45:00', 'K')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate, gametype) values (27,'Winner Group C','Runner Up Group D','20040626 19:45:00', 'K')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate, gametype) values (28,'Winner Group D','Runner Up Group C','20040627 19:45:00', 'K')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate, gametype) values (29,'WIN25','WIN27','20040630 19:45:00', 'K')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate, gametype) values (30,'WIN26','WIN28','20040701 19:45:00', 'K')";
mysql_query($query) or die ("Dead : $query.".mysql_error());
$query = "insert into $dbaseMatchData (matchid, hometeam, awayteam, matchdate, gametype) values (31,'WIN29','WIN30','20040704 19:45:00', 'K')";
mysql_query($query) or die ("Dead : $query.".mysql_error());

CloseConnection($link);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Import
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">
    <table>
      <tr>
        <td class="TBLHEAD" colspan="4" align="CENTER">
          <font class="TBLHEAD">
            Insert Euro 2004 fixtures
          </font>
        </td>
      </tr>
      <tr>
        <td class="LEFTCOL">
          <font class="TBLHEAD">
            <?php require "adminsetupmenu.php"; ?>
          </font>
        </td>
        <td class="CENTERCOL" colspan="3" align="CENTER">
          <font class="TBLROW">
The fixtures have been inserted. Double check the kickoff times are they should be stored in server time. The kickoff time will appear on the fixtures pages with the correct time zone.
          </font>
        </td>
      </tr>
    </table>
  </body>
</html>

