<?php
/**
* Font class represent a Font Squirrel Font family
*/
class FontSq_Font {
	/**
	* Create a Font object from the WP Post with the 'font' custom post type
	*/
	public function __construct( $WP_Post = null ) {
		if( !$WP_Post || $WP_Post->post_type != 'font') return;
		$this->post = $WP_Post;
		$this->title = $WP_Post->post_title;
		$this->family = get_post_meta($WP_Post->ID, 'font-family', true);
		$this->name = get_post_meta($WP_Post->ID, 'font-name', true);
		$this->root_dir = WP_CONTENT_DIR . "/fonts/" . $this->family;
		$this->root_url = WP_CONTENT_URL . "/fonts/" . $this->family;
		$this->stylesheet = $this->root_url .'/stylesheet.css';
	}

	public function create_post_type(){
		register_post_type('font', array(
			'labels' => array(
				'name'			=> __('Fonts', 'fontsquirrel'),
				'singular_name' => __('Font', 'fontsquirrel'),
				'add_new_item'	=> _x('Add New Font', 'font', 'fontsquirrel'),
				'edit_item'		=> __('Edit Font', 'fontsquirrel'),
				'new_item'		=> __('New Font', 'fontsquirrel'),
				'view_item'		=> __('View Font', 'fontsquirrel'),
				'search_items' 	=> __('Search Font', 'fontsquirrel'),
				'not_found' 	=> __('No fonts found', 'fontsquirrel'),
				'not_found_in_trash' => __('No fonts found in Trash', 'fontsquirrel'),
			),
			'description' => __('a font family', 'fontsquirrel'),
			'public' => false,
			'show_ui' => true,
			'menu_icon' => plugins_url('../img/squirrel-menu.png', __FILE__),
			'supports' => array( 'title' ),
		));
	}

	public function get_installed_fonts() {
		$posts = get_posts(array(
			'post_type' => 'font',
			'nopaging'	=> true,
		));
		$fonts = array();
		foreach( $posts as $post ) {
			$fonts[] = new FontSq_Font($post);
		}
		return $fonts;
	}

	public function display_sample_images(){
		?><img src="<?php echo $this->root_url . '/sample_alphabet.png' ?>" style="padding-bottom:1em;margin-bottom:1em;border-bottom:1px #ccc solid;"/>
		<img src="<?php echo $this->root_url . '/sample_image.png' ?>" style="padding-bottom:1em;margin-bottom:1em;border-bottom:1px #ccc solid;"/>
		<img src="<?php echo $this->root_url . '/sample_paragraph_9.png' ?>"/>
		<img src="<?php echo $this->root_url . '/sample_paragraph_10.png' ?>" style="margin-left:20px"/>
		<img src="<?php echo $this->root_url . '/sample_paragraph_12.png' ?>" style="margin-left:20px"/>
		<img src="<?php echo $this->root_url . '/sample_paragraph_16.png' ?>" style="margin-left:20px"/><?php
	}

	public function display_sample_raw(){
		?><img src="<?php echo $this->root_url . '/listing_image.png' ?>"/><?php
	}
}
