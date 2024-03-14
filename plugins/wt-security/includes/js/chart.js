const isDarkThemeSet = ()=> {
    return !!document.querySelector(".wtotem_theme—dark")}
;

async function drawLineChart(dataset) {
    var diagram = document.getElementById('wtotem_chart_diagram');
    var days = diagram.dataset.days;

    const attacksAccessor = (d) => d.attacks;
    const blockedAccessor = (d) => d.blocked;
    const biggerAccessor = (d) =>
        d.attacks > d.blocked ? d.attacks : d.blocked;
    const dateParser = (days <= 1) ? d3.timeParse("%Y-%m-%d %H:%m:%M"): d3.timeParse("%Y-%m-%d");
    const xAccessor = (d) => dateParser(d.date);


    const chartWrapper = document.getElementById('line-chart')
    let dimensions = {
        width: chartWrapper.offsetWidth,
        height: 251,
        margin: {
            top: 15,
            right: 15,
            bottom: 40,
            left: 60,
        },
    };
    dimensions.boundedWidth =
        dimensions.width - dimensions.margin.left - dimensions.margin.right;
    dimensions.boundedHeight =
        dimensions.height - dimensions.margin.top - dimensions.margin.bottom;

    const wrapper = d3
        .select("#line-chart")
        .append("svg")
        .attr("width", dimensions.width)
        .attr("height", dimensions.height);

    const bounds = wrapper
        .append("g")
        .style(
            "transform",
            `translate(${dimensions.margin.left - 10}px, ${
                dimensions.margin.top
                }px)`
        );


    const yValues = dataset.reduce(
        (acc, curr) => [...acc, curr.attacks, curr.blocked],
        [0, 100]
    );

    const yScale = d3
        .scaleLinear()
        .domain(d3.extent(yValues))
        .range([dimensions.boundedHeight, 0]);
    const xScale = d3
        .scaleTime()
        .domain(d3.extent(dataset, xAccessor))
        .range([0, dimensions.boundedWidth]);

    function make_y_gridlines() {
        return d3.axisLeft(yScale).ticks(5);
    }

    bounds
        .append("g")
        .attr("class", "grid")
        .call(
            make_y_gridlines().tickSize(-dimensions.boundedWidth).tickFormat("")
        );

    const area1 = d3
        .area()
        .x((d) => xScale(xAccessor(d)))
        .y0(yScale(0))
        .y1((d) => yScale(attacksAccessor(d)));

    const area2 = d3
        .area()
        .x((d) => xScale(xAccessor(d)))
        .y0(yScale(0))
        .y1((d) => yScale(blockedAccessor(d)));

    const isDarkTheme = isDarkThemeSet();
    const minOpacity = isDarkTheme ? 0.2 : 0.1;
    const maxOpacity = isDarkTheme ? 0.8 : 0.6;

    bounds
        .append("linearGradient")
        .attr("id", "area-gradient1")
        .attr("gradientUnits", "userSpaceOnUse")
        .attr("x1", 0)
        .attr("y1", yScale(0))
        .attr("x2", 0)
        .attr("y2", yScale(100))
        .selectAll("stop")
        .data([
            { offset: "0%", color: "#d46c6a", opacity: minOpacity},
            { offset: "100%", color: "#d46c6a", opacity: maxOpacity},
        ])
        .enter()
        .append("stop")
        .attr("offset", function (d) {
            return d.offset;
        })
        .attr("stop-color", function (d) {
            return d.color;
        })
        .attr("stop-opacity", function (d) {
            return d.opacity;
        });

    bounds
        .append("linearGradient")
        .attr("id", "area-gradient2")
        .attr("gradientUnits", "userSpaceOnUse")
        .attr("x1", 0)
        .attr("y1", yScale(0))
        .attr("x2", 0)
        .attr("y2", yScale(100))
        .selectAll("stop")
        .data([
            { offset: "0%", color: "#bace3d", opacity: minOpacity },
            { offset: "100%", color: "#bace3d", opacity: maxOpacity},
        ])
        .enter()
        .append("stop")
        .attr("offset", function (d) {
            return d.offset;
        })
        .attr("stop-color", function (d) {
            return d.color;
        })
        .attr("stop-opacity", function (d) {
            return d.opacity;
        });

    const lineGenerator1 = d3
        .line()
        .x((d) => xScale(xAccessor(d)))
        .y((d) => yScale(attacksAccessor(d)));
    const lineGenerator2 = d3
        .line()
        .x((d) => xScale(xAccessor(d)))
        .y((d) => yScale(attacksAccessor(d)));

    const line1 = bounds
        .append("path")
        .datum(dataset)
        .attr("d", lineGenerator1(dataset))
        .attr("class", "area")
        .attr("d", area1)
        .style("fill", "url(#area-gradient1)");

    const line2 = bounds
        .append("path")
        .datum(dataset)
        .attr("d", lineGenerator2(dataset))
        .attr("class", "area")
        .attr("d", area2)
        .style("fill", "url(#area-gradient2)");

    const yAxisGenerator = d3.axisLeft().scale(yScale);
    // const xAxisGenerator = d3.axisBottom().scale(xScale);

    let dateFormat = (days <= 1) ? d3.timeFormat("%H:%M") : (days > 31) ? d3.timeFormat("%b %Y") : d3.timeFormat("%b %d");
    const xAxisGenerator = (days <= 1) ? d3.axisBottom().scale(xScale).ticks(d3.timeHour.every(2)).tickFormat(dateFormat) :
        (days > 31) ? d3.axisBottom().scale(xScale).ticks(d3.timeMonth.every(1)).tickFormat(dateFormat) :
            (days <= 7) ? d3.axisBottom().scale(xScale).ticks(d3.timeDay.every(1)).tickFormat(dateFormat) :
                d3.axisBottom().scale(xScale).ticks(9).tickFormat(dateFormat);
    //const xAxisGenerator = d3.axisBottom().scale(xScale).ticks(ticks).tickFormat(dateFormat);

    const yAxis = bounds.append("g").attr("class", "axis").call(yAxisGenerator);
    const xAxis = bounds
        .append("g")
        .call(xAxisGenerator)
        .attr("class", "axis chart-date")
        .style("transform", `translateY(${dimensions.boundedHeight}px)`);

    const listeningRect = bounds
        .append("rect")
        .attr("class", "listening-rect")
        .attr("width", dimensions.boundedWidth)
        .attr("height", dimensions.boundedHeight)
        .on("mousemove", onMouseMove)
        .on("mouseleave", onMouseLeave);

    const tooltipAttacks = d3.select("#tooltipAttacks");
    const tooltipBlocked = d3.select("#tooltipBlocked");
    const tooltipCircle1 = bounds
        .append("circle")
        .attr("class", "tooltip-circle")
        .attr("r", 4)
        .attr("stroke", "#F3F5F6")
        .attr("fill", "#1D293F")
        .attr("stroke-width", 2)
        .style("opacity", 0);
    const tooltipCircle2 = bounds
        .append("circle")
        .attr("class", "tooltip-circle")
        .attr("r", 4)
        .attr("stroke", "#F3F5F6")
        .attr("fill", "#1D293F")
        .attr("stroke-width", 2)
        .style("opacity", 0);
    function onMouseMove() {
        const mousePosition = d3.mouse(this);
        const hoveredDate = xScale.invert(mousePosition[0]);

        const getDistanceFromHoveredDate = (d) =>
            Math.abs(xAccessor(d) - hoveredDate);
        const closestIndex = d3.scan(
            dataset,
            (a, b) =>
                getDistanceFromHoveredDate(a) - getDistanceFromHoveredDate(b)
        );
        const closestDataPoint = dataset[closestIndex];

        const closestXValue = xAccessor(closestDataPoint);
        const closestAttacksValue = attacksAccessor(closestDataPoint);
        const closestBlockedValue = blockedAccessor(closestDataPoint);

        if(closestAttacksValue || closestBlockedValue){
            AmplitudeAnalytics.worldMap(closestAttacksValue, closestBlockedValue);
        }

        const x = xScale(closestXValue) + dimensions.margin.left + 8;
        const yAttacks =
            yScale(closestAttacksValue) + dimensions.margin.top + 10;
        const yBlocked =
            yScale(closestBlockedValue) + dimensions.margin.top + 10;

        tooltipAttacks.style(
            "transform",
            `translate(` +
            `calc( -50% + ${x}px),` +
            `calc(-100% + ${yAttacks}px)` +
            `)`
        );
        tooltipBlocked.style(
            "transform",
            `translate(` +
            `calc( -50% + ${x}px),` +
            `calc(-100% + ${yBlocked}px)` +
            `)`
        );

        tooltipAttacks.style("opacity", 1);
        tooltipBlocked.style("opacity", 1);

        tooltipAttacks.select("#countAttacks").html(closestAttacksValue);
        tooltipBlocked.select("#countBlocked").html(closestBlockedValue);
        tooltipCircle1
            .attr("cx", xScale(closestXValue))
            .attr("cy", yScale(closestAttacksValue))
            .style("opacity", 1);
        tooltipCircle2
            .attr("cx", xScale(closestXValue))
            .attr("cy", yScale(closestBlockedValue))
            .style("opacity", 1);
    }

    function onMouseLeave() {
        tooltipAttacks.style("opacity", 0);
        tooltipBlocked.style("opacity", 0);

        tooltipCircle1.style("opacity", 0);
        tooltipCircle2.style("opacity", 0);
    }
}

