<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_shortcode( 'TBMS', 'tbms_shortcode' );
function tbms_shortcode( $post_id ) {

	ob_start();
	$tbms_post_settings = get_post_meta( $post_id['id'], 'tbms_post_data_' . $post_id['id'], true );

	$tbms_post_id = $post_id['id'];

	// fetch all Team
	$tbms_post_data = array(
		'p'         => $tbms_post_id,
		'post_type' => 'tbms_cpt_name',
		'orderby'   => 'ASC',
	);
	$tbms_loop      = new WP_Query( $tbms_post_data );

	wp_enqueue_script( 'awplife-tbms-bootstrap-js', TBMS_PLUGIN_URL . 'assets/js/bootstrap.js', array( 'jquery' ), '', false );
	wp_enqueue_style( 'awplife-tbms-bootstrap-css', TBMS_PLUGIN_URL . 'assets/css/tbms-frontend-bootstrap.css' );
	wp_enqueue_style( 'awplife-tbms-font-awesome-css', TBMS_PLUGIN_URL . 'assets/css/font-awesome.css' );

	// post Setting
	if ( isset( $tbms_post_settings['tbms_template_design'] ) ) {
		$tbms_template_design = $tbms_post_settings['tbms_template_design'];
	} else {
		$tbms_template_design = 'template1';
	}
	if ( isset( $tbms_post_settings['tbms_image_size'] ) ) {
		$tbms_image_size = $tbms_post_settings['tbms_image_size'];
	} else {
		$tbms_image_size = 'medium';
	}
	if ( isset( $tbms_post_settings['tbms_total_column'] ) ) {
		$tbms_total_column = $tbms_post_settings['tbms_total_column'];
	} else {
		$tbms_total_column = '';
	}
	if ( isset( $tbms_post_settings['tbms_background_team_color'] ) ) {
		$tbms_background_team_color = $tbms_post_settings['tbms_background_team_color'];
	} else {
		$tbms_background_team_color = '#34495e';
	}
	if ( isset( $tbms_post_settings['tbms_decription_color'] ) ) {
		$tbms_decription_color = $tbms_post_settings['tbms_decription_color'];
	} else {
		$tbms_decription_color = '#ffffff';
	}
	if ( isset( $tbms_post_settings['tbms_link_tab'] ) ) {
		$tbms_link_tab = $tbms_post_settings['tbms_link_tab'];
	} else {
		$tbms_link_tab = '_blank';
	}
	if ( isset( $tbms_post_settings['tbms_custom_css'] ) ) {
		$tbms_custom_css = $tbms_post_settings['tbms_custom_css'];
	} else {
		$tbms_custom_css = '';
	}
	include 'include/non-carousel/no-owl-shotcode.php';
	if ( $tbms_template_design == 'template1' ) {
		$template_number = 'template1';
		include 'assets/css/template1.php'; }
	if ( $tbms_template_design == 'template2' ) {
		$template_number = 'template2';
		include 'assets/css/template2.php'; }
	if ( $tbms_template_design == 'template3' ) {
		$template_number = 'template3';
		include 'assets/css/template3.php'; }
	if ( $tbms_template_design == 'template4' ) {
		$template_number = 'template4';
		include 'assets/css/template4.php'; }
	?>
<style>	
	<?php echo $tbms_custom_css; ?>
</style>	
	<?php
	wp_reset_query();
	return ob_get_clean();
}
?>
