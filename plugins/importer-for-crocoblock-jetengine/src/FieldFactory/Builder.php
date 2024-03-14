<?php

namespace CodingChicken\Importer\JetEngine\FieldFactory;

if( !class_exists('CodingChicken\Importer\JetEngine\FieldFactory\Builder')) {
	class Builder {

		static protected $add_on;
		protected $post_type;
		protected $taxonomy_type;
		static protected $import_type;
		static protected $parser;
		static protected $fields;
		static private $supported_field_types = ['text','date','time','datetime-local','textarea','wysiwyg','number','colorpicker','html','select','radio','checkbox','switcher','iconpicker'];
		static private $cct_title_shown = false;
		static private $meta_title_shown = false;

		static private $relations_title_shown = false;

		public function __construct() {
			self::$add_on = \CodingChicken\Importer\JetEngine\IMPORTER_JETENGINE_Plugin::getAddon();

			self::$parser        = new \CodingChicken\Importer\JetEngine\Parsers\ParseJetEngineData();
			$this->post_type     = self::$parser->get_post_type();
			$this->taxonomy_type = self::$parser->get_taxonomy_type();
			self::$fields        = self::$parser->get_fields();

			// Set import type.
			if(in_array($this->post_type, ['import_users', 'shop_customer'])){
				self::$import_type = 'user';
			}elseif(!empty($this->taxonomy_type)){
				self::$import_type = 'taxonomy';
			}else{
				self::$import_type = 'post';
			}

			// Allow integrating other types.
			self::$supported_field_types = apply_filters('cc_jetengine_importer_supported_field_types', self::$supported_field_types);

		}

		static public function get_import_type(){
			return self::$import_type;
		}

		public function render() {

			$fields_loaded = false;

			// If $fields isn't an array then something is broken and we should abort.
			if(!is_array(self::$fields)){
				return false;
			}

			// Process each field returned.
			foreach(self::$fields as $field){
				// If we have at least one field to show update the indicator.
				$maybe_show_fields = boolval(self::render_single_field($field));
				if(!$fields_loaded && $maybe_show_fields){
					$fields_loaded = true;
				}
			}

			// Display a 'no fields to display' notice if no fields were loaded.
			if( !$fields_loaded ) {

				$fieldObject = new \CodingChicken\Importer\JetEngine\FieldFactory\Fields\None(self::$add_on, []);

				$fieldObject->render();
			}

			// Assume it all worked if we get this far.
			return true;
		}

		public function validateField($fieldToCheck){

			foreach(self::$fields as $field){
				if( $field['type'] == $fieldToCheck){
					return true;
				}
			}

			// Explicitly return false if no field is matched.
			return false;
		}

		static public function getFields(){
			return self::$fields;
		}

		static public function get_single_field_by_name( $name, $subelement = false ,$parent = false) {

			$fields_to_search = self::$fields;

			// If $subelement is set then we search for fields with that subelement first.
			if ( $subelement !== false && $parent !== false) {

				// Find target parent field.
				$fields_to_search = array_filter( $fields_to_search, function ($v) use ($parent){
					return $v['name'] == $parent;
				});

				// Only get any subfields defined for subelement.
				$fields_to_search = array_column($fields_to_search, $subelement)[0]; // Remove extraneous parent array.
			}

			// Search fields for field to import.
			$fields_to_search = array_filter( $fields_to_search, function ( $v ) use ( $name ) {
				return $v['name'] == $name; // Matching by name since it's unique per import type and id can change.
			} );

			return reset($fields_to_search); // remove extraneous outer array.

		}

		static public function import($fieldData, $import_type = null){
			// Add import type.
			// Allow overriding it for special processing.
			$fieldData['import_type'] = $import_type ?? self::$import_type;

			// If it's not a repeater field then match to top level fields.
				// We need to include the correct field data when initializing the builder.
				$field = array_filter( self::$fields, function ( $v ) use ( $fieldData ) {
					return $v['name'] == $fieldData['name']; // Matching by name since it's unique per import type and id can change.
				} );

			// The field comes back as a nested array, but it shouldn't be nested.
			$field = reset( $field );

			// Get builder.
			$fieldObject = self::loadFieldObject($field, true);

			// Ensure the object was loaded and that it has the 'import' method.
			if(is_object($fieldObject) && method_exists(get_class($fieldObject), 'import')) {
				// Import field data.
				return $fieldObject->import( $fieldData );
			}

			return false;
		}

		static public function render_single_field($field){
			// Only process data fields and skip appearance/formatting fields and that are in the supported list.
			if( !isset($field['object_type']) || $field['object_type'] != 'field' || $field['type'] == 'hidden'){
				return false;
			}

			// Display Titles as needed.
			if(!self::$cct_title_shown && !empty($field['is_cct'])){
				// Provide a subheading.
				self::$add_on->add_title('Custom Content Type fields'); // This should be changed to HTML later so it's better styled.
				self::$cct_title_shown = true;
			}elseif(!self::$meta_title_shown && empty($field['is_cct'])){
				// Provide a subheading.
				self::$add_on->add_title('Meta fields');
				self::$meta_title_shown = true;
			}elseif(!self::$relations_title_shown && empty($field['is_cct']) && !empty($field['is_relation'])){
				// Provide a subheading.
				self::$add_on->add_title( 'Relations');
				self::$relations_title_shown = true;
			}

			if( !in_array($field['type'], self::$supported_field_types) ){
				self::unsupported($field);
				return false;
			}

			// Generate field or display 'unsupported' message.
			if($fieldObject = self::loadFieldObject($field)){
				return $fieldObject->render($field);
			}else{
				self::unsupported($field);
				return false;
			}

		}

		static private function unsupported($field){

			$fieldObject = new \CodingChicken\Importer\JetEngine\FieldFactory\Fields\Unsupported(self::$add_on, $field);

			return $fieldObject->render();
		}

		static public function loadFieldObject($field, $import = false) {
			// Determine field type to process.
			if($import !== false && isset($field['is_repeater']) && $field['is_repeater']){
				$type = 'repeater';
			}else{
				$type = $field['type'];
			}

			// Action to allow loading additional field libraries before the class check.
			do_action( 'cc_jetengine_importer_pre_load_field_type_library', $type );

			// Make sure the class is available or show an unsupported message.
			if ( class_exists( 'CodingChicken\Importer\JetEngine\FieldFactory\Fields\\' . self::parseName( $type ) ) ) {
				$class = '\CodingChicken\Importer\JetEngine\FieldFactory\Fields\\' . self::parseName( $type );
				return new $class( self::$add_on, $field );
			}elseif( class_exists( 'CodingChicken\Pro\Importer\JetEngine\FieldFactory\Fields\\' . self::parseName( $type))){
				$class = '\CodingChicken\Pro\Importer\JetEngine\FieldFactory\Fields\\' . self::parseName( $type );
				return new $class( self::$add_on, $field );
			}

			// Return false if no builder could be loaded.
			return false;
		}

		static private function parseName($name, $style = 'class'){
			if($style == 'class'){
				return ucfirst(str_replace('-','_', $name));
			}else{
				return str_replace('-','_', $name);
			}
		}

	}
}

/*
 Field array returned from JetEngine
array(3) {
  [0]=>
  array(9) {
    ["title"]=>
    string(8) "repeater"
    ["name"]=>
    string(8) "repeater"
    ["object_type"]=>
    string(5) "field"
    ["width"]=>
    string(4) "100%"
    ["options"]=>
    array(0) {
    }
    ["type"]=>
    string(8) "repeater"
    ["id"]=>
    int(9453)
    ["isNested"]=>
    bool(false)
    ["repeater-fields"]=>
    array(1) {
      [0]=>
      array(4) {
        ["title"]=>
        string(13) "repeater-text"
        ["name"]=>
        string(13) "repeater-text"
        ["type"]=>
        string(4) "text"
        ["id"]=>
        int(6789)
      }
    }
  }

 */