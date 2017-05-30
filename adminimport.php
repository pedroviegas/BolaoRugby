<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 14 March 2003
 * File  : adminimport.php
 * Desc  : Import the data from a previous version of the
 *       : prediction league.
 ********************************************************/
 require "systemvars.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>
      Import
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">
    <table>
      <tr>
        <td class="TBLHEAD" colspan="4" align="CENTER">
          <font class="TBLHEAD">
            Import data from a version 0.43 - 0.50 database
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
            <form method="POST" action="importdbase.php">
              <table>
                <tr>
                  <td class="TBLROW" colspan="3">
                    <font class="TBLROW">
                      This utility is for importing the data from an older version of the prediction league. 
                      It is possible to copy within the same database as long as different table names are used between the versions.
                      <br>
                      It is assumed that there is only one league in the old tables, i.e. there is only one lid in the tables. 
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      Database Host (URL)
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      <input type="TEXT" size="20" name="URL" value="localhost">
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
                      <input type="TEXT" size="20" name="DBASENAME" value="PredictionLeague">
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
            This is the name of the database containing the data. This can be the same name as the new database.
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      User Data table name
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      <input type="TEXT" size="20" name="USERDATATBL" value="UserData">
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
            This is the name of the user data table from which to retrieve the userdata. The default value for this table is UserData. This only needs to be modified if you changed the value in SystemVars.php.
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      Match Data table name
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      <input type="TEXT" size="20" name="MATCHDATATBL" value="MatchData">
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
            This is the name of the match data table from which to retrieve the matchdata. The default value for this table is MatchData. This only needs to be modified if you changed the value in SystemVars.php.
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      Prediction Data table name
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
                      <input type="TEXT" size="20" name="PREDDATATBL" value="PredictionData">
                    </font>
                  </td>
                  <td class="TBLROW">
                    <font class="TBLROW">
            This is the name of the prediction data table from which to retrieve the predictiondata. The default value for this table is MatchData. This only needs to be modified if you changed the value in SystemVars.php.
                    </font>
                  </td>
                </tr> 
                <tr>
                  <td colspan="3" class="TBLROW" align="CENTER">
                    <input type="SUBMIT" NAME="IMPORT" VALUE="IMPORT">
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

