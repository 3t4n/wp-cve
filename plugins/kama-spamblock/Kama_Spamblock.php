<?php

class Kama_Spamblock {

	/** @var Kama_Spamblock_Options */
	public $opt;

	/** @var string */
	public $plug_dir;

	/** @var string */
	public $plug_file;

	/** @var string */
	private $nonce = '';

	/**
	 * `comment` for WP 5.5+
	 *
	 * @var string[]
	 */
	private $process_comment_types = [ '', 'comment' ];

	/**
	 * @param string $plug_file
	 */
	public function __construct( $plug_file ) {

		$this->opt = new Kama_Spamblock_Options();

		$this->plug_file = $plug_file;
		$this->plug_dir = dirname( $plug_file );

		$this->process_comment_types = apply_filters( 'kama_spamblock__process_comment_types', $this->process_comment_types );
	}

	public function init_plugin() {

		if( ! defined( 'DOING_AJAX' ) ){
			load_plugin_textdomain( 'kama-spamblock', false, basename( $this->plug_dir ) . '/languages' );
		}

		is_admin()
			? $this->init_admin()
			: $this->init_front();
	}

	private function init_admin() {
		add_action( 'admin_init', [ $this->opt, 'admin_options' ] );
		add_filter( 'plugin_action_links_' . plugin_basename( $this->plug_file ), [ Kama_Spamblock_Options::class, 'settings_link' ] );
	}

	private function init_front() {
		if( ! $this->process_comment_types ) {
			return;
		}

		if( ! wp_doing_ajax() && ! is_admin() ){
			add_action( 'wp_footer', [ $this, 'main_js' ], 99 );
		}

		$this->nonce = self::make_nonce( date( 'jn' ) . $this->opt->unique_code );

		add_filter( 'preprocess_comment', [ $this, 'block_spam' ], 0 );
	}

	/**
	 * Check and block comment if needed.
	 *
	 * @param array $commentdata
	 *
	 * @return array
	 */
	public function block_spam( $commentdata ) {

		$this->block_pings_trackbacks( $commentdata );
		$this->block_regular_comment( $commentdata );

		return $commentdata;
	}

	private function block_pings_trackbacks( $commentdata ) {

		if( ! in_array( $commentdata['comment_type'], [ 'trackback', 'pingback' ], true ) ){
			return;
		}

		$external_html = wp_remote_retrieve_body( wp_remote_get( $commentdata['comment_author_url'] ) );

		$quoted_home_url = preg_quote( parse_url( home_url(), PHP_URL_HOST ), '~' );
		$has_backlink = preg_match( "~<a[^>]+href=['\"](https?:)?//$quoted_home_url~si", $external_html );

		if( ! $has_backlink ){
			die( 'no backlink.' );
		}
	}

	private function block_regular_comment( $commentdata ) {

		if( ! in_array( $commentdata['comment_type'], $this->process_comment_types, true ) ) {
			return;
		}

		$ksbn_code = isset( $_POST['ksbn_code'] ) ? trim( $_POST['ksbn_code'] ) : '';

		if( self::make_nonce( $ksbn_code ) !== $this->nonce ){
			/** @noinspection ForgottenDebugOutputInspection */
			wp_die( $this->block_form() );
		}
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	private static function make_nonce( $key ) {
		// maybe already md5
		return preg_match( '/^[a-f0-9]{32}$/', $key ) ? $key : md5( $key );
	}

	/**
	 * @return void
	 */
	public function main_js() {
		global $post;

		// note: is_singular() may work incorrectly
		if( ! empty( $post ) && ( 'open' !== $post->comment_status ) && is_singular() ){
			return;
		}
		?>
		<script id="kama_spamblock">
			(function(){

				const catch_submit = function( ev ){

					let sbmt = ev.target.closest( '#<?= esc_html( $this->opt->sibmit_button_id ) ?>' );

					if( ! sbmt ){
						return;
					}

					let input = document.createElement( 'input' );
					let date = new Date();

					input.value = ''+ date.getUTCDate() + (date.getUTCMonth() + 1) + '<?= esc_html( $this->opt->unique_code ) ?>';
					input.name = 'ksbn_code';
					input.type = 'hidden';

					sbmt.parentNode.insertBefore( input, sbmt );
				}

				document.addEventListener( 'mousedown', catch_submit );
				document.addEventListener( 'keypress', catch_submit );
			})()
		</script>
		<?php
	}

	/**
	 * Output form when comment has been blocked.
	 *
	 * @return string
	 */
	private function block_form() {
		ob_start();
		?>
		<h1><?= __( 'Antispam block your comment!', 'kama-spamblock' ) ?></h1>

		<form method="post" action="<?= site_url( '/wp-comments-post.php' ) ?>">
			<p>
				<?= sprintf(
			       __( 'Copy %1$s to the field %2$s and press button', 'kama-spamblock' ),
			       '<code style="background:rgba(255,255,255,.2);">' . $this->nonce . '</code>',
			       '<input type="text" name="ksbn_code" value="" style="width:150px; border:1px solid #ccc; border-radius:3px; padding:.3em;" />'
		       ) ?>
			</p>

			<input type="submit" style="height:70px; width:100%; font-size:150%; cursor:pointer; border:none; color:#fff; background:#555;" value="<?= __( 'Send comment again', 'kama-spamblock' ) ?>" />

			<?php
			unset( $_POST['ksbn_code'] );

			foreach( $_POST as $key => $val ){
				echo sprintf( '<textarea style="display:none;" name="%s">%s</textarea>', $key, esc_textarea( stripslashes( $val ) ) );
			}
			?>
		</form>
		<?php
		return ob_get_clean();
	}

}

