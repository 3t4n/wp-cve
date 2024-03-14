/*global alert: false, EQ$: false, jQuery: false, Highcharts: false, Chart: false */
/*jslint vars: true */

/** 
* @preserve Copyright 2016 Pine Grove Software, LLC
* AccurateCalculators.com
*/

/**
* Schedules and Charts
* @const
*/
var SC$ = (function (EQ, $) {
	'use strict';

	var SC = EQ || {};

	// print preview
	var strOpenTag = '<!DOCTYPE html>';
	var strHTMLHead = '<html lang="en"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1">';

	var strHTMLTitle = '<title>Your Personalized Loan Schedule</title>';

	var strStyleScreen = '<style type="text/css" media="screen">';

	strStyleScreen = strStyleScreen + 'html,body{margin:0;padding:0;color:#333;height:100%;width:100%;min-width:320px;font-family:"Source Code Pro",monospace; font-size:8px; font-weight:400; overflow: hidden;} body{overflow-y: scroll} tr {line-height: 1.2} @media (min-width: 569px) {html,body{font-size:12px}} @media (min-width: 768px) {html,body{font-size:14px}} .label {font-family: "Roboto", sans-serif;} .medium {font-weight: 600; font-style: italic} .bold {font-weight: 700} .center {text-align: center} .left {text-align: left} .right {text-align: right} .wrapper {padding:1px; width:100%; height: 100%} table {width: 100%; border-collapse:collapse; margin-bottom: 20px;} #rpt tbody tr.totals, #rpt tbody tr:nth-child(even).totals {background-color: transparent;} #rpt tbody tr:nth-child(even) {background: #FCFFFF;} #rpt tbody tr:hover, #rpt tbody tr:hover.totals {background: #303E64; color: #fff; font-weight:400} #rpt tbody::after {content: ""; display: block; height: 29px;} .cHead {background: #303E64; color: #fff} td {padding: 5px 5px;} .spcr {width: 2%} .hCell {width: 24%} .rpt_title {width: 100%; font-size: 120%} .rpt_footer {width: 100%; font-style: italic; font-size: 90%;}  .btn {display: inline-block; margin-bottom: 0; font-weight: normal; vertical-align: middle; touch-action: manipulation; cursor: pointer; background-image: none; border: 1px solid transparent; white-space: nowrap; padding: 6px 12px; font-size: 100%; line-height: 1.42857143; border-radius: 4px; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; } .btn-primary {color: #fff; background-color: #303e64; border-color: #283353;} .btn-primary:focus, .btn-primary.focus {color: #ffffff; background-color: #1f2942; border-color: #000000; } .btn-primary:hover {color: #ffffff; background-color: #1f2942; border-color: #141a29; } .btn-primary:active, .btn-primary.active, .open > .dropdown-toggle.btn-primary { color: #ffffff; background-color: #1f2942; border-color: #141a29;} .btn-row {padding: 15px 0 5px; width:100%; margin-bottom:20px} td.brder {border-top: 1px solid #303E64} #btnPrint {margin-right:15px} #btnCopy {margin-left:15px} tr.empty {background-color: transparent !important; color:#333 !important;} .i {font-style: italic} .altColor{color:#00c} .rpt6col {width: 19%;} .rpt6colvnarrow {width: 7%;} .rpt6colnarrow {width: 15%;} .rpt6colwide {width: 21%;} .rpt7col {width: 16%;} .rpt7colvnarrow {width: 7%;} .rpt7colnarrow {width: 9%;} .rpt7colwide {width: 20%;} .rpt9col {width: 11%;} .rpt9colvnarrow {width: 6%;} .rpt9colnarrow {width: 11%;} .rpt9colwide {width: 13%;} ';

	strStyleScreen = strStyleScreen + '</style>';

	var strBodyOpen = '<body><div class="wrapper">';

	// closing div is for .wrapper
	var strCloseTags = '</div></body></html>';

	// End for print preview

	var EVENT_DATE = 0, // YYYYMMDD, used for sorting
		LOAN_NO = 1,
		ROW_TYPE = 2,
		PER_STR = 3,
		DATE_STR = 4,
		CF = 5, // cash flow
		CREDIT = 6,
		DEBIT = 7,
		INT = 8,
		PRIN = 9,
		NET = 10, // net change
		BAL = 11,
		MONTH = 12,
		YEAR = 13,
		PROPTAX = 16,
		INS = 17,
		PMIAMT = 18,
		TOTPMT = 19;


	//
	// build string and create HTML schedule
	// use HTML table elements for layout and style those
	//
	var createMortgageScheduleTableMIT = function (schedule) {
		var L, i, strReportPage, strSchedule, rate, pmt, interest, prin, bal, num, freq, strDate, strDateFirst, strDateLast, periodYear, totalInterest, totalPI, transaction, pnts, pntsInMoney, roundingMsg, strAmortMthd, strUnadjustedBal, whichSchedule, propTaxes, ins, escrow, pmiPmt, totPmt;

		strReportPage = new EQ.StringBuffer();
		strSchedule = new EQ.StringBuffer();
		L = schedule.length - 1;
		whichSchedule = EQ.schedule.schedule1;
		if (L !== 0) {
			transaction = schedule[0];
			strDate = transaction[DATE_STR];
			bal = EQ.formatLocalFloat(transaction[BAL], EQ.moneyConventions, 2);
			rate = EQ.formatLocalFloat(EQ.summary.nominalRate[EQ.schedule.schedule1] * 100, EQ.rateConventions, 4);

			// [KT] - 11/11/2016 - array index = 1 may not be first payment. Might be total rows after initial loan event in Dec.
			i = 1;
			do {
				transaction = schedule[i]; // first cash flow
				strDateFirst = transaction[DATE_STR];
				i += 1;
			} while (strDateFirst === null && i < schedule.length);

			pmt = EQ.formatLocalFloat(transaction[CF], EQ.moneyConventions, 2);
			freq = EQ.STR_FREQUENCIES[EQ.summary.pmtFreq];

			transaction = schedule[schedule.length - 1]; // running total details
			totalInterest = EQ.formatLocalFloat(transaction[INT], EQ.moneyConventions, 2);
			totalPI = EQ.formatLocalFloat(transaction[CF], EQ.moneyConventions, 2);
			strDateLast = EQ.summary.lastCreditDateStr[EQ.schedule.schedule1];
			num = EQ.formatLocalFloat(EQ.summary.totalNDebits[EQ.schedule.schedule1], EQ.numConventions, 0);

			propTaxes = EQ.formatLocalFloat(EQ.summary.propTaxes, EQ.moneyConventions, 2);
			ins = EQ.formatLocalFloat(EQ.summary.ins, EQ.moneyConventions, 2);


			// [KT] - 11/26/2016, added points
			pnts = EQ.formatLocalFloat(EQ.summary.pnts * 100, EQ.rateConventions, 4);
			pntsInMoney = EQ.formatLocalFloat(EQ.summary.pntsInMoney, EQ.moneyConventions, 2);

			strUnadjustedBal = EQ.formatLocalFloat(EQ.summary.unadjustedBalance, EQ.moneyConventions, 2);
			if (EQ.summary.unadjustedBalance < 0.0) {
				roundingMsg = 'Last payment amount decreased by ' + strUnadjustedBal + ' due to rounding';
			} else if (EQ.summary.unadjustedBalance > 0.0) {
				roundingMsg = 'Last payment amount increased by ' + strUnadjustedBal + ' due to rounding';
			} else {
				roundingMsg = '';
			}

			strAmortMthd = 'Calculation method:&nbsp;Normal';

			strSchedule.append('<table>');
			strSchedule.append('<thead>');
			strSchedule.append('<tr class="label rpt_title center bold i"><td colspan="6">Mortgage Summary</td></tr>');
			strSchedule.append('<tr class="empty"><td colspan="6"></td></tr>');
			strSchedule.append('</thead>');

			// 6 columns
			strSchedule.append('<tbody>');
			strSchedule.append('<tr><td class="label hCell">Loan Amount:</td><td class="right">' + bal + '</td><td class="spcr">&nbsp;</td><td class="spcr">&nbsp;</td><td class="label hCell">Number of Payments:</td><td class="right">' + num + '</td></tr>');
			strSchedule.append('<tr><td class="label hCell">Annual Interest Rate:</td><td class="right">' + rate + '</td><td class="spcr">&nbsp;</td><td class="spcr">&nbsp;</td><td class="label hCell">Periodic Payment:</td><td class="right">' + pmt + '</td></tr>');
			strSchedule.append('<tr><td class="label hCell">Loan Date:</td><td class="right">' + strDate + '</td><td class="spcr">&nbsp;</td><td class="spcr">&nbsp;</td><td class="label hCell">1st Payment Due:</td><td class="right">' + strDateFirst + '</td></tr>');
			strSchedule.append('<tr><td class="label hCell">Periodic Property Taxes:</td><td class="right">' + propTaxes + '</td><td class="spcr">&nbsp;</td><td class="spcr">&nbsp;</td><td class="label hCell">Periodic Insurance:</td><td class="right">' + ins + '</td></tr>');
			// [KT] - 11/26/2016, added points
			if (EQ.summary.pnts !== 0) {
				strSchedule.append('<tr><td class="label hCell">Points:</td><td class="right">' + pnts + '</td><td class="spcr">&nbsp;</td><td class="spcr">&nbsp;</td><td class="label hCell">Charges Due to Points:</td><td class="right">' + pntsInMoney + '</td></tr>');
			}
			strSchedule.append('<tr><td class="label hCell">Payment Frequency:</td><td class="right">' + freq + '</td><td class="spcr">&nbsp;</td><td class="spcr">&nbsp;</td><td class="label hCell">Last Payment Due:</td><td class="right">' + strDateLast + '</td></tr>');
			strSchedule.append('<tr><td class="label hCell">Total Interest Due:</td><td class="right">' + totalInterest + '</td><td class="spcr">&nbsp;</td><td class="spcr">&nbsp;</td><td class="label hCell">Total All Payments:</td><td class="right">' + totalPI + '</td></tr>');
			strSchedule.append('</tbody>');
			strSchedule.append('</table>');

			strSchedule.append('<table id="rpt">');
			strSchedule.append('<thead>');
			strSchedule.append('<tr class="label rpt_title center bold i"><td colspan="9">Mortgage Payment Schedule</td></tr>');
			strSchedule.append('<tr class="empty"><td colspan="9"></td></tr>');
			strSchedule.append('<tr class="label cHead"><td class="rpt9colvnarrow">#/Year</td><td class="rpt9colnarrow center">Date</td><td class="rpt9col right">Total<br>Payment</td><td class="rpt9col right">Escrow<br>Amount</td><td class="rpt9col right">PMI<br>Amount</td><td class="rpt9col right">P&nbsp;&amp;&nbsp;I<br>Amount</td><td class="rpt9colwide right">Interest</td><td class="rpt9colwide right">Principal</td><td class="rpt9colwide right">Balance</td></tr>');
			strSchedule.append('</thead>');

			strSchedule.append('<tfoot>');

			strSchedule.append('<tr class="label rpt_footer left"><td colspan="4">' + strAmortMthd + '</td><td colspan="5" class="right" style="vertical-align: text-top">' + roundingMsg + '</td></tr>');
			strSchedule.append('<tr class="label rpt_footer left"><td colspan="4">AccurateCalculators.com</td><td colspan="5" class="right" style="vertical-align: text-top">Escrow = property taxes + insurance</td></tr>');
			strSchedule.append('</tfoot>');

			strSchedule.append('<tbody>');

			// don't skip header row
			for (i = 0; i <= L; i += 1) {
				transaction = schedule[i];
				if (i > 0) {
					periodYear = transaction[PER_STR];
				} else {
					periodYear = 'Loan:';
				}

				strDate = transaction[DATE_STR];
				pmt = EQ.formatLocalFloat(transaction[CF], EQ.numConventions, 2);
				interest = EQ.formatLocalFloat(transaction[INT], EQ.numConventions, 2);
				prin = EQ.formatLocalFloat(transaction[PRIN], EQ.numConventions, 2);
				bal = EQ.formatLocalFloat(transaction[BAL], EQ.numConventions, 2);
				escrow = EQ.formatLocalFloat(EQ.roundMoney(transaction[PROPTAX] + transaction[INS]), EQ.numConventions, 2);
				pmiPmt = EQ.formatLocalFloat(transaction[PMIAMT], EQ.numConventions, 2);
				totPmt = EQ.formatLocalFloat(transaction[TOTPMT], EQ.numConventions, 2);

				if (transaction[ROW_TYPE] === EQ.ROW_TYPES.DETAIL) {
					strSchedule.append('<tr><td>' + periodYear + '</td><td class="center">' + strDate + '</td><td class="right">' + totPmt + '</td><td class="right">' + escrow + '</td><td class="right">' + pmiPmt + '</td><td class="right">' + pmt + '</td><td class="right">' + interest + '</td><td class="right">' + prin + '</td><td class="right">' + bal + '</td></tr>');


				} else if (transaction[ROW_TYPE] === EQ.ROW_TYPES.ANNUAL_TOTALS) {
					// with line
					strSchedule.append('<tr class="totals medium"><td class="right" colspan="2">' + periodYear + '</td><td class="right brder">' + totPmt + '</td><td class="right brder">' + escrow + '</td><td class="right brder">' + pmiPmt + '</td><td class="right brder">' + pmt + '</td><td class="right brder">' + interest + '</td><td class="right brder">' + prin + '</td><td></td></tr>');

				} else {
					strSchedule.append('<tr class="totals medium"><td class="right" colspan="2">' + periodYear + '</td><td class="right">' + totPmt + '</td><td class="right">' + escrow + '</td><td class="right">' + pmiPmt + '</td><td class="right">' + pmt + '</td><td class="right">' + interest + '</td><td class="right">' + prin + '</td><td></td></tr>');

					// empty row
					strSchedule.append('<tr class="empty"><td colspan="9"></td></tr>');

				}

			} // for

			strSchedule.append('</tbody>');
			strSchedule.append('</table>');

		} // L !== 0


		////////////////////////////////////////////////////////
		// build report
		strReportPage.append(strOpenTag);
		strReportPage.append(strHTMLHead);
		strReportPage.append(strHTMLTitle);
		strReportPage.append(strStyleScreen);
		strReportPage.append(strBodyOpen);
		strReportPage.append(strSchedule.toString());
		strReportPage.append(strCloseTags);


		//		$.featherlight({iframe: 'about:blank', iframeWidth: '98%', iframeHeight: '100%' });
		// this works v1.5.1, using the class of the iframe as a jQuery selector, no modification to Featherlight code
		//		$.featherlight({iframe: 'about:blank'});
		//		var $iframe = $('.featherlight iframe');
		//		$iframe.ready(function () {
		//			$iframe.contents().find("body").append(strReportPage.toString());
		//		});



		// this works, using the iframe's id, no jQuery. Modification to Featherlight code required to add element id.
		// Note: style iframe with this css selector .featherlight iframe {..}
		$.featherlight({iframe: 'about:blank',
			beforeOpen: function () {
				$('body').css({'overflow-y': 'hidden'}); 
			},
			afterClose: function () {
				$('body').css({'overflow-y': 'scroll'}); 
			}});
		var oIframe = document.getElementById('featherlight-id-fc');  // Featherlight's iframe
		var iframeDoc = (oIframe.contentWindow.document || oIframe.contentDocument);

		iframeDoc.open();
		iframeDoc.write(strReportPage.toString());
		iframeDoc.close();



	}; // createMortgageScheduleTableMIT



	////////////////////////////////////////////////////////////////////////
	/////////////////////// CHARTS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////
	var createChartsMIT = {
		// page with 3 charts
		html3: '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Financial Calculators Charts</title><style type="text/css" media="screen">html,body{margin:0;padding:0;color:#333;height:100%;width:100%;overflow: hidden;} body{overflow-y: scroll} #container{width: 95%; margin: 10px auto;} canvas {margin-bottom:25px;}</style></head><body><div id="container"><canvas id="canvas1"></canvas><canvas id="canvas2"></canvas><canvas id="canvas3"></canvas></div></body></html>',
		L: 0,
		annualTotals: null,
		runningTotals: null,
		totalsPie: null,
		years: null,
		chart0Title: '',
		chart1Title: '',
		chart2Title: '',
		chart3Title: '',
		// Bootstrap 3.0 colors
		bs_primary_blue: '#337ab7', // basic blue, primary
		bs_info_blue: '#5bc0de', // light blue, info
		bs_success_green: '#5cb85c', // green, success
		bs_orange_warning: '#f0ad4e', // orange, warning
		bs_red_danger: '#d9534f', // red, danger
		// index (columns) values for 2 dimensional schedule array
		EVENT_DATE: 0, // YYYYMMDD, used for sorting
		LOAN_NO: 1,
		ROW_TYPE: 2,
		PER_STR: 3,
		DATE_STR: 4,
		CF: 5, // cash flow
		CREDIT: 6,
		DEBIT: 7,
		INT: 8,
		PRIN: 9,
		NET: 10, // net change
		BAL: 11,
		MONTH: 12,
		YEAR: 13,
		annual_int: [],  // d1: [],
		annual_prin: [], // d2: [],
		annual_pmt: [], // d3: [],
		running_int: [], // d4: [],
		running_prin: [], // d5: [],
		running_pmt: [], // d6: [],
		bal: [], // d7: [],
		d8: [],
		d9: [],
		d10: [],
		d11: [],
		category: [],
		interest: 0,
		interest2: 0, // for biweekly pie chart
		prin: 0,
		prin2: 0, // for biweekly pie chart
		xpmt: 0, // for extra payment pie chart
		rate: 0,
		payments: 0,
		payments2: 0, // for biweekly pie chart
		balance: 0,
		strDate: '01/01/1999',
		strDate2: '01/01/1999',
		strDate2bi: '01/01/1999', // for biweekly pie chart
		kStr: EQ.moneyConventions.ccy_r === '' ? 'k' : ' k',



		// reset data arrays
		clear: function () {
			createChartsMIT.L = 0;
			createChartsMIT.annual_int = [];  // d1 =  [],
			createChartsMIT.annual_prin = []; // d2 =  [],
			createChartsMIT.annual_pmt = []; // d3 =  [],
			// [KT] 01/08/2021
			createChartsMIT.d8 = [];
			createChartsMIT.d9 = [];
			createChartsMIT.d10 = [];
			createChartsMIT.d11 = [];

			createChartsMIT.running_int = []; // d4 =  [],
			createChartsMIT.running_prin = []; // d5 =  [],
			createChartsMIT.running_pmt = []; // d6 =  [],
			createChartsMIT.bal = []; // d7 =  [],
			createChartsMIT.category = [];
		},


		/////////////////////////////////////////
		initAnnualTotalChart: function (iframeDoc) {

			// stacked bar showing annual totals, line show annual payments
			var barChartData = {
				labels: createChartsMIT.category, // year labels for x-axis
				datasets: [{
					type: 'bar',
					label: 'Principal',
					backgroundColor: 'rgba(92,184,92,0.75)',
					data: createChartsMIT.annual_prin // annual principal totals
				}, {
					type: 'bar',
					label: 'Interest',
					backgroundColor: 'rgba(217,83,79,0.75)',
					data: createChartsMIT.annual_int // annual interest totals
				}, {
					type: 'line',
					label: 'Payment',
					borderWidth: 1, // width in pixels
					borderColor: 'rgba(51,51,51,0.5)', // line color
					pointBackgroundColor: 'rgba(0,0,0,0.75)',
					//fill: false, // these can be set once in the global object
					//lineTension: 0,
					data: createChartsMIT.annual_pmt // annual total payment
				}]
			};


			// get a canvas to draw on
			var ctx = iframeDoc.getElementById('canvas1').getContext('2d');

			// allocate and initialize a chart
			createChartsMIT.annualTotals = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					title: {
						display: true,
						text: createChartsMIT.chart0Title  // "Chart.js Bar Chart - Stacked"
					},
					tooltips: {
						mode: 'label',
						callbacks: {
							label: function (tooltipItems, data) {
								return EQ.formatLocalFloat(tooltipItems.yLabel, EQ.moneyConventions, 0);
							}
						}
					},
					responsive: true,
					scales: {
						xAxes: [{
							stacked: true
						}],
						yAxes: [{
							stacked: true,
							ticks: {
								callback: function (label, index, labels) {
									return EQ.formatLocalFloat(label / 1000, EQ.moneyConventions, 0) + createChartsMIT.kStr;
								}
							}
						}]
					}
				}
			});

		}, // initAnnualTotalChart



		/////////////////////////////////////////
		initAccumulatedTotalChart: function (iframeDoc) {
			// stacked bar showing annual totals, lines show annual payments and balance
			var barChartData = {
				labels: createChartsMIT.category, // years along the x-axis
				datasets: [{
					type: 'bar',
					yAxisID: 'y-axis-0',
					label: 'Principal',
					backgroundColor: 'rgba(92,184,92,0.75)',
					data: createChartsMIT.running_prin
				}, {
					type: 'bar',
					yAxisID: 'y-axis-0',
					label: 'Interest',
					backgroundColor: 'rgba(217,83,79,0.75)',
					data: createChartsMIT.running_int
				}, {
					type: 'line',
					label: 'Payment',
					id: 'y-axis-0',
					borderWidth: 1, // width in pixels
					borderColor: 'rgba(51,51,51,0.5)', // line color
					pointBackgroundColor: 'rgba(0,0,0,0.75)',
					data: createChartsMIT.running_pmt
				}, {
					type: 'line',
					yAxisID: 'y-axis-1',
					label: 'Balance',
					borderWidth: 1, // width in pixels
					borderColor: 'rgba(151,187,205,0.5)', // line color
					pointBackgroundColor: 'rgba(0,0,0,0.75)',
					data: createChartsMIT.bal
				}]

			};

			// get a canvas to draw on
			var ctx = iframeDoc.getElementById('canvas2').getContext('2d');

			// allocate and initialize a chart
			createChartsMIT.runningTotals = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					title: {
						display: true,
						text: createChartsMIT.chart1Title // "Chart.js Bar Chart - Stacked"
					},
					tooltips: {
						mode: 'label',
						callbacks: {
							label: function (tooltipItems, data) {
								return EQ.formatLocalFloat(tooltipItems.yLabel, EQ.moneyConventions, 0);
							}
						}
					},
					responsive: true,
					scales: {
						xAxes: [{
							stacked: true
						}],
						yAxes: [{
							stacked: true,
							position: 'left',
							id: 'y-axis-0',
							ticks: {
								beginAtZero: true,
								suggestedMax: createChartsMIT.running_pmt[createChartsMIT.running_pmt.length - 1],
								callback: function (label, index, labels) {
									return EQ.formatLocalFloat(label / 1000, EQ.moneyConventions, 0) + createChartsMIT.kStr;
								}
							}
						}, {
							display: false,
							stacked: false,
							position: 'right',
							id: 'y-axis-1',
							ticks: {
								beginAtZero: true,
								suggestedMax: createChartsMIT.running_pmt[createChartsMIT.running_pmt.length - 1],
								callback: function (label, index, labels) {
									return EQ.formatLocalFloat(label / 1000, EQ.moneyConventions, 0) + createChartsMIT.kStr;
								}
							}
						}]
					}
				}
			});

		}, // initAccumulatedTotalChart


		/////////////////////////////////////////
		initPIPieChart: function (iframeDoc) {
			var config = {
				type: 'pie',
				data: {
					datasets: [{
						data: [
							createChartsMIT.prin,
							createChartsMIT.interest
						],
						backgroundColor: [
							'rgba(92,184,92,0.75)',
							'rgba(217,83,79,0.75)'
						]
					}],
					labels: [
						'Total Principal',
						'Total Interest'
					]
				},
				options: {
					tooltips: {
						mode: 'label',
						callbacks: {
							label: function (tooltipItems, data) {
								var allData = data.datasets[tooltipItems.datasetIndex].data;
								var tooltipLabel = data.labels[tooltipItems.index];
								var tooltipData = allData[tooltipItems.index];
								var label = [];

								label[0] = data.labels[tooltipItems.index] + ': ' + EQ.formatLocalFloat(tooltipData, EQ.moneyConventions, 0);
								label[1] = 'Pct. of Total Payments: ' + EQ.formatLocalFloat(EQ.roundMoney((tooltipData / createChartsMIT.payments) * 100, 1), EQ.rateConventions, 1);
								return label;
							}
						}
					},
					responsive: true,
					title: {
						display: true,
						text: createChartsMIT.chart2Title //"Chart.js Bar Chart - Stacked"
					}
				}
			};
			// get a canvas to draw on
			var ctx = iframeDoc.getElementById('canvas3').getContext('2d');

			// allocate and initialize a chart
			createChartsMIT.totalsPie = new Chart(ctx, config);

		}, // initPIPieChart


		////////////////////////////////////////////////////
		// initialize data structures needed for conventional loan charts
		createLoanCharts: function (schedule) {
			var L, i, j, transaction, bal;

			// set default to straight lines - no curves
			Chart.defaults.global.elements.line.tension = 0;
			// set default no fill beneath the line
			Chart.defaults.global.elements.line.fill = false;

			// init data structures
			j = 0;
			L = schedule.length - 1;
			// [KT] 11/13/2016 - check array is populated
			if (schedule.length > 0) {
				createChartsMIT.years = schedule[L][YEAR] - schedule[0][YEAR] + 1;

				for (i = 0; i <= L; i += 1) {
					transaction = schedule[i];
					if (transaction[ROW_TYPE] === EQ.ROW_TYPES.ANNUAL_TOTALS) {
						// if annual payments and year has no payment (when loan is originated), do not include.
						createChartsMIT.annual_pmt.push(Math.round(transaction[CF]));
						createChartsMIT.annual_int.push(Math.round(transaction[INT]));
						createChartsMIT.annual_prin.push(Math.round(transaction[PRIN]));
						// if less than or equal to 11 years or divisible by 3, show calendar year label
						if ((createChartsMIT.years <= 11) || (j % 3 === 0) || j === 0) {
							createChartsMIT.category.push(transaction[YEAR]);
						} else {
							createChartsMIT.category.push('');
						}
						j += 1;
					} else if (transaction[ROW_TYPE] === EQ.ROW_TYPES.RUNNING_TOTALS) {
						createChartsMIT.running_pmt.push(Math.round(transaction[CF]));
						createChartsMIT.running_int.push(Math.round(transaction[INT]));
						createChartsMIT.running_prin.push(Math.round(transaction[PRIN]));
						createChartsMIT.bal.push(bal);
					} else {
						// balance from a normal transaction
						bal = Math.round(transaction[BAL]);
					}
				} // for

				// init data for pie chart
				transaction = schedule[L];
				createChartsMIT.interest = transaction[INT];
				createChartsMIT.prin = transaction[PRIN];
				createChartsMIT.payments = transaction[CF];

				transaction = schedule[0];
				createChartsMIT.strDate = transaction[DATE_STR];  // EQ.dateMath.customDateStrFromDate(transaction.dTransDate);
				// last transaction date
				transaction = schedule[L - 2];
				createChartsMIT.strDate2 = transaction[DATE_STR];
				createChartsMIT.rate = EQ.summary.nominalRate[EQ.schedule.schedule1] * 100;
				createChartsMIT.chart0Title = 'Annual Principal and Interest Totals';
				createChartsMIT.chart1Title = 'Accumulated Principal and Interest with Remaining Balance';
				createChartsMIT.chart2Title = 'Total Principal and Interest';
			} // L > 0

			// init lightbox with empty iframe, prevent parent page from scrolling while lightbox is open - inserting iframe ID DID NOT WORK WITH IE 11 (of course), tried afterOpen(), afterContent()
			$.featherlight({iframe: 'about:blank',
				beforeOpen: function () {
					$('body').css({'overflow-y': 'hidden'}); 
				},
				beforeClose: function () {
					createChartsMIT.clear(); createChartsMIT.annualTotals.destroy(); createChartsMIT.annualTotals = null; createChartsMIT.runningTotals.destroy(); createChartsMIT.runningTotals = null; createChartsMIT.totalsPie.destroy(); createChartsMIT.totalsPie = null; 
				},
				afterClose: function () {
					$('body').css({'overflow-y': 'scroll'}); 
				}});

			var oIframe = document.getElementById('featherlight-id-fc');  // Featherlight's iframe
			var iframeDoc = (oIframe.contentWindow.document || oIframe.contentDocument);

			iframeDoc.open();
			iframeDoc.write(createChartsMIT.html3); // create an empty page in the iframe with 3 canvases
			iframeDoc.close();

			// initialize and show chart objects, note: setTimeout() is a Firefox work around
			setTimeout(function () {
				createChartsMIT.initAnnualTotalChart(iframeDoc); 
			}, 500);
			setTimeout(function () {
				createChartsMIT.initAccumulatedTotalChart(iframeDoc); 
			}, 500);
			setTimeout(function () {
				createChartsMIT.initPIPieChart(iframeDoc); 
			}, 500);
			//createChartsMIT.initAnnualTotalChart(iframeDoc);
			//createChartsMIT.initAccumulatedTotalChart(iframeDoc);
			//createChartsMIT.initPIPieChart(iframeDoc);

		}
	};  // createChartsMIT


	SC.showMortgageSchedule = function (schedule) {
		createMortgageScheduleTableMIT(schedule);
	};

	SC.showLoanCharts = function (schedule) {
		createChartsMIT.createLoanCharts(schedule);
	};
	return SC;

}(EQ$, jQuery));
