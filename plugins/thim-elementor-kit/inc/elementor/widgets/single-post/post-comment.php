<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Post_Comment extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-post-comment';
	}

	public function get_title() {
		return esc_html__( 'Post Comment', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-comments';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_POST );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'skin_temp',
			array(
				'label'   => esc_html__( 'Skin', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'' => esc_html__( 'Theme Comments', 'thim-elementor-kit' ),
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-post/before-preview-query' );

		$settings = $this->get_settings_for_display();
		?>

		<div class="thim-ekit-single-post__comment">
			<?php comments_template(); ?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-post/after-preview-query' );
	}
}
