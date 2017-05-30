<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 17th March 2004
 * File  : forgotpasswordview.php
 * Desc  : Send the password to the user.
 ********************************************************/
?>
<form method="POST" action="index.php?sid=&cmd=sendpass">
  <!-- Show the Users info -->
  <table class="HELPTB">
    <tr>
      <td colspan="2" align="CENTER" class="TBLHEAD">
        <font class="TBLHEAD">
          Password Reminder
        </font>
      </td>
    </tr>
    <tr>
      <td align="CENTER" class="TBLHEAD">
        <font class="TBLHEAD">
          User ID
        </font>
      </td>
      <td class="TBLROW">
        <font class="TBLROW">
          <input type="text" size="20" name="USERID">
        </font>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="LEFT" class="TBLROW">
        <font class="TBLROW">
          Enter your User ID and press the send button. Your password will be emailed to the email address in your profile.
        </font>
      </td>
    </tr>
    <tr>
      <td class="TBLROW" align="CENTER" colspan="2">
        <input type="SUBMIT" name="Send" value="Send">
      </td>
    </tr>
  </table>
</form>
