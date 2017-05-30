<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : admincreateadminuser.php
 * Desc  : Form used to create the admin user for the 
 *       : prediction league.
 ********************************************************/
  require_once "systemvars.php";
  require_once "dbasedata.php";
  require_once "configvalues.php";
  require_once "sortfunctions.php";
  require_once "security.php";
  require_once ("lang/".GetLangFile());

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Create the Admin User for the Prediction League
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

// Check the form is complete. 
// Check that all the entries are complete.
// Check that the two passwords are equal.
// Check the email address 
function checkForm(form) {

	// Ensure a USER ID is entered
	if (form.USERID.value == "") {
		alert(<?php echo "\"$User_ID_Required\""; ?>);
		return (false);
	}

  if (form.USERID.value.length >= 32) {
		alert(<?php echo "\"$User_ID_too_long\""; ?>);
		return (false);
  }

	// Ensure that the passwords is not empty
	if (form.PASSWORD.value == "") {
		alert(<?php echo "\"$Passwords_is_required\""; ?>);
		return (false);
	}

	// Ensure that the passwords are equal
	if (form.PASSWORD.value != form.PASSWORD2.value) {
		alert(<?php echo "\"$Passwords_do_not_match\""; ?>);
		return (false);
	}

  if (form.PASSWORD.value.length >= 32) {
		alert(<?php echo "\"$Password_too_long\""; ?>);
		return (false);
  }

	return true;
}

// -->
</SCRIPT>

  </head>

  <body class="MAIN">

  <table class="MAINTB">
  <?php if ($ErrorCode != "") { ?>
      <tr>
        <td colspan="3" bgcolor="red" align="center">
          <!-- Error Message Row -->
          <font class="TBLHEAD">
          <?php 
            echo $ErrorCode; 
          ?>
          </font>
        </td>
      </tr>
  <?php 
    }
  ?>

    <tr>
      <!-- Left column -->
      <td class="TBLHEAD" colspan="2" align="CENTER">
        <font class="TBLHEAD">
        Create the admin users.
        </font>
      </td>
    </tr>
    <tr>
      <!-- Left column -->
      <td class="LEFTCOL">
        <!-- Menu -->
        <?php require_once "adminsetupmenu.php"?>
        <!-- End Menu -->
      </td>
      <!-- Central Column -->
      <td align="LEFT" class="CENTERCOL">
        <font class="TBLROW">
        <!-- Central Column -->
<?php
  // Test to see if the maximum number of admin users has already
  // been created.
  
  $link = OpenConnection();
  if ($link == FALSE) {
    ErrorNotify("Unable to open connection");
    exit;
  }

  $query = "select * from $dbaseUserData where lid='$leagueID' and  usertype=\"4\"";
  $result = mysql_query($query) 
    or die("Unable to peform query: $query");

  if ($result == FALSE) {
    ErrorNotify("Query Failed : $query");
    CloseConnection($link);
    return TRUE;
  }
  
  // Count the number of admin users.
  $numrows = mysql_num_rows($result);
  if ($numrows >= $maxAdminUsers) {
?>
The Maximum number of admin users for league ID <?php echo $leagueID; ?> has been created. If you want to allow more, you need to change the configuration variable maxAdminUsers. 
If you have imported data from a previous version then the admin users from that league have been imported.
<?php
    if ($numrows > $maxAdminUsers) {
?>
<p>
WARNING: you have more admin users [<?php echo $numrows?>] than are configured [<?php echo $maxAdminUsers?>]. 
<?php
    }
  } else {  
?>
    <form method="POST" action="createadminuser.php">
    <table>
      <tr>
        <td class="TBLHEAD" colspan="3" align="CENTER">
          <font class="TBLHEAD">
            Admin User Administration [LeagueID <?php echo $leagueID;?>]
          </font>
        </td>
      </tr>
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Admin User Name
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="TEXT" size="20" name="USER" value="">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            The name for the admin user.
          </font>
        </td>
      </tr> 
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Admin Email Address
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="TEXT" size="40" name="EMAIL" value="">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            The admin users email address
          </font>
        </td>
      </tr> 
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Password
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="PASSWORD" size="20" name="PASSWORD">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
                    The password for the admin user.
          </font>
        </td>
      </tr> 
      <tr>
        <td class="TBLROW">
          <font class="TBLROW">
            Password Again
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
            <input type="PASSWORD" size="20" name="PASSWORD2">
          </font>
        </td>
        <td class="TBLROW">
          <font class="TBLROW">
                    Repeat the password for the admin user.
          </font>
        </td>
      </tr> 
      <tr>
        <td colspan="3" class="TBLROW" align="CENTER">
          <input type="SUBMIT" onClick="return checkForm(this.form);" name="CREATE" value="<?php echo $Create ?>">
        </td>
      </tr>
    </table>
  </form>
<?php
  }
?>
        </font>
      </td>
      <!-- Right Column -->
      <td class="RIGHTCOL" align="RIGHT">
      </td>
    </tr>
  </table>
  </body>
</html>

