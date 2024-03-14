<?php

	// check if is_preview
	$is_preview   = false;
	$preview_mode = '';
if ( ! empty( $_GET['rafflepress-preview'] ) ) {
	$is_preview   = true;
	$preview_mode = 'live';
	if ( ! empty( $_GET['mode'] ) && $_GET['mode'] == 'live' ) {
		$preview_mode = 'live';
	}
	if ( ! empty( $_GET['mode'] ) && $_GET['mode'] == 'actions' ) {
		$preview_mode = 'actions';
	}
}
	//bail if user not logged in
if ( ! is_user_logged_in() && $is_preview ) {
	wp_die( 'You must be logged in to view this page.' );
}

// bail if user logged in, is preview and not admin
if ( is_user_logged_in() && $is_preview && ! current_user_can( apply_filters( 'rafflepress_manage_options_capability', 'manage_options' ) ) ) {
	wp_die( 'You must be logged in as an admin to view this page.' );
}

// check for iframe

if ( ! class_exists( 'rafflepress_lessc' ) ) {
	require_once RAFFLEPRESS_PLUGIN_PATH . 'app/vendor/rafflepress_lessc.inc.php';
}

	require_once RAFFLEPRESS_PLUGIN_PATH . 'resources/giveaway-templates/google-fonts.php';

	// check for facebook bot
	$is_bot = false;
if (
		strpos( $_SERVER['HTTP_USER_AGENT'], 'facebookexternalhit/' ) !== false ||
		strpos( $_SERVER['HTTP_USER_AGENT'], 'Facebot' ) !== false
	) {
	$is_bot = true;
}

if (
		strpos( $_SERVER['HTTP_USER_AGENT'], 'WhatsApp' ) !== false
	) {
	$is_bot = true;
}

	$actual_link = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";




	global $wpdb;

	// Get Giveaway
	$tablename  = $wpdb->prefix . 'rafflepress_giveaways';
	$tablename2 = $wpdb->prefix . 'rafflepress_entries';
	$sql        = "SELECT *,(SELECT count(id) FROM $tablename2 WHERE giveaway_id = %d and deleted_at IS NULL) as entries FROM $tablename WHERE id = %d and deleted_at IS NULL";
	$safe_sql   = $wpdb->prepare( $sql, $rafflepress_id, $rafflepress_id );
	$giveaway   = $wpdb->get_row( $safe_sql );

	//$token = get_option('rafflepress_token');

if ( empty( $giveaway ) ) {
	wp_die( __( 'No Giveaway Found', 'rafflepress' ) );
}


	// check for confirmation passed in
if ( ! empty( $_GET['confirm'] ) && ! empty( $_GET['id'] ) ) {
	$confirm = sanitize_text_field( $_GET['confirm'] );
	$id      = absint( $_GET['id'] );

	global $wpdb;

	$tablename  = $wpdb->prefix . 'rafflepress_contestants';
	$sql        = "SELECT * FROM $tablename WHERE token = %s AND giveaway_id = %d AND id = %d";
	$safe_sql   = $wpdb->prepare( $sql, $confirm, $rafflepress_id, $id );
	$contestant = $wpdb->get_row( $safe_sql );

	// confirm contestant
	if ( ! empty( $contestant ) ) {
		$tablename            = $wpdb->prefix . 'rafflepress_contestants';
		$contestant_confirmed = $wpdb->update(
			$tablename,
			array(
				'status' => 'confirmed',
			),
			array( 'id' => $contestant->id ),
			array(
				'%s',
			),
			array( '%d' )
		);

		if ( $contestant_confirmed ) {
			$msg = __( 'Your email has been confirmed.', 'rafflepress' );
			setcookie( 'rafflepress_flash_' . $rafflepress_id, urlencode( $msg ), strtotime( '+60 days' ), '/' );
			setcookie( 'rafflepress_hash_' . $rafflepress_id, urlencode( $contestant->id . '|' . $contestant->email ), strtotime( '+60 days' ), '/' );
			setcookie( 'rafflepress_email', $contestant->email, strtotime( '+60 days' ), '/' );
		}

		// confirm any refer a friend entries if email confirmation enabled
		$settings = json_decode( $giveaway->settings );
		if ( ! empty( $settings->enable_confirmation_email ) ) {

			$tablename = $wpdb->prefix . 'rafflepress_entries';
			$sql       = 'UPDATE ' . $tablename . ' SET deleted_at = NULL WHERE referrer_id = %d';
			$safe_sql  = $wpdb->prepare( $sql, $id );
			$result    = $wpdb->query( $safe_sql );
		}
	}

	nocache_headers();
	$url = $giveaway->parent_url;
	$url = esc_url($url);
	if ( ! empty( $giveaway->slug ) ) {
		$url = home_url() . '/' . $giveaway->slug;
	}
	if ( empty( $url ) ) {
		$url = home_url() . '?rafflepress_page=rafflepress_render&rafflepress_id=' . $giveaway->id;
	}
	header( "Location: $url" );
	exit;
}


	$settings = json_decode( $giveaway->settings );


