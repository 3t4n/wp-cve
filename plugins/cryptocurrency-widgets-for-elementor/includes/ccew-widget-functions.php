<?php

/**
 * Inset data in Database
 */
function ccew_widget_coin_peprika_insert_data()
{
    $update_api_name = 'ccew-active-api';
    $data_cache_name = 'ccew-saved-coindata';
    $activate_api = get_transient($update_api_name);
    $cache = get_transient($data_cache_name);

    //$api_option = get_option("ccew-api-settings");
    $api_option = get_option('openexchange-api-settings');
    $cache_time = isset($api_option['select_cache_time']) ? (int) $api_option['select_cache_time'] : 10;

    // Avoid updating database if cache exist and same API is requested
    if ($activate_api == 'CoinPaprika' && false != $cache) {
        return;
    }

    $api_url = 'https://api.coinpaprika.com/v1/tickers';
    $request = wp_remote_get(
        $api_url,
        array(
            'timeout' => 120,
            'sslverify' => false,
        )
    );
    if (is_wp_error($request)) {
        return false; // Bail early
    }
    $body = wp_remote_retrieve_body($request);
    $coin_info = json_decode($body, true);
    $response = array();
    $coin_data = array();
    $coin_info = array_slice($coin_info, 0, 200);

    if (is_array($coin_info) && !empty($coin_info)) {
        foreach ($coin_info as $coin) {
            $response['coin_id'] = ccew_coin_array($coin['id']);
            $response['rank'] = $coin['rank'];
            $response['name'] = $coin['name'];
            $response['symbol'] = strtoupper($coin['symbol']);
            $response['price'] = ccew_set_default_if_empty($coin['quotes']['USD']['price'], 0.00);
            $response['percent_change_1h'] = ccew_set_default_if_empty($coin['quotes']['USD']['percent_change_1h']);
            $response['percent_change_24h'] = ccew_set_default_if_empty($coin['quotes']['USD']['percent_change_24h']);
            $response['percent_change_7d'] = ccew_set_default_if_empty($coin['quotes']['USD']['percent_change_7d']);
            $response['percent_change_30d'] = ccew_set_default_if_empty($coin['quotes']['USD']['percent_change_30d']);
            //   $response['high_24h'] = 'N/A';
            //  $response['low_24h'] = 'N/A';
            $response['market_cap'] = ccew_set_default_if_empty($coin['quotes']['USD']['market_cap'], 0);
            //  $response['total_volume'] = 'N/A';
            $response['total_supply'] = ccew_set_default_if_empty($coin['total_supply']);
            $response['circulating_supply'] = ccew_set_default_if_empty($coin['circulating_supply']);
            //$response['7d_chart'] = 'N/A';
            //  $response['logo'] = 'N/A';
            $response['coin_last_update'] = gmdate('Y-m-d h:i:s');
            $coin_data[] = $response;
        }

        $DB = new ccew_database();
        $DB->ccew_insert($coin_data);
        set_transient($data_cache_name, date('H:s:i'), $cache_time * MINUTE_IN_SECONDS);
        set_transient($update_api_name, 'CoinPaprika', 0);
        return true;
    }
}

/**
 * Insert data in Database using Coingecko
 */
function ccew_widget_insert_data()
{
    $update_api_name = 'ccew-active-api';
    $data_cache_name = 'ccew-saved-coindata';
    $activate_api = get_transient($update_api_name);
    $cache = get_transient($data_cache_name);

    $api_option1 = get_option('openexchange-api-settings');
    $coingecko_api_key = (isset($api_option1['coingecko_api'])) ? $api_option1['coingecko_api'] : "";
    $coingecko_api_cache_time = isset($api_option1['select_cache_time']) ? (int) $api_option1['select_cache_time'] : 10;

    if (!ccew_check_user()) {
        return;
    }

    // Avoid updating database if cache exist and same API is requested
    if ($activate_api == 'CoinGecko' && false != $cache) {
        return;
    }

    $coin_info = array();
    $api_url = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=200&page=1&sparkline=true&price_change_percentage=1h%2C24h%2C7d%2C30d&x_cg_demo_api_key=' . $coingecko_api_key;
    $request = wp_remote_get(
        $api_url,
        array(
            'timeout' => 120,
            'sslverify' => false,
        )
    );
    if (is_wp_error($request)) {
        return false; // Bail early
    }
    $body = wp_remote_retrieve_body($request);
    $coin_info = json_decode($body);
    $response = array();
    $coin_data = array();
    if (is_array($coin_info) && !empty($coin_info)) {
        ccew_track_coingecko_api_hit();
        foreach ($coin_info as $coin) {
            $response['coin_id'] = $coin->id;
            $response['rank'] = $coin->market_cap_rank;
            $response['name'] = $coin->name;
            $response['symbol'] = strtoupper($coin->symbol);
            $response['price'] = ccew_set_default_if_empty($coin->current_price, 0.00);
            $response['percent_change_1h'] = ccew_set_default_if_empty($coin->price_change_percentage_1h_in_currency);
            $response['percent_change_24h'] = ccew_set_default_if_empty($coin->price_change_percentage_24h_in_currency);
            $response['percent_change_7d'] = ccew_set_default_if_empty($coin->price_change_percentage_7d_in_currency);
            $response['percent_change_30d'] = ccew_set_default_if_empty($coin->price_change_percentage_30d_in_currency);
            $response['high_24h'] = ccew_set_default_if_empty($coin->high_24h);
            $response['low_24h'] = ccew_set_default_if_empty($coin->low_24h);
            $response['market_cap'] = ccew_set_default_if_empty($coin->market_cap, 0);
            $response['total_volume'] = ccew_set_default_if_empty($coin->total_volume);
            $response['total_supply'] = ccew_set_default_if_empty($coin->total_supply);
            $response['circulating_supply'] = ccew_set_default_if_empty($coin->circulating_supply);
            $response['7d_chart'] = json_encode($coin->sparkline_in_7d->price);
            $response['logo'] = $coin->image;
            $response['coin_last_update'] = gmdate('Y-m-d h:i:s');
            $coin_data[] = $response;
        }
        $DB = new ccew_database();
        $DB->ccew_insert($coin_data);
        set_transient($data_cache_name, date('H:s:i'), $coingecko_api_cache_time * MINUTE_IN_SECONDS);
        set_transient($update_api_name, 'CoinGecko', 0);
        return true;
    }
}

/**
 * Single coin update
 */
