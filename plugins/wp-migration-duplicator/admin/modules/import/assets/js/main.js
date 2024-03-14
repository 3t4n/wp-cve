(function ($) {
    'use strict';
    $(function () {

        var wt_import =
                {
                    onPrg: 0,
                    Set: function ()
                    {
                        wt_import.save_settings();
                        wt_mgdp_dropzone.init('mgdp_import_dropzone');
                        wt_mgdp_dropzone.auto_populate();
                        
                        $('.wt_import_mgdp_popup_cancel').off('click').on('click', function(){
                            window.location.reload();
                            jQuery('.wt_import_mgdp_popup_cancel').hide();
                        });

                        $(document).on('click', '.wt_mgdp_delete_backup', function () {
                            if (confirm(wp_migration_duplicator_import.labels.sure))
                            {
                                var export_id = $(this).attr('data-id');
                                var tr = $(this).parents('tr');
                                tr.css({'opacity': .5});

                                var data = {
                                    _wpnonce: wp_migration_duplicator_import.nonces.main,
                                    action: "wt_mgdp_import",
                                    sub_action: 'delete',
                                    export_id: export_id,
                                };

                                $.ajax({
                                    url: wp_migration_duplicator_import.ajax_url,
                                    type: 'post',
                                    data: data,
                                    dataType: 'json',
                                    success: function (data)
                                    {
                                        if (data.status)
                                        {
                                            tr.remove();
                                            if ($('.wt_mgdp_backup_list_table tbody tr').length == 0)
                                            {
                                                $('.wt_mgdp_backup_list_table tbody').html(wt_mgdp_no_bckup_html);
                                            }
                                        } else
                                        {
                                            wp_migration_duplicator_notify_msg.error(data.msg);
                                        }
                                    },
                                    error: function ()
                                    {
                                        wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_backups.labels.error);
                                    }
                                });
                            }
                        });

                        $('[name="wt_mgdp_import_btn"]').off('click').on('click', function(){
                            $('body').css('overflow', 'hidden');
                            var extension_zip_loaded_imp = $('input[name="extension_zip_loaded_imp"]').val();
                            var extension_zlib_loaded_imp = $('input[name="extension_zlib_loaded_imp"]').val();
                            if (extension_zip_loaded_imp == 'disabled' && extension_zlib_loaded_imp == 'disabled') {
                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_import.labels.zip_disable);
                                return true;
                            }
                            if (wt_import.onPrg == 1) {
                                return false;
                            }

                            var import_method = $('select[name="wt_mgdb_import_option"]').val();
                            if ('local' == import_method) {
                                if ($.trim($('[name="attachment_url"]').val()) == '')
                                {
                                    $('.wt_mgdp_import_er').show().find('td').html(wp_migration_duplicator_import.labels.backupfilenotempty);
                                    $('body').css('overflow', 'auto');
                                    return false;
                                }
                            } else if ('ftp' == import_method) {
                                var profile = $('select[name="wt_mgdb_import_ftp_profiles"]').val();
                                var path = $('input[name="wt_mgdb_import_path"]').val();
                                var ftp_file = $('input[name="wt_mgdb_import_ftp_file"]').val();
                                if (0 == profile) {
                                    $('.wt_mgdp_import_er').show().find('td').html(wp_migration_duplicator_import.labels.noprofile);
                                    $('body').css('overflow', 'auto');
                                    return false;
                                }
                                if ('' == path) {
                                    $('.wt_mgdp_import_er').show().find('td').html(wp_migration_duplicator_import.labels.pathrequired);
                                    $('body').css('overflow', 'auto');
                                    return false;
                                }
                            } else if ('googledrive' == import_method) {
                                var filename = $("#wt_mgdb_google_drive_file").val();
                                if ('' == filename) {
                                    $('.wt_mgdp_import_er').show().find('td').html(wp_migration_duplicator_import.labels.nofilename);
                                    $('body').css('overflow', 'auto');
                                    return false;
                                }
                            } else if ('s3bucket' == import_method) {
                                var filename = $("#wt_mgdb_s3bucket_file").val();
                                if ('' == filename) {
                                    $('.wt_mgdp_import_er').show().find('td').html(wp_migration_duplicator_import.labels.nofilename);
                                    $('body').css('overflow', 'auto');
                                    return false;
                                }
                            }
                            wt_import.onPrg = 1;
                            $('.spinner').css({'visiblity': 'visible'});
                            //$('[name="wt_mgdp_import_btn"]').css({'opacity': '.5', 'cursor': 'not-allowed'});
                            jQuery('.wt_mgdp_cron_popup').show();
                            $('.import_popup_second').show();
                            $('.wt_mgdp_import_log_main, .wt_mgdp_import_loglist_main').show();
                            $('.wf_import_loader').show();
                            $('.wt_mgdp_import_form, .wt_info_box').hide();
                            $('.wt_mgdp_import_loglist_inner').html('');
                            wt_import.updateLog(wp_migration_duplicator_import.labels.connecting, wp_migration_duplicator_import.labels.connecting);
                            wt_import.startImport('fetch_file', 0, 1);

                        });

                        $('.wt_mgdp__start_new_import').off('click').on('click', function(){
                            $('.wt_mgdp_dropzone').show();
                            $('.increase_upload_size').show();
                            $('.wt_mgdp_import_attachment_url, .wt_mgdp_import_loglist_inner').html('');
                            $('.wt_mgdp_import_log_main, .wt_mgdp_import_loglist_main, .wt_mgdp__start_new_import').hide();
                            $('.wt_mgdp_import_form, .wt_info_box').show();
                            $('[name="wt_mgdp_import_btn"]').css({'opacity': 1, 'cursor': 'pointer'}).show();
                        });
                    },
                    
                    updateLog: function (label, sub_label)
                    {
                        $('.wt_mgdp_import_log_main').html(label);
                        $('.wt_mgdp_import_loglist_inner').append(sub_label);
                    },
                    
                    save_settings: function ()
                    {
                        $('[name="wt_mgdp_save_import_settings_btn"]').off('click').on('click', function(){
                            document.querySelector(".spinner-save-import").style.visibility = 'visible';
                            var im_data_size_per_req = $('input[name="im_data_size_per_req"]').val();
                            var im_db_file_per_req = $('input[name="im_db_file_per_req"]').val();
                            var ajx_dta = {};
                            ajx_dta['settings_data'] = {'im_data_size_per_req': im_data_size_per_req, 'im_db_file_per_req': im_db_file_per_req, };
                            ajx_dta['action'] = 'mgdp_plugin_save_import_settings';
                            ajx_dta['_wpnonce'] = wp_migration_duplicator_import.nonces.main,
                                    jQuery.ajax({
                                        url: wp_migration_duplicator_import.ajax_url,
                                        type: 'POST',
                                        data: ajx_dta,
                                        dataType: "json",
                                        success: function (response)
                                        {
                                            document.querySelector(".spinner-save-import").style.visibility = 'hidden';
                                            if (response.success === true)
                                            {
                                                wp_migration_duplicator_notify_msg.success(wp_migration_duplicator_export.labels.success);

                                            } else {
                                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                                            }

                                        },
                                        error: function ()
                                        {
                                            document.querySelector(".spinner-save-import").style.visibility = 'hidden';
                                            wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                                        }
                                    });
                        });

                    },
                    
                    restoreImportScreen: function ()
                    {
                        wt_import.onPrg = 0;
                        $('.import_popup_second').hide();
                        $('.import_info').hide();
                        $('.import_popup_third').show();

                    },
                    
                    startImport: function (sub_action, offset, limit)
                    {
                        var data = {
                            _wpnonce: wp_migration_duplicator_import.nonces.main,
                            action: "wt_mgdp_import",
                            sub_action: sub_action,
                            attachment_url: $('[name="attachment_url"').val(),
                            import_method: $('select[name="wt_mgdb_import_option"]').val(),
                            ftp_profile: $('select[name="wt_mgdb_import_ftp_profiles"]').val(),
                            ftp_path: $('input[name="wt_mgdb_import_path"').val(),
                            ftp_file: $('input[name="wt_mgdb_import_ftp_file"').val(),
                            google_drive_file: $("#wt_mgdb_google_drive_file").val(),
                            wt_mgdb_dropbox_file: $('input[name="wt_mgdb_dropbox_file"').val(),
                            wt_mgdb_s3bucket_file: $("#wt_mgdb_s3bucket_file").val(),
                            offset: offset,
                            limit: limit,

                        };
                        $.ajax({
                            url: wp_migration_duplicator_import.ajax_url,
                            type: 'post',
                            data: data,
                            dataType: 'json',
                            success: function (data)
                            {
                                wt_import.updateLog(data.label, data.sub_label);
                                if (data.status)
                                {
                                    if (data.finished == 0)
                                    {
                                        wt_import.startImport(data.step, data.offset, data.limit);
                                    } else
                                    {
                                        $('.wf_import_loader').hide();
                                        wt_import.restoreImportScreen();
                                    }
                                } else
                                {
                                    wp_migration_duplicator_notify_msg.error(data.msg);
                                    $('body').css('overflow', 'auto');

                                    jQuery('.wt_mgdp_cron_popup').hide();
                                    $('.import_popup_second').hide();
                                    $('.import_info').hide();
                                }
                            },
                            error: function ()
                            {
                                $('body').css('overflow', 'auto');
                                wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                            }
                        });
                    }
                }
        wt_import.Set();
    });
})(jQuery);


