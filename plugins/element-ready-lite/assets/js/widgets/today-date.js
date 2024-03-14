(function($) {

    /*------------------------------
            Date Widget
        -------------------------------*/
        var Elements_Ready_Theme_Today_Date = function($scope, $) {
                
                var $container = $scope.find('.er-clock-date-wrapper');
                var enable     = $container.attr('data-enable');
                var ampm       = $container.attr('data-ampm');
                var second     = $container.attr('data-second');
                var is_editor     = $container.attr('data-is_editor');
                var timer_sec = is_editor == 'yes'? 4000: 1000;
                if(enable == 'yes'){
                    var time = setInterval(function(){ 
                        var today = new Date();
                        var hr = today.getHours();
                        var min = today.getMinutes();
                        var sec = today.getSeconds();
                        ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
                        hr = (hr == 0) ? 12 : hr;
                        hr = (hr > 12) ? hr - 12 : hr;
                        //Add a zero in front of numbers<10
                        hr  = Elements_Ready_check_Time(hr);
                        min = Elements_Ready_check_Time(min);
                        sec = Elements_Ready_check_Time(sec);

                        var html_content = hr + ":" + min + ":" + sec + " " + ap;
                        if( ampm=='yes' ){

                            if( second == 'yes' ){
                                html_content = hr + ":" + min + ":" + sec + " " + ap;
                            }else{
                                html_content = hr + ":" + min + " " + ap;
                            }
                            
                        }else{

                            if( second == 'yes' ){
                                html_content = hr + ":" + min + ":" + sec;
                            }else{
                                html_content = hr + ":" + min;
                            }
                            
                        }
                       $scope.find(".er-clock").html(html_content);
                    
                     }, timer_sec);
                }
               
           
        }
        function Elements_Ready_check_Time(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
     
        $(window).on('elementor/frontend/init', function() {
            elementorFrontend.hooks.addAction('frontend/element_ready/Elements_Ready_Theme_Today_Date.default', Elements_Ready_Theme_Today_Date);
        });
  
    })(jQuery);