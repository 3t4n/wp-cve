<?php

namespace CodingChicken\Importer\JetEngine\Parsers;

if( !class_exists('CodingChicken\Importer\JetEngine\Parsers\ParseCustomContentTypes')) {
	class ParseCustomContentTypes {
		private $parser = false;
		private $add_on = false;
		private $helpers = false;

		public function __construct(){
			if( class_exists('CodingChicken\Pro\Importer\JetEngine\Parsers\ParseCustomContentTypes')) {
				$this->parser = new \CodingChicken\Pro\Importer\JetEngine\Parsers\ParseCustomContentTypes();

			}else{
				// Get addon instance.
				$this->add_on = \CodingChicken\Importer\JetEngine\IMPORTER_JETENGINE_Plugin::getAddon();

				// load helpers.
				$this->helpers = new \CodingChicken\Importer\JetEngine\Helpers\Import();
			}
		}

		public function __call($name, $args){
			if($this->parser === false){
				if($this->isCCT()) {
					$this->add_on->add_title('Custom Content Type fields'); // This should be changed to HTML later so it's better styled.
					$this->add_on->add_text( '<span style="font-weight:500;display:block;padding-top:10px;padding-bottom:0;" class="cc_jetengine_importer_custom_content_types_unsupported">Install the Coding Chicken - JetEngine Importer Pro Pack to add CCT support: <a href="https://codingchicken.com/downloads/crocoblock-jetengine-importer-pro-pack/" target="_blank">Click Here</a></span>' );
				}

			}elseif(method_exists($this->parser, $name)){
				return $this->parser->{$name}(...$args); // Use spread operator.

			}

			return false;
		}

		private function isCCT(){
			$slug = $this->helpers->get_post_type();

			if($slug == 'taxonomies'){
				$slug = $this->helpers->get_taxonomy_type();
			}

			global $wpdb;

			// Check if post type is a CCT without a single page reference.
			if(!empty($slug)) {
				$query = $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}jet_post_types WHERE `slug` = %s", $slug );

				$id = $wpdb->get_var( $query );

				if ( ! empty( $id ) && is_numeric( $id ) && ! is_wp_error( $id ) ) {
					return true; // It is a CCT.
				} else {
					$search_value = rtrim(str_replace('a:1:{', '', serialize( [ 'related_post_type' => $slug ] )), '}');

					$query = $wpdb->prepare( "SELECT id FROM {$wpdb->prefix}jet_post_types WHERE `args` LIKE '%%%s%%';", $search_value );

					$id = $wpdb->get_var( $query );

					if ( ! empty( $id ) && is_numeric( $id ) && ! is_wp_error( $id ) ) {

						return true; // It is a CCT.

					}

				}

			}

			return false;
		}

	}
}

/*


            ob_start();
            var_dump($meta_boxes);
            error_log(ob_get_clean());

		   // $type_instance = $module->manager->get_content_types( 'auto' );




 */
