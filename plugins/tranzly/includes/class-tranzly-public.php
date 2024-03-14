<?php

/**
 * The public-facing functionality of the plugin.
 * @link       https://tranzly.io
 * @since      1.0.0
 * @package    Tranzly
 * @subpackage Tranzly/public
 */

class Tranzly_Public {

	/**
	 * The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		


}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/tranzly.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/js/tranzly.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name,'tranzly_plugin_vars', array('ajaxurl' => admin_url('admin-ajax.php'),'plugin_url' =>TRANZLY_PLUGIN_URI,'site_url'=>get_site_url()));

	}



		/**
		 * Add meta Tags
		 */
		 
	public function add_meta_tags() {
		

		$post_id=get_the_ID();
		$cnpost=get_post($post_id);
		$cnpost->guid;
		$cnpost->post_name;
		
		$tranzly_post_translated_to=get_post_meta($post_id, 'tranzly_post_translated_to',true);
		$tranzly_post_translated_to_from=get_post_meta($post_id, 'tranzly_post_translated_to_from',true);
		
		/*Language switcher custom links*/
		$cnlink      = empty( $cnlink ) ? sanitize_text_field('') : '';
		$link      = empty( $link ) ? sanitize_text_field('') : '';
		$link2      = empty( $link2 ) ? sanitize_text_field('') : '';
		/*Language switcher language*/
		$cnlang = ( !empty( $_GET['lang'] ) ? sanitize_text_field( wp_unslash($_GET['lang'] )) : '' );	
			
		if ($tranzly_post_translated_to_from) {
			if ($tranzly_post_translated_to_from['0']['tranzly_parent_post_id']) {
				$tranzly_parent_post_id=$tranzly_post_translated_to_from['0']['tranzly_parent_post_id'];
				$tranzly_post_translated_to=get_post_meta($tranzly_parent_post_id, 'tranzly_post_translated_to',true);
			}
		}

		if ($tranzly_post_translated_to) {
			foreach ($tranzly_post_translated_to as $translated_to) {
				$tranzly_child_post_id=$translated_to['tranzly_child_post_id'];
				foreach ( tranzly_supported_languages() as $code => $name ) : 
					if ($code==$translated_to['translated_to']) {
						$tranzly_child_post=get_post($tranzly_child_post_id);
						if($tranzly_child_post->post_status=='publish'){
							$tranzly_child_post->guid;
							$cnname[]=$name;
							if ($tranzly_child_post_id!=$post_id) {
								
					if ( isset( $link ) ) {
								 $link.='<div class="icondiv"><a class="translatedlink" target="_blank" href="'.get_post_permalink($tranzly_child_post_id).'"><img class="icon_img" src="'.TRANZLY_PLUGIN_URI.'assets/imgs/'. esc_html( $name ).'.png"></a></div>';	
							}
							}
							
						 }
					 } ?>
			<?php endforeach; 
			}
		}
		if ($tranzly_post_translated_to_from) {
					foreach ($tranzly_post_translated_to_from as $translated_to_from) {
						if ($translated_to_from['translated_from']) {
							$tranzly_parent_post_id=$translated_to_from['tranzly_parent_post_id'];
							foreach ( tranzly_supported_languages() as $code => $name ) : 
						 	if ($code==$translated_to_from['translated_from']) {
						 		$tranzly_parent_post=get_post($tranzly_parent_post_id);
						 		if($tranzly_parent_post->post_status=='publish'){
										if ( isset( $link ) ) {
						 			$link2.='<div class="icondiv"><a class="translatedlink" target="_blank" href="'.get_post_permalink($tranzly_parent_post_id).'"><img class="icon_img" src="'.TRANZLY_PLUGIN_URI.'assets/imsg/'. esc_html( $name ).'.png"></a></div>';
							 		
									}
								}
							 }
							 endforeach; 
						}
					}
				} 
		
		/*Language switcher language*/
		$cnlang = ( !empty( $_GET['lang'] ) ? sanitize_text_field( wp_unslash($_GET['lang'] )) : '' );
		
		$post_type=get_post_type($post_id);
		$lang = get_bloginfo( 'language' );
		if ($tranzly_post_translated_to_from) {
			if ($cnlang) {?>
				<meta name="language" content="<?php echo esc_url(get_site_url());  ?>/<?php echo esc_attr($cnlang); ?>">
				<meta name="canonical" href="<?php echo esc_url(get_site_url()); ?>/<?php echo esc_attr($cnlang); ?>/<?php echo esc_attr($cnpost->post_name); ?>">
			<?php }else{?>
					<meta name="language" content="<?php echo esc_url(get_site_url()); ?>/<?php echo esc_attr($tranzly_post_translated_to_from[0]['translated_to']); ?>">
					<meta name="canonical" href="<?php echo esc_url(get_site_url()); ?>/<?php echo esc_attr($tranzly_post_translated_to_from[0]['translated_to']);?>/<?php echo esc_attr($cnpost->post_name); ?>">
			<?php }
		}
		if($tranzly_post_translated_to){
			if ($cnlang) { ?>
					<meta name="language" content="<?php echo esc_url(get_site_url()); ?>/<?php echo esc_attr($cnlang); ?>">
					<meta name="canonical" href="<?php echo esc_url(get_site_url()); ?>/<?php echo esc_attr($cnlang); ?>/<?php echo esc_attr($cnpost->post_name); ?>">				
			<?php }else{ ?>
					<meta name="language" content="<?php echo esc_url(get_site_url()); ?>/<?php echo esc_attr($tranzly_post_translated_to[0]['translated_from']); ?>">
					<meta name="canonical" href="<?php echo esc_url(get_site_url()); ?>/<?php echo esc_attr($tranzly_post_translated_to[0]['translated_from']); ?>/<?php echo esc_attr($cnpost->post_name); ?>">
			<?php }
			
		}
		
	}



