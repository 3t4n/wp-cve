<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection HtmlUnknownTarget */

class Football_Pool_Stadium extends Football_Pool_Stadiums {
	public $id = 0;
	public $name = '';
	public $photo = '';
	public $comments = '';
	
	public function __construct( $stadium = 0 ) {
		if ( is_int( $stadium ) && $stadium != 0 ) {
			$s = $this->get_stadium_by_id( $stadium );
			if ( is_object( $s ) ) {
				$this->id = $s->id;
				$this->name = $s->name;
				$this->photo = $s->photo;
				$this->comments = $s->comments;
			}
		} elseif ( is_array( $stadium ) ) {
			$this->id = $stadium['id'];
			$this->name = $stadium['name'];
			$this->photo = $stadium['photo'];
			$this->comments = $stadium['comments'];
		}
	}
	
	private function get_photo_url( $photo ) {
		$path = '';
		if ( stripos( $photo, 'http://' ) !== 0 && stripos( $photo, 'https://' ) !== 0 ) {
			$path = trailingslashit( FOOTBALLPOOL_UPLOAD_URL . 'stadiums' );
		}
		
		return $path . $photo;
	}
	
	public function HTML_image( $return = 'image' ) {
		$thumb = ( $return == 'thumb' ) ? ' thumb stadium-list' : '';
		return sprintf( '<img src="%s" title="%s" alt="%s" class="stadium-photo%s">'
						, esc_attr( Football_Pool_Utils::xssafe( $this->get_photo_url( $this->photo ) ) )
						, esc_attr( Football_Pool_Utils::xssafe( $this->name ) )
						, esc_attr( Football_Pool_Utils::xssafe( $this->name ) )
						, $thumb
					);
	}
	
	public function get_plays() {
		global $pool;
		$matches = $pool->matches->matches;
		
		$plays = [];
		foreach ( $matches as $match ) {
			if ( $match['stadium_id'] == $this->id ) $plays[] = $match;
		}
		
		return $plays;
	}
}
