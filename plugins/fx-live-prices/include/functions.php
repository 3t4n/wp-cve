<?php
// global variables
$fxlive_db_meta_key = 'fxlive_widget_meta_key';
$fxlive_slug_name = 'fx-pricing-widget';
$widget_multi_ids = '1,2,5,14,20,41';
$widget_single_id = '5';
$widget_symbols = 'EUR/USD,USD/JPY,USD/INR,GBP/CHF,AED/PKR';
$fxlive_post_data = array();



new fxlive_widgetsPost();
class fxlive_widgetsPost{

	function __construct(){
		add_action( 'admin_enqueue_scripts', array($this, 'include_script_admin') ); // include css file

		add_action( 'init', array($this, 'widget_page') ); // register post

		add_filter( 'post_row_actions', array($this, 'remove_quick_edit') ); // remove quick edit

		add_filter( 'manage_posts_columns', array($this, 'shortcode_column_add') ); // add list column

    	add_action( 'manage_posts_custom_column', array($this, 'custom_column_data'), 2, 2 ); // add column data

    	add_shortcode( 'fx-widget', array($this, 'shortcode_add') ); // add shortcode

    	add_action( 'save_post', array($this, 'save_postdata') ); // save post data

    	add_action( 'wp_ajax_fxlive_preview_widget_ajax', array($this, 'preview_widget_list') ); // widget list preview



    	add_action( 'add_meta_boxes', array($this, 'register_side_publish') ); // widget list right side

    	add_action( 'add_meta_boxes', array($this, 'register_widget_setting') ); // widget all setting
	}



	/* check post type is fx */
    function fx_is_post_type() { 
    	global $fxlive_slug_name;

        if(get_post_type() == $fxlive_slug_name) {
	        return true;
        }
        else if(isset($_GET['post_type']) && $_GET['post_type'] == $fxlive_slug_name)
        {
	        return true;
	    }

        return false;
    }
	/* check post type is fx end */



