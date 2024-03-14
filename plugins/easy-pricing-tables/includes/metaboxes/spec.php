<?php

/**
 * Add the features meta box
 * @var [type]
 */
$features_metabox = new WPAlchemy_MetaBox(array
(
    'id' => '1_dh_ptp_settings',
    'title' => __('Pricing Table Settings', 'easy-pricing-tables'),
    'template' => PTP_PLUGIN_PATH . 'includes/metaboxes/features-metabox.php',
    'types' => array('easy-pricing-table', 'us_footer' ),
    'autosave' => TRUE,
    'priority' => 'high',
    'context' => 'normal',
    
));

if( DH_PTP_LICENSE_PACKAGE === 'Free' ) {

	$banner_metabox = new WPAlchemy_MetaBox(array
	(
		'id' => 'dh_ptp_banner',
		'title' => __('Wanna Get More Sales?', 'easy-pricing-tables'),
		'template' => PTP_PLUGIN_PATH . 'includes/metaboxes/banner-metabox.php',
		'types' => array('easy-pricing-table'),
		'context' => 'side',
		'priority' => 'high',
		'skip_admin_head' => true
	));

	$tt_quick_links_metabox = new WPAlchemy_MetaBox(array
	(
		'id' => 'dh_ptp_banner_quick_link',
		'title' => __('Quick Links', 'easy-pricing-tables'),
		'template' => PTP_PLUGIN_PATH . 'includes/metaboxes/quick-links-metabox.php',
		'types' => array('easy-pricing-table'),
		'context' => 'side',
		'priority' => 'high',
		'skip_admin_head' => true
	));

	$tt_review_metabox = new WPAlchemy_MetaBox(array
	(
		'id' => 'dh_ptp_banner_review_box',
		'title' => __('Like this plugin?', 'easy-pricing-tables'),
		'template' => PTP_PLUGIN_PATH . 'includes/metaboxes/ptp-review-metabox.php',
		'types' => array('easy-pricing-table'),
		'context' => 'side',
		'priority' => 'high',
		'skip_admin_head' => true
	));

}

$ept_allowed_tags = array(
    'a'       => array(
        'href'   => array(),
        'title'  => array(),
        'class' => array(),
        'target' => array(),
        'style' => array(),
    ),
    'abbr'    => array( 'title' => array() ),
    'acronym' => array( 'title' => array() ),
    'i'      => array(),
    'b'      => array(),
    'br'      => array(),
    'u'      => array(),
    'code'    => array(),
    'pre'     => array(),
    'em'      => array(),
    'strong'  => array(),
    'div'     => array( 
        'style' => array(),
        'class' => array(),
    ),
    'p'       => array( 
        'style' => array(),
        'class' => array(),
    ),
    'span'    => array( 
        'style' => array(),
        'class' => array(),
    ),
    'strike'  => array( 
        'style' => array(),
        'class' => array(),
    ),
    'ul'      => array(),
    'ol'      => array(),
    'li'      => array(),
    'h1'      => array(),
    'h2'      => array(),
    'h3'      => array(),
    'h4'      => array(),
    'h5'      => array(),
    'h6'      => array(),
    'img'     => array(
        'src'   => array(),
        'class' => array(),
        'style' => array(),
        'alt'   => array(),
    ),
);

?>