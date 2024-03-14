<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    sb_bar
 * @subpackage sb_bar/admin
 */
class sb_bar_Settings extends sb_bar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $sb_bar    The ID of this plugin.
	 */
	private $sb_bar;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $sb_bar       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $sb_bar ) {

		$this->id    = 'general';
		$this->label = __( 'General', 'woocommerce' );
		$this->sb_bar = $sb_bar;
		$this->plugin_settings_tabs['haha'] = 'bbb';
	}

	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init(){

		// register_setting( $option_group, $option_name, $settings_sanitize_callback );
		register_setting(
			$this->sb_bar . '_options',
			$this->sb_bar . '_options',
			array( $this, 'settings_sanitize' )
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->sb_bar . '-display-options', // section
			apply_filters( $this->sb_bar . '-display-section-title', __( '', $this->sb_bar ) ),
			array( $this, 'display_options_section' ),
			$this->sb_bar
		);

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'disable-bar',
			apply_filters( $this->sb_bar . '-disable-bar-label', __( 'Disable Bar', $this->sb_bar ) ),
			array( $this, 'disable_bar_options_field' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options' // section to add to
		);
		add_settings_field(
			'post-type',
			apply_filters( $this->sb_bar . '-post-type-label', __( 'Show on which post types', $this->sb_bar ) ),
			array( $this, 'post_type' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options' // section to add to
		);
		add_settings_field(
			'ttr-text',
			apply_filters( $this->sb_bar . '-ttr-text-label', __( 'Change "Time to read" text', $this->sb_bar ) ),
			array( $this, 'ttr_input_field' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'by-text',
			apply_filters( $this->sb_bar . '-author-text-label', __( 'Change Author "by" text', $this->sb_bar ) ),
			array( $this, 'author_input_field' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'wpm-left',
			apply_filters( $this->sb_bar . '-wpm-left', __( 'Words Per Minute', $this->sb_bar ) ),
			array( $this, 'wpm_field' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);

		add_settings_field(
			'comment-box-id',
			apply_filters( $this->sb_bar . '-comment-box-label', __( 'Comment box ID', $this->sb_bar ) ),
			array( $this, 'comment_box_id' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'prev-next-posts',
			apply_filters( $this->sb_bar . '-prev-next-posts', __( 'Prev/Next Posts', $this->sb_bar ) ),
			array( $this, 'prev_next_posts' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'custom-color',
			apply_filters( $this->sb_bar . '-custom-color', __( 'Choose Color', $this->sb_bar ) ),
			array( $this, 'custom_color' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'custom-title',
			apply_filters( $this->sb_bar . '-custom-title', __( 'Shorten Big Post Titles', $this->sb_bar ) ),
			array( $this, 'custom_title' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);
		add_settings_field(
			'twitter-via',
			apply_filters( $this->sb_bar . '-twitter-via', __( 'Twitter via @username(without @)', $this->sb_bar ) ),
			array( $this, 'twitter_via' ),
			$this->sb_bar,
			$this->sb_bar . '-display-options'
		);

	}

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_options_section( $params ) {

		echo '<p>' . $params['title'] . '</p>';

	} // display_options_section()


	/**
	 * Enable Bar Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_bar_options_field() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-bar'] ) ) {

			$option = $options['disable-bar'];

		}

		?><input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[disable-bar]" name="<?php echo $this->sb_bar; ?>_options[disable-bar]" value="1" <?php checked( $option, 1 , true ); ?> />
		<p class="description">Disabling bar is also disabling front end loading of scripts css/js.</p> <?php
	} // disable_bar_options_field()

	/**
	 * Enable Bar Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function post_type() {

		$options 	= get_option( $this->sb_bar . '_options' );
		$option 	= array();

		if ( ! empty( $options['post-type'] ) ) {
			$option = $options['post-type'];
		}

		$args = array(
		   'public'   => true
		);
		$post_types = get_post_types( $args, 'names' );

		foreach ( $post_types as $post_type ) {
			if($post_type != 'page' && $post_type != 'attachment') {

				$checked = in_array($post_type, $option) ? 'checked="checked"' : ''; ?>
				<p>
					<input type="checkbox" id="<?php echo $this->sb_bar; ?>_options[post-type]" name="<?php echo $this->sb_bar; ?>_options[post-type][]" value="<?php echo esc_attr( $post_type ); ?>" <?php echo $checked; ?> />
		   			<?php echo $post_type; ?>			
		   		</p>
			<?php }
				
		}  ?>
			<p class="description">IMPORTANT: Bar will not show up untill one of these is checked.</p>
	<?php 
	} // post_type()

	/**
	 * Time to read text field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function ttr_input_field() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= 'time to read:';

		if ( ! empty( $options['ttr-text'] ) ) {
			$option = $options['ttr-text'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[ttr-text]" name="<?php echo $this->sb_bar; ?>_options[ttr-text]" value="<?php echo esc_attr( $option ); ?>">
		<?php
	} // ttr_input_field()

	/**
	 * Author Text Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function author_input_field() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= 'by';

		if ( ! empty( $options['by-text'] ) ) {
			$option = $options['by-text'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[by-text]" name="<?php echo $this->sb_bar; ?>_options[by-text]" value="<?php echo esc_attr( $option ); ?>">
		<?php
	} // author_input_field()


	/**
	 * Word Per Minute Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function wpm_field() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= '250';

		if ( ! empty( $options['wpm-text'] ) ) {
			$option = $options['wpm-text'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[wpm-text]" name="<?php echo $this->sb_bar; ?>_options[wpm-text]" value="<?php echo esc_attr( $option ); ?>">
		<p class="description">They say 250 words per minute is avarage read time, you can increase/decrease it here. After which plugin will calculate new time to read per article.</p>
		<?php
	} // wpm_field()

	/**
	 * Comments box ID
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function comment_box_id() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= 'comments';

		if ( ! empty( $options['comment-box-id'] ) ) {
			$option = $options['comment-box-id'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[comment-box-id]" name="<?php echo $this->sb_bar; ?>_options[comment-box-id]" value="<?php echo esc_attr( $option ); ?>">
		<p class="description">(without #) This is needed for comment to scroll to comment box on click. Default one is "comments".</p>
		<?php
	} // comment_box_id()


	/**
	 * Prev/Next Posts
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function prev_next_posts() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= '';

		if ( ! empty( $options['prev-next-posts'] ) ) {
			$option = $options['prev-next-posts'];
		}

		?>
		<select id="<?php echo $this->sb_bar; ?>_options[prev-next-posts]" name="<?php echo $this->sb_bar; ?>_options[prev-next-posts]" >
			<option value="cat" <?php selected( $option, "cat" ); ?> >Posts from Same Category</option>
			<option value="tags" <?php selected( $option, "tags" ); ?> >Posts with same Tags</option>
			<option value="all" <?php selected( $option, "all" ); ?> >All Posts</option>
		</select>
		<p class="description">Do you want prev/next buttons to show posts from all categories or just from current post category?</p>
		<?php

	} // prev_next_posts()

	/**
	 * Custom Color Scheme
	 *
	 * @since 		1.1.0
	 * @return 		mixed 			The settings field
	 */
	public function custom_color() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= 'default';

		if ( ! empty( $options['custom-color'] ) ) {
			$option = $options['custom-color'];
		}

		?>
		<select id="<?php echo $this->sb_bar; ?>_options[custom-color]" name="<?php echo $this->sb_bar; ?>_options[custom-color]" >
			<option value="default" <?php selected( $option, "default" ); ?> >Default (Everyone loves blue)</option>
			<option value="white" <?php selected( $option, "white" ); ?> >Clouds(White)</option>
			<option value="green" <?php selected( $option, "green" ); ?> >Green</option>
			<option value="orange" <?php selected( $option, "orange" ); ?> >Orange</option>
			<option value="red" <?php selected( $option, "red" ); ?> >Red</option>
			<option value="purple" <?php selected( $option, "purple" ); ?> >Purple</option>
			<option value="asphalt" <?php selected( $option, "asphalt" ); ?> >Wet Asphalt</option>
		</select>
		<p class="description">Choose one of predefined colors. Color picker will come later, remember - keeping plugin fast and light is priority.</p>
		<?php

	} // custom_color()

	/**
	 * Twitter via
	 *
	 * @since 		1.2.0
	 * @return 		mixed 			The settings field
	 */
	public function twitter_via() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= '';

		if ( ! empty( $options['twitter-via'] ) ) {
			$option = $options['twitter-via'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[twitter-via]" name="<?php echo $this->sb_bar; ?>_options[twitter-via]" value="<?php echo esc_attr( $option ); ?>">
		<p class="description">When sharing via twitter, add @via username that you want to be. Yoursite twitter name usually.</p>
		<?php
	} // twitter_via()

	/**
	 * Custom Title
	 *
	 * @since 		1.1.1
	 * @return 		mixed 			The settings field
	 */
	public function custom_title() {

		$options  	= get_option( $this->sb_bar . '_options' );
		$option 	= '';

		if ( ! empty( $options['custom-title'] ) ) {
			$option = $options['custom-title'];
		}

		?>
		<input type="text" id="<?php echo $this->sb_bar; ?>_options[custom-title]" name="<?php echo $this->sb_bar; ?>_options[custom-title]" value="<?php echo esc_attr( $option ); ?>">
		<p class="description">You can shorten long post titles so that share buttons can fit. Enter maximum number of letters title can have before cutting it and placing three dots. Leave blank to always show full title.</p>
		<?php
	} // custom_title()

}
