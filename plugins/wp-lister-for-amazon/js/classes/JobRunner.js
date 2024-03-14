
// init namespace
if ( typeof WPLA != 'object') var WPLA = {};


// revealing module pattern
WPLA.JobRunner = function () {
    
    // this will be a private property
    var jobsQueue = {};
    var jobsQueueActive = false;
    var jobKey = 0;
    var currentTask = 0;
    var currentSubTask = 0;
    var subtaskQueue = {};
    var retryCount = 0;
    var cancel_operation = 0;
    var self = {};
    
    // this will be a public method
    var init = function () {
        self = this; // assign reference to current object to "self"
    
        // jobs window "close" button
        jQuery('#wpla_jobs_window .btn_close').click( function(event) {
            tb_remove();                    
        }).hide();

        // jobs window "cancel" button
        jQuery('#wpla_jobs_window .btn_cancel').click( function(event) {
            jQuery('#wpla_jobs_message').html('Cancelling...');
            self.cancel_operation = true;
        });

    }

    var runJob = function ( jobname, title, extra_params ) {
        
        // show jobs window
        this.showWindow( title );

        // load task list
        var params = {
            action: 'wpla_jobs_load_tasks',
            job: jobname,
            _wpnonce: wpla_JobRunner_i18n.wpla_ajax_nonce
        };

        // include optional extra parameters
        if ( extra_params ) {
            params = jQuery.extend( params, extra_params );
        }

        // if ( extra_params && extra_params.item_id ) {
        //     params.item_id = extra_params.item_id;
        // }
        // if ( extra_params && extra_params.item_ids ) {
        //     params.item_ids = extra_params.item_ids;
        // }

        // var jqxhr = jQuery.getJSON( ajaxurl, params )
        var jqxhr = jQuery.post( ajaxurl, params, null, 'json' )
        .done( function( response ) {

            // set global queue
            self.jobKey = response.job_key;
            self.jobsQueue = response.tasklist;
            self.jobsQueueActive = true;
            self.currentTask = 0;

            if ( jQuery.isArray(self.jobsQueue) && ( self.jobsQueue.length > 0 ) ) {
                // run first task
                self.runTask( self.jobsQueue[ self.currentTask ] );
            } else {
                var logMsg = '<div id="message" class="updated" style="display:block !important;"><p>' + 
                'I could not find any tasks to process. If you think this is a bug, please contact support.' +
                '</p></div>';
                jQuery('#wpla_jobs_log').append( logMsg );
                self.updateProgressBar( 1 );
                self.completeJob();
            }


        })
        .fail( function(e,xhr,error) {
            jQuery('#wpla_jobs_log').append( "There was a problem fetching the job list. " );
            jQuery('#wpla_jobs_log').append( "The server responded:<hr>" + self.escapeHtml( e.responseText ) + "<hr>" );
            jQuery('#wpla_jobs_window .btn_close').show();
            jQuery('#wpla_jobs_window .btn_cancel').hide();
            // alert( "There was a problem fetching the job list. The server responded:\n\n" + e.responseText ); 
            console.log( "error", xhr, error ); 
            console.log( e.responseText ); 
            console.log( "ajaxurl", ajaxurl ); 
            console.log( "params", params ); 
        });

    }

    var runSubTask = function ( subtask ) {

        // console.log('runSubTask(): ', subtask );
        var currentLogRow = jQuery('#wpla_logRow_'+self.currentTask);

        // logRow: set title
        // currentLogRow.find('.logRowTitle').html( subtask.displayName );
        // currentLogRow.find('.logRowTitle').append( '<br>&nbsp;-&nbsp;' + subtask.displayName );


        // create new log row for currentSubTask
        var new_row = ' <div id="wpla_subTaskLogRow_'+self.currentTask+'_'+self.currentSubTask+'" class="logRow">' +
                        '   <div class="logRowTitle"></div>' +
                        '   <div class="logRowErrors"></div>' +
                        '   <div class="logRowStatus"></div>' +
                        '</div>';
        jQuery('#wpla_jobs_log').append( new_row );
        var currentSubTaskLogRow = jQuery('#wpla_subTaskLogRow_'+self.currentTask+'_'+self.currentSubTask);


        // logRow: set title
        currentSubTaskLogRow.find('.logRowTitle').html( '<span style="color:silver;padding-left:1em;">' + subtask.displayName + '</span>' );

        // logRow: set status icon
        var statusIconURL = wpla_url + "img/ajax-loader.gif";
        currentSubTaskLogRow.find('.logRowStatus').html( '<img src="'+statusIconURL+'" />' );


        // run task
        // task.displayName = 'ID '+self.jobKey; // reset displayName
        var params = {
            action: 'wpla_jobs_run_subtask',
            job: self.jobKey,
            subtask: subtask,
            _wpnonce: wpla_JobRunner_i18n.wpla_ajax_nonce
        };
        // var jqxhr = jQuery.getJSON( ajaxurl, params )
        var jqxhr = jQuery.post( ajaxurl, params, null, 'json' )
        .done( function( response ) {

            // check task success
            if ( response.success ) {
                var statusIconURL = wpla_url + "img/icon-success.png";
                var errors_label  = response.errors.length == 1 ? 'warning' : 'warnings';
            } else {
                var statusIconURL = wpla_url + "img/icon-error.png";                
                var errors_label  = response.errors.length == 1 ? 'error' : 'errors';
            }

            // update subtask row status
            currentSubTaskLogRow.find('.logRowStatus').html( '<img src="'+statusIconURL+'" />' );

            // prepare next subtask
            self.currentSubTask++;
            if ( self.currentSubTask < self.subtaskQueue.length ) {

                // run next task
                self.runSubTask( self.subtaskQueue[ self.currentSubTask ] );

            } else {

                // update main task status
                currentLogRow.find('.logRowStatus').html( '<img src="'+statusIconURL+'" />' );

                // all subtasks complete
                self.nextTask();

            }

        })
        .fail( function(e,xhr,error) {

            // quit on other errors
            jQuery('#wpla_jobs_log').append( "A problem occurred while processing this task. The server responded with code " + e.status + ": " + e.responseText + "<br>" );
            jQuery('#wpla_jobs_window .btn_close').show();
            jQuery('#wpla_jobs_window .btn_cancel').hide();
            // alert( "There was a problem running the task '"+task.displayName+"'.\n\nThe server responded:\n" + e.responseText + '\n\nPlease contact support@wplab.com.' ); 
            console.log( "XHR object", e ); 
            console.log( "error", xhr, error ); 
            console.log( e.responseText ); 

        });

    }

    var runTask = function ( task ) {

        // estimate time left
        // var time_left = 'estimating time left...';
        var time_left = wpla_JobRunner_i18n.msg_estimating_time;
        if (self.currentTask == 0) {
            self.time_started = new Date().getTime() / 1000;
        } else {
            var current_time = new Date().getTime() / 1000;
            time_running = current_time - self.time_started;
            time_estimated = time_running / self.currentTask * self.jobsQueue.length;
            time_left = time_estimated - time_running;
            if ( time_left > 60 ) {
                time_left = Math.round(time_left/60) + ' min.';
            } else {
                time_left = Math.round(time_left) + ' sec.';
            }
            // time_left = 'about {0} remaining'.format( time_left )
            time_left = wpla_JobRunner_i18n.msg_time_left.format( time_left )
        }

        // update message
        // var processing_msg = 'processing {0} of {1}'.format( self.currentTask+1, self.jobsQueue.length );
        var processing_msg = wpla_JobRunner_i18n.msg_processing.format( self.currentTask+1, self.jobsQueue.length );
        jQuery('#wpla_jobs_message').html( processing_msg + ' - ' + time_left );
        this.updateProgressBar( (self.currentTask + 1) / self.jobsQueue.length );

        // create new log row for currentTask
        var new_row = ' <div id="wpla_logRow_'+self.currentTask+'" class="logRow">' +
                        '   <div class="logRowTitle"></div>' +
                        '   <div class="logRowErrors"></div>' +
                        '   <div class="logRowStatus"></div>' +
                        '</div>';
        jQuery('#wpla_jobs_log').append( new_row );
        var currentLogRow = jQuery('#wpla_logRow_'+self.currentTask);


        // logRow: set title
        currentLogRow.find('.logRowTitle').html( task.displayName );

        // logRow: set status icon
        var statusIconURL = wpla_url + "img/ajax-loader.gif";
        currentLogRow.find('.logRowStatus').html( '<img src="'+statusIconURL+'" />' );

        // run task
        // task.displayName = 'ID '+self.jobKey; // reset displayName
        var params = {
            action: 'wpla_jobs_run_task',
            job: self.jobKey,
            task: task,
            _wpnonce: wpla_JobRunner_i18n.wpla_ajax_nonce
        };
        // var jqxhr = jQuery.getJSON( ajaxurl, params )
        var jqxhr = jQuery.post( ajaxurl, params, null, 'json' )
        .done( function( response ) {

            if ( response.subtasks && response.success ) {
    
                self.subtaskQueue = response.subtasks;
                self.currentSubTask = 0;

                if ( self.subtaskQueue.length > 0 ) {
                    // run first subtask
                    self.runSubTask( self.subtaskQueue[ self.currentSubTask ] );
                    return;
                }
            }

            // check task success
            if ( response.success ) {
                var statusIconURL = wpla_url + "img/icon-success.png";
                var errors_label  = response.errors.length == 1 ? 'warning' : 'warnings';
                var errors_label  = response.errors.length == 1 ? 'message' : 'messages';
            } else if ( response.errors ) {
                var statusIconURL = wpla_url + "img/icon-error.png";                                
                var errors_label  = response.errors.length == 1 ? 'error' : 'errors';
            } else {
                var statusIconURL = wpla_url + "img/icon-error.png";                                
                console.log( 'server returned: ',response );
                // jQuery('#wpla_jobs_log').append( 'The server returned: '.response );
            }

            // update row status
            currentLogRow.find('.logRowStatus').html( '<img src="'+statusIconURL+'" />' );

            // handle errors
            if ( response.errors && response.errors.length > 0 ) {

                // create show details button
                var taskDetailsBtn = '<a href="#" onclick="jQuery(\'#taskDetails_'+self.currentTask+'\').slideToggle(300);return false;" class="" style="">'+response.errors.length + ' '+errors_label+'</a>';
                currentLogRow.find('.logRowErrors').html( taskDetailsBtn );

                // add errors and warnings to hidden div
                var taskDetails = '<div id="taskDetails_'+self.currentTask+'" class="taskDetails" style="display:none;">';
                for (var i = response.errors.length - 1; i >= 0; i--) {
                    var err = response.errors[i]
                    taskDetails += err.HtmlMessage + "<!br>";
                };
                taskDetails += '</div>';
                jQuery('#wpla_jobs_log').append( taskDetails );

            }

            // handle delay (prevent throttling)
            if ( response.delay ) {
                setTimeout( self.nextTask(), response.delay );
                return;
            }

            // next task
            self.nextTask();

        })
        .fail( function(e,xhr,error) {
            // update row status
            var statusIconURL = wpla_url + "img/icon-error.png";                
            currentLogRow.find('.logRowStatus').html( '<img src="'+statusIconURL+'" />' );

            // default error handling mode: skip
            // if ( typeof wpla_ajax_error_handling === 'undefined' ) wpla_ajax_error_handling = 'skip';

            // dont get fooled by 404 or 500 errors for admin-ajax.php
            if ( ( e.status == 404 ) || ( e.status == 500 ) ) {


                if ( ( wpla_ajax_error_handling == 'retry') && ( self.retryCount < 5 ) ) {

                    // try running the task again
                    self.retryCount++;
                    jQuery('#wpla_jobs_log').append( "Warning: server returned "+e.status+". will try again...<!br>" );
                    self.runTask( self.jobsQueue[ self.currentTask ] );

                } else if ( wpla_ajax_error_handling == 'skip') {

                    // prepare next task
                    self.currentTask++;
                    if ( self.currentTask < self.jobsQueue.length ) {
                        // run next task
                        self.runTask( self.jobsQueue[ self.currentTask ] );
                    } else {
                        // all tasks complete
                        // jQuery('#wpla_jobs_message').html('finishing up...');
                        jQuery('#wpla_jobs_message').html( wpla_JobRunner_i18n.msg_finishing_up );
                        self.completeJob();
                    }

                } else { // halt

                    // halt task processing
                    jQuery('#wpla_jobs_log').append( "A problem occurred while processing this task. The server responded with code " + e.status + ": " + e.responseText + "<br>" );
                    jQuery('#wpla_jobs_window .btn_close').show();
                    jQuery('#wpla_jobs_window .btn_cancel').hide();

                }

            // } else if ( e.status == 500 ) {

            //     // just try running the task again
            //     jQuery('#wpla_jobs_log').append( "Warning: server returned 500. going to try again...<br>" );
            //     self.runTask( self.jobsQueue[ self.currentTask ] );

            } else {
    
                // quit on other errors
                jQuery('#wpla_jobs_log').append( "A problem occurred while processing this task. The server responded with code " + e.status + ": " + e.responseText + "<br>" );
                jQuery('#wpla_jobs_window .btn_close').show();
                jQuery('#wpla_jobs_window .btn_cancel').hide();
                // alert( "There was a problem running the task '"+task.displayName+"'.\n\nThe server responded:\n" + e.responseText + '\n\nPlease contact support@wplab.com.' ); 
                console.log( "XHR object", e ); 
                console.log( "error", xhr, error ); 
                console.log( e.responseText ); 

            }


        });

    }

    var nextTask = function () {

        if ( self.cancel_operation ) {
            jQuery('#wpla_jobs_message').html('Processing was cancelled');
            jQuery('#wpla_jobs_window .btn_close').show();
            jQuery('#wpla_jobs_window .btn_cancel').hide();
            return;
        }

        self.currentTask++;
        self.retryCount=0;
        if ( self.currentTask < self.jobsQueue.length ) {

            // run next task
            self.runTask( self.jobsQueue[ self.currentTask ] );

        } else {

            // all tasks complete
            // jQuery('#wpla_jobs_message').html('finishing up...');
            jQuery('#wpla_jobs_message').html( wpla_JobRunner_i18n.msg_finishing_up );
            self.completeJob();

        }

    }

    var completeJob = function () {

        // inform server of completed job
        var params = {
            action: 'wpla_jobs_complete_job',
            job: self.jobKey,
            _wpnonce: wpla_JobRunner_i18n.wpla_ajax_nonce
        };
        var jqxhr = jQuery.getJSON(
            ajaxurl,
            params,
            function( response ) {

                // append to log
                jQuery('#wpla_jobs_log').append( response.error );

                // all tasks complete
                self.jobsQueueActive = false;
                jQuery('#wpla_jobs_message').html('&nbsp;');
                // jQuery('#wpla_jobs_window .btn_close').show();

                if ( jQuery.isArray(self.jobsQueue) && ( self.jobsQueue.length > 0 ) ) {
                    // jQuery('#wpla_jobs_footer_msg').html( 'All ' + self.jobsQueue.length + ' tasks have been completed.' );
                    // jQuery('#wpla_jobs_footer_msg').html( 'All {0} tasks have been completed.'.format( self.jobsQueue.length ) );
                    jQuery('#wpla_jobs_footer_msg').html( wpla_JobRunner_i18n.msg_all_completed.format( self.jobsQueue.length ) );

                    // if there were any tasks completed, refresh the current page when closing the jobs window
                    jQuery('#wpla_jobs_window .btn_close').click( function(event) {
                        // refresh page
                        // window.location.href = window.location.href;
                        // history.go(0); // alternative

                        // refresh the page - without any action parameter that might be present
                        if ( window.location.href.indexOf("&action") != -1 ) {
                            window.location.href = window.location.href.substr( 0, window.location.href.indexOf("&action") )
                            // increase step if step parameter is present - step MUST be the last paremeter!
                        } else if ( window.location.href.indexOf("&step") != -1 ) {
                            this_step = window.location.href.substr( window.location.href.indexOf("&step") + 6 );
                            next_step = parseInt( this_step ) + 1;
                            window.location.href = window.location.href.substr( 0, window.location.href.indexOf("&step") ) + '&step=' + next_step.toString()
                        } else {
                            window.location.href = window.location.href;
                        }
                    }).show();

                } else {
                    jQuery('#wpla_jobs_footer_msg').html( '' );
                    jQuery('#wpla_jobs_window .btn_close').show();
                }

                jQuery('#wpla_jobs_window .btn_cancel').hide();

            }
        )
        .fail( function(e,xhr,error) {
            jQuery('#wpla_jobs_log').append( "problem completing job - server responded: " + e.responseText + "<br>" );
            jQuery('#wpla_jobs_window .btn_close').show();
            jQuery('#wpla_jobs_window .btn_cancel').hide();
            alert( "There was a problem completing this job.\n\nThe server responded:\n" + e.responseText + '\n\nPlease contact support@wplab.com.' ); 
            console.log( "error", xhr, error ); 
            console.log( e.responseText ); 
        });

    }

    
    // show jobs window
    var showWindow = function ( title ) {

        // show jobs window
        var tbHeight = tb_getPageSize()[1] - 160;
        var tbURL = "#TB_inline?height="+tbHeight+"&width=500&modal=true&inlineId=wpla_jobs_window_container"; 
        jQuery('#wpla_jobs_log').html('').css('height', tbHeight - 130 );
        jQuery('#wpla_jobs_title').html( title );
        // jQuery('#wpla_jobs_message').html('fetching list of tasks...');
        jQuery('#wpla_jobs_message').html( wpla_JobRunner_i18n.msg_loading_tasks );
        // jQuery('#wpla_jobs_footer_msg').html( "Please don't close this window until all tasks are completed." );
        jQuery('#wpla_jobs_footer_msg').html( wpla_JobRunner_i18n.footer_dont_close );

        // init progressbar
        jQuery("#wpla_progressbar").progressbar({ value: 0.01 });
        jQuery("#wpla_progressbar").children('span.caption').html('0%');

        // hide close button
        jQuery('#wpla_jobs_window .btn_close').hide();
        jQuery('#wpla_jobs_window .btn_cancel').show();

        // show window
        tb_show("Jobs", tbURL);             

    }

    // js equivalent of htmlspecialchars()
    var escapeHtml = function ( text ) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    var updateProgressBar = function ( value ) {
        // jQuery("#wpla_progressbar").progressbar({ value: value });
        jQuery("#wpla_progressbar").animate_progressbar( value * 100, 500 );
    }

    return {
        // declare which properties and methods are supposed to be public
        init: init,
        runJob: runJob,
        runTask: runTask,
        runSubTask: runSubTask,
        nextTask: nextTask,
        completeJob: completeJob,
        updateProgressBar: updateProgressBar,
        escapeHtml: escapeHtml,
        showWindow: showWindow
    }
}();


