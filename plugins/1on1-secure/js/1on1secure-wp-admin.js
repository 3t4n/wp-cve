function tableSortingOneOn1Secure() {
  jQuery(document).ready(function() {
    jQuery('#myTable th').click(function() {
    var table = jQuery(this).closest('table');
    var index = jQuery(this).index();
    var rows = table.find('tbody > tr').toArray().sort(comparatorOneOn1Secure(index));
    this.asc = !this.asc;
    if (!this.asc) {
      rows = rows.reverse();
    }
    for (var i = 0; i < rows.length; i++) {
      table.append(rows[i]);
    }
  });

  function comparatorOneOn1Secure(index) {
    return function(a, b) {
      var valA = jQuery(a).find('td').eq(index).text();
      var valB = jQuery(b).find('td').eq(index).text();
      return jQuery.isNumeric(valA) && jQuery.isNumeric(valB) ?
        valA - valB :
        valA.localeCompare(valB);
    };
  }
  });
}

function popupOneOn1Secure() {
  var checkBox = document.getElementById("dataanalysis");
  var text = document.getElementById("agree");
  var no = document.getElementById("no");

  no.addEventListener('click', function(event){
    document.getElementById("dataanalysis").checked = false;
  })

  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
    text.style.display = "none";
  }
}

function closepopupOneOn1Secure() {
  var popupbox = document.getElementById("agree");
  popupbox.style.display = "none";
}

function popupdropdownOneOn1Secure() {
  var dropdown = document.getElementById("actionforbadips");
  var ErrorPageForBadIps = document.getElementById("errorpageforbadips1on1secure");

  if (dropdown.value == 3){
    ErrorPageForBadIps.disabled = false;
    ErrorPageForBadIps.style.display = "block";
  } else {
    ErrorPageForBadIps.disabled = true;
    ErrorPageForBadIps.style.display = "none";
  }
}

function displayPieChartOneOn1Secure() {
  //get the data from 1on1secure-wp-admin to a JavaScript array

  if (typeof piechartdataarray == 'undefined') {
    return; //exit this function if there is no data
  }

  var piechartvalue = piechartdataarray.piechartvalue;

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawPieChartsOneOn1Secure);

  function drawPieChartsOneOn1Secure() {
    var data = new google.visualization.arrayToDataTable(piechartvalue);

    var options = {
      is3D: true,
      backgroundColor: 'transparent',
      chartArea: {
        height: '80%',
        width: '80%',
      },
      colors: ['#2C2D2F', '#E67E23'],
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);

    function resizeChart() {
      chart.draw(data, options);
    }
    window.addEventListener('resize', resizeChart);
    resizeChart();

  }
}

function displayHitLogChartReportOneOn1Secure() {
  // Get the data from 1on1secure-wp-admin to a JavaScript array

  if (typeof hitlogchartdataarray == 'undefined') {
    return; //exit this function if there is no data
  }

  var hitlogchartvalue = hitlogchartdataarray.hitlogchartvalue;
  var hitlognames = hitlogchartdataarray.hitlognames;

  google.charts.load('current', {'packages':['corechart']});

  google.charts.setOnLoadCallback(function() {                            //fix when load the page for the first time and all the charts are displayed.
    updateVisibleChartOneOn1Secure();
    drawHitLogChartsOneOn1Secure();
  });

  function drawSpecificChartOneOn1Secure(hitlogname) {                    //set the default chart size to prevent the smaller chart after choosing the hitlogname
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Date');
    data.addColumn('number', 'Hits');
    data.addRows(hitlogchartdataarray.hitlogchartvalue[hitlogname]);

    var options = {
      title: hitlogname,
      chartArea: {
        height: '50%',
        width: '80%',
      },
      backgroundColor: 'transparent',
      vAxis: {
        minValue: 0,
      },
      curveType: 'curve',
      pointSize: 5,
      legend: {
        position: 'none',
      },
      series: {
        0: { color: '#E67E23', areaOpacity: 1.0 },
      },
    };

    var chart = new google.visualization.AreaChart(document.getElementById('chart_' + hitlogname));
    chart.draw(data, options);

    //resize the chart when the screen is smaller or bigger
    function resizeChart() {
      chart.draw(data, options);
    }
    window.addEventListener('resize', resizeChart);
    resizeChart();
  }

  function drawHitLogChartsOneOn1Secure() {                                 //this is the main function to display the chart
    hitlognames.forEach(function(hitlogname) {
      drawSpecificChartOneOn1Secure(hitlogname);
    });
  }

  function updateVisibleChartOneOn1Secure() {                               //update the chart after choosing the hitlogname
    var selectedClassification = document.getElementById('classificationSelect').value;
    hitlognames.forEach(function(hitlogname) {
      var chartDiv = document.getElementById('chart_' + hitlogname);
      if (selectedClassification === hitlogname) {
        chartDiv.style.display = 'block';
        drawSpecificChartOneOn1Secure(hitlogname);                           //draws the specific chart after making sure container is visible
      } else {
        chartDiv.style.display = 'none';
      }
    });
  }

  //expose the function to the global scope
  window.updateVisibleChartOneOn1Secure = updateVisibleChartOneOn1Secure;
}

//ensure displayHitLogChartReportOneOn1Secure function is called as soon as the page's DOM is loaded:
document.addEventListener('DOMContentLoaded', function() {
  displayHitLogChartReportOneOn1Secure();
});

jQuery(window).on('load', function() {

  if (typeof hitlogchartdataarray == 'undefined') {
    return; //exit this function if there is no data
  }

  displayPieChartOneOn1Secure();
  tableSortingOneOn1Secure();
  popupOneOn1Secure();
  closepopupOneOn1Secure();
  popupdropdownOneOn1Secure();
});