/**
 *	Dropzone initaiting section
 * 	More info at [www.dropzonejs.com](http://www.dropzonejs.com)
 */
var wt_mgdp_dropzone =
        {
            elm: null,
            old_file: false,
            Set: function ()
            {
                if (typeof Dropzone === 'undefined') {
                    return false;
                }
                Dropzone.autoDiscover = false;
                this.auto_populate();
            },
            auto_populate: function ()
            {
                var template_val = jQuery.trim(jQuery('#local_file').val());
                if (template_val != "")
                {
                    var file_name = template_val.split('/').pop();
                    this.set_success(file_name);
                }
            },
            set_success: function (file_name)
            {
                jQuery(".wt_mgdp_dz_file_success").html(wp_migration_duplicator_import.labels.upload_done);
                jQuery(".wt_mgdp_dz_file_success_msg").html(wp_migration_duplicator_import.labels.upload_done_msg);
                jQuery(".wt_mgdp_dz_remove_link").html(wp_migration_duplicator_import.labels.remove);
                jQuery(".wt_mgdp_dz_file_name").html(file_name);
                jQuery(".dz-message").css({'margin-top': '60px'});

                /* register file deleting event */
                wt_mgdp_dropzone.remove_file();
            },
            init: function (elm_id)
            {
                if (typeof Dropzone === 'undefined') {
                    return false;
                }
                this.elm = jQuery("#" + elm_id);
                var drop_zone_obj = new Dropzone("#" + elm_id,{
                url: wp_migration_duplicator_import.ajax_url,
                chunking: true,
                chunkSize: 2*1024*1024,
                forceChunking: true,
                parallelChunkUploads: false,
                retryChunks: true,
                retryChunksLimit: 3,
                maxFiles: 1,
                timeout: 36000000,
                createImageThumbnails: false,
                maxFilesize: wp_migration_duplicator_import.max_import_file_size,
                dictDefaultMessage: wp_migration_duplicator_import.labels.drop_upload,
                dictInvalidFileType: wp_migration_duplicator_import.labels.invalid_file,
                dictResponseError: wp_migration_duplicator_import.labels.server_error,
                paramName: 'wt_mgdp_import_file',
                params: function (files, xhr, chunk) {
                    if (chunk) {
                        return {
                            _wpnonce: wp_migration_duplicator_import.nonces.main,
                            action: "wt_mgdp_import",
                            sub_action: 'upload_import_file',
                            data_type: 'json',
                            file_url: '',
                            dzUuid: chunk.file.upload.uuid,
                            dzChunkIndex: chunk.index,
                            dzTotalFileSize: chunk.file.size,
                            dzCurrentChunkSize: chunk.dataBlock.data.size,
                            dzTotalChunkCount: chunk.file.upload.totalChunkCount,
                            dzChunkByteOffset: chunk.index * this.options.chunkSize,
                            dzChunkSize: this.options.chunkSize,
                            dzFilename: chunk.file.name,
                        };
                    }
                }, 
                previewTemplate: "<div class=\"dz-preview dz-file-preview\">\n <div class=\"dz-upload-info\"></div> \n <div class=\"dz-details\">\n  <div class=\"dz-filename\"><span data-dz-name></span></div>\n </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n </div>",
            
             });

                drop_zone_obj.on("addedfile", function (file) {

                    jQuery(".wt_mgdp_file_upload_size").hide();
                    jQuery(".dz-upload-info").html(wp_migration_duplicator_import.labels.uploading);
                    jQuery(".dz-message").css({'margin-top': '85px'});
                    jQuery(".dz-upload-info").css({'font-size': '14px','font-weight': 600});

                    var dropzone_target = wt_mgdp_dropzone.elm.attr('wt_mgdp_dropzone_target');
                    var dropzone_target_elm = jQuery(dropzone_target);
                    if (dropzone_target_elm.length > 0)
                    {
                        var file_url = dropzone_target_elm.val();
                        if (file_url != "")
                        {
                            drop_zone_obj.options.params['file_url'] = file_url; /* this is to remove the already uploaded file */
                        }
                    }

                });

                drop_zone_obj.on("dragstart", function (file) {
                    wt_mgdp_dropzone.elm.addClass('wt_drag_start');
                });

                drop_zone_obj.on("dragover", function (file) {
                    wt_mgdp_dropzone.elm.addClass('wt_drag_start');
                });

                drop_zone_obj.on("dragleave", function (file) {
                    wt_mgdp_dropzone.elm.removeClass('wt_drag_start');
                });

                drop_zone_obj.on("drop", function (file) {
                    wt_mgdp_dropzone.elm.removeClass('wt_drag_start');
                });

                drop_zone_obj.on("dragend", function (file) {
                    wt_mgdp_dropzone.elm.removeClass('wt_drag_start');
                });

                drop_zone_obj.on("fallback", function (file) {
                    wt_mgdp_dropzone.elm.html(wt_mgdp_import_basic_params.msgs.outdated);
                    return null;
                });
                drop_zone_obj.on("error", function (file, message) {
                    drop_zone_obj.removeFile(file);
                    wp_migration_duplicator_notify_msg.error(message);
                });

                drop_zone_obj.on("success", function (file, response) {
                    var response  = file.xhr.response;
                    var file_name = file.name;

                    /* remove file obj */
                    drop_zone_obj.removeFile(file);


                    if (wt_mgdp_dropzone.isJson(response))
                    {
                        response = JSON.parse(response);
                        if (response.status == 1)
                        {
                            wt_mgdp_dropzone.set_success(file_name);
                            jQuery('[name="wt_mgdp_local_file"').val(response.url);
                            jQuery('[name="attachment_url"').val(response.url);
                            var dropzone_target = wt_mgdp_dropzone.elm.attr('wt_mgdp_dropzone_target');
                            var dropzone_target_elm = jQuery(dropzone_target);
                            if (dropzone_target_elm.length > 0)
                            {
                                dropzone_target_elm.val(response.url);

                            }
                        } else
                        {
                            wp_migration_duplicator_notify_msg.error(response.msg);
                        }
                    } else
                    {
                        wp_migration_duplicator_notify_msg.error(wp_migration_duplicator_export.labels.error);
                    }
                });
            },
            remove_file: function ()
            {
                jQuery('.wt_mgdp_dz_remove_link').unbind('click').click(function (e) {
                    e.stopPropagation();
                    jQuery(".wt_mgdp_file_upload_size").show();
                    var dropzone_target = wt_mgdp_dropzone.elm.attr('wt_mgdp_dropzone_target');
                    var mapping_profile = jQuery('.wt-iew-import-template-sele').val();
                    var dropzone_target_elm = jQuery(dropzone_target);
                    if (dropzone_target_elm.length > 0)
                    {
                        var file_url = dropzone_target_elm.val();
                        if (file_url != "")
                        {
                            dropzone_target_elm.val('');
                            jQuery(".wt_mgdp_dz_file_success, .wt_mgdp_dz_remove_link, .wt_mgdp_dz_file_name, .wt_mgdp_dz_file_success_msg").html('');
                            jQuery(".dz-message").css({'margin-top': '85px'});

                            jQuery.ajax({
                                type: 'POST',
                                url: wp_migration_duplicator_import.ajax_url,
                                data: {
                                    '_wpnonce': wp_migration_duplicator_import.nonces.main,
                                    'action': "wt_mgdp_import",
                                    'sub_action': 'delete_import_file',
                                    'mapping_profile': mapping_profile,
                                    'data_type': 'json',
                                    'file_url': file_url,
                                },
                                dataType: 'json'

                            });
                        }
                    }
                });
            },
            isJson: function (str)
            {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            }
        }
wt_mgdp_dropzone.Set();
