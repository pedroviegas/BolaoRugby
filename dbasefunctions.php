<?php
/*********************************************************
 * Author: John Astill
 * Date  : 9th December
 * File  : dbasefunctions.php
 ********************************************************/

class Dbase {

  // MySQL Version number
  var $mysqlver;

  // Link number
  var $link;

  // Database username
  var $username;

  // Database password
  var $password;

  // Database host
  var $host;

  // Database name
  var $dbasename;

  // Last query
  var $lastquery;

  // Last result set.
  var $lastresults;

  // Last return value
  var $lastreturnvalue;

  // Is there a connection
  var $isconnected;

/////////////////////////////////////////////////////
// Constructor
/////////////////////////////////////////////////////
  function Dbase() {
    global $dbaseHost, $dbaseName, $dbaseUsername, $dbasePassword;

    $this->username = $dbaseUsername;
    $this->password = $dbasePassword;
    $this->host = $dbaseHost;
    $this->dbasename = "`$dbaseName`";

    $this->link = mysql_connect($this->host, $this->username, $this->password)
      or die("Could not connect to $this->host\n".mysql_error());

    $this->mysqlver = mysql_get_server_info();
  }

/////////////////////////////////////////////////////
// Accessor methods
/////////////////////////////////////////////////////
  
  // Get the link.
  function getVersion() {
    return $this->mysqlver;
  }
  // Get the link.
  function getLink() {
    return $this->link;
  }

  // Get the username.
  function getUsername() {
    return $this->username;
  }

  // Get the hostname.
  function getHost() {
    return $this->host;
  }

  // Get the dbasename.
  function getDbasename() {
    return $this->dbasename;
  }

  // Get the last result set.
  function getLastResultSet() {
    return $this->lastresults;
  }

  // Get the last return value.
  function getLastReturnValue() {
    return $this->lastreturnvalue;
  }

  // Get the number of rows in the last result set.
  function getNumberOfRows() {
    if($this->lastresults != false) {
      return mysql_num_rows($this->lastresults);
    }
    return 0;
  }

  // Is there a connection?
  function isConnected() {
    return $this->isconnected;
  }

  // Free the result
  function freeResult() {
    if ($this->lastresults != true && $this->lastresults != false) {
      mysql_free_result($this->lastresults);
    }
  }

  ///////////////////////////////////////////////////
  // Open the database connection.
  ///////////////////////////////////////////////////
  function open() {
    global $dbaseName;
    // Connecting, selecting database
    if ($this->link == "") {
      $this->link = mysql_connect($this->host, $this->username, $this->password)
        or die("Could not connect to $this->host\n".mysql_error());
    }

    mysql_query("USE ".$this->dbasename, $this->link)
        or die("Could not select database '$dbaseName' for link[$this->link]\n".mysql_error());

    $this->isconnected = true;

    return $this->link;
  }

  ///////////////////////////////////////////////////
  // Perform the query
  ///////////////////////////////////////////////////
  function query($query) {
    global $LogQueries;

    $this->lastquery = $query;
    $this->lastresults = mysql_query($query, $this->link) 
                           or die("Unable to execute query: $query<br>\r\n".mysql_error());
    if ($this->lastresults == false) {
      logMsg("Unable to execute query :$query\r\n".mysql_error());
    }
    if ($LogQueries == true) {
      logMsg("Performed : $query");
    }
    return $this->lastresults;
  }

  ///////////////////////////////////////////////////
  // Close the connection.
  ///////////////////////////////////////////////////
  function close() {
  }
}

/*******************************************************
* Open a connection to the database and select the
* database.
* The database name and connection information is
* taken from Global Configration files.
* @return TRUE if the connection is created 
* successfully
*******************************************************/
function OpenConnection() {

  // Allow access to the global table name.
  global $dbaseHost, $dbaseName, $dbaseUsername, $dbasePassword;

  // Connecting, selecting database
  $link = mysql_connect($dbaseHost, $dbaseUsername, $dbasePassword)
      or die("Could not connect\n".mysql_error());

  //mysql_select_db($dbaseName, $link)
  mysql_query("USE $dbaseName", $link)
      or die("Could not select database $dbaseName for link[$link]\n".mysql_error());

  return $link;
}

