<?php

// Core class for plugin functionality

final class Plugify_Clean_Testimonials {

	public function __construct () {

		// Register actions
		add_action( 'init', array( __CLASS__, 'init' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
		add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ) );
		add_action( 'wp_insert_post', array( __CLASS__, 'insert_testimonial' ), 10, 1 );
		add_action( 'manage_posts_custom_column', array( __CLASS__, 'testimonial_column' ), 10, 2 );

		// Filters for testimonial post type columns
		add_filter( 'manage_edit-testimonial_columns', array( __CLASS__, 'testimonial_columns' ), 5 );
		add_filter( 'manage_edit-testimonial_sortable_columns', array( __CLASS__, 'testimonial_sortable_columns' ), 5 );

		// Filter for testimonial category taxonomy columns
		add_filter( 'manage_edit-testimonial_category_columns', array( __CLASS__, 'testimonial_taxonomy_columns' ) );
		add_filter( 'manage_testimonial_category_custom_column', array( __CLASS__, 'testimonial_taxonomy_column' ), 10, 3 );

		// AJAX hooks
		add_action( 'wp_ajax_get_random_testimonial', array( __CLASS__, 'ajax_get_random_testimonial' ) );
		add_action( 'wp_ajax_nopriv_get_random_testimonial', array( __CLASS__, 'ajax_get_random_testimonial' ) );

		// Load textdomain
		$this->load_textdomain();
		
		// Install tasks
		register_activation_hook( trailingslashit( dirname( __FILE__ ) ) . 'clean-testimonials.php', array( &$this, 'install' ) );

	}

	public static function install () {

		// Store timestamp of when activation occured
		if( !get_option( 'ct_activated' ) ) {
			update_option( 'ct_activated', time() );
		}

	}

	/**
	* Load language files
	*
	* @since 1.5.1
	*
	* @return void
	*/
	public function load_textdomain() {

		// Set filter for plugin's languages directory
		$lang_dir = plugin_dir_path( __FILE__ ) . 'languages/';

		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'clean-testimonials' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'clean-testimonials', $locale );

