<?php

	if (!function_exists('wf_get_languages')) {

    function wf_get_languages() {
			$languages = array();
			//get all languages from polylang plugin https://wordpress.org/plugins/polylang/
			global $polylang;
			if (function_exists('PLL')) {
				// for polylang versions > 1.8
				$pl_languages = PLL()->model->get_languages_list();
			} else if (isset($polylang)) {
				// for older polylang version
				$pl_languages = $polylang->model->get_languages_list();
			}
			if (isset($pl_languages)) {
				// iterate through polylang language list
				foreach ($pl_languages as $pl_language) {
					$languages[] = $pl_language->slug;
				}
			} else if(function_exists('icl_get_languages')) {
				//get all languages with icl_get_languages for wpml
				$wpml_languages = icl_get_languages('skip_missing=0');
				foreach ($wpml_languages as $wpml_language) {
					$languages[] = !empty($wpml_language['language_code']) ? $wpml_language['language_code'] : $wpml_language['code'];
				}
			}
			else {
				//return wp get_locale() - first 2 chars (en, it, de ...)
				$languages[] = substr(get_locale(),0,2);
			}
			return $languages;
		}

	}

if (!function_exists('wf_get_language')) {

	function wf_get_language() {
		$language = null;
		//get language from polylang plugin https://wordpress.org/plugins/polylang/
		if(function_exists('pll_current_language'))
			$language = pll_current_language();
		//get language from wpml plugin https://wpml.org
		elseif(defined('ICL_LANGUAGE_CODE'))
			$language = ICL_LANGUAGE_CODE;
		//return wp get_locale() - first 2 chars (en, it, de ...)
		else
			$language = substr(get_locale(),0,2);

		return $language;
	}

}
