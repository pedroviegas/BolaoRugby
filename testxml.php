<?php
/*********************************
 * Author : John Astill (c)
 * Date   : 1st July 2004
 * Testing a live XML feed of odds
 *********************************/
$file = "football.xml";

$tagname = "";
$show = FALSE;
$inmarket = FALSE;
$intext = FALSE;
$value = "";
$storendt = FALSE;
$fixtvalidated = FALSE;
$ndtvals = array();

$fixt = "Manchester United vs Arsenal";

function newevent() {
  global $storendt;

  $storendt = TRUE;
}

function storendtvals($tag) {
  global $ndtvals, $value, $storendt;

  if ($storendt == TRUE) {
    $ndtvals[$tag] = $value;
  }
}

function endevent() {
  global $show, $fixtvalidated;

  $show = FALSE;
  $fixtvalidated = FALSE;
}

function endelm() {
  global $intext, $show, $tagname, $value;

  if ($intext == TRUE) {
    $intext = FALSE;

    if ($show == TRUE) {
     echo "[$tagname] $value<br>\n";
    }
  
    $value = "";
  }
  
}

function endmarket() {
}

function validfixture() {
  global $fixt, $ndtvals;
  $match = trim($ndtvals["NAME"]);
  if (strstr($fixt,$match)) {
    return TRUE;
  }
  return FALSE;
}

function newmarket() {
  global $show,$storendt, $ndtvals, $fixtvalidated;

  //if ($fixtvalidated == TRUE && validfixture() == FALSE) {
  if (validfixture() == FALSE) {
    $fixtvalidated = TRUE;
    return;
  }
  
  $show = TRUE;
  $storendt = FALSE;

  // Display NDT
  $match = $ndtvals["NAME"];
  $date = $ndtvals["DATE"];
  $time = $ndtvals["TIME"];
  echo "Fixture: $match [$date $time]<br>\n";
}

function startElement($parser, $name, $attrs) {
    global $tagname;

    //print "$name<br>\n";
    $tagname = $name;

    if (strstr($name,"EVENT")) {
      newevent();
    }

    if (strstr($name,"MARKET")) {
      newmarket();
    }
}

function endElement($parser, $name) {


    storendtvals($name);

    if (strstr($name,"MARKET")) {
      endmarket();
    }

    if (strstr($name,"EVENT")) {
      endevent();
    }

    endelm();
}

function data($parser, $data) {
  global $intext, $value;
  
  $intext = TRUE;
  $value .= $data;
}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "data");
if (!($fp = fopen($file, "r"))) {
    die("could not open XML input");
}

while ($data = fread($fp, 4096)) {
    if (!xml_parse($xml_parser, $data, feof($fp))) {
        die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
    }
}
xml_parser_free($xml_parser);
?>
