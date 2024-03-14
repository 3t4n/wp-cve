<?php
/**
 * Plugin Name: Flip Box Carousel
 * Plugin URI: 
 * Description: Dual Image Flip Box Carousel
 * Version: 1.1.0
 * Author: Arpit Patel
 * Author URI: https://wordpress.org/support/users/arpit-patel/
 * License: GPL2
 */
if ( is_admin() )
{
	include( plugin_dir_path( __FILE__ ) . 'bliss-flipbox-setting.php');
}
add_action('init', 'bliss_register_flip_box');
function bliss_register_flip_box()
{
	register_post_type('flipbox', array(
	'label' => 'Flip Box',
	'description' => 'This will allow you to Show Flip Boxes in Carousel.',
	'public' => false,
	'show_ui' => true,
	'show_in_menu' => true,
	'capability_type' => 'post',
	'map_meta_cap' => true,
	'hierarchical' => false,
	'rewrite' => false,
	'query_var' => true,
	'supports' => array(
	'title',
	),
	'labels' => array(
	'name' => 'Flip Box',
	'singular_name' => 'Flip Box',
	'menu_name' => 'Flip Box',
	'add_new' => 'Add Flip Box',
	'add_new_item' => 'Add New Flip Box',
	'edit' => 'Edit',
	'edit_item' => 'Edit Flip Box',
	'new_item' => 'New Flip Box',
	'view' => 'View Flip Box',
	'view_item' => 'View Flip Box',
	'search_items' => 'Search Flip Box',
	'not_found' => 'No Flip Box Found',
	'not_found_in_trash' => 'No Flip Box Found in Trash',
	'parent' => 'Parent Flip Box'
			)
	));
}

function bliss_flipbox_taxonomy() {
	register_taxonomy(
	'flipbox_categories', 
	'flipbox',   		 //post type name
	array(
	'hierarchical' 		=> true,
	'label' 			=> 'Flipbox Category',  //Display name
	'query_var' 		=> true,
	'rewrite'			=> array(
	'slug' 			=> 'flipbox_categories', // This controls the base slug that will display before each term
	'with_front' 	=> false // Don't display the category base before
	)
	)
	);
}
add_action( 'init', 'bliss_flipbox_taxonomy');

add_filter( 'manage_taxonomies_for_flipbox_columns', 'bliss_flipbox_post_type_columns' );
function bliss_flipbox_post_type_columns( $taxonomies ) {
	$taxonomies[] = 'flipbox_categories';
	return $taxonomies;
}

function bliss_flipbox_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once(plugin_dir_path( __FILE__ ) . 'init.php');
}

add_action( 'init', 'bliss_flipbox_initialize_cmb_meta_boxes', 9999 );

//Add Meta Boxes

