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
<div class="sp-tab__tab-content">
<?php
if ( is_array( $sptpro_data_src ) || is_object( $sptpro_data_src ) ) {
	$sptpro_cont_count = 1;
	foreach ( $sptpro_data_src as $key => $sptpro_data ) {
		global $wp_embed;
		$sptpro_active_tab_class = $sptpro_tab_opened === $sptpro_cont_count ? '' : 'collapsed';
		$sptpro_active_class     = $sptpro_tab_opened === $sptpro_cont_count ? 'sp-tab__show sp-tab__active' : '';
		$tabs_pane_variable_id   = 'tab-' . $post_id . $sptpro_cont_count;

		$sptpro_content       = apply_filters( 'sp_wp_tabs_content', $sptpro_data['tabs_content_description'] );
		$sptpro_content_embed = str_replace( ']]>', ']]&gt;', $sptpro_content );
		if ( apply_filters( 'sp_wp_tabs_autop_remove', true ) ) {
			$sptpro_content_embed = wpautop( trim( $sptpro_content_embed ) );
		}
		$tabs_content_description = do_shortcode( shortcode_unautop( $wp_embed->autoembed( $sptpro_content_embed ) ) );

		switch ( $sptpro_tabs_on_small_screen ) {
			case 'full_widht':
				?>
				<div id="<?php echo esc_attr( $tabs_pane_variable_id ); ?>" class="sp-tab__tab-pane <?php echo esc_attr( $sptpro_active_class ); ?>" role="tabpanel">
					<div class="sp-tab-content <?php echo esc_attr( $animation_name ); ?>"><?php echo $tabs_content_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				</div>
				<?php
				break;
			case 'accordion_mode':
				$sptpro_show_class           = $sptpro_tab_opened === $sptpro_cont_count ? 'sp-tab__show' : '';
				$tabs_pane_variable_controls = 'collapse-' . $post_id . $sptpro_cont_count;
				$tabs_pane_variable_heading  = 'heading-' . $post_id . $sptpro_cont_count;
				$tabs_content_title          = $sptpro_data['tabs_content_title'];
				$aria_expanded               = $sptpro_show_class ? 'true' : 'false';
				?>
				<div id="<?php echo esc_attr( $tabs_pane_variable_id ); ?>" class="sp-tab__card sp-tab__tab-pane <?php echo esc_attr( $sptpro_active_class ); ?>" role="tabpanel" aria-labelledby="aria-<?php echo esc_attr( $tabs_pane_variable_id ); ?>">
					<label class="<?php echo esc_attr( $sptpro_active_tab_class ); ?>" data-sptoggle="collapse" for="#<?php echo esc_attr( $tabs_pane_variable_controls ); ?>" aria-expanded="<?php echo esc_attr( $aria_expanded ); ?>" aria-controls="<?php echo esc_attr( $tabs_pane_variable_controls ); ?>">
						<div class="sp-tab__card-header" role="sptab" id="<?php echo esc_attr( $tabs_pane_variable_heading ); ?>"><?php echo esc_html( $tabs_content_title ); ?></div>
					</label>

					<div id="<?php echo esc_attr( $tabs_pane_variable_controls ); ?>" class="sp-tab__collapse <?php echo esc_attr( $sptpro_show_class ); ?>" data-parent="#content" role="tabpanel" aria-labelledby="<?php echo esc_attr( $tabs_pane_variable_heading ); ?>">
						<div class="sp-tab__card-body">
							<div class="sp-tab-content <?php echo esc_attr( $animation_name ); ?>"><?php echo $tabs_content_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						</div>
					</div>
				</div>
				<?php
				break;
		}
		$sptpro_cont_count++;
	}
}
?>
</div>
