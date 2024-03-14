<?php

if ( class_exists( 'Youzify_User' ) ) {
	return;
}

class Youzify_User {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Cover.
	 */
	function cover( $user_id = null ) {

		// Get User ID.
		$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

	    // Get Cover Photo Path.
	    $cover_path = bp_attachments_get_attachment( 'url', array( 'object_dir' => 'members', 'item_id' => $user_id ) );

	    if ( empty( $cover_path ) ) {

	        // Get Default Cover.
	        $cover_path = youzify_option( 'youzify_default_profiles_cover' );

	        // If default cover not exist use pattern.
	        if ( empty( $cover_path ) ) {
	            return apply_filters( 'youzify_user_profile_cover', '<div class="youzify-cover-pattern" style="background-image: url(' . youzify_get_default_profile_cover() . ');width: 100%; height: 100%; position: absolute;"></div>' );
	        }

	    }

	    $cover_path = apply_filters( 'youzify_user_profile_cover_link', $cover_path, $user_id );

		return apply_filters( 'youzify_user_profile_cover', '<img class="youzify-user-profile-cover-img lazyload" loading="lazy" ' . youzify_get_image_attributes_by_link( $cover_path ) . ' alt="">', $user_id );

	}

	/**
	 * Photo
	 */
	function photo( $args = null ) {

		// Set Up Variable.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_displayed_user_id();
		$target  = isset( $args['target'] ) ? $args['target'] : 'header';

		// Get Photo Border Style
		$border_style = youzify_option( 'youzify_' . $target . '_photo_border_style', 'circle' );

		$img_path = bp_core_fetch_avatar(
			array(
				'item_id' => $user_id,
				'type'	  => 'full',
			)
		);

		// Set Default avatar if avatar url is empty
		$img_path = ! empty( $img_path ) ? $img_path : youzify_get_avatar_img_by_url( bp_core_avatar_default() );

		// Prepare Photo Class
		$photo_class = array( 'youzify-profile-photo', "youzify-photo-$border_style" );

		if ( 'on' == apply_filters( 'youzify_enable_' . $target . '_photo_border', 'on' ) ) {
			$photo_class[] = 'youzify-photo-border';
		}

		if ( 'circle' == $border_style && 'on' ==  youzify_option( 'youzify_profile_photo_effect', 'on' ) ) {
			$photo_class[] = 'youzify-profile-photo-effect';
		}

		$upload_img = bp_is_my_profile() ? '<div class="youzify-open-avatar-uploader youzify-open-bp-uploader" data-type="avatar"><div data-youzify-tooltip="' . __( 'Change Avatar', 'youzify' ) . '"><i class="fas fa-camera"></i></div></div>' : '';

		// Get Profile Url
		$profile_url = bp_members_get_user_url( $user_id );

		echo "<div class='" . youzify_generate_class( $photo_class ) . "'>";
		echo "<a href='" . apply_filters( 'youzify_user_profile_avatar_link', $profile_url, $user_id ) . "' class='youzify-profile-img'>" . $upload_img . apply_filters( 'youzify_user_profile_avatar_img', $img_path, $user_id ) . "</a>";
		echo "</div>";


	}

	/**
	 * Username.
	 */
	function name( $user_id = null ) {

		// Get User ID.
		$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		echo "<div class='youzify-name'><h2>". apply_filters( 'youzify_user_profile_username', bp_core_get_user_displayname( $user_id ) ) . "</h2></div>";

	}

