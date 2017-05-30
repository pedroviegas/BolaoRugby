<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 10th December
 * File  : loginpanel.php
 * Desc  : Display the login panel if an user is not 
 *       : logged in.
 ********************************************************/

require_once "lang/lang.english.php";
require_once ("lang/".GetLangFile());

if ($User == 0 || $User->loggedIn == FALSE) {
?>
  <!-- Login panel -->
  <form method="POST" action="index.php?cmd=login">
    <table class="LOGINTB">
      <tr>
        <td align="center" colspan="2" class="LOGINHD">
          <font class="LOGINHD">
            <?php echo $Login_txt."\n"; ?>
          </font>
        </td>
      </tr>
      <tr>
        <td colspan="1" class="LOGINRW">
          <font class="LOGINRW">
            <small>
            <?php echo $Username_txt."\n"; ?>
            </small>
          </font>
        </td>
        <td colspan="1" class="LOGINRW">
          <font class="LOGINRW">
            <input type="text" size="8" name="LOGIN">
          </font>
        </td>
      </tr>
      <tr>
        <td colspan="1" class="LOGINRW">
          <font class="LOGINRW">
            <small>
            <?php echo $Password_txt."\n"; ?>
            </small>
          </font>
        </td>
        <td colspan="1" class="LOGINRW">
          <font class="LOGINRW">
            <input type="password" size="8" name="PWD">
          </font>
        </td>
      </tr>
      <tr>
        <td align="center" colspan="2" class="LOGINRW">
          <font class="LOGINRW">
            <input type="submit" name="logon" value="<?php echo $Logon; ?>">
          </font>
        </td>
      </tr>
<?php
// Only display this if the game is not locked.
if ($LockedGame != "TRUE") {
?>
      <tr>
        <td colspan="2" class="LOGINRW">
          <font class="LOGINRW">
            <a href="index.php?sid=&cmd=createnewuser"><?php echo $New_User; ?></a>
          </font>
        </td>
      </tr>
<?php
}
?>
      <tr>
        <td colspan="2" class="LOGINRW">
          <font class="LOGINRW">
            <a href="index.php?sid=<?php echo $SID?>&cmd=forgotpassword"><?php echo $Forgot_password; ?></a><br>
          </font>
        </td>
      </tr>
    </table>
  </form>
<?php 
} 
?>
