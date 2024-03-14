<?php

if( isset( $_POST['wpcmp_submit_for_create_posts'] ) )
{
	if (
	    ! isset( $_POST['wpcmp_nonce'] )
	    || ! wp_verify_nonce( $_POST['wpcmp_nonce'], 'wpcmp_create_posts' )
	)
	{
	   print 'Sorry, your nonce did not verify.'; exit;
	}
	else
	{
		extract( $_POST );

		if ( ! empty( $wpcmp_posts_titles ) )
		{
			$posts_titles = explode( "\n", $wpcmp_posts_titles );

			$category_id = array( 1 );

			if ( ! empty( $wpcmp_new_post_category ) )
			{	
				foreach ( $wpcmp_new_post_category as $id )
				{	
					if ( ! in_array( $id, $category_id ) )
					{	
						$category_id[] = $id;
					}
				}
			}

			$created_posts = array();

			foreach ( $posts_titles as $post_title )
			{	
				$post = array(
				  'post_title'    => wp_strip_all_tags( $post_title ),
				  'post_content'  => '',
				  'post_type' 	  => $wpcmp_new_post_type,
				  'post_status'   => $wpcmp_new_post_status,
				  'post_author'   => $wpcmp_new_post_author,
				  'post_category' => $category_id
				);

				// Insert the post into the database
				$post_id = wp_insert_post( $post );

				if( ! is_wp_error( $post_id ) )
				{
				 	//the post is valid
					$wpcmp_show_message = 'success';
					
					$created_posts[] = $post_id;
				}
			}

			if ( $wpcmp_show_message && $wpcmp_show_message == 'success' )
			{	
				$wpcmp_message = 'Posts Successfully Created!';
			}
		}
	}
}
