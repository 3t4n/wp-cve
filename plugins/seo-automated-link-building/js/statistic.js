jQuery(function ($) {
  // days chart
  var ctx = document.getElementById("daysChart").getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      datasets: [{
        lineTension: 0,
        label: seoAutomatedLinkBuildingStatistic.label,
        data: seoAutomatedLinkBuildingStatistic.data,
        backgroundColor: [
          'rgba(191, 208, 12, 0.4)',
        ],
        borderColor: [
          'rgba(191, 208, 12, 1)',
        ],
        borderWidth: 3,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false,
      },
      scales: {
        xAxes: [{
          type: 'time',
          time: {
            format: 'YYYY-MM-DD',
            tooltipFormat: seoAutomatedLinkBuildingStatistic.tooltipFormat,
          },
          scaleLabel: {
            display: true,
            labelString: seoAutomatedLinkBuildingStatistic.date,
          }
        }],
        yAxes: [{
          ticks: {
            precision: 0,
          },
          scaleLabel: {
            display: true,
            labelString: seoAutomatedLinkBuildingStatistic.label,
          }
        }]
      }
    }
  });
  // best chart
  var ctx = document.getElementById("bestChart").getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: seoAutomatedLinkBuildingStatistic.best.map(i => i.label),
      datasets: [{
        lineTension: 0,
        label: seoAutomatedLinkBuildingStatistic.label,
        data: seoAutomatedLinkBuildingStatistic.best,
        backgroundColor: [
          'rgba(191, 208, 12, 0.4)',
        ],
        borderColor: [
          'rgba(191, 208, 12, 1)',
        ],
        borderWidth: 3,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        display: false,
      },
      scales: {
        xAxes: [{
          scaleLabel: {
            display: true,
            labelString: seoAutomatedLinkBuildingStatistic.title,
          }
        }],
        yAxes: [{
          ticks: {
            beginAtZero: true,
            precision: 0,
          },
          scaleLabel: {
            display: true,
            labelString: seoAutomatedLinkBuildingStatistic.label,
          }
        }]
      }
    }
  });

  $('.tab').hide().first().show();
  $('.tab-link').removeClass('active').first().addClass('active');
  $('.tab-link').each(function(index, elm) {
    $(elm).on('click', function() {
      $('.tab').hide().eq(index).show();
      $('.tab-link').removeClass('active').eq(index).addClass('active');
    });
  });
});
