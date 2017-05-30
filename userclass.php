<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 18th July 2003
 * File  : userclass.php
 * Desc  : Class representing a user/player in the 
 *       : prediction league.
 ********************************************************/
class User {
  // The league ID for this user
  var $lid;

  // The basedir for this league
  var $basedir;

  // The id number the user is identified by.
  var $userid;

  // The name the user is currently using.
  var $username;

  // The users password.
  var $pwd;

  // The email address of the user.
  var $emailaddr;

  // The URL of the icon to be used by the user.
  var $icon;

  // The priveleges of the user.
  var $usertype;

  // the current language the user is logged in with
  var $lang;

  // The day the user created the record.
  var $createdate;

  // Flag to indicate whether the user is logged in.
  var $loggedIn;

  // Default scores used when no prediction is made
  var $dflths;
  var $dfltas;

  // Show email address
  var $allowemail;

  // If the user has auto predict enabled
  var $auto;

  // Last IP info
  var $lastip;
  var $lastport;

  // Last login
  var $lastlogin;

  // Show standings
  var $showstandings;

  // Contructor for the user class.
  function User() {
    global $baseDirName, $leagueID;

    $this->userid = "";
    $this->username = "";
    $this->pwd = "";
    $this->emailaddr = "";
    $this->icon = "";
    $this->usertype = "0";
    $this->createdate = "";
    $this->lang = "english";
    $this->loggedIn = FALSE;
    $this->dflths = 0;
    $this->dfltas = 0;
    $this->auto = "N";
    $this->lastip = "";
    $this->lastport = "";
    $this->lastlogin = "";
    $this->allowemail = "N";
    $this->showstandings = "N";

    // Used to make sure the user is in the correct league
    $this->lid = $leagueID;
    $this->basedir = $baseDirName;
  }

////////////////////////////////////////////
// Accessors.
////////////////////////////////////////////
  function setShowStandings($standings) {
    $this->showstandings = $standings;
  }

  function getShowStandings() {
    return $this->showstandings;
  }

  function getLang() {
    return $this->lang;
  }

  function setIcon($icon) {
    $this->icon = $icon;
  }

  function getIcon() {
    return $this->icon;
  }

  function getLastPort() {
    return $this->lastport;
  }

  function getCreateDate() {
    return $this->createdate;
  }

  function isAdmin() {
    return CheckAdmin($this->usertype);
  }

  function getUserType() {
    return $this->usertype;
  }

  function setEmailAddress($email) {
    $this->emailaddr = $email;
  }

  function setLanguage($lang) {
    $this->lang = $lang;
  }

  function getEmailAddress() {
    return $this->emailaddr;
  }

  // This one is an oddity as we do not want to store the password in
  // the session data.
  function getPassword() {
    global $dbase, $dbaseUserData, $leagueID;

    $uid = $this->getUserID();

    $query = "select password from $dbaseUserData where lid='$leagueID' and userid='$uid'";
    $res = $dbase->query($query);
    $line = mysql_fetch_array($res);
    $pwd = $line["password"];
    return $pwd;
  }

  function getUserID() {
    return $this->userid;
  }

  function getUsername() {
    return $this->username;
  }

  function setUsername($uname) {
    $this->username = $uname;
  }

  function setAllowEmail($ae) {
    $this->allowemail = $ae;
  }

  function getAllowEmail() {
    return $this->allowemail;
  }

  function getLastLogin() {
    return $this->lastlogin;
  }

  function getLastIp() {
    return $this->lastip;
  }

  function getAllIp() {
    global $dbase, $dbaseAddrData, $leagueID;

    // Get an array of the IP addresses.
    $query = "SELECT *, unix_timestamp(ts) as ut FROM $dbaseAddrData WHERE userid='$this->userid' and lid='$leagueID'";
    $result = $dbase->query($query);
    
    $ipAddresses = array();

    while ($ipRow = mysql_fetch_array($result)) {
      $addr = $ipRow["ipaddr"];
      $port = $ipRow["port"];
      $timestamp = $ipRow["ut"];
      $ipAddresses[$addr] = "$addr:$port $timestamp";
    }
    
    return $ipAddresses;
  }

////////////////////////////////////////////
// Dbase functions.
////////////////////////////////////////////
  function CreateUserFromResultSet($result) {
    $rs = mysql_fetch_array($result);
    $user = new User();
    $user->userid = $rs["userid"];
    $user->username = $rs["username"];
    $user->pwd = $rs["password"];
    $user->emailaddr = $rs["email"];
    $user->icon = $rs["icon"];
    $user->usertype = $rs["usertype"];
    $user->lang = $rs["lang"];
    $user->showstandings = $rs["showstandings"];
    $user->lastip = $rs["lastip"];
    $user->lastport = $rs["lastport"];
    $user->lastlogin = $rs["lastlogin"];
    $user->createdate = $rs["since"];
    $user->allowemail = $rs["allowemail"];

    return $user;
  }

  /*******************************************************
   * Write the user data to the database.
   *******************************************************/
  function persist() {
    global $dbase, $dbaseUserData, $leagueID;

    $query = "UPDATE $dbaseUserData SET username='$this->username', email='$this->emailaddr', icon='$this->icon', lang='$this->lang', usertype='$this->usertype', showstandings='$this->showstandings', allowemail='$this->allowemail' WHERE lid=$leagueID and userid=$this->userid";
    $result = $dbase->query($query);
  }

  /*******************************************************
   * Create a user by getting the details from the 
   * database for the given username.
   *******************************************************/
  function CreateUserFromDB($userid) {
    global $dbase, $leagueID, $dbaseUserData;
    
    $query = "select * from $dbaseUserData where userid='$userid' and lid='$leagueID'";
    $result = $dbase->query($query);
    return User::CreateUserFromResultSet($result);
  }

  // Log the current IP address of the user.
  // This should get inserted into both the 
  // user data and the ip address log.
  function updateLogInfo() {
    global $_SERVER, $dbase, $User, $dbaseAddrData, $dbaseUserData, $leagueID;

    $this->lastip = $_SERVER["REMOTE_ADDR"];
    if (array_key_exists("REMOTE_PORT", $_SERVER)) {
      $this->lastport = $_SERVER["REMOTE_PORT"];
    } else {
      $this->lastport = "Unknown";
    }

    $this->lastlogin = GetCurrentDateWithTZOffsetAsDatetime();

    $addr = "$this->lastip:$this->lastport";

    $query = "UPDATE $dbaseUserData SET lastip='$addr', lastlogin='$this->lastlogin' where userid='$User->userid' and lid='$leagueID'";
    $dbase->query($query);

    $query = "INSERT IGNORE INTO $dbaseAddrData (lid,userid,ipaddr,port) values ('$leagueID','$this->userid','$this->lastip','$this->lastport');";
    $dbase->query($query);

  }
}
?>
