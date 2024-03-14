<?php
/*
Plugin Name: Like me if you like this article
Description: This will recommend to like any Facebook page on the bottom of every single article.
Author: Mayo Moriyama
Version: 0.6
Author URI: http://blog.mayuko.me
Plugin URI: https://github.com/mayukojpn/like-me-if-you-like-this-article
Text Domain: like-me-if-you-like-this-article
Domain Path: /languages
*/

define( 'MAMAHACK_DIR', dirname( __FILE__ ) );

$mamahack = new FB_if_you_like();
$mamahack->register();

class FB_if_you_like {

	public function register()
	{
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}
	public function plugins_loaded()
	{
		load_plugin_textdomain( 'like-me-if-you-like-this-article', false, plugin_basename( MAMAHACK_DIR ) . '/languages' );

		add_filter( 'the_content', array( $this, 'the_content' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ), 21 );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
	public function wp_enqueue_scripts()
	{
		/*
		* If you want to style only your theme. You can stop style of this plugin.
		*
		* add_filter( 'mamahack_style', "__return_false" );
		*/
		$style = apply_filters( 'mamahack_style', plugins_url( 'css/mamahack.css', __FILE__ ) );
		if ( $style ) {
			wp_enqueue_style(
				'mamahack_style',
				$style
			);
		}

	}
	public function the_content( $contents )
	{
		if ( ! is_singular() ) {
			return $contents;
		}
		$like = '<p>&nbsp;</p>';

		if ( get_option( 'mamahack_fb_account' ) )
		{
			$like .= '<div class="mamahack-fb">';
			$like .= '<div class="mamahack-fb__boxThumb" style="background-image: url(';
			if ( has_post_thumbnail( get_the_ID() ) )
			{
				$like .= wp_get_attachment_image_url( get_post_thumbnail_id( get_the_ID() ), 'medium' );
			}
			elseif ( has_site_icon() )
			{
				$like .= get_site_icon_url();
			}
			$like .= ')"></div>';
			$like .= '<div class="mamahack-fb__boxLike">';
			$like .= '<p class="mamahack-fb__boxLike__message">'.__( 'Did you enjoy the blog?<br>Like me!', 'like-me-if-you-like-this-article' ).'</p>';
			$like .= '<div class="mamahack-fb__boxLike__button">';
			$like .= '<iframe src="https://www.facebook.com/plugins/like.php?href=https://www.facebook.com/';
			$like .=  esc_html( get_option('mamahack_fb_account') );
			$like .= '&send=false&layout=button_count&width=100&show_faces=false&action=like&colorscheme=light&font=arial&height=20" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:105px; height:21px;" allowTransparency="true"></iframe>';
			$like .= '</div>';
			$like .= '<p class="mamahack-fb__boxLike__note">'.__( 'Get the latest.', 'like-me-if-you-like-this-article' ).'</p>';
			$like .= '</div>';
			$like .= '</div>';
		}
		if( get_option( 'mamahack_tw_account' ) )
		{
			$like .= '<div class="mamahack-tw">';
			$like .= '<p class="mamahack-tw__item">';

			if( $mamahack_tw_message = esc_html( get_option( 'mamahack_tw_message ') ) )
			{
				$like .= sprintf( __( 'Follow %s on Twitter!', 'like-me-if-you-like-this-article' ), $mamahack_tw_message );
			}
			else
			{
				$like .= __( 'Follow me on Twitter!', 'like-me-if-you-like-this-article' );
			}
			$like .= '</p>';
			$like .= '<a href="https://twitter.com/'.esc_html( get_option('mamahack_tw_account') ).'" class="twitter-follow-button mamahack-tw__item" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @'.esc_html( get_option('mamahack_tw_account') ).'</a>';
			$like .= '</div>';
		}

		return apply_filters( 'mamahack_the_content', $contents . $like, $like, $contents );
	}
	public function wp_footer()
	{
		echo '<div id="fb-root"></div>';
		echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
	}

	public function mamahack_section_message() {

	}

	public function mamahack_fb_account() { ?>
	 	https://www.facebook.com/<input name="mamahack_fb_account" id="mamahack_fb_account" type="text" size="30" value="<?php
	 		echo esc_html( get_option('mamahack_fb_account') ); ?>">/
		<?php
	}

	public function mamahack_tw_message() { ?>
	 	<input name="mamahack_tw_message" id="mamahack_tw_message" type="text" size="30" value="<?php
	 		echo esc_html( get_option('mamahack_tw_message') ); ?>">
		<?php
	}

	public function mamahack_tw_account() { ?>
	 	@<input name="mamahack_tw_account" id="mamahack_tw_account" type="text" size="30" value="<?php
	 		echo esc_html( get_option('mamahack_tw_account') ); ?>">
		<?php
	}


	public function admin_init() {
		add_settings_section(
			'like-me-if-you-like-this-article',
			__( 'Like me if you like this article Settings', 'like-me-if-you-like-this-article' ),
			array( $this, 'mamahack_section_message' ),
			'reading' );

	 	add_settings_field(
			'mamahack_fb_account',
			__( 'Facebook page id', 'like-me-if-you-like-this-article' ),
			array( $this, 'mamahack_fb_account' ),
			'reading',
			'like-me-if-you-like-this-article');

		add_settings_field(
			'mamahack_tw_message',
			__( 'Twitter display name', 'like-me-if-you-like-this-article' ),
			array( $this, 'mamahack_tw_message' ),
			'reading',
			'like-me-if-you-like-this-article');

		add_settings_field(
			'mamahack_tw_account',
			__( 'Twitter account' , 'like-me-if-you-like-this-article' ),
			array( $this, 'mamahack_tw_account' ),
			'reading',
			'like-me-if-you-like-this-article');


	 	register_setting('reading','mamahack_fb_account');
		register_setting('reading','mamahack_tw_message');
		register_setting('reading','mamahack_tw_account');
	 }

}
