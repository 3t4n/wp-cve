<?php
/*
	Plugin Name: Particle Background WP
	Description: Add cool and innovative particle backgrounds to any WordPress theme without any code.  Fully customizeable.
	Text Domain: particle-background-wp
	Domain Path: /languages
	Author: Ryan Novotny
	Author URI: https://particlebackground.com
	License: GPLv2
	Version: 2.0.0
*/

defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );

//DEFINE SOME USEFUL CONSTANTS
define( 'RN_PBWP_DEBUG', FALSE );
define( 'RN_PBWP_PLUGIN_VER', '2.0.0');
define( 'RN_PBWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RN_PBWP_PLUGINS_URL', plugins_url( '', __FILE__ ) );
define( 'RN_PBWP_PLUGINS_BASENAME', plugin_basename(__FILE__) );
define( 'RN_PBWP_PLUGIN_FILE', __FILE__ );

function rn_pbwp_add_menu_page(){
    add_menu_page( 
        __( 'Particle Background', 'particle-background-wp' ),
        __( 'Particle Background', 'particle-background-wp' ),
        'manage_options',
        'rn_pbwp_menu_page',
        'rn_pbwp_render_menu_page',
        'dashicons-image-filter',
        92
    );
}
add_action( 'admin_menu', 'rn_pbwp_add_menu_page' );

function rn_pbwp_admin_enqueue_scripts() {
	wp_enqueue_style( 'rn_pbwp-admin-css', RN_PBWP_PLUGINS_URL . '/includes/admin.css' );
}
add_action( 'admin_enqueue_scripts', 'rn_pbwp_admin_enqueue_scripts' );

function rn_pbwp_render_menu_page() {
	
	wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
	
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'rn_pbwp-admin-js', RN_PBWP_PLUGINS_URL . '/includes/admin.js', array( 'wp-color-picker' ) );
	 
	$nonce =  empty( $_POST['rn-pbwp-nonce'] ) ? false : sanitize_text_field( $_POST['rn-pbwp-nonce'] );
	$nonce_ok = wp_verify_nonce( $nonce, 'rn-pbwp-dash' );
	
	$css_target = get_option( 'rn-pbwp-css_target', false );
	$text = stripslashes_deep( get_option( 'rn-pbwp-text', '<p style="text-align: center;">Particle Background WP</p>' ) );
	$bg_color = get_option( 'rn-pbwp-bg_color', '#b61924' );
	$dot_color = get_option( 'rn-pbwp-dot_color', '#ffffff' );
	$particle_density = get_option( 'rn-pbwp-particle_density', '1' );
	$enable_front_page = get_option( 'rn-pbwp-enable_front_page', false );
	$enable_blog_page = get_option( 'rn-pbwp-enable_blog_page', false );
	$custom_css = get_option( 'rn-pbwp-custom_css', '' );
		
	if( $nonce_ok ) {
		
		$text = empty( $_POST['rn_pbwp_text'] ) ? '<p style="text-align: center;"> </p>' : wp_kses_post( stripslashes_deep( $_POST['rn_pbwp_text'] ) );
		$bg_color = empty( $_POST['rn_pbwp']['bg_color'] ) ? $bg_color : sanitize_text_field( $_POST['rn_pbwp']['bg_color'] );
		$dot_color = empty( $_POST['rn_pbwp']['dot_color'] ) ? $dot_color : sanitize_text_field( $_POST['rn_pbwp']['dot_color'] );
		$particle_density = empty( $_POST['rn_pbwp']['particle_density'] ) ? $particle_density : sanitize_text_field( $_POST['rn_pbwp']['particle_density'] );
		$custom_css = empty( $_POST['rn_pbwp']['custom_css'] ) ? '' : sanitize_textarea_field( $_POST['rn_pbwp']['custom_css'] );

		$css_target = empty( $_POST['rn_pbwp']['css_target'] ) ? false : sanitize_text_field( $_POST['rn_pbwp']['css_target'] );
		
		$enable_front_page = empty( $_POST['rn_pbwp']['enable_front_page'] ) ? false : true;
		$enable_blog_page = empty( $_POST['rn_pbwp']['enable_blog_page'] ) ? false : true;
		
		update_option( 'rn-pbwp-text', $text );
		update_option( 'rn-pbwp-dot_color', $dot_color );
		update_option( 'rn-pbwp-bg_color', $bg_color );
		update_option( 'rn-pbwp-particle_density', $particle_density );
		update_option( 'rn-pbwp-css_target', $css_target );
		
		update_option( 'rn-pbwp-enable_front_page', $enable_front_page );
		update_option( 'rn-pbwp-enable_blog_page', $enable_blog_page );
		update_option( 'rn-pbwp-custom_css', $custom_css );

	}
	
	if ( !empty( $_POST ) && !$nonce_ok ) {
		echo '<div class="rn-pbwp-dash error"><p>Failed to save, please try logging in again</p></div>';
	}
	
