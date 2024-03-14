<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 3/06/2015
 * Time: 18:35
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {
    /**
     * Class My_Plugin_Section_Node
     * http://codex.wordpress.org/Function_Reference/add_settings_section
     */
    class My_Plugin_Section_Node
    {
        public $id;
        public $title;
        public $description;
        public $tab;

        // $page and $callback will be set by the parent class My_Plugin_Settings_Admin

        /**
         * @param $id
         * @param $title
         */
        function __construct($id, $title, $description = null, $tab = null)
        {
            $this->id = $id;
            $this->title = $title;
            if (isset($tab)) {
                $this->tab = $tab;
            }
            if (isset($description)) {
                $this->description = $description;
            }
        }


        /**
         * Converts a key value to lower case and replaces spaces with '_'
         * @return string
         * @internal param $value
         */
        public function get_tab_key()
        {
            $key = strtolower(str_replace(' ', '_', $this->tab));
            return $key;
        }

    }
}