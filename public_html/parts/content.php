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
  * 2. generate a div or span element for each of the results
  * 3. determine screen size and then use that to determine each div/span's
  *    size and the quantity per row of search results
  * 4. attach javascript to alter the css of these results whenever the 
  *    content div is resized into specific categories. 
  *    i.e. 640 <a> 960 <b> 1280 <c> 1920
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
  $count = 0;
  $ret .= "<div>";
  foreach($results as $result) {
    if(!($count%4)) {
      $ret .= "</div>".
              "<div>".
              "".
              "";
    }
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
    if(!($count%4)) {
      $ret .= "</div>".
              "".
              "".
              "";
    }
    $count++;
  }
  $ret .= "</div>";
  return $ret;
}




function underScore($text) {
  $text = str_replace(' ', '_', $text);
  $text = preg_replace('/[^A-Za-z0-9\-]/', '_', $text);
  return $text;
}