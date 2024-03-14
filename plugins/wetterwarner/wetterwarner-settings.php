<?php
/*
* Wetterwarner Admin Einstellungen
* Author: Tim Knigge
* https://it93.de/projekte/wetterwarner/
*/ 
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
add_action( 'admin_menu', 'wetterwarner_add_admin_menu' );
add_action( 'admin_init', 'wetterwarner_settings_init' );
add_action( 'admin_enqueue_scripts', 'wetterwarner_admin_scripts' );

function wetterwarner_add_admin_menu(  ) { 
	add_options_page( 'Wetterwarner Settings', 'Wetterwarner', 'manage_options', 'wetterwarner', 'wetterwarner_options_page' );
}
function wetterwarner_settings_init(  ) { 
	
	register_setting( 'pluginPage', 'wetterwarner_settings');
	
	add_settings_section(
		'wetterwarner_pluginPage_section', 
		__( 'Wetterwarner Settings', 'wetterwarner' ), 
		'wetterwarner_settings_section_callback', 
		'pluginPage'
	);

    add_settings_field( 
	'ww_farbe_stufe1', 
	__( 'Background Color level 1', 'wetterwarner' ), 
	'ww_farbe_stufe1_field',
	'pluginPage', 
	'wetterwarner_pluginPage_section'
	);
	add_settings_field( 
	'ww_farbe_stufe2', 
	__( 'Background Color level 2', 'wetterwarner' ), 
	'ww_farbe_stufe2_field',
	'pluginPage', 
	'wetterwarner_pluginPage_section'
	);
	add_settings_field( 
	'ww_farbe_stufe3', 
	__( 'Background Color level 3', 'wetterwarner' ), 
	'ww_farbe_stufe3_field',
	'pluginPage', 
	'wetterwarner_pluginPage_section'
	);
	add_settings_field( 
	'ww_farbe_stufe4', 
	__( 'Background Color level 4', 'wetterwarner' ), 
	'ww_farbe_stufe4_field',
	'pluginPage', 
	'wetterwarner_pluginPage_section'
	);
}
function wetterwarner_admin_scripts(  ) { 
	wp_enqueue_style( 'wp-color-picker' );
	wp_register_script( 'wp-color-picker-alpha', plugins_url('/js/wp-color-picker-alpha.js',  __FILE__ ), array( 'wp-color-picker' ));
	wp_add_inline_script(
	'wp-color-picker-alpha',
	'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );');
	wp_enqueue_script( 'wp-color-picker-alpha' );
}

function ww_farbe_stufe1_field( ) {
	$options = get_option( 'wetterwarner_settings' );
	if( !isset( $options['ww_farbe_stufe1'] )) {
		$options['ww_farbe_stufe1'] = 'rgba(255,255,170,0.5)'; 
	}
	echo '<input type="text" class="color-picker" name="wetterwarner_settings[ww_farbe_stufe1]" data-alpha-enabled="true" value="' .$options['ww_farbe_stufe1']. '">';
}
function ww_farbe_stufe2_field( ) {
	$options = get_option( 'wetterwarner_settings' );
	if( !isset( $options['ww_farbe_stufe2'] )) {
		$options['ww_farbe_stufe2'] = 'rgba(255,218,188,0.5)';
	}
    echo '<input type="text" class="color-picker" name="wetterwarner_settings[ww_farbe_stufe2]" data-alpha-enabled="true" value="' .$options['ww_farbe_stufe2']. '">';
}
function ww_farbe_stufe3_field( ) {
	$options = get_option( 'wetterwarner_settings' );
	if( !isset( $options['ww_farbe_stufe3'] )) {
		$options['ww_farbe_stufe3'] = 'rgba(255,204,204,0.5)';
	}
    echo '<input type="text" class="color-picker" name="wetterwarner_settings[ww_farbe_stufe3]" data-alpha-enabled="true" value="' .$options['ww_farbe_stufe3']. '">';
}
function ww_farbe_stufe4_field( ) {
	$options = get_option( 'wetterwarner_settings' );
	if( !isset( $options['ww_farbe_stufe4'] )) {
		$options['ww_farbe_stufe4'] = 'rgba(198,155,198,0.5)';
	}
    echo '<input type="text" class="color-picker" name="wetterwarner_settings[ww_farbe_stufe4]" data-alpha-enabled="true" value="' .$options['ww_farbe_stufe4']. '">';
}

function wetterwarner_settings_section( ){
	wetterwarner_admin_notification();
	do_settings_sections( 'pluginPage' );
}
function wetterwarner_settings_footer() {
	echo '<h3>Wetterwarner Debug Info</h3>';
	echo '<p>Die Debug Informationen wurden verschoben in den <a href="/wp-admin/site-health.php?tab=debug">Webseiten-Zustandsbericht</a>.</p>';
	echo '<p><a href=\"https://it93.de/projekte/wetterwarner/dokumentation/\" target=\"_blank\">Dokumentation</a></p>';
}
function wetterwarner_settings_section_callback(  ) {
	echo '<div class="wrap">';
	echo __( 'The following settings are independent of the widget options.<br><br>', 'wetterwarner' );
	echo '</div>';
}
function wetterwarner_admin_notification() {
    $response = wp_remote_get('https://api.it93.de/wetterwarner/admin_notification.txt');

    if (is_array($response) && !is_wp_error($response)) {
        $headers = $response['headers']; // array of http header lines
        $notification_content = $response['body']; // use the content

        $notification_lines = explode("\n", $notification_content);

        if (count($notification_lines) >= 2 && $notification_lines[0] != '0') {
            ?>
            <div class="notice notice-info is-dismissible">
                <p><?php echo $notification_lines[1]; ?></p>
            </div>
            <?php
        }
    } else {
        echo "Admin Benachrichtigung Fehler ...";
    }
}
function wetterwarner_options_page() {
	?>
	<form action='options.php' method='post'>
		<?php
		settings_fields( 'pluginPage' );	
		wetterwarner_settings_section();
		submit_button();
		wetterwarner_settings_footer();
		?>
	</form>
	<?php
}
function startsWith( $haystack, $needle ) {
     $length = strlen( $needle );
     return substr( $haystack, 0, $length ) === $needle;
}
?>