<?php 

namespace app\Admin\Meta;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

use app\Helpers as Helper;

/**
 * Post class for meta box. This contains the meta box,
 * HTML, form fields, everything to have a SimpleSEO work 
 * for pages and posts.
 *
 * @since  2.0.0
 */
class Post {

    public function __construct($post) {
		$form = new Helper\Form();

		wp_nonce_field(SSEO_PATH, 'sseo_nonce');

		$content = '<div class="cds-seo-metabox-tabs">';
		$content .= '<input id="cds-seo-tab1" type="radio" name="tab-group" checked="checked" />';
		$content .= '<label class="tab" for="cds-seo-tab1" class="active">'.__('SEO', SSEO_TXTDOMAIN).'</label>';
		$content .= '<input id="cds-seo-tab2" type="radio" name="tab-group" />';
		$content .= '<label class="tab" for="cds-seo-tab2">'.__('Keywords', SSEO_TXTDOMAIN).'</label>';
		$content .= '<input id="cds-seo-tab3" type="radio" name="tab-group" />';
		$content .= '<label class="tab" for="cds-seo-tab3">'.__('Robots', SSEO_TXTDOMAIN).'</label>';
		$content .= '<input id="cds-seo-tab4" type="radio" name="tab-group" />';
		$content .= '<label class="tab" for="cds-seo-tab4">'.__('Facebook', SSEO_TXTDOMAIN).'</label>';
		$content .= '<input id="cds-seo-tab5" type="radio" name="tab-group" />';
		$content .= '<label class="tab" for="cds-seo-tab5">'.__('Twitter', SSEO_TXTDOMAIN).'</label>';
		$content .= '<input id="cds-seo-tab6" type="radio" name="tab-group" />';
		$content .= '<label class="tab" for="cds-seo-tab6">'.__('Advanced', SSEO_TXTDOMAIN).'</label>';

		$content .= '<div id="cds-seo-preview" class="cds-seo-tab">';

		$sseo_meta_title = esc_html(get_post_meta($post->ID, 'sseo_meta_title', true));
		$sseo_meta_description = esc_html(get_post_meta($post->ID, 'sseo_meta_description', true));
		$sseo_canonical_url = esc_html(get_post_meta($post->ID, 'sseo_canonical_url', true));
		$sseo_meta_keywords = esc_html(get_post_meta($post->ID, 'sseo_meta_keywords', true));
		$sseo_robot_noindex = esc_html(get_post_meta($post->ID, 'sseo_robot_noindex', true));
		$sseo_robot_nofollow = esc_html(get_post_meta($post->ID, 'sseo_robot_nofollow', true));
		$sseo_fb_title = esc_html(get_post_meta($post->ID, 'sseo_fb_title', true));
		$sseo_fb_description = esc_html(get_post_meta($post->ID, 'sseo_fb_description', true));
		$sseo_fb_image = esc_html(get_post_meta($post->ID, 'sseo_fb_image', true));
		$sseo_tw_title = esc_html(get_post_meta($post->ID, 'sseo_tw_title', true));
		$sseo_tw_description = esc_html(get_post_meta($post->ID, 'sseo_tw_description', true));
		$sseo_tw_image = esc_html(get_post_meta($post->ID, 'sseo_tw_image', true));
		$current_url = get_permalink($post->ID);

		$content .= '<div class="cds-seo-section">';
		$content .= '<h3>'.__('Preview', SSEO_TXTDOMAIN).'</h3>';
		$content .= '<div class="preview_snippet">';
		$content .= '<div id="sseo_snippet">';
		$content .= '<a><span id="sseo_snippet_title">'.$sseo_meta_title.'</span></a>';
		$content .= '<div class="cds-seo-current-url">';
		$content .= '<cite id="sseo_snippet_link">'.$current_url.'</cite>';
		$content .= '</div>'; /* cds-seo-current-url */
		$content .= '<span id="sseo_snippet_description">'.esc_attr($sseo_meta_description).'</span>';
		$content .= '</div>'; /* sseo_snippet */
		$content .= '</div>'; /* preview_snippet */
		$content .= '</div>'; /* cds-seo-section */

		$content .= '<div class="cds-seo-section">';
		$content .= '<h3>';
		$content .= __('SEO', SSEO_TXTDOMAIN);
		$content .= '</h3>';

		$content .= $form->input('sseo_meta_title', array(
			'label' => __('Title', SSEO_TXTDOMAIN),
			'value' => $sseo_meta_title,
		));

		$content .= '<p><span id="sseo_title_count">0</span> '.__('characters. Most search engines use a maximum of 60 chars for the title.', SSEO_TXTDOMAIN).'</p>';

		$content .= $form->textarea('sseo_meta_description', array(
			'label' => __('Description', SSEO_TXTDOMAIN),
			'value' => $sseo_meta_description,
		));

		$content .= '<p><span id="sseo_desc_count">0</span> '.__('characters. Most search engines use a maximum of 160 chars for the description.', SSEO_TXTDOMAIN).'</p>';

		$content .= '</div>'; /* .cds-seo-section */
		$content .= '</div>'; /* #cds-seo-preview */

		$content .= '<div id="cds-seo-keywords" class="cds-seo-tab">';
		$content .= '<div class="cds-seo-section">';

		$content .= $form->textarea('sseo_meta_keywords', array(
			'label' => __('Keywords', SSEO_TXTDOMAIN),
			'value' => $sseo_meta_keywords,
		));

		$content .= '<p>';
		$content .= __('A comma separated list of your most important keywords for this page that will be written as META keywords.', SSEO_TXTDOMAIN);
		$content .= '</p>';

		$content .= '</div>'; /* .cds-seo-section */
		$content .= '</div>'; /* cds-seo-keywords */

		$content .= '<div id="cds-seo-robots" class="cds-seo-tab">';
		$content .= '<div class="cds-seo-section">';
		$content .= '<h3>'.__('Robots', SSEO_TXTDOMAIN).'</h3>';

		$content .= $form->input('sseo_robot_noindex', array(
			'type' => 'checkbox',
			'label' => __('Robots Meta NOINDEX', SSEO_TXTDOMAIN),
			'checked' => $sseo_robot_noindex,
		));

		$content .= $form->input('sseo_robot_nofollow', array(
			'type' => 'checkbox',
			'label' => __('Robots Meta NOFOLLOW', SSEO_TXTDOMAIN),
			'checked' => $sseo_robot_nofollow,
		));

		$content .= '</div>'; /* .cds-seo-section */
		$content .= '</div>'; /* #cds-seo-robots */



		$content .= '<div id="cds-seo-facebook" class="cds-seo-tab">';
		$content .= '<div class="cds-seo-section">';
		$content .= '<h3>'.__('Facebook', SSEO_TXTDOMAIN).'</h3>';

		$content .= $form->input('sseo_fb_title', array(
			'label' => __('Facebook Title', SSEO_TXTDOMAIN),
			'value' => $sseo_fb_title,
		));

		$content .='<p>';
		$content .= __('This is the title shared on Facebook, if left blank the default title for the post will be used.', SSEO_TXTDOMAIN);
		$content .= '</p>';

		$content .= $form->textarea('sseo_fb_description', array(
			'label' => __('Facebook Description', SSEO_TXTDOMAIN),
			'value' => $sseo_fb_description,
		));

		$content .= '<p>'.__('This is the description shared on Facebook, if left blank the default description for the post will be used.', SSEO_TXTDOMAIN).'</p>';
		
		$content .= '<div class="fb-img-container">';

		$image = null;
		if (intval($sseo_fb_image) > 0) {
			$image = wp_get_attachment_image($sseo_fb_image, 'medium', false, array('id' => 'sseo-fb-preview-image', 'class' => 'media-input', 'style' => 'display:block !important;'));
		} else {
			//$image = '<img id="sseo-fb-preview-image" src="" class="media-input" />';
		}

		$content .= $image.'</div><div class="clearfix">&nbsp;</div>';

		$content .= '<input type="hidden" name="sseo_fb_image" value="'.esc_attr($sseo_fb_image).'" id="sseo-fb-image" />
		<input type="button" class="button-primary sseo-media-button" value="'.__('Select an Image', SSEO_TXTDOMAIN).'" id="sseo_fb_media_manager"/>
		<input type="button" class="button-primary sseo-media-button" value="'.__('Remove Image', SSEO_TXTDOMAIN).'" id="sseo-fb-media-remove"/>';

		$content .= '<p>'.__('If you want to override the image used on Facebook for this post, upload / choose an image here. The size of the image file must not exceed 8 MB. Use images that are at least 1200 x 630 pixels for the best display on high resolution devices. At the minimum, you should use images that are 600 x 315 pixels to display link page posts with larger images.', SSEO_TXTDOMAIN).'</p>';

		$content .= '</div>'; /* .cds-seo-section */
		$content .= '</div>'; /* #cds-seo-facebook */



		$content .= '<div id="cds-seo-twitter" class="cds-seo-tab">';
		$content .= '<div class="cds-seo-section">';
		$content .= '<h3>'.__('Twitter', SSEO_TXTDOMAIN).'</h3>';

		$content .= $form->input('sseo_tw_title', array(
			'label' => __('Twitter Title', SSEO_TXTDOMAIN),
			'value' => $sseo_tw_title,
		));

		$content .= '<p>'.__('This is the title shared on Twitter, if left blank the default title for the post will be used.', SSEO_TXTDOMAIN).'</p>';

		$content .= $form->textarea('sseo_tw_description', array(
			'label' => __('Twitter Description', SSEO_TXTDOMAIN),
			'value' => $sseo_tw_description,
		));

		$content .= '<p>'.__('This is the description shared on Twitter, if left blank the default description for the post will be used.', SSEO_TXTDOMAIN).'</p>';
		
		$content .= '<div class="tw-img-container">';

		$image = null;
		if (intval($sseo_tw_image) > 0) {
			$image = wp_get_attachment_image($sseo_tw_image, 'medium', false, array('id' => 'sseo-tw-preview-image', 'class' => 'media-input', 'style' => 'display:block !important;'));
		} else {
			//$image = '<img id="sseo-tw-preview-image" src="" class="media-input" />';
		}

		$content .= $image.'</div><div class="clearfix">&nbsp;</div>';

		$content .= '<input type="hidden" name="sseo_tw_image" value="'.esc_attr($sseo_tw_image).'" id="sseo-tw-image" />
		<input type="button" class="button-primary sseo-media-button" value="'.__('Select an Image', SSEO_TXTDOMAIN).'" id="sseo_tw_media_manager"/>
		<input type="button" class="button-primary sseo-media-button" value="'.__('Remove Image', SSEO_TXTDOMAIN).'" id="sseo-tw-media-remove"/>';

		$content .= '<p>'.__('If you want to override the image used on Twitter for this post, upload / choose an image here. The recommended image size for Twitter is 1024 by 512 pixels.', SSEO_TXTDOMAIN).'</p>';

		$content .= '</div>'; /* .cds-seo-section */
		$content .= '</div>'; /* #cds-seo-twitter */

		$content .= '<div id="cds-seo-advanced" class="cds-seo-tab">';
		$content .= '<div class="cds-seo-section">';
		$content .= '<h3>'.__('Advanced', SSEO_TXTDOMAIN).'</h3>';

		$content .= $form->input('sseo_canonical_url', array(
			'label' => 'Canonical URL',
			'value' => $sseo_canonical_url,
		));
		$content .= '<div class="clearfix">&nbsp;</div>';

		$content .= '</div>'; /* .cds-seo-section */
		$content .= '</div>'; /* #cds-seo-advanced */

		$content .= '<div class="clearfix">&nbsp;</div>';
		$content .= '</div>'; /* .cds-seo-metabox-tabs */

		echo $content;
    }
	
}

?>