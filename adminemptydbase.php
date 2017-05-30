<?php
/*********************************************************
 * Author: John Astill
 * Date  : 2nd July 2003 (C)
 * File  : adminemptydbase.php
 * Desc  : This file will prompt the user about emptying
 *       : the contents of the database.
 ********************************************************/
 require "systemvars.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Empty the current dbase tables
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">
    <table>
      <tr>
        <td class="TBLHEAD" colspan="4" align="CENTER">
          <font class="TBLHEAD">
            Empty the tables!!!!!!
          </font>
        </td>
      </tr>
      <tr>
        <td class="LEFTCOL">
          <font class="TBLHEAD">
            <?php require "adminsetupmenu.php"; ?>
          </font>
        </td>
        <td class="CENTERCOL" colspan="3" align="CENTER">
          <font class="TBLHEAD">
            <form method="POST" action="emptytables.php">
              <table>
                <tr>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      Database Host (URL)
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      <input type="TEXT" size="20" name="URL" value="">
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      The FQDN for the database.
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      Username
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      <input type="TEXT" size="20" name="USERNAME" value="">
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      This entry is required to connect to the MySQL database if the database has authentication configured.
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
                      <input type="PASSWORD" size="20" name="PASSWORD" value="">
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      This entry is required to connect to the MySQL database if the database has authentication configured.
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      Database Name
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      <input type="TEXT" size="20" name="DBASENAME" value="">
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
            This is the name of the database which contains the tables to be emptied.
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td class="TBLROW" colspan="3">
                    <font class="TBLROW">
                      <b>
                      This will empty the tables and all the contents. Make sure this is what you intend. All your Prediction League data will be lost.
                      </b>
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td colspan="3" class="TBLROW" align="CENTER">
                    <input type="SUBMIT" NAME="EMPTY" VALUE="Empty">
                  </td>
                </tr>
              </table>
            </form>
          </font>
        </td>
      </tr>
    </table>
  </body>
</html>

