<?php
/*
Plugin Name: FD Word Statistics
Plugin URI: http://flagrantdisregard.com/wordstats/
Description: Computes Gunning-Fog, Flesch, and Flesch-Kincaid readability indexes about posts as they are edited for the purpose of improving their readability.
Author: John Watson
Author URI: http://flagrantdisregard.com/
Version: 1.3
*/ 

/************************************************************
WordStatsPlugin.php - A plugin for calculating readability
statistics for WordPress based on the WordStats readability
class.

Copyright (C) Thu Aug 26 2004 John Watson
john@flagrantdisregard.com
http://flagrantdisregard.com/

$Id: wordstatsplugin.php 339 2005-12-30 18:35:04Z John $

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
'
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
************************************************************/

class WordStats {
	var $text;
	
	// These variables cache calculations between calls to set_text();
	var $words;
	var $sentences;
	var $syllables;
	var $hardwords;
	var $avgwordspersentence;
	var $avgsyllablespersentence;
	var $avgsyllablesperword;
	
	// This method must be called first to set the text value to be analyzed
	function set_text($text) {
		$this->text = strtolower(trim(strip_tags($text)));
		
		// Reset cache variables
		$this->words = -1;
		$this->sentences = -1;
		$this->hardwords = -1;
		$this->avgwordspersentence = -1;
		$this->avgsyllablespersentence = -1;
		$this->avgsyllablesperword = -1;
	}
	
	// Get the text value set earlier
	function get_text() {
		return($this->text);
	}
	
	// Get the word count
	function get_words() {
		if ($this->text == '') { return(0); }
		
		// Return cached value if available
		if ($this->words != -1) { return($this->words); }
		
		// What about dashes (vs. hyphens).
		//return(count(preg_split('/( |\n)/', trim($this->text))));
		$count = str_word_count($this->text);
		if ($count <= 0) {
			// There's always one, isn't there?  This prevents division by zero, too.
			$count = 1;
		}
		
		// Update cache
		$this->words = $count;
		
		return($count);
	}
	
	// Get the sentence count
	// This could use some improvements for special cases like dialog and multiple punctuation.
	function get_sentences() {
		if ($this->text == '') { return(0); }
		
		// Return cached value if available
		if ($this->sentences != -1) { return($this->sentences); }
		
		// What about multiple punctuation??  And... ellipses.  And too much enthusiasm!!!
		$count = count(preg_split('/(!|\.|\?)/', $this->text))-1;
		if ($count <= 0) {
			// There's always one, isn't there?  This prevents division by zero, too.
			$count = 1;
		}
		
		// Update cache
		$this->sentences = $count;
		
		return($count);
	}
	
	// Get the syllable count
	// This could use some improvements in both counting syllables and hard words.
	function get_syllables($mode = 0) {
		if ($this->text == '') { return(0); }
		
		// Return cached value if available
		if ($this->syllables != -1 && $this->hardwords != -1) {
			if ($mode == 0) {
				return($this->syllables);
			}
			if ($mode == 1) {
				return($this->hardwords);
			}
			if ($mode == 2) {
				return(array($this->syllables, $this->hardwords));
			}
		}
		
		$count = 0;
		$hardwords = 0;
		
		foreach(str_word_count($this->text, 1) as $word) {
			$wordcount = 0;
			$lastchar = '';
			$thischar = '';
			for($i=0;$i<strlen($word);$i++) {
				$thischar = substr($word, $i, 1);
				if (preg_match('/[aeiouy]/i', $thischar) && !preg_match('/[aeiouy]/i', $lastchar)) {
					$wordcount++;
				}
				$lastchar = $thischar;
			}
			
			// **************************************************
			// Special rules
			// **************************************************
			
			// Word ends in a consonant followed by ed
			if (substr($word, -2) == 'ed') {
				if (preg_match('/[aeiouy]ed$/i', $word)) {
					//$wordcount--;
				}
			}
			
			// Word is an -ism
			if (substr($word, -3) == 'ism') { $wordcount++; }
			
			// Word contains silent e
			if (strstr($word, 'use')) { $wordcount--; }
			if (substr($word, -1) == 'e') { $wordcount--; }
			
			// Word ends in es
			if (substr($word, -2) == 'es' && substr($word, -3) != 'ies') {$wordcount--; }
			
			// Word ends in ying
			if (substr($word, -4) == 'ying') { $wordcount++; }
			
			// **************************************************
			
			// Every word has at least one syllable
			if ($wordcount <= 0) { $wordcount = 1; }
			
			// Add syllables in this word to the total
			$count += $wordcount;
			
			// Hard words are those with 3 or more syllables
			// There are exceptions not codified here.
			if ($wordcount >= 3) {
				$hardwords++;
			}
			
			// Debug output
			//printf("%s => %d<hr />", $word, $wordcount);
			
		}
		
		// Update cache
		$this->syllables = $count;
		$this->hardwords = $hardwords;
		
		if ($mode == 0) {
			return($count);
		}
		if ($mode == 1) {
			return($hardwords);
		}
		if ($mode == 2) {
			return(array($count, $hardwords));
		}
	}
	
	// Get the average words per sentence
	function get_avg_words_per_sentence() {
		if ($this->text == '') { return(0); }
		
		// Return cached value if available
		if ($this->avgwordspersentence != -1) { return($this->avgwordspersentence); }
		
		$count = $this->get_words()/$this->get_sentences();
		
		// Update cache
		$this->avgwordspersentence = $count;
		
		return($count);
	}
	
