<?php
/* 
Plugin Name: XPS Ship Integration
Description: The XPS Ship integration, a free integration for WooCommerce merchants, is the only integration that gives you all the necessary functionality for shipping
Version: 1.1.74
Author: XPS Ship
WC requires at least: 2.4.8
WC tested up to: 8.5.1
*/


include_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once('rsis-validation-utils.php');

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

global $clientInfo;
$clientInfo = array(
    'clientCode' => 'xps',
    'clientName' => 'XPS Ship',
    'clientUrl' => 'https://xpsshipper.com',
    //'clientUrl' => 'http://xpsshipper.dev14.rocksolidinternet.com',
    'logoUrl' => 'https://xpsshipper.com/ec/static/images/client/xps/xps-cover-small.png',
    'buildNumber' => 'ec', // _002
    '_sentEmail' => false
);

add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

function webshipClientHasDedicatedPlugin() {
    if ($GLOBALS['clientInfo']['clientName'] === 'XPS Ship') {
        return true;
    }
    return false;
}

register_uninstall_hook(__FILE__, 'webshipUninstallFn');

function webshipUninstallFn() {
    delete_option('last_request_received_from_webship_timestamp');
    delete_option('woocommerce_webship_api_key');
}

// http://woocommerce.shoppingcarts.rocksolidinternet.com/wp-admin/admin.php?page=wc-settings&tab=integration
function webshipWoocommerceInit() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function() {
            $woocommerceLink = esc_url("https://woocommerce.com/");
            echo <<<EOF
                <div class="error"><p><b>The {$GLOBALS['clientInfo']['clientName']} plugin requires WooCommerce to be installed and activated - Download <a href="$woocommerceLink" target="_blank">WooCommerce</a> here</b></p></div>
EOF;
        });
        
    }
    else {
        
        function do_not_copy_meta_data($orderMetaQuery, $originalOrderId, $renewalOrderId) {
            
                $orderMetaQuery .= ' AND `meta_key` NOT IN ('
                                . "'_tracking_provider', "
                                . "'_tracking_number', "
                                . "'_date_shipped', "
                                . "'_order_custtrackurl', "
                                . "'_order_custcompname', "
                                . "'_order_trackno', "
                                . "'_order_trackurl')";
            
            
            $orderMetaQuery .= " AND `meta_key` NOT IN ('_wc_shipment_tracking_items')";
            
            return $orderMetaQuery;
        }
        
        if (!class_exists('WC_Shipment_Tracking') && !class_exists('WebshipShipmentTracking')) {
            class WebshipShipmentTracking {
                public function __construct() {
                    add_action('add_meta_boxes', array($this, 'add_meta_box'));
                    
                    add_action('wp_ajax_webship_addTrackingEntry', array($this, 'http_addTrackingEntry'));
                    
                    add_action('wp_ajax_webship_deleteTrackingEntry', array($this, 'http_deleteTrackingEntry'));
                    
                    // order list view column heading
                    add_filter('manage_shop_order_posts_columns', array( $this, 'renderShipmentTrackingColumnHeaderInOrderListView'), 99);
                    
                    // order list view field contents
                    add_action('manage_shop_order_posts_custom_column', array($this, 'renderShipmentTrackingFieldInOrderListView'));
                    
                    // Customer / Order CSV Export column headers/data.
                    add_filter('wc_customer_order_csv_export_order_headers', array($this, 'addShipmentTrackingColumnHeaderToCsvExport'));
                    add_filter('wc_customer_order_csv_export_order_row', array($this, 'addShipmentTrackingFieldToCsvExport'), 10, 3);
                    
                    // Order Update Email
                    add_action('woocommerce_email_before_order_table', array($this, 'renderEmail'), 0, 3);
                    
                    // Customer "My Account" view
                    // http://woocommerce.shoppingcarts2.rocksolidinternet.com/my-account/view-order/[order id]/
                    add_action('woocommerce_view_order', array($this, 'renderTrackingInfo'));
                    
                    $woocommerceSubscriptionPluginVersion = class_exists('WC_Subscriptions') && ! empty(WC_Subscriptions::$version) ? WC_Subscriptions::$version : null;
                    
                    // Prevent data being copied to subscriptions
                    if (null !== $woocommerceSubscriptionPluginVersion && version_compare($woocommerceSubscriptionPluginVersion, '2.0.0', '>=')) {
                        add_filter('wcs_renewal_order_meta_query', 'do_not_copy_meta_data', 10, 4);
                    } 
                    else {
                        add_filter('woocommerce_subscriptions_renewal_order_meta_query', 'do_not_copy_meta_data', 10, 4);
                    }
                }
                
                public function renderTrackingInfo($orderId) {
                    $trackingEntries = $this->getTrackingEntries($orderId);
                    
                    if (count($trackingEntries) > 0) {
                        ?>
                        <h2>Tracking Information</h2>
                    
                        <table class="shop_table shop_table_responsive my_account_tracking">
                            <thead>
                                <tr>
                                    <th class="tracking-provider"><span class="nobr"><?php _e('Provider', 'webship-shipment-tracking'); ?></span></th>
                                    <th class="tracking-number"><span class="nobr"><?php _e('Tracking Number', 'webship-shipment-tracking'); ?></span></th>
                                    <th class="date-shipped"><span class="nobr"><?php _e('Date', 'webship-shipment-tracking'); ?></span></th>
                                    <th class="order-actions">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody><?php
                            foreach ($trackingEntries as $trackingEntry) {
                                ?><tr class="tracking">
                                    <td class="tracking-provider" data-title="<?php _e('Provider', 'webship-shipment-tracking'); ?>">
                                        <?php echo esc_html($this->getFormattedShippingProvider($trackingEntry['tracking_provider']) ?: $trackingEntry['custom_tracking_provider']); ?>
                                    </td>
                                    <td class="tracking-number" data-title="<?php _e( 'Tracking Number', 'webship-shipment-tracking'); ?>">
                                        <?php echo esc_html($trackingEntry['tracking_number']); ?>
                                    </td>
                                    <td class="date-shipped" data-title="<?php _e('Status', 'webship-shipment-tracking'); ?>" style="text-align:left; white-space:nowrap;">
                                        <time datetime="<?php echo date('Y-m-d', $trackingEntry['date_shipped']); ?>" title="<?php echo date('Y-m-d', $trackingEntry['date_shipped']); ?>"><?php echo date_i18n(get_option('date_format'), $trackingEntry['date_shipped']); ?></time>
                                    </td>
                                    <td class="order-actions" style="text-align: center;">
                                            <a href="<?php echo esc_url($this->getFormattedTrackingLink($trackingEntry['postcode'], $trackingEntry['tracking_provider'], $trackingEntry['tracking_number']) ?: $trackingEntry['custom_tracking_link']); ?>" target="_blank" class="button"><?php _e('Track', 'webship-shipment-tracking'); ?></a>
                                    </td>
                                </tr><?php
                            }
                            ?></tbody>
                        </table>
                        <?php
                    }
                }
                
                public function renderEmail($order, $sentToAdmin, $plainText = null) {
                    if (!$GLOBALS['clientInfo']['_sentEmail']) {
                        $orderId = is_callable(array($order, 'get_id')) ? $order->get_id() : $order->id;
                        
                        $trackingEntries = $this->getTrackingEntries($orderId);
                        
                        if (count($trackingEntries) > 0) {
                            if ($plainText) {
                                echo apply_filters('woocommerce_shipment_tracking_my_orders_title', __('TRACKING INFORMATION', 'webship-shipment-tracking'));
                            
                                echo  "\n";
                        
                                foreach ($trackingEntries as $trackingEntry) {
                                    echo esc_html($this->getFormattedShippingProvider($trackingEntry['tracking_provider']) ?: $trackingEntry['custom_tracking_provider']) . "\n";
                                    echo esc_html($trackingEntry['tracking_number']) . "\n";
                                    echo esc_url($this->getFormattedTrackingLink($trackingEntry['postcode'], $trackingEntry['tracking_provider'], $trackingEntry['tracking_number']) ?: $trackingEntry['custom_tracking_link']) . "\n\n";
                                }
                            
                                echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= \n\n";
                            } 
                            else {
                                ?>
                                    <h2><?php echo apply_filters('woocommerce_shipment_tracking_my_orders_title', __('Tracking Information', 'webship-shipment-tracking') ); ?></h2>
                                
                                    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%;" border="1">
                                
                                        <thead>
                                            <tr>
                                                <th class="tracking-provider" scope="col" class="td" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4; padding: 12px;"><?php _e('Provider', 'webship-shipment-tracking'); ?></th>
                                                <th class="tracking-number" scope="col" class="td" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4; padding: 12px;"><?php _e('Tracking Number', 'webship-shipment-tracking'); ?></th>
                                                <th class="date-shipped" scope="col" class="td" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4; padding: 12px;"><?php _e('Date', 'webship-shipment-tracking'); ?></th>
                                                <th class="order-actions" scope="col" class="td" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4; padding: 12px;">&nbsp;</th>
                                            </tr>
                                        </thead>
                                
                                        <tbody><?php
                                        foreach ($trackingEntries as $trackingEntry) {
                                            
                                            $formattedTrackingLink = $this->getFormattedTrackingLink($trackingEntry['postcode'], $trackingEntry['tracking_provider'], $trackingEntry['tracking_number']) ?: $trackingEntry['custom_tracking_link'];
                                            ?><tr class="tracking">
                                                <td class="tracking-provider" data-title="<?php _e('Provider', 'webship-shipment-tracking'); ?>" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4; padding: 12px;">
                                                    <?php echo esc_html($this->getFormattedShippingProvider($trackingEntry['tracking_provider']) ?: $trackingEntry['custom_tracking_provider']); ?>
                                                </td>
                                                <td class="tracking-number" data-title="<?php _e('Tracking Number', 'webship-shipment-tracking'); ?>" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4; padding: 12px;">
                                                    <?php echo esc_html($trackingEntry['tracking_number']); ?>
                                                </td>
                                                <td class="date-shipped" data-title="<?php _e('Status', 'webship-shipment-tracking'); ?>" style="text-align: left; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4; padding: 12px;">
                                                    <time datetime="<?php echo date('Y-m-d', intval($trackingEntry['date_shipped'])); ?>" title="<?php echo date('Y-m-d', intval($trackingEntry['date_shipped'])); ?>"><?php echo date_i18n(get_option('date_format'), $trackingEntry['date_shipped'] ); ?></time>
                                                </td>
                                                <td class="order-actions" style="text-align: center; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #737373; border: 1px solid #e4e4e4; padding: 12px;">
                                                        <a href="<?php echo esc_url($formattedTrackingLink); ?>" target="_blank"><?php _e('Track', 'webship-shipment-tracking'); ?></a>
                                                </td>
                                            </tr><?php
                                        }
                                        ?></tbody>
                                    </table>
                                    <br />
                                    <br />
                                <?php
                            }
                            
                        }
                        
                        $GLOBALS['clientInfo']['_sentEmail'] = true;
                    }
                }
                
                public function addShipmentTrackingColumnHeaderToCsvExport($headers) {
                    $headers['shipment_tracking'] = 'shipment_tracking';
                    return $headers;
                }
                        
                public function addShipmentTrackingFieldToCsvExport($orderData, $order, $csvGenerator) {
                    $orderId = is_callable(array($order, 'get_id')) ? $order->get_id() : $order->id;
                    $trackingEntries = $this->getTrackingEntries($orderId);
                    $newOrderData = array();
                    $oneRowPerItem = false;
                    $shipmentTrackingCsvOutput = '';
            
                    if (count($trackingEntries) > 0) {
                        foreach ($trackingEntries as $item) {
                            $pipe = null;
                            foreach ($item as $key => $value) {
                                if ('date_shipped' === $key) {
                                    $value = date('Y-m-d', $value);
                                }
            
                                $shipmentTrackingCsvOutput .= "$pipe$key:$value";
            
                                if (!$pipe) {
                                    $pipe = '|';
                                }
                            }
            
                            $shipmentTrackingCsvOutput .= ';';
                        }
                    }
            
                    if (version_compare(wc_customer_order_csv_export()->get_version(), '4.0.0', '<')) {
                        $oneRowPerItem = ('default_one_row_per_item' === $csvGenerator->order_format || 'legacy_one_row_per_item' === $csvGenerator->order_format);
                    } 
                    elseif (isset($csvGenerator->format_definition)) {
                        $oneRowPerItem = 'item' === $csvGenerator->format_definition['row_type'];
                    }
            
                    if ($oneRowPerItem) {
                        foreach ($orderData as $data) {
                            $newOrderData[] = array_merge( (array) $data, array('shipment_tracking' => $shipmentTrackingCsvOutput));
                        }
                    } 
                    else {
                        $newOrderData = array_merge($orderData, array('shipment_tracking' => $shipmentTrackingCsvOutput));
                    }
            
                    return $newOrderData;
                }
                
                public function renderShipmentTrackingColumnHeaderInOrderListView( $columns ) {
                    $columns['shipment_tracking'] = __('Shipment Tracking', 'webship');
                    return $columns;
                }
            
                public function renderShipmentTrackingFieldInOrderListView($column) {
                    global $post;
            
                    if ('shipment_tracking' === $column) {
                        $trackingEntries = $this->getTrackingEntries($post->ID);
    
                        if (count($trackingEntries) > 0) {
                            echo '<ul>';
                
                            foreach ($trackingEntries as $trackingEntry) {
                                $formattedTrackingLink = $this->getFormattedTrackingLink($trackingEntry['postcode'], $trackingEntry['tracking_provider'], $trackingEntry['tracking_number']) ?: $trackingEntry['custom_tracking_link'];
                                printf(
                                    '<li><a href="%s" target="_blank">%s</a></li>',
                                    esc_url($formattedTrackingLink),
                                    esc_html($trackingEntry['tracking_number'])
                                );
                            }
                            echo '</ul>';
                        } 
                        else {
                            echo '–';
                        }
                    }
                }
                
                // http://woocommerce.shoppingcarts2.rocksolidinternet.com/wp-admin/post.php?post=15&action=edit
                public function add_meta_box() {
                    add_meta_box('webship-shipment-tracking', __('Shipment Tracking', 'webship-shipment-tracking'), array($this, 'meta_box'), 'shop_order', 'side', 'high');
                }
                
                public function deleteTrackingEntry( $orderId, $trackingId ) {
                    $trackingEntries = $this->getTrackingEntries($orderId);

                    $isDeleted = false;
            
                    if (count($trackingEntries) > 0 ) {
                        foreach ($trackingEntries as $key => $item) {
                            if ($item['tracking_id'] == $trackingId) {
                                unset($trackingEntries[$key]);
                                $isDeleted = true;
                                break;
                            }
                        }
                        $this->saveTrackingEntries( $orderId, $trackingEntries );
                    }
            
                    return $isDeleted;
                }
                
                public function addTrackingEntry($orderId, $params) {
                    $orderId = wc_clean($orderId);
                    $errorMessage = RsisValidationUtils::applyValidators(
                        $params,
                        array(
                            'tracking_provider' => array('type' => 'string'),
                            'custom_tracking_provider' => array('type' => 'string'),
                            'custom_tracking_link' => array('type' => 'string'),
                            'tracking_number' => array('type' => 'string'),
                            'date_shipped' => array(),
                        )
                    );
                    
                    if ($errorMessage) {
                        throw new \Exception($errorMessage);
                    }
                    
                    if (version_compare(WC_VERSION, '3.0', '<')) {
                        $postcode = get_post_meta($orderId, '_shipping_postcode', true);
                    } 
                    else {
                        $order = new WC_Order($orderId);
                        $postcode = $order->get_shipping_postcode();
                    }
            
                    if (empty($postcode)) {
                        $postcode = get_post_meta($orderId, '_shipping_postcode', true);
                    }
                    
                    $trackingEntry = array(
                        'tracking_provider' => wc_clean($params['tracking_provider']),
                        'custom_tracking_provider' => wc_clean($params['custom_tracking_provider']),
                        'custom_tracking_link' => wc_clean($params['custom_tracking_link']),
                        'tracking_number' => wc_clean($params['tracking_number'] ),
                        'date_shipped' => wc_clean(strtotime($params['date_shipped'])),
                        'postcode' => $postcode
                    );
    
                    if ($trackingEntry['custom_tracking_provider']) {
                        $trackingEntry['tracking_id'] = md5("{$trackingEntry['custom_tracking_provider']}-{$trackingEntry['tracking_number']}" . microtime());
                    } 
                    else {
                        $trackingEntry['tracking_id'] = md5("{$trackingEntry['tracking_provider']}-{$trackingEntry['tracking_number']}" . microtime());
                    }
            
                    $trackingEntries   = $this->getTrackingEntries($orderId);
                    $trackingEntries[] = $trackingEntry;
            
                    $this->saveTrackingEntries($orderId, $trackingEntries);
            
                    return $trackingEntry;
                }
                
                public function saveTrackingEntries($orderId, $trackingEntries) {
                    if (version_compare(WC_VERSION, '3.0', '<')) {
                        update_post_meta($orderId, '_wc_shipment_tracking_items', $trackingEntries);
                    } 
                    else {
                        $order = new WC_Order( $orderId );
                        $order->update_meta_data('_wc_shipment_tracking_items', $trackingEntries);
                        $order->save_meta_data();
                    }
                }
                
                public function getTrackingEntries($orderId) {
                    global $wpdb;
            
                    if (version_compare(WC_VERSION, '3.0', '<')) {
                        $trackingEntries = get_post_meta($orderId, '_wc_shipment_tracking_items', true);
                    }
                    else {
                        $order = new WC_Order($orderId);
                        $trackingEntries = $order->get_meta('_wc_shipment_tracking_items', true);
                    }

                    return $trackingEntries ?: array();
                }
                
                public function http_addTrackingEntry() {
                    check_ajax_referer('create-tracking-entry', 'security', true);
            
                    if (null !== wc_clean($_POST['tracking_number']) && strlen(wc_clean($_POST['tracking_number'])) > 0) {            
                        $trackingEntry = $this->addTrackingEntry(wc_clean($_POST['order_id']), array(
                            'tracking_provider'        => wc_clean($_POST['tracking_provider']),
                            'custom_tracking_provider' => wc_clean($_POST['custom_tracking_provider']),
                            'custom_tracking_link'     => wc_clean($_POST['custom_tracking_link']),
                            'tracking_number'          => wc_clean($_POST['tracking_number']),
                            'date_shipped'             => wc_clean($_POST['date_shipped']), // submitted like 2022-02-26
                        ));
                        
                        $this->renderSingleTrackingEntry($trackingEntry);
                    }
            
                    die();
                }
                
                public function http_deleteTrackingEntry() {
                    check_ajax_referer('delete-tracking-entry', 'security', true);

                    $orderId    = wc_clean($_POST['order_id']);
                    $trackingId = wc_clean($_POST['tracking_id']);
            
                    $this->deleteTrackingEntry($orderId, $trackingId);
                }
                
                public function renderSingleTrackingEntry($trackingEntry) {
                    $formattedTrackingLink = esc_url($this->getFormattedTrackingLink($trackingEntry['postcode'], $trackingEntry['tracking_provider'], $trackingEntry['tracking_number']) ?: $trackingEntry['custom_tracking_link']);
                    ?>
                    <div class="tracking-entry" id="tracking-entry-<?php echo esc_attr( $trackingEntry['tracking_id'] ); ?>">
                        <p class="tracking-content">
                            <strong><?php echo esc_html($this->getFormattedShippingProvider($trackingEntry['tracking_provider']) ?: $trackingEntry['custom_tracking_provider']); ?></strong>
                            <?php if (strlen($formattedTrackingLink) > 0) : ?>
                                - <?php echo sprintf('<a href="%s" target="_blank" title="' . esc_attr(__('Click here to track your shipment', 'webship-shipment-tracking')) . '">' . __('Track', 'webship-shipment-tracking') . '</a>', $formattedTrackingLink); ?>
                            <?php endif; ?>
                            <br/>
                            <em><?php echo esc_html($trackingEntry['tracking_number'] ); ?></em>
                        </p>
                        <p class="meta">
                            <?php echo esc_html(sprintf(__('Shipped on %s', 'webship-shipment-tracking'), date_i18n('Y-m-d', $trackingEntry['date_shipped']))); ?>
                            <a href="#" class="delete-tracking" rel="<?php echo esc_attr($trackingEntry['tracking_id']); ?>"><?php _e('Delete', 'webship-shipment-tracking'); ?></a>
                        </p>
                    </div>
                    <?php
                }
                
                public function getFormattedShippingProvider($trackingProvider) {
                    foreach ($this->getShippingProviders() as $country => $shippingProviderGroup) {
                        foreach ($shippingProviderGroup as $formattedCarrierName => $formattedLink) {
                            if (sanitize_title($formattedCarrierName) === sanitize_title($trackingProvider)) {
                                return $formattedCarrierName;
                            }
                        }
                    }
                    
                    return $trackingProvider;
                }
                
                public function getFormattedTrackingLink($postcode, $trackingProvider, $trackingNumber) {
                    foreach ($this->getShippingProviders() as $country => $shippingProviderGroup) {
                        foreach ($shippingProviderGroup as $formattedCarrierName => $formattedLink) {
                            if (sanitize_title($formattedCarrierName) === sanitize_title($trackingProvider)) {
                                return sprintf($formattedLink, $trackingNumber, urlencode($postcode));
                            }
                        }
                    }
                }
                
                public function getShippingProviders() {
                    return array(
                        'Australia' => array(
                            'Australia Post'   => 'http://auspost.com.au/track/track.html?id=%1$s',
                            'Fastway Couriers' => 'http://www.fastway.com.au/courier-services/track-your-parcel?l=%1$s',
                        ),
                        'Austria' => array(
                            'post.at' => 'http://www.post.at/sendungsverfolgung.php?pnum1=%1$s',
                            'dhl.at'  => 'http://www.dhl.at/content/at/de/express/sendungsverfolgung.html?brand=DHL&AWB=%1$s',
                            'DPD.at'  => 'https://tracking.dpd.de/parcelstatus?locale=de_AT&query=%1$s',
                        ),
                        'Brazil' => array(
                            'Correios' => 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=%1$s',
                        ),
                        'Belgium' => array(
                            'bpost' => 'http://track.bpost.be/etr/light/showSearchPage.do?oss_language=EN',
                        ),
                        'Canada' => array(
                            'Canada Post' => 'http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=%1$s',
                        ),
                        'Czech Republic' => array(
                            'PPL.cz'      => 'http://www.ppl.cz/main2.aspx?cls=Package&idSearch=%1$s',
                            'Česká pošta' => 'https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers=%1$s',
                            'DHL.cz'      => 'http://www.dhl.cz/cs/express/sledovani_zasilek.html?AWB=%1$s',
                            'DPD.cz'      => 'https://tracking.dpd.de/parcelstatus?locale=cs_CZ&query=%1$s',
                        ),
                        'Finland' => array(
                            'Itella' => 'http://www.posti.fi/itemtracking/posti/search_by_shipment_id?lang=en&ShipmentId=%1$s',
                        ),
                        'France' => array(
                            'Colissimo' => 'http://www.colissimo.fr/portail_colissimo/suivre.do?language=fr_FR&colispart=%1$s',
                        ),
                        'Germany' => array(
                            'DHL Intraship (DE)' => 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s&rfn=&extendedSearch=true',
                            'Hermes'             => 'https://tracking.hermesworld.com/?TrackID=%1$s',
                            'Deutsche Post DHL'  => 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s',
                            'UPS Germany'        => 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=de_DE&InquiryNumber1=%1$s',
                            'DPD.de'             => 'https://tracking.dpd.de/parcelstatus?query=%1$s&locale=en_DE',
                        ),
                        'Ireland' => array(
                            'DPD.ie'  => 'http://www2.dpd.ie/Services/QuickTrack/tabid/222/ConsignmentID/%1$s/Default.aspx',
                            'An Post' => 'https://track.anpost.ie/TrackingResults.aspx?rtt=1&items=%1$s',
                        ),
                        'Italy' => array(
                            'BRT (Bartolini)' => 'http://as777.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm&Nspediz=%1$s',
                            'DHL Express'     => 'http://www.dhl.it/it/express/ricerca.html?AWB=%1$s&brand=DHL',
                        ),
                        'India' => array(
                            'DTDC' => 'http://www.dtdc.in/dtdcTrack/Tracking/consignInfo.asp?strCnno=%1$s',
                        ),
                        'Netherlands' => array(
                            'PostNL' => 'https://mijnpakket.postnl.nl/Claim?Barcode=%1$s&Postalcode=%2$s&Foreign=False&ShowAnonymousLayover=False&CustomerServiceClaim=False',
                            'DPD.NL' => 'http://track.dpdnl.nl/?parcelnumber=%1$s',
                        ),
                        'New Zealand' => array(
                            'Courier Post' => 'http://trackandtrace.courierpost.co.nz/Search/%1$s',
                            'NZ Post'      => 'http://www.nzpost.co.nz/tools/tracking?trackid=%1$s',
                            'Fastways'     => 'http://www.fastway.co.nz/courier-services/track-your-parcel?l=%1$s',
                            'PBT Couriers' => 'http://www.pbt.com/nick/results.cfm?ticketNo=%1$s',
                        ),
                        'South African' => array(
                            'SAPO' => 'http://sms.postoffice.co.za/TrackingParcels/Parcel.aspx?id=%1$s',
                        ),
                        'Sweden' => array(
                            'PostNord Sverige AB' => 'http://www.postnord.se/sv/verktyg/sok/Sidor/spara-brev-paket-och-pall.aspx?search=%1$s',
                            //'DHL.se'              => 'http://www.dhl.se/content/se/sv/express/godssoekning.shtml?brand=DHL&AWB=%1$s',
                            'Bring.se'            => 'http://tracking.bring.se/tracking.html?q=%1$s',
                            'UPS.se'              => 'http://wwwapps.ups.com/WebTracking/track?track=yes&loc=sv_SE&trackNums=%1$s',
                            'DB Schenker'         => 'http://privpakportal.schenker.nu/TrackAndTrace/packagesearch.aspx?packageId=%1$s',
                        ),
                        'United Kingdom' => array(
                            //'DHL'                       => 'http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=%1$s',
                            'DPD.co.uk'                 => 'http://www.dpd.co.uk/tracking/trackingSearch.do?search.searchType=0&search.parcelNumber=%1$s',
                            'InterLink'                 => 'http://www.interlinkexpress.com/apps/tracking/?reference=%1$s&postcode=%2$s#results',
                            'ParcelForce'               => 'http://www.parcelforce.com/portal/pw/track?trackNumber=%1$s',
                            'Royal Mail'                => 'https://www.royalmail.com/track-your-item/?trackNumber=%1$s',
                            'TNT Express (consignment)' => 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=CON&respLang=en&respCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&g
            enericSiteIdent=',
                            'TNT Express (reference)'   => 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=REF&respLang=en&respCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&genericSiteIdent=',
                            'UK Mail'                   => 'https://old.ukmail.com/ConsignmentStatus/ConsignmentSearchResults.aspx?SearchType=Reference&SearchString=%1$s',
                        ),
                        'United States' => array(
                            'Asendia'       => 'http://apps.asendiausa.com/tracking/packagetracking.html?pid=%1$s',
                            'Fedex'         => 'https://www.fedex.com/fedextrack/?trknbr=%1$s',
                            'FedEx Sameday' => 'https://www.fedexsameday.com/fdx_dotracking_ua.aspx?tracknum=%1$s',
                            'OnTrac'        => 'http://www.ontrac.com/trackingdetail.asp?tracking=%1$s',
                            'UPS'           => 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%1$s',
                            'USPS'          => 'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=%1$s',
                            'DHL'        => 'https://www.logistics.dhl/us-en/home/tracking/tracking-ecommerce.html?tracking-id=%1$s',
                        ),
                    );
                }
                
                public function meta_box() {
                    global $post;
            
                    $trackingEntries = $this->getTrackingEntries($post->ID);

                    echo '<div id="tracking-entries">';
            
                    if (count($trackingEntries) > 0) {
                        foreach ($trackingEntries as $trackingEntry) {
                            $this->renderSingleTrackingEntry($trackingEntry);
                        }
                    }
            
                    echo '</div>';
            
                    echo '<button class="button button-show-form" type="button">' . __('Add Tracking Number', 'webship-shipment-tracking') . '</button>';
            
                    echo '<div id="shipment-tracking-form" style="display:none;">';
                    // Providers
                    echo '<p class="form-field tracking_provider_field"><label for="tracking_provider">' . __('Provider:', 'webship-shipment-tracking') . '</label><br/><select id="tracking_provider" name="tracking_provider" class="chosen_select" style="width:100%;">';
            
                    echo '<option value="">' . __('Custom Provider', 'webship-shipment-tracking') . '</option>';
            
                    $selectedProvider = '';
            
                    if (!$selectedProvider) {
                        $selectedProvider = sanitize_title(apply_filters('woocommerce_shipment_tracking_default_provider', ''));
                    }
                    
                    $shippingProviders = $this->getShippingProviders();
            
                    foreach ($shippingProviders as $provider_group => $providers) {
                        echo '<optgroup label="' . esc_attr($provider_group) . '">';
                        foreach ($providers as $provider => $url) {
                            echo '<option value="' . esc_attr(sanitize_title($provider)) . '" ' . selected(sanitize_title($provider), $selectedProvider, true) . '>' . esc_html($provider) . '</option>';
                        }
                        echo '</optgroup>';
                    }
            
                    echo '</select> ';
            
                    woocommerce_wp_hidden_input( array(
                        'id'    => 'webship_shipment_tracking_delete_nonce',
                        'value' => wp_create_nonce('delete-tracking-entry'),
                    ));
            
                    woocommerce_wp_hidden_input( array(
                        'id'    => 'webship_shipment_tracking_create_nonce',
                        'value' => wp_create_nonce('create-tracking-entry'),
                    ));
            
                    woocommerce_wp_text_input( array(
                        'id'          => 'custom_tracking_provider',
                        'label'       => __('Provider Name:', 'webship-shipment-tracking'),
                        'placeholder' => '',
                        'description' => '',
                        'value'       => '',
                    ));
            
                    woocommerce_wp_text_input( array(
                        'id'          => 'tracking_number',
                        'label'       => __('Tracking number:', 'webship-shipment-tracking'),
                        'placeholder' => '',
                        'description' => '',
                        'value'       => '',
                    ));
            
                    woocommerce_wp_text_input( array(
                        'id'          => 'custom_tracking_link',
                        'label'       => __('Tracking link:', 'webship-shipment-tracking'),
                        'placeholder' => 'http://',
                        'description' => '',
                        'value'       => '',
                    ));
            
                    woocommerce_wp_text_input( array(
                        'id'          => 'date_shipped',
                        'label'       => __('Date shipped:', 'webship-shipment-tracking'),
                        'placeholder' => date_i18n(__('Y-m-d', 'webship-shipment-tracking'), time()),
                        'description' => '',
                        'class'       => 'date-picker-field',
                        'value'       => date_i18n(__('Y-m-d', 'webship-shipment-tracking'), current_time('timestamp')),
                    ));
            
                    echo '<button class="button button-primary button-save-form">' . __('Save Tracking', 'webship-shipment-tracking') . '</button>';
            
                    // Live preview
                    echo '<p class="preview_tracking_link">' . __('Preview:', 'webship-shipment-tracking') . ' <a href="" target="_blank">' . __('Click here to track your shipment', 'webship-shipment-tracking') . '</a></p>';
            
                    echo '</div>';
            
                    $providersJsArray = array();
            
                    foreach ($shippingProviders as $providers) {
                        foreach ($providers as $provider => $link) {
                            $providersJsArray[sanitize_title($provider)] = urlencode($link);
                        }
                    }

                    $js = "
                        jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field ').hide()
            
                        jQuery('input#custom_tracking_link, input#tracking_number, #tracking_provider').change(function() {
            
                            var tracking  = jQuery('input#tracking_number').val()
                            var provider  = jQuery('#tracking_provider').val()
                            var providers = jQuery.parseJSON('" . json_encode($providersJsArray) . "')
            
                            var postcode = jQuery('#_shipping_postcode').val() || jQuery('#_billing_postcode').val()
            
                            postcode = encodeURIComponent(postcode)
            
                            var link = ''
            
                            if (providers[provider]) {
                                link = providers[provider]
                                link = link.replace('%251%24s', tracking)
                                link = link.replace('%252%24s', postcode)
                                link = decodeURIComponent(link)
            
                                jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field').hide()
                            }
                            else {
                                jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field').show()
            
                                link = jQuery('input#custom_tracking_link').val()
                            }
            
                            if (link) {
                                jQuery('p.preview_tracking_link a').attr('href', link)
                                jQuery('p.preview_tracking_link').show()
                            } 
                            else {
                                jQuery('p.preview_tracking_link').hide()
                            }
            
                        } ).change()
                        
                        $('#webship-shipment-tracking')
                            .on('click', 'a.delete-tracking', function() {
                                var trackingId = $(this).attr('rel')
                                
                                $('#tracking-entry-' + trackingId).block({
                                    message: null,
                                    overlayCSS: {
                                        background: '#fff',
                                        opacity: 0.6
                                    }
                                })
                        
                                var data = {
                                    action:      'webship_deleteTrackingEntry',
                                    order_id:    woocommerce_admin_meta_boxes.post_id,
                                    tracking_id: trackingId,
                                    security:    $('#webship_shipment_tracking_delete_nonce').val()
                                }
                        
                                $.post(woocommerce_admin_meta_boxes.ajax_url, data, function(response) {
                                    $('#tracking-entry-' + trackingId).unblock()
                                    if (response != '-1') {
                                        $('#tracking-entry-' + trackingId).remove()
                                    }
                                })
                        
                                return false
                            })
                            .on('click', 'button.button-show-form', function() {
                                $('#shipment-tracking-form').show()
                                $('#webship-shipment-tracking button.button-show-form').hide()
                            })
                            .on('click', 'button.button-save-form', function() {
                                if (!$('input#tracking_number').val()) {
                                    return false
                                }
                    
                                $('#shipment-tracking-form').block({
                                    message: null,
                                    overlayCSS: {
                                        background: '#fff',
                                        opacity: 0.6
                                    }
                                })
                                
                                var data = {
                                    action:                   'webship_addTrackingEntry',
                                    order_id:                 woocommerce_admin_meta_boxes.post_id,
                                    tracking_provider:        $('#tracking_provider').val(),
                                    custom_tracking_provider: $('#custom_tracking_provider').val(),
                                    custom_tracking_link:     $('input#custom_tracking_link').val(),
                                    tracking_number:          $('input#tracking_number').val(),
                                    date_shipped:             $('input#date_shipped').val(),
                                    security:                 $('#webship_shipment_tracking_create_nonce').val()
                                }

                                $.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {
                                    $('#shipment-tracking-form').unblock()
                                    if (response != '-1') {
                                        $('#shipment-tracking-form').hide()
                                        $('#webship-shipment-tracking #tracking-entries').append(response)
                                        $('#webship-shipment-tracking button.button-show-form').show()
                                        $('#tracking_provider').selectedIndex = 0
                                        $('#custom_tracking_provider').val('')
                                        $('input#custom_tracking_link').val('')
                                        $('input#tracking_number').val('')
                                        $('input#date_shipped').val('')
                                        $('p.preview_tracking_link').hide()
                                    }
                                })
                    
                                return false
                            })
                    ";
            
                    if (function_exists('wc_enqueue_js')) {
                        wc_enqueue_js($js);
                    } 
                    else {
                        WC()->add_inline_js($js);
                    }
                }
    
            }
            
            $webshipShipmentTracking = new WebshipShipmentTracking();
            
            // global
            function addTrackingEntry($orderId, $params) {
                $webshipShipmentTracking = new WebshipShipmentTracking();
                
                $webshipShipmentTracking->addTrackingEntry($orderId, array(
                    'tracking_provider'        =>  $params['tracking_provider'],
                    'custom_tracking_provider' => $params['custom_tracking_provider'],
                    'custom_tracking_link'     => $params['custom_tracking_link'],
                    'tracking_number'          => $params['tracking_number'],
                    'date_shipped'             => $params['date_shipped'],
                ));
            }
        }
        
        class WC_Webship_Integration extends WC_Integration {
            public static $apiKey = null;
    
            public function __construct() {
                $this->id = 'webship';
                $this->method_title = __($GLOBALS['clientInfo']['clientName'], 'woocommerce-webship');
                $this->method_description = __("{$GLOBALS['clientInfo']['clientName']} allows you ship through numerous carriers", 'woocommerce-webship');
    
                if (!get_option('woocommerce_webship_api_key', false)) {
                    // generate an api key
                    $toHash = get_current_user_id() . date('U') . mt_rand();
                    $key = hash_hmac('md5', $toHash, wp_hash($toHash));
    
                    update_option('woocommerce_webship_api_key', $key);
                }
   
                $this->init_form_fields();
                $this->init_settings();
        
                self::$apiKey = get_option('woocommerce_webship_api_key', false);
    
                // save the api key we generated
                $this->settings['apiKey'] = self::$apiKey;
        
                add_action('woocommerce_update_options_integration_webship', array($this, 'process_admin_options'));
    
                if (webshipClientHasDedicatedPlugin()) {
                    if (!function_exists('add_external_link_admin_submenu')) {
                        function add_external_link_admin_submenu() {
                            global $submenu;
                            $permalink = admin_url('admin.php') . '?page=wc-settings&tab=integration&section=webship';
                            $submenu['woocommerce'][] = array($GLOBALS['clientInfo']['clientName'], 'manage_options', $permalink);
                        }
                        add_action('admin_menu', 'add_external_link_admin_submenu');
                    }
                    
                    $dismissedSetupNotice = get_user_meta(get_current_user_id(), 'dismissed_webship-setup_notice');
    
                    if (!$dismissedSetupNotice) {
                        add_action('admin_notices', array($this, 'setup_notice'));
                    }
                }
            }
            
            public function setup_notice() {
                $currentTab = isset($_GET['tab']) ? $_GET['tab'] : '';
                $lastRequestReceivedFromWebshipTimestamp = get_option('last_request_received_from_webship_timestamp');
                if (!empty(wc_clean($currentTab)) && 'integration' === wc_clean($currentTab) || $lastRequestReceivedFromWebshipTimestamp) {
                    return;
                }
                
                $logo = plugins_url('assets/images/webship.png', dirname(__FILE__));
                $adminUrl = admin_url('admin.php?page=wc-settings&tab=integration&section=webship');
                
                $interpolatableApiKeyString = self::$apiKey;
                
                $hideNoticeUrl = esc_url(wp_nonce_url(add_query_arg('wc-hide-notice', 'webship-setup'), 'woocommerce_hide_notices_nonce', '_wc_notice_nonce'));
                
                $wordpressUrl = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                
                $logoUrl = esc_url($GLOBALS['clientInfo']['logoUrl']);
                
                $createIntegrationUrl = esc_url("{$GLOBALS['clientInfo']['clientUrl']}/{$GLOBALS['clientInfo']['buildNumber']}/#/settings/integrations/new/woocommerce?woocommerceApiKey={$interpolatableApiKeyString}&woocommerceSite_url=$wordpressUrl");
    
                $html = <<<EOF
                    <div id="message" class="updated woocommerce-message webship-setup" style="padding:20px;">
                        <img alt="{$GLOBALS['clientInfo']['clientName']}" title="{$GLOBALS['clientInfo']['clientName']}" src="{$logoUrl}" style="width:140px" />
                        <a class="woocommerce-message-close notice-dismiss" href="{$hideNoticeUrl}">Dismiss</a>
                        <p>To start printing shipping labels with {$GLOBALS['clientInfo']['clientName']} navigate to <a class="external-link" href="$createIntegrationUrl" target="_blank">{$GLOBALS['clientInfo']['clientUrl']}</a> and log in or sign up for a new account.</p>
                        
                        <p>After logging in, configure your WooCommerce integration to initiate communication between {$GLOBALS['clientInfo']['clientName']} and WooCommerce.</p>
                        
                        <p>Once you've connected your integrations, you can begin booking shipments for those orders</p>
                    </div>
EOF;

                echo $html;
            }
            
            function admin_options() {
                $interpolatableApiKeyString = self::$apiKey;
                
                $wordpressUrl = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                
                $lastRequestReceivedFromWebshipTimestamp = get_option('last_request_received_from_webship_timestamp');
                $successfulConnectionHtml = '';
                if ($lastRequestReceivedFromWebshipTimestamp) {
                    $readableDate = date('F j, Y, g:i a', $lastRequestReceivedFromWebshipTimestamp);
                    $successfulConnectionHtml = <<<EOF
                        <div id="connected-message" class="notice updated" style="padding-bottom: 20px; padding-left: 20px;">
                            <h2><span class="dashicons dashicons-yes"></span> Connection Successful</h2>
                            {$GLOBALS['clientInfo']['clientName']} was able to successfully retrieve your WooCommerce orders on $readableDate
                        </div>
EOF;
                }
                
                echo <<<EOF
                    $successfulConnectionHtml
                    
                    <h2>{$GLOBALS['clientInfo']['clientName']} Plugin</h2>
    
                    <table class="form-table">
EOF;
                $this->generate_settings_html();
                
                $permalink = esc_url(admin_url('admin.php') . '?page=wc-settings&tab=shipping&section=webship');
                
                $buttonClass = $lastRequestReceivedFromWebshipTimestamp ? '' : 'button-primary';
                $buttonText = $lastRequestReceivedFromWebshipTimestamp ? 'Reconnect' : 'Connect';
                $goToWebshipHtml = '';
                if (webshipClientHasDedicatedPlugin()) {
                    if ($lastRequestReceivedFromWebshipTimestamp) {
                        $connectUrl = "{$GLOBALS['clientInfo']['clientUrl']}/{$GLOBALS['clientInfo']['buildNumber']}/#/settings/integrations/new/woocommerce?woocommerceApiKey={$interpolatableApiKeyString}&woocommerceSite_url=$wordpressUrl";
                        
                        $goToWebshipHref = esc_url("{$GLOBALS['clientInfo']['clientUrl']}/{$GLOBALS['clientInfo']['buildNumber']}/#/ship");
                        
                        $goToWebshipHtml = <<<EOF
                            <a class="external-link button button-primary" href="$goToWebshipHref">
                                Start Shipping on <b>{$GLOBALS['clientInfo']['clientName']}</b>
                            </a>
EOF;
                    }
                    else {
                        $redirectTo = urlencode("{$GLOBALS['clientInfo']['clientUrl']}/{$GLOBALS['clientInfo']['buildNumber']}/#/settings/integrations/new/woocommerce?woocommerceApiKey={$interpolatableApiKeyString}&woocommerceSite_url=$wordpressUrl");
                        $connectUrl = esc_url("{$GLOBALS['clientInfo']['clientUrl']}/{$GLOBALS['clientInfo']['buildNumber']}/signup/114?redirectTo=$redirectTo");
                    }
    
                echo <<<EOF
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            Connect
                        </th>
                        <td class="forminp">
                            <fieldset>
                                $goToWebshipHtml
                                <a class="external-link button $buttonClass" href="$connectUrl">
                                    $buttonText my WooCommerce Store to {$GLOBALS['clientInfo']['clientName']}
                                </a>
                                
                                <p class="description">Click the "$buttonText" button to signup for a new account or login to {$GLOBALS['clientInfo']['clientName']}. Once you're logged in, your WooCommerce store will automatically be connected to {$GLOBALS['clientInfo']['clientName']} and orders will automatically start to appear</p>
                            </fieldset>
                        </td>
                    </tr>
EOF;
                }
                
                echo <<<EOF
                    <tr>
                        <td></td>
                        <td><hr /></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><i>Enable and configure live shipping rates for your customers during checkout from {$GLOBALS['clientInfo']['clientName']} on the <a href="$permalink">integrated quoting settings page</a></i></td>
                    </tr>
                </table> 
EOF;
            }
            
            public function init_form_fields() {
                $formFields = array();
                
                if (!webshipClientHasDedicatedPlugin()) {
                    $formFields['apiKey'] = array(
                        'title' => __('API Key', 'woocommerce-webship'),
                        'description' => __("Copy this text and paste it into the corresponding field on your WooCommerce settings page within {$GLOBALS['clientInfo']['clientName']}", 'woocommerce-webship'),
                        'default' => '',
                        'type' => 'text',
                        'desc_tip' => __("This is the <code>API Key</code> we generated for you in WooCommerce that allows {$GLOBALS['clientInfo']['clientName']} to retrieve and upate orders", 'woocommerce-webship'),
                        'custom_attributes' => array(
                            'readonly' => 'readonly',
                        ),
                        'value' => WC_Webship_Integration::$apiKey,
                    );
                }
                
                $this->form_fields = $formFields;
            }
        }
    }
}

