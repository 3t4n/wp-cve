<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Image_Masonary extends WPBakeryShortCode{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_image_masonry', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
    	wp_enqueue_script( 'jquery-masonry' );

    	wp_register_script( 'isotope-pkgd', HTMEGAVC_LIBS_URI . '/isotope/isotope.pkgd.min.js', '', '', '');
    	wp_enqueue_script( 'isotope-pkgd' );

    	wp_register_script( 'htmegavc-image-masonry-active', plugins_url('js/image-masonry-active.js', __FILE__), '', '', true);
    	wp_enqueue_script( 'htmegavc-image-masonry-active' );
    	
    	wp_register_style( 'htmegavc-image-grid', plugins_url('css/image-grid.css', __FILE__));
    	wp_enqueue_style( 'htmegavc-image-grid' );
    }


    public function render_shortcode( $atts, $content = null ) {

        extract(shortcode_atts(array(
           
           // Content
           'imagegrid_style'	=>	'1',
           'imagegrid_column'	=>	'3',
           'gridimage_imagesize'	=>	'',
           'imagegrid_list'	=>	'',

           // Styling
           'imagegrid_image_overlay_color'	=>	'',

           // Title Styling
           'imagegrid_title_align'	=>	'center',
           'imagegrid_title_color'	=>	'',
           'imagegrid_title_background'	=>	'',
           'imagegrid_title_padding'	=>	'',

           // Description Styling
           'imagegrid_desciption_align'	=>	'center',
           'imagegrid_desciption_color'	=>	'',
           'imagegrid_desciption_background'	=>	'',
           'imagegrid_desciption_padding'	=>	'',

           // Button Styling
           'imagegrid_readmorebtn_color'	=>	'',
           'imagegrid_readmorebtn_background'	=>	'',
           'imagegrid_readmorebtn_box_shadow'	=>	'',
           'imagegrid_readmorebtn_padding'	=>	'',
           'imagegrid_readmorebtn_border_width'	=>	'',
           'imagegrid_readmorebtn_border_radius'	=>	'',
           'imagegrid_readmorebtn_border_style'	=>	'',
           'imagegrid_readmorebtn_border_color'	=>	'',

           // Button Hover Styling
           'imagegrid_readmorebtn_hover_color'	=>	'',
           'imagegrid_readmorebtn_hover_background'	=>	'',
           'imagegrid_readmorebtn_hover_border_color'	=>	'',

           // Title Typography
           'imagegrid_title_use_google_font'	=>	'',
           'imagegrid_title_google_font'	=>	'',
           'imagegrid_title_typography'	=>	'',

           // Description Typography
           'imagegrid_desciption_use_google_font'	=>	'',
           'imagegrid_desciption_google_font'	=>	'',
           'imagegrid_desciption_typography'	=>	'',

            'custom_class' => '', 
            'wrapper_css' => '', 
        ),$atts));


        // wrapper class
        $wrapper_class_arr = array();
        
        $unique_class = uniqid('htmegavc_image_masonry_');
        $wrapper_class_arr[] = $unique_class;
        $wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_image_grid_wrapper', $atts );
        $wrapper_class_arr[] =  $custom_class;

        // add wrapper class
        $wrapper_class_arr[] =  'htmegavc-image-masonry';
        $wrapper_class_arr[] =  'htmegavc-masonry-activation htmegavc-gridimage-area htmegavc-image-gridstyle-'.$imagegrid_style;

        // join all wrapper class
        $wrapper_class = implode(' ', $wrapper_class_arr);

        // Styling
        $wrapper_inline_style = $title_inline_style = $description_inline_style = $button_inline_style = $button_hover_inline_style = '';
        $wrapper_inline_style .= "background-color: $imagegrid_image_overlay_color;";
        
        // Title Styling
        $title_inline_style .= "text-align:$imagegrid_title_align;";
        $title_inline_style .= "color:$imagegrid_title_color;";
        $title_inline_style .= "background-color:$imagegrid_title_background;";
        $title_inline_style .= "padding:$imagegrid_title_padding;";

        // Description Styling
        $description_inline_style .= "text-align:$imagegrid_desciption_align;";
        $description_inline_style .= "color:$imagegrid_desciption_color;";
        $description_inline_style .= "background-color:$imagegrid_desciption_background;";
        $description_inline_style .= "padding:$imagegrid_desciption_padding;";

        // Button Styling
        $button_inline_style .= "color:$imagegrid_readmorebtn_color;";
        $button_inline_style .= "background-color:$imagegrid_readmorebtn_background;";
        $button_inline_style .= "box-shadow:$imagegrid_readmorebtn_box_shadow;";
        $button_inline_style .= "padding:$imagegrid_readmorebtn_padding;";
        $button_inline_style .= "border-width:$imagegrid_readmorebtn_border_width;";
        $button_inline_style .= "border-style:$imagegrid_readmorebtn_border_style;";
        $button_inline_style .= "border-color:$imagegrid_readmorebtn_border_color;";
        $button_inline_style .= "border-radius:$imagegrid_readmorebtn_border_radius;";

        // Button Hover Styling
        $button_hover_inline_style .= "color:$imagegrid_readmorebtn_hover_color;";
        $button_hover_inline_style .= "background-color:$imagegrid_readmorebtn_hover_background;";
        $button_hover_inline_style .= "border-color:$imagegrid_readmorebtn_hover_border_color;";

        // Title Typography
        $google_font_data1 = htmegavc_build_google_font_data($imagegrid_title_google_font);
        if ( 'true' == $imagegrid_title_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $imagegrid_title_google_font = htmegavc_build_google_font_style($google_font_data1);
        $title_inline_style .= htmegavc_combine_font_container($imagegrid_title_typography.';'.$imagegrid_title_google_font);

        // Description Typography
        $google_font_data1 = htmegavc_build_google_font_data($imagegrid_desciption_google_font);
        if ( 'true' == $imagegrid_desciption_use_google_font && isset( $google_font_data1['values']['font_family'] )) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_font_data1['values']['font_family'] ), 'https://fonts.googleapis.com/css?family=' . $google_font_data1['values']['font_family'] );
        }

        // concate google font properties and other properties
        $imagegrid_desciption_google_font = htmegavc_build_google_font_style($google_font_data1);
        $title_inline_style .= htmegavc_combine_font_container($imagegrid_desciption_typography.';'.$imagegrid_desciption_google_font);


        $output = '';
        $output .= '<style>';
        $output .= "
			.$unique_class .htmegavc-singleimage-grid .thumb a::before{ $wrapper_inline_style }
			.$unique_class .htmegavc-singleimage-grid .image-grid-content h2{ $title_inline_style }
			.$unique_class .htmegavc-singleimage-grid .image-grid-content{ background-color: $imagegrid_image_overlay_color; }
			.$unique_class .htmegavc-singleimage-grid .image-grid-content p,
			.$unique_class .htmegavc-singleimage-grid .image-grid-content{ $description_inline_style;}
			.$unique_class .htmegavc-singleimage-grid .image-grid-content a.read-btn{ $button_inline_style }
			.$unique_class .htmegavc-singleimage-grid .image-grid-content a.read-btn:hover{ $button_hover_inline_style }

        ";
        $output .= '</style>';

        ob_start();
        $imagegrid_list = isset($atts['imagegrid_list']) ? vc_param_group_parse_atts($atts['imagegrid_list']) : array();

        // bs column
        $columns = $imagegrid_column;
        $collumval = 'htb-col-md-4 htb-col-sm-6 htb-col-12 masonary-item';
        if( $columns != 5 ){
            $colwidth = round(12/$columns);
            $collumval = 'htb-col-md-'.$colwidth.' htb-col-sm-6 htb-col-12 masonary-item';
        }else{
            $collumval = 'custom-col-5 htb-col-sm-12 htb-col-md-6 masonary-item';
        }

        ?>

        <div class="<?php echo esc_attr( $wrapper_class ); ?>" >
		    <div class="htb-row masonry-wrap">
		        <?php
		            foreach ( $imagegrid_list as $imagegrid ):

		            	// generate link
		            	$link_url = $link_target = '';
		            	$link_arr = explode('|', $imagegrid['gridimage_btnlink']);
		            	if(count($link_arr) > 1){
		            	  $link_url  =  urldecode(str_replace('url:', '', $link_arr[0]));
		            	  $link_target  =  urldecode(str_replace('target:', '', $link_arr[2]));
		            	}

		            	// image size
		            	if(strpos($gridimage_imagesize, 'x')){
		            	    $size_arr = explode('x', $gridimage_imagesize);
		            	    $gridimage_imagesize = array($size_arr[0],$size_arr[1]);
		            	}

		            	$title = isset($imagegrid['gridimage_title']) ? $imagegrid['gridimage_title'] : '';
		            	$desc = isset($imagegrid['gridimage_description']) ? $imagegrid['gridimage_description'] : '';
		            	$btn_text = isset($imagegrid['gridimage_btntxt']) ? $imagegrid['gridimage_btntxt'] : '';
		            ?>
		                <div class="<?php echo esc_attr( $collumval);?>">
		                    <div class="<?php echo esc_attr('htmegavc-singleimage-grid htmegavc-singleimage-gridstyle-'.$imagegrid_style); ?>" >
		                        <div class="thumb">
		                            <?php
		                                if( $link_url ){
		                                    echo '<a href="'.esc_url( $link_url ).'">'. wp_get_attachment_image($imagegrid['gridimage_image'], $gridimage_imagesize) .'</a>';
		                                }else{
		                                    echo wp_get_attachment_image($imagegrid['gridimage_image'], $gridimage_imagesize); 
		                                }
		                            ?>
		                        </div>
		                        <?php if( $title ||$desc  || $link_url ): ?>
		                            <div class="image-grid-content">
		                                <div class="hover-action">
		                                    <?php 
		                                        if( $title ){
		                                            echo '<h2>'.esc_html( $title ).'</h2>';
		                                        }

		                                        if(  $desc ){
		                                            echo '<p>'.esc_html( $desc ).'</p>';
		                                        }

		                                        if ( $link_url ) {
		                                            echo sprintf( '<a class="read-btn" href="%1$s">%2$s</a>', $link_url, $btn_text );
		                                        }
		                                    ?>
		                                </div>
		                            </div>
		                        <?php endif;?>
		                    </div>
		                </div>

		            <?php
		            endforeach;
		        ?>
		    </div>
        </div>

        <?php
        $output .= ob_get_clean();
        return $output;
  }
 
    public function integrateWithVC() {
 
        /*
        Lets call vc_map function to "register" our custom shortcode within WPBakery Page Builder interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("HT Image Masonry", 'htmegavc'),
            "description" => __("Add Image Masonry to your page", 'htmegavc'),
            "base" => "htmegavc_image_masonry",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_image_grid_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(

            	// cotnent
            	array(
            	  "param_name" => "imagegrid_style",
            	  "heading" => __("Style", 'htmegavc'),
            	  "type" => "dropdown",
            	  "default_set" => '1',
            	  'value' => [
            	      __( 'Style One', 'htmegavc' )  =>  '1',
            	      __( 'Style Two', 'htmegavc' )  =>  '2',
            	      __( 'Style Three', 'htmegavc' )  =>  '3',
            	      __( 'Style Four', 'htmegavc' )  =>  '4',
            	      __( 'Style Five', 'htmegavc' )  =>  '5',
            	  ],
            	),
            	array(
            	  "param_name" => "imagegrid_column",
            	  "heading" => __("Columns", 'htmegavc'),
            	  "type" => "dropdown",
            	  'value' => [
            	      __( 'Columns 3', 'htmegavc' )  =>  '3',
            	      __( 'Column 1', 'htmegavc' )  =>  '1',
            	      __( 'Columns 2', 'htmegavc' )  =>  '2',
            	      __( 'Columns 4', 'htmegavc' )  =>  '4',
            	      __( 'Columns 5', 'htmegavc' )  =>  '5',
            	  ],
            	),
            	array(
            	    'param_name' => 'gridimage_imagesize',
            	    'heading' => __( 'Image Size', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_list',
            	    "heading" => __("Grid Image List", 'text_domainn'),
            	    'type' => 'param_group',
            	    'params' => array(
            	       array(
            	           'param_name' => 'gridimage_title',
            	           'heading' => __( 'Image Title', 'htmegavc' ),
            	           'type' => 'textfield',
            	       ),
            	       array(
            	           'param_name' => 'gridimage_description',
            	           'heading' => __( 'Description', 'htmegavc' ),
            	           'type' => 'textarea',
            	       ),
            	       array(
            	           'param_name' => 'gridimage_image',
            	           'heading' => __( 'Image', 'htmegavc' ),
            	           'type' => 'attach_image',
            	       ),
            	       array(
            	           'param_name' => 'gridimage_btntxt',
            	           'heading' => __( 'Button Text', 'htmegavc' ),
            	           'type' => 'textfield',
            	       ),
            	       array(
            	           'param_name' => 'gridimage_btnlink',
            	           'heading' => __( 'Link to this image ', 'htmegavc' ),
            	           'type' => 'vc_link',
            	           'value' => 'url:#',
            	       ),
            	    )
            	),


            	// Styling
            	array(
            	    'param_name' => 'imagegrid_image_overlay_color',
            	    'heading' => __( 'Overlay Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// Title Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Title Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_title_align',
            	    'heading' => __( 'Title Alignment', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    'value' => [
            	        __( 'Center', 'htmegavc' )  =>  'center',
            	        __( 'Left', 'htmegavc' )  =>  'left',
            	        __( 'Right', 'htmegavc' )  =>  'right',
            	        __( 'Justify', 'htmegavc' )  =>  'justify',
            	    ],
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_title_color',
            	    'heading' => __( 'Title Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_title_background',
            	    'heading' => __( 'Title BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_title_padding',
            	    'heading' => __( 'Title Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// Description Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Description Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_desciption_align',
            	    'heading' => __( 'Description Alignment', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    'value' => [
            	        __( 'Center', 'htmegavc' )  =>  'center',
            	        __( 'Left', 'htmegavc' )  =>  'left',
            	        __( 'Right', 'htmegavc' )  =>  'right',
            	        __( 'Justify', 'htmegavc' )  =>  'justify',
            	    ],
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_desciption_color',
            	    'heading' => __( 'Description Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_desciption_background',
            	    'heading' => __( 'Description BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_desciption_padding',
            	    'heading' => __( 'Description Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// Button Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Button Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_color',
            	    'heading' => __( 'Button Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_background',
            	    'heading' => __( 'Button BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	  'param_name' => 'imagegrid_readmorebtn_box_shadow',
            	  'heading' => __( 'Button Box Shadow', 'htmegavc' ),
            	  'type' => 'textfield',
            	  'description' => __( 'Example value: 0 0 10px rgba(0, 0, 0, 0.1) <a target="_blank" href="https://www.w3schools.com/cssref/css3_pr_box-shadow.asp">Learn More</a>', 'htmegavc' ),
            	  'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_padding',
            	    'heading' => __( 'Button Padding', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'CSS padding. Example: 18px 0, which stand for padding-top and padding-bottom is 18px', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_border_width',
            	    'heading' => __( 'Button Border width', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'CSS Border width. Example: 2px, which stand for border-width:2px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_border_radius',
            	    'heading' => __( 'Button Border style', 'htmegavc' ),
            	    'type' => 'dropdown',
            	    "default_set" => '1',
            	    'value' => [
            	        __( 'None', 'htmegavc' )  =>  'none',
            	        __( 'Solid', 'htmegavc' )  =>  'solid',
            	        __( 'Double', 'htmegavc' )  =>  'double',
            	        __( 'Dotted', 'htmegavc' )  =>  'dotted',
            	        __( 'Dashed', 'htmegavc' )  =>  'dashed',
            	        __( 'Groove', 'htmegavc' )  =>  'groove',
            	    ],
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_border_style',
            	    'heading' => __( 'Button Border Radius', 'htmegavc' ),
            	    'type' => 'textfield',
            	    'description' => __( 'CSS Border Radius. Example: 5px, which stand for border-radius:5px;', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_border_color',
            	    'heading' => __( 'Button Border color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'CSS Border color of blockquote.', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),

            	// Button Hover Styling
            	array(
            	    "param_name" => "custom_heading",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Button Hover Styling","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_hover_color',
            	    'heading' => __( 'Button Hover Text Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_hover_background',
            	    'heading' => __( 'Button Hover BG Color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),
            	array(
            	    'param_name' => 'imagegrid_readmorebtn_hover_border_color',
            	    'heading' => __( 'Button Hover Border color', 'htmegavc' ),
            	    'type' => 'colorpicker',
            	    'description' => __( 'CSS Border color of blockquote.', 'htmegavc' ),
            	    'group'  => __( 'Styling', 'htmegavc' ),
            	),


            	// Typography
            	// Title Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Title Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'imagegrid_title_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'imagegrid_title_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'imagegrid_title_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'imagegrid_title_typography',
            	  'type' => 'font_container',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_size',
            	      'line_height',
            	      'text-align',
            	      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
            	      'color_description' => __( 'Select Heading Color.', 'htmegavc' ),
            	    ),
            	  ),
            	),

            	// Description Typography
            	array(
            	    "param_name" => "package_typograpy",
            	    "type" => "htmegavc_param_heading",
            	    "text" => __("Description Typography","htmegavc"),
            	    "class" => "htmegavc-param-heading",
            	    'edit_field_class' => 'vc_column vc_col-sm-12',
            	    'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'checkbox',
            	  'heading' => __( 'Use google Font?', 'htmegavc' ),
            	  'param_name' => 'imagegrid_desciption_use_google_font',
            	  'description' => __( 'Use font family from google font.', 'htmegavc' ),
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	),
            	array(
            	  'type' => 'google_fonts',
            	  'param_name' => 'imagegrid_desciption_google_font',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_family_description' => __( 'Select font family.', 'htmegavc' ),
            	      'font_style_description' => __( 'Select font styling.', 'htmegavc' ),
            	    ),
            	  ),
            	  'dependency' =>[
            	      'element' => 'imagegrid_desciption_use_google_font',
            	      'value' => array( 'true' ),
            	  ],
            	),
            	array(
            	  'param_name' => 'imagegrid_desciption_typography',
            	  'type' => 'font_container',
            	  'group'  => __( 'Typography', 'htmegavc' ),
            	  'settings' => array(
            	    'fields' => array(
            	      'font_size',
            	      'line_height',
            	      'text-align',
            	      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
            	      'color_description' => __( 'Select Heading Color.', 'htmegavc' ),
            	    ),
            	  ),
            	),

                // extra class
                array(
                    'param_name' => 'custom_class',
                    'heading' => __( 'Extra class name', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Style this element differently - add a class name and refer to it in custom CSS.', 'htmegavc' ),
                ),
                array(
                  "param_name" => "wrapper_css",
                  "heading" => __( "Wrapper Styling", "htmevavc" ),
                  "type" => "css_editor",
                  'group'  => __( 'Wrapper Styling', 'htmegavc' ),
              ),
            )
        ) );
    }

}

// Finally initialize code
new Htmegavc_Image_Masonary();