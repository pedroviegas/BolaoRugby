<?php

/*********************************************************
 * Author: John Astill (c)
 * Date  : 20th March 2003
 * File  : getmatchstats.php
 * Descr : This file is used in conjunction with the 
 *       : index file and dynamic html to allow the 
 *       : leading tipsters to be displayed in an html
 *       : file.
 *********************************************************/
  require_once "statsfunctions.php";
  require_once "systemvars.php";
  require_once "dbasedata.php";
  require_once "configfunctions.php";
  require_once "configvalues.php";

  // If you want to change the number of displayed users, change this value.
  $cmd = $HTTP_GET_VARS["cmd"];
  $mid = $HTTP_GET_VARS["matchid"];
  $mdate = $HTTP_GET_VARS["matchdate"];
  
  $prev = 0;
  $first = 0;
  $newmid = 0;

//  $query = "select distinct matchid, * from $dbaseMatchData inner join $dbasePredictionData on $dbaseMatchData.lid=$dbasePredictionData.lid and $dbaseMatchData.matchid=$dbasePredictionData.matchid order by matchdate, matchid";
  $query = "select * from $dbaseMatchData where lid = $leagueID order by matchdate, matchid";
  $result = $dbase->query($query);

  if (mysql_num_rows($result) == 1) {
    $newmid = $mid;    
  } else {
    $id = $mid;
    // Now we have all the matches that have the same date
    while ($line = mysql_fetch_array($result)) {
      $id = $line["matchid"];
      if ($first == 0) {
        $first = $id;
      }
      if ($id == $mid && $prev != 0) {
        if ($cmd == "prev") {
          $newmid = $prev; 
          break;
        } 
      }
      if ($mid == $prev && $cmd == "next") {
        $newmid = $id;
        break;
      }
      $prev = $id;
    }
    if ($mid == $first && $cmd == "prev") {
      $newmid = $id;
    }
    if ($id == $mid && $cmd == "next") {
      $newmid = $first;
    }
  }
  
  // Now work out the stats for this match.
  $query = "select * from $dbaseMatchData where matchid='$newmid' and lid='$leagueID'";
  $result = $dbase->query($query);
  $line = mysql_fetch_array($result);
  $hometeam = $line["hometeam"];
  $awayteam = $line["awayteam"];
  
  $results = Array();
  $results = getStatsForMatch($results, $newmid);
  arsort($results);
  $count = count($results);
  
  $numpreds = array_sum($results);
?>
<script type="text/javascript">
  var count = <?php echo $count; ?>;
  var scores = new Array(<?php echo $count; ?>);
  var predcount = new Array(<?php echo $count; ?>);
  var numpreds = <?php echo $numpreds; ?>;
<?php

$count = 0;
while (list($key,$val) = each($results)) {

?>
  scores[<?php echo $count; ?>] = "<?php echo "$key";?>";
  predcount[<?php echo $count; ?>] = "<?php echo "$val";?>";
<?php
  $count ++;
}
?>
  window.parent.setStats("<?php echo $newmid; ?>","<?php echo $mdate; ?>","<?php echo $hometeam; ?>","<?php echo $awayteam; ?>",count,scores,predcount,numpreds);
</script>
