<?php 
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 9th December 2001
 * File  : sortfunctions.php
 ********************************************************/

/////////////////////////////////////////////////////////
// Show a users details.
/////////////////////////////////////////////////////////
function ShowUserDetails($uid) {
  global $Language, $Txt_User_Type, $Txt_User_Created, $Txt_All_Ip, $Txt_Last_Ip, $Txt_Last_Login, $Icon;
  $user = User::CreateUserFromDB($uid);

?>
<table class="CENTER">
<tr>
<td colspan="100%" class="TBLHEAD">
<font class="TBLHEAD">
<?php echo $user->getUsername()." [".$user->getUserID()."]";?>
</font>
</td>
</tr>
<tr>
<td colspan="100%" class="TBLROW">
<font class="TBLROW">
<a href="mailto:<?php echo $user->getEmailAddress(); ?>">
<?php echo $user->getEmailAddress();?>
</a>
</font>
</td>
</tr>
<tr>
<td colspan="100%" class="TBLROW">
<font class="TBLROW">
<?php echo "<b>$Txt_User_Type</b> ".UserType::ToText($user->getUserType());?>
</font>
</td>
</tr>
<tr>
<td colspan="100%" class="TBLROW">
<font class="TBLROW">
<?php echo "<b>$Icon</b> ";?>
<img src="<?php echo $user->getIcon();?>" height="24">
<?php echo " [".$user->getIcon()."]";?>
</font>
</td>
</tr>
<tr>
<td colspan="100%" class="TBLROW">
<font class="TBLROW">
<?php echo "<b>$Language</b> ".$user->getLang();?>
</font>
</td>
</tr>
<tr>
<td colspan="100%" class="TBLROW">
<font class="TBLROW">
<?php echo "<b>$Txt_User_Created</b> ".convertDateToScreenDate($user->getCreateDate());?>
</font>
</td>
</tr>
<tr>
<td colspan="100%" class="TBLROW">
<font class="TBLROW">
<?php
   $lastlogin = $user->getLastLogin();
   if ($lastlogin != "") {
     $lastlogin = convertDatetimeToScreenDate($lastlogin);
   }
   echo "<b>$Txt_Last_Login</b> $lastlogin";?>
</font>
</td>
</tr>
<tr>
<td colspan="100%" class="TBLROW">
<font class="TBLROW">
<?php echo "<b>$Txt_Last_Ip</b> ".$user->getLastIp().":".$user->getLastPort();?>
</font>
</td>
</tr>
<tr>
<td colspan="100%" class="TBLROW">
<font class="TBLROW">
<?php echo "<b>$Txt_All_Ip</b> <br>";?>
<?php 

$allip = $user->getAllIp();
if (count($allip) > 0 ) {
?>
<table>
<?php
  while (list($key,$val) = each($allip)) {
?> <tr> <?php
    // Tokenize on the space.
    $ip = strtok($val," ");
    $ts = strtok(" ");
    $ts = date("Y-m-d H:i:s",$ts);
    $ts = convertDatetimeToScreenDate($ts);
    echo "<td class='TBLROW'><font class='TBLROW'>$ip</font></td><td><font class='TBLROW'>$ts</font></td>";
?> </tr> <?php
  }
?>
</table>
<?php
}
//print_r($user->getAllIp());
/*
while(list($key, $val) = each($user->getAllIp())) {
  echo "$val<br>";
}
*/
?>
</font>
</td>
</tr>
</table>
<?php
}

