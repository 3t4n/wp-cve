<?php

	use \Elementor\Widget_Base;
	use \Elementor\Plugin;
	use \Elementor\Icons_Manager;


	class Elfi_Light_Masonry_Gallery extends Widget_Base {

		use elfiLightallery;

		/**
		 * Get widget name.
		 *
		 *
		 * @since 2.0.8
		 * @access public
		 *
		 * @return string Widget name.
		 */
		public function get_name() {
			return 'elfi_masonry_gallery';
		}

		/**
		 * Get widget title.
		 *
		 * @since 2.0.8
		 * @access public
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return __( 'ELFI Gallery Masonry', 'elfi-masonry-addon' );
		}

		/**
		 * Get widget icon.
		 *
		 *
		 * @since 2.0.8
		 * @access public
		 *
		 * @return string Widget icon.
		 */
		public function get_icon() {
			return 'elfi-galley-masonry';
		}

		/**
		 * Get widget categories.
		 *
		 *
		 * @since 2.0.8
		 * @access public
		 *
		 * @return array Widget categories.
		 */
		public function get_categories() {
			return [ 'elfi-category' ];
		}

		public function get_script_depends() {

		  return ['isotope.pkgd.min', 'elfi-masonry-gallery'];

		}
		/**
		 *widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @since 2.0.8
		 * @access protected
		 */
		protected function register_controls() {

				$this->elfi_Gallery_Settings();
				$this->elfi_ColorSettings();
	
		}

		/**
		 * Render widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @since 2.0.8
		 * @access protected
		 */
		protected function render() {

			$settings = $this->get_settings_for_display();


	$rand_type = rand(123456789,12345678);

	if($settings['grid_clmn'] == 'two'){
		$grid_layout = '49';
		$layout_class ='layout_two';
	}
	if($settings['grid_clmn'] == 'three'){
		$grid_layout = '32.33';
		$layout_class ='layout_three';
	}
	if($settings['grid_clmn'] == 'four'){
		$grid_layout = '24';
		$layout_class ='layout_four';
	}

	 $grid_style_class = $settings['grid_style'];




	 $grid_effetcs = $settings['grid_style_effetcs'];
	 $elfigallerylist = $settings['elfigallerylist'];
	/**
	 * Responsible for defining all control Style occur in the Elemntor Widget area.
	 */		

			//elfi_light_fnc_style($settings);
		$on_draught = '';	
	    $on_drt = '';

	?>


<div class="elfi-filter-wrapper-<?php echo esc_attr($rand_type); ?>" id="elfi_masronry_wrapper">

	 <div class="grid-init" id="grid-filter-init-<?php echo esc_attr($rand_type)?>">		

<?php
$x= 0;
if($elfigallerylist)
 foreach ($elfigallerylist as $value) {

 	$title = $value['galley_title'];
 	$enable_prevbuy = $value['enable_prevbuy'];
 	$popup_content = $value['popup_content'];
 	$image = $value['image'];
 	if($image){
	$image_link = $value['image']['url'];
 	}else{
 	$image_link = '';	
 	}
 	$popup_style = $value['popup_style'];
 	$popupicon = $value['popupicon'];
 	$enable_link = $value['enable_link'];
 	if($enable_link  == 'yes'){
 		$gallery_link = $value['gallery_link']['url'];
 		$linkicon = $value['linkicon'];
		
 	$titlevalue = '<a href="'.$gallery_link.'"><h2>'.$title.'</h2></a>';
 	}else{
 	$gallery_link = '';
 		$linkicon =  '';
 	$titlevalue = '<h2>'.$title.'</h2>';
 	}
 	$image_alts = get_post_meta($value['image']['id'], '_wp_attachment_image_alt', TRUE);

 	if(!empty($image_alts)){
	$image_alt = get_post_meta($value['image']['id'], '_wp_attachment_image_alt', TRUE);

 	}else{
 	$image_alt = $title;	
 	}
 	if($enable_prevbuy  == 'yes'){
		$target = $value['preview_link']['is_external'] ? ' target="_blank"' : '';
		$targetb = $value['Buy_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $value['preview_link']['nofollow'] ? ' rel="nofollow"' : '';
		$nofollowb = $value['Buy_link']['nofollow'] ? ' rel="nofollow"' : '';
 		$preview_title = $value['prev_title'];
 		$preview_link = $value['preview_link']; 
 		$buy_title = $value['buy_title']; 
 		$buy_link =  $value['Buy_link'];
 	}else{


 		$preview_title = '';
 		$preview_link = ''; 
 		$buy_title =  '';
 		$buy_link =  '';
 	}
?>
	<div  class=" grid-item <?php echo esc_attr($on_draught) . ' ' . $layout_class; ?>" style="width:<?php echo esc_attr($grid_layout) ?>%">

	   <?php if($grid_style_class == 'portfolio_wrap_free' || $grid_style_class == 'portfolio_wrap_one' || $grid_style_class == 'portfolio_wrap_two'|| $grid_style_class == 'portfolio_wrap_three'|| $grid_style_class == 'portfolio_wrap_four'|| $grid_style_class == 'portfolio_wrap_five'|| $grid_style_class == 'portfolio_wrap_six'|| $grid_style_class == 'portfolio_wrap_seven'|| $grid_style_class == 'portfolio_wrap_eight'|| $grid_style_class == 'portfolio_wrap_nine' ){ ?>

		<div class="elfi-free-item elfi-free-effect__item <?php echo esc_attr($grid_effetcs); ?>">

	   	  <img class="elfi-free-item__image" src="<?php echo esc_url($image_link); ?>"  alt="<?php echo esc_attr($image_alt); ?>" >

	   	        <div class="portfolio_content">

	   	  <div class="elfi-free-item__info">
		<?php  	if($enable_link  == 'yes'){ echo '<a href="'.$gallery_link.'">
	   	    <h2 class="elfi-free-item__header">'.esc_html($title).'</h2></a>';
	    }else{ echo '<h2 class="elfi-free-item__header">'.esc_html($title).'</h2>';} ?>
	   	    <div class="elfi-free-item__links">
			<?php  	if($enable_link  == 'yes'){ ?>
	   	      <div class="elfi-free-item__link-block">

	   	      	<a  class="elfi-free-item__link" href="<?php echo esc_url($gallery_link); ?>"><?php Icons_Manager::render_icon($linkicon, ['aria-hidden' => 'true']) ?></a>
	   	      </div>
			<?php } ?>

		      <div class="elfi-free-item__link-block">
		        <a class="elfi-free-item__link" href="<?php echo esc_url($image_link); ?>" ><?php Icons_Manager::render_icon($value['popupicon'], ['aria-hidden' => 'true']) ?></a>
		      </div>

		    </div>

		  </div>

		</div>



	   <?php
	} ?>
	
	   </div>
	   	</div>
	   <?php

		
	$x++;
	 }

 ?>


	</div>
	</div>


	<?php

	    if (Plugin::instance()->editor->is_edit_mode()) {
	        $this->render_editor_script();
	    }
	    
	}

	protected function render_editor_script()
	{


		$this->gallery_render_script();


	}

	}