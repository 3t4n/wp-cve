<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

//XML

//Headers
if (function_exists('siteseo_sitemaps_headers')) {
	siteseo_sitemaps_headers();
}

//WPML
add_filter( 'wpml_get_home_url', 'siteseo_remove_wpml_home_url_filter', 20, 5 );

function siteseo_xml_sitemap_index_xsl() {
	$home_url = home_url().'/';

	if (function_exists('pll_home_url')) {
		$home_url = site_url().'/';
	}

	$home_url = apply_filters( 'siteseo_sitemaps_home_url', $home_url );

	$siteseo_sitemaps_xsl ='<?xml version="1.0" encoding="UTF-8"?><xsl:stylesheet version="2.0"
				xmlns:html="http://www.w3.org/TR/REC-html40"
				xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
				xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">';
	$siteseo_sitemaps_xsl .="\n";
	$siteseo_sitemaps_xsl .='<head>';
	$siteseo_sitemaps_xsl .="\n";
	$siteseo_sitemaps_xsl .='<title>XML Sitemaps</title>';
	$siteseo_sitemaps_xsl .='<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
	$siteseo_sitemaps_xsl .="\n";
	$siteseo_sitemaps_xsl_css = '<style type="text/css">';

	$siteseo_sitemaps_xsl_css .= apply_filters('siteseo_sitemaps_xsl_css', '
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}
	body {
		background: #F7F7F7;
		font-size: 14px;
		font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
	}
	h1 {
		color: #23282d;
		font-weight:bold;
		font-size:20px;
		margin: 20px 0;
	}
	p {
		margin: 0 0 15px 0;
	}
	p a {
		color: rgb(0, 135, 190);
	}
	p.footer {
		padding: 15px;
		background: rgb(250, 251, 252) none repeat scroll 0% 0%;
		margin: 10px 0px 0px;
		display: inline-block;
		width: 100%;
		color: rgb(68, 68, 68);
		font-size: 13px;
		border-top: 1px solid rgb(224, 224, 224);
	}
	#main {
		margin: 0 auto;
		max-width: 55rem;
		padding: 1.5rem;
		width: 100%;
	}
	#sitemaps {
		width: 100%;
		box-shadow: 0 0 0 1px rgba(224, 224, 224, 0.5),0 1px 2px #a8a8a8;
		background: #fff;
		margin-top: 20px;
		display: inline-block;
	}
	#sitemaps .loc, #sitemaps .lastmod {
		font-weight: bold;
		display: inline-block;
		border-bottom: 1px solid rgba(224, 224, 224, 1);
		padding: 15px;
	}
	#sitemaps .loc {
		width: 70%;
	}
	#sitemaps .lastmod {
		width: 30%;
		padding-left: 0;
	}
	#sitemaps ul {
		margin: 10px 0;
		padding: 0;
	}
	#sitemaps li {
		list-style: none;
		padding: 10px 15px;
	}
	#sitemaps li a {
		color: rgb(0, 135, 190);
		text-decoration: none;
	}
	#sitemaps li:hover{
		background:#F3F6F8;
	}
	#sitemaps .item-loc {
		width: 70%;
		display: inline-block;
	}
	#sitemaps .item-lastmod {
		width: 30%;
		display: inline-block;
		padding: 0 10px;
	}');

	$siteseo_sitemaps_xsl_css .= '</style>';

	$siteseo_sitemaps_xsl .= $siteseo_sitemaps_xsl_css;
	$siteseo_sitemaps_xsl .='</head>';
	$siteseo_sitemaps_xsl .='<body>';
	$siteseo_sitemaps_xsl .='<div id="main">';
	$siteseo_sitemaps_xsl .='<h1>'.esc_html__('XML Sitemaps','siteseo').'</h1>';
	$siteseo_sitemaps_xsl .='<p><a href="'.esc_url($home_url).'sitemaps.xml">Index sitemaps</a></p>';
	$siteseo_sitemaps_xsl .='<xsl:if test="sitemap:sitemapindex/sitemap:sitemap">';
	$siteseo_sitemaps_xsl .='<p>'.sprintf(esc_html__('This XML Sitemap Index file contains %s sitemaps.','siteseo'),'<xsl:value-of select="count(sitemap:sitemapindex/sitemap:sitemap)"/>').'</p>';
	$siteseo_sitemaps_xsl .='</xsl:if>';
	$siteseo_sitemaps_xsl .='<xsl:if test="sitemap:urlset/sitemap:url">';
	$siteseo_sitemaps_xsl .='<p>'.sprintf(esc_html__('This XML Sitemap contains %s URL(s).','siteseo'),'<xsl:value-of select="count(sitemap:urlset/sitemap:url)"/>').'</p>';
	$siteseo_sitemaps_xsl .='</xsl:if>';
	$siteseo_sitemaps_xsl .='<div id="sitemaps">';
	$siteseo_sitemaps_xsl .='<div class="loc">';
	$siteseo_sitemaps_xsl .='URL';
	$siteseo_sitemaps_xsl .='</div>';
	$siteseo_sitemaps_xsl .='<div class="lastmod">';
	$siteseo_sitemaps_xsl .=esc_html__('Last update','siteseo');
	$siteseo_sitemaps_xsl .='</div>';
	$siteseo_sitemaps_xsl .='<ul>';
	$siteseo_sitemaps_xsl .='<xsl:for-each select="sitemap:sitemapindex/sitemap:sitemap">';
	$siteseo_sitemaps_xsl .='<li>';
	$siteseo_sitemaps_xsl .='<xsl:variable name="sitemap_loc"><xsl:value-of select="sitemap:loc"/></xsl:variable>';
	$siteseo_sitemaps_xsl .='<span class="item-loc"><a href="{$sitemap_loc}"><xsl:value-of select="sitemap:loc" /></a></span>';
	$siteseo_sitemaps_xsl .='<span class="item-lastmod"><xsl:value-of select="sitemap:lastmod" /></span>';
	$siteseo_sitemaps_xsl .='</li>';
	$siteseo_sitemaps_xsl .='</xsl:for-each>';
	$siteseo_sitemaps_xsl .='</ul>';

	$siteseo_sitemaps_xsl .='<ul>';
	$siteseo_sitemaps_xsl .='<xsl:for-each select="sitemap:urlset/sitemap:url">';
	$siteseo_sitemaps_xsl .='<li>';
	$siteseo_sitemaps_xsl .='<xsl:variable name="url_loc"><xsl:value-of select="sitemap:loc"/></xsl:variable>';
	$siteseo_sitemaps_xsl .='<span class="item-loc"><a href="{$url_loc}"><xsl:value-of select="sitemap:loc" /></a></span>';

	$siteseo_sitemaps_xsl .= '<xsl:if test="sitemap:lastmod">';
	$siteseo_sitemaps_xsl .='<span class="item-lastmod"><xsl:value-of select="sitemap:lastmod" /></span>';
	$siteseo_sitemaps_xsl .='</xsl:if>';
	$siteseo_sitemaps_xsl .='</li>';
	$siteseo_sitemaps_xsl .='</xsl:for-each>';
	$siteseo_sitemaps_xsl .='</ul>';

	$siteseo_sitemaps_xsl .='</div>';
	$siteseo_sitemaps_xsl .='</div>';
	$siteseo_sitemaps_xsl .='</body>';
	$siteseo_sitemaps_xsl .='</html>';

	$siteseo_sitemaps_xsl .='</xsl:template>';

	$siteseo_sitemaps_xsl .='</xsl:stylesheet>';

	$siteseo_sitemaps_xsl = apply_filters('siteseo_sitemaps_xsl', $siteseo_sitemaps_xsl);

	return $siteseo_sitemaps_xsl;
}
echo siteseo_xml_sitemap_index_xsl(); //phpcs:ignore
