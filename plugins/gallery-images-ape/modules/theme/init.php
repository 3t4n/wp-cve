<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class wpAPEGalleryModule_Theme extends wpApeGallery_Module{
	
	public $bodyClass = null;

	public $defaultTheme = 0;

	public function __construct(){
		$this->bodyClass = WPAPE_GALLERY_NAMESPACE.'theme_listing';
		parent::__construct();		
	}

	function getModuleFileName(){
		return __FILE__;
	}

	function load(){
		//include_once 'test.php';
	}

	function hooks(){
		add_action( 'init', array( $this, 'registerThemeType') );

		add_action( 'init', array( $this, 'setDefaultTheme') );

		add_action( 'init', array( $this, 'themeDefaultRedirect') );

		add_action( 'wp_ajax_wpape_gallery_default_theme_save', array($this, 'ajax_default_theme_save') );
		
		add_action( 'admin_notices', array($this, 'theme_default_notice_success') );

/*
		add_filter('views_edit-'.WPAPE_GALLERY_THEME_POST, array($this, 'my_filter'));
		add_filter('views_edit-'.WPAPE_GALLERY_THEME_POST, array($this, 'my_filter'));
		add_action( 'before_delete_post', array($this, 'fired_on_delete_theme'),  1 );
		add_action( 'transition_post_status', array($this, 'theme_change_staus'), 10, 3 );
*/

		add_action( 'wp_trash_post', array($this, 'fired_before_delete_theme') );

		if ( 
			apeGalleryHelper::isAdminArea() && 
			apeGalleryHelper::getPostType() == WPAPE_GALLERY_THEME_POST 
		){
			add_action('admin_notices', array($this, 'noticeTryDeleteDefaultTheme'));

			add_filter('admin_body_class', array($this, 'addBodyClass'));

			add_action( 'admin_menu' , array($this, 'removeMetabox') );
			add_filter( 'post_updated_messages', array( $this, 'theme_updated_messages') );

			/* type dialog */
			add_action( 'in_admin_header', 	array( $this, 'assets_files_dialog') );
			add_action( 'in_admin_header', 	array($this, 'dialogHTML') );

			if( apeGalleryHelper::is_edit('list')  ){

				add_filter( 'manage_'.WPAPE_GALLERY_THEME_POST.'_posts_columns' , 		array( $this, 'addColumnsToThemesListing') );
				add_action( 'manage_'.WPAPE_GALLERY_THEME_POST.'_posts_custom_column' , array( $this, 'renderColumnsToThemesListing'), 10, 2 );

				//add_filter( 'manage_posts_columns', array( $this, 'columns_reorder'));
				add_filter( 'post_row_actions', 	array( $this, 'row_actions'), 10, 2 );

				add_action( 'in_admin_header', 	array( $this, 'assets_files') );

				add_action( 'admin_head-edit.php', array( $this, 'theme_change_title_in_list') );

			}

		}
	}


	function theme_change_title_in_list() {
	    add_filter( 'the_title', array( $this, 'construct_new_title'), 100, 2 );
	}

	function construct_new_title( $title, $post_id ) {
	    return $title.$this->getDefaultLabel($post_id);
	}

	public function noticeTryDeleteDefaultTheme(){ 
		if( 
			!isset($_REQUEST['delete_default_theme']) || 
			!$_REQUEST['delete_default_theme']  
		) return ;

		printf( 
			'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
			__("You can't delete default theme. Just  select another theme as default and after that delete current theme.", 'gallery-images-ape')
		);
	}


	public function fired_before_delete_theme( $post_id ){
		
		if ( get_post_type($post_id) != WPAPE_GALLERY_THEME_POST ) return;

		$defaultThemeId = (int) get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 ); 

   		if( $post_id != $defaultThemeId )  return;

   		$url = 'edit.php?post_type=wpape_gallery_theme&delete_default_theme=1';

   		wp_redirect( admin_url($url) );

   		exit();	

	}

