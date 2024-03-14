<?php
function ccew_getData()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce(esc_sql($_POST['nonce']), 'ccew-create-widget')) {
        die('Please refresh window and check it again');
    }
    $settings = isset($_POST['settings']) ? esc_sql($_POST['settings']) : null;
    if ($settings !== null) {
        // Layout Settings
        // $settings = filter_var_array($_POST['settings'],FILTER_SANITIZE_STRING);
        $widget_type = $settings['widget_type'];
        $fiat_currency = $settings['fiat_currency'];
        $number_formating = $settings['number_formating'];
        $api = get_option('ccew-api-settings');
        $api = (!isset($api['select_api']) && empty($api['select_api'])) ? "coin_gecko" : $api['select_api'];
        if ($fiat_currency == 'USD') {
            $fiat_c_rate = 1;
        } else {
            $fiat_c_rate = ccew_usd_conversions($fiat_currency);
        }
        $content = '';

        if ($settings['widget_type'] == 'list') {
            $display_24h_changes = $settings['display_24h_changes'];
            $ccew_display_table_head = $settings['ccew_display_table_head'];
            $numberof_coins = (!empty($settings['numberof_coins'])) ? $settings['numberof_coins'] : '';
            $display_graph = $settings['display_graph'];
            $sortby = 'list';
        } elseif ($settings['widget_type'] == 'top_gainer_loser') {
            $display_24h_changes = $settings['display_24h_changes'];
            $numberof_coins = $settings['numberof_coins'];
            $sortby = $settings['sortby'];
            $display_graph = $settings['display_graph'];
        } elseif ($settings['widget_type'] == 'card') {
            $card_style = $settings['ccew_widget_style'];
            $ccew_card2_changes = $settings['ccew_card2_changes'];
            $display_24h_changes = $settings['display_24h_changes'];
            $ccew_display_chart_offset = $settings['ccew_display_chart_offset'];
            $ccew_chart_color = $settings['ccew_chart_color'];
            $ccew_chart_border_color = $settings['ccew_chart_border_color'];
            $selected_coin = $settings['selected_coin'];
            $display_high_low = $settings['display_high_low'];
            $display_1h_changes = $settings['display_1h_changes'];
            $display_7d_changes = $settings['display_7d_changes'];
            $display_30d_changes = $settings['display_30d_changes'];
            $display_rank = $settings['display_rank'];
            $display_marketcap = $settings['display_marketcap'];
            $coin_symbol_visibility = $settings['coin_symbol_visibility'];
            // Layout Settings
        } elseif ($settings['widget_type'] == 'advanced_table') {
            $current_page = isset($_POST['draw']) && (int) $_POST['draw'] ? esc_sql($_POST['draw']) : 1;
            $start_point = isset($_POST['start']) ? esc_sql($_POST['start']) : 0;
            $coin_no = $start_point + 1;
            $numberof_coins = (!empty($settings['numberof_coins'])) ? $settings['numberof_coins'] : '';
            $data_length = isset($_POST['length']) ? esc_sql($_POST['length']) : 10;
            $required_coins = $settings['required_coins'];
            $Total_DBRecords = '1000';
            $order_col_name = 'market_cap';
            $order_type = 'DESC';
        } else {
            $display_24h_changes = $settings['display_24h_changes'];
            $selected_coin = $settings['selected_coin'];
        }

        if ($widget_type == 'list' || $widget_type == 'top_gainer_loser') {
            $content .= '<div class="ccew-wrapper ccew-price-list ccew-bg ">';
            $coin_info = ccew_widget_get_list_data($numberof_coins, $sortby);
        } elseif ($widget_type == 'advanced_table') {
            $coin_info = ccew_get_table_data($data_length, $start_point, $numberof_coins, $order_col_name, $order_type);
        } else {
            $coin_data = ccew_widget_get_coin_data($selected_coin);
            $coin_info[] = ccew_objectToArray($coin_data);
        }
        // if coin data is empty
        if (isset($coin_info['empty'])) {
            $error = "<div id='ccew-error'>" . __('Please Select Coin', 'ccew') . '</div>';
            wp_send_json(
                array(
                    'status' => 'success',
                    'data' => $error,
                )
            );
        } elseif ($coin_info == null || $coin_info[0] == false) {
            $error = "<div id='ccew-error'>" . __('No Coin Data Found', 'ccew') . '</div>';
            wp_send_json(
                array(
                    'status' => 'success',
                    'data' => $error,
                )
            );

        }
        $dynamic_class_head = "";
        if ($widget_type == "list" && $ccew_display_table_head == 'yes') {
            if ($display_graph !== 'yes') {
                $dynamic_class_head = 'ccew-align';
            }
            $content .= '<div class="ccew-list-head">';
            //$content .= '<div class="ccew-list-head-logo">Logo</div>';
            $content .= '<div class="ccew-list-head-name">Name</div>';
            $content .= '<div class="ccew-list-head-price ' . esc_attr($dynamic_class_head) . '">Price</div>';
            if ($display_graph == 'yes') {
                $content .= '<div class="ccew-list-head-graph">Graph</div>';
            }
            $content .= '</div>';
        }
        foreach ($coin_info as $coin) {
            if ($widget_type == 'advanced_table') {
                $coin = (array) $coin;
            }
            $coin_name = $coin['name'];
            $coin_id = $coin['coin_id'];
            $coin_logo_html = ccew_get_coin_logo(ccew_coin_array($coin_id), $size = 32);
            $currency_symbol = ccew_currency_symbol($fiat_currency);
            $coin_price = $coin['price'] * $fiat_c_rate;
            $market_cap = $coin['market_cap'] * $fiat_c_rate;
            $volume = $coin['total_volume'];
            $supply = $coin['circulating_supply'];

            $rank = $coin['rank'];
            $symbol = $coin['symbol'];
            $change_24_h = number_format($coin['percent_change_24h'], 2, '.', '') . '%';

            $final_price = $coin_price;

            $change_1h = number_format($coin['percent_change_1h'], 2, '.', '') . '%';
            $change_24h = number_format($coin['percent_change_24h'], 2, '.', '') . '%';
            $change_30d = number_format($coin['percent_change_30d'], 2, '.', '') . '%';
            $change_7d = number_format($coin['percent_change_7d'], 2, '.', '') . '%';

            if ($widget_type == 'list' || $widget_type == 'top_gainer_loser') {
                $widget_type = 'list';
                $chartprice = $coin['7d_chart'];
                $comparechart = isset($chartprice) ? json_decode($chartprice) : '';
                $chart_first_value = isset($comparechart[0]) ? $comparechart[0] : "";
                $chart_last_value = (is_array($comparechart)) ? $comparechart[count($comparechart) - 1] : "N/A";
                if ($chart_last_value >= $chart_first_value) {
                    $stroke_color = '#67c624';
                } else {
                    $stroke_color = '#ed1414';
                }
            }

            if ($number_formating == 'on') {
                if ($coin_price > 1) {
                    $coin_price = ccew_widget_format_coin_value($coin_price);
                } else {
                    $coin_price = ccew_value_format_number($coin_price);
                }
                $volume = ccew_widget_format_coin_value($volume);
                $market_cap = ccew_widget_format_coin_value($market_cap);
                $supply = ccew_widget_format_coin_value($supply);
                $high_24h = ccew_widget_format_coin_value($coin['high_24h'] * $fiat_c_rate);
                $low_24h = ccew_widget_format_coin_value($coin['low_24h'] * $fiat_c_rate);
            } else {
                $coin_price = ccew_value_format_number($coin_price);
                $volume = ccew_value_format_number($volume);
                $market_cap = ccew_value_format_number($market_cap);
                $supply = ccew_value_format_number($supply);
                $high_24h = ccew_value_format_number($coin['high_24h'] * $fiat_c_rate);
                $low_24h = ccew_value_format_number($coin['low_24h'] * $fiat_c_rate);
            }
            if ($widget_type == 'card' && isset($card_style) && $card_style == "style-2") {
                if ($api == "coin_paprika" && empty($coin['7d_chart'])) {
                    $coin_paprika_id = ccew_coin_array($coin_id, true);

                    $updated_data = ccew_save_chart7day($coin_paprika_id);
                    $chartprice = $updated_data;

                } else {

                    $chartprice = $coin['7d_chart'];

                }

                $comparechart = json_decode($chartprice);
                $points_24 = convert_24points($comparechart);
                $change_per_24 = (float) $coin['percent_change_24h'];
                $coin_price_changes = $coin['price'] * $fiat_c_rate;
                $changes = ($coin_price_changes * $change_per_24) / 100;
                if ($number_formating == 'on') {
                    $changes = $currency_symbol . ccew_widget_format_coin_value($changes);
                } else {
                    $changes = $currency_symbol . ccew_value_format_number($changes);
                }
            }
            if ($widget_type !== 'advanced_table') {
                $price = $currency_symbol . $coin_price;
                $market_cap = $currency_symbol . $market_cap;
                $volume = $currency_symbol . $volume;
                $supply = $supply . ' ' . $symbol;
                $high_24h = $currency_symbol . $high_24h;
                $low_24h = $currency_symbol . $low_24h;
                require CCEW_DIR . 'layouts/ccew-' . $widget_type . '.php';
            }

            if ($widget_type == 'advanced_table') {
                $coins['rank'] = $coin_no;
                $coins['id'] = $coin_id;
                $coins['name'] = strtoupper($coin_name);
                $coins['symbol'] = strtoupper($symbol);
                $coins['logo'] = $coin_logo_html;
                $coins['price'] = $coin['price'] * $fiat_c_rate;
                $coins['change_percentage_24h'] = number_format($coin['percent_change_24h'], 2, '.', '');
                $coins['market_cap'] = $coin['market_cap'] * $fiat_c_rate;
                if ($api == "coin_gecko") {
                    $coins['total_volume'] = $coin['total_volume'];
                }
                $coins['supply'] = $coin['circulating_supply'];
                $coins_list[] = $coins;
                $coin_no++;
            }
        }

        if ($widget_type == 'list') {
            $content .= '</div>';
        } elseif ($widget_type == 'advanced_table') {
            $response = array(
                'draw' => $current_page,
                'recordsFiltered' => $required_coins,
                'recordsTotal' => $Total_DBRecords,
                'data' => $coins_list,
            );

            wp_send_json($response);
        }

        $response = array(
            'status' => 'success',
            'data' => $content,
        );
        wp_send_json($response);
    }
}
