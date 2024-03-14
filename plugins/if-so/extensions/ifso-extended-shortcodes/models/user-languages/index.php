<?php
	require_once(__DIR__.'/iso-639-1-codes.php');

	function build_user_languages_visual($languages) {
		$html = '';

		foreach ($languages as $lang_code) {
			$lang_name = get_language_name($lang_code);

		    $html .= '
<div class="ifso-lang-row">
	<span class="ifso-lang-name">
		' . $lang_name . '
	</span>
	<span class="ifso-lang-code">
		(' . $lang_code . ')
	</span>
</div>';

		}

	 	return $html;
	}

	function get_language_name($lang_code) {
		global $codes;

		if ( strpos($lang_code, '-') !== false ) {
			$lang_code = preg_split("/-/", $lang_code)[0];
		}

		$lang_name = "Unknown";
		if ( isset( $codes[$lang_code] ) ) {
	 		$lang_name = $codes[$lang_code];
	    }

	    return $lang_name;
	}

	function build_user_languages_clean_visual($languages) {
		$html = '';

		$i = 0;
		$count = count($languages) - 1;
		foreach ($languages as $lang_code) {
			$lang_name = get_language_name($lang_code);

			$html .= $lang_name . ' (' . $lang_code . ')';

			if ($i < $count) {
				$html .= '|'; // new line
			}

			$i++;
		}

		return $html;
	}