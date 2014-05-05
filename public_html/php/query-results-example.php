<?php

require_once("../vendor/autoload.php");
require_once("../../config.php");

$search = "franchise shooter";
$query_words = explode(" ", $search);

// connect to the database
$link = mysql_connect('engr-cpanel-mysql.engr.illinois.edu', $SQL_USER, $SQL_PASS);
if (!$link) {
    die('Not connected : ' . mysql_error());
}
mysql_select_db('gamefinder_db', $link);

// collect the query frequency of each word
$search = $search + " ";
$qfs = array();
foreach($query_words as $word){
	$qfs[Porter::Stem($word)] ++;
}

// stem all of the words in the query
for($i = 0; $i < count($query_words); $i++){
	$query_words[$i] = Porter::Stem($query_words[$i]);
}

// remove duplicate words
$query_words = array_unique($query_words);

// collect the document frequency of each word
$dfs = array();
foreach($query_words as $word){
	mysql_select_db('gamefinder_db', $link);
	$escaped_word = mysql_real_escape_string($word);
	$query = "SELECT freq FROM DocumentFrequency WHERE word = \"$escaped_word\"";
	$result = mysql_query($query)  or die($query. "<br/><br/>".mysql_error());;
	while(($row = mysql_fetch_row($result)) != null) {
		$dfs[$word] = $row[0];
	}
}

// collect all of the reviews that contain query terms (with term frequencies and doc lengths)
$reviews = array();
foreach($query_words as $word){
	$escaped_word = mysql_real_escape_string($word);
	$query = "SELECT Id,word,count,Parsed_length FROM Reviews_have_Words NATURAL JOIN Reviews WHERE word = \"$escaped_word\"";
	$result = mysql_query($query)  or die($query. "<br/><br/>".mysql_error());;
	while(($row = mysql_fetch_row($result)) != null) {
		$id = $row[0];
		$count = $row[2];
		$reviews[$id]["id"] = $id; // this is redundant, but this information is needed later for sorting
		$reviews[$id]["term-freq"][$word] = $count;
		$reviews[$id]["length"] = $row[3];
	}
}
var_dump($reviews);

// get the number of documents and avg doc length
$query = "SELECT COUNT(*) AS count, AVG(Parsed_length) AS avg FROM Reviews GROUP BY Id";
$result = mysql_query($query)  or die($query. "<br/><br/>".mysql_error());;
$docN = null;
$docLengthAvg = null;
while(($row = mysql_fetch_row($result)) != null) {
	$docN = $row[0];
	$docLengthAvg = $row[1];
}

// calculate the BM25 score for each review
$k1 = 1;
$k3 = 1000;
$kB = 0.3;
foreach($reviews as $review_id => $review){
	$docLength = $review["length"];
	foreach($review["term-freq"] as $word => $tf){
		$df = $dfs[$word];
		$qf = $qfs[$word];
		$idf = log(($docN-$df+0.5)/($df+0.5));
		$weight = (($k1+1.0)*$tf) / ($k1*(1.0-$kB+$kB*$docLength/$docLengthAvg)+$tf);
		$tWeight = (($k3+1)*$qf) / ($k3+$qf);
		$reviews[$review_id]["score"] += $idf * $weight * $tWeight;
	}
}

// sort the reviews by score
usort($reviews, "compare");
function compare($r1, $r2){
	return $r2["score"] - $r1["score"];
}

var_dump($reviews);

?>