	/**
	 * Meta.
	 */
	function meta( $user_id = null ) {

		// Init Vars.
		$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();
		$first_meta = youzify_option( 'youzify_hheader_meta_type_1', 'full_location' );
		$second_meta = youzify_option( 'youzify_hheader_meta_type_2', 'user_url' );

		$meta = '';

		if ( $first_meta ) {
			// Get First Meta Value.
			$first_value = youzify_get_user_field_data( $first_meta, $user_id );
			if ( ! empty( $first_value ) ) {
				$first_icon = youzify_option( 'youzify_hheader_meta_icon_1', 'fas fa-map-marker-alt' );
				$meta .= apply_filters( 'youzify_get_profile_header_meta_1', '<li><i class="' . $first_icon . '"></i><span>' . sanitize_text_field( $first_value ) . '</span></li>', $first_value, $first_icon );
			}
		}

		if ( $second_meta ) {
			// Get Second Meta Value.
			$second_value = youzify_get_user_field_data( $second_meta, $user_id );
			if ( ! empty( $second_value ) ) {
				$second_icon = youzify_option( 'youzify_hheader_meta_icon_2', 'fas fa-link' );
				$meta .= apply_filters( 'youzify_get_profile_header_meta_2', '<li><i class="' . $second_icon . '"></i><span>' . sanitize_text_field( $second_value ) . '</span></li>', $second_value, $second_icon );
			}
		}

		// Get User Types.
		$user_member_types = bp_get_member_type( $user_id, false );

		if ( $user_member_types && apply_filters( 'youzify_profile_header_show_member_type_meta', true ) ) {

			$types = bp_get_member_types( array( 'show_in_list' => true ),  'objects' );

			if ( ! empty( $types ) ) {
				foreach ( $types as $type ) {
					if ( $type->show_in_list && in_array( $type->name, $user_member_types ) ) {
						$meta .= '<li><i class="' . get_term_meta( $type->db_id, 'youzify_type_icon', true ) . '"></i><span>' . bp_get_member_type_directory_link( $type->name ). '</span></li>';
					}
				}

			}

		}

		echo '<div class="youzify-usermeta"><ul>' . $meta;
			do_action( 'youzify_get_profile_header_user_meta' );
		echo '</ul></div>';

		do_action( 'youzify_after_profile_header_user_meta' );

		do_action( 'bp_profile_header_meta' );

	}

	/**
	 * Location.
	 */
	function location( $only_data = false, $user_id = null ) {

		// Get user city & country.
		$user_city    = youzify_get_xprofile_field_value( 'user_city', $user_id );
		$user_country = youzify_get_xprofile_field_value( 'user_country', $user_id );

		if ( empty( $user_country ) && empty( $user_city ) ) {
			return false;
		}

		// Get Location
		if ( ! empty( $user_country ) && empty( $user_city ) ) {
			$user_location = $user_country;
		} elseif (  empty( $user_country ) && ! empty( $user_city ) ) {
			$user_location = $user_city;
		} elseif ( ! empty( $user_country ) && ! empty( $user_city ) ) {
			$user_location = "$user_city, $user_country";
		}

		if ( $only_data ) {
			return sanitize_text_field( $user_location );
		}

		// Get Location HTML.
		$location = '<li><i class="fas fa-map-marker-alt"></i><span>' . sanitize_text_field( $user_location ) . '</span></li>';

		echo apply_filters( 'youzify_get_profile_header_meta_user_location', $location );

	}

	/**
	 * Badges
	 */
	function badges( $args = null, $user_id = null ) {
		do_action( 'youzify_author_box_badges_content', $args );
	}

	/**
	 * Rating.
	 */
	function ratings( $args = null, $user_id = null ) {
		do_action( 'youzify_author_box_ratings_content', $args );
	}

	/**
	 * Address.
	 */
	function website() {

		// Get User Website
		$user_website = get_the_author_meta( 'user_url', bp_displayed_user_id() );

		if ( empty( $user_website ) ) {
			return false;
		}

		// Get Website HTML.
		$website = '<li><a href="' . esc_url( $user_website ) . '" target="_blank" rel="nofollow noopener"><i class="fas fa-link"></i><span>' . youzify_esc_url( $user_website ) . '</span></a></li>';

		echo apply_filters( 'youzify_get_profile_header_meta_user_website', $website );

	}

