<?php
class EDS_Menu_Frontend {
	public function __construct() {
		$this->options=get_option('_eds_Options' );
		
		
		//add_action( 'admin_notices', array( $this, 'admin_notices' ), 99 );
		add_action('wp_footer', array( $this, 'eds_responsive_menu' ), 90);
		add_action('wp_enqueue_scripts', array( $this, 'hook_javascript_css' ),98);
		add_action('wp_footer', array( $this, 'awesome_responsive_menu_script' ),98);
		//add_action( 'wp_enqueue_scripts',array( $this, 'eds_font_enqueue_scripts' ),98);
		
		
	}
	
	function eds_responsive_menu() {
		global $awesome_responsive_menu;
		
		if($this->options['show_sub_menu_way']=='accordion'){
			$walker=new EDS_Accordion_Menu_Walker();
		}else if($this->options['show_sub_menu_way']=='always'){
			$walker=new EDS_always_Menu_Walker();
		}else{
			$walker=new Wpse8170_Menu_Walker();
		}
		$args=array(
			'menu'=>$this->options['eds_choose_menu'],
			'container' => false,
			'menu_class' => 'eds-responsive-menu',
			"walker"            =>$walker,
		 );
		  $image = wp_get_attachment_image_src($this->options['eds_menu_logo'], 'full' );
		  
		if($this->options['choose_effect_type']!='simply_drop_down'){
			//echo '<a id="simple-menu" href="#sidr" class="eds-toggle-icon"><span  aria-label="Toggle Navigation" class="eds-lines-button x "><span class="eds-lines"></span></span></a>';
			echo '<div id="simple-menu" class="eds-toggle-icon"><i class="fa fa-bars"></i></div>';
		}
		if($this->options['choose_effect_type']=='drop_down'){
			$attribute='id="eds_drop_down_menu" class="eds-responsive-menu-wrp sidr"';
		}else if($this->options['choose_effect_type']=='drop_down_right'){
			$attribute='id="eds_drop_down_menu" class="eds-responsive-menu-wrp sidr eds-pull-right"';
		}else if($this->options['choose_effect_type']=='down_up'){
			$attribute='id="eds_down_up_menu" class="eds-responsive-menu-wrp sidr"';
		}else if($this->options['choose_effect_type']=='down_up_right'){
			$attribute='id="eds_down_up_menu" class="eds-responsive-menu-wrp sidr eds-pull-right"';
		}else if($this->options['choose_effect_type']=='simply_drop_down'){
			echo '<div id="simple-menu" class="eds-toggle-icon"><i class="fa fa-bars"></i></div>';
			$attribute='id="eds_simply_drop_down" class="eds-responsive-menu-wrp sidr"';
		}else{
			$attribute='id="sidr" class="eds-responsive-menu-wrp"';
		}
		
		
		echo '<div '.$attribute.'>';
		if($this->options['eds_menu_logo']!=""&& $this->options['eds_logo_positions']=='top'){
			echo '<a href="'.esc_url( home_url( '/' ) ).'" rel="bookmark" class="eds_logo"><img src="'.$image[0].'"/></a>';
		}
		if($this->options['search_box_mode']==true && $this->options['eds_search_positions']=='top'){
			echo $this->wpbsearchform();
		}
		
		
		wp_nav_menu($args);
		
		
		if($this->options['eds_menu_logo']!=""&& $this->options['eds_logo_positions']=='bottom'){
			echo '<a href="'.esc_url( home_url( '/' ) ).'" rel="bookmark" class="eds_logo"><img src="'.$image[0].'"/></a>';
		}
		if($this->options['search_box_mode']==true&& $this->options['eds_search_positions']=='bottom'){
			echo $this->wpbsearchform();
		}
		if($this->options['eds_social_profile']==true){
			echo $this->social_profile();
		}
		echo '</div>';
		

	}
	function wpbsearchform() {
	
		$form = '<form role="search" method="get" id="eds-searchform" action="' . home_url( '/' ) . '" >
		<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __('Search for:') . '" size="160" />
		<input type="submit" id="searchsubmit" value="" />
		</form>';
		return $form;
	}
	function social_profile(){
		$output .='<div class="eds-social-profile">';	
		if($this->options['eds_fb']!=""){
			$output .='<a href="'.$this->options['eds_fb'].'"  class="eds-soical-btn fb" target="_blank"><i class="fa fa-facebook"></i></a>';
		}
		if($this->options['eds_tw']!=""){
			$output .='<a href="'.$this->options['eds_tw'].'" class="eds-soical-btn tw" target="_blank"><i class="fa fa-twitter"></i></a>';
		}
		if($this->options['eds_g_plus']!=""){
			$output .='<a href="'.$this->options['eds_g_plus'].'" class="eds-soical-btn g_plus" target="_blank"><i class="fa fa-google-plus"></i></a>';
		}
		if($this->options['eds_lin']!=""){
			$output .='<a href="'.$this->options['eds_lin'].'" class="eds-soical-btn lin" target="_blank"><i class="fa fa-linkedin"></i></a>';
		}
		if($this->options['eds_ins']!=""){
			$output .='<a href="'.$this->options['eds_ins'].'" class="eds-soical-btn ins" target="_blank"><i class="fa fa-instagram"></i></a>';
		}
		if($this->options['eds_pin']!=""){
			$output .='<a href="'.$this->options['eds_pin'].'" class="eds-soical-btn pin" target="_blank"><i class="fa fa-pinterest"></i></a>';
		}
		return $output;
	}
	function hook_javascript_css() {
	    if (!is_admin()) { 
		wp_enqueue_style( 'eds-responsive-menu-component', EDS_MENU_URI . '/assets/css/component.css' );
		wp_enqueue_style( 'eds-responsive-menu-sidr-css', EDS_MENU_URI . '/assets/css/eds.sidr.css' );
		wp_enqueue_style( 'eds-responsive-menu-dynamic-css-toggle', EDS_MENU_URI . '/inc/css/eds_dynamic_css.php' );
		
		
		
		//wp_enqueue_script('eds-jquery','http://cdn.jsdelivr.net/jquery/2.2.0/jquery.min.js', array('jquery'), '1.0', true);
		//wp_enqueue_script('eds-jquery');
		wp_enqueue_script('eds-responsive-menu-sidr-js', EDS_MENU_URI . '/assets/js/jquery.sidr.js', array('jquery'), '1.0', true);
		wp_enqueue_script('eds-responsive-menu-sidr-js');
		wp_enqueue_style( 'eds-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css' );
			
        }     
		
		
	}
	
