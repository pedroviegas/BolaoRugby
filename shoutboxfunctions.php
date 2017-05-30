<?php
/*********************************************************
 * Author: John Astill (c) 2002
 * Date  : 17th July 2004
 * File  : shoutboxfunctions.php
 ********************************************************/

  /**********************************************************************
   * Delete the shouts
   **********************************************************************/
  function DeleteShouts() {
    global $dbase, $leagueID, $dbaseShout;

    while(list($key,$sid) = each($_POST)) {
      if (substr($key,0,3) == "SID") {
        $dbase->query("delete from $dbaseShout where lid='$leagueID' and sid='$sid'");
      }
    }
  }

  /**********************************************************************
   * Show the shouts so that the admin can delete them
   **********************************************************************/
  function ShowShoutsForAdmin() {
    global $leagueID, $dbaseShout, $dbase, $Txt_Shoutbox_Title, $Txt_No_Shouts;
    global $EnableShoutbox, $User, $dbaseUserData, $SID, $ShoutboxCols, $ShoutboxMsgs;
    global $Delete, $Txt_Manage_Shouts, $_GET, $Txt_By_Date, $Txt_By_User;
?>
<?php
    $order = "date";
    if (array_key_exists("order",$_GET)) {
      $order = $_GET["order"];
    }
    $query = "SELECT * FROM $dbaseShout INNER JOIN $dbaseUserData ON $dbaseShout.lid=$dbaseUserData.lid AND $dbaseShout.userid=$dbaseUserData.userid WHERE $dbaseUserData.lid='$leagueID' ORDER BY ts DESC";
    if ($order == "user") {
      $query = "SELECT * FROM $dbaseShout INNER JOIN $dbaseUserData ON $dbaseShout.lid=$dbaseUserData.lid AND $dbaseShout.userid=$dbaseUserData.userid WHERE $dbaseUserData.lid='$leagueID' ORDER BY username";
    }
    $res = $dbase->query($query);
    if ($dbase->getNumberOfRows() <  1) {
      echo "$Txt_No_Shouts";
    } else {
?>
<form id="DELESHOUTS" name="DELESHOUTS" method="POST" action="index.php?sid=<?php echo $SID; ?>&cmd=deleteshouts">
<table class="CENTER">

<tr>
<td class="TBLHEAD" colspan="2" align="CENTER">
<font class="TBLHEAD">
<?php echo $Txt_Manage_Shouts; ?>
</font>
</td>
</tr>
<tr>
<td class="TBLROW" colspan="2" align="CENTER">
<font class="TBLROW">
<?php 
  if ($order == "user") {
    echo "$Txt_By_User|<a href='index.php?cmd=manageshouts&order=date&sid=$SID'>$Txt_By_Date</a>";
  } else {
    echo "<a href='index.php?cmd=manageshouts&order=user&sid=$SID'>$Txt_By_User</a>|$Txt_By_Date";
  }
?> 
</font>
</td>
</tr>
<?php
      while ($line = mysql_fetch_array($res)) {
        $userid = $line["userid"];
        $username = $line["username"];
        $shoutid = $line["sid"];
        $msg = WrapText($line["msg"], $ShoutboxCols, '<br>');
        $icon = $line['icon'];
        $timestamp = $line['ts'];
?>
        <tr>
        <td class="TBLROW">
        <font class="TBLROW">
        <input type="checkbox" name="SID<?php echo $shoutid;?>" value="<?php echo $shoutid;?>">
        </font>
        </td>
        <td class="TBLROW" width="100%">
        <font class="TBLROW">
        <?php echo convertDatetimeToScreenDate(GetDatetimeFromTimestamp($timestamp))."<br>" ?>
        <?php echo $username;?> [<img src='<?php echo $icon;?>' class='SHOUTROW'>]:</b><br><?php echo $msg; ?>
        </font>
        </td>
        </tr>
<?php
      }
?>
<tr>
<td class="TBLROW" colspan="2" align="CENTER">
<font class="TBLROW">
<input id+"delete" name="Delete" type="submit" value="<?php echo $Delete; ?>">
<input type="RESET" value="Reset" id="Reset">
</font>
</td>
</tr>
</table>
</form>
<?php
    }
  }

  /**********************************************************************
   * Get the shout data.
   **********************************************************************/
  function GetShoutData() {
    global $leagueID, $dbaseShout, $dbase, $Txt_Shoutbox_Title, $Txt_No_Shouts;
    global $EnableShoutbox, $User, $dbaseUserData, $SID, $ShoutboxCols, $ShoutboxMsgs;

?>
<?php
    $query = "SELECT * FROM $dbaseShout INNER JOIN $dbaseUserData ON $dbaseShout.lid=$dbaseUserData.lid AND $dbaseShout.userid=$dbaseUserData.userid WHERE $dbaseUserData.lid='$leagueID' ORDER BY sid DESC LIMIT $ShoutboxMsgs";
    $res = $dbase->query($query);
    if ($dbase->getNumberOfRows() <  1) {
      echo "$Txt_No_Shouts";
    } else {
      while ($line = mysql_fetch_array($res)) {
        $userid = $line["userid"];
        $username = $line["username"];
        $msg = WrapText($line["msg"], $ShoutboxCols, '<br>');
        $icon = $line['icon'];
        echo "<b>$username [<img src='$icon' class='SHOUTROW'>]:</b><br>$msg<p>";
      }
    }
  }

/********************************************************
 * Insert row
 ********************************************************/
function InsertShout() {
  global $User, $dbase, $leagueID, $dbaseShout;

  $shout = $_POST["SHOUT"];
  $uid = $User->getUserId();
  $dbase->query("INSERT INTO $dbaseShout (lid,userid,msg) values ('$leagueID', '$uid',' $shout')");
}

/********************************************************
 * Show the shoutbox
 ********************************************************/
  function ShowShoutbox() {
    global $Txt_Shoutbox_Title, $EnableShoutbox, $User, $dbaseUserData, $SID;
    global $Txt_Shout;

    $nxt = '';
    if (array_key_exists('cmd',$_GET)) {
      $nxt = $_GET['cmd'];
      $nxt = GetNextValidCommand($nxt);
    }

    if ($EnableShoutbox != "TRUE") {
      return;
    }

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
/***********************************************
 * Check the passwords are equal
 ***********************************************/
  function CheckText(form) {
    // Ensure that the text is not empty
    if (form.SHOUT.value == "") {
      return (false);
    }
    return true;
  }
-->
</SCRIPT>
    <table class="RIGHT">
      <tr>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $Txt_Shoutbox_Title; ?>
          </font>
        </td>
      </tr>
      <tr>
        <td class="SHOUTROW">
          <font class="SHOUTROW">
          <div class="SHOUTROW">
          <?php GetShoutData();?>
          </div>
          </font>
        </td>
      </tr>
<?php 
    if ($User->loggedIn == TRUE) {
?>
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            <form method="POST" action="index.php?cmd=shout&nxt=<?php echo $nxt; ?>&sid=<?php echo $SID;?>">
              <textarea  cols="15" name="SHOUT" id="SHOUT"></textarea><br>
              <input type="submit" onClick="return CheckText(this.form);" name="SHOUTBUTTON" value="<?php echo $Txt_Shout;?>">
            </form>
          </font>
        </td>
      </tr>
    <?php } ?>
    </table>
<?php
  }

?>
