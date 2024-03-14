(function( $ ) {
	'use strict';
	$(function() {
	    var wt_export=
	    {
	    	find_arr:new Array(),
			replace_arr:new Array(),
			exclude_arr: new Array(),
			ftp_profile : '',
			ftp_path : '',
			ftp_file:'',
			google_drive_filename: '',
			dropbox_filename: '',
                        local_filename:'',
                        export_type :'',
			s3bucket_file_name:'',
                        onPrg:0,
                        stopNow:0,
                        export_id:0,
			export_option:'local',
			
                    Set: function ()
                    {
                        wt_export.bind_form_toggle();
                        wt_export.save_settings();
                        wt_export.delete_schedule();

                        $('[name="wt_schedule_cancel_btn"]').off('click').on('click', function(){
                            var $re = jQuery('#accordion-wrapper');
                            $re.removeClass('accordionsc-wrapper');
                            $re.toggleClass('accordion-wrapper');
                            $('.wt_backup_schedule_data').hide();
                            $('.wt_backup_data').show();
                        });
                        
                        
                        $('[name="wt_mgdp_export_click_btn"]').off('click').on('click', function(){
                            var $re = jQuery('#accordion-wrapper');
                            if ($re.hasClass('accordionsc-wrapper')) {
                                $re.removeClass('accordionsc-wrapper');
                                $re.toggleClass('accordion-wrapper');
                            }
                            jQuery('.wt_mgdp_cron_popup').show();
                            $('body').css('overflow', 'hidden');
                            jQuery('.popup_first').show();
                            $('.popup_second').hide();
                            $('.popup_third').hide();
                            $('.wt_backup_schedule_data').hide();
                            $('.wt_backup_data').show();
                            var filename = '';
                            var sorage = 'File will be downloaded locally';
                            var content_details = '';
                            var migration_option = $('select[name="wt_mgdb_export_option"]').val();

                            if ('ftp' == migration_option) {
                                filename = $('input[name="wt_mgdb_export_file"]').val();
                                sorage = "Backup file will be exported to FTP/SFTP. ";
                            } else if ('googledrive' == migration_option) {
                                filename = $('input[name="wt_mgdb_google_drive_file_name"]').val();
                                sorage = "Backup file will be exported to Google Drive.";
                            } else if ('s3bucket' == migration_option) {
                                filename = $('input[name="wt_mgdb_s3bucket_file_name"]').val();
                                sorage = "Backup file will be exported to Amazon S3.";
                            }
                            document.getElementById("export_location").innerHTML = sorage;
                            jQuery("#wt_mgdp_cron_file_name").val(filename);

                            var file = false;
                            var db = false;
                            if ($('[name="export_type_file"]').prop('checked') == true) {
                                file = true;
                            }

                            if ($('[name="export_type_db"]').prop('checked') == true) {
                                db = true;
                            }
                            var content = $('input[name="export_type_default"]:checked').val();
                            if (file == true && db == true) {
                                content_details = "Both files and database are selected.";
                                wt_export.export_type = 'files_and_db'
                            } else if (file == true && db == false) {
                                content_details = "Files will be backed up(excluded database). ";
                                wt_export.export_type = 'files'
                            } else if (file == false && db == true) {
                                content_details = "Database will be backed up(excluded files).";
                                wt_export.export_type = 'db'
                            } else {
                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.export_content_empty);
                                jQuery('.wt_mgdp_cron_popup').hide();
                                return true;
                            }
                            document.getElementById("export_content").innerHTML = content_details;
                            if (content_details === '') {
                                jQuery('.export_content').hide();
                            }
                            document.getElementById("export_size").innerHTML = 'Calculating ...';
                            wt_export.exclude_arr = [];
                            wt_export.getExcludeArray();
                            wt_export.get_file_size(wt_export.export_type, wt_export.exclude_arr);
                        });


                        $('[name="export_type_file"]').off('click').on('click', function(){
                            if ($('[name="export_type_file"]').prop('checked') == false) {
                                document.getElementById("contents").style.border = "none";
                                document.getElementById("content-div").style.height = "220px";
                                document.getElementById("btn-exp").style.marginTop = "-50px";
                                jQuery('.exclude_files').hide();
                            } else {
                                jQuery('.exclude_files').show();
                                document.getElementById("contents").style.borderRight = "1px solid #DDDDDD";
                                document.getElementById("content-div").style.height = "378px";
                                document.getElementById("btn-exp").style.marginTop = "2px";
                            }
                        });

                        $('[name="cron_export_type_files"]').off('click').on('click', function(){
                            if ($('[name="cron_export_type_files"]').prop('checked') == false) {
                                jQuery('.exclude_folder').hide();
                            } else {
                                jQuery('.exclude_folder').show();
                            }
                        });
                        
                        
                        $('.wt_mgdp_popup_cancel').off('click').on('click', function(){
                            $('body').css('overflow', 'auto');
                            $('#export_class div:nth-child(1) > .wt-migrator-accordion-content').show();
                            $('html, body').stop(true, true).animate({
                                scrollTop: $("#wt_backup_data").offset().top
                            }, 2000);
                            jQuery('.wt_mgdp_cron_popup').hide();
                            jQuery('.wf_progress_bar').hide();
                            jQuery('.export_complete').hide();
                            jQuery('[name="wt_mgdp_export_download_btn"]').hide();
                            jQuery('[name="wt_mgdp_export_btn"]').show();
                        });
                        
                        
                        $('.wt_mgdp_finish_popup_cancel').off('click').on('click', function(){
                            $('body').css('overflow', 'auto');
                            jQuery('.wt_mgdp_cron_popup').hide();
                            jQuery('.wf_progress_bar').hide();
                            jQuery('.export_complete').hide();
                            jQuery('[name="wt_mgdp_export_download_btn"]').hide();
                            jQuery('[name="wt_mgdp_export_btn"]').show();
                        });
                        
                        
                        $('.wt_mgdp_cron_popup_close').off('click').on('click', function(){
                            jQuery('.wt_mgdp_cron_popup').hide();
                            jQuery('.wf_progress_bar').hide();
                            jQuery('.export_complete').hide();
                            jQuery('[name="wt_mgdp_export_download_btn"]').hide();
                            jQuery('[name="wt_mgdp_export_btn"]').show();
                            $('body').css('overflow', 'auto');
                        });

                        $('[name="usrselectall"]').off('click').on('click', function(){// Iterate each checkbox
                            $(document.querySelectorAll('#wt_exclude_folders ul:nth-child(1) > li > input[type=checkbox]')).prop('checked', true);
                        });

                        $('[name="usrunselectall"]').off('click').on('click', function(){
                            $(document.querySelectorAll('#wt_exclude_folders ul:nth-child(1) > li > input[type=checkbox]')).prop('checked', false);
                        });

                        $('[name="usrselectall_def"]').off('click').on('click', function(){// Iterate each checkbox
                            $(document.querySelectorAll('#wt_exclude_folders_deafult ul:nth-child(1) > li > input[type=checkbox]')).prop('checked', true);
                        });

                        $('[name="usrunselectall_def"]').off('click').on('click', function(){
                            $(document.querySelectorAll('#wt_exclude_folders_deafult ul:nth-child(1) > li > input[type=checkbox]')).prop('checked', false);
                        });

                        $('[name="wt_mgdp_export_btn"]').off('click').on('click', function(e){
                            jQuery('.popup_first').hide();

                            jQuery('.popup_second').show();
                            var extension_zip_loaded = $('input[name="extension_zip_loaded"]').val();
                            var extension_zlib_loaded = $('input[name="extension_zlib_loaded"]').val();
                            if (extension_zip_loaded == 'disabled' && extension_zlib_loaded == 'disabled') {
                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.zip_disable);
                                return true;
                            }

                            if (wt_export.onPrg == 1) {
                                return false;
                            }

                            var migration_option = $('select[name="wt_mgdb_export_option"]').val();
                            if ('ftp' == migration_option) {
                                var profile = $('select[name="wt_mgdb_export_ftp_profiles"]').val();
                                var path = $('input[name="wt_mgdb_export_path"]').val();
                                if (0 == profile) {
                                    wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.choose_profile);
                                    return false;
                                } else if ('' == path) {
                                    wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.specify_path);
                                    return false;
                                }
                            }
                            if ('local' == migration_option) {
                                wt_export.local_filename = $('input[name="wt_mgdp_cron_file_name"]').val();
                            }
                            wt_export.onPrg = 1;
                            wt_export.stopNow = 0;
                            $(window).scrollTop(0);
                            $('.spinner, [name="wt_mgdp_export_stop_btn"]').show();
                            $('[name="wt_mgdp_export_btn"]').css({'opacity': '.5', 'cursor': 'not-allowed'});
                            wt_export.getFindandReplaceVal();
                            wt_export.getExcludeArray();
                            wt_export.getFTPDetailsArray();
                            wt_export.getGoogleDriveDetailsArray();
                            wt_export.getDropboxArray();
                            wt_export.getAmazonS3Array();
                            $('.wf_export_sub').show();
                            $('.wf_export_loader').show();
                            $('.wf_export_main').show();
                            wt_export.updateProgressBar(0, 0, wp_migration_duplicator_export.labels.connecting, wp_migration_duplicator_export.labels.connecting);
                            wt_export.startExport(0, 1, 0, 'start_export', 0, 10000);
                        });
                        $('[name="wt_mgdp_export_stop_btn"]').off('click').on('click', function(){
                            if (confirm(wp_migration_duplicator_export.labels.sure))
                            {
                                wt_export.stopNow = 1;
                                wt_export.updateProgressBarlabel(wp_migration_duplicator_export.labels.stopping, wp_migration_duplicator_export.labels.stopping);
                                wt_export.stopExport();
                            }
                        });
                    },
                    
                    
                    get_file_size: function (content, exclude)
                    {
                        var ajx_dta = {};
                        ajx_dta['data'] = {'content': content, 'exclude': exclude, };
                        ajx_dta['action'] = 'mgdp_get_file_size';
                        ajx_dta['_wpnonce'] = wp_migration_duplicator_export.nonces.main,
                                jQuery.ajax({
                                    url: wp_migration_duplicator_export.ajax_url,
                                    type: 'POST',
                                    data: ajx_dta,
                                    dataType: "json",
                                    success: function (response)
                                    {
                                        if (response.success === true)
                                        {
                                            document.getElementById("export_size").innerHTML = response.data;

                                        }

                                    }
                                });
                    },
                    
                    
                    stopExport: function ()
                    {
                        jQuery('.wt_mgdp_cron_popup').hide();
                        $('body').css('overflow', 'auto');
                        var data = {
                            _wpnonce: wp_migration_duplicator_export.nonces.main,
                            action: "wt_mgdp_export",
                            sub_action: 'stop_export',
                            export_id: this.export_id,
                        };
                        $('[name="wt_mgdp_export_stop_btn"]').hide();
                        $('.wf_export_sub').hide();
                        $('.wf_export_loader').hide();
                        $('.wf_export_main').hide();
                        $.ajax({
                            url: wp_migration_duplicator_export.ajax_url,
                            type: 'post',
                            data: data,
                            dataType: 'json',
                            success: function (data)
                            {
                                wt_export.resetOnprg();
                                if (data.status)
                                {
                                    wt_export.updateProgressBarlabel(wp_migration_duplicator_export.labels.stopped, wp_migration_duplicator_export.labels.stopped);
                                } else
                                {
                                    wp_migration_duplicator_notify_msg.error(data.msg);
                                    wt_export.updateProgressBarlabel(data.msg, data.msg);
                                }
                            },
                            error: function ()
                            {
                                wt_export.resetOnprg();
                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.failedtostop);
                                wt_export.updateProgressBarlabel(wp_migration_duplicator_export.labels.failedtostop, wp_migration_duplicator_export.labels.failedtostop);
                            }
                        });
                    },
                    
                    
                    save_settings: function ()
                    {
                        $('[name="wt_mgdp_save_settings_btn"]').off('click').on('click', function(){
                            document.querySelector(".spinner-save-export").style.visibility = 'visible'; 
                            var data_size_per_req = $('input[name="data_size_per_req"]').val();
                            var db_record_per_req = $('input[name="db_record_per_req"]').val();
                            var file_per_req = $('input[name="file_per_req"]').val();
                            var ajx_dta = {};
                            ajx_dta['settings_data'] = {'data_size_per_req': data_size_per_req, 'db_record_per_req': db_record_per_req, 'file_per_req': file_per_req, };
                            ajx_dta['action'] = 'mgdp_plugin_save_settings';
                            ajx_dta['_wpnonce'] = wp_migration_duplicator_export.nonces.main,
                                    jQuery.ajax({
                                        url: wp_migration_duplicator_export.ajax_url,
                                        type: 'POST',
                                        data: ajx_dta,
                                        dataType: "json",
                                        success: function (response)
                                        {
                                            document.querySelector(".spinner-save-export").style.visibility = 'hidden'; 
                                            if (response.success === true)
                                            {
                                                wp_migration_duplicator_notify_msg.success(wp_migration_duplicator_export.labels.success);

                                            } else {
                                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                                            }

                                        },
                                        error: function ()
                                        {
                                            document.querySelector(".spinner-save-export").style.visibility = 'hidden'; 
                                            wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                                        }
                                    });
                        });

                    },
                    
                    
                    delete_schedule: function ()
                    {
                        $('[name="wt_mgdp_schedule_export_delete"]').off('click').on('click', function () {

                            var ajx_dta = {};
                            ajx_dta['action'] = 'mgdp_plugin_delete_schedule';
                            ajx_dta['_wpnonce'] = wp_migration_duplicator_export.nonces.main,
                                    jQuery.ajax({
                                        url: wp_migration_duplicator_export.ajax_url,
                                        type: 'POST',
                                        data: ajx_dta,
                                        dataType: "json",
                                        success: function (response)
                                        {
                                            if (response.success === true)
                                            {
                                                wp_migration_duplicator_notify_msg.success(wp_migration_duplicator_export.labels.success);
                                                window.location.reload();
                                            } else {
                                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                                            }

                                        },
                                        error: function ()
                                        {
                                            wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                                        }
                                    });
                        });

                    },
                    
                    set_export_progress_info: function (msg)
                    {
                        $('.wt_mgdp_loader_info_box').show().html(msg);
                    },
                    
                    resetOnprg: function ()
                    {
                        wt_export.onPrg = 0;
                        $('.spinner, [name="wt_mgdp_export_stop_btn"]').hide();
                        $('.wt_mgdp_export_find_replace_tb').show();
                        $('[name="wt_mgdp_export_btn"]').css({'opacity': '1', 'cursor': 'pointer'});
                    },
                    getFindandReplaceVal: function ()
                    {
                        $('[name="find[]"]').each(function () {
                            wt_export.find_arr.push($(this).val());
                        });
                        $('[name="replace[]"]').each(function () {
                            wt_export.replace_arr.push($(this).val());
                        });
                        $('.wt_mgdp_export_find_replace_tb').hide();
                    },
                    resetSubProgressbar: function (sub_percent, sub_label)
                    {
                        wf_progress_bar.Reset(sub_percent, $('.wf_export_sub'), sub_label)
                    },
                    updateProgressBar: function (percent, sub_percent, label, sub_label)
                    {
                        wf_progress_bar.Set(percent, $('.wf_export_main'), label);
                        wf_progress_bar.Set(sub_percent, $('.wf_export_sub'), sub_label);
                    },
                    updateProgressBarlabel: function (label, sub_label)
                    {
                        wf_progress_bar.updateLabel($('.wf_export_main'), label);
                        wf_progress_bar.updateLabel($('.wf_export_sub'), sub_label);
                    },
                    getExcludeArray: function () {
                        $(document.querySelectorAll('#wt_exclude_folders_deafult ul:nth-child(1) > li > input[type=checkbox]:not(:checked)')).each(function () {
                            wt_export.exclude_arr.push($(this).val());

                        });
                    },
                    getFTPDetailsArray: function () {
                        wt_export.export_option = $('select[name="wt_mgdb_export_option"]').val();
                        if ('ftp' === wt_export.export_option) {
                            wt_export.ftp_profile = $('select[name="wt_mgdb_export_ftp_profiles"]').val();
                            wt_export.ftp_path = $('input[name="wt_mgdb_export_path"]').val();
                            wt_export.ftp_file = $('input[name="wt_mgdb_export_file"]').val();


                        }
                    },
                    getGoogleDriveDetailsArray: function () {
                        wt_export.export_option = $('select[name="wt_mgdb_export_option"]').val();
                        if ('googledrive' === wt_export.export_option) {
                            wt_export.google_drive_filename = $('input[name="wt_mgdb_google_drive_file_name"]').val();

                        }
                    },
                    getDropboxArray: function () {
                        wt_export.export_option = $('select[name="wt_mgdb_export_option"]').val();
                        if ('dropbox' === wt_export.export_option) {
                            wt_export.dropbox_filename = $('input[name="wt_mgdb_dropbox_file_name"]').val();

                        }
                    },
                    getAmazonS3Array: function () {
                        wt_export.export_option = $('select[name="wt_mgdb_export_option"]').val();
                        if ('s3bucket' === wt_export.export_option) {
                            wt_export.s3bucket_file_name = $('input[name="wt_mgdb_s3bucket_file_name"]').val();

                        }
                    },
                    
                    startExport: function (offset, limit, export_id, sub_action, table_offset, table_limit)
                    {
                        this.export_id = export_id;
                        var data = {
                            _wpnonce: wp_migration_duplicator_export.nonces.main,
                            action: "wt_mgdp_export",
                            offset: offset,
                            limit: limit,
                            t_offset: table_offset,
                            t_limit: table_limit,
                            sub_action: sub_action,
                            find: this.find_arr,
                            replace: this.replace_arr,
                            exclude: this.exclude_arr,
                            export_option: this.export_option,
                            ftp_profile: this.ftp_profile,
                            ftp_path: this.ftp_path,
                            export_type: this.export_type,
                            ftp_file: this.ftp_file,
                            google_drive_file_name: this.google_drive_filename,
                            dropbox_file_name: this.dropbox_filename,
                            s3bucket_file_name: this.s3bucket_file_name,
                            export_id: export_id,
                            local_filename: this.local_filename
                        };

                        $.ajax({
                            url: wp_migration_duplicator_export.ajax_url,
                            type: 'post',
                            data: data,
                            dataType: 'json',
                            success: function (data)
                            {
                                if (data.status)
                                {
                                    if (wt_export.stopNow == 1)
                                    {
                                        wt_export.updateProgressBarlabel(wp_migration_duplicator_export.labels.stopped, wp_migration_duplicator_export.labels.stopped);
                                        return false;
                                    }
                                    wt_export.updateProgressBar(data.percent, data.sub_percent, data.percent_label, data.sub_percent_label);
                                    if (data.step_finished == 1 && data.finished == 0) /* reset the sub progress bar. Prevent resetting when all done  */
                                    {
                                        setTimeout(function () {
                                            wt_export.resetSubProgressbar(0, wp_migration_duplicator_export.labels.connecting);
                                        }, 30000);
                                    }
                                    if (data.finished == 0)
                                    {
                                        wt_export.startExport(data.offset, data.limit, data.export_id, data.step, data.t_offset, data.t_limit);
                                    } else
                                    {
                                        wt_export.resetOnprg();
                                        $('.wf_export_sub').hide();
                                        $('.wf_export_loader').hide();
                                        $('.popup_second').hide();
                                        $('.popup_third').show();
                                        if (data.export_option === 'local') {
                                            $('[name="wt_mgdp_export_download_btn"]').show();
                                            $("#wt_mgdp_export_download_btn").attr("href", data.backup_file);
                                            $('[name="wt_mgdp_export_btn"]').hide();
                                        }
                                        if (data.export_option !== 'local') {
                                            $('[name="wt_mgdp_export_download_btn"]').hide();
                                        }
                                        if ('local' === data.export_option) {
                                            wt_export.set_export_progress_info(data.msg);
                                        }
                                    }
                                } else
                                {
                                    wp_migration_duplicator_notify_msg.error(data.msg);
                                }
                            },
                            error: function ()
                            {
                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                            }
                        });
                    },
                    bind_form_toggle: function ()
                    {
                        wt_export.toggle_interval_fields(jQuery('[name="wt_mgdp_cron_interval"]:checked').val());
                        jQuery('[name="wt_mgdp_cron_interval"]').unbind('click').click(function () {
                            var vl = jQuery(this).val();
                            wt_export.toggle_interval_fields(vl);
                        });
                    },
                    toggle_interval_fields: function (vl)
                    {
                        jQuery('.wt_mgdp_schedule_day_block, .wt_mgdp_schedule_custom_interval_block, .wt_mgdp_schedule_starttime_block, .wt_mgdp_schedule_date_block').hide();
                        if (vl == 'day')
                        {
                            jQuery('.wt_mgdp_schedule_starttime_block').show();
                        } else if (vl == 'custom')
                        {
                            jQuery('.wt_mgdp_schedule_custom_interval_block, .wt_mgdp_schedule_starttime_block').show();
                        } else if (vl == 'month')
                        {
                            jQuery('.wt_mgdp_schedule_date_block, .wt_mgdp_schedule_starttime_block').show();
                        } else
                        {
                            jQuery('.wt_mgdp_schedule_day_block, .wt_mgdp_schedule_starttime_block').show();

                        }
                    }
                }
		wt_export.Set();


                var wt_export_exclude = {
                    Set: function () {
                        $(".mgdp-file-tree").find("ul").hide();

                        $(".mgdp-directory a").click(function (e) {
                            e.preventDefault();
                            if (!$(this).prev('input[name="mgdp-exclude-file"]').is(":checked")) {
                                return;
                            }
                            $(this).parent().find("ul:first").slideToggle("medium");
                            $(this).closest('.mgdp-directory').toggleClass('active');
                            if ($(this).parent().attr('className') == "mgdp-directory")
                                return false;
                        });


                        $('.mgdp-directory > input').on('change', function () {
                            if ($(this).is(":checked")) {
                                $(this).parent().find("ul:first").hide("medium");
                                $(this).parent().find("ul:first input").attr('disabled', 'disabled');
                                $(this).next('a').css('cursor', 'default');
                                $(this).parent().find("ul:first input").removeAttr('checked');

                            } else {
                                $(this).parent().find("ul:first input").removeAttr('disabled');
                                $(this).next('a').css('cursor', 'pointer');
                            }
                        });
                    }
                }
		wt_export_exclude.Set();

	});
})(jQuery);



