<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 17th March 2004
 * File  : adminentermultfixturesview.php
 * This page allows an administrator to add
 * extra fixtures to the prediction league.
 * The current contents of the fixture list will
 * also be displayed.
 ********************************************************/
global $NumMultFixts, $SID, $Txt_YYYYMMDD,$Txt_Time,$Txt_24hr;
global $Txt_Fixture_Administration, $Txt_Week, $Date,$Txt_HHMMSS;
global $Txt_Home_Team, $Txt_Away_Team, $Txt_Bonus_Points,$Txt_Add,$Txt_Reset;
?>
<table class="STANDTB">
<tr>
<td id="forms" valign="top">
  <form id="addform" name="AddFixture" method="POST" action="index.php?sid=<?php echo $SID; ?>&cmd=addmultfixt">
  <table class="STANDTB">
  <tr>
  <td colspan="7" align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Fixture_Administration; ?>
  </font>
  </td>
  </tr>

  <tr>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Week; ?>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Date;?><br><small><?php echo $Txt_YYYYMMDD;?></small>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Time;?> <small><?php echo $Txt_24hr;?></small><br><small><?php echo $Txt_HHMMSS;?></small>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Home_Team; ?>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Away_Team; ?>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  <?php echo $Txt_Bonus_Points; ?>
  </font>
  </td>
  </tr>
  <!-- Content Rows -->
<?php 
  for ($i=0; $i<$NumMultFixts; $i++) {
?>
  <tr>
  <td class="TBLROW" align="CENTER">
  <font id="formcount" class="TBLROW">
  <?php echo $i+1; ?>
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formweek" class="TBLROW">
  <input type="text" name="WEEK<?php echo $i; ?>" size="2">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formdate" class="TBLROW">
  <input type="text" name="DATE<?php echo $i; ?>" size="10">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formtime" class="TBLROW">
  <input type="text" name="TIME<?php echo $i; ?>" size="8">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formhome" class="TBLROW">
  <input type="text" name="HOMETEAM<?php echo $i; ?>" size="15">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formaway" class="TBLROW">
  <input type="text" name="AWAYTEAM<?php echo $i; ?>" size="15">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formbonus" class="TBLROW">
  <input type="text" name="BONUSPOINTS<?php echo $i; ?>" size="2">
  </font>
  </td>
</tr>
<?php
  }
?>
<tr>
  <td id="formsubmit" align="center" class="TBLROW" colspan="7">
  <input id="formsubmitinput" type="submit" name="ADD" value="<?php echo $Txt_Add;?>">
  <input id="formresetinput" onclick="addfixture()" type="reset" name="RESET" value="<?php echo $Txt_Reset;?>">
  </td>
  </tr>
  </table>
  </form>
  <?php GetCurrentFixtures(false); ?>
</td>
</tr>
</table>
