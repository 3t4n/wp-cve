<?php
/*
Plugin Name: Protect schema.org markup in HTML editor
Description: Easy tool to stop HTML editor from removing schema.org/microdata tags from post or page content.
Author: Ecwid Team
Author URI: http://www.ecwid.com?source=tinymce-schemaorg-markup
Version: 0.6
*/

function tsm_get_extended_valid_elements() {
	$elements = array(
		'@'    => array(
			'id',
			'class',
			'style',
			'title',
			'itemscope',
			'itemtype',
			'itemprop',
			'datetime',
			'rel',
		),
		'article',
		'div',
		'p',
		'dl',
		'dt',
		'dd',
		'ul',
		'li',
		'span',
		'a'    => array(
			'href',
			'name',
			'target',
			'rev',
			'charset',
			'lang',
			'tabindex',
			'accesskey',
			'type',
			'class',
			'onfocus',
			'onblur',
		),
		'img'  => array(
			'src',
			'alt',
			'width',
			'height',
		),
		'meta' => array(
			'content',
		),
		'link' => array(
			'href',
		),
		'time' => array(
			'itemprop',
			'content',
		),
	);

	return apply_filters( 'tsm_extended_valid_elements', $elements );
}

function tsm_tinymce_init( $settings ) {
	if ( ! empty( $settings['extended_valid_elements'] ) ) {
		$settings['extended_valid_elements'] .= ',';
	} else {
		$settings['extended_valid_elements'] = '';
	}

	$result = $settings['extended_valid_elements'];

	$elements = tsm_get_extended_valid_elements();

	foreach ( $elements as $key => $element ) {
		if ( is_array( $element ) && ! empty( $key ) ) {
			$name       = $key;
			$attributes = $element;
		} else {
			$name       = $element;
			$attributes = array();
		}

		if ( ! empty( $result ) ) {
			$result .= ',';
		}

		$result .= $name;

		if ( ! empty( $attributes ) ) {
			$result .= '[' . implode( '|', $attributes ) . ']';
		}
	}

	$settings['extended_valid_elements'] = $result;
	if ( ! isset( $settings['valid_children'] ) ) {
		$settings['valid_children'] = '';
	}
	$settings['valid_children'] .= '+body[meta],+div[meta],+body[link],+div[link]';

	return $settings;
}

function tsm_wp_kses_allowed_html( $tags, $context ) {

	if ( $context !== 'post' ) {
		return $tags;
	}

	$schema_attributes = array(
		'itemscope' => true,
		'itemtype'  => true,
		'itemprop'  => true,
		'datetime'  => true,
		'content'  => true,
	);

	if ( ! empty( $tags ) ) {
		$tags_with_schema_attrs = array();

		foreach ( $tags as $tag => $attributes ) {
			$tags_with_schema_attrs[ $tag ] = array_merge( $attributes, $schema_attributes );
		}

		return $tags_with_schema_attrs;
	}

	return $tags;
}

add_filter( 'tiny_mce_before_init', 'tsm_tinymce_init' );
add_filter( 'wp_kses_allowed_html', 'tsm_wp_kses_allowed_html', 10, 2 );