		// Setup paths to current locale file
		$mofile_local = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/clean-testimonials/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			load_textdomain( 'clean-testimonials', $mofile_global );
		}
		elseif ( file_exists( $mofile_local ) ) {
			load_textdomain( 'clean-testimonials', $mofile_local );
		}
		else {
			// Load the default language files
			load_plugin_textdomain( 'clean-testimonials', false, $lang_dir );
		}

	}

	public static function init () {

		/*≈=====≈=====≈=====≈=====≈=====≈=====≈=====
		Testimonial Post Type
		≈=====≈=====≈=====≈=====≈=====≈=====≈=====*/
		// Setup core dependencies
		$post_type_labels = array(
			'name' => __( 'Testimonials', 'clean-testimonials' ),
			'singular_name' => __( 'Testimonial', 'clean-testimonials' ),
			'add_new' => __( 'Add New', 'clean-testimonials' ),
			'add_new_item' => __( 'Add New Testimonial', 'clean-testimonials' ),
			'edit_item' => __( 'Edit Testimonial', 'clean-testimonials' ),
			'new_item' => __( 'New Testimonial', 'clean-testimonials' ),
			'view_item' => __( 'View Testimonial', 'clean-testimonials' ),
			'search_items' => __( 'Search Testimonials', 'clean-testimonials' ),
			'not_found' =>  __( 'No Testimonials found', 'clean-testimonials' ),
			'not_found_in_trash' => __( 'No Testimonials found in the trash', 'clean-testimonials' ),
			'parent_item_colon' => ''
		);

		// Register the post type
		register_post_type( 'testimonial',
			array(
				 'labels' => $post_type_labels,
				 'singular_label' => __( 'Testimonial', 'clean-testimonials' ),
				 'public' => true,
				 'show_ui' => true,
				 '_builtin' => false,
				 '_edit_link' => 'post.php?post=%d',
				 'capability_type' => 'post',
				 'hierarchical' => false,
				 'rewrite' => array( 'slug' => 'testimonial' ),
				 'query_var' => 'testimonial',
				 'supports' => array( 'title', 'editor', 'thumbnail' ),
				 'menu_position' => 5
			)
		);

		/*≈=====≈=====≈=====≈=====≈=====≈=====≈=====
		Testimonial Taxonomy
		≈=====≈=====≈=====≈=====≈=====≈=====≈=====*/
		// Register and configure Testimonial Category taxonomy
		$taxonomy_labels = array(
			'name' => __( 'Testimonial Categories', 'clean-testimonials' ),
			'singular_name' => __( 'Testimonial Category', 'clean-testimonials' ),
			'search_items' =>  __( 'Search Testimonial Categories', 'clean-testimonials' ),
			'all_items' => __( 'All Testimonial Categories', 'clean-testimonials' ),
			'parent_item' => __( 'Parent Testimonial Categories', 'clean-testimonials' ),
			'parent_item_colon' => __( 'Parent Testimonial Category', 'clean-testimonials' ),
			'edit_item' => __( 'Edit Testimonial Category', 'clean-testimonials' ),
			'update_item' => __( 'Update Testimonial Category', 'clean-testimonials' ),
			'add_new_item' => __( 'Add New Testimonial Category', 'clean-testimonials' ),
			'new_item_name' => __( 'New Testimonial Category', 'clean-testimonials' ),
			'menu_name' => __( 'Categories', 'clean-testimonials' )
	  );

		register_taxonomy( 'testimonial_category', 'testimonial', array(
				'hierarchical' => true,
				'labels' => $taxonomy_labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'testimonials' )
			)
		);

		// Ensure jQuery is enqueued
		wp_enqueue_script( 'jquery' );

		// Enqueue admin scripts
		if( is_admin() )
			wp_enqueue_script( 'ct_scripts', plugins_url( 'assets/js/scripts.js', __FILE__ ), array( 'jquery' ) );

	}

	public static function admin_init () {

		// Add metabox for testimonial meta
		add_meta_box( 'testimonial-details', 'Client Details', array( __CLASS__, 'testimonial_metabox' ), 'testimonial', 'normal', 'core' );

	}

	public static function admin_notices () {

		// Display donation prompt if CT has been installed for more than two weeks
		$installed = get_option( 'ct_activated' );

		if( time() >= ( $installed + ( 86400 * 14 ) ) && !get_option( 'ct_prompted' ) ) {
			echo '<div id="message" class="updated"><p>' . __( 'Loving Clean Testimonials? Help support development by <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=hello%40plugify%2eio&item_name=Plugify%20Plugins%20Development%20Donation&currency_code=USD" target="_blank">buying us a coffee</a>, or leave a <a href="http://wordpress.org/support/view/plugin-reviews/clean-testimonials?filter=5" target="_blank">rating for us!</a> You\'ll never see this again, don\'t worry.', 'clean-testimonials' ) . '</p></div>';
			update_option( 'ct_prompted', 'yes' );
		}

	}

	public static function widgets_init () {

		register_widget( 'Testimonials_Widget' );

	}

	public static function testimonial_columns ( $columns ) {

		unset( $columns['date'] );
		$columns['testimonial_client_name'] = __( 'Client', 'clean-testimonials' );
		$columns['testimonial_client_company_name'] = __( 'Company', 'clean-testimonials' );
		$columns['testimonial_category'] = __( 'Category', 'clean-testimonials' );
		$columns['testimonial_shortcode'] = __( 'Shortcode', 'clean-testimonials' );
		$columns['testimonial_thumbnail'] = __( 'Thumbnail', 'clean-testimonials' );

		$columns['date'] = __( 'Date', 'clean-testimonials' );

		return $columns;

	}

	public static function testimonial_sortable_columns ( $columns ) {

		$columns['testimonial_client_name'] = 'testimonial_client';
		$columns['testimonial_client_company_name'] = 'testimonial_client_company_name';

		return $columns;

	}

	public static function testimonial_column ( $column, $post_id ) {

		global $post;

		if( $post->post_type != 'testimonial' )
			return;

		switch( $column ) {

			case 'testimonial_category':

				$list = get_the_term_list( $post->ID, 'testimonial_category', null, ', ', null );
				echo $list == '' ? '<em>N/A</em>' : $list;

				break;

			case 'testimonial_shortcode':
				echo sprintf( '[testimonial id="%s"]', $post->ID );
				break;

			case 'testimonial_thumbnail':

				if( has_post_thumbnail( $post->ID ) )
					echo wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), array( 64, 64 ) );
				else
					echo __( 'No thumbnail supplied', 'clean-testimonials' );

				break;

			default:

				$value = get_post_meta( $post->ID, $column, true );
				echo $value == '' ? '<em>N/A</em>' : $value;

		}

	}

	public static function testimonial_taxonomy_columns ( $columns ) {

		return array(

			'cb' => '<input type="checkbox" />"',
			'name' => __( 'Name', 'clean-testimonials' ),
			'shortcode' => __( 'Shortcode', 'clean-testimonials' ),
			'slug' => __( 'Slug', 'clean-testimonials' ),
			'posts' => __( 'Testimonials', 'clean-testimonials' )

		);

	}

	public static function testimonial_taxonomy_column ( $out, $column_name, $id ) {

		if( $column_name == 'shortcode' )
			return sprintf( '[testimonials category="%s"]', $id );

	}

	public static function testimonial_metabox ( $post ) {

		global $post;

		// Display Client Details form
		?>

		<table class="testimonial-client-details">

			<tr>
				<td valign="middle" align="left" width="125"><label for="testimonial_client_name"><?php _e( 'Client Name', 'clean-testimonials' ); ?></label></td>
				<td valign="middle" align="left" width="150"><input type="text" name="testimonial_client_name" value="<?php echo esc_attr( get_post_meta( $post->ID, 'testimonial_client_name', true ) ); ?>" />
				<td valign="middle" align="left"><em><?php _e( 'The name of the client giving this testimonial', 'clean-testimonials' ); ?></em></td>
			</tr>
			<tr>
				<td valign="middle" align="left"><label for="testimonial_client_company_name"><?php _e( 'Company Name', 'clean-testimonials' ); ?></label></td>
				<td valign="middle" align="left"><input type="text" name="testimonial_client_company_name" value="<?php echo esc_attr( get_post_meta( $post->ID, 'testimonial_client_company_name', true ) ); ?>" />
				<td valign="middle" align="left"><em><?php _e( 'The company which this client represents', 'clean-testimonials' ); ?></em></td>
			</tr>
			<tr>
				<td valign="middle" align="left"><label for="testimonial_client_email"><?php echo _e( 'Email', 'clean-testimonials' ); ?></label></td>
				<td valign="middle" align="left"><input type="text" name="testimonial_client_email" value="<?php echo esc_attr( get_post_meta( $post->ID, 'testimonial_client_email', true ) ); ?>" />
				<td valign="middle" align="left"><em><?php _e( 'Contact email address of whom is giving the testimonial', 'clean-testimonials' ); ?></em></td>
			</tr>
			<tr>
				<td valign="middle" align="left"><label for="testimonial_client_website"><?php _e( 'Website', 'clean-testimonials' ); ?></label></td>
				<td valign="middle" align="left"><input type="text" name="testimonial_client_company_website" value="<?php echo esc_attr( get_post_meta( $post->ID, 'testimonial_client_company_website', true ) ); ?>" />
				<td valign="middle" align="left"><em><?php _e( 'Website of whom is giving the testimonial', 'clea-testimonials' ); ?></em></td>
			</tr>

		</table>

		<?php

	}

	public static function ajax_get_random_testimonial () {

		$testimonial = get_posts( array(

			'post_type' => 'testimonial',
			'posts_per_page' => 1,
			'orderby' => 'rand'

		) );

		if( $testimonial ) {

			$testimonial = new WP_Testimonial( $testimonial[0]->ID );
			$testimonial->word_limit = isset( $_POST['word_limit'] ) ? $_POST['word_limit'] : -1;

			ob_start();

			$testimonial->render();
			$markup = ob_get_contents();

			ob_end_clean();

			wp_send_json_success( array( 'markup' => $markup, 'testimonial_id' => $testimonial->ID ) );

		}
		else {
			wp_send_json_error();
		}

	}

	public static function insert_testimonial ( $post_id ) {

		global $post;

		if( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( isset( $_GET['action'] ) && $_GET['action'] == 'trash' ) )
			return;

		if( @$post->post_type != 'testimonial' )
			return;

		foreach( $_POST as $key => $value )
			if( strpos( $key, 'testimonial_' ) === 0 )
				update_post_meta( $post_id, $key, sanitize_text_field( $value ) );

	}

}

?>
