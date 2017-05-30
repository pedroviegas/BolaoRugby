<html>
<head>
<title>
Inserting test data
</title>
</head>
<body>
Inserting test data<br>
<?php

require "systemvars.php";
require "dbasedata.php";
require "configvalues.php";
require "security.php";

$link = OpenConnection();
$start = 5;
$end = 300;

for ($i=$start; $i<$end; $i++) {
  $mid = $i;
  $uname = "uname$i";
  $home = "home$i";
  $away = "away$i";
  $pwd = md5($uname);
  //$query = "replace into plmatchdata (matchid, matchdate, hometeam, awayteam, homescore, awayscore) values ($mid,'2004-01-23 15:00:00','$home','$away',1,1)";
  $query = "insert into pluserdata (userid, icon, username, usertype, password) values ($mid, 'default.gif','$uname', 1, '$pwd')";
  mysql_query($query) or die("$query<br>".mysql_error());
}

for ($i=$start; $i<$end; $i++) {
  for ($j=1; $j<25; $j++) {
    $score = $j%5;
    $query = "replace into plpredictiondata (userid, matchid, homescore,awayscore) values ($i, $j, $score, 1)";
    mysql_query($query) or die("$query<br>".mysql_error());
  }
}

CloseConnection($link);

?>
<br>
Finished inserting test data.
</body>
</html>
