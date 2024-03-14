<?php

function loancomparison_admin_tabs($current = 'settingsone') { 
	
	$allowed_html = callback_allowed_html();
	
	$tabs = array(
		'settingsone'   => 'Settings',
		'upgrade'	   => 'Upgrade',
		'styles'		=> 'Styles',
	);
	echo wp_kses('<h2 class="nav-tab-wrapper">',$allowed_html);
	foreach( $tabs as $tab => $name ) {
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		echo wp_kses("<a class='nav-tab$class' href='?page=loan-comparison/settings.php&tab=$tab'>$name</a>",$allowed_html);
	}
	echo wp_kses('</h2>',$allowed_html);

}

function loancomparison_tabbed_page() {
	
	$allowed_html = callback_allowed_html();
	
	echo wp_kses('<div class="wrap">',$allowed_html);
	echo wp_kses('<h1>Loan Comparison Settings</h1>',$allowed_html);
	if ( isset ($_GET['tab'])) {
		loancomparison_admin_tabs($_GET['tab']); $tab = $_GET['tab'];
	} else {
		loancomparison_admin_tabs('settingsone'); $tab = 'settingsone';
	}
	switch ($tab) {
		case 'settingsone'  : loancomparison_settings (); break; 
		case 'upgrade'	  : loancomparison_upgrade(); break;
		case 'styles'	   : loancomparison_styles(); break;
		}
	echo wp_kses('</div>',$allowed_html);
	
}

function loancomparison_get_options () {
	
	$options = array(
		'loanlabel',
		'interestlabel',
		'interestamountlabel',
		'repaymentlabel',
		'totallabel',
		'hideapply',
		'hideallapply',
		'showmoreinfo',
		'moreinfo',
		'info1label',
		'info2label',
		'info3label',
		'info4label',
		'showreviewlink',
		'reviewlabel',
		'reviewtarget',
		'reviewbank',
		'logonofollow',
		'logoblank',
		'buttonnofollow',
		'buttonblank',
		'addterms',
		'loanslider',
		'termslider',
		'loaninitial',
		'loanhide',
		'periodinitial',
		'termhide',
		'currency',
		'ba',
		'separator',
		'decimalcomma',
		'currencyspace',
		'decimals',
		'rounding',
		'daylabel',
		'monthlabel',
		'yearlabel',
		'dayslabel',
		'monthslabel',
		'yearslabel',
		'addfees',
		'whenfees',
		'showlimits',
		'termseparator',
		'showrating',
		'maxrating',
		'halfstars',
		'sort',
		'showexample',
		'examplelocation',
		'hidetotal',
		'napr',
		'simpleinterest',
		'nobuttons',
		'nosanitize',
		'applylabel',
		'sponsoredlabel',
	);

	$loancomparisonkey = loancomparison_get_stored_key();

	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$authoptions = array (
			'loanslabel',
			'feeslabel',
			'termlabel',
			'ratinglabel',
			'banknamelabel',
			'loancostlabel',
			'otherinfolabel',
			'creditscorelabel',
			'showchecks',
			'check1',
			'check2',
			'check3',
			'check4',
			'check5',
			'check6',
			'useinfoboxes',
			'barchartenabled',
			'barchartorder',
			'formtitle',
			'showfeatures',
			'feature1',
			'feature2',
			'feature3',
			'showheader',
			'showsponsored',
			'showfilters',
			'filterlabel',
			'filterlabel1',
			'filterlabel2',
			'filterlabel3',
			'filterlabel4',
			'filterlabel5',
			'filterlabel6',
			'filterlabel7',
			'filterlabel8',
			'filterlabel9',
			'filterlabel10',
			'filterlabel11',
			'filterlabel12',
			'filterlabel13',
			'filterlabel14',
			'filterlabel15',
			'filtertype',
			'filterdefault',
			'showbankfilters',
			'bankfiltertype',
			'bankfilterlabel',
			'showsorting',
			'sortby',
			'columnsort',
			'sortby_rating',
			'sortby_interest',
			'sortby_bankname',
			'sortby_loan',
			'sortby_loanamount',
			'sortby_interestamount',
			'sortby_fees',
			'sortby_repayment',
			'sortby_total',
			'sortby_term',
			'sortby_infolink',
			'sortby_loancost',
			'sortby_otherinfo',
			'sortby_creditscore',
			'sortbylabel',
			'sort_rating',
			'sort_interest',
			'sort_loanamount',
			'sort_fees',
			'sort_bankname',
			'sort_repayment',
			'sort_total',
			'sort_term',
			'sortrating',
			'sortinterest',
			'sortloanamount',
			'sortfees',
			'sortbankname',
			'sortrepayment',
			'sorttotal',
			'sortterm',
			'showoffers',
			'offers',
			'one-offer',
			'no-offers',
			'numbertoshow',
			'numbertoshowlabel',
			'shownumbering',
			'roundnumbering',
			'showribbon',
			'ribbonlabel1',
			'ribbonlabel2',
			'ribbonlabel3',
			'ribbonlabel4',
			'ribbonlabel5',
			'ribbonlabel6',
			'showbankname',
			'showribbonlabel',
			'infolinklabel',
			'infolinkanchor',
			'minfee',
			'savings',
			'showfico',
			'ficolabel',
			'ficolinklabel',
			'ficolink',
			'ficolinktarget',
			'replacebutton',
			'buttonlabel',
			'listurl',
			'hidelistsliders',
			'borrowinglabel'
		);
		$options = array_merge( $options,$authoptions);
	}
	return $options;
}

