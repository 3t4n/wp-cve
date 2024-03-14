var reports = {
    drawAreaChart: function (data, format, isGa4) {
        var chartData, options, chart, formatter;
        chartData = google.visualization.arrayToDataTable(data);
        if (format) {
            if (isGa4) {
                formatter = new google.visualization.NumberFormat({
                    suffix: 's',
                    fractionDigits: 0
                });

            } else {
                formatter = new google.visualization.NumberFormat({
                    suffix: '%',
                    fractionDigits: 2
                });
            }

            formatter.format(chartData, 1);
        }

        options = {
            legend: {
                position: 'none'
            },
            pointSize: 3,
            colors: [wpmsDashboardAnalytics.colorVariations[0], wpmsDashboardAnalytics.colorVariations[4]],
            chartArea: {
                width: '99%',
                height: '90%'
            },
            vAxis: {
                textPosition: "in",
                minValue: 0
            },
            hAxis: {
                textPosition: 'none'
            }
        };
        chart = new google.visualization.AreaChart(wpmsDrawElement);

        chart.draw(chartData, options);
        jQuery('.wpms-spinner-loading').css('visibility', 'hidden');
    },
    drawTableChart: function (data) {
        var chartData, options, chart;

        chartData = google.visualization.arrayToDataTable(data);
        options = {
            page: 'enable',
            pageSize: 10,
            width: '100%',
            allowHtml: true
        };
        chart = new google.visualization.Table(wpmsDrawElement);

        chart.draw(chartData, options);
        jQuery('.wpms-spinner-loading').css('visibility', 'hidden');
    },
    drawOrgChart: function (data) {
        var chartData, options, chart;

        chartData = google.visualization.arrayToDataTable(data);
        options = {
            allowCollapse: true,
            allowHtml: true,
            height: '100%'
        };
        chart = new google.visualization.OrgChart(wpmsDrawElement);

        chart.draw(chartData, options);
        jQuery('.wpms-spinner-loading').css('visibility', 'hidden');
    },
    throwError: function() {
        // Stop loading
        jQuery('.wpms-spinner-loading').css('visibility', 'hidden');
        // Render error
        jQuery('.wpms-error-response').css('display', 'block');
        wpmsDrawElement.textContent = '';
    },
    render: function (response) {
        // Charts selection
        const data = response['data'];
        const rQuery = response['requestQuery'];
        if (typeof data !== 'undefined' && jQuery.isArray(data)) {
            if (jQuery.inArray(rQuery, ['locations', 'referrers', 'contentpages', 'searches']) > -1) {
                // Draw table chart
                reports.drawTableChart(data);
            } else if (rQuery === 'channelGrouping' || rQuery === 'deviceCategory') {
                // Draw organization charts
                reports.drawOrgChart(data);
            } else {
                // Draw area charts
                if (rQuery === 'visitBounceRate') {
                    let isGa4 = false;
                    if (typeof data[0][1] !== 'undefined' && data[0][1] === 'AVG Engagement Time') {
                        isGa4 = true;
                    }
                    reports.drawAreaChart(data, true, isGa4);
                } else {
                    // No format
                    reports.drawAreaChart(data, false, false);
                }
            }
        }
    },
    init: function (response) {
        // Remove children element before draw
        wpmsDrawElement.textContent = '';
        reports.render(response);

        jQuery(window).on('resize', function () {
            // Refresh
            jQuery('.wpms-spinner-loading').css('visibility', 'visible');
            reports.render(response);
        });
    }
};
var getAnalyticsData = function (saveChange, requestDate, requestQuery) {
    // Dont have project id
    if (!jQuery("#wpms-analytics-charts").length) {
        return;
    }
    let from, to;
    from = '30daysAgo';
    to = 'yesterday';
    if (typeof requestDate !=='undefined' && requestDate !== '') {
        from = requestDate;
        if (from === 'today') {
            to = 'today';
        }
    }
    const postData = {
        action: 'wpms',
        task: 'analytics_widgets_data',
        saveChange: saveChange,
        from: from,
        to: to,
        requestDate: requestDate,
        requestQuery: requestQuery,
        wpms_security: wpmsDashboardAnalytics.wpms_security,
        wpms_nonce: wpmsDashboardAnalytics.wpms_nonce
    }

    // Before send
    jQuery('.wpms-spinner-loading').css('visibility', 'visible');
    jQuery.post(wpmsDashboardAnalytics.ajaxUrl, postData, function (response) {
        // After send, do draw charts
        if (typeof response['data'] !== 'undefined' && !jQuery.isNumeric(response['data']) && jQuery.isArray(response['data'])) {
            jQuery('.wpms-error-response').css('display', 'none');
            reports.init(response);
        } else {
            // Something went wrong, throw error
            reports.throwError();
        }
    });
}

// We should draw Google charts inside drawElement
const wpmsDrawElement = document.getElementById('wpms-analytics-charts');
jQuery(document).ready(function ($) {
    let requestDate, requestQuery;
    // Init get
    requestDate = $('#wpms-request-date').val();
    requestQuery = $('#wpms-request-query').val();
    if (typeof requestDate !== 'undefined' && typeof requestQuery !== 'undefined') {
        setTimeout(function () {
            // Wait for Google Visualization ready
            getAnalyticsData(0, requestDate, requestQuery);
        }, 1000);
    }

    // On change selection
    $(document).on('change', '#wpms-request-date, #wpms-request-query', function () {
        requestDate = $('#wpms-request-date').val();
        requestQuery = $('#wpms-request-query').val();
        if (typeof requestDate !== 'undefined' && typeof requestQuery !== 'undefined') {
            getAnalyticsData(1, requestDate, requestQuery);
        }
    });
});