<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;
class ADMINZ_Helper_Flatsome_Acf_Banner{
	public $field_locations = array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'theme-general-settings',
			),
		),
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'all',
			),
		),
		array(
			array(
				'param' => 'taxonomy',
				'operator' => '==',
				'value' => 'all',
			),
		),
	);


	function __construct() {
		if(!function_exists('get_field')) return;
		
	}

	function init(){
		$this->create_field();
		$this->create_hook();
	}

	function create_hook(){
		add_action('flatsome_after_header',function(){
		    $this->create_html();
		});
	}

	function create_html(){
		if(is_front_page()) return;
	    $banner = $this->get_banner();
		if(!$banner) return;
		$get_breadcrumb = $this->get_breadcrumb();
	    $title = $this->get_title();
		$height = $this->get_banner_height();
		$shortcode = $this->get_shortcode();
	                
	    ob_start();
	    ?>
	    [section class="adminz_banner" bg_overlay="rgba(0,0,0,.5)" bg="<?php echo esc_attr($banner) ?>" bg_size="original" dark="true" height="<?php echo esc_attr($height); ?>"]
			[row]				
	            [col span__sm="12" span="9" class="pb-0"]
	                <?php echo esc_attr($get_breadcrumb) ?>
					<?php if($title):?>
	                	[title class="adminz_banner_title mb-0" text="<?php echo esc_attr($title); ?>" tag_name="h1"]
					<?php endif; ?>
	            [/col]				
	        [/row]
			<?php if($shortcode) echo do_shortcode( $shortcode ); ?>
			<?php echo do_action('adminz_acf_banner_after',$this); ?>
	    [/section]
	    <style type="text/css">
	    	@media (max-width: 549px){
	    		.adminz_banner{
	    			min-height: 30vh !important;
	    		}
	    	}
	    </style>
	    <?php
	    echo do_shortcode( ob_get_clean());
	}

	function create_field(){
		add_action( 'acf/include_fields', function() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group( array(
			'key' => 'group_6506a81783b36',
			'title' => 'Banner option',
			'fields' => array(
				array(
					'key' => 'field_adminz_banner',
					'label' => 'Default banner image',
					'name' => 'adminz_banner',
					'aria-label' => '',
					'type' => 'image',
					'instructions' => 'Required to show banner',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'id',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
					'preview_size' => 'medium',
				),	
				array(
					'key' => 'field_65569bd7a9ad2',
					'label' => 'Banner height',
					'name' => 'banner_height',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'placeholder' => '399px',
					'prepend' => '',
					'append' => '',
				),					
				array(
					'key' => 'field_65569bbc879af',
					'label' => 'Breadcrumb shortcode',
					'name' => 'breadcrumb_shortcode',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '[adminz_breadcrumb]',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => '',
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_adminz_title',
					'label' => 'Custom Title',
					'name' => 'adminz_title',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'return_format' => '',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
					'preview_size' => 'medium',
				),
				array(
					'key' => 'field_adminz_acf_banner_shortcode',
					'label' => 'Shortcode',
					'name' => 'adminz_acf_banner_shortcode',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'return_format' => '',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
					'preview_size' => 'medium',
				),
				array(
					'key' => 'field_6511111c879af',
					'label' => 'Disable Breadcrumb',
					'name' => 'disable_breadcrumb',
					'aria-label' => '',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_6512111c879af',
					'label' => 'Disable Title',
					'name' => 'disable_title',
					'aria-label' => '',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),			
			),
			'location' => $this->field_locations,
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'left',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		) );
	} );


	}

	function get_banner_height(){
		$id = get_the_ID();
		if(is_home()){
			$id = get_option('page_for_posts');
		}

		if($return = get_field('banner_height',$id)){
			return $return;
		}

		
		if(is_a(get_queried_object(), 'WP_Term')){
			if($return = get_field('banner_height',get_queried_object())){
				return $return;
			}
		}


		if($return = get_field('banner_height','option')){
			return $return;
		}


	    return '399px';
	}

	function get_breadcrumb(){
		$id = get_the_ID();
		if(is_home()){
			$id = get_option('page_for_posts');
		}

		if(get_field('disable_breadcrumb',$id)) return;
		if($return = get_field('breadcrumb_shortcode',$id)){
			return $return;
		}

		

		if(is_a(get_queried_object(), 'WP_Term')){
			if($return = get_field('breadcrumb_shortcode',get_queried_object())){
				return $return;
			}
		}

		if(get_field('disable_breadcrumb','option')) return;
 		if($return = get_field('breadcrumb_shortcode','option')){
			return $return;
		}

		return;

	}

	function get_banner(){
		$id = get_the_ID();
		if(is_home()){
			$id = get_option('page_for_posts');
		}

		if($return = get_field('adminz_banner',$id)){
			return $return;
		}

		
		if(is_a(get_queried_object(), 'WP_Term')){
			if($return = get_field('adminz_banner',get_queried_object())){
				return $return;
			}
		}

		if(get_field('disable_banner','option')) return;
		if($return = get_field('adminz_banner','option')){
			return $return;
		}


	    return;
	}

	function get_title(){	

		$id = get_the_ID();
		if(is_home()){
			$id = get_option('page_for_posts');
		}

		if(get_field('disable_title',$id)) return;
		if($return = get_field('adminz_title',$id)){
			return $return;
		}

		

		if(is_a(get_queried_object(), 'WP_Term')){
			if($return = get_field('adminz_title',get_queried_object())){
				return $return;
			}

		}	

		if(get_field('disable_title','option')) return;
		if($return = get_field('adminz_title','option')){
			return $return;
		}
		

		if (is_single() or is_page()) {
			return get_the_title();
		}
		
		if (is_archive()){
			if(function_exists('is_shop') and is_shop()){
				return get_the_title(get_option('woocommerce_shop_page_id'));
			}
			return get_queried_object()->name;
		}

		if(is_search()){
			return __("Search");
		}

		if(is_404()){
			return __("Page not found");
		}

		if(is_home()){
			return get_the_title(get_option('page_for_posts') );
		}

	    return get_the_title();
	}

	function get_shortcode(){
		$id = get_the_ID();
		if(is_home()){
			$id = get_option('page_for_posts');
		}

		if($return = get_field('adminz_acf_banner_shortcode',$id)){
			return $return;
		}

		

		if(is_a(get_queried_object(), 'WP_Term')){
			if($return = get_field('adminz_acf_banner_shortcode',get_queried_object())){
				return $return;
			}

		}	


		if($return = get_field('adminz_acf_banner_shortcode','option')){
			return $return;
		}
		
		return '';
	}
}



/*
	EXAMPLE
	$sa = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Acf_Banner;
$sa->init();
	
*/