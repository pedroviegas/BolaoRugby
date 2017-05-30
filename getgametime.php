<?php
require "systemvars.php";
require "dbasedata.php";
require "configvalues.php";
require "sortfunctions.php";

/*********************************************************
 * Author: John Astill (c)
 * Date  : 20th March 2003
 * File  : getservertime.php
 *********************************************************/
$date = TimeOnlyZoneOffset("now");
$offset = (date("Z")/(60*60));
?>
<script type="text/javascript">
  var fixt = "<?php echo $date ?>";
  var offset = "<?php echo $offset ?>";
  window.parent.setGameDate(fixt,offset);
</script>
