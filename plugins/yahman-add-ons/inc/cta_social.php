<?php
defined( 'ABSPATH' ) || exit;
/**
 * Social CTA
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_cta_social(){

	if( is_front_page() || is_attachment() )return;

	
	$defaults = array(
		'heading'    => esc_html__('Follow us', 'yahman-add-ons'),
		'ending'    => esc_html__('We will keep you updated', 'yahman-add-ons'),
		'facebook'    => false,
		//'facebook_script'    => false,
		'twitter'    => false,
		'feedly'    => false,
	);

	$option = get_option('yahman_addons');

	$settings = wp_parse_args( $option['cta_social'], $defaults );

	$cta_social['heading'] = apply_filters('yahman_addons_cta_social_heading', $settings['heading'] );
	$cta_social['ending'] = apply_filters('yahman_addons_cta_social_ending', $settings['ending'] );
	$cta_social['facebook'] = $settings['facebook'];
	//$cta_social['facebook_script'] = $settings['facebook_script'];
	$cta_social['twitter'] = $settings['twitter'];
	$cta_social['feedly'] = $settings['feedly'];

	$facebook_id = isset($option['sns_account']['facebook']) ? $option['sns_account']['facebook'] : '';
	$facebook_app_id = isset($option['sns_account']['facebook_app_id']) ? $option['sns_account']['facebook_app_id'] : '';
	$twitter_id = isset($option['sns_account']['twitter']) ? $option['sns_account']['twitter'] : '';

	$no_image = !empty($option['other']['no_image']) ? $option['other']['no_image'] : YAHMAN_ADDONS_URI . 'assets/images/no_image.png';



	$post = get_post();
	$thumurl = yahman_addons_get_thumbnail( $post->ID , 'medium' );

	ob_start();
	?>
	<div class="cta_box mb_L shadow_box fit_content">
		<div class="cta_box_wrap f_box">
			<div class="cta_box_thum fit_box_img_wrap">
				<img src="<?php echo esc_url($thumurl[0]); ?>" class="scale_13 trans_10" width="<?php echo esc_attr($thumurl[1]); ?>" height="<?php echo esc_attr($thumurl[2]); ?>" />
			</div>
			<div class="cta_box_like f_box f_col ai_c">

				<p class="cta_box_like_text"><?php echo esc_html($cta_social['heading']); ?></p>

				<div class="cta_box_social f_box f_col ai_c">
					<?php if($cta_social['facebook'] && $facebook_id != ''){ ?>
						<div class="cta_box_fa f_box jc_c">
							<?php
							require_once YAHMAN_ADDONS_DIR . 'inc/facebook_script.php';
							yahman_addons_facebook_script();
								/*
								<iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo urlencode( 'https://www.facebook.com/'.esc_attr($facebook_id) ); ?>&width=140&layout=button_count&action=like&size=large&share=false&height=28&appId=<?php echo esc_attr($facebook_app_id); ?>" width="132" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
*/
								?>
								<div class="fb-like" data-href="https://www.facebook.com/<?php echo esc_attr($facebook_id); ?>" data-width="" data-layout="button_count" data-action="like" data-size="large" data-share="false"></div>


							</div>
						<?php } ?>
						<?php if($cta_social['twitter'] && $twitter_id != ''){ ?>
							<div class="cta_box_tw f_box ai_c jc_c">
								<?php
							//add_action( 'wp_footer', 'yahman_addons_twitter_widgets_script');
									wp_register_script( 'yahman_twitter-widgets', '' );
									?>
									<a href="https://twitter.com/<?php echo esc_attr(str_replace( '@' , '' , $twitter_id )); ?>" class="twitter-follow-button" data-show-count="true" data-size="large" data-show-screen-name="false">Follow <?php echo esc_attr('@' . str_replace( '@' , '' , $twitter_id )); ?></a>
							</div>
						<?php } ?>
						<?php if($cta_social['feedly']){ ?>
							<div class="cta_box_fe">

								<a href="<?php echo esc_url('https://feedly.com/i/subscription/feed/'.get_bloginfo('rss2_url')); ?>" target="_blank" class="sns_feedly icon_rectangle non_hover icon_rec" title="<?php echo esc_html_x('Follow', 'cta_feedly_follow' ,'yahman-add-ons'); ?>" style="font-size:16px;width:auto;height:28px;position:relative;text-decoration:none;border-radius:5px;text-align:left;padding:0 10px 0 5px;color:#fff;background:#2BB24C;display:inline-block;"><svg class="svg-icon" width="20" height="20" fill="#fff" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;display:inline-block;"><path class="sns_icon_1" d="M7.396 21.932L.62 15.108c-.825-.824-.825-2.609 0-3.39l9.709-9.752c.781-.78 2.521-.78 3.297 0l9.756 9.753c.826.825.826 2.611 0 3.391l-6.779 6.824c-.411.41-1.053.686-1.695.686H9c-.596-.001-1.19-.276-1.604-.688zm6.184-2.656c.137-.138.137-.413 0-.55l-1.328-1.328c-.138-.15-.412-.15-.549 0l-1.329 1.319c-.138.134-.138.405 0 .54l1.054 1.005h1.099l1.065-1.02-.012.034zm0-5.633c.092-.09.092-.32 0-.412l-1.42-1.409c-.09-.091-.32-.091-.412 0l-4.121 4.124c-.139.15-.139.465 0 .601l.959.96h1.102l3.893-3.855v-.009zm0-5.587c.092-.091.137-.366 0-.458l-1.375-1.374c-.09-.104-.365-.104-.502 0l-6.914 6.915c-.094.09-.14.359-.049.449l1.1 1.05h1.053l6.687-6.582z"></path></svg></a>

							</div>
						<?php } ?>
					</div>
					<p class="cta_box_like_text"><?php echo esc_html($cta_social['ending']); ?></p>

				</div>
			</div>
		</div>
		<?php

		return ob_get_clean();

	}