	/* include css file */
	function include_script_admin() {
    	if( $this->fx_is_post_type() ) {
	        wp_enqueue_style('fxlive-widget-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
	        wp_enqueue_style('fxlive-widget-style', FXLIVE_PLUGIN_DIR.'assets/style.css' , array(), FXLIVE_PLUGIN_VERSION);

	        wp_enqueue_script('fxlive-widget-script', FXLIVE_PLUGIN_DIR.'assets/fx_script.js', array('jquery'));

	        if( isset($_GET['action']) &&  !empty($_GET['post']) && $_GET['action'] == 'edit' ) 
	        {
	        	global $fxlive_db_meta_key;
	        	$fx_edit_data = get_post_meta( intval($_GET['post']), $fxlive_db_meta_key, true);
	           
	            if( !empty($fx_edit_data['fx-select-widget']) ) 
	               wp_add_inline_script( 'fxlive-widget-script', 'jQuery(document).ready(function(){ jQuery("#fx-next").trigger("click"); });' );
		    }
	    }
    }
    /* include css file end */



	/* register post */
	function widget_page() {
    	global $fxlive_slug_name;
		$labels = array(
			'name'               	=> __( 'Forex Live Rates' , 'fx-live-prices'),
			'singular_name'  	=>  'fx-live-prices',
			'add_new'            => __( 'Add New', 'fx-live-prices' ),
			'add_new_item'  	=> __( 'Add New Widget', 'fx-live-prices' ),
			'edit_item'          	=> __( 'Edit Widget', 'fx-live-prices' ),
			'new_item'           => __( 'New Widget', 'fx-live-prices' ),
			'all_items'          	=> __( 'All Widgets', 'fx-live-prices' ),
			'view_item'          => __( 'View Widget', 'fx-live-prices' ),
			'search_items'   	=> __( 'Search Widgets', 'fx-live-prices' ),
			'not_found'          => __( 'No Widgets found', 'fx-live-prices' ),
			'not_found_in_trash' => __( 'No Widgets found in the Trash', 'fx-live-prices' ), 
			'menu_name'      	=> __( 'Forex Widgets', 'fx-live-prices' )
		);
		$args = array(
			'labels'             		=> $labels,
			'description'     		=> 'Holds our Widgets and Widget specific data',
			'public'             		=> true,
			'publicly_queryable' => false,
			'taxonomies'        => array(),
			'menu_position'	=> 5,
			'supports'    			=> array('title'),
			'has_archive'       => false,
			'menu_icon' 			=> 'dashicons-chart-bar' // icons https://developer.wordpress.org/resource/dashicons/#pressthis
		);
      	register_post_type( $fxlive_slug_name, $args ); 
    }
    /* register post end */



    /* remove quick edit */
    function remove_quick_edit( $actions ) { 
        if( $this->fx_is_post_type() ) {
            unset($actions['inline hide-if-no-js']);
            return $actions;
        }  
        return $actions;
    }
	/* remove quick edit end */



	/* add list column */
    function shortcode_column_add($columns) {
        if( $this->fx_is_post_type() ) {
	        /*
	        // Long Method for specific column target
	        $new = array();
			foreach($columns as $key => $title) {
				$new[$key] = $title;
				if ($key=='title'){
					// Put the column after this column
			  		$new['fxlive-shortcode-column'] = 'Shortcode1';
				}
			}
			return $new;
			*/

			// OR Second Method
			unset($columns['date']); // unset then add again
            $columns['fxlive-shortcode-column'] = 'Shortcode';
            $columns['date'] = 'Date';
            return $columns;
        }
        return $columns;
    }
	/* add list column end */


	/* add column data */
    function custom_column_data( $column_name, $id ) {
        if ( 'fxlive-shortcode-column' === $column_name )
            echo "[fx-widget id=$id]";
    }
	/* add column data end */


	/* add shortcode */
	function shortcode_add( $atts ) {
		global $fxlive_db_meta_key;

		if(!empty($atts['id'])){
			$fx_result = get_post_meta($atts['id'], $fxlive_db_meta_key, true);
			if(!empty($fx_result['fx-hide-iframe']) && get_post_status($atts['id']) == 'publish'){
				// encode at line 268
				$iframe =  wp_specialchars_decode($fx_result['fx-hide-iframe'],ENT_QUOTES);
				$iframe .= ' <!-- Forex live rates source fxpricing.com and FCSAPI --> ';
				return $iframe;
			}

		}
	}
	/* add shortcode end */

	/* save post data */
    function save_postdata( $post_id ) {
        global $fxlive_db_meta_key;

        if( !empty($_POST['fxlive_widget_nonce_field']) && 
        	wp_verify_nonce($_POST['fxlive_widget_nonce_field'], 'fxlive_widget_nonce_action') && 
    		isset($_POST['fx_widget']) )
        {
        	$postData = $_POST['fx_widget']; // {Before sanitized} Array, Each index sanitize below 
        	$newData = array(); // $postData: {After sanitized} after sanitized, value save in this variable then save in DB

        	/* fx list select */
            if( !empty($postData['fx-select-list'])  && 
            	is_array($postData["fx-select-list"]) ) 
            {
            	$newSelectList = array_map( 'intval', $postData["fx-select-list"] );
                $newData['fx-select-list'] = implode( ',', $newSelectList );
            }
            
            /* fx list single select */
            if(!empty($postData["fx-get-symbol-list"])) 
            	$newData['fx-get-symbol-list'] = sanitize_text_field($postData['fx-get-symbol-list']);

            /* theme select */
            if(!empty($postData["fx-get-theme-list"]))
            	$newData['fx-get-theme-list'] = sanitize_text_field($postData['fx-get-theme-list']);

            /* text align select */
            if(!empty($postData["fx-get-value-align-list"]))
            	$newData['fx-get-value-align-list'] = sanitize_text_field($postData['fx-get-value-align-list']);

            /* target click select */
            if(!empty($postData["fx-target-click"]))
            	$newData['fx-target-click'] = sanitize_text_field($postData['fx-target-click']);

            /* Enter Custom target URL */
            if(!empty($postData["fx-target-link"]))
            	$newData['fx-target-link'] = esc_url_raw($postData['fx-target-link']);


            /* width */
            if(!empty($postData["fx-width"]))
            	$newData['fx-width'] = intval($postData['fx-width']);

            /* width autosize check */
            if(!isset($postData["fx-autosize-width"]) && 
                isset($postData["fx-width"]))
                $newData["fx-autosize-width"] = "unchecked";
            else
                $newData["fx-autosize-width"] = 'checked';

            /* height */
            if(!empty($postData["fx-height"]))
                $newData["fx-height"] = intval($postData["fx-height"]);

            /* Widget key */
            if(!empty($postData["fx-widget-key"]))
                $newData["fx-widget-key"] = sanitize_text_field($postData["fx-widget-key"]);

            /* Flags Style select */
            if(!empty($postData["fx-get-flags-list"]))
                $newData["fx-get-flags-list"] = sanitize_text_field($postData["fx-get-flags-list"]);

            /* Available data columns */
            if( !empty($postData["fx-column-select"]) && is_array($postData["fx-column-select"]) )
				$newData["fx-column-select"] = array_map( 'sanitize_text_field', $postData["fx-column-select"] );

			/* border hide */
            if(!empty($postData["fx-border-hide"]) && $postData["fx-border-hide"] == 'checked')
                $newData["fx-border-hide"]	= 'checked';

			/* FX logo hide */
            if(!empty($postData["fx-fcs-link"]) 	&& $postData["fx-fcs-link"]	=='checked')
                $newData["fx-fcs-link"] 		= 'checked';

			/* pair weight */
            if(!empty($postData["fx-pair-weight"]) 	&& $postData["fx-pair-weight"]=='checked')
                $newData["fx-pair-weight"] 	= 'checked';

			/* speed second */
            if(!empty($postData["fx-speed-second"]))
                $newData["fx-speed-second"] = intval($postData["fx-speed-second"]);

			/* noslide */
            if(!empty($postData["fx-noslide"]) && $postData["fx-noslide"] == 'checked')
                $newData["fx-noslide"] = 'checked';

            /* iframe save in DB*/
            if(!empty($postData["fx-hide-iframe"]))
                $newData["fx-hide-iframe"] = esc_html($postData["fx-hide-iframe"]);

            /* Selected widget name */
            if(!empty($postData["fx-select-widget"]))
                $newData["fx-select-widget"] = sanitize_text_field($postData["fx-select-widget"]);

            //$my_data = wp_json_encode($newData);
            update_post_meta($post_id, $fxlive_db_meta_key, $newData);
	    }
    }
	/* save post data end */





	/* widget list preview */
	function preview_widget_list() {
    	if(!empty($_POST['fx_select_widget'])){
    		global $widget_multi_ids, $widget_single_id, $widget_symbols, $fxlive_post_data, $fxlive_db_meta_key;
	    	$select_widget = strtolower( sanitize_text_field($_POST['fx_select_widget']) );
	    	$valid_values = array(
	    			'market currency rates', 'ticker tape', 'single ticker', 'forex cross rates',
	    			'technical indicator', 'simple moving', 'last candle',  'pivot point'
	    		);
	    	if(!in_array( $select_widget,$valid_values)){
	    		return false;
	    	}

	    	if(!empty($_POST['fx_post_id']))
	    		$fxlive_post_data = get_post_meta( intval($_POST['fx_post_id']) , $fxlive_db_meta_key, true);


			//$widget_powered = ' <div id="fx-pricing-widget-copyright"> <span>Powered by </span><a href="https://fxpricing.com/" target="_blank">FX Pricing</a> </div> <style type="text/css"> #fx-pricing-widget-copyright{ text-align: center; font-size: 13px; font-family: sans-serif; margin-top: 10px; margin-bottom: 10px; color: #9db2bd; } #fx-pricing-widget-copyright a{ text-decoration: unset; color: #bb3534; font-weight: 600; } </style>';
			$widget_powered = ' ';

		    $data = array();


			/* market currency rates wighet data */
			$data['market currency rates']['iframe'] = '<iframe src="https://fxpricing.com/fx-widget/market-currency-rates-widget.php?id='.esc_attr($widget_multi_ids).'" width="100%" height="300" style="border: 1px solid #eee;"></iframe>';
			$data['market currency rates']['setting'] = 'symbol,theme,value_align,target_link,width,height,widget_key,flags,column,border,fcs_link,pair_weight';
			$data['market currency rates']['width_px'] = false;
			$data['market currency rates']['symbol_item'] = 'ids';
			$data['market currency rates']['iframe_border'] = true;
			$data['market currency rates']['height'] = '300';
			$data['market currency rates']['column_list'] = 'checked=>all,price,ask,bid,chg,chg_per,spread,time';
			$data['market currency rates']['src'] = 'https://fxpricing.com/fx-widget/market-currency-rates-widget.php?';



			/* ticker tape wighet data */
			$data['ticker tape']['iframe'] = '<iframe src="https://fxpricing.com/fx-widget/ticker-tape-widget.php?id='.esc_attr($widget_multi_ids).'" width="100%" height="78"  style="border: 1px solid #eee;"></iframe>';
			$data['ticker tape']['setting'] = 'symbol,theme,target_link,speed,width,widget_key,flags,column,border,fcs_link,pair_weight';
			$data['ticker tape']['width_px'] = false;
			$data['ticker tape']['symbol_item'] = 'ids';
			$data['ticker tape']['iframe_border'] = true;
			$data['ticker tape']['height'] = '78';
			$data['ticker tape']['column_list'] = 'checked=>3,ask,bid,spread,chg,chg_per';
			$data['ticker tape']['src'] = 'https://fxpricing.com/fx-widget/ticker-tape-widget.php?';



			/* single ticker wighet data */
			$data['single ticker']['iframe'] = '<iframe src="https://fxpricing.com/fx-widget/single-ticker-widget.php?id='.esc_attr($widget_single_id).'" width="100%" height="110" style="border: 1px solid #eee;"></iframe>';
			$data['single ticker']['setting'] = 'symbol,theme,value_align,target_link,width,widget_key,flags,column,border,fcs_link,pair_weight';
			$data['single ticker']['width_px'] = false;
			$data['single ticker']['symbol_item'] = 'id';
			$data['single ticker']['iframe_border'] = true;
			$data['single ticker']['height'] = '110';
			$data['single ticker']['column_list'] = 'checked=>2,chg,chg_per,ask,bid';
			$data['single ticker']['src'] = 'https://fxpricing.com/fx-widget/single-ticker-widget.php?';



			/* forex cross wighet data */
			$data['forex cross rates']['iframe'] = '<iframe src="https://fxpricing.com/fx-widget/forex-cross-rates.php?symbol='.esc_attr($widget_symbols).'" width="100%" height="401" style="border: 1px solid #eee;"></iframe>';
			$data['forex cross rates']['setting'] = 'symbol,theme,target_link,width,height,widget_key,flags,border,fcs_link,pair_weight';
			$data['forex cross rates']['width_px'] = false;
			$data['forex cross rates']['symbol_item'] = 'symbol';
			$data['forex cross rates']['iframe_border'] = true;
			$data['forex cross rates']['height'] = '401';
			$data['forex cross rates']['src'] = 'https://fxpricing.com/fx-widget/forex-cross-rates.php?';



			/* technical indicator wighet data */
			$data['technical indicator']['iframe'] = '<iframe src="https://fxpricing.com/fx-widget/technical-indicator-widget.php?id='.esc_attr($widget_single_id).'" width="100%" height="601" style="border: 1px solid #eee;" ></iframe>';
			$data['technical indicator']['setting'] = 'symbol,theme,value_align,width,height,widget_key,flags,border,fcs_link';
			$data['technical indicator']['width_px'] = false;
			$data['technical indicator']['symbol_item'] = 'id';
			$data['technical indicator']['iframe_border'] = true;
			$data['technical indicator']['height'] = '601';
			$data['technical indicator']['src'] = 'https://fxpricing.com/fx-widget/technical-indicator-widget.php?';



			/* simple moving wighet data */
			$data['simple moving']['iframe'] = '<iframe src="https://fxpricing.com/fx-widget/simple-moving-widget.php?id='.esc_attr($widget_single_id).'" width="100%" height="554" style="border: 1px solid #eee;" ></iframe>';
			$data['simple moving']['setting'] = 'symbol,theme,value_align,width,height,widget_key,flags,border,fcs_link';
			$data['simple moving']['width_px'] = false;
			$data['simple moving']['symbol_item'] = 'id';
			$data['simple moving']['iframe_border'] = true;
			$data['simple moving']['height'] = '554';
			$data['simple moving']['src'] = 'https://fxpricing.com/fx-widget/simple-moving-widget.php?';



			/* last candle wighet data */
			$data['last candle']['iframe'] = '<iframe src="https://fxpricing.com/fx-widget/last-candle-widget.php?id='.esc_attr($widget_multi_ids).'" width="100%" height="300" style="border: 1px solid #eee;" ></iframe>';
			$data['last candle']['setting'] = 'symbol,theme,value_align,target_link,width,height,widget_key,flags,column,border,fcs_link,pair_weight';
			$data['last candle']['width_px'] = false;
			$data['last candle']['symbol_item'] = 'ids';
			$data['last candle']['iframe_border'] = true;
			$data['last candle']['height'] = '300';
			$data['last candle']['column_list'] = 'checked=>all,open,high,low,close,time';
			$data['last candle']['src'] = 'https://fxpricing.com/fx-widget/last-candle-widget.php?';



			/* pivot-point-widget data */
			$data['pivot point']['iframe'] = '<iframe src="https://fxpricing.com/fx-widget/pivot-point-widget.php?id='.esc_attr($widget_single_id).'" width="100%" height="444" style="border: 1px solid #eee;" ></iframe>';
			$data['pivot point']['setting'] = 'symbol,theme,value_align,width,height,widget_key,flags,column,border,fcs_link';
			$data['pivot point']['width_px'] = false;
			$data['pivot point']['symbol_item'] = 'id';
			$data['pivot point']['iframe_border'] = true;
			$data['pivot point']['height'] = '444';
			$data['pivot point']['column_list'] = 'checked=>all,classic,fibonacci,camarilla,woodies,demarks';
			$data['pivot point']['src'] = 'https://fxpricing.com/fx-widget/pivot-point-widget.php?';


	    	if(!empty($data[$select_widget])){
	    		if(!empty($fxlive_post_data['fx-hide-iframe']))
	    			$data[$select_widget]['iframe'] = wp_specialchars_decode( $fxlive_post_data['fx-hide-iframe'], ENT_QUOTES );
	    		else
	    			$data[$select_widget]['iframe'] = $data[$select_widget]['iframe'].$widget_powered;

	    		$data[$select_widget]['settings'] = $this->widget_column_display($data[$select_widget], $data[$select_widget]['symbol_item']);
	    		$data[$select_widget]['label'] = ucwords($select_widget);

	    		echo json_encode($data[$select_widget]);
	    	}
	    	else{
	    		echo 'false';
	    	}
	    }
	    else{
	    	echo 'false';
	    }
	    wp_die(); 
	}
	/* widget list preview end */



	/* display which column show */
	function widget_column_display($data_setting=array(), $symbol_item=''){
		$setting_array = explode(',', $data_setting['setting']);
        $setting_echo = '';
        global $widget_multi_ids, $widget_single_id, $widget_symbols;

        $is_align_theme = false;
        if(preg_match('/theme,value_align/is', $data_setting['setting'])){
          $is_align_theme = true;
        }

        $is_border_link = false;
        if(preg_match('/border,fcs_link/is', $data_setting['setting'])){
          $is_border_link = true;
        }

        $is_widget_key_height = false;
        if(preg_match('/height,widget_key/is', $data_setting['setting'])){
          $is_widget_key_height = true;
        }

        $is_widget_key_flags = false;
        if(!$is_widget_key_height && 
          preg_match('/widget_key,flags/is', $data_setting['setting']))
        {
          $is_widget_key_flags = true;
        }

        /* column select list */
        $column_list = '';
        if(!empty($data_setting['column_list'])){
          $column_list = $data_setting['column_list'];
        }

        /* symbol selected list get */
        $widget_ids = '';
        if($symbol_item == 'symbol')
        	$widget_ids = $widget_symbols;
        else if(preg_match('/ids|symbol/is', $symbol_item))
        	$widget_ids = $widget_multi_ids;
        else
        	$widget_ids = $widget_single_id;

        if (is_array($setting_array)) {
	        foreach ($setting_array as $key => $setting_name) {
	          if(empty($setting_name))
	            continue;
	          $setting_echo .= $this->widget_setting($setting_name, $data_setting['width_px'], $data_setting['symbol_item'], $data_setting['height'], $is_align_theme, $column_list, $is_border_link, $widget_ids, $is_widget_key_height, $is_widget_key_flags);
	        }
	    }

        return $setting_echo;
	}
	/* display which column show end */



	/* widget column setting */
	function widget_setting($setting_name='',$width_px=false,$symbol_item='',$height=300,$is_align_theme=false,$column_list='',$is_border_link=false,$widget_ids='',$is_widget_key_height=false,$is_widget_key_flags=false)
	{
		global $fxlive_post_data;
		$setting_name = trim(strtolower($setting_name));
		$data = array();

	    /* get all symbol list from db */
	    if(!empty($fxlive_post_data['fx-select-list']))
	    	$widget_ids = $fxlive_post_data['fx-select-list'];
	    else if(!empty($fxlive_post_data['fx-get-symbol-list']))
	    	$widget_ids = $fxlive_post_data['fx-get-symbol-list'];

		$data['symbol'] = $this->symbol_list($setting_name, $symbol_item, $widget_ids);

		$theme_aling_col = '';
		$theme_first_div = '';
		$alig_last_div = '';
		if($is_align_theme){
			$theme_aling_col = 'fx-col-md-6 fx-col-sm-12';
			$theme_first_div = '<div class="fx-row">';
			$alig_last_div = '</div>';
		}
		/* widget select theme user */
	    $data['theme'] = $theme_first_div.'
	    	<div class="fx-m-t-20 '.esc_attr($theme_aling_col).'">
	            <label class="fx-m-l-10">'.esc_html__('Widget theme','fx-live-prices').'</label><br>
	            <select class="fx-search-list" id="fx-get-theme-list" name="fx_widget[fx-get-theme-list]">
	              <option value="light" '.			 esc_attr( (isset($fxlive_post_data["fx-get-theme-list"]) && $fxlive_post_data["fx-get-theme-list"] == "light") ? "selected" : "").'>'.esc_html__('Light theme (White Background)','fx-live-prices').'</option>
	              <option value="dark" '.			 esc_attr( (isset($fxlive_post_data["fx-get-theme-list"]) && $fxlive_post_data["fx-get-theme-list"] == "dark") ? "selected" : "").'>'.esc_html__('Dark them (Black Background)','fx-live-prices').'</option>
	              <option value="transparent" '.esc_attr( (isset($fxlive_post_data["fx-get-theme-list"]) && $fxlive_post_data["fx-get-theme-list"] == "transparent") ? "selected" : "").'>'.esc_html__('Transparent Background','fx-live-prices').'</option>
	            </select>
	        </div>';


	    /* widget value align set user */
	    $data['value_align'] = '
	    	<div class="fx-m-t-20 '.esc_attr($theme_aling_col).'">
	            <label class="fx-m-l-10">'.esc_html__('Text align','fx-live-prices').'</label><br>
	            <select class="fx-search-list" id="fx-get-value-align-list" name="fx_widget[fx-get-value-align-list]">
	              <option value="center" '.esc_attr((isset($fxlive_post_data["fx-get-value-align-list"]) && $fxlive_post_data["fx-get-value-align-list"] == "center") ? "selected" : "").'>'.esc_html__('Center','fx-live-prices').'</option>
	              <option value="left" '.		 esc_attr((isset($fxlive_post_data["fx-get-value-align-list"]) && $fxlive_post_data["fx-get-value-align-list"] == "left") ? "selected" : "").'>'.esc_html__('Left','fx-live-prices').'</option>
	              <option value="right" '.	 esc_attr((isset($fxlive_post_data["fx-get-value-align-list"]) && $fxlive_post_data["fx-get-value-align-list"] == "right") ? "selected" : "").'>'.esc_html__('Right','fx-live-prices').'</option>
	            </select>
	        </div>'.$alig_last_div;


	    /* iframe width set user */
	    if(isset($fxlive_post_data["fx-autosize-width"])){
			$width_px = false;
	    	if($fxlive_post_data["fx-autosize-width"] == 'unchecked')
				$width_px = true;
	    }

	    $data['width'] = '
	    	<div class="fx-m-t-20">
	            <label class="fx-m-l-10">'.esc_html__('Width','fx-live-prices').'</label><br>
	            <div class="fx-row">
	              <div class="fx-col">
	                <input id="fx-width" type="text" min="1" name="fx_widget[fx-width]" value="'.esc_attr( !empty($fxlive_post_data["fx-width"]) ? $fxlive_post_data["fx-width"] : "300").'" class="fx-type-amount">
	              </div>
	              <div class="fx-col fx-checkbox-style-cust">
	                <input name="fx_widget[fx-autosize-width]" type="checkbox" value="checked" id="fx-autosize-width" '. esc_attr($width_px ? "" : "checked").'>
	                <label for="fx-autosize-width">'.esc_html__('Autosize','fx-live-prices').'</label>
	                <div class="fx-m-l-10 fx-m-b-10">
	                  <i class="fa fa-question-circle fa-lg fx-tooltip" aria-hidden="true">
	                  	<span class="fx-tooltiptext fx-tooltiptext-large">'.esc_html__('When autosize is checked, the widget uses 100% of available width of the enclosing element.','fx-live-prices').'</span>
	                  </i>
	                </div>
	              </div>
	            </div>
	        </div>';


	    $hei_wid_col = '';
		$hei_wid_first_div = '';
		$hei_wid_last_div = '';
		if($is_widget_key_height){
			$hei_wid_col = ' fx-col-md-6 fx-col-sm-12 ';
			$hei_wid_first_div = '<div class="fx-row">';
			$hei_wid_last_div = '</div>';
		}

		$wid_flag_col = '';
		$wid_flag_first_div = '';
		$wid_flag_last_div = '';
		if($is_widget_key_flags){
			$wid_flag_col = ' fx-col-md-6 fx-col-sm-12 ';
			$wid_flag_first_div = '<div class="fx-row">';
			$wid_flag_last_div = '</div>';
		}

	    /* iframe height set user */
	    $data['height'] = $hei_wid_first_div.'
	    	<div class="fx-m-t-20 '.esc_attr($hei_wid_col).'">
	            <label class="fx-m-l-10">'.esc_html__('Height','fx-live-prices').'</label><br>
	            <input id="fx-height" name="fx_widget[fx-height]" type="text"  min="1" value="'.esc_attr( !empty($fxlive_post_data["fx-height"]) ? $fxlive_post_data["fx-height"] : $height).'" class="fx-type-amount">
	        </div>';

	    /* paid user set widget key */
	    $data['widget_key'] = $wid_flag_first_div.'
	    	<div class="fx-m-t-20 '.esc_attr($hei_wid_col.$wid_flag_col).'">
	            <label class="fx-m-l-10">
	            	'.esc_html__('Widget key','fx-live-prices').' <span>(<a href="https://fcsapi.com/login" target="_blank">'.esc_html__('paid user','fx-live-prices').'</a>) </span>
                  <i class="fa fa-question-circle fa-lg fx-tooltip fx-m-l-5" aria-hidden="true">
                  	<span class="fx-tooltiptext">To get the widget key, go to the <a href="https://fcsapi.com/login" target="_blank" class="fx-info">FCSAPI</a> website.</span>
                  </i>
	            </label><br>
	            <input id="fx-widget-key" name="fx_widget[fx-widget-key]" type="text" value="'.esc_attr( isset($fxlive_post_data["fx-widget-key"]) ? $fxlive_post_data["fx-widget-key"] : "").'" placeholder="'.esc_html__('Enter your widget key','fx-live-prices').'">
	        </div>'.$hei_wid_last_div;


	    /* widget flags style select user */
	    $data['flags'] = '
	    	<div class="fx-m-t-20 '.esc_attr($wid_flag_col).'">
	            <label class="fx-m-l-10">'.esc_html__('Flags style','fx-live-prices').'</label><br>
	            <select class="fx-search-list" id="fx-get-flags-list" name="fx_widget[fx-get-flags-list]">
	              <option value="flags-circle" '.		esc_attr( (isset($fxlive_post_data["fx-get-flags-list"]) && $fxlive_post_data["fx-get-flags-list"] == "flags-circle") ? "selected" : "").'>'.	esc_html__('Flags circle shape','fx-live-prices').'</option>
	              <option value="flags-rectangle" '.	esc_attr( (isset($fxlive_post_data["fx-get-flags-list"]) && $fxlive_post_data["fx-get-flags-list"] == "flags-rectangle") ? "selected" : "").'>'.esc_html__('Flags rectangle shape','fx-live-prices').'</option>
	              <option value="flags-hide" '.			esc_attr( (isset($fxlive_post_data["fx-get-flags-list"]) && $fxlive_post_data["fx-get-flags-list"] == "flags-hide") ? "selected" : "").'>'.		esc_html__('Flags hide','fx-live-prices').'</option>
	            </select>
	        </div>'.$wid_flag_last_div;



	    /* custome url link click */
	    $data['target_link'] = '
	    	<div class="fx-row">
	    		<div class="fx-m-t-20 fx-col-md-6 fx-col-sm-12">
		            <label class="fx-m-l-10">
		            	'.esc_html__('Target click','fx-live-prices').'
		            	<i class="fa fa-question-circle fa-lg fx-tooltip fx-m-l-5 fx-m-l-5" aria-hidden="true">
		                  <span class="fx-tooltiptext fx-tooltiptext-large">'. esc_html__('When user click on Currency name/link, what will happen? Open details it in new tab or in same tab?','fx-live-prices') .' <br> '.esc_html__('Paid users need to enter Widget_key','fx-live-prices').'</span>
		                </i>
		            </label><br>
		            <select class="fx-search-list" id="fx-target-click" name="fx_widget[fx-target-click]">
		              <option value="blank" '.		esc_attr((isset($fxlive_post_data["fx-target-click"]) && $fxlive_post_data["fx-target-click"] == "blank") ? "selected" : "").'>'.esc_html__('Open in new tab','fx-live-prices').'</option>
		              <option value="current" '.	esc_attr((isset($fxlive_post_data["fx-target-click"]) && $fxlive_post_data["fx-target-click"] == "current") ? "selected" : "").'>'.esc_html__('Open in same tab - (Paid users only)','fx-live-prices').'</option>
		              <option value="disable" '.	esc_attr((isset($fxlive_post_data["fx-target-click"]) && $fxlive_post_data["fx-target-click"] == "disable") ? "selected" : "").'>'.esc_html__('Hide all links - (Paid users)','fx-live-prices').'</option>
		            </select>
		        </div>
		        <div class="fx-m-t-20 fx-col-md-6 fx-col-sm-12">
		            <label class="fx-m-l-10">
		            	'.esc_html__('Enter custom target URL','fx-live-prices').'
		            	<i class="fa fa-question-circle fa-lg fx-tooltip fx-m-l-5" aria-hidden="true">
		                	<span class="fx-tooltiptext fx-tooltiptext-large">'.esc_html__('Default currency pairs are linked to FXPricing. You can use your custom URL to redirect it when user click on any currency link. (Note: it will use same link on all currency links)','fx-live-prices').'</span>
		                </i>
		            </label><br>
		            <input id="fx-target-link" name="fx_widget[fx-target-link]" type="text" value="'.esc_attr((isset($fxlive_post_data["fx-target-link"])) ? $fxlive_post_data["fx-target-link"] : "").'" placeholder="'.esc_html__('https://yourdomain.com','fx-live-prices').'"
		            	'. esc_attr((isset($fxlive_post_data["fx-target-click"]) && $fxlive_post_data["fx-target-click"] == "disable") ? "readonly" : "") .' >
		        </div>
		    </div>';

	    /* user select columns */
	    if(!empty($column_list)){
	    	if(preg_match('/checked=>all/is', $column_list)){
	    		$column_msg = 'all';
	    	}
	    	else{
	    		preg_match('/checked=>(.*?),/is', $column_list, $msg);
	    		$column_msg = @$msg[1];
	    	}

		    $columns = '
		    	<div class="fx-m-t-20 fx-m-l-10" id="fx-columns">
		    		<label>'.esc_html__('Available data/columns','fx-live-prices').'</label>
		    		<p>You can show '.$column_msg.' of below columns in the widget.</p>
		            <div class="fx-row">';
		    
		    $columns_array = explode(',', $column_list);
		    $checked_columns = 0;
		    if (is_array($columns_array)) {
			    foreach ($columns_array as $key => $value) {
			    	if($key == 0){
			    		if($value == 'checked=>all'){
			    			$checked_columns = count($columns_array);
			    		}
			    		else{
			    			$checked_columns = $column_msg;
			    		}

			    		continue;
			    	}

			    	// $title_show and $vaule dont need to "esc_attr" its pre_defined
			    	$title_show = ucfirst($value);
			    	if($value == 'chg')
			    		$title_show = 'Change';
			    	else if($value == 'chg_per')
			    		$title_show = 'Change%';

			    	if(!empty($fxlive_post_data["fx-column-select"]))
			    	{
			    		$columns .= "
				    		<div class='fx-checkbox-style-cust fx-col-6'>
				              <input type='checkbox' class='fx-column-select' id='fx-$value' name='fx_widget[fx-column-select][]' ".esc_attr((in_array($value, $fxlive_post_data["fx-column-select"])) ? 'checked' : '')." value='$value'>
				              <label for='fx-$value'>$title_show</label>
				            </div>";
			    	}
			    	else{
				    	$columns .= "
				    		<div class='fx-checkbox-style-cust fx-col-6'>
				              <input type='checkbox' class='fx-column-select' id='fx-$value' name='fx_widget[fx-column-select][]' ".esc_attr(($checked_columns < $key) ? '' : 'checked')." value='$value'>
				              <label for='fx-$value'>$title_show</label>
				            </div>";
			        }
			    }
			}

		    $columns .= '</div>
		        </div>';

		    $data['column'] = $columns;
		}
		else{
			$data['column'] = '';
		}


		$col = '';
		$first_div = '';
		$last_div = '';
		if($is_border_link){
			$col = ' fx-col-6 ';
			$first_div = '<div class="fx-row">';
			$last_div = '</div>';
		}
		/* hide,show iframe border */
	    $data['border'] = $first_div.'
	    	<div class="fx-m-t-20 '.esc_attr($col).'">
	    		<label class="fx-m-l-10">'.esc_html__('Border','fx-live-prices').'</label><br>
	            <div class="fx-m-l-10 fx-checkbox-style-cust">
	              <input type="checkbox" id="fx-border-hide" name="fx_widget[fx-border-hide]" value="checked" '.esc_attr((isset($fxlive_post_data["fx-border-hide"]) && $fxlive_post_data["fx-border-hide"] == "checked") ? "checked" : "").'>
	              <label for="fx-border-hide">'.esc_html__('Hide main border','fx-live-prices').'</label>
	            </div>
	        </div>';


		/* fcs link bottom show or hide */
	    $data['fcs_link'] = '
	    	<div class="fx-m-t-20 '.esc_attr($col).'">
	    		<label class="fx-m-l-10">
	    			'.esc_html__('FX logo','fx-live-prices').' <small>(<a href="https://fcsapi.com/login" target="_blank">'.esc_html__('Paid users','fx-live-prices').'</a>) </small>
	    			<i class="fa fa-question-circle fa-lg fx-tooltip fx-m-l-5" aria-hidden="true">
	                  	<span class="fx-tooltiptext fx-tooltiptext-large">'.esc_html__('To hide Branding you need to enter widget key. To get the widget key, visit','fx-live-prices').' <a href="https://fcsapi.com/login" target="_blank" class="fx-info">FCS API</a></span>
	                </i>
	    		</label><br>
	            <div class="fx-m-l-10 fx-checkbox-style-cust">
	              <input type="checkbox" id="fx-fcs-link" name="fx_widget[fx-fcs-link]" value="checked" '.esc_attr((isset($fxlive_post_data["fx-fcs-link"]) && $fxlive_post_data["fx-fcs-link"] == "checked") ? "checked" : "").'>
	              <label for="fx-fcs-link">'.esc_html__('Hide branding logo','fx-live-prices').'</label>
	            </div>
	        </div>'.$last_div;


		/* pair font weight bold or normal */
	    $data['pair_weight'] = '
	    	<div class="fx-m-t-20">
	    		<label class="fx-m-l-10">'.esc_html__('Currency font weight','fx-live-prices').'</label> (<small for="fx-pair-weight">'.esc_html__('Default font weight is bold','fx-live-prices').'</small>)<br>
	            <div class="fx-m-l-10 fx-checkbox-style-cust">
	              <input type="checkbox" id="fx-pair-weight" name="fx_widget[fx-pair-weight]" value="checked" '.esc_attr((isset($fxlive_post_data["fx-pair-weight"]) && $fxlive_post_data["fx-pair-weight"] == "checked") ? "checked" : "").'>
	              <label for="fx-pair-weight">'.esc_html__('Normal/Thin font','fx-live-prices').'</label>
	            </div>
	            
	        </div>';


	    /* speed set user */
	    $data['speed'] = '
	    	<div class="fx-m-t-20">
              <label class="fx-m-l-10">'.esc_html__('Slide speed','fx-live-prices').' <small>('.esc_html__('second','fx-live-prices').')</small></label><br>
              <div class="fx-row">
                <div class="fx-col">
                  <input id="fx-speed-second" type="text" name="fx_widget[fx-speed-second]" value="'.esc_attr(isset($fxlive_post_data["fx-speed-second"]) ? $fxlive_post_data["fx-speed-second"] : 6 ).'" class="fx-type-amount" placeholder="second" '.esc_attr((!empty($fxlive_post_data["fx-noslide"]) && $fxlive_post_data["fx-noslide"] == "checked") ? "disabled" : "").'>
                </div>
                <div class="fx-col fx-checkbox-style-cust">
                  <input type="checkbox" id="fx-noslide" name="fx_widget[fx-noslide]" value="checked" '.esc_attr((isset($fxlive_post_data["fx-noslide"]) && $fxlive_post_data["fx-noslide"] == "checked") ? "checked" : "").'>
                  <label for="fx-noslide">'.esc_html__('No Slide','fx-live-prices').'</label>
                </div>
              </div>
          	</div>';

	    return $data[$setting_name];
	}
	/* widget column setting end */



	/* symbol list create */
	function symbol_list($setting_name, $symbol_item='', $widget_ids='')
	{
		if($setting_name != 'symbol')
			return false;

		global $fxlive_post_data;

		$list_forex = json_decode(fxlive_forex_symbol_list(), true);

		$class_w = 'fx-col-12';
		if(preg_match('/ids|symbol/is', $symbol_item)){
			$class_w = ' fx-col-lg-11 fx-col-10 ';
			$select_symbol = '<div class="fx-symbol-multiple-item-add fx-p-l-10">';
		}

		$list = '<div class="fx-m-t-20">
	        <label class="fx-m-l-10">'.esc_html__('Add currency symbols in widgets','fx-live-prices').'</label><br>';

	    $ids = explode(',', strtolower($widget_ids));

        $list .= '<div class="fx-row">
          <div class="'.esc_attr($class_w).'">
            <select class="fx-search-list" id="fx-get-symbol-list" name="fx_widget[fx-get-symbol-list]">';

        if (is_array($list_forex)) {
	        foreach ($list_forex as $key => $value) {
	        	$value["id"]			= esc_attr($value["id"]);
	        	$value["symbol"]	= esc_attr($value["symbol"]);

	        	if( !empty($ids) && 
	        		($value["id"] == $ids[count($ids)-1] || strtolower($value["symbol"]) == $ids[count($ids)-1])
	        	){
	        		$list .= '<option value="'.$value["id"].'" selected>'.$value["symbol"].'</option>';
	        	}else{
	        		$list .= '<option value="'.$value["id"].'">'.$value["symbol"].'</option>';
	        	}

	        	if(preg_match('/ids|symbol/is', $symbol_item) && 
	        		(in_array($value["id"], $ids) ||  in_array(strtolower($value["symbol"]), $ids))
	        	){
	        		$select_symbol .= '
	        		<div class="fx-row fx-symbol-add-item-main fx-m-b-5">
		                <div class="fx-col-lg-11 fx-col-10">
		                  <div class="fx-symbol-id" data-symbol-id="'.$value["id"].'">'.$value["symbol"].'</div>';

		            if($symbol_item == 'symbol'){
		                $select_symbol .= '<input type="hidden" name="fx_widget[fx-select-list][]" value="'.$value["symbol"].'">';
		            }
		            else{
		            	$select_symbol .= '<input type="hidden" name="fx_widget[fx-select-list][]" value="'.$value["id"].'">';
		            }

		           	$select_symbol .= '</div>
		                <div class="fx-col-lg-1  fx-col-2">
		                  <div class="fx-square-sign fx-symbole-remove-item">-</div>
		                </div>
		            </div>';
	        	}
	        }
	    }

        if(preg_match('/ids|symbol/is', $symbol_item)){
        	$select_symbol .= '</div>';
        }
        $list .= '</select></div>';

      	if(preg_match('/ids|symbol/is', $symbol_item)){
			$list .= '
				<div class="fx-col-lg-1 fx-col-2 fx-square-sign-main p-r-0">
					<div class="fx-square-sign fx-plus" id="fx-symbol-item-add">+</div>
					</div>';
      	}
        $list .= '</div></div>';

        if(preg_match('/ids|symbol/is', $symbol_item))
        	return $select_symbol.$list;

	    return $list;
	}
	/* symbol list create end */



	/* widget list right side */
	function register_side_publish() {
	    add_meta_box( 
	        'fxlive-side-publish',
	        esc_html__( 'Publish', 'fx-live-prices' ),
	        'fxlive_widget_publish_side',
	        'fx-pricing-widget',
	        'side'
	    );
	}
	/* widget list right side end */



	/* widget all setting */
	function register_widget_setting() {
	    add_meta_box( 
	        'fxlive-all-setting-meta',
	        esc_html__( 'Add New Widget', 'fx-live-prices' ),
	        'fxlive_widget_setting_display',
	        'fx-pricing-widget'
	    );
	}
	/* widget all setting end */

}
