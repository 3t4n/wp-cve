jQuery(function($) {
	google.charts.load('current', {'packages': ['corechart'], 'language': SeDashboardOptions.chart_language});
	google.charts.setOnLoadCallback(drawChart);

	function createQueryDiv(count, query)
	{
		var queryDisplayText = textTruncate(query, SeDashboardOptions.max_search_text_length);
		var div = $('<div></div>').addClass('se-query-item').append(
			$('<span></span>').addClass('se-query-item-count').text(count + '.')
		).append(
			$('<span></span>').addClass('se-query-item-query').text(queryDisplayText).attr('title', query)
		);

		return div;
	}

	function textTruncate(str, length, ending)
	{
		if (length == null) {
			length = 100;
		}

		if (ending == null || ending == undefined) {
			ending = '...';
		}

		if (str.length > length) {
			return str.substring(0, length - ending.length) + ending;
		} else {
			return str;
		}
	}

	function drawChart()
	{
		if ($('#se-language').length == 0) {
			return;
		}
		
		var lang_code = $('#se-language').val();

		if (!SeDashboardOptions.engines || !SeDashboardOptions.engines.hasOwnProperty(lang_code)) {
			return;
		}

		var params = {
			timezone_offset: new Date().getTimezoneOffset(),
			ajax_custom: 'true',
			private_key: SeDashboardOptions.engines[lang_code].private_key,
			search_queries: {
				limit: SeDashboardOptions.search_queries_limit
			},
			search_queries_no_results: {
				limit: SeDashboardOptions.search_queries_limit
			}
		};
		var enabledValues = $.map($('.se-analytics-select-list input:checkbox:checked'), function(e,i) {
			return e.value;
		});
		$.each(enabledValues, function(i, val) {
			params[val] = 'true';
		});

		// Select date period
		if ($('#se-time-period').length > 0) {
			params['time_period'] = $('#se-time-period').val();
		}

		$('#se-chart').parent().addClass('se-loading');
		$('#se-chart-error').addClass('se-hidden');
		$.getJSON(SeDashboardOptions.host + SeDashboardOptions.url_path, params)
		.done(function(data) {
			var startDate = new Date(data.time_from);
			var endDate = new Date(data.time_to);
			var dataTable = new google.visualization.DataTable();
			var snizeChart = new google.visualization.AreaChart(document.getElementById('se-chart'));
			var rows = [];

			var search_data_enabled = params.hasOwnProperty('search_data');
			var product_clicks_enabled = params.hasOwnProperty('product_clicks');
			var categories_clicks_enabled = params.hasOwnProperty('categories_clicks');
			var suggestions_clicks_enabled = params.hasOwnProperty('suggestions_clicks');
			var max_clicks = 0;

			for (var day = startDate; day <= endDate; day.setDate(day.getDate() + 1)) {
				var date = day.toISOString().substr(0, 10);
				var total_searches = 0;
				var product_clicks = 0;
				var categories_clicks = 0;
				var suggestions_clicks = 0;

				if (search_data_enabled && data.search_data.hasOwnProperty(date)) {
					total_searches = parseInt(data.search_data[date]);
				}

				if (product_clicks_enabled && data.product_clicks.hasOwnProperty(date)) {
					product_clicks = parseInt(data.product_clicks[date]);
				}

				if (categories_clicks_enabled && data.categories_clicks.hasOwnProperty(date)) {
					categories_clicks = parseInt(data.categories_clicks[date]);
				}

				if (suggestions_clicks_enabled && data.suggestions_clicks.hasOwnProperty(date)) {
					suggestions_clicks = parseInt(data.suggestions_clicks[date]);
				}

				var row = [new Date(day.getTime())];

				if (search_data_enabled) {
					max_clicks = Math.max(max_clicks, total_searches);
					row.push(total_searches);
				}

				if (product_clicks_enabled) {
					max_clicks = Math.max(max_clicks, product_clicks);
					row.push(product_clicks);
				}

				if (categories_clicks_enabled) {
					max_clicks = Math.max(max_clicks, categories_clicks);
					row.push(categories_clicks);
				}

				if (suggestions_clicks_enabled) {
					max_clicks = Math.max(max_clicks, suggestions_clicks);
					row.push(suggestions_clicks);
				}

				rows.push(row);
			}

			dataTable.addColumn('date', SeDashboardOptions.txt.date);

			if (search_data_enabled) {
				dataTable.addColumn('number', SeDashboardOptions.txt.total_searches);
			}

			if (product_clicks_enabled) {
				dataTable.addColumn('number', SeDashboardOptions.txt.product_clicks);
			}

			if (categories_clicks_enabled) {            
				dataTable.addColumn('number', SeDashboardOptions.txt.category_clicks);
			}

			if (suggestions_clicks_enabled) {
				dataTable.addColumn('number', SeDashboardOptions.txt.suggestion_clicks);
			}

			dataTable.addRows(rows);

			snizeChart.draw(dataTable, {
				chartArea: {
					width: '90%'
				},
				colors: ['#B3EECD', '#80E2AB', '#00C558', '#00C558'],
				vAxis: {
					format: 'decimal',
					gridlines: {
						count: -1
					}
				},
				hAxis: {
					format: 'MMM d, y',
					gridlines: {
						color: 'transparent'
					},
					minorGridlines: {
						color: 'transparent',
						count: 0
					},
					textStyle: {
						color: '#333'
					}
				},
				vAxis: {
					gridlines: {
						color: '#E2E2E2'
					},
					minorGridlines: {
						count: 0
					},
					textStyle: {
						color: '#333'
					},
					viewWindow: {
						min: 0,
						max: max_clicks < 2 ? 2 : max_clicks
					},
				},
				legend: 'none'
			});

			// Display top queries
			var top_search_container = $('.se-dashboard .se-top-search-queries .se-results-content');
			var top_search_no_result_container = $('.se-dashboard .se-top-search-no-result-queries .se-results-content');

			// Remove previous data if exits
			top_search_container.find('.se-query-item').remove();
			top_search_no_result_container.find('.se-query-item').remove();

			if (data.search_queries && data.search_queries.length > 0) {
				top_search_container.parent().find('.se-no-results').addClass('se-hidden');

				$.each(data.search_queries, function(i, val) {
					top_search_container.append(createQueryDiv(val.count, val.query));
				});
			} else {
				top_search_container.parent().find('.se-no-results.se-hidden').removeClass('se-hidden');
			}

			if (data.search_queries_no_results && data.search_queries_no_results.length > 0) {
				top_search_no_result_container.parent().find('.se-no-results').addClass('se-hidden');

				$.each(data.search_queries_no_results, function(i, val) {
					top_search_no_result_container.append(createQueryDiv(val.count, val.query));
				});
			} else {
				top_search_no_result_container.parent().find('.se-no-results.se-hidden').removeClass('se-hidden');
			}
		}).fail(function() {
			$('#se-chart-error').removeClass('se-hidden');
		}).always(function() {
			$('#se-chart').parent().removeClass('se-loading');
		});
	}

	$('#se-time-period').on('change', function(e) {
		$.cookie('se-dashboard-period', this.value);
		drawChart();
	});

	$('select#se-language').on('change', function(e) {
		$.cookie('se-dashboard-language', this.value);
		drawChart();
	});

	$('.se-analytics-select-list input:checkbox').on('change', function(e) {
		// Do not allow to uncheck all checkboxes
		// Single chart required at last one data
		if ($('.se-analytics-select-list input:checkbox:checked').length > 0) {
			drawChart();
		} else {
			this.checked = true;
		}
		var name = 'se-dashboard-select-' + this.value;
		$.cookie(name, this.checked);
	});
});