add_action('plugins_loaded', 'webshipWoocommerceInit');

add_filter('woocommerce_integrations', function($integrations) {
    $integrations[] = 'WC_Webship_Integration';

    return $integrations;
});

/**
 * @param array $links Links.
 *
 * @return array Links.
 */
function woocommerce_webship_api_plugin_action_links($links) {
    $link = admin_url('admin.php?page=wc-settings&tab=integration&section=webship');

    return array_merge(array('<a href="' . esc_url($link) . '">' . __('Settings', 'woocommerce-webship') . '</a>'), $links);
}
add_filter('plugin_action_links_' . plugin_basename( __FILE__ ), 'woocommerce_webship_api_plugin_action_links');


/*
    general integration defining endpoints for pulling and updating orders when they are shipped
    accessible via: GET http://woocommerce.shoppingcarts.rocksolidinternet.com/?wc-api=wc_webship
    https://docs.woocommerce.com/document/wc_api-the-woocommerce-api-callback/
   
    example for getOrders:
    GET http://woocommerce.shoppingcarts.rocksolidinternet.com/?wc-api=wc_webship&apiKey=8982e6b0f57c7830dae51f4bbdb1e451&action=getOrders&page=1&statuses=wc-processing

    example for updateOrder
    POST http://woocommerce.shoppingcarts.rocksolidinternet.com/?wc-api=wc_webship&apiKey=8982e6b0f57c7830dae51f4bbdb1e451&action=updateOrder&orderId=141&trackingNumber=TEST123&carrier=USPS
    {
      "items": [
        {
          "lineId": "83",
          "quantity": 1
        }
      ]
    }
*/
function transformWoocommerceOrderIntoWebshipOrder($order) {
    $GET = array_map('sanitize_text_field', $_GET);
    $includeOrdersWithOnlyVirtualItems = isset($GET['includeOrdersWithOnlyVirtualItems']) ? $GET['includeOrdersWithOnlyVirtualItems'] : null;
    
    $onWoocommerceV3OrNewer = version_compare(WC_VERSION, '3.0', '>=');
    
    if ($onWoocommerceV3OrNewer) {
        $timestamp = $order->get_date_paid() ?: $order->get_date_completed() ?: $order->get_date_created();
        $timestamp = $timestamp->getTimestamp();
    }
    else {
        $orderDate = $order->order_date;
        $date = new \DateTime($orderDate);
        $timestamp = $date->getTimestamp();
    }

    $shippingCountry = $onWoocommerceV3OrNewer ? $order->get_shipping_country() : $order->shipping_country;
    $shippingAddress = $onWoocommerceV3OrNewer ? $order->get_shipping_address_1() : $order->shipping_address_1;
    if (empty($shippingCountry) && empty($shippingAddress)) {
        $name = ($onWoocommerceV3OrNewer ? $order->get_billing_first_name() : $order->billing_first_name) . ' ' . ($onWoocommerceV3OrNewer ? $order->get_billing_last_name() : $order->billing_last_name);
        $destination = array(
            'company' => $onWoocommerceV3OrNewer ? $order->get_billing_company() : $order->billing_company,
            'name' => $name, 
            'address1' => $onWoocommerceV3OrNewer ? $order->get_billing_address_1() : $order->billing_address_1,
            'address2' => $onWoocommerceV3OrNewer ? $order->get_billing_address_2() : $order->billing_address_2,
            'city' => $onWoocommerceV3OrNewer ? $order->get_billing_city() : $order->billing_city,
            'state' => $onWoocommerceV3OrNewer ? $order->get_billing_state() : $order->billing_state,
            'zip' => $onWoocommerceV3OrNewer ? $order->get_billing_postcode() : $order->billing_postcode,
            'country' => $onWoocommerceV3OrNewer ? $order->get_billing_country() : $order->billing_country,
            'phone' => $onWoocommerceV3OrNewer ? $order->get_billing_phone() : $order->billing_phone,
            'email' => $onWoocommerceV3OrNewer ? $order->get_billing_email() : $order->billing_email
        );
    }
    else {
        $name = ($onWoocommerceV3OrNewer ? $order->get_shipping_first_name() : $order->shipping_first_name) . ' ' . ($onWoocommerceV3OrNewer ? $order->get_shipping_last_name() : $order->shipping_last_name);
        $destination = array(
            'company' => $onWoocommerceV3OrNewer ? $order->get_shipping_company() : $order->shipping_company,
            'name' => $name, 
            'address1' => $onWoocommerceV3OrNewer ? $order->get_shipping_address_1() : $order->shipping_address_1,
            'address2' => $onWoocommerceV3OrNewer ? $order->get_shipping_address_2() : $order->shipping_address_2,
            'city' => $onWoocommerceV3OrNewer ? $order->get_shipping_city() : $order->shipping_city,
            'state' => $onWoocommerceV3OrNewer ? $order->get_shipping_state() : $order->shipping_state,
            'zip' => $onWoocommerceV3OrNewer ? $order->get_shipping_postcode() : $order->shipping_postcode,
            'country' => $onWoocommerceV3OrNewer ? $order->get_shipping_country() : $order->shipping_country,
            'phone' => $onWoocommerceV3OrNewer ? $order->get_billing_phone() : $order->billing_phone,
            'email' => $onWoocommerceV3OrNewer ? $order->get_billing_email() : $order->billing_email
        );
    }
    
    $shippingMethods = $order->get_shipping_methods();
    $shippingMethodNames = array();

    foreach ($shippingMethods as $shippingMethod) {
        $methodName = preg_replace('/[^A-Za-z0-9 \-\.\_,]/', '', $shippingMethod['name']);
        array_push($shippingMethodNames, $methodName);
    }

    $webshipOrder = array(
        'orderNumber' => ltrim($order->get_order_number(), '#'),
        'id' => is_callable(array($order, 'get_id')) ? $order->get_id() : $order->id,
        'created' => $timestamp,
        'shippingService' => implode(', ', $shippingMethodNames),
        'shipping_total' => sprintf('%01.2f', $onWoocommerceV3OrNewer ? $order->get_shipping_total() : $order->get_total_shipping()),
        'customerNotes' => $onWoocommerceV3OrNewer ? $order->get_customer_note() : $order->customer_note,
        'destination' => $destination,
        'items' => array(),
        'weightUnit' => 'lb'
    );

    $orderHasAtleastOneItem = false;
    
    $orderItems = $order->get_items() + $order->get_items('fee');
    foreach ($orderItems as $itemId => $item) {
        if ($onWoocommerceV3OrNewer) {
            $product = is_callable(array($item, 'get_product')) ? $item->get_product() : null;
        } 
        else {
            $product = $order->get_product_from_item($item);
        }

        if ($includeOrdersWithOnlyVirtualItems) {
            $orderHasAtleastOneItem = true;
        }
        // skip items that don't require shipping
        else if ((!$product || !$product->needs_shipping()) && 'fee' !== $item['type']) {
            continue;
        }
        
        $orderHasAtleastOneItem = true;

        $webshipItem = array(
            'lineId' => (string) $itemId,
        );

        if ('fee' === $item['type']) {
            $webshipItem = array_merge($webshipItem, array(
                'title' => $onWoocommerceV3OrNewer ? $item->get_name() : $item['name'],
                'quantity' => (string) 1,
                'price' => $order->get_item_total($item, false, true)
            ));
        }

        if ($product && $product->needs_shipping()) {
            $imageId = $product->get_image_id();
            $imgUrl = $imageId ? current(wp_get_attachment_image_src($imageId, 'shop_thumbnail')) : '';
            
            if (method_exists($product, 'get_id')) {
                $productId = $product->get_id();
            }
            else {
                $productId = $product->id;
            }

            $webshipItem = array_merge($webshipItem, array(
                'productId' => (string) $productId,
                'sku' => $product->get_sku(),
                'title' => $product->get_title(),
                'imgUrl' => $imgUrl,
                'shippingWeight' => (string) wc_get_weight($product->get_weight(), 'lbs'),
                'quantity' => (string) $item['qty'],
                'price' => $order->get_item_subtotal($item, false, true),
                'productLength' => $product->get_length(),
                'productWidth' => $product->get_width(),
                'productHeight' => $product->get_height(),
                'url' => $product->get_permalink()
            ));
        }

        if ($item['item_meta']) {
            if (version_compare(WC_VERSION, '3.0.0', '<')) {
                $itemMeta = new WC_Order_Item_Meta($item, $product);
                $formattedMeta = $itemMeta->get_formatted('_');
            }
            else {
                add_filter('woocommerce_is_attribute_in_product_name', '__return_false');
                $formattedMeta = $item->get_formatted_meta_data();
            }

            if (!empty($formattedMeta)) {
                $attributes = array();

                foreach ($formattedMeta as $metaKey => $meta) {
                    if (version_compare(WC_VERSION, '3.0.0', '<')) {
                        array_push($attributes, array(
                            'name' => $meta['label'],
                            'value' => $meta['value']
                        ));
                    } 
                    else {
                        array_push($attributes, array(
                            'name' => $meta->display_key,
                            'value' => wp_strip_all_tags($meta->display_value)
                        ));
                    }
                }

                $webshipItem['attributes'] = $attributes;
            }
        }
        
        array_push($webshipOrder['items'], $webshipItem);
    }

    if (!$orderHasAtleastOneItem) {
        return false;
    }

    return $webshipOrder;
}

