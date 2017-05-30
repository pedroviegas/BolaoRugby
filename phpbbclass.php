<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 28th April 2004
 * File  : phpbbclass.php
 * Desc  : Class used to integrate with phpBB
 *       : prediction league.
 ********************************************************/

require_once "authorityclass.php";

class phpBB extends authority {
  function phpBB() {

  }


  // Is there a user logged in.
  function isLoggedIn() {
    return true;
  }

  // Get the data for the user from the appropriate storage.
  // If the user has logged into phpbb and not yet used 
  // PF, then this will read the data from the PF tables.
  function getUserData() {

  }

  function setUserData() {

  }

}
?>
