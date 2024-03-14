<?php

function pmlc_the_content($content) {
	// replace auto-linked keywords with corresponding <a> tags
	$keywords = new PMLC_Keyword_List();
	$content = $keywords->getBy('is_trashed', 0)->applyTo($content, @get_the_ID()); // it might be `the_content` filter is called explicitly but not for a page currently rendered
	// replace some urls with their automatches
	if (preg_match_all('%(?<=^|[\'"\s\[\](){}])https?://[\w\d:#@\%/;$()~_?+=\\\\&.-]+(?=$|[\'"\s\[\](){}])%i', $content, $mtchs)) {
		foreach(array_unique($mtchs[0]) as $url) {
			$content = str_replace($url, PMLC_Automatch_Record::findMatch($url), $content);
		}
	}
	return $content;
}