jQuery(document).ready(function($) {

    // Edit Timestamp Toggle
    $(".edit-timestamp").click(function(e) {
        $(".expiry-date-fields").slideDown('fast');  
        $(this).css('visibility','hidden');
        $(".cancel-timestamp").css('visibility','visible');
    });
 
    // Cancel Timestamp Toggle
    $(".cancel-timestamp").click(function(e) {
        e.preventDefault();
        $(".expiry-date-fields").slideUp('fast');  
        $(this).css('visibility','hidden');
        $(".edit-timestamp").css('visibility','visible');
    });
 
    // specific script element 
    $('.r8_tsm_page_select').select2({
        data: tsm_data,
        placeholder: 'Select page(s) or post(s) where the script should be output',
        width: '400'
    });

    // active/inactive status
    var previousSelectedStatus = null;
    $("#r8_tsm_script_active .inside").hover(
        function() {
            previousSelectedStatus =  $("input[name=r8_tsm_active]:checked").val(); 
        }, function() { 
            previousSelectedStatus = null;
        }
      );
    $('input[type=radio][name=r8_tsm_active]').click(function(){ 
        if (previousSelectedStatus == 'active') {  
            if (this.value == 'inactive') {  
                let previousSelectedVal =  $("input[name=r8_tsm_script_expiry]:checked").val(); 
                if( previousSelectedVal == 'Schedule' ){
                    var r = confirm('If you Inactive this script the Schedule will be set to "Never expires". Active Scheduled script can\'t be inactive.' );
                    if( r == true ){
                        $("input[name=r8_tsm_script_expiry]").each(function() {  
                            if( $(this).val() == 'Schedule' ){ 
                                $('#expire_date_type_never').prop( "checked", true );
                                $(".schedule-row").addClass('hidden');
                                $('.expiration-status strong').text('Never expires'); 
                            } 
                        });
                    }else{  
                        return false;
                    }
                }
            }
        }
       if (previousSelectedStatus == 'inactive') {
            if (this.value == 'active') {
                let previousSelectedVal =  $("input[name=r8_tsm_script_expiry]:checked").val(); 
                if( previousSelectedVal == 'Schedule' ){
                    let start = $("#schedule-start").datepicker("getDate");
                    let end   = $("#schedule-end").datepicker("getDate"); 
                    let todayDate = new Date(); 
                    let formatTodayDate = (todayDate.getMonth()+1) + "/" + todayDate.getDate() + "/" + todayDate.getFullYear();
                    let currentDate = new Date(start.getTime());
                    let between = [];
                    while (currentDate <= end) {
                        let d = new Date(currentDate);
                        let strDate = (d.getMonth()+1) + "/" + d.getDate() + "/" + d.getFullYear();
                        between.push(strDate);
                        currentDate.setDate(currentDate.getDate() + 1);
                    }
                    let status = false;
                    for (var i = 0; i < between.length; i++) { 
                        if (between[i] == formatTodayDate) { 
                            status = true;
                            break;
                        }
                    }
                    if( status == false ){ 
                        var r = confirm('If you Active this script the Schedule will be set to "Never expires". Inative Scheduled script can\'t be active.' );
                        if( r == true ){
                            $("input[name=r8_tsm_script_expiry]").each(function() {  
                                if( $(this).val() == 'Schedule' ){ 
                                    $('#expire_date_type_never').prop( "checked", true );
                                    $(".schedule-row").addClass('hidden');
                                    $('.expiration-status strong').text('Never expires'); 
                                } 
                            });
                        }else{  
                            return false;
                        }
                    }
                }
            }
       }
    });
 
    // schedule switcher
    $('input[type=radio][name=r8_tsm_script_expiry]').change(function() {
        if (this.value == 'Never') {
            $(".schedule-row").addClass('hidden'); 
        }
        if (this.value == 'Schedule') {
            $(".schedule-row").removeClass('hidden'); 
        }
    });
    $("#schedule-start").datepicker({
        dateFormat : "M d, yy",
        minDate: 0,
        onClose: function (selectedDate) {
            $("#schedule-end").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#schedule-end").datepicker({
        dateFormat : "M d, yy", 
        minDate: 0 
    }); 

    // set schedule 
    $(".button.schedule").click(function(e) {
        e.preventDefault();  
        let pass = true;
        $("input[type=radio][name=r8_tsm_script_expiry]").each(function() { 
            if( $(this).is(":checked") ){
                if( $(this).val() == 'Never' ){ 
                    $('.expiration-status strong').text($(this).data('title'));
                } 
                if( $(this).val() == 'Schedule' ){ 
                    let status = `Scheduled ${$("#schedule-start").val()} to ${$("#schedule-end").val()}`; 
                    start = $("#schedule-start").val(); 
                    end = $("#schedule-end").val(); 
                    var startDate = new Date(start); 
                    var endDate = new Date(end);  
                    if (startDate <= endDate) { 
                        $('.expiration-status strong').text(status); 
                        $(".schedule-row .err-msg").addClass('hidden');   
                    } else {
                        pass = false;
                        $(".schedule-row .err-msg").text('Start date must be less than of End date.'); 
                        $(".schedule-row .err-msg").removeClass('hidden');   
                    }
                }
                if( pass ){
                    $(".expiry-date-fields").slideUp('fast'); 
                    $(".edit-timestamp").css('visibility','visible');  
                }              
            }
        });
    }); 

});
