<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * di gestione degli scripts lato admin
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

function fatt_24_admin_scripts()
{
    $screen = get_current_screen();
    $screen_id  = $screen ? $screen->id : '';
    $scripts = array();
    $path = '/js/';
    $data = [
        'url' => admin_url('admin-ajax.php')
    ];

    /** 
     * Elenco delle schermate admin da cui posso innescare le chiamate API
     * in data 10.11.2023 è stata aggiunta 'woocommerce_page_wc-orders' per compatibilità con HPOS
     * https://woo.com/it-it/document/high-performance-order-storage/
     * https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book
     */
    $admin_pages = ['edit-shop_order','shop_order', 'woocommerce_page_wc-orders' ];

    /** Schermata elenco degli ordini o dettaglio dell'ordine  */
    if (in_array($screen_id, $admin_pages)) {
        $scripts = ['f24_pdfcmd', 'f24_download'];
        $data = [
            'download_pdf_file' => wp_create_nonce('download_pdf_file'),
            'url' => admin_url('admin-ajax.php')
        ];
    }

    /**
     * Scripts utilizzati nella schermata 'impostazioni generali' di Fattura24
     */
    if ($screen_id == 'settings_page_fatt-24-settings') {
            $path .= 'settings/';
            $scripts = [
                        'f24_settings_options',
                        'f24_send_reviews',
                        'f24_test_key',
                        'f24_default_purpose',
                        'f24_api_message',
                        'f24_download_log'
                    ];
            $data = [
                        'apiKey' => get_option('fatt-24-API-key'),
                        'messages' => fatt_24_get_ajax_messages(),
                        'source' => FATT_24_API_SOURCE,
                        'f24Info' => fatt_24_getInfo(),
                        'wp_debug' => WP_DEBUG,
                        'f24_debug' => (int) get_option('fatt-24-log-enable'),
                        'plugin_path' => fatt_24_url('/'),
                        'rating_nonce' => wp_create_nonce('rating_nonce'),
                        'logFileName' => fatt_24_getLogFileName(),
                        'download_nonce' => wp_create_nonce('fatt_24_download_nonce'),
                        'testkey_nonce' => wp_create_nonce('testkey_nonce'),
                        'url' => admin_url('admin-ajax.php')
                    ];
    }
    
    /* elenco plugin */
    if ($screen_id == 'plugins' || $screen_id == 'plugins-network') {
            $path .= 'plugins/';
            $scripts = ['f24_deactivation_script'];
            $data = [
                'url' => admin_url('admin-ajax.php'),
                'message' => __('Processing...', 'fattura24')
            ];

            // aggiungo pure lo stile personalizzato
            wp_register_style('fattura24-deactivation', fatt_24_url('/css/deactivation.css'), [], '1.0.0'); 
            wp_enqueue_style('fattura24-deactivation');
    }

    foreach ($scripts as $script) {
        $file = $path . $script . '.js';
        wp_enqueue_script($script, plugins_url($file, __FILE__), array('jquery'));
        wp_localize_script($script, 'f24_scripts_data', $data);
    }
    // mi serve aggiungere il foglio di stile anche lato admin
    fatt_24_scripts_and_styles();
}

/**
 *  Aggiunge il link 'impostazioni' nella schermata di elenco plugin
 *  Edit del 14.03.2023 il link di disattivazione viene sostituito per far sì che si possa eseguire lo script
 *  Edit del 20.03.2023 : metodo agganciato anche a network_plugin_links, cfr fattura24.php righe 129-130
 */
function fatt_24_settings_link($actions)
{
    $screen = get_current_screen();
    $screen_id  = $screen ? $screen->id : '';
    /**
     * In ambiente multisito $actions['deactivate'] esiste solo 
     * nelle impostazioni admin del network, mentre per i siti figli non è definita
     */
    if (isset($actions['deactivate'])) {
        $actions['deactivate'] = str_replace('<a', '<a class="fattura24-deactivate-link"', $actions['deactivate']);
    }

    /**  
     * Aggiungo il link rapido alle impostazioni solo nella schermata dei plugin
     * in ambiente network e nelle impostazioni del network il link NON è aggiunto
     */
    $action_links = $screen_id !== 'plugins'? array() : array(
        'settings' => '<a href="' . admin_url('options-general.php?page=fatt-24-settings') . '" aria-label="' . esc_attr__('View Fattura24 Settings', 'fattura24') . '">' . esc_html__('Settings', 'fattura24') . '</a>',
    );
    return array_merge($action_links, $actions);   
}

/**
 *  Aggiunge links nella schermata di elenco plugin, a destra di 'Visualizza i dettagli'
 */
function fatt_24_row_meta($links, $file)
{
    $plugin_basename = plugin_basename(F24_PLUGIN_FILE);
    if ($plugin_basename !== $file) {
        return $links;
    }

    $row_meta = array(
        'docs' => '<a target="_blank" href="' . esc_url(apply_filters('fattura24_docs_url', 'https://www.fattura24.com/woocommerce/introduzione/')) . '" aria-label="'. esc_attr__('View Fattura24 documentation', 'fattura24') . '">'. esc_html__('Fattura24 Docs', 'fattura24') . '</a>',
        'support' => '<a href="' . esc_url(apply_filters('fattura24_support_url', admin_url('options-general.php?page=fatt-24-support'))) . '" aria-label="'. esc_attr__('Support', 'fattura24') . '">'. esc_html__('Fattura24 Support', 'fattura24') . '</a>',
        'video_guides' => '<a target="_blank" href="' . esc_url(apply_filters('fattura24_video_guides', 'https://www.youtube.com/watch?v=svsJbyVNQmk&list=PLCvEiE9DaQULWoz7nUmiKihuMxWo4DxLb')) . '" aria-label="'. esc_attr__('View Fattura24 video guides', 'fattura24') . '">'. esc_html__('Video guides', 'fattura24') . '</a>',
        'review' => '<a target="_blank" href="' . esc_url(apply_filters('fattura24_review', 'https://wordpress.org/support/plugin/fattura24/reviews/?rate=5#new-post')) . '" aria-label="'. esc_attr__('Review Fattura24', 'fattura24') . '">'. esc_html__('Review', 'fattura24') . '</a>'
    );

    return array_merge($links, $row_meta);
}