	/**
	 * Social Networks.
	 */
	function networks( $args = null ) {

		// Set Up Variable.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : null;
		$element = isset( $args['target'] ) ? $args['target'] : 'header';

		if ( ! youzify_is_user_has_networks( $user_id ) ) {
			return false;
		}

		// Networks Styling.
		$this->styling_networks( $element );

		// Get Social Networks
		$social_networks = youzify_option( 'youzify_social_networks' );

		// Display Networks Icons
		$display_networks = youzify_option( 'youzify_display_' . $element . '_networks', 'on' );

		// if Element is Widget Make it Networks Visible.
		if ( 'widget' == $element ) {
			$element = 'wg';
			$display_networks = 'on';
		}

		// Check Networks Visibility.
		if ( 'on' != $display_networks || empty( $social_networks ) ) {
			return false;
		}

		// Get networks Data.
		$data = youzify_get_args(
			array(
				'networks_type'   => youzify_option( 'youzify_' . $element . '_sn_bg_type', 'colorful' ),
				'networks_format' => youzify_option( 'youzify_' . $element . '_sn_bg_style', 'radius' ),
		), $args );

		// Get Networks Size
		$networks_size = youzify_options( 'youzify_wg_sn_icons_size' );
		if ( 'wg' == $element ) {
			$networks_class[] = "youzify-icons-$networks_size";
		}

		// Prepare Networks Class .
		$networks_class[] = "youzify-$element-networks";
		$networks_class[] = "youzify-icons-{$data['networks_type']}";
		$networks_class[] = "youzify-icons-{$data['networks_format']}";
		$networks_class[] = "youzify-networks-$user_id";

		// Networks Action
		do_action( 'youzify_before_networks', $args );

		// Get Networks Type
		$networks_class = youzify_generate_class( $networks_class );

		echo "<ul class='$networks_class'>";

		foreach ( $social_networks as $network => $data ) {

			// Get Widget Data
			$icon = apply_filters( 'youzify_user_social_networks_icon', $data['icon'] );
			$name = sanitize_text_field( $data['name'] );
			$link = esc_url( youzify_get_user_meta( $network, $user_id ) );

			if ( $link && $icon ) {
				echo "<li class='$network'><a href='$link' target='_blank' rel='nofollow noopener'>";
				echo "<i class='$icon'></i>";
				if ( 'wg' == $element && 'full-width' == $networks_size ) {
					echo $name;
				}
				echo '</a></li>';
			}

		}

		echo '</ul>';
	}

	/**
	 * Networks Styling.
	 **/

    /**
     * Header Social Networks Styling.
     */
    function styling_networks( $element = null ) {

        // Get Social Networks Data
        $social_networks  = youzify_option( 'youzify_social_networks' );
        $display_networks = youzify_option( 'youzify_display_' . $element . '_networks', 'on' );

        // if Element is Widget Make Networks Visible.
        if ( 'widget' == $element ) {
            $element = 'wg';
            $display_networks = 'on';
        }

        if ( 'on' != $display_networks || empty( $social_networks ) ) {
            return false;
        }

        // Get Networks Type & Size.
        $networks_size = youzify_option( 'youzify_wg_sn_icons_size', 'full-width' );
        $networks_type = youzify_option( 'youzify_' . $element . '_sn_bg_type', 'colorful' );

        // Get Styling Element.
        $icon = ( 'wg' == $element && 'full-width' == $networks_size ) ? 'a' : 'i';

        echo '<style type="text/css">';

        foreach ( $social_networks as $network => $data ) {

            // Get network Color
            $color = $data['color'];

            // Prepare selector
            $selector = ".youzify-$element-networks.youzify-icons-$networks_type .$network $icon";

            if ( 'colorful' == $networks_type ) {
                $property = "background-color";
            } elseif ( 'silver' == $networks_type || 'transparent' == $networks_type ) {
                $selector .= ':hover';
                $property = "background-color";
            } else {
                $selector .= ':hover';
                $property = "color";
            }

            // Prepare Css Code
            echo  "$selector { $property: $color !important; }";

        }

        echo '</style>';

    }