function ccew_single_coin_peprika_update($coin_id)
{

    $api_url = 'https://api.coinpaprika.com/v1/tickers/' . $coin_id;
    $request = wp_remote_get(
        $api_url,
        array(
            'timeout' => 120,
            'sslverify' => false,
        )
    );
    if (is_wp_error($request)) {
        return false; // Bail early
    }
    $body = wp_remote_retrieve_body($request);
    $coin_info = json_decode($body, true);
    $response = array();
    $coin_data = array();
    if (is_array($coin_info) && !empty($coin_info)) {
        $coin = $coin_info;
        $response['coin_id'] = ccew_coin_array($coin['id']);
        $response['rank'] = $coin['rank'];
        $response['name'] = $coin['name'];
        $response['symbol'] = strtoupper($coin['symbol']);
        $response['price'] = ccew_set_default_if_empty($coin['quotes']['USD']['price'], 0.00);
        $response['percent_change_1h'] = ccew_set_default_if_empty($coin['quotes']['USD']['percent_change_1h']);
        $response['percent_change_24h'] = ccew_set_default_if_empty($coin['quotes']['USD']['percent_change_24h']);
        $response['percent_change_7d'] = ccew_set_default_if_empty($coin['quotes']['USD']['percent_change_7d']);
        $response['percent_change_30d'] = ccew_set_default_if_empty($coin['quotes']['USD']['percent_change_30d']);
        //   $response['high_24h'] = 'N/A';
        //  $response['low_24h'] = 'N/A';
        $response['market_cap'] = ccew_set_default_if_empty($coin['quotes']['USD']['market_cap'], 0);
        //  $response['total_volume'] = 'N/A';
        $response['total_supply'] = ccew_set_default_if_empty($coin['total_supply']);
        $response['circulating_supply'] = ccew_set_default_if_empty($coin['circulating_supply']);
        $response['7d_chart'] = json_encode(ccew_coin_peprik_historical_data($coin_id));
        //  $response['logo'] = 'N/A';
        $response['coin_last_update'] = gmdate('Y-m-d h:i:s');
        $coin_data[] = $response;

        $DB = new ccew_database();
        $response_save = $DB->ccew_insert($coin_data);
        return ccew_coin_data_return(ccew_coin_array($coin_id));
    }
}
function ccew_save_chart7day($coin_id)
{

    $response['coin_id'] = ccew_coin_array($coin_id);

    $response['7d_chart'] = json_encode(ccew_coin_peprik_historical_data($coin_id));

    $coin_data[] = $response;

    $DB = new ccew_database();
    $response_save = $DB->ccew_insert($coin_data);
    return $response['7d_chart'];

}

/**
 * track coingecko api hits
 */
function ccew_track_coingecko_api_hit()
{
    $api_hits = get_option('cmc_coingecko_api_hits');
    if ($api_hits === false) {
        // Option doesn't exist, so initialize it with a value of 0
        add_option('cmc_coingecko_api_hits', 0);
    }
    $api_hits = (int) $api_hits + 1; // Increment the value by 1
    update_option('cmc_coingecko_api_hits', $api_hits);
}

/**
 * check user is new or old
 */

function ccew_check_user()
{

    $fresh_install = get_option('ccew-fresh-installation');

    $api_option = get_option("openexchange-api-settings");
    $coingecko_api_key = (isset($api_option['coingecko_api'])) ? $api_option['coingecko_api'] : "";
    $coingecko_api_key = ($fresh_install) ? $coingecko_api_key : 'true';
    if (!empty($coingecko_api_key)) {
        return true;
    } else {
        return false;
    }
}

function ccew_coin_peprik_historical_data($coin_id)
{

    // $transient_name = "ccew_7day_chart_" . $coin_id;
    $coin_data = "";

    // if (empty($coin_data) || $coin_data == false) {
    $seven_day = strtotime("-2 week");
    $api_url = 'https://api.coinpaprika.com/v1/tickers/' . $coin_id . '/historical?start=' . $seven_day . '&interval=1d';
    $request = wp_remote_get(
        $api_url,
        array(
            'timeout' => 120,
            'sslverify' => false,
        )
    );
    if (is_wp_error($request)) {
        return false; // Bail early
    }
    $body = wp_remote_retrieve_body($request);
    $coin_info = json_decode($body, true);
    $coin_data = array();

    if (is_array($coin_info) && !empty($coin_info)) {
        foreach ($coin_info as $coin => $value) {
            $coin_data[] = $value['price'];

        }

    }

    //  set_transient($transient_name, $coin_data, 24 * HOUR_IN_SECONDS);
    return $coin_data;

    //}

    //return $coin_data;

}

function ccew_single_coin_update($coin_id)
{
    $api_option1 = get_option('openexchange-api-settings');
    $coingecko_api_key = (isset($api_option1['coingecko_api'])) ? $api_option1['coingecko_api'] : "";

    $api_url = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids=' . $coin_id . '&order=market_cap_desc&per_page=100&page=1&sparkline=true&price_change_percentage=1h%2C24h%2C7d%2C30d&x_cg_demo_api_key=' . $coingecko_api_key;
    $request = wp_remote_get(
        $api_url,
        array(
            'timeout' => 120,
            'sslverify' => false,
        )
    );
    if (is_wp_error($request)) {
        return false; // Bail early
    }
    $body = wp_remote_retrieve_body($request);
    $coin_info = json_decode($body);
    $response = array();
    $coin_data = array();
    if (is_array($coin_info) && !empty($coin_info)) {
        ccew_track_coingecko_api_hit();
        $coin = $coin_info[0];
        $response['coin_id'] = $coin->id;
        $response['rank'] = $coin->market_cap_rank;
        $response['name'] = $coin->name;
        $response['symbol'] = strtoupper($coin->symbol);
        $response['price'] = ccew_set_default_if_empty($coin->current_price, 0.00);
        $response['percent_change_1h'] = ccew_set_default_if_empty($coin->price_change_percentage_1h_in_currency);
        $response['percent_change_24h'] = ccew_set_default_if_empty($coin->price_change_percentage_24h_in_currency);
        $response['percent_change_7d'] = ccew_set_default_if_empty($coin->price_change_percentage_7d_in_currency);
        $response['percent_change_30d'] = ccew_set_default_if_empty($coin->price_change_percentage_30d_in_currency);
        $response['high_24h'] = ccew_set_default_if_empty($coin->high_24h);
        $response['low_24h'] = ccew_set_default_if_empty($coin->low_24h);
        $response['market_cap'] = ccew_set_default_if_empty($coin->market_cap, 0);
        $response['total_volume'] = ccew_set_default_if_empty($coin->total_volume);
        $response['total_supply'] = ccew_set_default_if_empty($coin->total_supply);
        $response['circulating_supply'] = ccew_set_default_if_empty($coin->circulating_supply);
        $response['7d_chart'] = json_encode($coin->sparkline_in_7d->price);
        $response['logo'] = $coin->image;
        $response['coin_last_update'] = gmdate('Y-m-d h:i:s');
        $coin_data[] = $response;
        $DB = new ccew_database();
        $DB->ccew_insert($coin_data);
        return ccew_coin_data_return($coin_id);
    }
}

