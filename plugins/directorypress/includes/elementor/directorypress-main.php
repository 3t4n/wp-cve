<?php
/**
 * Main Directory Widget
 * @since 3.2.0
 */
 
use Elementor\Plugin;
class DirectoryPress_Elementor_Main_Widget extends \Elementor\Widget_Base {
	//public $is_directorypress_archive = 1;
	public function __construct($data = [], $args = null) {
      parent::__construct($data, $args);

	}
	public function get_name() {
		return 'directorypress-main';
	}

	public function get_title() {
		return __( 'Directory', 'DIRECTORYPRESS' );
	}

	public function get_icon() {
		return 'fas fa-sitemap';
	}

	public function get_categories() {
		return [ 'directorypress' ];
	}

	protected function register_controls() {
		$directories = directorypress_directorytypes_array_options();
		$fields = directorypress_fields_array_options();
		// Settings section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'archive_top_banner',
			[
				'label' => __( 'Top Ads Banner', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Insert your banner', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'archive_below_search_banner',
			[
				'label' => __( 'Ads Banner After Search Form', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Insert your banner', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'archive_below_category_banner',
			[
				'label' => __( 'Ads Banner After Category Section', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Insert your banner', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'archive_below_locations_banner',
			[
				'label' => __( 'Ads Banner After Locations Section', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Insert your banner', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'archive_below_listings_banner',
			[
				'label' => __( 'Ads Banner After Listings Section', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Insert your banner', 'DIRECTORYPRESS' ),
			]
		);
		$this->end_controls_section();
		
		// content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'directorytype',
			[
				'label' => __( 'Select Directory Type', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => $directories,
				'default' => [0],
			]
		);
		$this->add_control(
			'search_fields',
			[
				'label' => __( 'Select Specific Fields', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $fields,
				'default' => [0],
			]
		);
		
		$this->add_control(
			'search_fields_advanced',
			[
				'label' => __( 'Select Specific Fields For Advanced Panel', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $fields,
				'default' => ['none'],
			]
		);
		$this->end_controls_section();

	}

	protected function render() {
		global $directorypress_object;
		$settings = $this->get_settings_for_display();
		if(in_array('0', $settings['search_fields'])){
			$fields = '';
		}elseif(in_array('none', $settings['search_fields'])){
			$fields = '-1';
		}else{
			$fields = implode(',', $settings['search_fields']);
		}
		if(in_array('0', $settings['search_fields_advanced'])){
			$advanced_fields = '';
		}elseif(in_array('none', $settings['search_fields_advanced'])){
			$advanced_fields = '-1';
		}else{
			$advanced_fields = implode(',', $settings['search_fields_advanced']);
		}
		
		$instance = array(
			'id' => $settings['directorytype'],
			'form_layout' => 'vertical',
			'search_fields' => $fields,
			'search_fields_advanced' => $advanced_fields,
			'archive_top_banner' => $settings['archive_top_banner'],
			'archive_below_search_banner' => $settings['archive_below_search_banner'],
			'archive_below_category_banner' => $settings['archive_below_category_banner'],
			'archive_below_locations_banner' => $settings['archive_below_locations_banner'],
			'archive_below_listings_banner' => $settings['archive_below_listings_banner']
		);
		
		$handler = new directorypress_directory_handler();
		$handler->init($instance, 'directorypress-main');
		
		echo '<div class="directorypress-elementor-directorytype-widget">';
		echo "<input type='hidden' name='el_archive_page'>";
			echo $handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				directorypress_select2_init();
				directorypress_process_main_search_fields();
			} )( jQuery );
		</script>';
		};
	}

}