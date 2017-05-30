<?php
/*********************************************************
 * Author: John Astill
 * Date  : 10th December
 * File  : createdbase.php
 * Desc  : Create the required database and tables for
 *       : the prediction league. This script interacts
 *       : with the given database to create the correct
 *       : structure of tables for the prediction league.
 *       : If the tables are created successfully, the 
 *       : created structure of database and tables is 
 *       : displayed.
 ********************************************************/
require_once "systemvars.php";
require_once "dbasefunctions.php";

  $url = $_POST["URL"];
  $dbname = $_POST["DBASENAME"];
  $username = $_POST["USERNAME"];
  $password = $_POST["PASSWORD"];
  $create = "";
  if (array_key_exists("CREATEDB",$_POST)) {
    $create = $_POST["CREATEDB"];
  }
  $createtables = $_POST["CREATETABLES"];
  $userDataTblName = $dbaseUserData;
  $predDataTblName = $dbasePredictionData;
  $matchDataTblName = $dbaseMatchData;
  $standingsTblName = $dbaseStandings;

  // Connect to the host.
  $link = mysql_connect($url, $username, $password)
      or die("Could not connect to $url");

  // Only create the database if requested to.
  if ($create == "TRUE") {
    // Create the database
    $db = mysql_query("create database $dbname",$link);
    if ($db == FALSE) {
      echo "Unable to create database $dbname. Make sure $dbname does not already exist\n";
      exit;
    }
  }

  if ($createtables == "TRUE") {
    $query = "create table $dbname.$dbaseUserData (lid int not null , userid int not null auto_increment, username varchar($userlen) not null , password varchar($passlen), email varchar($emaillen), icon varchar($fnamelen), lang varchar(32), usertype smallint, dflths smallint default 0, dfltas smallint default 0, since DATE, auto enum('Y','N') default 'N', lastip varchar(20) default 0, lastport varchar(8) default 0, lastlogin datetime, allowemail enum('Y','N') default 'N', showstandings enum('Y','N') default 'N', primary key (lid,userid));";
    $userresult = mysql_query($query)
        or die("Query failed: $query<br>\n".mysql_error());

  $query = "create table $dbname.$dbasePredictionData (lid int not null , userid int not null, matchid int not null ,predtime timestamp, homescore smallint unsigned, awayscore smallint unsigned, points smallint unsigned default 0, isauto enum('Y','N') default 'N', primary key(lid, userid, matchid))";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseMatchData (lid int not null, matchid int not null auto_increment, matchdate DATETIME not null, hometeam varchar($teamlen) not null, awayteam varchar($teamlen) not null, homescore smallint unsigned, awayscore smallint unsigned, homepen smallint unsigned default 0, awaypen smallint unsigned default 0, bonuspoints int default 0, gametype char default 'L', competition int, week smallint default 0, primary key(lid,matchid));";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseStandings (lid int not null, userid int not null, week smallint not null default 0, weekdate datetime, position smallint not null, prevpos smallint default 0, pld int unsigned, won int unsigned, drawn int unsigned, lost int unsigned, gfor smallint unsigned, gagainst smallint unsigned, diff smallint, points int unsigned, displaypos smallint not null default 0, monthdate int not null default 0, prevmpts int default 0 not null, prevwpts int default 0 not null, standtype enum ('w','m') default 'w' not null, primary key(lid,userid, week, monthdate));";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseConfigData (lid int not null, grp int not null, param varchar(32) not null, value varchar(90) not null, descr text not null, ro enum('Y','N') default 'N', primary key(lid,param));";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseMsgData (lid int not null, msgid int not null auto_increment, threadid int, prevmsg int, sender int not null, receiver int not null, subject varchar(255), body text, status enum(\"new\",\"read\",\"deleted\"), msgtime timestamp, primary key(lid,msgid));";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseAddrData (lid int not null default 0, userid int not null , ipaddr varchar(32) not null, port varchar(8), ts timestamp, primary key(lid,userid, ipaddr));";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseComps (lid int not null default 0, comp int not null auto_increment, name varchar(64) not null, primary key(lid,comp));";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseGroups (lid int not null default 0, grp int not null, team varchar(30) not null, primary key(lid,grp,team));";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseGroupStandings (lid int not null default 0, grp char not null, team varchar(30) not null, w int, d int, l int, f int, a int, pld int, pts int, primary key(lid,grp,team));";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());

    $query = "create table $dbname.$dbaseShout (lid int not null, sid int not null auto_increment, ts timestamp, userid int not null, msg text, primary key(lid, sid))";
    $userresult = mysql_query($query)
      or die("Query failed: $query<br>\n".mysql_error());
  }

  PopulateConfigTable();
?>
<html>
  <head>
    <title>
      Created Database <?php echo $dbname; ?>
    </title>
    <link rel="stylesheet" href="common.css" type="text/css">
  </head>

  <body class="MAIN">
    <table>
      <tr>
        <td class="TBLHEAD" colspan="4" align="CENTER">
          <font class="TBLHEAD">
            Database and Table Administration
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
<table width="400">
<tr>
<td colspan="2" align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
<b>
Database <?php echo $dbname ?>
</b>
</font>
</td>
</tr>
<tr>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
Table
</font>
</td>
<td align="CENTER" class="TBLHEAD">
<font class="TBLHEAD">
Attributes
</font>
</td>
</tr>
<?php
  
  // List the table names.
//  $link = mysql_connect($dbaseHost,$dbaseUsername,$dbasePassword);
  $tbl_list = mysql_list_tables($dbname,$link);
  $i = 0;
  while ($i < mysql_num_rows ($tbl_list)) {
    echo "<tr><td valign=\"TOP\" class=\"TBLROW\"><font class=\"TBLROW\">";
      $tb_names[$i] = mysql_tablename ($tbl_list, $i);
      echo $tb_names[$i];

      // List the field names.
      $fields = mysql_list_fields($dbname, $tb_names[$i], $link);
      $columns = mysql_num_fields($fields);

      echo "</font></td>";
      echo "<td class=\"TBLROW\"><font class=\"TBLROW\">";
      for ($j = 0; $j < $columns; $j++) {
          echo mysql_field_name($fields, $j) . "<br>\n";
      }
      
      $i++;
    echo "</font></td></tr>";
  }
?>
</table>

          </font>
        </td>
      </tr>
    </table>

  </body>
</html>

