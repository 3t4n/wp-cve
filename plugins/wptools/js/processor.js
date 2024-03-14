/**
 * @ Author: Bill Minozzi
 * */
 jQuery(document).ready(function ($) {

     // console.log('ok-22');


     var cpuTotalArray = ['0'];
     var cpuUsedArray = ['0'];
    function wptools_processor_run() {
      // console.log('ok-23');
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    'action': 'wptools_get_ajax_data'
                },
                success: function (data) {
                    // console.log(data);
                     try {
                        var myjson = JSON.parse(data);
                      } catch (error) {
                         // console.error(error);
                        return;
                      }
                    //  console.log(myjson);


                  // console.log( myjson['proc_stat_cpu_total']);

                  // console.log( myjson['proc_stat_cpu_usage']);

                  if(typeof myjson['proc_stat_cpu_total'] == 'number' && typeof myjson['proc_stat_cpu_usage'] == 'number' ) {
                
                    cpuTotalArray.unshift(myjson['proc_stat_cpu_total']); //calculate and update the cpu usage
                    cpuUsedArray.unshift(myjson['proc_stat_cpu_usage']);
                    var cpuTotalDiff = (cpuTotalArray[0] - cpuTotalArray[1]);
                    var cpuUsedDiff = (cpuUsedArray[0] - cpuUsedArray[1]);
                    var load = ((cpuUsedDiff/cpuTotalDiff) * 100).toFixed(0);
                    if(load != 'NaN'){
                        $("#wptools_proc_load").html(load+'%');
                    }


                    if(typeof wpt_chart !== "undefined"){
                      wpt_chart.append(new Date().getTime(), ((cpuUsedDiff/cpuTotalDiff) * 100).toFixed(1) );
                     // wpt_chart.append(new Date().getTime(), Math.wpt_chart() * 10000);
                    }
                 


                  }
                  else{
                    jQuery( "#cpu_history" ).hide();
                    jQuery( "#wpt_chart").hide();
                  }


                if(typeof myjson['load_1'] != 'undefined' && typeof myjson['load_1'] != null ){
                  //console.log(typeof myjson['load_1']);
                  // if(typeof document.getElementById("load_1") != null ) {
                  if (typeof document.getElementById("load_1") !== 'undefined' && document.getElementById("load_1")) {
                    document.getElementById("load_1").innerHTML = myjson['load_1'];
                    document.getElementById("load_5").innerHTML = myjson['load_5'];
                    document.getElementById("load_15").innerHTML = myjson['load_15'];
                  }

                }
                else{

                  document.getElementById("load_1").innerHTML = "Blocked by your hosting";
                  jQuery( "#cpu_history" ).hide();

                }

                // console.log(typeof myjson['cores'] );

                if(typeof myjson['cores'] != 'undefined' && typeof myjson['cores'] != null && typeof myjson['cores'] == 'number' ){
                  if (typeof document.getElementById("cores") !== 'undefined' && document.getElementById("cores")) {
                    document.getElementById("cores").innerHTML = myjson['cores'];
                  }
                }
                else{
                  // jQuery( "#cores_label" ).hide();
                  // Cannot set properties of null
                  //if(typeof document.getElementById("cores") != null){
                  // if( document.getElementById("cores") !== null ){ 

                  if (typeof document.getElementById("cores") !== 'undefined' && document.getElementById("cores"))				{ 
                    document.getElementById("cores").innerHTML = "Look Tab Server Check Requirements";
                  }
 
                }

     

                },
                error: function (xhr, status, error) {
                    console.log('Ajax Error Get Processor: '+error);
                    console.log('Status: '+status);
                    console.log('Error Status Code: '+xhr.status);
                },
                timeout: 5000
            });
    }

        setInterval(wptools_processor_run, '5000');
        wptools_processor_run();


          if (typeof TimeSeries === 'function') {
              var wpt_chart = new TimeSeries();
          }
          else{
            return;
          }



          

          if (    !(typeof(SmoothieChart)=='undefined')   ) {
              var chart_wpt = new SmoothieChart({
                verticalSections:6,
                labels:{fontSize:14},
                millisPerPixel:40,
                millisPerLine:10000,
                responsive: true,
                maxValue:100,
                minValue: 0
              });
          }
          else
          {
            return;
          }

          if(typeof document.getElementById("wpt_chart") !== 'object') {
            return;
          }

           chart_wpt.addTimeSeries(wpt_chart,
              { strokeStyle:'rgb(0, 255, 0)', 
              fillStyle:'rgba(0, 255, 0, 0.4)', 
              lineWidth:3
              });
                  
           
           chart_wpt.streamTo(document.getElementById("wpt_chart"),0);
            


});