/////////////////////////////////////////////////////////
// Show a list of the users.
/////////////////////////////////////////////////////////
function ShowUsers($user) {
  global $dbaseUserData,$SID,$leagueID, $dbase, $Txt_Admin, $Username_txt;
  global $Txt_Predictions;

  $userquery = "SELECT * FROM $dbaseUserData where lid='$leagueID'";
  $userresult = $dbase->query($userquery);

  // Display the username as a header.
?>
  <table class="STANDTB">
  <tr>
  <td class="TBLHEAD" colspan="9" align="center"><font class="TBLHEAD">Users</font></td>
  </tr>
  <tr>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><small><?php echo $Txt_Admin;?></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><small><?php echo $Username_txt;?></small></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><small>Last Login</small></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><small>Predictions</small></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><small>&nbsp;</small></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><small>&nbsp;</small></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><small>Email</small></font></td>
  <td align="center" class="TBLHEAD"><font class="TBLHEAD"><small>Details</small></font></td>
  </tr>
<?php
  // First loop. Used to get all the users.
  while ($userline = mysql_fetch_array($userresult, MYSQL_ASSOC)) {
    // For each user display all their predictions.
    // against the actual result.
    $username = $userline["username"];
    $userid = $userline["userid"];
    $usertype = $userline["usertype"];
    $icon = $userline["icon"];
    $email = $userline["email"];
    $since = $userline["since"];
    $lastlogin = $userline["lastlogin"];
    if ($lastlogin != "") {
      $lastlogin = convertDatetimeToScreenDate($lastlogin);
    }

    echo "<tr>\n";
    if ($usertype >= 4) {
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"index.php?sid=$SID&cmd=moduseradmin&admin=N&userid=$userid&username=$username\"><small>Yes</small></a></font></td>\n";
    } else {
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"index.php?sid=$SID&cmd=moduseradmin&admin=Y&userid=$userid&username=$username\"><small>No</small></a></font></td>\n";
    }
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><small>$username</small></font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><small>$lastlogin</small></font></td>\n";
    ?>
    <td class="TBLROW">
      <font class="TBLROW">
        <a href="index.php?cmd=adminuserpreds&user=<?php echo $userid;?>&username=<?php echo $username;?>&sid=<?php echo $SID;?>">
          <small>
          <?php echo $Txt_Predictions;?>
          </a>
        </small>
      </font>
    </td>
    <?php
    if ($userid == $user->getUserID()) {
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\"></font></td>\n";
    } else {
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href='index.php?sid=$SID&cmd=deleteuser&userid=$userid' onclick=\"return confirm('Are you sure you want to delete ".addslashes($username)." and all their predictions');\"><small>delete</small></a></font></td>\n";
    }
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"index.php?sid=$SID&cmd=changeuserpass&userid=$userid&username=$username\"><small>password</small></a></font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"mailto:$email\"><small>Email</small></a></font></td>\n";
    echo "<td class=\"TBLROW\"><font class=\"TBLROW\"><a href=\"index.php?sid=$SID&cmd=userdetails&uid=$userid\"><small>Details</small></a></font></td>\n";
    echo "</tr>\n";
  }
  echo "</table>\n";
}

function ChangeUserPassword($userid, $username) {
  global $Password_txt, $Password_Again, $Change_Password, $Change_Password_button, $SID;
?>
  <SCRIPT LANGUAGE="JavaScript">
  <!--
  /***********************************************
   * Check the passwords are equal
   ***********************************************/
  function checkPasswords(form) {

    // Ensure that the passwords is not empty
    if (form.PWD1.value == "") {
      alert(<?php echo "\"$Passwords_is_required \""; ?>);
      return (false);
    }

    // Ensure that the passwords are equal
    if (form.PWD1.value != form.PWD2.value) {
      alert(<?php echo "\"$Passwords_do_not_match \""; ?>);
      return (false);
    }

    if (form.PWD1.value.length >= 32) {
      alert(<?php echo "\"$Password_too_long \""; ?>);
      return (false);
    }

    return true;
  }
  // -->
  </SCRIPT>
      <form method="POST" action="index.php?cmd=changeuserpassword&sid=<?php echo $SID; ?>">
        <table class="STANDTB">
          <tr>
            <td class="TBLHEAD" align="CENTER" colspan="2">
              <font class="TBLHEAD">
                <?php echo $Change_Password.": ".$username; ?>
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLHEAD" align="LEFT">
              <font class="TBLHEAD">
                <?php echo $Password_txt; ?>
              </font>
            </td>
            <td class="TBLHEAD" align="LEFT">
              <font class="TBLHEAD">
                <?php echo $Password_Again; ?>
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLROW" align="LEFT">
              <font class="TBLROW">
                <input type="hidden" name="USERID" value="<?php echo $userid; ?>">
                <input type="hidden" name="USERNAME" value="<?php echo $username; ?>">
                <input type="password" size="20" name="PWD1">
              </font>
            </td>
            <td class="TBLROW" align="LEFT">
              <font class="TBLROW">
                <input type="password" size="20" name="PWD2">
              </font>
            </td>
          </tr>
          <tr>
            <td class="TBLROW" align="CENTER" colspan="2">
              <font class="TBLROW">
                <input type="submit" onClick="return checkPasswords(this.form);" Value="<?php echo $Change_Password_button; ?>" Name="ChangePwd">
              </font>
            </td>
          </tr>
        </table>
      </form>
<?php
}