/*******************************************************
* Close the database connection.
* @param link - the link returned when the connection
*               was opened.
*******************************************************/
function CloseConnection($link) {
  // Closing connection
  mysql_close($link);
}

/*********************************************************
 * Compare the two given dates.
 * @param d1 the first date
 * @param d2 the second date
 * @return -1 if d1 < d2
 *          0 if d1 = d2
 *          1 if d1 > d2
 ********************************************************/
function CompareDates($d1, $d2) {
  if ($d1 < $d2) {
    return -1;
  }
  if ($d1 > $d2) {
    return 1;
  }
  return 0;
}

/*********************************************************
 * Compare the given date with the current date.
 * Strip the individual components from the date in the
 * format YYYY-MM-DD.
 * @param d1 the first date
 * @return -1 if d1 < Current date
 *          0 if d1 = Current date
 *          1 if d1 > Current date
 ********************************************************/
function CompareDate($d1) {
  $year = substr($d1,0,4);
  $month = substr($d1,5,2);
  $day = substr($d1,8,2);

  $currentyear = date("Y");
  $currentmonth = date("m");
  $currentday = date("d");

  // Test the year first
  if ($year < $currentyear) {
    return -1;
  }
  // Test the year first
  if ($year > $currentyear) {
    return 1;
  }

  // At this point the year is the same.
  // Test the month
  if ($month < $currentmonth) {
    return -1;
  }
  if ($month > $currentmonth) {
    return 1;
  }

  // Finally the day
  if ($day < $currentday) {
    return -1;
  }
  if ($day > $currentday) {
    return 1;
  }

  // They are equal
  return 0;
}

/*********************************************************
 * Compare the given datetime with the current datetime.
 * Strip the individual components from the date in the
 * format YYYY-MM-DD.
 * @param d1 the first date
 * @return -1 if d1 < Current date
 *          0 if d1 = Current date
 *          1 if d1 > Current date
 ********************************************************/
function CompareDatetime($date) {
  // If the date isn't today, return the CompareDate value.
  $result = CompareDate($date);
  if ($result != 0) {
    return $result;
  }

  $currentHours = date("H");
  $currentMinutes = date("i");
  $hours = substr($date,11,2);
  $minutes = substr($date,14,2);
  if ($hours < $currentHours) {
    return -1;
  } else if ($hours > $currentHours) {
    return 1;
  }

  if ($minutes < $currentMinutes) {
    return -1;
  } else if ($minutes > $currentMinutes) {
    return 1;
  }

  // Equal
  return 0;
}

function SetConfigParam($grp, $param, $desc, $value, $ro) {
  global $dbaseConfigData, $leagueID;
  $link = OpenConnection();

  $query = "replace into $dbaseConfigData (lid,grp, param, descr, value, ro) values ($leagueID,\"$grp\",\"$param\",\"$desc\",\"$value\",\"$ro\")";
  mysql_query($query) or die("Unable to set config var: $query\n".mysql_error());
}

/**************************************************************************
 * Creates the config table
 **************************************************************************/
