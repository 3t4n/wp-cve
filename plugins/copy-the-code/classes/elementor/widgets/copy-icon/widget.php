<?php
/**
 * Elementor Copy Icon Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */

namespace CopyTheCode\Elementor\Block;

use CopyTheCode\Helpers;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Copy Icon Block
 *
 * @since 3.1.0
 */
class CopyIcon extends Widget_Base {

	/**
	 * Constructor
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		// Core.
		wp_enqueue_script( 'ctc-clipboard', COPY_THE_CODE_URI . 'assets/js/clipboard.js', [ 'jquery' ], COPY_THE_CODE_VER, true );

		// Block.
		wp_enqueue_style( 'ctc-el-copy-icon', COPY_THE_CODE_URI . 'classes/elementor/widgets/copy-icon/style.css', [], COPY_THE_CODE_VER );
	}

	/**
	 * Get script dependencies
	 */
	public function get_script_depends() {
		return [ 'ctc-clipboard' ];
	}

	/**
	 * Get style dependencies
	 */
	public function get_style_depends() {
		return [ 'ctc-el-copy-icon' ];
	}

	/**
	 * Get name
	 */
	public function get_name() {
		return 'ctc_copy_icon';
	}

	/**
	 * Get title
	 */
	public function get_title() {
		return esc_html__( 'Copy to Clipboard Icon', 'copy-the-code' );
	}

	/**
	 * Get icon
	 */
	public function get_icon() {
		return 'eicon-favorite';
	}

	/**
	 * Get categories
	 */
	public function get_categories() {
		return Helpers::get_categories();
	}

	/**
	 * Get keywords
	 */
	public function get_keywords() {
		return Helpers::get_keywords( [ 'icon' ] );
	}

	/**
	 * Render block
	 */
	public function render() {
		?>
		<span class="ctc-block ctc-copy-icon">
			<span copy-as-raw="yes" class="ctc-block-copy ctc-block-copy-icon" role="button" aria-label="Copied">
				<?php echo Helpers::get_svg_copy_icon(); ?>
				<?php echo Helpers::get_svg_checked_icon(); ?>
			</span>
			<?php Helpers::render_copy_content( $this ); ?>
		</span>
		<?php
	}

	/**
	 * Register block controls
	 */
	protected function _register_controls() {
		Helpers::register_copy_content_section( $this );

		Helpers::register_style_section(
			$this,
			'Icon',
			'.ctc-copy-icon svg',
			[
				'padding'           => false,
				'border_radius'     => false,
				'typography'        => false,
				'text_align'        => false,
				'normal_background' => false,
				'normal_box_shadow' => false,
				'normal_border'     => false,
				'normal_text_color' => [
					'label'     => esc_html__( 'Fill Color', 'copy-the-code' ),
					'selectors' => [
						'{{WRAPPER}} .ctc-copy-icon svg' => 'fill: {{VALUE}};',
					],
				],
				'hover_text_color'  => [
					'label'     => esc_html__( 'Fill Color', 'copy-the-code' ),
					'selectors' => [
						'{{WRAPPER}} .ctc-copy-icon svg:hover' => 'fill: {{VALUE}};',
					],
				],
				'size'              => [
					'label'     => esc_html__( 'Icon Size', 'copy-the-code' ),
					'selectors' => [
						'{{WRAPPER}} .ctc-copy-icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				],
			]
		);
	}

}