function bliss_flipbox_metaboxes( $meta_boxes ) {
	$prefix = '_fcb_'; // Prefix for all fields
	$editor_id = ['fusion_main_editor_wrap'];
	$meta_boxes[] = array(
		'id' => 'fcb_metabox',
		'title' => 'Flip Box Data',
		'pages' => array('flipbox'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Frontside Heading',
				'desc' => 'Add a heading for the frontside of the flip box.',
				'id' => $prefix . 'frontside_heading',
				'type' => 'text',

				 'attributes'  => array(
			        'required'    => 'required',
			    ),
			),
			array(
				'name' => 'Backside Heading',
				'desc' => 'Add a heading for the backside of the flip box.',
				'id' => $prefix . 'backside_heading',
				'type' => 'text'
			),
			array(
				'name' => 'Frontside Content',
				'desc' => 'Add content for the frontside of the flip box.',
				'id' => $prefix . 'frontside_content',
				'type' => 'text'
			),
			array(
			    'name' => 'Frontside Image',
			    'desc' => 'Add Image for the frontside of the flip box.',
			    'id' => $prefix . 'frontside_image',
			    'type' => 'file',
			    'allow' => array( 'url', 'attachment' ),
			   
			),
			array(
			    'name' => 'Backtside Image',
			    'desc' => 'Add Image for the Backside of the flip box.',
			    'id' => $prefix . 'Backside_image',
			    'type' => 'file',
			    'allow' => array( 'url', 'attachment' ) // limit to just attachments with array( 'attachment' )
			),
			array(
				'name' => 'Backside Content',
				'desc' => 'Add content for the Backside of the flip box.',
				'id' => $prefix . 'backside_content',
				'type' => 'wysiwyg',
				 'options' => array(
        'wpautop' => true, // use wpautop?
        'media_buttons' => true, // show insert/upload button(s)
        'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
        'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
        'tabindex' => '',
        'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the `<style>` tags, can use "scoped".
        'editor_class' => '', // add extra class(es) to the editor textarea
        'teeny' => false, // output the minimal editor config used in Press This
        'dfw' => false, // replace the default fullscreen with DFW (needs specific css)
        'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
        'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()  
    ),
			),
			array(
			    'name' => 'Backtside Button Text',
			    'desc' => 'Add Text for the Backside button of the flip box.',
			    'id' => $prefix . 'backside_button_txt',
			    'type' => 'text'
			),
			array(
			    'name' => 'Backtside Button URL',
			    'desc' => 'Add URL for the Backside button of the flip box.',
			    'id' => $prefix . 'backside_button_link',
			    'type' => 'text',
			    'default' => '#'
			),
			array(
			    'name' => 'Backtside Button Background Color',
			    'id'   => $prefix . 'backside_button_bg_color',
			    'type' => 'colorpicker',
			    'default'  => '#747474',
			    'repeatable' => false,
			    'show_on_cb' => 'cmb2_only_show_for_user_1',
			),
			array(
			    'name' => 'Backtside Button Text Color',
			    'id'   => $prefix . 'backside_button_text_color',
			    'type' => 'colorpicker',
			    'default'  => '#ffffff',
			    'repeatable' => false,
			    'show_on_cb' => 'cmb2_only_show_for_user_1',
			),
		),
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'bliss_flipbox_metaboxes' );

function cmb2_only_show_for_user_1( $field ) {
    // Returns true if current user's ID is 1, else false
     $gobal_color = get_option('gobal_color'); 
     if($gobal_color == 1){
    return false;
}else{return true;}
}

