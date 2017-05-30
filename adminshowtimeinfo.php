<?php
/*********************************************************
 * Author: John Astill (c)
 * Date  : 20th March 2003
 * File  : adminshowtimeinfo.php
 *********************************************************/
require "systemvars.php";
require "dbasedata.php";
require "configvalues.php";
require "security.php";
require "sortfunctions.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Show Server and Client times</title>
<link rel="stylesheet" href="common.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function show(){
	var currtime=new Date();
	var hours=currtime.getHours();
	var minutes=currtime.getMinutes();
	var seconds=currtime.getSeconds();
	var sf="AM";
	var time="Unknown";

  if (hours>12){
    sf="PM";
    hours=hours-12;
  }

  if (hours==0)
    hours=12;
  if (hours<10)
	  hours="0"+hours;
  if (minutes<=9)
    minutes="0"+minutes;
  if (seconds<=9)
    seconds="0"+seconds;

  time = "Client Side Time "+hours+":"+minutes+":" +seconds+" "+sf+" ["+(currtime.getTimezoneOffset()/60)+"]";

  var old = document.getElementById("CLIENTTIME");
  var bodyRef = document.getElementById("CLIENT");
  var newInput = document.createElement("div");
  var bold = document.createElement("b");
  var newText = document.createTextNode(time);
  newInput.setAttribute("id","CLIENTTIME");
  bold.appendChild(newText);
  newInput.appendChild(bold);
  bodyRef.replaceChild(newInput,old);  
  setTimeout("show()",1000);
  return false;
}

function setServerDate(date, serveroffset){
  var currtime = new Date();
  var cltz = currtime.getTimezoneOffset() / 60;

  var old = document.getElementById("SERVERTIME");
  var bodyRef = document.getElementById("SERVER");
  var newInput = document.createElement("div");
  var bold = document.createElement("b");
  var newText = document.createTextNode("Server Side Time "+date);
  newInput.setAttribute("id","SERVERTIME");
  bold.appendChild(newText);
  newInput.appendChild(bold);
  bodyRef.replaceChild(newInput,old);  
  

  setTimeout("document.frames[0].location.reload()",1000);
  return false;
}

function setGameDate(date, serveroffset){
  var currtime = new Date();
  var cltz = currtime.getTimezoneOffset() / 60;

  var old = document.getElementById("GAMETIME");
  var bodyRef = document.getElementById("GAME");
  var newInput = document.createElement("div");
  var bold = document.createElement("b");
  var newText = document.createTextNode("Game Time "+date);
  newInput.setAttribute("id","GAMETIME");
  bold.appendChild(newText);
  newInput.appendChild(bold);
  bodyRef.replaceChild(newInput,old);  
  
  // Wait one minute
  setTimeout("document.frames[0].location.reload()",60000);
  return false;
}
show()
//-->
</SCRIPT>
</head>
<body class="MAIN" onload='javascript:show();'>

  <table class="MAINTB">
    <tr>
      <!-- Left column -->
      <td class="TBLHEAD" colspan="2" align="CENTER">
        <font class="TBLHEAD">
        Check the time on the server [LeagueID <?php echo $leagueID; ?>].
        </font>
      </td>
    </tr>
    <tr>
      <!-- Left column -->
      <td class="LEFTCOL">
        <!-- Menu -->
        <?php require "adminsetupmenu.php"?>
        <!-- End Menu -->
      </td>
      <!-- Central Column -->
      <td align="LEFT" class="CENTERCOL">
        <font class="TBLROW">
        <!-- Central Column -->
All the times displayed and entered in the script work on Server time. 
<p>
Thus if your server is in Eastern USA and you are in Britain you would have a offset of -5. The users would see this offset, but everything on the server would be working in the local time.<br>
If your client and server are in the same time zone, then the following times should match. The client timezone may be missing a '-' sign, be careful.<p>

<iframe id="SERVERFRAME" name="SERVERFRAME" style="width:0px; height:0px;border:0px" src="getservertime.php"></iframe>
<div id="CLIENT" name="CLIENT">
<div id="CLIENTTIME" name="CLIENTTIME">
</div>
</div>

<div id="SERVER" name="SERVER">
<div id="SERVERTIME" name="SERVERTIME">
</div>
</div>

<iframe id="GAMEFRAME" name="GAMEFRAME" style="width:0px; height:0px;border:0px" src="getgametime.php"></iframe>
<div id="GAME" name="GAME">
<div id="GAMETIME" name="GAMETIME">
</div>
</div>

<p>
Using the time displayed for the client and server you can determine the timezone offset you need to set. Be careful when using the timezones (values in []) as javascript has times behind UTC as positive and ahead as negative. The server side time is shown the other way around. Make sure that you test the timezone offset after setting it. Create a fixture a few minutes in the future. Make a prediction before the game time. Wait until after kickoff and try to change your prediction. It should be disabled.
The offset is required when setting the game parameters.
</font>
      </td>
      <!-- Right Column -->
      <td class="RIGHTCOL" align="RIGHT">
      </td>
    </tr>
  </table>


</body>
</html>
