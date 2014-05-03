<?php

require_once("../vendor/autoload.php");
require_once("./StanfordNLP/Base.php");
require_once("./StanfordNLP/StanfordTagger.php");
require_once("./StanfordNLP/POSTagger.php");

$string = "The Amazing Spider-Man 2 game feels like what would happen if you found a cursed monkey's paw and wished for the best web-slinging experience ever. For all the fun you can have swinging around New York City, it’s canceled out by boring Peter Parker segments, frustratingly dull combat, and annoyingly persistent glitches. Meanwhile, plot is tossed aside in favor of churning out an unsatisfying parade of supervillains.
ADVERTISEMENT
Traversal is much-improved from 2012’s Amazing Spider-Man game. Your web lines have to actually attach to a nearby building or structure, so you're encouraged to swing close to the ground where taxis and other vehicles honk as you narrowly miss them. It’s much more fun than flying high above them on webs apparently attached to clouds. Having the left and right triggers (or mouse buttons) mapped to swinging with their respective arms is a nice addition, too; the ability to alternate adds a tad more authenticity to Spidey's wild and fast swinging, as does cutting around a corner by using the inside arm. The first time I made a crazy dive from a skyscraper and attached a web to a building right before hitting the ground, I could finally relate to the common scene that’s played out in just about every Spider-Man movie over the last dozen years. I never wanted to stop moving in The Amazing Spider-Man 2.
Adding to that feeling is that things aren't much fun once you slow down. Like the last movie tie-in, The Amazing Spider-Man 2 attempts to be Arkham-lite when it comes to combat, and fails due to its repetitive nature. While Spidey's range for stealth attacks has been increased, that benefit is negated by the guesswork involved in discovering said range, since there is no indicator like in The Amazing Spider-Man.
When you're inevitably discovered, there's usually not much to winning fights: just button-mash the attack button until your Spidey-sense tingles, then dodge or counter. If you need to slow an enemy down, spam them with webbing. It lacks the options that make Batman’s combat interesting.
The sub-par enemy AI comes to light whenever you Web Dash just a few feet away from a thug. That’ll often confound lesser foes, even the ones that accompany bosses. When enemies aren't easily confused, they're straight-up broken; you can expect multiple occurrences of thugs walking into walls or bosses that glitch into game-breaking states. This either renders them completely open to attack or indefinitely invincible, requiring a restart.
Missions in The Amazing Spider-Man 2 are significantly shorter than its predecessor’s, but the running time is roughly the same seven hours due to undesirable padding. The combat lead-up to a boss battle is actually pretty short – more often than not, you have to clear a single (albeit large) room of enemies before progressing to the supervillan. It takes about 15 minutes to beat one, but you’ll have spent another 15 during that mission slowly walking around taking photos and searching for items as Peter Parker. The biggest affront in this added time is the “interactive” dialogue sequences. These are consequence-free choices that just determine the order in which questions are asked.
Not that it really matters. Where the movies at least make an effort to give you nuanced introductions and unique team-ups, this bland plot throws just about every single one of the seven supervillans into the fray individually and without fanfare, particularly at the end. It certainly doesn’t help that the script is very schizophrenic, jumping from attempted melodrama to hackneyed gags a matter of seconds. You can unlock more backstory via side-missions, but they're rarely worth the effort. Nevertheless, you will have to tackle them on occasion, since there are a few instances where the only requirement to progress in a mission is “explore the city.” The only good ones are the Russian mob hideouts, which are always just the right length and unlock cool new costumes.
The Amazing Spider-Man 2 is available for all major platforms, but outside of the unflinching framerate, which stays steady no matter how fast Spidey swings, I wasn’t all that impressed by its looks on PC or newer consoles. On the Wii U, PlayStation 3, and Xbox 360, the hardware is able to keep pace with a few graphical concessions. The biggest difference is the ground traffic in New York City, where the rush hour of more powerful platforms turns into 3AM Tuesday midtown traffic on the older systems. Across the board, though, there are a few low-budget stylistic choices unbecoming of a blockbuster movie adaptation, like the lack of lip syncing in the intros and the static news screens that accompany the outros of petty crime side-missions.
In addition to Off-TV Play, the Wii U version places the map on the GamePad, but it’s really not much of a boon, as it’s zoomed in and doesn’t respond to touch. If you want to see outside the five-to-six block radius or drop a beacon, you’ll have to pause and access Spidey’s phone menu as you’d do in every other version.";

$pos = new \StanfordNLP\POSTagger(
  '/home/gamefinder/stanford-postagger-2014-01-04/models/english-left3words-distsim.tagger',
  '/home/gamefinder/stanford-postagger-2014-01-04/stanford-postagger.jar'
);

$tagged = array();

while(strlen($string) !== 0){
	// this tags the first sentence in $string
	$sentence_tagged = $pos->tag(explode(' ', $string)); 

	// for every tagged word in the sentence
	$count = count($sentence_tagged);
	for($i = 0; $i < $count; $i++){
		$pair = $sentence_tagged[$i];
		$word = $pair[0];
		$tag = $pair[1];

		// remove the tagged word from the base string
		$string = substr($string, stripos($string, $word) + strlen($word));

		if (in_array($tag, array("DT", "TO", "IN", "PRP", "CC", '
			PRP$'))){
			// we don't care about this word
			unset($sentence_tagged[$i]); // remove word from array
		}
	}

	// add remaining sentence words to overall array
	$tagged = array_merge($tagged, $sentence_tagged);
}


// stemmed word frequency counting
$word_freq = array();
foreach($tagged as $pair){
	$word = $pair[0];
	$tag = $pair[1];
	$word_freq[Porter::Stem($word)] += 1;
}

var_dump($word_freq);

?>