/**
 * Determine if the given email exists.
 * @param email the email to look for.
 */
function doesEmailExist($email) {
  global $dbaseUserData, $leagueID, $dbase;

  $query = "SELECT email from $dbaseUserData where lid='$leagueID' and email=\"$email\"";
  $result = $dbase->query($query);

  if ($result == FALSE) {
    ErrorNotify("Query Failed : $query");
    return TRUE;
  }
  
  // If we get >0 rows, the username already exists.
  $numrows = mysql_num_rows($result);

  if ($numrows == 0) {
    return FALSE;
  }

  return TRUE;
} 

/**
 * Determine if the given user exists.
 * @param user the user to look for.
 */
function doesUsernameExist($user) {
  global $dbaseUserData,$leagueID, $dbase;

  $query = "SELECT username from $dbaseUserData where username=\"$user\" and lid='$leagueID'";
  $result = $dbase->query($query);

  if ($result == FALSE) {
    ErrorNotify("Query Failed : $query");
    return TRUE;
  }
  
  // If we get >0 rows, the username already exists.
  $numrows = mysql_num_rows($result);

  if ($numrows == 0) {
    return FALSE;
  }

  return TRUE;
} 

function doesUseridExist($user) {
  global $dbaseUserData,$leagueID, $dbase;

  $query = "SELECT username from $dbaseUserData where userid=\"$user\" and lid='$leagueID'";
  $result = $dbase->query($query);

  if ($result == FALSE) {
    ErrorNotify("Query Failed : $query");
    return TRUE;
  }
  
  // If we get >0 rows, the username already exists.
  $numrows = mysql_num_rows($result);

  if ($numrows == 0) {
    return FALSE;
  }

  return TRUE;
} 

/*********************************************************
 * Modify the admin status of this user.
 *********************************************************/
function ModifyUserAdmin() {
  global $_SERVER, $dbase, $dbaseUserData, $leagueID, $maxAdminUsers, $SID;

  $userid = $_GET["userid"];
  $username = $_GET["username"];
  $admin = $_GET["admin"];

  /* Make sure that we have not exceeded the max number of admins. */
  $query = "select * from $dbaseUserData where usertype>='4' and lid='$leagueID'";
  $result = $dbase->query($query);
  $count = mysql_num_rows($result);

  $usertype = 1;
  if ($admin == "Y") {
    $usertype = 4;
  }

  if ($usertype == 4 && $count >= $maxAdminUsers) {
    // TODO create a message .This currently does not work because the 
    // headers have already been sent.
    ErrorRedir("The maximum number of admin users has been reached. Modify Max Admin users in config to add another admin","index.php?sid=$SID&cmd=showusers");
    exit;
  }

  // Change the user type
  $query = "update $dbaseUserData set usertype='$usertype' where lid='$leagueID' and userid='$userid'";
  $result = $dbase->query($query);

  // Log the action
  LogMsg("Made user $username into an admin user.");
}

