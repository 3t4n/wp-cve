<?php
/*
Plugin Name: Loan Comparison
Plugin URI: https://loanpaymentplugin.com/
Description: Loan Repayment Comparisons.
Version: 1.6.2
Author: aerin
Author URI: https://quick-plugins.com/
Domain Path: /languages
*/

require_once( plugin_dir_path( __FILE__ ) . '/options.php' );
require_once( plugin_dir_path( __FILE__ ) . '/constants.php' );

add_shortcode('loancomparison', 'loancomparison_loop');
add_shortcode('loancomparisontable', 'loancomparison_table');
add_action('wp_enqueue_scripts', 'loancomparison_scripts');
add_filter('plugin_action_links', 'loancomparison_plugin_action_links', 10, 2 );
add_action('init', 'loancomparison_init');
add_action('wp_head', 'loancomparison_head_css');
add_action('init', 'loancomparison_lang_init');

// Languages

function loancomparison_lang_init() {
	load_plugin_textdomain( 'loan-comparison', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

function loancomparison_init() {
	
	load_plugin_textdomain( 'loan-comparison', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	
	if ( !function_exists( 'register_block_type' ) ) {
		return;
	}
	
	$loancomparisonkey = loancomparison_get_stored_key();

	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		wp_register_script(
			'premium',
			plugins_url( 'premium.js', __FILE__ ),
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
		);

		register_block_type(
			'loancomparison/premium', array(
				'attributes' => array(
					'table'  => array(
						'type'=> 'string',
						'default'   => '1'
					),
				),
				'editor_script'   => 'premium',
				'render_callback' => 'loancomparison_loop'
			)
		);
	} else {
		wp_register_script(
			'free',
			plugins_url( 'free.js', __FILE__ ),
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
		);

		register_block_type(
			'loancomparison/free', array(
				'editor_script'   => 'free',
				'render_callback' => 'loancomparison_loop'
			)
		); 
	}
}

if (is_admin()) require_once( plugin_dir_path( __FILE__ ) . '/settings.php' );

function loancomparison_table($atts) {
	
	//loancomparison_get_stored_upgrade();
	
	if (!$atts['table']) $atts['table'] = '1';
	$settings   = loancomparison_get_stored_settings($atts['table']);
	$hidden = $settings['hidelistsliders'] ? 'hidden' : '';
	
	$atts = shortcode_atts(array(
		'table'			=> '',
		'slider'		=> '',
		'filter'		=> '',
		'filters'		=> '',
		'rating'		=> '',
		'sorting'		=> '',
		'offers'		=> '',
		'bank'			=> '',
		'moreinfo'		=> '',
		'interest'		=> '',
		'zeropercent'	=> '',
		'ratespage'		=> true,
		'loaninitial'	=> '',
		'periodinitial'	=> '',
	),$atts,'loan-comparison');
	
	if (isset($_GET['amount']) && $_GET['amount'])	$_GET['amount']		= esc_attr($_GET['amount']);
	if (isset($_GET['term']) && $_GET['term'])		$_GET['term']		= esc_attr($_GET['term']);
	if (isset($_GET['filters']) && $_GET['filters'])$_GET['filters']	= esc_attr($_GET['filters']);
	
	if (isset($_GET['amount']) && $_GET['amount'])	$atts['loaninitial'] = $_GET['amount'];
	if (isset($_GET['term']) && $_GET['term'])		$atts['periodinitial'] = $_GET['term'];
	if (isset($_GET['filters']) && $_GET['filters'])$atts['filters'] = $_GET['filters'];
	
	return loancomparison_display($atts);
}

function loancomparison_loop($atts) {
	
	//loancomparison_get_stored_upgrade();
	
	$atts = shortcode_atts(array(
		'table'			=> '',
		'slider'		=> '',
		'filter'		=> '',
		'filters'		=> '',
		'rating'		=> '',
		'sorting'		=> '',
		'offers'		=> '',
		'bank'			=> '',
		'moreinfo'		=> '',
		'interest'		=> '',
		'zeropercent'	=> '',
		'ratespage'		=> false,
		'loaninitial'	=> '',
		'periodinitial'	=> '',
	),$atts,'loan-comparison');
	
	if (isset($_GET['amount']) && $_GET['amount'])	$_GET['amount']		= esc_attr($_GET['amount']);
	if (isset($_GET['term']) && $_GET['term'])		$_GET['term']		= esc_attr($_GET['term']);
	if (isset($_GET['filters']) && $_GET['filters'])$_GET['filters']	= esc_attr($_GET['filters']);
	
	if (isset($_GET['amount']) && $_GET['amount'])	$atts['loaninitial'] = $_GET['amount'];
	if (isset($_GET['term']) && $_GET['term'])		$atts['periodinitial'] = $_GET['term'];
	if (isset($_GET['filters']) && $_GET['filters'])$atts['filters'] = $_GET['filters'];
	
	return loancomparison_display($atts);
}

function loancomparison_get_sortby($settings) {
	$sb = array();
	$sort = explode(',',$settings['columnsort']);
	foreach($sort as $item) {
		$ta = ['name' => $item, 'checked' => ($settings['sortby_'.$item]?? '') == 'checked'];
		$sb[] = $ta;
	}
	return $sb;
}

function loancomparison_get_sortby_sorting($settings) {
	$sb = array();
	$sort = explode(',',$settings['sortby']);
	foreach($sort as $item) {
			
		$ta = ['name' => $item, 'checked' => ($settings['sort_'.$item]?? '') == 'checked'];
		
		$sb[] = $ta;
		
	}
	
	return $sb;
}

function loancomparison_display($atts) {

	$singlestyle = $filter1=$filter2=$filter3=$filter4=$filter5=$filter6=false;
	
	if (!$atts['table']) $atts['table'] = '1';
	
	$settings			= loancomparison_get_stored_settings($atts['table']);
	$style				= loancomparison_get_stored_style();
	$loancomparisonkey	= loancomparison_get_stored_key();
	$interest			= loancomparison_get_stored_interest($atts['table']);
	
	$settings['singlebank'] = false;
	
	if ($atts['loaninitial'])   $settings['loaninitial'] = $atts['loaninitial'];
	if ($atts['periodinitial']) $settings['periodinitial'] = $atts['periodinitial'];
	
	$sb = loancomparison_get_sortby($settings);
	
	$cC = 0;
	foreach ($sb as $v) {
		
		if ($v['checked']) $cC++;
		
	}

	if ($atts['bank']) {
		
		if (!strpos($atts['bank'],',') && $settings['hideapply']) $settings['singlebank'] = true;
		$atts['termslider'] = $atts['loanslider'] = 'hidden';
		$singlestyle = '<style>.bank_details {width:20%;}</style>';

		$settings['formtitle'] = $settings['showfeatures'] = $settings['showheader'] = $settings['showsorting'] = $settings['barchartenabled'] = $settings['showfilters'] = $settings['showoffers'] = false;
		if ($atts['moreinfo']) $settings['showmoreinfo'] = false;
		
		foreach ($interest as $k => $v) {
			if (strpos($atts['bank'],$v['alt']) === false) unset($interest[$k]);
		}

	}
	if ($settings['hideallapply']) $settings['singlebank'] = true;
	$startCols = $cC;
	$startPercent = 66.66666666666666;
	if ($loancomparisonkey && $settings['columnsorting']) {
		
		if ($startCols == 0) $startCols = 1;

		if ($settings['singlebank']) {
			$startPercent += 16.66666666666667;
		}
		
		$singlestyle = '<style>.bank_details {width:'.($startPercent / $startCols).'%;}</style>';
		
	}
	
	$settings['alternate'] = $style['bank-alternate'];
	if ($settings['loanhide']) $atts['loanslider'] = 'hidden';
	if ($settings['termhide']) $atts['termslider'] = 'hidden';
	
	if ($atts['rating']) $settings['showrating'] = true;
	if ($atts['sorting']) $settings['showsorting'] = true;
	if ($atts['filter']) $settings['showfilters'] = true;
	if ($atts['filter'] == 'hidden') $settings['showfilters'] = false;
	if ($atts['offers']) $settings['showoffers'] = true;
	$settings['interest'] = $atts['interest'];
	$settings['zeropercent'] = $atts['zeropercent'];
	if ($settings['simpleinterest']) $settings['interest'] = 'simple';
	if ($settings['columnsorting']) $settings['columnsorting'] = true;
	
	$filters = str_split($atts['filters']);
	
	$settings['filterquery'] = $atts['filters'];
	
	foreach ($filters as $item) {
		${'filter'.$item} = ' checked';
	}
	
	if ($atts['filter'] == 1) $filter1 = ' checked';
	if ($atts['filter'] == 2) $filter2 = ' checked';
	if ($atts['filter'] == 3) $filter3 = ' checked';
	if ($atts['filter'] == 4) $filter4 = ' checked';
	if ($atts['filter'] == 5) $filter5 = ' checked';
	if ($atts['filter'] == 6) $filter6 = ' checked';

	// Normalize values
	$outputA = array();
	foreach ($settings as $k => $v) {
		$outputA[$k] = $v;
		
		if (is_array($v)) {
			$outputA[$k] = $v;
			continue;
		}
		
		if (strtolower($v) == 'checked') $outputA[$k] = true;
		
		if ($v == '') $outputA[$k] = false;
		
		if (in_array($k,array('loaninitial','maxrating','numbertoshow','periodinitial'))) $outputA[$k] = (float) $v;
		//if (is_numeric($v) && (preg_match('/^[0-9.]+/',$v))) $outputA[$k] = (float) $v;
		//if (preg_match('/[0-9.]+/',$v)) $outputA[$k] = (float) $v;
	}
	
	$stepper = array('term' => array(),'principal' => array());
	
	// Calculate Principal Steps
	$steps = array();
	$steps[0] = array('loan' => 0);
	foreach ($settings['loan'] as $k => $v) {
		if (!$v['step']) continue;
		if ($v['step']) $s = ceil((((int)$v['max'] - (int)$v['min']) / (int)$v['step']) + 1);
		for ($i = 0; $i < $s; $i++) {
			// Start with min
			if ($i == 0) $steps[] = array('amount' => (int)$v['min'] * 1);
			elseif ($i == $s) $steps[] = array('amount' => (int)$v['max'] * 1);
			else {
				$steps[] = array('amount' => ((int)$v['step'] * $i) + (int)$v['min']);
			}
		}
	}
	$stepper['principal'] = $steps;

	$principlemin = $steps[1]['amount'];
	
	// Calculate Term Steps
	$steps = array();
	$steps[0] = array('term' => 0, 'period' => 0);
	foreach ($settings['period'] as $k => $v) {
		if (!$v['step']) continue;
		if ($v['step']) $s = ceil((((int)$v['max'] - (int)$v['min']) / (int)$v['step']) + 1);
		for ($i = 0; $i < $s; $i++) {
			// Start with min
			if ($i == 0) $steps[] = array('term' => (int)$v['min'] * 1, 'period' => $v['term']);
			elseif ($i == ($s - 1)) $steps[] = array('term' => (int)$v['max'] * 1, 'period' => $v['term']);
			else {
				$steps[] = array('term' => ((int)$v['step'] * $i) + (int)$v['min'], 'period' => $v['term']);
			}
		}
	}
	$stepper['term'] = $steps;
	
	$termmax = count($steps) - 1;
	
	$newArray = array();
	foreach ($interest as $k => $v) {
		$v['info1'] = str_replace('[infolink]','<a href="'.$v['infolink'].'">'.$settings['infolinkanchor'].'</a>',$v['info1']);
		$v['info2'] = str_replace('[infolink]','<a href="'.$v['infolink'].'">'.$settings['infolinkanchor'].'</a>',$v['info2']);
		$v['info3'] = str_replace('[infolink]','<a href="'.$v['infolink'].'">'.$settings['infolinkanchor'].'</a>',$v['info3']);
		$v['info4'] = str_replace('[infolink]','<a href="'.$v['infolink'].'">'.$settings['infolinkanchor'].'</a>',$v['info4']);
		if (!$v['logo']) $v['logo'] = plugins_url( 'images/placeholder.jpg', __FILE__ );
		if ($v['min_loan'] < $principlemin) $v['min_loan'] = "$principlemin";
		if ($v['min_term'] < 1) $v['min_loan'] = "1";
		if ($v['max_term'] > $termmax) $v['max_term'] = "$termmax";
		$k = (int) str_replace('_','',$k);
		$newArray[]	= array('term' => $k);
		end($newArray);
		
		foreach ($v as $key => $value) {
			$newArray[key($newArray)][$key] = $value;
		}
	}

	$outputA['ribbonlabel1'] = $settings['ribbonlabel1'];
	$outputA['ribbonlabel2'] = $settings['ribbonlabel2'];
	$outputA['ribbonlabel3'] = $settings['ribbonlabel3'];
	$outputA['ribbonlabel4'] = $settings['ribbonlabel4'];
	$outputA['ribbonlabel5'] = $settings['ribbonlabel5'];
	$outputA['ribbonlabel6'] = $settings['ribbonlabel6'];
	
	// Build Output for the JS
	$outputA['rates'] = $newArray;
	
	$outputA['steps'] = $stepper;
	$outputA['term_slider_hidden'] = ((isset($atts['termslider']) && $atts['termslider'] == 'hidden')? true:false);
	$outputA['loan_slider_hidden'] = ((isset($atts['loanslider']) && $atts['loanslider'] == 'hidden')? true:false);
	
	$outputA['filter_hidden'] = ((isset($atts['filter']) && $atts['filter'] == 'hidden')? true:false);
	$outputA['showbankfilters'] = (isset($settings['showbankfilters']) ? true:false);
	$termslider_hidden = (($outputA['term_slider_hidden'])? ' loancomparison-termslider-hidden':'');
	$loanslider_hidden = (($outputA['loan_slider_hidden'])? ' loancomparison-loanslider-hidden':'');
	$termslider_full = (($outputA['loan_slider_hidden'])? ' loancomparison-termslider-full':'');
	$loanslider_full = (($outputA['term_slider_hidden'])? ' loancomparison-loanslider-full':'');
	$outputA['plugin_url'] = plugin_dir_url(__FILE__);
	$outputA['ratespage'] = $atts['ratespage'];
	$outputA['getamt'] = ($_GET['amount'] ?? '');
	$outputA['gettrm'] = ($_GET['term']?? '');
	
	// Build the Output
	
	$outputA['columnorder'] = $sb;
	$outputA['authorized'] = (($loancomparisonkey['authorised'])? true:false);

	wp_add_inline_script( 'loancomparison_script', 'sc_rates = '.json_encode($outputA).';','before' ); 
	
	$output = $singlestyle.'<form action="" class="loancomparison_form" method="POST" id="loancomparison_form">';
	
	// Title and Features
	if ($settings['formtitle'] && $loancomparisonkey['authorised']) {
		$output .= '<h2>'.$settings['formtitle'].'</h2>';
	}
	if ($settings['showfeatures'] && $loancomparisonkey['authorised']) {
		$output .= '<p class="features"><i class="fa fa-check" aria-hidden="true"></i> '.$settings['feature1'].' <i class="fa fa-check" aria-hidden="true"></i> '.$settings['feature2'].' <i class="fa fa-check" aria-hidden="true"></i> ' .$settings['feature3'].'</p>';
	}

	if ($atts['ratespage'] && $settings['hidelistsliders'] && $settings['borrowinglabel']) {
		
		$settings['borrowinglabel'] = str_replace('[amount]', '<span class="loan-amount"></span>', $settings['borrowinglabel']);	
		$settings['borrowinglabel'] = str_replace('[term]', '<span class="loan-term"></span>', $settings['borrowinglabel']);	
		
		$output .= '<p>'.$settings['borrowinglabel'].'</p>';
	}
	
	if ($settings['barchartenabled'] && $loancomparisonkey['authorised']) {
		$output .= '<div id="bargraph"></div>';
	}

	$output .= '<div class="loancomparison_form_header">
	<div class="loancomparison_sliders">';
	
	// Amount Slider  
	$output .= '<div class="loancomparison-range loancomparison-slider-principal loancomparison-slider-'.$atts['slider'].$loanslider_hidden.$loanslider_full.'">
		<div class="loancomparison_slider_output">';
			if ($settings['nobuttons']) {
				$output .= '<span class="output-number output-left">'.$settings['loanslider'].'</span><span class="output-number output-right"><output></output></span>';
			} else {
				$output .= '<div class="output-pad"><span class="circle-down circle-control"></span><span class="output-number"><span class="output-label">'.$settings['loanslider'].'</span> <output></output></span><span class="circle-up circle-control"></span></div>';
			}
	$output .= '</div>
	<input type="range" class="sc_loan_amount" name="loan-amount" min="1" max="'.(count($stepper['principal'])-1).'" value="'.$settings['loaninitial'].'" step="1" data-loancomparison>
	</div>';
	
	// Term Slider
	$output .= '<div class="loancomparison-range loancomparison-slider-term loancomparison-slider-'.$atts['slider'].$termslider_hidden.$termslider_full.'">
		<div class="loancomparison_slider_output">';
			if ($settings['nobuttons']) {
				$output .= '<span class="output-number output-left">'.$settings['termslider'].'</span><span class="output-number output-right"><output></output></span>';
			} else {
				$output .= '<div class="output-pad"><span class="circle-down circle-control"></span><span class="output-number"><span class="output-label">'.$settings['termslider'].'</span> <output></output></span><span class="circle-up circle-control"></span></div>';
			}
	$output .= '</div>
	<input type="range" name="loan-period" min="1" max="'.(count($stepper['term'])-1).'" value="'.$settings['periodinitial'].'" step="1" data-loancomparison>
	</div>
	<div style="clear:both"></div>';
	
	$output .= '</div></div>';
	
	// Credit Score Selection
	if ($settings['showfico'] && $loancomparisonkey['authorised']) {
		
		$output .= '<div class="loancomparison-fico-section">';
		
		if ($settings['ficolabel']) {
			$output .= '<div class="ficolabel">'.$settings['ficolabel'].'</div>';
		}
		$output .= '<div class="loancomparison-fico">';
		$classes = ['verypoor','poor','fair','good','excellent'];
		foreach ($settings['fico'] as $k => $fico) {
			$output .= '<div class="fico-option '.$classes[$k].' '.(($fico['selected'])? 'selected-start':'').'" rel="'.$k.'"><div class="fico-contain"><div class="fico-label">'.$fico['label'].'</div><div class="fico-score">'.$fico['min'].'-'.$fico['max'].'</div></div></div>';
		}
		$output .= '</div>';
		if ($settings['ficolinklabel']) {
			$target = $settings['ficolinktarget'] ? ' target="_blank"' : '';
			$settings['ficolinklabel'] = str_replace('[a]', '<a href="'.$settings['ficolink'].'"'.$target.'>', $settings['ficolinklabel']);
			$settings['ficolinklabel'] = str_replace('[/a]', '</a>', $settings['ficolinklabel']);
			$output .= '<div class="ficolink">'.$settings['ficolinklabel'].'</div>';
		}
		$output .= '</div>';
	}
	
	// Filters
	if (($settings['showfilters'] && $loancomparisonkey['authorised']) || $atts['ratespage']) {
	
		$filtertype = $settings['filtertype'];
		
		if ($filtertype == 'dropdown') {
			
			$output .= '<div class="filters"><span class="sortinglabel">'.$settings['filterlabel'].'</span><select id="filters" name="filters">';
			if ($settings['filterdefault']) $output .= '<option value="">'.$settings['filterdefault'].'</option>';
			for ($i=1; $i<16; $i++) {
				if ($settings['filterlabel'.$i]) $output .= '<option value="filter'.$i.'">'. $settings['filterlabel'.$i] . '</option>';
			}
			$output .= '</select></div>';   
			
		} else {
			
			$output .= '<div class="loancomparison-filterlabel"><ul>';
		
		
			if ($settings['filterlabel']) $output .= '<li class="label">'.$settings['filterlabel'].'</li>';
		
			for ($i = 1; $i <= 15; $i++) {
				if ($settings['filterlabel'.$i]) $output .= '<li class="check"><div class="loancomparison_checkbox"><input type="checkbox" class="loancomparison_filter" name="filter'.$i.'" id="filter'.$i.'" value="checked"'.${'filter'.$i}.'><label for="filter'.$i.'"></label></div><span>'.$settings['filterlabel'.$i].'</span></li>';
			}

			$output .= '</ul></div>';
			
		}
	}
	
	if (!$settings['replacebutton'] || $atts['ratespage']) {
		
		// Bank Filters
		if ($settings['showbankfilters'] && $loancomparisonkey['authorised']) {
			
			$filtertype = $settings['bankfiltertype'];
			
			if ($filtertype == 'dropdown') {
				
				$output .= '<div class="filters"><span class="sortinglabel">'.$settings['bankfilterlabel'].'</span><select id="bankfilters" name="bankfilters">';
				$output .= '<option value="" selected="selected"></option>';
				
				foreach (array_reverse($interest) as $k => $v) {
					if ($v['bankfilter']) $output .= '<option value="filter'.$k.'">'.$v['alt'].'</option>';  
				}
			
				$output .= '</select></div>'; 
			
			} else {	
			
				$output .= '<div class="loancomparison-filterlabel"><ul>';
			
				if ($settings['bankfilterlabel']) $output .= '<li class="label">'.$settings['bankfilterlabel'].'</li>';
			
				foreach (array_reverse($interest) as $k => $v) {
					if ($v['bankfilter']) $output .= '<li class="check"><div class="loancomparison_checkbox"><input type="checkbox" class="loancomparison_bankfilter" name="bankfilter'.$k.'" id="bankfilter'.$k.'" value="'.$v['alt'].'"'.${'bankfilter'.$k}.'><label for="bankfilter'.$k.'"></label></div><span>'.$v['alt'].'</span></li>';
				}
				$output .= '</ul></div>';
			}
		}
		
		// Sortby
		if ($settings['showsorting'] && $loancomparisonkey['authorised']) {
			$sbs = loancomparison_get_sortby_sorting($settings);
			$output .= '<div class="sorting"><span class="sortinglabel">'. $settings['sortbylabel'] . '</span> <select id="sortby" name="sortby">';
			foreach($sbs as $item) {
				if ($item['checked']) $output .= $settings['sort'.$item['name']] ? '<option value="'.$item['name'].'">'. $settings['sort'.$item['name']] . '</option>' : ''; 
			}
			$output .= '</select></div>';
		}

		$offers = str_replace('[number]', '<span class="numberofoffers"></span>', $settings['offers']);
			
		// Number of offers
		if ($settings['showoffers'] && $loancomparisonkey['authorised']) {
			$output .= '<div class="banks_matches">
			<div class="offers">'.$offers.'</div>
			<div class="one-offer">'.$settings['one-offer'].'</div>
			<div class="no-offers">'.$settings['no-offers'].'</div>
			</div>';
		}

		// Table Header
		if ($settings['showheader']) {
			if ($settings['showfees']) $settings['loanlabel'] = $settings['feeslabel'];
			$output .= '<div class="banks_header">
			<div class="bank_details">&nbsp;</div>
			<div class="bank_details">'.$settings['interestlabel'].'</div>
			<div class="bank_details">'.$settings['loanlabel'].'</div>
			<div class="bank_details">'.$settings['repaymentlabel'].'</div>
			<div class="bank_details">'.$settings['totallabel'].'</div>
			<div class="bank_details">&nbsp;</div>
			<div style="clear:left"></div>
			</div>';
		}

		// Table of Results
		$output .= '<div class="loancomparison_rates"></div>
		<div id="lc_show_more"><div class="bg"></div>
		<div class="fg">'.$settings['numbertoshowlabel'].'</div></div>
		</form>';
	} else {
		
		$output .= '<div class="bank_apply standalone"><a style="padding: 0 6px" rel="'.$settings['listurl'].'" id="ratespage" href="'.$settings['listurl'].'">'.$settings['buttonlabel'].'</a></div>';
		
	}
	
	return $output;
}


// Build Custom CSS

function loancomparison_generate_css() {
	$style = loancomparison_get_stored_style();
	
	if ($style['nostyles'] || $style['nocustomstyles']) return;
	$padding = $background = false;
	$handle = $style['handle-size'];
	$handlepos = ($style['handle-size'] - $style['slider-thickness']) / 2 + $style['handle-thickness'];
	
	if ($style['background'] == 'white') {
		$background = "background:#FFF;";
	}
	if ($style['background'] == 'color') {
		$background = "background:".$style['backgroundhex'].";";
	}
	
	$data = 'form.loancomparison_form {padding:'.$style['padding'].'px;'.$background.'}
.loancomparison_form .output-number	{color: '.$style['slider-label-colour'].';}
.output-number output {color: '.$style['slider-output-colour'].';}
.loancomparison_form .circle-down, .loancomparison_form .circle-up {border-color: '.$style['buttoncolour'].';}
.loancomparison_form .circle-down:after, .loancomparison_form .circle-up:after, .loancomparison_form .circle-up:before {background-color:'.$style['buttoncolour'].';}
.loancomparison, .loancomparison__fill {height: '.$style['slider-thickness'].'px;background: '.$style['slider-background'].';}
.loancomparison__fill {background: '.$style['slider-revealed'].';}
.loancomparison__handle {background: '.$style['handle-background'].';border: '.$style['handle-thickness'].'px solid '.$style['handle-border'].';width: '.$handle.'px;height: '.$handle.'px;position: absolute;top: -'.$handlepos.'px;'.$style['handle-corners'].'%;border-radius:'.$style['handle-corners'].'%;}
.bank_box { margin-bottom: '.$style['bank-bottom-margin'].'px; background: '.$style['bank-bankground'].'; border: '.$style['bank-border-thickness'].'px solid '.$style['bank-border-colour'].';padding:'.$style['bank-padding'].'px;}
.sorting, .loancomparison-filterlabel {border-top: '.$style['bank-border-thickness'].'px solid '.$style['bank-border-colour'].';}
.bank_box.bank_offset { background-color: '.$style['bank-alternate-background'].' !important; }
.bank_details span {color:'.$style['bank-label-colour'].';}.bank_details b {color:'.$style['bank-output-colour'].';}
.bank_apply a, .bank_apply span {border: '.$style['button-border-thickness'].'px solid '.$style['button-border-colour'].';border-radius: '.$style['button-border-radius'].'px; background: '.$style['button-background-colour'].'; color: '.$style['button-label-colour'].' !important;box-shadow: 0 5px 11px 0 rgba(0,0,0,.18),0 4px 5px 0 rgba(0,0,0,.15);}
.bank_apply span {background: '.$style['button-nolink'].';}
.bank_apply a:hover {background: '.$style['button-background-hover'].';}
.readmore {color: '.$style['more-link'].';}.bank_box h6 {color:'.$style['more-headers'].';}.colsmindent {color:'.$style['more-colour'].';}
.loancomparison-ribbon span.ribbon1{background:'.$style['ribbon1'].'}.loancomparison-ribbon span.ribbon2{background:'.$style['ribbon2'].'}.loancomparison-ribbon span.ribbon3{background:'.$style['ribbon3'].'}.loancomparison-ribbon span.ribbon4{background:'.$style['ribbon4'].'}
.loancomparison-numbering {color: '.$style['numbering-color'].';background: '.$style['numbering-background'].';}';
	
	if ($style['filterlabelhide']) $data .= '@media only screen and (max-width: '.($style['filterlabelbreakpointpx']?? '').'px) {.loancomparison-filterlabel .label { display: none; }}';
	if ($style['slider-block']) $data .= loancomparison_square_css ($style);
	return $data;
}

// Builds Square Slider CSS
function loancomparison_square_css ($style) {
	$css = '.loancomparison, .loancomparison__fill {height: '.$style['slider-thickness'].'px;box-shadow: none;border-radius: 0;}
.loancomparison__handle {width: '.$style['slider-thickness'].'px;height: '.$style['slider-thickness'].'em;border:none;top: 0;box-shadow: none;border-radius: 0;}';
	return $css;
}

// Add to Head
function loancomparison_head_css ($atts) {
	
	$allowed_html = callback_allowed_html();
	
	$data = '<style type="text/css" media="screen">'.loancomparison_generate_css().'</style>';
	echo wp_kses($data,$allowed_html);
}

// Enqueue the Scripts
function loancomparison_scripts() {
	
	//$theform  = (!$formnumber || $formnumber == 1) ? 1 : $formnumber;
	
	wp_enqueue_style( 'loancomparison_style', plugins_url('loancomparison.css', __FILE__));
	wp_enqueue_script("jquery-effects-core");
	wp_enqueue_script('loancomparison_script',plugins_url('loancomparison.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
	wp_enqueue_script('loancomparison_bargraph_script',plugins_url('jQuery.bargraph.js', __FILE__ ), array( 'jquery' ), false, true );
}

// Settings Links
function loancomparison_plugin_action_links($links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$loancomparison_links = '<a href="'.get_admin_url().'options-general.php?page=loan-comparison/settings.php">'.__('Settings').'</a>';
		array_unshift( $links, $loancomparison_links );
	}
	return $links;
}

function loancomparison_addSpaces($s) {
	return trim(preg_replace("/(\d)(?=(\d{3})+$)/",'$1 ',$s));
}

function loancomparison_removeSpaces($s) {
	return str_replace(' ', '', $s);
}

// Get URL of the current page
function loancomparison_current_page_url() {
	$pageURL = 'http';
	if (!isset($_SERVER['HTTPS'])) $_SERVER['HTTPS'] = '';
	if (!empty($_SERVER["HTTPS"])) {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if (($_SERVER["SERVER_PORT"] != "80") && ($_SERVER['SERVER_PORT'] != '443'))
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else 
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	return $pageURL;
}

function callback_allowed_html() {

	$allowed_tags = array(
		'style' => array(),
		'script' => array(),
		'form' => array(
			'method' => array(),
			'action' => array(),
		),
		'fieldset' => array(
			'style' => array(),
		),
		'input' => array(
			'id'	=> array(),
			'type'	=> array(),
			'name'	=> array(),
			'value'	=> array(),
			'checked'=> array(),
			'class' => array(),
			'style' => array()
		),
		'select' => array(
			'name'	=> array(),
		),
		'option' => array(
			'type'	=> array(),
			'name'	=> array(),
			'value'	=> array(),
			'selected'=> array(),
			'class' => array(),
			'style' => array()
		),
		'textarea' => array(
			'name'	=> array(),
			'rows'	=> array(),
			'cols'	=> array()
		),
		'table' => array(
			'id' => array(),
			'class' => array(),
			'style' => array(),
		),
		'tbody' => array(
			'id' => array(),
			'class' => array(),
			'style' => array(),
		),
		'tr' => array(
			'id' => array(),
			'class' => array(),
			'style' => array(),),
		'th' => array(
			'style' => array(),
		),
		'td' => array(
			'id' => array(),
			'class' => array(),
			'style' => array(),
			'colspan' => array(),
		),
		'a' => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
		),
		'b' => array(),
		'code' => array(),
		'div' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'br' => array(),
		'em' => array(),
		'h1' => array(),
		'h2' => array(),
		'h3' => array(),
		'h4' => array(),
		'h5' => array(),
		'h6' => array(),
		'i' => array(),
		'img' => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
		),
		'li' => array(
			'id' => array(),
			'class' => array(),
			'style' => array(),
		),
		'ol' => array(
			'class' => array(),
		),
		'p' => array(
			'id' => array(),
			'class' => array(),
			'style' => array(),
		),
		'span' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'strong' => array(),
		'ul' => array(
			'id' => array(),
			'class' => array(),
		),
		'template' => array(),
	);
	
	return $allowed_tags;
}