		function awesome_responsive_menu_script() {
		
		?>
   
<script>
jQuery(document).ready(function() {
	
	
  
  
	<?php if($this->options['choose_effect_type']!='drop_down' && $this->options['choose_effect_type']!='down_up'&& $this->options['choose_effect_type']!='drop_down_right' && $this->options['choose_effect_type']!='down_up_right' && $this->options['choose_effect_type']!='simply_drop_down'):?>
jQuery('#simple-menu').sidr({
  <?php if($this->options['animation_speed']!=""):?>speed:<?php echo $this->options['animation_speed'];?>, <?php endif;?>
  
  <?php if($this->options['choose_effect_type']=='side_right_body'):?>side: 'right',<?php endif;?> 
  <?php if($this->options['choose_effect_type']=='side_left'):?>side: 'left', body: '',<?php endif;?> 
   <?php if($this->options['choose_effect_type']=='side_right'):?>side: 'right', body: '',<?php endif;?> 
   onOpen: function onOpen() {
	   jQuery('.eds-toggle-icon i').removeClass('fa-bars');
	   jQuery('.eds-toggle-icon i').addClass('fa-close');
	   },
   onClose: function onClose() {
	   jQuery('.eds-toggle-icon i').addClass('fa-bars');
	   jQuery('.eds-toggle-icon i').removeClass('fa-close');
	   },
  });
  <?php else: ?>
   if(jQuery('.eds-toggle-icon').length){
	   
	  jQuery('.eds-toggle-icon').click(function(e) {
        e.preventDefault();
		<?php if($this->options['show_sub_menu_way']!='accordion'):?>
			jQuery('.eds-responsive-menu-wrp').find('ul').removeClass('move-out');
		<?php endif;?>
		<?php if($this->options['choose_effect_type']=='drop_down'):?>
			jQuery('#eds_drop_down_menu').toggleClass('active_drop_down');
		<?php endif;?>
		<?php if($this->options['choose_effect_type']=='drop_down_right'):?>
			jQuery('#eds_drop_down_menu').toggleClass('active_drop_down');
		<?php endif;?>
		<?php if($this->options['choose_effect_type']=='down_up'):?>
			jQuery('#eds_down_up_menu').toggleClass('active_drop_down');
		<?php endif;?>
		<?php if($this->options['choose_effect_type']=='down_up_right'):?>
			jQuery('#eds_down_up_menu').toggleClass('active_drop_down');
		<?php endif;?>
		<?php if($this->options['choose_effect_type']=='simply_drop_down'):?>
			jQuery('#eds_simply_drop_down').toggleClass('active_drop_down');
		<?php endif;?>
		
		jQuery('.eds-toggle-icon i').toggleClass('fa-bars').toggleClass('fa-close');
		 return false;
		
    });
  }
 <?php endif;?>
  
 
   if(jQuery('li.menu-item-has-children').length){
	  
	 jQuery('li.menu-item-has-children i.eds-arrows').click(function(e) {
        e.preventDefault();
		
		<?php if($this->options['show_sub_menu_way']!='accordion' && $this->options['show_sub_menu_way']!='always'):?>
			
			jQuery(this).parent('a').parent('li').parent('ul').addClass('move-out');
			jQuery(this).parent('a').parent('li').children('ul.sub-menu').addClass('move-in');
		
		<?php endif;?>
		
		<?php if($this->options['show_sub_menu_way']=='accordion' && $this->options['show_sub_menu_way']!='always'):?>
			jQuery(this).parent('a').parent('li').children('ul.sub-menu').slideToggle(<?php echo $this->options['animation_speed'];?>);
			jQuery(this).toggleClass('active');
		<?php endif;?>
			
		
    });
  }
  <?php if($this->options['show_sub_menu_way']!='accordion' && $this->options['show_sub_menu_way']!='always'):?>
    if(jQuery('li.back-pre-nav').length){
	 jQuery('li.back-pre-nav').after().click(function(e) {
        e.preventDefault();
		jQuery(this).parent('ul.sub-menu').parent('li').parent('ul').removeClass('move-out');
		
		jQuery(this).parent('ul.sub-menu').removeClass('move-in');
    });
  }
 <?php endif;?>
 <?php if($this->options['show_sub_menu_way']!='accordion' && $this->options['show_sub_menu_way']!='always'):?>
	jQuery('.eds-responsive-menu li.menu-item-has-children').each(function(index, element) {
			jQuery(this).find('.back-pre-nav a').html(jQuery(this).children('a').text());
	});
 <?php endif;?>
});
</script>
			 
		<?php 
		}

		


}