function loancomparison_settings() {
	
	$allowed_html = callback_allowed_html();
	
	$formnumber = loancomparison_get_stored_formnumber();
	$theform  = (!$formnumber || $formnumber == 1) ? 1 : $formnumber;
	
	$content = $drop = $grid = $before = $after = $none = $non = $float = $always = false;
	$none = $comma = $apostrophe = $dot = $space = false;
	$tenround = $hundredround = $thousandround = false;
	$beforeinterest = $afterinterest = false;
	
	if (isset($_POST['update-bank-list']) && check_admin_referer("save_loancomparison")) {
		
		$settings = loancomparison_get_stored_settings($theform);
		
		$settings['replacebutton']	= ((!empty($_POST['replacebutton']))? 'checked':'');
		$bank_url	= str_replace(' ','-',preg_replace('/[^_a-zA-Z0-9 -]/','',$_POST['pageslug']));
		$bank_title	= $_POST['pagetitle'];
		$bank_id	= 0;

		if (isset($settings['pageid']) && $settings['pageid']) {
			$bank_id = (int) $settings['pageid'];
		}
		
		if (!empty($bank_url) && !empty($bank_title)) {
			$inserted_id = wp_insert_post(array(
				'ID' => $bank_id,
				'post_content'	=> '[loancomparisontable]',
				'post_title'	=> $bank_title,
				'post_name'		=> $bank_url,
				'post_type'		=> 'page',
				'post_status'	=> 'publish'
			));
			
			if ($inserted_id) {
				
				$settings['pageid'] = $inserted_id;
				$settings['pagetitle'] = $bank_title;
				$settings['pageslug'] = $bank_url;
				
			}
		}
		
		if (!(int) $settings['pageid']) {
			$settings['replacebutton'] = '';
			loancomparison_admin_notice("Some Settings Not Saved Because A Banks Page Was Not Created");
		} else {
			loancomparison_admin_notice("Banks Page List Settings Saved Successfully");
		}
		
		update_option( LC_SETTINGS.$theform, $settings );
				
	}
	
	if( isset( $_POST['ImportSettings']) && check_admin_referer("save_loancomparison")) {
		$settings = loancomparison_get_stored_settings(1);
		update_option( LC_SETTINGS.$theform, $settings );
		loancomparison_admin_notice("Table 1 Settings Imported");
	}


	if( isset( $_POST['changeform']) && check_admin_referer("save_loancomparison")) {
		$loancomparison_form = $theform = $_POST['calculator'];
		update_option( LC_FORMNUMBER, $loancomparison_form);
	}
	
	if( isset( $_POST['Insertallbanks']) && check_admin_referer("save_loancomparison")) {
		$settings = loancomparison_get_stored_settings($theform);
		$interest = loancomparison_get_stored_interest($theform);
		foreach ($interest as $bank) {
			if (!$allowduplicates && post_exists($bank['alt'])) {
				break;
			} else {
				$pid = loancomparison_insert_bank($bank,$settings);
				$permalink = get_permalink($pid);
				$bank['link'] = $permalink;
			}
		}
		update_option( LC_INTEREST.$theform, $interest );
		loancomparison_admin_notice("Pages created for all banks.");
	}
	
	if( isset( $_POST['Insert']) && check_admin_referer("save_loancomparison")) {
		$settings = loancomparison_get_stored_settings($theform);
		$interest = loancomparison_get_stored_interest($theform);
		
		$allowduplicates = $_POST['allowduplicates'];
		
		for ($i = 0; $i <= count($interest); $i++) {
			if (isset($_POST[$i])) {
				if (!$allowduplicates && post_exists($interest[$i]['alt'])) {
					break;
				} else {
					$pid = loancomparison_insert_bank($interest[$i],$settings);
					$permalink = get_permalink($pid);
					$interest[$i]['link'] = $permalink;
				}
			}
		}
		update_option( LC_INTEREST.$theform, $interest );
		loancomparison_admin_notice("Bank pages created.");
	}

	if( isset( $_POST['Submit']) && check_admin_referer("save_loancomparison")) {
		$options = loancomparison_get_options();
		
		$loancomparisonkey = loancomparison_get_stored_key();

		$settings = array();
		$settings['columnsorting'] = false;
		$settings['nosanitize'] = isset($_POST['nosanitize']) ? 'checked' : null;
		
		if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
			$settings['columnsorting'] = true;
			$settings['fico'] = array();
			for ($i=0;$i<=4;$i++) {
				array_push($settings['fico'],array(
					'min'   => sanitize_text_field($_POST['fico'.$i.'min']),
					'max'   => sanitize_text_field($_POST['fico'.$i.'max']),
					'label'  => sanitize_text_field($_POST['fico'.$i.'label'])
			 ));
			}
		}
		
		foreach ( $options as $item) {
			if (isset($_POST[$item])) {
				if ($settings['nosanitize']) $settings[$item] = stripslashes($_POST[$item]);
				else $settings[$item] = sanitize_text_field(stripslashes($_POST[$item]));
			}
		}
		
		$settings['loan'] = array();
		$settings['period'] = array();
		for ($i=0;$i<=4;$i++) {
			array_push($settings['loan'],array(
				'min'   => sanitize_text_field($_POST['loan'.$i.'min'] ?? ''),
				'max'   => sanitize_text_field($_POST['loan'.$i.'max'] ?? ''),
				'step'  => sanitize_text_field($_POST['loan'.$i.'step'] ?? '')
			));
			
			array_push($settings['period'],array(
				'min'   => sanitize_text_field($_POST['period'.$i.'min'] ?? ''),
				'max'   => sanitize_text_field($_POST['period'.$i.'max'] ?? ''),
				'step'  => sanitize_text_field($_POST['period'.$i.'step'] ?? ''),
				'term'  => sanitize_text_field($_POST['period'.$i.'term'] ?? '')
			));
		}
		
		if (isset($_POST['resetcolumns']) && $_POST['resetcolumns']) {
			$settings['columnsort'] = false;
			$settings['columnsorting'] = false;
		}
		
		if (isset($_POST['resetsorting']) && $_POST['resetsorting']) {
			$settings['sortby'] = false;
		}
		
		update_option( LC_SETTINGS.$theform, $settings );

		$fields = array(
			'logo',
			'alt',
			'rating',
			'max_loan',
			'min_loan',
			'max_term',
			'min_term',
			'link',
			'logolink',
			'infolink',
			'infolinkanchor',
			'sponsored',
			'blocklink',
			'bankfilter',
			'mininterest',
			'maxinterest',
			'filter1',
			'filter2',
			'filter3',
			'filter4',
			'filter5',
			'filter6',
			'filter7',
			'filter8',
			'filter9',
			'filter10',
			'filter11',
			'filter12',
			'filter13',
			'filter14',
			'filter15',
			'check1',
			'check2',
			'check3',
			'check4',
			'check5',
			'check6',
			'ribbon1',
			'ribbon2',
			'ribbon3',
			'ribbon4',
			'ribbon5',
			'ribbon6',
			'example',
			'info1',
			'info2',
			'info3',
			'info4',
			'hide',
			'startupfee',
			'percentfee',
			'annualfixed',
			'annualpercent',
			'otherinfo',
			'minimum_fico'
		);
		$newInterest = array();

		foreach ($_POST['interest'] as $item) {
			$ni = array();
			if ($item['min_term'] < 1 || $item['min_term'] > $item['max_term']) $item['min_term'] = 1;
			if ($item['min_loan'] < $settings['loan'][0]['min']) $item['min_loan'] = $settings['loan'][0]['min'];
			foreach ( $fields as $field) {
				if (isset($item[$field])) {
					if ($settings['nosanitize']) $ni[$field] = stripslashes($item[$field]);
					else $ni[$field] = sanitize_text_field(stripslashes($item[$field]));
				} else {
					$ni[$field] = null;
				}
					
			}
			$newInterest[] = $ni;
		}
		update_option( LC_INTEREST.$theform, $newInterest );
		loancomparison_admin_notice("The table ".$theform." settings have been updated.");
		
		$interest = $newInterest;
	}
	
	if( isset( $_POST['Reset']) && check_admin_referer("save_loancomparison")) {
		
		/*
			Delete the page created (if it was created)
		*/
		
		if(isset($settings)){
			$pageid = (int) $settings['postid']?? '';
			if ($pageid > 0) {
				wp_delete_post($pageid, true);
			}
		}
		
		delete_option(LC_SETTINGS.$theform);
		delete_option(LC_INTEREST.$theform);
		loancomparison_admin_notice("The table ".$theform." settings have been reset.");
	}
	
	$loancomparisonkey = loancomparison_get_stored_key();

	$settings = loancomparison_get_stored_settings($theform);
	$interest = loancomparison_get_stored_interest($theform);
	
	/*
		Normalize Sort
	*/
	$sb	= loancomparison_get_sortby($settings);
	$sbs   = loancomparison_get_sortby_sorting($settings);
	
	${$settings['ba']} = 'checked';
	${$settings['separator']} = 'checked';
	${$settings['decimals']} = 'checked';
	${$settings['rounding']} = 'checked';
	${$settings['examplelocation']} = 'checked';
	${$settings['whenfees']} = 'checked';
	
	if ($settings['ba'] == 'before') {
		$settings['cb'] = $settings['currency'];
		$settings['ca'] = ' ';
	} else {
		$settings['ca'] = $settings['currency'];
		$settings['cb'] = ' ';
	}
	
	$terms ='</tr></table>';
	
	// Calculate Term Steps
	$step = 1;
	$terms .='<table class="step-table"><tr>';
	foreach ($settings['period'] as $k => $v) {
		if (!$v['step']) continue;
		$s = ceil((((int)$v['max'] - (int)$v['min']) / (int)$v['step']) + 1);
		for ($i = 0; $i < $s; $i++) {
			$terms .='<td>'.$step.'<br><em>';
			if ($i == 0) {$terms .= ($v['min'] * 1).$v['term'];}
			else $terms .= (((int)$v['step'] * $i) + (int)$v['min']).$v['term'];
			$terms .='</em></td>';
			$step++;
		}
	}
	$terms .='</tr></table>';
	
	$termsteps = $step - 1;
	
	$url = plugins_url('/loan-comparison/images/application-form.png');
	
	$content .='<script>jQuery(function() {
			var loancomparison_rsort = jQuery( "#loancomparison_rsort" ).sortable({
					cursor: "move",
					opacity:0.8,
					update:function(e,ui) {
						var order = loancomparison_rsort.sortable("toArray").join();
						jQuery("#loancomparison_order_sort").val(order);
					}
				}
			);
			
			var loancomparison_column_rsort = jQuery( "#loancomparison_column_rsort" ).sortable({
					cursor: "move",
					opacity:0.8,
					update:function(e,ui) {
						var order = loancomparison_column_rsort.sortable("toArray").join();
						jQuery("#loancomparison_column_sort").val(order);
					}
				}
			);
			
			var loancomparison_sort = jQuery( "#loancomparison_sort" ).sortable(
				{
					axis: "y",
					cursor: "move",
					opacity:0.8,
					update:function(e,ui) {
						var order = loancomparison_sort.sortable("toArray").join();
						jQuery("#loancomparison_settings_sort").val(order);
					}
				}
			);
		});</script>';

	$content .='<div class="loancomparison-options">
	<form method="post" enctype="multipart/form-data" action="">
	
	<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	<h2>'.__('Settings for Table', 'loan-comparison').' '.$theform.'</h2>';
	
	if ($theform == '1') {
		$content .='<p>'.__('Add this comparison table to your site using the shortcode', 'loan-comparison').': [loancomparison]</p>';
	} else {
		$content .='<p>'.__('Add this comparison table to your site using the shortcode', 'loan-comparison').': [loancomparison table='.$theform.']</p>';
	}

	$content .='<p>'.__('If you need help with the settings', 'loan-comparison').' <a href="https://bankcomparisonplugin.com/settings/" target="_blank">'.__('visit the support site', 'loan-comparison').'</a>.</p>
	
	<h2>'.__('Loan Application', 'loan-comparison').'</h2>
	
	<p>'.__('Add a loan application form to the site', 'loan-comparison').'. <a href="https://bankcomparisonplugin.com/wp-content/uploads/loan-application.zip">'.__('Download and install the plugin', 'loan-comparison').'</a>. <a href="https://bankcomparisonplugin.com/loan-application/?amount=$1000&term=2%20Years&bank=Instabank" target="_blank">'.__('See the Demo', 'loan-comparison').'</a>.</p>';
	
	$content .='</fieldset>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		<h2>'.__('Form Selection', 'quick-interest-slider').'</h2>
		<p><select name="calculator">';
		for ($i= 1; $i<=50; $i++) {
			$selected = $theform == $i ? ' selected' : '';
			$content .='<option value="'.$i.'"'.$selected.'>Table '.$i.'</option>';
		}
		$content .='</select > <input type="submit" name="changeform" class="button-secondary" value="Change Form" /></p>
		</fieldset>';
	}

	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Slider Settings', 'loan-comparison').'</h2>';
	
	$content .='<table><tr><th colspan="3">'.__('Loan Amount', 'loan-comparison').'</th><th></th><th colspan="4">'.__('Term', 'loan-comparison').'</th></tr>
	<tr><th>'.__('Min', 'loan-comparison').'</th><th>'.__('Max', 'loan-comparison').'</th><th>'.__('Step', 'loan-comparison').'</th><th>&nbsp;&nbsp;</th><th>'.__('Min', 'loan-comparison').'</th><th>'.__('Max', 'loan-comparison').'</th><th>'.__('Step', 'loan-comparison').'</th><th>'.__('Term', 'loan-comparison').'</th></tr>';
	
	for ($i=0;$i<3;$i++) {
		$content .='<tr>
		<td style="width:5em;"><input type="text" name="loan'.$i.'min" value ="' . $settings['loan'][$i]['min'] . '" /></td>
		<td style="width:5em;"><input type="text" name="loan'.$i.'max" value ="' . $settings['loan'][$i]['max'] . '" /></td>
		<td style="width:5em;"><input type="text" name="loan'.$i.'step" value ="' . $settings['loan'][$i]['step'] . '" /></td>
		<td></td>
		<td style="width:5em;"><input type="text" name="period'.$i.'min" value ="' . $settings['period'][$i]['min'] . '" /></td>
		<td style="width:5em;"><input type="text" name="period'.$i.'max" value ="' . $settings['period'][$i]['max'] . '" /></td>
		<td style="width:5em;"><input type="text" name="period'.$i.'step" value ="' . $settings['period'][$i]['step'] . '" /></td>
		<td style="width:5em;"><input type="text" name="period'.$i.'term" value ="' . $settings['period'][$i]['term'] . '" /></td>
		</tr>';
	}
	
	$content .='</table>';
	
	// Calculate Principal Steps
	$content .='<p><b>'.__('Amount Slider Step Values', 'loan-comparison').':</b></p>';
	
	$step = 1;
	$content .='<table class="step-table"><tr>';
	foreach ($settings['loan'] as $k => $v) {
		if (!$v['step']) continue;
		$s = ceil((((int)$v['max'] - (int)$v['min']) / (int)$v['step']) + 1);
		for ($i = 0; $i < $s; $i++) {
			$content .='<td>'.$step.'<br><em>';
			if ($i == 0) $content .= ($v['min'] * 1);
			else $content .= ((int)$v['step'] * $i) + (int)$v['min'];
			$content .='</em></td>';
			$step++;
		}
	}
	
	$content .='</tr></table>';
	
	$content .='<p><b>'.__('Term Slider Step Values', 'loan-comparison').':</b></p>';
	
	$content .= $terms;
	
	$content .= '<p><b>'.__('Step Values', 'loan-comparison').'</b> '.__('The values of each slider position are given in the table above.', 'loan-comparison').' '.__('Use the step numbers to set the initial postions.', 'loan-comparison').' '.__('These step numbers are also used in the bank data below to set the min term and max term', 'loan-comparison').'.</p>';

	$content .= '<p><b>'.__('Amount Slider', 'loan-comparison').':</b> '.__('Label', 'loan-comparison').': <input type="text" style="width:7em" name="loanslider" value ="' . $settings['loanslider'] . '" />&nbsp;&nbsp;&nbsp;'.__('Currency Symbol', 'loan-comparison').': <input type="text" style="width:3em;" name="currency" value ="' . $settings['currency'] . '" />&nbsp;&nbsp;&nbsp;'.__('Initial Position', 'loan-comparison').': <input type="text" style="width:3em;" name="loaninitial" value ="' . $settings['loaninitial'] . '" />&nbsp;&nbsp;&nbsp;<input type="checkbox" name="loanhide"  value="checked" ' . $settings['loanhide'] . '/> '.__('Hide amount slider', 'loan-comparison').'</p>';
	
	$content .= '<p><b>'.__('Term Slider', 'loan-comparison').':</b> '.__('Label', 'loan-comparison').': <input type="text" style="width:7em" name="termslider" value ="' . $settings['termslider'] . '" />&nbsp;&nbsp;&nbsp;'.__('Period Labels', 'loan-comparison').'&nbsp;&nbsp;&nbsp;<em>Singular:</em> <input type="text" style="width:4em;" name="daylabel" value ="' . $settings['daylabel'] . '" />&nbsp;<input type="text" style="width:4em;" name="monthlabel" value ="' . $settings['monthlabel'] . '" />&nbsp;<input type="text" style="width:4em;" name="yearlabel" value ="' . $settings['yearlabel'] . '" />&nbsp;&nbsp;&nbsp;<em>Plural:</em> <input type="text" style="width:4em;" name="dayslabel" value ="' . $settings['dayslabel'] . '" />&nbsp;<input type="text" style="width:4em;" name="monthslabel" value ="' . $settings['monthslabel'] . '" />&nbsp;<input type="text" style="width:4em;" name="yearslabel" value ="' . $settings['yearslabel'] . '" />&nbsp;&nbsp;&nbsp;'.__('Initial Position', 'loan-comparison').': <input type="text" style="width:3em;" name="periodinitial" value ="' . $settings['periodinitial'] . '" />&nbsp;&nbsp;&nbsp;<input type="checkbox" name="termhide"  value="checked" ' . $settings['termhide'] . '/> '.__('Hide term slider', 'loan-comparison').'</p>
	<p><input type="checkbox" name="nobuttons"  value="checked" ' . $settings['nobuttons'] . '/> '.__('Hide buttons', 'loan-comparison').' ('.__('labels and values move to the ends of the slider', 'loan-comparison').')</p>';

	$content .='</fieldset>';

	$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Bank Data', 'loan-comparison').'</h2>
	<table id="sorting">
	<tbody id="loancomparison_sort">';
	
	foreach ($interest as $k => $v) {
		$content .= build_bank_row($settings, $k, $v);
	}
	$fields = array(
			'logo'=>'',
			'alt'=>'',
			'rating'=>'',
			'max_loan'=>'',
			'min_loan'=>'',
			'max_term'=>'',
			'min_term'=>'',
			'link'=>'',
			'logolink'=>'',
			'infolink'=>'',
			'sponsored'=>'',
			'blocklink'=>'',
			'bankfilter'=>'',
			'mininterest'=>'',
			'maxinterest'=>'',
			'filter1'=>'',
			'filter2'=>'',
			'filter3'=>'',
			'filter4'=>'',
			'filter5'=>'',
			'filter6'=>'',
			'filter7'=>'',
			'filter8'=>'',
			'filter9'=>'',
			'filter10'=>'',
			'filter11'=>'',
			'filter12'=>'',
			'filter13'=>'',
			'filter14'=>'',
			'filter15'=>'',
			'check1'=>'',
			'check2'=>'',
			'check3'=>'',
			'check4'=>'',
			'check5'=>'',
			'check6'=>'',
			'ribbon1'=>'',
			'ribbon2'=>'',
			'ribbon3'=>'',
			'ribbon4'=>'',
			'ribbon5'=>'',
			'ribbon6'=>'',
			'example'=>'',
			'info1'=>'',
			'info2'=>'',
			'info3'=>'',
			'info4'=>'',
			'hide'=>'',
			'startupfee'=>'',
			'percentfee'=>'',
			'annualfixed'=>'',
			'annualpercent'=>'',
			'otherinfo'=>'',
			'minimum_fico'=>''
		);
	$bankTemplate = build_bank_row($settings, '!K!', $fields);

	$content .= '</tbody></table>
	
	<p><input type="button" name="AddBank" id="loancomparisonAddBank" onclick="loancomparison_add_bank()" class="button-secondary" value="+ Add New Bank" /></p>

	<input type="hidden" id="loancomparison_settings_sort" name="sort" value="'.$settings['sort'].'" />';
	
	$newbank ='var loancomparison_bank_count = '.$k.';
		var loancomparison_bank_template = '.json_encode($bankTemplate).';
		
		function loancomparison_add_bank() {
			loancomparison_bank_count++;
			
			jQuery(loancomparison_bank_template.replace(/\!K\!/g,loancomparison_bank_count)).appendTo(jQuery("#sorting"));
			
			/*
				Add functionality to the remove buttons
			*/
			jQuery("#sorting .remove_this").unbind("click").bind("click",loancomparison_remove_bank);
		}
		function loancomparison_remove_bank() {
			jQuery(this).closest("tr").remove();
		}
		
		jQuery(document).ready(function() {
			jQuery("#sorting .remove_this").click(loancomparison_remove_bank);
		})';
	
	wp_add_inline_script( 'loancomparison-media', $newbank );
		
	$content .= '</fieldset>';
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">';
	
	$content .= '<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Table '.$theform.' Settings" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the settings?\' );"/></p>
	
	</fieldset>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		
		// Bar Charts
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Bar Charts', 'loan-comparison').'</h2>
		
		<p><input type="checkbox" value="true"'.(($settings['barchartenabled'])? ' checked="checked" ':'').'name="barchartenabled" id="barchartenabled" /> <label for="barchartenabled">'.__('Show Barcharts', 'loan-comparison').'</label></p>
		<p><input type="checkbox" value="true"'.(($settings['barchartorder'])? ' checked="checked" ':'').'name="barchartorder" id="barchartorder" /> <label for="barchartorder">'.__('Reverse Sort', 'loan-comparison').' ('.__('highest to lowest', 'loan-comparison').')</label></p>
	
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Form Heading', 'loan-comparison').'</h2>
		
		<p>'.__('Title', 'loan-comparison').':<input type="text" style="width:10em;" name="formtitle" value ="' . $settings['formtitle'] . '" /></p>
	
		<p><input type="checkbox" name="showfeatures" value="checked" '.$settings['showfeatures'].'> '.__('Show features below title', 'loan-comparison').'</p>
		<table>
		
			<tr>
			<td>'.__('Feature', 'loan-comparison').' 1: <input type="text" name="feature1" value ="' . $settings['feature1'] . '" /></td>
			<td>'.__('Feature', 'loan-comparison').' 2: <input type="text" name="feature2" value ="' . $settings['feature2'] . '" /></td>
			<td>'.__('Feature', 'loan-comparison').' 3: <input type="text" name="feature3" value ="' . $settings['feature3'] . '" /></td>
			</tr>
	
		</table>
	
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Feature Filters', 'loan-comparison').'</h2>
	
		<p><input type="checkbox" name="showfilters" value="checked" '.$settings['showfilters'].'> '.__('Show filters', 'loan-comparison').'</p>
		<p>Label: <input type="text" style="width:10em;" name="filterlabel" value ="' . $settings['filterlabel'] . '" /></p>
	
		<table>
		
		<tr>
		<td>'.__('Filter', 'loan-comparison').' 1:<input type="text" name="filterlabel1" value ="' . $settings['filterlabel1'] . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 2:<input type="text" name="filterlabel2" value ="' . $settings['filterlabel2'] . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 3:<input type="text" name="filterlabel3" value ="' . $settings['filterlabel3'] . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 4:<input type="text" name="filterlabel4" value ="' . $settings['filterlabel4'] . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 5:<input type="text" name="filterlabel5" value ="' . $settings['filterlabel5'] . '" /></td>
		</tr>
		<tr>
		<td>'.__('Filter', 'loan-comparison').' 6:<input type="text" name="filterlabel6" value ="' . $settings['filterlabel6'] . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 7:<input type="text" name="filterlabel7" value ="' . ($settings['filterlabel7'] ?? '') . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 8:<input type="text" name="filterlabel8" value ="' . ($settings['filterlabel8']?? '') . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 9:<input type="text" name="filterlabel9" value ="' . ($settings['filterlabel9']?? '') . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 10:<input type="text" name="filterlabel10" value ="' . ($settings['filterlabel10']?? '') . '" /></td>
		</tr>
		<tr>
		<td>'.__('Filter', 'loan-comparison').' 11:<input type="text" name="filterlabel11" value ="' . ($settings['filterlabel11']?? '') . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 12:<input type="text" name="filterlabel12" value ="' . ($settings['filterlabel12']?? '') . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 13:<input type="text" name="filterlabel13" value ="' . ($settings['filterlabel13']?? '') . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 14:<input type="text" name="filterlabel14" value ="' . ($settings['filterlabel14']?? '') . '" /></td>
		<td>'.__('Filter', 'loan-comparison').' 15:<input type="text" name="filterlabel15" value ="' . ($settings['filterlabel15']?? '') . '" /></td>
		</tr>
		</table>
		
		<p>
			<input type="radio" name="filtertype" value="checkbox" '.(($settings['filtertype'] == 'checkbox')? 'checked="checked"':'').'> '.__('Show as Checkboxes', 'loan-comparison').'
			<input type="radio" name="filtertype" value="dropdown" '.(($settings['filtertype'] == 'dropdown')? 'checked="checked"':'').'> '.__('Show as Dropdown', 'loan-comparison').'
		</p>
		<p>'.__('No filter label', 'loan-comparison').': <input type="text" style="width:10em;" name="filterdefault" value ="' . $settings['filterdefault'] . '" /> ('.__('used if the dropdown option is selected', 'loan-comparison').')</p>
	
		</fieldset>';
		
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Bank Filters', 'loan-comparison').'</h2>
	
		<p><input type="checkbox" name="showbankfilters" value="checked" '.$settings['showbankfilters'].'> '.__('Show bank filters', 'loan-comparison').'</p>
		<p>Label: <input type="text" style="width:10em;" name="bankfilterlabel" value ="' . $settings['bankfilterlabel'] . '" /></p>
		
		<p>
			<input type="radio" name="bankfiltertype" value="checkbox" '.(($settings['bankfiltertype'] == 'checkbox')? 'checked="checked"':'').'> '.__('Show as Checkboxes', 'loan-comparison').'
			<input type="radio" name="bankfiltertype" value="dropdown" '.(($settings['bankfiltertype'] == 'dropdown')? 'checked="checked"':'').'> '.__('Show as Dropdown', 'loan-comparison').'
		</p>
		
		<p><input type="checkbox" name="showsponsored" value="checked" '.$settings['showsponsored'].'> '.__('Only show sponsored result on default', 'loan-comparison').'</p>
	
		</fieldset>';
		
		$content .='<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Sorting', 'loan-comparison').'</h2>
	
		<p><input type="checkbox" name="showsorting" value="checked" '.$settings['showsorting'].'> '.__('', 'loan-comparison').'Show sorting</p>
	
		<p>Label: <input type="text" style="width:10em;" name="sortbylabel" value ="' . $settings['sortbylabel'] . '" /></p>';
	
		$sort = explode(",", $settings['sortby']);
		$sortby = 'sort_';
		$content .='<p>'.__('Drag and drop the elements below to change the display order', 'loan-comparison').'</p>
		<ul class="sorting" id="loancomparison_rsort">';
		
		foreach($sbs as $item) {
			
			switch ( $item['name'] ) {
				case 'rating':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['sortrating'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'interest':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['sortinterest'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'fees':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['sortfees'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'loanamount':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['sortloanamount'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'bankname':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['sortbankname'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'repayment':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['sortrepayment'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'total':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['sorttotal'],$item['checked'],$sortby).'</li>';
				break; 
				
			}
		}
		$content .='</ul>
		<table>
	
		<tr>
		<td>'.__('Rating Label', 'loan-comparison').': <input type="text" name="sortrating" value ="' . $settings['sortrating'] . '" /></td>
		<td>'.__('Interest Rates Label', 'loan-comparison').': <input type="text" name="sortinterest" value ="' . $settings['sortinterest'] . '" /></td>
		<td>'.__('Loan Amount Label', 'loan-comparison').': <input type="text" name="sortloanamount" value ="' . $settings['sortloanamount'] . '" /></td>
		<td>'.__('Fees Label', 'loan-comparison').': <input type="text" name="sortfees" value ="' . $settings['sortfees'] . '" /></td>
		</tr>
	
		<tr>
		<td>'.__('Bank Name Label', 'loan-comparison').': <input type="text" name="sortbankname" value ="' . $settings['sortbankname'] . '" /></td>
		<td>'.__('Repayment Label', 'loan-comparison').': <input type="text" name="sortrepayment" value ="' . $settings['sortrepayment'] . '" /></td>
		<td>'.__('Total Label', 'loan-comparison').': <input type="text" name="sorttotal" value ="' . $settings['sorttotal'] . '" /></td>
		<td></td>
		</tr>

		</table>
		
		<p>'.__('To remove a sorting option delete the label', 'loan-comparison').'.</p>
		
		<p>'.__('Reset sorting order', 'loan-comparison').' <input type="checkbox" name="resetsorting" value="checked"></p>
		
		<input type="hidden" id="loancomparison_order_sort" name="sortby" value="'.$settings['sortby'].'" />
	
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Credit Scores', 'loan-comparison').'</h2>
	
		<p><input type="checkbox" name="showfico" value="checked" '.$settings['showfico'].'> '.__('Show credit score selector', 'loan-comparison').'</p>		
		<p class="description">'.__('Filters the results according to the selected credit rating', 'loan-comparison').'</p>
		<table>
		<tr><th>Min</th><th>Max</th><th>Label</th></tr>';
		for ($i=0; $i <= 4; $i++) {
			$content .= '<tr>
			<td><input type="text" style="width: 4em" name="fico'.$i.'min" value ="' . $settings['fico'][$i]['min'] . '" /></td>
			<td><input type="text" style="width: 4em" name="fico'.$i.'max" value ="' . $settings['fico'][$i]['max'] . '" /></td>
			<td><input type="text" name="fico'.$i.'label" value ="' . $settings['fico'][$i]['label'] . '" /></td>
			</tr>';
		}
		
		$content .= '</table>
		<p>'.__('Selector Label', 'loan-comparison').': <input type="text" name="ficolabel" value ="' . $settings['ficolabel'] . '" /></p>
		<p>'.__('Link to credit score checker', 'loan-comparison').': <input type="text" name="ficolinklabel" value ="' . $settings['ficolinklabel'] . '" /></p>
		<p>'.__('Credit score checker URL', 'loan-comparison').': <input type="text" name="ficolink" value ="' . $settings['ficolink'] . '" /></p>
		<p><input type="checkbox" name="ficolinktarget" value="checked" '.($settings['ficolinktarget'] ?? '').'> '.__('Open in new tab', 'loan-comparison').'</p>
	
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Number of Matches', 'loan-comparison').'</h2>
	
		<p><input type="checkbox" name="showoffers" value="checked" '.$settings['showoffers'].'> '.__('', 'loan-comparison').'Show number of matches</p>
	
		<table>
	
		<tr>
		<td>'.__('Offers', 'loan-comparison').': <input type="text" name="offers" value ="' . $settings['offers'] . '" /></td>
		<td>'.__('One Offer', 'loan-comparison').': <input type="text" name="one-offer" value ="' . $settings['one-offer'] . '" /></td>
		<td>'.__('No Offers', 'loan-comparison').': <input type="text" name="no-offers" value ="' . $settings['no-offers'] . '" /></td>
		</tr>

		</table>
	
		</fieldset>
		
		<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>Table Header</h2>
	
		<p><input type="checkbox" name="showheader" value="checked" '.$settings['showheader'].'> Show table header</p>
	
		</fieldset>';
	
	} else {
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		<h2 style="color:#B52C00">'.__('Barcharts, Filters, Sorting and Features', 'loan-comparison').'</h2>
		<p>'.__('Upgrade to pro to add barcharts, application forms, more columns, promo ribbons, filters, sorting, credit scores, bank features and more.', 'loan-comparison').'.</p>
		<p><strong><a href="https://bankcomparisonplugin.com/" target="_blank">'.__('See the demo', 'loan-comparison').'.</a></strong></p>
		<h3><a href="?page=loan-comparison/settings.php&tab=upgrade">'.__('Upgrade to Pro', 'loan-comparison').'</a></h3>
		</fieldset>';
	}

	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
	
		$content .= '<h2>'.__('Column Ordering', 'loan-comparison').'</h2>';
		
		$sort = explode(",", $settings['columnsort']);
		$sortby = 'sortby_';
		$content .='<p>'.__('Drag and drop the elements below to change the display order', 'loan-comparison').'. '.__('You can select up to 6 columns but 4 fit nicely on the page', 'loan-comparison').'.</p>
		<ul class="sorting" id="loancomparison_column_rsort">';
		
		foreach($sb as $item) {
			
			switch ( $item['name'] ) {
				
				case 'rating':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['ratinglabel'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'interest':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['interestlabel'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'term':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['termlabel'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'fees':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['feeslabel'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'loanamount':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['loanlabel'],$item['checked'],$sortby).'</li>';
				break;
					
				case 'interestamount':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['interestamountlabel'],$item['checked'],$sortby).'</li>';
				break;
					
				case 'loan':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['loanslabel'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'bankname':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['banknamelabel'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'repayment':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['repaymentlabel'],$item['checked'],$sortby).'</li>';
				break;
				
				case 'total':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['totallabel'],$item['checked'],$sortby).'</li>';
				break; 
					
				case 'infolink':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['infolinklabel'],$item['checked'],$sortby).'</li>';
				break;
				case 'loancost':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['loancostlabel'],$item['checked'],$sortby).'</li>';
				break;
				case 'otherinfo':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['otherinfolabel'],$item['checked'],$sortby).'</li>';
				break;
				case 'creditscore':
					$content .= '<li id="'.$item['name'].'">'.loancomparison_build_check($item['name'],$settings['creditscorelabel'],$item['checked'],$sortby).'</li>';
				break;
				
				}
		}
		$content .='</ul>';
	
		$content .='<input type="hidden" id="loancomparison_column_sort" name="columnsort" value="'.$settings['columnsort'].'" />';
		
	}
	
	$content .='<h2>'.__('Bank Column Labels', 'loan-comparison').'</h2>
	
	<table>
	
	<tr>
	<td>'.__('Interest', 'loan-comparison').': <input type="text" name="interestlabel" value ="' . $settings['interestlabel'] . '" /></td>
	<td>'.__('Loans From', 'loan-comparison').': <input type="text" name="loanlabel" value ="' . $settings['loanlabel'] . '" /></td>
	<td>'.__('Repayment', 'loan-comparison').': <input type="text" name="repaymentlabel" value ="' . $settings['repaymentlabel'] . '" /></td>
	<td>'.__('Total', 'loan-comparison').': <input type="text" name="totallabel" value ="' . $settings['totallabel'] . '" /></td>
	</tr>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
	
		$content .='<td>'.__('Loan', 'loan-comparison').': <input type="text" name="loanslabel" value ="' . $settings['loanslabel'] . '" /></td>
		<td>'.__('Fees', 'loan-comparison').': <input type="text" name="feeslabel" value ="' . $settings['feeslabel'] . '" /></td>
	   <td>'.__('Bank Name', 'loan-comparison').': <input type="text" name="banknamelabel" value ="' . $settings['banknamelabel'] . '" /></td>
	   <td>'.__('Terms', 'loan-comparison').': <input type="text" name="termlabel" value ="' . $settings['termlabel'] . '" /></td>
		</tr>
	
		<tr>
		<td>'.__('Loan Cost', 'loan-comparison').': <input type="text" name="loancostlabel" value ="' . $settings['loancostlabel'] . '" /></td>
		<td>'.__('Additional info', 'loan-comparison').': <input type="text" name="otherinfolabel" value ="' . $settings['otherinfolabel'] . '" /></td>
		<td>'.__('Rating', 'loan-comparison').': <input type="text" name="ratinglabel" value ="' . $settings['ratinglabel'] . '" /></td>
		<td>'.__('Link to more info', 'loan-comparison').': <input type="text" name="infolinklabel" value ="' . $settings['infolinklabel'] . '" /></td>
		</tr>
	
		<tr>
		<td>'.__('Credit Score', 'loan-comparison').': <input type="text" name="creditscorelabel" value ="' . $settings['creditscorelabel'] . '" /></td>
		<td>'.__('Total Interest', 'loan-comparison').': <input type="text" name="interestamountlabel" value ="' . $settings['interestamountlabel'] . '" /></td>
		<td></td>
		<td></td>
		</tr>';
	}

	$content .='</table>
	<h2>'.__('Output Options', 'loan-comparison').'</h2>
	<p><input type="checkbox" name="addfees" value="checked" '.$settings['addfees'].'> '.__('Add fees to principle', 'loan-comparison').' <input type="radio" name="whenfees" value="beforeinterest" ' . $beforeinterest . ' />Before calculations&nbsp;&nbsp;&nbsp;
	<input type="radio" name="whenfees" value="afterinterest" ' . $afterinterest . ' />After calculations</p>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .= '<p><input type="checkbox" name="minfee" value="checked" '.($settings['minfee'] ?? '').'> '.__('Use fixed fee as a minimum', 'loan-comparison').' ('.__('fees are calculated from the percentage and then checked aginst the minimum fee', 'loan-comparison').').</p>
		<p><input type="checkbox" name="savings" value="checked" '.($settings['savings'] ?? '').'> '.__('Make fees a saving', 'loan-comparison').' ('.__('amount is deducted from total after interest calculations', 'loan-comparison').').</p>';
	}
	
	$content .= '<p><input type="checkbox" name="showlimits" value="checked" '.$settings['showlimits'].'> '.__('Show min and max on repayments and totals', 'loan-comparison').'.</p>
	<p><input type="checkbox" name="napr" value="checked" '.$settings['napr'].'> '.__('Use nominal APR', 'loan-comparison').' (<a href="https://bankcomparisonplugin.com/effective-apr/">'.__('The default is Effective APR', 'loan-comparison').')</a>.</p>
	<p><input type="checkbox" name="simpleinterest" value="checked" '.$settings['simpleinterest'].'> '.__('Use Simple Interest', 'loan-comparison').'</p>
	<p>'.__('Min/Max separator', 'loan-comparison').': <input type="text" style="width:2em;" name="termseparator" value ="' . $settings['termseparator'] . '" /></p>
	<p>'.__('Info Link Anchor', 'loan-comparison').': <input type="text" style="width:10em;" name="infolinkanchor"  value ="' . $settings['infolinkanchor'] . '" /></p>
	<p>'.__('Reset column order', 'loan-comparison').' <input type="checkbox" name="resetcolumns" value="checked"></p>
	
	</fieldset>
	
	<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Ratings', 'loan-comparison').'</h2>
	
	<p><input type="checkbox" name="showrating" value="checked" '.$settings['showrating'].'> '.__('Show ratings', 'loan-comparison').' ('.__('displays below the logo', 'loan-comparison').').</p>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .= '<p>'.__('Max rating', 'loan-comparison').': <input type="text" style="width:2em" name="maxrating"  value ="' . $settings['maxrating'] . '" /> ('.__('The number of stars you have', 'loan-comparison').').</p>
		<p><input type="checkbox" name="halfstars" value="checked" '.$settings['halfstars'].'> '.__('Allow half stars', 'loan-comparison').'.</p>';
	} else {
		$content .= '<input type="hidden" name="maxrating" value="5">';
	}
	
	$content .= '</fieldset>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		
		<h2>'.__('Promo Ribbons', 'loan-comparison').'</h2>
		<p><input type="checkbox" name="showribbon" value="checked" '.$settings['showribbon'].'> '.__('Show ribbons', 'loan-comparison').'</p>
		
		<table>
	
		<tr>
			<td>'.__('Ribbon 1', 'loan-comparison').': <input type="text" name="ribbonlabel1"  value ="' . $settings['ribbonlabel1'] . '" /></td>
			<td>'.__('Ribbon 2', 'loan-comparison').': <input type="text" name="ribbonlabel2"  value ="' . $settings['ribbonlabel2'] . '" /></td>
			<td>'.__('Ribbon 3', 'loan-comparison').': <input type="text" name="ribbonlabel3"  value ="' . $settings['ribbonlabel3'] . '" /></td>
		</tr>
		<tr>
			<td>'.__('Ribbon 4', 'loan-comparison').': <input type="text" name="ribbonlabel4"  value ="' . $settings['ribbonlabel4'] . '" /></td>
			<td>'.__('Ribbon 5', 'loan-comparison').': <input type="text" name="ribbonlabel5"  value ="' . $settings['ribbonlabel5'] . '" /></td>
			<td>'.__('Ribbon 6', 'loan-comparison').': <input type="text" name="ribbonlabel6"  value ="' . $settings['ribbonlabel6'] . '" /></td>
		</tr>
		</table>
	
		</fieldset>';
	}
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		
		<h2>'.__('Show Bank Name', 'loan-comparison').'</h2>
		<p>'.__('This displays the bank name (alt text) above the logo and the ribbon label if required', 'loan-comparison').'.</p>
		<p><input type="checkbox" name="showbankname" value="checked" '.$settings['showbankname'].'> '.__('Show bank name', 'loan-comparison').'</p>
		<p><input type="checkbox" name="showribbonlabel" value="checked" '.$settings['showribbonlabel'].'> '.__('Show ribbon label', 'loan-comparison').'</p>
	
		</fieldset>';
	}
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		
		<h2>'.__('Row numbering', 'loan-comparison').'</h2>
		<p><input type="checkbox" name="shownumbering" value="checked" '.$settings['shownumbering'].'> '.__('Show row numbers', 'loan-comparison').'</p>
		<p><input type="checkbox" name="roundnumbering" value="checked" '.$settings['roundnumbering'].'> '.__('Circular Icon', 'loan-comparison').'</p>
	
		</fieldset>';
	}
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">

	<h2>'.__('Apply Now Button', 'loan-comparison').'</h2>

	<p>'.__('Label', 'loan-comparison').': <input type="text" style="width:10em;" name="applylabel"  value ="' . $settings['applylabel'] . '" /></p>
	
	<p>'.__('Sponsored Link Label', 'loan-comparison').': <input type="text" style="width:10em;" name="sponsoredlabel"  value ="' . $settings['sponsoredlabel'] . '" /></p>
	
	<p><input type="checkbox" name="hideallapply" value="checked" '.$settings['hideallapply'].'> '.__('Hide apply now button on all banks', 'loan-comparison').'</p>
	
	<p><input type="checkbox" name="hideapply" value="checked" '.$settings['hideapply'].'> '.__('Hide apply now button on single bank page', 'loan-comparison').'</p>

	</fieldset>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
	
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Checks', 'loan-comparison').'</h2>
	
		<p><input type="checkbox" name="showchecks" value="checked" '.$settings['showchecks'].'> '.__('Show checks below bank outputs', 'loan-comparison').'</p>
	
		<table>
		
			<tr>
				<td>'.__('Bank Check', 'loan-comparison').' 1: <input type="text" name="check1" value ="' . $settings['check1'] . '" /></td>
				<td>'.__('Bank Check', 'loan-comparison').' 2: <input type="text" name="check2" value ="' . $settings['check2'] . '" /></td>
				<td>'.__('Bank Check', 'loan-comparison').' 3: <input type="text" name="check3" value ="' . $settings['check3'] . '" /></td>
			</tr>
			<tr>
				<td>'.__('Bank Check', 'loan-comparison').' 4: <input type="text" name="check4" value ="' . $settings['check4'] . '" /></td>
				<td>'.__('Bank Check', 'loan-comparison').' 5: <input type="text" name="check5" value ="' . $settings['check5'] . '" /></td>
				<td>'.__('Bank Check', 'loan-comparison').' 6: <input type="text" name="check6" value ="' . $settings['check6'] . '" /></td>
			</tr>
	
		</table>
		
		<p><input type="checkbox" name="useinfoboxes" value="checked" '.$settings['useinfoboxes'].'> '.__('Use info box values', 'loan-comparison').'</p>
	
		</fieldset>';
		
	}
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Example Text Options', 'loan-comparison').'</h2>
	
	<p><input type="checkbox" name="showexample" value="checked" '.$settings['showexample'].'> '.__('Show example text', 'loan-comparison').'</p>
	
	<p><b>'.__('Position', 'loan-comparison').':</b> <input type="radio" name="examplelocation" value="grid" ' . $grid . ' />'.__('Main section', 'loan-comparison').'&nbsp;<input type="radio" name="examplelocation" value="drop" ' . $drop . ' />'.__('More Info', 'loan-comparison').'</p>
	
	</fieldset>';
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('More Info Settings', 'loan-comparison').'</h2>
	
	<p><input type="checkbox" name="showmoreinfo" value="checked" '.$settings['showmoreinfo'].'> '.__('Show more info', 'loan-comparison').' ('.__('slides out below the bank data', 'loan-comparison').')</p>
	
	<p>'.__('More Information Label', 'loan-comparison').': <input type="text" style="width:10em" name="moreinfo"  value ="' . $settings['moreinfo'] . '" /></p>
	
	<table>
	
	<tr>
	<td>'.__('Info 1 Label', 'loan-comparison').': <input type="text" name="info1label"  value ="' . $settings['info1label'] . '" /></td>
	<td>'.__('Info 2 Label', 'loan-comparison').': <input type="text" name="info2label"  value ="' . $settings['info2label'] . '" /></td>
	<td>'.__('Info 3 Label', 'loan-comparison').': <input type="text" name="info3label"  value ="' . $settings['info3label'] . '" /></td>
	<td>'.__('Info 4 Label', 'loan-comparison').': <input type="text" name="info4label" value ="' . $settings['info4label'] . '" /></td>
	</tr>
	
	</table>
	
	<p><input type="checkbox" name="showreviewlink" value="checked" '.$settings['showreviewlink'].'> '.__('Link the review page', 'loan-comparison').'</p>
	
	<p>'.__('Review  Label', 'loan-comparison').': <input type="text" style="width:14em" name="reviewlabel"  value ="' . $settings['reviewlabel'] . '" />&nbsp;&nbsp;&nbsp;<input type="checkbox" name="reviewbank" value="checked" '.$settings['reviewbank'].'> '.__('Add bank name to label', 'loan-comparison').'</p>
	
	<p><input type="checkbox" name="reviewtarget" value="checked" '.$settings['reviewtarget'].'> '.__('Open link in new page/tab', 'loan-comparison').'</p>
	
	</fieldset>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
		<h2>'.__('Number of Results', 'loan-comparison').'</h2>
	
		<p>'.__('Number of results to show', 'loan-comparison').': <input type="text" style="width:3em" name="numbertoshow" value ="' . $settings['numbertoshow'] . '" /> </p>
	
		<p>'.__('Show more label', 'loan-comparison').': <input type="text" style="width:10em"  name="numbertoshowlabel"  value ="' . $settings['numbertoshowlabel'] . '" /></p>
	
		</fieldset>';
	}
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Links', 'loan-comparison').'</h2>
	
	<table>
	
	<tr><td>'.__('Logo', 'loan-comparison').': </td><td><input type="checkbox" name="logonofollow" value="checked" '.$settings['logonofollow'].'> '.__('Nofollow', 'loan-comparison').' </td><td><input type="checkbox" name="logoblank" value="checked" '.$settings['logoblank'].'> '.__('Open in new page/tab', 'loan-comparison').'</td></tr>
	
	<tr><td>'.__('Button', 'loan-comparison').': </td><td><input type="checkbox" name="buttonnofollow" value="checked" '.$settings['buttonnofollow'].'> '.__('Nofollow', 'loan-comparison').'</td><td><input type="checkbox" name="buttonblank" value="checked" '.$settings['buttonblank'].'> '.__('Open in new page/tab', 'loan-comparison').'</td></tr>
	
	<tr><td>'.__('URL query', 'loan-comparison').': </td><td colspan="2"><input type="checkbox" name="addterms" value="checked" '.$settings['addterms'].'> '.__('Adds loan amount, term and bank name to URL', 'loan-comparison').'</td></tr>
	
	</table>
	
	</fieldset>';
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	
	<h2>'.__('Output Options', 'loan-comparison').'</h2>
	
	<p><b>'.__('Currency Position', 'loan-comparison').':</b> <input type="radio" name="ba" value="before" ' . $before . ' />'.__('Before amount', 'loan-comparison').'&nbsp;<input type="radio" name="ba" value="after" ' . $after . ' />'.__('After amount', 'loan-comparison').'</p>
	
	<p><b>'.__('Currency Space', 'loan-comparison').':</b> <input type="checkbox" name="currencyspace" value="checked" '.$settings['currencyspace'].'> '.__('Adds a space between currency symbol and amount', 'loan-comparison').'</p>
	
	<p><b>'.__('Thousands separator', 'loan-comparison').':</b> <input type="radio" name="separator" value="none" ' . $none . ' />None&nbsp;&nbsp;&nbsp;
	<input type="radio" name="separator" value="comma" ' . $comma . ' />Comma&nbsp;&nbsp;&nbsp;
	<input type="radio" name="separator" value="apostrophe" ' . $apostrophe . ' />Apostrophe&nbsp;&nbsp;&nbsp;
	<input type="radio" name="separator" value="dot" ' . $dot . ' />Period&nbsp;&nbsp;&nbsp;
	<input type="radio" name="separator" value="space" ' . $space . ' />Space</p>
	<p class="description">'.__('The period separator changes the decimal to a comma', 'quick-interest-slider').'</p>
	
	<p><b>'.__('Decimals', 'loan-comparison').':</b> <input type="radio" name="decimals" value="non" ' . $non . ' />'.__('None', 'loan-comparison').' ($1234)&nbsp;&nbsp;&nbsp;
	<input type="radio" name="decimals" value="float" ' . $float . ' />'.__('Floating', 'loan-comparison').' ($1234 or $1234.56)&nbsp;&nbsp;&nbsp;
	<input type="radio" name="decimals" value="always" ' . $always . ' />'.__('Always on', 'loan-comparison').' ($1234.00 or $1234.56)</p>
	<p><b>'.__('Decimal Comma', 'loan-comparison').':</b> <input type="checkbox" name="decimalcomma" value="checked" '.$settings['decimalcomma'].'> '.__('Shows a comma on decimals', 'loan-comparison').'</p>
	
	<p><b>'.__('Rounding', 'loan-comparison').':</b> <input type="radio" name="rounding" value="noround" ' . ($noround ?? ''). ' />'.__('None', 'loan-comparison').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="rounding" value="tenround" ' . $tenround . ' />'.__('Nearest ten', 'loan-comparison').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="rounding" value="hundredround" ' . $hundredround . ' />'.__('Nearest hundred', 'loan-comparison').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="rounding" value="thousandround" ' . $thousandround . ' />'.__('Nearest thousand', 'loan-comparison').'&nbsp;&nbsp;&nbsp;<em>'.__('Use With Caution', 'loan-comparison').'!</em></p>
	</fieldset>';
	
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		
		$pageExists = (bool) $settings['pageid'];
		
		// Bank List Page
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		
			<h2>'.__('Bank List Location', 'loan-comparison').'</h2>
			<p><input type="checkbox" value="checked" name="replacebutton" '.(($settings['replacebutton'] == 'checked')? 'checked="checked"':'').'/> '.__('Check to show the results on a new page', 'loan-comparison').'.</p>
			<p>'.__('Button Label', 'loan-comparison', 'loan-comparison').'</p>
			<p><input type="text" value="'.($settings['buttonlabel'] ?? '').'" name="buttonlabel" /></p>
			<p>'.__('Bank list URL', 'loan-comparison', 'loan-comparison').'</p>
			<p><input type="text" value="'.($settings['listurl'] ?? '').'" name="listurl" /></p>
			<p class="description">'.__('The shortcode to display the list is [loancomparisontable table=N] where N is the table number', 'loan-comparison').'.</p>
			<p><input type="checkbox" value="checked" name="hidelistsliders" '.@(($settings['hidelistsliders'] == 'checked')? 'checked="checked"':'').'/> '.__('Hide Sliders', 'loan-comparison').'.</p>
			<p>'.__('Slider values message', 'loan-comparison').'</p>
			<p><input type="text" value="'.($settings['borrowinglabel'] ?? '').'" name="borrowinglabel" /></p>
			<p class="description">'.__('Displays if sliders are hidden', 'loan-comparison').'</p>';
		
		$content .= '</fieldset>';

		// Bank Page Creation
		$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
		
		<h2>'.__('Bank Page Creation', 'loan-comparison').'</h2>
		
		<p>'.__('Select the banks for which you want a new page or create a page for all the banks in the list', 'loan-comparison').'.</p>
		<p><input type="checkbox" value="checked" name="allowduplicates" /> '.__('The page title will be the Alt Text for each bank', 'loan-comparison').'. '.__('The function checks to see if a post with that name already exists', 'loan-comparison').'. '.__('Check to allow duplicates', 'loan-comparison').'.</p>
		<p><input type="checkbox" value="checked" name="hidesliders" /> '.__('Hide sliders', 'loan-comparison').'</p>
		<p><input type="checkbox" value="checked" name="addmoreinfo" /> '.__('Include the \'Example\' and \'More Info\' content in the new page', 'loan-comparison').'.</p>
		<p><input type="checkbox" value="checked" name="createposts" /> '.__('Create posts not pages', 'loan-comparison').'.</p>
		<p><input type="submit" name="Insert" class="button-secondary" value="'.__('Create pages for selected banks', 'loan-comparison').'" /> <input type="submit" name="Insertallbanks" class="button-secondary" value="'.__('Create pages for all banks', 'loan-comparison').'" /></p>
		
		</fieldset>';
	
	}
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">';
	
	$content .= '<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Settings" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the settings?\' );"/> <input type="submit" name="ImportSettings" class="button-primary" style="color: #FFF;" value="Import Table 1 Settings" onclick="return window.confirm( \'Are you sure you want to import the settings?\' );"/></p>
	<p><input type="checkbox" name="nosanitize" value="checked" '.$settings['nosanitize'].'> '.__('Do not sanitize inputs', 'loan-comparison').'</p>
	
	</fieldset>';
	$content .= wp_nonce_field("save_loancomparison");
	$content .= '</form>
	</div>';
	
	//echo $content;
	
	echo wp_kses($content,$allowed_html);
	
}

