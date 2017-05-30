<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 17th March 2004
 * File  : controller.php
 * Descr : Determine the action to take given the command.
 ********************************************************/
class Controller {
  var $cmd;
  var $user;

  function Controller($cmd, $user) {
    $this->cmd = $cmd;
    $this->user = $user;
  }

  /**************************************************************
   * If this command has some output, return true.
   **************************************************************/
  function is_view() {
    $arr = array("help" => "help",
         "adminuserpreds" => "",
         "adminpostuserpreds" => "",
         "enteruserpred" => "",
         "modallfixt" => "",
         "deletemsgs" => "",
         "tournament" => "",
         "showmsg" => "",
         "addcomp" => "",
         "manageicons" => "",
         "manageshouts" => "",
         "deleteshouts" => "",
         "compmang" => "",
         "removealldeletedmsgs" => "",
         "postmsg" => "",
         "createmsg" => "",
         "add" => "",
         "postresultval" => "",
         "postresult" => "",
         "userpreds" => "",
         "sendbugreport" => "",
         "reportbug" => "",
         "viewlog" => "",
         "viewallmsgs" => "",
         "emailall" => "",
         "emailallusers" => "",
         "changeconfig" => "",
         "configleague" => "",
         "deleteuser" => "",
         "createnewuser" => "",
         "moduseradmin" => "",
         "userdetails" => "",
         "showusers" => "",
         "msgusers" => "",
         "table" => "",
         "mypos" => "",
         "tableweek" => "",
         "matchres" => "",
         "stats" => "",
         "missingpreds" => "",
         "msgs" => "",
         "mypreds" => "",
         "forgotpassword" => "",
         "enterfixture" => "",
         "emf" => "",
         "profile" => "",
         "eor" => "",
         "er" => "",
         "matchpreds" => "",
         "updatestandings" => "",
         "modmultfixt" => "",
         "addmultfixt" => "",
         "changeuserpass" => "",
         "icon" => "");

    return (array_key_exists($this->cmd, $arr));
  }

  /***************************************************************
   * Perform the action for the given command.
   * These pages affect the main window. We cannot forward once we
   * have started the output so anything that affects the outer
   * columns must currently go here.
   * To remove this would require buffering the output.
   ***************************************************************/
  function execute() { 
    global $SID, $dbase, $dbaseUserData, $leagueID, $dbaseMatchData, $dbasePredictionData;
    global $UserDeleteAccount, $IconsDirectory, $AuditEmailAddr, $Txt_Homescore, $Txt_Awayscore;
    global $PredictionLeagueTitle, $Txt_Predictions_For, $adminEmailAddr, $Txt_Predictions_From;

    switch($this->cmd) {
      case "logout":
        UnregisterUser();
        forward("index.php?sid=&cmd=table");
        break;

      case "deletefixture":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to delete a fixture by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to delete a fixture without correct authority.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          DeleteFixture();
          forward("index.php?sid=$SID&cmd=enterfixture");
        }
        break;

      case "deleteaccount":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to delete an account by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } else if ($UserDeleteAccount == "FALSE") {
          logMsg("Attempt to delete an account by user ".$this->user->getUsername()." when user deleting of accounts is disabled.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          DeleteUserAccount($this->user->getUserID(), $this->user->getPassword());
          UnregisterUser();
          forward("index.php?sid=$SID&cmd=table");
        }
        break;

      case "deleteall":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to delete all fixtures by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to delete all fixtures without correct authority.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          DeleteAllFixtures();
          DeleteAllGroups();
          forward("index.php?sid=$SID&cmd=enterfixture");
        }
        break;

      // Take the parameters and create a new user.
      case "createuser":
        $username = $_POST["USERID"];
        $password = $_POST["PWD1"];
        $email = $_POST["EMAIL"];
        $icon = $_POST["ICON"];
        $asAdmin = $_POST["ASADMIN"];
        CreateUser($username, $password, $email, $icon, $asAdmin);
        // This is only hit if the login fails after creating the user.
        forward("index.php?sid=$SID&cmd=createnewuser");
        break;

      case "login":
        if (array_key_exists("LOGIN", $_POST) && array_key_exists("PWD", $_POST)) {
          $username = $_POST["LOGIN"];
          $pwd = $_POST["PWD"];
        login($username, $pwd);
        } else {
          forward("index.php");
        }
        break;

