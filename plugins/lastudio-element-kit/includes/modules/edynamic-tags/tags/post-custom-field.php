<?php
namespace LaStudioKitThemeBuilder\Modules\EdynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag as Base_Tag;

use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Custom_Field extends Base_Tag {

	public function get_name() {
		return 'lakit-custom-field';
	}

	public function get_title() {
		return esc_html__( 'LA-kit Custom Field', 'lastudio-kit' );
	}

	public function get_group() {
		return 'post';
	}

	public function get_categories() {
		return [ 'text', 'url', 'post_meta', 'color' ];
	}

    public function get_panel_template_setting_key() {
        return 'lakit_key';
    }

    public function is_settings_required() {
        return true;
    }

    protected function register_controls() {
        $this->add_control(
            'lakit_key',
            [
                'label' => esc_html__( 'Key', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_custom_keys_array(),
            ]
        );

        $this->add_control(
            'custom_key',
            [
                'label' => esc_html__( 'Custom Key', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'lakit_key',
                'condition' => [
                    'lakit_key' => '',
                ],
            ]
        );

    }

    public function render() {
        $key = $this->get_settings( 'lakit_key' );

        if ( empty( $key ) ) {
            $key = $this->get_settings( 'custom_key' );
        }

        if ( empty( $key ) ) {
            return;
        }

        $value = apply_filters('lakit_dynamictags/custom_field', get_post_meta( get_the_ID(), $key, true ), $key, get_the_ID() );

        echo wp_kses_post( $value );
    }

    private function get_custom_keys_array() {
        $custom_keys = get_post_custom_keys();

        $options = [
            '' => esc_html__( 'Select...', 'lastudio-kit' ),
        ];

        if ( ! empty( $custom_keys ) ) {
            foreach ( $custom_keys as $custom_key ) {
                if ( '_' !== substr( $custom_key, 0, 1 ) ) {
                    $options[ $custom_key ] = $custom_key;
                }
                else{
                    if( '_la_' === substr( $custom_key, 0, 4 ) || '_pf_' === substr( $custom_key, 0, 4 ) ){
                        $options[ $custom_key ] = $custom_key;
                    }
                }
            }
        }

        return $options;
    }
}
