<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 20th March 2003
 * File  : getleaderboard.php
 * Descr : This file is used in conjunction with the 
 *       : index file and dynamic html to allow the 
 *       : leading tipsters to be displayed in an html
 *       : file.
 *********************************************************/
  require "systemvars.php";
  require "dbasedata.php";

  // If you want to change the number of displayed users, change this value.
  $numberOfUsers = 10;

  $date = date("h:i:s A") ." [". (date("Z")/(60*60))."]";

  $query = "select username,position,points from $dbaseStandings,$dbaseUserData where $dbaseUserData.userid=$dbaseStandings.userid and $dbaseStandings.lid='$leagueID' order by week desc, position limit 10";
  $standingsres = $dbase->query($query);
  $standings = array();
  $points = array();
  $count = 0;
  while ($pos = mysql_fetch_array($standingsres)) {
    $name = $pos["username"];
    $posit = $pos["position"];
    $pts = $pos["points"];
    //$standings[$count] = "$name $posit";
    $standings[$count] = $name;
    $points[$count] = $pts;
    $count++;
  }
?>
<script type="text/javascript">
  var names = new Array(<?php echo $numberOfUsers; ?>);
  var points = new Array(<?php echo $numberOfUsers; ?>);
<?php 
  
  while (list($key,$val) = each($standings)) {
?>
    names[<?php echo $key; ?>] = '<?php echo "$val";?>';
    points[<?php echo $key; ?>] = '<?php echo "$points[$key]";?>';
<?php
  }
?>
  // Loop through the leaders and add to an array to
  // be passed to the javascript.
  window.parent.setLeaderBoard(names,points);
</script>
