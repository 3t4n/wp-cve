<?php
if (!defined('ABSPATH')) {
    exit();
}

trait CCPW_Helper_Functions
{

    /**
     * Get coins data
     *
     * @param array $coin_id_arr The array of coin IDs
     * @return array|bool The coins data if successful, false otherwise
     */
    protected function ccpw_get_coins_data($coin_id_arr)
    {
        // Initialize the database
        $DB = new ccpw_database;

        // Get the coins data
        $coin_data = $DB->get_coins(array('coin_id' => $coin_id_arr, 'number' => '1000', 'orderby' => 'market_cap', 'order' => 'DESC'));

        // Check if the coins data is an array and is set
        if (is_array($coin_data) && isset($coin_data)) {
            // Convert the coins data to an array
            $coin_rs_data = $this->ccpw_objectToArray($coin_data);
            return $coin_rs_data;
        } else {
            return false;
        }

    }

    /**
     * Check if the user has entered a Coingecko API key
     *
     * @return bool True if the user has entered a Coingecko API key, false otherwise
     */
    protected function ccpw_check_user()
    {
        // Check if it's a fresh installation
        $fresh_install = get_option('ccpw-fresh-installation');

        // Get the Coingecko API key from the options
        $api_option = get_option("openexchange-api-settings");
        $coingecko_api_key = (isset($api_option['coingecko_api'])) ? $api_option['coingecko_api'] : "";

        // If it's a fresh installation, use the Coingecko API key, otherwise use 'true'
        $coingecko_api_key = ($fresh_install) ? $coingecko_api_key : 'true';

        // If the Coingecko API key is not empty, return true
        if (!empty($coingecko_api_key)) {
            return true;
        }
        // If the Coingecko API key is empty, return false
        else {
            return false;
        }
    }

    /**
     * Get top coins data
     *
     * @param int $limit The limit of coins to fetch
     * @return array|bool The top coins data if successful, false otherwise
     */
    protected function ccpw_get_top_coins_data($limit)
    {
        // Define the order column name and type
        $order_col_name = 'market_cap';
        $order_type = 'DESC';

        // Initialize the database
        $DB = new ccpw_database;

        // Get the coins data
        $coin_data = $DB->get_coins(array(
            "number" => $limit,
            'offset' => 0,
            'orderby' => $order_col_name,
            'order' => $order_type,
        ));

        // If the coins data is an array and is set, convert it to an array and return it
        if (is_array($coin_data) && isset($coin_data)) {
            $coins_rs_arr = $this->ccpw_objectToArray($coin_data);
            return $coins_rs_arr;
        }
        // If the coins data is not an array or is not set, return false
        else {
            return false;
        }
    }

