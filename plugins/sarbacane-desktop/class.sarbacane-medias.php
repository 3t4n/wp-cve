<?php

class SarbacaneMedias {

	public function get_medias() {
		$wp_query = new WP_Query( array( 'post_type' => 'attachment', 'post_status' => 'inherit' ) );
		$medias = array();
		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) {
				$wp_query->the_post();
				$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
				$medias[] = '
	{
		"name":"' . get_the_title() . '",
		"url":"' . get_the_guid() . '",
		"mime_type":"' . get_post_mime_type() . '",
		"thumbnail":"' . $thumbnail[0] . '"
	}';
			}
		}
		$medias = implode( ',', $medias );
		return '{"medias":[' . $medias . "\n\t" . ']' . "\n" . '}';
	}

}
