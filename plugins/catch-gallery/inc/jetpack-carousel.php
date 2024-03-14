<?php
if ( ! class_exists( 'Catch_Gallery_Carousel' ) ) :
class Catch_Gallery_Carousel {

	var $prebuilt_widths = array( 370, 700, 1000, 1200, 1400, 2000 );

	var $first_run = true;

	var $in_jetpack = true;

	function __construct() {
		add_action( 'init', array( $this, 'init' ) );

	}

	function init() {
		if ( $this->maybe_disable_jp_carousel() )
			return;

		$this->in_jetpack = ( class_exists( 'Jetpack' ) && method_exists( 'Jetpack', 'enable_module_configurable' ) ) ? true : false;

		$options = catch_gallery_get_options();

		if ( is_admin() ) {
			// Register the Carousel-related related settings

			if ( ! $this->in_jetpack ) {
				$carousel_disabled = isset( $options['carousel_enable'] ) ? $options['carousel_enable']  : false;
				if ( ! $carousel_disabled ) {
					return; // Carousel disabled, abort early, but still register setting so user can switch it back on
				}
			}
			// If in admin, register the ajax endpoints.
			add_action( 'wp_ajax_get_attachment_comments', array( $this, 'get_attachment_comments' ) );
			add_action( 'wp_ajax_nopriv_get_attachment_comments', array( $this, 'get_attachment_comments' ) );
			add_action( 'wp_ajax_post_attachment_comment', array( $this, 'post_attachment_comment' ) );
			add_action( 'wp_ajax_nopriv_post_attachment_comment', array( $this, 'post_attachment_comment' ) );
		} else {
			$carousel_disabled = isset( $options['carousel_enable'] ) ? $options['carousel_enable']  : false;
			if ( ! $carousel_disabled ) {
				return; // Carousel disabled, abort early, but still register setting so user can switch it back on
			}
			// If on front-end, do the Carousel thang.
			$this->prebuilt_widths = apply_filters( 'jp_carousel_widths', $this->prebuilt_widths );
			add_filter( 'post_gallery', array( $this, 'enqueue_assets' ), 1000, 2 ); // load later than other callbacks hooked it
			add_filter( 'gallery_style', array( $this, 'add_data_to_container' ) );
			add_filter( 'wp_get_attachment_link', array( $this, 'add_data_to_images' ), 10, 2 );
		}

		if ( $this->in_jetpack && method_exists( 'Jetpack', 'module_configuration_load' ) ) {
			Jetpack::enable_module_configurable( dirname( dirname( __FILE__ ) ) . '/carousel.php' );
			Jetpack::module_configuration_load( dirname( dirname( __FILE__ ) ) . '/carousel.php', array( $this, 'jetpack_configuration_load' ) );
		}
	}

	function maybe_disable_jp_carousel() {
		return apply_filters( 'jp_carousel_maybe_disable', false );
	}

	function jetpack_configuration_load() {
		wp_safe_redirect( admin_url( 'options-media.php#catch_gallery_options[carousel_background_color]' ) );
		exit;
	}

	function asset_version( $version ) {
		return apply_filters( 'jp_carousel_asset_version', $version );
	}

