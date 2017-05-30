<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 28th August 2004
 * File  : fixshowdivisions.php
 *********************************************************/
   require_once "systemvars.php";
   require_once "dbasedata.php";

  /**************************************************************
   * Set a value if it does not already exist
   **************************************************************/
  function setValueIfNotExists($value,$query) {
    global $dbase, $leagueID, $dbaseConfigData;
    $result = $dbase->query("select * from $dbaseConfigData where lid='$leagueID' and param='$value'");
    if ($dbase->getNumberOfRows() > 0) {
      return;
    }
    $result = $dbase->query($query);
  }

  /**************************************************************
   * Run a simple query.
   **************************************************************/
  function runQuery($query) {
    global $dbase;
    $result = $dbase->query($query);
  }

  // Add SHOWDIVISIONS
  setValueIfNotExists('SHOWDIVISIONS',"insert into $dbaseConfigData (lid,param,value,descr) values ('$leagueID','SHOWDIVISIONS','$ShowDivisions','Show the tabs as divisions instead of numbering.')");

  echo "Show divisions should be fixed. For security purposes remove this file from your server!!!!!<br>";

?>
