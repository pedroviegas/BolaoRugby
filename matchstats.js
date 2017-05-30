
/***********************************************************
 * Set the statistics for the match.
 ***********************************************************/
function setStats(matchid,matchdate,hometeam,awayteam,count,scores,scorescount,numpreds) {

  alert(matchid);

  // Now add the stats
  // Loop through the stats and create the rows.
  var oldstats = document.getElementById("STATSTAB");

  var statsdiv = document.getElementById("STATS");
  // Now create a table
  var table = document.createElement("table");
  table.setAttribute("id","STATSTAB");
  table.className = "PREDTB";

  var thead = document.createElement("thead");
  var row = document.createElement("tr");
  
  var cell = document.createElement("td");
  cell.className = "TBLHEAD";
  cell.colSpan = 2;
  cell.align = "center";
  
  var font = document.createElement("font");
  font.className = "TBLHEAD";
  var text = document.createTextNode("<?php echo $Prediction_Stats; ?>");
  font.appendChild(text);

  var br = document.createElement("br");
  font.appendChild(br);

  // Previous link
  var a = document.createElement("a");
  a.className = "PRED";
  a.setAttribute("target","MATCHSTATS");
  a.appendChild(text);
  font.appendChild(a);

  // Next link
  a = document.createElement("a");
  a.className = "PRED";
  a.setAttribute("target","MATCHSTATS");
  a.setAttribute('href',"getmatchstats.php?sid=<?php echo $SID; ?>&matchid="+matchid+"&matchdate="+matchdate+"&cmd=next");
  text = document.createTextNode("<?php echo " $Next"; ?>");
  a.appendChild(text);
  font.appendChild(a);

  br = document.createElement("br");
  font.appendChild(br);

  // Stats/fixture link
  a = document.createElement("a");
  a.className = "PRED";
  a.setAttribute('href',"showpredictionsformatch.php?sid=<?php echo $SID; ?>&matchid="+matchid+"&date="+matchdate);
  text = document.createTextNode(hometeam+" v "+awayteam);
  a.appendChild(text);
  font.appendChild(a);

  cell.appendChild(font);
  row.appendChild(cell);
  thead.appendChild(row);
  table.appendChild(thead);

  var tbody = document.createElement("tbody");
  tbody.setAttribute("id","STATS_ROWS");
  
  var predtext;
  var row1;
  var cell1;
  var cell2;
  var font1;
  var img;
  var pct = 0;
  for (i=0; i<count; i++) {
    predtext = document.createTextNode(scores[i]);
    cell1 = document.createElement("td");
    cell1.className = "TBLROW";

    font1 = document.createElement("font");
    font1.className = "TBLROW";
    font1.appendChild(predtext);

    cell1.appendChild(font1);
    row1 = document.createElement("tr");
    row1.appendChild(cell1);

    img = document.createElement("img");
    img.setAttribute("src","percentbar.gif");
    img.setAttribute("alt","Percentage");
    img.height= "10";
    pct = (scorescount[i])/numpreds;
    img.width = pct*40;
    pct = Math.floor(pct * 1000)/10;
    predtext = document.createTextNode(" "+pct+"% ["+scorescount[i]+"]");

    font1 = document.createElement("font");
    font1.className = "TBLROW";
    font1.appendChild(img);
    font1.appendChild(predtext);
    
    cell2 = document.createElement("td");
    cell2.className = "TBLROW";
    cell2.appendChild(font1);

    row1.appendChild(cell2);

    tbody.appendChild(row1);
  }
  table.appendChild(tbody);
  statsdiv.replaceChild(table,oldstats);
}
