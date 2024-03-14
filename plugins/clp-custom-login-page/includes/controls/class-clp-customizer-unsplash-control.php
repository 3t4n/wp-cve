<?php
	/**
	 * Connect to Unsplash via API
     * @author chozze
	 * @version 1.0.0
	 */
class CLP_Customizer_Unsplash_Control extends \WP_Customize_Control {
	
	public $type = 'unsplash';

	public function enqueue() {
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'customizer-unsplash', CLP_PLUGIN_PATH . 'assets/js/customizer-unsplash.js', array('jquery', 'masonry'), CLP_Helper_Functions::assets_version('assets/js/customizer-unsplash.js'), true );
		wp_enqueue_style( 'customizer-unsplash', CLP_PLUGIN_PATH . 'assets/css/customizer-unsplash.css', array(), CLP_Helper_Functions::assets_version('assets/css/customizer-unsplash.css') );
	}

	public function render_content() {

		$json_unsplash = $this->value() ? json_decode($this->value(), true) : json_decode('{}', true);

		?>
        <button id="clp-customizer-unsplash" class="clp-customizer-btn" data-nonce="<?php echo wp_create_nonce( 'clp-custom-login-page-unsplash' );?>"><?php echo esc_html( $this->label ); ?></button>
        <span class="description customize-control-description"><?php echo $this->description; ?></span>
        <input type="hidden" value="<?php echo esc_attr( $this->value() ); ?>" id="clp-background-unsplash-url"
            <?php
            $this->input_attrs();
            $this->link();
            ?>
		>

		<div class="unsplash-info" style="display:<?php echo !empty( $json_unsplash ) ? 'block': 'none';?>">
			<img class="clp-unsplash-thumbnail" src="<?php echo !empty( $json_unsplash ) ? $json_unsplash['urls']['small'] : null;?>"></img>
			<p class="unsplash-copyright" style="margin-top:0">
				<a href="<?php echo !empty( $json_unsplash ) ? esc_attr( $json_unsplash['link'] ) : null;?>" target="_blank" class="clp-unsplash-link-html"><?php _e('Photo', 'clp-custom-login-page');?></a>
				<span> by </span>
				<a href="<?php echo !empty( $json_unsplash ) ? esc_attr( $json_unsplash['userlink'] ) : null;?>" target="_blank" class="clp-unsplash-link-portfolio_url"><?php echo !empty( $json_unsplash ) ? esc_attr( $json_unsplash['username'] ) : null;?></a>
				<span> / </span>
				<a href="https://unsplash.com" target="_blank">Unsplash</a>
				<a href="#remove-photo" id="clp-remove-unsplash-photo" style="float:right;color:red"><?php _e('Remove', 'clp-custom-login-page');?></a>
			</p>
		</div>
		
		

		<div id="clp-unsplash-modal" style="display:none">
			<div tabindex="0" class="media-modal wp-core-ui" role="dialog" aria-labelledby="media-frame-title">
			
				<button type="button" class="clp-unsplash-modal-close media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text"><?php _e('Close dialog', 'clp-custom-login-page');?></span></span></button>
			
				<div class="media-modal-content" role="document">
					<div class="media-frame mode-select wp-core-ui hide-menu">
						<div class="media-frame-title"><h1><?php echo esc_html( $this->label ); ?></h1></div>
		
						<div class="media-frame-tab-panel">
			
							<div class="media-frame-content" data-columns="3">
								<div class="unsplash-browser attachments-browser">
									<div class="media-toolbar">
										<div class="media-toolbar-primary search-form">
											<label for="media-search-input" class="unsplash-search-input-label"><?php _e('Search', 'clp-custom-login-page');?></label>
											<span class="spinner"></span>
											<input type="search" placeholder="<?php _e('Search free high-resolution photos..', 'clp-custom-login-page');?>" autofocus="autofocus" id="unsplash-search-input" class="unsplash-search">
										</div>
									</div>

									<div id="clp-unsplash-images">
										<div id="load-more" style="position:absolute;bottom:0;height:2px"></div>
									</div>

									<div class="no-media show">
										<h2><?php _e('No Items found', 'clp-custom-login-page');?></h2>
									</div>
								</div>
							</div>
						</div>
			
						<div class="media-frame-toolbar">
							<div class="media-toolbar">
								<div class="media-toolbar-primary bottom">
									<span class="spinner"></span>
									<button id="clp-unsplash-select-button" type="button" class="button media-button button-primary button-large media-button-select" disabled="disabled"><?php _e('Select', 'clp-custom-login-page');?></button>
								</div>
							</div>
						</div>
		
					</div>
				</div>
			</div>
		
			<div class="media-modal-backdrop"></div>
		</div>
		<?php
	}

}