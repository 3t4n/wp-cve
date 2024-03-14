<?php

defined( 'ABSPATH' ) or die(':)');


function video_popup_on_pageload_callback(){
	do_action('video_popup_opload_cb_action');
}


function video_popup_on_pageload_f(){
?>
		<div class="wrap">
			<div class="vp-clear-fix">


				<div class="vp-left-col">
					<?php if( !get_option('vp_green_bg_menu') ) : ?>
						<?php update_option('vp_green_bg_menu', 'true'); ?>
						<style type="text/css">
							body a.toplevel_page_video_popup_general_settings{
    							background: #0073aa !important;
							}
						</style>
					<?php endif; ?>

					<h1 style="margin-bottom: 20px !important;"><span><?php _e('Video PopUp on Page Load', 'video-popup'); ?></span></h1>

					<h2><?php _e("Display Pop-up Video on page loading.", 'video-popup'); ?></h2>

					<p><?php _e('Please read <a href="https://wp-plugins.in/note_about_on_page_load" target="_blank">this note</a> about the <strong>"On Page Load"</strong> feature.', 'video-popup'); ?></p>

					<?php
                        if( isset($_GET['settings-updated']) and $_GET['settings-updated'] == 'true' ){
                        	$style = "none";
                            ?>
                                <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
                                     <p><strong><?php _e('Settings saved.'); ?></strong></p>
                                    <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.'); ?></span></button>
                                </div>
                            <?php
                        }else{
                        	$style = 'block';
                        }

                        if( isset($_GET['vp_deleted_cookie']) ){
							?>
                                <div style="display: <?php echo $style; ?>;" id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
                                     <p><strong><?php _e('Cookie was deleted.', 'video-popup'); ?></strong></p>
                                    <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.'); ?></span></button>
                                </div>
                            <?php
                        }
                    ?>

					<form method="post" action="options.php">
                		<?php
                			settings_fields("vp_al_section");
                    		do_settings_sections("vp_al_options");
                    		submit_button();
                		?>
            		</form>

            	</div>

            	<div class="vp-right-col">

            		<div class="postbox vp-no-premium-ext">
                    <h2 style="border-bottom: 1px solid #eee !important;padding: 12px !important;margin: 0 !important;"><span><?php _e('Get The Premium Extension!', 'video-popup'); ?></span></h2>
                    <div class="inside" style="padding: 12px !important;margin: 0 !important;">

                        <div class="main">

                            <p style="margin: 0 !important;"><?php _e("Get it at a low price! Unlock all the features. Easy to use, download it, install it, activate it, and enjoy! Get it now!", 'video-popup'); ?></p>

                            <p style="margin-bottom: 0 !important;"><a href="https://wp-plugins.in/Get-VP-Premium-Extension" class="vp-settings-btn vp-get-premium-su" target="_blank"><?php _e('Get The Premium Extension', 'video-popup'); ?></a></p>

                        </div>

                    </div>
                </div>


                <div class="postbox">
                    <h2 style="border-bottom: 1px solid #eee !important;padding: 12px !important;margin: 0 !important;"><span><?php _e('Explanation of Use', 'video-popup'); ?></span></h2>
                    <div class="inside" style="padding: 12px !important;margin: 0 !important;">

                        <div class="main">

                            <p style="margin: 0 !important;"><?php _e('Need help? Support? Questions? Read the Explanation of Use.', 'video-popup'); ?></p>

                            <p style="margin-bottom: 0 !important;"><a href="https://wp-plugins.in/VideoPopUp-Usage" class="vp-settings-btn vp-read-expofuse-su" target="_blank"><?php _e('Explanation of Use', 'video-popup'); ?></a></p>

                        </div>

                    </div>
                </div>

            	</div>


        	</div>
		</div>
	<?php
}
add_action('video_popup_opload_cb_action', 'video_popup_on_pageload_f');


