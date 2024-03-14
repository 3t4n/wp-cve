<?php

class Kama_Spamblock_Options {

	const OPT_NAME = 'ks_options';

	/** @var string */
	public $sibmit_button_id;

	/** @var string */
	public $unique_code;

	public function __construct() {
		$opt = array_merge( $this->default_options(), get_option( self::OPT_NAME, [] ) );
		$opt = apply_filters( 'kama_spamblock__options', $opt );
		$opt = (object) $opt;

		$this->unique_code = $opt->unique_code;
		$this->sibmit_button_id = $opt->sibmit_button_id;
	}

	/**
	 * @return string[]
	 */
	public function default_options(){

		return [
			'sibmit_button_id' => 'submit',
			'unique_code'      => 'uniq9065',
		];
	}

	public function admin_options() {
		add_settings_section( 'kama_spamblock', '', '', 'discussion' ); // set no title

		add_settings_field(
			self::OPT_NAME . '_field',
			__( 'Kama Spamblock settings', 'kama-spamblock' ),
			[ $this, 'options_field', ],
			'discussion',
			'kama_spamblock'
		);

		register_setting( 'discussion', self::OPT_NAME, [ __CLASS__, 'sanitize_opt' ] );
	}

	public static function sanitize_opt( $opts ) {

		foreach( $opts as $key => & $val ){
			if( 'sibmit_button_id' === $key ){
				$val = sanitize_html_class( $val );
			}
			elseif( 'unique_code' === $key ){
				$val = preg_replace( '~[^A-Za-z0-9*%$#@!_-]~', '', $val );
			}
			else{
				$val = sanitize_text_field( $val );
			}
		}

		return $opts;
	}

	public function options_field() {
		?>
		<p>
			<input type="text" name="<?= self::OPT_NAME ?>[sibmit_button_id]" value="<?= esc_attr( $this->sibmit_button_id ) ?>" />
			<?= __( 'ID attribute of comment form submit button. Default: <code>submit</code>', 'kama-spamblock' ) ?>
		</p>
		<p>
			<input type="text" name="<?= self::OPT_NAME ?>[unique_code]" value="<?= esc_attr( $this->unique_code ) ?>" />
			<?= __( 'Any unique code. Change it if you receave spam comments.', 'kama-spamblock' ) ?>
		</p>
		<?php
	}

	public static function settings_link( $links ) {

		$links[] = sprintf( '<a href="%s">%s</a>', admin_url( '/options-discussion.php#wpfooter' ), __( 'Settings', 'kama-spamblock' ) );

		return $links;
	}

}
