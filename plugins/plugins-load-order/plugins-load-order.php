<?php
/*
Plugin Name: Plugins Load Order
Plugin URI: http://josebarreramayoral.es/en/my-plugin/plugins-load-order-plugin/
Description: Allows you to change the order in which plugins will be loaded by Wordpress
Version: 1.2.2
Author: Jose Antonio de la Barrera Mayoral
Author URI: http://josebarreramayoral.es/en/
License: GPL
Text Domain: pluginsloadorder
*/

define( 'PLO_FILE', __FILE__ );
define( 'PLO_RELATIVE_PATH', plugin_basename( __FILE__ ) );
define( 'PLO_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

define( 'PLO_OPTIONS', 'plugins_load_order_options' );
define( 'PLO_TEXT_DOMAIN', 'pluginsloadorder' );

/*==================================================*/
/*	Hook de activación del plugin					*/
/*--------------------------------------------------*/
function plugins_load_order_init_options() {
	$plo_pro_order = get_option('active_plugins');

	if (is_array($plo_pro_order) && !empty($plo_pro_order)) {
		$plo_pro_order = implode(",", $plo_pro_order);
	} else {
		$plo_pro_order = '';
	}

	$defaults = array(
    	'plo_pro_order' => $plo_pro_order,
    	'plo_in_order' => 'primero',
    );

	$currentOptions = ( get_option( PLO_OPTIONS ) ) ? get_option( PLO_OPTIONS ) : array();


	$theOptions = array_merge( $defaults, $currentOptions );

	update_option( PLO_OPTIONS, $theOptions );
    
}
register_activation_hook( __FILE__, 'plugins_load_order_init_options' );


/*==================================================*/
/*	Hook para crear una entrada en el menú de 		*/
/*	configuración 									*/
/*--------------------------------------------------*/
function plugins_load_order_create_options_submenu(){
	$page_title = __('Plugins Load Order options', PLO_TEXT_DOMAIN);
	$menu_title = __('Plugins Load Order', PLO_TEXT_DOMAIN);
	$capability = 'manage_options';
	$menu_slug = 'plugins_load_order_settings_menu';
	$callback = 'plugins_load_order_settings_page';

	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $callback );
}
add_action('admin_menu', 'plugins_load_order_create_options_submenu' );


/*==================================================*/
/*	Hook para la funcion sanitize de las opciones	*/
/*	del plugin 										*/
/*--------------------------------------------------*/
function plugins_load_order_register_settings() {
	register_setting( 'plugins_load_order_group', PLO_OPTIONS, 'plugins_load_order_sanitize_options' );
}
add_action( 'admin_init', 'plugins_load_order_register_settings' );


/*==================================================*/
/*	Carga el idioma del plugin 						*/
/*	Compatible con WPML y Polylang 					*/
/*--------------------------------------------------*/
function plugins_load_order_load_textdomain() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
		global $sitepress;
		if( !is_admin() ){
			$sitepress->switch_lang(ICL_LANGUAGE_CODE);
			load_plugin_textdomain(PLO_TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
		} else {
			$get_l = 'en';
			$locale = 'en_US';
			if( isset($_GET['lang']) ){
				$get_l = $_GET['lang'];
			}
			$languages = icl_get_languages('skip_missing=0&orderby=code&order=desc');
			if( !empty($languages) ){
				foreach($languages as $l){
					if( $l['language_code'] == $get_l ){
						$locale = $l['default_locale'];
					}
				}
			}
			$moFile = dirname( __FILE__ ) . '/languages/'. PLO_TEXT_DOMAIN . '-' . $locale . '.mo';
			load_textdomain(PLO_TEXT_DOMAIN, $moFile);
		}
	} else if( is_plugin_active( 'polylang/polylang.php' ) ) {
		global $polylang;
		if( $polylang ){
			$locale = pll_current_language('locale');
			$moFile = dirname( PLO_FILE ) . '/languages/'. PLO_TEXT_DOMAIN . '-' . $locale . '.mo';
			load_textdomain(PLO_TEXT_DOMAIN, $moFile);
		}
	} else {
		load_plugin_textdomain(PLO_TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
	}
}
add_action( 'init', 'plugins_load_order_load_textdomain' );


/*==================================================*/
/*	Filtro para la actualización de la opción 		*/
/*	pre_update_option para comprobar que el plugin 	*/
/*	esta en primera posicion en caso de estar 		*/
/*	configurado en las opciones del plugin 			*/
/*--------------------------------------------------*/
function plugins_load_order_first_on_safe( $value, $old_value ){
	$currentOptions = get_option(PLO_OPTIONS);
	$option_value = 'active_plugins';

	if( isset( $currentOptions['plo_in_order'] ) && $currentOptions['plo_in_order'] == 'primero' && $plo_current_position = array_search(PLO_RELATIVE_PATH, $value) ){
		$new_value = array();
		$new_value[] = PLO_RELATIVE_PATH;
		for( $i = 0; $i < count($value); $i++ ){
			if( $value[$i] != PLO_RELATIVE_PATH ) $new_value[] = $value[$i];
		}
		$value = $new_value;

		return $value;
	}

	return $value;
}
add_filter( 'pre_update_option_active_plugins', 'plugins_load_order_first_on_safe', 256, 2 );



