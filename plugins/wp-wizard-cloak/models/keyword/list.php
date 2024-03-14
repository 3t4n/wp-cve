<?php

class PMLC_Keyword_List extends PMLC_Model_List {
	
	public function __construct() {
		parent::__construct();
		$this->setTable(PMLC_Plugin::getInstance()->getTablePrefix() . 'keywords');
	}
	
	/**
	 * Apply keyword list to the content
	 * @param string $content
	 * @param int[optional] $post_id
	 * @return string
	 */
	public function applyTo($content, $post_id = NULL) {
		if ($this->total()) {
			$limits = array(); // array to buffer limits of replacement allowed
			$chanks_whole_tags = preg_split('%(<(textarea|h[1-8]|a)\b[^>]*>.+?</\2>)%i', $content, -1, PREG_SPLIT_DELIM_CAPTURE); // regular exp defines tags with content which we will skip
			foreach ($chanks_whole_tags as $i_whole => $c_whole_tags) {
				if ($i_whole % 3 == 2) { // remove subpattern which matched a tag name
					$chanks_whole_tags[$i_whole] = '';
				} else if ($i_whole % 3 == 0 and '' != $c_whole_tags) { // skip boundaries and empty strings
					$chanks_tags = preg_split('%(<[^>]*?>)%',  $c_whole_tags, -1, PREG_SPLIT_DELIM_CAPTURE); // regula exp defines rule to skip open or closing tags, but content is left to be handled with replacements
					foreach ($chanks_tags as $i => $c_tags) {
						if ($i % 2 == 0 and '' != $c_tags) { // skip boundaries and empty strings
							foreach ($this as $keyword) {
								$a_tag_template = '<a class="pmlc-linked-keyword" href="' . ($post_id && '' != $keyword['post_id_param'] ? add_query_arg($keyword['post_id_param'], $post_id, $keyword['url']) : $keyword['url']) . '"' . ($keyword['rel_nofollow'] ? ' rel="nofollow"' : '') . ($keyword['target_blank'] ? ' target="_blank"' : '') . '>$0</a>';
								foreach (preg_split('%,\s*%', $keyword['keywords']) as $word) {
									isset($limits[$word]) or $limits[$word] = ($keyword['replace_limit'] ? $keyword['replace_limit'] : -1);
									if ($limits[$word] != 0) {
										$c_tags = preg_replace('%(?<=^|[^\pL-])' . preg_quote($word, '%') . '(?=$|[^\pL-])%u' . ($keyword['match_case'] ? '' : 'i'), $a_tag_template, $c_tags, $limits[$word], $replaces);
										$limits[$word] < 0 or $limits[$word] -= $replaces;
									}
								}
							}
							$chanks_tags[$i] = $c_tags;
						}
					}
					$chanks_whole_tags[$i_whole] = implode('', $chanks_tags);
				}
			}
			$content = implode('', $chanks_whole_tags);
		}
		return $content;
	}
}