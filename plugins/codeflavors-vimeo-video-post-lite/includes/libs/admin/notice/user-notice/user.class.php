<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Admin\Notice\User_Notice;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * @ignore
 */
class User {
	/**
	 * Stores the minimum capability that the user must
	 * have
	 *
	 * @var string
	 */
	private $capability;
	/**
	 * Store user meta name that will contain user choices.
	 *
	 * @var string
	 */
	private $meta_name;
	/**
	 * Days to delay the message is user opts to be notified at a later time
	 *
	 * @var int
	 */
	private $delay;

	/**
	 * Constructor
	 *
	 * @param string $meta_name
	 * @param string $capability
	 * @param int $delay
	 */
	public function __construct( $meta_name, $delay = 7, $capability = 'manage_options' ){
		$this->capability = $capability;
		$this->meta_name = $meta_name;
		$this->delay = $delay;

		add_action( 'admin_init', array(
			$this,
			'check_option'
		), 9999999 );
	}

	/**
	 * Checks if currently logged in user is authorized to view the content
	 * based on his capability
	 *
	 * @return boolean - can view (true) or can't view (false) the content
	 */
	public function can_see(){
		if( !current_user_can( $this->capability ) ){
			return false;
		}

		$preference = $this->get_user_preference();
		if( $preference ){
			if( 'yes' == $preference[ 'answer' ] ){
				return false;
			}else if( 'later' == $preference[ 'answer' ] ){
				return ( $preference[ 'time' ] + ( $this->delay * DAY_IN_SECONDS ) ) <= time();
			}
		}
		// if no preference set, user hasn't made a choice so he can view the message
		return true;
	}

	/**
	 * Checks if user clicked on an option into the message and stores it
	 */
	public function check_option(){
		if( isset( $_GET[ $this->meta_name ] ) ){
			check_admin_referer( 'vimeotheque_review_action', 'vmtq_nonce' );

			$answers = array(
				'yes',
				'later'
			);
			if( in_array( $_GET[ $this->meta_name ], $answers ) ){
				$option = array(
					'answer' => $_GET[ $this->meta_name ],
					'time' => time()
				);
				update_user_meta( get_current_user_id(), $this->meta_name, $option );
			}
		}
	}

	/**
	 * Returns the option chosen by the user from db
	 *
	 * @return string
	 */
	private function get_user_preference(){
		return get_user_meta( get_current_user_id(), $this->meta_name, true );
	}

	/**
	 * Returns the paramter that must be set on GET to trigger
	 * user option storage
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	public function get_query_arg( $type = 'no' ){
		$r = array();
		$r[ $this->meta_name ] = $type;
		$r[ 'vmtq_nonce' ] = wp_create_nonce( 'vimeotheque_review_action' );
		return $r;
	}
}