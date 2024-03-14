<?php

namespace MegaElementsAddonsForElementor;

if( ! defined( 'ABSPATH' ) ) exit();
/**
 * Category class.
 */
class Mega_Elements_Addons_For_Elementor_Category {
    
    private static $instance = null;
    
    public function __construct() {
        $this->create_meafe_category();
    }
    
    /*
     * Create Mega Elements - Addons for Elementor Category
     * 
     * Adds category `Mega Elements - Addons for Elementor` in the editor panel.
     * 
     * @access public
     * 
     */
    public function create_meafe_category() {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'meafe-elements',
            array(
                'title' => __( 'Mega Elements - Addons for Elementor', 'mega-elements-addons-for-elementor' ),
            ),
        1);
    }
    /**
     * Creates and returns an instance of the class
     * 
     * @since  2.6.8
     * @access public
     * 
     * @return object
     */
    public static function get_instance() {
        if( self::$instance == null ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
    

/**
 * Returns an instance of the plugin class.
 * @since  2.6.8
 * @return object
 */
function mega_elements_addons_for_elementor_category() {
	return Mega_Elements_Addons_For_Elementor_Category::get_instance();
}

mega_elements_addons_for_elementor_category();