function DeleteUser($userid) {
  global $_GET, $dbasePredictionData, $dbaseUserData, $dbase, $leagueID, $adminEmailAddr, $PredictionLeagueTitle;

  // Delete from the UserData table
  $query = "DELETE FROM $dbaseUserData where lid='$leagueID' and userid=\"$userid\"";
  $result = $dbase->query($query);

  // Delete from the PredictionData table
  $query = "DELETE FROM $dbasePredictionData where lid='$leagueID' and userid=\"$userid\"";
  $result = $dbase->query($query);

  // Email the administrator the deleted user
  $text = "User deleted.\nUser = $userid\nSent to $adminEmailAddr.\nVersion = ".VERSION;
  @mail($adminEmailAddr, "$PredictionLeagueTitle New User",$text,"From: $email");

  // Log the action
  LogMsg($text);
}

/********************************************************
 * Display the form for creating a new user.
 ********************************************************/
function CreateNewUser($user) {
  global $LockedGame, $defaulticon, $User_ID_Required, $User_ID_too_long, $Passwords_is_required; 
  global $Passwords_do_not_match, $Password_too_long, $Email_address_is_required, $Email_too_long;
  global $Email_address_is_not_valid, $User_Info, $User_ID, $Select_user_ID, $Password_txt, $SID;
  global $Password_length, $Password_txt, $Repeat_password, $Email_Address_txt, $Enter_valid_email_address, $Icon, $Select_icon, $Create; 

// If the user is logged in as admin then they are attempting
// to create a new user in a Locked Game.
$isAdmin =  CheckAdmin($user->usertype);
$asAdmin = "FALSE";
if ( ($LockedGame == "TRUE")
   &&($isAdmin == TRUE) ) {
  $asAdmin = "TRUE";
}

$newicon = $defaulticon;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--

// Check the form is complete. 
// Check that all the entries are complete.
// Check that the two passwords are equal.
// Check the email address 
function checkForm(form) {

	// Ensure a USER ID is entered
	if (form.USERID.value == "") {
		alert(<?php echo "\"$User_ID_Required\""; ?>);
		return (false);
	}

  if (form.USERID.value.length >= 32) {
		alert(<?php echo "\"$User_ID_too_long\""; ?>);
		return (false);
  }

	// Ensure that the passwords is not empty
	if (form.PWD1.value == "") {
		alert(<?php echo "\"$Passwords_is_required\""; ?>);
		return (false);
	}

	// Ensure that the passwords are equal
	if (form.PWD1.value != form.PWD2.value) {
		alert(<?php echo "\"$Passwords_do_not_match\""; ?>);
		return (false);
	}

  if (form.PWD1.value.length >= 10) {
		alert(<?php echo "\"$Password_too_long\""; ?>);
		return (false);
  }

	// Ensure that an email address is entered
	if (form.EMAIL.value == "") {
		alert(<?php echo "\"$Email_address_is_required\""; ?>);
		return (false);
	}

  if (form.EMAIL.value.length >= 60) {
		alert(<?php echo "\"$Email_too_long\""; ?>);
		return (false);
  }

	// Ensure that an email address is valid
	if (form.EMAIL.value.indexOf('@') < 0) {
		alert(<?php echo "\"$Email_address_is_not_valid\""; ?>);
		return (false);
	}

	return true;
}

// -->
</SCRIPT>

<!-- Show the Users info -->
<form method="POST" action="index.php?sid=<?php echo $SID;?>&cmd=createuser">
<table width="500">

<tr>
<td colspan="3" align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
<?php echo $User_Info."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
<?php echo $User_ID."\n"; ?>
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<input type="TEXT" size="10" name="USERID" value="">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<?php echo $Select_user_ID."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
<?php echo $Password_txt; ?>
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<input type="hidden" name="ASADMIN" value="<?php echo $asAdmin;?>">
<input type="password" size="10" name="PWD1">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<?php echo $Password_length."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
<?php echo $Password_txt; ?>
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<input type="password" size="10" name="PWD2">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<?php echo $Repeat_password."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
<?php echo $Email_Address_txt."\n"; ?>
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<input type="TEXT" size="20" name="EMAIL" value="">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<?php echo $Enter_valid_email_address."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
<?php echo $Icon."\n"; ?>
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<?php echo $newicon;?>
<input type="HIDDEN" name="ICON" value="<?php echo $newicon;?>">
</font>
</td>
<td class="TBLROW">
<font class="TBLROW">
<?php echo $Select_icon."\n"; ?>
</font>
</td>
</tr>
<tr>
<td colspan="3" align="CENTER">
<font class="TBLROW">
<input type="SUBMIT" onClick="return checkForm(this.form);" name="CREATE" value="<?php echo $Create; ?>">
</td>
</tr>
</table>
</form>
<?php
}

