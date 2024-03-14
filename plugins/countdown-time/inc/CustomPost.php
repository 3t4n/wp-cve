<?php
class CTBCustomPost{
	public $post_type = 'ctb';

	public function __construct(){
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
		add_action( 'init', [$this, 'onInit'] );
		add_shortcode( 'ctb', [$this, 'onAddShortcode'] );
		add_filter( 'manage_ctb_posts_columns', [$this, 'manageCTBPostsColumns'], 10 );
		add_action( 'manage_ctb_posts_custom_column', [$this, 'manageCTBPostsCustomColumns'], 10, 2 );
		add_action( 'use_block_editor_for_post', [$this, 'useBlockEditorForPost'], 999, 2 );
		add_filter( 'custom_menu_order', [$this, 'orderSubMenu'] );
	}

	function adminEnqueueScripts( $hook ){
		if( 'edit.php' === $hook || 'post.php' === $hook ){
			wp_enqueue_style( 'ctb-admin-post', CTB_DIR_URL . 'dist/admin-post.css', [], CTB_VERSION );
			wp_enqueue_script( 'ctb-admin-post', CTB_DIR_URL . 'dist/admin-post.js', [], CTB_VERSION );
			wp_set_script_translations( 'ctb-admin-post', 'countdown-time', CTB_DIR_PATH . 'languages' );
		}
	}

	function onInit(){
		$menuIcon = "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 512 512' fill='#fff'><path d='m288 48h-64a24 24 0 0 1 0-48h64a24 24 0 0 1 0 48z' /><path d='m414.392 129.608c-1.867-1.867-3.772-3.684-5.692-5.477l19.963-26.769a8 8 0 0 0 -1.63-11.195l-25.65-19.13a8 8 0 0 0 -11.2 1.631l-19.844 26.614a224.63 224.63 0 1 0 44.053 34.326zm-158.392 350.392c-105.869 0-192-86.131-192-192s86.131-192 192-192 192 86.131 192 192-86.131 192-192 192z' /><path d='m408 296h-152a8 8 0 0 1 -8-8v-152a8 8 0 0 1 8-8c88.224 0 160 71.775 160 160a8 8 0 0 1 -8 8z' /><circle cx='201.421' cy='145.912' r='8' /><circle cx='160.185' cy='169.732' r='8' /><circle cx='128.327' cy='205.129' r='8' /><circle cx='108.967' cy='248.638' r='8' /><circle cx='104' cy='296' r='8' /><circle cx='113.912' cy='342.579' r='8' /><circle cx='137.732' cy='383.815' r='8' /><circle cx='173.129' cy='415.673' r='8' /><circle cx='216.638' cy='435.033' r='8' /><circle cx='264' cy='440' r='8' /><circle cx='310.579' cy='430.088' r='8' /><circle cx='351.815' cy='406.268' r='8' /><circle cx='383.673' cy='370.871' r='8' /><circle cx='403.033' cy='327.362' r='8' /></svg>";

		register_post_type( 'ctb', [
			'labels'				=> [
				'name'			=> __( 'Countdown Time', 'countdown-time'),
				'singular_name'	=> __( 'Countdown Time', 'countdown-time' ),
				'add_new'		=> __( 'Add New', 'countdown-time' ),
				'add_new_item'	=> __( 'Add New', 'countdown-time' ),
				'edit_item'		=> __( 'Edit', 'countdown-time' ),
				'new_item'		=> __( 'New', 'countdown-time' ),
				'view_item'		=> __( 'View', 'countdown-time' ),
				'search_items'	=> __( 'Search', 'countdown-time'),
				'not_found'		=> __( 'Sorry, we couldn\'t find the that you are looking for.', 'countdown-time' )
			],
			'public'				=> false,
			'show_ui'				=> true, 		
			'show_in_rest'			=> true,							
			'publicly_queryable'	=> false,
			'exclude_from_search'	=> true,
			'menu_position'			=> 14,
			'menu_icon'				=> 'data:image/svg+xml;base64,' . base64_encode( $menuIcon ),		
			'has_archive'			=> false,
			'hierarchical'			=> false,
			'capability_type'		=> 'page',
			'rewrite'				=> [ 'slug' => 'ctb' ],
			'supports'				=> [ 'title', 'editor' ],
			'template'				=> [ ['ctb/countdown-time'] ],
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

	function manageCTBPostsColumns( $defaults ) {
		unset( $defaults['date'] );
		$defaults['shortcode'] = 'ShortCode';
		$defaults['date'] = 'Date';
		return $defaults;
	}

	function manageCTBPostsCustomColumns( $column_name, $post_ID ) {
		if ( $column_name == 'shortcode' ) {
			echo "<div class='ctbFrontShortcode' id='ctbFrontShortcode-$post_ID'>
				<input value='[ctb id=$post_ID]' onclick='ctbHandleShortcode( $post_ID )'>
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

		$sMenu = $submenu['edit.php?post_type=ctb'];
		$arr = [];
		if( CTB_HAS_PRO && ctbIsPremium() ){
			if( isset( $sMenu[5] ) ){
				$arr[] = $sMenu[5]; // Countdown Time
			}
			if( isset( $sMenu[10] ) ){
				$arr[] = $sMenu[10]; // Add New
			}
		}
		if( isset( $sMenu[11] ) ){
			$arr[] = $sMenu[11]; // Help
		}
		if( isset( $sMenu[12] ) ){
			$arr[] = $sMenu[12]; // Upgrade FS or Account
		}
		$submenu['edit.php?post_type=ctb'] = $arr;
	
		return $menu_ord;
	}
}
new CTBCustomPost();