<?php

require_once("../vendor/autoload.php");
require_once("./StanfordNLP/Base.php");
require_once("./StanfordNLP/StanfordTagger.php");
require_once("./StanfordNLP/POSTagger.php");
require_once("../../config.php");

$remove_tags = array("DT", "TO", "IN", "PRP", "CC", 'PRP$', '.', ',', ':', '-LRB-', '-RRB-', 'WP');

set_time_limit(5*60); // max execution time of a line is 5 minutes

// connect to the database
$link = mysql_connect('engr-cpanel-mysql.engr.illinois.edu', $SQL_USER, $SQL_PASS);
if (!$link) {
    die('Not connected : ' . mysql_error());
}
mysql_select_db('gamefinder_db', $link);

// select all of the reviews that haven't been parsed yet
$query = "SELECT Id,Content from Reviews WHERE Parsed_content IS NULL AND Parsed_length IS NULL ORDER BY RAND()";
$unparsed_reviews = mysql_query($query)  or die($query. "<br/><br/>".mysql_error());;
while(($row = mysql_fetch_row($unparsed_reviews)) != null) {	
	$id = $row[0];
	$string = $row[1];
	$time = time(); // start time of execution

	// load tagging library
	$pos = new \StanfordNLP\POSTagger(
	  '/home/gamefinder/stanford-postagger-2014-01-04/models/english-left3words-distsim.tagger',
	  '/home/gamefinder/stanford-postagger-2014-01-04/stanford-postagger.jar'
	);

	$tagged = array();
	$parsed_content = "";
	$parsed_length = 0;
	while(strlen($string) !== 0){
		// this tags the first sentence in $string
		$sentence_tagged = $pos->tag(explode(' ', $string)); 

		// if this review has taken more than two mintes to parse
		if (time() > ($time + 2*60)){
			echo "Review taking too long <br/>";
			break; // stop parsing sentences
		}

		// for every tagged word in the sentence
		$count = count($sentence_tagged);
		for($i = 0; $i < $count; $i++){
			$pair = $sentence_tagged[$i];
			$word = $pair[0];
			$tag = $pair[1];

			// remove the tagged word from the base string
			$string = substr($string, stripos($string, $word) + strlen($word));

			if (in_array($tag, $remove_tags)){
				// we don't care about this word
				unset($sentence_tagged[$i]); // remove word from array
			} else {
				$parsed_content = $parsed_content . Porter::Stem($word) . " ";
				$parsed_length ++;
			}
		}
		
		// add remaining sentence words to overall array
		$tagged = array_merge($tagged, $sentence_tagged);
	}

	// if this review has taken more than two mintes to parse
	if (time() > ($time + 2*60)){
		echo "Review taking too long <br/>";
		continue; //skip current review
	}

	// stemmed word frequency counting
	$word_freq = array();
	foreach($tagged as $pair){
		$word = $pair[0];
		$tag = $pair[1];
		$word_freq[Porter::Stem($word)] += 1;
	}

	// put all of the stemmed word frequencies into the database
	foreach($word_freq as $word => $count){
		// add word to database
		$word = mysql_real_escape_string($word);
		$query = "INSERT IGNORE INTO Words (word) VALUES (\"$word\")";
		$result = mysql_query($query)  or die($query. "<br/><br/>".mysql_error());;

		// add frequency
		$query = "INSERT IGNORE INTO Reviews_have_Words (Id, word, count) VALUES ($id, \"$word\", $count)";
		$result = mysql_query($query)  or die($query. "<br/><br/>".mysql_error());;
	}

	// put the Parsed_content and Parsed_length into the database
	$parsed_content = mysql_real_escape_string($parsed_content);
	$query = "UPDATE Reviews SET Parsed_length = $parsed_length, Parsed_content = \"$parsed_content\" WHERE Id = $id";
	$result = mysql_query($query)  or die($query. "<br/><br/>".mysql_error());;

	echo "Done with $id <br/>";
}

?>