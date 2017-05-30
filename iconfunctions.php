<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 24th June 2004
 * File  : iconfunctions.php
 *********************************************************/

/*********************************************************
 * Show the icons so that the user can select one.
 *********************************************************/
function ShowIcons($del) {
  global $SID, $Click_on_the_Icon,$Txt_Are_You_Sure_Icon, $Txt_Click_On_The_Icon_To_Delete;
  global $IconsDirectory;
?>
<table class="CENTER">
  <tr>
    <td align="CENTER" class="TBLHEAD" colspan="4">
      <font class="TBLHEAD">
        <?php
if ($del == false) {
  echo $Click_on_the_Icon."\n";
} else {
  echo $Txt_Click_On_The_Icon_To_Delete."\n";
} ?>
      </font>
    </td>
  </tr>
<?php
  $dirname = $IconsDirectory;
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
    if ($del == false) {
      echo "<a href=\"index.php?sid=$SID&cmd=si&icon=$file\">";
    } else {
      echo "<a onclick='return confirm(\"$Txt_Are_You_Sure_Icon\");' href=\"index.php?sid=$SID&cmd=delicon&icon=$file\">";
    }
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
<?php
}

/*****************************************
 * Deleting icons
 *****************************************/
function DeleteIcon() {
  global $SID,$IconsDirectory;
  $icon = $_GET["icon"];
  $dirname = $IconsDirectory;

  if (false == unlink("$dirname/$icon")) {
    ErrorRedir("Unable to delete $icon", "index.php?sid=$SID&cmd=manageicons");
  }
}

?>
