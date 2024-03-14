/**

 * @package 
 * @version 1.2.4
 * @author Jason Lau
 * @link http://jasonlau.biz
 * @copyright 2011-2013
 * @license GNU/GPL 3+
 * @uses WordPress

Plugin Name: Visitor Maps Extended Referer
Plugin URI: http://jasonlau.biz
Description: This WordPress plugin extends and depends on the <a href="http://www.642weather.com/weather/scripts-wordpress-visitor-maps.php" target="_blank">Visitor Maps and Who's Online</a> plugin by <a title="Visit author homepage" href="http://www.642weather.com/weather/scripts.php" target="_blank">Mike Challis</a>. This plugin alters the <em>Referer</em> column for the Admin's <em>Who's Been Online</em> grid, so it displays the referring host name followed by the search string, if there is one. It also prevents long URLs from expanding the grid too wide for viewing. Additionally, you can ban and unban visitor IP addresses using a toggle button. Banned IP addresses are added to .htaccess, which is automatically backed-up before any changes are made. There are no settings required.
Author: Jason Lau
Version: 1.2.4
Author URI: http://jasonlau.biz
*/

jQuery(function($){

    var LINK_MAX_WIDTH = 400,

    getHostname = function(str) {
        var re, hn = '', t = str.split('file:');
        if(!t[1]){
           re = new RegExp('^(?:f|ht)tp(?:s)?\://([^/]+)', 'im');
           try{hn = str.match(re)[1].toString();}catch(e){}
           if(hn == ''){
            hn = 'Bot';
           }          
           return hn; 
        } else {
           return 'localhost'; 
        }        
    },

    getUrlVars = function(original_link){
        var vars = {};
        var parts = original_link.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value){
            vars[key] = value;
        });
        return vars;
    },
    
    base64_encode = function(data){

    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = ac = 0, enc="", tmp_arr = [];

    if (!data) {
        return data;
    }

    data = utf8_encode(data+'');
    
    do {
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1<<16 | o2<<8 | o3;

        h1 = bits>>18 & 0x3f;
        h2 = bits>>12 & 0x3f;
        h3 = bits>>6 & 0x3f;
        h4 = bits & 0x3f;

        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);
    
    enc = tmp_arr.join('');
    
    switch( data.length % 3 ){
        case 1:
            enc = enc.slice(0, -2) + '==';
        break;
        case 2:
            enc = enc.slice(0, -1) + '=';
        break;
    }

    return enc;
    },
    
    utf8_encode = function(argString) {
        
    var string = (argString+''),
    utftext = "",
    start, end,
    stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;
        }
    }

    if (end > start) {
        utftext += string.substring(start, string.length);
    }

    return utftext;
    },
    
    activate_grid = function() {
        $("font[color='green'] a").each(function(){       
        var original_link = $(this).attr("href");
        var q = getUrlVars(original_link)["q"];
        if(!q && getUrlVars(original_link)["p"]){
            q = getUrlVars(original_link)["p"];
        }

        if(original_link){
            var host = getHostname(original_link).replace(/www./gi, "");
            if(q){
                var original_query = q;
                q = q.replace(/%20|%2B|\+/gi, " ").replace(/%3E|%3C/gi,"").toLowerCase();
                
                $(this).html(host);
                $(this).after(' <a style="color: green; text-decoration: none;border-bottom: 1px dashed green;" href="https://www.google.com/#hl=en&safe=on&output=search&sclient=psy-ab&q=' + original_query + '" target="_blank" title="Search Google for ' + unescape(q) + '">' + unescape(q) + '</a>');
            } else {
                $(this).html(host);
            }
            
            // Proxify original link
            $(this).attr({"href" : "http://2ho.me/?link=" + base64_encode(original_link), "title" : original_link});
            
            if(host == 'localhost'){
                $(this).replaceWith('<span title="' + original_link + '" style="background-color:#D4D4D4;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;cursor:pointer;">X</span> <span style="color: green; text-decoration: none;border-bottom: 1px dashed green;" title="' + original_link + '">' + host + '</span>');
            }
            
            if(vmerf_htaccess){
                // enable referer banning
               if($.inArray(host, vmerf_banned_referers) != -1 && host != vmerf_admin_host && host != 'localhost'){
                    $(this).before('<a class="vmerf_ban_referers_toggle" data-referer="' + host + '" data-mode="unban referer" title="Unban this referer" href="javascript:void(0);" style="background-color:#B30000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</a> ');
                } else if(host != vmerf_admin_host && host != 'localhost') {
                    $(this).before('<a class="vmerf_ban_referers_toggle" data-referer="' + host + '" data-mode="ban referer" title="Ban this referer" href="javascript:void(0);" style="background-color:#008000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</a> ');
                } else {
                    $(this).before('<span title="Home" style="background-color:#D4D4D4;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;cursor:pointer;">X</span> ');
                } 
            }
                       
        }                
    });
    
    $('.table-top td:contains("' + vmerf_referer_text + '")').append(" & Search String");    
    $(".column-dark td:contains(a), .column-light td:contains(a)").each(function(){
        $(this).wrapInner('<div style="max-width:' + LINK_MAX_WIDTH + 'px; overflow:hidden; padding:0px; margin:0px"></div>');
    });
    
    if(vmerf_htaccess){
        // enable ip banning
    $('a[href*="http://www.ip-adress.com/ip_tracer/"]').each(function(){
        var ip = $(this).attr("href").split("http://www.ip-adress.com/ip_tracer/");
        if(($.inArray(ip[1], vmerf_banned_ips) != -1 || (ip[1] == vmerf_ip && vmerf_mode != 'unban')) && (ip[1] != vmerf_admin_ip)){
           $(this).before('<a class="vmerf_ban_toggle" data-ip="'+ip[1]+'" data-mode="unban" title="Unban this IP address" href="javascript:void(0);" style="background-color:#B30000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</a> '); 
        } else if(ip[1] != vmerf_admin_ip) {
            $(this).before('<a class="vmerf_ban_toggle" data-ip="'+ip[1]+'" data-mode="ban" title="Ban this IP address" href="javascript:void(0);" style="background-color:#008000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</a> ');
        } else {
            $(this).before('<span title="Admin" style="background-color:#D4D4D4;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;cursor:pointer;">A</span> ');
        }
        
    });
    }
    $(".vmerf_ban_toggle").on('click', function(){
        if($(this).hasClass('vmerf_purge')){
            var c = confirm('Are you sure you want to unban ALL of the IP addresses?');
            if(!c){
                return false;
            }
        }
        if($(this).hasClass('add_ip')){
            $("#vmerf_form input#vmerf_mode").val("ban");
            $("#vmerf_form input#vmerf_ip").val($(".vmerf_ip_to_add").val());
        } else {
            $("#vmerf_form input#vmerf_mode").val($(this).data('mode'));
            $("#vmerf_form input#vmerf_ip").val($(this).data('ip'));   
        }
        if($("#vmerf_form input#vmerf_ip").val() && $("#vmerf_form input#vmerf_ip").val() != ""){ 
           $("#vmerf_form").submit(); 
        } else {
            alert('Error: No IP address was entered!');
        }       
    });
    
    $(".vmerf_ban_referers_toggle").on('click', function(){
        if($(this).hasClass('vmerf_purge_referers')){
            var c = confirm('Are you sure you want to unban ALL of the referers?');
            if(!c){
                return false;
            } else{
                $("#vmerf_form input#vmerf_mode").val("purge referers");
                $("#vmerf_form input#vmerf_referer").val("purge referers");
            }
        } else {
            if($(this).hasClass('add_referer')){
            $("#vmerf_form input#vmerf_mode").val("ban referer");
            $("#vmerf_form input#vmerf_referer").val($(".vmerf_referer_to_add").val()); 
        } else {
            $("#vmerf_form input#vmerf_mode").val($(this).data('mode'));
            $("#vmerf_form input#vmerf_referer").val($(this).data('referer'));  
        }
        }
        
        if($("#vmerf_form input#vmerf_referer").val() && $("#vmerf_form input#vmerf_referer").val() != ""){
           $("#vmerf_form").submit(); 
        } else {
            alert('Error: No referer was entered!');
        }       
    });
    
    }, // activate
    
    i = 1;
    
    $("div.wrap table").each(function(){
       $(this).addClass('vmerf-table-'+i); 
       i++;
    });
    
    i = 1;
    
    $("div.wrap tr").each(function(){
       $(this).addClass('vmerf-tr-'+i); 
       i++;
    });
    
    i = 1;
    
    $("div.wrap td").each(function(){
       $(this).addClass('vmerf-td-'+i); 
       i++;
    });
    
    i = 1;
    
    $("div.wrap a").each(function(){
       $(this).addClass('vmerf-a-'+i); 
       i++;
    });
               
    $(".vmerf-table-1").after('<form id="vmerf_form" action="' + window.location.href + '" method="post"><input type="hidden" name="vmerf_mode" id="vmerf_mode" value="" /><input type="hidden" name="vmerf_ip" id="vmerf_ip" value="" /><input type="hidden" name="vmerf_referer" id="vmerf_referer" value="" /></form>');  

    if(vmerf_message){
        $("#vmerf_form").after('<div class="updated" style="padding:10px 4px">'+vmerf_message+'</div>');
        if(!vmerf_htaccess_warning){
            $(".updated").delay(5000).hide('slow');
        }
    }
    var editor_html = '';
    editor_html += '<br /><a href="#" class="vmerf-auto-update-form-container-toggle" title="Toggle to view or edit auto-update settings">+ Automatic Update</a> ('
    if(!vmerf_auto_update){
        editor_html += 'Off';
    } else {
        editor_html += 'On';
    }
    editor_html += ') <div class="vmerf-auto-update-form-container" style="padding:10px 4px;display:none;"><form id="vmerf_auto_update_form" action="' + window.location.href + '" method="post"><input type="hidden" name="vmerf_mode" id="vmerf_mode" value="set auto update" />';   
    editor_html += 'Auto-update <select title="Automatically refresh the Who\'s Been Online grid?" size="1" name="vmerf_auto_update" id="vmerf_auto_update"><option value="false"';
    if(!vmerf_auto_update){
        editor_html += ' selected="selected"';
    }
    editor_html += '>Off</option><option value="true"';
    if(vmerf_auto_update){
        editor_html += ' selected="selected"';
    }
    editor_html += '>On</option></select><br />Refresh grid every <input type="number" min="1" value="'+vmerf_auto_update_time+'" name="vmerf_auto_update_time" style="width:50px;" title="Enter a number no less than 1" required="required" /> minute(s).<br /><button type="submit" class="vmerf_auto_update button-primary" data-mode="set auto update" title="Update settings"  style="cursor:pointer;">Update Settings</button><span class="vmerf_refresh_button button-secondary" title="Refresh the Who\'s Been Online grid now." style="cursor:pointer;">Refresh Now</span></form><br />Progress <div class="vmerf_refresh_status" title="Time remaining" style="width:200px;background-color:#DDDDDD;border:1px solid #C0C0C0;color:#333333;padding:1px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;"><div class="vmerf_refresh_progress" style="width:1%;padding:0px;line-height:10px;color:#F5F5F5;border-right:1px solid #333333;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;background:#075698;background:-webkit-gradient(linear, 0 0, 0 100%, from(#E9E9E9), to(#FFFFFF));background:-moz-linear-gradient(#E9E9E9, #FFFFFF);background:-o-linear-gradient(#E9E9E9, #FFFFFF);background:linear-gradient(#E9E9E9, #FFFFFF);">.</div></div></div>';
      
    if(vmerf_htaccess){
        // enable editors and key
    var num_ips = (vmerf_banned_ips[0] == '') ? 0 : vmerf_banned_ips.length;
    editor_html += '<br /><a href="#" class="vmerf-banned-ips-form-container-toggle" title="Toggle to view or edit banned IP addresses">+ Banned IP Addresses</a> (' + num_ips + ')<div class="vmerf-banned-ips-form-container" style="padding:10px 4px;display:none;">';
    if(num_ips > 0){
        editor_html += '<a class="vmerf_ban_toggle vmerf_purge" data-ip="000.000.000.000" data-mode="purge" title="Unban all IP addresses." href="javascript:void(0);" style="">Unban All</a>';
    }   
    editor_html += '<div id="vmerf_ip_form" style="width:180px; height:80px; overflow:auto; border:1px solid #C0C0C0; background-color: #EFEFEF; padding: 5px;">';   
    for(var i = 0; i < num_ips; i++){
        editor_html += '<a class="vmerf_ban_toggle" data-ip="' + vmerf_banned_ips[i] + '" data-mode="unban" title="Unban this IP address" href="javascript:void(0);" style="background-color:#B30000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</a> ' + vmerf_banned_ips[i] + '<br />';
    }
    editor_html += '</div><a class="vmerf_ban_toggle add_ip" data-ip="" data-mode="ban" title="Ban any IP address" href="javascript:void(0);" style="background-color:#008000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</a> <input type="text" value="" class="vmerf_ip_to_add" placeholder="New IP Address To Ban" style="width:174px;" /></div>';
    
    var num_referers = (vmerf_banned_referers[0] == '') ? 0 : vmerf_banned_referers.length;
    editor_html += '<br /><a href="#" class="vmerf-banned-referers-form-container-toggle" title="Toggle to view or edit banned referers">+ Banned Referers</a> (' + num_referers + ')<div class="vmerf-banned-referers-form-container" style="padding:10px 4px;display:none;">';
    if(num_referers > 0){
        editor_html += '<a class="vmerf_ban_referers_toggle vmerf_purge_referers" data-referer="" data-mode="purge referers" title="Unban all referers." href="javascript:void(0);" style="">Unban All</a>';
    }   
    editor_html += '<div id="vmerf_referers_form" style="width:180px; height:80px; overflow:auto; border:1px solid #C0C0C0; background-color: #EFEFEF; padding: 5px;">';   
    for(var i = 0; i < num_referers; i++){
        editor_html += '<a class="vmerf_ban_referers_toggle" data-referer="' + vmerf_banned_referers[i] + '" data-mode="unban referer" title="Unban this referer" href="javascript:void(0);" style="background-color:#B30000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</a> ' + vmerf_banned_referers[i] + '<br />';
    }
    editor_html += '</div><a class="vmerf_ban_referers_toggle add_referer" data-referer="" data-mode="ban referer" title="Ban any referer" href="javascript:void(0);" style="background-color:#008000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</a> <input type="text" value="" class="vmerf_referer_to_add" placeholder="New Referer To Ban" style="width:174px;" /></div>';
    
    $('a.vmerf-a-3').after(editor_html);
    
    $(".vmerf-banned-ips-form-container-toggle").click(function(){
        if($(".vmerf-banned-ips-form-container").is(":visible")){
            $(".vmerf-banned-ips-form-container").hide('slow');
            $(this).html('+ Banned IP Addresses');
        } else {
            $(".vmerf-banned-ips-form-container").show('slow');
            $(this).html('- Banned IP Addresses');
        }
    });
    
    $(".vmerf-banned-referers-form-container-toggle").click(function(){
        if($(".vmerf-banned-referers-form-container").is(":visible")){
            $(".vmerf-banned-referers-form-container").hide('slow');
            $(this).html('+ Banned Referers');
        } else {
            $(".vmerf-banned-referers-form-container").show('slow');
            $(this).html('- Banned Referers');
        }
    });
    
    $("table.vmerf-table-2").append('<tr><td><span style="background-color:#008000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</span> Ban   </td><td><span style="background-color:#B30000;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;">X</span> Unban  </td></tr><tr><td><span title="Admin" style="background-color:#D4D4D4;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;cursor:pointer;">A</span> Admin   </td><td><span title="Disabled" style="background-color:#D4D4D4;color:white;padding:0px 4px;text-decoration:none;-moz-border-radius:10px;-khtml-border-radius:10px;-webkit-border-radius:10px;border-radius:10px;cursor:pointer;">X</span> Disabled  </td></tr>')
    } else {
        $('a.vmerf-a-3').after(editor_html);
    } // if htaccess
    
    $(".vmerf-auto-update-form-container-toggle").click(function(){
        if($(".vmerf-auto-update-form-container").is(":visible")){
            $(".vmerf-auto-update-form-container").hide('slow');
            $(this).html('+ Automatic Update');
        } else {
            $(".vmerf-auto-update-form-container").show('slow');
            $(this).html('- Automatic Update');
        }
    });
    $(".vmerf_refresh_button").click(function(){
        $("#vmerf-grid-container").load(window.location.href + ' table:contains("'+ vmerf_table_selector_text +'")', function(){
            activate_grid();
            try{ console.log('Grid successfully reloaded.'); }catch(e){};
        });
    });
    
    activate_grid();
    $('.vmerf-table-3').wrap('<div id="vmerf-grid-container"></div>');
          
    var vmerf_reload_grid = function(){
        var t = Math.round(parseInt(vmerf_auto_update_time)*60*1000);
        $('.vmerf_refresh_progress').stop().css('width','1%').animate({
            width: '100%',
        }, t, function() {});
        $("#vmerf-grid-container").load(window.location.href + ' table:contains("'+ vmerf_table_selector_text +'")', function(){
            activate_grid();
            try{ console.log('Grid successfully reloaded. Reloading in '+ vmerf_auto_update_time +' minutes.'); }catch(e){};
        });
    }
    if(vmerf_auto_update){
        var t = Math.round(parseInt(vmerf_auto_update_time)*60*1000);
        var int = setInterval(vmerf_reload_grid, t);
        $('.vmerf_refresh_progress').css('width','1%').animate({
    width: '100%',
  }, t, function() {
    // Animation complete.
  });
    }
    $('small:contains("' + vmerf_powered_by_text + '")').append('. <a href="http://wordpress.org/extend/plugins/visitor-maps-extended-referer-field/" target="_blank">Extended</a> by <a href="http://jasonlau.biz" target="_blank">JasonLau.biz</a>');
}); 