const drawWafChart = (data) => {
    var firewallChart = d3.select("#line-chart").selectAll("svg")
    firewallChart = firewallChart.remove();
    drawLineChart(data);
};

if(typeof waf_chart == "object"){
    drawWafChart(waf_chart);
}

var resizeTimerFirewallChart;
window.onresize = function (event) {
    clearTimeout(resizeTimerFirewallChart);
    resizeTimerFirewallChart = setTimeout(function () {
        drawLineChart(waf_chart);
    }, 10);
};

// server-status
async function drawServerStatusChart(id, elementSelector, tooltipSelector, tooltipValueSelector, color, dataset) {
    const dataAccessor = (d) => d.value;

    let diagram  = document.querySelector(elementSelector);
    let days = diagram.dataset.days;

    const dateParser = (days <= 1) ? d3.timeParse("%Y-%m-%d %H:%m:%M"): d3.timeParse("%Y-%m-%d");

    const xAccessor = (d) => dateParser(d.date);

    const chartWrapper = document.querySelector(elementSelector);
    let dimensions = {
        width: chartWrapper.offsetWidth,
        height: 251,
        margin: {
            top: 15,
            right: 15,
            bottom: 40,
            left: 60,
        },
    };
    dimensions.boundedWidth =
        dimensions.width - dimensions.margin.left - dimensions.margin.right;
    dimensions.boundedHeight =
        dimensions.height - dimensions.margin.top - dimensions.margin.bottom;

    const wrapper = d3
        .select(elementSelector)
        .append("svg")
        .attr("width", dimensions.width)
        .attr("height", dimensions.height);

    const bounds = wrapper
        .append("g")
        .style(
            "transform",
            `translate(${dimensions.margin.left - 10}px, ${
                dimensions.margin.top
                }px)`
        );

    const yValues = dataset.reduce(
        (acc, curr) => [...acc, curr.value],
        [0, 100]
    );

    const yScale = d3
        .scaleLinear()
        .domain(d3.extent(yValues))
        .range([dimensions.boundedHeight, 0]);
    const xScale = d3
        .scaleTime()
        .domain(d3.extent(dataset, xAccessor))
        .range([0, dimensions.boundedWidth]);

    function make_y_gridlines() {
        return d3.axisLeft(yScale).ticks(5);
    }

    bounds
        .append("g")
        .attr("class", "grid")
        .call(
            make_y_gridlines().tickSize(-dimensions.boundedWidth).tickFormat("")
        );

    const area1 = d3
        .area()
        .x((d) => xScale(xAccessor(d)))
        .y0(yScale(0))
        .y1((d) => yScale(dataAccessor(d)));

    const gradientAreaId = "area-gradient-" + id;

    const isDarkTheme = isDarkThemeSet();
    const minOpacity = isDarkTheme ? 0.2 : 0.1;
    const maxOpacity = isDarkTheme ? 0.8 : 0.6;

    bounds
        .append("linearGradient")
        .attr("id", gradientAreaId)
        .attr("gradientUnits", "userSpaceOnUse")
        .attr("x1", 0)
        .attr("y1", yScale(0))
        .attr("x2", 0)
        .attr("y2", yScale(100))
        .selectAll("stop")
        .data([
            { offset: "0%", color: color, opacity: minOpacity },
            { offset: "100%", color: color, opacity: maxOpacity },
        ])
        .enter()
        .append("stop")
        .attr("offset", function (d) {
            return d.offset;
        })
        .attr("stop-color", function (d) {
            return d.color;
        })
        .attr("stop-opacity", function (d) {
            return d.opacity;
        });

    const lineGenerator1 = d3
        .line()
        .x((d) => xScale(xAccessor(d)))
        .y((d) => yScale(dataAccessor(d)));

    const line1 = bounds
        .append("path")
        .datum(dataset)
        .attr("d", lineGenerator1(dataset))
        .attr("class", "area")
        .attr("d", area1)
        .style("fill", "url(#"+gradientAreaId+")");

    const yAxisGenerator = d3.axisLeft().scale(yScale);
    // const xAxisGenerator = d3.axisBottom().scale(xScale);
    let dateFormat = (days <= 1) ? d3.timeFormat("%H:%M") : (days > 31) ? d3.timeFormat("%b %Y")  : d3.timeFormat("%b %d");
    const xAxisGenerator = (days <= 1) ? d3.axisBottom().scale(xScale).ticks(d3.timeHour.every(2)).tickFormat(dateFormat) :
        (days > 31) ? d3.axisBottom().scale(xScale).ticks(d3.timeMonth.every(1)).tickFormat(dateFormat) :
            (days <= 7) ? d3.axisBottom().scale(xScale).ticks(d3.timeDay.every(1)).tickFormat(dateFormat) :
                d3.axisBottom().scale(xScale).ticks(9).tickFormat(dateFormat);

    const yAxis = bounds.append("g").attr("class", "axis").call(yAxisGenerator);
    const xAxis = bounds
        .append("g")
        .call(xAxisGenerator)
        .attr("class", "axis")
        .style("transform", `translateY(${dimensions.boundedHeight}px)`);

    const listeningRect = bounds
        .append("rect")
        .attr("class", "listening-rect")
        .attr("width", dimensions.boundedWidth)
        .attr("height", dimensions.boundedHeight)
        .on("mousemove", onMouseMove)
        .on("mouseleave", onMouseLeave);

    const tooltipData = d3.select(tooltipSelector);
    const tooltipCircle1 = bounds
        .append("circle")
        .attr("class", "tooltip-circle")
        .attr("r", 4)
        .attr("stroke", "#F3F5F6")
        .attr("fill", "#1D293F")
        .attr("stroke-width", 2)
        .style("opacity", 0);

    function onMouseMove() {
        const mousePosition = d3.mouse(this);
        const hoveredDate = xScale.invert(mousePosition[0]);

        const getDistanceFromHoveredDate = (d) =>
            Math.abs(xAccessor(d) - hoveredDate);
        const closestIndex = d3.scan(
            dataset,
            (a, b) =>
                getDistanceFromHoveredDate(a) - getDistanceFromHoveredDate(b)
        );
        const closestDataPoint = dataset[closestIndex];

        const closestXValue = xAccessor(closestDataPoint);
        const closestValue = dataAccessor(closestDataPoint);

        const x = xScale(closestXValue) + dimensions.margin.left + 8;
        const yAttacks =
            yScale(closestValue) + dimensions.margin.top + 10;

        tooltipData.style(
            "transform",
            `translate(` +
            `calc( -50% + ${x}px),` +
            `calc(-100% + ${yAttacks}px)` +
            `)`
        );

        tooltipData.style("opacity", 1);

        tooltipData.select(tooltipValueSelector).html(closestValue);
        tooltipCircle1
            .attr("cx", xScale(closestXValue))
            .attr("cy", yScale(closestValue))
            .style("opacity", 1);
    }

    function onMouseLeave() {
        tooltipData.style("opacity", 0);
        tooltipCircle1.style("opacity", 0);
    }
}

