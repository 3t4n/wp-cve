<?php
/**
 * Iubenda Auto-block Script Handler.
 *
 * Handles the attachment of scripts into the head section directly.
 *
 * @package Iubenda
 */

/**
 * Auto-block Script Appender class.
 *
 * It is used to append scripts into the head section of a web page.
 */
class Auto_Blocking_Script_Appender {

	/**
	 * Script URL
	 *
	 * @var string
	 */
	const URL = 'https://cs.iubenda.com/autoblocking/%s.js';

	/**
	 * Code extractor instance.
	 *
	 * @var Iubenda_Code_Extractor The code extractor object.
	 */
	private $code_extractor;

	/**
	 * Constructor for Head_Script_Handler.
	 *
	 * @param   Iubenda_Code_Extractor $code_extractor  The code extractor.
	 */
	public function __construct( Iubenda_Code_Extractor $code_extractor ) {
		$this->code_extractor = $code_extractor;
	}

	/**
	 * Handle the script for auto-blocking functionality.
	 */
	public function handle() {
		if ( $this->code_extractor->is_auto_blocking_enabled() ) {
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
	 * Build the auto-block script url.
	 *
	 * @return string
	 */
	private function url() {
		return sprintf( static::URL, $this->code_extractor->get_site_id() );
	}
}
