<?php
/* wppa-upldr-widget.php
* Package: wp-photo-album-plus
*
* display a list of users linking to their photos
* Version 8.6.04.005
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

class UpldrWidget extends WP_Widget {

    /** constructor */
    function __construct() {
		$widget_ops = array( 'classname' => 'wppa_upldr_widget', 'description' => __( 'Display which users uploaded how many photos', 'wp-photo-album-plus' ) );
		parent::__construct( 'wppa_upldr_widget', __( 'WPPA+ Uploader Photos', 'wp-photo-album-plus' ), $widget_ops );
    }

	/** @see WP_Widget::widget */
    function widget($args, $instance) {
		global $wpdb;

		// Initialize
		wppa_widget_timer( 'init' );
		wppa_reset_occurrance();
        wppa( 'in_widget', 'upldr' );
		wppa_bump_mocc( $this->id );
        extract( $args );
		$instance 		= wppa_parse_args( (array) $instance, $this->get_defaults() );
 		$widget_title 	= apply_filters( 'widget_title', $instance['title'] );

		// Logged in only and logged out?
		if ( wppa_checked( $instance['logonly'] ) && ! is_user_logged_in() ) {
			return;
		}

		$page 				= in_array( 'album', wppa( 'links_no_page' ) ) ? '' : wppa_get_the_landing_page('upldr_widget_linkpage', __('User uploaded photos', 'wp-photo-album-plus' ));
		$ignorelist			= explode(',', $instance['ignore']);
		$upldrcache 		= wppa_get_option( 'wppa_upldr_cache', array() );
		$needupdate 		= false;
		$users 				= wppa_get_users();
		$workarr 			= array();
		$showownercount 	= wppa_checked( $instance['showownercount'] );
		$showphotocount 	= wppa_checked( $instance['showphotocount'] );
		$total_ownercount 	= 0;
		$total_photocount 	= 0;
		$selalbs 			= str_replace( '.', ',', wppa_expand_enum( wppa_alb_to_enum_children( wppa_expand_enum( $instance['parent'] ) ) ) );

		// Make the data we need
		if ( $users ) foreach ( $users as $user ) {
			if ( ! in_array($user['user_login'], $ignorelist) ) {
				$me = wppa_get_user();
				if ( $user['user_login'] != $me && isset ( $upldrcache[$this->get_widget_id()][$user['user_login']]['c'] ) ) {
					$photo_count = $upldrcache[$this->get_widget_id()][$user['user_login']]['c'];
				}
				else {
					if ( $instance['parent'] ) {
						$query = $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos
												  WHERE owner = %s
												  AND album IN (".$selalbs.")
												  AND ( ( status <> 'pending' AND status <> 'scheduled' )
												  OR owner = %s )", $user['user_login'], $me );
					}
					else {
						$query = $wpdb->prepare( "SELECT COUNT(*)
												  FROM $wpdb->wppa_photos
												  WHERE owner = %s
												  AND ( ( status <> 'pending' AND status <> 'scheduled' )
												  OR owner = %s )", $user['user_login'], $me );
					}
					$photo_count = $wpdb->get_var( $query );
					if ( $user['user_login'] != $me ) {
						$upldrcache[$this->get_widget_id()][$user['user_login']]['c'] = $photo_count;
						$needupdate = true;
					}
				}
				if ( $photo_count ) {
					if ( $user['user_login'] != $me && isset ( $upldrcache[$this->get_widget_id()][$user['user_login']]['d'] ) ) $last_dtm = $upldrcache[$this->get_widget_id()][$user['user_login']]['d'];
					else {
						if ( $instance['parent'] ) {
							$last_dtm = $wpdb->get_var($wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_photos
																		WHERE owner = %s AND album IN (".$selalbs.")
																		AND ( ( status <> 'pending' AND status <> 'scheduled' )
																		OR owner = %s )
																		ORDER BY timestamp DESC
																		LIMIT 1", $user['user_login'], $me ));
						}
						else {
							$last_dtm = $wpdb->get_var($wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_photos
																		WHERE owner = %s
																		AND ( ( status <> 'pending' AND status <> 'scheduled' )
																		OR owner = %s )
																		ORDER BY timestamp DESC
																		LIMIT 1", $user['user_login'], $me ));
						}
					}
					if ( $user['user_login'] != $me ) {
						$upldrcache[$this->get_widget_id()][$user['user_login']]['d'] = $last_dtm;
						$needupdate = true;
					}

					$workarr[] = array('login' => $user['user_login'], 'name' => $user['display_name'], 'count' => $photo_count, 'date' => $last_dtm);

					$total_photocount += $photo_count;
					$total_ownercount++;
				}
			}
		}
		else {
			$widget_content =
				__( 'There are too many registered users in the system for this widget' , 'wp-photo-album-plus' );
			echo "\n" . $before_widget;
			if ( !empty( $widget_title ) ) { echo $before_title . $widget_title . $after_title; }
			echo $widget_content . $after_widget;
			return;
		}

		if ( $needupdate ) wppa_update_option('wppa_upldr_cache', $upldrcache);

		// Bring me to top
		$myline = false;
		if ( is_user_logged_in() ) {
			$me = wppa_get_user();
			foreach ( array_keys($workarr) as $key ) {
				$user = $workarr[$key];
				if ( $user['login'] == $me ) {
					$myline = $workarr[$key];
					unset ( $workarr[$key] );
				}
			}
		}

		// Sort workarray
		$ord = $instance['sortby'] == 'name' ? SORT_ASC : SORT_DESC;
		$workarr = wppa_array_sort($workarr, $instance['sortby'], $ord);

		// Create widget content
		$widget_content = "\n".'<!-- WPPA+ Upldr Widget start -->';
		$widget_content .= '<div class="wppa-upldr" data-wppa="yes">';
		if ( $showownercount ) {
			$widget_content .= sprintf( __( 'Number of contributors: %d', 'wp-photo-album-plus' ), $total_ownercount ) . '<br>';
		}
		if ( $showphotocount ) {
			$widget_content .= sprintf( __( 'Number of photos: %d', 'wp-photo-album-plus' ), $total_photocount ) . '<br><br>';
		}
		$widget_content .= '<table><tbody>';
		$albs = $instance['parent'] ? wppa_expand_enum( wppa_alb_to_enum_children( wppa_expand_enum( $instance['parent'] ) ) ) : '';
		if ( ! $albs ) $albs = '0';
		$a = wppa_trim_wppa_( '&amp;wppa-album='.$albs );
		$width = round( wppa_opt( 'widget_width' ) / 1.8 ) . 'px;';

		if ( $myline ) {
			$user = $myline;
			$widget_content .= '<tr class="wppa-user" >
									<td style="padding: 0 3px;max-width:' . $width . 'overflow:hidden"><a href="'.wppa_encrypt_url(wppa_get_upldr_link($user['login']).$a).'" title="'.__('Photos uploaded by', 'wp-photo-album-plus' ).' '.$user['name'].'" ><b>'.$user['name'].'</b></a></td>
									<td style="padding: 0 3px"><b>'.$user['count'].'</b></td>
									<td style="padding: 0 3px"><b>'.wppa_get_time_since($user['date']).'</b></td>
								</tr>';
		}
		foreach ( $workarr as $user ) {
			$widget_content .= '<tr class="wppa-user" >
									<td style="padding: 0 3px;max-width:' . $width . 'overflow:hidden"><a href="'.wppa_encrypt_url(wppa_get_upldr_link($user['login']).$a).'" title="'.__('Photos uploaded by', 'wp-photo-album-plus' ).' '.$user['name'].'" >'.$user['name'].'</a></td>
									<td style="padding: 0 3px">'.$user['count'].'</td>
									<td style="padding: 0 3px">'.wppa_get_time_since($user['date']).'</td>
								</tr>';
		}
		$widget_content .= '</tbody></table></div>';
		$widget_content .= '<div style="clear:both"></div>';

		// Output
		$result = "\n" . $before_widget;
		if ( ! empty( $widget_title ) ) {
			$result .= $before_title . $widget_title . $after_title;
		}
		$result .= $widget_content . $after_widget;

		wppa_echo( $result );
		echo wppa_widget_timer( 'show', $widget_title );

		wppa( 'in_widget', false );

	}

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {

		// Completize all parms
		$instance = wppa_parse_args( $new_instance, $this->get_defaults() );

		// Sanitize certain args
		$instance['title'] 		= strip_tags( $instance['title'] );

		wppa_remove_widget_cache( $this->id );

		wppa_flush_upldr_cache( 'widgetid', $this->get_widget_id() );

        return $instance;
    }

    /** @see WP_Widget::form */
    function form( $instance ) {
		global $wpdb;

		// Defaults
		$instance = wppa_parse_args( (array) $instance, $this->get_defaults() );

		// Title
		wppa_widget_input( $this, 'title', $instance['title'], __( 'Title', 'wp-photo-album-plus' ) );

		// Sortby
		$options = array(	__( 'Display name', 'wp-photo-album-plus' ),
							__( 'Number of photos', 'wp-photo-album-plus' ),
							__( 'Most recent photo', 'wp-photo-album-plus' ),
							);
		$values  = array( 	'name',
							'count',
							'date',
							);

		wppa_widget_selection( $this, 'sortby', $instance['sortby'], __( 'Sort by', 'wp-photo-album-plus' ), $options, $values );

		// Ignore these users
		wppa_widget_input( 	$this,
							'ignore',
							$instance['ignore'],
							__( 'Ignore', 'wp-photo-album-plus' ),
							__( 'Enter loginnames seperated by commas', 'wp-photo-album-plus' )
							);
?>


		<p><label for="<?php echo $this->get_field_id('parent'); ?>"><?php _e('Look only in albums (including sub albums):', 'wp-photo-album-plus' ); ?></label>
			<input type="hidden" id="<?php echo $this->get_field_id('parent'); ?>" name="<?php echo $this->get_field_name('parent'); ?>" value="<?php echo $instance['parent'] ?>" />
			<?php if ( $instance['parent'] ) echo '<br/><small>( '.$instance['parent'].' )</small>' ?>
			<select class="widefat" multiple onchange="wppaGetSelEnumToId( 'parentalbums-<?php echo $this->get_widget_id() ?>', '<?php echo $this->get_field_id('parent') ?>' )" id="<?php echo $this->get_field_id('parent-list'); ?>" name="<?php echo $this->get_field_name('parent-list'); ?>" >
			<?php
				// Prepare albuminfo
				if ( wppa_has_many_albums() ) {
					$albums = array();
				}
				else {
					$albums = $wpdb->get_results( "SELECT id, name FROM $wpdb->wppa_albums", ARRAY_A );
				}
				if ( ! empty( $albums ) ) {
					$albums = wppa_add_paths( $albums );
					$albums = wppa_array_sort( $albums, 'name' );
				}

				// Please select
				$sel = $instance['parent'] ? '' : 'selected ';
				echo '<option class="parentalbums-'.$this->get_widget_id().'" value="" '.$sel.'>-- '.__('All albums', 'wp-photo-album-plus' ).' --</option>';

				// Find the albums currently selected
				$selalbs = explode( '.', wppa_expand_enum( $instance['parent'] ) );

				// All standard albums
				foreach ( $albums as $album ) {
					$s = in_array( $album['id'], $selalbs );
					$sel = $s ? 'selected ' : '';
					echo '<option class="parentalbums-'.$this->get_widget_id().'" value="' . esc_attr( $album['id'] ) . '" '.$sel.'>'.stripslashes( __( $album['name'] , 'wp-photo-album-plus' ) ) . ' (' . $album['id'] . ')</option>';
				}
			?>
			</select>
		</p>
<?php
		// Ownercount
		wppa_widget_checkbox( $this, 'showownercount', $instance['showownercount'], __( 'Show count of owners', 'wp-photo-album-plus' ) );

		// Photocount
		wppa_widget_checkbox( $this, 'showphotocount', $instance['showphotocount'], __( 'Show count of photos', 'wp-photo-album-plus' ) );

		// Loggedin only
		wppa_widget_checkbox( $this, 'logonly', $instance['logonly'], __( 'Show to logged in visitors only', 'wp-photo-album-plus' ) );
	}

	function get_widget_id() {
		$widgetid = substr( $this->get_field_name( 'txt' ), strpos( $this->get_field_name( 'txt' ), '[' ) + 1 );
		$widgetid = substr( $widgetid, 0, strpos( $widgetid, ']' ) );
		return $widgetid;
	}

	// Set defaults
	function get_defaults() {

		$defaults = array( 	'title' 			=> __( 'Uploader Photos', 'wp-photo-album-plus' ),
							'sortby' 			=> 'name',
							'ignore' 			=> 'admin',
							'parent' 			=> '',
							'showownercount' 	=> '',
							'showphotocount' 	=> '',
							'logonly' 			=> 'no',
							);
		return $defaults;
	}

} // class UpldrWidget

// register UpldrWidget widget
add_action('widgets_init', 'wppa_register_UpldrWidget' );

function wppa_register_UpldrWidget() {
	register_widget("UpldrWidget");
}
