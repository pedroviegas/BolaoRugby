<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 9th December
 * File  : menus.php
 ********************************************************/
  /*******************************************************
  * Check the user id and password from the cookie.
  *******************************************************/
  $isAdmin =  CheckAdmin($User->usertype);

require "msgfunctions.php";
require ("lang/".GetLangFile());

?>
<script>
<!--
function setGameDate(date, serveroffset){
  var old = document.getElementById("SERVERTIME");
  var bodyRef = document.getElementById("SERVER");
  var newInput = document.createElement("div");
  var bold = document.createElement("b");
  var newText = document.createTextNode(date);
  //var newText = document.createTextNode("Server Side Time "+date);
  newInput.setAttribute("id","SERVERTIME");
  bold.appendChild(newText);
  newInput.appendChild(bold);
  bodyRef.replaceChild(newInput,old);  
  
  // Wait one minute
  setTimeout("document.frames[0].location.reload()",60000);
  return false;
}
//-->
</script>
<!-- Menu -->
<table class="MENUTB">
<!-- Show the game time. -->
<tr>
<td align="center" class="LOGINHD">
<font class="LOGINHD">
<?php echo $Game_Time."\n";?>
</font>
</td>
</tr>
<tr>
<td align="center" class="TBLROW">
<font class="TBLROW">
<iframe id="SERVERFRAME" name="SERVERFRAME" style="width:0px; height:0px;border:0px" src="getgametime.php"></iframe>

<div id="SERVER" name="SERVER">
<div id="SERVERTIME" name="SERVERTIME">
XX:XX
</div>
</div>

</font>
</td>
</tr>

<?php
  if (($homePage != "" and $homePageTitle != "") or $chatRoomURL != "") { 
?>
<tr>
<td align="center" class="LOGINHD">
<font class="LOGINHD">
<?php echo $Txt_Home_Page."\n";?>
</font>
</td>
</tr>
<?php
}
?>
<tr>
<td class="TBLROW">
<!-- Home Page -->
<?php 
  if ($homePage != "" and $homePageTitle != "") {
    echo "<a href=\"$homePage\">$homePageTitle</a><br>";
  }
?>
<?php 
  if ($chatRoomURL != "") { 
?>
<a href="<?php echo $chatRoomURL ?>" target="_NewChatEnglFC">Chat Room</a><br>
<?php 
  } 
?>
</td>
</tr>
</table>

<?php
  if ($User != 0 && $User->loggedIn == TRUE) {
?>
    <table class="MENUTB">
    <tr>
    <td align="center" class="LOGINHD">
    <font class="LOGINHD">
    [<?php echo " $User->username ]"; ?>
    </font>
    </td>
    </tr>
<?php 
    $uid = $User->getUserId();
    $query = "SELECT * FROM $dbaseStandings WHERE lid='$leagueID' and userid='$uid' ORDER BY week DESC";
    $res = $dbase->query($query);
?>
<tr>
<td align="left" class="TBLROW">
<font class="TBLROW">
<?php 
    if ($dbase->getNumberOfRows() > 0) {
      $line = mysql_fetch_array($res);
      $position = $line["position"];
      $points = $line["points"];
    } else {
      $position = "-";
      $points = "-";
    }
    echo "$Txt_Position $position ";
    echo "$Txt_Points $points";
?>
</font>
</td>
</tr>
    <tr>
    <td align="center" valign="middle" class="TBLROW">
      <a href="index.php?sid=<?php echo $SID?>&cmd=icon">
      <img border="0" alt="<?php echo $Txt_Change_Icon; ?>" src="<?php echo $User->icon?>">
      </a>
    </td>
    </tr>
    </table>
<?php
  }
?>

<table class="MENUTB">
<tr>
<td align="center" class="LOGINHD">
<font class="LOGINHD">
<?php echo $Menu."\n";?>
</font>
</td>
</tr>
<tr>
<td class="TBLROW">
<a href="index.php<?php echo "?sid=$SID&cmd=table"?>"><?php echo $Prediction_Table;?></a><br>
<!--
<a href="index.php<?php echo "?sid=$SID&cmd=tournament"?>"><?php echo $Txt_Tournament_Standings;?></a><br>
-->
<a href="index.php<?php echo "?cmd=matchres&sid=$SID"?>"><?php echo $Fixtures_Results;?></a><br>
<a href="index.php<?php echo "?sid=$SID&cmd=stats"?>"><?php echo $League_Statistics;?></a><br>

<?php 
  // Only show these if the user is loged on
  if ($User->loggedIn == TRUE) {
?>
<a href="index.php<?php echo "?sid=$SID&cmd=missingpreds"?>"><?php echo $My_Missing_Predictions;?></a><br>
<a href="index.php<?php echo "?sid=$SID&cmd=mypreds"?>"><?php echo $My_Predictions;?></a><br>
<a href="index.php<?php echo "?sid=$SID&cmd=profile"?>"><?php echo $My_Profile;?></a><br>
<a href="index.php<?php echo "?sid=$SID&cmd=logout"?>"><?php echo $Logout;?></a>
</td>
</tr>

<?php 
  // Only show this if messaging enabled
  if ($useMessaging == "TRUE") {
?>
<tr>
<td class="TBLROW">
<a href="index.php<?php echo "?sid=$SID&cmd=msgs"?>"><?php echo $My_Messages.NewMail($User->userid);?></a>
<br><a href="index.php<?php echo "?sid=$SID&cmd=msgusers"?>"><?php echo $Txt_Message_User;?></a>
</td>
</tr>

<?php
  }
  }
?>
<tr>
<td class="TBLROW">
<a href="index.php<?php echo "?sid=$SID&cmd=help"?>"><?php echo $Help;?></a><br>
<a href="mailto:<?php echo $adminEmailAddr?>?subject=PredictionLeague"><?php echo $Email_Us;?></a>

</td>
</tr>
</table>

<?php 
  // If the user is an administrator, show the admin index.
  if($isAdmin) {
    require "adminmenus.php";
  }
?>
<!-- End Menu -->
