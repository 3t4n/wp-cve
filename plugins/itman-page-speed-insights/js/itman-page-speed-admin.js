/*
* Draw Google Chart in settings page
*/
function drawChart(chart_data) {
	var data = new google.visualization.DataTable();
		data.addColumn('date', 'Day');
		data.addColumn('number', 'Desktop');
		data.addColumn('number', 'Mobile');
		data.addColumn('number', '(90-100) fast');
		data.addColumn('number', '(50-89) average');
		data.addColumn('number', '(0-49) slow');

		data.addRows(chart_data);
	
	
	var options = {
	title: '',
	colors: ['#80b7ec','#92ed7f'],
	curveType: 'none',
	lineWidth: 2,
	pointSize: 4,
	focusTarget: 'category',
	chartArea: {left: 20, right: 40, width: '100%'},
	legend: { position: 'bottom' },
							vAxes: {
		0: {textPosition: 'none'},
		1: {}
	},
	series: { 
		0: {targetAxisIndex: 1},
		1: {targetAxisIndex: 1},
		2: {lineDashStyle: [10, 4], color: '#178239', pointsVisible: false, visibleInLegend: false, lineWidth: 1},
		3: {lineDashStyle: [10, 4], color: '#e67700', pointsVisible: false, visibleInLegend: false, lineWidth: 1},
		4: {lineDashStyle: [10, 4], color: '#c7221f', pointsVisible: false, visibleInLegend: false, lineWidth: 1}
	},
	hAxis: {
		format: 'd. MMM',
		minorGridlines: {count: 0},
		textStyle: {color: '#666666' ,fontName: '\"Lucida Grande\", \"Lucida Sans Unicode\", Arial, Helvetica, sans-serif', fontSize: 11}
	},						
	vAxis: {
		baselineColor: '#e6e6e6',
		viewWindow: {min: 0, max: 101}, 
		gridlines: {count: 11, color: '#e6e6e6'},
		minorGridlines: {count: 0},
		textStyle: {color: '#666666' ,fontName: '\"Lucida Grande\", \"Lucida Sans Unicode\", Arial, Helvetica, sans-serif', fontSize: 11},
		textPosition: 'out'
		}
	};

	var chart = new google.visualization.LineChart(document.getElementById('page_speed_history_chart'));

if (chart) {
    console.log('Chart element found.');
    chart.draw(data, options);
} else {
    console.log('Chart element not found.');
}


	chart.draw(data, options);
}