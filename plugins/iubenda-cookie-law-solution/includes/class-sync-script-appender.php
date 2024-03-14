<?php
/**
 * Iubenda Sync Script Handler.
 *
 * Handles the attachment of scripts into the head section directly.
 *
 * @package Iubenda
 */

/**
 * Sync Script Appender class.
 *
 * It is used to append scripts into the head section of a web page.
 */
class Sync_Script_Appender {

	/**
	 * The script URL.
	 *
	 * @var string
	 */
	const URL = 'https://cs.iubenda.com/sync/%s.js';

	/**
	 * Code extractor instance.
	 *
	 * @var Iubenda_Code_Extractor The code extractor object.
	 */
	private $code_extractor;

	/**
	 * Constructor for Head_Script_Handler.
	 *
	 * @param Iubenda_Code_Extractor $code_extractor The code extractor.
	 */
	public function __construct( Iubenda_Code_Extractor $code_extractor ) {
		$this->code_extractor = $code_extractor;
	}

	/**
	 * Handle the script.
	 */
	public function handle() {
		if ( $this->is_able_to_append_script() ) {
			// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
			?>
			<script type="text/javascript" class="_iub_cs_skip">
				var _iub = _iub || {};
				_iub.csConfiguration = {
					"siteId": "<?php echo esc_attr( $this->code_extractor->get_site_id() ); ?>",
					"cookiePolicyId": "<?php echo esc_attr( $this->code_extractor->get_cookie_policy_id() ); ?>",
				};
			</script>
			<script class="_iub_cs_skip" src="<?php echo esc_url( $this->url() ); ?>"></script>
			<?php
	        // phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript
		}
	}

	/**
	 * Check if the script is able to be appended.
	 *
	 * @return bool
	 */
	private function is_able_to_append_script() {
		return ( $this->check_script_is_already_presented() || $this->check_for_simplified_users() );
	}

	/**
	 * Check if the sync script is already presented.
	 *
	 * @return bool
	 */
	private function check_script_is_already_presented() {
		// Bail if the auto blocking is enabled.
		if ( $this->code_extractor->is_auto_blocking_enabled() ) {
			return false;
		}

		// Check if sync script is already present using manual embed code.
		foreach ( $this->code_extractor->get_scripts() as $script ) {
			if ( strpos( $script['src'], 'iubenda.com/sync/' ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if the current configuration type is simplified and the auto blocking is disabled.
	 *
	 * @return bool
	 */
	private function check_for_simplified_users() {
		// Is the current configuration type is simplified.
		$is_cs_simplified = ( new Iubenda_CS_Product_Service() )->is_cs_simplified();

		return ( $is_cs_simplified && $this->is_auto_blocking_disabled() );
	}

	/**
	 * Build the sync script url.
	 *
	 * @return string
	 */
	private function url() {
		return sprintf( static::URL, $this->code_extractor->get_site_id() );
	}

	/**
	 * Check if the auto blocking is disabled.
	 *
	 * @return bool
	 */
	private function is_auto_blocking_disabled() {
		return ! $this->code_extractor->is_auto_blocking_enabled();
	}
}