/**
 * Return coin  data for card and label widget
 */
function ccew_coin_data_return($coin_id)
{
    $DB = new ccew_database();
    $coin_info = $DB->get_coins(array('coin_id' => $coin_id));
    return $coin_info[0];
}

function convert_24points($points)
{
    $charts = array();
    $charts = array_slice($points, -24);

    return json_encode($charts);
}

/**
 * Check coin exist or not in database
 * Check last update of coin
 */
function ccew_widget_get_coin_data($coin_id)
{
    if ($coin_id === '') {
        return false;
    }
    $DB = new ccew_database();
    $coin_data_available = $DB->coin_exists_by_id($coin_id);
    if ($coin_data_available == true) {
        $updated = $DB->check_coin_latest_update($coin_id);
        if ($updated == true) {
            return ccew_coin_data_return($coin_id);
        } else {
            $api = get_option('ccew-api-settings');
            $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];
            $api_data = ($api == "coin_gecko") ? ccew_single_coin_update($coin_id) : ccew_single_coin_peprika_update(ccew_coin_array($coin_id, true));

            return $api_data;

        }
    } else {
        $api = get_option('ccew-api-settings');
        $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];
        $api_data = ($api == "coin_gecko") ? ccew_single_coin_update($coin_id) : ccew_single_coin_peprika_update(ccew_coin_array($coin_id, true));

        return $api_data;

    }
}

/**
 * Return coin data for list widget
 */

function ccew_widget_get_list_data($numberof_coins, $sortby)
{
    $cache = get_transient('ccew-saved-coindata');
    $coin_data = array();
    $data = '';
    // Updating database if cache is not available
    if (false == $cache) {
        $api = get_option('ccew-api-settings');
        $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];

        $data = ($api == "coin_gecko") ? ccew_widget_insert_data() : ccew_widget_coin_peprika_insert_data();

    }
    $DB = new ccew_database();
    if ($sortby == 'gainer') {
        $coins = $DB->get_coins(
            array(
                'number' => $numberof_coins,
                'order' => 'DESC',
                'orderby' => 'percent_change_24h',
            )
        );
        foreach ($coins as $coin) {
            $coin = ccew_objectToArray($coin);
            if ($coin['percent_change_24h'] >= 0) {
                $coin_data[] = $coin;
            }
        }
    } elseif ($sortby == 'loser') {
        $coins = $DB->get_coins(
            array(
                'number' => $numberof_coins,
                'order' => 'ASC',
                'orderby' => 'percent_change_24h',
            )
        );
        foreach ($coins as $coin) {
            $coin = ccew_objectToArray($coin);
            if ($coin['percent_change_24h'] < 0) {
                $coin_data[] = $coin;
            }
        }
    } else {
        if (is_array($numberof_coins)) {
            foreach ($numberof_coins as $coin_id) {
                $coins = $DB->get_coins(array('coin_id' => $coin_id));
                $coin = ccew_objectToArray($coins[0]);
                $coin_data[] = $coin;
            }
        } elseif (empty($numberof_coins)) {
            $coin_data['empty'] = 'empty';
        } else {
            $coins = $DB->get_coins(
                array(
                    'number' => $numberof_coins,
                    'order' => 'DESC',
                    'orderby' => 'market_cap',
                )
            );
            foreach ($coins as $coin) {
                $coin = ccew_objectToArray($coin);
                $coin_data[] = $coin;
            }
        }
    }
    if ($data === false) {
        if ($coin_data == null || $coin_info[0] == false) {
            return false;
        } else {
            return $coin_data;
        }
    } else {
        return $coin_data;
    }
}

function ccew_get_table_data($data_length, $startpoint, $numberof_coins, $order_col_name, $order_type)
{

    $cache_data = get_transient('ccew-saved-coindata');
    // Updating database if cache is not available
    if (false == $cache_data) {
        $api = get_option('ccew-api-settings');
        $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];
        $data_update = ($api == "coin_gecko") ? ccew_widget_insert_data() : ccew_widget_coin_peprika_insert_data();
    }
    $DB = new ccew_database();
    if (is_array($numberof_coins)) {
        $coin_data = $DB->get_coins(
            array(
                'coin_id' => $numberof_coins,
                'offset' => $startpoint,
                'number' => $data_length,
                'orderby' => $order_col_name,
                'order' => $order_type,
            )
        );
    } else {
        $coin_data = $DB->get_coins(
            array(
                'number' => $data_length,
                'offset' => $startpoint,
                'orderby' => $order_col_name,
                'order' => $order_type,
            )
        );
    }
    return $coin_data;
}

function ccew_changes_up_down($value)
{
    $change_class = 'up';
    $change_sign = '<i class="ccew_icon-up" aria-hidden="true"></i>';
    $change_sign_minus = '-';
    $changes_html = '';
    if (strpos($value, $change_sign_minus) !== false) {
        $change_sign = '<i class="ccew_icon-down" aria-hidden="true"></i>';
        $change_class = 'down';
    }
    $changes_html = '<span class="changes ' . esc_attr($change_class) . '">' . $change_sign . esc_html($value) . '</span>';
    return $changes_html;
}

/**
 * Check if provided $value is empty or not.
 * Return $default if $value is empty
 */
function ccew_set_default_if_empty($value, $default = 'N/A')
{
    return $value ? $value : $default;
}

/**
 * Check coin logo availbale in database or local
 * Return coin logo
 */
