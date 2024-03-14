<?php
/**
 * Read all custom styling from our database
 */
function dh_ptp_simple_flat_css($id, $meta)
{

    // General Settings: Design
    $design1_no_spacing_between_columns = isset($meta['design1-no-spacing-between-columns'])?true:false;
    $design1_shake_buttons_on_hover = isset($meta['shake-buttons-on-hover'])?$meta['shake-buttons-on-hover']:0;
    $design1_rounded_corner_width = isset($meta['rounded-corners'])?$meta['rounded-corners']:'0px';
    $design1_hover_effects = isset($meta['design1-hover-effects'])?$meta['design1-hover-effects']:0;
    
    // General Settings: Font Sizes
    $design1_most_popular_font_size = isset($meta['most-popular-font-size'])?$meta['most-popular-font-size']:0.9;
    $design1_most_popular_font_size_type = isset($meta['most-popular-font-size-type'])?$meta['most-popular-font-size-type']:"em";
    $design1_plan_name_font_size = isset($meta['plan-name-font-size'])?$meta['plan-name-font-size']:1;
    $design1_plan_name_font_size_type = isset($meta['plan-name-font-size-type'])?$meta['plan-name-font-size-type']:"em";
    $design1_price_font_size = isset($meta['price-font-size'])?$meta['price-font-size']:1.25;
    $design1_price_font_size_type = isset($meta['price-font-size-type'])?$meta['price-font-size-type']:"em";
    $design1_bullet_item_font_size = isset($meta['bullet-item-font-size'])?$meta['bullet-item-font-size']:0.875;
    $design1_bullet_item_font_size_type = isset($meta['bullet-item-font-size-type'])?$meta['bullet-item-font-size-type']:"em";
    $design1_button_font_size = isset($meta['button-font-size'])?$meta['button-font-size']:1;
    $design1_button_font_size_type = isset($meta['button-font-size-type'])?$meta['button-font-size-type']:"em";
    
    // Unfeatured Columns: Background Colors
    $design1_unfeatured_border_color = isset($meta['unfeatured-border-color'])?$meta['unfeatured-border-color']:'#dddddd';
    $design1_title_area_background_color = isset($meta['title-area-background-color'])?$meta['title-area-background-color']:'#dddddd';
    $design1_pricing_background_color = isset($meta['pricing-background-color'])?$meta['pricing-background-color']:'#eeeeee';
    $design1_unfeatured_button_area_background_color = isset($meta['unfeatured-button-area-background-color'])?$meta['unfeatured-button-area-background-color']:'#eeeeee';
    $design1_featured_background_color = isset($meta['featured-background-color'])?$meta['featured-background-color']:'#ffffff';
    
    // Unfeatured Columns: Font Colors
    $design1_title_area_font_color = isset($meta['title-area-font-color'])?$meta['title-area-font-color']:'#333333';
    $design1_pricing_area_font_color = isset($meta['pricing-area-font-color'])?$meta['pricing-area-font-color']:'#333333';
    $design1_featured_font_color = isset($meta['featured-font-color'])?$meta['featured-font-color']:'#333333';
    
    // Unfeatured Columns: Button Colors
    $design1_button_color = isset($meta['button-color'])?$meta['button-color']:'#e74c3c';
    $design1_button_border_color = isset($meta['button-border-color'])?$meta['button-border-color']:'#c0392b';
    $design1_button_hover_color = isset($meta['button-hover-color'])?$meta['button-hover-color']:'#c0392b';
    $design1_button_font_color = isset($meta['button-font-color'])?$meta['button-font-color']:'#ffffff';
    
    // Featured Column: Background Colors
    $design1_featured_border_color = isset($meta['featured-border-color'])?$meta['featured-border-color']:'#dddddd';
    $design1_featured_title_background_color = isset($meta['featured-title-area-background-color'])?$meta['featured-title-area-background-color']:'#dddddd';
    $design1_featured_pricing_background_color = isset($meta['featured-pricing-background-color'])?$meta['featured-pricing-background-color']:'#eeeeee';
    $design1_featured_feature_background_color = isset($meta['featured-feature-background-color'])?$meta['featured-feature-background-color']:'#ffffff';
    $design1_featured_feature_label_border_color = isset($meta['featured-feature-label-border-color'])?$meta['featured-feature-label-border-color']:'#7f8c8d';
    $design1_most_popular_area_background_color = (isset($meta['most-popular-area-background-color']))?$meta['most-popular-area-background-color']:'#7f8c8d';
    $design1_featured_button_area_background_color = isset($meta['featured-button-area-background-color'])?$meta['featured-button-area-background-color']:'#eeeeee';
    
    // Featured Column: Font Colors
    $design1_featured_title_font_color = isset($meta['featured-title-area-font-color'])?$meta['featured-title-area-font-color']:'#333333';
    $design1_featured_pricing_font_color = isset($meta['featured-pricing-area-font-color'])?$meta['featured-pricing-area-font-color']:'#333333';
    $design1_featured_feature_color = isset($meta['featured-feature-font-color'])?$meta['featured-feature-font-color']:'#333333';
    $design1_most_popular_font_color = isset($meta['most-popular-font-color'])?$meta['most-popular-font-color']:'#ffffff';
    
    // Featured Column: Button Colors
    $design1_featured_button_color = isset($meta['featured-button-color'])?$meta['featured-button-color']:'#3498db';
    $design1_featured_button_border_color = isset($meta['featured-button-border-color'])?$meta['featured-button-border-color']:'#2980b9';
    $design1_featured_button_hover_color = isset($meta['featured-button-hover-color'])?$meta['featured-button-hover-color']:'#2980b9';
    $design1_featured_button_font_color = isset($meta['featured-button-font-color'])?$meta['featured-button-font-color']:'#ffffff';
      
    ?>
    
    /* simple flat #<?php echo $id;?> */
    #ptp-<?php echo $id ?>  {
        border-color: <?php echo esc_attr( $design1_unfeatured_border_color ); ?>;
        padding: 0px;
        margin-left: 0px;
        margin-right: 0px;
    }
    #ptp-<?php echo $id ?> div.ptp-item-container  {
        border-radius: <?php echo esc_attr( $design1_rounded_corner_width ); ?>; 
    }
    
    #ptp-<?php echo $id ?> div.ptp-item-container div {
        margin: 0px; 
    }
    
    #ptp-<?php echo $id ?> .ptp-highlight div.ptp-item-container{
        border-color: <?php echo esc_attr( $design1_featured_border_color ); ?>;
    }
    #ptp-<?php echo $id ?> div.ptp-plan {
        border-top-right-radius: <?php echo esc_attr( $design1_rounded_corner_width ); ?>;
        border-top-left-radius: <?php echo esc_attr( $design1_rounded_corner_width ); ?>;
        font-size: <?php echo esc_attr( $design1_plan_name_font_size ) . esc_attr( $design1_plan_name_font_size_type ); ?>;
        border-color: <?php echo esc_attr( $design1_title_area_background_color ); ?>;
        background-color: <?php echo esc_attr( $design1_title_area_background_color ); ?>;
        color: <?php echo esc_attr( $design1_title_area_font_color ); ?>;
        padding: 0.9375em 1.25em;
    }
    #ptp-<?php echo $id ?> div.ptp-plan .has-tip {
        border-bottom: dotted 1px <?php echo $design1_title_area_font_color; ?>;
        color: <?php echo $design1_title_area_font_color; ?>;
    }
    #ptp-<?php echo $id ?> .ptp-highlight div.ptp-plan{
        border-color: <?php echo esc_attr( $design1_featured_title_background_color ); ?>;
        background-color: <?php echo esc_attr( $design1_featured_title_background_color ); ?>;
        color: <?php echo esc_attr( $design1_featured_title_font_color ); ?>;
    }
    #ptp-<?php echo $id ?> .ptp-highlight div.ptp-plan .has-tip {
        border-bottom: dotted 1px <?php echo esc_attr( $design1_featured_title_font_color ); ?>;
        color: <?php echo esc_attr( $design1_featured_title_font_color ); ?>;
    }
    #ptp-<?php echo $id ?> div.ptp-price{
        font-size: <?php echo esc_attr( $design1_price_font_size ) . esc_attr( $design1_price_font_size_type ); ?>;
        background-color: <?php echo esc_attr( $design1_pricing_background_color ); ?>;
        color: <?php echo esc_attr( $design1_pricing_area_font_color ); ?>;
        padding: 0.9375em 1.25em;
    }
    #ptp-<?php echo $id ?> div.ptp-price .has-tip {
        border-bottom: dotted 1px <?php echo esc_attr( $design1_pricing_area_font_color ); ?>;
        color: <?php echo esc_attr( $design1_pricing_area_font_color ); ?>;
    }
    #ptp-<?php echo $id ?> .ptp-highlight div.ptp-price{
        background-color: <?php echo esc_attr( $design1_featured_pricing_background_color ); ?>;
        color: <?php echo esc_attr( $design1_featured_pricing_font_color ); ?>;
    }
    #ptp-<?php echo $id ?> .ptp-highlight div.ptp-price .has-tip {
        border-bottom: dotted 1px <?php echo esc_attr( $design1_featured_pricing_font_color ); ?>;
        color: <?php echo esc_attr( $design1_featured_pricing_font_color ); ?>;
    }
    #ptp-<?php echo $id ?> div.ptp-cta{
        border-bottom-right-radius: <?php echo esc_attr( $design1_rounded_corner_width ); ?>;
        border-bottom-left-radius: <?php echo esc_attr( $design1_rounded_corner_width ); ?>;
        background-color: <?php echo esc_attr( $design1_unfeatured_button_area_background_color ); ?>;
        padding-top: 1.25em;
        padding-bottom: 1.25em;
    }
    #ptp-<?php echo $id ?> .ptp-highlight div.ptp-cta{
        background-color: <?php echo esc_attr( $design1_featured_button_area_background_color ); ?>;
    }
    #ptp-<?php echo $id ?> a.ptp-button{
        border-radius: <?php echo esc_attr( $design1_rounded_corner_width ); ?>;
        font-size: <?php echo esc_attr( $design1_button_font_size ) . esc_attr( $design1_button_font_size_type ); ?>;
        color: <?php echo esc_attr( $design1_button_font_color ); ?>;
        background-color: <?php echo esc_attr( $design1_button_color ); ?>;
        border-bottom: <?php echo esc_attr( $design1_button_border_color ); ?> 4px solid;
        margin: 0px;
    }
    #ptp-<?php echo $id ?> a.ptp-button .has-tip, #ptp-<?php echo $id ?> a.ptp-button:hover .has-tip {
        border-bottom: dotted 1px <?php echo esc_attr( $design1_button_font_color ); ?>;
        color: <?php echo esc_attr( $design1_button_font_color ); ?>;
    }
    #ptp-<?php echo $id ?> a.ptp-button:hover{
        <?php if ($design1_shake_buttons_on_hover) : ?>
            margin-top: 2px;
            border-bottom: <?php echo esc_attr( $design1_button_border_color ); ?> 2px solid;
        <?php else : ?>
            background-color: <?php echo esc_attr( $design1_button_hover_color ); ?>;
        <?php endif; ?>
    }
    #ptp-<?php echo $id; ?> div.ptp-bullet-item {
        color: <?php echo esc_attr( $design1_featured_font_color ); ?>;
        background-color: <?php echo esc_attr( $design1_featured_background_color ); ?>;
        font-size: <?php echo esc_attr( $design1_bullet_item_font_size ) . esc_attr( $design1_bullet_item_font_size_type ); ?>;
        border-color: <?php echo esc_attr( $design1_unfeatured_border_color ); ?>;
        padding: 0.9375em 0.5em 0.9375em 0.5em;
    }
    #ptp-<?php echo $id; ?> .ptp-highlight .ptp-bullet-item {
        color: <?php echo esc_attr( $design1_featured_feature_color ); ?>;
        border-color: <?php echo esc_attr( $design1_featured_border_color ); ?>;
        background-color: <?php echo esc_attr( $design1_featured_feature_background_color ); ?>;
    }
    #ptp-<?php echo $id; ?> .ptp-highlight a.ptp-button{
        color: <?php echo esc_attr( $design1_featured_button_font_color ); ?>;
        background-color: <?php echo esc_attr( $design1_featured_button_color ); ?>;
        border-bottom: <?php echo esc_attr( $design1_featured_button_border_color );?> 4px solid;
    }
    #ptp-<?php echo $id ?> .ptp-highlight a.ptp-button:hover{
        <?php if ($design1_shake_buttons_on_hover) : ?>
            margin-top: 2px;
            border-bottom: <?php echo esc_attr( $design1_featured_button_border_color ); ?> 2px solid;
        <?php else : ?>
            background-color: <?php echo esc_attr( $design1_featured_button_hover_color ); ?>;
        <?php endif; ?>
    }
    #ptp-<?php echo $id; ?> .ptp-highlight a.ptp-button .has-tip {
        border-bottom: dotted 1px <?php echo esc_attr( $design1_featured_button_font_color ); ?>;
        color: <?php echo esc_attr( $design1_featured_button_font_color ); ?>;
    }
    #ptp-<?php echo $id ?> div.ptp-most-popular{
        border-radius: <?php echo esc_attr( $design1_rounded_corner_width ); ?>;
        font-size: <?php echo esc_attr( $design1_most_popular_font_size ) . esc_attr( $design1_most_popular_font_size_type ); ?>;
        background-color: <?php echo esc_attr( $design1_most_popular_area_background_color ); ?>;
        color: <?php echo esc_attr( $design1_most_popular_font_color ); ?>;
        border: 1px solid <?php echo esc_attr( $design1_featured_feature_label_border_color ); ?>;
    }
    
    <?php if ($design1_no_spacing_between_columns) : ?>
        #ptp-<?php echo $id ?> .ptp-col{ padding-left:0; padding-right:0; margin-left: 0px;}
        #ptp-<?php echo $id ?> div.ptp-most-popular {margin-bottom: 0px;}
        #ptp-<?php echo $id ?> div.ptp-not-most-popular {margin-bottom: 2px;}
    <?php endif;?>
    
    <?php if ($design1_hover_effects) : ?>
        #ptp-<?php echo $id; ?>.ptp-pricing-table {
            margin-top: 30px;
        }
        #ptp-<?php echo $id ?> div.ptp-item-container {
            margin: 0px;
        }
        
        #ptp-<?php echo $id; ?>.ptp-pricing-table .ptp-col {
            -moz-transition: all 0.2s linear;
            -ms-transition: all 0.2s linear;
            -o-transition: all 0.2s linear;
            -webkit-transition: all 0.2s linear;
            transition: all 0.2s linear;
            margin-bottom: 30px;
        }
        #ptp-<?php echo $id; ?>.ptp-pricing-table .ptp-col:hover {
            margin-top: -20px!important;
            margin-bottom: 10px;
        }
        #ptp-<?php echo $id; ?>.ptp-pricing-table .ptp-col .ptp-cta {
            -moz-transition: all 0.2s linear;
            -ms-transition: all 0.2s linear;
            -o-transition: all 0.2s linear;
            -webkit-transition: all 0.2s linear;
            transition: all 0.2s linear;
        }
        #ptp-<?php echo $id; ?>.ptp-pricing-table .ptp-col:hover .ptp-cta {
            padding-top: 40px !important;
            padding-bottom: 40px !important;
        }
        
    <?php endif; ?>
    
    /* end of simple flat #<?php echo $id;?> */
    
    <?php
	// Print stylish custom css setting	
	if(isset($meta['ept-custom-css-setting-dg1'])) {
		if (function_exists ('wp_add_inline_script') ) {
			wp_add_inline_style ( 'dh-ptp-custom-css', $meta['ept-custom-css-setting-dg1'] );
		} else {
			echo $meta['ept-custom-css-setting-dg1'];
		}
    }
	
}


