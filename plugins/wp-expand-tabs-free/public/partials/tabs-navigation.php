<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/public/partials
 */

?>
<ul class="sp-tab__nav sp-tab__nav-tabs" id="sp-tab__ul" role="tablist">
	<?php
	if ( is_array( $sptpro_data_src ) || is_object( $sptpro_data_src ) ) {
		$sptpro_data_count = 1;
		foreach ( $sptpro_data_src as $key => $sptpro_data ) {
			$sptpro_active_class       = $sptpro_tab_opened === $sptpro_data_count ? ' sp-tab__active' : '';
			$tabs_content_title        = '<' . $sptpro_title_heading_tag . ' class="sp-tab__tab_title">' . $sptpro_data['tabs_content_title'] . '</' . $sptpro_title_heading_tag . '>';
			$tabs_aria_controls_for_id = 'tab-' . $post_id . $sptpro_data_count;
			?>
			<li class="sp-tab__nav-item" role="tab">
				<label class="sp-tab__nav-link<?php echo esc_attr( $sptpro_active_class ); ?>" data-sptoggle="tab" for="#<?php echo esc_attr( $tabs_aria_controls_for_id ); ?>" role="tab" <?php echo sprintf( esc_attr( $title_data_attr ), esc_attr( $tabs_aria_controls_for_id ) ); ?>>
					<span class="tab_title_area"><?php echo wp_kses_post( $tabs_content_title ); ?></span>
				</label>
			</li>
			<?php
			$sptpro_data_count++;
		}
	}
	?>
</ul>
