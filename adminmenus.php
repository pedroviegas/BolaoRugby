<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 20th March 2003
 * File  : adminmenus.php
 *********************************************************/
?>
<!-- Menu -->
<table class="MENUTB">
<tr>
<td align="center" class="LOGINHD">
<font class="LOGINHD">
<?php echo $Admin_Menu." [$leagueID]\n";?>
</font>
</td>
</tr>
<tr>
<td class="TBLROW">
<a href="index.php?sid=<?php echo $SID?>&cmd=enterfixture"><?php echo $Enter_Fixture;?></a><br>
<a href="index.php?sid=<?php echo $SID?>&cmd=emf"><?php echo $Lang_Enter_Multiple_Fixtures;?></a><br>
<a href="index.php?cmd=modallfixt&sid=<?php echo $SID?>"><?php echo $Txt_Modify_All_Fixtures;?></a><br>
<a href="index.php?sid=<?php echo $SID?>&cmd=er"><?php echo $Enter_Result;?></a><br>
<a href="index.php?sid=<?php echo $SID?>&cmd=eor"><?php echo $Lang_Enter_Outstanding_Results;?></a><br>
<!--
<a href="index.php?sid=<?php echo $SID?>&cmd=compmang"><?php echo $Txt_Manage_Competition;?></a><br>
-->
<a href="index.php?sid=<?php echo $SID?>&cmd=showusers"><?php echo $Show_Users;?></a><br>
<?php if ($LockedGame == "TRUE") { ?>
<a href="index.php?sid=<?php echo $SID; ?>&cmd=createnewuser"><?php echo $New_User; ?></a><br>
<?php } ?>
<a href="index.php?sid=<?php echo $SID?>&cmd=updatestandings"><?php echo $Recalculate_Standings;?></a><br> 
<a href="index.php?sid=<?php echo $SID?>&cmd=emailallusers"><?php echo $Email_All_Users;?></a><br>
<a href="index.php?sid=<?php echo $SID?>&cmd=manageicons"><?php echo $Txt_Manage_Icons;?></a><br>
<a href="index.php?sid=<?php echo $SID?>&cmd=manageshouts"><?php echo $Txt_Manage_Shouts;?></a><br>
<?php
if ($useMessaging == "TRUE") { ?>
<a href="index.php?sid=<?php echo $SID?>&cmd=viewallmsgs"><?php echo $View_All_Messages;?></a><br>
<?php
  }
  if ($logfile != "") {
?>
<a href="index.php?sid=<?php echo $SID?>&cmd=viewlog"><?php echo $View_Log;?></a><br>
<?php
  }
?>
<a href="index.php?sid=<?php echo $SID?>&cmd=configleague"><?php echo $Config_League;?></a><br>
<a href="index.php?sid=<?php echo $SID?>&cmd=reportbug"><?php echo $Lang_Report_Bug;?></a>
</td>
</tr>
</table>
<!-- End Menu -->
