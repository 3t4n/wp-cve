function yektanetChartTimeFrameChange(timeframe, title) {
    let element_to_set_class = `yektanet_${timeframe}_timeframe`;
    const ajaxUrl = jQuery('input#yektanet_ajax_url').val();
    jQuery.ajax({
        type: 'POST',
        url: ajaxUrl,
        data: {
            'action': 'yektanet_change_timeframe_ajax',
            'timeframe': timeframe,
            'title': title
        },
        success: function (data) {
            data = JSON.parse(data)
            if (data.status === true) {
                jQuery("span").removeClass("yektanet__active__chart__button");
                jQuery(`span#${element_to_set_class}`).addClass("yektanet__active__chart__button");
                Highcharts.chart("yektanet_top_products_chart", {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: title
                    },
                    subtitle: {
                        text: 'Source: <a href="https://yektanet.com" target="_blank">Yektanet</a>'
                    },
                    xAxis: {
                        type: 'category',
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'تعداد بازدید توسط کاربران'
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        pointFormat: '<b>{point.y} بازدید</b>'
                    },
                    series: [{
                        name: 'Population',
                        data: data.data,
                        dataLabels: {
                            enabled: true,
                            rotation: -90,
                            color: 'red',
                            align: 'right',
                            format: '{point.y}',
                            y: 10,
                            style: {
                                fontSize: '20px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    }]
                });
            }
        }
    });
}
