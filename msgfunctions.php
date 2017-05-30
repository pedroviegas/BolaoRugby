<?php 
/*********************************************************
 * Author: John Astill (c) 2003
 * Date  : 30 January 2003
 * File  : msgfunctions.php
 ********************************************************/

/******************************************************
 * Determine if there are any new messages for the user.
 ******************************************************/
  function NewMail($uid) {
    global $dbaseMsgData, $leagueID, $dbase;

    $query = "select sender,msgtime,subject from $dbaseMsgData where receiver=\"$uid\" and status=\"new\" and lid='$leagueID'";
    $result = $dbase->query($query);
    if (mysql_num_rows($result) > 0) {
      return "<img border=\"0\" src=\"img/emailiconnew18x12.gif\" width=\"18\" height=\"12\"";
    }
  }

/******************************************************
 * Show all messages
 ******************************************************/
  function ShowAllMessages() {
    global $dbaseMsgData, $dbaseUserData, $SID, $REM,$Remove_All_Deleted, $Status_Col,$From,$To,$Date,$Subject, $leagueID, $dbase;

    $query = "select $dbaseUserData.username, sender, receiver, msgid, subject, msgtime, body, status, receiverdata.username as recvuser from $dbaseMsgData inner join $dbaseUserData on $dbaseUserData.userid=$dbaseMsgData.sender and $dbaseUserData.lid=$dbaseMsgData.lid inner join $dbaseUserData as receiverdata on receiverdata.userid=$dbaseMsgData.receiver and $dbaseUserData.lid=receiverdata.lid where $dbaseUserData.lid='$leagueID'";
    
    $result = $dbase->query($query);
    $count = mysql_num_rows($result);

    echo "<form method=\"POST\" action=\"index.php?sid=$SID&cmd=removealldeletedmsgs\">";
    echo "<table class=\"MSGTB\">";
    echo "<tr>";
    echo "<td class=\"TBLHEAD\" width=\"80\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$From\n";
    echo "</font>";
    echo "<td class=\"TBLHEAD\" width=\"80\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$To\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLHEAD\" width=\"80\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$Date\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLHEAD\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$Subject\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLHEAD\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$Status_Col\n";
    echo "</font>";
    echo "</td>";
    echo "</tr>";
    while ($line = mysql_fetch_array($result)) {
      $sender = $line["username"];
      $senderid = $line["sender"];
      $receiverid = $line["receiver"];
      $recvuser = $line["recvuser"];
      $msgid = $line["msgid"];
      $subject = $line["subject"];
      $msgdate = GetDateFromTimestamp($line["msgtime"]);
      $body = $line["body"];
      $status = $line["status"];

      echo "<tr>";
      echo "<td class=\"TBLROW\">";
      echo "<font class=\"TBLROW\">";
      echo "<a href=\"index.php?sid=$SID&cmd=showmsg&msgid=$msgid&fs=X\">$sender</a>\n";
      echo "</font>";
      echo "</td>";
      echo "<td class=\"TBLROW\">";
      echo "<font class=\"TBLROW\">";
      echo "$recvuser\n";
      echo "</font>";
      echo "</td>";
      echo "<td class=\"TBLROW\">";
      echo "<font class=\"TBLROW\">";
      echo "$msgdate\n";
      echo "</font>";
      echo "</td>";
      echo "<td class=\"TBLROW\">";
      echo "<font class=\"TBLROW\">";
      echo "$subject\n";
      echo "</font>";
      echo "</td>";
      echo "<td class=\"TBLROW\">";
      echo "<font class=\"TBLROW\">";
      echo $status;
      echo "</font>";
      echo "</td>";
      echo "</tr>";

    }
    if ($count > 0) {
      echo "<tr>";
      echo "<td class=\"TBLROW\" align=\"CENTER\" colspan=\"5\">";
      echo "<font class=\"TBLROW\">";
      echo "<input type=\"submit\" value=\"$Remove_All_Deleted\" name=\"DELETE\">";
      echo "</font>";
      echo "</td>";
      echo "</tr>";
    }

    echo "</table>";
    echo "</form>";
  }

