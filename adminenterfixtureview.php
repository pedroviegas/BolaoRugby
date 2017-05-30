<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 17th March 2004
 * File  : adminenterfixtureview.php
 * This page allows an administrator to add
 * extra fixtures to the prediction league.
 ********************************************************/
  global $SID, $Txt_Delete_Fixtures, $User, $Txt_HHMMSS, $Txt_24hr, $Txt_YYYYMMDD;
?>
<script type="text/javascript" language="javascript"> 

 /****************************************************************
  * Double check that the user really wants to do what they are 
  * doing.
  ****************************************************************/
 function really(one, two) {
   if (true == confirm(one))
     return confirm(two);
   return false;
 }

 /****************************************************************
  * Simple function to replace the contents a the form values.
  * Change the page content to show the modify form.
  * <a href="javascript:void modifyfixture('$date','$time','$hometeam','$awayteam');">Modify</a>
  ****************************************************************/
 function addfixture() {
    var old = document.getElementById("addform");
    old.setAttribute("action","index.php?cmd=add&sid=<?php echo $SID?>");
    old.setAttribute("method","POST");

    // Get a reference to the parent
    var bodyRef = document.getElementById("formdate");
    var newInput = document.createElement("div");
    var newText = document.createTextNode('');
  // Get a reference to the block being replaced
    old = document.getElementById("formdatetext");
    newInput.setAttribute("id","formdatetext");
    newInput.appendChild(newText);
    // Replace the piece
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formtime");
    newInput = document.createElement("div");
    newText = document.createTextNode('');
    old = document.getElementById("formtimetext");
    newInput.setAttribute("id","formtimetext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formhome");
    newInput = document.createElement("div");
    newText = document.createTextNode('');
    old = document.getElementById("formhometext");
    newInput.setAttribute("id","formhometext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formaway");
    newInput = document.createElement("div");
    newText = document.createTextNode('');
    old = document.getElementById("formawaytext");
    newInput.setAttribute("id","formawaytext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formbonus");
    newInput = document.createElement("div");
    newText = document.createTextNode('');
    old = document.getElementById("formbonustext");
    newInput.setAttribute("id","formbonustext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formsubmit");
    //newInput = document.createElement("<input value='Add' name='Add'>");
    newInput = document.createElement("input");
    old = document.getElementById("formsubmitinput");
    newInput.setAttribute("id","formsubmitinput");
    newInput.setAttribute("name","Add");
    newInput.setAttribute("value","Add");
    newInput.setAttribute("type","submit");
    bodyRef.replaceChild(newInput,old);
 }

 // Change the display to show the fixture to be modified.
 function modifyfixture(matchid,serverdate,week,date,time,home,away,bonus, sid) {
   document.AddFixture.WEEK.value = week;
   document.AddFixture.DATE.value = date;
   document.AddFixture.TIME.value = time;
   document.AddFixture.HOMETEAM.value = home;
   document.AddFixture.AWAYTEAM.value = away;
   document.AddFixture.BONUS.value = bonus;

    // DOM stuff for updating the date
    // Get a reference to the parent
    var old = document.getElementById("addform");
    old.setAttribute("action","index.php?sid="+sid+"&cmd=modify");

    old = document.getElementById("OLDWEEK");
    old.setAttribute("value",week);

    old = document.getElementById("OLDDATE");
    old.setAttribute("value",serverdate);

    old = document.getElementById("OLDHOME");
    old.setAttribute("value",home);

    old = document.getElementById("OLDAWAY");
    old.setAttribute("value",away);

    old = document.getElementById("OLDBONUS");
    old.setAttribute("value",bonus);

    old = document.getElementById("MATCHID");
    old.setAttribute("value",matchid);

    var bodyRef = document.getElementById("formdate");
    var newInput = document.createElement("div");
    var newText = document.createTextNode(date);
    old = document.getElementById("formdatetext");
    newInput.setAttribute("id","formdatetext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formweek");
    newInput = document.createElement("div");
    newText = document.createTextNode(week);
    old = document.getElementById("formweektext");
    newInput.setAttribute("id","formweektext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formtime");
    newInput = document.createElement("div");
    newText = document.createTextNode(time);
    old = document.getElementById("formtimetext");
    newInput.setAttribute("id","formtimetext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formhome");
    newInput = document.createElement("div");
    newText = document.createTextNode(home);
    old = document.getElementById("formhometext");
    newInput.setAttribute("id","formhometext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formaway");
    newInput = document.createElement("div");
    newText = document.createTextNode(away);
    old = document.getElementById("formawaytext");
    newInput.setAttribute("id","formawaytext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    bodyRef = document.getElementById("formbonus");
    newInput = document.createElement("div");
    newText = document.createTextNode(bonus);
    old = document.getElementById("formbonustext");
    newInput.setAttribute("id","formbonustext");
    newInput.appendChild(newText);
    bodyRef.replaceChild(newInput,old);

    // Change the button text.
    bodyRef = document.getElementById("formsubmit");
    //newInput = document.createElement("<input name='Modify' value='Modify'>");
    newInput = document.createElement("input");
    old = document.getElementById("formsubmitinput");
    newInput.setAttribute("id","formsubmitinput");
    newInput.setAttribute("name","modify");
    newInput.setAttribute("value","Modify");
    newInput.setAttribute("type","submit");
    bodyRef.replaceChild(newInput,old);

 }
</script>
<table>
<tr>
<td id="forms" valign="top">
  <form id="addform" name="AddFixture" method="POST" action="index.php?sid=<?php echo $SID; ?>&cmd=add">
  <table class="STANDTB">
  <tr>
  <td colspan="7" align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  Fixture Administration
  </font>
  </td>
  </tr>

  <tr>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  Week
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  Date<br><small><?php echo $Txt_YYYYMMDD;?></small>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  Time <small><?php echo $Txt_24hr; ?></small><br><small><?php echo $Txt_HHMMSS;?></small>
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  Home Team
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  Away Team
  </font>
  </td>
  <td align="CENTER" valign="TOP" class="TBLHEAD">
  <font class="TBLHEAD">
  Bonus Points
  </font>
  </td>
  <td align="CENTER" class="TBLHEAD">
  <font class="TBLHEAD">
  <input id="formresetinput" onclick="addfixture()" type="reset" name="RESET" value="Reset">
  </font>
  </td>
  </tr>
  <!-- Content Row -->
  <tr>
  <td class="TBLROW" align="CENTER">
  <font id="formweek" class="TBLROW">
  <div id="formweektext"></div>
  <input type="text" name="WEEK" size="2">
  <input id="OLDWEEK" type="hidden" name="OLDWEEK" value="">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formdate" class="TBLROW">
  <div id="formdatetext"></div>
  <input type="text" name="DATE" size="10">
  <input id="OLDDATE" type="hidden" name="OLDDATE" value="">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formtime" class="TBLROW">
  <div id="formtimetext"></div>
  <input type="text" name="TIME" size="8">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formhome" class="TBLROW">
  <div id="formhometext"></div>
  <input type="text" name="HOMETEAM" size="15">
  <input id="MATCHID" type="hidden" name="MATCHID" value="">
  <input id="OLDHOME" type="hidden" name="OLDHOME" value="">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formaway" class="TBLROW">
  <div id="formawaytext"></div>
  <input type="text" name="AWAYTEAM" size="15">
  <input id="OLDAWAY" type="hidden" name="OLDAWAY" value="">
  </font>
  </td>
  <td class="TBLROW" align="CENTER">
  <font id="formbonus" class="TBLROW">
  <div id="formbonustext"></div>
  <input type="text" name="BONUS" size="2">
  <input id="OLDBONUS" type="hidden" name="OLDBONUS" value="">
  </font>
  </td>
  <td id="formsubmit" align="center" class="TBLROW">
  <input id="formsubmitinput" type="submit" name="ADD" value="Add">
  </td>
  </tr>
  </table>
  </form>
  <?php GetCurrentFixtures(true); ?>
  <form method="POST" action="index.php?cmd=deleteall&sid=<?php echo $SID; ?>">
  <table class="STANDTB">
  <tr>
  <td class="TBLROW" align="CENTER">
  <font class="TBLROW">
  <input type="HIDDEN" name="USER" value="<?php echo $User->getUsername();?>">
  <input id="deleteall" type="submit" onClick="return really('<?php echo $Txt_Delete_Fixtures;?>','<?php echo $Txt_Delete_Fixtures;?>');" name="DELETEALL" value="Delete All">
  </font>
  </td>
  </tr>
  </table>
  </form>
</td>
</tr>
</table>
