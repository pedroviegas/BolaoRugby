<?php
/***************************************************
 * This file contains the Tournament specific code.
 * This is where the standings for the teams are 
 * calculated and the playofffixtures decided.
 ***************************************************/

  // Team standing.
  class Standing {
    var $pos;
    var $grp;
    var $team;
    var $pld;
    var $won;
    var $lost;
    var $drawn;
    var $gf;
    var $ga;
    var $pts;

    function Standing($grp, $team) {
      $this->grp = $grp;
      $this->team = $team;
      $this->won = 0;
      $this->drawn = 0;
      $this->lost = 0;
      $this->pld = 0;
      $this->gf = 0;
      $this->ga = 0;
      $this->pts = 0;
    }

    function setPosition($np) {
      $this->pos = $np;
    }

    function getPosition() {
      return $this->pos;
    }

    function setGroup($ng) {
      $this->grp = $ng;
    }

    function getGroup() {
      return $this->grp;
    }

    function setTeam($nt) {
      $this->team = $nt;
    }

    function getTeam() {
      return $this->team;
    }

    function setPlayed($np) {
      $this->pld = $np;
    }

    function getPlayed() {
      return $this->pld;
    }

    function setWon($nw) {
      $this->won = $nw;
    }

    function getWon() {
      return $this->won;
    }

    function setDrawn($nd) {
      $this->drawn = $nd;
    }

    function getDrawn() {
      return $this->drawn;
    }

    function setLost($nl) {
      $this->lost = $nl;
    }

    function getLost() {
      return $this->lost;
    }

    function setFor($nf) {
      $this->for = $nf;
    }

    function getFor() {
      return $this->for;
    }

    function setAgainst($na) {
      $this->against = $na;
    }

    function getAgainst() {
      return $this->against;
    }

    function setPoints($np) {
      $this->pts = $np;
    }

    function getPoints() {
      return $this->pts;
    }

    /************************************************
     * Update the statistics
     ************************************************/
    function updateStats($gf, $ga) {
      //echo "$this->team $gf v $ga "; // TODO
      $this->pld += 1;
      //echo "$this->pld<br>"; // TODO
      if ($gf > $ga) {
        $this->won += 1;
      } else if ($ga > $gf) {
        $this->lost += 1;
      } else {
        $this->drawn += 1;
      }

      $this->gf += $gf;
      $this->ga += $ga;

      $this->pts = ($this->won*3) + ($this->drawn);
    }

    /************************************************
     * Write this to the database
     ************************************************/
    function persist() {
      global $leagueID, $dbase, $dbaseGroupStandings;
      $query = "replace into $dbaseGroupStandings set grp='$this->grp', pld='$this->pld', f='$this->gf', a='$this->ga',lid='$leagueID', team='$this->team', w='$this->won', d='$this->drawn', l='$this->lost', pts='$this->pts'";
      $dbase->query($query);
      // echo $query."<br>"; // TODO
    }
  }

  /*************************************************
   * Determine if the last game of the group is
   * over.
   *************************************************/
  function IsGroupFinished($grp) {
    global $dbase, $dbaseMatchData, $dbaseGroups, $leagueID;

    $query = "select matchdate from $dbaseMatchData inner join $dbaseGroups on $dbaseMatchData.hometeam = $dbaseGroups.team where $dbaseMatchData.lid='$leagueID' and grp='$grp' and matchid<25 order by matchdate desc limit 1";
    $res = $dbase->query($query);
    if ($dbase->getNumberOfRows() > 0) {
      $line = mysql_fetch_array($res);
      $date = $line["matchdate"];

      // Is the match in the past?
      if (CompareDatetime($date) < 0) {
        return TRUE;
      }
    }
    return FALSE;
  }

 /**************************************************
  * Set the teams for the knockout stages.
  * This is very reliant on the matchids being 
  * correct.
  **************************************************/
  function SetKnockoutTeams() {
    global $dbaseMatchData, $dbase, $leagueID, $dbaseGroupStandings;


// If the last game of group A is complete
if (IsGroupFinished('A') == TRUE) {
    // Winner of group A is match 25 home team and
    // runner up of group B is match 25 away team .....
    $query = "SELECT * FROM $dbaseGroupStandings where lid='$leagueID' and grp='A' order by pts desc, (f-a) desc limit 2";
    $res = $dbase->query($query);
    $line = mysql_fetch_array($res);
    $m25home = $line["team"];
    $line = mysql_fetch_array($res);
    $m26home = $line["team"];

    $query = "update $dbaseMatchData set hometeam='$m25home' where lid='$leagueID' and matchid='25'";
    $dbase->query($query);

    $query = "update $dbaseMatchData set hometeam='$m26home' where lid='$leagueID' and matchid='26'";
    $dbase->query($query);
}

// If the last game of group B is complete
if (IsGroupFinished('B') == TRUE) {
    $query = "SELECT * FROM $dbaseGroupStandings where lid='$leagueID' and grp='B' order by pts desc, (f-a) desc limit 2";
    $res = $dbase->query($query);
    $line = mysql_fetch_array($res);
    $m26away = $line["team"];
    $line = mysql_fetch_array($res);
    $m25away = $line["team"];

    $query = "update $dbaseMatchData set awayteam='$m25away' where lid='$leagueID' and matchid='25'";
    $dbase->query($query);

    $query = "update $dbaseMatchData set awayteam='$m26away' where lid='$leagueID' and matchid='26'";
    $dbase->query($query);
}

// If the last game of group C is complete
if (IsGroupFinished('C') == TRUE) {
    $query = "SELECT * FROM $dbaseGroupStandings where lid='$leagueID' and grp='C' order by pts desc, (f-a) desc limit 2";
    $res = $dbase->query($query);
    $line = mysql_fetch_array($res);
    $m27home = $line["team"];
    $line = mysql_fetch_array($res);
    $m28home = $line["team"];

    $query = "update $dbaseMatchData set hometeam='$m27home' where lid='$leagueID' and matchid='27'";
    $dbase->query($query);

    $query = "update $dbaseMatchData set hometeam='$m28home' where lid='$leagueID' and matchid='28'";
    $dbase->query($query);
}

// If the last game of group D is complete
if (IsGroupFinished('D') == TRUE) {
    $query = "SELECT * FROM $dbaseGroupStandings where lid='$leagueID' and grp='D' order by pts desc, (f-a) desc limit 2";
    $res = $dbase->query($query);
    $line = mysql_fetch_array($res);
    $m28away = $line["team"];
    $line = mysql_fetch_array($res);
    $m27away = $line["team"];

    $query = "update $dbaseMatchData set awayteam='$m27away' where lid='$leagueID' and matchid='27'";
    $dbase->query($query);

    $query = "update $dbaseMatchData set awayteam='$m28away' where lid='$leagueID' and matchid='28'";
    $dbase->query($query);
}

// For the semis make sure the Quarter finals are done.
    // For the semis, the teams are taken from the quarter final winners.
    ////////////////////////////////////////////////////////////////////////////////////
    // Set the hometeam for game 29
    if (($team = GetWinner(25)) != FALSE) {
      $query = "update $dbaseMatchData set hometeam='$team' where matchid='29' and lid='$leagueID'";
      $dbase->query($query);
    }

    // Set the awayteam for game 29
    if (($team = GetWinner(27)) != FALSE) {
      $query = "update $dbaseMatchData set awayteam='$team' where matchid='29' and lid='$leagueID'";
      $dbase->query($query);
    }

    // Set the hometeam for game 30
    if (($team = GetWinner(26)) != FALSE) {
      $query = "update $dbaseMatchData set hometeam='$team' where matchid='30' and lid='$leagueID'";
      $dbase->query($query);
    }

    // Set the awayteam for game 30
    if (($team = GetWinner(28)) != FALSE) {
      $query = "update $dbaseMatchData set awayteam='$team' where matchid='30' and lid='$leagueID'";
      $dbase->query($query);
    }

    //////////////////////////////////////////////////////////////////////////////////////
    // Set the teams for the final
    if (($team = GetWinner(29)) != FALSE) {
      $query = "update $dbaseMatchData set hometeam='$team' where matchid='31' and lid='$leagueID'";
      $dbase->query($query);
    }
    if (($team = GetWinner(30)) != FALSE) {
      $query = "update $dbaseMatchData set awayteam='$team' where matchid='31' and lid='$leagueID'";
      $dbase->query($query);
    }
  }

  /*************************************************
   * Get the winner of the given game.
   *************************************************/
  function GetWinner($matchid) {
    global $dbase, $dbaseMatchData, $leagueID;

    $today = time();

    $query = "SELECT * , UNIX_TIMESTAMP(matchdate) AS uts FROM $dbaseMatchData WHERE lid='$leagueID' and matchid='$matchid'";
    $res = $dbase->query($query);
    $line = mysql_fetch_array($res);
    $mdate = $line["uts"];
    $home = $line["hometeam"];
    $away = $line["awayteam"];
    $hs = $line["homescore"];
    $as = $line["awayscore"];
    $hp = $line["homepen"];
    $ap = $line["awaypen"];

    // If the game hasn't been played yet we can exit.
    if ($today < $mdate) {
      return FALSE;
    }

    // Add the penalties to the goals. If there are no penalties 
    // then the values should be 0.
    if ($hp == "") {
      $hp = 0;
      $ap = 0;
    }
    $hs += $hp;
    $as += $ap;
    $win = $away;
    // No need to test the == as the penalties have been included.
    if ($hs > $as) {
      $win = $home;
    }

    return $win;
  }

 /**************************************************
  * Calculate the standings so far. This works for
  * the group stages. Once the group stages are 
  * complete, the group standings transfer into the
  * knockout round.
  **************************************************/
  function TournamentCalculateStandings() {
    $wpts = "3";
    $dpts = "1";
  }

 /**************************************************
  * Show the standings for each of the groups.
  * This will also show the tree for the latter
  * stages.
  **************************************************/
  function ShowTournamentStandings() {
    global $Txt_Group_A, $Txt_Group_B, $Txt_Group_C, $Txt_Group_D, $Txt_Final;
    global $dbaseMatchData, $leagueID, $dbase, $Txt_Quarter_Finals, $Txt_Semi_Finals;
?>
<table class="STANDTB">
<colgroup>
<col width="260">
</colgroup>
<tr>
<td>
<?php TournamentShowGroupStandings('A', $Txt_Group_A); ?>    
</td>
<td>
<?php TournamentShowGroupStandings('B', $Txt_Group_B); ?>    
</td>
</tr>
<tr>
<td>
<?php TournamentShowGroupStandings('C', $Txt_Group_C); ?>    
</td>
<td>
<?php TournamentShowGroupStandings('D', $Txt_Group_D); ?>    
</td>
</tr>
</table>
<table>
  <colgroup>
    <col>
  </colgroup>
  <tr>
    <td class="TBLHEAD" colspan="6">
      <font class="TBLHEAD">
        <?php echo $Txt_Quarter_Finals; ?>
      </font>
    </td>
  </tr>
<?php
  $query = "SELECT * FROM $dbaseMatchData where lid='$leagueID' AND matchid>=25 and matchid<=28";
  $res = $dbase->query($query);
  while ($line = mysql_fetch_array($res)) {
    $hs = $line["homescore"];
    $as = $line["awayscore"];
?>
  <tr>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo convertDatetimeToScreenDate($line["matchdate"]); ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $line["hometeam"]; ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $hs; ?>
<?php if ($hs == $as and $hs != "") { ?>
        [<?php echo $line["homepen"]; ?>]
<?php }  else echo "&nbsp;"?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
         v
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $as; ?>
<?php if ($hs == $as and $hs != "") { ?>
        [<?php echo $line["awaypen"]; ?>]
<?php }  else echo "&nbsp;"?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $line["awayteam"]; ?>
      </font>
    </td>
  </tr>
<?php 
  }
 ?>
  <tr>
    <td class="TBLHEAD" colspan="6">
      <font class="TBLHEAD">
        <?php echo $Txt_Semi_Finals; ?>
      </font>
    </td>
  </tr>
<?php
  $query = "SELECT * FROM $dbaseMatchData where lid='$leagueID' AND matchid>=29 and matchid<=30";
  $res = $dbase->query($query);
  while ($line = mysql_fetch_array($res)) {
    $hs = $line["homescore"];
    $as = $line["awayscore"];
?>
  <tr>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo convertDatetimeToScreenDate($line["matchdate"]); ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $line["hometeam"]; ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $line["homescore"]; ?>
<?php if ($hs == $as and $hs != "") { ?>
        [<?php echo $line["homepen"]; ?>]
<?php }  else echo "&nbsp;"?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
         v
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $as; ?>
<?php if ($hs == $as and $hs != "") { ?>
        [<?php echo $line["awaypen"]; ?>]
<?php }  else echo "&nbsp;"?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $line["awayteam"]; ?>
      </font>
    </td>
  </tr>
<?php 
  }
 ?>
  <tr>
    <td class="TBLHEAD" colspan="6">
      <font class="TBLHEAD">
        <?php echo $Txt_Final; ?>
      </font>
    </td>
  </tr>
<?php
  $query = "SELECT * FROM $dbaseMatchData where lid='$leagueID' AND matchid=31";
  $res = $dbase->query($query);
  while ($line = mysql_fetch_array($res)) {
    $hs = $line["homescore"];
    $as = $line["awayscore"];
?>
  <tr>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo convertDatetimeToScreenDate($line["matchdate"]); ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $line["hometeam"]; ?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $line["homescore"]; ?>
<?php if ($hs == $as and $hs != "") { ?>
        [<?php echo $line["homepen"]; ?>]
<?php }  else echo "&nbsp;"?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
         v
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $as; ?>
<?php if ($hs == $as and $hs != "") { ?>
        [<?php echo $line["awaypen"]; ?>]
<?php }  else echo "&nbsp;"?>
      </font>
    </td>
    <td class="TBLROW">
      <font class="TBLROW">
        <?php echo $line["awayteam"]; ?>
      </font>
    </td>
  </tr>
<?php 
  }
 ?>
</table>
<?php
 }

 /**************************************************
  * Calculate the standings for each of the groups.
  **************************************************/
  function TournamentCalculateGroupStandings() {
    global $leagueID, $dbaseMatchData, $dbaseGroups, $dbase;

    // Loop through the results and create the 
    // standings for each team.

    // Create a standing for each team
    $standings = array();
    $query = "SELECT * FROM $dbaseGroups WHERE lid='$leagueID'";
    $res = $dbase->query($query);
    while ($line = mysql_fetch_array($res)) {
      $team = $line["team"];
      $grp = $line["grp"];

      $standings[$team] = new Standing($grp, $team);
    }

    $today = getTodaysDate();
    $query = "SELECT * FROM $dbaseMatchData AS a WHERE matchdate<'$today' AND a.lid='$leagueID' AND homescore IS NOT NULL and matchid>='1' and matchid<='24'";
    $res = $dbase->query($query);


    while ($line = mysql_fetch_array($res)) {
      $ht = $line["hometeam"];
      $at = $line["awayteam"];
      $hs = $line["homescore"];
      $as = $line["awayscore"];

      //echo "$ht $hs v $as $at<br>";
      $standings[$ht]->updateStats($hs, $as);
      $standings[$at]->updateStats($as, $hs);
    }

    // Now put the data into the dbase table.
    while (list($key,$val) = each($standings)) {
      $val->persist();
    }

    // Now calculate the teams for the later stages
    SetKnockoutTeams();
  }

  /******************************************************
   * Show the group standings ...
   ******************************************************/
  function TournamentShowGroupStandings($grp, $head) {
    global $leagueID, $dbase, $dbaseGroupStandings;
    global $P,$W,$D,$L,$F,$A,$PTS;

    $query = "SELECT * FROM $dbaseGroupStandings where lid='$leagueID' and grp='$grp' order by pts desc,(f-a) desc, f desc";
    $res = $dbase->query($query);
?>
      <table width="240">
      <tr>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $head; ?>
          </font>
        </td>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $P; ?>
          </font>
        </td>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $W; ?>
          </font>
        </td>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $D; ?>
          </font>
        </td>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $L; ?>
          </font>
        </td>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $F; ?>
          </font>
        </td>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $A; ?>
          </font>
        </td>
        <td class="TBLHEAD">
          <font class="TBLHEAD">
            <?php echo $PTS; ?>
          </font>
        </td>
      </tr>
<?php
    $count = 0;
    while ($line = mysql_fetch_array($res)) {
      if ($count++ < 2) {
        $style = "LEADER";
      } else {
        $style = "TBLROW";
      }
?>
      <tr>
        <td class="<?php echo $style; ?>">
          <font class="<?php echo $style; ?>">
            <?php echo $line["team"]; ?>
          </font>
        </td>
        <td class="<?php echo $style; ?>">
          <font class="<?php echo $style; ?>">
            <?php echo $line["pld"]; ?>
          </font>
        </td>
        <td class="<?php echo $style; ?>">
          <font class="<?php echo $style; ?>">
            <?php echo $line["w"]; ?>
          </font>
        </td>
        <td class="<?php echo $style; ?>">
          <font class="<?php echo $style; ?>">
            <?php echo $line["d"]; ?>
          </font>
        </td>
        <td class="<?php echo $style; ?>">
          <font class="<?php echo $style; ?>">
            <?php echo $line["l"]; ?>
          </font>
        </td>
        <td class="<?php echo $style; ?>">
          <font class="<?php echo $style; ?>">
            <?php echo $line["f"]; ?>
          </font>
        </td>
        <td class="<?php echo $style; ?>">
          <font class="<?php echo $style; ?>">
            <?php echo $line["a"]; ?>
          </font>
        </td>
        <td class="<?php echo $style; ?>">
          <font class="<?php echo $style; ?>"><b>
            <?php echo $line["pts"]; ?>
          </b></font>
        </td>
      </tr>
<?php
    }
?>
      </table>
<?php
  }

  /***************************************************************
   * Delete all the group entries.
   ***************************************************************/
  function DeleteAllGroups() {
    global $dbase, $dbaseGroups, $leagueID;

    $query = "delete from $dbaseGroups where lid='$leagueID'";
    $dbase->query($query);
  }
?>
