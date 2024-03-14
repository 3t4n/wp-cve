<?php
/*
Plugin Name: Testimonials by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/testimonials/
Description: Add testimonials and feedbacks from your customers to WordPress posts, pages and widgets.
Author: BestWebSoft
Text Domain: bws-testimonials
Domain Path: /languages
Version: 1.0.8
Author URI: https://bestwebsoft.com/
License: GPLv3 or later
*/

/*  @ Copyright 2021  BestWebSoft  ( https://support.bestwebsoft.com )

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Add option page in admin menu */
if ( ! function_exists( 'tstmnls_admin_menu' ) ) {
	function tstmnls_admin_menu() {
        global $menu;

        $awaiting_tstmnls = wp_count_posts( 'bws-testimonial' )->draft;
        $awaiting_tstmnls_i18n = number_format_i18n( $awaiting_tstmnls );

        $menu_item = wp_list_filter(
            $menu,
            array( 2 => 'edit.php?post_type=bws-testimonial' )
        );

        if ( ! empty( $menu_item )  ) {
            $menu_item_position = key( $menu_item );
            $menu[$menu_item_position][0] .= ' <span class="awaiting-mod count-' . $awaiting_tstmnls . '"><span class="pending-count" aria-hidden="true">' . $awaiting_tstmnls_i18n . '</span></span>';
        }

		$settings = add_submenu_page( 'edit.php?post_type=bws-testimonial', __( 'Testimonials Settings', 'bws-testimonials' ), __( 'Settings', 'bws-testimonials' ), 'manage_options', "testimonials.php", 'tstmnls_settings_page' );
		add_submenu_page( 'edit.php?post_type=bws-testimonial', 'BWS Panel', 'BWS Panel', 'manage_options', 'tstmnls-bws-panel', 'bws_add_menu_render' );

		add_action( 'load-' . $settings, 'tstmnls_add_tabs' );
		add_action( 'load-post.php', 'tstmnls_add_tabs' );
		add_action( 'load-edit.php', 'tstmnls_add_tabs' );
		add_action( 'load-post-new.php', 'tstmnls_add_tabs' );
	}
}

/**
 * Internationalization
 */
