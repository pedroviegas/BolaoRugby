<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 30th Jan 2003
 * File  : sessiondata.php
 * Desc  : Data that is required during a logged in 
 *       : session. Matters not if the session uses 
 *       : cookies or PHP sessions.
 ********************************************************/

/*************************************************************
** Store User data in session information
** needs to be global
*************************************************************/
$User = new User();

$SID="";
if (array_key_exists("sid",$_GET)) {
  $SID = $_GET["sid"];
}

// For some servers it is necessary to update the session file
// directory.
if ($sessionFileDir != "") {
  session_save_path($sessionFileDir);
}

// Set the cookie timeout.
session_set_cookie_params($cookieTimeout);

// If we have an SID already, then use it to start the session.
if ($SID == "") {
  session_start();
  $SID = session_id();
} else {
  session_start($SID);
}

// Check the user is in the session
if (array_key_exists("User",$_SESSION)) {
  $User = $_SESSION["User"];
}

// Make sure that the session is related to this instance of the league. If not
// then log the user out and throw an error.
if (($User->basedir != $baseDirName) || ($User->lid != $leagueID)) {
  logMsg("User with session data already existing from another league. Other league basedir=".$User->basedir.". Logging the user out.");
  UnregisterUser();
  // Make sure that the user data is reloaded
  header("Location: index.php");
  exit;
}

// These are needed to suppress warnings in the language files.
$userid = $User->userid;
$username = $User->username;
$email = "";
$password = "";

// Put the message list in the session.
$MsgList = new MessageList();
if (array_key_exists("MsgList",$_SESSION)) {
  $MsgList = $_SESSION["MsgList"];
} else {
  $MsgList = new MessageList();
  RegisterMsgList();
}

$ErrorCode = "";
if (array_key_exists("ErrorCode",$_SESSION)) {
  $ErrorCode = $_SESSION["ErrorCode"];
}

session_unregister("ErrorCode");
//unset($_SESSION["ErrorCode"]);

/*************************************************************
** Store MsgList in session information
** needs to be global
*************************************************************/
function RegisterMsgList() {
  global $MsgList;
  //if (FALSE == session_register("MsgList")) {
    //die("Can't register Message List");
  //}
  $_SESSION["MsgList"] = $MsgList;
}

/*************************************************************
** Store User data in session information
** needs to be global
*************************************************************/
function RegisterUser() {
  global $User;
  //if (FALSE == session_register("User")) {
    //die("Can't register User");
  //}
  $_SESSION["User"] = $User;
}

/*************************************************************
** Delete User data in session information
** needs to be global
*************************************************************/
function UnregisterUser() {
  global $User;

  $_SESSION["USER"] = "";
  //unset($User);
  //unset($_SESSION["USER"]);
  //unset($GLOBALS["User"]);
  session_unregister("User");
}

?>