/*	
	public function fired_on_delete_theme( $postid ){
    	if ( $post_type != WPAPE_GALLERY_THEME_POST ) return;
	}


	public function theme_change_staus( $new_status, $old_status, $post ) {

    	if ( $post->post_type != WPAPE_GALLERY_THEME_POST ) return;

	   if ( $new_status == 'trash' ) {
			$defaultThemeId = (int) get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 ); 

	   		if( $post->ID == $defaultThemeId ) {
	   			$new_status = $old_status;
		   		$url = 'edit.php?post_type=wpape_gallery_theme&delete_default_theme=1';
		   		wp_redirect( admin_url($url) );
		   		exit();	
	   		}
	   }
	}

	function my_filter($views){
	    $views['import'] = '<a href="#" class="primary">Import</a>';
	    print_r($views);
	    return $views;
	}*/

	function addBodyClass($classes){
		return $classes . ' ' . $this->bodyClass;
	}


	function theme_updated_messages( $messages ) {

		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages[WPAPE_GALLERY_THEME_POST] = array(
		    0  => '', // Unused. Messages start at index 1.

		    1  => __( 'Ape Theme updated.', 'gallery-images-ape' ),
		    2  => __( 'Custom field updated.', 'gallery-images-ape' ),
		    3  => __( 'Custom field deleted.', 'gallery-images-ape' ),
		    4  => __( 'Ape Theme updated.', 'gallery-images-ape' ),
		    
		    /* translators: %s: date and time of the revision */
		    5  => isset( $_GET['revision'] ) ? sprintf( __( 'Ape Theme restored to revision from %s', 'gallery-images-ape' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		    
		    6  => __( 'Ape Theme published.', 'gallery-images-ape' ),
		    7  => __( 'Ape Theme saved.', 'gallery-images-ape' ),
		    8  => __( 'Ape Theme submitted.', 'gallery-images-ape' ),
		    9  => sprintf(
		        	__( 'Ape Theme scheduled for: <strong>%1$s</strong>.', 'gallery-images-ape' ),
		        	date_i18n( __( 'M j, Y @ G:i' ), 
		        	strtotime( $post->post_date ) 
		        )
		    ),
		    10 => __( 'Ape Theme draft updated.', 'gallery-images-ape' )
		);

		return $messages;
	}

	function removeMetabox() {
		remove_meta_box( 'slugdiv' , WPAPE_GALLERY_THEME_POST , 'normal' ); 
	}

	function registerThemeType(){

		register_post_type( 
			WPAPE_GALLERY_THEME_POST,
		    array(
				'labels' => array(
					'name' 				=> __( 'Ape Gallery Themes', 'gallery-images-ape' ),
					'add_new_item'  	=> __( 'Add New Theme for Ape Gallery', 'gallery-images-ape' ),
					'all_items'         => __( 'Themes', 'gallery-images-ape' ),
					'edit_item'         => __( 'Edit Theme', 'gallery-images-ape' ),
					'not_found'         => __( 'No themes found.', 'gallery-images-ape' ),
					'not_found_in_trash'=> __( 'No themes found in Trash.', 'gallery-images-ape' ),

					/*
					'singular_name'    => _x( 'Singular Name', 'post type singular name', 'gallery-images-ape' ),
					'menu_name'          => _x( 'Menu name', 'admin menu', 'gallery-images-ape' ),
					'new_item'           => __( 'New Item', 'gallery-images-ape' ),
					'view_item'          => __( 'View Item', 'gallery-images-ape' ),
					*/
				),
			'public'      	=> true,
			'has_archive'   => false,
			'hierarchical'  => false,
			'supports'    	=> array( 'title' ), 

			//'menu_icon'     	=> 'dashicons-editor-kitchensink',

			'show_in_menu'     			=> 'edit.php?post_type=' . WPAPE_GALLERY_POST,
			'show_in_admin_bar' 		=> false, 
			'show_in_nav_menus' 		=> false,
			'publicly_queryable' 		=> false,
			'exclude_from_search' 		=> true,
			'show_in_admin_all_list'    => false,
			'show_in_admin_status_list' => false,
			)
		);
	}
	
	function theme_default_notice_success() {
		if( isset($_GET['theme-updated']) ){
			$screen = get_current_screen();   

			if( $_GET['theme-updated']=='true'){
				$class = 'notice notice-success is-dismissible';
				$message = __( 'Default theme is defined.', 'gallery-images-ape' );
			} else { 
				$class = 'notice notice-error is-dismissible';
				$message = __( 'Error: Default theme is not defined.', 'gallery-images-ape' );
			}
        	
        	printf( 
        		'<div class="%1$s"><p>%2$s</p></div>', 
        		esc_attr( $class ), 
        		esc_html( $message ) 
        	); 
		}
	}
	
	function setDefaultTheme(){
		
		if( isset($_GET['wpape_gallery_theme_action']) && $_GET['wpape_gallery_theme_action']=='setdefault' ){
			
			$idTheme = isset($_GET['post']) ? (int) $_GET['post'] : 0 ;
			$link = add_query_arg( 'theme-updated', 'false',  wp_get_referer() );

			if( $idTheme && check_admin_referer( 'set-default-id_'.$idTheme, 'wpape_gallery_theme') ){
				update_option( WPAPE_GALLERY_PREFIX.'default_theme', $idTheme );
				$link = add_query_arg( 'theme-updated', 'true',  wp_get_referer() );
			}
			wp_redirect( $link );
			exit;
		}
	}

	function themeDefaultRedirect(){
		
		if( 
			isset($_REQUEST['wpape_gallery_theme_action']) && 
			$_REQUEST['wpape_gallery_theme_action']=='themeDefaultRedirect' 
		){
			$defaultTheme = (int) get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 );
			$location = admin_url( 'post.php?action=edit&post='. $defaultTheme );
			wp_redirect( $location );
			exit;
		}
	}

	function ajax_default_theme_save(){
				
		$idGallery = 0;
		
		if( isset($_GET['idGallery']) ) $idGallery = (int) $_GET['idGallery'];

		if( $idGallery ){
			check_ajax_referer( 'wpape_gallery_themes_default_'.$idGallery, 'nonce' );

			update_option( WPAPE_GALLERY_PREFIX.'default_theme', $idGallery );	
			$return = array(
			    'message'  => __( 'saved', 'gallery-images-ape'),
			    'ID'       => $idGallery
			);
			wp_send_json($return);
			exit();
		}
		apeGalleryHelper::showError(403);
	}

	function addColumnsToThemesListing($columns) { 
		return array_merge($columns, 
			array( 				
				'wpApeGalleryThemeColumnType' => __('Type', 'gallery-images-ape'),
			)
		); 
	}

	function renderColumnsToThemesListing( $column, $post_id ) {
	    
	    switch ( $column ) {
			case 'wpApeGalleryThemeColumnType':
				$this->printThemeType( $post_id );
			break;
		}
	}

	private function getDefaultLabel( $post_id ){
		if(  
			(int) $post_id && 
			(int) $post_id == get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 )  
		) return  ' ['.__( 'Default theme', 'gallery-images-ape').']';	
	}


	private function printThemeType( $post_id ){
		$post_id = (int) $post_id;
		if( $post_id==false ) return ;
		printf(
			'<strong>%s</strong>',
			ucfirst( get_post_meta( $post_id, WPAPE_GALLERY_NAMESPACE.'type', true ) )			
		);
	}

