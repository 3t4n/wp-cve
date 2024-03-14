<?php

/**
 * Main announcment class
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    VisitWeho
 * @subpackage VisitWeho/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class CW_Announcement {

	public $ID;
	public $name;
	public $url;
	public $background_color;
	public $text_color;
	public $content;
	public $closable;
	public $closable_duration;

	public function __construct( $id ) {
		$a = get_post( $id );
		$this->ID = $id;
		$this->name = $a->post_title;
		$this->url = get_post_meta( $id, 'cw_announcement_url', true );
		$this->background_color = get_post_meta( $id, 'cw_background_color', true );
		$this->closable = get_post_meta( $id, 'cw_announcement_closable', true );
		$this->closable_duration = get_post_meta( $id, 'cw_announcement_closable_duration', true );
		$this->text_color = get_post_meta( $id, 'cw_text_color', true );
		$this->content = apply_filters('the_content', $a->post_content );
	}

}