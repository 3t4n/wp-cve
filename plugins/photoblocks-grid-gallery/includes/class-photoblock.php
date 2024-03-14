<?php

/**
 * @since      1.0.0
 * @package    Photoblocks
 * @subpackage Photoblocks/includes
 * @author     MachoThemes <office@machothemes.net>
 */

class PhotoBlock {

	public $image;
	public $caption;
	public $geometry;
	public $click;
	private $settings;

	private $gallery;
	private $data;

	public $gallery_id;

	public function __construct( $gallery, $data, $settings ) {
		$this->gallery  = $gallery;
		$this->settings = $settings;
		$this->data     = $data;

		$this->gallery_id = $gallery['id'];

		foreach ( $data as $key => $value ) {
			$this->{$key} = $value;
		}
	}

	private function value( $block_value, $field ) {
		return $block_value ? $block_value : $this->gallery[ $field ];
	}

	public function copy() {
		$newData = new stdClass();
		foreach ( $this->data as $key => $value ) {
			$encoded         = json_encode( $value );
			$newData->{$key} = json_decode( $encoded );
		}
		return new PhotoBlock( $this->gallery, $newData, $this->settings );
	}

	public function caption( $where ) {
		//print "\n\ncaption: $where\n\n";
		if ( ! $this->has_captions() ) {
			return '';
		}
		//print "^^";

		if ( $this->has_title() && $this->caption_position( 'title', 'v' ) == $where ) {
			return '<span class="pb-title">' . $this->caption->title->text . '</span>';
		}

		if ( $this->has_description() && $this->caption_position( 'description', 'v' ) == $where ) {
			return '<span class="pb-description">' . $this->caption->title->text . '</span>';
		}
	}

	public function caption_position( $field, $axis ) {
		 //print "\n\n<< $field $axis >>\n\n";

		if ( $this->caption->{$field}->position->{$axis} ) {
			return $this->caption->{$field}->position->{$axis};
		}

		$f = 'caption_' . $field . '_position_' . $axis;

		if ( $this->type == 'text' ) {
			$f = 'block_text_' . $field . '_position_' . $axis;
		}

		//print "\n\n|| $f {$this->settings->get($this->gallery, $f)} ||\n\n";

		return $this->settings->get( $this->gallery, $f );
	}

	public function has_captions_or_social( $where = null ) {
		return $this->has_title( $where ) || $this->has_description( $where ) || $this->has_any_social_here( $where );
	}

	public function has_captions( $where = null ) {
		 return $this->has_title( $where ) || $this->has_description( $where );
	}

	public function has_title( $where = null ) {
		return $this->has_text( 'title', $where );
	}

	public function has_description( $where = null ) {
		return $this->has_text( 'description', $where );
	}

	public function has_text( $field, $where = null ) {
		if ( $where == null ) {
			return isset( $this->caption->{$field}->text ) &&
			! empty( $this->caption->{$field}->text );
		}

		$f = "caption_{$field}_position_v";
		if ( $this->type == 'text' ) {
			$f = "block_text_{$field}_position_v";
		}

		return $this->has_text( $field ) &&
		$this->value( $this->caption->{$field}->position->v, $f ) == $where;
	}

	public function custom_styles( $field ) {
		$style = array();
		if ( ! empty( $this->caption->{$field}->color ) ) {
			$style['color'] = $this->caption->{$field}->color;
		}

		if ( ! empty( $this->caption->{$field}->size ) ) {
			$style['font-size'] = $this->caption->{$field}->size . 'px';
		}

		$ret = '';
		foreach ( $style as $k => $v ) {
			$ret .= $k . ': ' . $v . ";\n";
		}

		return $ret;
	}

	public function get_alt() {
		if ( isset( $this->image->alt ) && ! empty( $this->image->alt ) ) {
			return $this->image->alt;
		}

		return $this->get_title();
	}

	public function get_title( $fallback_description = true ) {
		if ( $this->has_title() ) {
			return strip_tags( $this->caption->title->text );
		}

		if ( ! $fallback_description ) {
			return '';
		}

		if ( $this->has_description() ) {
			return strip_tags( $this->caption->description->text );
		}

		return '';
	}

	public function get_description() {
		if ( $this->has_description() ) {
			return strip_tags( $this->caption->description->text );
		}

		return '';
	}

	public function get_lightbox_caption() {
		switch ( $this->settings->get( $this->gallery, 'lightbox_caption_field' ) ) {
			case 'none':
				return '';
			case 'title':
				return $this->get_title( false );
			case 'description':
				return $this->get_description();
			case 'title_description':
				return ( $this->get_title( false ) . '<br>' . $this->get_description() );
		}
	}

	public function has_custom_overlay() {
		return ! empty( $this->caption->background->color );
	}

	public function get_overlay_bg() {
		return $this->caption->background->color;
	}

	public function colspan() {
		 return $this->geometry->colspan;
	}

	public function rowspan() {
		 return $this->geometry->rowspan;
	}

	public function col() {
		 return $this->geometry->col;
	}

	public function row() {
		 return $this->geometry->row;
	}

	public function valign() {
		return $this->image->alignment->v ? $this->image->alignment->v : $this->gallery['image_alignment_v'];
	}

	public function halign() {
		return $this->image->alignment->h ? $this->image->alignment->h : $this->gallery['image_alignment_h'];
	}

	public function style() {
		$style    = array();
		$bg_key   = $this->type == 'image' ? 'block_background_color' : 'block_text_background_color';
		$style [] = 'background-color=' . $this->caption->background->color ? $this->caption->background->color : $this->gallery[ $bg_key ];

		return implode( ';', $style );
	}

