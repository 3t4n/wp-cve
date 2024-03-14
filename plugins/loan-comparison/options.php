<?php

function loancomparison_get_stored_key() {
	$key = get_option(LC_KEY);
	if ($key) return $key;
	else return array('authorised' => false);
}

function loancomparison_get_stored_formnumber() {
	return get_option(LC_FORMNUMBER);
}

function loancomparison_get_stored_settings($table) {
	
	$settings = get_option(LC_SETTINGS.'_new'.$table);
	
	if($settings) {
		update_option(LC_SETTINGS.$table,$settings);
		//delete_option(LC_SETTINGS.'_new');
	}
	
	$settings = get_option(LC_SETTINGS.$table);
	
	if(!$settings) {
		$settings = array(
			'ficolinktarget'	=> 'checked',
			'logoblank'			=> 'checked',
			'buttonblank'		=> 'checked',
			'hideapply'			=> 'checked',
			'minfee'			=> 'checked',
			'sort_rating'		=> 'checked',
			'sort_interest'		=> 'checked',
			'sort_loanamount'	=> 'checked',
			'sort_bankname' 	=> 'checked',
			'sort_repayment'	=> 'checked',
			'sort_total'		=> 'checked',
			'sortby_interest'	=> 'checked',
			'sortby_loanamount'	=> 'checked',
			'sortby_repayment'	=> 'checked',
			'sortby_total'		=> 'checked',
			'showmoreinfo'		=> 'checked'
		);
	}

	$default = array(
		'currency'			=> '$',
		'ba'			=> 'before',
		'currencyspace'		=> false,
		'separator'			=> 'none',
		'decimalcomma'		=> '',
		'decimals'		=> 'always',
		'rounding'			=> 'noround',
		'fico'			=> array(
			array('min'	=> 360, 'max'	=> 580, 'label'	=> 'Very Poor','selected'	=> false),
			array('min'	=> 580, 'max'	=> 640, 'label'	=> 'Poor','selected'	=> false),
			array('min'	=> 640, 'max'	=> 700, 'label'	=> 'Fair','selected'	=> true),
			array('min'	=> 700, 'max'	=> 750, 'label'	=> 'Good','selected'	=> false),
			array('min'	=> 750, 'max'	=> 850, 'label'	=> 'Excellent','selected'	=> false)
		),
		'showfico'			=> '',
		'ficolabel'			=> 'Your Credit Score',
		'ficolinklabel'		=> 'To check your credit score visit [a]myfico.com[/a].',
		'ficolink'			=> 'https://www.myfico.com/',
		'loan'				=> array(
			array(
				'min'		=> 100,
				'max'		=> 900,
				'step'		=> 100,
			),
			array(
				'min'		=> 1000,
				'max'		=> 10000,
				'step'		=> 500,
			),
			array(
				'min'		=> '',
				'max'		=> '',
				'step'		=> '',
			)
		),
		'period'		=> array(
			array(
				'min'		=> 7,
				'max'		=> 14,
				'step'		=> 7,
				'term'		=> 'D',
			),
			array(
				'min'		=> 1,
				'max'		=> 11,
				'step'		=> 1,
				'term'		=> 'M',
			),
			array(
				'min'		=> 1,
				'max'		=> 18,
				'step'		=> 1,
				'term'		=> 'Y',
			)
		),
		'loaninitial'		=> 10,
		'periodinitial'		=> 15,
		'loanhide'			=> false,
		'termhide'			=> false,
		'napr'				=> false,
		'simpleinterest'	=> false,
		'barchartenabled'	=> false,
		'barchartorder'		=> false,
		'formtitle'			=> 'Loan Comparisons',
		'showfeatures'		=> '',
		'feature1'			=> 'Compare loans',
		'feature2'			=> 'Sort results',
		'feature3'			=> 'Apply online',
		'showfilters'		=> '',
		'showsponsored'		=> '',
		'filterlabel'		=> __('Filter for:', 'loan-comparison'),
		'filterlabel1'		=> __('Under 18 loans', 'loan-comparison'),
		'filterlabel2'		=> __('Zero fees', 'loan-comparison'),
		'filterlabel3'		=> __('Early repayment', 'loan-comparison'),
		'filterlabel4'		=> '',
		'filterlabel5'		=> '',
		'filterlabel6'		=> '',
		'filterdefault'		=> '',
		'filtertype'		=> 'checkbox',
		'showbankfilters'	=> '',
		'bankfilterlabel'	=> 'Filter by bank:',
		'bankfiltertype'	=> '',
		'showheader'		=> '',
		'loanlabel'			=> __('Loans From', 'loan-comparison'),
		'loanslabel'		=> __('Min/Max Loan', 'loan-comparison'),
		'feeslabel'			=> __('Admin Fee', 'loan-comparison'),
		'interestlabel'		=> __('Typical Interest', 'loan-comparison'),
		'interestamountlabel'=> __('Total Interest', 'loan-comparison'),
		'repaymentlabel'	=> __('Repayments', 'loan-comparison'),
		'totallabel'		=> __('Total Cost', 'loan-comparison'),
		'termlabel'			=> __('Min/Max Term', 'loan-comparison'),
		'applylabel'		=> __('Apply Now', 'loan-comparison'),
		'loancostlabel'		=> __('Loan Cost', 'loan-comparison'),
		'otherinfolabel'	=> __('Other Info', 'loan-comparison'),
		'creditscorelabel'	=> __('Credit Score', 'loan-comparison'),
		'ratinglabel'		=> __('Bank Rating', 'loan-comparison'),
		'banknamelabel'		=> __('Bank Name', 'loan-comparison'),
		'sponsoredlabel'	=> __('Sponsored', 'loan-comparison'),
		'infolinklabel'		=> __('More Info', 'loan-comparison'),
		'infolinkanchor'	=> __('Read More', 'loan-comparison'),
		'moreinfo'			=> __('More Information', 'loan-comparison'),
		'info1label'		=> __('Free Bananas', 'loan-comparison'),
		'info2label'		=> __('Daily Newspapers', 'loan-comparison'),
		'info3label'		=> __('Meet Penguins', 'loan-comparison'),
		'info4label'		=> __('Eat More Fruit', 'loan-comparison'),
		'showreviewlink'	=> '',
		'reviewlabel'		=> __('Review', 'loan-comparison'),
		'reviewtarget'		=> '',
		'reviewbank'		=> 'checked',
		'showchecks'		=> '',
		'check1'			=> 'Free Beer',
		'check2'			=> 'Open All Day',
		'check3'			=> 'Cuddly Toys',
		'check4'			=> 'Aardvarks',
		'check5'			=> '',
		'check6'			=> '',
		'useinfoboxes'		=> '',
		'showexample'		=> '',
		'examplelocation'	=> 'grid',
		'logonofollow'		=> '',
		'buttonnofollow'	=> '',
		'hideallapply'		=> '',
		'addterms'			=> '',
		'loanslider'		=> __('Amount:', 'loan-comparison'),
		'termslider'		=> __('Term:', 'loan-comparison'),
		'numbertoshow'		=> 10,
		'numbertoshowlabel'	=> __('Show more results', 'loan-comparison'),
		'daylabel'			=> __('Day', 'loan-comparison'),
		'monthlabel'		=> __('Month', 'loan-comparison'),
		'yearlabel'			=> __('Year', 'loan-comparison'),
		'dayslabel'			=> __('Days', 'loan-comparison'),
		'monthslabel'		=> __('Months', 'loan-comparison'),
		'yearslabel'		=> __('Years', 'loan-comparison'),
		'showoffers'		=> '',
		'offers'			=> __('We have found [number] matches for you', 'loan-comparison'),
		'one-offer'			=> __('We have found one match for you', 'loan-comparison'),
		'no-offers'			=> __('There are no matches. Search again', 'loan-comparison'),
		'addfees'			=> '',
		'whenfees'			=> 'beforeinterest',
		'termseparator'		=> '-',
		'showrating'		=> '',
		'maxrating'			=> 5,
		'halfstars'			=> '',
		'showsorting'		=> false,
		'columnsorting'		=> false,
		'sortbylabel'		=> __('Sort by:', 'loan-comparison'),
		'sortrating'		=> __('Highest Rating', 'loan-comparison'),
		'sortinterest'		=> __('Lowest Interest Rate', 'loan-comparison'),
		'sortloanamount'	=> __('Smallest Loan Amount', 'loan-comparison'),
		'sortbankname'		=> __('Bank Name', 'loan-comparison'),
		'sortfees'			=> __('Lowest Fees', 'loan-comparison'),
		'sortrepayment'		=> __('Repayments', 'loan-comparison'),
		'sorttotal'			=> __('Total to Pay', 'loan-comparison'),
		'sortterm'			=> __('Terms', 'loan-comparison'),
		'sortby'			=> 'rating,interest,bankname,fees,loanamount,term,repayment,total',
		'columnsort'		=> 'rating,interest,bankname,loanamount,interestamount,loan,fees,repayment,total,term,infolink,loancost,otherinfo,creditscore',
		'showlimits'		=> '',
		'ribbonlabel'		=> 'Hot Deal',
		'newlabel'			=> 'test',
		'sort'				=> '',
		'showribbon'		=> '',
		'ribbonlabel1'		=> 'Best Deal',
		'ribbonlabel2'		=> 'Low Rates',
		'ribbonlabel3'		=> 'Reccomended',
		'ribbonlabel4'		=> 'Ice Cream!',
		'ribbonlabel5'		=> '',
		'ribbonlabel6'		=> '',
		'showbankname'		=> '',
		'showribbonlabel'	=> '',
		'shownumbering'		=> '',
		'roundnumbering'	=> '',
		'showfees'			=> '',
		'showterm'			=> '',
		'nobuttons'			=> '',
		'interest'			=> 'amortisation',
		'zeropercent'		=> '',
		'pagetitle'			=> '',
		'pageslug'			=> '',
		'replacebutton'		=> '',
		'buttonlabel'		=> 'Show Comparisons',
		'pageid'			=> '',
		'borrowinglabel'	=> 'Borrowing <b>[amount]</b> for <b>[term]</b>',
		'nosanitize'		=> '',
		'sort_fees'			=> '',
		'sort_term'			=> '',
		'sortby_rating'		=> '',
		'sortby_loan'		=> '',
		'sortby_fees'		=> '',
		'sortby_term'		=> '',
		'sortby_infolink'	=> '',
		'sortby_loancost'	=> '',
		'sortby_otherinfo'	=> '',
		'sortby_creditscore'=> '',
		'sortby_interestamount'	=> '',
		'sortby_bankname'	=> ''
	);

	$settings = loancomparison_splice($settings,$default);
	
	/*
	if (!$settings['sortby']) {
		$settings['sortby']			= 'rating,interest,bankname,fees,loanamount,term,repayment,total';
		$settings['sort_rating']	= $settings['sort_interest'] = $settings['sort_loanamount'] = $settings['sort_bankname'] = $settings['sort_repayment'] = $settings['sort_total'] = 'checked';
		$settings['sort_fees']		= $settings['sort_term'] = '';
	}

	if (!$settings['columnsort']) {
		$settings['columnsort'] = 'rating,interest,bankname,loanamount,interestamount,loan,fees,repayment,total,term,infolink,loancost,otherinfo,creditscore';
		$settings['sortby_interest'] = $settings['sortby_loanamount'] = $settings['sortby_repayment'] = $settings['sortby_total'] = 'checked';
		$settings['sortby_rating'] = $settings['sortby_loan']		= $settings['sortby_fees'] = $settings['sortby_term'] = '';
		$settings['sortby_infolink'] = $settings['sortby_loancost']	= $settings['sortby_otherinfo']	= $settings['sortby_creditscore'] = $settings['sortby_interestamount'] = $settings['sortby_bankname'] = '';
	};
	*/

	//if($settings['showfees']) {$settings['sortby_fees'] = 'checked';$settings['sortby_loanamount'] = false;}
	//if($settings['showterm']) {$settings['sortby_term'] = 'checked';$settings['sortby_repayment'] = false;}

	return $settings;
}

