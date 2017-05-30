<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 17th March 2004
 * File  : iconview.php
 *********************************************************/
global $SID, $Click_on_the_Icon, $IconsDirectory;
?>
<table class="CENTER">
  <tr>
    <td align="CENTER" class="TBLHEAD" colspan="4">
      <font class="TBLHEAD">
        <?php echo $Click_on_the_Icon."\n"; ?>
      </font>
    </td>
  </tr>
<?php
  $dirname = "$IconsDirectory";
  $dir = @opendir($dirname);
  if ($dir == FALSE) {
    // Oh no, no files.
    $error = "Installation problem, unable to open directory $dirname";
    echo($error);
  }

  $count = 0;
  while (($file = readdir($dir)) != FALSE) {
    if (TRUE == is_dir($file)) {
      continue;
    }

    if (($count%4) == 0) {
      echo "<tr>\n";
    }
    $count++;
?>
            <td align="CENTER" class="TBLROW">
              <font class="TBLROW">
<?php
                $fullname = $dirname."/".$file;
                echo "<a href=\"index.php?sid=$SID&cmd=si&icon=$file\">";
                echo "<img border=\"0\" src=\"$fullname\">";
                echo "</a>";
?>
              </font>
            </td>
<?php
    if (($count%4) == 0) {
      echo "</tr>\n";
    }
  }
  closedir($dir);
?>
</table>
