<?php 
//ini_set('session.bug_compat_42', 1);
ini_set('display_errors', true);
//error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL);
/*********************************************************
 * Author: John Astill (c)
 * Date  : 10th December 2001
 * File  : systemvars.php
 * Desc  : Global data definitions. This is where the 
 *       : prediction league is configured for the 
 *       : specific installation.
 ********************************************************/
require_once "usertypeclass.php";
require_once "userclass.php";
require_once "msgclass.php";
require_once "logfunctions.php";
require_once "error.php";
require_once "version.php";

//////////////////////////////////////////
// System variable and configuration
// For user configurable including usernames and
// passwords see the file config.php
//////////////////////////////////////////
/////////////////////////////////////////////////
// User configuration
/////////////////////////////////////////////////
require_once "config.php";

/////////////////////////////////////////////////
// Default points for scoring, this can be 
// set in the config screen.
/////////////////////////////////////////////////
$corrHomeScore = 0;
$corrAwayScore = 0;
$corrOneScore = 0;
$corrResult = 1;
$corrScore = 3;
$incorrResult = 0;
$monthlyWinner = 0;

/*************************************************************
**************************************************************
* The following values should not be modified unless you
* REALLY know what you are doing.
**************************************************************
**************************************************************/
/*************************************************************
// Data Tables
// The following is where you define the names of your
// database tables.
*************************************************************/
//////////////////////////////////////////////////////////////
// The number of weekly winners to display
//////////////////////////////////////////////////////////////
$WeeklyWinnersCount = "5";

//////////////////////////////////////////////////////////////
// The number of Ups and downs to show
//////////////////////////////////////////////////////////////
$UpsAndDownsCount = "5";

//////////////////////////////////////////////////////////////
// Allow users to delete their own accounts.
//////////////////////////////////////////////////////////////
$UserDeleteAccount = "FALSE";

/*************************************************************
// This allows the default directory for session files
// to be changed. This should only be changed if you really
// know what you are doing.
// This should only need to be changed if you are having
// problems with sessions on your server.
// The directory you choose must exist and be writeable
// by the server.
*************************************************************/
$sessionFileDir = "";

/*************************************************************
// The abolute path for the scripts. This is only needed
// if the server does not support the PATH_TRANSLATED server
// variable. The value should be that of the absolute path 
// of your install directory. 
// e.g. /home/sites/benny/PredictionLeague
*************************************************************/
$FileDirectory = "";

/*************************************************************
// The directory that the icons are stored in.
*************************************************************/
$IconsDirectory = "icons";

/*************************************************************
// The League ID. Future support for multiple leagues.
*************************************************************/
$leagueID = "0";

/*************************************************************
// The name of the table to be used for the configuration Data.
// This value *MUST* be the same as the value defined when 
// creating the tables.
*************************************************************/
$dbaseConfigData = "plconfigdata";

/*************************************************************
// The name of the table to be used for the User Data.
// This value *MUST* be the same as the value defined when 
// creating the tables.
*************************************************************/
$dbaseUserData = "pluserdata";

/*************************************************************
// The name of the table to be used for the Prediction Data.
// This value *MUST* be the same as the value defined when 
// creating the tables.
*************************************************************/
$dbasePredictionData = "plpredictiondata";

/*************************************************************
// The name of the table to be used for the Match Data. This
// value *MUST* be the same as the value defined when creating
// the tables.
*************************************************************/
$dbaseMatchData = "plmatchdata";

/*************************************************************
// The name of the table to be used for the user messaging.
// This value *MUST* be the same as the value defined when 
// creating the tables.
*************************************************************/
$dbaseMsgData = "plmsgdata";

/*************************************************************
// The name of the table to be used for logging the users IP
// addresses.
// This value *MUST* be the same as the value defined when 
// creating the tables.
*************************************************************/
$dbaseAddrData = "pladdresslog";

/*************************************************************
// The name of the table to be used for the current standings.
// This value *MUST* be the same as the value defined when 
// creating the tables.
*************************************************************/
$dbaseStandings = "plstandings";

/************************************************************
 * Competitions (e.g Premier League, La Liga)
 ************************************************************/
$dbaseComps = "plcomps";
$dbaseGroups = "plgroups";
$dbaseGroupStandings = "plgroupstandings";

/************************************************************
 * Shoutbox
 ************************************************************/
$dbaseShout = "plshout";

/*************************************************************
** The home page for the Prediction League
*************************************************************/
$PLHome = "http://www.predictionfootball.com/";

/*************************************************************
** Should the users predictions be visible
*************************************************************/
$ViewUserPredictions = "FALSE";
$ViewFutureStats = "TRUE";

/*************************************************************
** The number of fixtures to show when making multiple
** fixtures.
*************************************************************/
$NumMultFixts = 12;

/*************************************************************
** Show the tabs as divisions instead of numbers
*************************************************************/
$ShowDivisions = "FALSE";

/*************************************************************
** The number of fixtures to show when making multiple
** fixtures.
*************************************************************/
$ShowWeeklyStats = "TRUE";

/*************************************************************
** The number of users to display on each page of the 
** prediction league. This is the default value. Each user
** can select their own.
*************************************************************/
$usersPerPage = 80;

/*************************************************************
** Show the icon in the standings table
*************************************************************/
$ShowIconInStandings = "TRUE";

/*************************************************************
** Number of monthly winners to show
*************************************************************/
$NumMonthlyWinners = "3";

/*************************************************************
** Enable the use of the shoutbox.
*************************************************************/
$EnableShoutbox = "TRUE";
$ShoutboxCols = "20";
$ShoutboxMsgs = "40";

/*************************************************************
** The timeout value for the session cookie. A value of 0
** will delete the cookie as soon as the browser is closed.
** Make sure that this value does not exceed the length of 
** sessions themselves.
*************************************************************/
$cookieTimeout = "0";

/////////////////////////////////////////////////
// Storage lengths
/////////////////////////////////////////////////
$userlen = 32; // Storage length for the username.
$passlen = 40; // Storage length for the password.
$fnamelen = 128; // Storage length for any filenames (or URLs).
$teamlen = 30; // Storage length for team names.
$emaillen = 60; // Storage length for email addresses.

/*************************************************************
** The maximum allowed number of admin users.
** If this value is increased it is essential that 
** the user is created as this could present a security 
** hole.
*************************************************************/
$maxAdminUsers = 1;

/*************************************************************
** Log the database queries. This is a debug option.
** Enabling this will cause lots of entries to be created in 
** the log file. This also assumes that the logfile is 
** enabled.
*************************************************************/
$LogQueries = false;

/////////////////////////////////////////////////////////
// Character set required. The default character set.
// This can be overridden in the language files.
/////////////////////////////////////////////////////////
$charset = "ISO8859-1";

/*************************************************************
** Maintain the logged in users data.
*************************************************************/
require_once "sessiondata.php";

?>
