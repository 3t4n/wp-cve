<?php

class MM_WPFS_Languages {
    /**
	 * Creates an array of locales/languages supported by Stripe Checkout.
	 *
	 * @return array list of locales/languages
	 */
    public static function getCheckoutLanguages() {
        return array(
            array(
                'value' => 'bg',
                'name' => __('Bulgarian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'cs',
                'name' => __('Czech', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'da',
                'name' => __('Danish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'de',
                'name' => __('German', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'el',
                'name' => __('Greek', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'en',
                'name' => __('English', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'en-GB',
                'name' => __('English (United Kingdom)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'es',
                'name' => __('Spanish (Spain)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'es-419',
                'name' => __('Spanish (Latin America)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'et',
                'name' => __('Estonian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'fi',
                'name' => __('Finnish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'fil',
                'name' => __('Filipino', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'fr',
                'name' => __('French (France)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'fr-CA',
                'name' => __('French (Canada)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'hr',
                'name' => __('Croatian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'hu',
                'name' => __('Hungarian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'id',
                'name' => __('Indonesian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'it',
                'name' => __('Italian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ja',
                'name' => __('Japanese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ko',
                'name' => __('Korean', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'lv',
                'name' => __('Lithuanian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'lt',
                'name' => __('Latvian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ms',
                'name' => __('Malay', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'mt',
                'name' => __('Maltese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'nb',
                'name' => __('Norwegian Bokmål', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'nl',
                'name' => __('Dutch', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'pl',
                'name' => __('Polish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'pt',
                'name' => __('Portuguese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'pt-BR',
                'name' => __('Portuguese (Brazil)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ro',
                'name' => __('Romanian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ru',
                'name' => __('Russian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'sk',
                'name' => __('Slovak', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'sl',
                'name' => __('Slovenian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'sv',
                'name' => __('Swedish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'th',
                'name' => __('Thai', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'tr',
                'name' => __('Turkish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'vi',
                'name' => __('Vietnamese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'zh',
                'name' => __('Simplified Chinese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'zh-HK',
                'name' => __('Chinese Traditional (Hong Kong)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'zh-TW',
                'name' => __('Chinese Traditional (Taiwan)', 'wp-full-stripe-admin')
            )
        );
    }

    public static function getCheckoutLanguageCodes() {
        $languages = MM_WPFS_Languages::getCheckoutLanguages();
        $languageCodes = array();

        foreach ($languages as $language) {
            array_push($languageCodes, $language['value']);
        }

        return $languageCodes;
    }

    /**
     * Creates an array of locales/languages supported by Stripe Elements.
     *
     * @return array list of locales/languages
     */
    public static function getStripeElementsLanguages() {
        return array(
            array(
                'value' => 'ar',
                'name' => __('Arabic', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'bg',
                'name' => __('Bulgarian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'cs',
                'name' => __('Czech', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'da',
                'name' => __('Danish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'de',
                'name' => __('German', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'el',
                'name' => __('Greek', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'en',
                'name' => __('English', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'en-GB',
                'name' => __('English (United Kingdom)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'es',
                'name' => __('Spanish (Spain)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'es-419',
                'name' => __('Spanish (Latin America)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'et',
                'name' => __('Estonian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'fi',
                'name' => __('Finnish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'fil',
                'name' => __('Filipino', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'fr',
                'name' => __('French (France)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'fr-CA',
                'name' => __('French (Canada)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'he',
                'name' => __('Hebrew', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'hr',
                'name' => __('Croatian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'hu',
                'name' => __('Hungarian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'id',
                'name' => __('Indonesian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'it',
                'name' => __('Italian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ja',
                'name' => __('Japanese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ko',
                'name' => __('Korean', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'lv',
                'name' => __('Lithuanian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'lt',
                'name' => __('Latvian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ms',
                'name' => __('Malay', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'mt',
                'name' => __('Maltese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'nb',
                'name' => __('Norwegian Bokmål', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'nl',
                'name' => __('Dutch', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'pl',
                'name' => __('Polish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'pt',
                'name' => __('Portuguese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'pt-BR',
                'name' => __('Portuguese (Brazil)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ro',
                'name' => __('Romanian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'ru',
                'name' => __('Russian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'sk',
                'name' => __('Slovak', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'sl',
                'name' => __('Slovenian', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'sv',
                'name' => __('Swedish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'th',
                'name' => __('Thai', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'tr',
                'name' => __('Turkish', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'vi',
                'name' => __('Vietnamese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'zh',
                'name' => __('Simplified Chinese', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'zh-HK',
                'name' => __('Chinese Traditional (Hong Kong)', 'wp-full-stripe-admin')
            ),
            array(
                'value' => 'zh-TW',
                'name' => __('Chinese Traditional (Taiwan)', 'wp-full-stripe-admin')
            )
        );
    }

    public static function getStripeElementsLanguageCodes() {
        $languages = self::getStripeElementsLanguages();
        $languageCodes = array();

        foreach ($languages as $language) {
            array_push($languageCodes, $language['value']);
        }

        return $languageCodes;
    }
}