const drawRamChart = (data) => {
    const ramChartSelector = "#ram-chart";
    const ramTooltipSelector = "#tooltipRam";
    const ramTooltipValueSelector = "#countRam";
    const ramChartColor = "#3d50df";


    let chartRam = d3.select(ramChartSelector).selectAll("svg")
    chartRam = chartRam.remove();

    drawServerStatusChart("ram", ramChartSelector, ramTooltipSelector, ramTooltipValueSelector, ramChartColor, data);
}


if(typeof ram_chart == "object"){
    drawRamChart(ram_chart);
}


const drawCpuChart = (data) => {
    const cpuChartSelector = "#cpu-chart";
    const cpuTooltipSelector = "#tooltipCpu";
    const cpuTooltipValueSelector = "#countCpu";
    const cpuChartColor = "#6d3594";

    let chartCpu = d3.select(cpuChartSelector).selectAll("svg");
    chartCpu = chartCpu.remove();

    drawServerStatusChart("cpu", cpuChartSelector, cpuTooltipSelector, cpuTooltipValueSelector, cpuChartColor, data);
}

if(typeof cpu_chart == "object") {
    drawCpuChart(cpu_chart);
}


var resizeTimerRamChart;
var resizeTimerCpuChart;

window.onresize = function (event) {

    if (document.querySelector("#ram-chart") !== null) {
        clearTimeout(resizeTimerRamChart);
        resizeTimerRamChart = setTimeout(function () {
            if(typeof ram_chart == "object") {
                drawRamChart(ram_chart);
            }
        }, 10);
    }

    if (document.querySelector("#cpu-chart") !== null) {
        clearTimeout(resizeTimerCpuChart);
        resizeTimerCpuChart = setTimeout(function () {
            if(typeof cpu_chart == "object") {
                drawCpuChart(cpu_chart);
            }
        }, 10);
    }
};