function loancomparison_get_stored_interest ($table) {

	$interest = get_option(LC_INTEREST.'_new'.$table);
	
	if($interest) {
		update_option(LC_INTEREST.$table,$interest);
		//delete_option(LC_INTEREST.'_new');
	}
	
	if ($table != '1') {
		$interest = get_option(LC_INTEREST.$table);
		if(!is_array($interest)) $interest = loancomparison_get_stored_interest('1');
	} else {
		$interest = get_option(LC_INTEREST.'1');
	}

	$default = array(
		array(
			'logo'			=> plugins_url( 'images/lendo.jpg', __FILE__ ),
			'alt'			=> 'Lendo',
			'link'			=> 'https://google.com',
			'logolink'		=> '',
			'infolink'		=> '',
			'rating'		=> '5',
			'mininterest'	=> '1.1',
			'maxinterest'	=> '5.9',
			'min_loan'		=> '100', 
			'max_loan'		=> '5000',
			'max_term'		=> '19',
			'min_term'		=> '1',
			'filter1'		=> '',
			'filter2'		=> 'checked',
			'filter3'		=> 'checked',
			'filter4'		=> 'checked',
			'filter5'		=> '',
			'filter6'		=> '',
			'check1'		=> '',
			'check2'		=> '',
			'check3'		=> '',
			'check4'		=> '',
			'check5'		=> '',
			'check6'		=> '',
			'ribbon1'		=> 'checked',
			'ribbon2'		=> '',
			'ribbon3'		=> '',
			'ribbon4'		=> '',
			'ribbon5'		=> '',
			'ribbon6'		=> '',
			'example'		=> 'Representative example: assumed borrowing of £10,000 over 60 months at a fixed rate of 2.8% per annum and fees of £0 would result in a representative rate of 2.8% APR (fixed), monthly repayments of £178.62, total amount repayable is £10,717.20.',
			'info1'			=> '8 out 10 people prefer kittens',
			'info2'			=> 'I like to ride my bicycle',
			'info3'			=> 'Strange lights in the sky',
			'info4'			=> 'What\'s on TV tonight?',
			'hide'			=> '',
			'startupfee'=> '100',
			'percentfee'=> '3',
			'otherinfo'		=> '',
			'sponsored'		=> '',
			'blocklink'		=> '',
			'bankfilter'	=> '',
			'infolinkanchor'=> '',
			'minimum_fico'=> 600
		),
		array(
			'logo'			=> plugins_url( 'images/ya-bank.gif', __FILE__ ), 
			'alt'			=> 'Ya Bank',
			'link'			=> 'https://google.com',
			'logolink'		=> '',
			'infolink'		=> '',
			'rating'		=> '4',
			'mininterest'	=> '1.3',
			'maxinterest'	=> '6.7',
			'min_loan'		=> '100', 
			'max_loan'		=> '5000',
			'max_term'		=> '20',
			'min_term'		=> '3',
			'filter1'		=> 'checked',
			'filter2'		=> '',
			'filter3'		=> 'checked',
			'filter4'		=> '',
			'filter5'		=> '',
			'filter6'		=> '',
			'check1'		=> '',
			'check2'		=> 'checked',
			'check3'		=> '',
			'check4'		=> 'checked',
			'check5'		=> '',
			'check6'		=> '',
			'ribbon1'		=> '',
			'ribbon2'		=> '',
			'ribbon3'		=> 'checked',
			'ribbon4'		=> '',
			'ribbon5'		=> '',
			'ribbon6'		=> '',
			'example'		=> 'Representative example: assumed borrowing of £10,000 over 60 months at a fixed rate of 2.8% per annum and fees of £0 would result in a representative rate of 2.8% APR (fixed), monthly repayments of £178.62, total amount repayable is £10,717.20.',
			'info1'			=> 'Have you washed the dishes? [infolink]',
			'info2'			=> 'A long cool beer please',
			'info3'			=> 'Time to take the dog for a walk',
			'info4'			=> 'Don\t forget your coat',
			'hide'			=> '',
			'startupfee'=> '90',
			'percentfee'=> '',
			'otherinfo'		=> 'Free Gifts',
			'sponsored'		=> '',
			'blocklink'		=> '',
			'bankfilter'		=> '',
			'minimum_fico'=> 700
		),
		array(
			'logo'			=> plugins_url( 'images/norwegian.gif', __FILE__ ),
			'alt'			=> 'Norwegian',
			'link'			=> 'https://google.com',
			'logolink'		=> '',
			'infolink'		=> '',
			'rating'	=> '1',
			'mininterest'=> '2.8',
			'maxinterest'=> '9.5',
			'min_loan'		=> '2000', 
			'max_loan'		=> '20000',
			'max_term'		=> '31',
			'min_term'		=> '2',
			'filter1'		=> 'checked',
			'filter2'		=> '',
			'filter3'		=> '',
			'filter4'		=> '',
			'filter5'		=> '',
			'filter6'		=> '',
			'check1'	=> 'checked',
			'check2'	=> 'checked',
			'check3'	=> 'checked',
			'check4'	=> 'checked',
			'check5'	=> '',
			'check6'	=> '',
			'ribbon1'		=> '',
			'ribbon2'		=> '',
			'ribbon3'		=> 'checked',
			'ribbon4'		=> '',
			'ribbon5'		=> '',
			'ribbon6'		=> '',
			'example'		=> 'Representative example: assumed borrowing of £10,000 over 60 months at a fixed rate of 2.8% per annum and fees of £0 would result in a representative rate of 2.8% APR (fixed), monthly repayments of £178.62, total amount repayable is £10,717.20.',
			'info1'			=> 'A good year for the roses',
			'info2'			=> 'I do like a good rummage',
			'info3'			=> 'Sail down the mighty Amazon river',
			'info4'			=> 'How about a round of golf',
			'hide'			=> '',
			'startupfee'=> '80',
			'percentfee'=> '',
			'otherinfo'		=> 'Toys!',
			'sponsored'		=> '',
			'blocklink'		=> '',
			'bankfilter'		=> '',
			'minimum_fico'=> 480
		),
		array(
			'logo'			=> plugins_url( 'images/opp-finans.gif', __FILE__ ),
			'alt'			=> 'Opp Finans',
			'link'			=> 'https://google.com',
			'logolink'		=> '',
			'infolink'		=> '',
			'rating'	=> '2',
			'mininterest'=> '3.5',
			'maxinterest'=> '',
			'min_loan'		=> '800', 
			'max_loan'		=> '50000',
			'max_term'		=> '30',
			'min_term'		=> '5',
			'filter1'		=> 'checked',
			'filter2'		=> 'checked',
			'filter3'		=> 'checked',
			'filter4'		=> '',
			'filter5'		=> '',
			'filter6'		=> '',
			'check1'	=> 'checked',
			'check2'	=> '',
			'check3'	=> 'checked',
			'check4'	=> '',
			'check5'	=> '',
			'check6'	=> '',
			'ribbon1'		=> '',
			'ribbon2'		=> '',
			'ribbon3'		=> '',
			'ribbon4'		=> 'checked',
			'ribbon5'		=> '',
			'ribbon6'		=> '',
			'example'		=> 'Representative example: assumed borrowing of £10,000 over 60 months at a fixed rate of 2.8% per annum and fees of £0 would result in a representative rate of 2.8% APR (fixed), monthly repayments of £178.62, total amount repayable is £10,717.20.',
			'info1'			=> 'It\'s been a hard day\'s night',
			'info2'			=> 'Sitting on the dock of the bay',
			'info3'			=> 'A funny thing happened on the way to work',
			'info4'			=> 'You can never have too many cushions',
			'hide'			=> '',
			'startupfee'=> '95',
			'percentfee'=> '',
			'otherinfo'		=> 'Kittens',
			'sponsored'		=> '',
			'blocklink'		=> '',
			'bankfilter'		=> '',
			'minimum_fico'=> 300
		),
		array(
			'logo'			=> plugins_url( 'images/santander.gif', __FILE__ ),
			'alt'			=> 'Santander',
			'link'			=> 'https://google.com',
			'logolink'		=> '',
			'infolink'		=> '',
			'rating'	=> '4',
			'mininterest'=> '2.76',
			'maxinterest'=> '3.15',
			'min_loan'		=> '1000', 
			'max_loan'		=> '30000',
			'max_term'		=> '24',
			'min_term'		=> '1',
			'filter1'		=> '',
			'filter2'		=> '',
			'filter3'		=> 'checked',
			'filter4'		=> '',
			'filter5'		=> '',
			'filter6'		=> '',
			'check1'	=> 'checked',
			'check2'	=> 'checked',
			'check3'	=> '',
			'check4'	=> '',
			'check5'	=> '',
			'check6'	=> '',
			'ribbon1'		=> '',
			'ribbon2'		=> '',
			'ribbon3'		=> '',
			'ribbon4'		=> 'checked',
			'ribbon5'		=> '',
			'ribbon6'		=> '',
			 'example'		=> 'Representative example: assumed borrowing of £10,000 over 60 months at a fixed rate of 2.8% per annum and fees of £0 would result in a representative rate of 2.8% APR (fixed), monthly repayments of £178.62, total amount repayable is £10,717.20.',
			'info1'			=> 'Nothing much to do today',
			'info2'			=> 'The long and winding road',
			'info3'			=> 'An elephant never forgets',
			'info4'			=> 'You never see anyone beating carpets anymore',
			'hide'			=> '',
			'startupfee'=> '0',
			'percentfee'=> '',
			'otherinfo'		=> '',
			'sponsored'		=> '',
			'blocklink'		=> '',
			'bankfilter'		=> '',
			'minimum_fico'=> 480
		),
		array(
			'logo'			=> plugins_url( 'images/komplett.gif', __FILE__ ),
			'alt'			=> 'Komplett',
			'link'			=> 'https://google.com',
			'logolink'		=> '',
			'infolink'		=> '',
			'rating'	=> '7',
			'mininterest'=> '1',
			'maxinterest'=> '5',
			'min_loan'		=> '1000', 
			'max_loan'		=> '40000',
			'max_term'		=> '14',
			'min_term'		=> '2',
			'filter1'		=> 'checked',
			'filter2'		=> '',
			'filter3'		=> 'checked',
			'filter4'		=> '',
			'filter5'		=> '',
			'filter6'		=> '',
			'check1'	=> '',
			'check2'	=> '',
			'check3'	=> 'checked',
			'check4'	=> 'checked',
			'check5'	=> '',
			'check6'	=> '',
				'ribbon1'		=> '',
			'ribbon2'		=> '',
			'ribbon3'		=> 'checked',
			'ribbon4'		=> '',
			'ribbon5'		=> '',
			'ribbon6'		=> '',
			'example'		=> 'Representative example: assumed borrowing of £10,000 over 60 months at a fixed rate of 2.8% per annum and fees of £0 would result in a representative rate of 2.8% APR (fixed), monthly repayments of £178.62, total amount repayable is £10,717.20.',
			'info1'			=> 'That looks like a fun thing to do',
			'info2'			=> 'Take your hands out your pockets',
			'info3'			=> 'Everyone likes kittens. They are so cute',
			'info4'			=> 'The show muct go on',
			'hide'			=> '',
			'startupfee'=> '56',
			'percentfee'=> '',
			'otherinfo'		=> '',
			'sponsored'		=> '',
			'blocklink'		=> '',
			'bankfilter'		=> '',
			'minimum_fico'=> 500
		),
		 array(
			'logo'			=> plugins_url( 'images/instabank.jpg', __FILE__ ),
			'alt'			=> 'Instabank',
			'link'			=> 'https://google.com',
			'logolink'		=> '',
			'infolink'		=> '',
			'rating'	=> '6',
			'mininterest'=> '4.2',
			'maxinterest'=> '7',
			'min_loan'		=> '500', 
			'max_loan'		=> '50000',
			'max_term'		=> '30',
			'min_term'		=> '7',
			'filter1'		=> 'checked',
			'filter2'		=> 'checked',
			'filter3'		=> '',
			'filter4'		=> '',
			'filter5'		=> '',
			'filter6'		=> '',
			'check1'	=> 'checked',
			'check2'	=> 'checked',
			'check3'	=> 'checked',
			'check4'	=> '',
			'check5'	=> '',
			'check6'	=> '',
			'ribbon1'		=> '',
			'ribbon2'		=> 'checked',
			'ribbon3'		=> '',
			'ribbon4'		=> '',
			'ribbon5'		=> '',
			'ribbon6'		=> '',
			'example'		=> 'Representative example: assumed borrowing of £10,000 over 60 months at a fixed rate of 2.8% per annum and fees of £0 would result in a representative rate of 2.8% APR (fixed), monthly repayments of £178.62, total amount repayable is £10,717.20.',
			'info1'			=> 'Music to hear, why hear\'st thou music sadly?',
			'info2'			=> 'Sweets with sweets war not, joy delights in joy',
			'info3'			=> 'Why lovest thou that which thou receivest not gladly',
			'info4'			=> 'Or else receivest with pleasure thine annoy?',
			'hide'			=> '',
			'startupfee'=> '445',
			'percentfee'=> '',
			'otherinfo'		=> '',
			'sponsored'		=> '',
			'bankfilter'=> '',
			'minimum_fico'=> 650
		),
	);
	
	if (is_array($interest)) {
		return $interest;
	} else {
		return $default;
	}
}

