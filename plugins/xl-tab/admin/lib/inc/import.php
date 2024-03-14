<?php
namespace Elementor\TemplateLibrary;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( did_action( 'elementor/loaded' ) ) {
	class XL_Tab_Import_Lib extends Source_Base {

		public function __construct() {
			parent::__construct();
			add_action( 'wp_ajax_xl_tab_import_template', array($this, 'xl_tab_import_data'));
		}

		public function get_id() {}
		public function get_title() {}
		public function register_data(){ }
		public function get_items( $args = [] ){}
		public function get_item( $template_id ){}
		public function get_data( array $args ){}
		public function delete_template( $template_id ){}
		public function save_item( $template_data ){}
		public function update_item( $new_data ){}
		public function export_template( $template_id ){}

		public function xl_tab_import_data() {

			$id = $_POST['id'];
			$remote = \XL_Tab_Library::$plugin_data["remote_site"];
			$end_point = \XL_Tab_Library::$plugin_data["single_endpoint"];
			$data = json_decode( wp_remote_retrieve_body( wp_remote_get( $remote.'wp-json/wp/v2/'.$end_point.'/?id='.$id )), true );
			$content = $data['content'];
			$content = $this->process_export_import_content( $content, 'on_import' );
			$content = $this->replace_elements_ids( $content );
			echo json_encode($content);
			wp_die();
		}
	}

	new XL_Tab_Import_Lib();
}