function bliss_flipcarousel_shortcode($atts, $content = null)
{
	$a = shortcode_atts(array(
			'category' => '',
			'number' => '',
	), $atts);
	
	//for a given post type, return given category posts.
	$post_type = 'flipbox';
	$tax = 'flipbox_categories';
	$tax_terms = get_terms($tax);
	if ($tax_terms) {
		$args=array(
				'post_type' => $post_type,
				"$tax" => $a['category'],
				'post_status' => 'publish',
				'posts_per_page' => $a['number'],
				'ignore_sticky_posts'=> 1,
				'orderby' => 'date',
				'order' => 'DESC'
				
		);
		$my_query = null;
		$my_query = new WP_Query($args);
		if( $my_query->have_posts() ) { 
			
		 ?>
		<?php $flipcon = '<div class="flipbox-wraper">
			<div class="flipbox-slider">
				<ul class="owl-carousel owl-theme" id="flipbox-carousel">'; ?>
					<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
					<?php $frontimg = get_post_meta( get_the_ID(), '_fcb_frontside_image', true );
						  $backimg =  get_post_meta( get_the_ID(), '_fcb_Backside_image', true );  
						  $fronthead = get_post_meta(get_the_ID(), '_fcb_frontside_heading', true);
						  $frontcontent = get_post_meta(get_the_ID(), '_fcb_frontside_content', true);

						  $backhead = get_post_meta(get_the_ID(), '_fcb_backside_heading', true);
						  $backcontent = get_post_meta(get_the_ID(), '_fcb_backside_content', true); 
						  $backbtn = get_post_meta(get_the_ID(), '_fcb_backside_button_txt', true);
						  $backbtncolor = get_post_meta(get_the_ID(), '_fcb_backside_button_bg_color', true);
						  $backbtntext = get_post_meta(get_the_ID(), '_fcb_backside_button_text_color', true); 
						  $customcss = '';
						   $gobal_color = get_option('gobal_color'); if($gobal_color != 1) { $customcss = "background: ".$backbtncolor. "; color:".$backbtntext."";} ?>
					<?php $flipcon .= '<li class="item">
					<div class="flipbox-front">
						<div class="flipbox-front-inner">'; ?>
							<?php if($frontimg != "") { ?>
							<?php $flipcon .= '<div class="flipbox-grafix flipbox-image">'; ?>
								<?php $flipcon .= wp_get_attachment_image( get_post_meta( get_the_ID(), '_fcb_frontside_image_id', 1 ), 'full' ); ?>
							<?php $flipcon .= '</div>'; ?>
							<?php } 
							if($fronthead != '')?> 
							<?php $flipcon .= '<h2 class="flipbox-heading">'; ?>
								<?php $flipcon .= get_post_meta(get_the_ID(), '_fcb_frontside_heading', true); ?>
							<?php $flipcon .= '</h2>'; ?>
							<?php if($frontcontent != '')  $flipcon .= '<p>' . $frontcontent . '</p>';  ?>
						<?php $flipcon .= '</div>
					</div>
					<div class="flipbox-back">
						<div class="flipbox-back-inner">'; ?>
							<?php if($backhead != '') ?>
							<?php $flipcon .= '<h3 class="flipbox-heading-back">'; ?>
								 <?php $flipcon .= $backhead; ?>
							<?php $flipcon .= '</h3>'; ?>
							<?php if($backimg != "")  $flipcon .= wp_get_attachment_image( get_post_meta( get_the_ID(), '_fcb_Backside_image_id', 1 ), 'full' ); ?>
							<?php if($backcontent != '') $flipcon .= '<p>' . $backcontent .'</p>'; ?>
							<?php if($backbtn != '') { ?>
							<?php $flipcon .= '<div class="flipbox-btn">'; ?>
								<?php $flipcon .= '<a href="'.get_post_meta(get_the_ID(), '_fcb_backside_button_link', true).'" class="flipbox-button" style="'.$customcss .'">
									<span class="flipbox-button-text">'.get_post_meta(get_the_ID(), '_fcb_backside_button_txt', true).'</span></a>'; ?>
							<?php $flipcon .= '</div>'; ?>
							<?php } ?>
						<?php $flipcon .= '</div>
					</div>
				</li>'; ?>
					<?php endwhile; ?>
				<?php $flipcon .= '</ul>
			</div>
		</div>';

		} 		
 }


 
 $flipnumber = get_option('flipnumber'); 
 $stopauto = get_option('autoplay'); 
 $navigation = get_option('navigation'); 
 $pagination = get_option('pagination'); 
 $loop = get_option('repeate_loop'); 
 $stop = get_option('stop_hover'); 
 $flip_speed = get_option('flip_speed'); 
 if($flipnumber != ""){$itemsDesktop = $flipnumber;}else{$itemsDesktop = "4";}
 if($navigation == 1){$navigation = 'true';}else{$navigation = "false";}
 if($loop == 1){$loop = 'true';}else{$loop = "false";}
 if($stop == 1){$stop = 'true';}else{$stop = "false";}
 if($pagination == 1){$pagination = 'true';}else{$pagination = "false";}
 if($stopauto != ""){$autoplayTimeout = $stopauto;}else{$autoplayTimeout = "3000";}
  ?>
<?php
 $flipcon .= '<script type="text/javascript" charset="utf-8">
jQuery.noConflict();
  jQuery(document).ready(function() {
              var owl = jQuery(".owl-carousel");
              owl.owlCarousel({
                nav: '.$navigation.',
                loop: '.$loop.',
                autoplayHoverPause: '.$stop.',
                autoplay: true,
                autoplayTimeout: '.$autoplayTimeout.',
                navText: "",
                dots: '.$pagination.',
                responsive: {
                  0: {
                    items: 1
                  },
                  567: {
                    items: 3
                  },
                  1024: {
                    items:'.$itemsDesktop.'
                  }
                }
              })
    jQuery("#flipbox-carousel .item").mouseover(function(){
	jQuery(this).addClass("hover"); 
	});
	 jQuery("#flipbox-carousel .item").mouseout(function(){
	jQuery(this).removeClass("hover");
	});
  });
</script>';
  ?><?php 
return $flipcon;

wp_reset_query();	
 }

