<?php
class CSBCustomPost{
	public $post_type = 'csb';

	public function __construct(){
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
		add_action( 'init', [$this, 'onInit'] );
		add_shortcode( 'csb', [$this, 'onAddShortcode'] );
		add_filter( 'manage_csb_posts_columns', [$this, 'manageCSBPostsColumns'], 10 );
		add_action( 'manage_csb_posts_custom_column', [$this, 'manageCSBPostsCustomColumns'], 10, 2 );
		add_action( 'use_block_editor_for_post', [$this, 'useBlockEditorForPost'], 999, 2 );
		add_filter( 'custom_menu_order', [$this, 'orderSubMenu'] );
	}

	function adminEnqueueScripts( $hook ){
		if( 'edit.php' === $hook || 'post.php' === $hook ){
			wp_enqueue_style( 'csb-admin-post', CSB_DIR_URL . 'dist/admin-post.css', [], CSB_VERSION );
			wp_enqueue_script( 'csb-admin-post', CSB_DIR_URL . 'dist/admin-post.js', [], CSB_VERSION );
			wp_set_script_translations( 'csb-admin-post', 'content-slider-block', CSB_DIR_PATH . 'languages' );
		}
	}

	function onInit(){
		$menuIcon = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 512 512' fill='#fff'><path d='m181.4 319.1 106.7-129.1 106.6 129.1z' /><circle cx='213' cy='153.6' r='36.3' /><path d='m227.7 274.6-34.5-42.3-75.9 86.8h100.8z' /><path d='m475.2 39.6h-438.4c-20.3 0-36.8 16.5-36.8 36.8v283.6c0 20.3 16.5 36.8 36.8 36.8h438.5c20.3 0 36.8-16.5 36.8-36.8v-283.6c-.1-20.3-16.6-36.8-36.9-36.8zm16.8 320.4c0 9.2-7.5 16.8-16.8 16.8h-438.4c-9.2 0-16.8-7.5-16.8-16.8v-283.6c0-9.2 7.5-16.8 16.8-16.8h438.5c9.2 0 16.8 7.5 16.8 16.8v283.6z' /><path d='m447.1 194c-3.9-3.9-10.2-3.9-14.1 0s-3.9 10.2 0 14.1l10 10-10 10c-3.9 3.9-3.9 10.2 0 14.1 2 2 4.5 2.9 7.1 2.9s5.1-1 7.1-2.9l17.1-17.1c1.9-1.9 2.9-4.4 2.9-7.1s-1.1-5.2-2.9-7.1z' /><path d='m79.1 194c-3.9-3.9-10.2-3.9-14.1 0l-17.1 17.1c-1.9 1.9-2.9 4.4-2.9 7.1s1.1 5.2 2.9 7.1l17.1 17.1c2 2 4.5 2.9 7.1 2.9s5.1-1 7.1-2.9c3.9-3.9 3.9-10.2 0-14.1l-10-10 10-10c3.8-4 3.8-10.4-.1-14.3z' /><path d='m175.7 418.3c-14.9 0-27 12.1-27 27s12.1 27 27 27 27-12.1 27-27-12.1-27-27-27zm0 34.1c-3.9 0-7-3.2-7-7 0-3.9 3.2-7 7-7s7 3.2 7 7c.1 3.8-3.1 7-7 7z' /><path d='m256 418.3c-14.9 0-27 12.1-27 27s12.1 27 27 27 27-12.1 27-27-12.1-27-27-27zm0 34.1c-3.9 0-7-3.2-7-7 0-3.9 3.2-7 7-7 3.9 0 7 3.2 7 7s-3.1 7-7 7z' /><path d='m336.3 418.3c-14.9 0-27 12.1-27 27s12.1 27 27 27 27-12.1 27-27-12.1-27-27-27zm0 34.1c-3.9 0-7-3.2-7-7 0-3.9 3.2-7 7-7 3.9 0 7 3.2 7 7s-3.1 7-7 7z' /></svg>";

		register_post_type( 'csb', [
			'labels'				=> [
				'name'			=> __( 'Content Slider', 'content-slider-block'),
				'singular_name'	=> __( 'Content Slider', 'content-slider-block' ),
				'add_new'		=> __( 'Add New', 'content-slider-block' ),
				'add_new_item'	=> __( 'Add New', 'content-slider-block' ),
				'edit_item'		=> __( 'Edit', 'content-slider-block' ),
				'new_item'		=> __( 'New', 'content-slider-block' ),
				'view_item'		=> __( 'View', 'content-slider-block' ),
				'search_items'	=> __( 'Search', 'content-slider-block'),
				'not_found'		=> __( 'Sorry, we couldn\'t find the that you are looking for.', 'content-slider-block' )
			],
			'public'				=> false,
			'show_ui'				=> true, 		
			'show_in_rest'			=> true,							
			'publicly_queryable'	=> false,
			'exclude_from_search'	=> true,
			'menu_position'			=> 14,
			'menu_icon'				=> 'data:image/svg+xml;base64,' . base64_encode($menuIcon),		
			'has_archive'			=> false,
			'hierarchical'			=> false,
			'capability_type'		=> 'page',
			'rewrite'				=> [ 'slug' => 'csb' ],
			'supports'				=> [ 'title', 'editor' ],
			'template'				=> [ ['csb/content-slider-block'] ],
			'template_lock'			=> 'all',
		]); // Register Post Type
	}

	function onAddShortcode( $atts ) {
		$post_id = $atts['id'];

		$post = get_post( $post_id );
		$blocks = parse_blocks( $post->post_content );

		ob_start();
		echo render_block($blocks[0]);

		return ob_get_clean();
	}

	function manageCSBPostsColumns( $defaults ) {
		unset( $defaults['date'] );
		$defaults['shortcode'] = 'ShortCode';
		$defaults['date'] = 'Date';
		return $defaults;
	}

	function manageCSBPostsCustomColumns( $column_name, $post_ID ) {
		if ( $column_name == 'shortcode' ) {
			echo "<div class='bPlAdminShortcode' id='bPlAdminShortcode-$post_ID'>
				<input value='[csb id=$post_ID]' onclick='copyBPlAdminShortcode($post_ID)'>
				<span class='tooltip'>Copy To Clipboard</span>
			</div>";
		}
	}

	function useBlockEditorForPost($use, $post){
		if ( $this->post_type === $post->post_type ) {
			return true;
		}
		return $use;
	}

	function orderSubMenu( $menu_ord ){
		global $submenu;

		$sMenu = $submenu['edit.php?post_type=csb'];
		$arr = [];
		if( csbIsPremium() ){
			if( isset( $sMenu[5] ) ){
				$arr[] = $sMenu[5]; // Content Slider
			}
			if( isset( $sMenu[10] ) ){
				$arr[] = $sMenu[10]; // Add New
			}
		}
		if( isset( $sMenu[11] ) ){
			$arr[] = $sMenu[11]; // Help
		}
		if( ( !csbIsPremium() || CSB_HAS_PRO ) && isset( $sMenu[12] ) ){
			$arr[] = $sMenu[12]; // Upgrade || Pricing || Account
		}
		$submenu['edit.php?post_type=csb'] = $arr;
	
		return $menu_ord;
	}
}
new CSBCustomPost();