      case "si":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change an icon by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          $icon = $IconsDirectory."/".$_GET["icon"];
          $this->user->setIcon($icon);
          $this->user->persist();
          $_SESSION["User"] = $this->user;
          forward("index.php?sid=$SID&cmd=icon");
        }
        break;

      case "changepreds":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change a users predictions by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          $week = 1;
          if (array_key_exists("week", $_GET)) {
            $week = $_GET["week"];
          } else {
            $week = GetThisWeek();
          }
          $numrows = $_POST["NUMROWS"];
          $userid = $this->user->getUserID();
          $text = "$PredictionLeagueTitle $Txt_Predictions_For ".$this->user->getUsername()."/r/n";
          $text = "$Txt_Predictions_From ".$this->user->getLastIp().":".$this->user->getLastPort()."/r/n";

          for ($i=0; $i<$numrows; $i++) {
            if (!array_key_exists("MATCHID$i", $_POST)) {
              continue;
            }
            $matchid = $_POST["MATCHID$i"];
            $query = "SELECT * FROM $dbaseMatchData WHERE lid='$leagueID' AND matchid='$matchid'";
            $res = $dbase->query($query);
            $line = mysql_fetch_array($res);
            $date = $line["matchdate"];
            $ht = $line["hometeam"];
            $at = $line["awayteam"];
            if (CompareDatetime($date) < 0) {
          logMsg("Attempt to change a users predictions after the kickoff time. Could be an attempt to hack the predictions or someone left there browser on the page too long.");
              continue;
            }
            $gfor = $_POST["GFOR$i"];
            $gagainst = $_POST["GAGAINST$i"];

            $query = "REPLACE INTO $dbasePredictionData SET userid='$userid', matchid='$matchid', homescore='$gfor', awayscore='$gagainst',lid='$leagueID'";
            if ((FALSE == is_numeric($gfor)) 
             || (FALSE == is_numeric($gagainst))) {
              $query = "DELETE FROM $dbasePredictionData WHERE userid='$userid' AND matchid='$matchid' AND lid='$leagueID'";
            }
            $result = $dbase->query($query);
            if ($AuditEmailAddr != "") {
              $datetext = convertDatetimeToScreenDate($date);
              $text .= $this->user->getUsername()." $matchid $datetext $ht $at $Txt_Homescore $gfor $Txt_Awayscore $gagainst\r\n";
            }
          }
          if ($AuditEmailAddr != "") {
            @mail($AuditEmailAddr, "$PredictionLeagueTitle $Txt_Predictions_For ".$this->user->getUsername(),$text,"From: $adminEmailAddr");
          }
        }
        forward("index.php?week=$week&sid=&cmd=mypreds");
        break;

      case "uploadicon":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change an icon by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          UploadIcon();
          forward("index.php?sid=$SID&cmd=profile");
        }
        break;

      case "delicon":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change delete an icon by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to delete an icon without correct authority.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          DeleteIcon();
          forward("index.php?sid=$SID&cmd=manageicons");
        }

      case "eorv":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change an icon by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify fixtures without correct authority.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          $count = $_POST["COUNT"];
          // Loop through all the results.
          for ($i=0; $i < $count; $i++) {
            $mid = $_POST["MID$i"];
            $gf = $_POST["GF$i"];
            $ga = $_POST["GA$i"];

            if ($mid == "" or $ga == "" or $gf == "" ) {
              continue;
            } else {
              $query = "update $dbaseMatchData SET homescore='$gf', awayscore='$ga' where lid='$leagueID' and matchid='$mid'";
              $result = $dbase->query($query);
            }
          }
          // Update the standings table.
          UpdateStandingsTable();
        }
        forward("index.php?sid=&cmd=eor");
        break;

      case "modify":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to modify a fixture by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify fixtures without correct authority.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          ModifyFixture();
        }
        break;

      case "shout":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to manaage competitions by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } else {
          InsertShout();
          $nxt = $_GET['nxt'];
          forward("index.php?sid=$SID&cmd=$nxt");
        }
        break;

      case "changeuserpassword":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to modify a fixture by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify fixtures without correct authority.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          $userid = $_POST["USERID"];
          $username = $_POST["USERNAME"];
          $password = $_POST["PWD1"];
          logMsg("Password is being changed for user $username [$userid] by ".$this->user->getUsername().".");

          // Encrypt the password if password encryption is enabled
          $encr = new Encryption($password);
          $encryptpass = $encr->Encrypt();

          $query = "UPDATE $dbaseUserData set password='$encryptpass' where userid='$userid' and lid='$leagueID'";
          $dbase->query($query);
          forward("index.php?sid=$SID&cmd=showusers");
        }
        break;

      case "changepass":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change an icon by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          $user = $_POST["USERID"];
          $password = $_POST["PWD1"];
          logMsg("Password is being changed for user [$user].");

          // Encrypt the password if password encryption is enabled
          $encr = new Encryption($password);
          $encryptpass = $encr->Encrypt();

          $query = "UPDATE $dbaseUserData set password='$encryptpass' where userid='$user' and lid='$leagueID'";
          $dbase->query($query);
          forward("index.php?sid=$SID&cmd=profile");
        }
        break;

      case "updateprof":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change an icon by someone not logged in. Could be an attempt to break in.");
          forward("index.php?sid=$SID&cmd=table");
        } else {
          $email = $_POST["EMAIL"];
          $uname = $_POST["USERNAME"];
          $lang = $_POST["LANG"];
          $standings = 'N';
          if (array_key_exists('STANDINGS',$_POST)) {
            $standings = $_POST["STANDINGS"];
            if ($standings == 'on') {
              $standings = 'Y';
            }
          }
          $allowemail = 'N';
          if (array_key_exists("ALLOWEMAIL", $_POST)) {
            $allowemail = $_POST["ALLOWEMAIL"];
          }

          // Make sure there is a username
          if ($uname == "") {
            ErrorRedir("Username required, please choose a name","index.php?sid=$SID&cmd=profile");
          }

          if ($uname != $this->user->getUsername()) {
            if (TRUE == doesUsernameExist($uname)) {
              ErrorRedir("Username $uname already being used.","index.php?sid=$SID&cmd=profile");
            }
          }

          $this->user->setLanguage($lang);
          $this->user->setShowStandings($standings);
          $this->user->setEmailAddress($email);
          $this->user->setUsername($uname);
          $this->user->setAllowEmail($allowemail);
          $this->user->persist();
          $_SESSION["User"] = $this->user;
          forward("index.php?sid=$SID&cmd=profile");
        }
        break;

      case "sendpass":
        $username = $_POST["USERID"];
        SendPassword($username);
        forward("index.php?sid=&cmd=table");
        break;

      default:
        logMsg("Unknown command $this->cmd received");
        forward("index.php?sid=$SID&cmd=table");
        break;
    }
  }

  /***************************************************************
   * Display the output for the given commands.
   ***************************************************************/
  function display() {
    global $SID;
    // Get the current position when displaying the standings.
    $pos = 1;
    if (array_key_exists('pos',$_GET)) {
      $pos = $_GET['pos'];
    }

    switch($this->cmd) {
      case "mypos":
        ShowStandingsTableForUser();
        break;

      case "table":
        ShowStandingsTable($pos);
        break;

      case "tableweek":
        $week = $_GET["week"];
        ShowStandingsTableForWeek($week, $pos);
        break;

      case "matchres":
        GetResults();
        break;

      case "stats":
        ShowStatistics();
        break;

      case "missingpreds":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view user predictions by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } else {
          GetUserMissingPredictions($this->user->getUserID());
        }
        break;

      case "mypreds":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view user predictions by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } else {
          GetUserPredictions($this->user->userid);
        }
        break;

      case "matchpreds":
        $matchid = $_GET["matchid"];
        $matchdate = $_GET["date"];
        GetPredictionsForMatch($matchid,$matchdate);
        break;

      case "userpreds":
        $userid = $this->user->getUserId();
        if (array_key_exists("user",$_GET)) {
          $userid = $_GET["user"];
        }
        $week = 1;
        if (array_key_exists("week",$_GET)) {
          $week = $_GET["week"];
        } else {
          $week = GetThisWeek();
        }
        ShowUserPredictions($userid, $week);
        break;

      case "profile":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view user profile by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } else {
          require_once "profileview.php";
        }
        break;

      case "createnewuser":
        CreateNewUser($this->user);
        break;

      case "forgotpassword":
        require_once "forgotpasswordview.php";
        break;

      case "msgs":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view user predictions by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } else {
          ShowUserMessages($this->user->getUserID());
        }
        break;

      case "help":
        require_once "helpview.php";
        break;

      case "userdetails":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view users details by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to view users details.");
          ShowStandingsTable($pos);
        } else {
          $userid = $_GET["uid"];
          ShowUserDetails($userid);
        }
        break;

      case "msgusers":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view users for messaging by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } else {
          ShowUsersForMessaging();
        }
        break;

      case "showusers":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view users by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to view users.");
          ShowStandingsTable($pos);
        } else {
          ShowUsers($this->user);
        }
        break;

      case "deleteuser":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change the admin status of a user.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." attempted change the admin status of a user.");
          ShowStandingsTable($pos);
        } else {
          $userid = $_GET["userid"];
          DeleteUser($userid);
          ShowUsers($this->user);
        }
        break;

      case "moduseradmin":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change the admin status of a user.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." attempted change the admin status of a user.");
          ShowStandingsTable($pos);
        } else {
          ModifyUserAdmin();
          ShowUsers($this->user);
        }
        break;

      case "configleague":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change the configuration when not logged in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." attempted change the configuration by a user without the correct privileges.");
          ShowStandingsTable($pos);
        } else {
          GetCurrentConfig();
        }
        break;

      case "emailall":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to email all users by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to email all users.");
          ShowStandingsTable($pos);
        } else {
          SendEmailToAll();
          ShowStandingsTable($pos);
        }
        break;

      case "emailallusers":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to email all users by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to email all users.");
          ShowStandingsTable($pos);
        } else {
          EmailAllUsers();
        }
        break;

      case "updatestandings":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to update the standings by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to update the standings.");
          ShowStandingsTable($pos);
        } else {
          UpdateStandingsTable();
          ShowStandingsTable($pos);
        }
        break;

      case "viewlog":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view all messages by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to view all messages without correct authority.");
          ShowStandingsTable($pos);
        } else {
          ShowLog();
        }
        break;

      case "viewallmsgs":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to view all messages by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to view all messages without correct authority.");
          ShowStandingsTable($pos);
        } else {
          ShowAllMessages();
        }
        break;

      case "removealldeletedmsgs":
        if ($this->user->loggedIn != TRUE) {
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to delete all messages without correct authority.");
          ShowStandingsTable($pos);
        } else {
          DeleteAllDeletedMsgs();
          ShowAllMessages();
        }
        break;

      case "deletemsgs":
        if ($this->user->loggedIn != TRUE) {
          ShowStandingsTable($pos);
        } else {
          DeleteMsgs($this->user->getUserID());
          ShowUserMessages($this->user->getUserID());
        }
        break;

      case "showmsg":
        if ($this->user->loggedIn != TRUE) {
          ShowStandingsTable($pos);
        } else {
          $msgid = $_GET["msgid"];
          $fs = $_GET["fs"];
          ShowMessage($msgid, $fs);
        }
        break;

      case "postmsg":
        if ($this->user->loggedIn != TRUE) {
          ShowStandingsTable($pos);
        } else {
          PostMsg($this->user->getUserID());
          ShowStandingsTable($pos);
        }
        break;

      case "createmsg":
        if ($this->user->loggedIn != TRUE) {
          ShowStandingsTable($pos);
        } else {
          CreateMsg();
        }
        break;

      case "reportbug":
        if ($this->user->loggedIn != TRUE) {
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          ShowStandingsTable($pos);
        } else {
          ReportBug();
        }
        break;

      case "sendbugreport":
        if ($this->user->loggedIn != TRUE) {
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          ShowStandingsTable($pos);
        } else {
          SendBugReport();
          ShowStandingsTable($pos);
        }
        break;

      case "changeconfig":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change an icon by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify fixtures without correct authority.");
          ShowStandingsTable($pos);
        } else {
          SetConfig();
          GetCurrentConfig();
        }
        break;

      case "eor":
        GetOutstandingResults();
        break;

      case "add":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to add a fixture by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to add a fixture without correct authority.");
          ShowStandingsTable($pos);
        } else {
          AddFixture();
          require_once "adminenterfixtureview.php";
        }
        break;

      case "postresultval":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change a result by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify a result without correct authority.");
          ShowStandingsTable($pos);
        } else {
          PostResult();
          GetCurrentFixturesForResults();
        }
        break;

      case "postresult":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to change a result by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify a result without correct authority.");
          ShowStandingsTable($pos);
        } else {
          require_once "postresultview.php";
        }
        break;

      case "er":
        GetCurrentFixturesForResults();
        break;

      case "changeuserpass":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to modify a user password by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify a users password without correct authority.");
          ShowStandingsTable(1);
        } else {
          $userid = $_GET["userid"];
          $username = $_GET["username"];
          ChangeUserPassword($userid, $username);
        }
        break;

      case "modallfixt":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to modify fixtures by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify fixtures without correct authority.");
          ShowStandingsTable($pos);
        } else {
          ModifyAllFixtures();
        }
        break;

      case "enterfixture":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to enter a fixture by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable($pos);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to enter a fixture without correct authority.");
          ShowStandingsTable($pos);
        } else {
          require_once "adminenterfixtureview.php";
        }
        break;

      case "tournament":
        ShowTournamentStandings();
        break;

      case "emf":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to modify fixtures by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify some fixtures without correct authority.");
          ShowStandingsTable(1);
        } else {
          require_once "adminentermultfixturesview.php";
        }
        break;

      case "addcomp":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to add a competition by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to add a competition without correct authority.");
          ShowStandingsTable(1);
        } else {
          AddCompetition();
          ShowCompetitions();
        }
        break;

      case "modmultfixt":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to modify some fixtures by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify some fixtures without correct authority.");
          ShowStandingsTable(1);
        } else {
          ModifyMultipleFixtures();
          ModifyAllFixtures();
        }
        break;

      case "addmultfixt":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to modify some fixtures by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify some fixtures without correct authority.");
          ShowStandingsTable(1);
        } else {
          AddMultipleFixtures();
          require_once "adminenterfixtureview.php";
        }
        break;

      case "enteruserpred":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to modify another users predictions by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify another users predictions without correct authority.");
          ShowStandingsTable(1);
        } else {
          echo "!!!!!!";
        }
        break;

      case "compmang":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to manaage competitions by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify the competitions without correct authority.");
          ShowStandingsTable(1);
        } else {
          ManageCompetitions();
        }
        break;

      case "deleteshouts":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to delete shouts by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to delete the shouts without correct authority.");
          ShowStandingsTable(1);
        } else {
          DeleteShouts();
          ShowShoutsForAdmin();
        }
        break;

      case "adminuserpreds":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to manaage another users predictions by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify anothers predictions without correct authority.");
          ShowStandingsTable(1);
        } else {
          $userid = $_GET["user"];
          AdminChangeUserPredictions($userid);
        }
        break;

      case "adminpostuserpreds":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to manaage another users predictions by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify anothers predictions without correct authority.");
          ShowStandingsTable(1);
        } else {
          $userid = $_GET["userid"];
          AdminPostUserPredictions();
          AdminChangeUserPredictions($userid);
        }
        break;

      case "manageshouts":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to manaage shouts by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify the shouts without correct authority.");
          ShowStandingsTable(1);
        } else {
          ShowShoutsForAdmin();
        }
        break;

      case "manageicons":
        if ($this->user->loggedIn != TRUE) {
          logMsg("Attempt to manaage icons by someone not logged in. Could be an attempt to break in.");
          ShowStandingsTable(1);
        } elseif ($this->user->isAdmin() == false) {
          LogMsg("User ".$this->user->getUserID()." tried to modify the icons without correct authority.");
          ShowStandingsTable(1);
        } else {
          ShowIcons(true);
        }
        break;

      case "icon":
        ShowIcons(false);
        break;

      default:
        echo "not found $this->cmd !!!";
        logMsg("Unknown command $this->cmd received");
        forward("index.php?sid=$SID&cmd=table");
        break;
    }
  }
}

/***************************************************************
 * Make sure the next command is not trying to resend a form.
 ***************************************************************/
function GetNextValidCommand($cmd) {
  $newCmd = $cmd;
  switch($cmd) {
    case 'add':
      $newCmd = 'enterfixture';
      break;
  }
  return $newCmd;
}

?>