if ( empty( $settings ) ) {
	exit;
}


	// Don't display if disabled
if ( empty( $giveaway->active ) && empty( $_GET['rafflepress-preview'] ) ) {
	exit;
}

	// Put up a note to admin if disabled
	// if (current_user_can( apply_filters('rafflepress_manage_options_capability', 'manage_options') ) && empty($giveaway->active)) {
	//     $disabled_msg = __('Giveaway is Disabled', 'rafflepress');
	// }



	// format show winner bool
if ( empty( $settings->show_winners ) ) {
	$show_winners = false;
} else {
	$show_winners = true;
}



	// Show Winners
	$winners = array();
if ( ! empty( $settings->show_winners ) ) {
	$tablename = $wpdb->prefix . 'rafflepress_contestants';
	$sql       = "SELECT email,fname,lname FROM $tablename WHERE giveaway_id = %d AND winner = 1";
	$safe_sql  = $wpdb->prepare( $sql, $giveaway->id );
	$winners   = $wpdb->get_results( $safe_sql );
	foreach ( $winners as $w ) {
		$w->gravatar = 'https://www.gravatar.com/avatar/' . md5( $w->email ) . '?s=32';
		$email       = explode( '@', $w->email );
		$email       = str_repeat( '*', strlen( $email[0] ) ) . '@' . $email[1];
		$w->email    = $email;
		$w->name     = $w->fname . ' ' . $w->lname;
	}
}

	// parent url
	$parent_url = '';
if ( ! empty( $_GET['iframe'] ) && ! empty( $_GET['parent_url'] ) ) {
	$tablename  = $wpdb->prefix . 'rafflepress_giveaways';
	$parent_url = urldecode( $_GET['parent_url'] );
	$parent_url = esc_url( $parent_url );
	if ( $giveaway->parent_url != $parent_url ) {
		// ensure domain is correct
		$home_url = home_url();
		if ( strpos( $parent_url, $home_url ) !== false ) {
		$wpdb->update(
			$tablename,
			array(
				'parent_url' => $parent_url,    // string
			),
			array( 'id' => $giveaway->id ),
			array(
				'%s',   // value1
			),
			array( '%d' )
		);
		}
	}
}


	// Convert to timestamp
	$timestamp       = strtotime( $giveaway->ends . ' UTC' );
	$start_timestamp = strtotime( $giveaway->starts . ' UTC' );


	// countdown status
	$countdown_status  = '';
	$coundown_expired  = false;
	$countdown_running = true;
	$msg               = '';
if ( $giveaway->starts != '0000-00-00 00:00:00' && $giveaway->ends != '0000-00-00 00:00:00' ) {
	if ( ! empty( $giveaway->starts ) && ! empty( $giveaway->ends ) ) {
		if ( time() < strtotime( $giveaway->starts . ' UTC' ) ) {
			$countdown_status  = 'Starts in ' . human_time_diff( time(), strtotime( $giveaway->starts . ' UTC' ) );
			$countdown_running = false;
			$msg               = __( 'This giveaway is not currently running.', 'rafflepress' );
		} elseif ( time() > strtotime( $giveaway->ends . ' UTC' ) ) {
			$countdown_status  = 'Ended ' . human_time_diff( time(), strtotime( $giveaway->ends . ' UTC' ) ) . ' ago';
			$coundown_expired  = true;
			$countdown_running = false;
			$msg               = __( 'This giveaway has ended.', 'rafflepress' );
		}
	}
}

	// Title
	$title = '';
