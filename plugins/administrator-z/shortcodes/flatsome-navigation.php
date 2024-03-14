<?php 
use Adminz\Admin\Adminz as Adminz;
function adminz_navigation(){
    add_ux_builder_shortcode('adminz_navigation', array(
        'name'      => __('Navigation Seletor'),
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'nav' . '.svg',
        'options' => array(
        	'image' => array(
                'type'       => 'image',
                'heading'    => __('Icon','administrator-z'),
                'default' => '',
            ),
            'color' =>array(
                'type' => 'colorpicker',
                'heading' => __('Icon Color','administrator-z'),
                'alpha' => true,
                'format' => 'hex',
            ),
        	'name' => array(
	          	'type' => 'textfield',
	          	'heading' => __( 'Menu name','administrator-z' ),
	          	'default'=> 'MENU'
	    	),
	    	'nav' => array(
	          'type' => 'select',
	          'heading' => __( 'Choose Navigation','adminzr' ),	          
	          'param_name' => 'slug',
		        'config' => array(
		            'multiple' => false,
		            'placeholder' => 'Select..',
		            'termSelect' => array(
		                'taxonomies' => 'nav_menu'
		            ),
		        )
	    	),
	    	'appearance'=>[
	    		'type'	=> 'group',
	    		'heading'=>	'Appearance',
	    		'options'=> [
			    	'destop_appearance'=>array(
		                'type' => 'select',
		                'heading'   =>__('Desktop appearance','administrator-z'),
		                'default' => 'horizontal',
		                'options'=> [
		                	'horizontal'=> 'Horizontal',
		                	'name_left'=> 'Horizontal & name',	
		                	'vertical' => 'Vertical',
		                	'vertical_name' => 'Vertical & Name',
		                	'button_toggle'=> 'Button toggle',
		                	'button_with_link'=> 'Button with link'	                	
		                ]
		            ),    	
			    	'mobile_appearance'=>array(
		                'type' => 'select',
		                'heading'   =>__('Mobile appearance','administrator-z'),
		                'default' => 'horizontal',
		                'options'=> [
		                	'horizontal'=> 'Horizontal',
		                	'name_left'=> 'Horizontal & name',
		                	'vertical' => 'Vertical',
		                	'vertical_name' => 'Vertical & Name',
		                	'button_toggle'=> 'Button toggle',
		                	'button_with_link'=> 'Button with link'
		                ]
		            ),
			    	'style' => array(
			          'type' => 'select',
			          'heading' => __( 'Nav Style','administrator-z' ),
			          'default' => '',
			          'options' => array(
			          		''=> "Default",
			              	'divided' => 'Divided',
							'line' => 'Line',
							'line-grow' => 'Line grow',
							'line-bottom' => 'Line bottom',
							'box' => 'Box',
							'outline' => 'Outline',
							'pills' => 'Pills',
							'tabs' => 'Tabs',
			          )
			    	),
			    	'size' => array(
			          'type' => 'select',
			          'heading' => __( 'Nav Size','administrator-z' ),
			          'default' => 'default',
			          'options' => array(
			              	'xsmall' => 'Xsmall',
			              	'small'	=> 'Small',
			              	'default'	=> 'Default',
			              	'medium'	=> 'Medium',
			              	'large'	=> 'Large',
			              	'xlarge'	=> 'Xlarge',
			          )
			    	),
			    	'spacing' => array(
			          'type' => 'select',
			          'heading' => __( 'Nav Spacing','administrator-z' ),			          
			          'default' => 'default',
			          'options' => array(
			              	'xsmall' => 'Xsmall',
			              	'small'	=> 'Small',
			              	'default'	=> 'Default',
			              	'medium'	=> 'Medium',
			              	'large'	=> 'Large',
			              	'xlarge'	=> 'Xlarge',
			          )
			    	),
			    	'uppercase' => array(
			          'type' => 'select',
			          'heading' => __( 'Uppercase','administrator-z' ),
			          'default' => 'normal',
			          'options' => array(
			              	'uppercase' => 'Uppercase',
			              	'normal' => 'Normal',
			              	'captilizer' => 'Captilizer'
			          )
			    	),
			    	'horizontal_align' => array(
			          'type' => 'select',
			          'heading' => __( 'Items align','administrator-z' ),
			          'default' => 'left',			          
			          'options' => array(
			              'left' => 'Left',
			              'right' => 'Right',
			              'center'=> 'Center',
			          )
			    	),
	    		]
	    	],	
			'other'=>[
				'type' =>'group',
				'heading' => 'Other',
				'options' => [
					'menu_mobile_link' => array(
			          	'type' => 'textfield',
			          	'heading' => __( 'Link','administrator-z' ),
			          	'default'=> '',			          	
			    	),
			    	'menu_mobile_link_text' => array(
			          	'type' => 'textfield',
			          	'heading' => __( 'Link text','administrator-z' ),
			          	'default'=> 'View more',			          	
			    	),
					'toggle' => array(
			          'type' => 'select',
			          'heading' => __( 'Vertical Items toggled','administrator-z' ),	          
			          'default' => 'no',
			          'options' => array(
			              'no' => 'No',
			              'yes' => 'Yes',
			          )
			    	),
			    	'class' => array(
			          'type' => 'textfield',
			          'heading' => __( 'Class','administrator-z' ),
			    	),
				]
			],
	    	
        ),
    ));
}
add_action('ux_builder_setup', 'adminz_navigation');