/*==================================================*/
/*	Función de sanitize de las opciones del plugin 	*/
/*--------------------------------------------------*/
function plugins_load_order_sanitize_options( $options ) {
	update_option( 'active_plugins', explode(",", $options['plo_pro_order'] ) ) ;
	return array_merge(get_option( PLO_OPTIONS ),$options);
}

function plugins_load_order_scripts_styles_admin() {

	if( isset( $_GET['page'] ) && $_GET['page'] == 'plugins_load_order_settings_menu' ){
		wp_enqueue_script(array(
			'jquery',
			'jquery-ui-sortable',
		));
		wp_enqueue_script( 'plo-js-custom', plugins_url( 'js/custom.js', __FILE__ ), array('jquery'), '1.0', true );

		wp_enqueue_style( 'css-plugin', plugins_url( 'css/plugins-loader-order.css', __FILE__ ), array() );
	}

}
add_action( 'admin_enqueue_scripts', 'plugins_load_order_scripts_styles_admin', 10 );


/**
* settings_page()
*/
function plugins_load_order_settings_page(){
	
?>
	<div class="wrap">
		<h2 class="main-title"><?php _e('Plugins Load Order options', PLO_TEXT_DOMAIN) ?></h2>
		<form method="post" action="options.php" id="ordenPluginForm">
			<?php settings_fields( 'plugins_load_order_group' ); ?>
			<?php $plugins_load_order_options = get_option( PLO_OPTIONS ); ?>
			<?php $active_plugins = get_option('active_plugins'); ?>

			<h3><?php _e('Set order in which plugins will be loaded', PLO_TEXT_DOMAIN); ?></h3>
			<ul id="sortable" class="espacio-arriba-30">
				<?php
					$contador = 1;
					$primero = false;
					foreach ($active_plugins as $a_plugin ) {
						$plugin_data = get_plugin_data( WP_PLUGIN_DIR."/".$a_plugin );
						if( $plugins_load_order_options['plo_in_order'] != "primero" || ( $plugins_load_order_options['plo_in_order'] == "primero" && $a_plugin != PLO_RELATIVE_PATH ) ){
							?>
								<li cadenaplugin="<?php echo $a_plugin ?>" class="row sortador <?php if($contador%2==0) echo "odd"; ?>">
									<span title="<?php echo __('Up', PLO_TEXT_DOMAIN) ?>" class="sort-icon dashicons-before dashicons-arrow-up"></span>
									<span class="sort-data"><?php echo $plugin_data['Name']; ?></span>
									<span title="<?php echo __('Down', PLO_TEXT_DOMAIN) ?>" class="sort-icon dashicons-before dashicons-arrow-down"></span>
									
								</li>
							<?php
							$primero = true;
							$contador++;
						}
					}
				?>
			</ul>

			<h3><?php _e( 'Would you like this plugin to be loaded always first?', PLO_TEXT_DOMAIN ); ?></h3>
			<p>
				<input type="radio" name="plugins_load_order_options[plo_in_order]" value="primero" <?php if( $plugins_load_order_options['plo_in_order'] == "primero" ) echo "checked" ?> > <?php _e( 'Yes, I want this plugin to be loaded always first', PLO_TEXT_DOMAIN ); ?> <br>
				<input type="radio" name="plugins_load_order_options[plo_in_order]" value="manual" <?php if( $plugins_load_order_options['plo_in_order'] == "manual" ) echo "checked" ?> > <?php _e( 'No, I want to set its priority', PLO_TEXT_DOMAIN ); ?> <br>
			</p>
			
			<p class="submit">
				<input type = "hidden" id="nuevoOrdenPlugin" name="plugins_load_order_options[plo_pro_order]" value = "<?php echo $plugins_load_order_options['plo_pro_order']; ?>" />
				<input id="btn-save-orden" type="submit" class="button-primary" value="<?php _e('Save changes', PLO_TEXT_DOMAIN) ?>" />
			</p>
		</form>
	</div>

	<script type="text/javascript">
		var in_order_on_ready = '<?php if( $plugins_load_order_options["plo_in_order"] == "primero" ) { echo PLO_RELATIVE_PATH; } else { echo "0"; } ?>';
	</script>
	
	<?php
}

?>
