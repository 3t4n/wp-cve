<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Lite_WPKoi_QR_Code extends Widget_Base {

	public function get_name() {
		return 'wpkoi-qr-code';
	}

	public function get_title() {
		return esc_html__( 'QR Code', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-barcode';
	}

   public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}


	protected function register_controls() {


  		$this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('QR Code', 'wpkoi-elements'),
            ]
        );

        $this->add_control(
			'qr_code_content',
			[
				'label'       => __( 'QR Code Content', 'wpkoi-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Add Your text here', 'wpkoi-elements' ),
				'default'     => '',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('QR Code', 'wpkoi-elements'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'qr_code_width',
            [
                'label' => esc_html__('QR Code Width', 'wpkoi-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ]
            ]
        );

        $this->add_control(
            'qr_code_height',
            [
                'label' => __('QR Code Height', 'wpkoi-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ]
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


	}


	public function render( ) {

        $settings = $this->get_settings();

        ?>
		<div id="wpkoi-qrcode-<?php echo $this->get_id(); ?>"></div>
        <script>
            jQuery(function(){
				jQuery('#wpkoi-qrcode-<?php echo $this->get_id(); ?>').qrcode({
					text	: "<?php echo $settings['qr_code_content']; ?>",
					width: <?php echo $settings['qr_code_width']['size']; ?>,
					height: <?php echo $settings['qr_code_height']['size']; ?>,
				});	
            });
        </script>

    <?php
    }
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('wpkoi-qrcode',WPKOI_ELEMENTS_LITE_URL.'elements/qr-code/assets/jquery.qrcode.min.js', [ 'elementor-frontend', 'jquery' ],WPKOI_ELEMENTS_LITE_VERSION, true);
	}

	public function get_script_depends() {
		return [ 'wpkoi-qrcode' ];
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register( new Widget_Lite_WPKoi_QR_Code() );