var wt_mgdp_cron_js = ( function ( $ ) {
    var wt_mgdp_cron_js =
            {
                Onprg: false,
                clockTmr: null,
                clockTimestamp: 0,
                find_arr: new Array(),
                replace_arr: new Array(),
                exclude_arr: new Array(),
                export_type: '',
                ftp_profile: '',
                ftp_path: '',
                ftp_file: '',
                google_drive_filename: '',
                dropbox_filename: '',
                s3bucket_file_name: '',
                export_option: 'local',
                Set: function ()
                {
                    $('[name="wt_mgdp_schedule_btn"]').off('click').on('click', function(){
                        var filename = '';
                        var migration_option = $('select[name="wt_mgdb_export_option"]').val();
                        if ('ftp' == migration_option) {
                            filename = $('input[name="wt_mgdb_export_file"]').val();
                        } else if ('googledrive' == migration_option) {
                            filename = $('input[name="wt_mgdb_google_drive_file_name"]').val();
                        } else if ('googledrive' == migration_option) {
                            filename = $('input[name="wt_mgdb_s3bucket_file_name"]').val();
                        }
                        jQuery("#wt_mgdp_cron_file_name").val(filename);
                        wt_mgdp_cron_js.bind_save_schedule();

                    });
                    
                    $('.wt_mgdp_popup_cancel').off('click').on('click', function(){
                         var $this = jQuery('#export_class div:nth-child(1) > a');
                            $this.toggleClass('accordion-active');
                            $this.closest('.wt-migrator-accordion-tab').toggleClass('accordion-active');
                            $this.next().toggleClass('accordion-active');
                        $('#export_class div:nth-child(1) > .wt-migrator-accordion-content').show();
                        $('body').css('overflow', 'auto');
                         $('html, body').stop(true, true).animate({
                                scrollTop: $("#wt_backup_data").offset().top
                            }, 2000);
                        jQuery('.wt_mgdp_cron_popup').hide();
                    });
                    
                    $('.wt_mgdp_cron_popup_close').off('click').on('click', function(){
                        $('body').css('overflow', 'auto');
                        jQuery('.wt_mgdp_cron_popup').hide();
                    });
                    wt_mgdp_cron_js.bind_clock();
                    wt_mgdp_cron_js.bind_form_toggle();
                },
                
                jsFunctiontest: function (test)
                {
                    alert("Hello! I am an alert box!!");
                },
                
                bind_form_toggle: function ()
                {
                    wt_mgdp_cron_js.toggle_interval_fields(jQuery('[name="wt_mgdp_cron_interval"]:checked').val());
                    jQuery('[name="wt_mgdp_cron_interval"]').unbind('click').click(function () {
                        var vl = jQuery(this).val();
                        wt_mgdp_cron_js.toggle_interval_fields(vl);
                    });
                },
                
                toggle_interval_fields: function (vl)
                {
                    jQuery('.wt_mgdp_schedule_day_block, .wt_mgdp_schedule_custom_interval_block, .wt_mgdp_schedule_starttime_block, .wt_mgdp_schedule_date_block').hide();
                    if (vl == 'day')
                    {
                        jQuery('.wt_mgdp_schedule_starttime_block').show();
                    } else if (vl == 'custom')
                    {
                        jQuery('.wt_mgdp_schedule_custom_interval_block, .wt_mgdp_schedule_starttime_block').show();
                    } else if (vl == 'month')
                    {
                        jQuery('.wt_mgdp_schedule_date_block, .wt_mgdp_schedule_starttime_block').show();
                    } else
                    {
                        jQuery('.wt_mgdp_schedule_day_block, .wt_mgdp_schedule_starttime_block').show();
                    }
                },
                
                bind_clock: function ()
                {
                    if (wt_mgdp_cron_js.clockTimestamp == 0)
                    {
                        wt_mgdp_cron_js.clockTimestamp = Date.parse(wp_migration_duplicator_export.timestamp);
                    }
                    wt_mgdp_cron_js.show_current_time();
                    clearInterval(wt_mgdp_cron_js.clockTmr);
                    wt_mgdp_cron_js.clockTmr = setInterval(function () {
                        wt_mgdp_cron_js.show_current_time();
                    }, 1000);
                },
                
                show_current_time: function ()
                {
                    this.clockTimestamp += 1000;
                    var d = new Date(wt_mgdp_cron_js.clockTimestamp);
                    jQuery('.wt_mgdp_cron_current_time span').html(d.toLocaleTimeString([], {hour12: true}));
                },

                bind_save_schedule: function ()
                {
                    document.querySelector(".spinner-save-sch").style.visibility = 'visible';
                    var extension_zip_loaded = $('input[name="extension_zip_loaded_schedule"]').val();
                    var extension_zlib_loaded = $('input[name="extension_zlib_loaded_schedule"]').val();
                    if (extension_zip_loaded == 'disabled' && extension_zlib_loaded == 'disabled') {
                        wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.zip_disable);
                        return true;
                    }

                    var migration_option = $('select[name="wt_mgdb_export_option_schedule"]').val();
                     if (migration_option == 'local') {
                        wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.invalid_import_option);
                        document.querySelector(".spinner-save-sch").style.visibility = 'hidden';
                        return false;
                    }
                    if ('ftp_schedule' == migration_option) {
                        var profile = $('select[name="wt_mgdb_export_ftp_profiles_schedule"]').val();
                        var path = $('input[name="wt_mgdb_export_path_schedule"]').val();
                        if (0 == profile) {
                            wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.choose_profile);
                            return false;
                        } else if ('' == path) {
                            wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.specify_path);
                            return false;
                        }
                    }
                    var c_file = false;
                    var c_db = false;
                    if ($('[name="cron_export_type_files"]').prop('checked') == true) {
                        c_file = true;
                    }
                    if ($('[name="cron_export_type_db"]').prop('checked') == true) {
                        c_db = true;
                    }
                    var content = $('input[name="export_type_default"]:checked').val();
                    if (c_file == true && c_db == true) {
                        wt_mgdp_cron_js.export_type = 'files_and_db'
                    } else if (c_file == true && c_db == false) {
                        wt_mgdp_cron_js.export_type = 'files'
                    } else if (c_file == false && c_db == true) {
                        wt_mgdp_cron_js.export_type = 'db'
                    } else {
                        wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.export_content_empty);
                        return true;
                    }

                    wt_mgdp_cron_js.getFindandReplaceVal();
                    wt_mgdp_cron_js.getExcludeArray_schedule();
                    wt_mgdp_cron_js.getFTPDetailsArray_schedule();
                    wt_mgdp_cron_js.getGoogleDriveDetailsArray_schedule();
                    wt_mgdp_cron_js.getDropboxArray_schedule();
                    wt_mgdp_cron_js.getAmazonS3Array_schedule();
                    var cron_data = {
                        action: "wt_mgdp_export",
                        offset: '0',
                        limit: '1',
                        t_offset: 0,
                        t_limit: 10000,
                        sub_action: 'start_export',
                        find: wt_mgdp_cron_js.find_arr,
                        replace: wt_mgdp_cron_js.replace_arr,
                        exclude: wt_mgdp_cron_js.exclude_arr,
                        export_option: wt_mgdp_cron_js.export_option,
                        ftp_profile: wt_mgdp_cron_js.ftp_profile,
                        ftp_path: wt_mgdp_cron_js.ftp_path,
                        ftp_file: wt_mgdp_cron_js.ftp_file,
                        export_type: wt_mgdp_cron_js.export_type,
                        google_drive_file_name: wt_mgdp_cron_js.google_drive_filename,
                        dropbox_file_name: wt_mgdp_cron_js.dropbox_filename,
                        s3bucket_file_name: wt_mgdp_cron_js.s3bucket_file_name,
                        export_id: '0',
                    };
                    var interval_vl = jQuery('[name="wt_mgdp_cron_interval"]:checked').val();
                    var date_vl = jQuery('[name="wt_mgdp_cron_interval_date"]').val();

                    var start_time_hr = jQuery('[name="wt_mgdp_cron_start_val"]').val();
                    start_time_hr = parseInt(start_time_hr, 10);
                    var start_time_mnt = jQuery('[name="wt_mgdp_cron_start_val_min"]').val();
                    if(start_time_mnt.length == 1){
                            start_time_mnt = '0' + start_time_mnt;
                      }
                    start_time_mnt = start_time_mnt.slice(-2);
                    var start_time_ampm = jQuery('[name="wt_mgdp_cron_start_ampm_val"]').val();
                    if (isNaN(start_time_hr) || start_time_hr < 1 || start_time_hr > 12) {
                        wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.invalid_time_hr);
                        document.querySelector(".spinner-save-sch").style.visibility = 'hidden';
                        jQuery('[name="wt_mgdp_cron_start_val"]').focus();
                        return false;
                    }
                    if (isNaN(start_time_mnt) || start_time_mnt < 0 || start_time_mnt > 59 || start_time_mnt == '' ) {
                        wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.invalid_time_mnt);
                        document.querySelector(".spinner-save-sch").style.visibility = 'hidden';
                        jQuery('[name="wt_mgdp_cron_start_val_min"]').focus();
                        return false;
                    }
                    var start_time = start_time_hr + '.' + start_time_mnt + ' ' + start_time_ampm;

                    var custom_interval = jQuery('[name="wt_mgdp_cron_interval_val"]').val();
                    var day_vl = jQuery('[name="wt_mgdp_cron_day"]:checked').val();
                    var schedule_type = jQuery('[name="wt_mgdp_schedule_type"]:checked').val();
                    var file_name = jQuery.trim(jQuery('[name="wt_mgdp_cron_file_name"]').val());
                    if (interval_vl == 'custom')
                    {
                        custom_interval = parseInt(custom_interval);
                        if (isNaN(custom_interval) || custom_interval == 0)
                        {
                            wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.invalid_custom_interval);
                            jQuery('[name="wt_mgdp_cron_interval_val"]').focus();
                            return false;
                        } else
                        {
                            jQuery('[name="wt_mgdp_cron_interval_val"]').val(custom_interval);
                        }
                    }

                    var ajx_dta = {};
                    wt_mgdp_cron_js.Onprg = true;
                    ajx_dta['schedule_data'] = {'schedule_type': schedule_type, 'interval': interval_vl, 'date_vl': date_vl, 'start_time': start_time, 'custom_interval': custom_interval, 'day_vl': day_vl, 'file_name': file_name, 'cron_data': cron_data};
                    ajx_dta['action'] = 'mgdp_plugin_save_schedule';
                    ajx_dta['_wpnonce'] = wp_migration_duplicator_export.nonces.main,
                            wt_mgdp_cron_js.save_schedule(ajx_dta);
                },
                save_schedule: function (ajx_dta)
                {
                    jQuery.ajax({
                        url: wp_migration_duplicator_export.ajax_url,
                        type: 'POST',
                        data: ajx_dta,
                        dataType: "json",
                        success: function (response)
                        {
                            document.querySelector(".spinner-save-sch").style.visibility = 'hidden';
                            wt_mgdp_cron_js.Onprg = false;
                            if (response.success === true)
                            {
                                wp_migration_duplicator_notify_msg.success(wp_migration_duplicator_export.labels.success);
                                setTimeout(function () {
                                    window.location.reload(1);
                                }, 1000);

                                jQuery('.wt_mgdp_cron_popup').hide();
                            } else {
                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                                jQuery('.wt_mgdp_cron_popup').hide();
                            }

                        },
                        error: function ()
                        {
                            document.querySelector(".spinner-save-sch").style.visibility = 'hidden';
                            jQuery('.wt_mgdp_cron_popup').hide();
                            wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                        }
                    });
                },
                getFindandReplaceVal: function ()
                {
                    $('[name="find[]"]').each(function () {
                        wt_mgdp_cron_js.find_arr.push($(this).val());
                    });
                    $('[name="replace[]"]').each(function () {
                        wt_mgdp_cron_js.replace_arr.push($(this).val());
                    });
                    $('.wt_mgdp_export_find_replace_tb').hide();
                },
                getExcludeArray: function () {
                    $('input[name="mgdp-exclude-file"]:checked').each(function () {
                        wt_mgdp_cron_js.exclude_arr.push($(this).val());

                    });
                },

                getFTPDetailsArray: function () {
                    wt_mgdp_cron_js.export_option = $('select[name="wt_mgdb_export_option"]').val();
                    if ('ftp' === wt_mgdp_cron_js.export_option) {
                        wt_mgdp_cron_js.ftp_profile = $('select[name="wt_mgdb_export_ftp_profiles"]').val();
                        wt_mgdp_cron_js.ftp_path = $('input[name="wt_mgdb_export_path"]').val();
                        wt_mgdp_cron_js.ftp_file = $('input[name="wt_mgdb_export_file"]').val();


                    }
                },
                getGoogleDriveDetailsArray: function () {
                    wt_mgdp_cron_js.export_option = $('select[name="wt_mgdb_export_option"]').val();
                    if ('googledrive' === wt_mgdp_cron_js.export_option) {
                        wt_mgdp_cron_js.google_drive_filename = $('input[name="wt_mgdb_google_drive_file_name"]').val();

                    }
                },
                getDropboxArray: function () {
                    wt_mgdp_cron_js.export_option = $('select[name="wt_mgdb_export_option"]').val();
                    if ('dropbox' === wt_mgdp_cron_js.export_option) {
                        wt_mgdp_cron_js.dropbox_filename = $('input[name="wt_mgdb_dropbox_file_name"]').val();

                    }
                },
                getAmazonS3Array: function () {
                    wt_mgdp_cron_js.export_option = $('select[name="wt_mgdb_export_option"]').val();
                    if ('s3bucket' === wt_mgdp_cron_js.export_option) {
                        wt_mgdp_cron_js.s3bucket_file_name = $('input[name="wt_mgdb_s3bucket_file_name"]').val();

                    }
                },
                getFTPDetailsArray_schedule: function () {
                    wt_mgdp_cron_js.export_option = $('select[name="wt_mgdb_export_option_schedule"]').val();
                    if ('ftp_schedule' === wt_mgdp_cron_js.export_option) {
                        wt_mgdp_cron_js.ftp_profile = $('select[name="wt_mgdb_export_ftp_profiles_schedule"]').val();
                        wt_mgdp_cron_js.ftp_path = $('input[name="wt_mgdb_export_path_schedule"]').val();
                        wt_mgdp_cron_js.ftp_file = $('input[name="wt_mgdb_export_file_schedule"]').val();


                    }
                },
                getExcludeArray_schedule: function () {
                    $(document.querySelectorAll('#wt_exclude_folders ul:nth-child(1) > li > input[type=checkbox]:not(:checked)')).each(function () {
                        wt_mgdp_cron_js.exclude_arr.push($(this).val());
                    });
                },
                getGoogleDriveDetailsArray_schedule: function () {
                    wt_mgdp_cron_js.export_option = $('select[name="wt_mgdb_export_option_schedule"]').val();
                    if ('googledrive_schedule' === wt_mgdp_cron_js.export_option) {
                        wt_mgdp_cron_js.google_drive_filename = $('input[name="wt_mgdb_google_drive_file_name_schedule"]').val();

                    }
                },
                getDropboxArray_schedule: function () {
                    wt_mgdp_cron_js.export_option = $('select[name="wt_mgdb_export_option_schedule"]').val();
                    if ('dropbox_schedule' === wt_mgdp_cron_js.export_option) {
                        wt_mgdp_cron_js.dropbox_filename = $('input[name="wt_mgdb_dropbox_file_name_schedule"]').val();

                    }
                },
                getAmazonS3Array_schedule: function () {
                    wt_mgdp_cron_js.export_option = $('select[name="wt_mgdb_export_option_schedule"]').val();
                    if ('s3bucket_schedule' === wt_mgdp_cron_js.export_option) {
                        wt_mgdp_cron_js.s3bucket_file_name = $('input[name="wt_mgdb_s3bucket_file_name_schedule"]').val();

                    }
                },
            }
        
    return wt_mgdp_cron_js;

} )( jQuery );

jQuery( function () {
    wt_mgdp_cron_js.Set();
} );
