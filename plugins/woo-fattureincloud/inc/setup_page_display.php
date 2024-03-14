<?php
// Don't access this directly, please

if (! defined('ABSPATH') ) {
    exit;
}
// check user permission to admin setup values

function woo_fattureincloud_setup_page_display() 
{
    if (!current_user_can('manage_woocommerce')) {
        wp_die('Unauthorized user');
    }

/**
 *
 * Get the value from menu or from search text field
 *
*/

    if (isset($_POST['woo_fattureincloud_order_id']) && wp_verify_nonce($_POST['_wpnonce'])) {

        update_option('woo_fattureincloud_order_id', $_POST['woo_fattureincloud_order_id']);

    }

    /**
     *
     * update value API UID and API KEY
     *
     */

    if (isset($_POST['wfic_id_azienda']) && wp_verify_nonce($_POST['_wpnonce'])) {
        update_option('wfic_id_azienda', sanitize_text_field($_POST['wfic_id_azienda']));
        
    }

    if (isset($_POST['wfic_nome_azienda']) && wp_verify_nonce($_POST['_wpnonce'])) {
        update_option('wfic_nome_azienda', sanitize_text_field($_POST['wfic_nome_azienda']));
        
    }

    if (isset($_POST['api_uid_fattureincloud']) && wp_verify_nonce($_POST['_wpnonce'])) {
        update_option('api_uid_fattureincloud', sanitize_text_field($_POST['api_uid_fattureincloud']));
        
    }

    if (isset($_POST['wfic_api_key_fattureincloud']) && wp_verify_nonce($_POST['_wpnonce'])) {
        update_option('wfic_api_key_fattureincloud', sanitize_text_field($_POST['wfic_api_key_fattureincloud']));

    }

    if (isset($_POST['woo-fattureincloud-anno-fatture']) && wp_verify_nonce($_POST['_wpnonce'])) {
        update_option('woo-fattureincloud-anno-fatture', sanitize_text_field($_POST['woo-fattureincloud-anno-fatture']));

    }

    if (isset($_POST['fattureincloud_auto_save'])) {
        update_option('fattureincloud_auto_save', $_POST['fattureincloud_auto_save']);

    }

    if (isset($_POST['fattureincloud_send_choice'])) {
        update_option('fattureincloud_send_choice', $_POST['fattureincloud_send_choice']);

    }

    if (isset($_POST['fattureincloud_paid'])) {
        update_option('fattureincloud_paid', sanitize_text_field($_POST['fattureincloud_paid']));
    
    }

    if (isset($_POST['update_customer_registry'])) {
        update_option('update_customer_registry', sanitize_text_field($_POST['update_customer_registry']));

    }

    if (isset($_POST['show_short_descr'])) {
        update_option('show_short_descr', sanitize_text_field($_POST['show_short_descr']));

    }

    if (isset($_POST['activate_customer_receipt'])) {
        update_option('activate_customer_receipt', sanitize_text_field($_POST['activate_customer_receipt']));

    }    


    if (isset($_POST['delete_autosave_fattureincloud'])) {
        delete_option('fattureincloud_autosent_id_fallito');
        $type = 'updated';
        $message = __( 'Segnalazione errore rimossa', 'woo-fattureincloud' );
        add_settings_error('woo-fattureincloud', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattureincloud');

    }

    if (isset($_POST['delete_autosave_fattureincloud_successo'])) {
        delete_option('fattureincloud_autosent_id_successo');
        $type = 'updated';
        $message = __( 'Segnalazione creazione automatica rimossa', 'woo-fattureincloud' );
        add_settings_error('woo-fattureincloud', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattureincloud');

    }

    if (isset($_POST['fattureincloud_partiva_codfisc'])) {
        update_option('fattureincloud_partiva_codfisc', $_POST['fattureincloud_partiva_codfisc']);
        $type = 'updated';
        $message = __( 'Valore Aggiornato', 'woo-fattureincloud' );
        add_settings_error('woo-fattureincloud', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattureincloud');

    }


    // include setup form external
    // get values from setup-file.php

    include_once plugin_dir_path(__FILE__) . '../inc/setup-file.php';

}