if ( ! empty( $settings->prizes[0]->name ) ) {
	$title = $settings->prizes[0]->name;
}

	$desc = '';
if ( ! empty( $settings->prizes[0]->description ) ) {
	$desc = $settings->prizes[0]->description;
}


	// translations
	require_once RAFFLEPRESS_PLUGIN_PATH . 'resources/views/frontend-translations.php';


	// if (empty($settings->text_color)) {
	//     $settings->text_color = 'red';
	// }

	// if (empty($settings->border_color)) {
	//     $settings->border_color= 'red';
	// }

	// if (empty($settings->background_color)) {
	//     $settings->background_color= 'red';
	// }

if ( empty( $settings->button_color ) ) {
	$settings->button_color = '#27AF60';
}

	// set auotp on settings
if ( ! empty( $settings->prizes ) ) {
	foreach ( $settings->prizes as $k => $v ) {
		if ( ! empty( $v->description ) ) {
			$settings->prizes[ $k ]->description = wpautop( $v->description );
		}
	}
}




	// modify entry option to include new props
if ( ! empty( $settings->entry_options ) ) {
	foreach ( $settings->entry_options as $k => $v ) {
		$v->show      = false;
		$v->completed = false;
	}
}

	// See if embed url is passed in
	$slug = rafflepress_lite_get_slug();

	$ref_url = home_url() . '?rpid=' . $giveaway->id;


	// Determine Share Image
	$share_image = '';
	$share_text  = '';
if ( ! empty( $settings->prizes[0]->image ) ) {
	$share_image         = $settings->prizes[0]->image;
	$share_image_fb      = $share_image;
	$share_image_twitter = $share_image;
}
if ( ! empty( $settings->prizes[0]->name ) ) {
	$share_text         = $settings->prizes[0]->name;
	$share_text_fb      = $share_text;
	$share_text_twitter = $share_text;
}

	$include_fb_sdk  = false;
	$facebook_app_id = '2059212067507517';
if ( ! empty( $settings->facebook_app_id ) ) {
	$facebook_app_id = $settings->facebook_app_id;
}


	$include_instagram_sdk = false;
	$include_tiktok_sdk = false;

