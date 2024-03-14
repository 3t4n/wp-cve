<?php
namespace Thim_EL_Kit\Elementor\DynamicTags;

use LearnPress;
use Thim_EL_Kit\SingletonTrait;

class Init {
	use SingletonTrait;

	public function __construct() {
		add_action( 'elementor/init', array( $this, 'include_files' ) );
		add_action( 'elementor/dynamic_tags/register', array( $this, 'register_tags' ) );
	}

	public function include_files() {
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-title.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-excerpt.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-featured-image.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-url.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-comment.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-author.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-date.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-terms.php';
		require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/tags/item-custom-field.php';

		// Include widgets on here to run static method LoadAjax of LP.
		if ( class_exists( 'LearnPress' ) ) {
			$lp_version = LearnPress::instance()->version;
			if ( version_compare( $lp_version, '4.2.6-beta-0', '>=' ) ) {
				require_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/archive-course/archive-course-new.php';
			}
		}
	}

	public function get_tag_classes_names() {
		return array(
			'Item_Title',
			'Item_Excerpt',
			'Item_Featured_Image',
			'Item_URL',
			'Item_Comment',
			'Item_Author',
			'Item_Date',
			'Item_Terms',
			'Item_Custom_Field',
		);
	}

	/** @var \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager */
	public function register_tags( $dynamic_tags_manager ) {
		$dynamic_tags_manager->register_group( 'thim-ekit', array( 'title' => esc_html__( 'Thim Elementor Kit', 'thim-elementor-kit' ) ) );

		$tag_classes_names = $this->get_tag_classes_names();

		foreach ( $tag_classes_names as $tag_class_name ) {
			$tag = 'Thim_EL_Kit\\Elementor\\DynamicTags\\Tags\\' . $tag_class_name;

			$dynamic_tags_manager->register( new $tag() );
		}
	}
}

Init::instance();