/*********************************************************************
 * Performs actual user creation
 *********************************************************************/
function CreateUser($username, $password, $email, $icon, $asAdmin) {
  global $dbase, $dbaseUserData, $leagueID, $adminEmailAddr, $allowMultipleUserPerEmail,$SID;
  global $Username_required, $Password_required, $Username_already_exists, $One_user_per_email;
  global $Email_Subject_txt, $Email_Answer_txt, $PredictionLeagueTitle;
  global $languageFile, $adminSignature, $Username_txt, $Password_txt;
  if (TRUE == doesUsernameExist($username)) {
    ErrorRedir($Username_already_exists,"index.php?sid=$SID&cmd=createnewuser");
  }

  /* The admin can configure the system to allow more than one user per password. */
  if ($allowMultipleUserPerEmail == "FALSE") {
    if (TRUE == doesEmailExist($email)) {
      ErrorRedir($One_user_per_email,"index.php?sid=$SID&cmd=createnewuser");
    }
  }

  // Make sure there is a username
  if ($username == "") {
    ErrorRedir($Username_required,"index.php?sid=$SID&cmd=createnewuser");
  }

  // Make sure there is a password
  if ($password == "") {
    ErrorRedir($Password_required,"index.php?sid=$SID&cmd=createnewuser");
  }

  // Encrypt the password if password encryption is enabled
  $encr = new Encryption($password);
  $encryptpass = $encr->Encrypt();
  $password = $encr->pwd;

  $todaysDate = date("Y-m-d");
  $lang = StripLang($languageFile);
  $query = "INSERT INTO $dbaseUserData (lid,username,password,email,icon,usertype,since,lang) values ('$leagueID','$username','$encryptpass','$email','$icon','1','$todaysDate', '$lang')";
  $result = $dbase->query($query);

  // Email the administrator the new user
  $text = "New User created.\nUser = $username\nPassword = $password\nEmail = $email\nIcon = $icon\nSent to $adminEmailAddr\nVersion = ".VERSION;
  $usertext = "$Email_Answer_txt\r\n$Username_txt = $username\r\n$Password_txt = $password\r\n\r\n$adminSignature";
  @mail($adminEmailAddr, "$PredictionLeagueTitle New User",$text,"From: $email");
  @mail($email,$Email_Subject_txt,$usertext,"From: $adminEmailAddr");

  // Add the new user to the standings table.
  UpdateStandingsTable();

  if ($asAdmin == "FALSE") {
    // Now that the user has been created, log them in.
    login($username,$password);
  }
}

function EmailAllUsers() {
  global $SID;
?>
<form method="POST" action="index.php?sid=<?php echo $SID;?>&cmd=emailall">
<table border="0">
<tr>
<td class="CENTERCOL">
<font class="TBLROW">
  Subject:
</font>
</td>
<td align="left">
<font class="TBLROW">
 <input type="TEXT" name="SUBJECT" size="40">
</font>
</td>
</tr>
<tr>
<td class="CENTERCOL">
<font class="TBLROW">
Message:
</font>
</td>
<td align="left">
<font class="TBLROW">
<textarea name="BODY" cols="40" rows="15"></textarea>
</font>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<font class="TBLROW">
<input type="SUBMIT" name="Send" value="Send">
</font>
</td>
</tr>
</table>
</form>
<?php
}

function check_php_version($version) {
  global $adminEmailAddr,$leagueID;

  // intval used for version like "4.0.4pl1"
  $testVer=intval(str_replace(".", "",$version));
  $curVer=intval(str_replace(".", "",phpversion()));
  if( $curVer < $testVer )
    return false;
  return true;
}