function video_popup_ajax_result_on_pageload(){
	if( isset($_GET['vp_on_pageload']) ){

		if( get_option('vp_al_op_video_url') == '' or get_option('vp_al_op_video_url') === false ){
			exit();
		}

		$get_video_url = get_option('vp_al_op_video_url');
		$md5 = md5($get_video_url);
		$cookie_id = $md5;

		if ( isset($_COOKIE["video_popup_ct_$cookie_id"]) and get_option('vp_al_op_cookie') ){
			exit();
		}

		$get_cookie_time = get_option('vp_al_op_cookie');

		if( !$get_cookie_time or $get_cookie_time == '' or $get_cookie_time == 0 or $get_cookie_time == '0' ){
			$get_time = 3600;
		}else{
			$get_time = $get_cookie_time * 3600;
		}

		$video_url = get_option('vp_al_op_video_url');

		if( get_option('vp_al_op_autoplay') ){
			$autoplay = 1;
		}else{
			$autoplay = 0;
		}

		if( get_option('vp_al_op_yt_mute') ){
			$mute = 1;
		}else{
			$mute = 0;
		}

		if( preg_match("/(youtube.com)/", $video_url) ){
    		$get_video_id = explode("v=", preg_replace("/(&)+(.*)/", null, $video_url) );
    		$the_video_id = $get_video_id[1];
    		$video_type_class = 'vp-vt-youtube';
		}elseif( preg_match("/(youtu.be)/", $video_url) ){
        		$get_video_id = explode("/", preg_replace("/(&)+(.*)/", null, $video_url) );
        		$the_video_id = $get_video_id[3];
        		$video_type_class = 'vp-vt-youtube';
		}elseif( preg_match("/(vimeo.com)/", $video_url) ){
			$vimeo_id = preg_replace("/[^\/]+[^0-9]|(\/)/", "", rtrim($video_url, "/"));
			$video_type_class = 'vp-vt-vimeo';
		}else{
			$video_type_class = 'vp-vt-locally';
		}

		if( get_option('vp_al_op_d_remove_border') ){
			$remove_border = 'vp-flex-no-border ';
		}else{
			$remove_border = '';
		}

		if( get_option('vp_al_op_display_custom') ){
			$id = get_option('vp_al_op_display_custom');

			if( is_single($id) ){
				$single = is_single($id);
			}else{
				$single = false;
			}

			if( is_page($id) ){
				$page = is_page($id);
			}else{
				$page = false;
			}
		}

		if(
			get_option( 'vp_al_op_display' ) == 'entire'
			or get_option( 'vp_al_op_display' ) == 'homepage' and is_home()
			or get_option( 'vp_al_op_display' ) == 'frontpage' and is_front_page()
			or get_option( 'vp_al_op_display' ) == 'custom' and ( $single === true or $page === true )
			){
				if( get_option('vp_al_op_logged_in') and !is_user_logged_in() or !get_option('vp_al_op_logged_in') ){
					if( get_option('vp_al_op_cookie') ){
						setcookie("video_popup_ct_$cookie_id", "video_popup_ct_$cookie_id", time() + $get_time, '/');
					}
				?>

				<script type="text/javascript">
					jQuery(function(){
						jQuery(".vp-a, .vp-s").on('click', function () {
							jQuery('.videoPopup-on-pageload').remove();
						});

						jQuery('.YouTubePopUp-Wrap').fadeIn(300);

						jQuery(".YouTubePopUp-Wrap, .YouTubePopUp-Close").click(function(){
                			jQuery(".YouTubePopUp-Wrap").fadeOut(300).delay(325).queue(function() { jQuery(this).remove(); });
            			});

            			jQuery('.vp-flex, .vp-flex *').click(function(e){
                			e.stopPropagation();
            			});

						jQuery(document).keyup(function(e) {
            				if ( e.keyCode == 27 ){
                				jQuery('.YouTubePopUp-Close').click();
            				}
        				});
					});
				</script>

				<div style="max-width:880px; height:440px;" class="vp-flex <?php echo $remove_border.$video_type_class; ?>">
					<span class="YouTubePopUp-Close"></span>
					<?php if ( preg_match("/(youtube.com)|(youtu.be)/", $video_url) ) : ?>
						<iframe src="https://www.youtube.com/embed/<?php echo $the_video_id; ?>?autoplay=<?php echo $autoplay; ?>&mute=<?php echo $mute; ?>" allow="autoplay" allowfullscreen></iframe>
					<?php endif; ?>

					<?php if ( preg_match("/(vimeo.com)/", $video_url) ) : ?>
						<iframe src="https://player.vimeo.com/video/<?php echo $vimeo_id; ?>?autoplay=<?php echo $autoplay; ?>" allow="autoplay" allowfullscreen></iframe>
					<?php endif; ?>

					<?php if ( preg_match("/(.mp4)/", $video_url) ) : ?>
						<?php
							if( $autoplay == 1 ){
								$mp4_options = 'controls autoplay controlsList="nodownload"';
							}else{
								$mp4_options = 'controls controlsList="nodownload"';
							}
						?>
						<video <?php echo $mp4_options; ?>><source src="<?php echo $video_url; ?>" type="video/mp4"></video>
					<?php endif; ?>
				</div>

			<?php

			}
		}

		exit();
	}
}
add_action('video_popup_on_pageload_action_cb', 'video_popup_ajax_result_on_pageload');