if ( ! function_exists( 'tstmnls_plugins_loaded' ) ) {
	function tstmnls_plugins_loaded() {
		load_plugin_textdomain( 'bws-testimonials', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

if ( ! function_exists ( 'tstmnls_init' ) ) {
	function tstmnls_init() {
		global $tstmnls_plugin_info, $pagenow;

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );

		if ( empty( $tstmnls_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$tstmnls_plugin_info = get_plugin_data( __FILE__ );
		}

		/* Call register settings function */
		if ( ! is_admin() || 'widgets.php' == $pagenow || 'plugins.php' == $pagenow || ( isset( $_REQUEST['page'] ) && 'testimonials.php' == $_REQUEST['page'] ) ) {
			tstmnls_register_settings();
		}

		/* Function check if plugin is compatible with current WP version */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $tstmnls_plugin_info, '4.5' );

        if ( is_admin() && 'edit.php' == $pagenow && $_GET['post_type'] == 'bws-testimonial' ) {
		    tstmnls_approve_button();
        }
		tstmnls_register_testimonial_post_type();

		tstmnls_show_testimonials_form();

		if ( has_action( 'tstmnls_show_review_form' ) && ! is_admin() ) {
			tstmnls_review_form();
		}

		/* Redirect if testimonials form send successfully */
		if ( ! isset( $_POST["tstmnls_testimonial_author"],
			$_POST["tstmnls_testimonial_title"],
			$_POST["tstmnls_testimonial_comment"] ) && isset( $_POST["redirect_once"] ) ) {
			$_POST["redirect_once"] = FALSE;
			$url = add_query_arg( 'message', 'true', $_SERVER['REQUEST_URI'] );
			header( "Location: " . $url );
			exit;
		}
	}
}

if ( ! function_exists( 'tstmnls_reviews_shortcode' ) ) {
	function tstmnls_reviews_shortcode() {
		ob_start();
		tstmnls_reviews();
		return ob_get_clean();
	}
}

if ( ! function_exists( 'tstmnls_review_form_shortcode' ) ) {
	function tstmnls_review_form_shortcode() {
		ob_start();
		tstmnls_review_form();
		return ob_get_clean();
	}
}

if ( ! function_exists ( 'tstmnls_admin_init' ) ) {
	function tstmnls_admin_init() {
		global $bws_plugin_info, $tstmnls_plugin_info, $bws_shortcode_list;

		if ( empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '180', 'version' => $tstmnls_plugin_info["Version"] );

		add_meta_box( 'custom-metabox', __( 'Testimonials Info', 'bws-testimonials' ), 'tstmnls_custom_metabox', 'bws-testimonial', 'normal', 'high' );

		/* add Testimonials to global $bws_shortcode_list */
		$bws_shortcode_list['tstmnls'] = array( 'name' => 'Testimonials', 'js_function' => 'tstmnls_shortcode_init' );
	}
}

if ( ! function_exists ( 'tstmnls_register_testimonial_post_type' ) ) {
	function tstmnls_register_testimonial_post_type() {
		$args = array(
			'label'				=> __( 'Testimonials', 'bws-testimonials' ),
			'singular_label'	=> __( 'Testimonial', 'bws-testimonials' ),
			'public'			=> true,
			'show_ui'			=> true,
			'capability_type'	=> 'post',
			'hierarchical'		=> false,
			'rewrite'			=> true,
			'supports'			=> array( 'title', 'editor', 'thumbnail' ),
			'labels'			=> array(
                'all_items'				=> __( 'Testimonials', 'bws-testimonials' ),
				'add_new'				=> __( 'Add New', 'bws-testimonials' ),
				'add_new_item'			=> __( 'Add a new testimonial', 'bws-testimonials' ),
				'edit_item'				=> __( 'Edit Testimonials', 'bws-testimonials' ),
				'new_item'				=> __( 'New testimonial', 'bws-testimonials' ),
				'view_item'				=> __( 'View testimonials', 'bws-testimonials' ),
				'search_items'			=> __( 'Search Testimonials', 'bws-testimonials' ),
				'not_found'				=> __( 'No testimonials found', 'bws-testimonials' ),
				'not_found_in_trash'	=> __( 'No testimonials found in Trash', 'bws-testimonials' ),
				'filter_items_list'		=> __( 'Testimonials list filter', 'bws-testimonials' ),
				'items_list_navigation' => __( 'Testimonials list navigation', 'bws-testimonials' ),
				'items_list'			=> __( 'Testimonials list', 'bws-testimonials' )
			),
		);
		register_post_type( 'bws-testimonial' , $args );
	}
}

if ( ! function_exists ( 'tstmnls_approve_button' ) ) {
    function tstmnls_approve_button() {
        if( isset( $_GET['nonce_approve'], $_GET['post_type'] ) && $_GET['post_type'] == 'bws-testimonial' ) {
            $post_id = $_GET['testimonial_id'];
            $post_status = get_post_status( $post_id );
            if( wp_verify_nonce( $_GET['nonce_approve'], 'publish-post_' . $post_id ) && $post_status == 'draft' ) {
                wp_publish_post($post_id);
            }
        }
    }
}

if ( ! function_exists ( 'tstmnls_modify_list_row_actions' ) ) {
    function tstmnls_modify_list_row_actions( $actions, $post ) {

        if ( $post->post_type == 'bws-testimonial' ) {
            $post_status = get_post_status( $post->ID );
            if ( $post_status == 'draft' ) {
                $actions['approvetestimonial'] = "<span class=\"trash\"><a class='vim-a aria-button-if-js' href='" . wp_nonce_url("edit.php?post_type=bws-testimonial", 'publish-post_' . $post->ID, 'nonce_approve') . "&testimonial_id=" . $post->ID . "'>" . __('Approve', 'bws-testimonials') . "</a></span>";
            }
        }
        return $actions;
    }
}

if ( ! function_exists ( 'tstmnls_patterns_testimonials' ) ) {
    function tstmnls_patterns_testimonials() {
        global $tstmnls_options;

        if( ! empty( $tstmnls_options['gdpr_link'] ) ) {
            $content = ' ' . '<a href="' . get_page_uri( $tstmnls_options['gdpr_link'] ) . '" target="_blank">' . $tstmnls_options['gdpr_text'] . '</a>';
        } else {
            $content = '<span>' . ' ' . $tstmnls_options['gdpr_text'] . '</span>';
        }
        $patterns = array(
            array( '/{PRIVACYPOLICY}/', $content ),
        );
        foreach ( $patterns as $pattern ) {
            $data = preg_replace( $pattern[0], $pattern[1], $tstmnls_options['gdpr_tm_name'] );
        }
        return $data;
    }
}

/**
 * @return array Default plugin options
 * @since 0.2.4
 */
if ( ! function_exists( 'tstmnls_get_option_defaults' ) ) {
	function tstmnls_get_option_defaults() {
		global $tstmnls_plugin_info;

		if ( ! $tstmnls_plugin_info ) {
			$tstmnls_plugin_info = get_plugin_data( __FILE__ );
		}

		$option_defaults = array(
			'plugin_option_version'		        => $tstmnls_plugin_info["Version"],
			'widget_title'				        => __( 'Testimonials', 'bws-testimonials' ),
			'count'						        => '5',
			'display_settings_notice'	        => 1,
            'custom_size_px'					=> array( 'tstmnls_custom_size' => array( 160, 120 ) ),
            'image_size_photo'					=> 'thumbnail',
			'order_by'					        => 'date',
			'order'						        => 'DESC',
			'suggest_feature_banner'	        => 1,
			'permissions'				        => 'all',
			'auto_publication'			        => 0,
			/* form labels */
			'gdpr_text'					        => 'Privacy Policy',
			'gdpr_link'					        => '',
			'gdpr_tm_name'				        => __( 'I agree to {PRIVACYPOLICY} and having this website store my submitted information so they can respond to my inquiry.', 'bws-testimonials' ),
			/* To email settings */
			'gdpr'						        => 0,
			'recaptcha_cb'				        => 0,
			'rating_cb'				            => 0,
			'reviews_per_load'					=> 5,
            /*carousel options*/
            'items_in_slide'					=> 1,
            'loop'						        => 0,
            'nav'						        => 0,
            'dots'						        => 0,
            'autoplay'					        => 0,
            'autoplay_timeout'			        => '2000',
            'auto_height'				        => 0
            /*The end*/
            );
		return $option_defaults;
	}
}

/**
* Register settings for plugin
*/
if ( ! function_exists( 'tstmnls_register_settings' ) ) {
	function tstmnls_register_settings() {
		global $tstmnls_options, $tstmnls_plugin_info;

		$tstmnls_option_defaults = tstmnls_get_option_defaults();

		/* Install the option defaults */
		if ( ! get_option( 'tstmnls_options' ) ) {
			add_option( 'tstmnls_options', $tstmnls_option_defaults );
		}

		$tstmnls_options = get_option( 'tstmnls_options' );

		if ( ! isset( $tstmnls_options['plugin_option_version'] ) || $tstmnls_options['plugin_option_version'] != $tstmnls_plugin_info["Version"] ) {

			tstmnls_plugin_activate();

			$tstmnls_option_defaults['display_settings_notice'] = 0;
			$tstmnls_options = array_merge( $tstmnls_option_defaults, $tstmnls_options );
			$tstmnls_options['plugin_option_version'] = $tstmnls_plugin_info["Version"];
			update_option( 'tstmnls_options', $tstmnls_options );
		}
	}
}

/**
 * Function for activation
 */
if ( ! function_exists( 'tstmnls_plugin_activate' ) ) {
	function tstmnls_plugin_activate() {
		/* registering uninstall hook */
		if ( is_multisite() ) {
			switch_to_blog( 1 );
			register_uninstall_hook( __FILE__, 'tstmnls_plugin_uninstall' );
			restore_current_blog();
		} else {
			register_uninstall_hook( __FILE__, 'tstmnls_plugin_uninstall' );
		}
	}
}

/**
 * Function to retrieve related plugin status information
 * @param	string	$plugin_name 		The name of related plugin
 * @return	array	$status 			An array with the following key=>value data: 'installed' => bool, 'active' => 'free'|'pro'|'outdated'|false, 'enabled' => bool
 */
if ( ! function_exists( 'tstmnls_get_related_plugin_status' ) ) {
	function tstmnls_get_related_plugin_status( $plugin_name = '' ) {
		$related_plugins = array(
			'recaptcha'		=> array(
				'link_slug'	=> array(
					'free'	=> 'google-captcha/google-captcha.php',
					'pro'	=> 'google-captcha-pro/google-captcha-pro.php',
                    'plus'  => 'google-captcha-plus/google-captcha-plus.php',
				),
				'options_name'	=> 'gglcptch_options'
			),
			'rating'		=> array(
				'link_slug'	=> array(
					'free'	=> 'rating-bws/rating-bws.php',
					'pro'	=> 'rating-bws-pro/rating-bws-pro.php'
				),
				'options_name'	=> 'rtng_options'
			),
			'sender'		=> array(
				'link_slug'	=> array(
					'free'	=> 'sender/sender.php',
					'pro'	=> 'sender-pro/sender-pro.php'
				),
				'options_name'	=> 'sndr_options'
			),
		);

		$status = array(
			'installed'		=> false,
			'active'		=> false,
			'enabled'		=> false
		);

		if ( empty( $plugin_name ) || ! array_key_exists( $plugin_name, $related_plugins ) ) {
			return $status;
		}

		$plugin = $related_plugins[ $plugin_name ];

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$all_plugins = get_plugins();

		foreach ( $plugin['link_slug'] as $link_slug ) {
			if ( array_key_exists( $link_slug, $all_plugins ) ) {
				$is_installed = true;
				break;
			}
		}

		if ( ! isset( $is_installed ) ) {
			return $status;
		}

		$status['installed'] = true;

		foreach ( $plugin['link_slug'] as $key => $link_slug ) {
			$status['enabled'] = is_plugin_active( $link_slug );

			if ( $status['enabled'] ) {
				$version = $key;
				break;
			}
		}

		if ( ! isset( $version ) ) {
			return $status;
		}

		$status['active'] = $version;

		if ( is_multisite() ) {
			if ( get_site_option( $plugin['options_name'] ) ) {
				$plugin_options = get_site_option( $plugin['options_name'] );
				if ( ! ( isset( $plugin_options['network_apply'] ) && 'all' == $plugin_options['network_apply'] ) ) {
					if ( get_option( $plugin['options_name'] ) ) {
						$plugin_options = get_option( $plugin['options_name'] );
					}
				}
			} elseif ( get_option( $plugin['options_name'] ) ) {
				$plugin_options = get_option( $plugin['options_name'] );
			}
		} else {
			if ( get_option( $plugin['options_name'] ) ) {
				$plugin_options = get_option( $plugin['options_name'] );
			}
		}

		if ( empty( $plugin_options ) ) {
			return $status;
		}

		return $status;
	}
}

/**
* Add settings page in admin area
*/
if ( ! function_exists( 'tstmnls_settings_page' ) ) {
	function tstmnls_settings_page() {
		global $title;
		if ( ! class_exists( 'Bws_Settings_Tabs' ) )
			require_once( dirname( __FILE__ ) . '/bws_menu/class-bws-settings.php' );
        require_once( dirname( __FILE__ ) . '/includes/class-tstmnls-settings.php' );
        $page = new Tstmnls_Settings_Tabs( plugin_basename( __FILE__ ) );
		if ( method_exists( $page,'add_request_feature' ) )
			$page->add_request_feature(); ?>
		<div class="wrap">
			<h1><?php echo $title; ?></h1>
			<noscript>
            	<div class="error below-h2">
                	<p><strong><?php _e( 'WARNING', 'bws-testimonials' ); ?>
                    	    :</strong> <?php _e( 'The plugin works correctly only if JavaScript is enabled.', 'bws-testimonials' ); ?>
                	</p>
            	</div>
        	</noscript>
            <?php $page->display_content(); ?>
		</div>
	<?php }
}

if ( ! function_exists( 'tstmnls_custom_metabox' ) ) {
	function tstmnls_custom_metabox() {
		global $post, $rtng_options;
		if ( empty( get_post_meta( $post->ID, 'testimonials_info', true ) ) )
			update_post_meta( $post->ID, 'testimonials_info', get_post_meta( $post->ID, '_testimonials_info', true ) );
		$testimonials_info = get_post_meta( $post->ID, 'testimonials_info', true ); ?>
		<p>
			<label for="tstmnls_author"><?php _e( 'Author', 'bws-testimonials' ); ?>:<br />
			<input type="text" id="tstmnls_author" name="tstmnls_author" value="<?php if ( ! empty( $testimonials_info['author'] ) ) echo $testimonials_info['author']; ?>"/></label>
		</p>
		<?php
		if ( isset( $testimonials_info['rating'], $rtng_options ) ) {
			$rating = maybe_unserialize( $testimonials_info['rating'] );
            if( isset( $rtng_options['testimonials_titles'] ) && is_array( $rtng_options['testimonials_titles'] ) ) {
                foreach( $rtng_options['testimonials_titles'] as $key => $value ) { ?>
				<p>
					<label for="tstmnls_rating_<?php echo $key; ?>"><?php echo $value; ?>:</label>
					<br />
					<input type="number" id="tstmnls_rating_<?php echo $key; ?>" name="tstmnls_rating[<?php echo $key; ?>]" value="<?php echo isset( $rating[ $key ] ) ? $rating[ $key ] : ''; ?>"/>
				</p>
			<?php }
            }
		} else { ?>
			<p>
				<label for="tstmnls_company_name"><?php _e( 'Company Name', 'bws-testimonials' ); ?>:</label>
				<br />
				<input type="text" id="tstmnls_company_name" name="tstmnls_company_name" value="<?php if ( ! empty( $testimonials_info['company_name'] ) ) echo $testimonials_info['company_name']; ?>"/>
			</p>
		<?php }
	}
}

if ( ! function_exists( 'tstmnls_save_postdata' ) ) {
	function tstmnls_save_postdata( $post_id ) {
		global $wpdb;

		/*
		* We need to verify this came from the our screen and with proper authorization,
		* because save_post can be triggered at other times.
		*/
		/* If this is an autosave, our form has not been submitted, so we don't want to do anything. */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		/* Check if our CPT is saved. */
		if ( get_post_type( $post_id ) != 'bws-testimonial' ) {
			return $post_id;
		}
		// This check needed because if we save post in frontend there might be different $_POST variables
		if ( ! is_admin() ) {
			return $post_id;
		}
		if ( empty( get_post_meta( $post_id, 'testimonials_info', true ) ) )
			update_post_meta( $post_id, 'testimonials_info', get_post_meta( $post_id, '_testimonials_info', true ) );
		$testimonials_info = get_post_meta( $post_id, 'testimonials_info', true );
		if ( isset( $_POST['tstmnls_author'] ) && ! empty( $_POST['tstmnls_author'] ) ) {
			$testimonials_info['author'] = esc_js( $_POST[ 'tstmnls_author' ] );
		}
		if ( isset( $_POST['tstmnls_company_name'] ) && ! empty( $_POST['tstmnls_company_name'] ) ) {
			$testimonials_info['company_name'] = esc_js( $_POST[ 'tstmnls_company_name' ] );
		}
		if ( isset( $_POST['tstmnls_rating'] ) ) {
			$testimonials_info['rating'] = array_map( 'intval', array_filter( $_POST['tstmnls_rating'] ) );

			$wpdb->update(
				$wpdb->prefix . 'bws_rating',
				array(
					'rating'	=> maybe_serialize( $testimonials_info['rating'] )
				),
				array(
					'object_id'	=> $post_id
				)
			);
		}

		/* Update the meta field in the database. */
		update_post_meta( $post_id, 'testimonials_info', $testimonials_info );
	}
}

if ( ! function_exists( 'tstmnls_delete_review' ) ) {
	function tstmnls_delete_review( $post_id ) {
		global $wpdb;
	
		if ( 'bws-testimonial' != get_post_type( $post_id ) ) {
			return;
		}

		$results = $wpdb->get_results( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'bws_rating"' );
		if ( ! empty( $results ) ) {
			$wpdb->delete(
				$wpdb->prefix . "bws_rating",
				array( 'object_id' => $post_id )
			);
		}
	}
}

/**
 * Remove shortcode from the content of the testimonial
 */
if ( ! function_exists ( 'tstmnls_content_save_pre' ) ) {
	function tstmnls_content_save_pre( $content ) {
		global $post;
		if ( isset( $post ) && "bws-testimonial" == $post->post_type && ! wp_is_post_revision( $post->ID ) && ! empty( $_POST ) ) {
			/* remove shortcode */
			$content = str_replace( '[bws_testimonials]', '', $content );
		}
		return $content;
	}
}
/*Widgets for Testimonials Slider*/
if ( ! class_exists( 'Testimonials_Slider' ) ) {
    class Testimonials_Slider extends WP_Widget {
        function __construct() {
            /* Instantiate the parent object */
            parent::__construct( 'tstmnls_testimonails_widget_slider',
                __( 'Testimonials Slider Widget', 'bws-testimonials' ),
                array( 'description' => __( 'Widget for displaying Testimonials Slider.', 'bws-testimonials' ) )
            );
        }

        function widget( $args, $instance ) {
            global $tstmnls_options;

            if ( empty( $tstmnls_options ) ) {
                tstmnls_register_settings();
            }

            $widget_title	= isset( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'], $instance, $this->id_base ) : $tstmnls_options['widget_title'];
            $count			= isset( $instance['count'] ) ? intval( $instance['count'] ) : $tstmnls_options['count'];

            echo $args['before_widget'];
            if ( ! empty( $widget_title ) ) {
                echo $args['before_title'] . $widget_title . $args['after_title'];
            }

            tstmnls_show_testimonials_slider( $count,$instance );
            echo $args['after_widget'];
        }

        function form( $instance ) {
            global $tstmnls_options;

            if ( empty( $tstmnls_options ) ) {
                tstmnls_register_settings();
            }

            $widget_title = isset( $instance['widget_title'] ) ? stripslashes( esc_html( $instance['widget_title'] ) ) : $tstmnls_options['widget_title'];
            $autoplay_timeout = isset( $instance['autoplay_timeout'] ) ? stripslashes( esc_html( $instance['autoplay_timeout'] ) ) : $tstmnls_options['autoplay_timeout']/1000;
            $loop = isset( $instance['loop'] ) ? $instance['loop'] : $tstmnls_options['loop'];
            $nav = isset( $instance['nav'] ) ? $instance['nav'] : $tstmnls_options['nav'];
            $dots = isset( $instance['dots'] ) ? $instance['dots'] : $tstmnls_options['dots'];
            $autoplay = isset( $instance['autoplay'] ) ? $instance['autoplay'] : $tstmnls_options['autoplay'];
            $auto_height = isset( $instance['auto_height'] ) ? $instance['auto_height'] : $tstmnls_options['auto_height']; ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Widget Title', 'bws-testimonials' ); ?>: </label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" maxlength="250" value="<?php echo esc_attr( $widget_title ); ?>"/>
            </p>
            <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'loop' ); ?>" name="<?php echo $this->get_field_name( 'loop' ); ?>"<?php checked( $loop ); ?> />
                <label for="<?php echo $this->get_field_id( 'loop' ); ?>"><?php _e( 'Loop', 'bws-testimonials' ); ?></label><br />
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'auto_height' ); ?>" name="<?php echo $this->get_field_name( 'auto_height' ); ?>"<?php checked( $auto_height ); ?> />
                <label for="<?php echo $this->get_field_id( 'auto_height' ); ?>"><?php _e( 'Auto Height', 'bws-testimonials' ); ?></label><br />
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'nav' ); ?>" name="<?php echo $this->get_field_name( 'nav' ); ?>"<?php checked( $nav ); ?> />
                <label for="<?php echo $this->get_field_id( 'nav' ); ?>"><?php _e( 'Arrow', 'bws-testimonials' ); ?></label><br/>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'dots' ); ?>" name="<?php echo $this->get_field_name( 'dots' ); ?>"<?php checked( $dots ); ?> />
                <label for="<?php echo $this->get_field_id( 'dots' ); ?>"><?php _e( 'Dots', 'bws-testimonials' ); ?></label><br/>
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>"<?php checked( $autoplay ); ?> />
                <label for="<?php echo $this->get_field_id( 'autoplay' ); ?>"><?php _e( 'Autoplay', 'bws-testimonials' ); ?></label><br />
                <label for="<?php echo $this->get_field_id( 'autoplay_timeout' ); ?>"><?php _e( 'Autoplay Timeout', 'bws-testimonials' ); ?>: </label>
                <input class="tiny-text" id="<?php echo $this->get_field_id( 'autoplay_timeout' ); ?>" name="<?php echo $this->get_field_name( 'autoplay_timeout' ); ?>" type="number" min="1" max="1000" value="<?php echo esc_attr( $autoplay_timeout ) ; ?>"/>
                <label for="<?php echo $this->get_field_id( 'autoplay_timeout' ); ?>"><?php _e( 'sec', 'bws-testimonials' ); ?></label>
            </p>
        <?php }

        function update( $new_instance, $old_instance ) {
            global $tstmnls_options;

            if ( empty( $tstmnls_options ) )
                tstmnls_register_settings();

            $instance = array();
            $instance['widget_title'] = ( isset( $new_instance['widget_title'] ) ) ? stripslashes( esc_html( $new_instance['widget_title'] ) ) : $tstmnls_options['widget_title'];
            $instance['autoplay_timeout'] = ( isset( $new_instance['autoplay_timeout'] ) ) ? stripslashes( esc_html( $new_instance['autoplay_timeout'] ) ) : $tstmnls_options['autoplay_timeout'];
            $instance['loop']			    = ! empty( $new_instance['loop'] ) ? 1 : 0;
            $instance['nav']			    = ! empty( $new_instance['nav'] ) ? 1 : 0;
            $instance['dots']			    = ! empty( $new_instance['dots'] ) ? 1 : 0;
            $instance['autoplay']			= ! empty( $new_instance['autoplay'] ) ? 1 : 0;
            $instance['auto_height']		= ! empty( $new_instance['auto_height'] ) ? 1 : 0;

            return $instance;
        }
    }
}

