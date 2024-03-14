<?php
class Meow_Modules_SeoEngine_Magic_Fix
{
    private $post = null;

    private $meta_key_seo_title = null;
    private $meta_key_seo_excerpt = null;

    function __construct( $post, $meta_key_seo_title = '_kiss_seo_title', $meta_key_seo_excerpt = '_kiss_seo_excerpt' ){
        $this->post = $post;

        $this->meta_key_seo_title = $meta_key_seo_title;
        $this->meta_key_seo_excerpt = $meta_key_seo_excerpt;
    }

    function magic_fix_post_not_updated( ){
		try {
			// $new_paragraphe = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $this->post, 'magic_fix_post_not_updated' );

			// // Convert to block editor format.
			// $new_paragraphe = sprintf( "<!-- wp:paragraph -->\n<p>%s</p>\n<!-- /wp:paragraph -->", $new_paragraphe );

			// UNTIL PRO : just change the date to now.
			$new_date = date( 'Y-m-d H:i:s' );

			return [ 'solution' => 'The new post\'s date:' , 'value' => $new_date ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
	}

	function magic_fix_post_not_updated_post_update( $new_date ){
		try {
			// UNTIL PRO : just change the date to now.
			// // Update the post's publish date by adding a new paragraph at the beginning of the post content.
			// $new_content = $new_paragraphe . "\n\n" . $this->post->post_content;
			// $this->post->post_content = $new_content;

			// Update the post's publish date.
			$date = date( 'Y-m-d H:i:s', strtotime( $new_date ) );
			$this->post->post_date = $date;
			$this->post->post_date_gmt = get_gmt_from_date( $this->post->post_date );

			// Update the post's modified date.
			$this->post->post_modified = $date;
			$this->post->post_modified_gmt = get_gmt_from_date( $this->post->post_modified );

			// Update the post.
			wp_update_post( $this->post );

			return [ 'solution' => 'The post\'s date was changed.' , 'value' => $new_date ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
	}

	function magic_fix_title_missing( ){
		try{
			$new_title = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $this->post, 'title' );
			$new_title = str_replace( '"' , '', $new_title);
	
			return [ 'solution' => 'New Title:' , 'value' => $new_title ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
		
	}

	function magic_fix_title_missing_post_update( $new_title ){
		try{
			// Update the post's title.
			$this->post->post_title = $new_title;
	
			// Update the post.
			wp_update_post( $this->post );
	
			return [ 'solution' => 'The Title was updated.' , 'value' => $new_title ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];	
		}
	}

	function magic_fix_title_seo( ){
		try{
			$new_title = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $this->post, 'seo_title' );
			$new_title = str_replace( '"' , '', $new_title);
	
			return [ 'solution' => 'New SEO Title:' , 'value' => $new_title ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
		
	}

	function magic_fix_title_seo_post_update( $new_title ){
		try{
			// Update the post's title.
			update_post_meta( $this->post->ID, $this->meta_key_seo_title, $new_title );
	
			return [ 'solution' => 'The SEO Title was updated.' , 'value' => $new_title ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];	
		}
	}

	function magic_fix_excerpt_missing( ){
		try{
			$new_excerpt = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $this->post, 'excerpt' );
			$new_excerpt = str_replace( '"' , '', $new_excerpt);
	
			return [ 'solution' => 'New Excerpt:' , 'value' => $new_excerpt ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
		
	}

	function magic_fix_excerpt_missing_post_update( $new_excerpt ){
		try{
			// Update the post's excerpt.
			$this->post->post_excerpt = $new_excerpt;
	
			// Update the post.
			wp_update_post( $this->post );
	
			return [ 'solution' => 'The Excerpt was updated.' , 'value' => $new_excerpt ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];	
		}
	}
	
	function magic_fix_excerpt_seo_length( ){
		try{
			$new_excerpt = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $this->post, 'seo_excerpt' );
			$new_excerpt = str_replace( '"' , '', $new_excerpt);
	
			return [ 'solution' => 'New SEO Excerpt:' , 'value' => $new_excerpt ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
	}

	function magic_fix_excerpt_seo_length_post_update( $new_excerpt ){
		try{
			// Update the post's excerpt.
			update_post_meta( $this->post->ID, $this->meta_key_seo_excerpt, $new_excerpt );
	
			return [ 'solution' => 'The SEO Excerpt was updated.' , 'value' => $new_excerpt ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];	
		}
	}

	function magic_fix_slug_length ( ) {
		try{
			$new_slug = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $this->post, 'slug' );
			$new_slug = str_replace( '"' , '', $new_slug);
	
			return [ 'solution' => 'New Slug:' , 'value' => $new_slug ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
		
	}

	function magic_fix_slug_length_post_update( $new_slug ) {
		try{
			// Update the post's slug.
			$this->post->post_name = $new_slug;
	
			// Update the post.
			wp_update_post( $this->post );
	
			return [ 'solution' => 'The Slug was updated.' , 'value' => $new_slug ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];	
		}
	}

	function magic_fix_post_too_short( ) {
		try{
			$new_paragraphe = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $this->post, 'magic_fix_post_too_short' );

			// Conver to block editor format.
			$new_paragraphe = sprintf( "<!-- wp:paragraph -->\n<p>%s</p>\n<!-- /wp:paragraph -->", $new_paragraphe );

	
			return [ 'solution' => 'A new paragraphe was added:' , 'value' => $new_paragraphe ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}

	}

	function magic_fix_post_too_short_post_update( $new_paragraphe ) {
		try{
			// Update the post's content.
			$new_content = $new_paragraphe . "\n\n" . $this->post->post_content;
			$this->post->post_content = $new_content;
	
			// Update the post.
			wp_update_post( $this->post );
	
			return [ 'solution' => 'A new paragraphe was added:' , 'value' => $new_paragraphe ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];	
		}
	}

	function magic_fix_images_missing_alt_text( $images ) {
		try{

			$missing_images = [];

			if ( empty( $images ) ) {
				return [ 'solution' => '❌ No images with missing alt text were found.' , 'value' => '' ];
			}

			global $mfrh_rest;
			foreach ( $images as $index => $image ) {
				$attachment_id = attachment_url_to_postid( $image );

				if ( $attachment_id == 0 ) {
					// $new_alt_texts[ 'Media Not Found ' . $not_found_count ] = '❌ The image was not found.';
					// $not_found_count++;
					continue;
				}

				// Verify that the image doesn't already have an alt text.
				$alt_attchment = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
				if ( !empty( $alt_attchment ) ) {
					$missing_images[ $index ][ 'attachment_alt' ] = $alt_attchment;
				}

				if ( !empty( $mfrh_rest ) ) {
					$response = $mfrh_rest->rest_ai_suggest( [
						'mediaId' => $attachment_id,
						'type' => 'alternative text',
					] );

					if ( is_wp_error( $response ) ) {
						error_log( $response->get_error_message() );
						continue;
					}

					$data = $response->get_data();
					if ( $data[ 'success' ] == false ) {
						continue;
					}

					$new_alt = $data[ 'data' ];
					$missing_images[ $index ][ 'mfrh_alt' ] = $new_alt;
				}

				$missing_images[ $index ][ 'media_id' ] = $attachment_id;
				$missing_images[ $index ][ 'img_src' ] = $image;
			}

			return [ 'solution' => 'Missing Alt Text:' , 'value' => $missing_images ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
	}

	function magic_fix_images_missing_alt_text_post_update( $images ) {
		try{
			$new_alt_texts = [];

			if ( empty( $images ) ) {
				return [ 'solution' => '❌ No images with missing alt text were found.' , 'value' => '' ];
			}

			foreach ( $images as $image ) {
				if ( array_key_exists( 'is_cancelled', $image ) && $image[ 'is_cancelled' ] ) {
					continue;
				}

				$new_alt = $image[ 'mfrh_alt' ];

				update_post_meta( $image[ 'media_id' ], '_wp_attachment_image_alt', $new_alt );
				$new_alt_texts[ $image[ 'media_id' ] ] = $new_alt;

			}

			return [ 'solution' => 'Alt Text was updated:' , 'value' => $new_alt_texts ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
	}

	function magic_fix_links_missing( ){
		try{
			// Generate wikipedia links andd a "you might like" paragraphe at the end of the post.
			$new_paragraphe = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $this->post, 'magic_fix_links_missing' );

			// Conver to block editor format.
			$new_paragraphe = sprintf( "<!-- wp:paragraph -->\n<p>%s</p>\n<!-- /wp:paragraph -->", $new_paragraphe );


			return [ 'solution' => 'A new paragraphe was added:' , 'value' => $new_paragraphe ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];
		}
	}

	function magic_fix_links_missing_update_post( $new_paragraphe ){
		try{
			// Update the post's content.
			$new_content = $this->post->post_content . "\n\n" . $new_paragraphe;
			$this->post->post_content = $new_content;

			// Update the post.
			wp_update_post( $this->post );

			return [ 'solution' => 'A new paragraphe was added:' , 'value' => $new_paragraphe ];
		}
		catch( Exception $e ) {
			return [ 'solution' => 'ERROR The post was not updated.' , 'value' => $e->getMessage() ];	
		}
	}
}