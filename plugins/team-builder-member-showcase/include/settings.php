<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_style( 'awplife-tbms-toogle-css', TBMS_PLUGIN_URL . 'assets/css/toogle-button.css' );
wp_enqueue_script( 'awplife-tbms-popper-min-js', TBMS_PLUGIN_URL . 'assets/js/popper.min.js', array( 'jquery' ) );
wp_enqueue_script( 'awplife-tbms-bootstrap-js', TBMS_PLUGIN_URL . 'assets/js/bootstrap.js', array( 'jquery' ) );

// code
$tbms_post_settings = get_post_meta( $post->ID, 'tbms_post_data_' . $post->ID, true );
?>
<div class="team-titles"><?php esc_html_e( 'Member Image Size', 'team-builder-member-showcase' ); ?> </div>
<?php
if ( isset( $tbms_post_settings['tbms_image_size'] ) ) {
	$tbms_image_size = $tbms_post_settings['tbms_image_size'];
} else {
	$tbms_image_size = 'tbms-custom-300';
}
?>
<select id="tbms_image_size" name="tbms_image_size" class="form-control tbms-tooltips" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e( 'Note: Custom cropped image will be available when you upload new images after plugin installation.', 'team-builder-member-showcase' ); ?>">
	<option value="thumbnail" 
	<?php
	if ( $tbms_image_size == 'thumbnail' ) {
		echo 'selected=selected';}
	?>
	><?php esc_html_e( 'Thumbnail', 'team-builder-member-showcase' ); ?></option>
	<option value="medium" 
	<?php
	if ( $tbms_image_size == 'medium' ) {
		echo 'selected=selected';}
	?>
	><?php esc_html_e( 'Medium', 'team-builder-member-showcase' ); ?></option>
	<option value="large" 
	<?php
	if ( $tbms_image_size == 'large' ) {
		echo 'selected=selected';}
	?>
	><?php esc_html_e( 'Large', 'team-builder-member-showcase' ); ?></option>
	<option value="full" 
	<?php
	if ( $tbms_image_size == 'full' ) {
		echo 'selected=selected';}
	?>
	><?php esc_html_e( 'Full', 'team-builder-member-showcase' ); ?></option>
	<option value="tbms-custom-300" 
	<?php
	if ( $tbms_image_size == 'tbms-custom-300' ) {
		echo 'selected=selected';}
	?>
	><?php esc_html_e( 'Custom 300x300', 'team-builder-member-showcase' ); ?></option>
	<option value="tbms-custom-500" 
	<?php
	if ( $tbms_image_size == 'tbms-custom-500' ) {
		echo 'selected=selected';}
	?>
	><?php esc_html_e( 'Custom 500x500', 'team-builder-member-showcase' ); ?></option>
</select>
<div class="team-titles"><?php esc_html_e( 'Team Members Per Row', 'team-builder-member-showcase' ); ?></div>
<?php
if ( isset( $tbms_post_settings['tbms_total_column'] ) ) {
	$tbms_total_column = $tbms_post_settings['tbms_total_column'];
} else {
	$tbms_total_column = 'col-md-3';
}
?>
<select id="tbms_total_column" name="tbms_total_column" class="form-control">
	<option value="col-md-3" 
	<?php
	if ( $tbms_total_column == 'col-md-3' ) {
		echo 'selected=selected';}
	?>
	><?php esc_html_e( 'Four Member', 'team-builder-member-showcase' ); ?></option>
	<option value="col-md-4" 
	<?php
	if ( $tbms_total_column == 'col-md-4' ) {
		echo 'selected=selected';}
	?>
	><?php esc_html_e( 'Three Member', 'team-builder-member-showcase' ); ?></option>
</select>

<div class="team-titles"><?php esc_html_e( 'Background & Image Hover Color', 'team-builder-member-showcase' ); ?></div>
<?php
if ( isset( $tbms_post_settings['tbms_background_team_color'] ) ) {
	$tbms_background_team_color = $tbms_post_settings['tbms_background_team_color'];
} else {
	$tbms_background_team_color = '#34495e';
}
?>
<input type="text" id="tbms_background_team_color" name="tbms_background_team_color" value="<?php echo esc_html( $tbms_background_team_color ); ?>" default-color="<?php echo esc_html( $tbms_background_team_color ); ?>">		

<div class="team-titles"><?php esc_html_e( 'Member Bio Color', 'team-builder-member-showcase' ); ?></div>
<?php
if ( isset( $tbms_post_settings['tbms_decription_color'] ) ) {
	$tbms_decription_color = $tbms_post_settings['tbms_decription_color'];
} else {
	$tbms_decription_color = '#ffffff';
}
?>
<input type="text" id="tbms_decription_color" name="tbms_decription_color" value="<?php echo esc_html( $tbms_decription_color ); ?>" default-color="<?php echo esc_html( $tbms_decription_color ); ?>">		

<div class="team-titles"><?php esc_html_e( 'Open Link In', 'team-builder-member-showcase' ); ?></div>
<p class="switch-field em_size_field">
	<?php
	if ( isset( $tbms_post_settings['tbms_link_tab'] ) ) {
		$tbms_link_tab = $tbms_post_settings['tbms_link_tab'];
	} else {
		$tbms_link_tab = '_blank';
	}
	?>
	<input type="radio" name="tbms_link_tab" id="team_link_tab1" value="_blank" 
	<?php
	if ( $tbms_link_tab == '_blank' ) {
		echo 'checked=checked';}
	?>
	>
	<label for="team_link_tab1"><?php esc_html_e( 'New Tab', 'team-builder-member-showcase' ); ?></label>
	<input type="radio" name="tbms_link_tab" id="team_link_tab2" value="_self" 
	<?php
	if ( $tbms_link_tab == '_self' ) {
		echo 'checked=checked';}
	?>
	>
	<label for="team_link_tab2"><?php esc_html_e( 'Same Tab', 'team-builder-member-showcase' ); ?></label>
</p>
<div class="team-titles"><?php esc_html_e( 'Custom Css', 'team-builder-member-showcase' ); ?></div>
<?php
if ( isset( $tbms_post_settings['tbms_custom_css'] ) ) {
	$tbms_custom_css = $tbms_post_settings['tbms_custom_css'];
} else {
	$tbms_custom_css = '';
}
?>
<p>
<textarea type="text" id="tbms_custom_css" name="tbms_custom_css" class="form-control" placeholder="<?php esc_html_e( 'Custom Css', 'team-builder-member-showcase' ); ?>" rows="3"><?php echo $tbms_custom_css; ?></textarea>
</p>

<script>
jQuery(document).ready(function(){
	// bootstrap tooltips
	jQuery('.tbms-tooltips').tooltip();
});
</script>
