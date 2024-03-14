<?php
function create_cloudlyup_gmap_post_type(){
	$labels = array(
		'name'               => _x( 'Map', 'post type general name', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add Location', 'map', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Location', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Locatins', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Locations', 'your-plugin-textdomain' ),

	);

	$args = array(
		'labels'             => $labels,
    'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'supports'           => array( 'title','thumbnail' ),
		'menu_icon'	 				 => 'dashicons-location-alt'
	);

	register_post_type( 'clupmap', $args );
}

function create_cloudlyup_addres_location_metabox(){

	add_meta_box(
		'mapaddress', // $id
		'Address Location', // $title
		'create_cloudlyup_address_metabox_fields', // $callback
		'clupmap', // $screen
		'normal', // $context
		'high' // $priority
	);
}
function create_cloudlyup_address_metabox_fields(){
	global $post;
		$meta = get_post_meta( $post->ID, 'cloudlyup_maulti_location_gmap_fields', true ); ?>
	<input type="hidden" name="your_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <!-- All fields will go here -->
		<p>
		<span>click heare to find Latitude and Longitude</span>
		<br>
		<a href="https://www.latlong.net/" target="_blank" class="help">Create</a>
		<br>
		<label class="maplab" for="cloudlyup_maulti_location_gmap_fields[latitude]">Latitude</label>
		<br>
		<input type="text" name="cloudlyup_maulti_location_gmap_fields[latitude]" id="cloudlyup_maulti_location_gmap_fields[latitude]" class="regular-text" value="<?php if(isset($meta['latitude'])) { echo $meta['latitude']; } ?>">
		<br>
		<br>
		<label class="maplab" for="cloudlyup_maulti_location_gmap_fields[longitude]">Longitude</label>
		<br>
		<input type="text" name="cloudlyup_maulti_location_gmap_fields[longitude]" id="cloudlyup_maulti_location_gmap_fields[longitude]" class="regular-text" value="<?php if(isset($meta['longitude'])) { echo $meta['longitude']; } ?>">
		<br>
	  </p>
		<p>
			<h2>Postal Address</h2>
			<br>
			<label class="maplab" for="cloudlyup_maulti_location_gmap_fields[lineone]">Address line one</label>
			<br>
			<input type="text" name="cloudlyup_maulti_location_gmap_fields[lineone]" id="cloudlyup_maulti_location_gmap_fields[lineone]" class="regular-text" value="<?php if(isset($meta['lineone'])) { echo $meta['lineone']; } ?>">
			<br>
			<br>
			<label class="maplab" for="cloudlyup_maulti_location_gmap_fields[linetwo]">Address line Two</label>
			<br>
			<input type="text" name="cloudlyup_maulti_location_gmap_fields[linetwo]" id="cloudlyup_maulti_location_gmap_fields[linetwo]" class="regular-text" value="<?php if(isset($meta['linetwo'])) { echo $meta['linetwo']; } ?>">
			<br>
			<br>
			<label class="maplab" for="cloudlyup_maulti_location_gmap_fields[linethree]">Address line Three</label>
			<br>
			<input type="text" name="cloudlyup_maulti_location_gmap_fields[linethree]" id="cloudlyup_maulti_location_gmap_fields[linethree]" class="regular-text" value="<?php if(isset($meta['linethree'])) { echo $meta['linethree']; } ?>">
			<br>
			<br>

		</p>
	<?php
}

