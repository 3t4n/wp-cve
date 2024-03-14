/********************************************************************
 * @plugin     ModernPolls
 * @file       resources/asstes/js/modern-polls.js
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

var pending = false;
var multiple = false;
var answers = [];



function mpp_vote(hash) {
    if (!pending) {
        pending = true;
        answers = [];

        var hash = hash;

        var mpp = '#mpp_' + hash;

        var id = jQuery(mpp).find('#mpp_id').val();
        var nonce = jQuery(mpp).find('#mpp_nonce').val();

        var singlePoll = mpp + ' .mpp-radio_input';
        var multiplePoll = mpp + ' .mpp-checkbox_input';


        if (jQuery(multiplePoll).length > 0) {
            multiple = true;
            jQuery(multiplePoll).each(function (i) {
                if (jQuery(this).is(':checked')) {
                    answers.push(parseInt(jQuery(this).val()));
                }
            });
        } else {
            jQuery(singlePoll).each(function (i) {
                if (jQuery(this).is(':checked')) {
                    answers.push(parseInt(jQuery(this).val()));
                }
            });
        }

        jQuery('html,body').animate({scrollTop: jQuery(mpp).offset().top}, 'slow');

        if (answers.length > 0) {
            answers = JSON.stringify(answers);
            jQuery.ajax({
                type: 'POST',
                xhrFields: {withCredentials: true},
                url: modernpollsL10n.ajax_url,
                data: 'action=mppVote&mpp_id=' + id + '&mpp_answers=' + answers + '&mpp_hash=' + hash + '&mpp_nonce=' + nonce,
                cache: false,
                success: function (data) {
                    pending = false;
                    jQuery(mpp).replaceWith(data);
                }
            });
        } else {
            pending = false;
            alert(modernpollsL10n.text_valid);
        }

    } else {
        alert(modernpollsL10n.text_wait);
    }
}

function mpp_result(hash, backLink = false) {

    var hash = hash;

    var mpp = '#mpp_' + hash;

    var id = jQuery(mpp).find('#mpp_id').val();
    var nonce = jQuery(mpp).find('#mpp_nonce').val();

    if (!backLink) {
        jQuery.ajax({
            type: 'POST',
            xhrFields: {withCredentials: true},
            url: modernpollsL10n.ajax_url,
            data: 'action=mppResult&mpp_id=' + id + '&mpp_hash=' + hash + '&mpp_nonce=' + nonce,
            cache: false,
            success: function (data) {
                jQuery(mpp).hide();

                if (jQuery(mpp + '_result').length < 1) {
                    jQuery(mpp).after(data);
                } else {
                    jQuery(mpp + '_result').replaceWith(data);
                    jQuery(mpp + '_result').show();
                }
            }
        });
    } else {
        jQuery(mpp + '_result').remove();
        jQuery(mpp).show();
    }
}

jQuery(document).ready(function () {
    jQuery(document).on('click', '.mpp-default_answer', function () {
        jQuery(this).find('input').attr('checked', true);
    });
});

let mppChartColors = [
    { backgroundColor: 'rgba(255, 99, 132, 0.2)', borderColor: 'rgba(255,99,132,1)'},
    { backgroundColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(54, 162, 235, 1)'},
    { backgroundColor: 'rgba(255, 206, 86, 0.2)', borderColor: 'rgba(255, 206, 86, 1)'},
    { backgroundColor: 'rgba(75, 192, 192, 0.2)', borderColor: 'rgba(75, 192, 192, 1)'},
    { backgroundColor: 'rgba(153, 102, 255, 0.2)', borderColor: 'rgba(153, 102, 255, 1)'},
    { backgroundColor: 'rgba(255, 159, 64, 0.2)', borderColor: 'rgba(255, 159, 64, 1)'},
    { backgroundColor: 'rgba(255,0,11,0.2)', borderColor: 'rgb(255,0,53)'},
    { backgroundColor: 'rgba(12,111,186,0.2)', borderColor: 'rgb(0,111,186)'},
    { backgroundColor: 'rgba(255,191,26,0.2)', borderColor: 'rgb(255,181,0)'},
    { backgroundColor: 'rgba(30,255,255,0.2)', borderColor: 'rgb(0,255,255)'},
    { backgroundColor: 'rgba(101,26,255,0.2)', borderColor: 'rgb(85,0,255)'},
    { backgroundColor: 'rgba(255,142,31,0.2)', borderColor: 'rgb(255,130,0)'},
]

let mppCreatePieChart = (hash, labels, data, options) => {
    let bgColors = []
    let bColors = [];
    mppChartColors.forEach(function(item, index) {
        bgColors.push(item.backgroundColor);
        bColors.push(item.borderColor);
    })

    let ctx = document.getElementById("mpp_" + hash + "_resultPieChart").getContext('2d');
    new Chart(ctx, {
        plugins: [ChartDataLabels],
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                labels: labels,
                data: data,
                backgroundColor: bgColors,
                borderColor: '#fff',
            }]
        },
        options: {
            tooltips: {
                enabled: false
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += parseInt(data);
                        });

                        let percentage = (value * 100 / sum);
                        return (percentage > 0) ? percentage.toFixed(2) + "%" : '';
                    },
                        color: '#fff',
                }
            },
            responsive: true,
            maintainAspectRatio: true,
            legend: {position: 'bottom'},
            title: {display: false}
        }
    });
}

let mppCreateBarChart = (hash, labels, data, options) => {
    let answerNames = labels;
    let answerData = data;
    let dataSets = [];
    answerData.forEach(function(item, index) {
        dataSets.push({
            label: answerNames[index],
            data: [item],
            backgroundColor: [mppChartColors[index].backgroundColor],
            borderColor: [mppChartColors[index].borderColor],
            borderWidth: 1
        });
    })
    let ctx = document.getElementById("mpp_" + hash +"_resultBarChart").getContext('2d');
    new Chart(ctx, {
        plugins: [ChartDataLabels],
        type: 'horizontalBar',
        data: {
            datasets: dataSets
        },
        options: {
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = data;
                        dataArr.map(data => {
                            sum += parseInt(data);
                        });

                        let percentage = (value * 100 / sum);
                        return (percentage > 0) ? percentage.toFixed(2) + "%" : '';
                    },
                    color: '#fff',
                }
            },
            responsive: true,
            legend: {position: 'bottom'},
            title: {display: false},
            scales: {yAxes: [{ticks: {beginAtZero:true}}]}
        }
    });
}