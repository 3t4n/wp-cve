<?php
defined( 'ABSPATH' ) || exit;
/**
 * user_profile_output
 *
 * @package YAHMAN Add-ons
 */



function yahman_addons_user_profile_output( $class = '' ) {
	$yahman_addons_social_user_profile = get_the_author_meta( 'yahman_addons_social_user_profile');
	if(!empty($yahman_addons_social_user_profile)){

		$yahman_addons_social_option = array('icon_shape','icon_size','icon_user_color','icon_user_hover_color','icon_tooltip');
		foreach ($yahman_addons_social_option as $value) {
			if(isset($yahman_addons_social_user_profile[$value])){
				$sns_info[$value] = $yahman_addons_social_user_profile[$value];
			}else{
				$sns_info[$value] = '';
			}
		}

		if($sns_info['icon_shape'] == '') $sns_info['icon_shape']  = 'icon_square';
		if($sns_info['icon_tooltip'] != '') $sns_info['icon_tooltip']  = ' sns_tooltip';

		$i = 1;

		while($i <= 5){
			$sns_info['account'][$i] = $sns_info['share'][$i] = '';

			$sns_info['icon'][$i] = $sns_url[$i] = '';
			if(isset($yahman_addons_social_user_profile['sns_icon_'.$i])){
				$sns_info['icon'][$i] = $yahman_addons_social_user_profile['sns_icon_'.$i];
			}
			if(isset($yahman_addons_social_user_profile['sns_url_'.$i])){
				$sns_info['url'][$i] = $yahman_addons_social_user_profile['sns_url_'.$i];
			}
			++$i;
		}

		$sns_info['loop'] = 5;
		$sns_info['class'] = ' sns_ap jc_c100';


		$sns_info['widget_id'] = 'sns_post_profile';

		echo '<li id="sns_post_profile" class="'.$class.'">';
		require_once YAHMAN_ADDONS_DIR . 'inc/widget/social-output.php';
		yahman_addons_social_output($sns_info);
		echo '</li>';
	}

}