add_shortcode( 'flipcarousel', 'bliss_flipcarousel_shortcode' );
add_filter('the_content', 'do_shortcode');
add_filter('widget_text','do_shortcode');


function bliss_fliobox_enqueue_script()
{   	
    wp_enqueue_script( 'flipbox_carousel_script', plugin_dir_url( __FILE__ ) . 'js/flipbox.carousel.min.js', array('jquery'), '1.0.0', false );
	wp_enqueue_style( 'flipbox_style', plugin_dir_url( __FILE__ ) . 'css/flipbox.carousel.css', '', '1.0.0');
}


add_action('wp_enqueue_scripts', 'bliss_fliobox_enqueue_script');


add_action('admin_menu', 'bliss_flipbox_admin_menu');

function bliss_flipbox_admin_menu() {
      add_submenu_page("edit.php?post_type=flipbox", "Flip Box Carousel Settings", "Setting", 'administrator', "flipbox", "bliss_flipbox_setting");
}
add_action( 'admin_enqueue_scripts', 'bliss_flipbox_color_picker' );
function bliss_flipbox_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/custom-admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

function bliss_flipbox_css()
{ 
$gobal_color = get_option('gobal_color'); 
$flip_height = get_option('flip_height'); 
$flip_color = get_option('flip_color'); 
$flip_txt_color = get_option('flip_txt_color'); 
$flip_title_color = get_option('flip_title_color');  
$flip_bg_color = get_option('flip_bg_color');
$flip_border_color = get_option('flip_border_color'); 
$flip_border_width = get_option('flip_border_width'); 
$flip_border_radius = get_option('flip_border_radius'); 
  ?>
<STYLE TYPE="text/css">
<?php if($flip_height != ''){ ?>
ul#flipbox-carousel li {height: <?php echo $flip_height; ?>px; }
<?php } else { ?>ul#flipbox-carousel li {height: auto;}<?php } ?>

<?php if($gobal_color == 1){ ?>
ul#flipbox-carousel li .flipbox-btn .flipbox-button{background: <?php echo $flip_color; ?>; color: <?php echo $flip_txt_color; ?>}
<?php } else { ?>ul#flipbox-carousel li .flipbox-btn .flipbox-button{background: #a0ce4e; color: #ffffff;}<?php } ?>
<?php if($flip_title_color != ''){ ?>
ul#flipbox-carousel li .flipbox-heading-back, ul#flipbox-carousel li .flipbox-heading{color: <?php echo $flip_title_color; ?>}
<?php } else { ?>ul#flipbox-carousel li .flipbox-heading-back, ul#flipbox-carousel li .flipbox-heading{color: #a0ce4e;}<?php } ?>
<?php if($flip_bg_color != '') { $bg = $flip_bg_color; } else { $bg = '#ffffff'; } ?>
<?php if($flip_border_color != '') { $brdcol = $flip_border_color; } else { $brdcol = 'rgba(0, 0, 0, 0.5)'; } ?>
<?php if($flip_border_width != '') { $brdwidth = $flip_border_width; } else { $brdwidth = '1'; } ?>
<?php if($flip_border_radius != '') { $radius = $flip_border_radius; } else { $radius = '4'; } ?>
ul#flipbox-carousel li .flipbox-front, ul#flipbox-carousel li .flipbox-back{background: <?php echo $bg; ?>; border-color: <?php echo $brdcol; ?>; border-width:<?php echo $brdwidth; ?>px; border-radius: <?php echo $radius; ?>px}
</STYLE>
<?php }
add_action('wp_head', 'bliss_flipbox_css');
?>
