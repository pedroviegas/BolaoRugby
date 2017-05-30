<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 9th December
 * File  : index.php
 ********************************************************/
// Measure the time taken to load the page.
$starttime = time();

  require_once "systemvars.php";
  require_once "dbasedata.php";
  require_once "configfunctions.php";
  require_once "configvalues.php";
  require_once "sortfunctions.php";
  require_once "filefunctions.php";
  require_once "security.php";
  require_once "userfunctions.php";
  require_once "iconfunctions.php";
  require_once "fixturefunctions.php";
  require_once "tournamentfunctions.php";
  require_once "shoutboxfunctions.php";
  require_once "controller.php";
  
  // Get the navigation command.
  $cmd = "table";
  if (array_key_exists("cmd",$_GET)) {
    $cmd = $_GET["cmd"];
  }
  $controller = new Controller($cmd, $User);

  if ($controller->is_view() == true) {
    require_once "mainview.php";
  } else {
    $controller->execute();
  }
?>
