<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
if ( ! class_exists( 'Thim_Ekit_Widget_Post_Comment' ) ) {
	require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/single-post/post-comment.php';
}
class Thim_Ekit_Widget_Course_Comment extends Thim_Ekit_Widget_Post_Comment {
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

	}

	public function get_name() {
		return 'thim-ekits-course-comment';
	}

	public function get_title() {
		return esc_html__( 'Course Comment', 'thim-elementor-kit' );
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE  );
	}

}
