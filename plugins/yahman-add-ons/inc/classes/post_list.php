<?php
defined( 'ABSPATH' ) || exit;

class YAHMAN_ADDONS_POST_LIST {

	public static function yahman_addons_post_list_output($posts , $settings) {

		$ranking_data = '<div class="post_list_box">';
		$ranking_data .= '<ul class="post_list_ul m0'.$settings['ul_class'].'" style="list-style:none;">';

		if( $settings['display_style'] == '1'){
			$function_name = 'list_without_thumbnail';
		}else{
			require_once YAHMAN_ADDONS_DIR . 'inc/get_thumbnail.php';
			if( $settings['display_style'] == '2') $function_name = 'list_with_thumbnail';
			if( $settings['display_style'] == '3') $function_name = 'title_over_thumbnail';
			if( $settings['display_style'] == '4') $function_name = 'title_under_thumbnail';
		}

		if(!YAHMAN_ADDONS_TEMPLATE){
			add_action( 'wp_footer', 'yahman_addons_enqueue_style_post_list' );
		}

		$back_data = self::$function_name( $posts,$settings );

		return array( $ranking_data .$back_data[0] .'</ul></div>'."\n" , $back_data[1]);

	}


	protected static function list_without_thumbnail($posts,$settings){

		$ranking_data = '';
		$i = 1;
		while ( $posts->have_posts() ) :
			$posts->the_post();


			$ranking_data .= '<li class="pl_item f_box post_list_border">';

			if($settings['ranking']){
				$ranking_data .= '<div class="pl_rank f0030px ta_c fw8 fc_fff f_box ai_c jc_c">';
				$ranking_data .=  esc_html( $i );
				$ranking_data .= '</div>';
			}

			$ranking_data .= '<div class="f_box f_col jc_c" style="width:100%;">';
			$ranking_data .= '<a href="'.esc_url(get_permalink()).'" class="pl_title f_box ai_c p5">';
			$ranking_data .= esc_html(mb_strimwidth(get_the_title(), 0, 48, "...", 'UTF-8'));
			$ranking_data .= '</a>';

			if($settings['pv']){
				$ranking_data .= '<div class="pl_pv_box z1  " style="padding:4px;text-align:right;">';
				$ranking_data .= get_post_meta($posts->posts[$i-1]->ID, esc_attr( '_yahman_addons_pv_'.$settings['time_period'] ) , true );
				$ranking_data .= 'pv</div>';
			}


			$ranking_data .= '</div>';
			$ranking_data .= '</li>';

			if($settings['update']){
				--$settings['number_post'];
				if($settings['number_post'] == 0)break;
			}
			++$i;
		endwhile;
		wp_reset_postdata();

		return array($ranking_data, ($i -1) );
	}

	protected static function title_under_thumbnail($posts,$settings){

		$ranking_data = '';
		$i = 1;
		while ( $posts->have_posts() ) :
			$posts->the_post();

			$thumurl = yahman_addons_get_thumbnail( get_the_ID() , 'medium' );

			$title = get_the_title();
			$post_url = get_permalink();

			$ranking_data .= '<li class="pl_item relative mb10 opa7'.$settings['li_class'].'">';


			if($settings['ranking']){
				$ranking_data .= '<div class="pl_rank left0 top0 ta_c fw8 fc_fff absolute z2">'.esc_html( $i ).'</div>';
			}


			$ranking_data .= '<a href="'. esc_url($post_url) .'" class="non_hover">';

			$ranking_data .= '<div class="pl_thum_box fit_box_img_wrap">';

			$ranking_data .= '<img src="'.esc_url($thumurl[0]).'" class="scale_13 trans_10" width="'.esc_attr($thumurl[1]).'" height="'.esc_attr($thumurl[2]).'" alt="'.esc_attr($title).'" title="'.esc_attr($title).'" />';

			$ranking_data .= '</div>';

			if($settings['pv']){
				$ranking_data .= '<div class="pl_pv_box z2 fc_fff absolute right0 top0" style="background:rgba(0,0,0,0.6);padding:4px;">';
				$ranking_data .= get_post_meta($posts->posts[$i-1]->ID, esc_attr( '_yahman_addons_pv_'.$settings['time_period'] ) , true );
				$ranking_data .= 'pv</div>';
			}

			$ranking_data .= '<div class="pl_thum_title f_box of_h">';
			$ranking_data .= esc_html($title);
			$ranking_data .= '</div>';
			$ranking_data .= '</a>';
			$ranking_data .= '</li>';


			if($settings['update']){
				--$settings['number_post'];
				if($settings['number_post'] == 0)break;
			}
			++$i;
		endwhile;
		wp_reset_postdata();

		return array($ranking_data, ($i -1) );



	}