	/**
	 * Profile Statistics.
	 */
	function statistics( $args = null ) {

		// Set Up Variable.
		$target = isset( $args['target'] ) ? $args['target'] : 'header';

		// Get User ID.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_displayed_user_id();

		// Get Details.
		$statistics_details = youzify_get_user_statistics_details( $user_id );

		// Get Types.
		$first_statistic_type = youzify_option( 'youzify_' . $target . '_first_statistic', 'posts' );
		$second_statistic_type = youzify_option( 'youzify_' . $target . '_second_statistic', 'comments' );
		$third_statistic_type = youzify_option( 'youzify_' . $target . '_third_statistic', 'views' );

		// Show/Hide Elements.
		$display_first_statistic  = youzify_option( 'youzify_display_' . $target . '_first_statistic', 'on' );
		$display_third_statistic  = youzify_option( 'youzify_display_' . $target . '_third_statistic', 'on' );
		$display_second_statistic = youzify_option( 'youzify_display_' . $target . '_second_statistic', 'on' );
		// }

		if ( 'on' != $display_first_statistic && 'on' != $display_third_statistic && 'on' != $display_second_statistic ) {
			return false;
		}

		// Get Statistics Data.
		$data = youzify_get_args(
			array(
				'statistics_bg' 	=> youzify_option( 'youzify_' . $target . '_use_statistics_bg', 'on' ),
				'statistics_border' => youzify_option( 'youzify_' . $target . '_use_statistics_borders', 'on' ),
		), $args );

		// Get Statistics Class Name.
		$statistics_class[] = "youzify-user-statistics";
		$statistics_class[] = ( 'on' == $data['statistics_bg'] ) ? 'youzify-statistics-bg' : null;
		$statistics_class[] = ( 'on' == $data['statistics_border'] ) ? 'youzify-use-borders' : null;

		?>
			<div class="<?php echo youzify_generate_class( $statistics_class ); ?>">
				<ul>
					<?php if ( 'on' == $display_first_statistic && isset( $statistics_details[ $first_statistic_type ] ) ) : ?>

						<?php
							if ( ! isset( $statistics_details[ $first_statistic_type ] ) ) {
								return;
							}

							$first_nbr = youzify_get_user_statistic_number( $user_id, $first_statistic_type, 'first' );
						 ?>

						<li>
							<a href="<?php echo esc_url( $statistics_details[ $first_statistic_type ]['link'] ); ?>">
							<div class="youzify-snumber" title="<?php echo esc_html( $first_nbr ); ?>"><?php echo $this->get_statistic_number( $first_nbr ); ?></div>
							<h3 class="youzify-sdescription"><?php echo $statistics_details[ $first_statistic_type ]['title']; ?></h3>
							</a>
						</li>

					<?php endif; ?>

					<?php if ( 'on' == $display_second_statistic && isset( $statistics_details[ $second_statistic_type ] )) : ?>

						<?php $second_nbr = youzify_get_user_statistic_number( $user_id, $second_statistic_type, 'second' ); ?>

						<li>
							<a href="<?php echo esc_url( $statistics_details[ $second_statistic_type ]['link'] ); ?>">
							<div class="youzify-snumber" title="<?php echo $second_nbr; ?>"><?php echo $this->get_statistic_number( $second_nbr ); ?></div>
							<h3 class="youzify-sdescription"><?php echo $statistics_details[ $second_statistic_type ]['title']; ?></h3>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( 'on' == $display_third_statistic && isset( $statistics_details[ $third_statistic_type ] ) ) : ?>

						<?php
							$third_nbr = youzify_get_user_statistic_number( $user_id, $third_statistic_type, 'third' );
						?>

						<li>
							<a href="<?php echo esc_url( $statistics_details[ $third_statistic_type ]['link'] ); ?>">
							<div class="youzify-snumber" title="<?php echo $third_nbr; ?>"><?php echo $this->get_statistic_number( $third_nbr); ?></div>
							<h3 class="youzify-sdescription"><?php echo $statistics_details[ $third_statistic_type ]['title']; ?></h3>
							</a>
						</li>
					<?php endif; ?>

					<?php do_action( 'youzify_user_profile_statistics', $args ); ?>

				</ul>
			</div>
		<?php
	}

	/**
	 * Convert Statistics Number
	 */
	function get_statistic_number( $number ) {

		// if Number equal 0 return it.
		if ( 0 == $number ) {
			return 0;
		}

		// Define Number Letters.
		$abbrevs = array(
			12 	=> __( 'T', 'youzify' ),
			9 	=> __( 'B', 'youzify' ),
			6 	=> __( 'M', 'youzify' ),
			3 	=> __( 'K', 'youzify' ),
			0 	=> ''
		);

		// Get Number Letter
		foreach( $abbrevs as $exponent => $abbrev ) {
			if( $number >= pow( 10, $exponent ) ) {
				$display_num = $number / pow( 10, $exponent );
				$decimals = ( $exponent >= 3 && round( $display_num ) < 100 ) ? 1 : 0;
				$number_format = number_format( $display_num, $decimals );
				return $number_format . $abbrev;
			}
		}

	}

	/**
	 * Settings Buttons.
	 */
	function buttons( $args = null ) {

		// Set Up Variable.
		$target  = isset( $args['target'] ) ? $args['target'] : 'header';
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_displayed_user_id();

		// Check if current Box Belong to the logged-in user.
		$is_current_user_widget = ( get_current_user_id() == $user_id ) ? true : false;

		?>

		<div class="youzify-account-menu">

		<?php if ( ! is_user_logged_in() ) : ?>
			<?php if ( bp_is_user() ) : ?>
			<a class="youzify-button youzify-login" data-show-youzify-login="true" href="<?php echo youzify_get_login_page_url(); ?>">
				<i class="fas fa-user"></i>
				<span class="youzify-button-title"><?php _e( 'Login', 'youzify' ); ?></span>
			</a>
			<?php endif; ?>

		<?php elseif ( is_user_logged_in() && $is_current_user_widget ) : ?>

			<?php if ( ! bp_is_user() || ! youzify_is_wild_navbar_active() ) :?>
				<?php youzify_user_quick_buttons( bp_loggedin_user_id()); ?>
			<?php endif; ?>

		<?php else : ?>
			<?php youzify_get_social_buttons( $user_id ); ?>
		<?php endif; ?>

		</div>

		<?php
	}

