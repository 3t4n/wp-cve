<?php
// include our HTML generators for each table
$require_patterns = [

	PTP_PLUGIN_PATH . 'includes/table-generation/*.php',

];

$exclusions = [
	PTP_PLUGIN_PATH . 'includes/table-generation/table-generator.php',
];

foreach ( $require_patterns as $pattern ) {
    $files = glob( $pattern );

    foreach ( $files as $file ) {
		if ( !in_array( $file, $exclusions, true ) ) {
			require_once $file;				
		}
    }
}

/**
 * Output the CSS of the pricing table
 * 
 * @param int $it the id of the easy pricing table
 * @todo track output CSS to prevent outputting it twice
 */
function dh_ptp_easy_pricing_table_dynamic_css( $id ) {
	
	//FIX YOAST MUCKING THINGS UP
	if ( doing_action( 'wp_head' ) ) {
		return false;
	}

	$meta = get_post_meta( $id, '1_dh_ptp_settings', true);
	
	ob_start();
	
	// Print css style per table
	if (isset($meta['dh-ptp-fancy-flat-template']) && $meta['dh-ptp-fancy-flat-template'] == 'selected') {
		// Print simple flat
		dh_ptp_fancy_flat_css($id, $meta);
	} elseif (isset($meta['dh-ptp-stylish-flat-template']) && $meta['dh-ptp-stylish-flat-template'] == 'selected') {
		// Print stylish flat
		dh_ptp_stylish_flat_css($id, $meta);
	} elseif (isset($meta['dh-ptp-design4-template']) && $meta['dh-ptp-design4-template'] == 'selected') {
		dh_ptp_design4_css($id, $meta);
	} elseif (isset($meta['dh-ptp-dg5-template']) && $meta['dh-ptp-dg5-template'] == 'selected') {
		dh_ptp_design5_css($id, $meta);
	} elseif (isset($meta['dh-ptp-dg6-template']) && $meta['dh-ptp-dg6-template'] == 'selected') {
		dh_ptp_design6_css($id, $meta);
	} elseif (isset($meta['dh-ptp-dg7-template']) && $meta['dh-ptp-dg7-template'] == 'selected') {
		dh_ptp_design7_css($id, $meta);
	} elseif (isset($meta['dh-ptp-comparison1-template']) && $meta['dh-ptp-comparison1-template'] == 'selected') {
		dh_ptp_comparison1_css($id, $meta);
	} elseif (isset($meta['dh-ptp-comparison2-template']) && $meta['dh-ptp-comparison2-template'] == 'selected') {
		dh_ptp_comparison2_css($id, $meta);
	} elseif (isset($meta['dh-ptp-comparison3-template']) && $meta['dh-ptp-comparison3-template'] == 'selected') {
		dh_ptp_comparison3_css($id, $meta);
	} else {
		// Print simple flat
		dh_ptp_simple_flat_css($id, $meta);
	}
	
	$css = ob_get_clean();

	$css = preg_replace( '/\s+/', ' ', $css );
	$css = preg_replace( '/;(?=\s*})/', '', $css );
	$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
	$css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
	$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
	$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
	$css = preg_replace( '/0 0 0 0/', '0', $css );
	$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
	$css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
	$css = preg_replace( '/and\(/', 'and (', $css );
	
	$css = apply_filters( 'fca_ept_css_filter', $css );
	
	return "<style>$css</style>";
		
}


/**
 * This function decides which table style we should create. It enqueue the appropriate CSS file and calls the appropriate function.
 *
 * @return string pricing table html
 */