	function enqueue_assets( $output ) {
		if ( ! empty( $output ) && ! apply_filters( 'jp_carousel_force_enable', false ) ) {
			// Bail because someone is overriding the [gallery] shortcode.
			remove_filter( 'gallery_style', array( $this, 'add_data_to_container' ) );
			remove_filter( 'wp_get_attachment_link', array( $this, 'add_data_to_images' ) );
			return $output;
		}

		do_action( 'jp_carousel_thumbnails_shown' );

		if ( $this->first_run ) {
			wp_register_script( 'spin', plugin_dir_url( __FILE__ ) . '../js/spin.js', false, '1.3' );
			wp_register_script( 'jquery-spin', plugin_dir_url( __FILE__ ) . '../js/jquery.spin.js', array( 'jquery', 'spin' ) );

			wp_enqueue_script( 'jetpack-carousel', plugin_dir_url( __FILE__ ) . '../js/jetpack-carousel.js', array( 'jquery-spin' ), $this->asset_version( CATCH_GALLERY_VERSION ), true );

			// Note: using  home_url() instead of admin_url() for ajaxurl to be sure  to get same domain on wpcom when using mapped domains (also works on self-hosted)
			// Also: not hardcoding path since there is no guarantee site is running on site root in self-hosted context.
			$is_logged_in = is_user_logged_in();
			$current_user = wp_get_current_user();
			$comment_registration = intval( get_option( 'comment_registration' ) );
			$require_name_email   = intval( get_option( 'require_name_email' ) );

			$options = catch_gallery_get_options();
			$localize_strings = array(
				'widths'               => $this->prebuilt_widths,
				'is_logged_in'         => $is_logged_in,
				'lang'                 => strtolower( substr( get_locale(), 0, 2 ) ),
				'ajaxurl'              => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
				'nonce'                => wp_create_nonce( 'carousel_nonce' ),
				'display_exif'         => isset( $options['carousel_display_exif'] ) ? $options['carousel_display_exif'] : false,
				'display_geo'          => isset( $options['carousel_display_geo'] ) ? $options['carousel_display_geo'] : false,
				'display_comments'     => isset( $options['comments_display'] ) ? $options['comments_display']: false,
				'fullsize_display'     => isset( $options['fullsize_display'] ) ? $options['fullsize_display'] : false,

				'background_color'     => isset( $options['carousel_background_color'] ) ? $options['carousel_background_color'] : 'black',
				'comment'              => esc_html__( 'Comment', 'catch-gallery' ),
				'post_comment'         => esc_html__( 'Post Comment', 'catch-gallery' ),
				'loading_comments'     => esc_html__( 'Loading Comments...', 'catch-gallery' ),

				'download_original'    => sprintf( __( 'View full size <span class="photo-size">%1$s<span class="photo-size-times">&times;</span>%2$s</span>', 'catch-gallery' ), '{0}', '{1}' ),

				'no_comment_text'      => esc_html__( 'Please be sure to submit some text with your comment.', 'catch-gallery' ),
				'no_comment_email'     => esc_html__( 'Please provide an email address to comment.', 'catch-gallery' ),
				'no_comment_author'    => esc_html__( 'Please provide your name to comment.', 'catch-gallery' ),
				'comment_post_error'   => esc_html__( 'Sorry, but there was an error posting your comment. Please try again later.', 'catch-gallery' ),
				'comment_approved'     => esc_html__( 'Your comment was approved.', 'catch-gallery' ),
				'comment_unapproved'   => esc_html__( 'Your comment is in moderation.', 'catch-gallery' ),
				'camera'               => esc_html__( 'Camera', 'catch-gallery' ),
				'aperture'             => esc_html__( 'Aperture', 'catch-gallery' ),
				'shutter_speed'        => esc_html__( 'Shutter Speed', 'catch-gallery' ),
				'focal_length'         => esc_html__( 'Focal Length', 'catch-gallery' ),
				'comment_registration' => $comment_registration,
				'require_name_email'   => $require_name_email,
				'login_url'            => wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ),
			);

			if ( ! isset( $localize_strings['jetpack_comments_iframe_src'] ) || empty( $localize_strings['jetpack_comments_iframe_src'] ) ) {
				// We're not using Jetpack comments after all, so fallback to standard local comments.
				if ( isset( $localize_strings['display_comments'] )){
					if ( $localize_strings['display_comments']) {


						if ( $is_logged_in ) {
							$localize_strings['local_comments_commenting_as'] = '<p id="jp-carousel-commenting-as">' . sprintf( __( 'Commenting as %s', 'catch-gallery' ), $current_user->data->display_name ) . '</p>';
						} else {
							if ( $comment_registration ) {
								$localize_strings['local_comments_commenting_as'] = '<p id="jp-carousel-commenting-as">' . __( 'You must be <a href="#" class="jp-carousel-comment-login">logged in</a> to post a comment.', 'catch-gallery' ) . '</p>';
							} else {
								$required = ( $require_name_email ) ? __( '%s (Required)', 'catch-gallery' ) : '%s';
								$localize_strings['local_comments_commenting_as'] = ''
								. '<fieldset><label for="email">' . sprintf( $required, esc_html__( 'Email', 'catch-gallery' ) ) . '</label> '
								. '<input type="text" name="email" class="jp-carousel-comment-form-field jp-carousel-comment-form-text-field" id="jp-carousel-comment-form-email-field" /></fieldset>'
								. '<fieldset><label for="author">' . sprintf( $required, esc_html__( 'Name', 'catch-gallery' ) ) . '</label> '
								. '<input type="text" name="author" class="jp-carousel-comment-form-field jp-carousel-comment-form-text-field" id="jp-carousel-comment-form-author-field" /></fieldset>'
								. '<fieldset><label for="url">' . esc_html__( 'Website', 'catch-gallery' ) . '</label> '
								. '<input type="text" name="url" class="jp-carousel-comment-form-field jp-carousel-comment-form-text-field" id="jp-carousel-comment-form-url-field" /></fieldset>';
							}
						}
					}else{
						$localize_strings['loading_comments'] = '';
						$localize_strings['comment'] = '';
					}
				}
			}

			$localize_strings = apply_filters( 'jp_carousel_localize_strings', $localize_strings );

			wp_localize_script( 'jetpack-carousel', 'jetpackCarouselStrings', $localize_strings );

			wp_enqueue_style( 'jetpack-carousel', plugin_dir_url( __FILE__ ) . '../css/jetpack-carousel.css', array(), $this->asset_version( CATCH_GALLERY_VERSION ) );
			wp_style_add_data( 'jetpack-carousel', 'rtl', 'replace' );

			wp_enqueue_style( 'jetpack-carousel-ie8fix', plugin_dir_url( __FILE__ ) . '../css/jetpack-carousel-ie8fix.css', '', $this->asset_version( CATCH_GALLERY_VERSION ) );
			wp_style_add_data( 'jetpack-carousel-ie8fix', 'conditional', 'lt IE 9' );

			do_action( 'jp_carousel_enqueue_assets', $this->first_run, $localize_strings );

			$this->first_run = false;
		}

