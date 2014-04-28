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
    $args = func_get_args();
    if(isset($args[1])){
      $ret .= generateSearchContent($args[1]);
    } else {
      $ret .= generateSearchContent();
    }
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
  public $url = "http://gamefinder.web.engr.illinois.edu"; //will actually be link to game url at gamefinder
  public $description = "Totally the Best";
  
  public function __construct($n, $l, $d, $r){
    $this->name = $n;
    $this->url = $l;
    $this->description = $d;
    $this->rank = $r;
  }
}

/********************************************************************
* Returns a String of HTML to display search results
* Optional Argument arg[1] is used for deciding between testing mode
* and live site usage. This will be changed later to a non-optional
* parameter
********************************************************************/
function generateSearchContent() {
  /*
  * 1. receive top 50 results, possibly already as a collection of objects
  *    or else as a listof unique identifiers to query the db with.
  * 2. display query so user rememebers what they searched for
  * 3. generate a div or span element for each of the results
  */
  
  $ret = "";
  $results = array();
  
  //#1 
  $args = func_get_args();
  if(isset($args[1])){
    foreach($args[1] as $res) {
      $results[] = new Result($res->name, $res->url, $res->description, $res->rank);
    }
  } else {
    $results[0] = new Result("Fruit Ninja", "http://www.google.com", "cool", "8.7");
    $results[1] = new Result("Dynasty Warriors 3", "www.reddit.com", "awesome", "8.4");
    $results[2] = new Result("Dynasty Warriors 4", "http://www.microsoft.com", "fantastic", "8.3");
    $results[3] = new Result("Goat Simulator", "http://www.imgur.com", "amazing", "7.8");
    $results[4] = new Result("Gary's Mod", "http://www.yahoo.com", "mind-blowing", "7.5");
    $results[5] = new Result("Qubert!", "http://www.reddit.com/r/technology", "zomg", "7.3");
    $results[6] = new Result("Math Blaster", "http://www.reddit.com/r/science", "a must have", "7.2");
    $results[7] = new Result("Asteroids", "http://www.reddit.com/r/blogs", "an instant classic", "7.1");
    $results[8] = new Result("Frogger", "http://www.reddit.com/r/politics", "up down left right", "6.9");
    $results[9] = new Result("Amalur: The Reckoning", "http://www.bing.com", "an interesting story", "6.5");
    $results[10] = new Result("Halo", "http://www.gamespot.com", "unparalled frenzy of pixels", " 6.0");
    $results[11] = new Result("Starcraft", "http://www.blizzard.com", "click click click click click", "5.8");
    $results[12] = new Result("Age of Empires", "http://www.koalastothemax.com", "hover hover hover", "5.5");
    $results[13] = new Result("Bowling", "http://www.steampowered.com", "fascinating discovery", "1.4");
    $results[14] = new Result("Outdoor Hunting 27", "http://www.ask.com", "still alive: day 27", "0.5");
  }
  
  #2
  $ret .= "<div id=\"queryReminder\">\n<p>Search Query: " . getQuery() . "</p>\n</div>\n";
  
  #3
  $ret .= "<div id=\"contentColumn\">\n";
  foreach($results as $result) {
    $ret .= "<span id=\"".$result->name."_".$result->rank."\" class=\"result\" title=\"".$result->description."\">\n".
            "<a href=\"".$result->url."\">\n".
    	    "<p id=\"".$result->name."\">\n".
            $result->name.
            "</p>\n".
            "</a>\n".
            "<br/>\n".
            "<p id=\"".$result->rank."\">\n".
            "Rank: ".$result->rank.
            "</p>\n".
            "<br/>\n".
            "</span>\n";
  }
  $ret .= "</div>\n";
  
  
  return $ret;
}

/**********************************************************
* Grabs query terms from page's url and returns as an array
**********************************************************/
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