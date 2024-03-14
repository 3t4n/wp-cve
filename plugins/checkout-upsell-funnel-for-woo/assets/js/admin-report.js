jQuery(document).ready(function () {
    'use strict';
    jQuery(".vi-ui.dropdown").dropdown();
    jQuery(document).on('change', '.viwcuf_edit_date_field .start_date', function () {
        let val = jQuery(this).val();
        if (val) {
            jQuery('.viwcuf_edit_date_field .end_date').attr('min', val);
        }
    });
    if (jQuery('#myChart').length) {
        var options = {}, data = {}, check = true;
        data['labels'] = viwcuf_admin_report.chart_labels;
        options = {
            responsive: true,
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                    },
                    ticks: {
                        display: false //this will remove only the label
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: true,
                    },
                    ticks: {
                        beginAtZero: true,
                    },
                }]
            }
        };
        console.log(viwcuf_admin_report)
        switch (viwcuf_admin_report.type) {
            case 'products':
                if (!viwcuf_admin_report.product_id) {
                    jQuery('.viwcuf-chart').hide();
                    return false;
                }
                data['datasets'] = [
                    {
                        label: viwcuf_admin_report.us_label,
                        borderWidth: 2,
                        fill: false,
                        backgroundColor: "rgb(50,192,70)",
                        borderColor: "rgb(50,192,70)",
                        pointBorderColor: "rgb(50,192,70)",
                        pointBackgroundColor: "rgb(50,192,70)",
                        pointBorderWidth: 0,
                        pointHitRadius: 0,
                        hoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgb(50,192,70)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderWidth: 2,
                        data: viwcuf_admin_report.us_data,
                    },
                    {
                        label: viwcuf_admin_report.ob_label,
                        borderWidth: 2,
                        fill: false,
                        backgroundColor: "rgb(192,79,71)",
                        borderColor: "rgb(192,79,71)",
                        pointBorderColor: "rgb(192,79,71)",
                        pointBackgroundColor: "rgb(192,79,71)",
                        pointBorderWidth: 0,
                        pointHitRadius: 0,
                        hoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgb(192,79,71)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderWidth: 2,
                        data: viwcuf_admin_report.ob_data,
                    },
                    {
                        label: viwcuf_admin_report.customer_label,
                        borderWidth: 2,
                        fill: false,
                        backgroundColor: "rgb(23,119,192)",
                        borderColor: "rgb(23,119,192)",
                        pointBorderColor: "rgb(23,119,192)",
                        pointBackgroundColor: "rgb(23,119,192)",
                        pointBorderWidth: 0,
                        pointHitRadius: 0,
                        hoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgb(23,119,192)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderWidth: 2,
                        data: viwcuf_admin_report.customer_data,
                    },
                    {
                        label: viwcuf_admin_report.guest_label,
                        borderWidth: 2,
                        fill: false,
                        backgroundColor: "rgb(42,187,192)",
                        borderColor: "rgb(42,187,192)",
                        pointBorderColor: "rgb(27,173,192)",
                        pointBackgroundColor: "rgb(27,173,192)",
                        pointBorderWidth: 0,
                        pointHitRadius: 0,
                        hoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgb(27,173,192)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderWidth: 2,
                        data: viwcuf_admin_report.guest_data,
                    },
                ];
                options['scales']['yAxes'] = [{
                    gridLines: {
                        display: true,
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    },
                }];
                break;
            default:
                data['datasets'] = [
                    {
                        label: viwcuf_admin_report.order_label,
                        borderWidth: 1,
                        fill: false,
                        backgroundColor: "rgb(42,187,192)",
                        borderColor: "rgb(42,187,192)",
                        pointBorderColor: "rgb(27,173,192)",
                        pointBackgroundColor: "rgb(27,173,192)",
                        pointBorderWidth: 0,
                        pointHitRadius: 0,
                        hoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgb(27,173,192)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderWidth: 2,
                        data: viwcuf_admin_report.order_data,
                    },
                    {
                        label: viwcuf_admin_report.ob_order_label,
                        borderWidth: 1,
                        fill: false,
                        backgroundColor: "rgb(50,192,70)",
                        borderColor: "rgb(50,192,70)",
                        pointBorderColor: "rgb(50,192,70)",
                        pointBackgroundColor: "rgb(50,192,70)",
                        pointBorderWidth: 0,
                        pointHitRadius: 0,
                        hoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgb(50,192,70)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderWidth: 2,
                        data: viwcuf_admin_report.ob_order_data,
                    },
                    {
                        label: viwcuf_admin_report.us_order_label,
                        borderWidth: 1,
                        fill: false,
                        backgroundColor: "rgb(192,79,71)",
                        borderColor: "rgb(192,79,71)",
                        pointBorderColor: "rgb(192,79,71)",
                        pointBackgroundColor: "rgb(192,79,71)",
                        pointBorderWidth: 0,
                        pointHitRadius: 0,
                        hoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgb(192,79,71)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderWidth: 2,
                        data: viwcuf_admin_report.us_order_data,
                    },
                ];
                options['tooltips'] = {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            let label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += viwcuf_convert(tooltipItem.yLabel);
                            return label;
                        },
                    }
                };
        }
        if (!check) {
            jQuery('.viwcuf-chart').hide();
            return false;
        }
        var myLineChart = new Chart(jQuery('#myChart'), {
            type: 'line',
            data: data,
            options: options,
        });
    }
});

function viwcuf_convert(value) {
    return value.toLocaleString('en-US', {
        style: 'currency',
        currency: viwcuf_admin_report.currency || '',
        minimumFractionDigits: viwcuf_admin_report.decimal,
        maximumFractionDigits: viwcuf_admin_report.decimal
    });
}