function loancomparison_get_stored_upgrade() {
	
	$upgrade = get_option(LC_UPGRADE);
	
	if ($upgrade) return;
	
	$upgrade_array = ['one'		=> 1, 'two'		=> 2, 'three'		=> 3, 'four'		=> 4, 'five'		=> 5];
	foreach ($upgrade_array as $k		=> $v) {
		$temp_arr = get_option(LC_SETTINGS.$k);
		update_option(LC_SETTINGS.$v, $temp_arr);
	}
	
	update_option(LC_UPGRADE,'upgrade');
	
	$formnumber = get_option(LC_FORMNUMBER);
	
	if (isset($upgrade_array[$formnumber])) $formnumber = $upgrade_array[$formnumber];
	
	update_option(LC_FORMNUMBER, $formnumber);
}

function loancomparison_get_stored_style() {

	$style = get_option('loancomparison_style');
	if(!is_array($style)) $style = array();
	
	$default = array(
		'nostyles'					=> false,
		'nocustomstyles'		=> false,
		'background'			=> 'white',
		'backgroundhex'				=> '#FFF',
		'padding'					=> 0,
		'slider-label-colour'		=> '#666666',
		'slider-output-colour'		=> '#369e71',
		'buttoncolour'				=> '#369e71',
		'buttonsize'			=> 25,
		'slider-thickness'			=> 14,
		'slider-background'			=> '#e6e6e6',
		'slider-revealed'			=> '#369e71',
		'slider-block'				=> false,
		'handle-size'				=> 20,
		'handle-background'			=> 'white',
		'handle-border'				=> '#aacc5e',
		'handle-corners'		=> 50,
		'handle-thickness'			=> 6,
		'filterlabelhide'			=> 'checked',
		'filterlabelbreakpoint'		=> '965',
		'bank-bankground'			=> '#ffffff',
		'bank-border-colour'	=> '#aacc5e',
		'bank-border-thickness'		=> 1,
		'bank-padding'				=> 20,
		'bank-bottom-margin'	=> 20,
		'bank-label-colour'			=> '#666666',
		'bank-output-colour'	=> '#343848',
		'bank-alternate'		=> '',
		'bank-alternate-background'=> '#f4f4f4',
		'button-label-colour'		=> '#FFFFFF',
		'button-background-colour'=> '#aacc5e',
		'button-background-hover'=> '#3D9BE9',
		'button-nolink'				=> '#666666',
		'button-border-colour'		=> '#369e71',
		'button-border-thickness'=> 3,
		'button-border-radius'		=> 3,
		'more-link'					=> '#369e71',
		'more-headers'				=> '#aacc5e',
		'more-colour'				=> '#343848',
		'ribbon1'					=> '#ff0000',
		'ribbon2'					=> '#ffea03',
		'ribbon3'					=> '#33ccff',
		'ribbon4'					=> '#00ff00',
		'numbering-color'			=> '#FFF',
		'numbering-background'		=> '#369e71',
	);
	
	$style = array_merge($default, $style);

	return $style;
}


function loancomparison_splice($a1,$a2) {
	foreach ($a2 as $a2k		=> $a2v) {
		if (is_array($a2v)) {
			if (!isset($a1[$a2k])) $a1[$a2k] = $a2v;
			else {
				if (is_array($a1[$a2k])) $a1[$a2k] = loancomparison_splice($a1[$a2k],$a2v);
			}
		} else {
			if (!isset($a1[$a2k])) $a1[$a2k] = $a2v;
		}
	}
	return $a1;
}