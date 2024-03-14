<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once('wacs_list_table.php');
require_once('wacs_notifications.php');
require_once('wacs_functions.php');

function wacs_settings_page()
{
    $notify = new Wacs_Notifications();
    $wacs_obj = new Wacs_List_Table();
    $func = new Wacs_Functions();

    if (isset($_GET['action']) && $_GET['action'] === 'delete' && wp_verify_nonce($_GET['_wpnonce'], 'delete')) {
            $states = get_option('wacs_states');
            unset($states[$_GET['state']]);
            if(get_option('wacs')) {
                if (empty($states)) {
                    delete_option('wacs_states');
                    delete_option('wacs_country');
                } else {
                    update_option('wacs_states', $states);
                }
                $notify->wacs_success(__('State has been deleted successfully.', 'woo-add-custom-states'));
            } else {
                $notify->wacs_error(__('The free version can not change Woocommerce pre-defined states. Please consider buying the <a href="https://www.trustech.net">Premium Version</a>.', 'woo-add-custom-states'));
            }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'add' && wp_verify_nonce($_POST['_wpnonce'], 'add')) {
        if (empty($_POST['state_name'])) {
            $notify->wacs_error(__('State Name can not be empty. Please enter State Name.', 'woo-add-custom-states'));
        } else {
            $state_name_add = sanitize_text_field($_POST['state_name']);
        }
        if (empty($_POST['state_code'])) {
            $notify->wacs_error(__('State Code can not be empty. Please enter State Code.', 'woo-add-custom-states'));
        } elseif ($func->wacs_is_not_unique(sanitize_text_field(strtoupper($_POST['state_code'])))) {
            $notify->wacs_error(__('State Code already added. Please enter a unique State Code.', 'woo-add-custom-states'));
        } else {
            $state_code_add = sanitize_text_field(strtoupper($_POST['state_code']));
            if(get_option('wacs_states') && is_array(get_option('wacs_states'))) {
                $wacs_states = array_map('esc_attr', get_option('wacs_states'));
            } else {
                $wacs_states = array();
            }
            $wacs_states[$state_code_add] = $state_name_add;
            if(get_option('wacs') == true) {
                update_option('wacs_states', $wacs_states);
                update_option('wacs_country', get_option('wacs_current_country'));
            } else {
                $notify->wacs_error(__('The free version can not change Woocommerce pre-defined states. Please consider buying the <a href="https://www.trustech.net">Premium Version</a>.', 'woo-add-custom-states'));
            }
            $notify->wacs_success(__('State has been added successfully.', 'woo-add-custom-states'));
            $state_code_add = '';
            $state_name_add = '';
        }
    }

    if (isset($_POST['session'])) {
        update_option('wacs_current_country', sanitize_text_field($_POST['session']));
        $state_code_add = '';
        $state_name_add = '';
    }
    ?>
    <div class="wrap">
        <h2>Add Custom States Settings</h2>
        <br>
        <?php
        $countries = WC()->countries->get_allowed_countries();
        echo '<table class="widefat"><tr>';
        echo '<th>Target Country</th><td><select id="countries_list">';
        if(get_option('wacs_current_country') && ! empty(get_option('wacs_current_country'))) {
	        $wacs_current_country = get_option('wacs_current_country');
        } else {
            reset($countries);
            update_option('wacs_current_country', key($countries));
	        $wacs_current_country = get_option('wacs_current_country');
        }
	    foreach ($countries as $code => $name) {
            if ($wacs_current_country == $code) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            echo '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
        }
        echo '</select></td>';
        echo '<form action="?page=wacs_add_states" method="POST">';
        echo '<input type="hidden" name="action" value="add"/>';
        wp_nonce_field('add');
        echo '<th>State name to add</th><td><input type="text" name="state_name" value="' . (!empty($state_name_add) ? $state_name_add : '') . '"/></td>';
        echo '<th>State code to add</th><td><input type="text" size="5" name="state_code" value="' . (!empty($state_code_add) ? $state_code_add : '') . '"/></td>';
        echo '<td><input type="submit" class="button-primary" value="Add"/></td>';
        echo '</form>';
        echo '</tr>';
        echo '</table><form method="post">';
        $page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );
        $paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
        printf( '<input type="hidden" name="page" value="%s" />', $page );
        printf( '<input type="hidden" name="paged" value="%d" />', $paged );
        $wacs_obj->prepare_items();
        $wacs_obj->display();
        if(get_option('wacs_deleted') && get_option('wacs_deleted') == true && get_option('wacs') == true) {
            $notify->wacs_success(__('All states have been deleted successfully.', 'woo-add-custom-states'));
            delete_option('wacs_deleted');
        } elseif(get_option('wacs') == false) {
            $notify->wacs_error(__('The free version can not change Woocommerce pre-defined states. Please consider buying the <a href="https://www.trustech.net">Premium Version</a>.', 'woo-add-custom-states'));
            delete_option('wacs_deleted');
        }
        echo '</form>';
        ?>
    </div>
    <?php
}