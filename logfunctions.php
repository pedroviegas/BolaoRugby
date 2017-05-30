<?php
/*********************************************************
 * Author: John Astill
 * Date  : 15th June 2002
 * File  : logfunctions.php
 ********************************************************/

/*******************************************************
* Append the given message to the log file.
*******************************************************/
function LogMsg($msg) {
  global $logfile, $_SERVER;

  $ip = $_SERVER["REMOTE_ADDR"];
  $port = "Unknown";
  if (array_key_exists("REMOTE_PORT",$_SERVER)) {
    $port = $_SERVER["REMOTE_PORT"];
  }
  $referer = "";
  if (array_key_exists('HTTP_REFERER',$_SERVER)) {
    $referer = $_SERVER["HTTP_REFERER"];
  }

  // If the log filename is "", don't try to write to it.
  if ($logfile == "") {
    return;
  }

  // Write to the end of the file
  $file = @fopen($logfile,'a+');
  if ($file == FALSE) {
    ErrorNotify("Unable to write to Log : $logfile . This could be due to the log file not having the correct permissions on the server.");
    return;
  }

  $date = date("M/d/Y H:i:s T");
  fwrite($file,"\n------------------------\n$date\nIP $ip:$port from $referer\n$msg");
  
  fclose($file);
}

/****************************************************
 * Display the log file on the screen
 ****************************************************/
function ShowLog() {
  global $logfile;
    if ($logfile == "") {
      echo "Logfile is not configured.\n";
    } else {
?>
<iframe src="<?php echo $logfile; ?>" width="520" height="400"></iframe> 
<?php
    }
}
?>
