<?php
/**
 * Controller class
 * @author KG
 * @version 1.0.0
 * @package Forms
 */
 
if ( ! class_exists( 'wpe_Core_Controller' ) ) {

	/**
	 * Controller class to display views.
	 * @author: KG
	 * @version: 3.0.0
	 * @package: Core
	 */
	class wpe_Core_Controller {
		
		/**
		 * Store object type
		 * @var  String
		 */
		private $entity;
		
		/**
		 * Store entity object return  by factory
		 * @var Object
		 */
	    private	$entityObj;
	    
	    /**
	     * Store properties of the $entity object.
	     * @var Array
	     */
	    private $entityObjProperties;
		
		/**
		 * Intialize controller properties
		 * @param String $objectType Pass type of the Object.
		 */
	    function __construct($objectType) {
			$this->entity = $objectType;
			if ( file_exists( WPE_Model.$this->entity.'/model.'.$this->entity.'.php' ) ) {
				$factoryObject = new FactoryModelWPE();
				$this->entityObj = $factoryObject->create_object( $this->entity );
				if ( is_object( $this->entityObj ) ) {
					$this->entityObjProperties = get_object_vars( $this->entityObj ); }
			}
		}
		
		/**
		 * Load requested views.
		 * @param  String $view View name.
		 * @param array  $options View Options.
		 */
		public function display($view, $options = array(),$perform_action=true) {
			if($perform_action)
				$response = $this->do_action();
			switch ( $view ) {
			 	default : $view = $view.'.php';
			}
			if ( ! empty( $view ) ) {
				return include( WPE_Model. "{$this->entity}/views/".$view );
			}
		}
		
		/**
		 * Return entity name.
		 * @return String Type of entity.
		 */
		protected function get_entity() {
			return $this->entity;
		}
		
		/**
		 * Handle form submissions
		 * @param  string $action Action name.
		 * @return [type]         Success or Failure response.
		 */
		protected function do_action( $action = '' ) {
			global $wpdb;
			try {
				if ( isset( $_POST['operation'] ) and sanitize_text_field( $_POST['operation']  ) != '' ) {
					$operation = sanitize_text_field( $_POST['operation']  );
					if(method_exists($this->entityObj,$operation)){
						$response = $this->entityObj->$operation();
					}
				}
			} catch (Exception $e) {
				$response['error'] = $e->getMessage();
			}
		if (empty($response)) {$response=0;}
			return $response;
		}
		
		/**
		 * Handle Add & Edit operations.
		 * @return Array Success or Failure response.
		 */
		protected function action_add_edit() {
			if ( isset( $_REQUEST['_wpnonce'] ) ) {
					$nonce = sanitize_text_field( $_REQUEST['_wpnonce'] ); }
			if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {
				die( 'Cheating...' );
			}
			$response = array();
			// Ignore changes in these class variables while setting up class object for insertion/updation.
	    	$properties_to_ignore = array( 'validations','table','unique' );
	    	foreach ( $properties_to_ignore as $classproperty ) {
				if ( array_key_exists( $classproperty,$this->entityObjProperties ) ) {
					unset( $this->entityObjProperties[ $classproperty ] ); }
			}
	    	foreach ( @$this->entityObjProperties as $key => $val ) {
	    		if ( isset( $_POST[ $key ] ) and ! is_array( $_POST[ $key ] ) ) {
					$post_key = sanitize_text_field( $_POST[ $key ] );
				} else {
					$post_key = array_map( 'attr', (array) sanitize_text_field( $_POST[ $key ] ) );
				}
				if ( isset( $post_key ) ) {
					@$this->entityObj->set_val( $key,$post_key );
				}
			}
			if ( isset( $_POST['entityID'] ) ) {
				// Setting value of Id field in case of edit.
				$this->entityObj->set_val( $this->entity.'_id',intval( sanitize_text_field( $_POST['entityID'] ) ) ); }
	    	if ( $this->entityObj->save() > 0 ) {
					$current_obj_name = ucfirst( $this->entity );
				if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] ) {
					$response['success']  = __( $current_obj_name.' '.'updated successfully',WPE_TEXT_DOMAIN );
				} else { 					$response['success']  = __( $current_obj_name.' '.'added successfully'.$current_obj_name.'s <a href="'.admin_url( 'admin.php?page=wpe_manage_'.$this->entity ).'">here</a>.', WPE_TEXT_DOMAIN ); }
			   	$_POST = array();
			}
			return $response;
		}
		
		/**
		 * Handle import locations action.
		 * @return Array Success or Failure Information.
		 */
		public function action_import_location() {
			$response = $this->entityObj->import_location();
			return $response; 
		}
		
		/**
		 * Handle Backup action.
		 * @return Array Success or Failure Information.
		 */
		public function action_take_backup() {
			$response = $this->entityObj->take_backup();
			return $response; 
		}
		
		/**
		 * Handle upload backup action.
		 * @return Array Success or Failure Information.
		 */
		public function action_upload_backup() {
			$response = $this->entityObj->upload_backup();
			return $response; 
		}
		
		/**
		 * Handle import backup action.
		 * @return Array Success or Failure Information.
		 */
		public function action_import_backup() {
			$response = $this->entityObj->import_backup();
			return $response; 
		}
	}
}