	// Get the average syllables per sentence
	function get_avg_syllables_per_sentence() {
		if ($this->text == '') { return(0); }
		
		// Return cached value if available
		if ($this->avgsyllablespersentence != -1) { return($this->avgsyllablespersentence); }
		
		$count = $this->get_syllables()/$this->get_sentences();
		
		// Update cache
		$this->avgsyllablespersentence = $count;
		
		return($count);
	}
	
	// Get the average syllables per word
	function get_avg_syllables_per_word() {
		if ($this->text == '') { return(0); }
		
		// Return cached value if available
		if ($this->avgsyllablesperword != -1) { return($this->avgsyllablesperword); }
		
		$count = $this->get_syllables()/$this->get_words();
		
		// Update cache
		$this->avgsyllablesperword = $count;
		
		return($count);
	}
	
	// The Flesch index (higher is better)
	function get_flesch() {
		if ($this->text == '') { return(0); }
		
		/*
		1. Calculate the average sentence length: L
		2. Calculate the average number of syllables per word: N
		3. Calculate the score between 0 and 100%.
		*/
		
		$L = $this->get_avg_words_per_sentence();
		$N = $this->get_avg_syllables_per_word();
		
		return(206.835 - $L*1.015 - $N*84.6);
	}
	
	// The Gunning-Fog index (lower is better)
	function get_fog() {
		if ($this->text == '') { return(0); }
		
		/*
		1. Count the number of words in the paragraph: W
		2. Count the number of sentences in the paragraph: S
		3. Count the number of words of three syllables or more: T
		4. Apply the following formula: [W/S + T*100/W] x 0.4
		*/
		
		$W = $this->get_words();
		$S = $this->get_sentences();
		$T = $this->get_syllables(1);
		
		return(($W/$S + $T*100/$W) * 0.4);
	}
	
	// The Flesch-Kincaid index (lower is better)
	function get_flesch_kincaid() {
		if ($this->text == '') { return(0); }
		
		/*
		This is a US Government Department of Defense standard test  [16].
		(i) Calculate L, the average sentence length (number of words / number of sentences).  Estimate the number of sentences to the nearest tenth, where necessary.
		(ii) Calculate N, the average number of syllables per word (number of syllables / number of words).
		Then grade level = ( L * 0.39 ) + ( N * 11.8 ) - 15.59
		So Reading Age = ( L * 0.39 ) + ( N * 11.8 ) - 10.59 years.
		*/
		
		$L = $this->get_avg_words_per_sentence();
		$N = $this->get_avg_syllables_per_word();
		
		return($L*0.39 + $N*11.8 - 15.59);
	}
}

/*
==================================================
Admin functions
==================================================
*/

function wordstats_add_meta_box() {
	add_meta_box('wordstats', 'Writing analysis', 'wordstats_meta_box', 'post');
	add_meta_box('wordstats', 'Writing analysis', 'wordstats_meta_box', 'page');
}

// Draw the readability statistics for the post
// being edited and the help rollovers.
function wordstats_meta_box() {
	global $wpdb;
	global $post;
	global $wp_version;

	if ($post->post_content != '') {
		$stat = new WordStats();
		$stat->set_text($post->post_content);
		$template =
			'<table width="100%%"><tr>'
			.'<td align="left" width="35%%">Sentences<br>%d</td> '
			.'<td align="left" width="20%%" title="Score indicates number of years of education required for comprehension.">Fog<br>%2.1f</td> '
			.'<td align="left" width="25%%" title="Score indicates number of years of education required for comprehension.">Kincaid<br>%2.1f</td> '
			.'<td align="left" width="20%%" title="Readability score between 0 (worst) and 100 (best).">Flesch<br>%3.0f</td> '
			.'</tr></table>';
		printf($template,
			$stat->get_sentences(),
			$stat->get_fog(),
			$stat->get_flesch_kincaid(),
			$stat->get_flesch()
		);
	}
}

/*
==================================================
Template functions
==================================================
You can use these just like other template tags in your
blog.  They all take at least one argument of the content
being edited.  Like this:

	Fog index: <?php wordstats_fog($pages[$page-1]); ?>
*/

// Get the word count
function wordstats_words($content) {
	$count = 0;
	
	if ($content != '') {
		$stat = new WordStats;
		$stat->set_text($content);
		$count = $stat->get_words();
	}
	
	return($count);
}

// Get the sentence count
function wordstats_sentences($content) {
	$count = 0;
	
	if ($content != '') {
		$stat = new WordStats;
		$stat->set_text($content);
		$count = $stat->get_sentences();
	}
	
	return($count);
}

// Get the Gunning-Fog index value
function wordstats_fog($content) {
	$index = 0;
	
	if ($content != '') {
		$stat = new WordStats;
		$stat->set_text($content);
		$index = $stat->get_fog();
	}
	
	return($index);
}

// Get the Flesch index value
function wordstats_flesch($content) {
	$index = 0;
	
	if ($content != '') {
		$stat = new WordStats;
		$stat->set_text($content);
		$index = $stat->get_flesch();
	}
	
	return($index);
}

// Get the Flesch-Kincaid index value
function wordstats_flesch_kincaid($content) {
	$index = 0;
	
	if ($content != '') {
		$stat = new WordStats;
		$stat->set_text($content);
		$index = $stat->get_flesch_kincaid();
	}
	
	return($index);
}

/*
==================================================
Add action hooks
==================================================
*/
add_action('add_meta_boxes', 'wordstats_add_meta_box');
