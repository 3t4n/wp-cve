<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Admin\Notice;

use Vimeotheque\Admin\Notice\User_Notice\Message;
use Vimeotheque\Admin\Notice\User_Notice\User;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * @ignore
 */
class Review_Notice extends Notice_Abstract implements Notice_Interface {
	/**
	 * Stores the number of days that must pass
	 * before showing users the notice
	 *
	 * @var interger - number of days
	 */
	private $delay;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var Message
	 */
	private $message;
	/**
	 * Stores option name from database
	 *
	 * @var string
	 */
	private $option_name;

	/**
	 * Review_Notice constructor.
	 *
	 * @param $option_name
	 * @param Message $message
	 * @param User $user
	 * @param int $delay
	 */
	public function __construct( $option_name, Message $message, User $user, $delay = 7 ) {
		parent::__construct();

		$this->option_name = $option_name;
		$this->delay = $delay;
		$this->message = $message;
		$this->user = $user;

		// pass user object to message object
		$this->message->set_user( $this->user );
	}

	/**
	 * @inheritDoc
	 */
	public function get_notice() {
		/**
		 * Filter that can prevent all plugin messages from being displayed.
		 *
		 * @ignore
		 *
		 * @param bool $allow   Allow notice to be displayed (true) or prevent it (false)
		 */
		$allow = apply_filters( 'vimeotheque\admin\notice\review_notice', true );
		if( !$allow ){
			return;
		}

		// check if user can see the message
		if( !$this->user->can_see() ){
			return;
		}

		// check if timer is expired
		if( $this->timer_expired() ){
			$this->message->display();
		}
	}

	/**
	 * Get option from database
	 *
	 * @return array - the option
	 */
	private function get_option(){
		$option = get_option( $this->option_name, false );
		if( ! $option ){
			$option = $this->_set_option();
		}
		return $option;
	}

	/**
	 * Set option in database
	 *
	 * @return array - the option
	 */
	private function _set_option(){
		$option = array(
			'timestamp' => time()
		);

		update_option( $this->option_name, $option );
		return $option;
	}

	/**
	 * Returns days delay in seconds
	 *
	 * @return integer - number of seconds
	 */
	private function delay_in_seconds(){
		return DAY_IN_SECONDS * $this->delay;
	}

	/**
	 * Check if DB timer is expired
	 *
	 * @param boolean $extended - when true, $this->delay will be doubled
	 * @return boolean - timer is expired (true) or not (false)
	 */
	private function timer_expired( $extended = false ){
		$option = $this->get_option();
		$delay = $this->delay_in_seconds();
		if( $extended ){
			$delay *= 2;
		}
		return ( time() - $option[ 'timestamp' ] >= $delay );
	}
}