function woocommerce_webship_api() {
    global $wpdb;
    
    $onWoocommerceV3OrNewer = version_compare(WC_VERSION, '3.0', '>=');

    $GET = array_map('sanitize_text_field', $_GET);
    $apiKey = isset($GET['apiKey']) ? $GET['apiKey'] : null;
    // getOrders, getOrder, updateOrder
    $action = isset($GET['action']) ? $GET['action'] : null;

    // action: getOrders
    $page = isset($GET['page']) ? $GET['page'] : null;
    // 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled'
    // invalid statuses will return all statuses, but we allow any status in case they have custom statuses
    $statuses = isset($GET['statuses']) ? explode(',', $GET['statuses']) : null;
    if ($statuses && version_compare(WC_VERSION, '3.1', '<')) {
        $statuses = array_map(function($status) {
            return "wc-$status";
        }, $statuses);
    }

    // action: updateOrder
    $orderId = isset($GET['orderId']) ? $GET['orderId'] : null;
    $trackingNumber = isset($GET['trackingNumber']) ? $GET['trackingNumber'] : null;
    $carrier = isset($GET['carrier']) ? $GET['carrier'] : null;

    if (empty($apiKey)) {
        wp_send_json_error(__('API Key is required', 'woocommerce-webship'));
    }

    if (!hash_equals(sanitize_text_field($apiKey), WC_Webship_Integration::$apiKey)) {
        wp_send_json_error(__('Invalid API Key', 'woocommerce-webship'));
    }
    
    $lastRequestReceivedFromWebshipTimestamp = get_option('last_request_received_from_webship_timestamp');
    $timestamp = time();
    if ($lastRequestReceivedFromWebshipTimestamp) {
        update_option('last_request_received_from_webship_timestamp', $timestamp);
    }
    else {
        add_option('last_request_received_from_webship_timestamp', $timestamp);
    }

    nocache_headers();

    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE','true');
    }

    if (!defined('DONOTCACHEOBJECT')) {
        define('DONOTCACHEOBJECT', 'true');
    }

    if (!defined('DONOTCACHEDB')) {
        define('DONOTCACHEDB', 'true');
    }
    
    if (!$action) {
        wp_send_json_error(__("You must provide a 'action' parameter", 'woocommerce-webship'));
    }
    else if ($action === 'getOrder') {
        $orderId = $GET['orderId'];
        if (!$orderId) {
            wp_send_json_error(__("You must provide an 'orderId' parameter with action $action", 'woocommerce-webship'));
        }
        $order = wc_get_order($orderId);
        
        if (!$order) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'no order found'));
            exit;
        }

        $webshipOrder = transformWoocommerceOrderIntoWebshipOrder($order);

        if ($webshipOrder) {
            header('Content-Type: application/json');
            echo json_encode(array('webshipOrder' => $webshipOrder, 'woocommerceOrder' => $order->get_data()));
            exit;
        }
        else {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'no order found'));
            exit;
        }
        
    }
    else if ($action === 'getOrders') {
        if (!$page) {
            wp_send_json_error(__("You must provide a 'page' parameter with action $action", 'woocommerce-webship'));
        }
        
        if (!$statuses) {
            wp_send_json_error(__("You must provide a comma separated string 'statuses' parameter with action $action", 'woocommerce-webship'));
        }
        
        $page = isset($page) ? absint($page) : 1;
        $limit = 300;

        if (version_compare(WC_VERSION, '3.1', '>=')) {
            $orderIds = wc_get_orders(array(
                // 'date_modified' => $start_date . '...' . $end_date,
                'type'          => 'shop_order',
                'status'        => $statuses,
                'return'        => 'ids',
                'orderby'       => 'date_modified',
                'order'         => 'DESC',
                'paged'         => $page,
                'limit'         => $limit,
            ));
            
            $orderIds = array_map(function($orderOrId) {
                return is_a($orderOrId, 'WC_Order') ? $orderOrId->get_id() : $orderOrId;
            }, $orderIds);
        }
        else {
            $orderIds = $wpdb->get_col(
                $wpdb->prepare("
                        SELECT ID FROM {$wpdb->posts}
                        WHERE post_type = 'shop_order'
                        AND post_status IN ('" . implode("','", $statuses) . "')
                        ORDER BY post_modified_gmt DESC
                        LIMIT %d, %d
                    ",
                    $limit * ($page - 1), $limit
                )
            );
        }
        
        $webshipOrders = array();
        foreach ($orderIds as $orderId) {
            $order = wc_get_order($orderId);
            $webshipOrder = transformWoocommerceOrderIntoWebshipOrder($order);
            if ($webshipOrder) {
                array_push($webshipOrders, $webshipOrder);
            }
        }
        
        if (version_compare(WC_VERSION, '3.1', '>=')) {
            $orderIds = wc_get_orders(array(
                // 'date_modified' => $start_date . '...' . $end_date,
                'type'          => 'shop_order',
                // 'pending', 'processing', 'on-hold', 'completed', 'refunded, 'failed', 'cancelled', or a custom order status
                'status'        => $statuses,
                'return'        => 'ids',
                'limit'         => -1
            ));
    
            $totalOrders = count($orderIds);
        }
        else {
            $totalOrders = $wpdb->get_var(
                $wpdb->prepare("
                        SELECT COUNT(ID) FROM {$wpdb->posts}
                        WHERE post_type = 'shop_order'
                        AND post_status IN ('" . implode("','", $statuses) . "')
                        LIMIT %d
                    ", 1
                )
            );
        }
    
        header('Content-Type: application/json');
        // total may differ from actual orders shown since we do some filtering after the fact
        echo json_encode(array('orders' => $webshipOrders, 'totalOrders' => $totalOrders, 'statuses' => $statuses));
        exit;
    }
    else if ($action === 'updateOrder') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            wp_send_json_error(__("updateOrder requires POST request method", 'woocommerce-webship'));
        }
        
        $body = file_get_contents('php://input');
        
        if ($body) {
            $requestBody = json_decode($body, true);
        }
        
        if (!$orderId) {
            wp_send_json_error(__("You must provide a 'orderId' parameter with action $action", 'woocommerce-webship'));
        }
        
        if (!$trackingNumber) {
            wp_send_json_error(__("You must provide a 'trackingNumber' parameter with action $action", 'woocommerce-webship'));
        }
        
        if (!$carrier) {
            wp_send_json_error(__("You must provide a 'carrier' parameter with action $action", 'woocommerce-webship'));
        }
        
        preg_match('/\((.*?)\)/', $orderId, $matches);
        if (is_array($matches) && isset($matches[1])) {
            $internalOrderId = $matches[1];
        } 
        else if (function_exists('wc_sequential_order_numbers')) {
            $order = wc_get_order($orderId);
            $internalOrderId = wc_sequential_order_numbers()->find_order_by_order_number($order->get_order_number());
        } 
        else if (function_exists('wc_seq_order_number_pro')) {
            $order = wc_get_order($orderId);
            $internalOrderId = wc_seq_order_number_pro()->find_order_by_order_number($order->get_order_number());
        }
        else {
            $internalOrderId = $orderId;
        }

        if (0 === $internalOrderId) {
            $internalOrderId = $orderId;
        }

        $order = wc_get_order($internalOrderId);
        
        if (!$order) {
            wp_send_json_error(__("No order found with ID $internalOrderId", 'woocommerce-webship'));
        }

        $orderFulfillmentComplete = false;
        $hasShippableItems = false;

        if (!empty($requestBody) && $requestBody['items']) {
            $totalItemQtyToShip = 0;
            $shipmentNotes = array();
            foreach ($requestBody['items'] as $item) {
                if (version_compare(WC_VERSION, '3.0', '>=')) {
                    $lineItem = $order->get_item($item['lineId']);
                    if (is_callable(array($lineItem, 'get_product'))) {
                        $product = $lineItem->get_product();
                        $title = $product->get_title();
                        $sku = $product->get_sku();
                    }
                } 
                else {
                    $items = $order->get_items();
                    if (isset($items[$item['lineId']])) {
                        $product = $order->get_product_from_item($items[$item['lineId']]);
                        $title = $product->get_title();
                        $sku = $product->get_sku();
                    }
                }
                
                $shippable = $product && $product->needs_shipping();
                
                if (!$shippable) {
                    $logger = new WC_Logger();
                    $logger->add($GLOBALS['clientInfo']['clientCode'], "Skipping {$title} {$sku} since it is not a shippable item");
                    
                    continue;
                }
                
                $totalItemQtyToShip += intval($item['quantity']);

                array_push($shipmentNotes, "{$title} {$sku} x {$item['quantity']}");
                
                $hasShippableItems = true;
            }
            
        }
        
        $totalItemQty = 0;
        foreach ($order->get_items() as $lineId => $lineItem) {
            
            if ($onWoocommerceV3OrNewer) {
                $product = is_callable(array($lineItem, 'get_product')) ? $lineItem->get_product() : null;
            } 
            else {
                $product = $order->get_product_from_item($lineItem);
            }

            if (is_a($product, 'WC_Product') && $product->needs_shipping()) {
                $totalItemQty += $lineItem['qty'];
            }
        }
        
        if (version_compare(WC_VERSION, '3.0', '>=')) {
            $wcDate = new WC_DateTime();
            $timestamp = $wcDate->getOffsetTimestamp();
            $ymdDate = $wcDate->format('Y-m-d');
        }
        else {
            $date = new \DateTime();
            $timestamp = $date->getTimestamp();
            $ymdDate = $date->format('Y-m-d');
        }
        
        $formattedDate = date_i18n(get_option('date_format'), $timestamp);
        if ($hasShippableItems) {
            
            $orderNote = implode(', ', $shipmentNotes) . " shipped on $formattedDate via $carrier - $trackingNumber ({$GLOBALS['clientInfo']['clientName']})";

            if (version_compare(WC_VERSION, '2.6', '>=')) {
                $totalItemQtyAlreadyShippedMeta = $order->get_meta('_webship_total_item_qty_already_shipped');
            }
            else {
                $totalItemQtyAlreadyShippedMeta = get_post_meta($orderId, '_webship_total_item_qty_already_shipped', true);
            }

            $totalItemQtyAlreadyShipped = max((int) $totalItemQtyAlreadyShippedMeta, 0);

            if (($totalItemQtyAlreadyShipped + $totalItemQtyToShip) >= $totalItemQty) {
                $orderFulfillmentComplete = true;
            }
            
            $logger = new WC_Logger();
            $logger->add($GLOBALS['clientInfo']['clientCode'], "Shipped $totalItemQtyToShip out of $totalItemQty items in order $orderId");
            
            if (version_compare(WC_VERSION, '2.6', '>=')) {
                $order->update_meta_data('_webship_total_item_qty_already_shipped', $totalItemQtyAlreadyShipped + $totalItemQtyToShip);
                $order->save_meta_data();
            }
            else {
                update_post_meta($orderId, '_webship_total_item_qty_already_shipped', $totalItemQtyAlreadyShipped + $totalItemQtyToShip);
            }
        }
        else if ($totalItemQty === 0) {
            $orderFulfillmentComplete = true;
            
            $orderNote = "Shipped items on $formattedDate via $carrier - $trackingNumber ({$GLOBALS['clientInfo']['clientName']})";
        }
        else {
            $logger = new WC_Logger();
            
            $logger->add($GLOBALS['clientInfo']['clientCode'], "No items found but order has items to fulfill, ignoring request");
        }
        
        if (class_exists('WC_Shipment_Tracking')) {
            if (function_exists('wc_st_add_tracking_number')) {
                wc_st_add_tracking_number($orderId, $trackingNumber, strtolower($carrier), $timestamp);
            } 
            else {
                // for shipment tracking < 1.4.0
                update_post_meta($orderId, '_tracking_provider', strtolower($carrier));
                update_post_meta($orderId, '_tracking_number', $trackingNumber );
                update_post_meta($orderId, '_date_shipped', $timestamp);
            }
        } 
        else {
            // otherwise use built in tracking
            addTrackingEntry($orderId, array(
                'tracking_provider'        => strtolower($carrier),
                'custom_tracking_provider' => '',
                'custom_tracking_link'     => '',
                'tracking_number'          => $trackingNumber,
                'date_shipped'             => $ymdDate,
            ));
        }

        if ($orderNote) {
            $order->add_order_note($orderNote, 0);
        }
        
        if ($orderFulfillmentComplete) {
            $order->update_status('completed');
            
            $logger = new WC_Logger();
            $logger->add($GLOBALS['clientInfo']['clientCode'], "Updated order $orderId to status 'completed'");
        }
        
        status_header(200);
        header('Content-Type: application/json');
        echo json_encode(array('ok' => true));
        exit;
    }
    else {
        wp_send_json_error(__("No such action $action", 'woocommerce-webship'));
    }
}

