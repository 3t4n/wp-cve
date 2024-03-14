/*  Local Time for Analog Clock  */
function rClock(){
    clock();
    setInterval(clock, 1000);
}

function clock(){
    var date= new Date();
    var time=[date.getHours(), date.getMinutes(), date.getSeconds()];

    var clockDivs=[document.querySelector('.h-hand'), document.querySelector('.m-hand'),document.querySelector('.s-hand')];

    var hour=time[1]/2+time[0]*30;

    clockDivs[0].style.transform="rotate("+hour +"deg)";
    clockDivs[1].style.transform="rotate("+ time[1]*6 +"deg)";
    clockDivs[2].style.transform="rotate("+ time[2]*6 +"deg)";

}

/* Custom Time Zone for Analog Clock */
function rtClock(){
    clock1();
    setInterval(clock1, 1000);
}

function clock1(){
    var now= new Date();
    var clockDivs=[document.querySelector('.h-hand'), document.querySelector('.m-hand'),document.querySelector('.s-hand')];
    
    function calcH(offset) {
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var nd = new Date(utc + (3600000*offset));
        var gh = nd.getHours();
        return gh;
    }

    function calcM(offset) {
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var nd = new Date(utc + (3600000*offset));
        var gm = nd.getMinutes();
        return gm;
    }

    function calcS(offset) {
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var nd = new Date(utc + (3600000*offset));
        var gs = nd.getSeconds();
        return gs;
    }

    //data from settings menu
    if(typeof(data) !== "undefined") {
        if(data !== null){
            var hrs=(calcH(data));
            var mins=(calcM(data));
            var secs=(calcS(data));

            var hour=mins/2+hrs*30;

            clockDivs[0].style.transform="rotate("+hour +"deg)";
            clockDivs[1].style.transform="rotate("+ mins*6 +"deg)";
            clockDivs[2].style.transform="rotate("+ secs*6 +"deg)";
        }
    }

    //data from shortcode
    if(typeof(datas) !== "undefined") {
        if(datas !== null){
            var hrs=(calcH(datas));
            var mins=(calcM(datas));
            var secs=(calcS(datas));

            var hour=mins/2+hrs*30;
  
            clockDivs[0].style.transform="rotate("+hour +"deg)";
            clockDivs[1].style.transform="rotate("+ mins*6 +"deg)";
            clockDivs[2].style.transform="rotate("+ secs*6 +"deg)";
        }
    }
}


/*  Local Time for Digital Clock  */
function dClock(){
    clock2();
    setInterval(clock2, 500);
}

function clock2() {

    const hours = document.querySelector('.hours');
    const minutes = document.querySelector('.minutes');
    const seconds = document.querySelector('.seconds');
    const ss = document.querySelector('.session');
    const w = document.querySelector('.week');
    
    var now = new Date(),
        hrs = now.getHours(),
        mins = now.getMinutes(),
        secs = now.getSeconds();
        session = "AM";
        day = now.getDay();
        week = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];

        w.innerHTML = week[day];

    if(typeof(timeformat) !== "undefined") {
        if(timeformat !== null){
            //12hr format
            if (timeformat === 'f1'){
                if (hrs > 12) {
                    hrss = hrs - 12;
                    if (hrss < 10) {
                        hours.innerHTML = '0' + hrss;
                    }
                    else{
                        hours.innerHTML = hrss;
                    }
                    session = "PM";
                    ss.innerHTML = session;

                } 
                else if(hrs == 12){
                    hours.innerHTML = hrs;
                    session = "PM";
                    ss.innerHTML = session;
                }
                else {
                    hours.innerHTML = hrs;
                    session = "AM";
                    ss.innerHTML = session;
                }

                if (hrs < 10) {
                    if (hrs == 0){
                        hours.innerHTML = 12;
                    }
                    else{
                        hours.innerHTML = '0'+ hrs; 
                    }
                }
 
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
            }
            //24hr format
            else if (timeformat === 'f2'){
                if (hrs < 10) {
                    hours.innerHTML = '0'+ hrs; 
                }else{
                    hours.innerHTML = hrs;
                }
        
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
                session = "";
                ss.innerHTML = session;
            }
        }
    }else{
        if (hrs > 12) {
                    hrss = hrs - 12;
                    if (hrss < 10) {
                        hours.innerHTML = '0' + hrss;
                    }
                    else{
                        hours.innerHTML = hrss;
                    }
                    session = "PM";
                    ss.innerHTML = session;

                } 
                else if(hrs == 12){
                    hours.innerHTML = hrs;
                    session = "PM";
                    ss.innerHTML = session;
                }
                else {
                    hours.innerHTML = hrs;
                    session = "AM";
                    ss.innerHTML = session;
                }

                if (hrs < 10) {
                    if (hrs == 0){
                        hours.innerHTML = 12;
                    }
                    else{
                        hours.innerHTML = '0'+ hrs; 
                    }
                }
 
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
    }

}

