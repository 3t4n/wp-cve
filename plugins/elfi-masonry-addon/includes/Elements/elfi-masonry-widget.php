<?php

	use \Elementor\Widget_Base;
	use \Elementor\Plugin;
	use \Elementor\Icons_Manager;


	class Elfi_Light_Masonry_Widget extends Widget_Base {

		use elfiLightHelper;

		/**
		 * Get widget name.
		 *
		 *
		 * @since 1.4.0
		 * @access public
		 *
		 * @return string Widget name.
		 */
		public function get_name() {
			return 'elfi_masonry_filter';
		}

		/**
		 * Get widget title.
		 *
		 * @since 1.4.0
		 * @access public
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return __( 'ELFI Filter Masonry', 'elfi-masonry-addon' );
		}

		/**
		 * Get widget icon.
		 *
		 *
		 * @since 1.4.0
		 * @access public
		 *
		 * @return string Widget icon.
		 */
		public function get_icon() {
			return 'elfi-filter-masonry';
		}

		/**
		 * Get widget categories.
		 *
		 *
		 * @since 1.4.0
		 * @access public
		 *
		 * @return array Widget categories.
		 */
		public function get_categories() {
			return [ 'elfi-category' ];
		}

		public function get_script_depends() {

		  return ['isotope-pkgd' , 'elfi-masonry-addon'];

		}
		/**
		 *widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @since 1.4.0
		 * @access protected
		 */
		protected function register_controls() {

				$this->elfi_Content_Settings();
				$this->elfi_ColorSettings();
				$this->elfi_ZoomSettings();
				$this->elfi_ButtonSettings();
				$this->elfi_ReadMoreSettings();

		}

		/**
		 * Render widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @since 1.4.0
		 * @access protected
		 */
		protected function render() {

		$settings = $this->get_settings_for_display();
		$elfi_display_types = $settings['elfi_display_types'];
		$elfi_post_type = $settings['elfi_post_type'];
	if($settings['elfi_post_type'] == 'elfi'){
		$elfi_taxonomy = 'el_portfolio';
		$elfi_term = $settings['elfi_portfolio'];
		$elfipro_post_not_in = $settings['elfi_portfolio_not_in'];

	}elseif($settings['elfi_post_type'] !== 'elfi' && $settings['elfi_post_type'] !== 'post' && $settings['elfi_post_type'] !== 'product'){
		$elfi_taxonomy = $settings['elfi_taxonomy_selcttype'];
		$elfi_term = $settings['elfi_portfolio_selcttype'];
		$elfipro_post_not_in = $settings['elfipro_custompost_not_in'];
		
	}elseif($settings['elfi_post_type'] == 'post'){
		$elfi_taxonomy = 'category';
		$elfi_term = $settings['elfi_post'];
		$elfipro_post_not_in = $settings['elfipro_post_not_in'];
	}elseif($settings['elfi_post_type'] == 'product'  && class_exists('WooCommerce')){
		$elfi_taxonomy = 'product_cat';
		$elfi_term = $settings['elfi_product'];
		$elfipro_post_not_in = $settings['elfipro_product_not_in'];
	
	}else{
		$elfi_taxonomy = '';
		$elfi_term =  '';
		$elfipro_post_not_in = '';	

	}
	
		

	 $grid_style_class = $settings['grid_style'];
	 $posts_per_page = $settings['post_per_page'];
	 $order_by = $settings['elfi_order_by'];
	 $order = $settings['elfi_order'];
	 $grid_effetcs = $settings['grid_style_effetcs'];
	 $full_text = $settings['elfipro_title_full'];
    $on_draught = '';
    $on_drt = '';

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

			elfi_light_fnc_style($settings);

	/**
	 * Responsible for defining all control Style occur in the Elemntor Widget area.
	 */		

	if( $settings['grid_style'] == 'portfolio_wrap_free'){	
	$rand_type = rand(123456789,12345678);

	  elfi_light_control_style($settings ,$rand_type);

	  $btn_style = $settings['btn_style'];
	  if($btn_style == 'style_one'){
	  	$btn_style_class = ' hover_one hover-3';
	  }

	  if($btn_style == 'style_six'){
	  	$btn_style_class = 'hover_six';
	  }

	?>


	<div class="elfi-filter-wrapper-<?php echo esc_attr($rand_type) ?>" id="elfi_masronry_wrapper">
		<?php 	if($elfi_display_types == 'category'){ ?>
		<div class="elfi-filter-nav">
			<ul style="text-align:<?php echo esc_attr($settings['button_align']) ?>">

				<!-- style Rest All -->


				<?php if($btn_style == 'style_one' || $btn_style == 'style_six'){?>


				<li class="active <?php echo esc_attr($btn_style_class); ?>" data-filter="*"><?php echo esc_html($settings['all_text_']); ?></li>
				<!-- style two -->

	<?php } 
 if(isset($elfi_term)){
	$elfi_categories_frontend = $elfi_term;

	if($elfi_categories_frontend){
	  foreach ($elfi_categories_frontend as $elfi_category_frontend) { 

	  	$term = get_term_by('term_id', $elfi_category_frontend, $elfi_taxonomy); 
	  	$name = $term->name; 
	if($btn_style == 'style_one' || $btn_style == 'style_six'){?>
	<li class=" <?php echo esc_attr($btn_style_class); ?>" data-filter=".<?php echo esc_attr($elfi_category_frontend); ?>"><?php echo esc_html($name); ?>
	<?php } ?>


	 <?php 

		}

		}
	 }


	  ?>

	</ul>

	 </div>  
<?php } ?>
	 <div class="grid-init" id="grid-filter-init-<?php echo esc_attr($rand_type); ?>">		
		<?php if(empty($elfi_categories_frontend) && ($elfi_display_types == 'category')){ 

			echo wp_kses_post(elfi_light_default());

		}
	if($elfi_display_types == 'category'){
	if(!empty( $elfi_term) && !empty($elfi_taxonomy) && !empty($elfi_post_type)){



	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$args = array(
	'post_type'=>$elfi_post_type,
	'posts_per_page' =>$posts_per_page,
	'orderby' =>$order_by,
	'order' =>$order,
	'paged' => $paged,
	'tax_query' => array(
	          array(
	             'taxonomy' =>$elfi_taxonomy,
	              'field'    => 'term_id',
	           	'terms'=> $elfi_term,
	              ),
	          ),

	'post__not_in' => $elfipro_post_not_in,

		);
	}
	}elseif($elfi_display_types == 'posts'){

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$x=1;
	$args = array(
	'post_type'=>$elfi_post_type,
	'posts_per_page' =>$posts_per_page,
	'orderby' =>$order_by,
	'order' =>$order,
	'paged' => $paged,

		);
	}else{
	$args= '';	
	}


	$elfi_post_details = new WP_Query($args);

	if($elfi_post_details->have_posts()){

	while ($elfi_post_details->have_posts()) { 
		$elfi_post_details->the_post();
		if($elfi_display_types == 'category'){
		$terms = get_the_terms( get_the_ID(), $elfi_taxonomy );
		                         
		if ( $terms && ! is_wp_error( $terms ) ) {
		 
		    $draught_links = array();
		    $draught_name = array();
		 
		    foreach ( $terms as $term ) {
		        $draught_links[] = $term->term_id;
		        $draught_name[] = $term->name;

		        $termLink = get_term_link( $term, $elfi_taxonomy);
		    }
		                         
		    $on_draught = join( " ", $draught_links );
		    $on_drt = join( " , ", $draught_name );
			}
		}
	?>

	<div  class=" grid-item <?php echo esc_attr($on_draught) . ' ' . $layout_class; ?>" style="width:<?php echo esc_attr($grid_layout) ?>%">

	   <?php if ($grid_style_class == 'portfolio_wrap_free')
	{ ?>

		<div class="elfi-free-item elfi-free-effect__item <?php echo esc_attr($grid_effetcs); ?>">

		  <img class="elfi-free-item__image" src="<?php echo esc_url(elfi_light_post_thumbnail()); ?>"  alt="<?php echo esc_attr(elfi_light_alt_text()); ?>" >

		       <?php if ($settings['hide_cat'] !== 'yes')
		  { ?>
		       <small class="elfi-cat"><?php echo esc_html($on_drt); ?></small> 
		       <?php
		  }
		  ?>
		  <div class="elfi-free-item__info">

		    <h3 class="elfi-free-item__header"><?php echo elfi_light_title($full_text); ?></h3>

		    <div class="elfi-free-item__links">

		      <div class="elfi-free-item__link-block">

		        <a class="elfi-free-item__link" href="<?php echo esc_url(get_the_permalink(get_the_ID())); ?>" class="elfi_port_link "><?php Icons_Manager::render_icon($settings['elfi_link_icon'], ['aria-hidden' => 'true']) ?></a>
		      </div>

		      <div class="elfi-free-item__link-block">

		        <a class="elfi-free-item__link" href="<?php echo esc_url(elfi_light_post_thumbnail()); ?>" ><?php Icons_Manager::render_icon($settings['elfi_icon'], ['aria-hidden' => 'true']) ?></a>
		      </div>

		    </div>

		  </div>

		</div>



	   <?php
	} ?>
	
	   </div>
	   <?php
	}

	wp_reset_postdata();
	} ?>
	</div>
	<?php if ($settings['display_readmore'] == 'yes')
	{
	?>
	<div class="elfi_readmore" >
	   <a href="<?php echo esc_url($settings['read_more_link']) ?>"><?php echo esc_html($settings['read_more_title']) ?> <i class="eicon-long-arrow-right hidden-log"></i></a>
	</div>
	<?php
	}

	?>
	</div>


	<?php
	    if (Plugin::instance()->editor->is_edit_mode()) {
	        $this->render_editor_script();
	    }

	   }
	   }

	protected function render_editor_script()
	{


		$this->elfi_render_script();


	}

	}