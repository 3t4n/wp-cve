<?php
namespace Enteraddons\Inc;

/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('Extensions_List') ) {

class Extensions_List extends \Enteraddons\Core\Base\Elements_Map {
    
    public function getElements() {
        return [
            'pro_list' => $this->extensions_list_pro(),
            'Lite_list' => $this->extensions_list_lite()
        ];
    } 

	/**
     * Pro version widget lists
     *
     *
     */
	public static function extensions_list_pro() {
        $extensions = [
            [
                'label'     => esc_html__( 'Header & Footer Snippets', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-source-code',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Accessibilities', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-accessibilities',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Speedup', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-speedup',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Image Compressor', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-image-compressor',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Webp Converter', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-webp-converter',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Url Shortener', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-url-shortener',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Maintenance Mode', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-maintenance-mode',
                'demo_link' => '#',
                'is_pro'    => true
            ],
            [
                'label'     => esc_html__( 'Woo Builder', 'enteraddons-pro' ),
                'name'      => '',
                'icon'      => 'entera entera-maintenance-mode',
                'demo_link' => '#',
                'is_pro'    => true
            ]
        ];
        return apply_filters( 'ea_pro_extensions_list', $extensions );
	}

    /**
     * Lite version widget lists
     *
     * Name is required 
     * If name more than 1 word it's should be concatenates with _  like ( heading_option )
     *
     */
    public static function extensions_list_lite() {

        return [

            [
                'label'  => esc_html__( 'Header Footer Builder', 'enteraddons' ),
                'name'  => 'header-footer',
                'icon'   => 'entera entera-header-footer-builder',
                'demo_link' => '#'
            ]

        ];

    }


}


}