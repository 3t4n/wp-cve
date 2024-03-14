<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 2/06/2015
 * Time: 12:40
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {


    /**
     * Class My_Plugin_Settings_Node
     * defines each node for configuration
     * Title, Type, Desc, and Select_Options are all used to form the HTML input form on the Admin page
     */
    class My_Plugin_Settings_Node
    {

        public static $NAME = __CLASS__;

        /**
         * Settings attributes for add_settings_field
         * NB: These are set as explicit variables to make it easier to access outside..!
         * @var
         */
        public $id;
        public $title;
        public $value;
        public $section;
        //callback function and page to be managed in Settings-base

        /**
         * Additional Settings Args - used when building Settings-Page
         * @var
         */
        public $default_value;
        public $input_type;
        public $description;
        public $select_options;
        public $class;

        /**
         * Additional Settings Args as array
         * @var array
         */
        private $arg_labels = array('id',
            'title',
            'value',
            'default_value',
            'input_type',
            'description',
            'select_options',
            'class');

        public function __construct()
        {
        }

        /**
         * Static function to create a new instance of Settings Node with array vaues
         * @param array $arr_values label value array of variables
         * @return My_Plugin_Settings_Node
         */
        public static function withArray(array $arr_values)
        {
            $instance = new self();
            $instance->fill_values($arr_values);
            return $instance;
        }

        /**
         * Static function to create a new instance of Settings Node with specified values
         * @param $id
         * @param $title
         * @param null $default_value
         * @param null $section
         * @param string $input_type
         * @param null $description
         * @param null $select_options
         * @param null $class
         * @return My_Plugin_Settings_Node
         */
        public static function withValues($id,
                                          $title,
                                          $default_value = null,
                                          $section = null,
                                          $input_type = My_Plugin_Settings_Input_Type::Text,
                                          $description = null,
                                          $select_options = null,
                                          $class = null)
        {

            $instance = new self();

            $instance->id = $id;
            $instance->title = $title;

            if (isset($section)) {
                $instance->section = $section;
            }
            if (isset($default_value)) {
                $instance->default_value = $default_value;
            }
            if (isset($input_type)) {
                $instance->input_type = $input_type;
            }
            if (isset($description)) {
                $instance->description = $description;
            }
            if (isset($select_options)) {
                $instance->select_options = $select_options;
            }
            if (isset($class)) {
                $instance->class = $class;
            }

            return $instance;
        }

        /**
         * Add each provided value, as long as it exists as a property - i.e ignore others!
         * @param array $arr_values
         */
        private function fill_values(array $arr_values)
        {
            foreach ($arr_values as $label => $value) {
                if (property_exists($this::$NAME, $label))
                    $this->$label = $arr_values[$label];
            }
        }

        /**
         * Returns the $args argument for add_settings_field()
         * @return array
         */
        public function get_additional_args()
        {
            $args = array();
            foreach ($this->arg_labels as $label) {
                if (isset($this->$label))
                    $args[$label] = $this->$label;
            }
            return $args;
        }
    }
}