//Custom Time Zone for Digital Clock
function dtClock(){
    clock3();
    setInterval(clock3, 500);
}

function clock3() {
    
    const hours = document.querySelector('.hours');
    const minutes = document.querySelector('.minutes');
    const seconds = document.querySelector('.seconds');
    const ss = document.querySelector('.session');
    const w = document.querySelector('.week');

    var now = new Date(),
        session = "AM";

    function calcH(offset) {
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var nd = new Date(utc + (3600000*offset));
        var gh = nd.getHours();
        return gh;
    }

    function calcM(offset) {
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var nd = new Date(utc + (3600000*offset));
        var gm = nd.getMinutes();
        return gm;
}

    function calcS(offset) {
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var nd = new Date(utc + (3600000*offset));
        var gs = nd.getSeconds();
        return gs;
}

    function calcW(offset) {
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var nd = new Date(utc + (3600000*offset));
        var gd = nd.getDay();
        var week = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        var gw = week[gd];
        return gw;
    }

    //data from settings menu
    if(typeof(data) !== "undefined") {
        if(data !== null){
            var hrs = (calcH(data));
            var mins = (calcM(data));
            var secs = (calcS(data));
            var dd = (calcW(data));
            w.innerHTML = dd;

    if(typeof(timeformat) !== "undefined") {
        if(timeformat !== null){

            //12hr format
            if (timeformat === 'f1'){
                if (hrs > 12) {
                    hrss = hrs - 12;
                    if (hrss < 10) {
                        hours.innerHTML = '0' + hrss;
                    }
                    else{
                        hours.innerHTML = hrss;
                    }
                    session = "PM";
                    ss.innerHTML = session;

                } 
                else if(hrs == 12){
                    hours.innerHTML = hrs;
                    session = "PM";
                    ss.innerHTML = session;
                }
                else {
                    hours.innerHTML = hrs;
                    session = "AM";
                    ss.innerHTML = session;
                }

                if (hrs < 10) {
                    if (hrs == 0){
                        hours.innerHTML = 12;
                    }
                    else{
                        hours.innerHTML = '0'+ hrs; 
                    }
                }
 
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
            }
            //24hr format
            else if (timeformat === 'f2'){
                if (hrs < 10) {
                    hours.innerHTML = '0'+ hrs; 
                }else{
                    hours.innerHTML = hrs;
                }
        
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
                session = "";
                ss.innerHTML = session;
            }
        }
    }else{
        if (hrs > 12) {
                    hrss = hrs - 12;
                    if (hrss < 10) {
                        hours.innerHTML = '0' + hrss;
                    }
                    else{
                        hours.innerHTML = hrss;
                    }
                    session = "PM";
                    ss.innerHTML = session;

                } 
                else if(hrs == 12){
                    hours.innerHTML = hrs;
                    session = "PM";
                    ss.innerHTML = session;
                }
                else {
                    hours.innerHTML = hrs;
                    session = "AM";
                    ss.innerHTML = session;
                }

                if (hrs < 10) {
                    if (hrs == 0){
                        hours.innerHTML = 12;
                    }
                    else{
                        hours.innerHTML = '0'+ hrs; 
                    }
                }
 
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
    }
    } 
    }
    //data from shortcode
    if(typeof(datas) !== "undefined") {
        if(datas !== null){
            var hrs = (calcH(datas));
            var mins = (calcM(datas));
            var secs = (calcS(datas));
            var dd = (calcW(datas));
            w.innerHTML = dd;
        
    if(typeof(timeformat) !== "undefined") {
        if(timeformat !== null){
            //12hr format
            if (timeformat === 'f1'){
                if (hrs > 12) {
                    hrss = hrs - 12;
                    if (hrss < 10) {
                        hours.innerHTML = '0' + hrss;
                    }
                    else{
                        hours.innerHTML = hrss;
                    }
                    session = "PM";
                    ss.innerHTML = session;

                } 
                else if(hrs == 12){
                    hours.innerHTML = hrs;
                    session = "PM";
                    ss.innerHTML = session;
                }
                else {
                    hours.innerHTML = hrs;
                    session = "AM";
                    ss.innerHTML = session;
                }

                if (hrs < 10) {
                    if (hrs == 0){
                        hours.innerHTML = 12;
                    }
                    else{
                        hours.innerHTML = '0'+ hrs; 
                    }
                }
 
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
            }
            //24hr format
            else if (timeformat === 'f2'){
                if (hrs < 10) {
                    hours.innerHTML = '0'+ hrs; 
                }else{
                    hours.innerHTML = hrs;
                }
        
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
                session = "";
                ss.innerHTML = session;
            }
        }
    }else{
        if (hrs > 12) {
                    hrss = hrs - 12;
                    if (hrss < 10) {
                        hours.innerHTML = '0' + hrss;
                    }
                    else{
                        hours.innerHTML = hrss;
                    }
                    session = "PM";
                    ss.innerHTML = session;

                } 
                else if(hrs == 12){
                    hours.innerHTML = hrs;
                    session = "PM";
                    ss.innerHTML = session;
                }
                else {
                    hours.innerHTML = hrs;
                    session = "AM";
                    ss.innerHTML = session;
                }

                if (hrs < 10) {
                    if (hrs == 0){
                        hours.innerHTML = 12;
                    }
                    else{
                        hours.innerHTML = '0'+ hrs; 
                    }
                }
 
                if (mins < 10) {
                    minutes.innerHTML = '0' + mins;
                } else {
                    minutes.innerHTML = mins;
                }
        
                if (secs < 10) {
                    seconds.innerHTML = '0' + secs;
                } else {
                    seconds.innerHTML = secs;
                }
    }
    }
    }
}

