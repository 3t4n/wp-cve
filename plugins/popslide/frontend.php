<?php
/**
 * Popslide front-end
 */

/**
 * Popslide front-end class
 */
class POPSLIDE_FRONT {

	public function __construct() {
		global $popslide;

		$this->settings = $popslide->get_settings();

		$enable = apply_filters( 'popslide/enable', true );

		if ( $enable ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'load_front_assets' ) );
			add_action( 'wp_head', array( $this, 'load_front_css' ) );
		}

		add_action( 'wp_ajax_popslide_get', array( $this, 'get' ) );
		add_action( 'wp_ajax_nopriv_popslide_get', array( $this, 'get' ) );

	}

	/**
	 * Load front assets
	 * @return void
	 */
	public function load_front_assets() {

		if (is_admin() )
			return false; // not this time

		wp_enqueue_style( 'dashicons' );

		wp_enqueue_script( 'jquery-cookie', POPSLIDE_JS . 'jquery.cookie.min.js', array( 'jquery' ), null, true );

		wp_enqueue_script( 'popslide-scripts', POPSLIDE_JS . 'front.js', array( 'jquery', 'jquery-cookie' ), null, true );

		$display = false;

		if ($this->settings->status == 'true') {
			if ( ( $this->settings->demo == 'true' && is_super_admin() ) || $this->settings->demo == 'false' ) {
				if ( wp_is_mobile() && $this->settings->mobile == 'false' ) {
				} else {
					$display = true;
				}
			}
		}

		$settings = apply_filters( 'popslide/settings', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'popslide' ),
			'status' => array(
				'active' => $display,
				'demo' => $this->settings->demo
			),
			'cookie' => array(
				'active' => $this->settings->cookie->active,
				'name' => $this->settings->cookie->name,
				'days' => $this->settings->cookie->days
			),
			'after' => array(
				'hits' => $this->settings->after->hits,
				'rule' => $this->settings->after->rule,
				'seconds' => $this->settings->after->seconds
			),
			'position' => $this->settings->position,
			// 'animation_type' => $this->settings->animation->type,
			'animation_duration' => $this->settings->animation->duration,
			'custom_target' => array(
				'targets' => $this->settings->cookie->custom_target,
				'close' => $this->settings->cookie->custom_target_close
			)
		) );

		wp_localize_script( 'popslide-scripts', 'popslide_settings', $settings );

	}

	public function get() {

		if ( ! check_ajax_referer( 'popslide', 'nonce', false ) ) {
			wp_send_json_error();
		}

		$content = do_shortcode( shortcode_unautop( wpautop( $this->settings->content ) ) );

		ob_start();

		// MailPoet fix
		if ( strrpos( $content , 'wysija_form') !== false ) {

			add_shortcode( 'wysija_form', '__return_false' );

			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

			foreach ($matches as $match) {
			
				if ( $match[2] == 'wysija_form' ) {

					$atts = shortcode_parse_atts( $match[3] );
					$shortcode = $match[0];

					$widgetNL = new WYSIJA_NL_Widget(true);
					$widget = $widgetNL->widget( array( 'form' => $atts['id'], 'form_type' => 'shortcode' ) );

					$content = str_replace( $shortcode, $widget, $content );

				}

			}

		}

	?>

		<div id="popslide" class="<?php echo $this->settings->position; ?> <?php echo $this->settings->align; ?> <?php echo $this->settings->custom_css->class; ?>" style="display: none;">
			<div class="popslide-table">
				<div class="popslide-inner">
					<?php if ($this->settings->close_button->position == 'top_left' || $this->settings->close_button->position == 'bottom_left'): ?>
						<div class="popslide-close <?php echo $this->settings->close_button->position; ?>"><span class="dashicons dashicons-no"></span></div>
					<?php endif ?>
						<div class="popslide-content">
							<?php echo $content; ?>
						</div>
					<?php if ($this->settings->close_button->position == 'top_right' || $this->settings->close_button->position == 'bottom_right'): ?>
						<div class="popslide-close <?php echo $this->settings->close_button->position; ?>"><span class="dashicons dashicons-no"></span></div>
					<?php endif ?>
				</div>
			</div>
		</div>

	<?php

		$html = ob_get_clean();

		wp_send_json_success( $html );

	}

	/**
	 * Load front css generated via php
	 * @return void
	 */
	public function load_front_css() {
	?>

		<style type="text/css">

			#popslide {
				position: fixed;
				width: 100%;
				display: none;
				z-index: 9999999;
				background-color: <?php echo $this->settings->bg_color; ?>;
				color: <?php echo $this->settings->font_color; ?>;
				width: <?php echo $this->settings->width->value.$this->settings->width->unit; ?>;
			}

			#popslide.left {
				left: 0;
			}

			#popslide.right {
				right: 0;
			}

			#popslide.center {
				left: 50%;
				margin-left: -<?php echo ($this->settings->width->value/2).$this->settings->width->unit; ?>;
			}

			#popslide .popslide-table {
				display: table;
				width: 100%;
			}

			#popslide .popslide-inner {
				display: table-row;	
			}

			#popslide .popslide-content {
				display: table-cell;
				padding: <?php echo $this->settings->padding->top->value.$this->settings->padding->top->unit.' '.$this->settings->padding->right->value.$this->settings->padding->right->unit.' '.$this->settings->padding->bottom->value.$this->settings->padding->bottom->unit.' '.$this->settings->padding->left->value.$this->settings->padding->left->unit; ?>;
			}

			#popslide .popslide-content p:last-child {
				margin-bottom: 0;
			}

			#popslide .popslide-close {
				display: table-cell;
				cursor: pointer;
				padding: <?php echo $this->settings->padding->top->value.$this->settings->padding->top->unit.' '.$this->settings->padding->right->value.$this->settings->padding->right->unit.' '.$this->settings->padding->bottom->value.$this->settings->padding->bottom->unit.' '.$this->settings->padding->left->value.$this->settings->padding->left->unit; ?>;
				color: <?php echo $this->settings->close_button->color; ?>;
				width: <?php echo $this->settings->close_button->font_size; ?>px;
				height: <?php echo $this->settings->close_button->font_size; ?>px;
			}

			#popslide .popslide-close span {
				width: <?php echo $this->settings->close_button->font_size; ?>px;
				height: <?php echo $this->settings->close_button->font_size; ?>px;
			}

			#popslide .popslide-close .dashicons:before {
				cursor: pointer;
				font-size: <?php echo $this->settings->close_button->font_size; ?>px;
			}

			#popslide .popslide-close.bottom_left,
			#popslide .popslide-close.bottom_right {
				vertical-align: bottom;
			}

			#popslide.top {
				top: 0;
			}

			#popslide.bottom {
				bottom: 0;
			}


			/* Wysija integration */
			.popslide-content .wysija-paragraph {
				display: inline-block;
			}

			.popslide-content .widget_wysija_cont p label {
				display: inline-block;
				margin-right: 10px;
			}

			.popslide-content .widget_wysija_cont p .wysija-input {
				margin-right: 10px;
			}

			.popslide-content .widget_wysija_cont .wysija-submit {
				display: inline-block;
				margin-top: 0;
				vertical-align: top;
			}

			<?php if (isset($this->settings->custom_css->status) && $this->settings->custom_css->status == 'true') echo $this->settings->custom_css->css; ?>

		</style>

	<?php
	}

}