	/**
	 * Author Box Head.
	 */
	function box_head( $target, $user_id = null ) {

		// Get User ID.
		$user_id = empty( $user_id ) ? bp_displayed_user_id() : $user_id;

		?>

		<div class="youzify-head-content">
			<a href="<?php echo apply_filters( 'youzify_author_box_profile_url', bp_members_get_user_url( $user_id ), $user_id ); ?>" class="youzify-head-username"><?php echo bp_core_get_user_displayname( $user_id ); ?><?php youzify_the_user_verification_icon( $user_id, 'medium' ); ?><div class="youzify-user-status"><?php echo youzify_add_user_online_status_icon( null, $user_id ); ?></div></a>
			<?php $this->box_meta( $target, $user_id ); ?>
		</div>

		<?php

	}

	/**
	 * Author Box Meta.
	 */
	function box_meta( $args = null ) {

		// Set Up Variables.
		$meta 	 = null;
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_displayed_user_id();

	    // Get Custom Meta Data
		$field_id  = $args['meta_type'];
	    $meta_icon = isset( $args['meta_icon'] ) ? $args['meta_icon'] : 'fas fa-globe';

	    $meta_value = youzify_get_user_field_data( $field_id, $user_id );

	    if ( empty( $meta_value ) ) {
	        // Set Default Meta.
	        $meta_html = '@ ' . bp_members_get_user_slug( $user_id );
	    } else {
	        // Create Custom Meta HTML Code.
	        $meta_html = '<i class="' . $meta_icon .'"></i>' . $meta_value;
	    }

		?>

		<span class="youzify-head-meta youzify-meta-<?php echo $field_id; ?>"><?php echo apply_filters( 'youzify_author_box_header_meta', $meta_html, $meta_icon, $meta_value, $field_id, $user_id ); ?></span>

		<?php

	}

	/**
	 * Profile Views Number.
	 */
	function views( $user_id = null ) {

		// Get View Count.
		$count = $this->get_profile_views( $user_id );

		if ( bp_is_user() && ! bp_is_my_profile() ) {
			$this->set_profile_views( $user_id, $count );
		}

		return $count;

	}

	/**
	 * Get Profile Views Number.
	 */
	function get_profile_views( $user_id ) {

		// Set Up Variables.
		$count = get_user_meta( $user_id, 'youzify_profile_views_count', true );

		// Get Views Number
		if ( $count == '' ) {
			return 0;
		}

		return $count;
	}

	/**
	 * Set Profile Views Number .
	 */
	function set_profile_views( $user_id, $count = 0 ) {

	    if ( $count > 0 ) {

			if ( apply_filters( 'youzify_profile_count_unique_views_only', false ) ) {

			 	// The user's IP address
			    $user_ip = $_SERVER['REMOTE_ADDR'];

				// Array of IP addresses that have already visited the post.
				if ( '' != get_user_meta( $user_id, 'youzify_profile_views_ip', true ) ) {
				    $ip = json_decode( get_user_meta( $user_id, 'youzify_profile_views_ip', true ), true );
				} else {
				    $ip = array();
				}

				// The following checks if the user's IP already exists
				for ( $i = 0; $i < count( $ip ); $i++ ) {
				    if ( $ip[ $i ] == $user_ip ) {
				        return false;
				    }
				}

				// Update and encode the $ip array into a JSON string
				$ip[ count( $ip ) ] = $user_ip;
				$json_ip = json_encode( $ip );

				// Update the user IP JSON object
				update_user_meta( $user_id, 'youzify_profile_views_ip', $json_ip );

			}

			// Increase Count.
			$count++;

			// Update the count
			update_user_meta( $user_id, 'youzify_profile_views_count', $count );

	    } else {

			// Save Count.
			add_user_meta( $user_id, 'youzify_profile_views_count', 1 );

	    }
	}
}

/**
 * Get a unique instance of Youzify Users.
 */
function youzify_users() {
	return Youzify_User::get_instance();
}

/**
 * Launch Youzify Users!
 */
youzify_users();