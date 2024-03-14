<?php 

if (!defined('ABSPATH'))
{
	exit;
}

function bpdisablewprssfeeds()
{
    $site_name = get_bloginfo('name');
    $site_url = get_bloginfo('url');
    wp_die(__('We have disabled feed, please view our site <a href="'.$site_url.'">'.$site_name.'</a>'));
}

$bpenableaallwprssrestricts = get_option('bpenablerssrestricts');

if (strtolower($bpenableaallwprssrestricts)  == 'yes')
{
    add_action('do_feed', 'bpdisablewprssfeeds',1);
    add_action('do_feed_rdf', 'bpdisablewprssfeeds',1);
    add_action('do_feed_rss', 'bpdisablewprssfeeds',1);
    add_action('do_feed_rss2', 'bpdisablewprssfeeds',1);
    add_action('do_feed_atom', 'bpdisablewprssfeeds',1);
    add_action('do_feed_rss2-comments', 'bpdisablewprssfeeds',1);
    add_action('do_feed_atom-comments', 'bpdisablewprssfeeds',1);
    remove_action('wp_head', 'feed_links_extra',3);
    remove_action('wp_head', 'feed_links',3);
}