jQuery(document).ready(function($){
    $('#download').on('click', function(e){
        $('#ip2location_db_table').submit();
        e.preventDefault();

        if ($('#database_name').val().length == 0 || $('#token').val().length == 0){
            $('#download_status').html('<div id="message" class="error"><p><strong>ERROR</strong>: Please make sure you have entered the login credential.</p></div>');
            return;
        }

        $('#download_status').html('');
        $('#database_name,#token,#download').prop('disabled', true);
        $('#ip2location-download-progress').show();

        $.post(ajaxurl, { action: 'update_ip2location_world_clock_database', database: $('#database_name').val(), token: $('#token').val() }, function(response) {
            if (response == 'SUCCESS') {
                alert('Download completed.');

                $('#download_status').html('<div id="message" class="updated"><p>Successfully downloaded the ' + $('#database_name :selected').text() + ' BIN database. Please refresh information by <a href="javascript:;" id="reload">reloading</a> the page.</p></div>');

                $('#reload').on('click', function(){
                    window.location = window.location.href.split('#')[0];
                });
            }
            else {
                alert('Download process aborted.');

                $('#download_status').html('<div id="message" class="error"><p><strong>ERROR</strong>: Failed to download ' + $('#database_name :selected').text() + ' BIN database. Please make sure you enter the login credential with permission correctly.</p></div>');
            }
        }).always(function() {
            $('#database_name').val('');
            
            $('#database_name,#token,#download').prop('disabled', false);
            $('#ip2location-download-progress').hide();
        });
    });
});