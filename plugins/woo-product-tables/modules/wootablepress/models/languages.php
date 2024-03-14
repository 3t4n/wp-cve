<?php

class LanguagesModelWtbp extends ModelWtbp {
	/**
	 * Returns an array of the DataTable translations.
	 *
	 * @return array
	 */
	public function getDefaultLanguages() {
		return array(
			'default',
			'Afrikaans',
			'Albanian',
			'Arabic',
			'Armenian',
			'Azerbaijan',
			'Bangla',
			'Basque',
			'Belarusian',
			'Bulgarian',
			'Catalan',
			'Chinese-traditional',
			'Chinese',
			'Croatian',
			'Czech',
			'Danish',
			'Dutch',
			'English',
			'Estonian',
			'Filipino',
			'Finnish',
			'French',
			'Galician',
			'Georgian',
			'German',
			'Greek',
			'Gujarati',
			'Hebrew',
			'Hindi',
			'Hungarian',
			'Icelandic',
			'Indonesian-Alternative',
			'Indonesian',
			'Irish',
			'Italian',
			'Japanese',
			'Korean',
			'Kyrgyz',
			'Latvian',
			'Lithuanian',
			'Macedonian',
			'Malay',
			'Mongolian',
			'Nepali',
			'Norwegian',
			'Persian',
			'Polish',
			'Portuguese-Brasil',
			'Portuguese',
			'Romanian',
			'Russian',
			'Serbian',
			'Sinhala',
			'Slovak',
			'Slovenian',
			'Spanish',
			'Swahili',
			'Swedish',
			'Tamil',
			'Thai',
			'Turkish',
			'Ukranian',
			'Urdu',
			'Uzbek',
			'Vietnamese'
		);
	}

	/**
	 * Returns an array of the current languages at the official DataTable repo.
	 *
	 * @return array|null
	 */
	public function downloadLanguages() {
		$url = 'https://api.github.com/repos/DataTables/Plugins/contents/i18n';
		$languages = array();

		$response = wp_remote_get($url);

		if (is_wp_error($response)) {
			return null;
		}

		if (200 !== wp_remote_retrieve_response_code($response)) {
			return null;
		}

		$files = json_decode($response['body']);

		if (!is_array($files)) {
			return null;
		}

		foreach ($files as $file) {
			$languages[] = str_replace('.lang', '', $file->name);
		}

		return $languages;
	}

	/**
	 * Tries to download full list of the languages from the official repo or
	 * returns the default languages list of download failed.
	 *
	 * @return array
	 */
	public function getLanguages() {
		$languages = $this->downloadLanguages();

		if (null === $languages) {
			$languages = $this->getDefaultLanguages();
		}

		return $languages;
	}

	public function getLanguageMap() {
		return array(
			'af' => 'Afrikaans',
			'sq' => 'Albanian',
			'ar' => 'Arabic',
			'hy' => 'Armenian',
			'az'=> 'Azerbaijan',
			'bn'=> 'Bangla',
			'eu'=> 'Basque',
			'be'=> 'Belarusian',
			'bg'=> 'Bulgarian',
			'ca'=> 'Catalan',
			'zh-TW'=> 'Chinese-traditional',
			'zh'=> 'Chinese',
			'hr'=> 'Croatian',
			'cs'=> 'Czech',
			'da'=> 'Danish',
			'nl'=> 'Dutch',
			'en'=> 'default',
			'et'=> 'Estonian',
			'fil'=> 'Filipino',
			'fi'=> 'Finnish',
			'fr'=> 'French',
			'gl'=> 'Galician',
			'ka'=> 'Georgian',
			'de'=> 'German',
			'el'=> 'Greek',
			'gu'=> 'Gujarati',
			'he'=> 'Hebrew',
			'hi'=> 'Hindi',
			'hu'=> 'Hungarian',
			'is'=> 'Icelandic',
			'id'=> 'Indonesian',
			'ga'=> 'Irish',
			'it'=> 'Italian',
			'ja'=> 'Japanese',
			'kk'=> 'Kazakh',
			'ko'=> 'Korean',
			'ky'=> 'Kyrgyz',
			'lv'=> 'Latvian',
			'lt'=> 'Lithuanian',
			'mk'=> 'Macedonian',
			'ms'=> 'Malay',
			'mn'=> 'Mongolian',
			'ne'=> 'Nepali',
			'nb'=> 'Norwegian-Bokmal',
			'nn'=> 'Norwegian-Nynorsk',
			'ps'=> 'Pashto',
			'fa'=> 'Persian',
			'pl'=> 'Polish',
			'pt-BR'=> 'Portuguese-Brasil',
			'pt'=> 'Portuguese',
			'ro'=> 'Romanian',
			'ru'=> 'Russian',
			'sr'=> 'Serbian',
			'si'=> 'Sinhala',
			'sk'=> 'Slovak',
			'sl'=> 'Slovenian',
			'es'=> 'Spanish',
			'sw'=> 'Swahili',
			'sv'=> 'Swedish',
			'ta'=> 'Tamil',
			'th'=> 'Thai',
			'tr'=> 'Turkish',
			'uk'=> 'Ukrainian',
			'ur'=> 'Urdu',
			'uz'=> 'Uzbek',
			'vi'=> 'Vietnamese',
			'cy'=> 'Welsh',
			'te'=> 'telugu',
		);
	}

