<?php
/**
 * Mask Image Group control class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Controls;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Group_Control_Mask_Image extends Group_Control_Base {

    /**
     * Fields.
     *
     * Holds all the background control fields.
     *
     * @access protected
     * @static
     *
     * @var array Background control fields.
     */
    protected static $fields;

    /**
     * Get background control type.
     *
     * Retrieve the control type, in this case `skt_addons_elementor_text_color`.
     *
     * @since 1.0
     * @access public
     * @static
     *
     * @return string Control type.
     */
    public static function get_type() {
        return 'skt-mask-image';
    }

    /**
     * Init fields.
     *
     * Initialize mask image control fields.
     *
     * @since 1.0
     * @access public
     *
     * @return array Control fields.
     */
    public function init_fields() {
        $fields = [];

	    $fields['mask_shape'] = [
		    'label' => _x( 'Masking Shape', 'Mask Image', 'skt-addons-elementor' ),
		    'title' => _x( 'Masking Shape', 'Mask Image', 'skt-addons-elementor' ),
		    'type' => Controls_Manager::CHOOSE,
		    'default' => 'default',
		    'options' => [
			    'default' => [
				    'title' =>_x( 'Default Shapes', 'Mask Image', 'skt-addons-elementor' ),
				    'icon' => 'skti skti-sktaddonselementor',
			    ],
			    'custom' => [
				    'title' => _x( 'Custom Shape', 'Mask Image', 'skt-addons-elementor' ),
				    'icon' => 'skti skti-image',
			    ],
		    ],
		    'toggle' => false,
		    'style_transfer' => true,
	    ];

	    $fields['mask_shape_default'] = [
		    'label' => _x( 'Default', 'Mask Image', 'skt-addons-elementor' ),
		    'label_block' => true,
            'show_label' => false,
		    'type' => Image_Selector::TYPE,
		    'default' => 'shape1',
		    'options' => sktaddonselementorextra_masking_shape_list( 'list' ),
		    'selectors' => [
			    '{{SELECTOR}}' => '-webkit-mask-image: url({{VALUE}}); mask-image: url({{VALUE}});',
		    ],
		    'selectors_dictionary' => sktaddonselementorextra_masking_shape_list( 'url' ),
		    'condition' => [
			    'mask_shape' => 'default',
		    ],
		    'style_transfer' => true,
	    ];

	    $fields['mask_shape_custom'] = [
		    'label' => _x( 'Custom Shape', 'Mask Image', 'skt-addons-elementor' ),
		    'type' => Controls_Manager::MEDIA,
            'show_label' => false,
            'description' => sprintf(
			    __( 'Note: Make sure svg support is enable to upload svg file. Or install %sSVG Support%s plugin to add svg support.', 'skt-addons-elementor' ),
			    '<a href="https://wordpress.org/plugins/svg-support/" target="_blank">',
			    '</a>'
		    ),
		    'selectors' => [
			    '{{SELECTOR}}' => '-webkit-mask-image: url({{URL}}); mask-image: url({{URL}});',
		    ],
		    'condition' => [
			    'mask_shape' => 'custom',
		    ],
		    'style_transfer' => true,
	    ];

	    $fields['mask_position'] = [
		    'label' => _x( 'Position', 'Mask Image', 'skt-addons-elementor' ),
		    'type' => Controls_Manager::SELECT,
		    'default' => 'center-center',
		    'options' => [
			    'center-center' => _x( 'Center Center', 'Mask Image', 'skt-addons-elementor' ),
			    'center-left' => _x( 'Center Left', 'Mask Image', 'skt-addons-elementor' ),
			    'center-right' => _x( 'Center Right', 'Mask Image', 'skt-addons-elementor' ),
			    'top-center' => _x( 'Top Center', 'Mask Image', 'skt-addons-elementor' ),
			    'top-left' => _x( 'Top Left', 'Mask Image', 'skt-addons-elementor' ),
			    'top-right' => _x( 'Top Right', 'Mask Image', 'skt-addons-elementor' ),
			    'bottom-center' => _x( 'Bottom Center', 'Mask Image', 'skt-addons-elementor' ),
			    'bottom-left' => _x( 'Bottom Left', 'Mask Image', 'skt-addons-elementor' ),
			    'bottom-right' => _x( 'Bottom Right', 'Mask Image', 'skt-addons-elementor' ),
		    ],
            'selectors_dictionary' => [
                'center-center' => 'center center',
                'center-left' => 'center left',
                'center-right' => 'center right',
                'top-center' => 'top center',
                'top-left' => 'top left',
                'top-right' => 'top right',
                'bottom-center' => 'bottom center',
                'bottom-left' => 'bottom left',
                'bottom-right' => 'bottom right',
            ],
		    'selectors' => [
			    '{{SELECTOR}}' => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
		    ],
		    'style_transfer' => true,
	    ];

	    $fields['mask_size'] = [
		    'label' => _x( 'Size', 'Mask Image', 'skt-addons-elementor' ),
		    'type' => Controls_Manager::SELECT,
		    'default' => 'contain',
		    'options' => [
			    'auto' => _x( 'Auto', 'Mask Image', 'skt-addons-elementor' ),
			    'cover' => _x( 'Cover', 'Mask Image', 'skt-addons-elementor' ),
			    'contain' => _x( 'Contain', 'Mask Image', 'skt-addons-elementor' ),
			    'initial' => _x( 'Custom', 'Mask Image', 'skt-addons-elementor' ),
		    ],
		    'selectors' => [
			    '{{SELECTOR}}' => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
		    ],
		    'style_transfer' => true,
	    ];

	    $fields['mask_custom_size'] = [
		    'label' => _x( 'Custom Size', 'Mask Image', 'skt-addons-elementor' ),
		    'type' => Controls_Manager::SLIDER,
		    'responsive' => true,
		    'size_units' => [ 'px', 'em', '%', 'vw' ],
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 1000,
			    ],
			    'em' => [
				    'min' => 0,
				    'max' => 100,
			    ],
			    '%' => [
				    'min' => 0,
				    'max' => 100,
			    ],
			    'vw' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'default' => [
			    'size' => 100,
			    'unit' => '%',
		    ],
		    'required' => true,
		    'selectors' => [
			    '{{SELECTOR}}' => '-webkit-mask-size: {{SIZE}}{{UNIT}}; mask-size: {{SIZE}}{{UNIT}};',
		    ],
		    'condition' => [
			    'mask_size' => 'initial',
		    ],
		    'style_transfer' => true,
	    ];

	    $fields['mask_repeat'] = [
		    'label' => _x( 'Repeat', 'Mask Image', 'skt-addons-elementor' ),
		    'type' => Controls_Manager::SELECT,
		    'default' => 'no-repeat',
		    'options' => [
			    'repeat' => _x( 'Repeat', 'Mask Image', 'skt-addons-elementor' ),
			    'repeat-x' => _x( 'Repeat-x', 'Mask Image', 'skt-addons-elementor' ),
			    'repeat-y' => _x( 'Repeat-y', 'Mask Image', 'skt-addons-elementor' ),
			    'space' => _x( 'Space', 'Mask Image', 'skt-addons-elementor' ),
			    'round' => _x( 'Round', 'Mask Image', 'skt-addons-elementor' ),
			    'no-repeat' => _x( 'No-repeat', 'Mask Image', 'skt-addons-elementor' ),
			    'repeat-space' => _x( 'Repeat Space', 'Mask Image', 'skt-addons-elementor' ),
			    'round-space' => _x( 'Round Space', 'Mask Image', 'skt-addons-elementor' ),
			    'no-repeat-round' => _x( 'No-repeat Round', 'Mask Image', 'skt-addons-elementor' ),
		    ],
            'selectors_dictionary' => [
                'repeat' => 'repeat',
                'repeat-x' => 'repeat-x',
                'repeat-y' => 'repeat-y',
                'space' => 'space',
                'round' => 'round',
                'no-repeat' => 'no-repeat',
                'repeat-space' => 'repeat space',
                'round-space' => 'round space',
                'no-repeat-round' => 'no-repeat round',
            ],
		    'selectors' => [
			    '{{SELECTOR}}' => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
		    ],
		    'style_transfer' => true,
	    ];

        return $fields;
    }


    /**
     * Filter fields.
     *
     * Filter which controls to display, using `include`, `exclude`, `condition`
     * and `of_type` arguments.
     *
     * @since 1.0
     * @access protected
     *
     * @return array Control fields.
     */
    protected function filter_fields() {
        $fields = parent::filter_fields();

        $args = $this->get_args();

        foreach ( $fields as &$field ) {
            if ( isset( $field['of_type'] ) && ! in_array( $field['of_type'], $args['types'] ) ) {
                unset( $field );
            }
        }

        return $fields;
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the mask image control. Used to return the
     * default options while initializing the mask image control.
     *
     * @since 1.0
     * @access protected
     *
     * @return array Default mask image control options.
     */
    protected function get_default_options() {
        return [
	        'popover' => [
		        'starter_name' => 'skt-mask-image',
		        'starter_title' => _x( 'Image Masking ', 'Mask Image', 'skt-addons-elementor' ),
		        'settings' => [
			        'render_type' => 'ui',
		        ],
	        ],
        ];
    }
}