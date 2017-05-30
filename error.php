<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 23rd January 2002
 * File  : error.php
 * Desc  : Error handling functions
 ********************************************************/

/*******************************************************
 * Email the error notification to the administrator
 * @param errorString a string indicating the error.
 *******************************************************/
function ErrorNotify($errorString) {
  global $ErrorCode;

  $ErrorCode = $errorString;
  
  @mail($adminEmailAddr, "$PredictionLeagueTitle Error",$errorString,"From: $adminEmailAddr");
}

/*******************************************************
 * Email the error notification to the administrator
 * @param errorString a string indicating the error.
 *******************************************************/
function ErrorRedir($errorString, $url) {
  global $ErrorCode, $HTTP_REFERER, $HTTP_HOST, $MsgList;

  $ErrorCode = $errorString;

  $MsgList->addNewMessage("ERROR",$errorString);
  
  //if (false == session_register("ErrorCode")) {
    //echo "Could not register ErrorCode: $ErrorCode";
    //exit;
  //}
  $_SESSION["ErrorCode"] = $ErrorCode;
  if (FALSE == headers_sent()) {
    /* Redirect browser to PHP web site */
    header("Location: $url"); 
    exit; 
  } else {
    $errorString = "HTTP Error headers already sent from $HTTP_REFERER on $HTTP_HOST".$errorString;
    @mail($adminEmailAddr, "$PredictionLeagueTitle Error",$errorString,"From: $adminEmailAddr");
  }
}

/*******************************************************
 * Email the error notification to the administrator
 * @param errorString a string indicating the error.
 *******************************************************/
function ClearErrorCode() {
  global $ErrorCode;
  $ErrorCode = "";
}

/*******************************************************
 * Wrap the text at the given number of characters
 *******************************************************/
function WrapText($txt, $chars, $break) {
  return wordwrap($txt,$chars,$break,1);
}

/*******************************************************
 * Report a bug to Prediction Football
 *******************************************************/
function ReportBug() {
  global $SID, $PredictionLeagueTitle, $dbase, $_SERVER;
  global $homePage, $allowMultipleUserPerEmail, $PasswordEncryption, $timezoneOffset, $Use24Hr, $LockedGame;

  $subject = "Bug Report for $PredictionLeagueTitle ".VERSION;

  // Get the version numbers and config details for PHP, MySQL and the Server.
  $details = "PHP Version = ".phpversion()."\r\n";
  $details .= "TMP File path = ".get_cfg_var('upload_tmp_dir')."\r\n";
  $details .= "MySQL Version = ".$dbase->getVersion()."\r\n";
  while(list($key,$val) = each($_SERVER)) {
    $details .= "$key = $val\r\n";
  }

  // Now add some of the PF config values.
  $details .= "\r\nPrediction Football Config Values\r\n";
  $details .= "Version = ".VERSION."\r\n";
  $details .= "Home Page = ".$homePage."\r\n";
  $details .= "Multiple Users Per Email  = ".$allowMultipleUserPerEmail."\r\n";
  $details .= "Timezone offset = ".$timezoneOffset."\r\n";
  $details .= "Use 24 Hour = ".$Use24Hr."\r\n";
  $details .= "Password Encryption = ".$PasswordEncryption."\r\n";
  $details .= "Locked Game = ".$LockedGame."\r\n";

  
?>
<form method="POST" action="index.php?sid=<?php echo $SID;?>&cmd=sendbugreport">
<table border="0">
<tr>
<td class="CENTERCOL">
<font class="TBLROW">
Subject:
</font>
</td>
<td align="left">
<font class="TBLROW">
<?php echo $subject; ?>
<input type="hidden" name="subject" value="<?php echo $subject;?>">
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
<tr>
<td class="CENTERCOL">
<font class="TBLROW">
Details:
</font>
</td>
<td align="left">
<font class="TBLROW">
<pre>
<?php echo WrapText($details, 60, '<br>'); ?>
<input type="hidden" name="details" value="<?php echo $details; ?>">
</pre>
</font>
</td>
</tr>
</table>
</form>
<?php
}

/*********************************************************
 * Send the bug report to bugs@predictionfootball.com
 *********************************************************/
function SendBugReport() {
  global $_POST, $adminEmailAddr;

  $email = "bugs@predictionfootball.com";
  $subject = $_POST["subject"];
  $body = $_POST["BODY"];
  $body .= "\r\n";
  $body .= $_POST["details"];
  //TODOecho "$email,$subject,<br>$body,<br>'From: $adminEmailAddr'";exit;
  @mail($email,$subject,$body,"From: $adminEmailAddr");
}