/**********************************************************
 * Send an email to all users.
 **********************************************************/
function SendEmailToAll() {
  global $_POST, $dbase, $dbaseUserData, $leagueID, $adminEmailAddr;

  $users = "";
  $subject = $_POST["SUBJECT"];
  $body = $_POST["BODY"];

  $query = "select username, email from $dbaseUserData where lid='$leagueID'";
  $result = $dbase->query($query);

  // Removed the BCC option as it appears that sending a single email can file when one of
  // the addresses is not valid.
  // Send as individual emails.
  while ($line = mysql_fetch_array($result)) {
    $user = $line["email"];
    if (FALSE == @mail($user,$subject,$body,
        "From: $adminEmailAddr\r\n"
        ."Reply-To: $adminEmailAddr\r\n")) {
      LogMsg("Unable to send email to user from $adminEmailAddr, to $user subject: $subject<br>$body");
      if (FALSE == @mail($user,$subject,$body,
                   "From: $adminEmailAddr\r\n"
                   ."Reply-To: $adminEmailAddr\r\n")) {
      }
    }
  }
}

/**********************************************************
 * Get the username for the given user id
 **********************************************************/
function getUsername($userid) {
  global $dbase, $dbaseUserData, $leagueID;

  $username = "Unknown";
  
  $query = "select username from $dbaseUserData where lid='$leagueID' and userid=\"$userid\" limit 1";
  $result = $dbase->query($query);
  $arr = mysql_fetch_array($result);
  $username = $arr[0];

  return $username;
}

/**********************************************************
 * Delete the user account. However leave the address log
 * intact.
 * TODO add a deleted flag to the userdata table so that an
 * audit facility is available.
 **********************************************************/
function DeleteUserAccount($userid, $pwd) {
  global $dbaseUserData,$dbasePredictionData,$leagueID,$dbase, $dbaseMsgData;
  global $SID,$Passwords_do_not_match;

  $password = md5($_POST["PWD"]);
  if ($password != $pwd) {
    ErrorRedir("$Passwords_do_not_match","index.php?sid=$SID&cmd=profile");
    exit;
  }
  // Delete the users predictions
  $query = "delete from $dbasePredictionData where userid='$userid' and lid='$leagueID'";
  $result = $dbase->query($query);

  // Delete the users messages
  $query = "update $dbaseMsgData set status='deleted' where receiver='$userid' and lid='$leagueID'";
  $result = $dbase->query($query);

  // Delete the entry from the userdata
  $query = "delete from $dbaseUserData where userid='$userid' and lid='$leagueID'";
  $result = $dbase->query($query);
}

function GetUsers() {
  global $dbase, $dbaseUserData, $leagueID;

  $users = array();

  $query = "select * from $dbaseUserData where lid='$leagueID'";
  $res = $dbase->query($query);
  while ($line = mysql_fetch_array($res)) {
    $uid = $line["userid"];
    $username = $line["username"];
    $users[$uid] = $username;
  }
  return $users;
}

/**********************************************************
 *  Post predictions for the user.
 *  Updates a users predictions and also mails the user
 *  the prediction values.
 **********************************************************/
function AdminPostUserPredictions() {
  global $_POST, $dbase, $dbasePredictionData, $dbaseUserData, $leagueID;
  global $adminEmailAddr;

  $count = $_POST["count"];
  $userid = $_GET["userid"];
  $preds = "Temp text, will be fixed \r\n\r\n";

  for ($i=1; $i<=$count; $i++) {
    if (array_key_exists("matchid$i",$_POST)) {
      $mid = $_POST["matchid$i"];
      $hs = $_POST["HS$i"];
      if (is_numeric($hs) == false) {
        continue;
      }
      $as = $_POST["AS$i"];
      $query = "REPLACE INTO $dbasePredictionData SET homescore='$hs', awayscore='$as', lid='$leagueID', matchid='$mid', userid='$userid'";
      $dbase->query($query);
      $preds .= "$mid $hs v $as\r\n";
    }
  }
  @mail($adminEmailAddr,"Admin changed preds for $userid",$preds);
}

