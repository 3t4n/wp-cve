<?php
if (!class_exists('CPTW_Shortcode')) {
    class CPTW_Shortcode
    {
        use CCPW_Helper_Functions;

        /**
         * Constructor method to initialize the plugin.
         * Registers the main plugin shortcode for the list widget.
         */
        public function __construct()
        {
            // Register main plugin shortcode for list widget
            add_shortcode('ccpw', array($this, 'ccpw_shortcode'));
            // Ajax call for datatable server processing
            add_action('wp_ajax_ccpw_get_coins_list', array($this, 'ccpw_get_ajax_data'));
            add_action('wp_ajax_nopriv_ccpw_get_coins_list', array($this, 'ccpw_get_ajax_data'));

        }

        /**
         * Custom function to handle the shortcode for cryptocurrency widgets.
         *
         * This function fetches data from various APIs and generates HTML based on the shortcode attributes.
         * It also enqueues necessary styles and scripts.
         *
         * @param array $atts Shortcode attributes.
         * @param string|null $content Shortcode content.
         * @return string HTML output for the shortcode.
         */
        public function ccpw_shortcode($atts, $content = null)
        {
            // Shortcode attributes
            $shortcode_attributes = shortcode_atts(
                array(
                    'id' => '',
                    'class' => '',
                ),
                $atts,
                'ccpw'
            );

            // Fetching necessary options from WordPress database
            $api_option = get_option("openexchange-api-settings");
            $coingecko_api_key = isset($api_option['coingecko_api']) ? $api_option['coingecko_api'] : "";
            $selected_api = get_option("ccpw_options");

            // Checking if selected API is CoinGecko and if user authentication is required
            if (isset($selected_api['select_api']) && $selected_api['select_api'] == 'coin_gecko' && !$this->ccpw_check_user()) {
                return __('Please enter Coingecko Free Api Key to get this plugin works.<br>', 'ccpw');
            } elseif (!$selected_api && !$this->ccpw_check_user()) {
                return __('Please enter Coingecko Free Api Key to get this plugin works.<br>', 'ccpw');
            }

            // Extracting post ID from shortcode attributes
            $post_id = $shortcode_attributes['id'];

            // Enqueuing necessary styles
            wp_enqueue_style('ccpw-styles', CCPWF_URL . 'assets/css/ccpw-styles.min.css', array(), CCPWF_VERSION, 'all');

            /*
             *  Return if post status is anything other than 'publish'
             */
            if (get_post_status($post_id) != 'publish') {
                return;
            }
            $preview_notice = '';

            // Including required WordPress plugin file if not already loaded
            if (!function_exists('is_plugin_active')) {
                require ABSPATH . 'wp-admin/includes/plugin.php';
            }

            // Initializing API object
            $api_obj = new CCPW_api_data();
            $api = get_option('ccpw_options');
            $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];

            // Fetching metadata from the database
            $type = get_post_meta($post_id, 'type', true);
            if ($type == 'table-widget') {
                // Update old settings for table widget
                $this->update_tbl_settings($post_id);
            }

            // Initializing variables
            $display_currencies = array();
            // Fetching data based on shortcode attributes
            $show_coins = get_post_meta($post_id, 'show-coins', true);
            $display_currencies = get_post_meta($post_id, 'display_currencies', true);
            $getData = (!empty($show_coins)) ? $show_coins : 'custom';
            $currency = get_post_meta($post_id, 'currency', true);
            $enable_formatting = get_post_meta($post_id, 'enable_formatting', true);
            $show_credit = get_post_meta($post_id, 'ccpw_coinexchangeprice_credits', true);
            $api_by = ($api == 'coin_paprika') ? 'Coinpaprika' : 'CoinGecko';
            $credit_html = '<div class="ccpw-credits"><a href="https://www.' . $api_by . '.com/?utm_source=crypto-widgets-plugin&utm_medium=api-credits" target="_blank" rel="nofollow">' . __('Powered by ' . $api_by . ' API', 'ccpw') . '</a></div>';
            $fiat_currency = $currency ? $currency : 'USD';
            $ticker_position = get_post_meta($post_id, 'ticker_position', true);
            $header_ticker_position = get_post_meta($post_id, 'header_ticker_position', true);
            $ticker_speed = (int) get_post_meta($post_id, 'ticker_speed', true);
            $t_speed = $ticker_speed * 1000;
            $cmc_slug = $this->get_cmc_single_page_slug();

            // Initializing output variables
            $output = '';
            $cls = '';
            $crypto_html = '';
            $display_changes = get_post_meta($post_id, 'display_changes', true);
            $back_color = get_post_meta($post_id, 'back_color', true);
            $font_color = get_post_meta($post_id, 'font_color', true);
            $custom_css = get_post_meta($post_id, 'custom_css', true);
            $id = 'ccpw-ticker' . $post_id . rand(1, 20);
            $is_cmc_enabled = get_option('cmc-dynamic-links');

            // Enqueuing necessary assets
            $this->ccpw_enqueue_assets($type, $post_id);

            // Dynamic styles initialization
            $dynamic_styles = '';
            $styles = '';
            $dynamic_styles_list = '';
            $dynamic_styles_multicurrency = '';
            $ticker_top = !empty($header_ticker_position) ? 'top:' . $header_ticker_position . 'px !important;' : 'top:0px !important;';

            $usd_conversions = array();

            // Based on shortcode type, fetching cryptocurrency data
            if ($type != 'table-widget') {
                if (!empty($getData) && is_numeric($getData)) {
                    // Fetching data from db
                    $all_coin_data = $this->ccpw_get_top_coins_data($getData);
                } else {
                    // Fetching data from db based on selected API
                    if ($api == "coin_paprika") {
                        // Additional processing for CoinPaprika API
                        if (is_array($display_currencies) && count($display_currencies) > 0) {
                            foreach ($display_currencies as $key => $value) {
                                $display_currencies[] = $this->ccpw_coin_array($value, true);
                            }
                        }
                    }

                    if (is_array($display_currencies) && count($display_currencies) > 0) {
                        // Fetching data from db for selected currencies
                        $all_coin_data = $this->ccpw_get_coins_data($display_currencies);
                    } else {
                        return $error = __('You have not selected any currencies to display', 'ccpw');
                    }
                }

                // Generating HTML based on fetched data
                if (is_array($all_coin_data) && count($all_coin_data) > 0) {
                    $selected_coins = array();
                    $usd_conversions = (array) $api_obj->ccpw_usd_conversions('all');
                    foreach ($all_coin_data as $currency) {
                        // Gather data from database
                        if ($currency != false) {
                            $coin_id = $currency['coin_id'];
                            $selected_coins[$coin_id] = $currency;

                            // Generate HTML according to the coin selection
                            if (isset($currency['coin_id']) && is_array($currency)) {
                                $crypto_html .= $this->ccpw_widget_html($currency, $api_obj, $fiat_currency, $is_cmc_enabled, $type, $display_changes, $usd_conversions, $cmc_slug);
                            }
                        }
                    }
                } else {
                    $error = __('You have not selected any currencies to display', 'ccpw');
                    return $error . '<!-- Cryptocurrency Widget ID: ' . esc_attr($post_id) . ' !-->';
                }
            }

            // Handling different types of widgets
            switch ($type) {
                case 'ticker':
                    // Handling ticker widget
                    $id = 'ccpw-ticker-widget-' . esc_attr($post_id);

                    if ($ticker_position == 'footer' || $ticker_position == 'header') {
                        $cls = 'ccpw-sticky-ticker';
                        if ($ticker_position == 'footer') {
                            $container_cls = 'ccpw-footer-ticker-fixedbar';
                        } else {
                            $container_cls = 'ccpw-header-ticker-fixedbar';
                        }
                    } else {
                        $cls = 'ccpw-ticker-cont';
                        $container_cls = '';
                    }

                    // Generating HTML for ticker widget
                    $output .= '<div style="display:none" class="ccpw-container ccpw-ticker-cont ' . esc_attr($container_cls) . '">';
                    $output .= '<div  class="tickercontainer" style="height: auto; overflow: hidden;">';
                    $output .= '<ul   data-tickerspeed="' . esc_attr($t_speed) . '" id="' . esc_attr($id) . '">';
                    $output .= $crypto_html;
                    if ($show_credit) {
                        $output .= '<li ="ccpw-ticker-credit">' . $credit_html . '</li>';
                    }
                    $output .= '</ul></div></div>';
                    break;
                // Additional cases for other widget types can be added here
                case 'price-label':
                    $id = 'ccpw-label-widget-' . esc_attr($post_id);
                    $output .= '<div id="' . esc_attr($id) . '" class="ccpw-container ccpw-price-label"><ul class="lbl-wrapper">';
                    $output .= $crypto_html;
                    $output .= '</ul></div>';
                    if ($show_credit) {
                        $output .= $credit_html;
                    }
                    break;
                case 'list-widget';
                    $id = 'ccpw-list-widget-' . esc_attr($post_id);

                    $cls = 'ccpw-widget';
                    $output .= '<div id="' . esc_attr($id) . '" class="' . esc_attr($cls) . '"><table class="ccpw_table" style="border:none!important;"><thead>
                    <th>' . esc_html__('Name', 'ccpw') . '</th>
                    <th>' . esc_html__('Price', 'ccpw') . '</th>';
                    if ($display_changes) {
                        $output .= '<th>' . esc_html__('24H (%)', 'ccpw') . '</th>';
                    }
                    $output .= '</thead><tbody>';
                    $output .= $crypto_html;
                    $output .= '</tbody></table></div>';

                    if ($show_credit) {
                        $output .= $credit_html;
                    }
                    break;
                case 'multi-currency-tab';
                    $id = 'ccpw-multicurrency-widget-' . esc_attr($post_id);

                    $usd_conversions = (array) $api_obj->ccpw_usd_conversions('all');

                    $output .= '<div class="currency_tabs" id="' . esc_attr($id) . '">
                    <ul class="multi-currency-tab">
                        <li data-currency="usd" class="active-tab">' . __('USD', 'ccpwx') . '</li>
                        <li data-currency="eur">' . __('EUR', 'ccpwx') . '</li>
                        <li data-currency="gbp">' . __('GBP', 'ccpwx') . '</li>
                        <li data-currency="aud">' . __('AUD', 'ccpwx') . '</li>
                        <li data-currency="jpy">' . __('JPY', 'ccpwx') . '</li>
                    </ul>';
                    $output .= '<div><ul class="multi-currency-tab-content">';
                    $output .= $crypto_html;
                    $output .= '</ul></div></div>';
                    if ($show_credit) {
                        $output .= $credit_html;
                    }
                    break;
                case 'table-widget';
                    $cls = 'ccpw-coinslist_wrapper';
                    $preloader_url = CCPWF_URL . 'assets/chart-loading.svg';
                    $ccpw_prev_coins = __('Previous', 'ccpw');
                    $ccpw_next_coins = __('Next', 'ccpw');
                    $coin_loading_lbl = __('Loading...', 'ccpw');
                    $ccpw_no_data = __('No Coin Found', 'ccpw');
                    $getRecords = '';
                    $id = 'ccpw-coinslist_wrapper';
                    $datatable_pagination = get_post_meta($post_id, 'pagination_for_table', true);
                    $old_settings = get_post_meta($post_id, 'display_currencies_for_table', true);
                    $r_type = 'top';
                    $c_id_arr = array();
                    // new settings top values
                    if (!empty($getData) && is_numeric($getData)) {
                        // fetch data from db
                        $getRecords = $getData;
                        $r_type = 'top';
                    } elseif ($getData == 'custom') {
                        if ($api == "coin_paprika") {
                            if (is_array($display_currencies) && count($display_currencies) > 0) {
                                foreach ($display_currencies as $key => $value) {
                                    $display_currencies[] = $this->ccpw_coin_array($value, true);
                                }
                            }
                        }
                        if (is_array($display_currencies) && count($display_currencies) > 0) {
                            $getRecords = count($display_currencies);
                            $c_id_arr = $display_currencies;
                        } else {
                            return $error = __('You have not selected any currencies to display', 'ccpw');
                        }
                        $r_type = 'custom';
                    } else {
                        $getRecords = 10;
                        $r_type = 'top';
                    }

                    if ($getRecords > $datatable_pagination) {
                        $limit = $datatable_pagination;
                    } else {
                        $limit = $getRecords;
                    }

                    $coins_list = json_encode($c_id_arr);
                    $output .= '<div id="' . esc_attr($id) . '" class="' . esc_attr($cls) . '">
                    <table id="ccpw-datatable-' . esc_attr($post_id) . '"
                    data-rtype="' . esc_attr($r_type) . '"
                    data-coin-list="' . esc_attr($coins_list) . '"
                    data-currency-type="' . esc_attr($fiat_currency) . '"
                    data-next-coins="' . esc_attr($ccpw_next_coins) . '"
                    data-loadinglbl="' . esc_attr($coin_loading_lbl) . '"
                    data-prev-coins="' . esc_attr($ccpw_prev_coins) . '"
                    data-dynamic-link="' . esc_attr($is_cmc_enabled) . '"
                    data-currency-slug="' . esc_url(home_url($cmc_slug)) . '"
                    data-required-currencies="' . esc_attr($getRecords) . '"
                    data-zero-records="' . esc_attr($ccpw_no_data) . '"
                    data-pagination="' . esc_attr($limit) . '"
                    data-number-formating="' . esc_attr($enable_formatting) . '"
                    data-currency-symbol="' . $this->ccpw_currency_symbol($fiat_currency) . '"
                    data-currency-rate="' . $api_obj->ccpw_usd_conversions($fiat_currency) . '"
                    class="display ccpw_table_widget table-striped table-bordered no-footer"
                    style="border:none!important;">
                    <thead data-preloader="' . esc_url($preloader_url) . '">
                    <th data-classes="desktop ccpw_coin_rank" data-index="rank">' . __('#', 'ccpw') . '</th>
                    <th data-classes="desktop ccpw_name" data-index="name">' . __('Name', 'ccpw') . '</th>
                    <th data-classes="desktop ccpw_coin_price" data-index="price">' . __('Price', 'ccpw') . '</th>
                    <th data-classes="desktop ccpw_coin_change24h" data-index="change_percentage_24h">' . __('Changes 24h', 'ccpw') . '</th>
                    <th data-classes="desktop ccpw_coin_market_cap" data-index="market_cap">' . __('Market CAP', 'ccpw') . '</th>';
                    if ($api === "coin_gecko") {

                        $output .= '<th data-classes="ccew_coin_total_volume" data-index="total_volume">' . esc_html__('Volume', 'ccpw') . '</th>';
                    }
                    $output .= '<th data-classes="ccpw_coin_supply" data-index="supply">' . __('Supply', 'ccpw') . '</th>';
                    $output .= '</tr></thead><tbody>';
                    $output .= '</tbody><tfoot></tfoot></table>';
                    if ($show_credit) {
                        $output .= $credit_html;
                    }
                    $output .= '</div>';
                    break;
            }

            // Adding dynamic CSS
            $dynamic_styles = $this->ccpw_dynamic_style($type, $post_id, $back_color, $font_color, $ticker_top);
            $ccpwcss = "<style type='text/css'>" . $dynamic_styles . $custom_css . "</style>";

            // Adding version comment for debugging
            $ccpwv = '<!-- Cryptocurrency Widgets - Version:- ' . CCPWF_VERSION . ' By Cool Plugins (CoolPlugins.net) -->';
            return $ccpwv . $output . $preview_notice . $ccpwcss;
        }

        /**
         * Generate HTML for Ticker and list widget.
         *
         * @param array $coin Coin data.
         * @param object $api_obj API object.
         * @param string $fiat_currency Fiat currency.
         * @param bool $is_cmc_enabled If CoinMarketCap is enabled.
         * @param string $type Widget type.
         * @param bool $display_changes If changes should be displayed.
         * @return string Generated HTML.
         */
        protected function ccpw_widget_html($coin, $api_obj, $fiat_currency, $is_cmc_enabled, $type, $display_changes, $usd_conversions, $cmc_slug)
        {
            // Initialize variables
            $coin_html = '';
            $api_options = get_option('ccpw_options');
            $api = (!isset($api_options['select_api']) || empty($api_options['select_api'])) ? "coin_gecko" : $api_options['select_api'];
            $coin_id = '';
            $coin_name = $coin['name'];
            $coin_id = ($api == "coin_gecko") ? $coin['coin_id'] : $this->ccpw_coin_array($coin['coin_id']);
            $coin_symbol = $coin['symbol'];
            $coin_logo_html = ($this->ccpw_get_coin_logo($coin['coin_id'], $size = 32) == false) ? '<img  alt="' . esc_attr($coin_name) . '" src="' . esc_url(CCPWF_COINS_LOGO . $coin['logo']) . '">' : $this->ccpw_get_coin_logo($coin['coin_id'], $size = 32);
            $coin_slug = strtolower($coin_name);
            $coin_price = isset($coin['price']) ? $coin['price'] : $coin['price'];
            $coin_price = ($fiat_currency != 'USD') ? $api_obj->ccpw_usd_conversions(strtoupper($fiat_currency)) * $coin_price : $coin_price;
            $coin_price_html = $this->ccpw_currency_symbol($fiat_currency) . $this->ccpw_format_number($coin_price);
            $percent_change_24h = number_format($coin['percent_change_24h'], 2, '.', ',') . '%';
            $change_sign = '<i class="ccpw_icon-up" aria-hidden="true"></i>';
            $change_class = 'up';
            $change_sign_minus = '-';
            $coin_link_start = '';
            $coin_link_end = '';

            // Generate coin link
            if ($is_cmc_enabled) {
                $coin_url = esc_url(home_url($cmc_slug . '/' . $coin_symbol . '/' . $coin_id . '/'));
                $coin_link_start = '<a class="cmc_links" title="' . esc_attr($coin_name) . '" href="' . esc_url($coin_url) . '">';
                $coin_link_end = '</a>';
            }

            // Determine change sign and class
            if (strpos($coin['percent_change_24h'], $change_sign_minus) !== false) {
                $change_sign = '<i class="ccpw_icon-down" aria-hidden="true"></i>';
                $change_class = 'down';
            }

            // Generate HTML based on widget type
            switch ($type) {
                case 'ticker':
                    // Generate Ticker HTML
                    $coin_html .= '<li id="' . esc_attr($coin_id) . '">';
                    $coin_html .= '<div class="coin-container">';
                    $coin_html .= $coin_link_start;
                    $coin_html .= '<span class="ccpw_icon">' . $coin_logo_html . '</span>';
                    $coin_html .= '<span class="name">' . esc_html($coin_name) . '(' . esc_html($coin_symbol) . ')</span>';
                    $coin_html .= $coin_link_end;
                    $coin_html .= '<span class="price">' . $coin_price_html . '</span>';
                    if ($display_changes) {
                        $coin_html .= '<span class="changes ' . esc_attr($change_class) . '">';
                        $coin_html .= $change_sign . $percent_change_24h;
                        $coin_html .= '</span>';
                    }
                    $coin_html .= '</div></li>';
                    break;
                case 'price-label':
                    // Generate Price Label HTML
                    $coin_html .= '<li id="' . esc_attr($coin_id) . '">';
                    $coin_html .= '<div class="coin-container">';
                    $coin_html .= $coin_link_start;
                    $coin_html .= '<span class="ccpw_icon">' . $coin_logo_html . '</span>';
                    $coin_html .= '<span class="name">' . esc_html($coin_name) . '</span>';
                    $coin_html .= $coin_link_end;
                    $coin_html .= '<span class="price">' . $coin_price_html . '</span>';
                    if ($display_changes) {
                        $coin_html .= '<span class="changes ' . esc_attr($change_class) . '">';
                        $coin_html .= $change_sign . $percent_change_24h;
                        $coin_html .= '</span>';
                    }
                    $coin_html .= '</div></li>';
                    break;
                case 'multi-currency-tab':
                    // Generate Multi-Currency Tab HTML
                    $coin_price = $coin['price'];
                    $EUR = isset($usd_conversions['EUR']) ? $usd_conversions['EUR'] : 0.928516;
                    $GBP = isset($usd_conversions['GBP']) ? $usd_conversions['GBP'] : 0.795123;
                    $AUD = isset($usd_conversions['AUD']) ? $usd_conversions['AUD'] : 1.533634;
                    $JPY = isset($usd_conversions['JPY']) ? $usd_conversions['JPY'] : 150.2420625;
                    $euro_price = $this->ccpw_currency_symbol('EUR') . $this->ccpw_format_number($coin_price * $EUR);
                    $gbp_price = $this->ccpw_currency_symbol('GBP') . $this->ccpw_format_number($coin_price * $GBP);
                    $aud_price = $this->ccpw_currency_symbol('AUD') . $this->ccpw_format_number($coin_price * $AUD);
                    $jpy_price = $this->ccpw_currency_symbol('JPY') . $this->ccpw_format_number($coin_price * $JPY);
                    $usd_price = $this->ccpw_currency_symbol('USD') . $this->ccpw_format_number($coin_price);
                    $coin_html .= '<li id="' . esc_attr($coin_id) . '">';
                    $coin_html .= '<div class="mtab-content">';
                    $coin_html .= $coin_link_start;
                    $coin_html .= '<span class="mtab_icon">' . $coin_logo_html . '</span>';
                    $coin_html .= '<span class="mtab_name">' . esc_html($coin_name) . '(' . esc_html($coin_symbol) . ')</span>';
                    $coin_html .= $coin_link_end;
                    $coin_html .= '<div class="tab-price-area"><span data-aud="' . esc_attr($aud_price) . '" data-jpy="' . esc_attr($jpy_price) . '" data-gbp="' . esc_attr($gbp_price) . '" data-eur="' . esc_attr($euro_price) . '" data-usd="' . esc_attr($usd_price) . '" class="mtab_price">' . $this->ccpw_currency_symbol('USD') . $this->ccpw_format_number($coin_price) . '</span>';
                    if ($display_changes) {
                        $coin_html .= '<span class="mtab_ ' . esc_attr($change_class) . '">';
                        $coin_html .= $change_sign . $percent_change_24h;
                        $coin_html .= '</span>';
                    }
                    $coin_html .= '</div></div></li>';
                    break;
                case 'list-widget':
                    // Generate List Widget HTML
                    $coin_html .= '<tr id="' . esc_attr($coin_id) . '">';
                    $coin_html .= '<td>';
                    $coin_html .= $coin_link_start;
                    $coin_html .= '<div class="ccpw_icon ccpw_coin_logo">' . $coin_logo_html . '</div>';
                    $coin_html .= '<div class="ccpw_coin_info">';
                    $coin_html .= '<span class="name">' . esc_html($coin_name) . '</span>';
                    $coin_html .= '<span class="coin_symbol">(' . esc_html($coin_symbol) . ')</span>';
                    $coin_html .= '</div></td><td class="price"><div class="price-value">' . $coin_price_html . '</div>';
                    $coin_html .= $coin_link_end;
                    $coin_html .= '</td>';
                    if ($display_changes) {
                        $coin_html .= '<td><span class="changes ' . esc_attr($change_class) . '">';
                        $coin_html .= $change_sign . $percent_change_24h;
                        $coin_html .= '</span></td>';
                    }
                    $coin_html .= '</tr>';
                    break;
            }
            return $coin_html;
        }

        /**
         * Function to handle AJAX request for datatable
         */
        public function ccpw_get_ajax_data()
        {
            // Verify nonce
            if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'ccpwf-tbl-widget')) {

                $response = array("draw" => 1, "recordsTotal" => 1, "recordsFiltered" => 1, "data" => [], 'error' => 'nonce_failed');

                echo json_encode($response);
                die();
            }

            // Initialize variables
            $rtype = isset($_POST['rtype']) ? esc_sql($_POST['rtype']) : 0;
            $start_point = isset($_POST['start']) ? esc_sql($_POST['start']) : 0;
            $data_length = isset($_POST['length']) ? esc_sql($_POST['length']) : 10;
            $current_page = isset($_POST['draw']) && (int) $_POST['draw'] ? esc_sql($_POST['draw']) : 1;
            $requiredCurrencies = isset($_POST['requiredCurrencies']) ? esc_sql($_POST['requiredCurrencies']) : 10;
            $fiat_currency = isset($_POST['currency']) ? esc_sql($_POST['currency']) : 'USD';
            $fiat_currency_rate = isset($_POST['currencyRate']) ? esc_sql($_POST['currencyRate']) : 1;
            $coin_no = $start_point + 1;
            $coins_list = array();
            $order_col_name = 'market_cap';
            $order_type = 'DESC';
            $DB = new ccpw_database();
            $Total_DBRecords = '1000';
            $coins_request_count = $data_length + $start_point;
            $api = get_option('ccpw_options');
            $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];
            $coinslist = isset($_POST['coinslist']) ? esc_sql($_POST['coinslist']) : array();
            $coindata = $rtype == 'top' ? $DB->get_coins(array('number' => $data_length, 'offset' => $start_point, 'orderby' => $order_col_name, 'order' => $order_type)) : $DB->get_coins(array('coin_id' => $coinslist, 'offset' => $start_point, 'number' => $data_length, 'orderby' => $order_col_name, 'order' => $order_type));

            // Process coin data
            $coin_ids = array();
            if ($coindata) {
                foreach ($coindata as $coin) {
                    $coin_ids[] = $coin->coin_id;
                }
            }

            // Initialize response array
            $response = array();
            $coins = array();
            $bitcoin_price = get_transient('ccpw_btc_price');
            $coins_list = array();

            // Process coin data
            if ($coindata) {
                foreach ($coindata as $coin) {
                    $coin = (array) $coin;
                    $coins['rank'] = $coin_no;
                    $coins['id'] = $coin['coin_id'];
                    if ($api == "coin_paprika") {
                        $coins['id'] = $this->ccpw_coin_array($coin['coin_id']);
                    }
                    $coins['logo'] = $this->ccpw_get_coin_logo($coin['coin_id'], $size = 32) == false ? '<img  alt="' . esc_attr($coin['name']) . '" src="' . CCPWF_COINS_LOGO . $coin['logo'] . '">' : $this->ccpw_get_coin_logo($coin['coin_id']);
                    $coins['symbol'] = strtoupper($coin['symbol']);
                    $coins['name'] = strtoupper($coin['name']);
                    $coins['price'] = $fiat_currency == 'USD' ? $coin['price'] : $coin['price'] * $fiat_currency_rate;
                    $coins['market_cap'] = $fiat_currency == 'USD' ? $coin['market_cap'] : $coin['market_cap'] * $fiat_currency_rate;
                    $coins['total_volume'] = $fiat_currency == 'USD' ? $coin['total_volume'] : $coin['total_volume'] * $fiat_currency_rate;
                    $coins['change_percentage_24h'] = number_format($coin['percent_change_24h'], 2, '.', '');
                    $coins['supply'] = $coin['circulating_supply'];
                    $coin_no++;
                    $coins_list[] = $coins;
                }
            }

            // Prepare response
            $response = array(
                'draw' => $current_page,
                'recordsTotal' => $Total_DBRecords,
                'recordsFiltered' => $requiredCurrencies,
                'data' => $coins_list,
            );
            // Send response
            echo json_encode($response);
            wp_die();

        }

        /**
         * Generates dynamic styles for widgets based on type, post ID, background color, and font color.
         *
         * @param string $type The type of widget.
         * @param int $post_id The ID of the post.
         * @param string $bg_color The background color.
         * @param string $fnt_color The font color.
         * @return string Dynamic styles for the widget.
         */
        public function ccpw_dynamic_style($type, $post_id, $back_color, $font_color, $ticker_top)
        {
            $dynamic_styles = "";
            $bg_color = !empty($back_color) ? 'background-color:' . $back_color . ';' : 'background-color:#fff;';
            $tbl_bg_color = !empty($back_color) ? 'background-color:' . $back_color . '!important;' : '';
            $fnt_color = !empty($font_color) ? 'color:' . $font_color . ';' : 'color:#000;';
            $tbl_fnt_color = !empty($font_color) ? 'color:' . $font_color . ';' : '';

            // Handling different types of widgets
            switch ($type) {
                case 'ticker':
                    // Handling ticker widget
                    // Adding dynamic ticker styles
                    $id = 'ccpw-ticker-widget-' . esc_attr($post_id);
                    $dynamic_styles .= '.tickercontainer #' . esc_attr($id) . '{' . esc_attr($bg_color) . '}
                .tickercontainer #' . esc_attr($id) . ' span.name,
                .tickercontainer #' . esc_attr($id) . ' .ccpw-credits a {' . esc_attr($fnt_color) . '}
                .tickercontainer #' . esc_attr($id) . ' span.coin_symbol {' . esc_attr($fnt_color) . '}
                .tickercontainer #' . esc_attr($id) . ' span.price,
                .tickercontainer .price-value{' . esc_attr($fnt_color) . '}
                .ccpw-header-ticker-fixedbar{' . esc_attr($ticker_top) . '}';
                    break;
                case 'price-label':
                    // Handling price label widget
                    $id = 'ccpw-label-widget-' . esc_attr($post_id);
                    $dynamic_styles .= '#' . esc_attr($id) . '.ccpw-price-label li a , #' . esc_attr($id) . '.ccpw-price-label li{' . esc_attr($fnt_color) . '}';
                    break;
                case 'list-widget':
                    // Handling list widget
                    $id = 'ccpw-list-widget-' . esc_attr($post_id);
                    $dynamic_styles .= '
                #' . $id . '.ccpw-widget .ccpw_table tr{' . esc_attr($bg_color) . '}
                #' . $id . '.ccpw-widget .ccpw_table tr th, #' . $id . '.ccpw-widget .ccpw_table tr td,
                #' . $id . '.ccpw-widget .ccpw_table tr td a{' . esc_attr($fnt_color) . '}';
                    break;
                case 'multi-currency-tab':
                    // Handling multi-currency tab widget
                    $id = 'ccpw-multicurrency-widget-' . esc_attr($post_id);
                    $dynamic_styles .= '.currency_tabs#' . esc_attr($id) . ',.currency_tabs#' . esc_attr($id) . ' ul.multi-currency-tab li.active-tab{' . esc_attr($bg_color) . '}
            .currency_tabs#' . esc_attr($id) . ' .mtab-content, .currency_tabs#' . esc_attr($id) . ' ul.multi-currency-tab li, .currency_tabs#' . esc_attr($id) . ' .mtab-content a{' . esc_attr($fnt_color) . '}';
                    break;
                case 'table-widget';
                    // Handling multi-currency tab widget
                    $id = 'ccpw-datatable-' . esc_attr($post_id);
                    if ($tbl_bg_color || $tbl_fnt_color) {
                        $dynamic_styles .= '.ccpw-coinslist_wrapper #' . esc_attr($id) . ' tr td,.ccpw-coinslist_wrapper #' . esc_attr($id) . ' tr th,.ccpw-coinslist_wrapper .dataTables_paginate a {' . esc_attr($tbl_bg_color) . ';' . esc_attr($tbl_fnt_color) . '}';
                    }

                    break;
            }

            return $dynamic_styles;
        }

        /**
         * Enqueue necessary assets according to the widget type.
         *
         * This function loads required scripts and stylesheets based on the widget type.
         *
         * @param string $type The type of widget.
         * @param int $post_id The ID of the post containing the widget.
         */
        public function ccpw_enqueue_assets($type, $post_id)
        {
            // Check if it's the admin panel and if the current page is not the Cryptocurrency Widgets settings page
            if (is_admin() && $this->ccpw_get_post_type_page() != 'ccpw') {
                return;
            }

            // Enqueue jQuery if not already loaded
            if (!wp_script_is('jquery', 'done')) {
                wp_enqueue_script('jquery');
            }

            // Enqueue common stylesheets
            wp_enqueue_style('ccpw-bootstrap', CCPWF_URL . 'assets/css/bootstrap.min.css', array(), CCPWF_VERSION, 'all');
            wp_enqueue_style('ccpw-custom-icons', CCPWF_URL . 'assets/css/ccpw-icons.min.css', array(), CCPWF_VERSION, 'all');
            wp_enqueue_style('ccpw-styles', CCPWF_URL . 'assets/css/ccpw-styles.min.css', array(), CCPWF_VERSION, 'all');

            // Load scripts and stylesheets based on the widget type
            switch ($type) {
                case 'ticker':
                    // For ticker widget
                    $ticker_id = 'ccpw-ticker-widget-' . esc_attr($post_id);
                    // Enqueue required scripts
                    wp_enqueue_script('ccpw_bxslider_js', CCPWF_URL . 'assets/js/ccpw-bxslider.min.js', array('jquery'), CCPWF_VERSION, true);
                    // Add inline script to initialize the ticker slider
                    wp_add_inline_script(
                        'ccpw_bxslider_js',
                        'jQuery(document).ready(function($){
				$(".ccpw-ticker-cont #' . $ticker_id . '").each(function(index){
					var tickerCon=$(this);
					var ispeed=Number(tickerCon.attr("data-tickerspeed"));
					$(this).bxSlider({
						ticker:true,
						minSlides:1,
						maxSlides:12,
						slideWidth:"auto",
						tickerHover:true,
						wrapperClass:"tickercontainer",
						speed: ispeed+ispeed,
						infiniteLoop:true
					});
				});
			});'
                    );
                    break;

                case 'multi-currency-tab':
                    // For multi-currency tab widget
                    wp_enqueue_script('ccpw_script', CCPWF_URL . 'assets/js/ccpw-script.min.js', array('jquery'), CCPWF_VERSION, true);
                    break;

                case 'table-widget':
                    // For table widget
                    // Enqueue DataTables and tableHeadFixer scripts
                    wp_enqueue_script('ccpw-datatable', CCPWF_URL . 'assets/js/jquery.dataTables.min.js', array('jquery'), CCPWF_VERSION, true);
                    wp_enqueue_script('ccpw-headFixer', CCPWF_URL . 'assets/js/tableHeadFixer.js', array('jquery'), CCPWF_VERSION, true);
                    // Enqueue custom DataTables stylesheet
                    wp_enqueue_style('ccpw-custom-datatable-style', CCPWF_URL . 'assets/css/ccpw-custom-datatable.min.css', array(), CCPWF_VERSION, 'all');
                    // Enqueue table widget script
                    wp_enqueue_script('ccpw-table-script', CCPWF_URL . 'assets/js/ccpw-table-widget.min.js', array('jquery'), CCPWF_VERSION, true);
                    // Localize script with necessary data
                    wp_localize_script(
                        'ccpw-table-script',
                        'ccpw_js_objects',
                        array(
                            'ajax_url' => admin_url('admin-ajax.php'),
                            'wp_nonce' => wp_create_nonce('ccpwf-tbl-widget'),
                        )
                    );
                    // Enqueue Numeral.js for number formatting
                    wp_enqueue_script('ccpw-numeral', CCPWF_URL . 'assets/js/numeral.min.js', array('jquery', 'ccpw-table-script'), CCPWF_VERSION, true);
                    // Enqueue tablesort script for table sorting functionality
                    wp_enqueue_script('ccpw-table-sort', CCPWF_URL . 'assets/js/tablesort.min.js', array('jquery', 'ccpw-table-script'), CCPWF_VERSION, true);
                    break;
            }
        }

    }
    new CPTW_Shortcode();

}
