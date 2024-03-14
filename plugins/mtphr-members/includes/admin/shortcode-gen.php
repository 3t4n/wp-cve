<?php

/* --------------------------------------------------------- */
/* !Add shortcodes to the generator - 1.1.8 */
/* --------------------------------------------------------- */

function mtphr_members_shortcodes() {

	global $mtphr_shortcode_gen_assets;

	$shortcodes = array();
	$shortcodes['mtphr_member_archive_gen'] = array(
		'label' => __('Member Archive', 'mtphr-members'),
		'icon' => 'mtphr-shortcodes-ico-view-thumbnail'
	);
	$shortcodes['mtphr_member_title_gen'] = array(
		'label' => __('Member Title', 'mtphr-members'),
		'icon' => 'mtphr-shortcodes-ico-brief-case-3'
	);
	$shortcodes['mtphr_member_contact_info_gen'] = array(
		'label' => __('Member Contact Info', 'mtphr-members'),
		'icon' => 'mtphr-shortcodes-ico-v-card-3'
	);
	$shortcodes['mtphr_member_social_sites_gen'] = array(
		'label' => __('Member Social Sites', 'mtphr-members'),
		'icon' => 'mtphr-shortcodes-ico-megaphone'
	);
	$shortcodes['mtphr_member_twitter_gen'] = array(
		'label' => __('Member Twitter', 'mtphr-members'),
		'icon' => 'mtphr-shortcodes-ico-twitter'
	);

	// Add the shortcodes to the list
	$mtphr_shortcode_gen_assets['mtphr_members'] = array(
		'label' => __('Metaphor Members', 'mtphr-members'),
		'shortcodes' => $shortcodes
	);
}
add_action( 'admin_init', 'mtphr_members_shortcodes' );