?>

<form method='post' id='rn_pbwp_admin_settings_form'>
	<?php wp_nonce_field( 'rn-pbwp-dash', 'rn-pbwp-nonce' ) ?>
	<div class='rn-pbwp-dash'>
		<h1>Particle Background WP</h1>
		<p><a href='https://particlebackground.com/?utm_medium=plugin' target='_blank'>Visit plugin home page</a></p>
		<p><a href='https://wordpress.org/support/plugin/particle-background-wp/' target='_blank'>Visit support forum</a></p>
		<p><a href='https://wordpress.org/support/plugin/particle-background-wp/reviews/?rate=5#new-post/' target='_blank'>Leave a review</a></p>
		<p><button type='submit' class='button button-primary' style='min-width: 140px;'>Save</button></p>
	</div>
	<div class='rn-pbwp-dash'>		
		<h2>Deploy</h2>
		<div class='setting'>
		<label>Shortcode 
		<input type='text' value='[particle-background-wp]' onclick='this.select()' readonly >
		</label>
		</div>
		<div class='setting'>
			<table>
				<tr>
					<th>Add to front page</th>
					<td><input type="checkbox" name="rn_pbwp[enable_front_page]" <?php checked( $enable_front_page ); ?> ></td>
				</tr>
				<tr>
					<th>Add to blog page</th>
					<td><input type="checkbox" name="rn_pbwp[enable_blog_page]" <?php checked( $enable_blog_page ); ?> ></td>
				</tr>
				<tr>
					<th>Add to matching CSS</th>
					<td><input type="text" name="rn_pbwp[css_target]" value="<?php echo esc_attr( $css_target ); ?>" ></td>
				</tr>
			</table>
		</div>
	</div>
	<div class='rn-pbwp-dash'>
		<h2>Content</h2>
		<div class='setting'>
			<?php wp_editor( $text, 'rn_pbwp_text' ); ?>
		</div>
	</div>
	<div class='rn-pbwp-dash'>	
		<h2>Settings</h2>
		
		<div class='setting'>
			<label>Particle Density</label><br>
			<progress class='particle-density-setting' max="100" id='density-value' value="<?php echo $particle_density ?>"></progress><br>
			<input class='particle-density-setting' type="range" min="0.01" max="1" step="0.01" id='density-range' name="rn_pbwp[particle_density]" value="<?php echo $particle_density ?>" >
		</div>

		<div class='setting inline'>
			<label>Dot Color</label>
			<input type="text" name="rn_pbwp[dot_color]" class="color-picker" value="<?php echo $dot_color ?>" >
		</div>	
		
		<div class='setting inline'>
			<label>Background Color</label>
			<input type="text" name="rn_pbwp[bg_color]" class="color-picker" value="<?php echo $bg_color ?>" >
			<label>Transparent
			<input type="checkbox" name="rn_pbwp[bg_color]" id="transparency-toggle" value="transparent" <?php checked( $bg_color === "transparent" ) ?> /></label>
		</div>
	</div>
	<div class='rn-pbwp-dash'>	
		<h2>Custom CSS</h2>
		<textarea rows='6' name="rn_pbwp[custom_css]" id="rn_pbwp_custom_css" ><?php echo $custom_css ?></textarea></label>
	</div>