		return $output;
	}

	function add_data_to_images( $html, $attachment_id ) {
		if ( $this->first_run ) // not in a gallery
		return $html;

		$attachment_id   = intval( $attachment_id );
		$orig_file       = wp_get_attachment_image_src( $attachment_id, 'full' );
		$orig_file       = isset( $orig_file[0] ) ? $orig_file[0] : wp_get_attachment_url( $attachment_id );
		$meta            = wp_get_attachment_metadata( $attachment_id );
		$size            = isset( $meta['width'] ) ? intval( $meta['width'] ) . ',' . intval( $meta['height'] ) : '';
		$img_meta        = ( ! empty( $meta['image_meta'] ) ) ? (array) $meta['image_meta'] : array();
		$comments_opened = intval( comments_open( $attachment_id ) );

		/*
		 * Note: Cannot generate a filename from the width and height wp_get_attachment_image_src() returns because
		 * it takes the $content_width global variable themes can set in consideration, therefore returning sizes
		 * which when used to generate a filename will likely result in a 404 on the image.
		 * $content_width has no filter we could temporarily de-register, run wp_get_attachment_image_src(), then
		 * re-register. So using returned file URL instead, which we can define the sizes from through filename
		 * parsing in the JS, as this is a failsafe file reference.
		 *
		 * EG with Twenty Eleven activated:
		 * array(4) { [0]=> string(82) "http://vanillawpinstall.blah/wp-content/uploads/2012/06/IMG_3534-1024x764.jpg" [1]=> int(584) [2]=> int(435) [3]=> bool(true) }
		 *
		 * EG with Twenty Ten activated:
		 * array(4) { [0]=> string(82) "http://vanillawpinstall.blah/wp-content/uploads/2012/06/IMG_3534-1024x764.jpg" [1]=> int(640) [2]=> int(477) [3]=> bool(true) }
		 */

		$medium_file_info = wp_get_attachment_image_src( $attachment_id, 'medium' );
		$medium_file      = isset( $medium_file_info[0] ) ? $medium_file_info[0] : '';

		$large_file_info  = wp_get_attachment_image_src( $attachment_id, 'large' );
		$large_file       = isset( $large_file_info[0] ) ? $large_file_info[0] : '';

		$attachment       = get_post( $attachment_id );
		$attachment_title = wptexturize( $attachment->post_title );
		$attachment_desc  = wpautop( wptexturize( $attachment->post_content ) );

		// Not yet providing geo-data, need to "fuzzify" for privacy
		if ( ! empty( $img_meta ) ) {
			foreach ( $img_meta as $k => $v ) {
				if ( 'latitude' == $k || 'longitude' == $k )
					unset( $img_meta[$k] );
			}
		}

		// See https://github.com/Automattic/jetpack/issues/2765
		if ( isset( $img_meta['keywords'] ) ) {
			unset( $img_meta['keywords'] );
		}

		$img_meta = json_encode( array_map( 'strval', array_filter( $img_meta, 'is_scalar' ) ) );

		$html = str_replace(
			'<img ',
			sprintf(
				'<img data-attachment-id="%1$d" data-orig-file="%2$s" data-orig-size="%3$s" data-comments-opened="%4$s" data-image-meta="%5$s" data-image-title="%6$s" data-image-description="%7$s" data-medium-file="%8$s" data-large-file="%9$s" title="%10$s" ',
				$attachment_id,
				esc_attr( $orig_file ),
				$size,
				$comments_opened,
				esc_attr( $img_meta ),
				esc_attr( $attachment_title ),
				esc_attr( $attachment_desc ),
				esc_attr( $medium_file ),
				esc_attr( $large_file ),
				esc_attr( $attachment_title )
			),
			$html
		);

		$html = apply_filters( 'jp_carousel_add_data_to_images', $html, $attachment_id );

		return $html;
	}

	function add_data_to_container( $html ) {
		global $post;

		if ( isset( $post ) ) {
			$blog_id = (int) get_current_blog_id();

			if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
				$likes_blog_id = $blog_id;
			} else {
				//$likes_blog_id = Jetpack_Options::get_option( 'id' );
			}

			$extra_data = array(
				'data-carousel-extra' => array(
					'blog_id' => $blog_id,
					'permalink' => get_permalink( $post->ID ),
					//'likes_blog_id' => $likes_blog_id
				)
			);

			$extra_data = apply_filters( 'jp_carousel_add_data_to_container', $extra_data );
			foreach ( (array) $extra_data as $data_key => $data_values ) {
				$html = str_replace( '<div ', '<div ' . esc_attr( $data_key ) . "='" . json_encode( $data_values ) . "' ", $html );
			}
		}

		return $html;
	}

	function get_attachment_comments() {
		if ( ! headers_sent() )
			header( 'Content-type: text/javascript' );

		do_action( 'jp_carousel_check_blog_user_privileges' );

		$attachment_id = ( isset( $_REQUEST['id'] ) ) ? (int) $_REQUEST['id'] : 0;
		$offset        = ( isset( $_REQUEST['offset'] ) ) ? (int) $_REQUEST['offset'] : 0;

		if ( ! $attachment_id ) {
			echo json_encode( esc_html__( 'Missing attachment ID.', 'catch-gallery' ) );
			die();
		}

		if ( $offset < 1 )
			$offset = 0;

		$comments = get_comments( array(
			'status'  => 'approve',
			'order'   => ( 'asc' == get_option( 'comment_order' ) ) ? 'ASC' : 'DESC',
			'number'  => 10,
			'offset'  => $offset,
			'post_id' => $attachment_id,
		) );

		$out      = array();

		// Can't just send the results, they contain the commenter's email address.
		foreach ( $comments as $comment ) {
			$out[] = array(
				'id'              => $comment->comment_ID,
				'parent_id'       => $comment->comment_parent,
				'author_markup'   => get_comment_author_link( $comment->comment_ID ),
				'gravatar_markup' => get_avatar( $comment->comment_author_email, 64 ),
				'date_gmt'        => $comment->comment_date_gmt,
				'content'         => wpautop($comment->comment_content),
			);
		}

		die( json_encode( $out ) );
	}

	function post_attachment_comment() {
		if ( ! headers_sent() )
			header( 'Content-type: text/javascript' );

		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce($_POST['nonce'], 'carousel_nonce' ) )
			die( json_encode( array( 'error' => esc_html__( 'Nonce verification failed.', 'catch-gallery' ) ) ) );

		$_blog_id = (int) $_POST['blog_id'];
		$_post_id = (int) $_POST['id'];
		$comment = $_POST['comment'];

		if ( empty( $_blog_id ) )
			die( json_encode( array( 'error' => esc_html__( 'Missing target blog ID.', 'catch-gallery' ) ) ) );

		if ( empty( $_post_id ) )
			die( json_encode( array( 'error' => esc_html__( 'Missing target post ID.', 'catch-gallery' ) ) ) );

		if ( empty( $comment ) )
			die( json_encode( array( 'error' => esc_html__( 'No comment text was submitted.', 'catch-gallery' ) ) ) );

		// Used in context like NewDash
		$switched = false;
		if ( is_multisite() && $_blog_id != get_current_blog_id() ) {
			switch_to_blog( $_blog_id );
			$switched = true;
		}

		do_action( 'jp_carousel_check_blog_user_privileges' );

		if ( ! comments_open( $_post_id ) )
			die( json_encode( array( 'error' => esc_html__( 'Comments on this post are closed.', 'catch-gallery' ) ) ) );

		if ( is_user_logged_in() ) {
			$user         = wp_get_current_user();
			$user_id      = $user->ID;
			$display_name = $user->display_name;
			$email        = $user->user_email;
			$url          = $user->user_url;

			if ( empty( $user_id ) )
				die( json_encode( array( 'error' => esc_html__( 'Sorry, but we could not authenticate your request.', 'catch-gallery' ) ) ) );
		} else {
			$user_id      = 0;
			$display_name = $_POST['author'];
			$email        = $_POST['email'];
			$url          = $_POST['url'];

			if ( get_option( 'require_name_email' ) ) {
				if ( empty( $display_name ) )
					die( json_encode( array( 'error' => esc_html__( 'Please provide your name.', 'catch-gallery' ) ) ) );

				if ( empty( $email ) )
					die( json_encode( array( 'error' => esc_html__( 'Please provide an email address.', 'catch-gallery' ) ) ) );

				if ( ! is_email( $email ) )
					die( json_encode( array( 'error' => esc_html__( 'Please provide a valid email address.', 'catch-gallery' ) ) ) );
			}
		}

		$comment_data =  array(
			'comment_content'      => $comment,
			'comment_post_ID'      => $_post_id,
			'comment_author'       => $display_name,
			'comment_author_email' => $email,
			'comment_author_url'   => $url,
			'comment_approved'     => 0,
			'comment_type'         => '',
		);

		if ( ! empty( $user_id ) )
			$comment_data['user_id'] = $user_id;

		// Note: wp_new_comment() sanitizes and validates the values (too).
		$comment_id = wp_new_comment( $comment_data );
		do_action( 'jp_carousel_post_attachment_comment' );
		$comment_status = wp_get_comment_status( $comment_id );

		if ( true == $switched )
			restore_current_blog();

		die( json_encode( array( 'comment_id' => $comment_id, 'comment_status' => $comment_status ) ) );
	}
}
endif;
new Catch_Gallery_Carousel;