		/**
		 * Add meta Tags
		 */
	public function tranzly_slug_filter_the_title( $content) {
		$post_id=get_the_ID();
		$translated_from=get_post_meta($post_id, 'translated_from',true);
		$translated_to=get_post_meta($post_id, 'translated_to',true);
		$tranzly_mylang=get_post_meta($post_id, 'tranzly_mylang',true);

		$tranzly_options = get_option( 'tranzly_options' );
		$enable_affiliates    = $tranzly_options['enable_affiliates'];
		$affiliate_id    = $tranzly_options['affiliate_id'];
		$affiliate_open_new_tab   = $tranzly_options['affiliate_open_new_tab'];
		$selector_mode  = $tranzly_options['selector_mode'];
		$selector_position  = $tranzly_options['selector_position'];
		$selector_tab  = $tranzly_options['selector_tab'];
		
		$cnlink      = empty( $cnlink ) ? sanitize_text_field('') : '';
		$link      = empty( $link ) ? sanitize_text_field('') : '';
		$link2      = empty( $link2 ) ? sanitize_text_field('') : '';
		$watermark      = empty( $watermark) ? sanitize_text_field('') : '';
		$watermark2      = empty( $watermark2) ? sanitize_text_field('') : '';
		

		$tranzly_post_translated_to=get_post_meta($post_id, 'tranzly_post_translated_to',true);
		$tranzly_post_translated_to_from=get_post_meta($post_id, 'tranzly_post_translated_to_from',true);
		// print_r($tranzly_post_translated_to);
		// print_r($tranzly_post_translated_to_from);
		if ($tranzly_post_translated_to_from) {
			if ($tranzly_post_translated_to_from['0']['tranzly_parent_post_id']) {
				$tranzly_parent_post_id=$tranzly_post_translated_to_from['0']['tranzly_parent_post_id'];
				$tranzly_post_translated_to=get_post_meta($tranzly_parent_post_id, 'tranzly_post_translated_to',true);
			}
		}
		if ($tranzly_post_translated_to) {
			foreach ($tranzly_post_translated_to as $translated_to) {
				$tranzly_child_post_id=$translated_to['tranzly_child_post_id'];
				foreach ( tranzly_supported_languages() as $code => $name ) : 
					if ($code==$translated_to['translated_to']) {
						$tranzly_child_post=get_post($tranzly_child_post_id);
						if($tranzly_child_post->post_status=='publish'){
							$tranzly_child_post->guid;
							$cnname[]=$name;
							if ($tranzly_child_post_id!=$post_id) {
								if ($selector_mode =='flags') {
										if ($selector_tab =='newtab') {
								 $link.='<div class="icondiv"><a class="translatedlink" target="_blank" href="'.get_site_url().'/'.$tranzly_child_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.TRANZLY_PLUGIN_URI.'assets/img/'. esc_html( $name ).'.png"></a></div>';	
									} else {
										 $link.='<div class="icondiv"><a class="translatedlink"  href="'.get_site_url().'/'.$tranzly_child_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.TRANZLY_PLUGIN_URI.'assets/img/'. esc_html( $name ).'.png"></a></div>';		
								}
								}else {
										if ($selector_tab =='newtab') {
									$link.='<div class="langtext"><a class="translatedlinks" target="_blank" href="'.get_site_url().'/'.$tranzly_child_post->post_name.'?lang='.$code.'">'. esc_html( $name ).'</a></div>';	
										} else {
									$link.='<div class="langtext"><a class="translatedlinks"  href="'.get_site_url().'/'.$tranzly_child_post->post_name.'?lang='.$code.'">'. esc_html( $name ).'</a></div>';	
										}
								
								
								}
							
							}
							
						 }
					 } ?>
			<?php endforeach; 
			}
		}
		if ($tranzly_post_translated_to_from) {
					foreach ($tranzly_post_translated_to_from as $translated_to_from) {
						if ($translated_to_from['translated_from']) {
							$tranzly_parent_post_id=$translated_to_from['tranzly_parent_post_id'];
							foreach ( tranzly_supported_languages() as $code => $name ) : 
						 	if ($code==$translated_to_from['translated_from']) {
						 		$tranzly_parent_post=get_post($tranzly_parent_post_id);
						 		if($tranzly_parent_post->post_status=='publish'){
						 			$link2.='<div class="icondiv"><a class="translatedlink" target="_blank" href="'.get_site_url().'/'.$tranzly_parent_post->post_name.'?lang='.$code.'"><img class="icon_img" src="'.TRANZLY_PLUGIN_URI.'assets/img/'. esc_html( $name ).'.png"></a></div>';
							 		}
							 }
							 endforeach; 
						}
					}
				}
				
			
		if ($enable_affiliates=='on'  ) {
			
			if ($affiliate_open_new_tab=='on'  ) {$targeto ='target="_blank"'; } 
			if (!empty($affiliate_id)  ) {$affiliateid = $affiliate_id; } else { $affiliateid ="https://tranzly.io";} 
			if (!empty($affiliate_placeholder)  ) {$affiliateplaceholder = $affiliate_placeholder; } else { $affiliateplaceholder  ="AI Translated by Tranzly";} 
			
			
				$watermark='<a class="afflink" href="'.$affiliateid.'" '.$targeto.'>'.$affiliateplaceholder.'</a>';
			} 
			else {
				
				$watermark='';
			}	

			
		if ($link!='' OR $link2!='') {
		$cnlink='<div class="cnicon">'.$watermark2.$watermark.' '.$link.$link2.'</div>';
		}
		$post_type=get_post_type( $post_id );


		if ($selector_position=='before') {
			
			
			$custom_content = $cnlink.$content;
			
		}else{
			
			$custom_content =$content.$cnlink;	
		}
		return $custom_content;
	}
	


