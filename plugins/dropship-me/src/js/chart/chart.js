jQuery(function($) {

    window.dmChart = {

        chartData : function( element, response, height ) {

            var $this = this;

            if( response.hasOwnProperty( 'error' ) ) {
                window.ADS.notify( response.error, 'danger' );
            } else {
                d3.select(element).selectAll('svg').remove();
                $this.chartRender( element, height, response, response.from, response.to );
            }
        },

        chartRender: function(element, height, response, from, to) {
            // Basic setup
            // ------------------------------

            // Define main variables
            var d3Container = d3.select(element),
                margin = {top: 5, right: 30, bottom: 30, left: 50},
                width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;
            height = height - margin.top - margin.bottom;

            var formatDate = from !== to ? d3.time.format("%d %B %Y") : d3.time.format("%H:00");

            // Tooltip
            var tooltip = d3.tip()
                .attr('class', 'd3-tip')
                .html(function (d) {
                    return '<ul class="list-unstyled">' +
                        '<li>' + response.sub_title + ': &nbsp;<span class="text-semibold pull-right">' + d.value + '</span></li>' +
                        '<li>' + response.time + ': &nbsp;<span class="text-semibold pull-right">' + formatDate(d.date) + '</span></li>' +
                        '</ul>';
                });

            // Format date
            var parseDate = from !== to ? d3.time.format("%Y/%m/%d").parse : d3.time.format("%Y-%m-%d %H:%M:%S").parse;

            // Line colors
            var scale = ["#4CAF50", "#FF5722", "#5C6BC0"],
                color = d3.scale.ordinal().range(scale);


            // Create chart
            // ------------------------------

            // Container
            var container = d3Container.append('svg');

            // SVG element
            var svg = container
                .attr('width', width + margin.left + margin.right)
                .attr('height', height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
                .call(tooltip);

            // Render
            // ------------------------------
            redraw();



            // Construct layout
            // ------------------------------

            // Add events
            var altKey;
            d3.select(window)
                .on("keydown", function() { altKey = d3.event.altKey; })
                .on("keyup", function() { altKey = false; });

            // Set terms of transition on date change
            function change() {
                d3.transition()
                    .duration(altKey ? 7500 : 500)
                    .each(redraw);
            }



            // Main chart drawing function
            // ------------------------------

            function redraw() {

                // Construct chart layout
                // ------------------------------

                // Create data nests
                //var nested = d3.nest().key(function(d) { return d.type; }).map(formatted);
                //var response_data = Object.values( response.data );
                var nested = d3.nest().key(function(d) { return d.type; }).map(response.data);

                // Get value from menu selection
                // the option values correspond
                //to the [type] value we used to nest the data
                //var series = menu.val();
                var series = 'value';

                // Only retrieve data from the selected series using nest
                var data = nested[series];

                // For object constancy we will need to set "keys", one for each type of data (column name) exclude all others.
                color.domain(d3.keys(data[0]).filter(function(key) { return (key !== "date" && key !== "type"); }));

                // Setting up color map
                var linedata = color.domain().map(function(name) {
                    return {
                        name: name,
                        values: data.map(function(d) {
                            return {name: name, date: parseDate(d.date), value: parseFloat(d[name])};
                        })
                    };
                });

                // Draw the line
                var line = d3.svg.line()
                    .x(function(d) { return x(d.date); })
                    .y(function(d) { return y(d.value); })
                    .interpolate('cardinal');



                // Construct scales
                // ------------------------------

                // Horizontal
                var x = d3.time.scale()
                    .domain([
                        d3.min(linedata, function(c) { return d3.min(c.values, function(v) { return v.date; }); }),
                        d3.max(linedata, function(c) { return d3.max(c.values, function(v) { return v.date; }); })
                    ])
                    .range([0, width]);

                // Vertical
                var y = d3.scale.linear()
                    .domain([
                        d3.min(linedata, function(c) { return d3.min(c.values, function(v) { return v.value; }); }),
                        d3.max(linedata, function(c) { return d3.max(c.values, function(v) { return v.value; }); })
                    ])
                    .range([height, 0]);



                // Create axes
                // ------------------------------

                var xTicks  = from !== to ? d3.time.days : d3.time.hours;
                var xFormat = from !== to ? d3.time.format('%d %b') : d3.time.format('%H:00');

                // Horizontal
                var xAxis = d3.svg.axis()
                    .scale(x)
                    .orient("bottom")
                    .tickPadding(8)
                    .ticks(xTicks, 4)
                    .innerTickSize(4)
                    .tickFormat(xFormat); // Display hours and minutes in 24h format

                // Vertical
                var yAxis = d3.svg.axis()
                    .scale(y)
                    .orient("left")
                    .tickPadding(8)
                    .ticks(6)
                    .tickSize(0 -width);



                //
                // Append chart elements
                //

                // Append axes
                // ------------------------------

                // Horizontal
                svg.append("g")
                    .attr("class", "d3-axis d3-axis-horizontal d3-axis-solid")
                    .attr("transform", "translate(0," + height + ")");

                // Vertical
                svg.append("g")
                    .attr("class", "d3-axis d3-axis-vertical d3-axis-transparent");



                // Append lines
                // ------------------------------

                // Bind the data
                var lines = svg.selectAll(".lines")
                    .data(linedata);

                // Append a group tag for each line
                var lineGroup = lines
                    .enter()
                    .append("g")
                    .attr("class", "lines")
                    .attr('id', function(d){ return d.name + "-line"; });

                // Append the line to the graph
                lineGroup.append("path")
                    .attr("class", "d3-line d3-line-medium")
                    .style("stroke", function(d) { return color(d.name); })
                    .style('opacity', 0)
                    .attr("d", function(d) { return line(d.values[0]); })
                    .transition()
                    .duration(500)
                    .delay(function(d, i) { return i * 200; })
                    .style('opacity', 1);



                // Append circles
                // ------------------------------

                var circles = lines.selectAll("circle")
                    .data(function(d) { return d.values; })
                    .enter()
                    .append("circle")
                    .attr("class", "d3-line-circle d3-line-circle-medium")
                    .attr("cx", function(d){return x(d.date)})
                    .attr("cy",function(d){return y(d.value)})
                    .attr("r", 3)
                    .style('fill', '#fff')
                    .style("stroke", function(d) { return color(d.name); });

                // Add transition
                circles
                    .style('opacity', 0)
                    .transition()
                    .duration(500)
                    .delay(500)
                    .style('opacity', 1);



                // Append tooltip
                // ------------------------------

                // Add tooltip on circle hover
                circles
                    .on("mouseover", function (d) {
                        tooltip.offset([-15, 0]).show(d);

                        // Animate circle radius
                        d3.select(this).transition().duration(250).attr('r', 4);
                    })
                    .on("mouseout", function (d) {
                        tooltip.hide(d);

                        // Animate circle radius
                        d3.select(this).transition().duration(250).attr('r', 3);
                    });

                // Change tooltip direction of first point
                // to always keep it inside chart, useful on mobiles
                lines.each(function () {
                    d3.select(d3.select(this).selectAll('circle')[0][0])
                        .on("mouseover", function (d) {
                            tooltip.offset([0, 15]).direction('e').show(d);

                            // Animate circle radius
                            d3.select(this).transition().duration(250).attr('r', 4);
                        })
                        .on("mouseout", function (d) {
                            tooltip.direction('n').hide(d);

                            // Animate circle radius
                            d3.select(this).transition().duration(250).attr('r', 3);
                        });
                });

                // Change tooltip direction of last point
                // to always keep it inside chart, useful on mobiles
                lines.each(function () {
                    d3.select(d3.select(this).selectAll('circle')[0][d3.select(this).selectAll('circle').size() - 1])
                        .on("mouseover", function (d) {
                            tooltip.offset([0, -15]).direction('w').show(d);

                            // Animate circle radius
                            d3.select(this).transition().duration(250).attr('r', 4);
                        })
                        .on("mouseout", function (d) {
                            tooltip.direction('n').hide(d);

                            // Animate circle radius
                            d3.select(this).transition().duration(250).attr('r', 3);
                        })
                });



                // Update chart on date change
                // ------------------------------

                // Set variable for updating visualization
                var lineUpdate = d3.transition(lines);

                // Update lines
                lineUpdate.select("path")
                    .attr("d", function(d) { return line(d.values); });

                // Update circles
                lineUpdate.selectAll("circle")
                    .attr("cy",function(d){return y(d.value)})
                    .attr("cx", function(d){return x(d.date)});

                // Update vertical axes
                d3.transition(svg)
                    .select(".d3-axis-vertical")
                    .call(yAxis);

                // Update horizontal axes
                d3.transition(svg)
                    .select(".d3-axis-horizontal")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);



                // Resize chart
                // ------------------------------

                // Call function on window resize
                $(window).on('resize', appSalesResize);

                // Call function on sidebar width change
                $(document).on('click', '.sidebar-control', appSalesResize);

                // Resize function
                //
                // Since D3 doesn't support SVG resize by default,
                // we need to manually specify parts of the graph that need to
                // be updated on window resize
                function appSalesResize() {

                    // Layout
                    // -------------------------

                    // Define width
                    width = d3Container.node().getBoundingClientRect().width - margin.left - margin.right;

                    // Main svg width
                    container.attr("width", width + margin.left + margin.right);

                    // Width of appended group
                    svg.attr("width", width + margin.left + margin.right);

                    // Horizontal range
                    x.range([0, width]);

                    // Vertical range
                    y.range([height, 0]);


                    // Chart elements
                    // -------------------------

                    // Horizontal axis
                    svg.select('.d3-axis-horizontal').call(xAxis);

                    // Vertical axis
                    svg.select('.d3-axis-vertical').call(yAxis.tickSize(0-width));

                    // Lines
                    svg.selectAll('.d3-line').attr("d", function(d) { return line(d.values); });

                    // Circles
                    svg.selectAll('.d3-line-circle').attr("cx", function(d){return x(d.date)})
                }
            }
        }
    };
});