function ccew_get_coin_logo($coin_id, $size = 32, $HTML = true)
{
    $logo_html = '';
    //$DB = new ccew_database();
    $coin_icon = ccew_coin_list_logos_default($coin_id);
    $logo_html = '<img id="' . esc_attr($coin_id) . '" alt="' . esc_attr($coin_id) . '" src="' . esc_url($coin_icon) . '" onerror="this.src = \'https://res.cloudinary.com/pinkborder/image/upload/coinmarketcap-coolplugins/128x128/default-logo.png\';">';

    return $logo_html;
}

// currencies symbol
function ccew_currency_symbol($name)
{
    $cc = strtoupper($name);
    $currency = array(
        'USD' => '&#36;', // U.S. Dollar
        'CLP' => '&#36;', // CLP Dollar
        'SGD' => 'S&#36;', // Singapur dollar
        'AUD' => '&#36;', // Australian Dollar
        'BRL' => 'R&#36;', // Brazilian Real
        'CAD' => 'C&#36;', // Canadian Dollar
        'CZK' => 'K&#269;', // Czech Koruna
        'DKK' => 'kr', // Danish Krone
        'EUR' => '&euro;', // Euro
        'HKD' => '&#36', // Hong Kong Dollar
        'HUF' => 'Ft', // Hungarian Forint
        'ILS' => '&#x20aa;', // Israeli New Sheqel
        'INR' => '&#8377;', // Indian Rupee
        'IDR' => 'Rp', // Indian Rupee
        'KRW' => '&#8361;', // WON
        'CNY' => '&#165;', // CNY
        'JPY' => '&yen;', // Japanese Yen
        'MYR' => 'RM', // Malaysian Ringgit
        'MXN' => '&#36;', // Mexican Peso
        'NOK' => 'kr', // Norwegian Krone
        'NZD' => '&#36;', // New Zealand Dollar
        'PHP' => '&#x20b1;', // Philippine Peso
        'PLN' => '&#122;&#322;', // Polish Zloty
        'GBP' => '&pound;', // Pound Sterling
        'SEK' => 'kr', // Swedish Krona
        'CHF' => 'Fr', // Swiss Franc
        'TWD' => 'NT&#36;', // Taiwan New Dollar
        'PKR' => 'Rs', // Rs
        'THB' => '&#3647;', // Thai Baht
        'TRY' => '&#8378;', // Turkish Lira
        'ZAR' => 'R', // zar
        'RUB' => '&#8381;', // rub
    );

    if (array_key_exists($cc, $currency)) {
        return $currency[$cc];
    }
}

/**
 * Formating of coin
 */
function ccew_value_format_number($n)
{
    if (!is_numeric($n)) {
        return 'Invalid Value';
    }

    if ($n <= 0.00001 && $n > 0) {
        return $formatted = number_format($n, 8, '.', ',');
    } elseif ($n <= 0.0001 && $n > 0.00001) {
        return $formatted = number_format($n, 8, '.', ',');
    } elseif ($n <= 0.001 && $n > 0.0001) {
        return $formatted = number_format($n, 5, '.', ',');
    } elseif ($n <= 0.01 && $n > 0.001) {
        return $formatted = number_format($n, 4, '.', ',');
    } elseif ($n < 1 && $n > 0.01) {
        return $formatted = number_format($n, 4, '.', ',');
    } else {
        return $formatted = number_format($n, 2, '.', ',');
    }
}

function ccew_format_coin_value($value, $precision = 2)
{

    if ($value < 1000000) {
        // Anything less than a million
        $formated_str = number_format($value, $precision);
    } elseif ($value < 1000000000) {
        // Anything less than a billion
        $formated_str = number_format($value / 1000000, $precision) . 'M';
    } else {
        // At least a billion
        $formated_str = number_format($value / 1000000000, $precision) . 'B';
    }

    return $formated_str;
}

function ccew_widget_format_coin_value($value, $precision = 2)
{
    if (!is_numeric($value)) {
        return 'Invalid Value';
    }

    if ($value < 1000000) {
        // Anything less than a million
        $formatted_str = number_format($value, $precision);
    } elseif ($value < 1000000000) {
        // Anything less than a billion
        $formatted_str = number_format($value / 1000000, $precision) . ' Million';
    } else {
        // At least a billion
        $formatted_str = number_format($value / 1000000000, $precision) . ' Billion';
    }

    return $formatted_str;
}

/* USD conversions */
function ccew_usd_conversions($currency)
{
    // use common transient between cmc and ccpw
    $conversions = get_transient('cmc_usd_conversions');
    $conversions_option = get_option('cmc_usd_conversions');

    if (empty($conversions) || $conversions === '' || empty($conversions_option)) {
        $api_option = get_option('openexchange-api-settings');
        $api = (!empty($api_option['openexchangerate_api'])) ? $api_option['openexchangerate_api'] : '';
        $request = '';
        if (empty($api)) {
            if (!empty($conversions_option)) {
                if ($currency == 'all') {
                    return $conversions_option;
                } else {
                    if (isset($conversions_option[$currency])) {
                        return $conversions_option[$currency];
                    }
                }
            }
            return false;
        } else {
            $request = wp_remote_get(
                'https://openexchangerates.org/api/latest.json?app_id=' . $api . '',
                array(
                    'timeout' => 120,
                    'sslverify' => true,
                )
            );
        }

        if (is_wp_error($request)) {
            return false;
        }

        $currency_ids = array('USD', 'AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'INR', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SEK', 'CHF', 'TWD', 'THB', 'TRY', 'CNY', 'KRW', 'RUB', 'SGD', 'CLP', 'IDR', 'PKR', 'ZAR');
        $body = wp_remote_retrieve_body($request);
        $conversion_data = json_decode($body);

        if (isset($conversion_data->rates)) {
            $conversion_data = (array) $conversion_data->rates;
        } else {
            $conversion_data = array();
            if (!empty($conversions_option)) {
                if ($currency == 'all') {
                    return $conversions_option;
                } else {
                    if (isset($conversions_option[$currency])) {
                        return $conversions_option[$currency];
                    }
                }
            }
        }

        if (is_array($conversion_data) && count($conversion_data) > 0) {
            foreach ($conversion_data as $key => $currency_price) {
                if (in_array($key, $currency_ids)) {
                    $conversions_option[$key] = $currency_price;
                }
            }

            uksort(
                $conversions_option,
                function ($key1, $key2) use ($currency_ids) {
                    return (array_search($key1, $currency_ids) > array_search($key2, $currency_ids)) ? 1 : -1;
                }
            );

            update_option('cmc_usd_conversions', $conversions_option);
            set_transient('cmc_usd_conversions', $conversions_option, 12 * HOUR_IN_SECONDS);
        }
    }

    if ($currency == 'all') {
        return $conversions_option;
    } else {
        if (isset($conversions_option[$currency])) {
            return $conversions_option[$currency];
        }
    }
}

