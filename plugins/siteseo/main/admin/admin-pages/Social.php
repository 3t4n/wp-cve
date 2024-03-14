<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

function siteseo_social_knowledge_type_callback(){
	$options = get_option('siteseo_social_option_name');

	$selected = isset($options['social_knowledge_type']) ? $options['social_knowledge_type'] : null; ?>

<select id="siteseo_social_knowledge_type" name="siteseo_social_option_name[social_knowledge_type]">
	<option <?php if ('none' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="none"><?php esc_html_e('None (will disable this feature)', 'siteseo'); ?>
	</option>
	<option <?php if ('Person' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="Person"><?php esc_html_e('Person', 'siteseo'); ?>
	</option>
	<option <?php if ('Organization' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="Organization"><?php esc_html_e('Organization', 'siteseo'); ?>
	</option>
</select>

<?php 
}

function siteseo_social_knowledge_name_callback(){
	$options = get_option('siteseo_social_option_name');
	$check = isset($options['social_knowledge_name']) ? $options['social_knowledge_name'] : null;

	printf(
		'<input type="text" name="siteseo_social_option_name[social_knowledge_name]" placeholder="' . esc_html__('eg: Miremont', 'siteseo') . '" aria-label="' . esc_html__('Your name/organization', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_social_knowledge_img_callback(){
	$options = get_option('siteseo_social_option_name');

	$options_set = isset($options['social_knowledge_img']) ? esc_attr($options['social_knowledge_img']) : null;

	$check = isset($options['social_knowledge_img']); ?>

<input id="siteseo_social_knowledge_img_meta" type="text"
	value="<?php echo esc_attr($options_set); ?>"
	name="siteseo_social_option_name[social_knowledge_img]"
	aria-label="<?php esc_html_e('Your photo/organization logo', 'siteseo'); ?>"
	placeholder="<?php esc_html_e('Select your logo', 'siteseo'); ?>" />

<input id="siteseo_social_knowledge_img_upload" class="btn btnSecondary" type="button"
	value="<?php esc_html_e('Upload an Image', 'siteseo'); ?>" />

<p class="description"><?php esc_html_e('JPG, PNG, WebP and GIF allowed.', 'siteseo'); ?></p>

<img style="width:300px;max-height:400px;"
	src="<?php echo esc_attr(siteseo_get_service('SocialOption')->getSocialKnowledgeImage()); ?>" />

<?php
}

function siteseo_social_knowledge_phone_callback(){
	$options = get_option('siteseo_social_option_name');
	$check = isset($options['social_knowledge_phone']) ? $options['social_knowledge_phone'] : null;

	printf(
		'<input type="text" name="siteseo_social_option_name[social_knowledge_phone]" placeholder="' . esc_html__('eg: +33123456789 (internationalized version required)', 'siteseo') . '" aria-label="' . esc_html__('Organization\'s phone number (only for Organizations)', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_social_knowledge_contact_type_callback(){
	$options = get_option('siteseo_social_option_name');

	$selected = isset($options['social_knowledge_contact_type']) ? $options['social_knowledge_contact_type'] : null; ?>

<select id="siteseo_social_knowledge_contact_type"
	name="siteseo_social_option_name[social_knowledge_contact_type]">
	<option <?php if ('customer support' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="customer support"><?php esc_html_e('Customer support', 'siteseo'); ?>
	</option>
	<option <?php if ('technical support' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="technical support"><?php esc_html_e('Technical support', 'siteseo'); ?>
	</option>
	<option <?php if ('billing support' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="billing support"><?php esc_html_e('Billing support', 'siteseo'); ?>
	</option>
	<option <?php if ('bill payment' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="bill payment"><?php esc_html_e('Bill payment', 'siteseo'); ?>
	</option>
	<option <?php if ('sales' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="sales"><?php esc_html_e('Sales', 'siteseo'); ?>
	</option>
	<option <?php if ('credit card support' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="credit card support"><?php esc_html_e('Credit card support', 'siteseo'); ?>
	</option>
	<option <?php if ('emergency' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="emergency"><?php esc_html_e('Emergency', 'siteseo'); ?>
	</option>
	<option <?php if ('baggage tracking' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="baggage tracking"><?php esc_html_e('Baggage tracking', 'siteseo'); ?>
	</option>
	<option <?php if ('roadside assistance' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="roadside assistance"><?php esc_html_e('Roadside assistance', 'siteseo'); ?>
	</option>
	<option <?php if ('package tracking' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="package tracking"><?php esc_html_e('Package tracking', 'siteseo'); ?>
	</option>
</select>

<?php
}

function siteseo_social_knowledge_contact_option_callback(){
	$options = get_option('siteseo_social_option_name');

	$selected = isset($options['social_knowledge_contact_option']) ? $options['social_knowledge_contact_option'] : null; ?>

<select id="siteseo_social_knowledge_contact_option"
	name="siteseo_social_option_name[social_knowledge_contact_option]">
	<option <?php if ('None' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="None"><?php esc_html_e('None', 'siteseo'); ?>
	</option>
	<option <?php if ('TollFree' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="TollFree"><?php esc_html_e('Toll Free', 'siteseo'); ?>
	</option>
	<option <?php if ('HearingImpairedSupported' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="HearingImpairedSupported"><?php esc_html_e('Hearing impaired supported', 'siteseo'); ?>
	</option>
</select>

<?php
}

function siteseo_social_accounts_facebook_callback(){
	$options = get_option('siteseo_social_option_name');
	$check   = isset($options['social_accounts_facebook']) ? $options['social_accounts_facebook'] : null;

	printf(
		'<input type="text" name="siteseo_social_option_name[social_accounts_facebook]" placeholder="' . esc_html__('eg: https://facebook.com/my-page-url', 'siteseo') . '" aria-label="' . esc_html__('Facebook Page URL', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_social_accounts_twitter_callback(){
	$options = get_option('siteseo_social_option_name');
	$check = isset($options['social_accounts_twitter']) ? $options['social_accounts_twitter'] : null;

	printf(
		'<input type="text" name="siteseo_social_option_name[social_accounts_twitter]" placeholder="' . esc_html__('eg: @my_twitter_account', 'siteseo') . '" aria-label="' . esc_html__('Twitter Page URL', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_social_accounts_pinterest_callback(){
	$options = get_option('siteseo_social_option_name');
	$check   = isset($options['social_accounts_pinterest']) ? $options['social_accounts_pinterest'] : null;

	printf(
		'<input type="text" name="siteseo_social_option_name[social_accounts_pinterest]" placeholder="' . esc_html__('eg: https://pinterest.com/my-page-url/', 'siteseo') . '" aria-label="' . esc_html__('Pinterest URL', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_social_accounts_instagram_callback(){
	$options = get_option('siteseo_social_option_name');
	$check   = isset($options['social_accounts_instagram']) ? $options['social_accounts_instagram'] : null;

	printf(
		'<input type="text" name="siteseo_social_option_name[social_accounts_instagram]" placeholder="' . esc_html__('eg: https://www.instagram.com/my-page-url/', 'siteseo') . '" aria-label="' . esc_html__('Instagram URL', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_social_accounts_youtube_callback(){
	$options = get_option('siteseo_social_option_name');
	$check   = isset($options['social_accounts_youtube']) ? $options['social_accounts_youtube'] : null;

	printf(
		'<input type="text" name="siteseo_social_option_name[social_accounts_youtube]" placeholder="' . esc_html__('eg: https://www.youtube.com/my-channel-url', 'siteseo') . '" aria-label="' . esc_html__('YouTube URL', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_social_accounts_linkedin_callback(){
	$options = get_option('siteseo_social_option_name');
	$check   = isset($options['social_accounts_linkedin']) ? $options['social_accounts_linkedin'] : null;

	printf(
		'<input type="text" name="siteseo_social_option_name[social_accounts_linkedin]" placeholder="' . esc_html__('eg: http://linkedin.com/company/my-company-url/', 'siteseo') . '" aria-label="' . esc_html__('LinkedIn URL', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_social_facebook_og_callback(){
	$options = get_option('siteseo_social_option_name');

	$check = isset($options['social_facebook_og']); ?>

<label for="siteseo_social_facebook_og">
	<input id="siteseo_social_facebook_og" name="siteseo_social_option_name[social_facebook_og]"
		type="checkbox" <?php checked($check, '1') ?>
	value="1"/>

	<?php esc_html_e('Enable OG data', 'siteseo'); ?>
</label>

<?php
}

function siteseo_social_facebook_img_callback(){
	$options = get_option('siteseo_social_option_name');

	$options_set = isset($options['social_facebook_img']) ? esc_attr($options['social_facebook_img']) : null;
	$options_set_attachment_id = isset($options['siteseo_social_facebook_img_attachment_id']) ? esc_attr($options['siteseo_social_facebook_img_attachment_id']) : null;
	$options_set_width = isset($options['siteseo_social_facebook_img_width']) ? esc_attr($options['siteseo_social_facebook_img_width']) : null;
	$options_set_height = isset($options['siteseo_social_facebook_img_height']) ? esc_attr($options['siteseo_social_facebook_img_height']) : null;

	?>

<input id="siteseo_social_fb_img_meta" type="text"
	value="<?php echo esc_attr($options_set); ?>"
	name="siteseo_social_option_name[social_facebook_img]"
	aria-label="<?php esc_html_e('Select a default image', 'siteseo'); ?>"
	placeholder="<?php esc_html_e('Select your default thumbnail', 'siteseo'); ?>" />


<input type="hidden" name="siteseo_social_facebook_img_width" id="siteseo_social_fb_img_width" value="<?php echo esc_html($options_set_width); ?>">
<input type="hidden" name="siteseo_social_facebook_img_height" id="siteseo_social_fb_img_height" value="<?php echo esc_html($options_set_height); ?>">
<input type="hidden" name="siteseo_social_facebook_img_attachment_id" id="siteseo_social_fb_img_attachment_id" value="<?php echo esc_html($options_set_attachment_id); ?>">

<input id="siteseo_social_fb_img_upload" class="btn btnSecondary" type="button"
	value="<?php esc_html_e('Upload an Image', 'siteseo'); ?>" />

<p class="description"><?php esc_html_e('Minimum size: 200x200px, ideal ratio 1.91:1, 8Mb max. (eg: 1640x856px or 3280x1712px for retina screens)', 'siteseo'); ?>
</p>
<p class="description"><?php esc_html_e('If no default image is set, we‘ll use your site icon defined from the Customizer.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_social_facebook_img_default_callback(){
	$options = get_option('siteseo_social_option_name');

	$check = isset($options['social_facebook_img_default']); ?>

<label for="siteseo_social_facebook_img_default">
	<input id="siteseo_social_facebook_img_default"
		name="siteseo_social_option_name[social_facebook_img_default]" type="checkbox" <?php checked($check, '1') ?>
	value="1"/>
	<?php echo wp_kses_post(__('Override every <strong>og:image</strong> tag with this default image (except if a custom og:image has already been set from the SEO metabox).', 'siteseo')); ?>
</label>

<?php $def_og_img = isset($options['social_facebook_img']) ? $options['social_facebook_img'] : '';

	if ('' == $def_og_img) { ?>
<div class="siteseo-notice is-warning is-inline">
	<p>
		<?php echo wp_kses_post(__('Please define a <strong>default OG Image</strong> from the field above', 'siteseo')); ?>
	</p>
</div>
<?php }

}

function siteseo_social_facebook_img_cpt_callback(){
	$post_types = siteseo_get_service('WordPressData')->getPostTypes();
	if (! empty($post_types)) {
		unset($post_types['post'], $post_types['page']);

		if (! empty($post_types)) {
			foreach ($post_types as $siteseo_cpt_key => $siteseo_cpt_value) { ?>
<h3><?php echo esc_html($siteseo_cpt_value->labels->name); ?>
	<em><small>[<?php echo esc_html($siteseo_cpt_value->name); ?>]</small></em>
</h3>

<?php if ('product' === $siteseo_cpt_value->name && is_plugin_active('woocommerce/woocommerce.php')) { ?>
<p>
	<?php esc_html_e('WooCommerce Shop Page.', 'siteseo'); ?>
</p>
<?php }

				$options = get_option('siteseo_social_option_name');

				$options_set = isset($options['social_facebook_img_cpt'][$siteseo_cpt_key]['url']) ? esc_attr($options['social_facebook_img_cpt'][$siteseo_cpt_key]['url']) : null;
				?>

<p>
	<input
		id="siteseo_social_facebook_img_cpt_meta_<?php echo esc_attr($siteseo_cpt_key); ?>"
		class="siteseo_social_facebook_img_cpt_meta" type="text"
		value="<?php echo esc_attr($options_set); ?>"
		name="siteseo_social_option_name[social_facebook_img_cpt][<?php echo esc_attr($siteseo_cpt_key); ?>][url]"
		aria-label="<?php esc_html_e('Select a default image', 'siteseo'); ?>"
		placeholder="<?php esc_html_e('Select your default thumbnail', 'siteseo'); ?>" />

	<input
		id="siteseo_social_facebook_img_upload"
		class="siteseo_social_facebook_img_cpt siteseo-btn-upload-media btn btnSecondary"
		data-input-value="#siteseo_social_facebook_img_cpt_meta_<?php echo esc_attr($siteseo_cpt_key); ?>"
		type="button"
		value="<?php esc_html_e('Upload an Image', 'siteseo'); ?>" />

</p>

<?php 
			}
		} else { ?>
<p>
	<?php esc_html_e('No custom post type to configure.', 'siteseo'); ?>
</p>
<?php }
	}
}

function siteseo_social_facebook_link_ownership_id_callback(){
	$options = get_option('siteseo_social_option_name');
	$check = isset($options['social_facebook_link_ownership_id']) ? $options['social_facebook_link_ownership_id'] : null;

	printf(
		'<input type="text" placeholder="' . esc_html__('1234567890','siteseo') . '" name="siteseo_social_option_name[social_facebook_link_ownership_id]" value="%s"/>',
		esc_html($check)
	); ?>

<p class="description">
	<?php esc_html_e('One or more Facebook Page IDs that are associated with a URL in order to enable link editing and instant article publishing.', 'siteseo'); ?>
</p>

<pre>&lt;meta property="fb:pages" content="page ID"/&gt;</pre>

<p>
	<span class="siteseo-help dashicons dashicons-external"></span>
	<a class="siteseo-help" href="https://www.facebook.com/help/1503421039731588" target="_blank">
		<?php esc_html_e('How do I find my Facebook Page ID?', 'siteseo'); ?>
	</a>
</p>
<?php
}

function siteseo_social_facebook_admin_id_callback(){
	$options = get_option('siteseo_social_option_name');
	$check   = isset($options['social_facebook_admin_id']) ? $options['social_facebook_admin_id'] : null;

	printf(
		'<input type="text" placeholder="' . esc_html__('1234567890','siteseo') . '" name="siteseo_social_option_name[social_facebook_admin_id]" value="%s"/>',
		esc_html($check)
	); ?>

<p class="description">
	<?php esc_html_e('The ID (or comma-separated list for properties that can accept multiple IDs) of an app, person using the app, or Page Graph API object.', 'siteseo'); ?>
</p>

<pre>&lt;meta property="fb:admins" content="admins ID"/&gt;</pre>

<?php
}

function siteseo_social_facebook_app_id_callback(){
	$options = get_option('siteseo_social_option_name');
	$check = isset($options['social_facebook_app_id']) ? $options['social_facebook_app_id'] : null;

	printf(
		'<input type="text" placeholder="' . esc_html__('1234567890','siteseo') . '" name="siteseo_social_option_name[social_facebook_app_id]" value="%s"/>',
		esc_html($check)
	); ?>

<p class="description">
	<?php echo wp_kses_post(__('The Facebook app ID of the site\'s app. In order to use Facebook Insights you must add the app ID to your page. Insights lets you view analytics for traffic to your site from Facebook. Find the app ID in your App Dashboard. <a class="siteseo-help" href="https://developers.facebook.com/apps/redirect/dashboard" target="_blank">More info here</a> <span class="siteseo-help dashicons dashicons-external"></span>', 'siteseo')); ?>
</p>

<pre>&lt;meta property="fb:app_id" content="app ID"/&gt;</pre>

<p>
	<span class="siteseo-help dashicons dashicons-external"></span>
	<a class="siteseo-help" href="https://developers.facebook.com/docs/apps/register" target="_blank">
		<?php esc_html_e('How to create a Facebook App ID', 'siteseo'); ?>
	</a>
</p>
<?php
}

function siteseo_social_twitter_card_callback(){
	$options = get_option('siteseo_social_option_name');

	$check = isset($options['social_twitter_card']); ?>

<label for="siteseo_social_twitter_card">
	<input id="siteseo_social_twitter_card" name="siteseo_social_option_name[social_twitter_card]"
		type="checkbox" <?php checked($check, '1') ?>
	value="1"/>

	<?php esc_html_e('Enable Twitter card', 'siteseo'); ?>
</label>

<?php
}

function siteseo_social_twitter_card_og_callback(){
	$options = get_option('siteseo_social_option_name');

	$check = isset($options['social_twitter_card_og']); ?>

<label for="siteseo_social_twitter_card_og">
	<input id="siteseo_social_twitter_card_og" name="siteseo_social_option_name[social_twitter_card_og]"
		type="checkbox" <?php checked($check, '1') ?>
	value="1"/>

	<?php esc_html_e('Use OG if no Twitter Cards', 'siteseo'); ?>
</label>

<?php
}

function siteseo_social_twitter_card_img_callback(){
	$options = get_option('siteseo_social_option_name');

	$options_set = isset($options['social_twitter_card_img']) ? esc_attr($options['social_twitter_card_img']) : null;
	
	?>

<input id="siteseo_social_twitter_img_meta" type="text"
	value="<?php echo esc_attr($options_set); ?>"
	name="siteseo_social_option_name[social_twitter_card_img]"
	aria-label="<?php esc_html_e('Default Twitter Image', 'siteseo'); ?>"
	placeholder="<?php esc_html_e('Select your default thumbnail', 'siteseo'); ?>" />

<input id="siteseo_social_twitter_img_upload" class="btn btnSecondary" type="button"
	value="<?php esc_html_e('Upload an Image', 'siteseo'); ?>" />

<p class="description">
	<?php esc_html_e('Minimum size: 144x144px (300x157px with large card enabled), ideal ratio 1:1 (2:1 with large card), 5Mb max.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_social_twitter_card_img_size_callback(){
	$options = get_option('siteseo_social_option_name');

	$selected = isset($options['social_twitter_card_img_size']) ? $options['social_twitter_card_img_size'] : null; ?>

<select id="siteseo_social_twitter_card_img_size"
	name="siteseo_social_option_name[social_twitter_card_img_size]">
	<option <?php if ('default' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="default"><?php esc_html_e('Default', 'siteseo'); ?>
	</option>
	<option <?php if ('large' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="large"><?php esc_html_e('Large', 'siteseo'); ?>
	</option>
</select>

<p class="description">
	<?php echo wp_kses_post(__('The Summary Card with <strong>Large Image</strong> features a large, full-width prominent image alongside a tweet. It is designed to give the reader a rich photo experience, and clicking on the image brings the user to your website.', 'siteseo')); ?>
</p>

<?php
}


function siteseo_print_section_info_social_knowledge()
{
	?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Knowledge Graph', 'siteseo'); ?>
	</h2>
</div>

<p>
	<?php esc_html_e('Configure Google Knowledge Graph.', 'siteseo'); ?>
</p>

<p class="siteseo-help">
	<span class="dashicons dashicons-external"></span>
	<a href="https://developers.google.com/search/docs/guides/enhance-site" target="_blank">
		<?php esc_html_e('Learn more on Google official website.', 'siteseo'); ?>
	</a>
</p>

<?php
}

function siteseo_print_section_info_social_accounts()
{
	?>

<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Your social accounts', 'siteseo'); ?>
	</h2>
</div>

<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<div>
		<p>
			<?php esc_html_e('Link your site with your social accounts.', 'siteseo'); ?>
		<p>
			<?php esc_html_e('Use markup on your website to add your social profile information to a Google Knowledge panel.', 'siteseo'); ?>
		</p>
		<p>
			<?php esc_html_e('Knowledge panels prominently display your social profile information in some Google Search results.', 'siteseo'); ?>
		</p>
		<p>
			<?php esc_html_e('Filling in these fields does not guarantee the display of this data in search results.', 'siteseo'); ?>
		</p>
	</div>
</div>

<?php
}

function siteseo_print_section_info_social_facebook()
{
	$docs  = siteseo_get_docs_links(); ?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Facebook (Open Graph)', 'siteseo'); ?>
	</h2>
</div>

<p>
	<?php esc_html_e('Manage Open Graph data. These metatags will be used by Facebook, Pinterest, LinkedIn, WhatsApp... when a user shares a link on its own social network. Increase your click-through rate by providing relevant information such as an attractive image.', 'siteseo'); ?>
	<?php echo wp_kses_post(siteseo_tooltip_link($docs['social']['og'], esc_html__('Manage Facebook Open Graph and Twitter Cards metas - new window', 'siteseo'))); ?>
</p>

<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<div>
		<p>
			<?php echo wp_kses_post(__('We generate the <strong>og:image</strong> meta in this order:', 'siteseo')); ?>
		</p>

		<ol>
			<li>
				<?php esc_html_e('Custom OG Image from SEO metabox', 'siteseo'); ?>
			</li>
			<li>
				<?php esc_html_e('Post thumbnail / Product category thumbnail (Featured image)', 'siteseo'); ?>
			</li>
			<li>
				<?php esc_html_e('First image of your post content', 'siteseo'); ?>
			</li>
			<li>
				<?php esc_html_e('Global OG Image set in SEO > Social > Open Graph', 'siteseo'); ?>
			</li>
			<li>
				<?php esc_html_e('Site icon from the Customizer', 'siteseo'); ?>
			</li>
		</ol>
	</div>
</div>

<?php
}

function siteseo_print_section_info_social_twitter()
{
	$docs  = siteseo_get_docs_links(); ?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Twitter (Twitter card)', 'siteseo'); ?>
	</h2>
</div>
<p>
	<?php esc_html_e('Manage your Twitter card.', 'siteseo'); ?>
	<?php echo wp_kses_post(siteseo_tooltip_link($docs['social']['og'], esc_html__('Manage Facebook Open Graph and Twitter Cards metas - new window', 'siteseo'))); ?>
</p>

<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<div>
		<p>
			<?php echo wp_kses_post(__('We generate the <strong>twitter:image</strong> meta in this order:', 'siteseo')); ?>
		</p>

		<ol>
			<li>
				<?php esc_html_e('Custom Twitter image from SEO metabox', 'siteseo'); ?>
			</li>
			<li>
				<?php esc_html_e('Post thumbnail / Product category thumbnail (Featured image)', 'siteseo'); ?>
			</li>
			<li>
				<?php esc_html_e('First image of your post content', 'siteseo'); ?>
			</li>
			<li>
				<?php esc_html_e('Global Twitter:image set in SEO > Social > Twitter Card', 'siteseo'); ?>
			</li>
		</ol>
	</div>
</div>

<?php
}

//Knowledge graph SECTION======================================================================
add_settings_section(
	'siteseo_setting_section_social_knowledge', // ID
	'',
	//__("Knowledge graph","siteseo"), // Title
	'siteseo_print_section_info_social_knowledge', // Callback
	'siteseo-settings-admin-social-knowledge' // Page
);

add_settings_field(
	'siteseo_social_knowledge_type', // ID
	__('Person or organization', 'siteseo'), // Title
	'siteseo_social_knowledge_type_callback', // Callback
	'siteseo-settings-admin-social-knowledge', // Page
	'siteseo_setting_section_social_knowledge' // Section
);

add_settings_field(
	'siteseo_social_knowledge_name', // ID
	__('Your name/organization', 'siteseo'), // Title
	'siteseo_social_knowledge_name_callback', // Callback
	'siteseo-settings-admin-social-knowledge', // Page
	'siteseo_setting_section_social_knowledge' // Section
);

add_settings_field(
	'siteseo_social_knowledge_img', // ID
	__('Your photo/organization logo', 'siteseo'), // Title
	'siteseo_social_knowledge_img_callback', // Callback
	'siteseo-settings-admin-social-knowledge', // Page
	'siteseo_setting_section_social_knowledge' // Section
);

add_settings_field(
	'siteseo_social_knowledge_phone', // ID
	__("Organization's phone number (only for Organizations)", 'siteseo'), // Title
	'siteseo_social_knowledge_phone_callback', // Callback
	'siteseo-settings-admin-social-knowledge', // Page
	'siteseo_setting_section_social_knowledge' // Section
);

add_settings_field(
	'siteseo_social_knowledge_contact_type', // ID
	__('Contact type (only for Organizations)', 'siteseo'), // Title
	'siteseo_social_knowledge_contact_type_callback', // Callback
	'siteseo-settings-admin-social-knowledge', // Page
	'siteseo_setting_section_social_knowledge' // Section
);

add_settings_field(
	'siteseo_social_knowledge_contact_option', // ID
	__('Contact option (only for Organizations)', 'siteseo'), // Title
	'siteseo_social_knowledge_contact_option_callback', // Callback
	'siteseo-settings-admin-social-knowledge', // Page
	'siteseo_setting_section_social_knowledge' // Section
);

//Social SECTION=====================================================================================
add_settings_section(
	'siteseo_setting_section_social_accounts', // ID
	'',
	//__("Social","siteseo"), // Title
	'siteseo_print_section_info_social_accounts', // Callback
	'siteseo-settings-admin-social-accounts' // Page
);

add_settings_field(
	'siteseo_social_accounts_facebook', // ID
	__('Facebook Page URL', 'siteseo'), // Title
	'siteseo_social_accounts_facebook_callback', // Callback
	'siteseo-settings-admin-social-accounts', // Page
	'siteseo_setting_section_social_accounts' // Section
);

add_settings_field(
	'siteseo_social_accounts_twitter', // ID
	__('Twitter Username', 'siteseo'), // Title
	'siteseo_social_accounts_twitter_callback', // Callback
	'siteseo-settings-admin-social-accounts', // Page
	'siteseo_setting_section_social_accounts' // Section
);

add_settings_field(
	'siteseo_social_accounts_pinterest', // ID
	__('Pinterest URL', 'siteseo'), // Title
	'siteseo_social_accounts_pinterest_callback', // Callback
	'siteseo-settings-admin-social-accounts', // Page
	'siteseo_setting_section_social_accounts' // Section
);

add_settings_field(
	'siteseo_social_accounts_instagram', // ID
	__('Instagram URL', 'siteseo'), // Title
	'siteseo_social_accounts_instagram_callback', // Callback
	'siteseo-settings-admin-social-accounts', // Page
	'siteseo_setting_section_social_accounts' // Section
);

add_settings_field(
	'siteseo_social_accounts_youtube', // ID
	__('YouTube URL', 'siteseo'), // Title
	'siteseo_social_accounts_youtube_callback', // Callback
	'siteseo-settings-admin-social-accounts', // Page
	'siteseo_setting_section_social_accounts' // Section
);

add_settings_field(
	'siteseo_social_accounts_linkedin', // ID
	__('LinkedIn URL', 'siteseo'), // Title
	'siteseo_social_accounts_linkedin_callback', // Callback
	'siteseo-settings-admin-social-accounts', // Page
	'siteseo_setting_section_social_accounts' // Section
);

//Facebook SECTION=========================================================================
add_settings_section(
	'siteseo_setting_section_social_facebook', // ID
	'',
	//__("Facebook","siteseo"), // Title
	'siteseo_print_section_info_social_facebook', // Callback
	'siteseo-settings-admin-social-facebook' // Page
);

add_settings_field(
	'siteseo_social_facebook_og', // ID
	__('Enable Open Graph Data', 'siteseo'), // Title
	'siteseo_social_facebook_og_callback', // Callback
	'siteseo-settings-admin-social-facebook', // Page
	'siteseo_setting_section_social_facebook' // Section
);

add_settings_field(
	'siteseo_social_facebook_img', // ID
	__('Select a default image', 'siteseo'), // Title
	'siteseo_social_facebook_img_callback', // Callback
	'siteseo-settings-admin-social-facebook', // Page
	'siteseo_setting_section_social_facebook' // Section
);

add_settings_field(
	'siteseo_social_facebook_img_default', // ID
	__('Apply this image to all your og:image tag', 'siteseo'), // Title
	'siteseo_social_facebook_img_default_callback', // Callback
	'siteseo-settings-admin-social-facebook', // Page
	'siteseo_setting_section_social_facebook' // Section
);

add_settings_field(
	'siteseo_social_facebook_img_cpt', // ID
	__('Define custom og:image tag for post type archive pages', 'siteseo'), // Title
	'siteseo_social_facebook_img_cpt_callback', // Callback
	'siteseo-settings-admin-social-facebook', // Page
	'siteseo_setting_section_social_facebook' // Section
);

add_settings_field(
	'siteseo_social_facebook_link_ownership_id', // ID
	__('Facebook Link Ownership ID', 'siteseo'), // Title
	'siteseo_social_facebook_link_ownership_id_callback', // Callback
	'siteseo-settings-admin-social-facebook', // Page
	'siteseo_setting_section_social_facebook' // Section
);

add_settings_field(
	'siteseo_social_facebook_admin_id', // ID
	__('Facebook Admin ID', 'siteseo'), // Title
	'siteseo_social_facebook_admin_id_callback', // Callback
	'siteseo-settings-admin-social-facebook', // Page
	'siteseo_setting_section_social_facebook' // Section
);

add_settings_field(
	'siteseo_social_facebook_app_id', // ID
	__('Facebook App ID', 'siteseo'), // Title
	'siteseo_social_facebook_app_id_callback', // Callback
	'siteseo-settings-admin-social-facebook', // Page
	'siteseo_setting_section_social_facebook' // Section
);

//Twitter SECTION==========================================================================
add_settings_section(
	'siteseo_setting_section_social_twitter', // ID
	'',
	//__("Twitter","siteseo"), // Title
	'siteseo_print_section_info_social_twitter', // Callback
	'siteseo-settings-admin-social-twitter' // Page
);

add_settings_field(
	'siteseo_social_twitter_card', // ID
	__('Enable Twitter Card', 'siteseo'), // Title
	'siteseo_social_twitter_card_callback', // Callback
	'siteseo-settings-admin-social-twitter', // Page
	'siteseo_setting_section_social_twitter' // Section
);

add_settings_field(
	'siteseo_social_twitter_card_og', // ID
	__('Use Open Graph if no Twitter Card is filled', 'siteseo'), // Title
	'siteseo_social_twitter_card_og_callback', // Callback
	'siteseo-settings-admin-social-twitter', // Page
	'siteseo_setting_section_social_twitter' // Section
);

add_settings_field(
	'siteseo_social_twitter_card_img', // ID
	__('Default Twitter Image', 'siteseo'), // Title
	'siteseo_social_twitter_card_img_callback', // Callback
	'siteseo-settings-admin-social-twitter', // Page
	'siteseo_setting_section_social_twitter' // Section
);

add_settings_field(
	'siteseo_social_twitter_card_img_size', // ID
	__('Image size for Twitter Summary card', 'siteseo'), // Title
	'siteseo_social_twitter_card_img_size_callback', // Callback
	'siteseo-settings-admin-social-twitter', // Page
	'siteseo_setting_section_social_twitter' // Section
);


$this->options = get_option('siteseo_social_option_name');
if (function_exists('siteseo_admin_header')) {
	siteseo_admin_header();
} ?>
<form method="post" action="<?php echo esc_url(admin_url('options.php')); ?>" class="siteseo-option">
	<?php
settings_fields('siteseo_social_option_group'); ?>

	<div id="siteseo-tabs" class="wrap">
		<?php
			echo wp_kses($this->siteseo_feature_title('social'), ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]]);
			$current_tab = '';
$plugin_settings_tabs	= [
				'tab_siteseo_social_knowledge' => esc_html__('Knowledge Graph', 'siteseo'),
				'tab_siteseo_social_accounts'  => esc_html__('Your social accounts', 'siteseo'),
				'tab_siteseo_social_facebook'  => esc_html__('Facebook (Open Graph)', 'siteseo'),
				'tab_siteseo_social_twitter'   => esc_html__('Twitter (Twitter card)', 'siteseo'),
			];

echo '<div class="nav-tab-wrapper">';
foreach ($plugin_settings_tabs as $tab_key => $tab_caption) {
	echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-social#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
}
echo '</div>'; ?>
				<div class="siteseo-tab <?php if ('tab_siteseo_social_knowledge' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_social_knowledge"><?php do_settings_sections('siteseo-settings-admin-social-knowledge'); ?></div>
				<div class="siteseo-tab <?php if ('tab_siteseo_social_accounts' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_social_accounts"><?php do_settings_sections('siteseo-settings-admin-social-accounts'); ?></div>
				<div class="siteseo-tab <?php if ('tab_siteseo_social_facebook' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_social_facebook"><?php do_settings_sections('siteseo-settings-admin-social-facebook'); ?></div>
				<div class="siteseo-tab <?php if ('tab_siteseo_social_twitter' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_social_twitter"><?php do_settings_sections('siteseo-settings-admin-social-twitter'); ?></div>
		</div>

		<?php siteseo_submit_button(__('Save changes', 'siteseo')); ?>
	</form>
<?php
