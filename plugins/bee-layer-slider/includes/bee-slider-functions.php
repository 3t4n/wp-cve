<?php

  add_action( 'admin_notices', 'bee_layer_pro_notice' );

function bee_layer_pro_notice() {
    ?>
    <div class="notice  is-dismissible" >
       <a style="text-decoration:none;" href="https://wpacme.com/product/layer-slider" target="_blank"><img src="<?php echo plugin_dir_url( __FILE__ ).'../public/css/images/layer_pro.png'; ?>" alt="upgrade pro version" /></a>
    </div>


    <?php
}
// Registers the new post type and taxonomy

function bee_layer_slider_type() {
	register_post_type( 'beeslider',
		array(
			'labels' => array(
				'name' => __( 'Layer Sliders' ),
				'singular_name' => __( 'Slider' ),
				'add_new' => __( 'Add New Slider' ),
				'add_new_item' => __( 'Add New Slider' ),
				'edit_item' => __( 'Edit Slider' ),
				'new_item' => __( 'Add New Slider' ),
				'view_item' => __( 'View Slider' ),
				'search_items' => __( 'Search Slider' ),
				'not_found' => __( 'No Slider found' ),
				'not_found_in_trash' => __( 'No slider found in trash' )
			),
			'public' => true,
			'supports' => array( 'title' ),
			'capability_type' => 'post',
			'rewrite' => array("slug" => "beesliders"), // Permalinks format
			'menu_position' => 5,
		    'menu_icon'           => plugin_dir_url( __FILE__ ).'../public/css/images/layers-icon.png',
		)	
	);
	
}

add_action( 'init', 'bee_layer_slider_type' );

///Change admin screen for fullwidth

function bee_screen_layout_columns( $columns ) {

    $columns['beeslider'] = 1;

    return $columns;

}

add_filter( 'screen_layout_columns', 'bee_screen_layout_columns' );

function bee_screen_layout_post() {

    return 1;
}


//Move publish metabox