if ( ! empty( $settings->entry_options ) ) {
	foreach ( $settings->entry_options as $v ) {
		if ( $v->type == 'refer-a-friend' && ! empty( $v->share_image->fb ) ) {
			$share_image_fb = $v->share_image->fb;
		}
		if ( $v->type == 'refer-a-friend' && ! empty( $v->share_text_fb ) ) {
			$share_text_fb = $v->share_text_fb;
		}
		if ( $v->type == 'refer-a-friend' && ! empty( $v->share_image->twitter ) ) {
			$share_image_twitter = $v->share_image->twitter;
		}
		if ( $v->type == 'refer-a-friend' && ! empty( $v->share_text_twitter ) ) {
			$share_text_twitter = $v->share_text_twitter;
		}
		if ( $v->type == 'visit-a-page' && empty( $v->url ) ) {
			$v->url = 'https://www.rafflepress.com';
		}
		if ( $v->type == 'visit-fb' && empty( $v->fb_url ) ) {
			$v->url = 'https://www.facebook.com/wpbeginner/';
		}
		if ( $v->type == 'tweet' && empty( $v->tweet ) ) {
			$v->tweet = '';
		}
		if ( $v->type == 'twitter-follow' && empty( $v->tweet ) ) {
			$v->tweet = 'rafflepress';
		}
		if ( $v->type == 'instagram-follow' && empty( $v->instagram_url ) ) {
			$v->instagram_url = 'http://www.instagram.com';
		}
		if ( $v->type == 'g2-follow' && empty( $v->g2_url ) ) {
			$v->g2_url = 'https://www.g2.com/products/seedprod/reviews';
		}
		if ( $v->type == 'capterra-follow' && empty( $v->capterra_url ) ) {
			$v->capterra_url = 'https://reviews.capterra.com/new/168669';
		}
		if ( $v->type == 'trustpilot-follow' && empty( $v->trustpilot_url ) ) {
			$v->trustpilot_url = 'https://www.trustpilot.com/review/rafflepress.com';
		}
		if ( $v->type == 'tiktok-follow' && empty( $v->tiktok_url ) ) {
			$v->tiktok_url = 'http://www.tiktok.com/wpbeginner/';
		}
		if ( $v->type == 'pinterest-follow' && empty( $v->pinterest_username ) ) {
			$v->pinterest_username = 'https://www.pinterest.com/wpbeginner/';
		}
		if ( $v->type == 'youtube-follow' && empty( $v->youtube_url ) ) {
			$v->youtube_url = 'https://www.youtube.com/channel/UChA624rCabHAmd6lpkLOw7A';
		}
		if ( $v->type == 'watch-a-video' && empty( $v->youtube_url ) ) {
			$v->youtube_url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
		}
		if ( $v->type == 'fb-page-post' || $v->type == 'visit-fb' || $v->type == 'facebook-like-share' || $v->type == 'facebook-share' ) {
			$include_fb_sdk = true;
		}
		if ( $v->type == 'instagram-page-post'  ) {
			$include_instagram_sdk = true;
		}
		if ( $v->type == 'tiktok-videos'  ) {
			$include_tiktok_sdk = true;
		}

		if ( $v->type == 'polls-surveys' ) {
			$v->answers_multiple = array();
		}
	}
} else {
	$settings->entry_options = array();
}
	// check if facebook login is enabled then disable our sdk
if ( ! empty( $settings->social_login_facebook ) ) {
	if ( ! empty( $settings->facebook_app_id ) ) {
		$include_fb_sdk = false;
	}
}



		// check for referral passed in

if ( ! empty( $_GET['rpr'] ) && $is_bot === false ) {
	$ref_id = absint( $_GET['rpr'] );
	nocache_headers();
	setcookie( 'rafflepress_ref_' . $rafflepress_id, $ref_id, strtotime( '+1 year' ), '/' );
	$url = esc_url($giveaway->parent_url);
	
	if ( ! empty( $giveaway->slug ) ) {
		$url = home_url() . '/' . $giveaway->slug;
	}
	if ( empty( $url ) ) {
		$url = home_url() . '?rafflepress_page=rafflepress_render&rafflepress_id=' . $giveaway->id;
	}
	//header("Location: $url");
	?>
			<!DOCTYPE html>
			<html>
			<head>
			<!-- Open Graph -->
			<meta property="og:url" content="<?php echo $ref_url; ?>" />
			<meta property="og:type" content="website" />
			<meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />

			<meta property="og:description" content="<?php echo esc_attr( $share_text_fb ); ?>" />

			<?php if ( ! empty( $share_image_fb ) ) : ?>
			<meta property="og:image" content="<?php echo esc_attr( $share_image_fb ); ?>" />
			<?php endif; ?>


			<!-- Twitter Card -->
			<meta name="twitter:card" content="summary_large_image" />
			<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
			<meta name="twitter:description" content="<?php echo esc_attr( $share_text_twitter ); ?>" />
			<?php if ( ! empty( $share_image_twitter ) ) : ?>
			<meta property="twitter:image" content="<?php echo $share_image_twitter; ?>" />
			<?php endif; ?>

			<?php
			echo '<script>';
			echo PHP_EOL;
			echo 'function rp_s_c(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                var expires = "expires="+ d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
              }';
			echo PHP_EOL;
			echo 'var urlParams = new URLSearchParams(window.location.search);';
			echo PHP_EOL;
			echo 'rp_s_c("rafflepress_ref_"+urlParams.get("rpid"), urlParams.get("rpr"), 365);';
			echo PHP_EOL;
			echo 'window.location.replace("' . $url . '");';
			echo PHP_EOL;
			echo '</script>';
			?>
			</head>
			<body>
			</body>
			</html>
			<?php

			exit;
}


