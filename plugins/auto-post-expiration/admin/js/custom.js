/* global wp, jQuery */
jQuery(document).ready(function(){
//jQuery('#expiration_date').datetimepicker();
/*var dateToday = new Date();
jQuery('#expire_date').datetimepicker({
format:'Y-m-d H:i',
hours12:true,
step: 5,
minDate: dateToday,
ampm: true, // FOR AM/PM FORMAT
minTime: 0,*/

    var dateToday = new Date();
    jQuery('#expire_date').datetimepicker({
        datepicker: 'Y-m-d',
        formatTime:"H:i",
        format:'Y-m-d H:i',
        step: 5,
        minDate: dateToday,
        //minTime: 0,

    });

});