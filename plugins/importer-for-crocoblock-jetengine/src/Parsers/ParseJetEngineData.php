<?php

namespace CodingChicken\Importer\JetEngine\Parsers;

if( !class_exists('CodingChicken\Importer\JetEngine\Parsers\ParseJetEngineData')) {
	class ParseJetEngineData {

		protected $fields = [];
		protected $post_type = '';
		protected $taxonomy_type = '';
		protected $is_cct = false;
		protected $cctParser = false;

		public function __construct() {
			$helpers = new \CodingChicken\Importer\JetEngine\Helpers\Import();
			$this->post_type = $helpers->get_post_type();
			$this->taxonomy_type = $helpers->get_taxonomy_type();
			$this->cctParser = new \CodingChicken\Importer\JetEngine\Parsers\ParseCustomContentTypes();

			// Ensure the necessary class is loaded.
			if( class_exists('Jet_Engine_Meta_Boxes')) {

				if(!empty($this->post_type) && !in_array($this->post_type, [ 'import_users', 'shop_customer', 'taxonomies' ])) { // check for customer import also

					// Grab fields from the post type also if they're set.
					$this->fields = \jet_engine()->meta_boxes->get_fields_for_context( 'post_type', $this->post_type );

				}elseif(in_array($this->post_type, ['import_users', 'shop_customer'])) {

					// Users have default fields that have to be merged into the main field list.
					// array_values used for PHP 8+ compatibility.
					$this->fields = array_merge(...array_values(\jet_engine()->meta_boxes->get_fields_for_context( 'user' )));

					// Purge any fields without an 'id' element (should only be default fields that are listed elsewhere already in the default import.
					$this->fields = array_filter( $this->fields, function ( $v ) {
						return isset($v['id']);
					} );

				}elseif(!empty($this->taxonomy_type)){

					$this->fields = \jet_engine()->meta_boxes->get_fields_for_context( 'taxonomy', $this->taxonomy_type );
				}

				// Check if import is for a CCT.
				$id = $this->cctParser->getCctId($this->post_type);

				// Only proceed if there are fields to process.
				if( !empty($this->fields) || $id !== false) {

					// Indicate current fields are not cct.
					$this->fields = array_map( function ( $x ) {
						$x['is_cct'] = false;

						return $x;
					}, $this->fields );

					if ( $id ) {
						// Retrieve CCT fields to add to list.
						$cct_fields = $this->cctParser->getMetaFields( $id );
						// Mark CCT fields so we can identify them later.
						$cct_fields = array_map( function ( $x ) {
							$x['is_cct'] = true;

							// Mark the repeater sub-fields as ccts also.
							if ( isset( $x['repeater-fields'] ) ) {
								$x['repeater-fields'] = array_map( function ( $y ) {
									$y['is_cct'] = true;

									return $y;
								}, $x['repeater-fields'] );
							}

							return $x;
						}, $cct_fields );

						// If it's a CCT merge the fields from it.
						$this->fields = array_merge( $cct_fields, $this->fields );

					}
				}

				// Allow modifications of fields.
				$this->fields = apply_filters('cc_jetengine_importer_fields_list', $this->fields);

				if( !empty($this->fields)){
					// Process glossary references in fields.
					$this->parseGlossaries();
					// Process bulk options.
					$this->parse_bulk_options();
				}

			}

		}

		public function get_fields(){
			return $this->fields;
		}

		public function get_post_type(){
			return $this->post_type;
		}

		public function get_taxonomy_type(){
			return $this->taxonomy_type;
		}

		private function parseGlossaries(){

			// We need to pull in the possible options from any assigned glossaries if the fields are configured to use one.
			foreach( $this->fields as $key => $field ){
				if( isset($field['options_from_glossary']) && $field['options_from_glossary'] ){
					// Retreive glossary options using glossary id saved in field.
					$glossary = \jet_engine()->glossaries->meta_fields->get_glossary_for_field( $field['glossary_id'] );

					if(is_array($glossary) && count($glossary) > 0){
						// Map each glossary value to an option value in the field.
						foreach($glossary as $option){
							$this->fields[$key]['options'][] = ['key' => $option['value'], 'value' => $option['label']];
						}
					}
				}

				// Parse repeater subfield glossary values.
				if( isset($field['repeater-fields'])){
					foreach( $field['repeater-fields'] as $repeater_key => $repeater_field){
						// Mark field as repeater.
						$this->fields[$key]['repeater-fields'][$repeater_key]['is_repeater'] = true;

						if( isset($repeater_field['options_from_glossary']) && $repeater_field['options_from_glossary'] ) {
							// Retreive glossary options using glossary id saved in field.
							$glossary = \jet_engine()->glossaries->meta_fields->get_glossary_for_field( $repeater_field['glossary_id'] );

							if ( is_array( $glossary ) && count( $glossary ) > 0 ) {
								// Map each glossary value to an option value in the field.
								foreach ( $glossary as $option ) {
									$this->fields[ $key ]['repeater-fields'][$repeater_key]['options'][] = [ 'key'   => $option['value'],									                                                      'value' => $option['label']
									];
								}
							}
						}
					}
				}
			}
		}

		private function parse_bulk_options(){
			foreach($this->fields as $key => $field){
				if(!empty($field['bulk_options'])){
					$bulk_options = explode("\n", $field['bulk_options']);

					foreach($bulk_options as $option){
						$option = explode('::', $option);
						// Ensure we have both a key and a value based on the supported formats:
						// One option per line. Allowed formats:
						// value - value and label will be the same
						// value::label - separate value and label
						// value::label::checked - separate value and label, checked by default
						if(!isset($option[1])){
							$option[1] = $option[0];
						}
						$this->fields[$key]['options'][] = ['key'=>$option[0], 'value'=>$option[1]];
					}
				}

				// Parse repeater subfields
				if( isset($field['repeater-fields'])){
					foreach( $field['repeater-fields'] as $repeater_key => $repeater_field){
						// Mark field as repeater.
						$this->fields[$key]['repeater-fields'][$repeater_key]['is_repeater'] = true;

						if( !empty($repeater_field['bulk_options']) ) {
							$bulk_options = explode("\n", $repeater_field['bulk_options']);

							foreach($bulk_options as $option){
								$option = explode('::', $option);
								// Ensure we have both a key and a value based on the supported formats:
								// One option per line. Allowed formats:
								// value - value and label will be the same
								// value::label - separate value and label
								// value::label::checked - separate value and label, checked by default
								if(!isset($option[1])){
									$option[1] = $option[0];
								}

								$this->fields[ $key ]['repeater-fields'][$repeater_key]['options'][] = ['key'=>$option[0], 'value'=>$option[1]];
							}
						}
					}
				}
			}
		}

	}
}