<?php

class EIC_Grid {

    private $post;
    private $data;

    public function __construct( $post )
    {
        // Get associated post
        if( is_object( $post ) && $post instanceof WP_Post ) {
            $this->post = $post;
        } else if( is_numeric( $post ) ) {
            $this->post = get_post( $post );
        } else {
            throw new InvalidArgumentException( 'Grids can only be instantiated with a Post object or Post ID.' );
        }

        // Get metadata
        $this->data = get_post_meta( $this->post->ID, 'eic_grid_data', true );
    }

    public function get_data()
    {
		$data = $this->data;

		// Prevent issues with unset details.
		if ( ! isset( $data['images'] ) || ! is_array( $data['images'] ) ) {
			$data['images'] = array();
		}

	    return $data;
    }

	public function update_data( $data )
	{
		$data['id'] = $this->ID();
		$data['version'] = EIC_VERSION;
		update_post_meta( $this->ID(), 'eic_grid_data', $data );
	}

	public function draw()
	{
        $layout = $this->layout() ? $this->layout() : EasyImageCollage::get()->helper( 'layouts' )->get( $this->layout_name() );
        $layout['name'] = $this->layout_name();

		if( EasyImageCollage::option( 'default_style_display', 'image' ) == 'background' ) {
			return EasyImageCollage::get()->helper( 'layouts' )->draw_layout( $layout, $this );
		} else {
			return EasyImageCollage::get()->helper( 'layouts' )->draw_layout_frontend( $layout, $this );
		}
	}

	// Grid Fields
	public function align()
	{
		return isset( $this->data['properties']['align'] ) ? $this->data['properties']['align'] : 'center';
	}

	public function border_color()
	{
		return $this->data['properties']['borderColor'];
	}

	public function border_width()
	{
		return intval( $this->data['properties']['borderWidth'] );
	}

	public function divider_adjust( $id )
	{
		if( isset( $this->data['dividers'] ) && isset( $this->data['dividers'][$id] ) ) {
			return floatval( $this->data['dividers'][$id] );
		}
		return false;
	}

	public function height()
	{
		return intval( $this->width() / $this->ratio() );
	}

	public function ID()
	{
		return $this->post->ID;
	}

	public function image( $id )
	{
		$images = $this->images();
		return isset( $images[$id] ) ? $images[$id] : false;
	}

	public function images()
	{
		$images = isset( $this->data['images'] ) && is_array( $this->data['images'] ) ? $this->data['images'] : array();
		return $images;
	}

    public function layout()
    {
        return is_array( $this->data['layout'] ) ? $this->data['layout'] : false;
    }

	public function layout_name()
	{
		return is_array( $this->data['layout'] ) ? 'custom-' . $this->ID() : $this->data['layout'];
	}

	public function ratio()
	{
		$ratio = floatval( $this->data['properties']['ratio'] );
		$ratio = $ratio == 0 ? 1 : $ratio;
		return $ratio;
	}

	public function version()
	{
		return isset( $this->data['version'] ) ? $this->data['version'] : '1.11.0';
	}

	public function width()
	{
		return intval( $this->data['properties']['width'] );
	}
}