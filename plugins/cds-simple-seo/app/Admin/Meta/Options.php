<?php 

namespace app\Admin\Meta;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Options class for meta box. This contains the meta box,
 * HTML, form fields, everything to have a SimpleSEO 
 * options page.
 *
 * @since  2.0.0
 */
class Options {
	
    public function __construct() {
		
		if (!empty($_GET['sitemap_deleted'])) {
			echo '<div class="notice notice-success is-dismissible"><p>'.__('The current sitemap has been deleted!', SSEO_TXTDOMAIN).'</p></div>';
		}
		
		if (!empty($_GET['sitemap_created'])) {
			echo '<div class="notice notice-success is-dismissible"><p>'.__('A new sitemap has been created!', SSEO_TXTDOMAIN).'</p></div>';
		}
		
		if (!empty($_GET['yoast'])) {
			echo '<div class="notice notice-success is-dismissible"><p>'.__('Yoast metadata has been coppied to Simple SEO, awesome!', SSEO_TXTDOMAIN).'</p></div>';
		}
		
		if (!empty($_GET['allinone'])) {
			echo '<div class="notice notice-success is-dismissible"><p>'.__('All in One SEO metadata has been coppied to Simple SEO, awesome!', SSEO_TXTDOMAIN).'</p></div>';
		}
		
		if (!empty($_GET['rankmath'])) {
			echo '<div class="notice notice-success is-dismissible"><p>'.__('Rank Math metadata has been coppied to Simple SEO, awesome!', SSEO_TXTDOMAIN).'</p></div>';
		}
		
		echo '<div class="notice notice-info is-dismissible"><p>'.__('Please', SSEO_TXTDOMAIN).' <a href="https://checkout.square.site/merchant/CGD6KJ0N7YECM/checkout/BN3726JNC6C6P6HL3JKNX3LC" target="_blank">'.__('donate to Simple SEO', SSEO_TXTDOMAIN).'.</a> or <a href="https://wordpress.org/support/plugin/cds-simple-seo/reviews/" target="_blank">'.__('Leave a Review.', SSEO_TXTDOMAIN).'</a> Or Both! Please email <a href="mailto:dave@coleds.com">David Cole</a> with any questions.</p></div>';
		
		$content = '<div class="wrap">';
		$content .= '<h1>';
		$content .= __('Simple SEO Options', SSEO_TXTDOMAIN);
		$content .= '</h1>';
		
		$content .= '<form method="post" action="options.php" novalidate>';
		
		echo $content;
		
		settings_fields('sseo-settings-group');
		do_settings_sections('sseo-settings-group');
		
		$content = '<table class="form-table" role="presentation">';
		$content .= '<tbody>';
		
		$content .= '<tr>';
		$content .= '<th scope="row"><label for="sseo_default_meta_title">';
		$content .= __('Default Meta Title', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '<td>';
		$content .= '<input name="sseo_default_meta_title" type="text" id="sseo_default_meta_title" value="'.esc_attr(get_option('sseo_default_meta_title')).'" class="regular-text">';
		$content .= '<p class="description">';
		$content .= __('This is the default information used for meta title (&lt;title&gt;&lt;&#47;title&gt;) this information will only be used if there is no set home page. If a homepage is set, then the meta data will be used from that page.', SSEO_TXTDOMAIN);
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		$content .= '<tr>';
		$content .= '<th scope="row">';
		$content .= '<label for="sseo_default_meta_description">';
		$content .= __('Default Meta Description', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '</th>';
		$content .= '<td>';
		$content .= '<textarea name="sseo_default_meta_description" rows="5" cols="50" class="large-text code">'.esc_textarea(get_option('sseo_default_meta_description')).'</textarea>';
		$content .= '<p class="description">';
		$content .= __('This is the default information used for meta description (&lt;meta name="description" content=""&gt;) this information will only be used if there is no set home page. If a homepage is set, then the meta data will be used from that page.', SSEO_TXTDOMAIN);
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		$content .= '<tr>';
		$content .= '<th scope="row">';
		$content .= '<label for="sseo_default_meta_keywords">';
		$content .= __('Default Meta Keywords', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '</th>';
		$content .= '<td>';
		$content .= '<textarea name="sseo_default_meta_keywords" rows="5" cols="50" class="large-text code">'.esc_textarea(get_option('sseo_default_meta_keywords')).'</textarea>';
		$content .= '<p class="description">';
		$content .= __('This is the default information used for meta keywords (&lt;meta name="keywords" content=""&gt;) this information will only be used if there is no set home page. If a homepage is set, then the meta data will be used from that page.<br/><em>A comma separated list of your most important keywords for this will be written as the meta keywords.</em>', SSEO_TXTDOMAIN);
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		/* sitemap */
		$content .= '<tr>';
		$content .= '<th scope="row">'; 
		$content .= '<label for="sseo_generate_sitemap">';		
		$content .= __('Generate a Sitemap?', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '</th>';
		$content .= '<td>';       
		$sseo_sitemap_selected_n = null;
		$sseo_sitemap_selected_y = null;
		if (get_option('sseo_generate_sitemap') == true) {
			$sseo_sitemap_selected_y = ' selected="selected"';
		} else {
			$sseo_sitemap_selected_n = ' selected="selected"';
		}
		$content .= '<select name="sseo_generate_sitemap">';
		$content .= '<option value="0"'.$sseo_sitemap_selected_n.'>No</option>';
		$content .= '<option value="1"'.$sseo_sitemap_selected_y.'>Yes</option>';
		$content .= '</select>';
		$content .= '<p class="description">';
		$content .= __('If checked Simple SEO will generate a sitemap for your website. This will disable the default WordPress sitemap generator. You must click save changes below. Your current sitemap can be seen ');
		$content .= '<a href="/sitemap.xml" rel="noopener" target="_blank">';
		$content .= __('here.', SSEO_TXTDOMAIN);
		$content .= '</a>';
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		/* sitemap buttons */
		$content .= '<tr>';
		$content .= '<th scope="row">'; 
		$content .= '</th>';
		$content .= '<td>';  
		$adminUrlCreate = wp_nonce_url(admin_url('admin-post.php?action=sseo_create_sitemap'));
		$adminUrlDel = wp_nonce_url(admin_url('admin-post.php?action=sseo_delete_sitemap'));
		$content .= '<a href="'.$adminUrlCreate.'" class="button button-primary" onClick="return confirm(\'Are you sure you want to create a new sitemap?\')">Create Sitemap</a>&nbsp;<a href="'.$adminUrlDel.'" class="button button-primary" onClick="return confirm(\'Are you sure you want to delete the current sitemap?\')">Delete Sitemap</a>';
		$content .= '</td>';
		$content .= '</tr>';
		$content .= '<tr>';
		$content .= '<th scope="row">'; 
		$content .= 'Sitemap Post Types';
		$content .= '</th>';
		$content .= '<td>'; 
		
		$post_types = get_post_types(['public' => true], 'names');
		$sseo_sitemap_post_types = get_option('sseo_sitemap_post_types');
		$content .= '<select name="sseo_sitemap_post_types[]" multiple>';
		if (is_array($post_types)) {
			foreach($post_types as $post_type) {
				$selected = null;
				if (is_array($sseo_sitemap_post_types) && in_array($post_type, $sseo_sitemap_post_types)) {
					$selected = ' selected="selected"';	
				}
				$content .= '<option value="'.$post_type.'"'.$selected.'>'.$post_type.'</option>';
			}
		}
		$content .= '</select>';
		$content .= '</td>';
		$content .= '</tr>';
		
		/* sseo_gsite_verification */
		$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_gsite_verification">';
		$content .= __('Google Verification Code', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '<td>';
		$content .= '<input name="sseo_gsite_verification" type="text" id="sseo_gsite_verification" value="'.esc_attr(get_option('sseo_gsite_verification')).'" class="regular-text">';
		$content .= '<p class="description">';
		$content .= '<a href="https://support.google.com/webmasters/answer/35179?hl=en" target="_blank">';
		$content .= __('Click Here For Site Verification Code', SSEO_TXTDOMAIN);
		$content .= '</a>';
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		/* sseo_ganalytics */
		/*$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_ganalytics">';
		$content .= __('Google Analytics', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '<td>';
		$content .= '<input name="sseo_ganalytics" type="text" id="sseo_ganalytics" value="'.esc_attr(get_option('sseo_ganalytics')).'" class="regular-text">';
		$content .= '<p class="description">';
		$content .= __('Universal Analytics will be going away, ');
		$content .= '<a href="https://support.google.com/analytics/answer/10089681" rel="noopener" target="_blank">';
		$content .= __('Google Analytics 4 properties', SSEO_TXTDOMAIN);
		$content .= '</a>. ';
		$content .= __('Refer to the ');
		$content .= '<a href="https://support.google.com/analytics#topic=10094551" rel="noopener" target="_blank">';
		$content .= __('Universal Analytics section', SSEO_TXTDOMAIN);
		$content .= '</a> ';
		$content .= __('if you\'re still using a Universal Analytics property, which will ');
		$content .= '<a href="https://support.google.com/analytics/answer/11583528" rel="noopener" target="_blank">';
		$content .= __('stop processing data', SSEO_TXTDOMAIN);
		$content .= '</a> ';
		$content .= __('on July 1, 2023 (July 1, 2024 for Analytics 360 properties).', SSEO_TXTDOMAIN);
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';*/
		
		/* sseo_g4analytics */
		$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_g4analytics">';
		$content .= __('Google Analytics 4', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '<td>';
		$content .= '<input name="sseo_g4analytics" type="text" id="sseo_g4analytics" value="'.esc_attr(get_option('sseo_g4analytics')).'" class="regular-text">';
		$content .= '<p class="description">';
		$content .= '<a href="https://support.google.com/analytics/answer/10089681?hl=en&ref_topic=9143232" target="_blank">Get Your Code</a>';
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		/* Bing verification code */
		$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_bing">Bing Verification';
		$content .= __('Bing Verification Code', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '<td>';
		$content .= '<input name="sseo_bing" type="text" id="sseo_bing" value="'.esc_attr(get_option('sseo_bing')).'" class="regular-text">';
		$content .= '<p class="description">';
		$content .= '<a href="https://www.bing.com/webmasters/about#/Dashboard/" target="_blank">';
		$content .= __('Click Here To Get Your Bing Code', SSEO_TXTDOMAIN);
		$content .= '</a>';
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		/* Yandex verification code */
		$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_yandex">';
		$content .= __('Yandex Verification Code', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '<td>';
		$content .= '<input name="sseo_yandex" type="text" id="sseo_yandex" value="'.esc_attr(get_option('sseo_yandex')).'" class="regular-text">';
		$content .= '<p class="description">';
		$content .= '<a href="https://webmaster.yandex.com/sites/add/" target="_blank">';
		$content .= __('Click Here To Get Your Yandex Code', SSEO_TXTDOMAIN);
		$content .= '</a>';
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		/* Facebook verification code */
		$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_fb_app_id">';
		$content .= __('Facebook App ID', SSEO_TXTDOMAIN);
		$content .= '</label>';
		$content .= '<td>';
		$content .= '<input name="sseo_fb_app_id" type="text" id="sseo_fb_app_id" value="'.esc_attr(get_option('sseo_fb_app_id')).'" class="regular-text">';
		$content .= '<p class="description">';
		$content .= '<a href="https://developers.facebook.com/docs/apps/" target="_blank">';
		$content .= __('Click Here To Get Your Facebook App ID', SSEO_TXTDOMAIN);
		$content .= '</a>';
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		
		/* sseo_twitter_username */
		/*$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_twitter_username">';
		$content .= __('Twitter username');
		$content .= '</label>';
		$content .= '<td>';
		$content .= '<input name="sseo_twitter_username" type="text" id="sseo_twitter_username" value="'.esc_attr(get_option('sseo_twitter_username')).'" class="regular-text">';
		$content .= '<p class="description">';
		$content .= __('for example @coledesignstudios');
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_fb_fimage">';
		$content .= __('Facebook Image');
		$content .= '</label>';
		$content .= '</th>';
		$content .= '<td>';
		$content .= '<select name="sseo_fb_fimage">';
		$selected = null;
		$sseo_fb_fimage = esc_attr(get_option('sseo_fb_fimage'));
		if (!empty($sseo_fb_fimage)) {
			$selected = ' selected="selected"';
		}
		$content .= '<option value="0"'.$selected.'>No</option>';
		$content .= '<option value="1"'.$selected.'>Yes</option>';
		$content .= '</select>';
		$content .= '<p class="description">';
		$content .= __('Use the post featured image instead of the facebook image. By default the featured image is used if there is no facebook image.');
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';
		$content .= '<tr>';
		$content .= '<th scope="row"><label class="post-attributes-label" for="sseo_tw_fimage">';
		$content .= __('Twitter Image');
		$content .= '</label>';
		$content .= '</th>';
		$content .= '<td>';
		$content .= '<select name="sseo_tw_fimage">';
		$selected = null;
		$sseo_tw_fimage = esc_attr(get_option('sseo_tw_fimage'));
		if (!empty($sseo_tw_fimage)) {
			$selected = ' selected="selected"';
		}
		$content .= '<option value="0"'.$selected.'>No</option>';
		$content .= '<option value="1"'.$selected.'>Yes</option>';
		$content .= '</select>';
		$content .= '<p class="description">';
		$content .= __('Use the post featured image instead of the twitter image. By default the featured image is used if there is no twitter image.');
		$content .= '</p>';
		$content .= '</td>';
		$content .= '</tr>';*/
		$content .= '</tbody>';
		$content .= '</table>';
		
		$content .= '<input type="submit" name="submit" id="submit" class="button button-primary" value="';
		$content .= __('Save Changes', SSEO_TXTDOMAIN);
		$content .= '">&nbsp;&nbsp;';
		
		$yoast_url = wp_nonce_url(admin_url('admin-post.php?action=sseo_yoast_import'));
		$content .= '<a href="'.$yoast_url.'" class="button button-primary" onclick="return confirm(';
		$content .= __('Are you sure you want to copy all Yoast title and description tags to Simple SEO? This will overwrite all Simple SEO data.');
		$content .= ')">';
		$content .= __('Import Yoast SEO Data', SSEO_TXTDOMAIN);
		$content .= '</a>&nbsp;&nbsp;';
		
		$rankmath_url = wp_nonce_url(admin_url('admin-post.php?action=sseo_rankmath_import'));
		$content .= '<a href="'.$rankmath_url.'" class="button button-primary" onclick="return confirm(';
		$content .= __('Are you sure you want to copy all Rank Math title and description tags to Simple SEO? This will overwrite all Simple SEO data.');
		$content .= ')">';
		$content .= __('Import Rank Math SEO Data', SSEO_TXTDOMAIN);
		$content .= '</a>&nbsp;&nbsp;';
		
		$aioseo_url = wp_nonce_url(admin_url('admin-post.php?action=sseo_allinone_import'));
		$content .= '<a href="'.$aioseo_url.'" class="button button-primary" onclick="return confirm(';
		$content .= __('Are you sure you want to copy all All In One SEO title and description tags to Simple SEO? This will overwrite all Simple SEO data.');
		$content .= ')">';
		$content .= __('Import All In One SEO Data', SSEO_TXTDOMAIN);
		$content .= '</a>&nbsp;&nbsp;';
		
		$content .= '</form>';
		
		$content .= '<p>Simple SEO ' . SSEO_VERSION;
		
		$content .= '</div>';
		
		echo $content;
    }
	
}

?>