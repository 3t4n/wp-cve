/**
 * -----------------------------------------------------------------------------
 * (c) 2016-2021 Pine Grove Software, LLC -- All rights reserved.
 * Contact: webmaster@financial-calculators.com
 * License: GPL2
 * www.financial-calculators.com
 * -----------------------------------------------------------------------------
 * Common code and global variables.
 * -----------------------------------------------------------------------------
 * compiled at: http://closure-compiler.appspot.com/home
 */

/* required for JSLint processing */
/*global wp: false */
/*jslint nomen: true, vars: true, sub: true */


/** 
 * @preserve Copyright 2016-2022 Pine Grove Software, LLC
 * financial-calculators.com
 * License: GPL2
 * strs.MORTGAGE-WIDGET.gpl.js
 */

// eslint-disable-next-line no-unused-vars
var FC$ = {}; 


/** 
 * @nocollapse
 * @return {Object}
 */
FC$ = (function (FC) {
	'use strict';

	// var FC = {};

	// const {__, _x, _n, sprintf} = wp.i18n;

	FC.strs = {
		// TRANSLATORS: ISO's language code en=English
		s000: wp.i18n.__('en', 'fc-mortgage-calculator'),
		s001: wp.i18n.__('Please use the backspace key to delete.', 'fc-mortgage-calculator'),
		s002: wp.i18n.__('Left, up & down arrow keys are disabled. So are the home, end, pgup and pgdn keys.', 'fc-mortgage-calculator'),
		s003: wp.i18n.__('Use backspace to delete.', 'fc-mortgage-calculator'),
		s004: wp.i18n.__('If value is selected, just start typing new value to clear prior value.', 'fc-mortgage-calculator'),
		s005: wp.i18n.__('When a number is selected (value shown in inverse), use right arrow key to clear selection without clearing value. Then backspace to edit.', 'fc-mortgage-calculator'),
		s006: wp.i18n.__('TIP: Generally it is best to use the TAB or SHIFT-TAB keys to move from one input to the next or previous input.', 'fc-mortgage-calculator'),
		s007: wp.i18n.__('TIP 2: Generally, editing a value is inefficient. Since values are auto selected, just type the number you want.', 'fc-mortgage-calculator'),
		s008: wp.i18n.__('Do not type the thousand separator character.', 'fc-mortgage-calculator'),
		s009: wp.i18n.__('(If using US convention, that would be the comma.)', 'fc-mortgage-calculator'),
		s010: wp.i18n.__('I\'m smart enough to enter them for you!', 'fc-mortgage-calculator'),
		s011: wp.i18n.__('An unknown date calculation error occurred.', 'fc-mortgage-calculator'),
		s012: wp.i18n.__('Please provide us with your inputs and settings so that we can fix this. Thank you.', 'fc-mortgage-calculator'),
		s013: wp.i18n.__('Date is not valid - bad year.', 'fc-mortgage-calculator'),
		s014: wp.i18n.__('Jan', 'fc-mortgage-calculator'),
		s015: wp.i18n.__('Feb', 'fc-mortgage-calculator'),
		s016: wp.i18n.__('Mar', 'fc-mortgage-calculator'),
		s017: wp.i18n.__('Apr', 'fc-mortgage-calculator'),
		s018: wp.i18n.__('May', 'fc-mortgage-calculator'),
		s019: wp.i18n.__('Jun', 'fc-mortgage-calculator'),
		s020: wp.i18n.__('Jul', 'fc-mortgage-calculator'),
		s021: wp.i18n.__('Aug', 'fc-mortgage-calculator'),
		s022: wp.i18n.__('Sept', 'fc-mortgage-calculator'),
		s023: wp.i18n.__('Oct', 'fc-mortgage-calculator'),
		s024: wp.i18n.__('Nov', 'fc-mortgage-calculator'),
		s025: wp.i18n.__('Dec', 'fc-mortgage-calculator'),
		s026: wp.i18n.__('Error: dates out of sequence.', 'fc-mortgage-calculator'),
		s027: wp.i18n.__('Exception', 'fc-mortgage-calculator'),
		s028: wp.i18n.__('occurred when accessing', 'fc-mortgage-calculator'),
		s029: wp.i18n.__('Invalid index', 'fc-mortgage-calculator'),
		// from eq.MORTGAGE-WIDGET.gpl.js
		// s100: wp.i18n.__('Internal limit reached. Balance exceeds +/- 99 trillion.', 'fc-mortgage-calculator'),
		s101: wp.i18n.__('YTD', 'fc-mortgage-calculator'),
		s102: wp.i18n.__('Running Totals', 'fc-mortgage-calculator'),
		s103: wp.i18n.__('Normal', 'fc-mortgage-calculator'),
		// from sc.MORTGAGE-WIDGET.gpl.js
		s201: wp.i18n.__('Your Personalized Mortgage Schedule', 'fc-mortgage-calculator'),
		s202: wp.i18n.__('Last payment amount decreased by', 'fc-mortgage-calculator'),
		s203: wp.i18n.__('due to rounding', 'fc-mortgage-calculator'),
		s204: wp.i18n.__('Last payment amount increased by', 'fc-mortgage-calculator'),
		s205: wp.i18n.__('Mortgage Loan Summary', 'fc-mortgage-calculator'),
		// s206: wp.i18n.__('Car Price', 'fc-mortgage-calculator'),
		// s207: wp.i18n.__('Down Payment', 'fc-mortgage-calculator'),
		s208: wp.i18n.__('Loan Amount', 'fc-mortgage-calculator'),
		s209: wp.i18n.__('Number of Payments', 'fc-mortgage-calculator'),
		s210: wp.i18n.__('Annual Interest Rate', 'fc-mortgage-calculator'),
		s211: wp.i18n.__('Periodic Payment', 'fc-mortgage-calculator'),
		s212: wp.i18n.__('Loan Date', 'fc-mortgage-calculator'),
		s213: wp.i18n.__('1st Payment Due', 'fc-mortgage-calculator'),
		s214: wp.i18n.__('Payment Frequency', 'fc-mortgage-calculator'),
		s215: wp.i18n.__('Last Payment Due', 'fc-mortgage-calculator'),
		s216: wp.i18n.__('Total Interest Due', 'fc-mortgage-calculator'),
		s217: wp.i18n.__('Total All Payments', 'fc-mortgage-calculator'),
		s218: wp.i18n.__('Mortgage Payment Schedule', 'fc-mortgage-calculator'),
		s219: wp.i18n.__('Year', 'fc-mortgage-calculator'),
		s220: wp.i18n.__('Date', 'fc-mortgage-calculator'),
		s221: wp.i18n.__('Payment', 'fc-mortgage-calculator'),
		s222: wp.i18n.__('Interest', 'fc-mortgage-calculator'),
		s223: wp.i18n.__('Principal', 'fc-mortgage-calculator'),
		s224: wp.i18n.__('Balance', 'fc-mortgage-calculator'),
		s225: wp.i18n.__('Calculation method: Normal', 'fc-mortgage-calculator'),
		s226: wp.i18n.__('Total Principal', 'fc-mortgage-calculator'),
		s227: wp.i18n.__('Total Interest', 'fc-mortgage-calculator'),
		s228: wp.i18n.__('Pct. of Total Payments', 'fc-mortgage-calculator'),
		s229: wp.i18n.__('Annual Principal and Interest Totals', 'fc-mortgage-calculator'),
		s230: wp.i18n.__('Accumulated Principal and Interest with Remaining Balance', 'fc-mortgage-calculator'),
		s231: wp.i18n.__('Total Principal and Interest', 'fc-mortgage-calculator'),
		s232: wp.i18n.__('Loan', 'fc-mortgage-calculator'),
		s233: wp.i18n.__('Periodic Property Taxes', 'fc-mortgage-calculator'),
		s234: wp.i18n.__('Periodic Insurance', 'fc-mortgage-calculator'),
		s235: wp.i18n.__('Points', 'fc-mortgage-calculator'),
		s236: wp.i18n.__('Charges Due to Points', 'fc-mortgage-calculator'),
		s237: wp.i18n.__('Total<br>Payment', 'fc-mortgage-calculator'),
		s238: wp.i18n.__('Escrow<br>Amount', 'fc-mortgage-calculator'),
		s239: wp.i18n.__('PMI<br>Amount', 'fc-mortgage-calculator'),
		s240: wp.i18n.__('P & I<br>Amount', 'fc-mortgage-calculator'),
		// interface.SHARED-WIDGET.gpl.js has no strings
		// interface.MORTGAGE-WIDGET.v2.gpl.js
		s401: wp.i18n.__('One of the following: "Price", "Down Payment Percent" or "Loan Amount" must be "0".', 'fc-mortgage-calculator'),
		s402: wp.i18n.__('You may use our general purpose loan calculator if you don\'t want to consider purchase price.', 'fc-mortgage-calculator'),
		s403: wp.i18n.__('Only one of the following: "Price", "Down Payment" or "Loan Amount" can be "0".', 'fc-mortgage-calculator'),
		// s404: wp.i18n.__('You may use our general purpose loan calculator if you don\'t want to consider purchase price.', 'fc-mortgage-calculator'),
		// s405: wp.i18n.__('There are too many unknown values.', 'fc-mortgage-calculator'),
		// s406: wp.i18n.__('Only one value may be "0."', 'fc-mortgage-calculator'),
		s407: wp.i18n.__('Normally private mortgage insurance is not required if the down payment equals or exceeds 20% of the purchase price.', 'fc-mortgage-calculator'),
		s408: wp.i18n.__('Please enter non-zero values for "Number of Payments" and "Annual Interest Rate".', 'fc-mortgage-calculator')

	};


	return FC;
}(FC$));
