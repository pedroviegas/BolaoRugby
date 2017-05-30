<?php 
/*********************************************************
 * Author: John Astill (c) 
 * Date  : 29th March 2004
 * File  : playerstandingsclass.php
 ********************************************************/

class PlayerStanding {
  var $userid;
  var $username;
  var $points;
  var $pld;
  var $won;
  var $drawn;
  var $lost;

  function PlayerStanding($standing) {
    $this->userid = $standing["userid"];
    $this->username = $standing["username"];
    $this->points = $standing["points"];
    $this->pld = $standing["pld"];
    $this->won = $standing["won"];
    $this->drawn = $standing["drawn"];
    $this->lost = $standing["lost"];
  }

  function subtract($standing) {
    $this->points -= $standing["points"];
    $this->pld -= $standing["pld"];
    $this->won -= $standing["won"];
    $this->drawn -= $standing["drawn"];
    $this->lost -= $standing["lost"];
  }
}
?>