	protected static function title_over_thumbnail($posts,$settings){

		$ranking_data = '';
		$i = 1;
		while ( $posts->have_posts() ) :
			$posts->the_post();

			$thumurl = yahman_addons_get_thumbnail(  get_the_ID() , 'medium' );
			$title = get_the_title();
			$post_url = get_permalink();

			$ranking_data .= '<li class="pl_item relative mb_M opa7 shadow_box flow_box'.$settings['li_class'].'">';

			$ranking_data .= '<a href="'. esc_url($post_url).'" class="non_hover db w100 h100 relative">';

			if($settings['ranking']){
				$ranking_data .= '<div class="pl_rank left0 top0 ta_c fw8 fc_fff absolute z2">'.esc_html( $i ).'</div>';
			}



			$ranking_data .= '<div class="pl_tt_title z1 fc_fff absolute w100 h100"><span class="absolute left0 right0 bottom0 line_clamp lc3 of_h">' . esc_html($title) . '</span></div>';

			if($settings['pv']){
				$ranking_data .= '<div class="pl_pv_box z2 fc_fff absolute right0 top0" style="background:rgba(0,0,0,0.6);padding:4px;">';
				$ranking_data .= get_post_meta($posts->posts[$i-1]->ID, esc_attr( '_yahman_addons_pv_'.$settings['time_period'] ) , true );
				$ranking_data .= 'pv</div>';
			}

			$ranking_data .= '<div class="pl_thum_box fit_box_img_wrap">';
			$ranking_data .= '<img src="'.esc_url($thumurl[0]).'" class="" width="'.esc_attr($thumurl[1]).'" height="'.esc_attr($thumurl[2]).'" alt="'.esc_attr($title).'" title="'.esc_attr($title).'" />';
			$ranking_data .= '</div>';

			$ranking_data .= '</a></li>';



			if($settings['update']){
				--$settings['number_post'];
				if($settings['number_post'] == 0)break;
			}
			++$i;
		endwhile;
		wp_reset_postdata();

		return array($ranking_data, ($i -1) );
	}

	protected static function list_with_thumbnail($posts,$settings){
		$ranking_data = '';
		$i = 1;
		while ( $posts->have_posts() ) :
			$posts->the_post();

			$thumurl = yahman_addons_get_thumbnail(  get_the_ID() , 'medium' );
			$title = get_the_title();
			$post_url = get_permalink();

			$ranking_data .= '<li class="pl_item relative mb10 mb_M">';
			$ranking_data .= '<a href="'. esc_url($post_url) .'" class="f_box w100 opa7">';
			if($settings['ranking']){
				$ranking_data .= '<div class="pl_rank f0030px ta_c fw8 fc_fff f_box ai_c jc_c">'. esc_html( $i ) .'</div>';
			}

			$ranking_data .= '<div class="post_list_thum fit_box_img_wrap">';


			$ranking_data .= '<img src="'.esc_url($thumurl[0]).'" class="scale_13 trans_10" width="'.esc_attr($thumurl[1]).'" height="'.esc_attr($thumurl[2]).'" alt="'.esc_attr($title).'" title="'.esc_attr($title).'" /></div>';

			$ranking_data .= '<div class="pl_title f_box f_col jc_c" style="width:100%;">';

			$ranking_data .=  '<div class="line_clamp lc2 of_h">'.esc_html(mb_strimwidth($title, 0, 64, "...", 'UTF-8')).'</div>';

			if($settings['pv']){
				$ranking_data .= '<div class="pl_pv_box z1  " style="padding:4px;text-align:right;">';
				$ranking_data .= get_post_meta($posts->posts[$i-1]->ID, esc_attr( '_yahman_addons_pv_'.$settings['time_period'] ) , true );
				$ranking_data .= 'pv</div>';
			}

			$ranking_data .= '</div>';
			$ranking_data .= '</a>';
			$ranking_data .= '</li>';

			if($settings['update']){
				--$settings['number_post'];
				if($settings['number_post'] == 0)break;
			}
			++$i;
		endwhile;
		wp_reset_postdata();

		return array($ranking_data, ($i -1) );

	}







}

