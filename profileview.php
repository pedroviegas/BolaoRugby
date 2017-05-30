<?php 
/*********************************************************
 * Author: John Astill
 * Date  : 17th March 2004
 * File  : profileview.php
 * Desc  : Display the profile for the currently logged
 *       : in user. 
 ********************************************************/

global $Passwords_is_required, $Passwords_do_not_match, $Password_too_long;
global $Email_address_is_required, $Email_too_long, $Email_address_is_not_valid;
global $User_Name, $Email_Address_txt,$Icon, $Language, $Txt_Icon_Change;
global $Change_Profile, $Upload_Icon, $Upload_Instructions, $Change_Password, $Old_Password_txt;
global $Password_txt, $Password_Again, $Change_Password_button, $UploadIcons, $User, $SID;
global $Txt_Allow_Emails, $Txt_Misc_Profile, $Txt_Delete_Account,$Txt_Deleting_User;
global $UserDeleteAccount, $Txt_Show_Standings_On_Login;
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

  if (form.PWD1.value.length >= 10) {
    alert(<?php echo "\"$Password_too_long \""; ?>);
    return (false);
  }

  return true;
}

/***********************************************
 * Check the form is complete. 
 * Check the email address 
 ***********************************************/
function checkProfileForm(form) {

  // Ensure that an email address is entered
  if (form.EMAIL.value == "") {
    alert(<?php echo "\"$Email_address_is_required \""; ?>);
    return (false);
  }

  if (form.EMAIL.value.length >= 60) {
    alert(<?php echo "\"$Email_too_long \""; ?>);
    return (false);
  }

  // Ensure that an email address is valid
  if (form.EMAIL.value.indexOf('@') < 0) {
    alert(<?php echo "\"$Email_address_is_not_valid \""; ?>);
    return (false);
  }

  return true;
}

// -->
</SCRIPT>

<!-- Show the Users info -->
<form method="POST" action="index.php?sid=<?php echo $SID?>&cmd=updateprof">
  <table class="STANDTB">
    <tr>
      <td align="CENTER" class="TBLHEAD">
        <font class="TBLHEAD">
          <?php echo $User_Name; ?>
        </font>
      </td>
      <td align="CENTER" class="TBLHEAD">
        <font class="TBLHEAD">
          <?php echo $Email_Address_txt; ?>
        </font>
      </td>
      <td align="CENTER" class="TBLHEAD">
        <font class="TBLHEAD">
          <?php echo $Icon; ?>
        </font>
      </td>
      <td align="CENTER" class="TBLHEAD">
        <font class="TBLHEAD">
          <?php echo $Language; ?> 
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" valign="top">
        <font class="TBLROW">
          <input type="TEXT" size="10" name="USERNAME" value="<?php echo $User->username ?>">
        </font>
      </td>
      <td class="TBLROW" valign="top">
        <font class="TBLROW">
          <input type="text" size="20" name="EMAIL" value="<?php echo $User->emailaddr ?>">
        </font>
      </td>
      <td class="TBLROW">
        <font class="TBLROW" valign="top">
          <img alt="<?php echo $Txt_Icon_Change ?>" src="<?php echo $User->icon ?>" border="0">
        </font>
      </td>
      <td class="TBLROW" valign="top">
        <font class="TBLROW">
          <select name="LANG">
            <?php GetLanguageOptions($User->lang); ?>
          </select>
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLHEAD" align="CENTER" valign="top" colspan="4">
        <font class="TBLHEAD">
          <?php echo $Txt_Misc_Profile; ?>
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" valign="top" align="left" colspan="4">
        <font class="TBLROW">
          <input type="checkbox" size="2" name="STANDINGS" <?php if ($User->showstandings == 'Y') echo "checked"?>>
          <?php echo $Txt_Show_Standings_On_Login; ?>
        </font>
      </td>
    </tr>