const colorModeToggle = document.querySelector("#color_scheme_toggle");
if(colorModeToggle){
    const addThemeChangeEventListener = (callback)=>{
        colorModeToggle.addEventListener("change", callback)
    }

    addThemeChangeEventListener(()=>{
        if(typeof cpu_chart == "object") {
            drawCpuChart(cpu_chart);
        }
        if(typeof ram_chart == "object") {
            drawRamChart(ram_chart);
        }
        if(typeof attacks_map == "object"){
            attacksMap();
        }
    });
}

// disk-chart

async function drawDiskChart_(elementSelector, data) {
// set the dimensions and margins of the graph
    var width = 150
    height = 150
    margin = 1

// The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
    var radius = Math.min(width, height) / 2 - margin

// append the svg object to the div called 'my_dataviz'
    var svg = d3.select(elementSelector)
        .append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

// Create dummy data
// var data = {a: 9, b: 20, c:30, d:8, e:12}

// set the color scale
    var color = d3.scaleOrdinal()
        .domain(data)
        .range(["#5E6977", "#3D50DF"])

// Compute the position of each group on the pie:
    var pie = d3.pie()
        .value(function(d) {return d.value; })
    var data_ready = pie(d3.entries(data))

// Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
    svg
        .selectAll('whatever')
        .data(data_ready)
        .enter()
        .append('path')
        .attr('d', d3.arc()
            .innerRadius(30)         // This is the size of the donut hole
            .outerRadius(radius)
        )
        .attr('fill', function(d){ return(color(d.data.key)) })
        .attr("stroke", "white")
        .style("stroke-width", "15px")
        .style("opacity", 1)

}