    /**
     * Get all coin IDs
     *
     * @return array|bool The coin IDs if successful, false otherwise
     */
    protected function ccpw_get_all_coin_ids()
    {
        // Initialize the database
        $DB = new ccpw_database;

        // Get the coin data
        $coin_data = $DB->get_coins(array('number' => '1000'));

        // If the coin data is an array and is set, convert it to an array and return it
        if (is_array($coin_data) && isset($coin_data)) {
            $coin_data = $this->ccpw_objectToArray($coin_data);
            $coins = array();
            $api = get_option('ccpw_options');
            $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];

            // Check the API and process the coin data accordingly
            if ($api == "coin_gecko") {
                foreach ($coin_data as $coin) {
                    $coins[$coin['coin_id']] = $coin['name'];
                }
            } else {
                foreach ($coin_data as $coin) {
                    $coin_id = $this->ccpw_coin_array($coin['coin_id']);

                    $coins[$coin_id] = $coin['name'];
                }

            }
            return $coins;
        } else {
            return false;
        }

    }

    /**
     * Check if provided $value is empty or not.
     * Return $default if $value is empty
     */
    protected function ccpw_set_default_if_empty($value, $default = 'N/A')
    {
        return $value ? $value : $default;
    }

    /**
     * Function to get the logo of a coin
     *
     * @param string $coin_id The ID of the coin
     * @param int $size The size of the logo
     * @param bool $HTML Whether to return the logo as HTML or not
     * @return string The logo HTML or URL if successful, false otherwise
     */
    protected function ccpw_get_coin_logo($coin_id, $size = 32, $HTML = true)
    {
        // Initialize the logo HTML
        $logo_html = '';

        // Get the API from the options
        $api = get_option('ccpw_options');
        $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];

        // Check the API and process the coin logo accordingly
        if ($api == "coin_gecko") {
            $coin_svg = CCPWF_DIR . '/assets/coin-logos/' . strtolower($coin_id) . '.svg';
            $coin_png = CCPWF_DIR . '/assets/coin-logos/' . strtolower($coin_id) . '.png';

            if (file_exists($coin_svg)) {
                $coin_svg = CCPWF_URL . 'assets/coin-logos/' . strtolower($coin_id) . '.svg';
                if ($HTML == true) {
                    $logo_html = '<img id="' . $coin_id . '" alt="' . $coin_id . '" src="' . $coin_svg . '" width="' . $size . '">';
                } else {
                    $logo_html = $coin_svg;
                }
                return $logo_html;

            } else if (file_exists($coin_png)) {
                $coin_png = CCPWF_URL . 'assets/coin-logos/' . strtolower($coin_id) . '.png';
                if ($HTML == true) {
                    $logo_html = '<img id="' . $coin_id . '" alt="' . $coin_id . '" src="' . $coin_png . '" width="' . $size . '">';
                } else {
                    $logo_html = $coin_png;
                }
                return $logo_html;

            } else {
                return false;
            }

        } else {
            $original_id = $coin_id;
            $coin_id = $this->ccpw_coin_array($coin_id);

            $coin_svg = CCPWF_DIR . '/assets/coin-logos/' . strtolower($coin_id) . '.svg';
            $coin_png = CCPWF_DIR . '/assets/coin-logos/' . strtolower($coin_id) . '.png';

            if (file_exists($coin_svg)) {
                $coin_svg = CCPWF_URL . 'assets/coin-logos/' . strtolower($coin_id) . '.svg';
                if ($HTML == true) {
                    $logo_html = '<img id="' . $coin_id . '" alt="' . $coin_id . '" src="' . $coin_svg . '" width="' . $size . '">';
                } else {
                    $logo_html = $coin_svg;
                }
                return $logo_html;

            } else if (file_exists($coin_png)) {
                $coin_png = CCPWF_URL . 'assets/coin-logos/' . strtolower($coin_id) . '.png';
                if ($HTML == true) {
                    $logo_html = '<img id="' . $coin_id . '" alt="' . $coin_id . '" src="' . $coin_png . '" width="' . $size . '">';
                } else {
                    $logo_html = $coin_png;
                }
                return $logo_html;

            } else {

                $coin_png = "https://static.coinpaprika.com/coin/$original_id/logo.png";
                $logo_html = '<img id="' . $original_id . '" alt="' . $original_id . '" src="' . $coin_png . '" width="' . $size . '">';
            }
            return $logo_html;

        }

        return $logo_path = CCPWF_URL . 'assets/images/default-logo.png';

        //return 'https://static.coinpaprika.com/coin/' . ccpws_coin_array($coin_id, true) . '/logo.png';

    }

    /**
     * Get the slug for the single page in CoinMarketCap integration.
     *
     * @return string The slug for the single page.
     */
    public function get_cmc_single_page_slug()
    {
        // Initialize the variable for the slug
        $cmc_slug = '';

        // Check if the CoinMarketCap integration function exists
        if (function_exists('cmc_extra_get_option')) {
            // Get the single page slug from CoinMarketCap integration
            $cmc_slug = cmc_extra_get_option('single-page-slug');

            // If the slug is empty, set a default value
            if (empty($cmc_slug)) {
                $cmc_slug = 'currencies';
            }
        } else {
            // If CoinMarketCap integration function doesn't exist, set a default value
            $cmc_slug = 'currencies';
        }

        // Return the slug for the single page
        return $cmc_slug;
    }
    /**
     * Function to track Coingecko API hits
     * This function increments the API hits count and updates the option in the database
     */
    public function ccpw_track_coingecko_api_hit()
    {
        // Get the current API hits count
        $api_hits = get_option('cmc_coingecko_api_hits');

        // If the option doesn't exist, initialize it with a value of 0
        if ($api_hits === false) {
            add_option('cmc_coingecko_api_hits', 0);
        }

        // Increment the API hits count by 1
        $api_hits = (int) $api_hits + 1;

        // Update the API hits count in the database
        update_option('cmc_coingecko_api_hits', $api_hits);
    }
    /**
     * Function to format a number based on its value
     * This function formats a number based on its value
     * If the number is less than or equal to -1, it is formatted with 2 decimal places
     * If the number is less than 0.50, it is formatted with 6 decimal places
     * If the number is greater than 0.50, it is formatted with 2 decimal places
     * @param float $n The number to be formatted
     * @return string The formatted number
     */
    public function ccpw_format_number($n)
    {
        $formatted = $n;
        if ($n <= -1) {
            $formatted = number_format($n, 2, '.', ',');
        } else if ($n < 0.50) {
            $formatted = number_format($n, 6, '.', ',');
        } else {
            $formatted = number_format($n, 2, '.', ',');
        }
        return $formatted;
    }

    /**
     * Function to convert object to array
     * This function checks if the input is an object, and if so, it gets the properties of the object.
     * If the input is an array, it returns the array converted to object using __FUNCTION__ for recursive call.
     * If the input is neither an object nor an array, it returns the input.
     * @param mixed $d The input to be converted
     * @return mixed The converted input
     */
    public function ccpw_objectToArray($d)
    {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map(array($this, 'ccpw_objectToArray'), $d);
        } else {
            return $d;
        }
    }

    // currencies symbol
    public function ccpw_currency_symbol($name)
    {
        $cc = strtoupper($name);
        $currency = array(
            "USD" => "&#36;", //U.S. Dollar
            "CLP" => "&#36;", //CLP Dollar
            "SGD" => "S&#36;", //Singapur dollar
            "AUD" => "&#36;", //Australian Dollar
            "BRL" => "R&#36;", //Brazilian Real
            "CAD" => "C&#36;", //Canadian Dollar
            "CZK" => "K&#269;", //Czech Koruna
            "DKK" => "kr", //Danish Krone
            "EUR" => "&euro;", //Euro
            "HKD" => "&#36", //Hong Kong Dollar
            "HUF" => "Ft", //Hungarian Forint
            "ILS" => "&#x20aa;", //Israeli New Sheqel
            "INR" => "&#8377;", //Indian Rupee
            "IDR" => "Rp", //Indian Rupee
            "KRW" => "&#8361;", //WON
            "CNY" => "&#165;", //CNY
            "JPY" => "&yen;", //Japanese Yen
            "MYR" => "RM", //Malaysian Ringgit
            "MXN" => "&#36;", //Mexican Peso
            "NOK" => "kr", //Norwegian Krone
            "NZD" => "&#36;", //New Zealand Dollar
            "PHP" => "&#x20b1;", //Philippine Peso
            "PLN" => "&#122;&#322;", //Polish Zloty
            "GBP" => "&pound;", //Pound Sterling
            "SEK" => "kr", //Swedish Krona
            "CHF" => "Fr", //Swiss Franc
            "TWD" => "NT&#36;", //Taiwan New Dollar
            "PKR" => "Rs", //Rs
            "THB" => "&#3647;", //Thai Baht
            "TRY" => "&#8378;", //Turkish Lira
            "ZAR" => "R", //zar
            "RUB" => "&#8381;", //rub
        );

        if (array_key_exists($cc, $currency)) {
            return $currency[$cc];
        }
    }

    /**
     * Check admin side post type page
     *
     * This function checks the current post type page in the admin area.
     * It first checks if there is a post and if so, returns its post type.
     * If there is no post, it checks the current screen and returns its post type.
     * If there is no current screen, it checks the request parameters for 'page', 'post_type', and 'post' and returns their sanitized values.
     * If none of these conditions are met, it returns null.
     *
     * @return string|null The post type or null if not found.
     */
    public function ccpw_get_post_type_page()
    {
        global $post, $typenow, $current_screen;

        if ($post && $post->post_type) {
            return $post->post_type;
        } elseif ($typenow) {
            return $typenow;
        } elseif ($current_screen && $current_screen->post_type) {
            return $current_screen->post_type;
        } elseif (isset($_REQUEST['page'])) {
            return sanitize_key($_REQUEST['page']);
        } elseif (isset($_REQUEST['post_type'])) {
            return sanitize_key($_REQUEST['post_type']);
        } elseif (isset($_REQUEST['post'])) {
            return get_post_type(sanitize_text_field($_REQUEST['post']));
        }
        return null;
    }

    /**
     * Update table settings
     *
     * This function updates the table settings based on the old settings.
     * It checks the old settings and updates the post meta accordingly.
     * It also deletes the old post meta after updating.
     *
     * @param int $post_id The post ID to update the settings for.
     */
    public function update_tbl_settings($post_id)
    {
        // Get the old settings
        $old_settings = get_post_meta($post_id, 'display_currencies_for_table', true);

        // If old settings exist, update the post meta and delete the old settings
        if ($old_settings) {
            switch ($old_settings) {
                case 'top-10':
                    $newVal = 10;
                    break;
                case 'top-50':
                    $newVal = 50;
                    break;
                case 'top-100':
                    $newVal = 100;
                    break;
                case 'top-200':
                    $newVal = 200;
                    break;
                case 'all':
                    $newVal = 250;
                    break;
                default:
                    $newVal = 10;
            }
            update_post_meta($post_id, 'show-coins', $newVal);
            delete_post_meta($post_id, 'display_currencies_for_table');
        }
    }

    public function ccpw_set_checkbox_default_for_new_post($default)
    {
        return isset($_GET['post']) ? '' : ($default ? (string) $default : '');
    }
    /**
     * Function to format a number based on its value
     *
     * This function takes a number as input and formats it based on its value.
     * It uses different number formats for different ranges of values.
     *
     * @param float $n The number to format.
     * @return string The formatted number.
     */
    public function ccpw_value_format_number($n)
    {
        // Check the value of the number and format it accordingly
        if ($n <= 0.00001 && $n > 0) {
            return number_format($n, 8, '.', ',');
        } else if ($n <= 0.0001 && $n > 0.00001) {
            return number_format($n, 6, '.', ',');
        } else if ($n <= 0.001 && $n > 0.0001) {
            return number_format($n, 5, '.', ',');
        } else if ($n <= 0.01 && $n > 0.001) {
            return number_format($n, 4, '.', ',');
        } else if ($n <= 1 && $n > 0.01) {
            return number_format($n, 3, '.', ',');
        } else {
            return number_format($n, 2, '.', ',');
        }
    }
    /**
     * Function to format a coin value
     *
     * This function takes a coin value as input and formats it based on its value.
     * It uses different number formats for different ranges of values.
     *
     * @param float $value The coin value to format.
     * @param int $precision The precision of the formatted value.
     * @return string The formatted coin value.
     */
    public function ccpw_format_coin_value($value, $precision = 2)
    {
        // Check the value of the coin and format it accordingly
        if ($value < 1000000) {
            // Anything less than a million
            $formated_str = number_format($value, $precision);
        } else if ($value < 1000000000) {
            // Anything less than a billion
            $formated_str = number_format($value / 1000000, $precision) . 'M';
        } else {
            // At least a billion
            $formated_str = number_format($value / 1000000000, $precision) . 'B';
        }

        return $formated_str;
    }
    /**
     * Function to format a coin value for a widget
     *
     * This function takes a coin value as input and formats it based on its value.
     * It uses different number formats for different ranges of values.
     *
     * @param float $value The coin value to format.
     * @param int $precision The precision of the formatted value.
     * @return string The formatted coin value.
     */
    public function ccpw_widget_format_coin_value($value, $precision = 2)
    {
        // Check the value of the coin and format it accordingly
        if ($value < 1000000) {
            // Anything less than a million
            $formated_str = number_format($value, $precision);
        } else if ($value < 1000000000) {
            // Anything less than a billion
            $formated_str = number_format($value / 1000000, $precision) . ' Million';
        } else {
            // At least a billion
            $formated_str = number_format($value / 1000000000, $precision) . ' Billion';
        }

        return $formated_str;
    }

    /**
     * Wrapper function around cmb2_get_option
     * @since  0.1.0
     * @param  string $key     Options array key
     * @param  mixed  $default Optional default value
     * @return mixed           Option value
     */
    public function ccpw_get_option($key = '', $default = false)
    {
        if (function_exists('cmb2_get_option')) {
            // Use cmb2_get_option as it passes through some key filters.
            return cmb2_get_option('ccpw_widget_settings', $key, $default);
        }

        // Fallback to get_option if CMB2 is not loaded yet.
        $opts = get_option('ccpw_widget_settings', $default);

        $val = $default;

        if ('all' == $key) {
            $val = $opts;
        } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
            $val = $opts[$key];
        }

        return $val;
    }

    /**
     * Retrieves coin data from the coin array list JSON file.
     *
     * @param string $coin_id The ID of the coin to retrieve data for.
     * @param bool $flip Whether to flip the array or not.
     * @return mixed Coin data if found, otherwise the provided coin ID.
     */
    protected function ccpw_coin_array($coin_id, $flip = false)
    {
        // Read the JSON file
        $json_data = file_get_contents(CCPWF_DIR . 'assets/coin-array-list.json');

        // Decode the JSON data into an associative array
        $coin_list = json_decode($json_data, true);

        // Flip the array if required
        if ($flip) {
            $coin_list = array_flip($coin_list);
        }

        // Return coin data if found, otherwise return the provided coin ID
        return isset($coin_list[$coin_id]) ? $coin_list[$coin_id] : $coin_id;
    }

}