/**********************************************************
 **********************************************************/
function AdminChangeUserPredictions($user) {
  global $_GET, $Txt_Predictions_For, $Date, $Home, $F, $A, $Away, $SID;
  global $dbase, $dbaseMatchData, $dbasePredictionData, $Txt_v, $leagueID;

  $username = $_GET["username"];
?>
<form method="POST" action="index.php?cmd=adminpostuserpreds&userid=<?php echo $user;?>&username=<?php echo $username;?>&sid=<?php echo $SID;?>">
<table class="CENTER">
  <tr>
    <td class="TBLHEAD" colspan="100%">
      <font class="TBLHEAD">
        <?php echo "$Txt_Predictions_For $username";?>
      </font>
    </td>
  </tr>
  <tr>
    <td class="TBLROW" align="center" colspan="100%">
      <font class="TBLROW">
        <input type="submit" name="Update" value="Update">
        <input type="hidden" name="userid" value="<?php echo $userid;?>">
      </font>
    </td>
  </tr>
  <tr>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo "$Date";?>
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo "$Home";?>
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo "$F";?>
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo "$A";?>
      </font>
    </td>
    <td class="TBLHEAD">
      <font class="TBLHEAD">
        <?php echo "Away";?>
      </font>
    </td>
  </tr>
<?php
  $query = "SELECT $dbaseMatchData.matchdate,$dbaseMatchData.hometeam,$dbaseMatchData.awayteam,$dbaseMatchData.homescore,$dbaseMatchData.awayscore,$dbasePredictionData.homescore,$dbasePredictionData.awayscore, $dbaseMatchData.matchid, bonuspoints FROM $dbaseMatchData LEFT JOIN $dbasePredictionData ON $dbasePredictionData.matchid=$dbaseMatchData.matchid AND userid='$user' AND $dbaseMatchData.lid=$dbasePredictionData.lid where $dbaseMatchData.lid='$leagueID' ORDER BY $dbaseMatchData.matchdate";   
  $res = $dbase->query($query);
  $count = 1;
while ($line = mysql_fetch_array($res)) {
  $date = $line["matchdate"];
  $matchdate = GetDateFromDateTime($date)." ".GetTimeFromDateTime($date);
  $matchid = $line["matchid"];
  $hometeam = $line["hometeam"];
  $homescore = $line["homescore"];
  $awayteam = $line["awayteam"];
  $awayscore = $line["awayscore"];

?>
  <tr>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $count; ?>
        <input type="hidden" name="matchid<?php echo $count;?>" value="<?php echo $matchid;?>">
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $matchdate; ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $hometeam; ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <input type="text" size="2" name="HS<?php echo $count;?>" value="<?php echo $homescore;?>">
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo "$Txt_v";?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <input type="text" size="2" name="AS<?php echo $count;?>" value="<?php echo $awayscore;?>">
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $awayteam; ?>
      </font>
    </td>
  </tr>
<?php
    $count++;
  }
?>
  <tr>
    <td class="TBLROW" align="center" colspan="100%">
      <font class="TBLROW">
        <input type="submit" name="Update" value="Update">
        <input type="hidden" name="count" value="<?php echo $count;?>">
      </font>
    </td>
  </tr>
</table>
</form>
<?php
}

/*****************************************************
 * Get the users that have been active within the
 * last configured time.
 *****************************************************/
function GetUsersLoggedIn() {
  global $dbase, $dbaseUserData, $leagueID, $Txt_Current_Users;
  $loggedInTime = 1800; // 1800 = 30mins worth of seconds
  $time = time();
  $time -= $loggedInTime;
  $now = date("Y-m-d H:i:s",$time);

  $query = "SELECT username from $dbaseUserData WHERE lid='$leagueID' and lastlogin>'$now' ORDER BY username";
  $res = $dbase->query($query);
  $users = "";
  while ($line = mysql_fetch_array($res)) {
    $users .= $line["username"]." ";
  }
  if ($users != "") {
    echo "$Txt_Current_Users $users";
  }
}
?>