function adminz_navigation_shortcode($atts){
	add_filter('nav_menu_css_class', 'adminz_add_additional_class_on_li', 1, 3);
	add_filter('walker_nav_menu_start_el', 'add_description_to_menu', 10, 4);
    extract(shortcode_atts(array(
    	'image'=> "",
    	"image_id"=> "nav_image_".wp_rand(),
    	'color'=>"",
    	'name'=> 'MENU',
    	'nav'    => '2',
    	'destop_appearance'=>'horizontal',
    	'mobile_appearance' => 'horizontal',
    	'menu_mobile_link'=> '',
    	'menu_mobile_link_text'=> 'View more',                
        'uppercase' => 'normal',	
        'style' => '',
        'toggle' => 'no',
        'horizontal_align' => 'left',        
        'size' => 'default',
        'spacing' => 'default',
        'class'=> 'adminz_navigation_custom'
    ), $atts));
    $id = "adminz_navigation".rand();
    $ul_class = 'nav-'.$horizontal_align.' nav-'.$style.' nav-'.$uppercase.' nav-size-'.$size.' nav-spacing-'.$spacing." ".$class;
    $walker  = 'FlatsomeNavDropdown';

    //image icon
    $image_html = "";
    $has_image = "";
    if($image){
        $attr['id'] = $image;
        $attr = [];
	    if($color) {
	        $attr['style']['color']= $color;
	    }
	    $image_html = Adminz::get_icon_html(
	    	wp_get_attachment_image_src( $image )[0],
	    	$attr
	    );
	    $has_image = "has-image";
    }
    
    ob_start();
    ?>
    <div class="hide-for-medium pc">
    	<?php 
    	switch ($destop_appearance) {
    		case 'vertical':    			
    			require( __DIR__.'/inc/navigation/vertical.php');
    			break;
			case 'vertical_name':    			
				require( __DIR__.'/inc/navigation/vertical_name.php');
    			break;
    		case 'name_left':	    		
    			require( __DIR__.'/inc/navigation/name_left.php');
    			break;
			case 'button_toggle':				
				require( __DIR__.'/inc/navigation/button_toggle.php');
				break;
			case 'button_with_link':				
				require( __DIR__.'/inc/navigation/button_with_link.php');
				break;
    		default:    			
    			require( __DIR__.'/inc/navigation/horizontal.php');
    			break;
    	}
    	?>
    </div>
    <!-- mobile-->
    <div class="show-for-medium mb">
    	<?php 
    	switch ($mobile_appearance) {
    		case 'vertical':    			
    			require( __DIR__.'/inc/navigation/vertical.php');
    			break;
			case 'vertical_name':    			
				require( __DIR__.'/inc/navigation/vertical_name.php');
    			break;
    		case 'name_left';    			
    			require( __DIR__.'/inc/navigation/name_left.php');
    			break;
			case 'button_toggle':				
				require( __DIR__.'/inc/navigation/button_toggle.php');
				break;
			case 'button_with_link':				
				require( __DIR__.'/inc/navigation/button_with_link.php');
				break;
    		default:    			
    			require( __DIR__.'/inc/navigation/horizontal.php');
    			break;
    	}
    	?>
    </div>
    <script type="text/javascript">
    	window.addEventListener('DOMContentLoaded', function() {
    		(function($) {
    			$('#<?php echo esc_attr($id);?> .nav-head .button').each(function(){
					$(this).on("click",function(){					
						var parent = $(this).closest(".nav-head");					
						var target = parent.next().toggleClass('hidden');
						
					});
				});
			})(jQuery);    		
    	});		
	</script>

    <?php


    
    remove_filter('nav_menu_css_class', 'adminz_add_additional_class_on_li', 1, 3);
	remove_filter('walker_nav_menu_start_el', 'add_description_to_menu', 10, 4);
	?>
	<style type="text/css">
		.adminz_navigation_wrapper .nav-head{display: flex; justify-content: space-between; align-items: center;}
		#<?php echo esc_attr($id);?>>.show-for-small{	padding-top: 10px; padding-bottom: 10px;}
		#<?php echo esc_attr($id);?>>.hide-for-small .is-default{padding-bottom: 30px;}
		#<?php echo esc_attr($id);?> .navhead{display:  inline-block; width: unset; vertical-align:  middle; margin-bottom:  0px;}
		#<?php echo esc_attr($id);?> .has-image{
			display: flex;
			align-items: center;
		}
		#<?php echo esc_attr($id);?> .has-image svg{
			width: 2em;
			margin-right: 10px;
		}
		.col-inner #<?php echo esc_attr($id);?> .sub-menu li{ margin-left: 0 ; }
		.adminz_navigation_wrapper ul.nav-center li{text-align:  center;}

	</style>	
	<?php
    $html = ob_get_clean();
    return "<div id='".$id."' class='adminz_navigation_wrapper'>".$html."</div>";
}
add_shortcode('adminz_navigation', 'adminz_navigation_shortcode');

function adminz_add_additional_class_on_li($classes, $item, $args) {
    if(isset($args->add_li_class)) {
        $classes[] = $args->add_li_class;
    }
    return $classes;
}
function add_description_to_menu($item_output, $item, $depth, $args) {
	
   	if (strlen($item->description) > 0 ) {
      	$item_output .= sprintf('<p class="description">%s</p>', esc_html($item->description)); 
   	}   
   return $item_output;
}
