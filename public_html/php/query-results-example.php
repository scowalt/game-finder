<?php

require_once("../vendor/autoload.php");
require_once("../../config.php");

$search = "franchise shooter";

// connect to the database
$link = mysql_connect('engr-cpanel-mysql.engr.illinois.edu', $SQL_USER, $SQL_PASS);
if (!$link) {
    die('Not connected : ' . mysql_error());
}
mysql_select_db('gamefinder_db', $link);

// first, stem all of the words in the query
$query_words = explode(" ", $search);
for($i = 0; $i < count($query_words); $i++){
	$query_words[$i] = Porter::Stem($query_words[$i]);
}

// next, collect all of the reviews that contain query terms
$reviews = array();
foreach($query_words as $word){
	
}

?>