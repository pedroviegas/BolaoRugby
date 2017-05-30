/*********************************************************
 * Author: John Astill (c)
 * Date  : 18th March 2004
 * File  : fixtures.js
 ********************************************************/

 /****************************************************************
  * Simple function to replace the contents a the form values.
  * Change the page content to show the modify form.
  * <a href="javascript:void modifyfixture('$date','$time','$hometeam','$awayteam');">Modify</a>
  ****************************************************************/
 function addfixture() {
    var old = document.getElementById("addform");
    old.setAttribute("action","modifyfixture.php?sid=<?php echo $SID?>");
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
 function modifyfixture(matchid,serverdate,date,time,home,away,bonus, sid) {
   document.AddFixture.DATE.value = date;
   document.AddFixture.TIME.value = time;
   document.AddFixture.HOMETEAM.value = home;
   document.AddFixture.AWAYTEAM.value = away;
   document.AddFixture.BONUS.value = bonus;

    // DOM stuff for updating the date
    // Get a reference to the parent
    var old = document.getElementById("addform");
    old.setAttribute("action","index.php?sid="+sid+"&cmd=modify");

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
