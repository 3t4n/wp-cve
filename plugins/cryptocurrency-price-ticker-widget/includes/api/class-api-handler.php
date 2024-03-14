<?php
if (!defined('ABSPATH')) {
    exit();
}
if (!class_exists('CCPW_api_data')) {
    class CCPW_api_data
    {
        use CCPW_Helper_Functions;

        /**
         * CCPW_API_ENDPOINT
         *
         * Holds the URL of the coins data API.
         *
         * @access public
         *
         */

        const COINGECKO_API_ENDPOINT = 'https://api.coingecko.com/api/v3/';
        const COINPAPRIKA_API_ENDPOINT = 'https://api.coinpaprika.com/v1/tickers';

        /**
         * OPENEXCHANGERATE_API_ENDPOINT
         *
         * Holds the URL of the openexchangerates API.
         *
         * @access public
         *
         */

        const OPENEXCHANGERATE_API_ENDPOINT = 'https://openexchangerates.org/api/latest.json?app_id=';

        public function __construct()
        {
            // self::CMC_API_ENDPOINT = 'https://apiv3.coinexchangeprice.com/v3/';
        }

        /**
         * Fetches data from the CoinGecko API and saves it in the database.
         * MUST NOT CALL THIS FUNCTION DIRECTLY.
         */
        public function ccpw_get_coin_gecko_data()
        {
            // Transient names for API activation and data cache
            $update_api_name = 'ccpw-active-api';
            $data_cache_name = 'ccpw-saved-coindata';

            // Retrieve transient data
            $activate_api = get_transient($update_api_name);
            $cache = get_transient($data_cache_name);

            // Get CoinGecko API key and cache time from settings
            $api_option = get_option("openexchange-api-settings");
            $coingecko_api_key = (isset($api_option['coingecko_api'])) ? $api_option['coingecko_api'] : "";
            $coingecko_api_cache_time = isset($api_option['select_cache_time']) ? (int) $api_option['select_cache_time'] : 10;

            // Check if user is authenticated
            if (!$this->ccpw_check_user()) {
                return;
            }

            // Avoid updating database if cache exists and the same API is requested
            if ($activate_api == 'CoinGecko' && false != $cache) {
                return;
            }

            // API URL for CoinGecko
            $api_url = self::COINGECKO_API_ENDPOINT . 'coins/markets?vs_currency=usd&order=market_cap_desc&per_page=250&page=1&sparkline=false&x_cg_demo_api_key=' . $coingecko_api_key;

            // Fetch data from CoinGecko API
            $request = wp_remote_get($api_url, array('timeout' => 120, 'sslverify' => false));

            // Check for WP error
            if (is_wp_error($request)) {
                return false; // Bail early
            }

            // Retrieve response body
            $body = wp_remote_retrieve_body($request);
            $coins = json_decode($body);
            $response = array();
            $coins_data = array();

            // Process coin data
            if (isset($coins) && $coins != "" && is_array($coins)) {
                // Track CoinGecko API hit
                $this->ccpw_track_coingecko_api_hit();

                foreach ($coins as $coin) {
                    $response['coin_id'] = $coin->id;
                    $response['rank'] = $coin->market_cap_rank;
                    $response['name'] = $coin->name;
                    $response['symbol'] = strtoupper($coin->symbol);
                    $response['price'] = $this->ccpw_set_default_if_empty($coin->current_price, 0.00);
                    $response['percent_change_24h'] = $this->ccpw_set_default_if_empty($coin->price_change_percentage_24h, 0);
                    $response['market_cap'] = $this->ccpw_set_default_if_empty($coin->market_cap, 0);
                    $response['total_volume'] = $this->ccpw_set_default_if_empty($coin->total_volume);
                    $response['circulating_supply'] = $this->ccpw_set_default_if_empty($coin->circulating_supply);
                    $response['logo'] = substr($coin->image, strpos($coin->image, "images") + 7);
                    $coins_data[] = $response;
                }

                // Insert data into database
                $DB = new ccpw_database();
                $DB->create_table();
                $DB->ccpw_insert($coins_data);

                // Set transients for cache
                set_transient($data_cache_name, date('H:s:i'), $coingecko_api_cache_time * MINUTE_IN_SECONDS);
                set_transient($update_api_name, 'CoinGecko', 0);
            }
        }

        /**
         * Fetches data from the CoinPaprika API and caches it for performance.
         */
        public function ccpw_get_coin_paprika_data()
        {
            // Transient names for API activation and data cache
            $update_api_name = 'ccpw-active-api';
            $data_cache_name = 'ccpw-saved-coindata';

            // Retrieve transient data
            $activate_api = get_transient($update_api_name);
            $cache = get_transient($data_cache_name);

            // Get cache time from API settings
            $api_option = get_option("openexchange-api-settings");
            $cache_time = isset($api_option['select_cache_time']) ? (int) $api_option['select_cache_time'] : 10;

            // Check if cache exists and the same API is requested, then return
            if ($activate_api == 'CoinPaprika' && false != $cache) {
                return;
            }

            // API URL for CoinPaprika
            $api_url = self::COINPAPRIKA_API_ENDPOINT;

            // Fetch data from API
            $request = wp_remote_get(
                $api_url,
                array(
                    'timeout' => 120,
                    'sslverify' => false,
                )
            );

            // Check for WP error
            if (is_wp_error($request)) {
                return false; // Bail early
            }

            // Retrieve response body
            $body = wp_remote_retrieve_body($request);
            $coin_info = json_decode($body, true);
            $response = array();
            $coins_data = array();

            // Limit the number of coins data to 250
            $coin_info = array_slice($coin_info, 0, 250);

            // Process coin data
            if (is_array($coin_info) && !empty($coin_info)) {
                foreach ($coin_info as $coin) {
                    $response['coin_id'] = $coin['id'];
                    $response['rank'] = $coin['rank'];
                    $response['name'] = $coin['name'];
                    $response['symbol'] = strtoupper($coin['symbol']);
                    $response['price'] = $this->ccpw_set_default_if_empty($coin['quotes']['USD']['price'], 0.00);
                    $response['percent_change_24h'] = $this->ccpw_set_default_if_empty($coin['quotes']['USD']['percent_change_24h']);
                    $response['market_cap'] = $this->ccpw_set_default_if_empty($coin['quotes']['USD']['market_cap'], 0);
                    $response['circulating_supply'] = $this->ccpw_set_default_if_empty($coin['circulating_supply']);
                    $response['total_volume'] = 'N/A';
                    $response['logo'] = 'N/A';
                    $response['last_updated'] = gmdate('Y-m-d h:i:s');
                    $coins_data[] = $response;
                }

                // Insert data into database
                $DB = new ccpw_database();
                $DB->ccpw_insert($coins_data);

                // Set transients for cache
                set_transient($data_cache_name, date('H:s:i'), $cache_time * MINUTE_IN_SECONDS);
                set_transient($update_api_name, 'CoinPaprika', 0);
            }
        }

        /**
         * Retrieve USD conversions for cryptocurrencies.
         *
         * This function fetches the USD conversions for various currencies and caches the data for performance.
         *
         * @param string $currency The currency code for which the conversion is requested.
         * @return mixed|array|bool The USD conversions for the specified currency or false if not available.
         */
        public function ccpw_usd_conversions($currency)
        {
            // Check if USD conversions data is available in transient
            $conversions = get_transient('cmc_usd_conversions');
            $conversions_option = get_option('cmc_usd_conversions');

            // If conversions data is not available or transient is empty, fetch from API
            if (empty($conversions) || $conversions === "" || empty($conversions_option)) {
                // Get OpenExchangeRate API settings
                $api_option = get_option("openexchange-api-settings");
                $api = (!empty($api_option['openexchangerate_api'])) ? $api_option['openexchangerate_api'] : "";

                // If API key is not provided, return existing data if available
                if (empty($api)) {
                    if (!empty($conversions_option)) {
                        if ($currency == "all") {
                            return $conversions_option;
                        } else {
                            if (isset($conversions_option[$currency])) {
                                return $conversions_option[$currency];
                            }
                        }
                    }
                    return false;
                } else {
                    // Fetch conversion data from OpenExchangeRate API
                    $request = wp_remote_get(self::OPENEXCHANGERATE_API_ENDPOINT . $api . '', array('timeout' => 120, 'sslverify' => true));
                }

                // Handle request error
                if (is_wp_error($request)) {
                    return false;
                }

                // List of supported currency IDs
                $currency_ids = array("USD", "AUD", "BRL", "CAD", "CZK", "DKK", "EUR", "HKD", "HUF", "ILS", "INR", "JPY", "MYR", "MXN", "NOK", "NZD", "PHP", "PLN", "GBP", "SEK", "CHF", "TWD", "THB", "TRY", "CNY", "KRW", "RUB", "SGD", "CLP", "IDR", "PKR", "ZAR");

                // Extract conversion data from API response
                $body = wp_remote_retrieve_body($request);
                $conversion_data = json_decode($body);

                // Check if conversion data is available
                if (isset($conversion_data->rates)) {
                    $conversion_data = (array) $conversion_data->rates;
                } else {
                    $conversion_data = array();
                    if (!empty($conversions_option)) {
                        if ($currency == "all") {
                            return $conversions_option;
                        } else {
                            if (isset($conversions_option[$currency])) {
                                return $conversions_option[$currency];
                            }
                        }
                    }
                }

                // Process and update conversion data
                if (is_array($conversion_data) && count($conversion_data) > 0) {
                    foreach ($conversion_data as $key => $currency_price) {
                        if (in_array($key, $currency_ids)) {
                            $conversions_option[$key] = $currency_price;
                        }
                    }

                    // Sort conversion data based on currency IDs
                    uksort($conversions_option, function ($key1, $key2) use ($currency_ids) {
                        return (array_search($key1, $currency_ids) > array_search($key2, $currency_ids)) ? 1 : -1;
                    });

                    // Update options and transient with the latest conversion data
                    update_option('cmc_usd_conversions', $conversions_option);
                    set_transient('cmc_usd_conversions', $conversions_option, 12 * HOUR_IN_SECONDS);
                }
            }

            // Return the requested currency conversion or all conversions if requested
            if ($currency == "all") {
                return $conversions_option;
            } else {
                if (isset($conversions_option[$currency])) {
                    return $conversions_option[$currency];
                }
            }
        }

    }
}