?>
<!DOCTYPE html>
<html lang="en" class="rafflepress-giveaway">

<head>
	<meta charset="utf-8">

	<title><?php echo esc_html( $title ); ?></title>
	<meta name="description" content="<?php echo esc_attr( $desc ); ?>">
	<?php if ( empty( $giveaway->slug ) || ! empty( $_GET['iframe'] ) ) { ?>
	<meta name="robots" content="noindex">
	<?php } ?>


	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Open Graph -->
	<meta property="og:url" content="<?php echo $ref_url; ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />

	<meta property="og:description" content="<?php echo esc_attr( $share_text_fb ); ?>" />

	<?php if ( ! empty( $share_image_fb ) ) : ?>
	<meta property="og:image" content="<?php echo esc_attr( $share_image_fb ); ?>" />
	<?php endif; ?>


	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
	<meta name="twitter:description" content="<?php echo esc_attr( $share_text_twitter ); ?>" />
	<?php if ( ! empty( $share_image_twitter ) ) : ?>
	<meta property="twitter:image" content="<?php echo $share_image_twitter; ?>" />
	<?php endif; ?>



	<!-- Bootstrap and default Style-->
	<?php
	//var_dump(wp_print_styles());
		wp_print_styles( 'rafflepress-style' );
		wp_print_styles( 'rafflepress-fontawesome' );
	?>


	<?php
	?>

	<style>
	.ytwp-wrapper {
		position: relative;
		padding-bottom: 56.25%;
		padding-top: 25px;
		height: 0;
	}

	.ytwp-wrapper iframe {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}

	.btn-preview {
		background-color: #f1460d !important;
		border-color: #f1460d !important;
		color: #fff !important;
	}

	.btn-preview.active {
		background-color: #c1390a !important;
		border-color: #9f2f09 !important;
	}



	<?php

	if ( ! empty( $settings->page_background_image ) && empty( $_GET['iframe'] ) ) {
		echo "
.rafflepress-giveaway body {
            background-image: url($settings->page_background_image);
        }

        ";

	}

	/* end-remove-for-free */


	if ( empty( $_GET['rafflepress-preview'] ) && empty( $_GET['iframe'] ) || ! empty( $_GET['giframe'] ) ) {
		echo '
.rafflepress-giveaway body {
            margin-top: 40px;
        }

        ';

	}


	if ( ! empty( $_GET['iframe'] ) ) {
		echo '
.rafflepress-giveaway body {
            background-color: transparent;
        }

        ';

	}

	if ( ! empty( $settings->background_color ) && 1 == 0 ) {
		echo "
#rafflepress-wrapper {
            background-color: $settings->background_color;
        }

        ";

	}

	if ( ! empty( $settings->text_color ) && 1 == 0 ) {
		echo "
#rafflepress-wrapper {
            color: $settings->text_color;
        }

        ";

	}


	if ( ! empty( $settings->border_color ) && 1 == 0 ) {

		echo "
#rafflepress-countdown,
        #rafflepress-my-entires,
        #rafflepress-prize-info,
        #rafflepress-giveaway-login,
        #rafflepress-giveaway-entries {
            border-color: $settings->border_color;
        }

        ";

	}


	if ( ! empty( $settings->button_color ) ) {
		$css = "




        .button-variant(@btncolor, @background, @border) {
            .lightordark (@btncolor);
            background-color: @background;
            border-color: @border;
            outline: none;

            &:focus,
            &.focus {
                .lightordark (@btncolor);
                background-color: darken(@background, 10%);
                border-color: darken(@border, 25%);
                outline: none;
            }

            &:hover {
                .lightordark (@btncolor);
                background-color: darken(@background, 10%);
                border-color: darken(@border, 12%);
                outline: none;
            }

            &:active,
            &.active,
            .open>.dropdown-toggle& {
                .lightordark (@btncolor);
                background-color: darken(@background, 10%);
                border-color: darken(@border, 12%);
                outline: none;

                &:hover,
                &:focus,
                &.focus {
                    .lightordark (@btncolor);
                    background-color: darken(@background, 17%);
                    border-color: darken(@border, 25%);
                    outline: none;
                }
            }

            &:active,
            &.active,
            .open>.dropdown-toggle& {
                background-image: none;
            }

            &.disabled,
            &[disabled],
            fieldset[disabled] & {

                &:hover,
                &:focus,
                &.focus {
                    .lightordark (@btncolor);
                    background-color: @background;
                    border-color: @border;
                }
            }


        }

        .lightordark (@c) when (luma(@c) > 65%) {
            color: #000;
        }

        .lightordark (@c) when (luma(@c) < 65%) {
            color: #fff;
        }

        @btnColor: $settings->button_color;

        .rafflepress-giveaway .btn-primary {
            .button-variant(@btnColor, @btnColor, @btnColor)
        }

        .rafflepress-giveaway .form-control:focus {
            border-color: $settings->button_color;
            box-shadow: none;
        }

        .fa-spinner {
            color: $settings->button_color;
        }

        .btn .fa-spinner {
            color: #fff;
        }

        a {
            color: $settings->button_color !important;
        }

        .rafflepress-giveaway .alert {
            background: $settings->button_color;
            .lightordark ($settings->button_color);
        }

        @keyframes hightlight_pulse_color {
            0% {
                box-shadow: inset 0 0 1px 1px $settings->button_color;
            }

            50% {
                box-shadow: inset 0 0 1px 1px #fff;
            }

            100% {
                box-shadow: inset 0 0 1px 1px $settings->button_color;
            }
        }





        ";
		try {
			$less  = new rafflepress_lessc();
			$style = $less->parse( $css );
			echo $style;
		} catch ( Exception $e ) {
			echo $e;
		}
	}

	?>
	</style>
	<?php
	?>


	<?php
		//wp_print_scripts('rafflepress-iframeresizer-frontend');
		//wp_print_scripts('rafflepress-iframeresizer-content');
	?>

	<script data-cfasync="false"
		src="<?php echo RAFFLEPRESS_PLUGIN_URL; ?>public/js/iframeResizer.contentWindow.min.js?ver=<?php echo RAFFLEPRESS_VERSION; ?>">
	</script>



	<!-- JS -->
	<?php
	?>

	<?php if ( ! empty( $settings->enable_recaptcha ) && ! empty( $settings->recaptcha_site_key ) && ! empty( $settings->recaptcha_secret_key ) ) { ?>
	<script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer>
	</script>

	<?php } ?>
