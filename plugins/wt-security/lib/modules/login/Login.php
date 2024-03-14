<?php
if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

require_once 'Captcha.php';
require_once 'BFProtection.php';
require_once 'GoogleAuthenticator.php';

/**
 * WebTotem Login class for Wordpress.
 */
class WebTotemLogin {

	const RECOVERY_CODE_SIZE = 8;
	const RECOVERY_CODE_COUNT = 5;

	/**
	 * Generates a new set of recovery codes and saves them to $user if provided.
	 *
	 * @param int $count
	 * @return array
	 */
	public static function generate_recovery_codes($count = self::RECOVERY_CODE_COUNT) {
		$codes = array();
		for ($i = 0; $i < $count; $i++) {
			$codes[] = self::random_bytes(self::RECOVERY_CODE_SIZE);
		}

		return $codes;
	}

	/**
	 * Save user's 2fa data.
	 *
	 * @param int $user_id
	 *   User ID.
	 *
	 * @return bool
	 *   Returns TRUE after save settings.
	 */
	public static function saveData( int $user_id, $codes, $secret ) {

		$data = json_decode(WebTotemOption::getOption('two_factor_data'), true) ?: [];
		$data[$user_id]['recovery'] = $codes;
		$data[$user_id]['secret'] = $secret;
		WebTotemOption::setOptions(['two_factor_data' => json_encode($data)]);

		return TRUE;
	}

	/**
	 * Delete user's 2fa data.
	 *
	 * @param int $user_id
	 *   User ID.
	 *
	 * @return bool
	 *   Returns TRUE after save settings.
	 */
	public static function delete( int $user_id ) {
		$data = json_decode(WebTotemOption::getOption('two_factor_data'), true) ?: [];

		if(isset($data[$user_id])) {
			unset($data[$user_id]);
		}
		WebTotemOption::setOptions(['two_factor_data' => json_encode($data)]);

		return TRUE;
	}

	/**
	 * Get user's 2fa data.
	 *
	 * @param int $user_id
	 *   User ID.
	 *
	 * @return mixed
	 *   Returns saved data by option name.
	 */
	public static function getData($user_id) {

		$data = json_decode(WebTotemOption::getOption('two_factor_data'), true) ?: [];

		if(array_key_exists($user_id, $data)){
			return $data[$user_id];
		}

		return false;
	}

	/**
	 * has user 2FA activated.
	 *
	 * @param WP_User $user
	 *   User.
	 *
	 * @return bool
	 */
	public static function hasUser2faActivated($user){
		if(self::getData($user->ID)){
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Check 2FA code by user.
	 *
	 * @param WP_User $user
	 *   User.
	 *
	 * @return bool
	 */
	public static function check2faCode($user, $code){
		$data = self::getData($user->ID);
		$g = new WebTotemGoogleAuthenticator();
		$code = trim($code);

		if(strlen($code) === 6 and $g->checkCode($data['secret'], $code)) {
			return true;
		} else if (strlen($code) >= 16) {
			$code = str_replace(' ', '', $code);
			$recovery = explode(',', $data['recovery']);
            $is_verify = false;
			foreach ($recovery as $key => $recoveryCode){
				if($recoveryCode === $code){
                    $is_verify = true;
                    break;
                }
			}
            if($is_verify){
                // Delete this code from the database.
                unset($recovery[$key]);
                $recovery = implode(',', $recovery);
                self::saveData($user->ID, $recovery, $data['secret']);

                return true;
            }
		}

		return false;
	}

	/**
	 * Get recovery data.
	 *
	 * @param WP_User $user
	 *   User.
	 *
	 * @return array
	 *   Returns saved data by option name.
	 */
	public static function getRecoveryData( $user ){
		$recovery = self::generate_recovery_codes();

		$fileContents = sprintf(__('Two-Factor Authentication Recovery Codes. %s (%s)', 'wtotem'), home_url(), $user->user_login) . "\r\n";
		$fileContents .= "\r\n" . __('Each line is a single recovery code, with optional spaces for readability. Your recovery codes are:', 'wtotem') . "\r\n\r\n";
		$recoveryBlocks = [];
		foreach ($recovery as $c) {
			$hex    = bin2hex( $c );
			$blocks = str_split( $hex, 4 );
			$blocks = implode( ' ', $blocks );
			$fileContents .= $blocks . "\r\n";
			$recoveryBlocks[] = $blocks;
		}

		$fileContents = str_replace("\n", "\\n", str_replace("\r", "\\r", addslashes($fileContents)));

		return [
			'fileName' => WEBTOTEM_SITE_DOMAIN . '_' . $user->user_login . '_recovery_codes.txt',
			'fileContents' => $fileContents,
			'recovery' => implode(',', array_map(function($c) { return bin2hex($c); }, $recovery)),
			'blocks' => $recoveryBlocks,
		];

	}

	/**
	 * @throws Exception
	 */
	public static function random_bytes($length) {
		$length = (int) $length;
		if (function_exists('random_bytes')) {
				$rand = random_bytes($length);
				if (is_string($rand)) {
					return $rand;
				}
		}

		$return = '';
		for ($i = 0; $i < $length; $i++) {
			$return .= chr(mt_rand(0, 255));
		}
		return $return;
	}

    /**
     * Check to any two-factor activated.
     *
     * @return bool returns true if at least one activation.
     */
    public static function anyTwoFactorActivated($user = null) {
        $data = json_decode(WebTotemOption::getOption('two_factor_data'), true) ?: [];
        if($data){
            return true;
        }
        return false;
    }

	/**
	 * Get two factor authenticator data.
	 *
	 * @return array
	 *   Returns google authenticator data.
	 */
	public static function getTwoFactorData($user = null) {

        if(!$user) { $user = wp_get_current_user(); };

		if($data = self::getData($user->ID)) {

			return [
				'isActivated' => true,
				'recovery' => explode(',', $data['recovery']),
			];
		}

		$data = self::getRecoveryData($user);
		$g = new WebTotemGoogleAuthenticator();

		$host = WebTotemOption::getMainHost();
		$data['secret'] = $g->generateSecret();
		$data['qr_url'] = $g->getURL( $user->user_login, $host['name'], $data['secret'] );

		$data['isActivated'] = false;

		return $data;

	}


    /**
     * Checks whether two-factor authorization is activated on the site.
     *
     * @return bool.
     */
    public static function isTwoFactorEnabled($user = null) {
        return WebTotemOption::getPluginSettings('two_factor');
    }

}