/**
 * List of Coin Ids
 */
function ccew_get_all_coin_ids()
{
    $DB = new ccew_database();
    $coin_data = $DB->get_coins(array('number' => '1000'));
    $coin_data = ccew_objectToArray($coin_data);
    if (is_array($coin_data) && isset($coin_data) && $coin_data != null) {

        $coins = array();
        foreach ($coin_data as $coin) {
            $coins[$coin['coin_id']] = $coin['name'];
        }
        return $coins;
    } else {
        $not['not'] = __('Coin Not Available', 'ccew');
        return $not;
    }
}
// object to array conversion
function ccew_objectToArray($d)
{
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

/*
|--------------------------------------------------------------------------
| generating coin logo URL based upon coin id
|--------------------------------------------------------------------------
 */
function ccew_coin_list_logos_default($coin_id, $size = 32)
{
    $logo_html = '';
    $coin_logo_info = array();
    $coin_svg = CCEW_DIR . '/assets/images/logos/' . $coin_id . '.svg';
    $coin_pngs = CCEW_DIR . '/assets/images/logos/' . $coin_id . '.png';
    if (file_exists($coin_svg)) {
        return $logo_path = CCEW_URL . 'assets/images/logos/' . $coin_id . '.svg';
    } else if (file_exists($coin_pngs)) {
        return $logo_path = CCEW_URL . 'assets/images/logos/' . $coin_id . '.png';
    } else {
        $api = get_option('ccew-api-settings');
        $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];
        if ($api == "coin_gecko") {
            $DB = new ccew_database();
            $coin_icon = $DB->get_coin_logo($coin_id);
            return $coin_icon;

        } else if ($api == "coin_paprika") {
            return 'https://static.coinpaprika.com/coin/' . ccew_coin_array($coin_id, true) . '/logo.png';

        }

        return $logo_path = CCEW_URL . 'assets/images/default-logo.png';

    }
}

