<?php
declare(strict_types=1);

namespace TreBiMeteo;

final class CurrentUser {

	/**
	 * @var \WP_User
	 */
	private $user;

	public function email(): string {
		$this->init();
		/**
		 * @var string $email
		 */
		$email = $this->user->get( 'user_email' );

		return $email;
	}

	private function init(): void {
		if ( $this->user !== null ) {
			return;
		}

		$this->user = \wp_get_current_user();
	}
}