if ( ! class_exists( 'Testimonials' ) ) {
	class Testimonials extends WP_Widget {
		function __construct() {
			/* Instantiate the parent object */
			parent::__construct( 'tstmnls_testimonails_widget',
				__( 'Testimonials Widget', 'bws-testimonials' ),
				array( 'description' => __( 'Widget for displaying Testimonials.', 'bws-testimonials' ) )
			);
		}

		function widget( $args, $instance ) {
			global $tstmnls_options;
			if ( empty( $tstmnls_options ) ) {
				tstmnls_register_settings();
			}

			$widget_title	= isset( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'], $instance, $this->id_base ) : $tstmnls_options['widget_title'];
			$count			= isset( $instance['count'] ) ? intval( $instance['count'] ) : $tstmnls_options['count'];
			echo $args['before_widget'];
			if ( ! empty( $widget_title ) ) {
				echo $args['before_title'] . $widget_title . $args['after_title'];
			}
			tstmnls_show_testimonials( $count );
			echo $args['after_widget'];
		}

		function form( $instance ) {
			global $tstmnls_options;
			if ( empty( $tstmnls_options ) ) {
				tstmnls_register_settings();
			}
			$widget_title = isset( $instance['widget_title'] ) ? stripslashes( esc_html( $instance['widget_title'] ) ) : $tstmnls_options['widget_title'];
			$count = isset( $instance['count'] ) ? intval( $instance['count'] ) : $tstmnls_options['count']; ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Widget Title', 'bws-testimonials' ); ?>: </label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" maxlength="250" value="<?php echo esc_attr( $widget_title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of Testimonials', 'bws-testimonials' ); ?>: </label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" min="1" max="10000" value="<?php echo esc_attr( $count ); ?>"/>
			</p>
		<?php }

		function update( $new_instance, $old_instance ) {
			global $tstmnls_options;
			if ( empty( $tstmnls_options ) )
				tstmnls_register_settings();

			$instance = array();
			$instance['widget_title'] = ( isset( $new_instance['widget_title'] ) ) ? stripslashes( esc_html( $new_instance['widget_title'] ) ) : $tstmnls_options['widget_title'];
			$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? intval( $new_instance['count'] ) : $tstmnls_options['count'];

			return $instance;
		}
	}
}