function video_popup_on_pageload(){
	do_action('video_popup_on_pageload_action_cb');
}
add_action('template_redirect', 'video_popup_on_pageload');


function video_popup_on_pageload_html(){

	if( get_option('vp_al_op_video_url') ){

		if( is_single() or is_page() ){
			global $post;
			$get_id = $post->ID;
			$ajax_link = home_url("/?p=$get_id&vp_on_pageload=t");
		}else{
			$ajax_link = "?vp_on_pageload=t";
		}

		if( get_option('vp_al_op_display_custom') ){
			$id = get_option('vp_al_op_display_custom');

			if( is_single($id) ){
				$single = is_single($id);
			}else{
				$single = false;
			}

			if( is_page($id) ){
				$page = is_page($id);
			}else{
				$page = false;
			}
		}

		if(
			get_option( 'vp_al_op_display' ) == 'entire'
			or get_option( 'vp_al_op_display' ) == 'homepage' and is_home()
			or get_option( 'vp_al_op_display' ) == 'frontpage' and is_front_page()
			or get_option( 'vp_al_op_display' ) == 'custom' and ( $single === true or $page === true )
			){

				?>
				<script type="text/javascript">
					jQuery(document).ready(function(){
        				jQuery('.vp-on-pageload-wrap').load("<?php echo $ajax_link; ?>");
					});
				</script>

				<div class="YouTubePopUp-Wrap videoPopup-on-pageload" style="display: none;">
					<div class="Video-PopUp-Content vp-on-pageload-wrap"></div>
				</div>
			<?php

		}

	}

}
add_action('wp_footer', 'video_popup_on_pageload_html');


function video_popup_delete_cookie(){
	if( isset($_GET['vp_delete_cookie']) ){

		$get_cookie_time = get_option('vp_al_op_cookie');

		if( !$get_cookie_time or $get_cookie_time == '' or $get_cookie_time == 0 or $get_cookie_time == '0' ){
			$get_time = 3600;
		}else{
			$get_time = $get_cookie_time * 3600;
		}

		$get_video_url = get_option('vp_al_op_video_url');
		$md5 = md5($get_video_url);
		$cookie_id = $md5;

		setcookie("video_popup_ct_$cookie_id", "video_popup_ct_$cookie_id", time() - $get_time, '/');

		wp_redirect( admin_url("admin.php?page=video_popup_on_pageload&vp_deleted_cookie=$cookie_id") );

		exit();

	}
}
add_action('admin_init', 'video_popup_delete_cookie');