<?php
/**
 *
 * Articles flex display
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_flex_articles($post,$settings){

	$f_cat = array();



	$f_cat['title'] = $post->post_title;

	$f_cat['post_url'] = get_permalink( $post->ID );

	$f_cat['direction'] = ' f_row';
	$f_cat['flex70'] = '';
	$f_cat['flex30'] = '';
	$f_cat['thum_space'] = '';

	if($settings['flex_column']){

		$f_cat['direction'] = ' f_col';

	}else{

		$f_cat['flex70'] = ' flex_70';
		$f_cat['flex30'] = ' flex_30';
	}




	?>
	<div class="art_wrap f_box<?php echo esc_attr($f_cat['direction']).esc_attr($settings['columns']); ?> mb_M bg_fff relative shadow_box flow_box">
		<a href="<?php echo esc_url($f_cat['post_url']); ?>" class="non_hover tap_no db w100 h100 absolute z1"></a>

		<?php if($settings['thumbnail']){
			$f_cat['thum_url'] = yahman_addons_get_thumbnail( $post->ID , $settings['thum_size']);
			if($settings['thum_space']) $f_cat['thum_space'] = ' p_M';
			?>
			<div class="art_img fit_box_img_wrap<?php echo esc_attr($f_cat['flex30']); ?>">

				<?php
				echo '<img decoding="async" src="'.esc_url($f_cat['thum_url'][0]).'" class="scale_13 trans_10'.esc_attr($f_cat['thum_space']).'" width="'.esc_attr($f_cat['thum_url'][1]).'" height="'.esc_attr($f_cat['thum_url'][2]).'" alt="'.esc_attr($f_cat['title']).'" title="'.esc_attr($f_cat['title']).'" style="padding-bottom:0;" />';
				?>

			</div>
		<?php } ?>
		<div class="art_meta<?php echo esc_attr($f_cat['flex70']); ?> hp_p f_box f_col jc_c f_auto">
			<?php if($settings['view_category']){
				$category = get_the_category();
				if(!empty($category)){ ?>
					<div class='art_category fsS hp_p'>
						<?php echo esc_html($category[0]->cat_name) ; ?>
					</div>
				<?php }	?>
			<?php } ?>
			<div class="art_title hp_p fsM fw8">
				<?php echo esc_html($f_cat['title']); ?>
			</div>
			<?php if($settings['view_date']){ ?>
				<div class='art_date fsS hp_p'>
					<?php echo get_the_date(); ?>
				</div>
			<?php } ?>
			<?php if($settings['description']){ ?>
				<div class='art_description fsS hp_p'>
					<?php echo mb_strimwidth( wp_strip_all_tags(strip_shortcodes($post ->post_content), true), 0 , 150 , '&hellip;' ); ?>
				</div>
			<?php } ?>

		</div>

	</div>
	<?php
}