/*	function columns_reorder($columns) {
		$all_columns = array();
		$themesDefault = 'wpApeGalleryThemeColumnDefault'; 

		$title = 'title'; 
		foreach($columns as $key => $value) {
			if( $key==$title ){
				$all_columns[$themesDefault] = $themesDefault;
			}
			$all_columns[$key] = $value;
		}
		return $all_columns;
	}*/


	function row_actions( $actions, WP_Post $post ) {

	    if( $post->post_type != WPAPE_GALLERY_THEME_POST ) return $actions;

	    unset( $actions['inline'] );

	    unset( $actions['inline hide-if-no-js'] );

	    if( get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 ) == $post->ID ) return $actions;
	    
	    if( isset($_GET['post_status']) &&  $_GET['post_status'] == 'trash' ) return $actions;
	    
	    if( isset($_GET['post_status']) &&  $_GET['post_status'] == 'draft' ) return $actions;

	    $url = 'post.php?post='. $post->ID.'&amp;wpape_gallery_theme_action=setdefault';
	    $url .= isset($_GET['paged']) && (int)$_GET['paged'] ? '&paged='.(int) $_GET['paged'] : '';
	    
	    $link = sprintf(
	    	'<a href="%s" aria-label="%s"><span class="dashicons dashicons-heart"></span> %s</a>',
	    		wp_nonce_url( admin_url($url), 'set-default-id_'.$post->ID, 'wpape_gallery_theme' ),
	    		__('Set default theme', 'gallery-images-ape'),
	    		__('Set default theme', 'gallery-images-ape')
	    );
	    
	    $actions =  apeGalleryHelper::array_insert_after( 
	    	$actions, 
	    	'edit', 
	    	array( 'wpape_gallery_theme_set_default' =>  $link ) 
	    );
	    
	    return $actions;
	}

	function assets_files(){
		wp_enqueue_style (WPAPE_GALLERY_ASSETS_PREFIX.'themes-listing', $this->moduleUrl.'css/themes.listing.css', array( ), WPAPE_GALLERY_VERSION );
		wp_enqueue_script (WPAPE_GALLERY_ASSETS_PREFIX.'themes-listing', $this->moduleUrl.'js/themes.listing.js', array('jquery'), WPAPE_GALLERY_VERSION );
	}

	function assets_files_dialog(){
		/* dialog */
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-dialog' );

		wp_register_script( WPAPE_GALLERY_ASSET.'admin-menu-dialog', $this->moduleUrl.'js/themes.dialog.js', array( 'jquery', 'jquery-ui-dialog' ), WPAPE_GALLERY_VERSION, true ); 
		wp_localize_script( WPAPE_GALLERY_ASSET.'admin-menu-dialog', 'ape_gallery_type_js_text', array(
				'title' 	=> __('Select Theme Type', 'gallery-images-ape'),
				'close' 	=> __('Close', 'gallery-images-ape'),
				'create' 	=> __('Create', 'gallery-images-ape'),
		));
		wp_enqueue_script( WPAPE_GALLERY_ASSET.'admin-menu-dialog' ); 

		wp_add_inline_script( WPAPE_GALLERY_ASSET.'admin-menu-dialog', 	$this->getDialogScript() );
		
		wp_enqueue_style (WPAPE_GALLERY_ASSETS_PREFIX.'theme-type', $this->moduleUrl.'css/theme.type.css', array( ), WPAPE_GALLERY_VERSION );
	}


	public function getDialogScript(){
		$script = ' const wpApeGalleryThemesBodyClass = "'.$this->bodyClass.'"; ';
		$script .= file_get_contents( $this->modulePath.'js/themes.select.js' );
		return $script;
	}

	public static function dialogHTML(){
		$collection = array(
			array( 
				'title'		=> __('Gallery Grid', 'gallery-images-ape'), 
				'image' 	=> 'grid3x3.png',
				'url' 		=> 'grid',
			),
			array( 
				'title'		=> __('Simple Slider', 'gallery-images-ape'), 
				'image' 	=> 'slider.png',
				'url' 		=> 'slider',
			)
		);

		$collectionPremium = array(
			array( 
				'title'		=> __('Carousel / Filmstrip', 'gallery-images-ape'), 
				'image' 	=> 'carousel.png',
				'url' 		=> 'carousel',
			),
			array( 
				'title'		=> __('Cube Slider', 'gallery-images-ape'), 
				'image' 	=> 'cubeslider.png',
				'url' 		=> 'cubeslider',
			),
		);

		if(WPAPE_GALLERY_PREMIUM){
			$collection = array_merge( $collection, $collectionPremium);
		}

		ob_start();
		?>
		<div id="ape-gallery-type-select" style="display: none;">
			<?php 
				self::selectTheme($collection);
				self::showPremiumTheme($collectionPremium); 
			?>
		</div>
		<?php
		$content = ob_get_contents();
		ob_clean();
		echo  $content;
	}

	public static function selectTheme( $collection ){
		?>
			<div class="type-grid">
				<?php 
					for ($i = 0; $i < count($collection); $i++) {
						$layout = $collection[$i]; 
						?>
						<div 
							class="type-grid-item <?php echo $i==0?' active':'';?>" 
							onclick="wpApeGalleryTypeDialogSelectItem(this);" 
							data-url="<?php echo admin_url('post-new.php?post_type='.WPAPE_GALLERY_THEME_POST.'&'.WPAPE_GALLERY_NAMESPACE.'type='.$layout['url']);?>"
							>
							<div class="type-grid-item-link">
								<img alt="<?php echo $layout['title']; ?>" src="<?php echo WPAPE_GALLERY_URL; ?>modules/type/images/<?php echo $layout['image'];?>" />
							</div>
							<div class="type-grid-item-title"><?php echo $layout['title']; ?></div>
						</div>
						<?php
					} 
				?>
			</div>
		<?php
	}

	public static function showPremiumTheme( $collection ){
		if(WPAPE_GALLERY_PREMIUM) return ;
		?>
			<h3><?php _e('Premium Themes', 'gallery-images-ape');?></h3>
			<hr />
			<div class="type-grid">
				<?php 
					for ($i = 0; $i < count($collection); $i++) {
						$layout = $collection[$i]; 
						?>
						<div 
							class="type-grid-item" 
							onclick="wpApeGalleryTypeDialogSelectPremiumItem(this);" 
							data-url="https://wpape.net/#pricing"
							>
							<div class="type-grid-item-link">
								<img alt="<?php echo $layout['title']; ?>" src="<?php echo WPAPE_GALLERY_URL; ?>modules/type/images/<?php echo $layout['image'];?>" />
							</div>
							<div class="type-grid-item-title"><?php echo $layout['title']; ?></div>
						</div>
						<?php
					} 
				?>
			</div>
		<?php
	}
}

$themeClass = new wpAPEGalleryModule_Theme();