/******************************************************
 * Show the messages for the given user
 ******************************************************/
  function ShowUserMessages($uid) {
    global $dbaseMsgData, $dbaseUserData,$SID,$From,$Date,$Subject,$Del,$Delete, $leagueID, $Txt_No_Messages, $dbase;

    $query = "select * from $dbaseMsgData inner join $dbaseUserData on $dbaseUserData.userid=$dbaseMsgData.sender and $dbaseUserData.lid=$dbaseMsgData.lid where receiver=\"$uid\" and status<>\"deleted\" and $dbaseUserData.lid='$leagueID'";
    
    $result = $dbase->query($query);
    $count = mysql_num_rows($result);

    echo "<form method=\"POST\" action=\"index.php?sid=$SID&cmd=deletemsgs\">";
    echo "<table class=\"MSGTB\">";
    echo "<tr>";
    echo "<td class=\"TBLHEAD\" width=\"80\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$From\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLHEAD\" width=\"80\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$Date\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLHEAD\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$Subject\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLHEAD\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$Del\n";
    echo "</font>";
    echo "</td>";
    echo "</tr>";

    if ($count == 0) {
        echo "<tr>";
        echo "<td class=\"TBLROW\" colspan='4'>";
        echo "<font class=\"TBLROW\">";
        echo $Txt_No_Messages;
        echo "</font>";
        echo "</td>";
        echo "</tr>";

    } else {
      while ($line = mysql_fetch_array($result)) {
        $sender = $line["username"];
        $senderid = $line["sender"];
        $msgid = $line["msgid"];
        $subject = $line["subject"];
        $msgdate = GetDateFromTimestamp($line["msgtime"]);
        $body = $line["body"];
        $status = $line["status"];

        echo "<tr>";
        echo "<td class=\"TBLROW\">";
        echo "<font class=\"TBLROW\">";
        echo "<a href=\"index.php?sid=$SID&cmd=showmsg&msgid=$msgid&fs=\">$sender</a>\n";
        echo "</font>";
        echo "</td>";
        echo "<td class=\"TBLROW\">";
        echo "<font class=\"TBLROW\">";
        echo "$msgdate\n";
        echo "</font>";
        echo "</td>";
        echo "<td class=\"TBLROW\">";
        echo "<font class=\"TBLROW\">";
        echo "$subject\n";
        echo "</font>";
        echo "</td>";
        echo "<td class=\"TBLROW\">";
        echo "<font class=\"TBLROW\">";
        echo "<input type=\"checkbox\" name=\"$msgid\" value=\"$msgid\">\n";
        echo "</font>";
        echo "</td>";
        echo "</tr>";

      }
      if ($count > 0) {
        echo "<tr>";
        echo "<td class=\"TBLROW\" align=\"CENTER\" colspan=\"4\">";
        echo "<font class=\"TBLROW\">";
        echo "<input type=\"submit\" value=\"$Delete\" name=\"DELETE\">";
        echo "</font>";
        echo "</td>";
        echo "</tr>";
      }
    }
    echo "</table>";
    echo "</form>";
  }

