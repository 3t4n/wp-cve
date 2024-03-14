(function(){
    const themeColors = {
        border:'#6A30F5',
        tooltipBg:'#FFFFFF',
        tooltipTitle:'#6A30F5',
        tooltipLabel:'#333333',
        tooltipBorder:'#FFCCFF',
        pointHoverBg:'#FFFFFF',
        gradientOne:'#C8B1FF',
        gradientTwo:'#E4DBF7',
        gradientThree:'#FFFFFF'
    }
    
    const chartElements = {
        chart:'#leadsConversionGraph',
        chartId:'leadsConversionGraph',
        headingText:'#af2_date-text',
        month:'#monthSelect',
        year:'#yearSelect',
    }

    const kpisElements = {
        impressions:'.kpis-impressions',
        leads:'.kpis-leads',
        conversionrate:'.kpis-conversionrate',
        impressionfactor:'.kpis-impressionfactor'
    }
    
    if( parseInt(af2_menu_components_object.dark_mode) ){
        themeColors.tooltipBg='#333333';
        themeColors.tooltipTitle='#FFFFFF';
        themeColors.tooltipLabel='#FFFFFF';
        themeColors.tooltipBorder='#6A30F5';
        themeColors.pointHoverBg='#FFFFFF';
    }
    
    const labels = [], values = [];
    const ctx = document.getElementById(chartElements.chartId).getContext("2d");
    
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, themeColors.gradientOne);
    gradient.addColorStop(0.5, themeColors.gradientOne);
    gradient.addColorStop(0.7, themeColors.gradientTwo);
    gradient.addColorStop(1, themeColors.gradientThree);
    
    const data = {
        labels: labels,
        datasets: [{
            data: values,
            fill: true,
            backgroundColor:gradient,       
            borderColor: themeColors.border,
            borderWidth: 2,
            tension: 0.3,
            pointRadius: 0,
            hitRadius: 30,
            pointStyle: 'circle',
            pointBorderWidth: 2,
            pointBorderColor: themeColors.border,
            pointHighlightFill: themeColors.border,
            pointHoverBackgroundColor: themeColors.pointHoverBg,
            pointHoverRadius: 8,
            pointHoverBorderWidth: 3        
        }]
    };
    
    const chartPlugin = [{
        afterDatasetsDraw: chart => {
            if (chart.tooltip?._active?.length) {
                let x = chart.tooltip._active[0].element.x;
                let yValue = chart.tooltip._active[0].element.y;
                let yAxis = chart.scales.y;
                let ctx = chart.ctx;
                ctx.save();
                ctx.beginPath();
                ctx.moveTo(x, (yValue + 5));
                ctx.lineTo(x, yAxis.bottom);
                ctx.lineWidth = 1;
                ctx.strokeStyle = themeColors.border;
                ctx.stroke();
                ctx.restore();
            }
        }
      }];
    
    const config = {
        type: 'line',
        data: data,
        plugins : chartPlugin,
        options:{
            responsive: true,
            plugins:{
                legend:{
                    display:false
                },
                tooltip:{
                    intersect : true,
                    backgroundColor:themeColors.tooltipBg,
                    titleColor:themeColors.tooltipTitle,
                    titleAlign:'center',
                    bodyAlign:'center',
                    caretPadding: 15,
                    boxWidth:0,
                    boxHeight:0,
                    xAlign:'center',
                    yAlign:'top',
                    borderColor:themeColors.tooltipBorder,
                    borderWidth:1,
                    bodyFont:{
                        size:12,
                        weight:'bold'
                    },
                    titleFont:{
                        size:16
                    },
                    padding: {
                        top: 10,
                        bottom:10,
                        right:20,                    
                        left:20
                    },
                    callbacks: {
                        label:function(tooltipItem, data){
                            return tooltipItem.label;
                        },
                        title: function(tooltipItem, data) {
                            return tooltipItem[0].formattedValue;
                        },
                        labelTextColor: function(context) {
                            return themeColors.tooltipLabel;
                        }
                    }
                },            
                scales: {
                    ticks:{
                        fontColor:themeColors.ticksColor
                    }
                }
            },
            scales:{
                y:{
                    min:0,
                    ticks:{
                        color:'#666',
                        display:true
                    }
                },
                x:{
                    align:'start',
                    ticks:{
                        color:'#666',
                        display:true
                    }
                }
            },
            maintainAspectRatio: true,
        },
        
    };
    
    if( parseInt(af2_menu_components_object.dark_mode) ){
        config.options.scales.y.ticks.color = '#fff';
        config.options.scales.x.ticks.color = '#fff';
    }
    
   
    
})();
