<?php

function siteseo_get_dyn_variables(){
	return apply_filters('siteseo_get_dynamic_variables', [
		'%%sep%%' => 'Separator',
		'%%sitetitle%%' => __('Site Title', 'siteseo'),
		'%%tagline%%' => __('Tagline', 'siteseo'),
		'%%post_title%%' => __('Post Title', 'siteseo'),
		'%%post_excerpt%%' => __('Post excerpt', 'siteseo'),
		'%%post_content%%' => __('Post content / product description', 'siteseo'),
		'%%post_thumbnail_url%%' => __('Post thumbnail URL', 'siteseo'),
		'%%post_url%%' => __('Post URL', 'siteseo'),
		'%%post_date%%' => __('Post date', 'siteseo'),
		'%%post_modified_date%%' => __('Post modified date', 'siteseo'),
		'%%post_author%%' => __('Post author', 'siteseo'),
		'%%post_category%%' => __('Post category', 'siteseo'),
		'%%post_tag%%' => __('Post tag', 'siteseo'),
		'%%_category_title%%' => __('Category title', 'siteseo'),
		'%%_category_description%%' => __('Category description', 'siteseo'),
		'%%tag_title%%' => __('Tag title', 'siteseo'),
		'%%tag_description%%' => __('Tag description', 'siteseo'),
		'%%term_title%%' => __('Term title', 'siteseo'),
		'%%term_description%%' => __('Term description', 'siteseo'),
		'%%search_keywords%%' => __('Search keywords', 'siteseo'),
		'%%current_pagination%%' => __('Current number page', 'siteseo'),
		'%%page%%' => __('Page number with context', 'siteseo'),
		'%%cpt_plural%%' => __('Plural Post Type Archive name', 'siteseo'),
		'%%archive_title%%' => __('Archive title', 'siteseo'),
		'%%archive_date%%' => __('Archive date', 'siteseo'),
		'%%archive_date_day%%' => __('Day Archive date', 'siteseo'),
		'%%archive_date_month%%' => __('Month Archive title', 'siteseo'),
		'%%archive_date_month_name%%' => __('Month name Archive title', 'siteseo'),
		'%%archive_date_year%%' => __('Year Archive title', 'siteseo'),
		'%%_cf_your_custom_field_name%%' => __('Custom fields from post, page, post type and term taxonomy', 'siteseo'),
		'%%_ct_your_custom_taxonomy_slug%%' => __('Custom term taxonomy from post, page or post type', 'siteseo'),
		'%%wc_single_cat%%' => __('Single product category', 'siteseo'),
		'%%wc_single_tag%%' => __('Single product tag', 'siteseo'),
		'%%wc_single_short_desc%%' => __('Single product short description', 'siteseo'),
		'%%wc_single_price%%' => __('Single product price', 'siteseo'),
		'%%wc_single_price_exc_tax%%' => __('Single product price taxes excluded', 'siteseo'),
		'%%wc_sku%%' => __('Single SKU product', 'siteseo'),
		'%%currentday%%' => __('Current day', 'siteseo'),
		'%%currentmonth%%' => __('Current month', 'siteseo'),
		'%%currentmonth_short%%' => __('Current month in 3 letters', 'siteseo'),
		'%%currentyear%%' => __('Current year', 'siteseo'),
		'%%currentdate%%' => __('Current date', 'siteseo'),
		'%%currenttime%%' => __('Current time', 'siteseo'),
		'%%author_first_name%%' => __('Author first name', 'siteseo'),
		'%%author_last_name%%' => __('Author last name', 'siteseo'),
		'%%author_website%%' => __('Author website', 'siteseo'),
		'%%author_nickname%%' => __('Author nickname', 'siteseo'),
		'%%author_bio%%' => __('Author biography', 'siteseo'),
		'%%_ucf_your_user_meta%%' => __('Custom User Meta', 'siteseo'),
		'%%currentmonth_num%%' => __('Current month in digital format', 'siteseo'),
		'%%target_keyword%%' => __('Target keyword', 'siteseo'),
	]);
}

/**
 * @param string $classes
 *
 * @return string
 */
function siteseo_render_dyn_variables($classes){
	
	echo '<button type="button" class="'.esc_attr(siteseo_btn_secondary_classes()).' siteseo-tag-single-all siteseo-tag-dropdown '.esc_attr($classes).'"><span class="dashicons dashicons-arrow-down-alt2"></span></button>';
	
	$variables = siteseo_get_dyn_variables();
	if(!empty($variables)){
		echo '<div class="siteseo-wrap-tag-variables-list"><ul class="siteseo-tag-variables-list">';
		
		foreach($variables as $key => $value){
			echo '<li data-value=' . esc_attr($key) . ' tabindex="0"><span>' . esc_html($value) . '</span></li>';
		}
		
		echo '</ul></div>';
	}
}
