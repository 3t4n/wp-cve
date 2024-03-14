<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 *
 * Handles generic Admin functionailties
 */
class ECSnippets_Admin {

	public function __construct() {
		add_action( 'admin_init', array($this, 'ecsnippets_save_snippet') );
		add_action( 'admin_menu', array($this, 'ecsnippets_manage_menu_pages') );
		add_action( 'admin_init', array($this, 'ecsnippets_snippet_table_bulk_action') );
	}

	/**
	 *Save new snippet or edit snippet
	 */
	public function ecsnippets_save_snippet() {

		if( isset($_GET['page']) && $_GET['page'] == 'ecsnippets-snippets' && !empty($_GET['action']) && isset($_POST['ecsnippets-snippet-save']) ) {

			// Check title field should not be empty
			if( empty($_POST['title']) ) {
				add_settings_error( 'ecsnippets-notices', '', __( 'Please enter snippet title.', 'ecsnippets' ), 'error' );
				return;
			}

			$Snippet = ECSnippets_Snippet::instance();

			$snippet_id = isset( $_GET['form_id'] ) ? esc_attr( $_GET['form_id'] ) : '';

			$snippetData = array();
			
			if( isset($_POST['title']) ) {
				$snippetData['title'] = esc_attr( $_POST['title'] );
			}

			if( isset($_POST['code']) ) {
				$snippetData['code'] = htmlentities( stripslashes( $_POST['code'] ) );
			}
			
			if( isset($_POST['position']) ) {
				$snippetData['position'] = esc_attr( $_POST['position'] );
			}

			if( !empty($snippetData) ) {	
				$Snippet->save_snippet( $snippet_id, $snippetData );
			}

			// Redirect if new snippet is adding
			if( $_GET['action'] == 'add-new-snippet' ) {
				$message = 11;
				$redirect_url = add_query_arg( array(
					'page' => 'ecsnippets-snippets',
					'message' => $message
				), admin_url('admin.php') );
				wp_redirect( $redirect_url );
				exit;
			}

			// Edit Snippet Message
			if( $_GET['action'] == 'edit-snippet' ) {
		        echo '<div class="updated" id="message">
					<p><strong>'.__( 'Snippet updated successfully.', 'ecsnippets' ).'</strong></p>
				</div>';
			}
		}
	}

	/**
	 * Manage menu page
	 *
	 * Adding required menu pages and submenu pages
	 * to manage the plugin functionality
	 */
	public function ecsnippets_manage_menu_pages() {
		
		// Add main menu page
		add_menu_page(
			__( 'Easy Code Snippets', 'ecsnippets' ),
			__( 'Easy Code Snippets', 'ecsnippets' ),
			'manage_options','ecsnippets-snippets',
			array( $this, 'ecsnippets_ltable_snippets_list' ),
			WPCS_PLUGIN_URL.'/images/icon.jpg'
		);
	}

	/**
	 * Snippet List Table
	 */
	public function ecsnippets_ltable_snippets_list() {

		$action = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : '';
		if( $action == 'add-new-snippet' || $action == 'edit-snippet' ) {
			include_once( WPCS_ADMIN_DIR . '/forms/wpcs-addedit-snippet.php' );
		} else {
			include_once( WPCS_ADMIN_DIR . '/forms/class-wpcs-snippet-list.php' );
		}
	}

	/**
	 * Bulk Action
	 */
	public function ecsnippets_snippet_table_bulk_action() {

		// Delete forms
		if( ((isset($_GET['action']) && $_GET['action'] == 'delete' ) || 
			 (isset($_GET['action2']) && $_GET['action2'] == 'delete' )
			) && isset($_GET['page']) && $_GET['page'] == 'ecsnippets-snippets'
			&& !empty($_GET['form']) && is_array($_GET['form']) ) {

			global $wpdb;

			// get redirect url
			$redirect_url = add_query_arg( array( 'page' => 'ecsnippets-snippets' ), admin_url('admin.php') );
			$snippet = $_GET['form'];
			$table_name = $wpdb->prefix . 'ecs_snippets';

			// Delete bulk Snippet
			foreach( $snippet as $key => $value ) {
				$wpdb->delete( $table_name , array( 'ID' => $value ) );
			}
			
			$redirect_url = add_query_arg( array( 'message' => '10' ), $redirect_url );
			wp_redirect( $redirect_url ); 
			exit;
		}

		// Check if more then 5 record exits.
		global $wpdb;
		$count_query = "SELECT count(*) FROM {$wpdb->prefix}ecs_snippets";
		$total_snippet = $wpdb->get_var($count_query);
		if( isset($_GET['action']) && $_GET['action'] == 'add-new-snippet' && 
			isset($_GET['page']) && $_GET['page'] == 'ecsnippets-snippets' && 
			$total_snippet >= '5' ) {
			$message = 12;
			$redirect_url = add_query_arg( array(
				'page' => 'ecsnippets-snippets',
				'message' => $message
			), admin_url('admin.php') );
			wp_redirect( $redirect_url );
			exit;
		}
	}
}
return new ECSnippets_Admin();