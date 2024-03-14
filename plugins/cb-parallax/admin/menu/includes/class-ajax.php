<?php
namespace CbParallax\Admin\Menu\Includes;

use CbParallax\Admin\Menu\Includes as MenuIncludes;
use WP_Error;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class responsible for the ajax functionality.
 *
 * @since             0.9.0
 * @package           bonaire
 * @subpackage        bonaire/admin/includes
 * @author            Demis Patti <demis@demispatti.ch>
 */
class cb_parallax_ajax {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var      string $domain
	 * @since    0.9.0
	 * @access   protected
	 */
	protected $domain;
	
	/**
	 * Holds the instance responsible for handling the user options.
	 *
	 * @var MenuIncludes\cb_parallax_options $options
	 * @since    0.9.0
	 * @access   protected
	 */
	protected $options;
	
	/**
	 * Holds the error text for failed nonce checks
	 *
	 * @var string $nonce_error_text
	 * @since    0.9.0
	 * @access   protected
	 */
	protected $nonce_error_text;
	
	/**
	 * cb_parallax_ajax constructor.
	 *
	 * @param string $domain
	 * @param MenuIncludes\cb_parallax_options $options
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function __construct( $domain, $options ) {
		
		$this->domain = $domain;
		$this->options = $options;
		$this->nonce_error_text = __( 'That won\'t do.', $this->domain );
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {
		
		add_action( 'wp_ajax_cb_parallax_save_options', array( $this, 'cb_parallax_save_options' ) );
		add_action( 'wp_ajax_cb_parallax_reset_options', array( $this, 'cb_parallax_reset_options' ) );
	}
	
	/**
	 * Initiates the routine to save the user-defined options for either the plugin or the specified post.
	 *
	 * Verifies the nonce, sends the data to the responsible class,
	 * receives the result and returns the answer.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function cb_parallax_save_options() {
		
		$nonce = $_REQUEST['nonce'];
		
		if ( false === wp_verify_nonce( $nonce, 'cb_parallax_manage_options_nonce' ) ) {
			
			$response = array(
				'success' => false,
				'message' => $this->nonce_error_text
			);
			
			wp_send_json_error( $response );
		}
		
		// retrieve the options
		$post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : '';
		$data = array();
		$options_arguments = '' === $_POST['post_id'] ? $this->options->get_default_options() : $this->options->get_default_options( 'image' );
		foreach ( $options_arguments as $key => $list ) {
			if ( isset( $_POST['input'][ $key ] ) ) {
				$data[ $key ] = $_POST['input'][ $key ];
			}
		}
		
		// Save options
		$result = $this->options->save_options( $data, $post_id );
		if ( is_wp_error( $result ) ) {
			/**
			 * @var WP_Error $result
			 */
			$code = $result->get_error_code();
			$msg = $result->get_error_message();
			
			if ( - 1 === $code ) {
				
				$response = array(
					'success' => true,
					'message' => $msg
				);
				
				wp_send_json_success( $response );
			}
			
			$response = array(
				'success' => false,
				'message' => $msg . ' ' . __( 'Please try again later.', $this->domain ) . ' (' . $code . ')'
			);
			
			wp_send_json_error( $response );
		} else {
			/**
			 * @var array $result
			 */
			$response = array(
				'success' => true,
				'message' => __( 'Settings saved.', $this->domain ),
				'smtp_state' => $result['smtp_state'],
				'imap_state' => $result['imap_state']
			);
			
			wp_send_json_success( $response );
		}
	}
	
	/**
	 * Initiates the routine to reset the user-defined options for either the plugin or the specified post.
	 *
	 * Verifies the nonce, sends the data to the responsible class,
	 * receives the result and returns the answer.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function cb_parallax_reset_options() {
		
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;
		
		if ( false === wp_verify_nonce( $nonce, 'cb_parallax_manage_options_nonce' ) ) {
			
			$response = array(
				'success' => false,
				'message' => $this->nonce_error_text
			);
			wp_send_json_error( $response );
		}
		
		/**
		 * Grab the post id.
		 * We use this value to determine if we're resetting plugin options or options on a per-post basis.
		 */
		$post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : '';
		
		$result = $this->options->reset_options( $post_id );
		if ( is_wp_error( $result ) ) {
			/**
			 * @var WP_Error $result
			 */
			$code = $result->get_error_code();
			$msg = $result->get_error_message();
			
			$response = array(
				'success' => false,
				'message' => $msg . ' ' . __( 'Please try again later.', $this->domain ) . '(' . $code . ')'
			);
			wp_send_json_error( $response );
		} else {
			/**
			 * @var array $result
			 */
			$response = array(
				'success' => true,
				'message' => __( 'Settings restored to default.', $this->domain ),
				'smtp_state' => $result['smtp_state'],
				'imap_state' => $result['imap_state']
			);
			wp_send_json_success( $response );
		}
	}
	
}