if ( ! function_exists( 'tstmnls_show_testimonials_slider' ) ) {
    function tstmnls_show_testimonials_slider( $count = false, $instance = false ) {
        echo tstmnls_show_testimonials_shortcode_slider( array( 'count' => $count , 'instance' => $instance ) );
    }
}

if ( ! function_exists( 'tstmnls_reviews' ) ) {
	function tstmnls_reviews( $post_id = null ) {
		global $wpdb, $tstmnls_options, $rtng_options;

		$status = tstmnls_get_related_plugin_status( 'rating' );

		if ( empty( $rtng_options ) ) {
			tstmnls_register_settings();
		}

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$amount_of_stars = isset( $rtng_options['quantity_star'] ) ? $rtng_options['quantity_star'] : 5;

		if ( 1 == $tstmnls_options['rating_cb'] ) {
            $last_review = $wpdb->get_row(
                "SELECT `p`.`ID`, `post_title`, `post_content`, `post_author`, `post_date`, `rating`
                FROM `" . $wpdb->prefix . "posts` AS p
                INNER JOIN `" . $wpdb->prefix . "bws_rating`
                ON `p`.`ID` = `object_id`
                WHERE `post_status` = 'publish' AND `post_id` = '" . $post_id . "'
                ORDER BY `p`.`ID` DESC",
                ARRAY_A
            );
        }   ?>
		<br />
		<br />
		<div id="tstmnls-reviews">
			<div class="tstmnls-total">
				<?php if ( $status['enabled'] && $tstmnls_options['rating_cb'] ) {
					$rating_args = array(
						'post_id'			=> $post_id,
						'show_review_count'	=> true,
					);
					echo rtng_show_total_rating( $rating_args );
					$ratings_arr = $wpdb->get_col(
						"SELECT `rating`
						FROM `" . $wpdb->prefix . "bws_rating`
						WHERE `post_id` = '" . $post_id . "'"
					);
					$total_arr = array();
					foreach ( $ratings_arr as $rate ) {
						$ratings = maybe_unserialize( $rate );
						foreach ( $ratings as $key => $value ) {
							if ( ! isset( $total_arr[ $key ] ) ) {
								$total_arr[ $key ] = 0;
							}
							$total_arr[ $key ] += $value;
						}
					}
					$count = count( $ratings_arr );
					foreach ( $total_arr as $key => $value ) {
						$total_arr[ $key ] = $value / $count;
					}
					ksort( $total_arr ); ?>
					<div class="tstmnls-total-rating">
						<?php foreach ( $total_arr as $key => $rate ) { ?>
							<div class="tstmnls-review-rating-block rtng-text">
								<?php if ( isset( $rtng_options['testimonials_titles'][ $key ] ) ) {
									echo $rtng_options['testimonials_titles'][ $key ];
									?>
									<div class="rtng-rate-bar-wrap">
										<div class="rtng-rate-bar" style="width: <?php echo $rate; ?>%"></div>
									</div>
									<div class="rtng-rate-bar-number"><?php echo number_format( ( $rate / 100 ) * $amount_of_stars, 1 ); ?></div>
									<?php
								} ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<br />
			<br />
			<?php
			if ( ! empty( $last_review ) ) {
				tstmnls_display_review( $last_review );
				?>
				<button type="button" data-post-id="<?php echo $post_id; ?>" class="button button-primary tstmnls-all-btn"><?php _e( 'See all reviews', 'bws-testimonials' ) ?></button>
			<?php } ?>
		</div><!-- #tstmnls-reviews -->
	<?php }
}

if ( ! function_exists( 'tstmnls_display_review' ) ) {
	function tstmnls_display_review( $review ) {
		global $tstmnls_options, $rtng_options;

		if ( empty( $review ) ) {
			return;
		}

		$status = tstmnls_get_related_plugin_status( 'rating' );
		
		if ( empty( $rtng_options ) ) {
			$rtng_options = get_option( 'rtng_options' );
		}
		if ( empty( $tstmnls_options ) ) {
			tstmnls_register_settings();
		}

		$amount_of_stars = isset( $rtng_options['quantity_star'] ) ? $rtng_options['quantity_star'] : 5;

		if ( isset( $review['rating'] ) ) {
			$rating = maybe_unserialize( $review['rating'] );
			$rating_avg = array_sum( $rating ) / count( $rating );
		}
		if ( empty( get_post_meta( $review['ID'], 'testimonials_info', true ) ) )
		    update_post_meta( $review['ID'], 'testimonials_info', get_post_meta( $review['ID'], '_testimonials_info', true ) );
		$testimonials_info = get_post_meta( $review['ID'], 'testimonials_info', true ); ?>
		<div class="tstmnls-single-review">
			<div class="tstmnls-autor">
				<div class="tstmnls-autor-thumbnail"><?php echo get_avatar( $review['post_author'] ); ?></div>
				<div class="tstmnls-author-flag"></div>
				<div class="tstmnls-author-name"><?php echo isset( $testimonials_info['author'] ) ? $testimonials_info['author'] : ''; ?></div>
				<div class="tstmnls-author-country"></div>
			</div>
			<div class="tstmnls-review">
				<h2 class="tstmnls-review-title"><?php echo $review['post_title']; ?></h2>
				<?php if ( $status['enabled'] && $tstmnls_options['rating_cb'] && isset( $review['rating'] ) ) { ?>
					<div class="tstmnls-review-rating-average"><?php echo rtng_display_stars( $rating_avg ); ?></div>
				<?php } ?>
				<p class="tstmnls-review-content"><?php echo $review['post_content']; ?></p>
				<?php if ( $status['enabled'] && $tstmnls_options['rating_cb'] && isset( $review['rating'] ) ) { ?>
					<div class="tstmnls-review-rating">
						<?php foreach ( $rating as $key => $rate ) { ?>
							<div class="tstmnls-review-rating-block">
								<?php if ( isset( $rtng_options['testimonials_titles'][ $key ] ) ) { ?>
									<span class="tstmnls-title"><?php echo $rtng_options['testimonials_titles'][ $key ]; ?></span>
									<div class="tstmnls-rate rtng-text">
										<span class="tstmnls-rate-number"><?php echo number_format( ( $rate / 100 ) * $amount_of_stars ); ?></span>
										<?php _e( 'out of', 'bws-testimonials' );
										echo $amount_of_stars; ?>	
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div><!-- .tstmnls-review -->
		</div><!-- .tstmnls-single-review -->
		<br />
		<br />
	<?php }
}

if ( ! function_exists( 'tstmnls_load_reviews' ) ) {
	function tstmnls_load_reviews() {
		global $wpdb, $tstmnls_options;

		if ( empty( $tstmnls_options ) ) {
			tstmnls_register_settings();
		}

		$offset = isset( $_POST['offset'] ) ? $_POST['offset'] : 0;

		$reviews = $wpdb->get_results(
			"SELECT `p`.`ID`, `post_title`, `post_content`, `post_author`, `post_date`, `rating`
			FROM `" . $wpdb->prefix . "posts` AS p
			INNER JOIN `" . $wpdb->prefix . "bws_rating`
			ON `p`.`ID` = `object_id`
			WHERE `post_status` = 'publish' AND `post_id` = '" . $_POST['post_id'] . "'
			ORDER BY `p`.`ID` DESC
			LIMIT " . $offset . ", " . $tstmnls_options['reviews_per_load'],
			ARRAY_A
		);

		if ( ! empty( $reviews ) ) {
			foreach ( $reviews as $review ) {
				tstmnls_display_review( $review );
			}
		}

		wp_die();
	}
}

if ( ! function_exists( 'tstmnls_review_form' ) ) {
	function tstmnls_review_form() {
		global $post, $wpdb, $tstmnls_options, $rtng_options;

		$status_rating = tstmnls_get_related_plugin_status( 'rating' );

		$form_title = $form_content = $subm_result = $form_error = $captcha_error_messages = '';
		
		if ( empty( $rtng_options ) ) {
			$rtng_options = get_option( 'rtng_options' );
		}
		if ( empty( $tstmnls_options ) ) {
			tstmnls_register_settings();
		}

		$status = tstmnls_get_related_plugin_status( 'recaptcha' );

		/* Check if plugin Google Captcha Pro is activated */
		if ( ( 'free' == $status['active'] || 'pro' == $status['active'] || 'plus' == $status['active'] ) && ! empty( $tstmnls_options['recaptcha_cb'] ) && ! gglcptch_is_hidden_for_role() ) {
			$tstmnsl_for_recaptcha = get_option( 'gglcptch_options' );
			$tstmnsl_for_recaptcha = $tstmnsl_for_recaptcha['testimonials'];
		}

		if ( isset( $_POST['tstmnls_review_form'] ) && wp_verify_nonce( $_POST['tstmnls_review_form'], 'tstmnls_review_form' ) ) {

			if ( function_exists( 'gglcptch_check' ) ) {
				$gglcptch_check = gglcptch_check();
			}

			if ( ! empty( $tstmnsl_for_recaptcha ) ) {
				$check = $gglcptch_check['response'];
			} else {
				$check = true;
			}

			$form_title = stripslashes( sanitize_text_field( trim( $_POST['tstmnls_testimonial_title'] ) ) );
			$form_content = stripslashes( sanitize_text_field( trim( $_POST['tstmnls_testimonial_comment'] ) ) );
			$rating = isset( $_POST['rtng_rating'] ) ? $_POST['rtng_rating'] : false;

			if ( true === $check && ! empty( $form_title ) && ! empty( $form_content ) ) {

				if ( false !== $rating ) {
					$stars = isset( $rtng_options['quantity_star'] ) ? $rtng_options['quantity_star'] : 5;
					foreach ( (array)$rating as $key => $rate ) {
						$rating[ $key ] = intval( $rate ) * 100 / $stars;
					}
				}

				$post_args = array(
					'post_type'		=> 'bws-testimonial',
					'post_title'	=> $form_title,
					'post_content'	=> $form_content,
					'meta_input'	=> array(
						'testimonials_info' => array(
							'author' => get_the_author_meta( 'display_name', $_POST['tstmnls_author_id'] ),
							'rating' => maybe_serialize( $rating ),
						)
					),
				);

				if ( ! empty( $tstmnls_options['auto_publication'] ) ) {
					$post_args['post_status'] = 'publish';
				} else {
					$post_args['post_status'] = 'draft';
				}

				$last_id = wp_insert_post( $post_args );

				if ( false !== $rating ) {
					$args = array(
						'post_id'			=> $_POST['tstmnls_post_id'],
						'object_id'			=> $last_id,
						'rating'			=> $rating,
						'type'				=> 'comment'
					);
					rtng_add_user_rating( $args );
				}

				unset(
					$_POST['tstmnls_testimonial_title'],
					$_POST['tstmnls_testimonial_comment'],
					$_POST['rtng_rating'],
					$_POST['rtng_show_title'],
					$_POST['tstmnls_GDPR'],
					$_POST['g-recaptcha-response'],
					$_POST['tstmnls_review_form'],
					$_POST['tstmnls_post_id'],
					$_POST['_wp_http_referer']
				);
				$form_title = $form_content = '';
			} elseif ( false === $check && ! empty( $tstmnls_options['recaptcha_cb'] ) && ! empty( $tstmnsl_for_recaptcha ) ) {
				$captcha_error_messages = gglcptch_get_message();
				$captcha_error_messages = '<p class="tstmnls_error">' . $captcha_error_messages . '</p>';
				$form_error = '<p class="tstmnls_error_form">' . __( 'Please make corrections below and try again.', 'bws-testimonials' ) . '</p>';
			}
		}
		if ( 'init' != current_filter() ) {
			if ( ! is_user_logged_in() && 'logged' == $tstmnls_options['permissions'] ) { ?>
				<div class="tstmnls_form_div">
					<p>
						<?php _e( 'This form is available only for logged in users. Please', 'bws-testimonials' ); ?>
						<a href="<?php echo wp_login_url(); ?>"> <?php _e( 'log in', 'bws-testimonials' ); ?></a>
						<?php _e( 'or', 'bws-testimonials' ); ?>
						<a href="<?php echo wp_registration_url(); ?>"> <?php _e( 'register', 'bws-testimonials' ); ?></a>
						<?php _e( 'on our site', 'bws-testimonials' ); ?>
					</p>
				</div>
			<?php } else {
				if ( isset( $_GET['message'] ) ) {
					if ( ! empty( $tstmnls_options['auto_publication'] ) ) {
						$subm_result = '<p class="tstmnls_result">' . __( 'Your review has been published!', 'bws-testimonials' ) . '</p>';
					} else {
						$subm_result = '<p class="tstmnls_result">' . __( 'Your review has been sent to administration!', 'bws-testimonials' ) . '</p>';
					}
				} elseif ( $status_rating['enabled'] && $tstmnls_options['rating_cb'] ) {
					$alredy_rated = $wpdb->get_var(
						"SELECT COUNT( * )
						FROM `" . $wpdb->prefix . "bws_rating`
						WHERE `post_id` = '" . $post->ID . "'
							AND `user_ip` = '" . rtng_get_user_ip() . "'
						LIMIT 1"
					);
					if ( '0' !== $alredy_rated ) {
						if ( isset( $rtng_options['already_rated_message'] ) ) {
							$subm_result = '<p class="tstmnls_result">' . $rtng_options['already_rated_message'] . '</p>';
						} else {
							$subm_result = '<p class="tstmnls_result">' . __( 'You have alredy published a review.', 'bws-testimonials' ) . '</p>';
						}
					}
				} ?>
				<div class="tstmnls_review_form">
					<?php if ( empty( $subm_result ) ) { ?>
						<form method="post" name="tstmnls_review_form" id="tstmnls_review_form">
							<?php echo $form_error; ?>
							<div class="tstmnls_field_form">
								<label for="tstmnls_testimonial_title"><?php _e( 'Review title:', 'bws-testimonials' ); ?>
									<span class="tstmnls_required_symbol"> * </span>
								</label>
								<input type="text" required name="tstmnls_testimonial_title" id="tstmnls_testimonial_title" value="<?php echo $form_title; ?>" >
							</div>
							<div class="tstmnls_field_form">
								<label for="tstmnls_testimonial_title"><?php _e( 'Review text:', 'bws-testimonials' ); ?>
									<span class="tstmnls_required_symbol"> * </span>
								</label>
								<textarea name="tstmnls_testimonial_comment" required class="tstmnls_testimonial_comment" rows="8" cols="80"><?php echo $form_content; ?></textarea>
							</div>

							<?php if ( $status_rating['enabled'] && $tstmnls_options['rating_cb'] ) { ?>
								<div class="tstmnls_review_rating">
									<?php rtng_show_rating_form( array( 'type' => 'comment', 'testimonial' => true ) ); ?>
								</div>
								<br />
							<?php }
							if ( ! empty( $tstmnls_options['gdpr'] ) ) { ?>
								<div class="tstmnls_field_form">
									<p class="tstmnls-GDPR-wrap">
										<label>
											<input id="tstmnls-GDPR-checkbox" required type="checkbox" name="tstmnls_GDPR"/>
											<?php echo tstmnls_patterns_testimonials(); ?>
										</label>
									</p>
								</div>
							<?php
							}
							if ( ! empty( $tstmnsl_for_recaptcha ) ) {
								echo $captcha_error_messages;
								echo apply_filters( 'tstmnls_display_recaptcha', '', 'testimonials' );
							}
							wp_nonce_field( 'tstmnls_review_form', 'tstmnls_review_form', true, true ); ?>
							<input type="submit" value="<?php _e( 'Publish', 'bws-testimonials' ); ?>">
							<input type="hidden" name="redirect_once" value="1" />
							<input type="hidden" name="tstmnls_post_id" value="<?php echo $post->ID; ?>" />
							<input type="hidden" name="tstmnls_author_id" value="<?php echo get_the_author_meta( 'ID' ); ?>" />
						</form>
					<?php } else {
						echo $subm_result;
					} ?>
				</div>
			<?php }
		}
	}
}

/**
 * Display Featured Post
 * @return echo Featured Post block
 */
if ( ! function_exists( 'tstmnls_show_testimonials' ) ) {
	function tstmnls_show_testimonials( $count = false ) {
		echo tstmnls_show_testimonials_shortcode( array( 'count' => $count ) );
	}
}

if ( ! function_exists('tstmnls_show_testimonials_shortcode_slider' ) ) {
   function tstmnls_show_testimonials_shortcode_slider( $attr ) {
       global $tstmnls_options, $wp_query, $post;
       $old_query = $wp_query;

       if ( empty( $tstmnls_options ) ) {
           tstmnls_register_settings();
       }

       $shortcode_attributes = shortcode_atts( array( 'count' => '' ), $attr );

       if ( empty( $shortcode_attributes['count'] ) ) {
           $shortcode_attributes['count'] = $tstmnls_options['count'];
       }

       $query_args = array(
           'post_type'			=> 'bws-testimonial',
           'post_status'		=> 'publish',
           'posts_per_page'	    => $shortcode_attributes['count'],
           'orderby'			=> $tstmnls_options['order_by'],
           'order'				=> $tstmnls_options['order']
       );
       $id = rand();
       $content = '<div class="bws-testimonials">';
       $content .= '<div class="owl-carousel bws-carousel-'. $id .' owl-theme" >';
       $tstmnl_query = new WP_Query( $query_args );

       while ( $tstmnl_query->have_posts() ) {
           $tstmnl_query->the_post();
	       if ( empty( get_post_meta( $post->ID, 'testimonials_info', true ) ) )
		       update_post_meta( $post->ID, 'testimonials_info', get_post_meta( $post->ID, '_testimonials_info', true ) );
	       $testimonials_info = get_post_meta( $post->ID, 'testimonials_info', true );
           $testimonial_thumbnail = has_post_thumbnail() ? '<div class="tstmnls-thumbnail">' . get_the_post_thumbnail( $post->ID, $tstmnls_options['image_size_photo'] ) . '</div>' : '';
           if ( 'tstmnls_custom_size' == $tstmnls_options['image_size_photo'] ) {
               $width = $tstmnls_options['custom_size_px']['tstmnls_custom_size'][0];
               $height = $tstmnls_options['custom_size_px']['tstmnls_custom_size'][1];
           } else {
               $width = get_option( $tstmnls_options['image_size_photo'] . '_size_w' );
               $height = get_option( $tstmnls_options['image_size_photo'] . '_size_h' );
           }
           if ( $width || $height ) {
               $inline_style = 'style="' . ( $width ? 'width:' . $width . 'px;' : '' ) . ( $height ? 'height:' . $height . 'px;' : '' ) . '"';
               $testimonial_thumbnail = preg_replace( '/<img /', '<img ' . $inline_style, $testimonial_thumbnail );
           }
           if ( $width ) {
               $testimonial_thumbnail = preg_replace( '/width="[0-9]*"/', 'width="' . $width . '"', $testimonial_thumbnail );
           }
           if ( $height ) {
               $testimonial_thumbnail = preg_replace( '/height="[0-9]*"/', 'height="' . $height . '"', $testimonial_thumbnail );
           }
           $content .= '<div class="item"><div class="testimonials_quote">
							<blockquote>' .
               $testimonial_thumbnail;

           $testimonial_content = get_the_content();
           /* insteed 'the_content' filter we use its functions to compability with social buttons */
           /* Hack to get the [embed] shortcode to run before wpautop() */
           require_once( ABSPATH . WPINC . '/class-wp-embed.php' );
           $wp_embed = new WP_Embed();
           $testimonial_content = $wp_embed->run_shortcode( $testimonial_content );
           $testimonial_content = $wp_embed->autoembed( $testimonial_content );
           $testimonial_content = wptexturize( $testimonial_content );
           $testimonial_content = convert_smilies( $testimonial_content );
           $testimonial_content = wpautop( $testimonial_content );
           $testimonial_content = shortcode_unautop( $testimonial_content );
           if ( function_exists( 'wp_filter_content_tags' ) ) {
               $testimonial_content = wp_filter_content_tags( $testimonial_content );
           }
           $testimonial_content = do_shortcode( $testimonial_content ); /* AFTER wpautop() */
           $testimonial_content = str_replace( ']]>', ']]&gt;', $testimonial_content );

           $content .= $testimonial_content;
           $content .= '</blockquote>';
           if ( is_rtl() ) {
               $content .= '<div class="rtl_testimonial_quote_footer">';
           } else {
               $content .= '<div class="testimonial_quote_footer">';
		   }
		   $company_name = isset( $testimonials_info['company_name'] ) ? $testimonials_info['company_name'] : '';
           $tstmnls_author_info = isset( $testimonials_info['author'] ) ? $testimonials_info['author'] : '';
           $content .= '<div class="testimonial_quote_author">' . $tstmnls_author_info . '</div><span>' . $company_name . '</span></div></div></div>';
       }
       ob_start();

           $script_bws_carousel = "
               ( function($) {
                   $(document).ready(function(){
    
                       var owl =  $( '.bws-carousel-" . $id . "' );
                       owl.owlCarousel( {
                           loop:" . ( ! empty( $attr['instance'] ) ? $attr['instance']['loop'] : $tstmnls_options['loop'] ) . ",
                           nav:" . ( ! empty( $attr['instance'] ) ? $attr['instance']['nav'] : $tstmnls_options['nav'] ) . ",
                           dots:" . ( ! empty( $attr['instance'] ) ? $attr['instance']['dots'] : $tstmnls_options['dots'] ) . ",
                           autoHeight:" . ( ! empty( $attr['instance'] ) ? $attr['instance']['auto_height'] : $tstmnls_options['auto_height'] ) . ",
                           autoplayTimeout:" . ( ! empty( $attr['instance'] ) ? $attr['instance']['autoplay_timeout']*1000 : $tstmnls_options['autoplay_timeout'] ) . ",
                           autoplay:" . ( ! empty( $attr['instance'] ) ? $attr['instance']['autoplay'] : $tstmnls_options['autoplay'] ) . ",
                           margin:10,
                           navText:[
                               \"<i class='dashicons dashicons-arrow-left-alt2'></i>\",
                               \"<i class='dashicons dashicons-arrow-right-alt2'></i>\"
                           ],
                           responsive:{
                               0:{
                                   items: 1
                               },
                               600:{
                                   items:" . ( $tstmnls_options['items_in_slide'] > 2 ? 2 : $tstmnls_options['items_in_slide'] ) . "
                               },
                               960:{
                                   items:" . ( $tstmnls_options['items_in_slide'] > 3 ? 3 : $tstmnls_options['items_in_slide'] ) . "
                               },
                               1200:{
                                   items:" . ( $tstmnls_options['items_in_slide'] ) . "
                               }
                           }
                       });
                       owl.on('mousewheel', '.owl-stage', function (e) {
                           if (e.deltaY>0) {
                               owl.trigger('next.owl');
                           } else {
                               owl.trigger('prev.owl');
                           }
                           e.preventDefault();
                       });
                   })
               })  (jQuery);";
           wp_register_script( 'tstmnls_bws-carousel', '' );
           wp_enqueue_script( 'tstmnls_bws-carousel' );
           wp_add_inline_script( 'tstmnls_bws-carousel', sprintf( $script_bws_carousel ) );

       wp_reset_postdata();
       wp_reset_query();
       $wp_query = $old_query;
       $content .= '</div><!-- .owl-carousel owl-theme -->';
       $content .= '</div><!-- .bws-testimonials -->';
       echo $content;
       $get_content = ob_get_clean();
       return $get_content;
   }
}

if ( ! function_exists( 'tstmnls_show_testimonials_shortcode' ) ) {
	function tstmnls_show_testimonials_shortcode( $attr ) {
		global $tstmnls_options, $wp_query, $post;
		$old_query = $wp_query;

		if ( empty( $tstmnls_options ) ) {
			tstmnls_register_settings();
		}

		$shortcode_attributes = shortcode_atts( array( 'count' => '' ), $attr );

		if ( empty( $shortcode_attributes['count'] ) ) {
			$shortcode_attributes['count'] = $tstmnls_options['count'];
		}

		$query_args = array(
			'post_type'			=> 'bws-testimonial',
			'post_status'		=> 'publish',
			'posts_per_page'	=> $shortcode_attributes['count'],
			'orderby'			=> $tstmnls_options['order_by'],
			'order'				=> $tstmnls_options['order']
		);

		$content = '<div class="bws-testimonials">';
		$tstmnl_query = new WP_Query( $query_args );

		while ( $tstmnl_query->have_posts() ) {
			$tstmnl_query->the_post();
			if ( empty( get_post_meta( $post->ID, 'testimonials_info', true ) ) )
				update_post_meta( $post->ID, 'testimonials_info', get_post_meta( $post->ID, '_testimonials_info', true ) );
			$testimonials_info = get_post_meta( $post->ID, 'testimonials_info', true );
            $testimonial_thumbnail = has_post_thumbnail() ? '<div class="tstmnls-thumbnail">' . get_the_post_thumbnail( $post->ID, $tstmnls_options['image_size_photo'] ) . '</div>' : '';
            if ( 'tstmnls_custom_size' == $tstmnls_options['image_size_photo'] ) {
                $width = $tstmnls_options['custom_size_px']['tstmnls_custom_size'][0];
                $height = $tstmnls_options['custom_size_px']['tstmnls_custom_size'][1];
            } else {
                $width = get_option( $tstmnls_options['image_size_photo'] . '_size_w' );
                $height = get_option( $tstmnls_options['image_size_photo'] . '_size_h' );
            }
            if ( $width || $height ) {
                $inline_style = 'style="' . ( $width ? 'width:' . $width . 'px;' : '' ) . ( $height ? 'height:' . $height . 'px;' : '' ) . '"';
                $testimonial_thumbnail = preg_replace( '/<img /', '<img ' . $inline_style, $testimonial_thumbnail );
            }
            if ( $width ) {
                $testimonial_thumbnail = preg_replace( '/width="[0-9]*"/', 'width="' . $width . '"', $testimonial_thumbnail );
            }
            if ( $height ) {
                $testimonial_thumbnail = preg_replace( '/height="[0-9]*"/', 'height="' . $height . '"', $testimonial_thumbnail );
            }

            $content .= '<div class="item"><div class="testimonials_quote">
							<blockquote>' .
								$testimonial_thumbnail;

			$testimonial_content = get_the_content();
			/* insteed 'the_content' filter we use its functions to compability with social buttons */
			/* Hack to get the [embed] shortcode to run before wpautop() */
			require_once( ABSPATH . WPINC . '/class-wp-embed.php' );
			$wp_embed = new WP_Embed();
			$testimonial_content = $wp_embed->run_shortcode( $testimonial_content );
			$testimonial_content = $wp_embed->autoembed( $testimonial_content );
			$testimonial_content = wptexturize( $testimonial_content );
			$testimonial_content = convert_smilies( $testimonial_content );
			$testimonial_content = wpautop( $testimonial_content );
			$testimonial_content = shortcode_unautop( $testimonial_content );
			if ( function_exists( 'wp_filter_content_tags' ) ) {
				$testimonial_content = wp_filter_content_tags( $testimonial_content );
			}
			$testimonial_content = do_shortcode( $testimonial_content ); /* AFTER wpautop() */
			$testimonial_content = str_replace( ']]>', ']]&gt;', $testimonial_content );

			$content .= $testimonial_content;
			$content .= '</blockquote>';
			if ( is_rtl() ) {
				$content .= '<div class="rtl_testimonial_quote_footer">';
			} else {
				$content .= '<div class="testimonial_quote_footer">';
			}
            $company_name = isset( $testimonials_info['company_name'] ) ? $testimonials_info['company_name'] : '';
            $tstmnls_author_info = isset( $testimonials_info['author'] ) ? $testimonials_info['author'] : '';
			$content .=	'<div class="testimonial_quote_author">' . $tstmnls_author_info . '</div><span>' . $company_name . '</span></div></div></div>';
		}
		wp_reset_postdata();
		wp_reset_query();
		$wp_query = $old_query;
		$content .= '</div><!-- .bws-testimonials -->';
		return $content;
	}
}

/**
 * Add recaptcha support
 */
if ( ! function_exists( 'tstmnls_add_recaptcha_forms' ) ) {
	function tstmnls_add_recaptcha_forms( $forms ) {
		$forms['testimonials'] = array( "form_name" => __( 'Testimonials Form', 'bws-testimonials' ) );
		return $forms;
	}
}

/**
 * Display testimonials form
 */
if ( ! function_exists( 'tstmnls_show_testimonials_form' ) ) {
	function tstmnls_show_testimonials_form () {
		global $tstmnls_options;
		$content = $subm_result = $form_error = $captcha_error_messages = '';
		$form_author = $form_company = $form_title = $form_content = '';
		
		if ( empty( $tstmnls_options ) )
			tstmnls_register_settings();		

		$status = tstmnls_get_related_plugin_status( 'recaptcha' );

		/* Check if plugin Google Captcha Pro is activated */
		if ( ( 'free' == $status['active'] || 'pro' == $status['active'] || 'plus' == $status['active'] ) && ! empty( $tstmnls_options['recaptcha_cb'] ) && ! gglcptch_is_hidden_for_role() ) {
			$tstmnsl_for_recaptcha = get_option( 'gglcptch_options' );
			$tstmnsl_for_recaptcha = $tstmnsl_for_recaptcha['testimonials'];
		}

		if (
			isset( $_POST['tstmnls_submit_testimonial'] ) &&
			isset( $_POST['tstmnls_field'] ) &&
			wp_verify_nonce( $_POST['tstmnls_field'], 'tstmnls_action' )
		) {

			if ( function_exists( 'gglcptch_check' ) ) {
				$gglcptch_check = gglcptch_check();
			}

			if ( ! empty( $tstmnsl_for_recaptcha ) ) {
				$check = $gglcptch_check['response'];
			} else {
				$check = true;
			}

			$form_author = stripslashes( esc_html( trim( $_POST['tstmnls_testimonial_author'] ) ) );
			$form_company = stripslashes( esc_html( trim( $_POST['tstmnls_testimonial_company_name'] ) ) );
			$form_title = stripslashes( esc_html( trim( $_POST['tstmnls_testimonial_title'] ) ) );
			$form_content = stripslashes( esc_html( trim( $_POST['tstmnls_testimonial_comment'] ) ) );

			if ( true === $check &&
				! empty( $form_author ) &&
				! empty( $form_title ) &&
				! empty( $form_content ) ) {
				if ( ( 'all' == $tstmnls_options['permissions'] ) ){
					$admin_email = get_option( 'admin_email' );
					$admin_user = get_user_by( 'email', $admin_email );
					$post_author = $admin_user->ID;
				} elseif ( 'logged' == $tstmnls_options['permissions'] ) {
					$post_author = get_current_user_id();
				}

				$post = array(
					'post_type'	=> 'bws-testimonial',
					'post_title'	=> $form_title,
					'post_content'	=> $form_content,
					'post_author'	=> $post_author,
					'meta_input'	=> array(
											'testimonials_info'	=> array(
											'author'				=> $form_author,
											'company_name'			=> $form_company
										)
					),
				);

				if ( ! empty( $tstmnls_options['auto_publication'] ) ) {
					$post['post_status'] = 'publish';
				} else {
					$post['post_status'] = 'draft';
				}

				$lastid = wp_insert_post( $post );

				unset( $_POST["tstmnls_testimonial_author"],
					$_POST["tstmnls_testimonial_company_name"],
					$_POST["tstmnls_testimonial_title"],
					$_POST["tstmnls_testimonial_comment"],
					$_POST["tstmnls_submit_testimonial"],
					$_POST["tstmnls_GDPR"],
					$_POST["g-recaptcha-response"],
					$_POST["tstmnls_field"],
					$_POST["_wp_http_referer"]
				);
				
				$form_author =  $form_company = $form_title = $form_content = '';

				/* the variable is used in tstmnls_init function */
				$_POST["redirect_once"] = TRUE;

			} elseif ( false === $check && ! empty( $tstmnls_options['recaptcha_cb'] ) && ! empty( $tstmnsl_for_recaptcha ) ) {
				$captcha_error_messages = gglcptch_get_message();
				$captcha_error_messages = '<p class="tstmnls_error">' . $captcha_error_messages . '</p>';
				$form_error = '<p class="tstmnls_error_form">' . __( 'Please make corrections below and try again.', 'bws-testimonials' ) . '</p>';
			}
		}
		if ( ! is_user_logged_in() && 'logged' == $tstmnls_options["permissions"] ) {
			$content =
			'<div class = "tstmnls_form_div">
				<p>' . __( 'This form is available only for logged in users. Please', 'bws-testimonials' ) . '<a href="' . wp_login_url() . '"> ' .
				__( 'log in', 'bws-testimonials' ) . '</a> ' . __( 'or', 'bws-testimonials' ) .
				'<a href="' . wp_registration_url() . '"> ' . __( 'register', 'bws-testimonials' ) . '</a> ' . __( 'on our site', 'bws-testimonials' ) . '</p>
			</div>';
		} else {
			if ( isset( $_GET['message'] ) ) {
				if ( ! empty( $tstmnls_options['auto_publication'] ) ) {
					$subm_result = '<p class="tstmnls_result">' . __( 'Your testimonial has been published!', 'bws-testimonials' ) . '</p>';
				} else {
					$subm_result = '<p class="tstmnls_result">' . __( 'Your testimonial has been sent to administration!', 'bws-testimonials' ) . '</p>';
				}
			}
			$content =
			'<div class = "tstmnls_form_div">' . $subm_result . '
				<form method="post" name="tstmnls_form_name" id="tstmnls_form_name" action="">
					<h2>' . __( 'Submit Your Testimonial', 'bws-testimonials' ) . '</h2>
					' . $form_error . '
					<div class="tstmnls_field_form">
						<label for="tstmnls_testimonial_author">' . __( 'Name:', 'bws-testimonials' ) . '
							<span class="tstmnls_required_symbol"> * </span>
						</label>
						<input type="text" required name="tstmnls_testimonial_author" id="tstmnls_testimonial_author" value="'. $form_author . '" >
					</div>
					<div class="tstmnls_field_form">
						<label for="tstmnls_testimonial_company_name">'. __( 'Company:', 'bws-testimonials' ) .'</label>
						<input type="text" name="tstmnls_testimonial_company_name" id="tstmnls_testimonial_company_name" value="'. $form_company .'" >
					</div>
					<div class="tstmnls_field_form">
						<label for="tstmnls_testimonial_title">' . __( 'Title:', 'bws-testimonials' ) . '
							<span class="tstmnls_required_symbol"> * </span>
						</label>
						<input type="text" required name="tstmnls_testimonial_title" id="tstmnls_testimonial_title" value="' . $form_title . '" >
					</div>
					<div class="tstmnls_field_form">
						<label for="tstmnls_testimonial_title">' . __( 'Testimonial:', 'bws-testimonials' ) . '
							<span class="tstmnls_required_symbol"> * </span>
						</label>
						<textarea name="tstmnls_testimonial_comment" required class="tstmnls_testimonial_comment" rows="8" cols="80">' . $form_content . '</textarea>
						<input type="hidden" name="tstmnls_submit_testimonial" id="tstmnls_submit_testimonial" value="1">
					</div>';
					if( ! empty( $tstmnls_options['gdpr'] ) ) {
						$content .= '<div class="tstmnls_field_form">
							<p class="tstmnls-GDPR-wrap">
								<label>
									<input id="tstmnls-GDPR-checkbox" required type="checkbox" name="tstmnls_GDPR" style="margin-right: 5px;"/>'
									. tstmnls_patterns_testimonials();
								$content .= '</label>
							</p>
						</div>';
					}
					if ( ! empty( $tstmnsl_for_recaptcha ) ) {
						$content .= $captcha_error_messages;
						$content .= apply_filters( 'tstmnls_display_recaptcha', '', 'testimonials' );
					}
					$content .= wp_nonce_field( 'tstmnls_action', 'tstmnls_field', true, false ) . '
					<input type="submit" value="' . __( 'Submit', 'bws-testimonials' ) . '">
				</form>
			</div>';
		}
		return $content;
	}
}

/**
 * Add styles for admin page and widget
 */
if ( ! function_exists ( 'tstmnls_admin_head' ) ) {
	function tstmnls_admin_head() {
		global $tstmnls_plugin_info;
		wp_enqueue_style( 'tstmnls_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
		wp_enqueue_script( 'tstmnls_script', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery' ), $tstmnls_plugin_info['Version'] );

		if ( isset( $_GET['page'] ) && "testimonials.php" == $_GET['page'] ) {
			bws_enqueue_settings_scripts();
			bws_plugins_include_codemirror();
		}
	}
}

if ( ! function_exists ( 'tstmnls_wp_head' ) ) {
	function tstmnls_wp_head() {
		wp_enqueue_style( 'tstmnls_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
	}
}

/**
 * Function to handle action links
 */
if ( ! function_exists( 'tstmnls_plugin_action_links' ) ) {
	function tstmnls_plugin_action_links( $links, $file ) {
		if ( ! is_network_admin() ) {
			/* Static so we don't call plugin_basename on every plugin row. */
			static $this_plugin;
			if ( ! $this_plugin )
				$this_plugin = plugin_basename( __FILE__ );

			if ( $file == $this_plugin ) {
				$settings_link = '<a href="admin.php?page=testimonials.php">' . __( 'Settings', 'bws-testimonials' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
}

if ( ! function_exists ( 'tstmnls_register_plugin_links' ) ) {
	function tstmnls_register_plugin_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() )
				$links[] = '<a href="admin.php?page=testimonials.php">' . __( 'Settings', 'bws-testimonials' ) . '</a>';
				$links[] = '<a href="https://support.bestwebsoft.com/hc/en-us/sections/200897195" target="_blank">' . __( 'FAQ', 'bws-testimonials' ) . '</a>';
				$links[] = '<a href="https://support.bestwebsoft.com">' . __( 'Support', 'bws-testimonials' ) . '</a>';
		}
		return $links;
	}
}

/* add admin notices */
if ( ! function_exists ( 'tstmnls_admin_notices' ) ) {
	function tstmnls_admin_notices() {
		global $hook_suffix, $tstmnls_plugin_info;
		if ( 'plugins.php' == $hook_suffix && ! is_network_admin() ) {
			bws_plugin_banner_to_settings( $tstmnls_plugin_info, 'tstmnls_options', 'bws-testimonials', 'admin.php?page=testimonials.php', 'post-new.php?post_type=bws-testimonial' );
		}

		if ( isset( $_REQUEST['page'] ) && 'testimonials.php' == $_REQUEST['page'] ) {
			bws_plugin_suggest_feature_banner( $tstmnls_plugin_info, 'tstmnls_options', 'bws-testimonials' );
		}
	}
}

if ( ! function_exists ( 'tstmnls_register_widgets' ) ) {
	function tstmnls_register_widgets() {
		register_widget( 'Testimonials' );
        register_widget( 'Testimonials_Slider' );
	}
}

/* add help tab */
if ( ! function_exists( 'tstmnls_add_tabs' ) ) {
	function tstmnls_add_tabs() {
		$screen = get_current_screen();
		if ( ( ! empty( $screen->post_type ) && 'bws-testimonial' == $screen->post_type ) ||
			( isset( $_GET['page'] ) && 'testimonials.php' == $_GET['page'] ) ) {
			$args = array(
				'id'			=> 'tstmnls',
				'section'		=> '200897195'
			);
			bws_help_tab( $screen, $args );
		}
	}
}

/* add shortcode content */
if ( ! function_exists( 'tstmnls_shortcode_button_content' ) ) {
	function tstmnls_shortcode_button_content( $content ) { ?>
		<div id="tstmnls" style="display:none;">
			<fieldset>
				<label>
					<input type="radio" name="tstmnls_select" value="bws_testimonials" checked="checked">
					<span><?php _e( 'Add testimonials to your page or post', 'bws-testimonials' ); ?></span>
				</label>
				<br>
				<label>
					<input type="radio" name="tstmnls_select" value="bws_testimonials_form">
					<span><?php _e( 'Testimonials Form', 'bws-testimonials' ); ?></span>
				</label>
				<br>
			    <label>
                    <input type="radio" name="tstmnls_select" value="bws_testimonials_slider">
                    <span><?php _e( 'Slider Testimonials', 'bws-testimonials' ); ?></span>
                </label>
                <br>
			    <label>
                    <input type="radio" name="tstmnls_select" value="bws_testimonials_reviews">
                    <span><?php _e( 'Testimonials Reviews', 'bws-testimonials' ); ?></span>
                </label>
                <br>
			    <label>
                    <input type="radio" name="tstmnls_select" value="bws_testimonials_review_form">
                    <span><?php _e( 'Testimonials Review Form', 'bws-testimonials' ); ?></span>
                </label>
            </fieldset>
			<input class="bws_default_shortcode" type="hidden" name="default" value="[bws_testimonials]" />
            <?php $tstmnls_shortcode_script = "
				function tstmnls_shortcode_init() {
					( function( $ ) {
						$( '.mce-reset input[name=\"tstmnls_select\"]' ).on( 'change', function() {
							var shortcode = $( '.mce-reset input[name=\"tstmnls_select\"]:checked' ).val();
							$( '.mce-reset #bws_shortcode_display' ).text( '[' + shortcode + ']' );
						} );
					} )( jQuery );
				}";
            wp_register_script( 'tstmnls_shortcode_button_script', '' );
            wp_enqueue_script( 'tstmnls_shortcode_button_script' );
            wp_add_inline_script( 'tstmnls_shortcode_button_script', sprintf( $tstmnls_shortcode_script ) ); ?>
			<div class="clear"></div>
		</div>
	<?php }
}

/* Testimonials data objects. Takes array of id, single id or 'all' */
if ( ! function_exists( 'tstmnls_get_testimonials_data' ) ) {
	function tstmnls_get_testimonials_data( $testimonial_id ) {
		$testimonials_posts = $testimonials_objects = $testimonial_post_meta_key = array();
		$tstmnls_post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'bws-testimonial';

		if ( 'all' == $testimonial_id || is_array( $testimonial_id ) ) {

			/* prepare args for get_posts */
			if ( is_array( $testimonial_id ) && ! empty( $testimonial_id ) ) {
				$testimonial_id_list = $testimonial_id;
				$args = array( 'post_type' => $tstmnls_post_type, 'include' => $testimonial_id_list );
			} else {
				$args = array( 'post_type' => $tstmnls_post_type );
			}

			$testimonials_posts = get_posts( $args );

		} else if ( is_int( $testimonial_id ) ) {

			$testimonial_int_id = intval( $testimonial_id );
			$testimonials_posts[] = get_post( $testimonial_int_id );

		}

		/* return false if there are no records */
		if ( ! $testimonials_posts ) {
			return false;
		}

		foreach ( (array)$testimonials_posts as $testimonials_post ) {

			/* add gallery data to resulting array from wp_posts */
			$testimonials_objects[ $testimonials_post->ID ]['testimonial_wp_post'] = $testimonials_post;

            $testimonial_post_meta = get_post_meta( $testimonials_post->ID, '', true );
            foreach ( $testimonial_post_meta as $key => $value ) {
                $testimonial_post_meta_item = get_post_meta( $testimonials_post->ID, $key, true );
                $testimonial_post_meta_key[$key] = $testimonial_post_meta_item;
            }
			$testimonials_objects[ $testimonials_post->ID ]['testimonial_post_meta'] = $testimonial_post_meta_key;

		}

		return $testimonials_objects;
	}
}

/**
 * Delete plugin options
 */
if ( ! function_exists( 'tstmnls_plugin_uninstall' ) ) {
	function tstmnls_plugin_uninstall() {
		global $wpdb;
		/* Delete options */
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$old_blog = $wpdb->blogid;
			/* Get all blog ids */
			$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				delete_option( 'tstmnls_options' );
				delete_option( 'widget_tstmnls_testimonails_widget' );
			}
			switch_to_blog( $old_blog );
		} else {
			delete_option( 'tstmnls_options' );
			delete_option( 'widget_tstmnls_testimonails_widget' );
		}

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

if ( ! function_exists( 'tstmnls_register_scripts' ) ) {
    function tstmnls_register_scripts() {
        /* Owl carousel style */
        wp_enqueue_style( 'owl.carousel.css', plugins_url( '/css/owl.carousel.css', __FILE__ ) );
        wp_enqueue_style( 'owl.theme.default.css', plugins_url( '/css/owl.theme.default.css', __FILE__ ) );
        /* Include dashicons */
        wp_enqueue_style( 'dashicons' );
        /* Include jquery */
        wp_enqueue_script( 'jquery' );
        /* Slider script */
        wp_enqueue_script( 'owl.carousel.js', plugins_url( '/js/owl.carousel.js', __FILE__ ) );
        /* Frontend script */
		wp_enqueue_script( 'tstmnls_front_script', plugins_url( 'js/script.js', __FILE__ ) );

		wp_localize_script( 'tstmnls_front_script', 'params', 
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' )
			)
		);  
    }
}

/* Plugin uninstall function */
register_activation_hook( __FILE__, 'tstmnls_plugin_activate' );

add_action( 'admin_menu', 'tstmnls_admin_menu' );
add_action( 'init', 'tstmnls_init' );
add_action( 'admin_init', 'tstmnls_admin_init' );
add_action( 'widgets_init', 'tstmnls_register_widgets' );
add_action( 'plugins_loaded', 'tstmnls_plugins_loaded' );

add_action( 'wp_enqueue_scripts', 'tstmnls_register_scripts' );

add_action( 'save_post', 'tstmnls_save_postdata' );
add_filter( 'post_row_actions', 'tstmnls_modify_list_row_actions', 10, 2 );
add_action( 'before_delete_post', 'tstmnls_delete_review' );
add_filter( 'content_save_pre', 'tstmnls_content_save_pre', 10, 1 );
/* Display Featured Post */
add_action( 'tstmnls_show_testimonials_slider', 'tstmnls_show_testimonials_slider' );
add_action( 'tstmnls_show_testimonials', 'tstmnls_show_testimonials' );
/* Review + rating */
add_action( 'tstmnls_show_reviews', 'tstmnls_reviews' );
add_action( 'tstmnls_show_review_form', 'tstmnls_review_form' );

add_action( 'wp_ajax_load_reviews', 'tstmnls_load_reviews' );
add_action( 'wp_ajax_nopriv_load_reviews', 'tstmnls_load_reviews' );
/* custom filter for bws button in tinyMCE */
add_filter( 'bws_shortcode_button_content', 'tstmnls_shortcode_button_content' );

add_shortcode( 'bws_testimonials', 'tstmnls_show_testimonials_shortcode' );
add_shortcode( 'bws_testimonials_form', 'tstmnls_show_testimonials_form' );
add_shortcode( 'bws_testimonials_slider', 'tstmnls_show_testimonials_shortcode_slider' );
add_shortcode( 'bws_testimonials_reviews', 'tstmnls_reviews_shortcode' );
add_shortcode( 'bws_testimonials_review_form', 'tstmnls_review_form_shortcode' );
/* Add style for admin page */
add_action( 'admin_enqueue_scripts', 'tstmnls_admin_head' );

/* Add style for widget */
add_action( 'wp_enqueue_scripts', 'tstmnls_wp_head' );
/* Add admin notices */
add_action( 'admin_notices', 'tstmnls_admin_notices' );
/* Additional links on the plugin page */
add_filter( 'plugin_action_links', 'tstmnls_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'tstmnls_register_plugin_links', 10, 2 );