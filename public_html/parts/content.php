<?php
/*********************************************
* Given a page's name, return the appropriate
* content body for the page i.e. list of games
*********************************************/
function content($type) {
  //setup edge bars
  $ret = "<section>\n".
	 "<div id=\"leftColumn\" >\n".
	 "</div>\n".
	 "<div id=\"rightColumn\" >\n".
	 "</div>\n";
  
  //generate content div based on page
  if($type === "index") {
    $ret .= "<div id=\"contentColumn\" >\n".
    	    "\n".
            "</div>\n";
  }elseif($type === "search"){
    $ret .= generateSearchContent();
  }else {
    $ret .= "<div id=\"contentColumn\" >".
    	    "".
            "</div>\n";
  }
  
  //finish the section 
  $ret .= "</section>\n";  
  return $ret;
}

class Result {
  public $name = "Title Title";
  public $rank = "2.3";
  
  public function __construct($n, $r){
    $this->name = $n;
    $this->rank = $r;
  }
}

//UNDER DEVELOPMENT
function generateSearchContent() {
  /*
  * 1. receive top 50 results, possibly already as a collection of objects
  *    or else as a listof unique identifiers to query the db with.
  * 2. display query so user rememebers what they searched for
  * 3. generate a div or span element for each of the results
  */
  //#1 
  $ret = "";
  $results = array();
  $results[0] = new Result("Fruit Ninja","8.7");
  $results[1] = new Result("Dynasty Warriors 3","8.4");
  $results[2] = new Result("Dynasty Warriors 4","8.3");
  $results[3] = new Result("Goat Simulator","7.8");
  $results[4] = new Result("Gary's Mod","7.5");
  $results[5] = new Result("Qubert!","7.3");
  $results[6] = new Result("Math Blaster","7.2");
  $results[7] = new Result("Asteroids","7.1");
  $results[8] = new Result("Frogger","6.9");
  $results[9] = new Result("Amalur: The Reckoning","6.5");
  $results[10] = new Result("Halo"," 6.0");
  $results[11] = new Result("Starcraft","5.8");
  $results[12] = new Result("Age of Empires","5.5");
  $results[13] = new Result("Bowling","1.4");
  $results[14] = new Result("Outdoor Hunting 27","0.5");
  
  #2
  $ret .= "<div id=\"queryReminder\">\n<p>Search Query: " . getQuery() . "</p>\n</div>\n";
  
  #3
  $ret .= "<div id=\"contentColumn\">\n";
  foreach($results as $result) {
    $ret .= "<span id=\"".$result->name."_".$result->rank."\" class=\"result\">\n".
    	    "<p id=\"".$result->name."\">\n".
            $result->name.
            "</p>\n".
            "<br/>".
            "<p id=\"".$result->rank."\">\n".
            $result->rank.
            "</p>\n".
            "<br/>".
            "</span>\n";
  }
  $ret .= "</div>";
  
  
  return $ret;
}

/*
* 
*/
function getQuery() {
  //get page url
  $url = curPageURL();
  
  //drop first half of url before the GET parameters
  //and save only the parameters
  $parts = explode('=', $url);
  array_shift($parts);
  $temp = implode($parts);
  $parts = explode("+", $temp);
  
  //re-join all the query words
  $queryString = "";
  foreach($parts as $part) {
    $queryString .= $part . " ";
  }
  
  return $queryString;
}

/********************************************************************
* obtained from http://webcheatsheet.com/php/get_current_page_url.php
********************************************************************/
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}


function underScore($text) {
  $text = str_replace(' ', '_', $text);
  $text = preg_replace('/[^A-Za-z0-9\-]/', '_', $text);
  return $text;
}