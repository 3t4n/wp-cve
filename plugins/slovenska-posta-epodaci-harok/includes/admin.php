<?php
/*
*	Run only in administration
*/
if (is_admin()) {
	add_action('init', 'tsseph_admin_init');
}

/*
*	Init menu, settings and scripts
*/
function tsseph_admin_init() {

    add_action( 'admin_menu', 'tsseph_menu' ); //Register admin menu
	add_action( 'admin_init', 'tsseph_register_settings' ); //Register settings
	add_action( 'admin_enqueue_scripts', 'tsseph_admin_enqueue_styles' ); //Register CSS for Admin
	add_action( 'admin_enqueue_scripts', 'tsseph_admin_enqueue_scripts' ); //Register scripts for Admin

	add_action( 'admin_enqueue_scripts', 'tsseph_select2_jquery' ); //Enqueue select2 library

	//WooCommerce
	add_filter( 'bulk_actions-edit-shop_order', 'tsseph_register_bulk_action' ); //Bulk action settings + XML Export 
	add_filter( 'bulk_actions-woocommerce_page_wc-orders', 'tsseph_register_bulk_action' ); //Bulk action settings + XML Export 
	add_filter( 'handle_bulk_actions-edit-shop_order', 'tsseph_bulk_action_handler', 10, 3 ); //Export
	add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', 'tsseph_bulk_action_handler', 10, 3 ); //Export
	add_action( 'admin_notices', 'tsseph_bulk_action_admin_notice' ); //Notice use when export is done	

	add_filter( 'plugin_row_meta', 'tsseph_support_development', 10, 4 ); //Add support link
}

/*
*	Create a submenu in Settings
*/
function tsseph_menu() {
	add_options_page( __( 'ePodací hárok',
		'spirit-eph' ), __( 'ePodací hárok',
		'spirit-eph' ), 'manage_options', 'spirit-eph',
		'tsseph_settings_page' );
}

/*
*	UI for administration page
*/
function tsseph_settings_page() {
    global $tsseph_options;
    
	$tsseph_options= get_option( 'tsseph_options' );
	$tsseph_bonus_options= get_option( 'tsseph_bonus_options' );
	
    include (SPIRIT_EPH_PLUGIN_PATH . "templates/settings-page.php");

	//echo tsseph_display_settings_page($tsseph_options, $tsseph_bonus_options);
}

/*
*	Ajax to reload settings page
*/
function tsseph_reload_settings_page() {

	$tsseph_options= get_option( 'tsseph_options' );
	$tsseph_bonus_options= get_option( 'tsseph_bonus_options' );

	tsseph_get_settings($tsseph_options, $tsseph_bonus_options);

	wp_die();
}
add_action('wp_ajax_tsseph_reload_settings_page', 'tsseph_reload_settings_page');



/*
*	Register settings
*/
function tsseph_register_settings() {
	register_setting( 'tsseph_settings_group','tsseph_options', 'tsseph_sanitize_options' );
}

/*
*	Incluce styles in admin
*/
function tsseph_admin_enqueue_styles() {
	wp_enqueue_style( 'spirit-eph', plugins_url('../css/spirit-eph.css', __FILE__ ), array(), SPIRIT_EPH_VERSION);
	//wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'); 
}

/*
*	Incluce scripts in admin
*/
function tsseph_admin_enqueue_scripts() {
    wp_enqueue_script('spirit-eph-admin', plugins_url('../js/spirit-eph-admin.js',__FILE__ ),  array('jquery','select2'),SPIRIT_EPH_VERSION, 'false' );
	wp_localize_script('spirit-eph-admin', 'tsseph_ajax_object', 
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ) 
    );
}