</head>


<?php
if ( $is_preview ) {
	?>

<body style="padding-top:0">
	<nav class="navbar navbar-default"
		style="height: 67px;border-top:0;border-radius:0; display:flex; align-items:center;background-color:#fafbfc">
		<div style="flex:1">
			<a href="<?php echo admin_url() . 'admin.php?page=rafflepress_lite#/'; ?>"><img style="    width: 45px;
	display: inline-block;
	margin-left:25px  " src="<?php echo RAFFLEPRESS_PLUGIN_URL; ?>/public/img/rafflepress-icon.png"
					alt="RafflePress Logo"></a>
		</div>

		<div style="  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;">
			<div class="btn-group" role="group">
				<a href="<?php echo $actual_link; ?>&mode=live"
					class="btn btn-primary btn-preview <?php echo ( $preview_mode == 'live' ) ? 'active' : ''; ?>"><?php _e( 'Live Preview', 'rafflepress' ); ?></a>
				<a href="<?php echo $actual_link; ?>&mode=actions"
					class="btn btn-primary btn-preview <?php echo ( $preview_mode == 'actions' ) ? 'active' : ''; ?>">
					<?php _e( 'Preview All Actions', 'rafflepress' ); ?></a>
			</div>
		</div>
		<div style="  flex: 1;"> </div>
	</nav> 
	<?php
} else {
	?>

	<body>
	<?php
}
?>
		<?php
		if ( $include_instagram_sdk ) {
			?>
		<script async src="//www.instagram.com/embed.js"></script>
		<?php } ?>

		<?php
		if ( $include_tiktok_sdk ) {
			?>
		<script async src="//www.tiktok.com/embed.js"></script>
		<?php } ?>

		<?php
		if ( $include_fb_sdk ) {
			?>
		<div id=" fb-root"></div>
		<script>
			window.fbAsyncInit = function() {
				FB.init({
				appId            : '<?php echo $facebook_app_id; ?>',
				autoLogAppEvents : true,
				xfbml            : true,
				version          : 'v8.0'
				});
			};
		</script>
		<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
	
			<?php
		}
		?>
		<?php
		if ( $include_instagram_sdk ) {
			?>
		<script async defer src="//www.instagram.com/embed.js"></script>
			<?php
		}
		?>
		
		<?php
		if ( ! empty( $disabled_msg ) ) {
				echo '<p style="text-align:center;font-weight:bold;">' . $disabled_msg . '</p>';
		}
		?>


		<div id="rafflepress-frontent-vue-app"></div>

		<!-- App JS -->

		<script>
		<?php $ajax_url = html_entity_decode( wp_nonce_url( admin_url( 'admin-ajax.php' ) . '?action=rafflepress_lite_giveaway_api', 'rafflepress_lite_giveaway_api' ) ); ?>
		var rafflepress_api_url = "<?php echo $ajax_url; ?>";

		<?php $ajax_url = html_entity_decode( wp_nonce_url( admin_url( 'admin-ajax.php' ) . '?action=rafflepress_lite_giveaway_comment', 'rafflepress_lite_giveaway_comment' ) ); ?>
		var rafflepress_comments_url = "<?php echo $ajax_url; ?>";

		<?php
		$fb_auth_integration_url = '';
		?>

		var rafflepress_data =
			<?php
			echo json_encode(
				array(
					'api_url'                 => RAFFLEPRESS_CALLBACK_URL,
					'plugin_path'             => RAFFLEPRESS_PLUGIN_URL,
					'show_winners'            => $show_winners,
					'end_date'                => $timestamp,
					'start_date'              => $start_timestamp,
					'login_txt'               => $rp_frontend_translations['txt_4'],
					'countdown_expired'       => $coundown_expired,
					'countdown_status'        => $countdown_status,
					'countdown_running'       => $countdown_running,
					'msg'                     => $msg,
					'is_preview'              => $is_preview,
					'preview_mode'            => $preview_mode,
					'giveaway'                => $giveaway,
					'settings'                => $settings,
					'winners'                 => $winners,
					'parent_url'              => esc_url($parent_url),
					'referral_url'            => $ref_url,
					'fb_auth_integration_url' => $fb_auth_integration_url,
				)
			);
			?>
			;

		var rafflepress_frontend_translation_data =
			<?php echo json_encode( $rp_frontend_translations ); ?>;
		</script>

		<?php
		$is_localhost = rafflepress_lite_is_localhost();
		if ( $is_localhost ) {
		} else {
			//wp_print_scripts('rafflepress-app');
			//wp_print_scripts('rafflepress-vendors');
			$vue_app_folder = RAFFLEPRESS_BUILD;
			?>
		<script
			src="<?php echo RAFFLEPRESS_PLUGIN_URL; ?>public/<?php echo $vue_app_folder; ?>/vue-frontend/js/app.js?ver=<?php echo RAFFLEPRESS_VERSION; ?>">
		</script>
		<script
			src="<?php echo RAFFLEPRESS_PLUGIN_URL; ?>public/<?php echo $vue_app_folder; ?>/vue-frontend/js/chunk-vendors.js?ver=<?php echo RAFFLEPRESS_VERSION; ?>">
		</script>
			<?php


		}
		?>
		<?php
		?>
		<div id="rafflepress-conversion-scripts"></div>
	</body>



</html>