function PopulateConfigTable() {
  global $corrResult, $corrHomeScore, $corrAwayScore, $corrScore, $PredictionLeagueTitle, $defaulticon, $homePage, $homePageTitle;
  global $allowMultipleUserPerEmail, $timezoneOffset, $Use24Hr, $reverseUserPredictions, $usersPerPage, $maxAdminUsers;
  global $NumMultFixts, $ViewUserPredictions, $ShowDivisions, $LockedGame;
  global $UploadIcons, $MaxIconFileSize, $PasswordEncryption, $UserDeleteAccount, $ViewFutureStats, $corrOneScore;
  global $incorrResult, $EnableShoutbox, $ShowWeeklyStats, $monthlyWinner, $ShowIconInStandings, $NumMonthlyWinners;

  // Scoring
  SetConfigParam(1,"CORR_HOME_SCORE","The number of points awarded for predicting the correct home score","$corrHomeScore","N");
  SetConfigParam(1,"CORR_AWAY_SCORE","The number of points awarded for predicting the correct away score","$corrAwayScore","N");
  SetConfigParam(1,"CORR_ONE_SCORE","The number of points awarded for predicting one correct score","$corrOneScore","N");
  SetConfigParam(1,"CORR_RESULT","Points for predicting the correct result.","$corrResult","N");
  SetConfigParam(1,"CORR_SCORE","Points for predicting the correct score.","$corrScore","N");
  SetConfigParam(1,"INCORR_RESULT","Points for predicting the incorrect result.","$incorrResult","N");
  SetConfigParam(1,"MWPOINTS","Points for the Monthly winner.","$monthlyWinner","N");

  // Title and descriptions
  SetConfigParam(2,"PRED_LEAGUE_TITLE","The title for the prediction league","$PredictionLeagueTitle","N");
  SetConfigParam(2,"DEF_ICON","The default icon for the users.","$defaulticon","N");
  SetConfigParam(2,"HOME_PAGE_URL","Your home page URL. This will be a link from the menu. If you leave this blank, no menu will show up","$homePage","N");
  SetConfigParam(2,"HOME_PAGE_TITLE","The title of your home page. Will show as the link on the menu.","$homePageTitle","N");
  SetConfigParam(2,"MULT_USERS","Set this if you want to allow multiple users to share the same email address. Values can be TRUE or FALSE","$allowMultipleUserPerEmail","N");
  SetConfigParam(2,"TZ_OFFSET","If your server is in a different time zone than your games, set this to the required offset","$timezoneOffset","N");
  SetConfigParam(2,"USE24HR","Display the time using the 12 hour clock, TRUE = 12 or FALSE = 24","$Use24Hr","N");
  SetConfigParam(2,"REV_USER_PREDS","Change the order in which the user sees their predictions. Values can be TRUE or FALSE","$reverseUserPredictions","N");
  SetConfigParam(2,"USERS_PER_PAGE","The number of users displayed in the table on one page.","$usersPerPage","N");
  SetConfigParam(2,"MAX_ADMIN_USERS","The maximum number of admin users allowed. Keep this to a minimum for security reasons.","$maxAdminUsers","N");
  SetConfigParam(3,"USE_MESSAGING","Enable the use of messaging between users. Values can be TRUE or FALSE","TRUE","N");
  SetConfigParam(3,"HIDE_0_PREDS","Hide the display of users with 0 predictions. Changing this value will require you to recalculate the standings to see the effect. Values can be TRUE or FALSE","TRUE","N");
  //SetConfigParam(3,"ISAUTO","Enable the use of automatic predictions. Values can be TRUE or FALSE","TRUE","N");
  SetConfigParam(3,"NUMMULTFIXTS","The number of multiple fixtures to display when adding multiple fixtures.","$NumMultFixts","N");
  SetConfigParam(3,"VIEWUSERPREDS","Can players see each others predictions, TRUE or FALSE","$ViewUserPredictions","N");
  SetConfigParam(3,"VIEWFUTURE","Should the statistics for future games be displayed.","$ViewFutureStats","N");
  SetConfigParam(3,"UPLOADICONS","Can users upload their own icons, TRUE or FALSE","$UploadIcons","N");
  SetConfigParam(3,"ICONSIZE","Maximum file size in KB of the icon","$MaxIconFileSize","N");
  SetConfigParam(3,"PASSWORDENCRYPT","Enable the encryption of user passwords. If you are enabling then the users must use the forgot password function to get their new password. TRUE or FALSE.","$PasswordEncryption","N");
  SetConfigParam(3,"LOCKEDGAME","Lock the game so that only admins can create users","$LockedGame","N");
  SetConfigParam(3,"SHOWDIVISIONS","Show the tabs in divisions instead of numbering","$ShowDivisions","N");
  SetConfigParam(3,"SHOWWEEKLY","Show the weekly standings and stats.","$ShowWeeklyStats","N");
  SetConfigParam(3,"USERDELACC","Can a user delete their own account.","$UserDeleteAccount","N");
  SetConfigParam(3,"SHOUTBOX","Enable the Shoutbox.","$EnableShoutbox","N");
  SetConfigParam(3,"SHOWICON","Show icons in the standings table.","$ShowIconInStandings","N");
  SetConfigParam(3,"NUMMONTHLYWIN","Number of users to show in monthly winners.","$NumMonthlyWinners","N");
  
  // Version
  SetConfigParam(4,"VERSION","The currently running version of the Prediction League scripts",VERSION,"Y");

}

?>
