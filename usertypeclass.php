<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 26th February 2004
 * File  : usertypeclass.php
 * Desc  : Class representing a user type
 *       : prediction league.
 ********************************************************/
class UserType {

  // Contructor for the user class.
  function UserType() {
  }

////////////////////////////////////////////
// Accessors.
////////////////////////////////////////////

////////////////////////////////////////////
// Class Methods
////////////////////////////////////////////
  function ToText($ut) {
    $types = array("1" => "Normal",
                   "2" => "Priveleged",
                   "4" => "Admin",
                   "8" => "Root");
    return $types[$ut];
  }
}
?>