add_action('woocommerce_api_wc_webship', 'woocommerce_webship_api');

/* webship integrated quoting */
/* 
    http://woocommerce.shoppingcarts.rocksolidinternet.com/wp-admin/admin.php?page=wc-settings&tab=shipping&section=webship
*/
if (is_plugin_active('woocommerce/woocommerce.php')) {
    add_action('woocommerce_shipping_init', 'webship_integrated_quoting_shipping_method');

    function webship_integrated_quoting_shipping_method() {
        if (!class_exists('Webship_Integrated_Quoting_Method')) {
            
            class Webship_Integrated_Quoting_Method extends WC_Shipping_Method {

              /**
               * Constructor for the shipping class
               * 
               * @access public
               * @return void
               */               
                public function __construct() {
                    $this->id = 'webship';
                    $this->method_title = __("{$GLOBALS['clientInfo']['clientName']} Integrated Quoting", 'webship');
                    $this->method_description = __("Integrated Quoting from {$GLOBALS['clientInfo']['clientName']}", 'webship');
                    
                    $this->init();
                    
                    $this->enabled = isset($this->settings['enabled']) && $this->settings['enabled'] === 'yes' ? 'yes' : '';
                }
                
                function admin_options() {
                    $wordpressUrl = urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]");
                    
                    echo <<<EOF
                        <h2>{$GLOBALS['clientInfo']['clientName']} Plugin</h2>
                        <table class="form-table">
EOF;
                    $this->generate_settings_html();
                    
                    $permalink = esc_url(admin_url('admin.php').'?page=wc-settings&tab=integration&section=webship');
                    
                    echo <<<EOF
                        <tr>
                            <td></td>
                            <td><hr /></td>
                        </tr>
                        <tr>
                            <th></th>
                            <td><i>Enable and configure your ability to load orders and fulfill them from {$GLOBALS['clientInfo']['clientName']} on the <a href="$permalink">{$GLOBALS['clientInfo']['clientName']} settings page</a></i></td>
                        </tr>
                    </table> 
EOF;
                }
                
               /**
                * Init your settings
                * 
                * @access public
                * @return void
                */ 
                function init() {
                   // Load the settings API
                   $this->init_form_fields();
                   $this->init_settings();
                   
                   // Save settings in admin if you have any defined
                   add_action('woocommerce_update_options_shipping_' .  $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */            
                function init_form_fields() {
                    $this->form_fields = array(
                        'enabled' => array(
                            'title' => __ ('Enable', 'webship'),
                            'type' => 'checkbox',
                            'description' => __('Enable Integrated Quoting', 'webship'),
                            'default' => 'yes'
                        ),

                        'apiKey' => array(
                            'title' => __ ('API Key', 'webship'),
                            'type'  => 'text',
                            'description' => __("Your WooCommerce Integrated Quoting API Key generated by {$GLOBALS['clientInfo']['clientName']}", 'webship'),
                            'default' => __('','webship')
                        ),
                    );
                    
                    if (!webshipClientHasDedicatedPlugin()) {
                        $this->form_fields['url'] = array(
                            'title' => __("{$GLOBALS['clientInfo']['clientName']} URL", 'webship'),
                            'type' => 'text',
                            'description' => __("Your {$GLOBALS['clientInfo']['clientName']} Base URL", 'webship'),
                            'default' => __('', 'webship')
                        );
                    }
                    
                }
                
                /**
                 * This function reaches out to Webship and returns shipping rates as specified in the Integrated Quoting settings.
                 * 
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package = array()) {
                    $address = array(
                        'country'   => trim($package['destination']['country']),
                        'state'     => trim($package['destination']['state']),
                        'zip'       => trim($package['destination']['postcode']),
                        'city'      => trim($package['destination']['city']),
                        'address1'  => trim($package['destination']['address']),
                        'address2'  => trim($package['destination']['address_2'])
                    );
                    
                    $products = array();
                    foreach ($package['contents'] as $itemId => $values) {
                        $_product = $values['data'];

                        $product = array(
                            'name'      => $_product->get_title(),
                            'length'    => $_product->get_length(),
                            'width'     => $_product->get_width(),
                            'height'    => $_product->get_height(),
                            'weight'    => $_product->get_weight(),
                            'quantity'  => $values['quantity']
                        );
                        
                        $products[] = $product;
                    }

                    $weightUnit = get_option('woocommerce_weight_unit');
                    $dimUnit = get_option('woocommerce_dimension_unit');
                    
                    if ($weightUnit === 'lbs') $weightUnit = 'lb';

                    $url = webshipClientHasDedicatedPlugin() ? $GLOBALS['clientInfo']['clientUrl'] : $this->settings['url'];


                    $response = wp_remote_post("$url/{$GLOBALS['clientInfo']['buildNumber']}/api/v3/integratedQuotingGeneral", array(
                        'timeout' => 45,
                        'body' => json_encode(array(
                            'address'    => $address,
                            'products'   => $products,
                            'apiKey'     => trim($this->settings['apiKey']),
                            'currency'   => get_woocommerce_currency(),
                            'weightUnit' => $weightUnit,
                            'dimUnit'    => $dimUnit,
                            'pluginVersionNumber' => '1.1.74'
                        ))
                    ));
                    
                    /*
                    WP_Error Object
                    (
                        [errors] => Array
                            (
                                [http_request_failed] => Array
                                    (
                                        [0] => A valid URL was not provided.
                                    )
                    
                            )
                    
                        [error_data] => Array
                            (
                            )
                    
                    )
                    */
                    if (gettype($response) === 'object') {
                        $logger = new WC_Logger();
                        $logger->add($GLOBALS['clientInfo']['clientCode'], json_encode($response));
                    }
                    else if (substr($response['response']['code'], 0, 1) !== '2') { // 2XX || or content type !== 'application/json'
                        $logger = new WC_Logger();
                        $logger->add($GLOBALS['clientInfo']['clientCode'], $response['response']['message']);
                    }
                    else {
                        $responseBodyJson = wp_remote_retrieve_body($response);
                        
                        $responseBody = json_decode($responseBodyJson, true);
    
                        $quotes = $responseBody['quotes'];
    
                        if ($quotes && count($quotes) > 0) {
                            foreach ($quotes as $quote) {
                                $this->add_rate(array(
                                    'id'    => $quote['serviceType'],
                                    'label' => $quote['serviceName'],
                                    'cost'  => $quote['totalPrice']
                                ));
                            }
                        }
                    }
                }
            }
            
            add_filter('woocommerce_shipping_methods', 'add_webship_integrated_qutoing_shipping_method');
            
            function add_webship_integrated_qutoing_shipping_method($methods) {
                $methods[] = 'Webship_Integrated_Quoting_Method';
                return $methods;
            }
            
            add_action('woocommerce_settings_saved','check_for_admin_fields');
            
            function check_for_admin_fields($args) {
                $Webship_Integrated_Quoting_Method = new Webship_Integrated_Quoting_Method();
                
                $url = (string) $GLOBALS['clientInfo']['clientUrl'] ?: $Webship_Integrated_Quoting_Method->settings['url'];
                $apiKey = (string) $Webship_Integrated_Quoting_Method->settings['apiKey'];
        
                if (empty($url)) {
                    WC_Admin_Settings::add_error("{$GLOBALS['clientInfo']['clientName']} URL is a required field");
                }
        
                if (empty($apiKey)) {
                    WC_Admin_Settings::add_error('API Key is a required field');
                }
            }
        }
    }
}