// animate_progressbar() method for progressbar
// http://stackoverflow.com/questions/5047498/how-do-you-animate-the-value-for-a-jquery-ui-progressbar
// (function(a){a.fn.animate_progressbar=function(d,e,f,b){if(d==null){d=0}if(e==null){e=1000}if(f==null){f="swing"}if(b==null){b=function(){}}var c=this.find(".ui-progressbar-value");c.stop(true).animate({width:d+"%"},e,f,function(){if(d>=99.5){c.addClass("ui-corner-right")}else{c.removeClass("ui-corner-right")}b()})}})(jQuery);
(function( jQuery ) {
    jQuery.fn.animate_progressbar = function(value,duration,easing,complete) {
        if (value == null)value = 0;
        if (duration == null)duration = 1000;
        if (easing == null)easing = 'swing';
        if (complete == null)complete = function(){};
        var progress = this.find('.ui-progressbar-value');
        var caption  = this.find('span.caption');
        progress.stop(true).animate({
            width: value + '%'
        },duration,easing,function(){
            if(value>=99.5){
                progress.addClass('ui-corner-right');
            } else {
                progress.removeClass('ui-corner-right');
            }
            caption.html(Math.round(value)+'%');
            complete();
        });
    }
})( jQuery );


// implement String.format()
// http://stackoverflow.com/questions/610406/javascript-equivalent-to-printf-string-format
if (!String.prototype.format) {
    String.prototype.format = function() {
        var args = arguments;
        if (typeof this.replace !== 'function') return false;
        return this.replace(/{(\d+)}/g, function(match, number) { 
            return typeof args[number] != 'undefined'
                ? args[number]
                : match
            ;
        });
    };
}

