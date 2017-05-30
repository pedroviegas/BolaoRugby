<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 17th March 2004
 * File  : helpview.php
 ********************************************************/
  require_once ("lang/".GetLangFile());

global $Creating_a_new_user, $Creating_a_new_user_txt, $Scoring_txt, $Scoring_txt_txt;
global $Txt_Logging_In_Title, $Txt_Logging_In_Txt, $Txt_Menu_Title, $Txt_Menu_Txt;
global $Txt_Messaging_Title, $Txt_Messaging_Txt;
?>
<table class="HELPTB">
<!-- Logging In -->
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="HELPHEAD">
<a name="SCORING">
<?php echo $Txt_Logging_In_Title."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="LEFT" class="TBLROW">
<font class="HELPROW">
<?php echo $Txt_Logging_In_Txt."\n"; ?>
</font>
</td>
</tr>
<!-- End Loggin In -->
<!-- Creating a new user -->
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="HELPHEAD">
<a name="NEWUSER">
<?php echo $Creating_a_new_user."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="LEFT" class="TBLROW">
<font class="HELPROW">
<?php echo $Creating_a_new_user_txt."\n"; ?>
</font>
</td>
</tr>
<!-- End Creating a new user -->
<!-- Scoring -->
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="HELPHEAD">
<a name="SCORING">
<?php echo $Scoring_txt."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="LEFT" class="TBLROW">
<font class="HELPROW">
<?php echo $Scoring_txt_txt."\n"; ?>
</font>
</td>
</tr>
<!-- End Scoring -->
<!-- Menu -->
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="HELPHEAD">
<a name="SCORING">
<?php echo $Txt_Menu_Title."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="LEFT" class="TBLROW">
<font class="HELPROW">
<?php echo $Txt_Menu_Txt."\n"; ?>
</font>
</td>
</tr>
<!-- End Menu -->
<!-- Messaging -->
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="HELPHEAD">
<a name="SCORING">
<?php echo $Txt_Messaging_Title."\n"; ?>
</font>
</td>
</tr>
<tr>
<td align="LEFT" class="TBLROW">
<font class="HELPROW">
<?php echo $Txt_Messaging_Txt."\n"; ?>
</font>
</td>
</tr>
<!-- End Messaging -->
</table>
