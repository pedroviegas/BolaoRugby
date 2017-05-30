<?php
/*******************************************************
 * Upload the given icon
 *******************************************************/
function UploadIcon() {
  global $MaxIconFileSize, $SID, $IconsDirectory;
  global $FileDirectory;

  if (array_key_exists('PATH_TRANSLATED',$_SERVER)) {
    $IconDirectory = dirname($_SERVER['PATH_TRANSLATED']).DIRECTORY_SEPARATOR.$IconsDirectory.DIRECTORY_SEPARATOR;
  } else {
    if ($FileDirectory == "") {
        ErrorRedir("Unable to determine the absolute path for the scripts. The server does not support PATH_TRANSLATED and you have not set the optional FileDirectory value. To get this to work set the values of FileDirectory in systemvars.","index.php?sid=$SID&cmd=profile");
    } else {
      $IconDirectory = $FileDirectory.DIRECTORY_SEPARATOR.$IconsDirectory.DIRECTORY_SEPARATOR;
    }
  }

  $name = "";

  // Make sure that the posted file has been uploaded.
  if ((array_key_exists("ICONNAME",$_FILES)) && (is_uploaded_file($_FILES["ICONNAME"]['tmp_name']) == TRUE)) {
    $filesize = $_FILES["ICONNAME"]['size'];
    $origname = $_FILES["ICONNAME"]['name'];
    if ( ($MaxIconFileSize*1024) < $filesize) {
      ErrorRedir("File size of $origname is too large [$filesize bytes]. Max file size is $MaxIconFileSize Kb","index.php?sid=$SID&cmd=profile");
    } else {
      $origname = $_FILES["ICONNAME"]['name'];
      $name = $origname;
      
      // Make sure that the target file does not exist.
      if (file_exists($IconsDirectory."/".$name)) {
        ErrorRedir("Unable to copy the file $origname into the icons directory as a file with that name already exists.","index.php?sid=$SID&cmd=profile");
      }
      
      if (@move_uploaded_file( $_FILES["ICONNAME"]['tmp_name'],$IconDirectory.$name) == FALSE) {
        LogMsg("Unable to copy the file $name to the directory $IconDirectory. Check that the icon directory is writeable for the web server.");
        ErrorRedir("Unable to copy the file $origname into the icons directory.","index.php?sid=$SID&cmd=profile");
      }
    }
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
    $port = "Unknown";
    if (array_key_exists("REMOTE_PORT",$_SERVER)) {
      $port = $_SERVER['REMOTE_PORT'];
    }
    LogMsg("Possible upload attack. Someone is attempting to upload files directly to this script from IP $ip Port $port."); 
  }
}
?>