const drawDiskChart = (data) => {
    const diskData = {use: data.used, free: data.free};
    const discChartSelector = "#disk-chart";
    drawDiskChart_(discChartSelector, diskData);
};

if(typeof disc_chart == "object") {
    drawDiskChart(disc_chart);
}

/**
 * Create chart "Attacks on world map"
 * @returns {Promise<void>}
 */
async function attacksMap() {

    var countries = attacks_map['countries'];
    var labels = attacks_map['labels'];
    new Chart(document.getElementById("firewall"), {
        type: "horizontalBar",

        data: {
            labels: labels,
            datasets: [
                {
                    backgroundColor: "#3D50DF",
                    data: attacks_map['attacks'],
                },
            ],
        },
        options: {
            cornerRadius: 3,
            maintainAspectRatio: false,
            scales: {
                yAxes: [
                    {
                        barPercentage: 0.6,
                        stacked: true,
                        gridLines: {
                            display: true,
                        },
                    },
                ],
                xAxes: [
                    {
                        gridLines: {
                            display: false,
                        },
                    },
                ],
            },
            legend: {
                display: false,
            },
        },
    });

    const fill = (country) => {
        svg._groups[0][0].childNodes[0].childNodes.forEach((e) => {
            if (e.__data__.properties.name === country) {
                d3.select(e).style("fill", "#3D50DF");
            }
        });
    };
// The svg
    var svg = d3.select("#firewallMap"),
        width = +svg.attr("width"),
        height = +svg.attr("height");

// Map and projection
    var path = d3.geoPath();
    var projection = d3
        .geoMercator()
        .scale(60)
        .center([0, 0])
        .translate([210, 220]);

// Load external data and boot
    d3.queue().defer(d3.json, world_map_json).await(ready);

    function ready(error, topo) {

        // Draw the map
        svg.append("g")
            .selectAll("path")
            .data(topo.features)
            .enter()
            .append("path")
            // draw each country
            .attr("d", d3.geoPath().projection(projection))
            // set the color of each country
            .attr("fill", function () {
                return "#5E6977";
            })
            .style("stroke", "transparent")
            .attr("class", function () {
                return "Country";
            });
        countries.map((e) => {
            fill(e);
        });
    }


    Chart.elements.Rectangle.prototype.draw = function () {
        function t(t) {
            return s[(f + t) % 4];
        }
        var r,
            e,
            i,
            o,
            _,
            h,
            l,
            a,
            b = this._chart.ctx,
            d = this._view,
            n = d.borderWidth,
            u = this._chart.config.options.cornerRadius;
        if (
            (u < 0 && (u = 0),
            void 0 === u && (u = 0),
                d.horizontal
                    ? ((r = d.base),
                        (e = d.x),
                        (i = d.y - d.height / 2),
                        (o = d.y + d.height / 2),
                        (_ = e > r ? 1 : -1),
                        (h = 1),
                        (l = d.borderSkipped || "left"))
                    : ((r = d.x - d.width / 2),
                        (e = d.x + d.width / 2),
                        (i = d.y),
                        (_ = 1),
                        (h = (o = d.base) > i ? 1 : -1),
                        (l = d.borderSkipped || "bottom")),
                n)
        ) {
            var T = Math.min(Math.abs(r - e), Math.abs(i - o)),
                v = (n = n > T ? T : n) / 2,
                g = r + ("left" !== l ? v * _ : 0),
                c = e + ("right" !== l ? -v * _ : 0),
                C = i + ("top" !== l ? v * h : 0),
                w = o + ("bottom" !== l ? -v * h : 0);
            g !== c && ((i = C), (o = w)),
            C !== w && ((r = g), (e = c));
        }
        b.beginPath(),
            (b.fillStyle = d.backgroundColor),
            (b.strokeStyle = d.borderColor),
            (b.lineWidth = n);
        var s = [
                [r, o],
                [r, i],
                [e, i],
                [e, o],
            ],
            f = ["bottom", "left", "top", "right"].indexOf(l, 0);
        -1 === f && (f = 0);
        var q = t(0);
        b.moveTo(q[0], q[1]);
        for (var m = 1; m < 4; m++)
            (q = t(m)),
                (nextCornerId = m + 1),
            4 == nextCornerId && (nextCornerId = 0),
                (nextCorner = t(nextCornerId)),
                (width = s[2][0] - s[1][0]),
                (height = s[0][1] - s[1][1]),
                (x = s[1][0]),
                (y = s[1][1]),
            (a = u) > Math.abs(height) / 2 &&
            (a = Math.floor(Math.abs(height) / 2)),
            a > Math.abs(width) / 2 &&
            (a = Math.floor(Math.abs(width) / 2)),
                height < 0
                    ? ((x_tl = x),
                        (x_tr = x + width),
                        (y_tl = y + height),
                        (y_tr = y + height),
                        (x_bl = x),
                        (x_br = x + width),
                        (y_bl = y),
                        (y_br = y),
                        b.moveTo(x_bl + a, y_bl),
                        b.lineTo(x_br - a, y_br),
                        b.quadraticCurveTo(x_br, y_br, x_br, y_br - a),
                        b.lineTo(x_tr, y_tr + a),
                        b.quadraticCurveTo(x_tr, y_tr, x_tr - a, y_tr),
                        b.lineTo(x_tl + a, y_tl),
                        b.quadraticCurveTo(x_tl, y_tl, x_tl, y_tl + a),
                        b.lineTo(x_bl, y_bl - a),
                        b.quadraticCurveTo(x_bl, y_bl, x_bl + a, y_bl))
                    : width < 0
                    ? ((x_tl = x + width),
                        (x_tr = x),
                        (y_tl = y),
                        (y_tr = y),
                        (x_bl = x + width),
                        (x_br = x),
                        (y_bl = y + height),
                        (y_br = y + height),
                        b.moveTo(x_bl + a, y_bl),
                        b.lineTo(x_br - a, y_br),
                        b.quadraticCurveTo(x_br, y_br, x_br, y_br - a),
                        b.lineTo(x_tr, y_tr + a),
                        b.quadraticCurveTo(x_tr, y_tr, x_tr - a, y_tr),
                        b.lineTo(x_tl + a, y_tl),
                        b.quadraticCurveTo(x_tl, y_tl, x_tl, y_tl + a),
                        b.lineTo(x_bl, y_bl - a),
                        b.quadraticCurveTo(x_bl, y_bl, x_bl + a, y_bl))
                    : (b.moveTo(x + a, y),
                        b.lineTo(x + width - a, y),
                        b.quadraticCurveTo(
                            x + width,
                            y,
                            x + width,
                            y + a
                        ),
                        b.lineTo(x + width, y + height - a),
                        b.quadraticCurveTo(
                            x + width,
                            y + height,
                            x + width - a,
                            y + height
                        ),
                        b.lineTo(x + a, y + height),
                        b.quadraticCurveTo(
                            x,
                            y + height,
                            x,
                            y + height - a
                        ),
                        b.lineTo(x, y + a),
                        b.quadraticCurveTo(x, y, x + a, y));
        b.fill(), n && b.stroke();
    };
}

if(typeof attacks_map == "object"){
    attacksMap();
}
