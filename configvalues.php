<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 20th March 2003
 * File  : configvalues.php
 *********************************************************/
  $query = "select * from $dbaseConfigData where lid='$leagueID'";
  $result = $dbase->query($query);
  if ($result != FALSE) {
    $arr = array();
    while ($line = mysql_fetch_array($result)) {
      $param = $line["param"];
      $val = $line["value"];
      $arr["$param"] = $val;
    }

    // There is a tight cohesion between the coding and the contents of the config
    // database.
    $hideNoPredictions = $arr["HIDE_0_PREDS"];
    $useMessaging = $arr["USE_MESSAGING"];
    $corrResult = $arr["CORR_RESULT"];
    $corrHomeScore = $arr["CORR_HOME_SCORE"];
    $corrAwayScore = $arr["CORR_AWAY_SCORE"];
    $corrOneScore = $arr["CORR_ONE_SCORE"];
    $corrScore = $arr["CORR_SCORE"];
    $incorrResult = $arr["INCORR_RESULT"];
    $PredictionLeagueTitle = $arr["PRED_LEAGUE_TITLE"];
    $defaulticon = $arr["DEF_ICON"];
    $homePage = $arr["HOME_PAGE_URL"];
    $homePageTitle = $arr["HOME_PAGE_TITLE"];
    $allowMultipleUserPerEmail = $arr["MULT_USERS"];
    $timezoneOffset = $arr["TZ_OFFSET"];
    $reverseUserPredictions = $arr["REV_USER_PREDS"];
    $usersPerPage = $arr["USERS_PER_PAGE"];
    if ($usersPerPage < 1) {
      $usersPerPage = 10;
    }
    $maxAdminUsers = $arr["MAX_ADMIN_USERS"];
    //$isauto = $arr["ISAUTO"];
    $NumMultFixts = $arr["NUMMULTFIXTS"];
    $ViewUserPredictions = $arr["VIEWUSERPREDS"];
    $Use24Hr = $arr["USE24HR"];
    $UploadIcons = $arr["UPLOADICONS"];
    $MaxIconFileSize = $arr["ICONSIZE"];
    $PasswordEncryption = $arr["PASSWORDENCRYPT"];
    $ShowDivisions = $arr["SHOWDIVISIONS"];
    $ShowWeeklyStats = $arr["SHOWWEEKLY"];
    $LockedGame = $arr["LOCKEDGAME"];
    $ViewFutureStats = $arr["VIEWFUTURE"];
    $UserDeleteAccount = $arr["USERDELACC"];
    $EnableShoutbox = $arr["SHOUTBOX"];
    $monthlyWinner = $arr["MWPOINTS"];
    $ShowIconInStandings = $arr["SHOWICON"];
    $NumMonthlyWinners = $arr["NUMMONTHLYWIN"];
  }
?>
