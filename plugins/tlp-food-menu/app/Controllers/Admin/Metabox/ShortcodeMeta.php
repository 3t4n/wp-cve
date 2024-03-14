<?php
/**
 * Admin Shortcode Metabox Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin\Metabox;

use RT\FoodMenu\Helpers\Fns;
use RT\FoodMenu\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Shortcode Metabox Class.
 */
class ShortcodeMeta {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 10 );
		add_action( 'save_post', [ $this, 'save_meta_boxes' ], 10, 2 );
		add_action( 'edit_form_after_title', [ $this, 'after_title_text' ] );
		add_action( 'admin_init', [ $this, 'fm_pro_remove_all_meta_box' ] );
		add_action( 'admin_footer', [ $this, 'pro_alert_html' ] );
	}

	/**
	 * Admin Enqueue Scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		global $pagenow, $typenow;

		// validate page.
		if ( ! in_array( $pagenow, [ 'post.php', 'post-new.php', 'edit.php' ] ) ) {
			return;
		}

		if ( $typenow != TLPFoodMenu()->shortCodePT ) {
			return;
		}

		wp_enqueue_media();

		$select2Id = 'fm-select2';

		if ( class_exists( 'Avada' ) ) {
			$select2Id = 'select2-avada-js';
		} elseif ( class_exists( 'wp_megamenu_base' ) ) {
			wp_dequeue_script( 'wpmm-select2' );
			wp_dequeue_script( 'wpmm_scripts_admin' );
		}

		wp_enqueue_style(
			[
				'wp-color-picker',
				'fm-select2',
				'fm-frontend',
				'fm-admin',
				'fm-admin-preview',
			]
		);

		wp_enqueue_script(
			[
				'jquery',
				'wp-color-picker',
				$select2Id,
				'fm-admin',
				'fm-admin-preview',
			]
		);

		$nonce = wp_create_nonce( Fns::nonceText() );

		wp_localize_script(
			'fm-admin',
			'fmp',
			[
				'nonceID' => esc_attr( Fns::nonceID() ),
				'nonce'   => esc_attr( $nonce ),
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
			]
		);
	}

	/**
	 * Add Meta Box.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		add_meta_box(
			TLPFoodMenu()->shortCodePT . '_sc_settings_meta',
			esc_html__( 'Short Code Generator', 'tlp-food-menu' ),
			[ $this, 'fm_sc_settings_selection' ],
			TLPFoodMenu()->shortCodePT,
			'normal',
			'high'
		);

		add_meta_box(
			TLPFoodMenu()->shortCodePT . '_sc_preview_meta',
			esc_html__( 'Layout Preview', 'tlp-food-menu' ),
			[ $this, 'fm_sc_preview_selection' ],
			TLPFoodMenu()->shortCodePT,
			'normal',
			'high'
		);

		add_meta_box(
			'rt_plugin_sc_pro_information',
			esc_html__( 'Documentation', 'tlp-food-menu' ),
			[ $this, 'rt_plugin_sc_pro_information' ],
			TLPFoodMenu()->shortCodePT,
			'side',
			'default'
		);
	}

	/**
	 * Save Meta Box.
	 *
	 * @param int    $post_id Post ID.
	 * @param object $post Post object.
	 * @return void
	 */
	public function save_meta_boxes( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! Fns::verifyNonce() ) {
			return $post_id;
		}

		if ( TLPFoodMenu()->shortCodePT != $post->post_type ) {
			return $post_id;
		}

		$mates = Fns::fmpScMetaFields();
		$mates = apply_filters( 'rtfm_sc_meta_fields', $mates );

		foreach ( $mates as $metaKey => $field ) {
			$rValue = ! empty( $_REQUEST[ $metaKey ] ) ? $_REQUEST[ $metaKey ] : null;
			$value  = Fns::sanitize( $field, $rValue );

			if ( empty( $field['multiple'] ) ) {
				update_post_meta( $post_id, $metaKey, $value );
			} else {
				if ( apply_filters( 'tlp_fmp_has_multiple_meta_issue', false ) ) {
					update_post_meta( $post_id, $metaKey, $value );
				} else {
					delete_post_meta( $post_id, $metaKey );
					if ( is_array( $value ) && ! empty( $value ) ) {
						foreach ( $value as $item ) {
							add_post_meta( $post_id, $metaKey, $item );
						}
					} else {
						update_post_meta( $post_id, $metaKey, '' );
					}
				}
			}
		}

		if ( isset( $_POST['_rtfm_last_active_tab'] ) ) {
			update_post_meta( $post_id, '_rtfm_last_active_tab', sanitize_text_field( wp_unslash( $_POST['_rtfm_last_active_tab'] ) ) );
		}
	}

	/**
	 * Text after title.
	 *
	 * @param object $post Post Object.
	 * @return string
	 */
	public function after_title_text( $post ) {
		if ( TLPFoodMenu()->shortCodePT !== $post->post_type ) {
			return;
		}

		$html  = null;
		$html .= '<div class="postbox" style="margin-bottom: 0;"><div class="inside">';
		$html .= '<p><input type="text" onfocus="this.select();" readonly="readonly" value="[foodmenu id=&quot;' . $post->ID . '&quot; title=&quot;' . $post->post_title . '&quot;]" class="large-text code rt-code-sc">
		<input type="text" onfocus="this.select();" readonly="readonly" value="&#60;&#63;php echo do_shortcode( &#39;[foodmenu id=&quot;' . $post->ID . '&quot; title=&quot;' . $post->post_title . '&quot;]&#39; ); &#63;&#62;" class="large-text code rt-code-sc">
		</p>';
		$html .= '</div></div>';

		Fns::print_html( $html, true );
	}

	/**
	 * Remove all meta boxes.
	 *
	 * @return void
	 */
	public function fm_pro_remove_all_meta_box() {
		if ( is_admin() ) {
			add_filter(
				'get_user_option_meta-box-order_' . TLPFoodMenu()->shortCodePT,
				[ $this, 'remove_all_meta_boxes_fmp_sc' ]
			);
		}
	}

	/**
	 * Add only custom meta box.
	 *
	 * @return array
	 */
	public function remove_all_meta_boxes_fmp_sc() {
		global $wp_meta_boxes;

		$publishBox   = $wp_meta_boxes[ TLPFoodMenu()->shortCodePT ]['side']['core']['submitdiv'];
		$scBox        = $wp_meta_boxes[ TLPFoodMenu()->shortCodePT ]['normal']['high'][ TLPFoodMenu()->shortCodePT . '_sc_settings_meta' ];
		$scPreviewBox = $wp_meta_boxes[ TLPFoodMenu()->shortCodePT ]['normal']['high'][ TLPFoodMenu()->shortCodePT . '_sc_preview_meta' ];
		$docBox       = $wp_meta_boxes[ TLPFoodMenu()->shortCodePT ]['side']['default']['rt_plugin_sc_pro_information'];

		$wp_meta_boxes[ TLPFoodMenu()->shortCodePT ] = [
			'side'   => [
				'core'    => [ 'submitdiv' => $publishBox ],
				'default' => [
					'rt_plugin_sc_pro_information' => $docBox,
				],
			],
			'normal' => [
				'high' => [
					TLPFoodMenu()->shortCodePT . '_sc_settings_meta' => $scBox,
					TLPFoodMenu()->shortCodePT . '_sc_preview_meta'  => $scPreviewBox,
				],
			],
		];

		return [];
	}

	/**
	 * Setting Sections
	 *
	 * @param $post
	 */
	/**
	 * Setting Sections
	 *
	 * @param object $post Post object.
	 * @return void
	 */
	public function fm_sc_settings_selection( $post ) {
		$last_tab = trim( get_post_meta( $post->ID, '_rtfm_last_active_tab', true ) );
		$last_tab = ! empty( $last_tab ) ? $last_tab : 'sc-fmp-layout';

		wp_nonce_field( Fns::nonceText(), Fns::nonceID() );

		$html  = null;
		$html .= '<div class="rt-tab-container">';
		$html .= sprintf(
			'<ul class="rt-tab-nav">
			<li %s><a href="#sc-fmp-layout"><i class="dashicons dashicons-layout"></i>' . esc_html__( 'Layout', 'tlp-food-menu' ) . '</a></li>
			<li %s><a href="#sc-fmp-filter"><i class="dashicons dashicons-filter"></i>' . esc_html__( 'Filtering', 'tlp-food-menu' ) . '</a></li>
			<li %s><a href="#sc-fmp-field-selection"><i class="dashicons dashicons-editor-table"></i>' . esc_html__( 'Field selection', 'tlp-food-menu' ) . '</a></li>
			<li %s><a href="#sc-fmp-style"><i class="dashicons dashicons-admin-customizer"></i>' . esc_html__( 'Styling', 'tlp-food-menu' ) . '</a></li>
			</ul>',
			'sc-fmp-layout' === $last_tab ? 'class="active"' : '',
			'sc-fmp-filter' === $last_tab ? 'class="active"' : '',
			'sc-fmp-field-selection' === $last_tab ? 'class="active"' : '',
			'sc-fmp-style' === $last_tab ? 'class="active"' : ''
		);

		$html .= sprintf(
			'<div id="sc-fmp-layout" class="rt-tab-content" %s>',
			'sc-fmp-layout' === $last_tab ? 'style="display:block"' : ''
		);
		$html .= Fns::renderView( 'metabox.layout', $post, true );
		$html .= '</div>';

		$html .= sprintf( '<div id="sc-fmp-filter" class="rt-tab-content" %s>%s</div>', 'sc-fmp-filter' === $last_tab ? 'style="display:block"' : '', Fns::rtFieldGenerator( Options::scFilterMetaFields() ) );
		$html .= sprintf( '<div id="sc-fmp-field-selection" class="rt-tab-content" %s>%s</div>', 'sc-fmp-field-selection' === $last_tab ? 'style="display:block"' : '', Fns::rtFieldGenerator( Options::scItemFields() ) );

		$html .= sprintf(
			'<div id="sc-fmp-style" class="rt-tab-content" %s>',
			'sc-fmp-style' === $last_tab ? 'style="display:block"' : ''
		);
		$html .= Fns::renderView( 'metabox.styling', $post, true );
		$html .= '</div>';

		$html .= sprintf( '<input type="hidden" id="_rtfm_last_active_tab" name="_rtfm_last_active_tab" value="%s"/>', $last_tab );
		$html .= '</div>';

		Fns::print_html( $html, true );
	}

	/**
	 * Pro information.
	 *
	 * @return void
	 */
	public function rt_plugin_sc_pro_information() {
		global $pagenow;

		$html    = '';
		$doc     = 'https://www.radiustheme.com/docs/food-menu/getting-started/installations/';
		$contact = 'https://www.radiustheme.com/contact/';
		$fb      = 'https://www.facebook.com/groups/234799147426640/';
		$rt      = 'https://www.radiustheme.com/';

		if ( ! TLPFoodMenu()->has_pro() ) {
			$html .= sprintf( '<div class="rt-document-box"><div class="rt-box-icon"><i class="dashicons dashicons-megaphone"></i></div><div class="rt-box-content"><h3 class="rt-box-title">Pro Features</h3>%s</div></div>', Options::get_pro_feature_list() );
		}

		$html .= sprintf(
			'<div class="rt-document-box">
				<div class="rt-box-icon"><i class="dashicons dashicons-media-document"></i></div>
				<div class="rt-box-content">
					<h3 class="rt-box-title">%1$s</h3>
						<p>%2$s</p>
						<a href="' . esc_url( $doc ) . '" target="_blank" class="rt-admin-btn">%1$s</a>
				</div>
			</div>',
			esc_html__( 'Documentation', 'tlp-food-menu' ),
			esc_html__( 'Get started by spending some time with the documentation we included step by step process with screenshots with video.', 'tlp-food-menu' )
		);

		$html .= '<div class="rt-document-box">
					<div class="rt-box-icon"><i class="dashicons dashicons-sos"></i></div>
					<div class="rt-box-content">
						<h3 class="rt-box-title">Need Help?</h3>
							<p>Stuck with something? Please create a
				<a href="' . esc_url( $contact ) . '" target="_blank">ticket here</a> or post on <a href="' . esc_url( $fb ) . '" target="_blank">facebook group</a>. For emergency case join our <a href="' . esc_url( $rt ) . '" target="_blank">live chat</a>.</p>
							<a href="' . esc_url( $contact ) . '" target="_blank" class="rt-admin-btn">Get Support</a>
					</div>
				</div>';

		Fns::print_html( $html );
	}


	/**
	 * Preview section
	 *
	 * @return void
	 */
	public function fm_sc_preview_selection() {
		echo "<div class='fmp-response'><span class='spinner'></span></div><div id='fmp-preview-container'></div>";
	}

	/**
	 * Pro Alert HTML.
	 *
	 * @return void
	 */
	public function pro_alert_html() {
		global $typenow;

		if ( TLPFoodMenu()->has_pro() ) {
			return;
		}

		if ( ( isset( $_GET['page'] ) && $_GET['page'] != 'food_menu_settings' ) || ! ( $typenow == TLPFoodMenu()->post_type || $typenow == TLPFoodMenu()->shortCodePT ) ) {
			return;
		}

		$html  = '';
		$pro   = 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/';
		$html .= '<div class="rtfm-document-box rtfm-alert rtfm-pro-alert">
				<div class="rtfm-box-icon"><i class="dashicons dashicons-lock"></i></div>
				<div class="rtfm-box-content">
					<h3 class="rtfm-box-title">' . esc_html__( 'Pro Field Alert!', 'tlp-food-menu' ) . '</h3>
					<p><span></span>' . esc_html__( 'Sorry! This is a Pro field. To activate this field, you need to upgrade to the Pro version.', 'tlp-food-menu' ) . '</p>
					<a href="' . esc_url( $pro ) . '" target="_blank" class="rt-admin-btn">' . esc_html__(
						'Get Pro Version',
						'tlp-food-menu'
					) . '</a>
					<a href="#" target="_blank" class="rtfm-alert-close rtfm-pro-alert-close"><span class="dashicons dashicons-no-alt"></span></a>
				</div>
			</div>';

		Fns::print_html( $html );
	}
}