/*
*	Enqueue Select2
*/
function tsseph_select2_jquery() {
    wp_register_style( 'select2css', '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', false, '1.0', 'all' );
    wp_register_script( 'select2', '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}


/*
*	Add support link
*/
function tsseph_support_development($links_array, $plugin_file_name, $plugin_data, $status) {

	if( strpos( $plugin_file_name, 'slovenska-posta-epodaci-harok.php' )) {
		$links_array[] = sprintf(
			'<a href="%1$s" target="_blank"><span class="dashicons dashicons-star-filled" aria-hidden="true" style="font-size:14px;line-height:1.3"></span>%2$s</a>',
			'https://matejpodstrelenec.sk/podpora-vyvoja-pluginov/',
			__( 'Podporte vývoj pluginu', 'mpod-eph' )
		);
	}

	return $links_array;
}

/*
*	Sanitize options
*/
function tsseph_sanitize_options( $options ) {

    $options['UserId'] = ( ! empty( $options['UserId'] ) ) ?sanitize_text_field( $options['UserId'] ) : '';
    $options['ApiKey'] = ( ! empty( $options['ApiKey'] ) ) ?sanitize_text_field( $options['ApiKey'] ) : '';
	$options['OdosielatelID'] = ( ! empty( $options['OdosielatelID'] ) ) ?sanitize_text_field( $options['OdosielatelID'] ) : '';
	$options['Meno'] = ( ! empty( $options['Meno'] ) ) ?sanitize_text_field( $options['Meno'] ) : '';
	$options['Organizacia'] = ( ! empty( $options['Organizacia'] ) ) ?sanitize_text_field( $options['Organizacia'] ) : '';
	$options['Ulica'] = ( ! empty( $options['Ulica'] ) ) ?sanitize_text_field( $options['Ulica'] ) : '';
	$options['Mesto'] = ( ! empty( $options['Mesto'] ) ) ?sanitize_text_field( $options['Mesto'] ) : '';
	$options['PSC'] = ( ! empty( $options['PSC'] ) ) ?sanitize_text_field( $options['PSC'] ) : '';
	$options['Krajina'] = ( ! empty( $options['Krajina'] ) ) ?sanitize_text_field( $options['Krajina'] ) : '';

	$options['RovnakaNavratova'] = ( ! empty( $options['RovnakaNavratova'] ) ) ?sanitize_text_field( $options['RovnakaNavratova'] ) : 0;
	$options['SMeno'] = ( ! empty( $options['SMeno'] ) ) ?sanitize_text_field( $options['SMeno'] ) : '';
	$options['SOrganizacia'] = ( ! empty( $options['SOrganizacia'] ) ) ?sanitize_text_field( $options['SOrganizacia'] ) : '';
	$options['SUlica'] = ( ! empty( $options['SUlica'] ) ) ?sanitize_text_field( $options['SUlica'] ) : '';
	$options['SMesto'] = ( ! empty( $options['SMesto'] ) ) ?sanitize_text_field( $options['SMesto'] ) : '';
	$options['SPSC'] = ( ! empty( $options['SPSC'] ) ) ?sanitize_text_field( $options['SPSC'] ) : '';
	$options['SKrajina'] = ( ! empty( $options['SKrajina'] ) ) ?sanitize_text_field( $options['SKrajina'] ) : '';

	$options['Telefon'] = ( ! empty( $options['Telefon'] ) ) ?sanitize_text_field( $options['Telefon'] ) : '';
	$options['Email'] = ( ! empty( $options['Email'] ) ) ?sanitize_email( $options['Email'] ) : '';
	$options['CisloUctu'] = ( ! empty( $options['CisloUctu'] ) ) ?sanitize_text_field( $options['CisloUctu'] ) : '';
    $options['TypEPH'] = ( ! empty( $options['TypEPH'] ) ) ?sanitize_text_field( $options['TypEPH'] ) : '';
    $options['SposobSpracovania'] = ( ! empty( $options['SposobSpracovania'] ) ) ?sanitize_text_field( $options['SposobSpracovania'] ) : '';	
	$options['SposobUhrady'] = ( ! empty( $options['SposobUhrady'] ) ) ?sanitize_text_field( $options['SposobUhrady'] ) : '';	
	$options['Trieda'] = ( ! empty( $options['Trieda'] ) ) ?sanitize_text_field( $options['Trieda'] ) : '';	

    $options['PaymentType'] = isset(  $options['PaymentType'] ) ? (array)  $options['PaymentType'] : array();
    $options['PaymentType'] = array_map('sanitize_text_field', $options['PaymentType']);

	//Podacie čísla
	$options['PodacieCisla'] = array(
        //Zmluvní zákazníci
        '14' => array(
            'RozsahPodCisFrom' => (isset($options['PodacieCisla'][14]['RozsahPodCisFrom']) ? $options['PodacieCisla'][14]['RozsahPodCisFrom'] : ''),
            'RozsahPodCisTo' => (isset($options['PodacieCisla'][14]['RozsahPodCisTo']) ? $options['PodacieCisla'][14]['RozsahPodCisTo'] : ''),
            'AktualnePodCislo' => (isset($options['PodacieCisla'][14]['AktualnePodCislo']) ? $options['PodacieCisla'][14]['AktualnePodCislo'] : '')  
        ),
        //Express kuriér
        '8' => array(
            'RozsahPodCisFrom' => (isset($options['PodacieCisla'][8]['RozsahPodCisFrom']) ? $options['PodacieCisla'][8]['RozsahPodCisFrom'] : ''),
            'RozsahPodCisTo' => (isset($options['PodacieCisla'][8]['RozsahPodCisTo']) ? $options['PodacieCisla'][8]['RozsahPodCisTo'] : ''),
            'AktualnePodCislo' => (isset($options['PodacieCisla'][8]['AktualnePodCislo']) ? $options['PodacieCisla'][8]['AktualnePodCislo'] : '')  
        ) 
	);

	//Vlastné podacie čísla
	$options['PodacieCislaEnabled'] = ( ! empty( $options['PodacieCislaEnabled'] ) ) ?sanitize_text_field( $options['PodacieCislaEnabled'] ) : 0;

	//Zmluvný vzťah so Slovenskou poštou
	$options['ZmluvnyVztahEnabled'] = ( ! empty( $options['ZmluvnyVztahEnabled'] ) ) ?sanitize_text_field( $options['ZmluvnyVztahEnabled'] ) : 0;
	
	$options['SendTrackingNo'] = (!empty($options['SendTrackingNo'])) ? absint($options['SendTrackingNo']) : 0;

	$ShippingZones = WC_Shipping_Zones::get_zones();

	foreach($ShippingZones as $ShippingZone) {
		foreach($ShippingZone['shipping_methods'] as $ShippingMethod) {

			$options['PredvolenyDruhZasielky_' . $ShippingMethod->get_instance_id()] = ( ! empty( $options['PredvolenyDruhZasielky_' . $ShippingMethod->get_instance_id()] ) ) ?sanitize_text_field( $options['PredvolenyDruhZasielky_' . $ShippingMethod->get_instance_id()] ) : '';
		}
	}
	
	return $options;
}