class Wpse8170_Menu_Walker extends Walker_Nav_Menu {
var $number = 1;
    function start_lvl(&$output, $depth=0, $args=array()) {
		
		$this->number=1;
		$output .= "\n<ul class=\"sub-menu\">\n";
	}
	
	
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        	$this->number++;
			if($this->number==2){
				$parent_name="";
				if($item->menu_item_parent>0){
					if(get_post($item->menu_item_parent)->post_title!=""){
						$parent_name=get_post($item->menu_item_parent);
					}else{
						$meta_data = get_post_meta($item->menu_item_parent, '_menu_item_object_id', true );
						$parent_name=get_post($meta_data);
					}
					
					$output .='<li class="back-pre-nav"><i class="eds-arrows-back"></i><a href="'.get_permalink($parent_name->ID).'">'.$parent_name->post_title.'</a></li>';
				}
			}
       		
           $class_names = $value = '';
           $classes = empty( $item->classes ) ? array() : (array) $item->classes;
           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';
		   $output .= '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		   
           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			if(get_post_meta( $item->ID, 'menu-item-font-awesome', true )!=""){
				if (preg_match("/fa-/i", get_post_meta( $item->ID, 'menu-item-font-awesome', true ))) {
					$fontawesome=get_post_meta( $item->ID, 'menu-item-font-awesome', true );
				}else{
					$fontawesome='fa-'.get_post_meta( $item->ID, 'menu-item-font-awesome', true );
				}
			    $item_output .='<i class="eds-icon fa '.$fontawesome.'"></i>';
				
		    }
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
			if (in_array("menu-item-has-children", $classes)) {
				$item_output .='<i class="eds-arrows"></i>';
			}
			$item_output .= '</a>';
			
			$item_output .= $args->after;
			
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}



class EDS_Accordion_Menu_Walker extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth=0, $args=array()) {
		
		
		$output .= "\n<ul class=\"sub-menu accordion_drop_down\">\n";
	}
	
	
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        
       		
           $class_names = $value = '';
           $classes = empty( $item->classes ) ? array() : (array) $item->classes;
           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';
			$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
			
           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			if(get_post_meta( $item->ID, 'menu-item-font-awesome', true )!=""){
				if (preg_match("/fa-/i", get_post_meta( $item->ID, 'menu-item-font-awesome', true ))) {
					$fontawesome=get_post_meta( $item->ID, 'menu-item-font-awesome', true );
				}else{
					$fontawesome='fa-'.get_post_meta( $item->ID, 'menu-item-font-awesome', true );
				}
			    $item_output .='<i class="eds-icon fa '.$fontawesome.'"></i>';
				
		    }
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
			if (in_array("menu-item-has-children", $classes)) {
				$item_output .='<i class="eds-arrows"></i>';
			}
			$item_output .= '</a>';
			
			$item_output .= $args->after;
			
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}

class EDS_always_Menu_Walker extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth=0, $args=array()) {
		
		
		$output .= "\n<ul class=\"sub-menu always\">\n";
	}
	
	
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        
       		
           $class_names = $value = '';
           $classes = empty( $item->classes ) ? array() : (array) $item->classes;
           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';
			$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
			
           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			if(get_post_meta( $item->ID, 'menu-item-font-awesome', true )!=""){
				if (preg_match("/fa-/i", get_post_meta( $item->ID, 'menu-item-font-awesome', true ))) {
					$fontawesome=get_post_meta( $item->ID, 'menu-item-font-awesome', true );
				}else{
					$fontawesome='fa-'.get_post_meta( $item->ID, 'menu-item-font-awesome', true );
				}
			    $item_output .='<i class="eds-icon fa '.$fontawesome.'"></i>';
				
		    }
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
			if (in_array("menu-item-has-children", $classes)) {
				$item_output .='<i class="eds-arrows"></i>';
			}
			$item_output .= '</a>';
			
			$item_output .= $args->after;
			
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}