	public function ratio() {
		if ( $this->type == 'image' || $this->type == 'post' ) {
			return $this->image->ratio;
		}

		return 0;
	}

	public function winning_size() {
		if ( $this->type == 'image' || $this->type == 'post' ) {
			if ( $this->image->ratio > ( $this->geometry->colspan / $this->geometry->rowspan ) ) {
				return 'width';
			}

			return 'height';
		}

		return '';
	}

	public function needs_href() {
		return $this->has_link_or_lightbox();
	}

	public function has_link() {
		return isset( $this->click->link ) && ! empty( $this->click->link );
	}

	public function has_lightbox() {
		return $this->lightbox() == 'fancybox' ||
			   $this->lightbox() == 'magnific';
	}

	public function lightbox() {
		$field = wp_is_mobile() ? 'lightbox_mobile' : 'lightbox';

		//echo "lightbox() is " . $this->settings->get($this->gallery, $field);

		return $this->settings->get( $this->gallery, $field );
	}

	public function has_link_or_lightbox() {
		if ( $this->has_link() ) {
			return true;
		}
		if ( $this->type == 'image' && ( $this->lightbox() == 'fancybox' || $this->lightbox() == 'magnific'
			|| $this->lightbox() == 'link_to_image' ) ) {
			return true;
		}

		return false;
	}

	public function get_link() {
		if ( $this->has_link() ) {
			return $this->click->link;
		}

		if ( ! empty( $this->gallery['lightbox_image_size'] ) && 'full' != $this->gallery['lightbox_image_size'] ) {
			$md = wp_get_attachment_image_src( $this->image->id, $this->gallery['lightbox_image_size'] );
			return $md[0];
		}

		if ( $this->has_lightbox() ) {
			$md = wp_get_attachment_metadata( $this->image->id );
			return wp_get_attachment_url( $this->image->id );
		}

		if ( $this->lightbox() == 'link_to_image' ) {
			return $this->image->url;
		}
	}

	public function get_lightbox_attrs() {
		$attrs = array();

		if ( $this->has_link() ) {
			if ( $this->click->target == '_lightbox' ) {
				if ( ! ( strpos( $this->click->link, 'youtube' ) > 0 ||
				strpos( $this->click->link, 'youtu.be' ) > 0 ||
				strpos( $this->click->link, 'vimeo' ) > 0 ) ) {
					$attrs [] = 'data-type="iframe"';
				}
			} else {
				return implode( ' ', $attrs );
			}
		}

		if ( $this->lightbox() == 'fancybox' ) {
			$attrs [] = 'data-fancybox="gallery-' . $this->gallery['id'] . '"';
			$attrs [] = 'data-thumb="' . $this->get_link() . '"';
		}

		if ( $this->lightbox() == 'magnific' ) {
			$attrs [] = 'data-magnific="gallery-' . $this->gallery['id'] . '"';
		}

		return implode( ' ', $attrs );
		//return 'data-fancybox="gallery" data-fancybox';
	}

	public function get_image_class() {
		if ( isset( $this->gallery['image_class'] ) ) {
			return $this->gallery['image_class'];
		}

		return '';
	}

	public function get_image_url() {
		//resize images
		if ( $this->type == 'post' ) {
			return $this->image->url;
		}
		if ( $this->type == 'image' && $this->settings->get( $this->gallery, 'resize_images' ) == '1' ) {
			if ( $this->settings->get( $this->gallery, 'relative_path' ) ) {
				return str_replace( $this->gallery['site_url'], '', $this->image->url );
			}

			return $this->image->url;
		}

		$i = wp_get_attachment_image_src( $this->image->id, $this->gallery['image_size'] );
		return $i[0];
	}

	public function get_link_class() {
		if ( isset( $this->gallery['link_class'] ) ) {
			return $this->gallery['link_class'];
		}

		return '';
	}

	public function mfp_iframe_class() {
		if ( $this->has_link() && $this->lightbox() == 'magnific' && $this->click->target == '_lightbox' ) {
			return 'mfp-iframe';
		}
		return '';
	}

	public function show_empty_overlay() {
		if ( isset( $this->gallery['caption_background_show_empty'] ) && $this->gallery['caption_background_show_empty'] == '1' ) {
			return true;
		}

		return false;
	}

	public function caption_effect() {
		return $this->settings->get( $this->gallery, 'caption_effect' );
	}

	public function random_position() {
		 return ( rand( 5, 100 ) - 50 ) . ',' . ( rand( 5, 100 ) - 50 );
	}

	public function filters() {
		if ( isset( $this->filters ) && is_array( $this->filters ) ) {
			$res = array();
			foreach ( $this->filters as $f ) {
				$res [] = PhotoBlocks_Utils::slugify( $f );
			}
			return implode( ' ', $res );
		}

		return '';
	}

	public function list_social() {
		 $social = array( 'facebook', 'pinterest', 'twitter', 'houzz', 'google' );
		$active  = array();
		foreach ( $social as $item ) {
			if ( $this->has_social( $item ) ) {
				$active [] = $item;
			}
		}

		return $active;
	}

	public function has_social( $social ) {
		 return $this->settings->get( $this->gallery, "sharing_$social" ) == '1';
	}

	public function has_any_social() {
		$social = array( 'facebook', 'pinterest', 'twitter', 'houzz', 'google' );
		foreach ( $social as $item ) {
			if ( $this->has_social( $item ) ) {
				return true;
			}
		}

		return false;
	}

	public function has_any_social_here( $where ) {
		 return $this->has_any_social() && $this->settings->get( $this->gallery, 'social_position_v' ) == $where;
	}
}
