<?php
/***********************************************************
 * Simple test script for showing php config values.
 ***********************************************************/

// This shows where the PHP config is stored
$path = get_cfg_var('cfg_file_path');
echo $path."<br>";

// Show the path used to store uploaded files.
$path = get_cfg_var('upload_tmp_dir');
if ($path == FALSE) {
  echo 'upload_tmp_dir not found, using system default whatever that may be.';
} else {
  echo "Path = $path";
}
echo "<br>";
$path = get_cfg_var('file_uploads');
if ($path == FALSE) {
  echo 'file_uploads not found';
} else {
  echo "file_uploads = $path";
}

echo "<br>";
$path = get_cfg_var('upload_max_filesize');
if ($path == FALSE) {
  echo 'upload_max_filesize not found';
} else {
  echo "upload_max_filesize = $path";
}

echo "<br>";
$path = get_cfg_var('doc_root');
if ($path == FALSE) {
  echo 'doc_root not found';
} else {
  echo "doc_root = $path";
}

echo "<br>";
echo $_SERVER['SCRIPT_FILENAME'];

echo "<br>";
echo $_SERVER['DOCUMENT_ROOT'];

echo "<br>";
echo $_SERVER['PATH_TRANSLATED'];

echo "<br>";
echo dirname($_SERVER['PATH_TRANSLATED']).DIRECTORY_SEPARATOR;
echo "<br>";
echo dirname($_SERVER['PATH_TRANSLATED']).PATH_SEPARATOR;

?>
