
/*global jQuery: false, GUI$: false, Cookies: false */
/*jslint vars: true */


/** 
* @preserve Copyright 2020 Pine Grove Software, LLC
* AccurateCalculators.com
* pine-grove.com
* interface.MORTGAGE-WIDGET.v2.gpl.js
*/
(function ($, GUI) {
	'use strict';

	// don't try to initialize the wrong calculator
	if (!document.getElementById('mortgage-plugin')) {
		return;
	}

	var obj = {}, // interface object to base equations
		schedule,
		// gui controls
		priceInput,
		pctInput,
		pvInput,
		numPmtsInput,
		rateInput,
		pmtInput,
		pntsInput,
		propTaxInput,
		insInput,
		pmiInput,
		dwnPmt;

	/**
	* init() -- init or reset GUI's values
	*/
	function initGUI() {

		// Note: default dates are set in calc() method.
		// main window

		priceInput.setValue(priceInput.getUSNumber());
		pctInput.setValue(pctInput.getUSNumber());
		pvInput.setValue(pvInput.getUSNumber());
		numPmtsInput.setValue(numPmtsInput.getUSNumber());
		rateInput.setValue(rateInput.getUSNumber());
		pmtInput.setValue(pmtInput.getUSNumber());
		pntsInput.setValue(pntsInput.getUSNumber());
		propTaxInput.setValue(propTaxInput.getUSNumber());
		insInput.setValue(insInput.getUSNumber());
		pmiInput.setValue(pmiInput.getUSNumber());

		document.getElementById('edPmt-mtg').value = GUI.formatLocalFloat(0.0, GUI.moneyConventions, 2);
		document.getElementById('edDownPmt-mtg').value = GUI.formatLocalFloat(0.0, GUI.moneyConventions, 2);
		document.getElementById('edInterest-mtg').value = GUI.formatLocalFloat(0.0, GUI.moneyConventions, 2);
		document.getElementById('edTotalPI-mtg').value = GUI.formatLocalFloat(0.0, GUI.moneyConventions, 2);

	}


	/**
	* clearGUI() -- reset GUI's values
	*/
	function clearGUI() {

		// main window
		priceInput.setValue(0.0);
		pctInput.setValue(0);
		pvInput.setValue(0.0);
		numPmtsInput.setValue(0);
		rateInput.setValue(0.0);
		pmtInput.setValue(0.0);
		pntsInput.setValue(0.0);
		propTaxInput.setValue(0.0);
		insInput.setValue(0.0);
		pmiInput.setValue(0.0);

		document.getElementById('edPmt-mtg').value = GUI.formatLocalFloat(0.0, GUI.moneyConventions, 2);
		document.getElementById('edDownPmt-mtg').value = GUI.formatLocalFloat(0.0, GUI.moneyConventions, 2);
		document.getElementById('edInterest-mtg').value = GUI.formatLocalFloat(0.0, GUI.moneyConventions, 2);
		document.getElementById('edTotalPI-mtg').value = GUI.formatLocalFloat(0.0, GUI.moneyConventions, 2);

	}


	/**
	* getInputs() -- get user inputs and initialize obj equation interface object
	*/
	function getInputs() {
		var pr, pct, selPmtFreq, selCmpFreq, selPmtMthd, selAmortMthd, nunknowns = 0;

		obj = new GUI.fin_params();

		pr = priceInput.getUSNumber();

		pct = pctInput.getUSNumber() / 100;

		obj.pv = pvInput.getUSNumber();

		// can we calculate the amount of the loan? Validate inputs
		if (pr !== 0 && pct !== 0 && obj.pv !== 0) {
			// Are the inputs valid? They may have already been calculated
			// pr - (pr * (pct / 100)) === loan amount, if the calculated loan amount is more than 0.01 greater than the input's loan amount, then fail.
			if (Math.abs(obj.pv - (pr - (pr * pct))) > 0.01) {
				alert('One of the following: "Price", "Down Payment Percent" or "Loan Amount" must be "0".\n\nYou may use our general purpose loan calculator if you don\'t want to consider purchase price.');
				return false;
			}
		}


		// are there too many unknowns?
		if (pr === 0) {
			nunknowns += 1;
		}
		if (pct === 0) {
			nunknowns += 1;
		}
		if (obj.pv === 0) {
			nunknowns += 1;
		}
		if (nunknowns > 1) {
			alert('Only one of the following: "Price", "Down Payment Percent" or "Loan Amount" can be "0".\n\nYou may use our general purpose loan calculator if you don\'t want to consider purchase price.');
			return false;
		}

		if (obj.pv === 0) {
			obj.pv = GUI.roundMoney(pr - (pr * pct));
			pvInput.setValue(obj.pv);
		}

		if (pct === 0) {
			pct = 1 - (obj.pv / pr);
			pctInput.setValue(Math.round(pct * 100));
		}

		if (pr === 0) {
			pr = GUI.roundMoney(obj.pv / (1 - pct));
			priceInput.setValue(pr);
		}

		dwnPmt = pr - obj.pv;

		obj.n = numPmtsInput.getUSNumber();

		obj.nominalRate = rateInput.getUSNumber() / 100;

		obj.cf = 0;

		// cash flow's payment frequency
		obj.pmtFreq = GUI.pmt_frequency;

		// cash flow's compound frequency
		obj.cmpFreq = GUI.pmt_frequency;

		selPmtMthd = document.getElementById('selPmtMthd-mtg');
		obj.pmtMthd = parseInt(selPmtMthd.value, 10);
		obj.amortMthd = 0; // normal

		obj.pnts = pntsInput.getUSNumber() / 100;
		obj.propTax = propTaxInput.getUSNumber();
		obj.ins = insInput.getUSNumber();
		obj.pmi = pmiInput.getUSNumber() / 100;
		obj.price = pr;

		if (obj.pmi > 0.0 && pct >= 0.20) {
			alert('Normally private mortgage insurance is not required if the down payment equals or exceeds 20% of the purchase price.');
		}

		return true;

	} // getInputs()


	/** 
	* calc() -- initialize CashInputs data structures for equation classes
	*/
	function calc() {
		var nUnknowns = 0;

		if (obj.pv === 0) {
			nUnknowns += 1;
		}
		if (obj.n === 0) {
			nUnknowns += 1;
		}
		if (obj.nominalRate === 0) {
			nUnknowns += 1;
		}

		if (nUnknowns > 0) {
			alert('Please enter non-zero values for "Number of Payments" and "Annual Interest Rate".');
			return null;
		}

		if (obj.cf === 0) {
			obj.cf = GUI.roundMoney(GUI.CF.calc(obj));
			if (obj.cf !== Infinity) {
				pmtInput.setValue(-obj.cf);
			} else {
				obj.cf = 0;
			}
		}

		if (obj.cf !== 0) {
			// these are approximate values - could calculate a complete schedule and pickup running totals after rounding
			//schedule = GUI.LOAN_SCHEDULE.calc(obj);
			document.getElementById('edDownPmt-mtg').value = GUI.formatLocalFloat(dwnPmt, GUI.moneyConventions, 2);

			document.getElementById('edInterest-mtg').value = GUI.formatLocalFloat((obj.n * Math.abs(obj.cf)) - obj.pv, GUI.moneyConventions, 2);

			document.getElementById('edTotalPI-mtg').value = GUI.formatLocalFloat(obj.n * Math.abs(obj.cf), GUI.moneyConventions, 2);
		}

	} // function calc()


	$(document).ready(function () {

		// If user has a ccy_format cookie, use its value first
		// otherwise if website set default currency use it
		// otherwise use currency determined by user's locale - previously initialized
		var currency = parseInt(document.getElementById('currency-mtg').value, 10);
		if (Cookies.get('ccy_format')) {
			GUI.updateNumConventions(parseInt(Cookies.get('ccy_format'), 10));
			// GUI.moneyConventions = new GUI.LocalConventions(parseInt(Cookies.get('ccy_format'), 10));
			// // clones moneyConventions and sets ccy_r = '%'
			// GUI.rateConventions = GUI.moneyConventions.rateConvention(); // clones currency conventions with'%' symbol
			// GUI.numConventions = GUI.moneyConventions.numConvention(); // clones currency conventions without currency
		} else if (currency !== undefined && currency !== null && typeof currency === 'number' && currency !== 999) {
			GUI.updateNumConventions(currency);
		}

		// If user has a date_format cookie, use its value first
		// otherwise if website set default date_mask use it
		// otherwise use date_mask determined by user's locale - previously initialized
		var date_mask = parseInt(document.getElementById('date_mask-mtg').value, 10);
		if (Cookies.get('date_format')) {
			GUI.dateConventions = new GUI.LocalDateConventions(parseInt(Cookies.get('date_format'), 10));
		} else if (date_mask !== undefined && date_mask !== null && typeof date_mask === 'number' && date_mask !== 999) {
			GUI.updateDateConventions(date_mask);
		}

		//
		// initialize GUI controls & dialog / modal controls here
		// attach
		//

		// only required on the AccurateCalculators.com site
		// here for each comment reply link of WordPress
		// $('.comment-reply-link').addClass('btn btn-primary');

		// here for the submit button of the comment reply form
		// $('#submit').addClass('btn btn-primary');

		// Style contact form submit button
		// $('.wpcf7-submit').addClass('btn btn-primary');

		// Add thumbnail styling
		// $('.wp-caption').addClass('thumbnail');

		// Now we'll add some classes for the WordPress default widgets - let's go
		// Add Bootstrap style for drop-downs
		// $('.postform').addClass('form-control');
		// end: only required on the AccurateCalculators.com site

		// main window
		priceInput = new GUI.NE('edPrice-mtg', GUI.moneyConventions, 2);

		pctInput = new GUI.NE('edDwnPmtPct-mtg', GUI.rateConventions, 0);
			
		pvInput = new GUI.NE('edPV-mtg', GUI.moneyConventions, 2);

		numPmtsInput = new GUI.NE('edNumPmts-mtg', GUI.numConventions, 0);

		rateInput = new GUI.NE('edRate-mtg', GUI.rateConventions, 4);

		pmtInput = new GUI.NE('edPmt-mtg', GUI.moneyConventions, 2);

		pntsInput = new GUI.NE('edPoints-mtg', GUI.rateConventions, 4);

		propTaxInput = new GUI.NE('edPropTaxes-mtg', GUI.moneyConventions, 2);

		insInput = new GUI.NE('edIns-mtg', GUI.moneyConventions, 2);

		pmiInput = new GUI.NE('edPMI-mtg', GUI.rateConventions, 4);

		initGUI();

		$('#btnCalc-mtg').click(function (e) {
			//alert("calculate");
			if (getInputs()) {
				calc();
			}
		});


		$('#btnClear-mtg').click(function (e) {
			//alert("clear");
			clearGUI();  // clear and reset GUI's values
		});


		$('#btnPrint-mtg').click(function (e) {
			getInputs();
			calc();
			GUI.print_calc();
		});


		$('#btnHelp-mtg').click(function (e) {
			//alert("help");
			GUI.show_help('#hText-mortgage');
		});


		$('#btnSchedule-mtg').click(function (e) {
			var schedule;
			GUI.summary.cashFlowType = 0; // loan
			getInputs();
			schedule = calc();
			GUI.showMortgageSchedule(GUI.MORTGAGE_SCHEDULE.calc(obj, schedule));
		});


		$('#btnCharts-mtg').click(function (e) {
			var schedule;
			GUI.summary.cashFlowType = 0; // loan
			getInputs();
			schedule = calc();
			GUI.showLoanCharts(GUI.MORTGAGE_SCHEDULE.calc(obj, schedule));
		});

		$('#CCY-mtg').click(function (e) {
			//alert("settings");
			GUI$.init_CURRENCYDATE_Dlg();
		});

	}); // $(document).ready

}(jQuery, GUI$));