	public function tranzly_posts_custom( $query ) {
		

			
		$cnlang = ( !empty( $_GET['lang'] ) ? sanitize_text_field( $_GET['lang'] ) : '' );
			
		if ($cnlang) {
			if ( $query->is_home() && $query->is_main_query() ) { 
				$query->set( 'orderby', 'title' ); 
				$query->set( 'order', 'DESC' ); 
				 $query->set( 'meta_key', 'tranzly_mylang' );
				 $query->set( 'meta_value', $cnlang );
				// $query->set('meta_query', array(
				// 				'relation' => 'OR',
				// 		        array(
				// 		              'key' => 'tranzly_mylang',
				// 		              'value' =>$cnlang,
				// 		              'compare' => 'LIKE',
				// 		              // 'type' => 'numeric'
				// 		        ),
				// 		    ));
			} 
		}
		// echo "<pre>";
		// print_r($query);
		// exit();

		return $query;

	}
	

	 public function tranzly_ajax_handaler(){
    	global $wpdb;
    	$param= isset($_REQUEST['param'])? sanitize_text_field(trim($_REQUEST['param'])):"";
		
		
    	if ($param=='find_post_page') {
    		$tranzly_page_id= sanitize_text_field(($_REQUEST['tranzly_page_id']));
    		$tranzly_post=get_post($tranzly_page_id);
    		$post_id=$tranzly_post->ID;
    		$tranzly_post_translated_to=get_post_meta($post_id, 'tranzly_post_translated_to',true);
			$tranzly_post_translated_to_from=get_post_meta($post_id, 'tranzly_post_translated_to_from',true);
			print_r($tranzly_post_translated_to);
			//print_r($tranzly_post_translated_to_from);

    	}

    	wp_die();
    }


}