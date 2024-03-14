<?php
/**
 * Renderer for Flickr slides.
 * 
 * @since 4.0.0
 */
class BQW_SP_Flickr_Slide_Renderer extends BQW_SP_Dynamic_Slide_Renderer {

	/**
	 * The Flickr instance.
	 *
	 * @since  1.0.0
	 * 
	 * @var object
	 */
	protected $flickr_instance = null;

	/**
	 * Initialize the renderer by declaring the supported tags.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->registered_tags = array(
			'image' => array( $this, 'render_image' ),
			'image_src' => array( $this, 'render_image_src' ),
			'image_title' => array( $this, 'render_image_title' ),
			'image_description' => array( $this, 'render_image_description' ),
			'image_link' => array( $this, 'render_image_link' ),
			'date' => array( $this, 'render_date' ),
			'username' => array( $this, 'render_username' ),
			'user_link' => array( $this, 'render_user_link' )
		);

		$this->registered_tags = apply_filters( 'sliderpro_flickr_tags', $this->registered_tags );
	}

	/**
	 * Return the final HTML markup of the slide.
	 *
	 * @since  1.0.0
	 * 
	 * @return string The slide HTML.
	 */
	public function render() {
		parent::render();
		
		$result = $this->query();
		$this->html_output = $this->replace_tags( $result );

		return $this->html_output;
	}

	/**
	 * Request data from Flickr based on the specified slide settings.
	 *
	 * @since 4.0.0
	 * 
	 * @return array The array of photo data.
	 */
	protected function query() {
		$loaded_photos = null;
		$collection_name = null;

		$api_key = $this->get_setting_value('flickr_api_key');

		if ( $api_key !== '' ) {
			$this->flickr_instance = new BQW_Flickr( $api_key );
		} else {
			return false;
		}

		$data_type = $this->get_setting_value( 'flickr_load_by' );
		$data_id = $this->get_setting_value( 'flickr_id' );
		$limit = $this->get_setting_value( 'flickr_per_page' );

		if ( $data_type === 'set_id' ) {
			$loaded_photos = $this->flickr_instance->get_photos_by_set_id( $data_id, 'description,date_upload,owner_name', $limit );
			$collection_name = 'photoset';
		} else if ( $data_type === 'user_id' ) {
			$loaded_photos = $this->flickr_instance->get_photos_by_user_id( $data_id, 'description,date_upload,owner_name', $limit );
			$collection_name = 'photos';
		}

		$photos = $loaded_photos[ $collection_name ]['photo'];

		foreach ( $photos as &$photo ) {
			$photo['owner'] = $collection_name === 'photoset' ? $loaded_photos['photoset']['owner'] : $photo['owner'];
		}

		return $photos;
	}

	/**
	 * Replace the registered tags with actual content
	 * and return the final HTML markup of the slide.
	 *
	 * @since 4.0.0
	 *
	 * @param  $photos The array of photos.
	 * @return string  The slide's HTML markup.
	 */
	protected function replace_tags( $photos ) {
		$final_html = '';
		$tags = $this->get_slide_tags();

		foreach ( $photos as $photo ) {
			$content = $this->html_output;

			foreach ( $tags as $tag ) {
				$result = $this->render_tag( $tag['name'], $tag['arg'], $photo );
				$content = str_replace( $tag['full'], $result, $content );
			}

			$final_html .= $content;
		}

		return $final_html;
	}

	/**
	 * Return the photo as an HTML image element.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag. The image size.
	 * @param  object $photo   The current photo.
	 * @return string          The photo HTML.
	 */
	protected function render_image( $tag_arg, $photo ) {
		$image_size = $tag_arg !== false ? $tag_arg : 'medium';
		$image_src = $this->flickr_instance->get_photo_url( $photo, $image_size );
		$image_full = '<img src="' . $image_src . '" />';

		return $image_full;
	}

	/**
	 * Return the photo URL.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag. The image size.
	 * @param  object $photo   The current photo.
	 * @return string          The photo URL.
	 */
	protected function render_image_src( $tag_arg, $photo ) {
		$image_size = $tag_arg !== false ? $tag_arg : 'medium';
		$image_src = $this->flickr_instance->get_photo_url( $photo, $image_size );

		return $image_src;
	}

	/**
	 * Return the title of the photo.
	 * 
	 * @since 4.0.5
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current photo.
	 * @return string          The photo title.
	 */
	protected function render_image_title( $tag_arg, $photo ) {
		return $photo['title'];
	}

	/**
	 * Return the description of the photo.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current photo.
	 * @return string          The photo description.
	 */
	protected function render_image_description( $tag_arg, $photo ) {
		return $photo['description']['_content'];
	}

	/**
	 * Return the link of the photo.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current photo.
	 * @return string          The photo link.
	 */
	protected function render_image_link( $tag_arg, $photo ) {
		return 'http://www.flickr.com/photos/' . $photo['owner'] . '/' . $photo['id'] . '/';
	}

	/**
	 * Return the date of the photo.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current photo.
	 * @return string          The photo date.
	 */
	protected function render_date( $tag_arg, $photo ) {
		return date( 'F j Y', $photo['dateupload'] );
	}

	/**
	 * Return the username of the photo's owner.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current photo.
	 * @return string          The owner's username.
	 */
	protected function render_username( $tag_arg, $photo ) {
		return $photo['ownername'];
	}

	/**
	 * Return the link to the owner's profile.
	 * 
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current photo.
	 * @return string          The owner's profile link.
	 */
	protected function render_user_link( $tag_arg, $photo ) {
		return 'http://www.flickr.com/people/' . $photo['owner'] . '/';
	}
}