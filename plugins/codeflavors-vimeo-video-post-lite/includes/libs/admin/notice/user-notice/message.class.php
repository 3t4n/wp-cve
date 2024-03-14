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
class Message {
	/**
	 * Store user reference
	 *
	 * @var User
	 */
	private $user;

	/**
	 * Review page URL
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Message that will be presented to user
	 *
	 * @var string
	 */
	private $message;

	/**
	 * Constructor
	 *
	 * @param string $message
	 * @param string $url
	 */
	public function __construct( $message, $url ){
		$this->url     = $url;
		$this->message = $message;
	}

	/**
	 * Saves the User instance needed to display message actions
	 *
	 * @param User $user
	 */
	public function set_user( User $user ){
		$this->user = $user;
	}

	/**
	 * Displays the message passed to the constructor
	 * including the footer links with user options
	 */
	public function display(){
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo $this->message;?></p>
			<p><?php $this->note_footer();?></p>
		</div>
		<?php
	}

	/**
	 * Generates the note footer containing links for various actions
	 *
	 * @param bool $echo
	 *
	 * @return string
	 */
	private function note_footer( $echo = true ){
		$template = '<a class="" href="%1$s" title="%2$s">%2$s</a>';
		$links = array(
			sprintf( $template, $this->url, __( "Sure, I'd love to!", 'codeflavors-vimeo-video-post-lite' ) ),
			sprintf( $template, esc_url( add_query_arg( $this->user->get_query_arg( 'yes' ) ) ), __( 'No, thanks.', 'codeflavors-vimeo-video-post-lite' ) ),
			sprintf( $template, esc_url( add_query_arg( $this->user->get_query_arg( 'yes' ) ) ), __( "I've already given a review.", 'codeflavors-vimeo-video-post-lite' ) ),
			sprintf( $template, esc_url( add_query_arg( $this->user->get_query_arg( 'later' ) ) ), __( 'Ask me later.', 'codeflavors-vimeo-video-post-lite' ) )
		);

		$output = implode( " &middot; ", $links );

		if( $echo ){
			echo $output;
		}

		return $output;
	}
}