/******************************************************
 * Show the given message
 ******************************************************/
  function ShowMessage($msgid, $fs) {
    global $dbaseMsgData, $dbaseUserData, $SID, $RE,$From,$Date,$Message_txt, $leagueID, $dbase;

    $query = "select * from $dbaseMsgData inner join $dbaseUserData on $dbaseUserData.userid=$dbaseMsgData.sender where msgid=\"$msgid\" and $dbaseUserData.lid=$dbaseMsgData.lid and not status=\"deleted\" and $dbaseUserData.lid='$leagueID'";
    $result = $dbase->query($query);

    if ($fs == "") {
      $query = "update $dbaseMsgData set status=\"read\" where msgid=\"$msgid\" and lid='$leagueID'";
      $result2 = mysql_query($query) or die("Unable to update database: $query");
    }

    echo "<table class=\"MSGTB\">";
    echo "<tr>";
    echo "<td class=\"TBLHEAD\" colspan=\"2\" align=\"CENTER\">";
    echo "<font class=\"TBLHEAD\">";
    echo "Message $msgid\n";
    echo "</font>";
    echo "</td>";
    echo "</tr>";

    $line = mysql_fetch_array($result);
    $sender = $line["username"];
    $senderid = $line["sender"];
    $msgid = $line["msgid"];
    $subject = $line["subject"];
    $msgdate = GetDateFromTimestamp($line["msgtime"]);
    $body = $line["body"];
    $status = $line["status"];

    echo "<tr>\n";
    echo "<td class=\"TBLHEAD\" width=\"80\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$From\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLROW\">";
    echo "<font class=\"TBLROW\">";
    echo "$sender <a href=\"index.php?sid=$SID&cmd=createmsg&userid=$senderid&subj=$RE:$subject\">[reply]</a>\n";
    echo "</font>";
    echo "</td>";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"TBLHEAD\" width=\"80\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$Date\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLROW\">";
    echo "<font class=\"TBLROW\">";
    echo "$msgdate\n";
    echo "</font>";
    echo "</td>";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"TBLHEAD\">";
    echo "<font class=\"TBLHEAD\">";
    echo "$Message_txt\n";
    echo "</font>";
    echo "</td>";
    echo "<td class=\"TBLROW\">";
    echo "<font class=\"TBLROW\">";
    echo WrapText($body,60,'<br>')."\n";
    echo "</font>";
    echo "</td>";
    echo "</tr>";

    echo "</table>";
  }
  
  /*****************************************************
   * Create a message.
   *****************************************************/
  function CreateMsg() {
    global $_GET, $New_Message, $User_Name, $Subject_txt, $Message_txt;
    global $SID, $Send_Msg_Button;

    $tgtuserid = $_GET["userid"];

    $subject = "";
    if (array_key_exists("subj",$_GET)) {
      $subject = $_GET["subj"];
    }
    $tgtusername = getUsername($tgtuserid);
    ?>
    <!-- Show the Users info -->
    <form method="POST" action="index.php?sid=<?php echo $SID; ?>&cmd=postmsg">
    <table width="500">

    <tr>
    <td colspan="2" align="CENTER" class="TBLHEAD">
    <font class="TBLHEAD">
    <?php echo $New_Message."\n"; ?>
    </font>
    </td>
    </tr>
    <tr>
    <td align="LEFT" class="TBLHEAD">
    <font class="TBLHEAD">
    <?php echo $User_Name."\n"; ?>
    </font>
    </td>
    <td class="TBLROW">
    <font class="TBLROW">
    <?php echo $tgtusername?>
    <input type="hidden" name="TGTUSER" value="<?php echo $tgtuserid?>">
    </font>
    </td>
    </tr>
    <tr>
    <td align="LEFT" class="TBLHEAD">
    <font class="TBLHEAD">
    <?php echo $Subject_txt; ?>
    </font>
    </td>
    <td class="TBLROW">
    <font class="TBLROW">
    <input type="text" size="30" name="SUBJECT" value="<?php echo $subject; ?>">
    </font>
    </td>
    </tr>
    <tr>
    <td valign="TOP" align="LEFT" class="TBLHEAD">
    <font class="TBLHEAD">
    <?php echo $Message_txt."\n"; ?>
    </font>
    </td>
    <td class="TBLROW">
    <font class="TBLROW">
    <textarea name="MESSAGE" rows="20" cols="30"></textarea>
    </font>
    </td>
    </tr>
    <tr>
    <td colspan="2" align="CENTER">
    <font class="TBLROW">
    <input type="SUBMIT" name="CREATE" value="<?php echo $Send_Msg_Button; ?>">
    </td>
    </tr>
    </table>
    </form>
    <?php
  }

  /*******************************************************
   * Post a message
   *******************************************************/
  function PostMsg($userid) {
    global $dbase, $_POST, $leagueID, $dbaseUserData,  $dbaseMsgData;
    global $New_Message_Subject,$New_Message_Body;

    $tgtuser = $_POST["TGTUSER"];
    $subject = $_POST["SUBJECT"];
    $message = $_POST["MESSAGE"];
    $srcuser = $userid;

    $query = "insert into $dbaseMsgData (lid,sender,receiver,subject,body,status) values ($leagueID,\"$srcuser\",\"$tgtuser\",\"$subject\",\"$message\",\"new\")";
    $dbase->query($query);

    $query = "select * from $dbaseUserData where userid='$tgtuser' and lid='$leagueID'";
    $res = $dbase->query($query);
    $line = mysql_fetch_array($res);

    $email = $line["email"];
    $uname = $line["username"];

    // Email the recipient to let them know they have received a new message.
   @mail($email,$New_Message_Subject,$New_Message_Body) or logMsg("Unable to send mail to $email: $New_Message_Body");
  }

  /*********************************************************
   * Delete messages.
   *********************************************************/
  function DeleteMsgs($userid) {
    global $dbase, $_POST, $dbaseMsgData, $leagueID;
    $tgtuser = "";
    if (array_key_exists("TGTUSER",$_POST)) {
      $tgtuser = $_POST["TGTUSER"];
    }

    $subject = "";
    if (array_key_exists("SUBJECT",$_POST)) {
      $subject = $_POST["SUBJECT"];
    }

    $message = "";
    if (array_key_exists("MESSAGE",$_POST)) {
      $message = $_POST["MESSAGE"];
    }
    $srcuser = $userid;

    // loop through the POST data
    $count = 0;
    $val = "";
    $wherelist= "";
    while (list($a,$b) = each($_POST)) {
      if ($a != "DELETE") {
        if ($wherelist == "") {
          $wherelist = "msgid = $a";
        } else {
          $wherelist .= " or msgid = $a";
        }
      }
    }

    if ($wherelist != "") {
      $query = "update $dbaseMsgData set status=\"deleted\" where lid='$leagueID' and $wherelist";
      $dbase->query($query);
    }
  }

  /***********************************************
   * Show the users for messaging
   ***********************************************/
  function ShowUsersForMessaging() {
    global $dbase, $dbaseUserData, $leagueID, $SID, $Txt_Message_User;

    $query = "SELECT * FROM $dbaseUserData WHERE lid='$leagueID' ORDER BY username ASC";
    $res = $dbase->query($query);

?>
<table class="MSGTB">
<tr>
<td class='TBLHEAD'>
<font class='TBLHEAD'>
<?php echo $Txt_Message_User; ?>
</font>
</td>
</tr>
<?php
    while ($line = mysql_fetch_array($res)) {
      $username = $line["username"];
      $uid = $line["userid"];
?>
<tr>
<td class='TBLROW'>
<font class='TBLROW'>
<a href="index.php?sid=<?php echo $SID;?>&cmd=createmsg&userid=<?php echo $uid;?>"><?php echo $username; ?></a>
</font>
</td>
</tr>
<?php
    }
?>
</table>
<?php
  }

  /***********************************************
   * Purge all the deleted messages.
   ***********************************************/
  function DeleteAllDeletedMsgs() {
    global $dbaseMsgData, $leagueID, $dbase;
    $query = "delete from $dbaseMsgData where status=\"deleted\" and lid='$leagueID'";
    $dbase->query($query);
  }
?>
