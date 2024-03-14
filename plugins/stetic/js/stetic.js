google.load("visualization", "1", {packages:["corechart"]});

(function(){

var thejquery = jQuery;
var isError = false;

var fourStats = function(pid, token, apikey, location, current_datetime) {
	
	var pid = pid,
  token = token,
	apikey  = apikey,
	weatherLocation = location,
	browserWidth = 0,
	browserHeight = 0,
	tzOffset = Date.parse(current_datetime)-Date.parse(new Date());
	
	function checkError(results) {
		if(isError) {
			return true;
		}
		if(typeof(results['error']) != 'undefined' && results['error'] != '') {
			isError = true;
			thejquery('#contentstetic').html('<br><h3 style="color: red;">' + (results['error']) + '</h3><p>Please check your project settings.</p>');
			return true;
		}
		return false;
	}
	
	addCommas = function(str) {
		str += '';
		x = str.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	};
	
	getBrowserIconClass = function(str) {
		if(typeof(str) == 'undefined' || str === null || (typeof(str) != 'object' && typeof(str) != 'string')) {
			return '';
		}
		return 'browser-logo browser-' + str.toLowerCase().replace(/\s[0-9\.]+$/, '').replace(/\s/g, "-");
	}
	
	getOsIconClass = function(str) {
		if(typeof(str) == 'undefined' || str === null || (typeof(str) != 'object' && typeof(str) != 'string')) {
			return '';
		}
		if(str.search(/^windows/i) != -1) {
			var os_str = "windows";
		} else if(str.search(/^macintosh/i) != -1 || str.search(/^mac os/i) != -1) {
			var os_str = "macintosh";
		}
		else if(str.search(/^ios/i) != -1) {
			var os_str = "ios";
		}
		else if(str.search(/^linux/i) != -1) {
			var os_str = "linux";
		} 
		else if(str.search(/^android/i) != -1) {
			var os_str = "android";
		} 
		else {
			var os_str = "";
		}
		return 'os-logo os-'  + os_str.toLowerCase().replace(/\s[0-9\.]+$/, '').replace(/\s/g, "-");
	}
	
	parseUri = function(str) {
		var parser = document.createElement('a');
		parser.href = (str.match(/^https?:\/\//i) !== null) ? str : ('http://' + str);
		return parser;
	}
	
	showFSDayPerfGraph = function() {
		var date = new Date();
		date.setTime(Date.parse(new Date())+tzOffset);
		var day = date.getDate();
		var month = date.getMonth()+1;
		var year = date.getFullYear();
    
		thejquery.getJSON('https://www.stetic.com/api/numbers?pid=' + pid + '&token=' + token + '&apikey=' + apikey + '&mon=' + month + '-' + year + '&day=' + day + '&jsoncallback=?', function(data) {
			
			if(checkError(data.results)) {
				return;
			}
			
			chart_data = new Array(['Hour', 'Page views', 'Visits']);
			for(i=0; i< data.results['items'].length; i++) {
				stat = data.results['items'][i];
				chart_data.push([i, parseInt(stat.h), parseInt(stat.v)]);
			}

			var gdata = google.visualization.arrayToDataTable(chart_data);

			var options = {
				title: 'Day Performance',
				hAxis: {title: 'Hour',  titleTextStyle: {color: 'red'}, textStyle: {
	  				color: '#3399CC',
	  				fontSize: 10
	  			}},
				legend: 'none',
				pointSize: 4,
				lineWidth: 3,
				gridlineColor: '#ececec',
				colors:['#86e302', '#4caeff'],
				reverseCategories: false,
				backgroundColor: '#ffffff',
				vAxis: {
					baselineColor: 'transparent',
					textPosition: 'in',
					textStyle: {
						color: '#8F8F8F',
						fontSize: 10
					}
				},
				chartArea: {
					width: "100%",
					height: "100%"
				}
			};

			var chart = new google.visualization.AreaChart(document.getElementById('chart_visitor_div'));
			chart.draw(gdata, options);
			
		});
	}
	
	showFSWPDashboard = function() {
		showFSDayPerfGraph();
		thejquery.getJSON('https://www.stetic.com/api/dashboard?pid=' + pid + '&token=' + token + '&apikey=' + apikey + '&jsoncallback=?', function(data) {
			
			if(checkError(data.results)) {
				return;
			}
			
			items_data = {"Visitors":data.results['visits_today'], 
						  "Page views":data.results['hits_today'], 
						  "Ø Pages/Visit":data.results['hits_per_visit_today'], 
						  "Ø Time on Site (Min.)":data.results['avg_time_today']};

			thejquery.each(items_data, function(name, val) {
				var stats_row = '<div class="fs-number"><span>' + val + '</span>' + name + '</div>';
				thejquery("#fs_dashboard_stats").append(stats_row);
			});
			
		});
	};
	
	showFSDashboard = function() {
		
		thejquery.getJSON('https://www.stetic.com/api/dashboard?pid=' + pid + '&token=' + token + '&apikey=' + apikey + '&jsoncallback=?', function(data) {
			
			if(checkError(data.results)) {
				return;
			}
			
			var header = ["Visits","Returning Visitors","New Visits","Page views","Ø Pages/Visit","Ø Time on Site (Min.)"];
			var items_template = {"Today":"today", "Yesterday":"yesterday", "This month":"thismonth", "Last month":"lastmonth", "This year":"thisyear", "Total":"total"};
			
			var items_data = {};
			
			thejquery.each(items_template, function(key, val) {
				  items_data[key] =	[data.results['visits_' + val], 
									 data.results['ret_visits_' + val],
									 data.results['visits_' + val]-data.results['ret_visits_' + val],
								 	 data.results['hits_' + val], 
								 	 data.results['hits_per_visit_' + val], 
								 	 data.results['avg_time_' + val]];
			});

			thejquery("#fs_overview_stats thead tr").append('<th></th>');
			thejquery.each(header, function(key, val) {
				  thejquery("#fs_overview_stats thead tr").append('<th>' + val + '</th>');
			});
			
			thejquery.each(items_data, function(name, items) {
				var stats_row = '<tr><td class="row-title">' + name + '</td>';
				thejquery.each(items, function(key, val) {
					if(!isNaN(val)) {
						val = addCommas(val);
					}
					stats_row += '<td class="fsnum">' + val + '</td>';
				});
				stats_row += '</tr>';
				thejquery("#fs_overview_stats tbody").append(stats_row);
			});
			

			if(data.results['visits_yth'] < data.results['visits_today']) {
				var tclass = "good";
			} else if(data.results['visits_yth'] > data.results['visits_today']) {
				var tclass = "bad";
			} else {
				var tclass = "noc";
			}
			thejquery("#fs_overview_stats tbody tr").first().children('td').eq(1).prepend('<small class="fs_' + tclass + '">' + data.results['visits_yth_percent'] + '%</small>');

			if(data.results['hits_yth'] < data.results['hits_today']) {
				var tclass = "good";
			} else if(data.results['hits_yth'] > data.results['hits_today']) {
				var tclass = "bad";
			} else {
				var tclass = "noc";
			}
			thejquery("#fs_overview_stats tbody tr").first().children('td').eq(4).prepend('<small class="fs_' + tclass + '">' + data.results['hits_yth_percent'] + '%</small>');

			thejquery("#fs_overview_stats thead tr").first().children('th').first().html('<span>' + data.results['user_online'] + '</span> Online');
			
		});
	};
	
	showFSGraphs = function() {
		
		showFSDayPerfGraph();
		
		var date = new Date();
		date.setTime(Date.parse(new Date())+tzOffset);
		date.setMonth( date.getMonth() - 1 );
		var fromday = date.getDate();
		var frommonth = date.getMonth()+1;
		var fromyear = date.getFullYear();
		var date = new Date();
		date.setTime(Date.parse(new Date())+tzOffset);
		var today = date.getDate();
		var tomonth = date.getMonth()+1;
		var toyear = date.getFullYear();
		
		thejquery.getJSON('https://www.stetic.com/api/numbers?pid=' + pid + '&token=' + token + '&apikey=' + apikey + '&from=' + fromyear + '-' + frommonth + '-' + fromday + '&to=' + toyear + '-' + tomonth + '-' + today + '&jsoncallback=?', function(data) {

			if(checkError(data.results)) {
				return;
			}

			chart_data = new Array(['Hour', 'Page views', 'Visits']);
			for(i=0; i< data.results['items'].length; i++) {
				stat = data.results['items'][i];
				chart_data.push([stat.name, parseInt(stat.h), parseInt(stat.v)]);
			}

			var gdata = google.visualization.arrayToDataTable(chart_data);

			var options = {
				title: 'Last 31 days Performance',
				hAxis: {title: 'Hour',  titleTextStyle: {color: 'red'}, textStyle: {
	  				color: '#3399CC',
	  				fontSize: 10
	  			}},
				legend: 'none',
				pointSize: 4,
				lineWidth: 3,
				gridlineColor: '#ececec',
				colors:['#86e302', '#4caeff'],
				reverseCategories: false,
				backgroundColor: '#ffffff',
				vAxis: {
					baselineColor: 'transparent',
					textPosition: 'in',
					textStyle: {
						color: '#8F8F8F',
						fontSize: 10
					}
				},
				chartArea: {
					width: "100%",
					height: "100%"
				}
			};

			var chart = new google.visualization.AreaChart(document.getElementById('chart_visitor_div_last31'));
			chart.draw(gdata, options);
			
		});
		var date = new Date();
		date.setTime(Date.parse(new Date())+tzOffset);
		var year = date.getFullYear();
		
		thejquery.getJSON('https://www.stetic.com/api/numbers?pid=' + pid + '&token=' + token + '&apikey=' + apikey + '&year=' + year + '&jsoncallback=?', function(data) {
			
			if(checkError(data.results)) {
				return;
			}
			
			chart_data = new Array(['Hour', 'Page views', 'Visits']);
			for(i=0; i< data.results['items'].length; i++) {
				stat = data.results['items'][i];
				chart_data.push([stat.name, parseInt(stat.h), parseInt(stat.v)]);
			}

			var gdata = google.visualization.arrayToDataTable(chart_data);

			var options = {
				title: 'Year Performance',
				hAxis: {title: 'Hour',  titleTextStyle: {color: 'red'}, textStyle: {
	  				color: '#3399CC',
	  				fontSize: 10
	  			}},
				legend: 'none',
				pointSize: 4,
				lineWidth: 3,
				gridlineColor: '#ececec',
				colors:['#86e302', '#4caeff'],
				reverseCategories: false,
				backgroundColor: '#ffffff',
				vAxis: {
					baselineColor: 'transparent',
					textPosition: 'in',
					textStyle: {
						color: '#8F8F8F',
						fontSize: 10
					}
				},
				chartArea: {
					width: "100%",
					height: "100%"
				}
			};

			var chart = new google.visualization.AreaChart(document.getElementById('chart_visitor_div_year'));
			chart.draw(gdata, options);
			
		});
	};

	
	showFSBoxStats = function(name, title, numtitle) {
		
		thejquery.getJSON('https://www.stetic.com/api/' + name + '?pid=' + pid + '&token=' + token + '&apikey=' + apikey + '&jsoncallback=?', function(data) {
			
			if(checkError(data.results)) {
				return;
			}
			
			
			html = '<div><table class="widefat"><thead><tr><th>' + numtitle + '</th><th>' + title + '</th></tr></thead><tbody>';
			if('items' in data.results && data.results['items'].length) {
				for(i=0; i < data.results['items'].length; i++) {
					stat = data.results['items'][i];
					html += '<tr><td class="row-title">' + stat.count + '</td><td>' + stat.name + '</td></tr>';
					if(i >= 9) {
						break;
					}
				}
			} else {
				html += '<tr><td class="row-title"></td><td>No data.</td></tr>';
			}
			html += '</tbody></table></div>';
			thejquery("#fs-box-row").append(html).fadeIn(300);
		});
	};
	
	
	
	showFSVisitorLog = function() {
		
		thejquery.getJSON('https://www.stetic.com/api/visitor_log?pid=' + pid + '&token=' + token + '&apikey=' + apikey + '&jsoncallback=?', function(data) {
			
			if(checkError(data.results)) {
				return;
			}
			
			for(i=0; i < data.results['items'].length; i++) {
				stat = data.results['items'][i];
				var html = '<tr>';
				html += '<td>' + stat.time + '<br>' + stat.hits + ' Page' + ((stat.hits>1)?'s':'') + '</td>';
				html += '<td>' + stat.ip + '(' + stat.host + ')<br>';
				html += '<span class="' + getOsIconClass(stat.os) + '"></span> ' + stat.os + ' <span class="' + getBrowserIconClass(stat.browser) + '"></span> ' + stat.browser + '<br>';
				html += 'Screen: ' + stat.screen + ' - GEO: ' + stat.geo + '</td>';
				html += '<td>';
				if(stat.referrer != '') {
					var ref = parseUri(stat.referrer);
					html += '<a href="http://' + stat.referrer + '" target="_blank">' + ref.hostname + '</a>';
				}
				html += '<br>' + stat.entry + '<br>';
				if(stat.kw != '') {
					html += 'Keyword: ' + stat.kw + '</td>';
				}
				html += '</tr>';
				thejquery("#fs-visitor-log tbody").append(html).fadeIn(300);
				if(i >= 25) {
					break;
				}
			}
		});
	};

	return {
		statsPage: function () {
			showFSGraphs();
			showFSDashboard();	
			showFSBoxStats('sites', 'Site', 'Views');
			window.setTimeout("showFSBoxStats('referrer', 'Referrer', 'Visits')", 1000);
			window.setTimeout("showFSBoxStats('browser', 'Browser', 'Visits')", 1500);
			window.setTimeout("showFSBoxStats('os', 'OS', 'Visits')", 2000);
			window.setTimeout("showFSBoxStats('screen', 'Screen', 'Visits')", 2500);
			window.setTimeout("showFSBoxStats('keywords', 'Keywords', 'Visits')", 500);
			showFSVisitorLog();
		},
		dashBoard: function () {
			showFSWPDashboard();
		},
	};
}

window.fourStats = fourStats;

})();