//coins ids
function ccew_coin_array($coin_id, $flip = false)
{
    $coin_list = array(
        "btc-bitcoin" => "bitcoin",
        "eth-ethereum" => "ethereum",
        "usdt-tether" => "tether",
        "usdc-usd-coin" => "usd-coin",
        "bnb-binance-coin" => "binancecoin",
        "busd-binance-usd" => "binance-usd",
        "xrp-xrp" => "ripple",
        "ada-cardano" => "cardano",
        "sol-solana" => "solana",
        "doge-dogecoin" => "dogecoin",
        "dot-polkadot" => "polkadot",
        "shib-shiba-inu" => "shiba-inu",
        "dai-dai" => "dai",
        "steth-lido-staked-ether" => "staked-ether",
        "matic-polygon" => "matic-network",
        "trx-tron" => "tron",
        "avax-avalanche" => "avalanche-2",
        "wbtc-wrapped-bitcoin" => "wrapped-bitcoin",
        "leo-leo-token" => "leo-token",
        "etc-ethereum-classic" => "ethereum-classic",
        "okb-okb" => "okb",
        "ltc-litecoin" => "litecoin",
        "ftt-ftx-token" => "ftx-token",
        "atom-cosmos" => "cosmos",
        "link-chainlink" => "chainlink",
        "cro-cryptocom-chain" => "crypto-com-chain",
        "near-near-protocol" => "near",
        "uni-uniswap" => "uniswap",
        "xlm-stellar" => "stellar",
        "xmr-monero" => "monero",
        "bch-bitcoin-cash" => "bitcoin-cash",
        "algo-algorand" => "algorand",
        "flow-flow" => "flow",
        "xcn-chain" => "chain-2",
        "vet-vechain" => "vechain",
        "icp-internet-computer" => "internet-computer",
        "fil-filecoin" => "filecoin",
        "eos-eos" => "eos",
        "frax-frax" => "frax",
        "ape-apecoin" => "apecoin",
        "hbar-hedera-hashgraph" => "hedera-hashgraph",
        "sand-the-sandbox" => "the-sandbox",
        "mana-decentraland" => "decentraland",
        "xtz-tezos" => "tezos",
        "qnt-quant" => "quant-network",
        "axs-axie-infinity" => "axie-infinity",
        "egld-elrond" => "elrond-erd-2",
        "chz-chiliz" => "chiliz",
        "aave-new" => "aave",
        "theta-theta-token" => "theta-token",
        "lend-ethlend" => "aave",
        "tusd-trueusd" => "true-usd",
        "bsv-bitcoin-sv" => "bitcoin-cash-sv",
        "usdp-paxos-standard-token" => "paxos-standard",
        "ldo-lido-dao" => "lido-dao",
        "kcs-kucoin-token" => "kucoin-shares",
        "btt-bittorrent" => "bittorrent",
        "zec-zcash" => "zcash",
        "hbtc-huobi-btc" => "huobi-btc",
        "miota-iota" => "iota",
        "ht-huobi-token" => "huobi-token",
        "grt-the-graph" => "the-graph",
        "hnt-helium" => "helium",
        "usdd-usdd" => "usdd",
        "klay-klaytn" => "klay-token",
        "xec-ecash" => "ecash",
        "ftm-fantom" => "fantom",
        "mkr-maker" => "maker",
        "usdn-neutrino-usd" => "neutrino",
        "snx-synthetix-network-token" => "havven",
        "neo-neo" => "neo",
        "gt-gatechain-token" => "gatechain-token",
        "paxg-pax-gold" => "pax-gold",
        "rune-thorchain" => "thorchain",
        "bit-bitdao" => "bitdao",
        "ar-arweave" => "arweave",
        "zil-zilliqa" => "zilliqa",
        "cake-pancakeswap" => "pancakeswap-token",
        "dfi-defi-chain" => "defichain",
        "nexo-nexo" => "nexo",
        "bat-basic-attention-token" => "basic-attention-token",
        "amp-amp" => "amp-token",
        "dash-dash" => "dash",
        "stx-stacks" => "blockstack",
        "enj-enjin-coin" => "enjincoin",
        "waves-waves" => "waves",
        "lrc-loopring" => "loopring",
        "xaut-tether-gold" => "tether-gold",
        "kava-kava" => "kava",
        "btg-bitcoin-gold" => "bitcoin-gold",
        "gmt-gomining-token" => "stepn",
        "crv-curve-dao-token" => "curve-dao-token",
        "ksm-kusama" => "kusama",
        "xem-nem" => "nem",
        "dcr-decred" => "decred",
        "twt-trust-wallet-token" => "trust-wallet-token",
        "gno-gnosis" => "gnosis",
        "mina-mina-protocol" => "mina-protocol",
        "1inch-1inch" => "1inch",
        "gala-gala" => "gala",
        "fxs-frax-share" => "frax-share",
        "xdc-xdc-network" => "xdce-crowd-sale",
        "celo-celo" => "celo",
        "cel-celsius" => "celsius-degree-token",
        "hot-holo" => "holotoken",
        "tfuel-theta-fuel" => "theta-fuel",
        "rpl-rocket-pool" => "rocket-pool",
        "cvx-convex-finance" => "convex-finance",
        "rvn-ravencoin" => "ravencoin",
        "qtum-qtum" => "qtum",
        "rose-oasis-network" => "oasis-network",
        "comp-compoundd" => "compound-governance-token",
        "gusd-gemini-dollar" => "gemini-dollar",
        "kda-kadena" => "kadena",
        "ens-ethereum-name-service" => "ethereum-name-service",
        "iost-iost" => "iostoken",
        "iotx-iotex" => "iotex",
        "ankr-ankr-network" => "ankr",
        "srm-serum" => "serum",
        "safemoon-safemoon" => "safemoon",
        "yfi-yearnfinance" => "yearn-finance",
        "lpt-livepeer" => "livepeer",
        "zel-zelcash" => "zelcash",
        "zrx-0x" => "0x",
        "omg-omg-network" => "omisego",
        "ust-terrausd" => "terrausd",
        "one-harmony" => "harmony",
        "jst-just" => "just",
        "glm-golem" => "golem",
        "rsr-reserve-rights" => "reserve-rights-token",
        "audio-audius" => "audius",
        "luna-terra-v2" => "terra-luna-2",
        "syn-synapse" => "synapse-2",
        "ln-link" => "link",
        "op-optimism" => "optimism",
        "sfm-safemoon" => "safemoon-2",
        "icx-icon" => "icon",
        "ont-ontology" => "ontology",
        "wax-wax" => "wax",
        "bal-balancer" => "balancer",
        "sushi-sushi" => "sushi",
        "nu-nucypher" => "nucypher",
        "scrt-secret" => "secret",
        "sc-siacoin" => "siacoin",
        "hive-hive" => "hive",
        "dydx-dydx" => "dydx",
        "zen-horizen" => "zencash",
        "mc-merit-circle" => "merit-circle",
        "babydoge-baby-doge-coin" => "baby-doge-coin",
        "dag-constellation" => "constellation-labs",
        "lusd-liquity-usd" => "liquity-usd",
        "knc-kyber-network" => "kyber-network-crystal",
        "xch-chia-" => "chia",
        "alusd-alchemixusd" => "alchemix-usd",
        "uma-uma" => "uma",
        "efyt-ergo" => "ergo",
        "sxp-swipe" => "swipe",
        "ewt-energy-web-token" => "energy-web-token",
        "skl-skale" => "skale",
        "mxc-machine-xchange-coin" => "mxc",
        "woo-wootrade" => "woo-network",
        "poly-polymath" => "polymath",
        "cspr-casper-network" => "casper-network",
        "nft-apenft" => "apenft",
        "chsb-swissborg" => "swissborg",
        "ethos-ethos" => "ethos",
        "dgb-digibyte" => "digibyte",
        "elon-dogelon-mars" => "dogelon-mars",
        "slp-smooth-love-potion" => "smooth-love-potion",
        "lsk-lisk" => "lisk",
        "pla-playdapp" => "playdapp",
        "rndr-render-token" => "render-token",
        "fei-fei-protocol" => "fei-usd",
        "astr-astar" => "astar",
        "pundix-pundi-x" => "pundi-x-2",
        "fx-function-x" => "fx-coin",
        "spell-spell-token" => "spell-token",
        "cet-coinex-token" => "coinex-token",
        "ckb-nervos-network" => "nervos-network",
        "nest-nest-protocol" => "nest",
        "eurs-stasis-eurs" => "stasis-eurs",
        "raca-radio-caca" => "radio-caca",
        "ren-republic-protocol" => "republic-protocol",
        "people-constitutiondao" => "constitutiondao",
        "xno-nano" => "nano",
        "win-winklink" => "wink",
        "cvc-civic" => "civic",
        "orbs-orbs" => "orbs",
        "cfx-conflux-network" => "conflux-token",
        "med-medibloc-qrc20" => "medibloc",
        "pltc-platoncoin" => "platoncoin",
        "snt-status" => "status",
        "inj-injective-protocol" => "injective-protocol",
        "titan-titanswap" => "titanswap",
        "ardr-ardor" => "ardor",
        "nmr-numeraire" => "numeraire",
        "celr-celer-network" => "celer-network",
        "api3-api3" => "api3",
        "prom-prometeus" => "prometeus",
        "tribe-tribe" => "tribe-2",
        "coti-coti" => "coti",
        "mx-mx-token" => "mx-token",
        "tel-telcoin" => "telcoin",
        "dka-dkargo" => "dkargo",
        "btse-btse-token" => "btse-token",
        "xyo-xyo-network" => "xyo-network",
        "chr-chromia" => "chromaway",
        "bsw-biswap" => "biswap",
        "ygg-yield-guild-games" => "yield-guild-games",
        "mbox-mobox" => "mobox",
        "rlc-iexec-rlc" => "iexec-rlc",
        "trb-tellor" => "tellor",
        "bnt-bancor" => "bancor",
        "uos-ultra" => "ultra",
        "exrd-e-radix" => "e-radix",
        "powr-power-ledger" => "power-ledger",
        "sys-syscoin" => "syscoin",
        "dent-dent" => "dent",
        "steem-steem" => "steem",
        "wrx-wazirx" => "wazirx",
        "rad-radicle" => "radicle",
        "hxro-hxro" => "hxro",
        "susd-susd" => "nusd",
        "keep-keep-network" => "keep-network",
        "ogn-origin-protocol" => "origin-protocol",
        "ray-raydium" => "raydium",
        "strax-stratis" => "stratis",
        "vtho-vethor-token" => "vethor-token",
        "req-request-network" => "request-network",
        "c98-coin98" => "coin98",
        "fun-funfair" => "funfair",
        "trac-origintrail" => "origintrail",
        "rev-revain" => "revain",
        "arrr-pirate" => "pirate-chain",
        "husd-husd" => "husd",
        "xido-xido-finance" => "xido-finance",
        "storj-storj" => "storj",
        "aurora-aurora" => "aurora-near",
        "veri-veritaseum" => "veritaseum",
        "rbn-ribbon-finance" => "ribbon-finance",
        "maid-maidsafecoin" => "maidsafecoin",
        "xmon-xmon" => "xmon",
        "ufo-ufo-gaming" => "ufo-gaming",
        "mtl-metal" => "metal",
        "stpt-stpt" => "stp-network",
        "cdt-blox" => "blox",
        "tlm-alien-worlds" => "alien-worlds",
        "reef-reef" => "reef",
        "ctc-creditcoin" => "creditcoin-2",
        "ads-adshares" => "adshares",
        "mdx-mdex" => "mdex",
        "qkc-quarkchain" => "quark-chain",
        "ark-ark" => "ark",
        "stormx-stormx" => "storm",
        "sfund-seedifyfund" => "seedify-fund",
        "renbtc-renbtc" => "renbtc",
        "xvs-venus" => "venus",
        "ocean-ocean-protocol" => "ocean-protocol",
        "ach-alchemy-pay" => "alchemy-pay",
        "movr-moonriver" => "moonriver",
        "elf-aelf" => "aelf",
        "nkn-nkn" => "nkn",
        "klv-klever" => "klever",
        "iq-everipedia" => "everipedia",
        "meta-metadium" => "metadium",
        "strk-strike" => "strike",
        "ant-aragon" => "aragon",
        "deso-decentralized-social" => "bitclout",
        "santos-santos-fc-fan-token" => "santos-fc-fan-token",
        "asd-ascendex-token" => "asd",
        "badger-badger" => "badger-dao",
        "xsgd-xsgd" => "xsgd",
        "rep-augur" => "augur",
        "fetch-ai" => "fetch-ai",
        "ilv-illuvium" => "illuvium",
        "core-cvaultfinance" => "cvault-finance",
        "akt-akash-network" => "akash-network",
        "utk-utrust" => "utrust",
        "rif-rif-token" => "rif-token",
        "tlos-telos" => "telos",
        "wmt-world-mobile-token" => "world-mobile-token",
        "mft-hifi-finance" => "mainframe",
        "tt-thunder-token" => "thunder-token",
        "cusd-celo-dollar" => "celo-dollar",
        "band-band-protocol" => "band-protocol",
        "dusk-dusk-network" => "dusk-network",
        "aergo-aergo" => "aergo",
        "ampl-ampleforth" => "ampleforth",
        "vra-verasity" => "verasity",
        "kp3r-keep3rv1" => "keep3rv1",
        "xvg-verge" => "verge",
        "pols-polkastarter" => "polkastarter",
        "ousd-origin-dollar" => "origin-dollar",
        "perp-perpetual-protocol" => "perpetual-protocol",
        "mngo-mango-markets" => "mango-markets",
        "wozx-efforce" => "wozx",
        "aleph-alephim" => "aleph",
        "dero-dero" => "dero",
        "agix-singularitynet" => "singularitynet",
        "hero-metahero" => "metahero",
        "sero-super-zero" => "super-zero",
        "divi-divi" => "divi",
        "idex-idex" => "aurora-dao",
        "wnxm-wrapped-nxm" => "wrapped-nxm",
        "hunt-hunt" => "hunt-token",
        "tomo-tomochain" => "tomochain",
        "cocos-cocos-bcx" => "cocos-bcx",
        "ava-travala" => "concierge-io",
        "etn-electroneum" => "electroneum",
        "eps-ellipsis" => "ellipsis",
        "forth-ampleforth-governance-token" => "ampleforth-governance-token",
        "xpr-proton" => "proton",
        "usdk-usdk" => "usdk",
        "pha-phala-network" => "pha",
        "rise-everrise" => "everrise",
        "jasmy-jasmycoin" => "jasmycoin",
        "pro-propy" => "propy",
        "orn-orion-protocol" => "orion-protocol",
        "cult-cult-dao" => "cult-dao",
        "cre-carry" => "carry",
        "super-superfarm" => "superfarm",
        "alpaca-alpaca-finance" => "alpaca-finance",
        "starl-starlink" => "starlink",
        "xcad-xcad-network" => "xcad-network",
        "lazio-lazio-fan-token" => "lazio-fan-token",
        "wan-wanchain" => "wanchain",
        "hydra-hydra" => "hydra",
        "ela-elastos" => "elastos",
        "aioz-aioz-network" => "aioz-network",
        "time-chronotech" => "chronobank",
        "blz-bluzelle" => "bluzelle",
        "yfii-dfimoney" => "yfii-finance",
        "kmd-komodo" => "komodo",
        "bmx-bitmart-token" => "bitmart-token",
        "alcx-alchemix" => "alchemix",
        "mln-enzyme" => "melon",
        "samo-samoyedcoin" => "samoyedcoin",
        "arpa-arpa-chain" => "arpa-chain",
        "lcx-lcx" => "lcx",
        "gas-gas" => "gas",
        "moc-mossland" => "mossland",
        "onit-onbuff" => "onbuff",
        "dnt-district0x" => "district0x",
        "aqt-alpha-quark-token" => "alpha-quark-token",
        "rfr-refereum" => "refereum",
        "ramp-ramp" => "ramp",
        "lto-lto-network" => "lto-network",
        "rei-rei-network" => "rei-network",
        "sbd-steem-dollars" => "steem-dollars",
        "hns-handshake" => "handshake",
        "dpi-defi-pulse-index" => "defipulse-index",
        "atolo-rizon" => "rizon",
        "bifi-beefyfinance" => "beefy-finance",
        "ceur-celo-euro" => "celo-euro",
        "kar-karura" => "karura",
        "fct-firmachain" => "firmachain",
        "qrdo-qredo" => "qredo",
        "pre-presearch" => "presearch",
        "noia-syntropy" => "noia-network",
        "dia-dia" => "dia-data",
        "soul-phantasma" => "phantasma",
        "quick-quickswap" => "quick",
        "lever-leverfi" => "lever",
        "bcd-bitcoin-diamond" => "bitcoin-diamond",
        "ae-aeternity" => "aeternity",
        "rook-rook" => "rook",
        "htr-hathor-network" => "hathor",
        "dep-deapcoin" => "deapcoin",
        "coval-circuits-of-value" => "circuits-of-value",
        "anc-anchor-protocol" => "anchor-protocol",
        "rsv-reserve" => "reserve",
        "map-map-protocol" => "marcopolo",
        "hoo-hoo-token" => "hoo-token",
        "cxo-cargox" => "cargox",
        "farm-harvest-finance" => "harvest-finance",
        "bts-bitshares" => "bitshares",
        "fio-fio-protocol" => "fio-protocol",
        "iris-irisnet" => "iris-network",
        "lit-litentry" => "litentry",
        "agld-adventure-gold" => "adventure-gold",
        "grs-groestlcoin" => "groestlcoin",
        "fox-fox-token" => "shapeshift-fox-token",
        "ubt-unibright" => "unibright",
        "mintme-com-coin" => "webchain",
        "rari-rarible" => "rarible",
        "key-selfkey" => "selfkey",
        "ern-ethernity-chain" => "ethernity-chain",
        "sps-splintershards" => "splinterlands",
        "mir-mir-coin" => "mirror-protocol",
        "aog-smartofgiving" => "smartofgiving",
        "om-mantra-dao" => "mantra-dao",
        "apm-apm-coin" => "apm-coin",
        "ctxc-cortex" => "cortex",
        "hoge-hoge-finance" => "hoge-finance",
        "firo-firo" => "zcoin",
        "cos-contentos" => "contentos",
        "qom-shiba-predator" => "shiba-predator",
        "mv-gensokishi-metaverse" => "gensokishis-metaverse",
        "nct-polyswarm" => "polyswarm",
        "solve-solve" => "solve-care",
        "aion-aion" => "aion",
        "mix-mixmarvel" => "mixmarvel",
        "wild-wilder-world" => "wilder-world",
        "chess-tranchess" => "tranchess",
        "adx-adex" => "adex",
        "nwc-newscryptoio" => "newscrypto-coin",
        "upp-sentinel-protocol" => "sentinel-protocol",
        "ali-ailink-token" => "alethea-artificial-liquid-intelligence-token",
        "gene-genopets" => "genopets",
        "kin-kin" => "kin",
        "toke-tokemak" => "tokemak",
        "stc-student-coin" => "starcoin",
        "ddx-derivadao" => "derivadao",
        "beam-beam" => "beam",
        "nuls-nuls" => "nuls",
        "prq-parsiq" => "parsiq",
        "vai-vai" => "vai",
        "hi-hi-dollar" => "hi-dollar",
        "tnb-time-new-bank" => "time-new-bank",
        "apx-apollox-token" => "apollox-2",
        "idrt-rupiah-token" => "rupiah-token",
        "axel-axel" => "axel",
        "snm-sonm" => "sonm",
        "swap-trustswap" => "trustswap",
        "mith-mithril" => "mithril",
        "ult-ultiledger" => "ultiledger",
        "mbl-moviebloc" => "moviebloc",
        "sos-opendao" => "opendao",
        "wxt-wirex-token" => "wirex",
        "mona-monacoin" => "monavale",
        "snl-sport-and-leisure" => "sport-and-leisure",
        "wtc-waltonchain" => "waltonchain",
        "troy-troy" => "troy",
        "hex-hex" => "hex",
        "mct-metacraft" => "myconstant",
        "la-latoken" => "latoken",
        "pac-paccoin" => "paccoin",
        "zb-zb" => "zb-token",
        "safe-safe" => "safe-coin-2",
        "babydoge-baby-doge-coin" => "babydoge-coin-eth",
        "asm-assemble-protocol" => "as-monaco-fan-token",
        "egg-nestree" => "waves-ducks",
        "tnt-tierion" => "tierion",
        "snn-sechain" => "sechain",
        "hyn-hyperion" => "hyperion",
        "eum-elitium" => "elitium",
        "clt-coinloan" => "coinloan",
        "orc-orbit-chain" => "orclands-metaverse",
        "ong-ong" => "somee-social-old",
        "data-streamr-datacoin" => "data-economy-index",
        "alt-alitas" => "alt-estate",
        "btcb-binance-bitcoin" => "bitcoinbrand",
        "con-conun" => "paycon-token",
        "loom-loom-network" => "loom-network-new",
        "pit-pitbull" => "pitbull",
        "best-bitpanda-ecosystem-token" => "bitpanda-ecosystem-token",
        "wbnb-wrapped-bnb" => "wbnb",
        "bnx-binaryx" => "binaryx",
        "cfg-centrifuge" => "centrifuge",
        "xym-symbol" => "symbol",
        "hedg-hedgetrade" => "hedgetrade",
        "cennz-centrality" => "centrality",
        "frts-fruits" => "fruits",
        "aht-ahatoken" => "ahatoken",
        "burger-burger-swap" => "burger-swap",
        "btrst-braintrust" => "braintrust",
        "asm-assemble-protocol" => "assemble-protocol",
        "ccxx-counosx" => "counosx",
        "mines-of-dalarnia-dar" => "mines-of-dalarnia",
        "rkn-rakon" => "rakon",
        "porto-fc-porto" => "fc-porto",
        "luna-terra" => "wrapped-terra",
        "ihc-inflation-hedging-coin" => "inflation-hedging-coin",
        "bnana-banana-token" => "banana-token",
        "btcv-bitcoin-vault" => "bitcoinv",
        "dx-dxchain-token" => "dxchain",
        "bora-bora" => "bora",
        "cbk-cobak-token" => "cobak-token",
        "msb-misbloc" => "misbloc",
        "xdag-dagger-by-xdag" => "dagger",
        "abbc-alibabacoin" => "alibabacoin",
        "solo-sologenic" => "solo-coin",
        "plc-platincoin" => "platincoin",
        "people-constitutiondao" => "constitutiondao-wormhole",
        "locus-locus-chain" => "locus-chain",
        "brg-bridge-oracle" => "bridge-oracle",
        "seele-seele" => "seele",
        "osmo-osmosis" => "osmosis",
        "asm-assemble-protocol" => "assemble-protocol",
    );

    if ($flip == true) {
        $fliped_array = array_flip($coin_list);
        return (isset($fliped_array[$coin_id])) ? $fliped_array[$coin_id] : $coin_id;
    } else {
        return (isset($coin_list[$coin_id])) ? $coin_list[$coin_id] : $coin_id;

    }

}
