<?php
/**
 * Getting Started Panel.
 *
 * @package fag
 */
?>
<div id="getting-started-panel" class="panel-left visible">
	<div class="panel-aside panel-column">
	 
	<?php
	$fag_free_plugins = array(
		'awp-companion' => array(
			'name'     => 'Slider Factory',
			'slug'     => 'slider-factory',
			'filename' => 'slider-factory.php',
		),
	);
	if ( ! empty( $fag_free_plugins ) ) {
		?>
		<div class="recomended-plugin-wrap">
		<?php
		foreach ( $fag_free_plugins as $fag_plugin ) {
			$fag_info = fag_call_plugin_api( $fag_plugin['slug'] );
			?>
				
			<h4 title="">
				<?php esc_html_e( 'Slider Factory', 'flickr-album-gallery' ); ?>
			</h4>
			<p class="mt-0">Create a slider by selecting layout, add slide image, configure setting, generate slider shortcode and embed slider shortcode on any page or post to start slide show.</p>
			<?php
			echo '<div class="mt-12">';
			echo fag_Getting_Started_Page_Plugin_Helper::instance()->get_button_html( $fag_plugin['slug'] );
			echo '</div>';
			?>

			</br>
			<?php
		}
		?>
		</div>
		<?php
	}
	?>
	 
	 
	 
	 
	</div> 

	<div class="panel-aside panel-column">
		<h4>Slider Factory Plugin Demo</h4>
		<a target="_blank" href="<?php echo esc_url( 'https://wpfrank.com/demo/slider-factory-pro/' ); ?>">
			<img src="<?php echo esc_url( FAG_PLUGIN_URL . '/plugin-notice/admin/images/slider-factory-banner.gif' ); ?>">
		</a>
		<a class="button button-primary" target="_blank" href="<?php echo esc_url( 'https://wpfrank.com/demo/slider-factory-pro/' ); ?>" title="<?php esc_attr_e( 'Check Demo', 'flickr-album-gallery' ); ?>">Check Demo</a>
		<a class="button button-success" target="_blank" href="<?php echo esc_url( 'https://wpfrank.com/wordpress-plugins/slider-factory-pro/' ); ?>" title="<?php esc_attr_e( 'Go To Pro', 'flickr-album-gallery' ); ?>">Got Pro Plugin</a>
	</div>
</div>