/**
 * Generate our simple flat pricing table HTML
 * @return [type]
 */
function dh_ptp_generate_simple_flat_pricing_table_html ($id, $hide = false)
{
    global $features_metabox;
    global $ept_allowed_tags;
    global $meta;

    $ept_allowed_tags = apply_filters( 'fca_ept_allowed_tags', $ept_allowed_tags );
    $meta = get_post_meta($id, $features_metabox->get_the_id(), TRUE);
    $post = get_post($id);
    
    $loop_index = 0;
    $hide_table = ($hide)?'style="display:none"':'';
    $pricing_table_html = '<div id="ptp-'. $id .'" class="ptp-pricing-table" '.$hide_table.'>';
    foreach ($meta['column'] as $column) {
        
        // Column details
        $plan_name = isset($column['planname'])?do_shortcode( $column['planname'] ):'';
        $plan_price = isset($column['planprice'])?do_shortcode( $column['planprice'] ):'';
        $plan_features = isset($column['planfeatures'])?do_shortcode( $column['planfeatures'] ):'';
        $button_text = isset($column['buttontext'])?$column['buttontext']:__('Add to Cart', 'easy-pricing-tables');
        $button_url = isset($column['buttonurl'])?$column['buttonurl']:'';

        // get custom shortcode button if any
        $custom_button = false;
        $shortcode_pattern = '|^\[shortcode\](?P<custom_button>.*)\[/shortcode\]$|';
        if ( 
            preg_match( $shortcode_pattern, $button_text, $matches) 
            ||
            preg_match( $shortcode_pattern, $button_url, $matches) 
        ) {
            $custom_button = $matches[ 'custom_button' ];
        }
     
		if ( has_shortcode( $button_url, 'ept_spp' ) ) {
			wp_enqueue_script( 'ptp-spp-checkout-js', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/includes/integrations/simplepay3/ptp-simplepay-checkout.js', array( 'jquery', 'simpay-public-pro' ) );
			$id = preg_replace( '/[^0-9]/', '', $button_url );
			if ( $id ) {
				$pricing_table_html .= '<div style="display: none;">' . do_shortcode("[simpay id='$id']") . '</div>';			
			}
		}
		
        // Featured column
        $feature = '';
        $feature_label = '<div class="ptp-not-most-popular">&nbsp;</div>';
        if(isset($column['feature']) && $column['feature'] == "featured") {
            $most_popular_text = isset($meta['most-popular-label-text'])?$meta['most-popular-label-text']:__('Most Popular', 'easy-pricing-tables');
            $feature = "ptp-highlight";
            $feature_label = '<div class="ptp-most-popular">' . dh_ptp_fa_icons( $most_popular_text ) . '</div>';
        }

        // Call to action buttons
        $call_to_action = '';
        $table_name = addslashes($post->post_title)?addslashes($post->post_title):'untitled pricing table';
        
        if (!isset($meta['hide-call-to-action-buttons']) || !$meta['hide-call-to-action-buttons']) {
            $open_in_new_tab = (isset($meta['design1-open-link-in-new-tab']) && $meta['design1-open-link-in-new-tab'])?'target="_blank"':'';
            
            $call_to_action =
                '<div class="ptp-cta">' .
                    (($custom_button)? $custom_button:'<a class="ptp-button ptp-checkout-button" id="ptp-'.$id.'-cta-'.$loop_index.'"  href="' . esc_url( do_shortcode ( $button_url ) ) . '" ' . $open_in_new_tab .'>' . dh_ptp_fa_icons( do_shortcode( wp_kses( $button_text, $ept_allowed_tags ) ) ) . '</a>') .
	  			'</div>';
        }
        
        // Hide empty rows
        $hide_empty_rows = isset($meta['design1-hide-empty-rows'])?true:false;
        
        // create the html code
        $pricing_table_html .= 
            '<div class="ptp-col ' . dh_ptp_get_number_of_columns() . ' '. $feature . ' ptp-col-id-' . $loop_index . ' dh-ptp-col-id-' . $loop_index . '">' .
                $feature_label .
                '<div class="ptp-item-container">'.
                    '<div class="ptp-plan">' . dh_ptp_fa_icons( wp_kses( $plan_name, $ept_allowed_tags ) ) . '</div>' . 
                    '<div class="ptp-price">' . dh_ptp_fa_icons( wp_kses( $plan_price, $ept_allowed_tags ) ) . '</div>' .
                    dh_ptp_features_to_html_simple_flat( $plan_features, dh_ptp_get_max_number_of_features(), $hide_empty_rows ) . 
                    $call_to_action .
                '</div>' .
            '</div>';

        $loop_index++;
    }

    $pricing_table_html .= '</div>';

    
    
    return $pricing_table_html;
}

/**
 * Returns the appropriate HTML class depending on how many columns our pricing table has
 * @return string
 */
function dh_ptp_get_number_of_columns()
{
    global $meta;

    $number_to_text = array(
        '1'=>'one', '2'=>'two', '3'=>'three', '4'=>'four', '5'=>'five',
        '6'=>'six', '7'=> 'seven', '8'=>'eight', '9'=>'nine', '10'=>'ten'
    );
    
    $count = count($meta['column']);
    if ($count > 0 && $count <= 10) {
        return sprintf('ptp-%s-col', $number_to_text[$count]);
    }
    
    return 'ptp-more-col';
}

/**
 * Returns the highest number of features that one of our columns uses (needed to create blank rows)
 * @return int
 */
function dh_ptp_get_max_number_of_features()
{
    global $meta;

    $max = 0;
    foreach ($meta['column'] as $column) {
        if(isset($column['planfeatures'])) {
            // get number of features
            $col_number_of_features = count( explode( "\n", $column['planfeatures'] ) );

            if ($col_number_of_features > $max) {
                $max = $col_number_of_features;
            }
        }
    }

    return $max;
}


/**
 * Generate HTML code for our features
 * @param $dh_ptp_plan_features - this is an array containing all features
 * @param $dh_ptp_max_number_of_features - the highest number of features that one of our columns uses
 * @return string - the html string containing all features
 */
function dh_ptp_features_to_html_simple_flat ($plan_features, $max_number_of_features, $hide_empty_rows)
{
    // the string to be returned
    global $ept_allowed_tags;
    $html = "";

    // explode string into a useable array
    $features = explode("\n", $plan_features);

    //how many features does this column have?
    $this_columns_number_of_features = count($features);

    //add each feature to $dh_ptp_feature_html
    for ($i=0; $i<$max_number_of_features; $i++) {
        if ($i < $this_columns_number_of_features && trim($features[$i]) != '') {
            $html .= '<div class="ptp-bullet-item ptp-row-id-'.$i.'">' . dh_ptp_fa_icons( wp_kses( $features[$i], $ept_allowed_tags ) ) . '</div>';
        } else {
           if (!$hide_empty_rows) {
                $html .= '<div class="ptp-bullet-item ptp-row-id-'.$i.' tt-ptp-empty-row ">&nbsp;</div>';
            }
        }
    }

    return $html;
}

function tt_ptp_enable_column_match_height_script_dg1( $id ) {
    $name_of_called_matchheight_func = "call_match_height_$id";
    
    ob_start();
    ?>
        <script type="text/javascript">
         jQuery(document).ready(function($) {
            jQuery.<?php echo $name_of_called_matchheight_func; ?> = function call_match_height_design1(ptp_id) {
                                $( ptp_id+' .ptp-plan' ).matchHeight(false); 
                                $( ptp_id+' .ptp-cta' ).matchHeight(false); 
                                $( ptp_id+' .ptp-price' ).matchHeight(false);
                                $( ptp_id+' .ptp-button' ).matchHeight(false);

                                $( ptp_id+' .ptp-bullet-item' ).each(function( index ){
                                    $( ptp_id+' .ptp-row-id-'+index ).matchHeight(false);
             
                                });  
                         }
            var ptp_id = '#ptp-'+<?php echo $id; ?> ;
            $.<?php echo $name_of_called_matchheight_func; ?> ( ptp_id );              
         });
      </script>
      
        <?php
    return ob_get_clean();

      
}