<!--
    <tr>
      <td class="TBLROW" valign="top" align="left" colspan="4">
        <font class="TBLROW">
          <input type="checkbox" size="2" name="ALLOWEMAIL" value="Y" <?php if ($User->allowemail == 'Y') echo "checked"?>>
          <?php echo $Txt_Allow_Emails; ?>
        </font>
      </td>
    </tr>
-->
<!--
    <tr>
      <td class="TBLHEAD" align="CENTER" valign="top" colspan="4">
        <font class="TBLHEAD">
          <?php echo $Lang_Enable_Auto_Predictions; ?>
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" valign="top" align="left" colspan="4">
        <font class="TBLROW">
          <input type="checkbox" size="2" name="AUTO" value="Y" <?php if ($User->auto == 'Y') echo "checked"?>>
          <?php echo $Lang_Enable; ?>
        </font>
      </td>
    </tr>
-->
    <tr>
      <td class="TBLROW" align="CENTER" colspan="5">
        <input type="submit" onClick="return checkProfileForm(this.form);" value="<?php echo $Change_Profile; ?>" name="Change">
      </td>
    </tr>

  </table>
</form>

<?php 
if ($UploadIcons == "TRUE") {
?>
<!-- Form uploading an icon -->
<form method="POST" enctype="multipart/form-data" action="index.php?sid=<?php echo $SID;?>&cmd=uploadicon">
  <table class="STANDTB">
    <tr>
      <td class="TBLHEAD" align="CENTER" colspan="3">
        <font class="TBLHEAD">
          <?php echo $Upload_Icon; ?>
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" align="LEFT">
        <font class="TBLROW">
          <?php echo $Upload_Instructions; ?>
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" align="LEFT">
        <font class="TBLROW">
          <input type="file" size="40" name="ICONNAME">
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" align="CENTER" colspan="3">
        <font class="TBLROW">
          <input type="submit" Value="<?php echo $Upload_Icon; ?>" Name="UploadIcon">
        </font>
      </td>
    </tr>
  </table>
</form>
<?php
}
?>

<!-- Form for changing the password -->
<form method="POST" action="index.php?sid=<?php echo $SID;?>&cmd=changepass">
  <table class="STANDTB">
    <tr>
      <td class="TBLHEAD" align="CENTER" colspan="3">
        <font class="TBLHEAD">
          <?php echo $Change_Password; ?>
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLHEAD" align="LEFT">
        <font class="TBLHEAD">
          <?php echo $Old_Password_txt; ?>
        </font>
      </td>
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
          <input type="password" size="10" name="OLDPWD">
        </font>
      </td>
      <td class="TBLROW" align="LEFT">
        <font class="TBLROW">
          <input type="hidden" name="USERID" value="<?php echo $User->userid; ?>">
          <input type="password" size="10" name="PWD1">
        </font>
      </td>
      <td class="TBLROW" align="LEFT">
        <font class="TBLROW">
          <input type="password" size="10" name="PWD2">
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" align="CENTER" colspan="3">
        <font class="TBLROW">
          <input type="submit" onClick="return checkPasswords(this.form);" Value="<?php echo $Change_Password_button; ?>" Name="ChangePwd">
        </font>
      </td>
    </tr>
  </table>
</form>
<?php 
  if ($UserDeleteAccount == "TRUE") {
?>
<!-- Form for deleting a user account -->
<form method="POST" action="index.php?sid=<?php echo $SID;?>&cmd=deleteaccount">
  <table class="STANDTB">
    <tr>
      <td class="TBLHEAD" align="CENTER" colspan="3">
        <font class="TBLHEAD">
          <?php echo $Txt_Delete_Account; ?>
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" align="LEFT" colspan="3">
        <font class="TBLROW">
           <?php echo $Txt_Deleting_User; ?>
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" align="LEFT">
        <font class="TBLROW">
          <b><?php echo "$Password_txt "; ?></b><input type="password" size="10" name="PWD">
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" align="CENTER" colspan="3">
        <font class="TBLROW">
          <input type="submit" Value="<?php echo $Txt_Delete_Account; ?>" Name="DeleteAccount">
        </font>
      </td>
    </tr>
  </table>
</form>
<?php } ?>
