<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : sendpassword.php
 * Desc  : Send the password for the given user to the 
 *       : email address in the users profile. If the 
 *       : user cannot be found, display en error message.
 ********************************************************/
  require "systemvars.php";
  require "configvalues.php";
  require "security.php";
  require "sortfunctions.php";

  $username = $_POST["USERID"];
  $response = "Error";

  // Get the email address of the user
  $query = "SELECT * FROM $dbaseUserData where username=\"$username\" and lid='$leagueID'";
  $result = $dbase->query($query, $link);

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
      $res2 = mysql_query($query)
                or die("Unable to update the password: $query\r\n".mysql_error());
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
?>

<html>
<head>
<title>Send Email</title>
<link rel="stylesheet" href="common.css" type="text/css">
</head>
<body class="MAIN">

  <table class="MAINTB">
    <tr>
      <td colspan="3" align="center">
        <!-- Header Row -->
        <?php echo $HeaderRow ?>
      </td>
    </tr>
    <tr>
      <td class="LEFTCOL">
        <?php require "menus.php"; ?>
      </td>
      <td class="CENTERCOL">
        <font class="TBLROW">
        <?php echo $response ?>
        </font>
      </td>
      <td class="RIGHTCOL">
        <?php require "loginpanel.php"; ?>
      </td>
    </tr>
  </table>

</body>
</html>