function bee_move_meta_box( $post_type ) {
	if ( in_array( $post_type, array( 'beeslider') ) ) {
		add_meta_box(
			'submitdiv',
			
			$post_type,
			'after_title',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'bee_move_meta_box' );

//Remove Preview button
function bee_posttype_admin_css() {
    global $post_type;
    $post_types = array(
                        /* set post types */
                        'beeslider',
                                       );
    if(in_array($post_type, $post_types))
    echo '<style type="text/css">#post-preview, #view-post-btn{display: none;}</style>';
}
add_action( 'admin_head-post-new.php', 'bee_posttype_admin_css' );
add_action( 'admin_head-post.php', 'bee_posttype_admin_css' );


function bee_code($atts)

{
  extract(shortcode_atts(array(
      'id' => ''
      
   ), $atts));

		
		
$bee_slides_datas= rwmb_meta('bee_slide_details','',$id );

?>

	<div class="bee-slider-wrapper">
			<div class="responisve-container">
				<div class="bee-slider">
					<div class="fs_loader"></div>
<?php
//Slide details
$bee_slides_datas = rwmb_meta('bee_slide_details','',$id );

// Slide settings

$bee_slider_settings = get_option( 'bee_slider_options' );

//Default Positions
$bee_img_default_pos_y=$bee_slider_settings['bee_img_top'];
$bee_img_default_pos_x=$bee_slider_settings['bee_img_left'];
$bee_txt_default_pos_x=$bee_slider_settings['bee_txt_left'];
$bee_txt_default_pos_y=$bee_slider_settings['bee_txt_top'];

   ///Image settings
	    $bee_img_anim=$bee_slider_settings['bee_img_anim'];
	//	$bee_img_delay=$bee_slider_settings['bee_img_anim_delay'];
		$bee_img_pos_y=$bee_slider_settings['bee_img_top'];
		$bee_img_pos_x=$bee_slider_settings['bee_img_left'];
		
	//Text settings
	    $bee_txt_anim=$bee_slider_settings['bee_txt_anim'];
		//$bee_txt_delay=$bee_slider_settings['bee_txt_anim_delay'];
		$bee_txt_pos_x=$bee_slider_settings['bee_txt_left'];
		$bee_txt_pos_y=$bee_slider_settings['bee_txt_top'];



if ( !empty( $bee_slides_datas ) ) {
	foreach ( $bee_slides_datas as $bee_slide_data ) {
	
	$bee_slide_bg = isset( $bee_slide_data['bee_slide_bg_img'] ) ? $bee_slide_data['bee_slide_bg_img'] : array();
	
	echo '<div class="slide fadeIn" style="background:url('.$bee_slide_bg.');background-repeat: no-repeat;
  background-size: cover;-webkit-transition: background-image 0.5s linear;">';
		
		 ///  Get image data
	$bee_slide_img = isset( $bee_slide_data['bee_slide_img'] ) ? $bee_slide_data['bee_slide_img'] : array();
		
	///Get Text data
	$bee_slide_txt = isset( $bee_slide_data['bee_text_layer'] ) ? $bee_slide_data['bee_text_layer'] : array();
	
		if ( ! empty( $bee_slide_img ) ) {
		$bee_img_step=0;
	
		
				//echo $bee_img_layer;
				
				echo '<img class="bee-slide-img" 	src="'.$bee_slide_img.'"   data-position="'.$bee_img_pos_y.','.$bee_img_pos_x.'" data-in="'.$bee_img_anim.'" data-step="'.$bee_img_step.'"  >';
$bee_img_step++;
//$bee_img_delay=$bee_img_delay+100;

	
			
		}
		if ( ! empty( $bee_slide_txt ) ) {
		$bee_txt_step= 1;
		
			foreach ( $bee_slide_txt as $bee_txt_detail ) {
				
				echo '<p 		class="bee-slider-text"  data-position="'.$bee_txt_pos_y.','.$bee_txt_pos_x.'" data-in="'.$bee_txt_anim.'" data-step="'.$bee_txt_step.'"  >'.$bee_txt_detail.'</p>';
				
					$bee_txt_step++;
		//$bee_txt_pos_x=$bee_txt_pos_x+50;
		$bee_txt_pos_y=$bee_txt_pos_y+50;
				//$bee_txt_delay=$bee_txt_delay+100;
				
				
			}
		}
		echo '</div>';
	$bee_txt_pos_y=$bee_txt_default_pos_y;
	$bee_txt_pos_x=$bee_txt_default_pos_x;
	}
}
echo '</div></div></div>';


}

 add_shortcode('bee-slider','bee_code');

 // Register settings page. In this case, it's a theme options page
add_filter( 'mb_settings_pages', 'bee_options_page' );
function bee_options_page( $settings_pages )
{
	$settings_pages[] = array(
		'id'          => 'bee_slider_settings',
		'option_name' => 'bee_slider_options',
		'menu_title'  => __( 'Global Settings', 'textdomain' ),
		'icon_url'    => 'dashicons-align-left',
		'style'       => 'no-boxes',
		'parent'        =>'edit.php?post_type=beeslider',
		'columns'     => 1,
		'tabs'        => array(
			'general' => __( 'General Settings', 'textdomain' ),
			'textlayer'  => __( 'Text Layer Options', 'textdomain' ),
			'imagelayer'     => __( 'Image Layer Options','textdomain' ),
			'demovideo'     => __( 'How To Use ?','textdomain' ),
		),
		'position'    => 3,
	);
	return $settings_pages;
	
	
}

// Register meta boxes and fields for settings page
add_filter( 'rwmb_meta_boxes', 'bee_options_meta_boxes' );
function bee_options_meta_boxes( $meta_boxes )
{
	$meta_boxes[] = array(
		'id'             => 'general',
		'title'          => __( 'General', 'textdomain' ),
		'settings_pages' => 'bee_slider_settings',
		'tab'            => 'general',
		'fields' => array(
			array(
				'name' => __( 'Default Background Image', 'textdomain' ),
				'id'   => 'bee_bg',
				'type' => 'file_input',
			),
				array(
				'name' => __( 'Background Color', 'textdomain' ),
				'id'   => 'bee_bg_color',
				'type' => 'color',
			),
				array(
				'name' => __( 'Border width', 'textdomain' ),
				'id'   => 'bee_slider_border',
				'type' => 'slider',
			),
			array(
				'name' => __( 'Border Color', 'textdomain' ),
				'id'   => 'bee_border_color',
				'type' => 'color',
			),
			),
	);
	$meta_boxes[] = array(
		'id'             => 'colors',
		'title'          => __( 'Colors', 'textdomain' ),
		'settings_pages' => 'bee_slider_settings',
		'tab'            => 'textlayer',
		'fields' => array(
		
		
		array(
						'name' => __( 'Animation', 'rwmb' ),
						'id'   => 'bee_txt_anim',
						'type' => 'select',
							'options' => array(
						     ''  => __('none', 'rwmb' ),
							'fade'  => __('fade' , 'rwmb' ),
							'left'  => __('left', 'rwmb' ),
							'topLeft'  => __('topLeft', 'rwmb' ),
							'bottomLeft'  => __('bottomLeft', 'rwmb' ),
							'right'  => __('right', 'rwmb' ),
							'topRight'  => __('topRight', 'rwmb' ),
							'bottomRight'  => __('bottomRight', 'rwmb' ),
							'top'  => __('top', 'rwmb' ),
							'bottom'  => __('bottom', 'rwmb' ),
							
						),
					),
			array(
				'name' => __( 'Top Padding', 'textdomain' ),
				'id'   => 'bee_txt_top',
				'type' => 'number',
				'std' => 100,
			),
				array(
				'name' => __( 'Left Padding', 'textdomain' ),
				'id'   => 'bee_txt_left',
				'type' => 'number',
				'std' => 700,
			),
			
			array(
				'name' => __( 'Font Size', 'textdomain' ),
				'id'   => 'bee_slide_font_size',
				'type' => 'slider',
			),
			array(
				'name' => __( 'Text Color', 'textdomain' ),
				'id'   => 'bee_slide_text_color',
				'type' => 'color',
				'std' => '#757575',
			),
			array(
				'name' => __( 'Text Background Color', 'textdomain' ),
				'id'   => 'bee_slide_text_bgcolor',
				'type' => 'color',
				'std' => '#ffffff',
			),
			array(
				'name' => __( 'Text Padding', 'textdomain' ),
				'id'   => 'bee_slide_padding',
				'type' => 'slider',
				'std' => 10,
			),
		),
	);
	$meta_boxes[] = array(
		'id'             => 'bee_demo_video',
		'title'          => __( 'Watch demo video for how to work', 'textdomain' ),
		'settings_pages' => 'bee_slider_settings',
		'tab'            => 'demovideo',
		'fields' => array(
		
	
			array(
				'id'   => 'bee_txt_top',
				'type' => 'custom_html',
				'std'  => '<iframe src="https://player.vimeo.com/video/192889406" width="640" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<p><a href="https://vimeo.com/192889406">Bee layer slider settings</a> from <a href="https://vimeo.com/user28787638">seenu</a> on <a href="https://vimeo.com">Vimeo</a>.</p>',
				
			),
					),
	);
	$meta_boxes[] = array(
		'id'             => 'info',
		'title'          => __( 'Theme Info', 'textdomain' ),
		'settings_pages' => 'bee_slider_settings',
		'tab'            => 'imagelayer',
		'fields'         => array(
			array(
						'name' => __( 'Animation', 'rwmb' ),
						'id'   => 'bee_img_anim',
						'type' => 'select',
							'options' => array(
						     ''  => __('none', 'rwmb' ),
							'fade'  => __('fade' , 'rwmb' ),
							'left'  => __('left', 'rwmb' ),
							'topLeft'  => __('topLeft', 'rwmb' ),
							'bottomLeft'  => __('bottomLeft', 'rwmb' ),
							'right'  => __('right', 'rwmb' ),
							'topRight'  => __('topRight', 'rwmb' ),
							'bottomRight'  => __('bottomRight', 'rwmb' ),
							'top'  => __('top', 'rwmb' ),
							'bottom'  => __('bottom', 'rwmb' ),
							
						),
	),	
	
		array(
				'name' => __( 'Image Maximum Height px', 'textdomain' ),
				'id'   => 'bee_img_height',
				'type' => 'text',
				'std' => 350,
			),
		array(
				'name' => __( 'Top Padding', 'textdomain' ),
				'id'   => 'bee_img_top',
				'type' => 'text',
				'std' => 100,
			),
				array(
				'name' => __( 'Left Padding', 'textdomain' ),
				'id'   => 'bee_img_left',
				'type' => 'text',
				'std' => 100,
			),
			
			
			
			
		),
	);
	return $meta_boxes;
}


///Custom Css

function bee_slider_custom_css() {
	wp_enqueue_style(
		'bee-custom-style',
		  plugin_dir_url( __FILE__ )  . 'includes/bee_custom_css.css'
	);
	
	$bee_slider_options= get_option('bee_slider_options'); 
        $bee_slider_bg = $bee_slider_options['bee_bg'];
		$bee_img_height=  $bee_slider_options['bee_img_height'];
		$bee_slider_bg_color = $bee_slider_options['bee_bg_color'];
		$bee_slider_border= $bee_slider_options['bee_slider_border'];
		$bee_border_color = $bee_slider_options['bee_border_color'];
		$bee_slide_text_color= $bee_slider_options['bee_slide_text_color'];
		$bee_slide_text_bgcolor= $bee_slider_options['bee_slide_text_bgcolor'];
		$bee_slide_padding= $bee_slider_options['bee_slide_padding'];
		$bee_slide_font_size= $bee_slider_options['bee_slide_font_size'];
        $bee_custom_css = "
               .bee-slider-wrapper{
	background: url({$bee_slider_bg}) {$bee_slider_bg_color} no-repeat center center ; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
	  border:{$bee_slider_border}px solid {$bee_border_color};
                     
                }
				
				 .bee-slide-img {
			max-height:{$bee_img_height}px!important;	 
		  width:auto!important;
		 }
				.bee-slider-text{
				


				text-transform:none;
			 max-width: 300px;
  word-break: break-all;
  white-space: normal;
			
				color:{$bee_slide_text_color};
				background:{$bee_slide_text_bgcolor};
				-webkit-background-size: cover;
 
				padding:{$bee_slide_padding}px;
				font-size:{$bee_slide_font_size}px;
				}
				";
        wp_add_inline_style( 'bee-custom-style', $bee_custom_css );
}
add_action( 'wp_enqueue_scripts', 'bee_slider_custom_css' );


add_action( 'manage_beeslider_posts_custom_column', function ( $column_name, $post_id ) 
{

    if ( $column_name == 'beeslider_shortcode')
	$bee_slide_shortcode="[bee-slider id=".get_the_ID()."]";
        printf( '<input type="text" value="'.$bee_slide_shortcode.'"  readonly />', esc_attr( __( 'Send Email' ) ) );
}, 10, 2 );


add_filter('manage_beeslider_posts_columns', function ( $columns ) 
{
    if( is_array( $columns ) && ! isset( $columns['beeslider_shortcode'] ) )
        $columns['beeslider_shortcode'] = __( 'Slider Shortcode' );     
		 
    return $columns;
} );