	public function getLanguageBackend() {
		return array(
			'Afrikaans' => 'Afrikaans',
			'Albanian' => 'Albanian',
			'Arabic' => 'Arabic',
			'Armenian' => 'Armenian',
			'Azerbaijan' => 'Azerbaijan',
			'Bangla' => 'Bangla',
			'Basque' => 'Basque',
			'Belarusian' => 'Belarusian',
			'Bulgarian' => 'Bulgarian',
			'Catalan' => 'Catalan',
			'Chinese-traditional' => 'Chinese-traditional',
			'Chinese' => 'Chinese',
			'Croatian' => 'Croatian',
			'Czech' => 'Czech',
			'Danish' => 'Danish',
			'Dutch' => 'Dutch',
			'English' => 'English',
			'Estonian' => 'Estonian',
			'Filipino'=> 'Filipino',
			'Finnish' => 'Finnish',
			'French' => 'French',
			'Galician' => 'Galician',
			'Georgian' => 'Georgian',
			'German' => 'German',
			'Greek' => 'Greek',
			'Gujarati' => 'Gujarati',
			'Hebrew' => 'Hebrew',
			'Hindi' => 'Hindi',
			'Hungarian' => 'Hungarian',
			'Icelandic' => 'Icelandic',
			'Indonesian' => 'Indonesian',
			'Irish' => 'Irish',
			'Italian' => 'Italian',
			'Japanese' => 'Japanese',
			'Kazakh' => 'Kazakh',
			'Korean' => 'Korean',
			'Kyrgyz' => 'Kyrgyz',
			'Latvian' => 'Latvian',
			'Lithuanian' => 'Lithuanian',
			'Macedonian' => 'Macedonian',
			'Malay' => 'Malay',
			'Mongolian' => 'Mongolian',
			'Nepali' => 'Nepali',
			'Norwegian-Bokmal' => 'Norwegian-Bokmal',
			'Norwegian-Nynorsk' => 'Norwegian-Nynorsk',
			'Pashto' => 'Pashto',
			'Persian' => 'Persian',
			'Polish' => 'Polish',
			'Portuguese-Brasil' => 'Portuguese-Brasil',
			'Portuguese' => 'Portuguese',
			'Romanian' => 'Romanian',
			'Russian' => 'Russian',
			'Serbian' => 'Serbian',
			'Sinhala' => 'Sinhala',
			'Slovak' => 'Slovak',
			'Slovenian' => 'Slovenian',
			'Spanish' => 'Spanish',
			'Swahili' => 'Swahili',
			'Swedish' => 'Swedish',
			'Tamil' => 'Tamil',
			'Thai' => 'Thai',
			'Turkish' => 'Turkish',
			'Ukrainian' => 'Ukrainian',
			'Urdu' => 'Urdu',
			'Uzbek' => 'Uzbek',
			'Vietnamese' => 'Vietnamese',
			'Welsh' => 'Welsh',
			'telugu' => 'telugu',
		);
	}
}
