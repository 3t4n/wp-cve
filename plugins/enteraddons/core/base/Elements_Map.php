<?php
namespace Enteraddons\Core\Base;
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

if( !class_exists('Elements_Map') ) {

    class Elements_Map {

        private static $listPro;
        private static $listLite;

        function __construct() {
            $this->init();
        }

        public function init() {
            $this->setElements();
        }

        public function elements_maping() {
        	$liteElements = self::$listLite;
            $proElements  = self::$listPro;
            $elements = wp_parse_args( $proElements, $liteElements );
            return $elements;
        }

        public function setElements() {
            $elements = $this->getElements();
            self::$listPro  = $elements['pro_list'];
            self::$listLite = $elements['Lite_list'];
        }

        public function getElements() {}

        public function getAllElements() {
            return $this->elements_maping();
        }
        
    }

}