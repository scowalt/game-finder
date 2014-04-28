<?php

/**************************************
* Represents the search page's results
* @param $n - name of game
* @param $l - link to game page
* @param $d - description of game
* @param $r - rank of game to query
**************************************/

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

?>