<?php

require_once("../vendor/autoload.php");
require_once("./StanfordNLP/Base.php");
require_once("./StanfordNLP/StanfordTagger.php");
require_once("./StanfordNLP/POSTagger.php");

$review = "The first few minutes of Call of Duty: Ghosts don't paint a terribly accurate portrait of what lies ahead. You barely have time to take stock of the idyllic Southern California setting before fire begins raining down from the heavens, destroying every car and home in sight as a shouty man commands you to follow him to safety. But whereas Infinity Ward's recent work on the Modern Warfare series was weighed down by bewildering plot twists and an affinity for restricting its most exciting moments to noninteractive set dressing, Ghosts tells a lean, straightforward story that throws you into plenty of spectacular situations, but with more breathing room to appreciate the action. Along with the outstanding new Extinction co-op mode and an abundance of clever refinements to competitive multiplayer, Call of Duty: Ghosts is a refreshing and thoroughly satisfying entry in the blockbuster shooter franchise.";

$pos = new \StanfordNLP\POSTagger(
  '/home/gamefinder/stanford-postagger-2014-01-04/models/english-left3words-distsim.tagger',
  '/home/gamefinder/stanford-postagger-2014-01-04/stanford-postagger.jar'
);

$result = $pos->tag(explode(' ', $review)); 
var_dump($result);


?>