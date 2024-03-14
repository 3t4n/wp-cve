<?php
/* wppa-searchwidget.php
* Package: wp-photo-album-plus
*
* display the search widget
* Version: 8.4.03.002
*
*/

class SearchPhotos extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 	'classname' => 'wppa_search_photos',
								'description' => __( 'Display search photos dialog', 'wp-photo-album-plus' )
							);
		parent::__construct( 'wppa_search_photos', __( 'WPPA+ Search Photos', 'wp-photo-album-plus' ), $widget_ops );															//
    }

	/** @see WP_Widget::widget */
    function widget( $args, $instance ) {
		global $widget_content;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'search' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		// Make the widget content
		$widget_content = '
		<span data-wppa="yes"></span>' .
		wppa_get_search_html( 	$instance['label'],
								wppa_checked( $instance['sub'] ),
								wppa_checked( $instance['root'] ),
								$instance['album'],
								$instance['landingpage'],
								wppa_checked( $instance['catbox'] ),
								wppa_checked( $instance['selboxes'] ) ? wppa_opt( 'search_selboxes' ) : false
								);

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		wppa_echo( $result );
		wppa_echo( wppa_widget_timer( 'show', $widget_title ) );

		wppa( 'in_widget', false );
    }

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 		= strip_tags( $instance['title'] );

		wppa_remove_widget_cache( $this->id );

        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {
		global $wpdb;

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Pre input text
		wppa_widget_input( 	$this,
							'label',
							$instance['label'],
							__( 'Text above input field', 'wp-photo-album-plus' ),
							__( 'Enter optional text that will appear before the input box. This may contain HTML so you can change font size and color.', 'wp-photo-album-plus' )
							);

		// Enable rootsearch
		wppa_widget_checkbox( 	$this,
								'root',
								$instance['root'],
								__( 'Enable rootsearch', 'wp-photo-album-plus' ),
								__( 'See Search -> I -> Item 16 to change the label text', 'wp-photo-album-plus' )
								);

		// Fixed root?
		$body = wppa_album_select_a( array( 	'selected' 			=> $instance['album'],
												'addblank' 			=> true,
												'sort'				=> true,
												'path' 				=> true,
												) );

		wppa_widget_selection_frame( 	$this,
										'album',
										$body,
										__( 'Album', 'wp-photo-album-plus' ),
										false,
										__( 'If you want the search to be limited to a specific album and its (sub-)sub albums, select the album here.', 'wp-photo-album-plus' ) .
											' ' .
											__( 'If you select an album here, it will overrule the previous checkbox using the album as a \'fixed\' root.', 'wp-photo-album-plus' )
										);

		// Subsearch?
		wppa_widget_checkbox( 	$this,
								'sub',
								$instance['sub'],
								__( 'Enable subsearch', 'wp-photo-album-plus' ),
								__( 'See Search -> I -> Item 17 to change the label text', 'wp-photo-album-plus' )
								);

		// Category selection
		wppa_widget_checkbox( 	$this,
								'catbox',
								$instance['catbox'],
								__( 'Add category selectionbox', 'wp-photo-album-plus' ),
								__( 'Enables the visitor to limit the results to an album category', 'wp-photo-album-plus' )
								);

		// Selection boxes
		wppa_widget_checkbox( 	$this,
								'selboxes',
								$instance['selboxes'],
								__( 'Add selectionboxes with pre-defined tokens', 'wp-photo-album-plus' ),
								__( 'See Search -> I -> Item 23 .. 29 for configuration', 'wp-photo-album-plus' )
								);

		// Landing page
		$options 	= array( __( '--- default ---', 'wp-photo-album-plus' ) );
		$values  	= array( '0' );
		$disabled 	= array( false );

		$query = 	"SELECT ID, post_title, post_content, post_parent " .
					"FROM " . $wpdb->posts . " " .
					"WHERE post_type = 'page' AND post_status = 'publish' " .
					"ORDER BY post_title ASC";
		$pages = 	$wpdb->get_results( $query, ARRAY_A );

		if ( $pages ) {

			// Translate qTranslate-x
			foreach ( array_keys( $pages ) as $index ) {
				$pages[$index]['post_title'] = __( stripslashes( $pages[$index]['post_title'] ) );
			}

			// Sort alpahbetically
			$pages = wppa_array_sort( $pages, 'post_title' );

			// Options / values
			foreach ( $pages as $page ) {

				$options[] 	= __( $page['post_title'] );
				$values[] 	= $page['ID'];
				$disabled[] = strpos( $page['post_content'], '[wppa' ) === false && strpos( $page['post_content'], '%%wppa%%' ) === false;

			}
		}

		wppa_widget_selection( 	$this,
								'landingpage',
								$instance['landingpage'],
								__( 'Landing page', 'wp-photo-album-plus' ),
								$options,
								$values,
								$disabled,
								'widefat',
								__( 'The default page will be created automatically', 'wp-photo-album-plus' )
								);

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );
 	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 		=> __( 'Search Photos', 'wp-photo-album-plus' ),
							'label' 		=> '',
							'root' 			=> false,
							'sub' 			=> false,
							'album' 		=> '0',
							'landingpage' 	=> '',
							'catbox' 		=> false,
							'selboxes' 		=> false,
							'logonly' 		=> 'no',
							);
		return $defaults;
	}

} // class SearchPhotos

// register SearchPhotos widget
add_action('widgets_init', 'wppa_register_SearchPhotos' );

function wppa_register_SearchPhotos() {
	register_widget( "SearchPhotos" );
}
