<?php $nonce = wp_create_nonce( 'quttera' ); ?>

<script type="text/javascript">

    var scanner_timer = 0;
    var url_name = "";
    var qtr_srv_name = "";

    jQuery(document).ready(function($) {
        $.ajaxSetup({
            type: 'POST',
            url: ajaxurl, /* predefined WP value */
            complete: function(xhr,status) {
                if ( status != 'success' ) {
                    console.log("Failed to communicate with WP");
                }
            }
        });

        $('#run-scanner').click( function() {
            url_name        = $('#url_name').val();
            qtr_srv_name    = $('#qtr_srv_name').val();
                
            $.ajaxSetup({
                type: 'POST',
                url: ajaxurl, /* predefined WP value */
                complete: function(xhr,status) {
                    if ( status != 'success' ) {
                        //alert("Failed to communicate with WP");
                    }
                }
            });

            console.log("Starting scan of " + url_name + " using " + qtr_srv_name);

	        document.getElementById("no_progress_bar").style.display = 'none';
        	document.getElementById("progress_bar").style.display = 'block';
        	document.getElementById("run-scanner").style.display = 'none';
        	document.getElementById("stop-scanner").style.display = 'block';

            document.getElementById("scanned_clean_files").innerHTML            = 0;
            document.getElementById("scanned_pos_suspicious_files").innerHTML   = 0;
            document.getElementById("scanned_suspicious_files").innerHTML       = 0;
            document.getElementById("scanned_malicious_files").innerHTML        = 0;
            document.getElementById("total_scanned_files").innerHTML = 0;

            QtrRunExternalScan( true );
            scanner_timer = setInterval( QtrRunExternalScan, 5000); 
        });   

        $('#stop-scanner').click( function() {
            console.log("Stop scan issued");
            QtrStopExternalScan();
            document.getElementById("scan_state").innerHTML = "stopped";
            return false;
        });

    }); 


    /*
     * URL validation procedure
     */
    function QtrValidateURL(textval) {
         var urlregex = new RegExp(
            "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2,12}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
         return urlregex.test(textval);
    }

    /*
     * Domain validation procedure
     */
    function QtrValidateDomain(domain) { 
        //var re = new RegExp(/^[a-zA-Z0-9][a-zA-Z0-9-_]{0,61}[a-zA-Z0-9]{0,1}\.([a-zA-Z]{1,6}|[a-zA-Z0-9-]{1,30}\.[a-zA-Z]{2,10})$/); 
        var d = domain.trim();
        var re = new RegExp(/^(www\.)?([a-zA-Z0-9][a-zA-Z0-9-_]{0,45}[a-zA-Z0-9]\.)+[a-zA-Z]{2,10}$/);
        return d.match(re);
    }   

    function QtrStopExternalScan()
    {
        clearInterval(scanner_timer);
        scanner_timer = 0;
        document.getElementById("no_progress_bar").style.display = 'block';
        document.getElementById("progress_bar").style.display = 'none';
      	document.getElementById("run-scanner").style.display = 'block';
       	document.getElementById("stop-scanner").style.display = 'none';

        console.log("External scan stopped");
    }

    function QtrRunExternalScan ( first_call ) 
    {
            this_url = url_name;
            qtr_url = qtr_srv_name;

        console.log("QtrRunExternalScan is running");
           
        if( !QtrValidateDomain(this_url) ){

            console.log(this_url + "is invalid");

            var curr_time = new Date().getTime();
            QtrShowInvestigationStatus( {   "state" : "Provided name of this web-site is invalid", 
                                            "age"   : curr_time,
                                            "url"   : "<invalid>"
                                       });
	        return;
	    }

        if( !QtrValidateURL(qtr_url) ){

            console.log(qtr_url + "is invalid");

    	    QtrHideInvestigationElements();
            var curr_time = new Date().getTime();
            QtrShowInvestigationStatus({ "state" : "Provided name of Qutter web malware scanner is invalid", 
                                         "age"   : curr_time,
                                         "url"   : "<invalid>" });
    	    return;
	    }

        if( first_call )
        {
            QtrHideInvestigationElements();
            var curr_time = new Date().getTime();
            QtrShowInvestigationStatus( {   "state" : "starting", 
                                            "age"   : curr_time,
                                            "url"   : this_url });
        }

        jQuery.ajax({
            data: {
                action: 'scanner-run_scan',
                _this: this_url,
                _wpnonce: '<?php echo $nonce; ?>',
                _qtr_url: qtr_url
            }, 
            success: function(r) {
                var res = jQuery.parseJSON(r);
                var state = res.content.state.toLowerCase();
                
                if ( state == 'new' )
                {
                    QtrShowInvestigationStatus({"state" : "Waiting for free web malware scanner slot.",
                                                "age"   : res.content.age,
                                                "url"   : res.content.url,
                                                "priority" : res.content.priority });
                }
                else if( state == 'download')
                {
                    QtrShowInvestigationStatus({ "state" : "Website content is being downloaded for investigation.", 
                                                "age"   : res.content.age,
                                                "url"   : res.content.url,
                                                "priority" : res.content.priority,
                                                "processed_files": res.content.processed_files  });
                    document.getElementById("total_processed_files").innerHTML = "Total Dowloaded:";

                }
                else if( state =='downloaded' )
                {
                    QtrShowInvestigationStatus({ "state" : "Website content has been downloaded and is waiting for scanner.", 
                                                "age"   : res.content.age,
                                                "url"   : res.content.url,
                                                "priority" : res.content.priority });
                    document.getElementById("total_processed_files").innerHTML = "Total Dowloaded:";


                }
                else if( state =='scan' || state =='scanned' )
                {
                    QtrShowInvestigationStatus({ "state" : "website content is being scanned", 
                                                "age"   : res.content.age,
                                                "url"   : res.content.url,
                                                "priority" : res.content.priority,
                                                "processed_files": res.content.processed_files });

                    document.getElementById("total_processed_files").innerHTML = "Total Scanned:";

                }
                else if( state == 'clean' )
                {
                    QtrStopExternalScan();
                    document.getElementById("scan_state").innerHTML = state;
                    QtrShowInvestigationReport(res.content);
                }
                else if( state=='potentially suspicious' || state=='potentially unsafe' )
                {
                    QtrStopExternalScan();
                    document.getElementById("scan_state").innerHTML = state;
                    QtrShowInvestigationReport(res.content);
                }
                else if (state=='suspicious' || state=='unsafe')
                {
                    QtrStopExternalScan();
                    document.getElementById("scan_state").innerHTML = state;
                    QtrShowInvestigationReport(res.content);                
                }
                else if (state=='malicious')
                {
                    QtrStopExternalScan();
                    document.getElementById("scan_state").innerHTML = state;
                    QtrShowInvestigationReport(res.content);                
                }
                else
                {
                    QtrStopExternalScan();
                    QtrShowInvestigationError(res.content);
                    document.getElementById("total_processed_files").innerHTML = "Total Processed:";
                }                
            }//end of success function
        });
    };


    function QtrShowInvestigationError ( status )
    {
        var urlDate     = new Date();
        var currentdate = urlDate.toLocaleString();

        document.getElementById("scan_state").innerHTML = status.state;

        clearInterval(scanner_timer);
        scanner_timer = 0;
    }
   
    /*
     * status comprised from fields:
     *      url  
     *      priority
     *      state  
     *      age   
     *      processed_files
     */  
    function QtrShowInvestigationStatus ( status )
    {
        QtrHideInvestigationElements();
        var urlDate     = new Date();
        var currentdate = urlDate.toLocaleString();

        document.getElementById("scan_start_time").innerHTML = currentdate;
        document.getElementById("scan_state").innerHTML = status.state;

        if( status.priority )
        {
            //str += "<b>Investigation priority</b>: " + status.priority + "</br>";
        }

        if( status.processed_files )
        {
            document.getElementById("total_scanned_files").innerHTML = status.processed_files + "</br>";
        }
    };
   
 
    function QtrShowInvestigationReport ( scan_report )
    {
        var clean_files             = 0;
        var pot_suspicious_files    = 0;
        var suspicious_files        = 0;
        var malicious_files         = 0;
        
        for( var i = 0; i < scan_report.report.length; i ++ )
        {
            var threat = scan_report.report[i].threat.toLowerCase();
            if( threat == "malicious" ){
                malicious_files         += 1;
            }else if( threat == "suspicious" ){
                suspicious_files        +=1;
            }else if( threat == "potentially suspicious"){
                pot_suspicious_files    += 1;
            }else{
                clean_files             += 1;
            }
        }

        document.getElementById("scanned_clean_files").innerHTML            = "<font color='green'><b>"  + clean_files + "</b></font>";
        document.getElementById("scanned_pos_suspicious_files").innerHTML   = "<font color='orange'><b>" + pot_suspicious_files + "</b></font>";
        document.getElementById("scanned_suspicious_files").innerHTML       = "<font color='red'><b>" + suspicious_files + "</b></font>";
        document.getElementById("scanned_malicious_files").innerHTML        = "<font color='#780000'><b>" + malicious_files + "</b></font>";

        document.getElementById("total_scanned_files").innerHTML = clean_files + pot_suspicious_files + suspicious_files + malicious_files;

        /*
        var summary =   "<table>" +
                        "<tr><td align='left'><b>Server IP:</b></td>" +
                             "<td align='left'><b>" + scan_report.ipaddr + "</b></td></tr>" +

                        "<tr><td align='left'><b>Location:</b></td>" +
                             "<td align='left'><b>" + scan_report.country + "</b></td></tr>" +

                        "<tr><td align='left'><b>Web Server:</b></td>" +
                             "<td align='left'><b>" + scan_report.http_server + "</b></td></tr>" +

                        "<tr><td align='left'><font color='green'><b>Clean files: </b></font></td>"+
                            "<td align='left'><font color='green'><b>"  + clean_files + "</b></font></td></tr>" +

                        "<tr><td align='left'><font color='orange'><b>Potentially Suspicious files: </b></font></td>"+
                            "<td align='left'><font color='orange'><b>" + pot_suspicious_files + "</b></font></td></tr>" +

                        "<tr><td align='left'><font color='red'><b>Suspicious files: </b></font></td>"+
                            "<td align='left'><font color='red'><b>" + suspicious_files + "</b></font></td></tr>" +

                        "<tr><td align='left'><font color='#780000'><b>Malicious files: </b></font></td>" +
                            "<td align='left'><font color='#780000'><b>"    + malicious_files + "</b></font></td></tr>";
        */

        if(  scan_report.is_blacklisted )
        {
            if(  scan_report.is_blacklisted &&  scan_report.is_blacklisted.toLowerCase() == "no" )
            {
                /*
                summary +=  "<tr><td align='left'><font color='green'><b>Blacklisted: </b></font></td>"+
                "<td align='left'><font color='green'><b>"  + scan_report.is_blacklisted + "</b></font></td></tr>";
                 */
                document.getElementById("blacklisted").innerHTML  = "<font color='green'><b>" + scan_report.is_blacklisted + "</b></font>";

            }
            else
            {
                /*
                summary +=  "<tr><td align='left'><font color='red'><b>Blacklisted: </b></font></td>"+
                "<td align='left'><font color='red'><b>"  + scan_report.is_blacklisted + "</b></font></td></tr>";
                 */
                 document.getElementById("blacklisted").innerHTML  = "<font color='red'><b>" +  scan_report.is_blacklisted + "</b></font>";
            }
        }
        else
        {
            document.getElementById("blacklisted").innerHTML        = "no";
        }

        /*
        summary +=      "<tr><td align='left'><b>External links:</b></td>" +
                             "<td align='left'><b>" + scan_report.links_count + "</b></td></tr>" +

                        "<tr><td align='left'><b>Detected iframes:</b></td>" +
                             "<td align='left'><b>" + scan_report.iframes_count + "</b></td></tr>" +

                        "<tr><td align='left'><b>External domains:</b></td>" +
                             "<td align='left'><b>" + scan_report.domains_count + "</b></td></tr>" +
 
                        "</table>" + 
                        "<hr/>";

        jQuery('#investigation_result').append(summary);
        
        var scanner_server = document.getElementById('qtr_srv_name').value;
        var domain_name    = document.getElementById('url_name').value;
        //var full_url       = scanner_server + "/detailed_report/" + domain_name;
        */

        //var full_url       = "https://quttera.com/detailed_report/" + domain_name;

        /*
        jQuery('#investigation_result').append("<form method='get' action='" + full_url + "' target='new'>" +
                "<input type='submit' class='button-primary' value='Full Investigation Report' style='font-weight: bold;'/></form>");
        
        jQuery('#investigation_report_info').show();
        jQuery('#run-scanner').show();
        jQuery('#investigation_result').show();       
        */
        $("#nav-scan-report-tab").removeClass("disabled");
    };
    
    
    function QtrHideInvestigationElements( )
    {
        jQuery('#investigation_result').hide();
        jQuery('#investigation_error').hide();
        jQuery('#investigation_progress').hide();    
        jQuery('#quttera_detected_malicious_content').hide();    
    }

</script>

