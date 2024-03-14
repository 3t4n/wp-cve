<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once('user-behavior-trigger-base.class.php');

class UserBehaviorBrowserLanguageTrigger extends UserBehaviorTriggerBase {
	protected function is_valid($trigger_data) {
		if ( !parent::is_valid( $trigger_data ) )
			return false;

		$rule = $trigger_data->get_rule();
		$user_behavior = $rule['User-Behavior'];

		return $user_behavior == 'BrowserLanguage';
	}

	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();

		// grab user's language
		$user_languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$languages = [];

		preg_match_all("/[a-zA-Z-]{2,10}/",
					   $user_languages,
					   $languages);

		if ($languages &&
			is_array($languages[0]))
			$languages = $languages[0];

		$is_primary = false;

		if ( isset($rule['user-behavior-browser-language-primary-lang']) )
			$is_primary = ($rule['user-behavior-browser-language-primary-lang'] == 'true');

		$selected_language = $rule['user-behavior-browser-language'];

		// check if user's language is in match with the 
		// user behavior selected language

		$is_present = false;

		if ( $is_primary ) { //Added en-US\UK support on $language array @ metabox
			// the checkbox is selected, thus check only for 1st language
			// in the last (aka `primary`)
			// echo $languages[0];
			// echo $selected_language;
			if (is_array($languages)) {
				$is_present = ($selected_language == $languages[0]);
			} else {
				$is_present = ($selected_language == $languages);
			}
		} else {
			$is_present = $this->is_haystack_contains_needle($languages,
				$selected_language);
		}

		if ( $is_present ) {
			return $trigger_data->get_content();
		}

		return false;
	}

	private function is_haystack_contains_needle($haystack, $needle) {
		if (!$haystack || 
			!$needle || 
			!is_array($haystack))
				return false;

		foreach ($haystack as $val) {
			if ((strpos($val, $needle) !== false) || 
				strpos($needle, $val) !== false) {
				return true;
			}
		}

		return false;
	}
}