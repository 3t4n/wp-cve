<?php

if ( ! defined( 'WPINC' ) ) die;

$settings = get_option('sform_settings');
$admin_notices = ! empty( $settings['admin_notices'] ) ? esc_attr($settings['admin_notices']) : 'false';
$color = ! empty( $settings['admin_color'] ) ? esc_attr($settings['admin_color']) : 'default';
$notice = '';
$wplang = get_locale();
$lang = strlen( $wplang ) > 0 ? explode( '_', $wplang )[0] : 'en';
$country = isset(explode( '_', $wplang )[1]) ? strtolower(explode( '_', $wplang )[1]) : '';
$slug = isset(explode( '_', $wplang )[2]) ? explode( '_', $wplang )[2] : 'default';
$locale = $lang == $country || $country == '' ? $lang : $lang . '-' . $country;
$language_pack = $locale . '/' . $slug;
$url = 'https://translate.wordpress.org/locale/' . $language_pack . '/wp-plugins/simpleform/';
?>

<div id="sform-wrap" class="sform">

<div id="new-release" class="<?php if ( $admin_notices == 'true' ) {echo 'invisible';} ?>"><?php echo apply_filters( 'sform_update', $notice ); ?>&nbsp;</div>
	    
<div class="full-width-bar <?php echo $color ?>"><h1 class="title <?php echo $color ?>"><span class="dashicons dashicons-groups responsive"></span><?php _e( 'Support', 'simpleform' ); ?></h1></div>
  
<div class="row">

<div class="columns-wrap"><div class="columns-body"><h2><?php esc_html_e( 'Support channels ', 'simpleform' ); ?></h2>
<h4><?php esc_html_e( 'FAQs', 'simpleform' ); ?></h4><?php _e('Are you having trouble getting started? Get started from the FAQs that cover everything you need to know about SimpleForm.', 'simpleform') ?> <a href="https://wpsform.com/faq/" target="_blank" rel="noopener nofollow"><?php _e( 'Have a look at the FAQs', 'simpleform' ); ?> →</a>
<h4><?php esc_html_e( 'Forum', 'simpleform' ); ?></h4><?php _e( 'Didn\'t find the information you were looking for? Go to the WordPress.org plugin repository to get started, and log into your account. Click on “Support” and, in the “Search this forum” field, type a keyword about the issue you’re experiencing. Read topics that are similar to your issue to see if the topic has been resolved previously. If your issue remains after reading past topics, please create a new topic and fill out the form. We\'ll be happy to answer any additional questions!', 'simpleform' ) ?> <a href="https://wordpress.org/support/plugin/simpleform/" target="_blank" rel="noopener noreferrer nofollow"><?php _e( 'View the support forum', 'simpleform' ) ?> →</a></div></div>

<div class="columns-wrap"><div class="columns-body"><h2><?php _e( 'Report bugs, errors, and typos', 'simpleform' ); ?></h2><p><?php _e( 'We need your help to make SimpleForm even better for you. If you notice any bugs, errors, or typos, please notify us as soon as possible. Report everything that you find. An issue might be glaringly obvious to you, but if you don’t report it, we may not even know about it. You can use the support forum in the WordPress.org plugin repository, or fill out a report form anonymously. Your feedback will be greatly appreciated!', 'simpleform' ); ?></p><a href="https://wpsform.com/report/" target="_blank" rel="noopener nofollow" class="sform support button <?php echo $color ?>"><?php _e( 'Report now', 'simpleform' ); ?></a></div></div>

</div>  

</div>