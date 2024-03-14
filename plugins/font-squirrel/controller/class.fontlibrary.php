<?php
/**
* Font Library class to store and display Font Families
*/
class FontLibrary {
	public function __construct() {
		require_once sprintf( '%s/../model/class.font.php', dirname( __FILE__ ) );
		$font = new FontSq_Font;
		add_action( 'init', array( $font, 'create_post_type' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'print_stylesheets' ) );

		if( is_admin()){
			require_once sprintf( '%s/../model/class.fontsquirrelapi.php', dirname( __FILE__ ) );
			$this->API = new FontSquirrelAPI( WP_CONTENT_DIR . "/fonts" );
			add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
			add_filter( 'views_edit-font', array( $this->API, 'list_classifications' ) );
			add_action( 'admin_menu', array( $this, 'register_page' ) );
			add_filter( 'default_title', array( $this->API, 'install_font' ), 10, 2 );
			add_action( 'add_meta_boxes_font', array( $this, 'register_meta_boxes' ), 10, 1 );
			add_filter( 'manage_edit-font_columns', array( $this, 'register_sample_image_column' ) );
			add_action( 'manage_font_posts_custom_column', array( $this, 'display_sample_image' ), 10, 2 );
			if( !is_multisite() ) add_action( 'before_delete_post', array( $this->API, 'remove_font' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'print_stylesheets' ) );

			require_once sprintf( '%s/../view/class.tinymce.php', dirname( __FILE__ ) );
			$tinymce = new FontSq_TinyMce();
			add_filter( 'mce_css', array( $tinymce, 'print_stylesheets' ) );
			add_filter( 'mce_buttons_2', array( $tinymce, 'font_buttons' ) );
			add_filter( 'tiny_mce_before_init', array( $tinymce, 'font_list' ) );

			// create a folder for downloaded fonts if needed
			if( !is_dir( WP_CONTENT_DIR . '/fonts' ) ) {
				mkdir( WP_CONTENT_DIR . '/fonts' );
			}
		}
	}

	public function print_stylesheets( $hook ){
		if( is_admin() && $hook != 'post.php' &&  $hook != 'widgets.php' ) return;

		$fonts = FontSq_Font::get_installed_fonts();
		foreach( $fonts as $font ) {
			wp_enqueue_style($font->family, $font->stylesheet);
		}
	}

	public function register_meta_boxes( $post ){
		if(isset($_REQUEST['family']) || $post->post_status !== 'auto-draft' ) {
			$font = new FontSq_Font($post);
			add_meta_box( 
				'specimens',
				__( 'Specimens', 'fontsquirrel' ),
				array( $font, 'display_sample_images' ),
				'font',
				'normal',
				'default'
			);
		}
	}

	public function register_sample_image_column( $columns ){
		$date = $columns['date'];
		unset( $columns['date'] );
		$columns['preview'] = __('Sample', 'fontsquirrel');
		$columns['date'] = $date;
		return $columns;
	}

	public function display_sample_image( $column, $post_id ){
		if( $column == 'preview' ) {
			$font = new FontSq_Font(get_post($post_id));
			$font->display_sample_raw();
		}
	}

	public function row_actions( $actions, $post ) {
		$delete = '<a class="submitdelete" onclick="return showNotice.warn();" href="' . wp_nonce_url( "post.php?action=delete&amp;post={$post->ID}", 'delete-post_' . $post->ID ) . '">' . __('Delete Permanently') . '</a>';
		$show = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Show this item' ) ) . '">' . __( 'Show' ) . '</a>';
		return array(
			'show' => $show,
			'delete' => $delete,
		);
	}

	public function register_page(){
		add_submenu_page( 'edit.php?post_type=font', __('Search Fonts', 'fontsquirrel'), __('Search Fonts', 'fontsquirrel'), 'edit_posts', 'search-fonts', array( $this, 'font_list_page' ) );
	}

	/**
	* Page to display fonts from Font Squirrel and install them
	* Similar to wp-admin/edit.php page
	*/
	public function font_list_page(){
		_get_list_table('WP_Posts_List_Table');
		require_once( dirname( __FILE__ ) . '/../view/class.fonts-list-table.php' );
		$wp_list_table = new FontSq_List_Table();
		// fetch families description
		$wp_list_table->prepare_items($this->API, isset($_REQUEST['classification']) ? $_REQUEST['classification'] : null);

		$post_type_object = get_post_type_object( 'font' );
		$title = $post_type_object->labels->name;
		?><div class="wrap">
		<h2><?php
		echo esc_html( $post_type_object->labels->name );
		if ( current_user_can( $post_type_object->cap->create_posts ) )
			echo ' <a href="' . esc_url( admin_url( 'post-new.php?post_type=font' ) ) . '" class="add-new-h2">' . esc_html( $post_type_object->labels->add_new ) . '</a>';
		if ( ! empty( $_REQUEST['s'] ) )
			printf( ' <span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', get_search_query() );
		?></h2>
		<ul class='subsubsub'>
		<?php $views = $this->API->list_classifications(array());
		foreach ( $views as $class => $view ) {
			$views[ $class ] = "\t<li class='$class'>$view";
		}
		echo implode( " |</li>\n", $views ) . "</li>\n"; ?>
		</ul>

		<form id="posts-filter" action="" method="get">

		<?php $wp_list_table->search_box( $post_type_object->labels->search_items, 'post' ); ?>

		<input type="hidden" name="post_status" class="post_status_page" value="<?php echo !empty($_REQUEST['post_status']) ? esc_attr($_REQUEST['post_status']) : 'all'; ?>" />
		<input type="hidden" name="post_type" class="post_type_page" value="font" />
		<?php if ( ! empty( $_REQUEST['show_sticky'] ) ) { ?>
		<input type="hidden" name="show_sticky" value="1" />
		<?php } ?>

		<?php $wp_list_table->display(); ?>

		</form>

		<?php
		if ( $wp_list_table->has_items() )
			$wp_list_table->inline_edit();
		?>

		<div id="ajax-response"></div>
		<br class="clear" />
		</div>
		<?php
	}
}


