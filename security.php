<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 9th December
 * File  : security.php
 * Desc  : usertypes:
 *       :     1 - Normal User
 *       :     2 - Priveleged User
 *       :     4 - Admin User
 *       :     8 - Root User
 *
 ********************************************************/

  require "encryptionclass.php";
  
  /*******************************************************
   * Log in the user with the given password.
   *******************************************************/
  function login($username, $pwd) {
    global $SID;
    $location = "index.php?sid=$SID&cmd=mypreds"; 
    loginwithtarget($username,$pwd,$location);
  }
  
  /*******************************************************
   * Log in the user with the given password and forward
   * to the given location.
   *******************************************************/
  function loginwithtarget($username, $pwd, $location) {
    global $User, $SID, $Txt_Username_Or_Password_Invalid;

    // If the user login has timed out, forward to the
    // index. If the user login is successful then 
    // update the User info.
    if (CheckUserLogin($username, $pwd) == FALSE) {
      /* Redirect browser to Prediction Index web site */
      ErrorRedir($Txt_Username_Or_Password_Invalid,"index.php?sid=$SID"); 
      /* Make sure that code below does not get executed when we redirect. */
      exit; 
    }

    RegisterUser($User);

    $User->updateLogInfo();

    if ($User->getShowStandings() == 'Y') {
      $location = "index.php?sid=$SID&cmd=mypos"; 
    }
    /* Redirect browser to user predictions web site */
    header("Location: $location"); 
    /* Make sure that code below does not get executed when we redirect. */
    exit; 

  }

  /*******************************************************
   * Allow for authentication to be performed in another application. 
   *******************************************************/
  function ExternalLogin() {
    global $User, $SID;

    // If the user login has timed out, forward to the
    // index. If the user login is successful then 
    // update the User info.
    if (ForceUserLogin($username, $pwd) == FALSE) {
      /* Redirect browser to Prediction Index web site */
      //ErrorRedir("Username or password invalid","index.php?sid=$SID"); 
      /* Make sure that code below does not get executed when we redirect. */
      exit; 
    }

    RegisterUser($User);

    $User->updateLogInfo();
  }

 /********************************************************
  * Check if the given user has admin priveleges.
  * @param perms the users current permissions.
  *******************************************************/
  function CheckAdmin($perms) {
    global $dbaseUserData;

    $NormalUser = 1;
    $PrivelegedUser = 2;
    $AdminUser = 4;
    $RootUser = 8;
    
    return $perms >= $AdminUser;
  }

 /********************************************************
  * Check if the given user is logged in.
  * @param user the user to check.
  * @param pwd the password of the user to check.
  *******************************************************/
  function ForceUserLogin($username, $pwd) {
    // Needs global include SystemVars.php
    global $dbaseUserData, $User,$leagueID;

    // Default the login to false.
    $User = new User;
    $User->loggedIn = FALSE;
    $User->usertype = 1;

    $link = OpenConnection();
    if ($link == FALSE) {
      return FALSE;
    }

    $userquery = "SELECT * FROM $dbaseUserData where username = \"$username\" and lid='$leagueID'";
    $userresult = mysql_query($userquery)
        or die("Query failed: $userquery");
    if ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      $User->userid =  $userline["userid"];
      $User->username = stripslashes($username);
      $User->emailaddr = $userline["email"];
      $User->icon = $userline["icon"];
      $User->usertype = $userline["usertype"];
      $User->createdate = $userline["since"];
      $User->lang = $userline["lang"];
      $User->dflths = $userline["dflths"];
      $User->dfltas = $userline["dfltas"];
      $User->lang = $userline["lang"];
      $User->auto = $userline["auto"];
      $User->loggedIn = TRUE;

      return TRUE;
    }
    CloseConnection($link);
    return FALSE;
  }

 /********************************************************
  * Check if the given user is logged in.
  * @param user the user to check.
  * @param pwd the password of the user to check.
  *******************************************************/
  function CheckUserLogin($username, $pwd) {
    // Needs global include SystemVars.php
    global $dbaseUserData, $User,$leagueID, $dbase;

    // if encryption enabled encrypt the password
    $encr = new Encryption($pwd);
    $pwd = $encr->Encrypt($pwd);

    // Default the login to false.
    $User = new User;
    $User->loggedIn = FALSE;
    $User->usertype = 1;

    $userquery = "SELECT * FROM $dbaseUserData where username = \"$username\" and lid='$leagueID'";
    $userresult = $dbase->query($userquery);
    if ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
      if ($pwd == $userline["password"]) {
        // The passwords are equal. Log the user in, and update the 
        // data.
        $User->userid =  $userline["userid"];
        $User->username = stripslashes($username);
        $User->emailaddr = $userline["email"];
        $User->icon = $userline["icon"];
        $User->usertype = $userline["usertype"];
        $User->createdate = $userline["since"];
        $User->lang = $userline["lang"];
        $User->dflths = $userline["dflths"];
        $User->dfltas = $userline["dfltas"];
        $User->lang = $userline["lang"];
        //$User->auto = $userline["auto"];
        $User->showstandings = $userline["showstandings"];
        $User->loggedIn = TRUE;

        return TRUE;
      }
    }
    return FALSE;
  }

 /*******************************************************
  * Check that a (any) user is logged in. This is used
  * in pages that require someone to be logged in to be
  * executed.
  *******************************************************/
  function CheckAUserLoggedIn($User,$txt, $tgt) {
    global $_SERVER;

    if ($User->loggedIn == FALSE) {
      $ip = $_SERVER["REMOTE_ADDR"];
      $port = "Unkown";
      if (array_key_exists("REMOTE_PORT", $_SERVER)) {
        $port = $_SERVER["REMOTE_PORT"];
      }
      $referer = $_SERVER["HTTP_REFERER"];
      
      LogMsg("$txt. This could be a spoofed page. IP Address $ip port $port referring page "); 
      forward("$tgt");
      exit;
    }

  }

  /************************************************************************************
   * Send the given user their password.
   ************************************************************************************/
  function SendPassword($username) {
    global $dbase, $dbaseUserData, $leagueID, $adminEmailAddr, $PredictionLeagueTitle, $PasswordEncryption;
    $response = "Error";

    $query = "SELECT * FROM $dbaseUserData where username=\"$username\" and lid='$leagueID'";
    $result = $dbase->query($query);

    $array = mysql_fetch_assoc($result);
    $userid = $array["userid"];
    $email = $array["email"];
    $password = $array["password"];
    if ($PasswordEncryption == "TRUE") {
      // Create a random password and update the user profile.
      $password = date("DadsLi").$array["userid"];
      $encr = new Encryption($password);
      $encryptpass = $encr->Encrypt();
      $query = "update $dbaseUserData set password='$encryptpass' where userid='$userid' and lid='$leagueID'";
      $res2 = $dbase->query($query);
    } else {
      $encr = new Encryption($password);
      $password = $encr->Decrypt();
    }
    if ($email != "") {
      $response = "Password sent to <b>$email</b> for <b>$username</b>";
      // Send the email.
      $text = "You have requested your password for $PredictionLeagueTitle.\n Your password is $password\nThank you";
      mail($email, "$PredictionLeagueTitle",$text,"From: $adminEmailAddr");

    } else {
      $response = "Sorry we could not find the user <b>$username</b>, please make sure the name is spelt correctly.";
    }

    // TODO Add response to the messages on the display
  }
?>