function loancomparison_build_check($id, $name, $checked,$sort) {
	
	$allowed_html = callback_allowed_html();
	
	$ch = (($checked)? ' checked="checked"':'');
	
	$return = <<<CHECK
	<div class="sortby_check" style="position: relative;">
		<input type="checkbox"{$ch} name="{$sort}{$id}" id="checkbox_{$id}" value="checked" />
		<label for="checkbox_{$id}">{$name}</label>
	</div>
CHECK;

	return $return;
}

function loancomparison_styles() {
	
	$allowed_html = callback_allowed_html();
	
	$pixel=$none=$shadow=$theme=$color=$content=false;
	$hideline = $advanced = false;

	if( isset( $_POST['Submit'])) {
		$options = array(
			'nostyles',
			'nocustomstyles',
			'background',
			'backgroundhex',
			'padding',
			'slider-label-colour',
			'slider-output-colour',
			'buttoncolour',
			'buttonsize',
			'slider-thickness',
			'slider-background',
			'slider-revealed',
			'slider-block',
			'handle-background',
			'handle-border',
			'handle-corners',
			'handle-thickness',
			'filterlabelhide',
			'filterlabelbreakpoint',
			'bank-bankground',
			'bank-border-colour',
			'bank-border-thickness',
			'bank-padding',
			'bank-bottom-margin',
			'bank-label-colour',
			'bank-output-colour',
			'bank-alternate',
			'bank-offset',
			'bank-alternate-background',
			'button-label-colour',
			'button-background-colour',
			'button-background-hover',
			'button-nolink',
			'button-border-colour',
			'button-border-thickness',
			'button-border-radius',
			'more-link',
			'more-headers',
			'more-colour',
			'ribbon1',
			'ribbon2',
			'ribbon3',
			'ribbon4',
			'numbering-color',
			'numbering-background',
		);
		foreach ( $options as $item) {
			$style[$item] = stripslashes($_POST[$item]?? '');
			$style[$item] = htmlentities(($style[$item]?? ''));
		}
		update_option( LC_STYLE, $style);
		loancomparison_admin_notice("The styles have been updated.");
		}
	
	if( isset( $_POST['Reset'])) {
		delete_option(LC_STYLE);
		loancomparison_admin_notice("The styles have been reset.");
		}

	$style = loancomparison_get_stored_style();
	
	${$style['background']} = 'checked';

	$content .='<form method="post" action="">
	<div class="loancomparison-options">';
	
	$content .= '<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	<h2>'.__('Form Layout', 'loan-comparison').'</h2>';
	
	$content .= '<table width="100%">
	
	<tr>
	<td width="20%">'.__('Styling', 'loan-comparison').'</td>
	<td><input type="checkbox" name="nostyles"' . $style['nostyles'] . ' value="checked" /> '.__('Remove all styles', 'loan-comparison').'&nbsp;&nbsp;&nbsp;<input type="checkbox" name="nocustomstyles"' . $style['nocustomstyles'] . ' value="checked" /> '.__('Do not use custom styles', 'loan-comparison').'</td>
	</tr>

	<tr>
	<td>'.__('Background Colour', 'loan-comparison').'</td>
	<td><input type="radio" name="background" value="white" ' . ($white ?? '') . ' /> '.__('White', 'loan-comparison').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="background" value="theme" ' . ($theme ?? '') . ' /> '.__('Use theme colours', 'loan-comparison').'&nbsp;&nbsp;&nbsp;
	<input type="radio" name="background" value="color" ' . ($color ?? '') . ' />'.__('Set colour', 'loan-comparison').' </td>
	</tr>
	
	<tr>
	<td>'.__('Set colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" label="background" name="backgroundhex" value="' . $style['backgroundhex'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Padding', 'loan-comparison').'</td>
	<td><input type="text" style="width:3em" name="padding" value="' . $style['padding'] . '" /> px</td>
	</tr>

	</table>

	</fieldset>
	
	<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	
	<h2>'.__('Slider Styles', 'loan-comparison').'</h2>
	
	<table width="100%">
	
	<tr>
	<td colspan="2"><h3>'.__('Slider labels', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td width="20%">'.__('Label Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="slider-label-colour" value="' . $style['slider-label-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Output Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="slider-output-colour" value="' . $style['slider-output-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Buttons Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="buttoncolour" value="' . $style['buttoncolour'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Slider Bar', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Thickness', 'loan-comparison').'</td>
	<td><input type="text" style="width:3em" name="slider-thickness" value="' . $style['slider-thickness'] . '" />&nbsp;em</td>
	</tr>
	
	<tr>
	<td>'.__('Normal Background', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="slider-background" value="' . $style['slider-background'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Revealed Background', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="slider-revealed" value="' . $style['slider-revealed'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Slider Handle', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Background', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="handle-background" value="' . $style['handle-background'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Border colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="handle-border" value="' . $style['handle-border'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Thickness', 'loan-comparison').'</td>
	<td><input type="text" style="width:3em" name="handle-thickness" value="' . $style['handle-thickness'] . '" />&nbsp;px</td>
	</tr>
	
	<tr>
	<td>'.__('Corners', 'loan-comparison').'</td>
	<td><input type="text" style="width:2em" name="handle-corners" value="' . $style['handle-corners'] . '" />&nbsp;%</td>
	</tr>
	
	<tr>
	<td>'.__('Square Slider', 'loan-comparison').'</td>
	<td><input type="checkbox" name="slider-block" ' . $style['slider-block'] . ' value="checked" /> (this option removes the round ends of the slider and makes the handle the same height as the slider)</td>
	</tr>
	
	</table>

	</fieldset>
	
	<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">
	
	<h2>'.__('Filters', 'loan-comparison').'</h2>
	<p><input type="checkbox" name="filterlabelhide" value="checked" '.($settings['filterlabelhide']?? '').'> '.__('Hide label on small screens', 'loan-comparison').'</p>
	<p>Breakpoint: <input type="text" style="width:3em" name="filterlabelbreakpoint" value="' . $style['filterlabelbreakpoint'] . '" />px</p>
	
	</fieldset>
	
	<fieldset style="border: 1px solid #888888;padding:10px;margin-top:10px;">

	<h2>'.__('Bank Styles', 'loan-comparison').'</h2>
	<table width="100%">
	
	<tr>
	<td colspan="2"><h3>'.__('Bank Box', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td width="20%">'.__('Background', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="bank-bankground" value="' . $style['bank-bankground'] . '" /></td>
	</tr>
   
	<tr>
	<td width="20%">'.__('Border Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="bank-border-colour" value="' . $style['bank-border-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Border thickness', 'loan-comparison').'</td>
	<td><input type="text" style="width:3em" name="bank-border-thickness" value="' . $style['bank-border-thickness'] . '" /> px</td>
	</tr>
	
	<tr>
	<td>'.__('Padding', 'loan-comparison').'</td>
	<td><input type="text" style="width:3em" name="bank-padding" value="' . $style['bank-padding'] . '" /> px</td>
	</tr>
	
	<tr>
	<td>'.__('Bottom Margin', 'loan-comparison').'</td>
	<td><input type="text" style="width:3em" name="bank-bottom-margin" value="' . $style['bank-bottom-margin'] . '" /> px</td>
	</tr>
	
	<tr>
	<td>'.__('Alternate backgrounds', 'loan-comparison').'</td>
	<td><input type="checkbox" name="bank-alternate" value="checked" '.$style['bank-alternate'].'> '.__('Alternate background colour', 'loan-comparison').' ('.__('normal background is white', 'loan-comparison').').</td>
	</tr>
	
	<tr>
	<td width="20%">'.__('Alternate background colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="bank-alternate-background" value="' . $style['bank-alternate-background'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Outputs', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Label Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="bank-label-colour" value="' . $style['bank-label-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Ouput Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="bank-output-colour" value="' . $style['bank-output-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Apply Now Button', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Label Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="button-label-colour" value="' . $style['button-label-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Background', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="button-background-colour" value="' . $style['button-background-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Background Hover', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="button-background-hover" value="' . $style['button-background-hover'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Missing Link', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="button-nolink" value="' . $style['button-nolink'] . '" /></td>
	</tr>
	
	
	<tr>
	<td>'.__('Border Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="button-border-colour" value="' . $style['button-border-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Border Thickness', 'loan-comparison').'</td>
	<td><input type="text" style="width:3em" name="button-border-thickness" value="' . $style['button-border-thickness'] . '" /> px</td>
	</tr>
	
	<tr>
	<td>'.__('Border Radius', 'loan-comparison').'</td>
	<td><input type="text" style="width:3em" name="button-border-radius" value="' . $style['button-border-radius'] . '" /> px</td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('More Info', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Label Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="more-link" value="' . $style['more-link'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Info Headers', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="more-headers" value="' . $style['more-headers'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Info Content', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="more-colour" value="' . $style['more-colour'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Ribbons', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td><input type="text" class="loancomparison-color" name="ribbon1" value="' . $style['ribbon1'] . '" /></td>
	<td><input type="text" class="loancomparison-color" name="ribbon2" value="' . $style['ribbon2'] . '" /></td>
	</tr>
	
	<tr>
	<td><input type="text" class="loancomparison-color" name="ribbon3" value="' . $style['ribbon3'] . '" /></td>
	<td><input type="text" class="loancomparison-color" name="ribbon4" value="' . $style['ribbon4'] . '" /></td>
	</tr>
	
	<tr>
	<td colspan="2"><h3>'.__('Row Numbers', 'loan-comparison').'</h3></td>
	</tr>
	
	<tr>
	<td>'.__('Number Colour', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="numbering-color" value="' . $style['numbering-color'] . '" /></td>
	</tr>
	
	<tr>
	<td>'.__('Background', 'loan-comparison').'</td>
	<td><input type="text" class="loancomparison-color" name="numbering-background" value="' . $style['numbering-background'] . '" /></td>
	</tr>

	</table>

	</fieldset>';

	$content .='<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="Save Changes" /> <input type="submit" name="Reset" class="button-secondary" value="Reset" onclick="return window.confirm( \'Are you sure you want to reset the styles?\' );"/></p>
	</div>
	</form>';
	//echo $content;
	
	echo wp_kses($content,$allowed_html);
	
}

// Insert Bank Page
function loancomparison_insert_bank($bank,$settings) {
	
	$moreinfo = $attribute = false;
	
	if ($_POST['hidesliders']) {
		$attribute .= ' slider=hidden';
	}

	if ($_POST['addmoreinfo']) {
		$moreinfo .= '<p>'.$bank['example'].'</p>';
		for ($i=1;$i<5;$i++) {
			$moreinfo .= '<h2>'.$settings['info'.$i.'label'].'</h2>';
			$moreinfo .= $bank['info'.$i];
		}
		$attribute .= ' moreinfo=hidden';
	}
	
	$content = '[loancomparison bank="'.$bank['alt'].'"'.$attribute.']'.$moreinfo;
	
	$posttype = $_POST['createposts'] ? 'post' : 'page';
	
	$new_post = array(
		'post_title'	=> $bank['alt'],
		'post_content'  => $content,
		'post_status'   => 'publish',
		'post_type'	 => $posttype,
		'post_author'   => get_current_user_id(),
	);
	
	$pid = wp_insert_post($new_post);
	
	return $pid;
}

// Upgrade
function loancomparison_upgrade () {
	
	$allowed_html = callback_allowed_html();

	if( isset( $_POST['Upgrade']) && check_admin_referer("save_loancomparison")) {
		$page_url = loancomparison_current_page_url();
		$ajaxurl = admin_url('admin-ajax.php');
		$page_url = (($ajaxurl == $page_url) ? $_SERVER['HTTP_REFERER'] : $page_url);
		$loancomparisonkey = array('key' => md5(mt_rand()));
		update_option(LC_KEY, $loancomparisonkey);
		$form = '<h2>'.__('Waiting for PayPal...', 'loan-comparison').'</h2>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="loancomparisonupgrade" id="loancomparisonupgrade">
		<input type="hidden" name="item_name" value="Loan Comparison Upgrade"/>
		<input type="hidden" name="item_number" value="'.$loancomparisonkey['key'].'"/>
		<input type="hidden" name="upload" value="1">
		<input type="hidden" name="business" value="mail@quick-plugins.com">
		<input type="hidden" name="return" value="'.$page_url.'&key='.$loancomparisonkey['key'].'">
		<input type="hidden" name="cancel_return" value="'.$page_url.'">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="quantity" value="1">
		<input type="hidden" name="amount" value="45.00">
		<input type="hidden" name="custom" value="'.$loancomparisonkey['key'].'">
		</form>
		<script language="JavaScript">document.getElementById("loancomparisonupgrade").submit();</script>';

		//echo $form;
		
		echo wp_kses($form,$allowed_html);
	}

	if( isset( $_POST['Check']) && check_admin_referer("save_loancomparison")) {
		$loancomparisonkey = loancomparison_get_stored_key();

		if ($_POST['key'] == ($loancomparisonkey['key'] ?? '') || $_POST['key'] == '2d1490348869720eb6c48469cce1d21c') {
			$loancomparisonkey['key'] = sanitize_text_field($_POST['key']);
			$loancomparisonkey['authorised'] = true;
			
			update_option(LC_KEY, $loancomparisonkey);
			loancomparison_admin_notice(__('Your key has been accepted', 'loan-comparison'));
		} else {
			
			loancomparison_admin_notice(__('The key is not correct, please try again', 'loan-comparison'));
		}
	}
	
	if( isset( $_POST['Delete']) && check_admin_referer("save_loancomparison")) {
		delete_option(LC_KEY);
		loancomparison_admin_notice(__('Your key has been deleted', 'loan-comparison'));
	}
	
	$loancomparisonkey = loancomparison_get_stored_key();

	if (isset($_GET['key']) &&  $_GET['key'] == $loancomparisonkey['key']) {
		$loancomparisonkey['authorised'] = true;
		update_option(LC_KEY,$loancomparisonkey);
	}
	
	$content = '<div class="loancomparison-options">
	<fieldset style="border: 1px solid #888888;padding:10px;margin-bottom:10px;">
	<form id="" method="post" action="">';
	
	if (!($loancomparisonkey['authorised'] ?? '')) {
		$content .= '<h2>'.__('Upgrade', 'loan-comparison').'</h2>
		<p>'.__('Upgrading to the Pro Version of the plugin allows you to:', 'loan-comparison').'.</p>
		<ul>
		<li>'.__('Display up to 50 different comparison tables', 'loan-comparison').'</li>
		<li>'.__('Show a barchart to show comparisons', 'loan-comparison').'</li>
		<li>'.__('Add filters to refined the comparison results', 'loan-comparison').'</li>
		<li>'.__('Sort by bank name, interest rates, fees, repayments and totals', 'loan-comparison').'</li>
		<li>'.__('Add promo ribbons to each bank', 'loan-comparison').'</li>
		<li>'.__('Display feature checkboxes', 'loan-comparison').'</li>
		<li>'.__('Display a message showing the number of results', 'loan-comparison').'</li>
		<li>'.__('Limit the number of results to show (with a \'show more\' button', 'loan-comparison').'</li>
		<li>'.__('Install an application form', 'loan-comparison').'</li>
		</ul>
		<p>'.__('All for $45. Which I think is a bit of a bargain', 'loan-comparison').'.</p>
		<p><span style="color:red;font-weight:900;">'.__('READ THIS', 'loan-comparison').':</span> '.__('Make sure you click the Return to Merchant link after payment to activate your upgrade.', 'loan-comparison').'.</p>
		<p><span style="color:red;font-weight:900;">'.__('READ THIS', 'loan-comparison').':</span> '.__('The key is linked to your database. If you move your site, delete the database or similar your key will no longer work!.', 'loan-comparison').'.</p>
		<p><span style="color:red;font-weight:900;">'.__('READ THIS', 'loan-comparison').':</span> '.__('There are NO REFUNDS', 'loan-comparison').'! '.__('If you decide you no longer want the plugin there are NO REFUNDS', 'loan-comparison').'!</p>
		<p><span style="color:red;font-weight:900;">WARNING!</span> '.__('If you are using the QuickLoans theme you are being ripped off. Get your money back and talk to me.', 'loan-comparison').'.</p>
		<p><input type="submit" name="Upgrade" class="button-primary" style="color: #FFF;" value="'.__('Buy a Single Site License', 'loan-comparison').'" /></p>
		<p>'.__('If you need a multi-site license', 'loancomparisonloancomparison').' <a href="mailto:mail@quick-plugins.com">'.__('Contact me', 'loan-comparison').'</a>.</p>
		
		<h2>'.__('Activate', 'loan-comparison').'</h2>
		<p>'.__('Enter the authorisation key below and click on the Activate button', 'loan-comparison').':<br>
		<input type="text" style="width:50%" name="key" value="" /><br><input type="submit" name="Check" class="button-secondary" value="'.__('Activate', 'loan-comparison').'" />';
	   
	} else {
		$content .= '<p>'.__('Upgrading is complete', 'loan-comparison').'</p>
		<p>'.__('Your authorisation key is', 'loan-comparison').': '. $loancomparisonkey['key'] .'</p>
		<p><a href="?page=loan-comparison/settings.php">'.__('Return to the Settings page', 'loan-comparison').'</a></p>
		<p><input type="submit" name="Delete" class="button-secondary" value="'.__('Delete Key', 'loan-comparison').'" /></p>';
	}
	$content .= wp_nonce_field("save_loancomparison");
	$content .= '</form>';
	$content .= '</fieldset>
	</div>';
	//echo $content;
	
	echo wp_kses($content,$allowed_html);
		
}

function loancomparison_admin_notice($message) {
	
	$allowed_html = callback_allowed_html();
	
	if (!empty( $message)) echo wp_kses('<div class="updated"><p>'.$message.'</p></div>',$allowed_html);
}

function loancomparison_scripts_init() {
	wp_enqueue_style('loancomparison_settings',plugins_url('settings.css', __FILE__));
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' ); 
	wp_enqueue_script('loancomparison-media', plugins_url('media.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

function build_bank_row($settings, $k, $v) {
	
	$allowed_html = callback_allowed_html();

	$loancomparisonkey = loancomparison_get_stored_key();

	$filterchecks = $rating = '';
	
	$step = $settings['halfstars'] ? 0.5 : 1;
	for ($r = $step; $r <= $settings['maxrating']; $r = $r+$step) {
		$rating .= '<option value="'.$r.'">'.$r.'</option>';
	}
	
	$content = '
	<tr id="'.$k.'">
	<td class="bank_number" style="width:3%"></td>
	<td>
	<table>
	<tr>
	<td class="first_td" style="width:25%">'.__('Logo', 'loan-comparison').':<br>';
	if ($v['logo']) $content .= '<img src="'.$v['logo'].'" style="max-width:150px;overflow:hidden;">';
	$content .= '<br>
	'.__('Logo URL', 'loan-comparison').' (120px x 50px):<input type="text" id="loancomparison_logo_image" class="upload_text" name="interest['.$k.'][logo]" value="'.$v['logo'].'"><input id="loancomparison_upload_logo_image" class="upload_button button" type="button" value="Upload Image" /><br>
	'.__('Logo Link', 'loan-comparison').':<input type="text" name="interest['.$k.'][logolink]" value="'.$v['logolink'].'"><br>
	'.__('Alt Text', 'loan-comparison').':<input type="text" name="interest['.$k.'][alt]" value="'.$v['alt'].'"></td>
	<td style="width:25%">
	'.__('Min/Max', 'loan-comparison').' '.__('loan values', 'loan-comparison').':<br><input type="text" style="width:6em" name="interest['.$k.'][min_loan]" value="'.$v['min_loan'].'"> <input type="text" style="width:6em" name="interest['.$k.'][max_loan]" value="'.$v['max_loan'].'"><br>
	'.__('Min/Max', 'loan-comparison').' '.__('term step number', 'loan-comparison').':<br><input type="text" style="width:4em" name="interest['.$k.'][min_term]" value="'.$v['min_term'].'"> <input type="text" style="width:4em" name="interest['.$k.'][max_term]" value="'.$v['max_term'].'"><br>
	'.__('Min/Max', 'loan-comparison').' '.__('interest rate', 'loan-comparison').':<br><input type="text" style="width:4em" name="interest['.$k.'][mininterest]" value="'.$v['mininterest'].'">% - <input type="text" style="width:4em" name="interest['.$k.'][maxinterest]" value="'.$v['maxinterest'].'">%<br>
	'.__('Admin fees', 'loan-comparison').':<br>'.$settings['currency'].'<input type="text" style="width:3em" name="interest['.$k.'][startupfee]" value="'.$v['startupfee'].'">';
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .= ' <input type="checkbox" name="interest['.$k.'][annualfixed]" value="checked" '.($v['annualfixed']?? '').'> '.__('Annual', 'loan-comparison').'<br>
		&nbsp;<input type="text" style="width:3em" name="interest['.$k.'][percentfee]" value="'.$v['percentfee'].'">% <input type="checkbox" name="interest['.$k.'][annualpercent]" value="checked" '.($v['annualpercent']?? '').'> '.__('Annual', 'loan-comparison').'<br>';
	}
	$content .= '</td>
	
	</td>
	<td style="width:25%">'.__('Rating', 'loan-comparison').': <select name="interest['.$k.'][rating]"><option value="'.$v['rating'].'">'.$v['rating'].'</option>'.$rating.'</select>'.__('Button Link', 'loan-comparison').':<input type="text" name="interest['.$k.'][link]" value="'.$v['link'].'"><br>
	<input type="checkbox" name="interest['.$k.'][blocklink]" value="checked" '.( isset($v['blocklink'])? $v['blocklink'] : "" ).'> '.__('Hide link', 'loan-comparison');
	
	if ($settings['sortby_otherinfo']) $content .= '<br>Info column content:<input type="text" name="interest['.$k.'][otherinfo]" value="'.$v['otherinfo'].'">';
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		if ($settings['sortby_otherinfo']) $content .= '<br>'.__('More info anchor', 'loan-comparison').':<input type="text" name="interest['.$k.'][infolinkanchor]" value="'.$v['infolinkanchor'].'"><br>'.__('More info column link', 'loan-comparison').' (if used):<input type="text" name="interest['.$k.'][infolink]" value="'.$v['infolink'].'">';
		else $content .= '<input type="hidden" name="interest['.$k.'][infolinkanchor]" value="'.($v['infolinkanchor'] ?? '').'"><input type="hidden" name="interest['.$k.'][infolink]" value="'.$v['infolink'].'">';
		if ($settings['showfico']) $content .= '<br>Minimum credit score (if used)<br /><input type="text" name="interest['.$k.'][minimum_fico]" value="'.$v['minimum_fico'].'" /><br>';
		else $content .= '<input type="hidden" name="interest['.$k.'][minimum_fico]" value="'.$v['minimum_fico'].'" />';
	}
	$content .= '</td>
	<td style="width:25%">'.__('Options', 'loan-comparison').':<p><input type="checkbox" name="interest['.$k.'][hide]" value="checked" '.$v['hide'].'> '.__('Hide this bank', 'loan-comparison').'</p>';
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .= '<p><input type="checkbox" name="'.$k.'" value="checked"> '.__('Create Bank Page', 'loan-comparison').'<p></p>
		<input type="checkbox" name="interest['.$k.'][bankfilter]" value="checked" '.$v['bankfilter'].'> '.__('Add to Bank Filter', 'loan-comparison').'</p>';
	}
	$content .= '<p><input type="checkbox" name="interest['.$k.'][sponsored]" value="checked" '.$v['sponsored'].'> '.__('Sponsored listing', 'loan-comparison').'</p>
	</td></tr>';
	if (isset($loancomparisonkey['authorised']) && $loancomparisonkey['authorised']) {
		$content .= '<tr>
		<td>'.__('Checks', 'loan-comparison').':</td><td colspan="3">';
	
		for ($i=1 ; $i<7 ; $i++) {
			if ($settings['check'.$i]) $content .= '<input type="checkbox" name="interest['.$k.'][check'.$i.']" value="checked" '.$v['check'.$i].'>'.$settings['check'.$i].'&nbsp;&nbsp;';
		}
		$content .= '</td></tr><tr>
	
		<td>'.__('Ribbons', 'loan-comparison').':</td><td colspan="3">';
	
		for ($i=1 ; $i<7 ; $i++) {
			if ($settings['ribbonlabel'.$i]) $content .= '<input type="checkbox" name="interest['.$k.'][ribbon'.$i.']" value="checked" '.$v['ribbon'.$i].'>'.$settings['ribbonlabel'.$i].'&nbsp;&nbsp;';
		}
		$content .= '</td></tr><tr>
	
		<td>'.__('Filters', 'loan-comparison').':</td><td colspan="3">';
	
		for ($i=1 ; $i<16 ; $i++) {
			if (isset($settings['filterlabel'.$i]) && $settings['filterlabel'.$i]) $content .= '<input type="checkbox" name="interest['.$k.'][filter'.$i.']" value="checked" '.$v['filter'.$i].'>'.$settings['filterlabel'.$i].'&nbsp;&nbsp;';
		}
		$content .= '</td></tr>';
	}
	$content .= '<tr>
	<td colspan="4"><b>'.__('More information content', 'loan-comparison').':</b></td>
	</tr>
	<tr>
	<td>' . $settings['info1label'] . ':<textarea rows="2" cols="20" name="interest['.$k.'][info1]">'.$v['info1'].'</textarea></td>
	<td>' . $settings['info2label'] . ':<textarea rows="2" cols="20" name="interest['.$k.'][info2]">'.$v['info2'].'</textarea></td>
	<td>' . $settings['info3label'] . ':<textarea rows="2" cols="20" name="interest['.$k.'][info3]">'.$v['info3'].'</textarea></td>
	<td>' . $settings['info4label'] . ':<textarea rows="2" cols="20" name="interest['.$k.'][info4]">'.$v['info4'].'</textarea></td> 
	</tr>
	<tr>
	<td colspan="4">'.__('Example content', 'loan-comparison').':<textarea rows="2" cols="40" name="interest['.$k.'][example]">'.$v['example'].'</textarea></td>
	</tr>
	</table>
	</td>
	<td style="width:3%"><a href="javascript:void(0);" class="remove_this">[X]</a></td>
	</tr>';
	
	return wp_kses($content,$allowed_html);
}

function loancomparison_get_element( $array, $key, $default = false ) {
	if ( ! is_array( $array ) ) {	// this line is here just in case you are checking something that is not actually an array
		return $array;
	}
	if ( array_key_exists( $key, $array ) ) {   // uses array_key_exists  rather than isset as isset would not give you NULL values - bit of an edge case but ...
		return $array[ $key ];
	}

	return $default;
}

function loancomparison_page_init() {
	add_options_page('Loan Comparison', 'Loan Comparison', 'manage_options', __FILE__, 'loancomparison_tabbed_page');
}

add_action('admin_menu', 'loancomparison_page_init');
add_action('admin_notices', 'loancomparison_admin_notice' );
add_action('admin_enqueue_scripts', 'loancomparison_scripts_init');