<?php

namespace ShopWP\API\Storefront\Shop;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

    public function query_get_available_localizations() {
        return [
            "query" => 'query Localizatiosn {
                localization {
                    language {
                        endonymName
                        isoCode
                        name
                    }
                    country {
                        isoCode
                        name
                        availableLanguages {
                            endonymName
                            isoCode
                            name
                        }      
                        currency {
                            isoCode
                            name
                            symbol
                        }
                    }

                    availableCountries {
                        isoCode
                        name

                        availableLanguages {
                            isoCode
                            endonymName
                            name
                        }

                        currency {
                            isoCode
                            name
                            symbol
                        }
                    }
                }
            }'
        ];
    }   

}