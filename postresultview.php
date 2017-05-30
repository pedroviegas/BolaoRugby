<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 1st June 2004
 * File  : postresultview.php
 * Desc  : The selected fixture is received in the GET
 *       : parameters. Show a form to the administrator.
 *       : When select is hit, update the database.
 * TODO  : Add javascript to validate the input. i.e. the
 *       : scores must be numeric values.
 ********************************************************/
global $SID;
?>
  <form method="POST" action="index.php?sid=<?php echo $SID;?>&cmd=postresultval">
  <table class="CENTER">
  <tr>
  <td colspan="7" align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  Enter Result
  </font>
  </td>
  </tr>

  <tr>
  <td align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  Date
  </font>
  </td>

  <td align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  Home Team
  </font>
  </td>
  <td align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  <!-- None -->
  &nbsp;
  </font>
  </td>

  <td align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  <!-- None -->
  &nbsp;
  </font>
  </td>

  <td align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  <!-- None -->
  &nbsp;
  </font>
  </td>

  <td align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  Away Team
  </font>
  </td>
  <td align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  <!-- None -->
  &nbsp;
  </font>
  </td>
  </tr>

  <tr>
  <td class="TBLROW" align="CENTER">
  <font class="TBLROW">
  <?php
    $date = $_GET["matchdate"];
    echo GetDateFromDatetime($date)." ".GetTimeFromDatetime($date);
  ?>
  <input type="hidden" size="2" name="MID" value="<?php echo $_GET["mid"] ?>">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font class="TBLROW">
  <?php echo $_GET["hometeam"]; ?>
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font class="TBLROW">
  <input type="text" size="2" name="HOMESCORE" value="<?php echo $_GET['hs']; ?>">
<?php
  $gametype = $_GET["gametype"];
  if ($gametype != 'L') {
?>
  <br>
  <input type="text" size="1" name="HOMEPEN" value="<?php echo $_GET['hp']; ?>">
<?php } ?>
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font class="TBLROW">
  v
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font class="TBLROW">
  <input type="text" size="2" name="AWAYSCORE" value="<?php echo $_GET['as']; ?>">
<?php
  if ($gametype != "L") {
?>
  <br>
  <input type="text" size="1" name="AWAYPEN" value="<?php echo $_GET['ap']; ?>">
<?php } ?>
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font class="TBLROW">
  <?php echo $_GET["awayteam"]; ?>
  </font>
  </td>
  <td align="center" class="TBLROW">
  <font class="TBLROW">
  <input type="submit" name="POST" value="Post">
  </font>
  </td>
  </tr>
  </table>
  </form>
