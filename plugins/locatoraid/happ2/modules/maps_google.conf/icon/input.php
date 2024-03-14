<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Maps_Google_Conf_Icon_Input_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	public function _init()
	{
		wp_enqueue_media();

		$this->app->make('/app/enqueuer')
			->register_script( 'hc-maps-google-custom-icon', 'happ2/modules/maps_google.conf/assets/js/input.js' )
			->enqueue_script( 'hc-maps-google-custom-icon' )
			;

		return $this;
	}

	public function grab( $name, $post )
	{
		$return = $this->app->make('/form/hidden')
			->grab($name, $post)
			;
		return $return;
	}

	public function render( $name, $value = NULL )
	{
		$metabox_id = 'hclc-location-icon';

	// Get WordPress' media upload URL
		// $upload_link = esc_url( get_upload_iframe_src( 'image', $model['id'] ) );
		$upload_link = esc_url( get_upload_iframe_src('image') );

		// See if there's a media id already saved as post meta
		$your_img_id = $value;

		// Get the image src
		$your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );

		// For convenience, see if the array is valid
		$you_have_img = is_array( $your_img_src );

	// out
		$return = $this->app->make('/html/element')->tag('div')
			->add_attr('id', $metabox_id)
			// ->add( $return )
			;

	// Your image container, which can be manipulated with js
		$div_custom_img_container = $this->app->make('/html/element')->tag('div')
			->add_attr('class', 'custom-img-container')
			;
		if( $you_have_img ){
			$div_custom_img_container
				->add(
					$this->app->make('/html/element')->tag('img')
						->add_attr('src', $your_img_src[0])
						->add_attr('style', 'max-width:100%;')
					)
				;
		}
		$return
			->add( $div_custom_img_container )
			;

	// Your add & remove image links
		$links = $this->app->make('/html/list')
			->set_gutter(1)
			;

		$upload_link = $this->app->make('/html/element')->tag('a')
			->add_attr('href', $upload_link)
			->add_attr('class', 'upload-custom-img')
			->add( __('Set Custom Icon', 'locatoraid') )
			;
		if( $you_have_img ){
			$upload_link
				->add_attr('class', 'hc-hide')
				;
		}

		$delete_link = $this->app->make('/html/element')->tag('a')
			->add_attr('href', '#')
			->add_attr('class', 'delete-custom-img')
			->add( __('Reset', 'locatoraid') )
			;
		if( ! $you_have_img ){
			$delete_link
				->add_attr('class', 'hc-hide')
				;
		}

		$links
			->add( $upload_link )
			->add( $delete_link )
			;
		$return
			->add( $links )
			;

	// A hidden input to set and post the chosen image id
		$return
			->add(
				$this->app->make('/form/hidden')
					->render( $name, esc_attr($your_img_id) )
					->add_attr('class', 'custom-img-id')
				)
			;

		return $return;
	}
}