</form>

<?php 
}

function rn_pbwp_enqueue_scripts() {
	
	$enable_front_page = get_option( 'rn-pbwp-enable_front_page', false );
	$enable_blog_page = get_option( 'rn-pbwp-enable_blog_page', false );
	$css_target = get_option( 'rn-pbwp-css_target', false );
	
	$enqueue = ( ( is_front_page() && $enable_front_page ) OR ( is_home() && $enable_blog_page ) );
	
	if ( apply_filters( 'rn-pbwp-enqueue', $enqueue ) ) {
		rn_pbwp_add_particles();
	}
	
	if ( $css_target ) {
		rn_pbwp_add_particles_target( $css_target );
	}
}
add_action( 'wp_enqueue_scripts', 'rn_pbwp_enqueue_scripts' );

function rn_pbwp_shortcode( $atts = array() ) {
	$id = uniqid ( 'rnpb-' );
	rn_pbwp_add_particles_target( '#' . $id );
	return "<div id='$id'></div>";
}
add_shortcode( 'particle-background-wp', 'rn_pbwp_shortcode' );

function rn_pbwp_add_particles() {
	
	$path = get_stylesheet_directory() . '/particle-background-wp/particlesjs-config.json';
	$json_config = false;
	
	if ( file_exists( $path ) ) {
		$json_config = file_get_contents( $path );
	}

	$rnPbwpData = array(
		'text' => do_shortcode( get_option( 'rn-pbwp-text', '' ) ),
		'bg_color' => get_option( 'rn-pbwp-bg_color', '#b61924' ),
		'dot_color' => get_option( 'rn-pbwp-dot_color', '#ffffff' ),
		'particle_density' => get_option( 'rn-pbwp-particle_density', 1 ),
		'custom_json' => json_decode( apply_filters( 'rn-pbwp-custom-json', $json_config ) ),
		'custom_css' => get_option( 'rn-pbwp-custom_css', '' )
	);
			
	wp_enqueue_script( 'rn_pbwp-particle-js', RN_PBWP_PLUGINS_URL . '/includes/particles.min.js' );
	wp_enqueue_script( 'rn_pbwp-app-js', RN_PBWP_PLUGINS_URL . '/includes/rn-pb-wp.js', array('rn_pbwp-particle-js'), RN_PBWP_PLUGIN_VER, true );

	wp_localize_script( 'rn_pbwp-app-js', 'rnPbwpData', $rnPbwpData );
	
}

function rn_pbwp_add_particles_target( $css_target ) {
	
	$path = get_stylesheet_directory() . '/particle-background-wp/particlesjs-config.json';
	$json_config = false;
	
	if ( file_exists( $path ) ) {
		$json_config = file_get_contents( $path );
	}

	$rnPbwpData = array(
		'css_target' => $css_target,
		'text' => do_shortcode( get_option( 'rn-pbwp-text', '' ) ),
		'bg_color' => get_option( 'rn-pbwp-bg_color', '#b61924' ),
		'dot_color' => get_option( 'rn-pbwp-dot_color', '#ffffff' ),
		'particle_density' => get_option( 'rn-pbwp-particle_density', 1 ),
		'custom_json' => json_decode( apply_filters( 'rn-pbwp-custom-json', $json_config ) ),
		'custom_css' => get_option( 'rn-pbwp-custom_css', '' )
	);
			
	wp_enqueue_script( 'rn_pbwp-particle-js', RN_PBWP_PLUGINS_URL . '/includes/particles.min.js' );
	wp_enqueue_script( 'rn_pbwp-app-css-target-js', RN_PBWP_PLUGINS_URL . '/includes/css-particle-target.js', array('rn_pbwp-particle-js'), RN_PBWP_PLUGIN_VER, true );

	wp_localize_script( 'rn_pbwp-app-css-target-js', 'rnPbwpData', $rnPbwpData );
	
}