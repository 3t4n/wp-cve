<?php

// don't load directly
if (!defined('ABSPATH')) die('-1');

class Htmegavc_Pricing_Table{
    function __construct() {

        // We safely integrate with VC with this hook
        add_action( 'vc_after_init', array( $this, 'integrateWithVC' ) );

        // creating a shortcode addon
        add_shortcode( 'htmegavc_pricing_table', array( $this, 'render_shortcode' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'htmegavc_pricing_table', plugins_url( '/css/pricing-table.css', __FILE__ ));

      wp_enqueue_style( 'htmegavc_pricing_table' );
    }
 

    public function render_shortcode( $atts, $content = null ) {

    	extract(shortcode_atts(array(
            'htmega_pricing_style' => '1',
            'pricing_title' => __('PERSONAL', 'htmegavc'),
            'pricing_icon' => '',
            'htmega_heighlight_pricing_table' => '',
            'htmega_currency_symbol' => '&#36;',
            'htmega_price' => '99',
            'htmega_period' => 'month',
            'htmega_button' => '',
            'custom_class' => '',
            'pricing_table_background_color' => '',
            'pricing_table_header_background' => '',
            'pricing_table_header_background_image' => '',
            'pricing_table_header_background_color' => '',
            'pricing_title_bg_color' => '',
            'pricing_title_typography' => '',
            'pricing_header_price_typography' => '',
            'htmega_button_bg_color' => '',
            'htmega_button_text_color' => '',
            'htmega_button_border_color' => '',
            'htmega_button_hover_bg_color' => '',
            'htmega_button_hover_text_color' => '',
            'htmega_button_hover_border_color' => '',
            'htmega_button_typography' => '', 
            'htmega_features_list' => '', 
            'wrapper_css' => '', 
    	),$atts));

		// wrapper class
		$wrapper_class_arr = array();

		$unique_class = uniqid('htmega_pricing_');
		$wrapper_class_arr[] = $unique_class;
		$wrapper_class_arr[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $wrapper_css, ' ' ), 'htmegavc_pricing_table', $atts );
		$wrapper_class_arr[] =  $custom_class;

		// add wrapper class
		$wrapper_class_arr[] =  'htmegavc-pricing htmegavc-pricing-panel ';
		$wrapper_class_arr[] =  'htmegavc-pricing-style-'. $htmega_pricing_style;
		$wrapper_class_arr[] =  $htmega_heighlight_pricing_table == 'true' ? ' htmegavc-pricing-heighlight' : '';;

		// join all wrapper class
		$wrapper_class = implode(' ', $wrapper_class_arr);

		// Styling
		$header_bg_color = $header_bg_image  = '';
		if($pricing_table_header_background == 'use_img'){
			if($pricing_table_header_background_image){
			  $header_bg_image =  wp_get_attachment_image_src($pricing_table_header_background_image , 'large');
			  $header_bg_image =  $header_bg_image[0];
			} else {
			  $header_bg_image = plugins_url('/images/pricing-1-bg.png', __FILE__);
			}
		} else{
			$header_bg_color = $pricing_table_header_background_color;
		}


		$header_inline_style = "
			background-image:url($header_bg_image);
			background-color: $header_bg_color;
		";
		$title_inline_style = "
			background-color: $pricing_title_bg_color;
		";
		$price_inline_style = "
			background-image:unset;
		";
		$button_inline_style = "
			background-color: $htmega_button_bg_color;
			color: $htmega_button_text_color;
			border-color: $htmega_button_border_color;
		";
		$button_hover_inline_style = "
			background-color: $htmega_button_hover_bg_color;
			color: $htmega_button_hover_text_color;
			border-color: $htmega_button_hover_border_color;
		";

		// Typography
		$title_inline_style .= htmegavc_combine_font_container($pricing_title_typography);
		$price_inline_style .= htmegavc_combine_font_container($pricing_header_price_typography);


		$output = '';
		$output .= '<style>';
		
		if($htmega_pricing_style != '7'){
			$output .= "
				.$unique_class.htmegavc-pricing .htmegavc-pricing-heading{ $header_inline_style }
			";
		} else {
			$output .= "
				.$unique_class.htmegavc-pricing .htmegavc-pricing-heading .price{ $header_inline_style }
			";
		}

		$output .= "
			.$unique_class.htmegavc-pricing .htmegavc-pricing-heading .title h2{ $title_inline_style }
			.$unique_class.htmegavc-pricing .htmegavc-pricing-heading .price h4{ $price_inline_style }

			.$unique_class.htmegavc-pricing .htmegavc-pricing-footer .price_btn{ $button_inline_style }
			.$unique_class.htmegavc-pricing .htmegavc-pricing-footer .price_btn:hover{ $button_hover_inline_style }
		";
		$output .= '</style>';

		ob_start();
	?>

		<div class="<?php echo esc_attr( $wrapper_class ); ?>" style="background-color:<?php echo esc_attr($pricing_table_background_color); ?>">

			<?php if($htmega_pricing_style == '7'): ?>

				<div class="htmegavc-pricing-heading">
				    <div class="price">
				        <h4><span class="pricing_new"><sub><?php echo esc_html($htmega_currency_symbol); ?></sub><?php echo esc_html($htmega_price); ?></span> <span class="separator">/</span> <span class="period-txt"><?php echo esc_html($htmega_period); ?></span></h4>
				    </div>
				    <div class="title">
				        <h2><?php echo esc_html($pricing_title); ?></h2>
				    </div>
				</div>
				<div class="htmegavc-pricing-body">
				    <ul class="htmegavc-features">
				        <?php
				         foreach ( vc_param_group_parse_atts($atts['htmega_features_list'] ) as $item ) :
				            $title = isset($item['htmega_features_name']) ? $item['htmega_features_name'] : '';

				            $repeater_style = htmegavc_combine_font_container(isset($item['htmega_features_name_typography']) ? $item['htmega_features_name_typography'] : '');
				            $repeater_style .= (isset($item['use_strikethrough']) && $item['use_strikethrough'] == 'yes') ? 'text-decoration: line-through;' : '';
				          ?>
				              <li style="<?php echo esc_attr($repeater_style); ?>"><?php echo esc_html($title); ?></li>
				          <?php endforeach; ?>
				    </ul>
				</div>
				<div class="htmegavc-pricing-footer">
				    <?php
				        //button
				        $button_arr = explode('|', $htmega_button);

				        if(count($button_arr) > 1){
				          $button_url         = urldecode($button_arr[0]);
				          $button_text        = explode(':', $button_arr[1]);
				          $button_text        = count($button_text) > 1  ? $button_text : array( __('SIGN UP', 'htmegavc'));
				          $button_target  = explode(':', urldecode($button_arr[2]));

				          $button_text = end($button_text);
				          $button_target = end($button_target);

				          if($button_text){
				            echo '<a class="price_btn" href="'. esc_url($button_url) .'" target="'. esc_attr($button_target) .'">'. esc_html(urldecode($button_text)) .'</a>';
				          }
				        }
				    ?>
				</div>

			<?php elseif($htmega_pricing_style == '6'): ?>

				<div class="htmegavc-pricing-heading">
				    <div class="title">
				        <h2><?php echo esc_html($pricing_title); ?></h2>
				    </div>
				    <div class="price">
				        <h4><span class="pricing_new"><sub><?php echo esc_html($htmega_currency_symbol); ?></sub><?php echo esc_html($htmega_price); ?></span> <span class="separator">/</span> <span class="period-txt"><?php echo esc_html($htmega_period); ?></span></h4>
				    </div>
				</div>
				<div class="htmegavc-pricing-body">
				    <ul class="htmegavc-features">
				        <?php foreach ( vc_param_group_parse_atts($atts['htmega_features_list'] ) as $item ) :
				            $title = isset($item['htmega_features_name']) ? $item['htmega_features_name'] : '';

				            $repeater_style = htmegavc_combine_font_container(isset($item['htmega_features_name_typography']) ? $item['htmega_features_name_typography'] : '');
				            $repeater_style .= (isset($item['use_strikethrough']) && $item['use_strikethrough'] == 'yes') ? 'text-decoration: line-through;' : '';
				          ?>
				              <li style="<?php echo esc_attr($repeater_style); ?>"><?php echo esc_html($title); ?></li>
				          <?php endforeach; ?>
				    </ul>
				</div>
				<div class="htmegavc-pricing-footer">
				    <?php
				        //button

   				        $button_arr = explode('|', $htmega_button);
   				        if(count($button_arr) > 1){
   				          $button_url         = urldecode($button_arr[0]);
   				          $button_text        = explode(':', $button_arr[1]);
   				          $button_text        = count($button_text) > 1  ? $button_text : array( __('SIGN UP', 'htmegavc'));
   				          $button_target  = explode(':', urldecode($button_arr[2]));

   				          $button_text = end($button_text);
   				          $button_target = end($button_target);

   				          if($button_text){
   				            echo '<a class="price_btn" href="'. esc_url($button_url) .'" target="'. esc_attr($button_target) .'">'. esc_html(urldecode($button_text)) .'</a>';
   				          }
   				        }
				    ?>
				</div>

			<?php elseif($htmega_pricing_style == '5'): ?>

				<div class="htmegavc-pricing-heading">
				    <div class="title">
				         <h2><?php echo esc_html($pricing_title); ?></h2>
				    </div>
				    <div class="price">
				        <h4><span class="pricing_new"><sub><?php echo esc_html($htmega_currency_symbol); ?></sub><?php echo esc_html($htmega_price); ?></span></h4>
				    </div>
				</div>
				<div class="htmegavc-pricing-body">
				    <ul class="htmegavc-features">
				        <?php foreach ( vc_param_group_parse_atts($atts['htmega_features_list'] ) as $item ) :
				            $title = isset($item['htmega_features_name']) ? $item['htmega_features_name'] : '';

				            $repeater_style = htmegavc_combine_font_container(isset($item['htmega_features_name_typography']) ? $item['htmega_features_name_typography'] : '');
				            $repeater_style .= (isset($item['use_strikethrough']) && $item['use_strikethrough'] == 'yes') ? 'text-decoration: line-through;' : '';
				          ?>
				              <li style="<?php echo esc_attr($repeater_style); ?>"><?php echo esc_html($title); ?></li>
				          <?php endforeach; ?>
				    </ul>

				    <?php
				        //button
   				        $button_arr = explode('|', $htmega_button);

   				        if(count($button_arr) > 1){
   				          $button_url         = urldecode($button_arr[0]);
   				          $button_text        = explode(':', $button_arr[1]);
   				          $button_text        = count($button_text) > 1  ? $button_text : array( __('SIGN UP', 'htmegavc'));
   				          $button_target  = explode(':', urldecode($button_arr[2]));

   				          $button_text = end($button_text);
   				          $button_target = end($button_target);

   				          if($button_text){
   				            echo ' <a class="price_btn" href="'. esc_url(urldecode($button_url)) .'"><span>'. esc_html( urldecode($button_text) ) .'</span></a>';
   				          }
   				        }
				    ?>
				            
				</div>

			<?php elseif($htmega_pricing_style == '4'): ?>
				
				<div class="htmegavc-pricing-heading">
				    <div class="title">
				        <h2><?php echo esc_html($pricing_title); ?></h2>
				    </div>
				    <div class="price">
				      <h4><span class="pricing_new"><sub><?php echo esc_html($htmega_currency_symbol); ?></sub><?php echo esc_html($htmega_price); ?></span></h4>
				    </div>
				</div>

				<div class="htmegavc-pricing-body">
				    <ul class="htmegavc-features">
				      <?php foreach ( vc_param_group_parse_atts($atts['htmega_features_list'] ) as $item ) :
				        $title = isset($item['htmega_features_name']) ? $item['htmega_features_name'] : '';

				        $repeater_style = htmegavc_combine_font_container(isset($item['htmega_features_name_typography']) ? $item['htmega_features_name_typography'] : $item['htmega_features_name_typography']);
				        $repeater_style .= (isset($item['use_strikethrough']) && $item['use_strikethrough'] == 'yes') ? 'text-decoration: line-through;' : '';
				      ?>
				          <li style="<?php echo esc_attr($repeater_style); ?>"><?php echo esc_html($title); ?></li>
				      <?php endforeach; ?>
				    </ul>
				</div>

				<div class="htmegavc-pricing-footer">
				    <?php
				        //button
   				        $button_arr = explode('|', $htmega_button);

   				        if(count($button_arr) > 1){
   				          $button_url         = urldecode($button_arr[0]);
   				          $button_text        = explode(':', $button_arr[1]);
   				          $button_text        = count($button_text) > 1  ? $button_text : array( __('SIGN UP', 'htmegavc'));
   				          $button_target  = explode(':', urldecode($button_arr[2]));

   				          $button_text = end($button_text);
   				          $button_target = end($button_target);

   				          if($button_text){
   				            echo '<a class="price_btn" href="'. esc_url($button_url) .'" target="'. esc_attr($button_target) .'">'. esc_html(urldecode($button_text)) .'</a>';
   				          }
   				        }
				    ?>
				</div>

			<?php elseif($htmega_pricing_style == '3'): ?>

				<div class="htmegavc-pricing-heading">
				    <div class="price" style="background-image:url(<?php echo esc_attr($header_bg_image); ?>); background-color:<?php echo esc_attr($header_bg_color); ?>">
				        <h4><span class="pricing_new"><sub><?php echo esc_html($htmega_currency_symbol); ?></sub><?php echo esc_html($htmega_price); ?></span> <span class="separator">/</span> <span><span><?php echo esc_html($htmega_period); ?></span></h4>
				    </div>
				    <div class="title">
				        <h2><?php echo esc_html($pricing_title); ?></h2>
				    </div>
				</div>

				<div class="htmegavc-pricing-body">
				    <ul class="htmegavc-features">
				      <?php foreach ( vc_param_group_parse_atts($atts['htmega_features_list'] ) as $item ) :
				        $title = isset($item['htmega_features_name']) ? $item['htmega_features_name'] : '';

				        $repeater_style = htmegavc_combine_font_container(isset($item['htmega_features_name_typography']) ? $item['htmega_features_name_typography'] : '');
				        $repeater_style .= (isset($item['use_strikethrough']) && $item['use_strikethrough'] == 'yes') ? 'text-decoration: line-through;' : '';
				      ?>
				          <li style="<?php echo esc_attr($repeater_style); ?>"><?php echo esc_html($title); ?></li>
				      <?php endforeach; ?>
				    </ul>
				</div>

				<div class="htmegavc-pricing-footer">
				    <?php
				        //button
				        $button_arr = explode('|', $htmega_button);

				        if(count($button_arr) > 1){
				          $button_url         = urldecode($button_arr[0]);
				          $button_text        = explode(':', $button_arr[1]);
				          $button_text        = count($button_text) > 1  ? $button_text : array( __('SIGN UP', 'htmegavc'));
				          $button_target  = explode(':', urldecode($button_arr[2]));

				          $button_text = end($button_text);
				          $button_target = end($button_target);

				          if($button_text){
				            echo '<a class="price_btn" href="'. esc_url($button_url) .'" target="'. esc_attr($button_target) .'">'. esc_html(urldecode($button_text)) .'</a>';
				          }
				        }
				    ?>
				</div>

			<?php elseif($htmega_pricing_style == '2'): ?>
				<div class="htmegavc-pricing-heading">
				    <div class="icon">
				        <?php 
				          if($pricing_icon){
				            echo wp_get_attachment_image($pricing_icon , 'large');
				          } else {
				            echo '<img src="'. plugins_url('/images/pricing-2-icon.png', __FILE__) .'" />';
				          }
				        ?>
				    </div>

				    <div class="title">
				        <h2><?php echo esc_html($pricing_title); ?></h2>
				    </div>
				    <div class="price">
				        <h4><span class="pricing_new"><sub><?php echo esc_html($htmega_currency_symbol); ?></sub><?php echo esc_html($htmega_price); ?></span><span></span></h4>
				    </div>
				</div>
				<div class="htmegavc-pricing-body">
			    	<div class="htmegavc-pricing-body">
			    	    <ul class="htmegavc-features">
			                <?php foreach ( vc_param_group_parse_atts($atts['htmega_features_list'] ) as $item ) :
			                  $title = isset($item['htmega_features_name']) ? $item['htmega_features_name'] : '';
			                  $repeater_style = htmegavc_combine_font_container(isset($item['htmega_features_name_typography']) ? $item['htmega_features_name_typography'] : '');
			                  $repeater_style .= (isset($item['use_strikethrough']) && $item['use_strikethrough'] == 'yes') ? 'text-decoration: line-through;' : '';
			                ?>
			                    <li style="<?php echo esc_attr($repeater_style); ?>"><?php echo esc_html($title); ?></li>
			                <?php endforeach; ?>
			    	    </ul>
			    	</div>
				</div>
				<div class="htmegavc-pricing-footer">
				    <?php
				        //button
				        $button_arr = explode('|', $htmega_button);

				        if(count($button_arr) > 1){
				          $button_url         = urldecode($button_arr[0]);
				          $button_text        = explode(':', $button_arr[1]);
				          $button_text        = count($button_text) > 1  ? $button_text : array( __('SIGN UP', 'htmegavc'));
				          $button_target  = explode(':', urldecode($button_arr[2]));

				          $button_text = end($button_text);
				          $button_target = end($button_target);

				          if($button_text){
				            echo '<a class="price_btn" href="'. esc_url($button_url) .'" target="'. esc_attr($button_target) .'">'. esc_html(urldecode($button_text)) .'</a>';
				          }
				        }
				    ?>
				</div>
			<?php else: ?>

				<div class="htmegavc-pricing-heading">
				    <div class="title">
				        <h2><?php echo esc_html($pricing_title); ?></h2>
				    </div>
				    <div class="price">
				        <h4><span class="pricing_new"><sub><?php echo esc_html($htmega_currency_symbol); ?></sub><?php echo esc_html($htmega_price); ?></span> <span class="separator">/</span> <span><?php echo esc_html($htmega_period); ?></span></h4>
				    </div>
				</div>

				<div class="htmegavc-pricing-body">
				    <ul class="htmegavc-features">
			            <?php foreach ( vc_param_group_parse_atts($atts['htmega_features_list'] ) as $item ) :
			              $title = isset($item['htmega_features_name']) ? $item['htmega_features_name'] : '';
			              $repeater_style = htmegavc_combine_font_container(isset($item['htmega_features_name_typography']) ? $item['htmega_features_name_typography'] : '');
			              $repeater_style .= (isset($item['use_strikethrough']) && $item['use_strikethrough'] == 'yes') ? 'text-decoration: line-through;' : '';
			            ?>
			                <li style="<?php echo esc_attr($repeater_style); ?>"><?php echo esc_html($title); ?></li>
			            <?php endforeach; ?>
				    </ul>
				</div>

				<div class="htmegavc-pricing-footer">
				    <?php
				        //button
				        $button_arr = explode('|', $htmega_button);

				        if(count($button_arr) > 1){
				          $button_url         = urldecode($button_arr[0]);
				          $button_text        = explode(':', $button_arr[1]);
				          $button_text        = count($button_text) > 1  ? $button_text : array( __('SIGN UP', 'htmegavc'));
				          $button_target  = explode(':', urldecode($button_arr[2]));

				          $button_text = end($button_text);
				          $button_target = end($button_target);

				          if($button_text){
				            echo '<a class="price_btn" href="'. esc_url($button_url) .'" target="'. esc_attr($button_target) .'">'. esc_html(urldecode($button_text)) .'</a>';
				          }
				        }
				    ?>
				</div>

			<?php endif; ?>

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
            "name" => __("HT Pricing Table", 'htmegavc'),
            "description" => __("Add Pricing table to your page", 'htmegavc'),
            "base" => "htmegavc_pricing_table",
            "class" => "",
            "controls" => "full",
            "icon" => 'htmegavc_pricing_table_icon', // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('HT Mega Addons', 'htmegavc'),
            "params" => array(
                array(
                  "param_name" => "htmega_pricing_style",
                  "heading" => __("Style", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => [
                      __( 'Style One', 'htmegavc' )  =>  '1',
                      __( 'Style Two', 'htmegavc' )  =>  '2',
                      __( 'Style Three', 'htmegavc' )  =>  '3',
                      __( 'Style Four', 'htmegavc' )  =>  '4',
                      __( 'Style Five', 'htmegavc' )  =>  '5',
                      __( 'Style Six', 'htmegavc' )  =>  '6',
                      __( 'Style Seven', 'htmegavc' )  =>  '7',
                  ],
                ),
                array(
                    "param_name" => "pricing_title",
                    "heading" => __( "Pricing Title", "htmegavc" ),
                    "type" => "textfield",
                ),
                array(
                    "param_name" => "pricing_icon",
                    "heading" => __( "Pricing Icon", "htmegavc" ),
                    "type" => "attach_image",
                    'dependency' =>[
                        'element' => 'htmega_pricing_style',
                        'value' => array( '2'),
                    ],
                ),
                array(
                  'param_name' => 'htmega_heighlight_pricing_table',
                  'heading' => __( 'Hilight this?', 'htmegavc' ),
                  'type' => 'checkbox',
                  'dependency' =>[
                      'element' => 'htmega_pricing_style',
                      'value' => array( '2', '4', '6' ),
                  ],
                ),
                array(
                    "param_name" => "htmega_currency_symbol",
                    "heading" => __( "Currency Symbol", "htmegavc" ),
                    "type" => "textfield",
                ),
                array(
                    "param_name" => "htmega_price",
                    "heading" => __( "Price", "htmegavc" ),
                    "type" => "textfield",
                ),
                array(
                    "param_name" => "htmega_period",
                    "heading" => __( "Period", "htmegavc" ),
                    "type" => "textfield",
                    'dependency' =>[
                        'element' => 'htmega_pricing_style',
                        'value' => array( '1', '3', '6', '7' ),
                    ],
                ),

                // repeater
                array(
                  'type' => 'param_group',
                  'heading' => __( 'Add Feature Lists', 'htmegavc' ),
                  'param_name' => 'htmega_features_list',
                  'group'  => __( 'Feature List', 'htmegavc' ),
                  'value' => urlencode( json_encode (array(
                      array(
                          'htmega_features_name'         => __('One Website','htmegavc'),
                      ),
                      array(
                          'htmega_features_name'         => __('Five User','htmegavc'),
                      ),
                      array(
                          'htmega_features_name'         => __('100 GB Bandwidth','htmegavc'),
                      ),
                      array(
                          'htmega_features_name'         => __('2 GB Storage','htmegavc'),
                      ),
                      array(
                          'htmega_features_name'         => __('24x7 Support','htmegavc'),
                      ),
                   ))),
                  'params' => array(
                    array(
                      "param_name" => "htmega_features_name",
                      "heading" => __( "Feature Name", "htmegavc" ),
                      "type" => "textfield",
                      "value" => "One Website",
                    ),
                    array(
                      "param_name" => "use_strikethrough",
                      "heading" => __("Use Strikethrough", 'my_text_domain'),
                      "type" => "dropdown",
                      'value' => [
                          __( 'No', 'my_text_domain' )  =>  'no',
                          __( 'Yes', 'my_text_domain' )  =>  'yes',
                      ],
                    ),

                    array(
                      'param_name' => 'htmega_features_name_typography',
                      'type' => 'font_container',
                      'group'  => __( 'Styling', 'htmegavc' ),
                      'settings' => array(
                        'fields' => array(
                          'color',
                          'color_description' => __( 'Select heading color.', 'htmegavc' ),
                        ),
                      ),
                    ),
                   ),
                ),
                // repeater end
                array(
                    'param_name' => 'htmega_button',
                    'heading' => __( 'Button Text / URL (Link)', 'htmegavc' ),
                    'type' => 'vc_link',
                    'value' => 'url:#',
                    'description' => __( 'Add link to button', 'htmegavc' ),
                ),
                array(
                    'param_name' => 'custom_class',
                    'heading' => __( 'Extra class name', 'htmegavc' ),
                    'type' => 'textfield',
                    'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'htmegavc' ),
                ),

                // styling tab
                array(
                  "param_name" => "pricing_table_background_color",
                  'heading' => __( 'Pricing Table BG Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "pricing_table_header_background",
                  "heading" => __("Pricing Table Header Background", 'htmegavc'),
                  "type" => "dropdown",
                  "default_set" => '1',
                  'value' => [
                      __( 'Default', 'htmegavc' )  =>  'none',
                      __( 'Use Image', 'htmegavc' )  =>  'use_img',
                      __( 'Use Color', 'htmegavc' )  =>  'use_color',
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "pricing_table_header_background_image",
                  'heading' => __( 'BG image', 'htmegavc' ),
                  'type' => 'attach_image',
                  'value' => plugins_url('/images/pricing-1-bg.png', __FILE__),
                  'dependency' => [
                      'element' => 'pricing_table_header_background',
                      'value' => array( 'use_img' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "pricing_table_header_background_color",
                  'heading' => __( 'BG Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'dependency' => [
                      'element' => 'pricing_table_header_background',
                      'value' => array( 'use_color' ),
                  ],
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),

                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Pricing Title Options","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "pricing_title_bg_color",
                  'heading' => __( 'Title BG Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'pricing_title_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family',
                      'font_size',
                      'line_height',
                      'color',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                      'color_description' => __( 'Select heading color.', 'htmegavc' ),
                    ),
                  ),
                ),
                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Pricing header Options","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'pricing_header_price_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family',
                      'font_size',
                      'line_height',
                      'color',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                      'color_description' => __( 'Select heading color.', 'htmegavc' ),
                    ),
                  ),
                ),

                array(
                    "type" => "htmegavc_param_heading",
                    "text" => __("Button Options","htmegavc"),
                    "param_name" => "package_typograpy",
                    "class" => "htmegavc-param-heading",
                    'edit_field_class' => 'vc_column vc_col-sm-12',
                    'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "htmega_button_bg_color",
                  'heading' => __( 'Button BG Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "htmega_button_text_color",
                  'heading' => __( 'Button Text Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "htmega_button_border_color",
                  'heading' => __( 'Button Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "htmega_button_hover_bg_color",
                  'heading' => __( 'Button Hover Bg Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "htmega_button_hover_text_color",
                  'heading' => __( 'Button Hover Text Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  "param_name" => "htmega_button_hover_border_color",
                  'heading' => __( 'Button Hover Border Color', 'htmegavc' ),
                  'type' => 'colorpicker',
                  'group'  => __( 'Styling', 'htmegavc' ),
                ),
                array(
                  'param_name' => 'htmega_button_typography',
                  'type' => 'font_container',
                  'group'  => __( 'Styling', 'htmegavc' ),
                  'settings' => array(
                    'fields' => array(
                      'font_family',
                      'font_size',
                      'line_height',
                      'color',
                      'font_size_description' => __( 'Enter font size. Eg: 12px', 'htmegavc' ),
                      'line_height_description' => __( 'Enter line height. Eg: 25px', 'htmegavc' ),
                      'color_description' => __( 'Select heading color.', 'htmegavc' ),
                    ),
                  ),
                ),



                array(
                  "type" => "css_editor",
                  "heading" => __( "Wrapper Styling", "htmegavc" ),
                  "param_name" => "wrapper_css",
              ),
            )
        ) );
    }
}

// Finally initialize code
new Htmegavc_Pricing_Table();