function save_cloudlyup_address_metabox_meta_fields( $post_id ) {
	// verify nonce
	if ( !wp_verify_nonce( $_POST['your_meta_box_nonce'], basename(__FILE__) ) ) {
		return $post_id;
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	if ( 'page' === $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}
if ( isset( $_POST['cloudlyup_maulti_location_gmap_fields'] )){
	$old = get_post_meta( $post_id, 'cloudlyup_maulti_location_gmap_fields', true );

	 $new = sanitize_meta( 'cloudlyup_maulti_location_gmap_fields', $_POST['cloudlyup_maulti_location_gmap_fields'], 'post' );

	 function sanitize_cloudlyup_maulti_location_gmap_fields($gmapfs) {


		$value = filter_var_array($_POST['cloudlyup_maulti_location_gmap_fields'],FILTER_SANITIZE_STRING);

		 return $value;
	 }
	 add_filter( 'sanitize_post_meta_cloudlyup_maulti_location_gmap_fields', 'sanitize_cloudlyup_maulti_location_gmap_fields' );

	if ( $new && $new !== $old ) {
		update_post_meta( $post_id, 'cloudlyup_maulti_location_gmap_fields', $new );
	} elseif ( '' === $new && $old ) {
		delete_post_meta( $post_id, 'cloudlyup_maulti_location_gmap_fields', $old );
	}
	}
}

if (function_exists('create_cloudlyup_gmap_post_type')){
add_action('init', 'create_cloudlyup_gmap_post_type');
}
if (function_exists('create_cloudlyup_addres_location_metabox')){
add_action('add_meta_boxes', 'create_cloudlyup_addres_location_metabox');
}
if (function_exists('save_cloudlyup_address_metabox_meta_fields')){
add_action( 'save_post', 'save_cloudlyup_address_metabox_meta_fields' );
}
function cloudlyup_gmap_style(){
	wp_enqueue_style('mapstyle', plugin_dir_url( __FILE__ ) . '/style.css');
}

if (function_exists('cloudlyup_gmap_style')){
add_action('init', 'cloudlyup_gmap_style');
}

if (function_exists('register_cloudlyup_gmap_setting_submenu')){
add_action('admin_menu', 'register_cloudlyup_gmap_setting_submenu');
}

function register_cloudlyup_gmap_setting_submenu() {
add_submenu_page( 'edit.php?post_type=clupmap', 'settings', 'Settings', 'administrator', 'edit.php?page=clupmap-settings', 'cloudlyup_gmap_settings_page' );
}

function register_cloudlyup_gmap_settingspage_fields() {
   add_option( 'gmap_api_key', '');
   register_setting( 'myplugin_options_group', 'gmap_api_key');

	 add_option( 'gmap_zoom', '5');
	 register_setting( 'myplugin_options_group', 'gmap_zoom');
}

if (function_exists('register_cloudlyup_gmap_settingspage_fields')){
add_action( 'admin_init', 'register_cloudlyup_gmap_settingspage_fields' );
}
function cloudlyup_gmap_settings_page() {

?>
<h2>Map Settings Page</h2>
<p>Multi Location google map settigns page</p>
<form method="post" action="options.php">
  <?php settings_fields( 'myplugin_options_group' ); ?>
  <table>
  <tr valign="top">
  <th scope="row"><label class="gmap_labal_field" for="gmap_api_key">Google Map API Key</label></th>
  <td><input type="text" class="gmap_settings_field" id="gmap_api_key" name="gmap_api_key" value="<?php echo get_option('gmap_api_key'); ?>" /> <a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank" >Help</a></td>
	</tr>
	<tr valign="top">
	<th scope="row"><label class="gmap_labal_field" for="gmap_zoom">Google zoom</label></th>
  <td><input type="text" class="gmap_zoom" id="gmap_zoom" name="gmap_zoom" value="<?php echo get_option('gmap_zoom'); ?>" /><span> Use numeric value 1 - 10</span></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>

  </div>
	<div class="sample">
		<?php echo do_shortcode('[CLOUPLYUP_MAP]'); ?>
	</div>
<?php

}
function wporg_add_custom_box()
{
    $screens = ['clupmap', 'wporg_cpt'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wporg_box_id',           // Unique ID
            'Google Map Over view',  // Box title
            'google_map_over_view',  // Content callback, must be of type callable
            $screen,
						'side'               // Post type
        );
    }
}
add_action('add_meta_boxes', 'wporg_add_custom_box');
function google_map_over_view(){
	echo do_shortcode('[CLOUPLYUP_MAP]');
}
