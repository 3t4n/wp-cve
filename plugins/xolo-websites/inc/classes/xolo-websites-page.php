<?php
/**
 * The plugin page view - the "settings" page of the plugin.
 *
 * @package XOLO WEBSITES
 */

namespace XOLO_WEBS;

$predefined_themes = $this->import_files;

if ( ! empty( $this->import_files ) && isset( $_GET['import-mode'] ) && 'manual' === $_GET['import-mode'] ) {
	$predefined_themes = array();
}


$XOLO_WEB_theme = wp_get_theme();

if ( isset( $_GET['xolo_notice'] ) ) {
	if ( $_GET['xolo_notice'] == 'dismiss-get-started' && $XOLO_WEB_theme->get( 'Author' ) == 'xolosoftware' ) {
		update_option( 'dismissed-get_started', TRUE );
	}
}

/**
 * Hook for adding the custom plugin page header
 */
do_action( 'XOLO-WEBSITES/plugin_page_header' );
?>

<div class="xl-websites"> <!-- wrap about-wrap -->
	<?php ob_start(); ?>	
		<?php if( $XOLO_WEB_theme->get( 'Author' ) !== 'xolosoftware' ) : ?>
			<div class="xl-theme-header xl-header-fixed">
				<div class="xl-theme-title">
					<a href="<?php echo esc_url( admin_url( 'themes.php?page=xolo-websites' ) ); ?>" class="xl-logo">
						<span class="xl-logo-icon"><i class="dashicons-before dashicons-upload"></i> <span class="xl-version xl-cusror-off"><?php echo esc_html(XOLO_WEB_VERSION); ?></span></span>
						<img src="<?php echo esc_url(XOLO_WEB_DIR_URI .'assets/images/logo.png'); ?>" class="xl-title">
					</a>
				</div>
				<div class="xl-top-links">
					<ul>
						<li class="xl-most-links">
							<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="xl-btn xl-btn-fill"><?php esc_html_e( 'Customize', 'xolo-websites' ); ?></a>
						</li>
					</ul>
				</div>
			</div>
		<?php endif; ?>	
	<?php
	$plugin_title = ob_get_clean();

	// Display the plugin title (can be replaced with custom title text through the filter below).
	echo wp_kses_post( apply_filters( 'XOLO-WEBSITES/plugin_page_title', $plugin_title ) );

	// Display warrning if PHP safe mode is enabled, since we wont be able to change the max_execution_time.
	if ( ini_get( 'safe_mode' ) ) {
		printf(
			esc_html__( '%sWarning: your server is using %sPHP safe mode%s. This means that you might experience server timeout errors.%s', 'xolo-websites' ),
			'<div class="notice  notice-warning  is-dismissible"><p>',
			'<strong>',
			'</strong>',
			'</p></div>'
		);
	}

	// Start output buffer for displaying the plugin intro text.
	ob_start();
	?>

	<?php
	$plugin_intro_text = ob_get_clean();

	// Display the plugin intro text (can be replaced with custom text through the filter below).
	echo wp_kses_post( apply_filters( 'XOLO-WEBSITES/plugin_intro_text', $plugin_intro_text ) );
	?>

	<?php if ( empty( $this->import_files ) ) : ?>
		<div class="notice  notice-info  is-dismissible">
			<p><?php esc_html_e( 'There are no predefined import files available in this theme. Please upload the import files manually!', 'xolo-websites' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( empty( $predefined_themes ) ) : ?>

		<div class="XOLO_WEB__file-upload-container">
			<h2><?php esc_html_e( 'Manual demo files upload', 'xolo-websites' ); ?></h2>

			<div class="XOLO_WEB__file-upload">
				<h3><label for="content-file-upload"><?php esc_html_e( 'Choose a XML file for content import:', 'xolo-websites' ); ?></label></h3>
				<input id="XOLO_WEB__content-file-upload" type="file" name="content-file-upload">
			</div>

			<div class="XOLO_WEB__file-upload">
				<h3><label for="widget-file-upload"><?php esc_html_e( 'Choose a WIE or JSON file for widget import:', 'xolo-websites' ); ?></label></h3>
				<input id="XOLO_WEB__widget-file-upload" type="file" name="widget-file-upload">
			</div>

			<div class="XOLO_WEB__file-upload">
				<h3><label for="customizer-file-upload"><?php esc_html_e( 'Choose a DAT file for customizer import:', 'xolo-websites' ); ?></label></h3>
				<input id="XOLO_WEB__customizer-file-upload" type="file" name="customizer-file-upload">
			</div>

			<?php if ( class_exists( 'ReduxFramework' ) ) : ?>
			<div class="XOLO_WEB__file-upload">
				<h3><label for="redux-file-upload"><?php esc_html_e( 'Choose a JSON file for Redux import:', 'xolo-websites' ); ?></label></h3>
				<input id="xolo_web__redux-file-upload" type="file" name="redux-file-upload">
				<div>
					<label for="redux-option-name" class="XOLO_WEB_redux-option-name-label"><?php esc_html_e( 'Enter the Redux option name:', 'xolo-websites' ); ?></label>
					<input id="xolo_web__redux-option-name" type="text" name="redux-option-name">
				</div>
			</div>
			<?php endif; ?>
		</div>

		<p class="xl-button-container">
			<button class="button button-hero button-primary xl-import-data"><?php esc_html_e( 'Import Demo Data', 'xolo-websites' ); ?></button>
		</p>

	<?php else : ?>

		<!-- xolo websites grid layout -->
		<div class="xl-page-content">
		<?php
			// Prepare navigation data.
			$categories = Helpers::get_all_demo_import_categories( $predefined_themes );
		?>
			<?php if ( ! empty( $categories ) ) : ?>
				<div class="xl-tab-panel">
					<div class="xl-theme-body">
		                <div class="xl-tabs">							
							<nav id="xlTabScroll" class="xl-tabs-scroll">								
			                    <div id="xlTabScrollMenu" class="xl-tabs-menu">
			                        <a href="#all" class="xl-tabs-link" aria-selected="true"><?php esc_html_e( 'All', 'xolo-websites' ); ?></a>
			                        <?php foreach ( $categories as $key => $name ) : ?>
										<a href="#<?php echo esc_attr( $key ); ?>" class="xl-tabs-link"><?php echo esc_html( $name ); ?></a>
									<?php endforeach; ?>
									<span id="tabActive" class="xl-tab-active"></span>
			                    </div>
		                	</nav>
		                	<div id="scrollerLeft" class="scroller scroller-left"><i class="dashicons dashicons-arrow-left-alt2"></i></div>
							<div id="scrollerRight" class="scroller scroller-right"><i class="dashicons dashicons-arrow-right-alt2"></i></div>
		                </div>
		                <div class="xl-pull-right">
		                	<div class="xl-search-form xl-tab-search">
		                		<input type="search" class="xl-quick-search" name="xl-qk-search" value="" placeholder="<?php esc_html_e( 'Search demos...', 'xolo-websites' ); ?>">
		                		<i class="dashicons dashicons-search"></i>
		                	</div>
		                </div>	                
		            </div>
				</div>
			<?php endif; ?>

			<div class="xl-sites-panel wp-clearfix">
				<div class="xl-sites-wrapper" id="wrap-disk">
					<?php foreach ( $predefined_themes as $index => $import_file ) : ?>
						<?php
							// Prepare import item display data.
							$img_src = isset( $import_file['import_preview_image_url'] ) ? $import_file['import_preview_image_url'] : '';
							// Default to the theme screenshot, if a custom preview image is not defined.
							if ( empty( $img_src ) ) {
								$theme = wp_get_theme();
								$img_src = $theme->get_screenshot();
							}
						?>
						<div class="xl-sites-items" data-categories="<?php echo esc_attr( Helpers::get_demo_import_item_categories( $import_file ) ); ?>" data-name="<?php echo esc_attr( strtolower( $import_file['import_file_name'] ) ); ?>">
							<div class="xl-items-inner">
								<?php if ( ! empty( $import_file['premium_url'] ) ) : ?>
								<div class="xl-pro-badge xl-cusror-off">
									<p><b>
											<span class="xl-pro-text"><?php esc_html_e( 'PREMIUM', 'xolo-websites' ); ?></span>
									</b></p>
								</div><!-- /.xl-pro-badge -->
								<?php endif; ?>
								<div class="xl-demo-screenshot">
									<?php if ( ! empty( $img_src ) ) : ?>
										<div class="xl-demo-image" style="background-image: url(<?php echo esc_url( $img_src ) ?>);"></div>
									<?php else : ?>
										<div class="xl-demo-image  xl-demo-image--no-image"><?php esc_html_e( 'No preview image.', 'xolo-websites' ); ?></div>
									<?php endif; ?>
									<div class="xl-demo-actions">
										<?php if ( ! empty( $import_file['preview_url'] ) ) : ?>
											<a class="xl-btn xl-btn-outline" href="<?php echo esc_url( $import_file['preview_url'] ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'xolo-websites' ); ?></a>
										<?php endif; ?>

										<?php if( $XOLO_WEB_theme->get( 'Author' ) == 'xolosoftware' ) : ?>
											<?php if ( ! empty( $import_file['premium_url'] ) && XOLO_WEB_PLUGIN_STATE == 'free' ) : ?>
												<a class="xl-btn xl-btn-fill" href="<?php echo esc_url( $import_file['premium_url']  ); ?>" target="_blank"><?php esc_html_e( 'Buy Now', 'xolo-websites' ); ?></a>
											<?php else : ?>
												<button class="xl-btn xl-btn-fill-green xl-demo-import-data" value="<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Import', 'xolo-websites' ); ?></button>
											<?php endif; ?>
										<?php else : ?>
											<a href="#" class="xl-btn xl-btn-fill-green xl-demo-import-data xl-demo-disabled" disabled="disabled" data-title="<?php esc_html_e( 'Please Active Xolo Theme', 'xolo-websites' ); ?>" value="<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Import', 'xolo-websites' ); ?></a>
										<?php endif; ?>
									</div>
								</div>
								<div class="xl-demo-meta<?php echo ! empty( $import_file['preview_url'] ) ? '  xl-demo-meta--with-preview' : ''; ?>">
									<div class="xl-demo-name"><h4 title="<?php echo esc_attr( $import_file['import_file_name'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></h4></div>									
								</div>
							</div>
						</div>
					<?php endforeach; ?>					
				</div>
				<div class="xl-sites-empty" style="display: none;">
					<h4><?php esc_html_e( 'No Demo More Found', 'xolo-websites' ); ?></h4>
					<a class="xl-btn xl-btn-fill" href="#" target="_blank"><?php esc_html_e( 'Xolo Website Demo', 'xolo-websites' ); ?></a>
				</div>
				<div class="XOLO_WEB__response js-xolo_web-ajax-response"></div>
			</div>			
		</div>
		<?php if( $XOLO_WEB_theme->get( 'Author' ) !== 'xolosoftware' ) : ?>
			<div class="xl-wpfooter">
				<p class="xl-demo-notice"><?php esc_html_e( 'New demos coming soon', 'xolo-websites' ); ?></p>
				<div class="xl-wpfooter-wrap">
					<ul class="xl-footer">
						<li><p id="footer-ver" class="footer-ver text-left"><?php esc_html_e( 'Xolo Websites v', 'xolo-websites' ); echo esc_html(XOLO_WEB_VERSION); ?></p></li>
						<li><span class="heart-icon">❤️</span> <a href="https://wordpress.org/support/plugin/xolo-websites/reviews/#new-post" target="_blank" class="xl-review"><?php esc_html_e( 'Leave a Review', 'xolo-websites' ); ?></a></li>
						<li><p id="footer-right" class="footer-social text-right">
							<a href="#" target="_blank"><span class="dashicons dashicons-twitter"></span></a>
							<a href="#" target="_blank"><span class="dashicons dashicons-facebook"></span></a>
						</p></li>
					</ul>
				</div>
			</div>
		<?php endif; ?>
		<div id="xl-modal-content"></div>

	<?php endif; ?>

	<div class="xl-websites-ajax-loader js-XOLO_WEB-ajax-loader">
		<div class="xl-ajax-loader">
			<span class="spinner"></span> <?php esc_html_e( 'Importing, please wait!', 'xolo-websites' ); ?>
		</div>
	</div>

	<div class="xl-websites-plugin js-XOLO_WEB-install-plugin">
		<div class="xl-install-plugin">
			<span class="spinner"></span> <?php esc_html_e( 'Installing Recommended Plugins, please wait!', 'xolo-websites' ); ?>
		</div>
	</div>

</div>

<?php
/**
 * Hook for adding the custom admin page footer
 */
do_action( 'XOLO-WEBSITES/plugin_page_footer' );
