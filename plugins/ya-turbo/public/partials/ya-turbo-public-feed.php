<?php

/**
 * Provide a public area view for the plugin
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.1
 *
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/admin/partials
 */

/**
 * @var object $feed
 */
?><?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

$mime       = feed_content_type('rss2');
$charset    = get_option('blog_charset');
$xml_tag    = '';

$xmlns      = implode(' ', array(
	'xmlns:yandex="http://news.yandex.ru"',
	'xmlns:media="http://search.yahoo.com/mrss/"',
	'xmlns:turbo="http://turbo.yandex.ru"',
	'version="2.0"'
));

/* link */
$xml_link   = esc_attr(add_query_arg(array(
	'feed' => YATURBO_FEED,
	'name' => $feed->slug),
	get_site_url()
));

$xml_link        = "<link>{$xml_link}</link>";

/* title */
$xml_title       = !$feed->title ? '' : "<title>{$feed->title}</title>";

/* description */
$xml_description = !$feed->description ? '' : "<description>{$feed->description}</description>";

/* Language */
$xml_language = !$feed->language ? '' : "<language>{$feed->language}</language>";

/* Plugin creator */
$xml_plugin_creator = "<turbo:cms_plugin>A04319A3CFD2EFE41AF2193A3C05F0F4</turbo:cms_plugin>";

/* Allowed html tags list */
$allowed_html = Ya_Turbo_Public::allowed_html();

header('Content-Type: ' . $mime . '; charset=' . $charset , true);

print <<<TPL
<?xml version="1.0" encoding="{$charset}" ?>
<rss {$xmlns}>
  <channel>
    {$xml_title}
    {$xml_link}
    {$xml_language}
    {$xml_description}
    {$xml_plugin_creator}
TPL;

if ( $feed->items->have_posts() ) {
    while ( $feed->items->have_posts() ) {
	    $feed->items->the_post();
	    $post_meta_related = get_post_meta( get_the_ID(), 'turbo_yandex_related', true );

	    /* Link */
        $xml_item_link = esc_attr( get_permalink() );

        /* title */
	    $xml_item_title = esc_attr( get_the_title() );

        /* pubDate */
	    $xml_item_pubDate = date( DateTime::RFC822, get_post_time() );

	    /* content */
	    $xml_item_content = force_balance_tags( get_the_content() );
		$xml_item_content = apply_filters( 'the_content', $xml_item_content );
	    $xml_item_content = wp_kses( $xml_item_content, $allowed_html);
	    $xml_item_content = preg_replace( '/^\s*\/\/<!\[CDATA\[([\s\S]*)\/\/\]\]>\s*\z/', '$1', $xml_item_content );
	    $xml_item_content = preg_replace('/<!--(.|\s)*?-->/', '', $xml_item_content);
	    $xml_item_content = preg_replace("/(<img\s(.+?)\/?>)/is", "<figure>$1</figure>", $xml_item_content);

        /* related */
	    $xml_item_related = "";

        if ( trim ($post_meta_related ) != '' ) {
	        $post_meta_related = explode( ',', $post_meta_related );
        	foreach ( $post_meta_related as $el ) {
				if ( get_the_ID() != $el ) {
					$url = esc_attr( get_permalink( $el ) );
					if ( $url ) {
						$title = esc_attr( get_the_title( $el ) );
						$img = esc_attr( get_the_post_thumbnail_url( $el ) );
						$img = $img ? "img=\"{$img}\"" : "";
						$xml_item_related .= "<link url=\"{$url}\" {$img}>{$title}</link>";
					}
				}
	        }

	        $xml_item_related = $xml_item_related
		        ?  "<yandex:related>{$xml_item_related}</yandex:related>"
		        : "";
        }

        print <<<TPL
<item turbo="true">
	<title>{$xml_item_title}</title>
	<link>{$xml_item_link}</link>
	<pubDate>{$xml_item_pubDate}</pubDate>
	<turbo:content>
		<![CDATA[
			{$xml_item_content}
		]]>
	</turbo:content>
	{$xml_item_related}
</item>
TPL;

    }
}

print <<<TPL
    
  </channel>
</rss>
TPL;
