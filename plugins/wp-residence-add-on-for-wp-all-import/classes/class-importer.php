<?php
if ( ! class_exists( 'WPAI_WP_Residence_Add_On_Importer' ) ) {
    class WPAI_WP_Residence_Add_On_Importer {

		protected $add_on;
		public $logger = null;
		public $post_type = '';

		public function __construct( RapidAddon $addon_object, $post_type ) {
			$this->add_on = $addon_object;
			$this->post_type = $post_type;
		}

        public function import( $post_id, $data, $import_options, $article ) {
            switch ( $this->post_type ) {
                case 'estate_agent':
                    $this->agents( $post_id, $data, $import_options, $article );
					break;
					
				case 'estate_property':
					$this->import_property_data( $post_id, $data, $import_options, $article );
					break;
            }
		}

		
		public function import_property_data( $post_id, $data, $import_options, $article ) {
			$importer = new WPAI_WP_Residence_Property_Importer( $this->add_on );
			// all text fields and slider and image fields
			$importer->import_text_image_custom_details( $post_id, $data, $import_options, $article );
			$importer->import_property_location( $post_id, $data, $import_options, $article );						
			$importer->import_advanced_options( $post_id, $data, $import_options, $article );			
		}
		
		/**************************************
         * AGENT FIELDS IMPORT FUNCTION START
         */

        public function agents( $post_id, $data, $import_options, $article ) {
			$importer = new WPAI_WP_Residence_Agents_Importer( $this->add_on );
			$importer->import_text_fields( $post_id, $data, $import_options, $article );
		}

		public function can_update_meta( $field, $import_options ) {
            return $this->add_on->can_update_meta( $field, $import_options );
        }

        public function can_update_image( $import_options ) {
            return $this->add_on->can_update_image( $import_options );
        }	
	}	
}
?>