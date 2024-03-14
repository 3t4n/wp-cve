<?php

class EIC_Ajax {

    public function __construct()
    {
        add_action( 'wp_ajax_image_collage', array( $this, 'ajax_image_collage' ) );
        add_action( 'wp_ajax_image_collage_preview', array( $this, 'ajax_image_collage_preview' ) );
    }

    public function ajax_image_collage()
    {
        if( check_ajax_referer( 'eic_image_collage', 'security', false ) )
        {

            $grid_data =  $_POST['grid'];
            $grid_id = intval( $grid_data['id'] );

            // Create new or update grid
            if( $grid_id === 0 ) {
                global $user_ID;

                $post = array(
                    'post_status' => 'publish',
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_author' => $user_ID,
                    'post_type' => EIC_POST_TYPE,
                    'post_content' => '',
                );

                $grid_id = wp_insert_post( $post );
            } else {
                $post = array(
                    'ID' => $grid_id,
                    'post_content' => ''
                );

                wp_update_post( $post );
            }

	        $grid = new EIC_Grid( $grid_id );
            $grid->update_data( $grid_data );

            echo json_encode($grid_id);
        }

        die();
    }

	public function ajax_image_collage_preview()
	{
		$preview = '';

		if( check_ajax_referer( 'eic_image_collage', 'security', false ) )
		{
			$grid_id = intval( $_POST['grid_id'] );

			$post = get_post( $grid_id );

			$preview .= '<span contentEditable="false" style="font-weight: bold;" data-eic-grid="' . $grid_id . '">Easy Image Collage ' . $grid_id . '</span>';
			$preview .= '<span contentEditable="false" style="float: right; color: darkred;" data-eic-grid-remove="' . $grid_id . '">' . __( 'remove', 'easy-image-collage' ) . '</span>';
			$preview .= '<br/><br/>';

			if( !is_null( $post ) && $post->post_type == EIC_POST_TYPE ) {
				$grid = new EIC_Grid( $grid_id );
				$images = $grid->images();

				if( !empty( $images ) ) {
					foreach( $images as $id => $image ) {
						if( $image ) {
							$thumb = wp_get_attachment_image_src( $image['attachment_id'], array( 100, 100 ) );

							if( $thumb ) {
								$preview .= '<span contentEditable="false" style="display: inline-block; background-image: url(\'' . $thumb[0] . '\'); background-size: ' . $thumb[1] . 'px ' . $thumb[2] . 'px; width: ' . $thumb[1] . 'px; height: ' . $thumb[2] . 'px;" data-eic-grid="' . $grid_id . '">&nbsp;</span>';
							}
						}
					}
				} else {
					$preview .= '<span contentEditable="false" data-eic-grid="' . $grid_id . '">' . __( 'No images in this collage yet', 'easy-image-collage' ) . '</span>';
				}
			}
		}

		echo $preview;
		die();
	}

    public function url()
    {
        $ajaxurl = admin_url( 'admin-ajax.php' );
        $ajaxurl .= '?eic_ajax=1';

        // WPML AJAX Localization Fix
        global $sitepress;
        if( isset( $sitepress) ) {
            $ajaxurl .= '&lang='.$sitepress->get_current_language();
        }

        return $ajaxurl;
    }
}