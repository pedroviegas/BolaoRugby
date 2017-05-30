<?php 
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 9th December 2001
 * File  : sortfunctions.php
 ********************************************************/

  /*******************************************************
   * Try to figure out what the month is in the 
   * number of months (year*12)+month.
   *******************************************************/
  function GetThisMonth() {
    $year = date("Y");
    $month = date("m");

    return ($year*12) +$month;
  }

  /*******************************************************
   * Try to figure out what this week is.
   *******************************************************/
  function GetThisWeek() {
    global $dbaseMatchData, $leagueID, $dbase;

    // This assumes that week numbers do not jump all over the place
    $today = getTodaysDate();
    $query = "SELECT * FROM $dbaseMatchData WHERE matchdate>='$today' AND lid='$leagueID' ORDER BY matchdate LIMIT 1";
    $res = $dbase->query($query);
    if ($dbase->getNumberOfRows() <1) {
      $query = "SELECT * FROM $dbaseMatchData WHERE matchdate<='$today' AND lid='$leagueID' ORDER BY matchdate DESC LIMIT 1";
      $res = $dbase->query($query);
      if ($dbase->getNumberOfRows() <1) {
        return 1;
      }
    }
    $line = mysql_fetch_array($res);
    $thisWeek = $line["week"];
    return $thisWeek;
  }
 
  /*************************************************************************
   * HH:MM:SS
   *************************************************************************/
  function GetSecondsFromTime($offset) {
    //echo "Offset $offset<br>";
    $secs = 0;
    $secs += substr($offset,0,2) * 3600;
    $secs += substr($offset,3,2) * 60;
    $secs += substr($offset,5,2);
    return $secs;
  }

  /*************************************************************************
   * Get the day value from the given datetime. 
   * 0 = Sunday
   * 6 = Saturday.
   *************************************************************************/
  function GetDayAsNumFromUnixTimestamp($timestamp) {
    return date("w",$timestamp);
  }

  /*************************************************************************
   * Return the current game time (time with timezone offset) as a string
   * in the form YYYMMDD HH:MM:SS
   ************************************************************************/
  function GetCurrentDateWithTZOffsetAsDatetime() {
    global $timezoneOffset;
    return date("Ymd H:i:s",time()+($timezoneOffset*3600));
  }

  /*************************************************************************
   * Get the raw date from a datetime. This function extracts the date
   * fields from YYYY-MM-DD HH:MM:SS
   ************************************************************************/
  function GetRawDateFromDatetime($datetime) {
    return substr($datetime,0,10);
  }

  /*************************************************************************
   * Get the raw time from a datetime. This function extracts the time
   * fields from YYYY-MM-DD HH:MM:SS. The function applies the timezone
   * offset.
   ************************************************************************/
  function GetTimeFromDatetime($datetime) {
    global $Use24Hr;
    $datetime = TimeZoneOffset($datetime);
    if ($Use24Hr == "TRUE") {
      return substr($datetime,11,11);
    } else {
      return substr($datetime,11,8);
    }
  }
  
  /*************************************************************************
   * Change the time based on the configured timezone offset.
   * Datetime format = YYYY-MM-DD 
   * Show only the time not the date.
   ************************************************************************/
  function TimeOnlyZoneOffset($datetime) {
    global $timezoneOffset, $Use24Hr;
    $offs = 60*60*$timezoneOffset;
    if ($Use24Hr == "TRUE") {
      return date("h:i A",strtotime($datetime)+$offs);
    } else {
      return date("H:i",strtotime($datetime)+$offs);
    }
  }

  /*************************************************************************
   * Change the time (datetime) based on the configured timezone offset.
   * Datetime format = YYYY-MM-DD 
   ************************************************************************/
  function TimeZoneOffset($datetime) {
    global $timezoneOffset, $Use24Hr;
    $offs = 60*60*$timezoneOffset;
    if ($Use24Hr == "TRUE") {
      return date("Y-m-d h:i:s A",strtotime($datetime)+$offs);
    } else {
      return date("Y-m-d H:i:s",strtotime($datetime)+$offs);
    }
  }

  /*************************************************************************
   * Some functions that are always in the 24 hour clock. This is used 
   * for match modifications
   *************************************************************************/
  function GetTimeFromDatetime24hr($datetime) {
    $datetime = TimeZoneOffset24hr($datetime);
    return substr($datetime,11,11);
  }
  
  /*************************************************************************
   * Change the time based on the configured timezone offset.
   * Datetime format = YYYY-MM-DD 
   ************************************************************************/
  function TimeZoneOffset24hr($datetime) {
    global $timezoneOffset;
    $offs = 60*60*$timezoneOffset;
    return date("Y-m-d H:i:s",strtotime($datetime)+$offs);
  }

  /*************************************************************************
   * Change the written time based on the configured timezone offset.
   * This is used when a user enters the date, the date is converted to the
   * server date.
   * Datetime format = YYYY-MM-DD 
   ************************************************************************/
  function RevTimeZoneOffsetNo24Hour($datetime) {
    global $timezoneOffset, $Use24Hr;
    $offs = -1 *(60*60*$timezoneOffset);
    return date("Y-m-d H:i:s",strtotime($datetime)+$offs);
  }

  /*************************************************************************
   * Change the written time based on the configured timezone offset.
   * This is used when a user enters the date, the date is converted to the
   * server date.
   * Datetime format = YYYY-MM-DD 
   ************************************************************************/
  function RevTimeZoneOffset($datetime) {
    global $timezoneOffset, $Use24Hr;
    $offs = -1 *(60*60*$timezoneOffset);
    if ($Use24Hr == "TRUE") {
      return date("Y-m-d h:i:s A",strtotime($datetime)+$offs);
    } else {
      return date("Y-m-d H:i:s",strtotime($datetime)+$offs);
    }
  }

  /*************************************************************************
   * Get the screen formatted date and time from the timestamp
   * Datetime format = YYYYMMDDHHMMSS 
   ************************************************************************/
  function GetDatetimeFromTimestamp($timestamp) {
    return GetDateFromTimestamp($timestamp)." ".GetTimeFromTimestamp($timestamp);
  }

  /*************************************************************************
   * Get the screen formatted time from the timestamp
   * Datetime format = YYYYMMDDHHMMSS 
   ************************************************************************/
  function GetTimeFromTimestamp($timestamp) {
    $datetime = substr($timestamp,0,4)."-".substr($timestamp,4,2)."-".substr($timestamp,6,2)." ".substr($timestamp,8,2).":".substr($timestamp,10,2).":".substr($timestamp,12,2);
    return GetTimeFromDatetime($datetime);
  }

  /*************************************************************************
   * Get the screen formatted date from the timestamp
   * Datetime format = YYYYMMDDHHMMSS 
   ************************************************************************/
  function GetDateFromTimestamp($timestamp) {

    $datetime = substr($timestamp,0,4)."-".substr($timestamp,4,2)."-".substr($timestamp,6,2);
    return GetDateFromDatetime($datetime);
  }

  /*************************************************************************
   * Get the screen formatted date from the datetime
   * Datetime format = YYYY-MM-DD 
   ************************************************************************/
  function GetDateFromDatetime($datetime) {
    // Months is defined in the language file
    global $Months;
    
    // Allow for the TZ Offset
    $datetime = TimeZoneOffset($datetime);

    $day = substr($datetime,8,2);
    $month = substr($datetime,5,2);
    $month = $Months[$month];
    $year = substr($datetime,0,4);
    
    $date = "$day $month $year";

    return $date;
  }

  /***********************************************************
   * Date format YYYY-MM-DD 
   **********************************************************/
  function convertDateToScreenDate($datetime) {
    // Months is defined in the language file
    global $Months, $Use24Hr;

    $datetime = TimeZoneOffset($datetime);

    $day = substr($datetime,8,2);
    $month = substr($datetime,5,2);
    $month = $Months[$month];
    $year = substr($datetime,0,4);

    $date = "$day $month $year";

    return $date;
  }

  /***********************************************************
   * Incoming Datetime format YYYY-MM-DD HH:MM:SS
   **********************************************************/
  function convertDatetimeToScreenDate($datetime) {
    // Months is defined in the language file
    global $Months, $Use24Hr;

    $datetime = TimeZoneOffset($datetime);

    $day = substr($datetime,8,2);
    $month = substr($datetime,5,2);
    $month = $Months[$month];
    $year = substr($datetime,0,4);
    $hours = substr($datetime,11,2);
    $mins = substr($datetime,14,2);

    $date = "";
    if ($Use24Hr == "TRUE") {
      $am = substr($datetime,20,2);
      $date = "$day $month $year $hours:$mins $am";
    } else {
      $date = "$day $month $year $hours:$mins";
    }

    return $date;
  }

  /***********************************************************
   * Get todays date
   **********************************************************/
  function getTodaysDate() {
    return date("Y-m-d H:i:s");
  }
?>