function dh_ptp_generate_pricing_table($id, $hide = false){
    $meta = get_post_meta( $id, '1_dh_ptp_settings', true );
	wp_enqueue_style('jquery');
   
	if( DH_PTP_LICENSE_PACKAGE !== 'Free' ) {
		wp_enqueue_style('ept-font-awesome');
		wp_enqueue_style('ept-foundation-css', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/foundation/foundation.min.css');
		wp_enqueue_script('ept-foundation-js', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/foundation/foundation.min.js', array( 'jquery' ) );
		wp_enqueue_script('ept-modernizr', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/js/modernizr.min.js', array( 'jquery' ) );
				
		wp_enqueue_script('ept-foundation-tooltip', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/foundation/foundation.tooltip.min.js', array( 'ept-foundation-js', 'ept-modernizr' ) );
		
		wp_enqueue_script('ept-ui-tooltip', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-tooltip.min.js', array( 'ept-foundation-tooltip' ) );
	}
	
	//ADD A DUMMY STYLESHEET TO APPEND INLINE CSS LATER IF SET
	wp_register_style( 'dh-ptp-custom-css', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/ptp-custom.min.css'  );	
	
    $return = dh_ptp_easy_pricing_table_dynamic_css($id);

    // Figure out which table we have here
    if (isset($meta['dh-ptp-fancy-flat-template']) && $meta['dh-ptp-fancy-flat-template'] == 'selected') {
        
        /**
         * fancy flat
         */
        
        //include css
        wp_enqueue_style( 'fancy-flat-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/fancy-flat/pricingtable.min.css'  );
     // Print stylish enable match-column-height
       if(isset($meta['match-column-height-dg2'])) {   
             wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js', array('jquery'));
             $return .= tt_ptp_enable_column_match_height_script_dg2( $id );
        }
        //call appropriate function
        $return .= dh_ptp_generate_fancy_flat_pricing_table_html($id, $hide);
    } elseif (isset($meta['dh-ptp-stylish-flat-template']) && $meta['dh-ptp-stylish-flat-template'] == 'selected') {
        
        /**
         * stylish flat
         */
        
        // Include dark theme by default
        wp_enqueue_style( 'stylish-flat-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/stylish-flat/css/pricingtable.min.css'  );
      // Print stylish enable match-column-height
       if(isset($meta['match-column-height-dg3'])) {   
             wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js', array('jquery'));
             $return .= tt_ptp_enable_column_match_height_script_dg3( $id );
        }
        //call appropriate function
        $return .= dh_ptp_generate_stylish_flat_pricing_table_html($id, $hide);
    } elseif (isset($meta['dh-ptp-design4-template']) && $meta['dh-ptp-design4-template'] == 'selected') {
        /**
         * Design 4
         */
        
        // Include default theme
        wp_enqueue_style( 'design4-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design4/css/pricingtable.min.css'  );
      // Print stylish enable match-column-height
      /* if(isset($meta['match-column-height-dg4'])) {   
             wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js');
             $return .=  tt_ptp_enable_column_match_height_script_dg4();
        }*/
        //call appropriate function
        $return .= dh_ptp_generate_design4_pricing_table_html($id, $hide);
    } elseif (isset($meta['dh-ptp-dg5-template']) && $meta['dh-ptp-dg5-template'] == 'selected') {
        /**
         * Design 5
         */
        
        // Include default theme
        wp_enqueue_style( 'design5-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design5/pricingtable.min.css'  );
       // Print stylish enable match-column-height
       if(isset($meta['match-column-height-dg5'])) {   
            wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js');
            $return .=  tt_ptp_enable_column_match_height_script_dg5( $id );
        }
        //call appropriate function
        $return .= dh_ptp_generate_design5_pricing_table_html($id, $hide);
    } elseif (isset($meta['dh-ptp-dg6-template']) && $meta['dh-ptp-dg6-template'] == 'selected') {
        /**
         * Design 6
         */
        
        // Include default theme
        wp_enqueue_style( 'design6-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design6/pricingtable.min.css'  );
       // Print stylish enable match-column-height
       if(isset($meta['match-column-height-dg6'])) {   
            wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js');
            $return .=  tt_ptp_enable_column_match_height_script_dg6( $id );
        }
        //call appropriate function
        $return .= dh_ptp_generate_design6_pricing_table_html($id, $hide);
    } elseif (isset($meta['dh-ptp-dg7-template']) && $meta['dh-ptp-dg7-template'] == 'selected') {
        /**
         * Design 6
         */
        
        // Include default theme
        wp_enqueue_style( 'design7-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design7/pricingtable.min.css'  );
       // Print stylish enable match-column-height
       if(isset($meta['match-column-height-dg7'])) {   
            wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js');
            $return .= tt_ptp_enable_column_match_height_script_dg7( $id );
        }
        //call appropriate function
        $return .= dh_ptp_generate_design7_pricing_table_html($id, $hide);
    } elseif (isset($meta['dh-ptp-comparison1-template']) && $meta['dh-ptp-comparison1-template'] == 'selected') {
        
        /**
         * Comparison 1
         */
        
        // Enqueue CSS
        wp_enqueue_style('comparison1-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/comparison1/css/comparison1-common.min.css'  );
        
        // Print stylish enable match-column-height
       if(isset($meta['match-column-height-cp1'])) {   
            wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js', array('jquery'));
            $return .= tt_ptp_enable_column_match_height_script_cp1( $id );
        }
        //call appropriate function
        $return .= dh_ptp_generate_comparison1_pricing_table_html($id, $hide);
    } elseif (isset($meta['dh-ptp-comparison2-template']) && $meta['dh-ptp-comparison2-template'] == 'selected') {
        
        /**
         * Comparison 2
         */
        
        // Enqueue CSS
        wp_enqueue_style('comparison2-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/comparison2/css/comparison2-common.min.css'  );        
        // Print stylish enable match-column-height
		         
        $return .=  tt_ptp_enable_column_match_height_script_cp2( isset($meta['match-column-height-cp2']), $id );
        
        //call appropriate function
        $return .= dh_ptp_generate_comparison2_pricing_table_html($id, $hide);
    } elseif (isset($meta['dh-ptp-comparison3-template']) && $meta['dh-ptp-comparison3-template'] == 'selected') {
        
        /**
         * Comparison 3
         */
		 
        // Enqueue CSS
        wp_enqueue_style('comparison3-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/comparison3/css/comparison3-common.min.css'  );
        
        // Print stylish enable match-column-height
       if(isset($meta['match-column-height-cp3'])) {   
            wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js', array('jquery'));
            $return .= tt_ptp_enable_column_match_height_script_cp3( $id );
        }
        //call appropriate function
        $return .= dh_ptp_generate_comparison3_pricing_table_html($id, $hide);
    } else {
        /**
         * Default: simple flat
         */
        
        //include css
        wp_enqueue_style( 'dh-ptp-design1', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design1/pricingtable.min.css'  );
        
        // Print stylish enable match-column-height
       if(isset($meta['match-column-height-dg1'])) {   
            wp_enqueue_script( 'matchHeight', PTP_PLUGIN_PATH_FOR_SUBDIRS.'/assets/ui/js/jquery.matchHeight-min.js', array('jquery'));
         
            $return .= tt_ptp_enable_column_match_height_script_dg1( $id );
        }
        //call appropriate function
        $return .= dh_ptp_generate_simple_flat_pricing_table_html($id, $hide);
    }
	
	wp_enqueue_style( 'dh-ptp-custom-css' );
    // Output the CSS
    return $return;
}

/**
 * This function decides which table style we should create. It enqueue the appropriate CSS file and calls the appropriate function.
 *
 * @return string pricing table html
 */
function dh_ptp_generate_ga_script( $event_category, $event_action , $event_label  ) {   
    return "onclick=\"if (__gaTracker) {__gaTracker('send', 'event', '".$event_category."', '".$event_action."', '".$event_label."');}\"";

}

/**
* Extract price information
* 
* Patterns of prices supported:
* - Currency then amount ($30, USD 30; €30) and possible text before and after
* - Amount then currency (30 euros) and possible text before and after
* - Amount only (30)
*/

function dh_ptp_get_price_formatted ( $planprice , $call_user_custom_func = '' ) {

        $price_formatted = '';
        $price_patterns = array(
            array(
                'id' => 'PTP_HTML',
                'format' => ':html',
                'pattern' => "/^(?P<html>.*<.*)$/",
            ),
            array(
                'id' => 'PTP_TEXT',
                'format' => ':price',
                'pattern' => "/^(?P<price>\D+)$/",
            ),
            array(
                'id' => 'PTP_CURR_PRICE',
                'format' => ':text_before:currency:price:text_after',
                'pattern' => "/^((?P<text_before>\D+)\s+)?(?P<currency>[^\d\s]+)\s*(?P<price>[\d.,']+)(\s?(?P<text_after>.+))?$/",
            ),
            array(
                'id' => 'PTP_PRICE_CURR',
                'format' => ':text_before:price:currency:text_after',
                'pattern' => "/^((?P<text_before>\D+)\s+)?(?P<price>\d[\d.,']*)\s*(?P<currency>[^\d\s]+)(\s+(?P<text_after>.+))?$/",
            ),
            array(
                'id' => 'PTP_PRICE',
                'format' => ':price:currency',
                'pattern' => "/^(?P<price>\d[\d.,']*)$/",
            ),
        );
        if (strlen($planprice) > 0) {
            /**
             * If we find a match, set $price and possibly $currency and break.
             * $price_pattern['format'] will help us build the price after the loop
             */
			
			$planprice = html_entity_decode ( $planprice );
            foreach ($price_patterns as $price_pattern) {
                if ( preg_match( $price_pattern['pattern'], trim( do_shortcode( $planprice ) ), $matches) ) {
                    break;
                }
            }
            
            if($call_user_custom_func) {
                $price_formatted = call_user_func( $call_user_custom_func , $matches , $price_pattern );
                
            } else {
                /**
                 * Prepare HTML
                 */
                $html = empty ( $matches[ 'html' ] ) ? '' : $matches[ 'html' ];
                $html && $html = '<div class="ptp-pricing-text">' . $html . '</div>';
                $currency = empty ( $matches[ 'currency' ] ) ? '$' : $matches[ 'currency' ];
                $currency = '<span class="sign">' . $currency . '</span>';
                $price =  (isset( $matches[ 'price' ]) && $matches[ 'price' ] !=='' )? $matches[ 'price' ]:'...';
                $text_before = empty ( $matches[ 'text_before' ] ) ? '' : '<div class="ptp-pricing-text">' . $matches[ 'text_before' ] . '</div>' ;
                $text_after = empty ( $matches[ 'text_after' ] ) ? '' : '<div class="ptp-pricing-text">' . $matches[ 'text_after' ] . '</div>' ;
                /**
                 * Replace value and produce formatted price
                 */
                $price_formatted = str_replace( 
                    array( ':html', ':price', ':currency', ':text_before', ':text_after' ),
                    array( $html, $price, $currency, $text_before, $text_after ),
                    $price_pattern['format']
                );
                
            }
        }
        
        return $price_formatted;
}

/* Replace shortcode text into font awesome icon */
function dh_ptp_fa_icons($raw, $extra = false, $fa_icon_type = '')
{
    // Extra Icons for Comparison 1
    if ($extra) {
        if($fa_icon_type == 'no-circle') {
            $raw = str_replace('[y]', '<i class="fa fa-check black"></i>', $raw);
           $raw = str_replace('[n]', '<i class="fa fa-times black"></i>', $raw);
        } else {
           $raw = str_replace('[y]', '<i class="fa fa-chevron-circle-down green"></i>', $raw);
           $raw = str_replace('[n]', '<i class="fa fa-times-circle red"></i>', $raw);   
        }
    }
    
	// Tooltip
	if (preg_match('/\[tooltip( content=[\"|\']{1}(.*)?[\"|\']{1})?\](.*)?\[\/tooltip\]/sim', $raw)) {
		$pattern = '/\[tooltip( content=[\"|\']{1}(.*)?[\"|\']{1})?\](.*)?\[\/tooltip\]/i';
		$replacement = '<span data-tooltip class="has-tip" title="$2">$3</span>';
		$raw = preg_replace($pattern, $replacement, $raw);
	}
	
    $pattern = '/\[(.*?)\]/i';
    $replacement = '<i class="fa fa-$1"></i>';
    $text = preg_replace($pattern, $replacement, $raw);
    
    return $text;
}