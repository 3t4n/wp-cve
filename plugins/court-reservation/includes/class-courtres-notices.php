<?php
/**
 * Define the notifications functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.5.0
 * @package    Courtres
 * @subpackage Courtres/includes
 * @author     Webmühle e.U. <office@webmuehle.at>
 */
class Courtres_Notices {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}


	/**
	 * mail after challenge was created
	 *
	 * @param  int $challenge_id
	 * @param  int $piramid_url
	 * @return true || false
	 */
	function after_challenge_created( $challenge_id, $piramid_url ) {

		if ( ! $challenge_id ) {
			return false;
		}

		if ( ! $piramid_url ) {
			$piramid_url = home_url();
		}

		$challenge    = Courtres_Entity_Challenges::get_challenge_by_id( $challenge_id );
		$headers      = array(
			sprintf( 'From: %s<%s>', $challenge['challenger']['wp_user']->display_name, $challenge['challenger']['wp_user']->user_email ),
			'content-type: text/html',
		);
		$subject      = __( 'You have been challenged by', 'court-reservation' ) . ' ' . $challenge['challenger']['wp_user']->display_name;
		$message_tmpl = __( 'You have been challenged by', 'court-reservation' ) . '  [challenger_name].<br /><a href="[accept_link]">' . __( 'Accept challenge', 'court-reservation' ) . '</a>';

		$link = add_query_arg(
			array(
				'cr-challenge' => $challenge_id,
				'cr-action'    => 'accept',
			),
			$piramid_url
		);

		$placeholders = array(
			'/\[challenger_name\]/' => $challenge['challenger']['wp_user']->display_name,
			'/\[accept_link\]/'     => $link,
		);
		$message      = preg_replace( array_keys( $placeholders ), $placeholders, $message_tmpl );

		$res = wp_mail( $challenge['challenged']['wp_user']->user_email, $subject, $message, $headers );
		return $res;
	}

}
