class ccew_call_ajax extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                renderHtml: '.ccew_htmlContainer',
                ccew_html_container: '.ccew_html_container',
                ccew_table_widget: '.ccew_table_widget'
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        return {
            $renderHtml: this.$element.find(selectors.renderHtml),
            $ccew_html_container: this.$element.find(selectors.ccew_html_container),
            $ccew_table_widget: this.$element.find(selectors.ccew_table_widget, )
        };
    }

    bindEvents() {
        var ccew_table_widget = this.elements.$ccew_table_widget;
        var selector = this.elements.$renderHtml;
        var id_selector = this.elements.$ccew_html_container;
        var data = selector.html();
   
        var ajaxURL = selector.attr("data-ajax-url");
        var wp_nonce = selector.attr("data-ajax-nonce");
        var id = id_selector.attr("id");
        var type = JSON.parse(data);
        var request = {
            'action': 'ccew_getData',
            'settings': JSON.parse(data),
            'nonce': wp_nonce,
        }

        jQuery.fn.generateSmallChart = function() {
            var thisEle = jQuery(this)
               var coin_id = 'bitcoin',
                period = jQuery(this).data("period"),
                chartfill = jQuery(this).data("chart-fill"),
                color = jQuery(this).data("color"),
                bgcolor=jQuery(this).data("bgcolor"),
                pointsSettings = jQuery(this).data("points"),
                currencyPrice = jQuery(this).data("currency-price"),
                currencySymbol = jQuery(this).data("currency-symbol"),
                points = 0;
            1 == pointsSettings && (points = 2);
                var historicalData = jQuery(this).data("content");
                undefined !== historicalData ? (historicalData = historicalData.map(function(value) {
                    var convertedPrice = parseFloat(value) * currencyPrice;
                    var decimalPosition = convertedPrice >= 1 ? 2 : convertedPrice < 1e-6 ? 8 : 6;
                    return convertedPrice.toFixed(decimalPosition)
                }), createChart(thisEle, historicalData, chartfill,color,bgcolor, points, currencySymbol)) : thisEle.before('<span class="no-graphical-data">' + thisEle.data("msz") + "</span>")
        }

        function createChart(element, chartData, chartfill,color,bgcolor, points, currencySymbol) {
            var data = {
                    labels: chartData,
                    datasets: [{
                        fill: chartfill,
                        lineTension: .25,
                        pointRadius: points,
                        data: chartData,
                        backgroundColor: bgcolor,
                        borderColor: color,
                        pointBorderColor: color
                    }]
                },
                maxval = Math.max.apply(Math, chartData) + 1 * Math.max.apply(Math, chartData) / 100,
                minval = Math.min.apply(Math, chartData) - 1 * Math.min.apply(Math, chartData) / 100,
                settings, chart = new Chart(element, {
                    type: "line",
                    data: data,
                    options: {
                        layout:{
                            padding:0
                        },
                        hover: {
                            mode: "nearest",
                            intersect: true
                        },
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                display: false
                            },
                            y: {
                                display: false,
                            }
                        },
                        animation: {
                            duration: 400
                        },
                        plugins:{
                            legend: {
                                display: false
                            },
                            tooltip: {
                                intersect: false,
                                displayColors: false,
                                callbacks: {
                                    label: function(context){
                                        let value = context.formattedValue
                                        return currencySymbol+""+value;
                                    },
                                    title: function(context){
                                        return "";
                                    }
                                }
                            }
                        }
                    }
                })
        }


        function chart_call_back(container, symbol) {

            var graphContainer = container;

            // var StrokeColor = color;

            var currencySymbol = symbol;


            anychart.onDocumentReady(function() {
                for (var i = 0; i < graphContainer.length; i++) {
                    var containerID = graphContainer[i].id;
                    var stage = anychart.graphics.create(containerID);
                    var chartPrice = graphContainer[i].dataset.chartprice;
                    chartPrice = JSON.parse(chartPrice);
                    var StrokeColor = graphContainer[i].dataset.stroke_color;
                    var coin_price = graphContainer[i].dataset.currency_price;
                    // create charts
                    var chart1 = anychart.sparkline();

                    chart1.data(chartPrice);
                    chart1.stroke(StrokeColor);
                    chart1.height('70%');
                    //chart1.padding('2px 12px');
                    // chart1.height('100');
                    //chart1.bounds(8, 0, 0, 20);
                    chart1.container(stage);
                    // chart1.padding('12px 12px');
                    // setting the tooltips with formatting
                    var columnTooltip = chart1.tooltip();
                    var decimalPostion = coin_price >= 1 ? 2 : coin_price < 1e-6 ? 8 : 6;
                    columnTooltip.format(currencySymbol + "{%value}{decimalsCount:" + decimalPostion + ",groupsSeparator:\\,}");

                    // initiate chart drawing
                    chart1.draw();
                };


            });

        }

        function ccew_ajax_data() {
            if (type.widget_type == 'advanced_table') {
                var table_id = '';
                jQuery.fn.ccewDatatable = function() {
                    table_id = jQuery(this).attr('id');
                    var $ccew_table = jQuery(this);
                    var columns = [];
                    var pagination = parseInt(type.pagination)
                    var loadingLbl = type.loading_lbl;
                    var prevtext = type.prev
                    var nexttext = type.next
                    var zeroRecords = type.no_data
                    var fiatSymbol = type.symbol
                    var numberFormat = type.number_formating
                    $ccew_table.find('thead th').each(function(index) {
                        var thisTH = jQuery(this);
                        var index = thisTH.data('index');
                        var classes = thisTH.data('classes');

                        columns.push({
                            data: index,
                            name: index,
                            render: function(data, type, row, meta) {

                                if (meta.settings.json === undefined) { return data; }
                                switch (index) {
                                    case 'rank':
                                        return data;
                                        break;
                                    case 'name':
                                        if (typeof dynamicLink != 'undefined' && dynamicLink != "") {
                                            var coinLink = currencyLink + '/' + row.symbol + '/' + row.id;
                                            var html = '<div class="' + classes + '"><a class="ccew_links" title="' + row.name + '" href="' + coinLink + '"><span class="ccew_coin_logo">' + row.logo + '</span><span class="ccew_coin_symbol">(' + row.symbol + ')</span><br/><span class="ccew_coin_name ccew-desktop">' + row.name + '</span></a></div>';
                                        } else {
                                            var html = '<div class="' + classes + '"><span class="ccew_coin_logo">' + row.logo + '</span><span class="ccew_coin_symbol">(' + row.symbol + ')</span><br/><span class="ccew_coin_name ccew-desktop">' + data + '</span></div>';
                                        }
                                        return html;
                                    case 'price':
                                        if (typeof data !== 'undefined' && data != null) {
                                            var formatedVal = ccew_numeral_formating(data);
                                            return html = '<div data-val="' + row.price + '" class="' + classes + '"><span class="ccew-formatted-price">' + fiatSymbol + formatedVal + '</span></div>';
                                        } else {
                                            return html = '<div class="' + classes + '>?</div>';
                                        }
                                        break;
                                    case 'change_percentage_24h':
                                        if (typeof data !== 'undefined' && data != null) {
                                            var changesCls = "up";
                                            var wrpchangesCls = "ccew-up";
                                            if (typeof Math.sign === 'undefined') { Math.sign = function(x) { return x > 0 ? 1 : x < 0 ? -1 : x; } }
                                            if (Math.sign(data) == -1) {
                                                var changesCls = "down";
                                                var wrpchangesCls = "ccew-down";
                                            }
                                            var html = '<div class="' + classes + ' ' + wrpchangesCls + '"><span class="changes ' + changesCls + '"><i class="ccew_icon-' + changesCls + '" aria-hidden="true"></i>' + data + '%</span></div>';
                                            return html;
                                        } else {
                                            return html = '<div class="' + classes + '">?</span></div>';
                                        }
                                        break;
                                    case 'market_cap':
                                        if (typeof data !== 'undefined' && data != null) {
                                            var formatedVal = ccew_numeral_formating(data);
                                            if (numberFormat) {
                                                var formatedVal = numeral(data).format('(0.00 a)').toUpperCase();
                                            }
                                            return html = '<div data-val="' + row.market_cap + '" class="' + classes + '"><span class="ccew-formatted-market-cap">' + fiatSymbol + formatedVal + '</span></div>';
                                        } else {
                                            return html = '<div class="' + classes + '>?</div>';
                                        }
                                        break;
                                    case 'total_volume':
                                        if (typeof data !== 'undefined' && data != null) {
                                            var formatedVal = ccew_numeral_formating(data);
                                            if (numberFormat) {
                                                var formatedVal = numeral(data).format('(0.00 a)').toUpperCase();
                                            }
                                            return html = '<div data-val="' + row.total_volume + '" class="' + classes + '"><span class="ccew-formatted-total-volume">' + fiatSymbol + formatedVal + '</span></div>';
                                        } else {
                                            return html = '<div class="' + classes + '>?</div>';
                                        }
                                        break;
                                    case 'supply':
                                        if (typeof data !== 'undefined' && data != null && row.supply != 'N/A') {
                                            var formatedVal = ccew_numeral_formating(data);
                                            if (numberFormat) {
                                                var formatedVal = numeral(data).format('(0.00 a)').toUpperCase();
                                            }
                                            return html = '<div data-val="' + row.supply + '" class="' + classes + '"><span class="ccew-formatted-supply">' + formatedVal + ' ' + row.symbol + '</span></div>';
                                        } else {
                                            return html = '<div class="' + classes + '">N/A</div>';
                                        }
                                        break;
                                    default:
                                        return data;
                                }
                            },
                            "createdCell": function(td, cellData, rowData, row, col) {
                                jQuery(td).attr('data-sort', cellData);
                            }
                        });
                    });


                    var table = $ccew_table.DataTable({
                        "deferRender": true,
                        "serverSide": true,
                        "ajax": {
                            "url": ajaxURL,
                            "type": "POST",
                            "dataType": "JSON",
                            "data": request,
                            "error": function(xhr, error, thrown) {
                                alert('Something wrong with Server');
                            }
                        },
                        "destroy": true,
                        "ordering": false,
                        "searching": false,
                        "pageLength": pagination,
                        "columns": columns,
                        "responsive": true,
                        "lengthChange": false,
                        "pagingType": "simple",
                        "processing": true,
                        "dom": '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
                        "language": {
                            "processing": loadingLbl,
                            "loadingRecords": loadingLbl,
                            "paginate": {
                                "next": nexttext,
                                "previous": prevtext
                            },
                        },
                        "zeroRecords": zeroRecords,
                        "emptyTable": zeroRecords,
                        "renderer": {
                            "header": "bootstrap",
                        },
                        "drawCallback": function(settings) {
                            $ccew_table.tableHeadFixer({
                                // fix table header
                                head: true,
                                // fix table footer
                                foot: false,
                                left: 2,
                                right: false,
                                'z-index': 1
                            });

                        },

                    });
                }
                jQuery(ccew_table_widget).each(function() {
                    jQuery(this).ccewDatatable();
                    new Tablesort(this, {
                        descending: true
                    });
                });

                function ccew_numeral_formating(data) {
                    if (data >= 25 || data <= -1) {
                        var formatedVal = numeral(data).format('0,0.00');
                    } else if (data >= 0.50 && data < 25) {
                        var formatedVal = numeral(data).format('0,0.000');
                    } else if (data >= 0.01 && data < 0.50) {
                        var formatedVal = numeral(data).format('0,0.0000');
                    } else if (data >= 0.0001 && data < 0.01) {
                        var formatedVal = numeral(data).format('0,0.00000');
                    } else {
                        var formatedVal = numeral(data).format('0,0.00000000');
                    }
                    return formatedVal;
                }

            } else {
                jQuery.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: ajaxURL,
                    data: request,
                    success: function(response) {
                        if (response.status == 'success') {
                            var data = response.data
                            jQuery('#' + id).html(data);
                            if (type.widget_type == "list" || type.widget_type == 'top_gainer_loser') {
                                var container = jQuery(id_selector).find('.ccew-coin-graph');
                                var symbol = container.data('currency') + ' ';
                                chart_call_back(container, symbol);
                            }else if(type.widget_type == 'card'){
                            jQuery("#"+ id+" .ccew-sparkline-charts").generateSmallChart()
                            }

                        } else {
                            console.log("response status failed");
                        }
                    },
                    error: function(data) {
                        console.log(data.status + ':' + data.statusText, data.responseText);
                    }
                })
            }
        }
        setInterval(ccew_ajax_data, 600000);
        ccew_ajax_data();
    }
}



jQuery(window).on('elementor/frontend/init', () => {

    const addHandler = ($element) => {
        elementorFrontend.elementsHandler.addHandler(ccew_call_ajax, {
            $element,
        });
    };

    elementorFrontend.hooks.addAction('frontend/element_ready/cryptocurrency-elementor-widget.default', addHandler);

});