<?php
/**
 * This file handles the dynamic parts of our blocks.
 *
 * @package BWFBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Render the dynamic aspects of our blocks.
 *
 * @since 1.2.0
 */
#[AllowDynamicProperties]

  class BWFBlocksOptin_Render_Block {
	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Instance
	 * @since 1.2.0
	 */
	private static $instance;

	/**
	 * Initiator.
	 *
	 * @since 1.2.0
	 * @return object initialized object of class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Register our dynamic blocks.
	 *
	 * @since 1.2.0
	 */
	public function register_blocks() {
		// Only load if Gutenberg is available.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$bwfblocks = [
			[
				'name'     => 'bwfblocks/optin-form',
				'callback' => 'do_optin_form_block',
			],
			[
				'name'     => 'bwfblocks/popup-form',
				'callback' => 'do_popup_form_block_block',
			],
		];

		foreach ( $bwfblocks as $block) {
			register_block_type(
				$block['name'],
				array(
					'render_callback' => array( $this, $block['callback'] ),
				)
			);
		}
	}

	public function has_block_visibiliy_classes( $settings, $classes ) {
		if( ! empty( $settings['vsdesk'] ) ) {
			$classes[] = 'bwf-hide-lg';
		}
		if( ! empty( $settings['vstablet'] ) ) {
			$classes[] = 'bwf-hide-md';
		}
		if( ! empty( $settings['vsmobile'] ) ) {
			$classes[] = 'bwf-hide-sm';
		}
		return $classes;
	}

	/**
	 * Output the dynamic aspects of our Advance Button blocks.
	 *
	 * @since 1.2.0
	 * @param array  $attributes The block attributes.
	 * @param string $content The inner blocks.
	 */
	public function do_optin_form_block( $attributes, $content ) {
		
		$defaults = bwfoptin_get_block_defaults();

		$attributes = wp_parse_args(
			$attributes,
			$defaults['optin-form']
		);

		return $this->do_form_block($attributes, $content );
	}

	/**
	 * Output the dynamic aspects of our Advance Button blocks.
	 *
	 * @since 1.2.0
	 * @param array  $attributes The block attributes.
	 * @param string $content The inner blocks.
	 */
	public function do_popup_form_block_block( $attributes, $content ) {
		$output = '';
		$defaults = bwfoptin_get_block_defaults();

		$attributes = wp_parse_args(
			$attributes,
			$defaults['popup-form']
		);
		$settings =  $attributes;
		$classNames = array(
			'bwfop-popup-form-container',
			'bwf-' . $settings['uniqueID'],
		);
		$output .= sprintf(
			'<div %1$s %2$s>',
			bwfblocks_attr(
				'poup',
				array(
					'class' => implode( ' ', $classNames ),
				),
				$settings
			),
			bwfblocks_attr(
				'poupId',
				array(
					'id' => isset( $settings['blockID'] ) ? $settings['blockID'] . '-popup' : null,
				),
				$settings
			)
		);

		$output .= $this->do_button_block( $attributes, $content );
		if ( ! isset( $settings['progressStripEnable'] ) ) {
			$settings['progressStripEnable'] = false;
		}
		$animate = $settings['progressStripEnable'] ? 'bwf_pp_animate': '';
		
		if ( ! isset( $settings['progressBarTextEnable'] ) ) {
			$settings['progressBarTextEnable'] = false;
		}
		if ( ! isset( $settings['progressBarEnable'] ) ) {
			$settings['progressBarEnable'] = false;
		}
        if ( !isset( $settings['popupType'] ) )  {
            $settings['popupType'] = 'bwf_pp_effect_fade';
        }
		ob_start();
		?>
			<div class="bwf_pp_overlay <?php echo esc_attr($settings['popupType']); ?>" id="<?php echo isset( $settings['blockID'] ) ? esc_attr($settings['blockID']) : null ?>">
				<div class="bwf_pp_wrap">
					<a class="bwf_pp_close" href="javascript:void(0);">×</a>
					<div class="bwf_pp_cont">
						<?php if ( $settings['progressBarEnable'] ) { ?>
                            <?php if ( $settings['progressBarTextEnable'] ) {  ?>
                                <div class="pp-bar-text above"><?php echo esc_html( $settings['progressBarText'] ); ?></div>
                            <?php } ?>
						<div class="bwf_pp_bar_wrap ">
							<div class="bwf_pp_bar <?php echo esc_attr($animate); ?>" role="progressbar" aria-valuenow="<?php echo $settings['progressWidth'] ? esc_attr( $settings['progressWidth'] ) : '75'; ?>" aria-valuemin="0" aria-valuemax="100">
								<?php if ( ! $settings['progressBarTextEnable'] ) {  ?>
								<span class="pp-bar-text inside"><?php echo esc_html( $settings['progressBarText'] ); ?></span>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
						<div class="bwf_pp_opt_head"><?php echo esc_html( $settings['heading'] ); ?></div>
						<div class="bwf_pp_opt_sub_head"><?php echo $settings['subHeading']; ?></div>
						<?php echo $content; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<div class="bwf_pp_footer"><?php echo esc_html( $settings['textAfter'] ); ?></div>
					</div>
				</div>
			</div>
		<?php
		$output .= ob_get_clean();
		$output .= '</div>';
		return $output;
	}

	/**
	 * Output the dynamic aspects of our Advance Button blocks.
	 *
	 * @since 1.2.0
	 * @param array  $attributes The block attributes.
	 * @param string $content The inner blocks.
	 */
	public function do_form_block( $attributes, $content) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
		$output = '';
		$defaults = bwfoptin_get_block_defaults();
		$settings = wp_parse_args(
			$attributes,
			$defaults['optin-form']
		);
		$classNames = array(
			'bwfop-form-container',
			'bwf-' . $settings['uniqueID'],
			$settings['classWrap'],
		);

		$output .= sprintf(
			'<div %1$s %2$s>',
			bwfblocks_attr(
				'form',
				array(
					'class' => implode( ' ', $classNames ),
				),
				$settings
			),
			bwfblocks_attr(
				'formId',
				array(
					'id' => isset( $settings['blockID'] ) ? $settings['blockID'] : null,
				),
				$settings
			)
		);

		$output .= sprintf(
			'<form %1$s method="post">',
			bwfblocks_attr(
				'forminner',
				array(
					'class' => 'bwfop-form-wrap wffn-custom-optin-from',
				),
				$settings
			)
		);

		ob_start();


		$wrapper_class = '';
		$show_labels   = isset( $settings['enableLabel'] ) ? $settings['enableLabel'] : false;
		$wrapper_class .= $show_labels ? '' : ' wfop_hide_label';

		$optinPageId    = WFOPP_Core()->optin_pages->get_optin_id();
		$optin_fields   = WFOPP_Core()->optin_pages->form_builder->get_optin_layout( $optinPageId );
		$optin_settings = WFOPP_Core()->optin_pages->get_optin_form_integration_option( $optinPageId );

		$get_fields = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $optinPageId );

		if( isset( $settings['fields'] ) && is_array( $settings['fields']  ) ) {
			foreach ( $get_fields  as &$value ) {
				foreach ($settings['fields'] as $val ) {
					if ( $val['InputName'] === $value['InputName'] ) {
						$value['width'] = $val['width'] ;
						break;
					}
				}
			}
		}
		$optin_fields['single_step'] =  $get_fields;

		$optin_fields['two_step']   = array();
		$optin_fields['third_step'] = array();

		$settings_css = array(
			'button_text' => isset( $settings['content'] ) ? $settings['content'] : 'Submit',
			'subtitle'    => isset( $settings['secondaryContentEnable'] ) && $settings['secondaryContentEnable'] && isset( $settings['secondaryContent'] ) ? $settings['secondaryContent'] : '',
			'show_labels' => isset( $settings['enableLabel'] ) ? $settings['enableLabel'] : false,
		);
		if ( ! empty( $settings['asteriskColor'] ) ) {
			$settings_css['mark_required_color'] = $settings['asteriskColor'];
		}
		if ( isset( $settings['submittingText'] ) ) {
			$settings_css['button_submitting_text'] = $settings['submittingText'];
			$settings_css['submitting_text']        = $settings['submittingText'];
		}

		$settings_css['button_text_typo_font_size'] = '26px';
		$custom_form = WFOPP_Core()->form_controllers->get_integration_object( 'form' );
		if ( $custom_form instanceof WFFN_Optin_Form_Controller_Custom_Form ) {
			$settings_css = wp_parse_args( $settings_css, WFOPP_Core()->optin_pages->form_builder->form_customization_settings_default() );
			$custom_form->_output_form( $wrapper_class, $optin_fields, $optinPageId, $optin_settings, 'inline', $settings_css );
		}
		?>
		<script>
			jQuery(document).trigger('wffn_reload_phone_field');
		</script>
		<?php
		$output .= ob_get_clean() .  '</form></div>';
		return $output;
	}

	/**
	 * Output the dynamic aspects of our Advance Button blocks.
	 *
	 * @since 1.2.0
	 * @param array  $attributes The block attributes.
	 * @param string $content The inner blocks.
	 */
	public function do_button_block( $attributes, $content ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

		$output = '';

		$settings = $attributes;

		$classNames = array(
			'bwf-btn-wrap',
			'bwfop-poup-button-wrap',
		);



		$output .= sprintf(
			'<div %1$s>',
			bwfblocks_attr(
				'button',
				array(
					'class' => implode( ' ', $classNames ),
				),
				$settings
			)
		);


		$buttonAnchorClasses = array( 'bwf-btn', 'bwf-btn-popup' );

		if( ! empty( $settings['secondaryContentEnable'] ) ) {
			$buttonAnchorClasses[] = 'has-secondary-text';
		}

		$button_rel = '';
		$button_target = '';
		$button = isset( $settings['button'] ) ? $settings['button'] : [];
		if( isset( $button['newTab'] ) && ! empty( $button['newTab'] ) ) {
			$button_target = '_blank';
			$button_rel = 'noopener noreferrer';
		}

		if( isset( $button['noFollow'] ) && ! empty( $button['noFollow'] ) ) {
			$button_rel .= ' nofollow';
		}
		$output .= sprintf(
			'<button %1$s >',
			bwfblocks_attr(
				'button-anchor',
				array(
					'id'     => isset( $settings['anchor'] ) ? $settings['anchor'] : '',
					'class'  => implode( ' ', $buttonAnchorClasses ),
					'href'   => isset( $settings['link'] ) ? esc_url( $settings['link'] ) : '',
					'target' => $button_target,
					'rel'    => $button_rel,
				),
				$settings
			), 
			isset( $settings['product'] ) ? $settings['product'] : ''
		);

		$output .= '<div class="bwfop-secondary">';
		//Button Icon Left Side
		if( isset( $button['icon'] ) && ! empty( $button['icon'] ) && 'left' === $button['iconPos'] ) {
			$output .= '<span class="bwf-icon-inner-svg">' . $button['icon'] . '</span>';
		}

		//Button content
		$output .= isset( $settings['content'] ) ? '<span class="bwf-btn-inner-text">' . $settings['content'] . '</span>' : '';

		//Button Icon Right Side
		if( isset( $button['icon'] ) && ! empty( $button['icon'] ) && 'right' === $button['iconPos'] ) {
			$output .= '<span class="bwf-icon-inner-svg">' . $button['icon'] . '</span>';
		}
		$output .= '</div>';
		// Button Secondary Text (Sub heading)
		if( isset( $settings['secondaryContentEnable'] ) && ! empty( $settings['secondaryContentEnable'] ) ) {
			$content2 = isset( $settings['secondaryContent'] ) ? $settings['secondaryContent'] : '';
			$output .= '<span class="bwf-btn-sub-text">' . $content2 . '</span>';
		}
		
		$output .= '</button>';
		$output .= '</div>';

		return $output;
	}
}

BWFBlocksOptin_Render_Block::get_instance();
