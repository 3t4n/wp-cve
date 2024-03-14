<div class="tab-pane main-tabpane active in" id="main" role="tabpanel" aria-labelledby="main-tab">

    <div class="main-inner-content shadowed">
        <div>
            <div class="accordion-pane">
                <section data-id="1">

                    <div class="card-header" id="headingOne">
                        <button class="" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <h2 style="float: left;"><?php _e('Template', 'mpg'); ?></h2>
                            <div class="collapse-actions"><a href="#" class="delete-project" style="display:none;"><?php _e('Delete template', 'mpg'); ?></a><i class="fa fa-chevron-down"></i></div>
                        </button>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">

                            <form class="main-template-info">
                                <!-- Project name -->
                                <div class="sub-section">
                                    <div class="block-with-tooltip">

                                        <p><?php _e('Project name', 'mpg'); ?></p>
                                        <input type="text" class="project-name" style="width:100%" required placeholder="<?php _e('Template Name', 'mpg'); ?>">

                                        <div class="tooltip-circle" data-tippy-content="<?php _e('Name your template so it’s easy for you to recognize what content you are trying to generate. This isn’t visible anywhere else.', 'mpg'); ?>">
                                            <i class="fa fa-question"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="sub-section">

                                    <!-- Template -->
                                    <h4><?php _e('Template', 'mpg'); ?></h4>

                                    <div class="block-with-tooltip" style="margin-bottom:20px">
                                        <p><?php _e('Entity type', 'mpg'); ?></p>
                                        <select id="mpg_entity_type_dropdown" style="width:100%" required>

                                            <option value="" disabled="disabled" selected="selected"><?php _e('Choose entity type', 'mpg'); ?></option>

                                            <?php
                                            foreach ($entities_array as $entity) {
                                                echo '<option value="' . esc_textarea($entity['name']) . '">' . esc_textarea($entity['label']) . '</option>';
                                            }

                                            ?>
                                        </select>
                                        <div class="tooltip-circle" data-tippy-content="<?php _e('Select the type of content for your template. MPG supports all entity types in your Wordpress installation, including posts and pages.', 'mpg'); ?>">
                                            <i class="fa fa-question"></i>
                                        </div>
                                    </div>

                                    <div class="block-with-tooltip" style="margin-bottom:30px">
                                        <p><?php _e('Template', 'mpg'); ?></p>

                                        <select id="mpg_set_template_dropdown" style="width:100%">
                                            <option value="" disabled="disabled" selected="selected" required>
                                                <?php _e('Choose template', 'mpg'); ?></option>
                                        </select>
                                        <div class="tooltip-circle" data-tippy-content="<?php _e('Select the entity you wish to use as a template for the generated content. MPG will replace any shortcodes when accessing the site through generated URL accordingly to your source file.', 'mpg'); ?>"><i class="fa fa-question"></i></div>
                                    </div>

                                    <div class="block-with-tooltip">
                                        <p><?php _e('Apply template if URL contains', 'mpg'); ?></p>
                                        <input type="text" id="mpg_apply_condition" maxlength="199" class="form-control" style="width: 100%" placeholder="<?php _e('Like a ?lang=en or /en/ (optional)', 'mpg'); ?>">
                                        <div class="tooltip-circle" data-tippy-content="<?php _e('URLs related with the template will work ONLY if generated URL will contain specified part. Like a /en/ or ?lang=it', 'mpg'); ?>"><i class="fa fa-question"></i></div>
                                    </div>

                                    <div class="block-with-tooltip" style="margin-top: 15px">
                                        <p><?php _e('Exclude template from crawlers and site loops', 'mpg'); ?></p>

                                        <input type="checkbox" style="width: 15px; margin-right: 400px;" id="mpg_exclude_template_in_robots">

                                        <div class="tooltip-circle" data-tippy-content="<?php _e('It’s is highly suggested to exclude template page from being indexed by search engines as it contains shortcodes. Also, the page/post will be excluded from search results in WordPress, categories and widgets, like a Recent posts. All generated pages will remain visible.', 'mpg'); ?>"><i class="fa fa-question"></i></div>
                                    </div>

                                    <div class="block-with-tooltip" style="margin-top: 15px">
                                        <p><?php _e('Participate in the search?', 'mpg'); ?></p>

                                        <input type="checkbox" style="width: 15px; margin-right: 400px;" id="mpg_participate_in_search">

                                        <div class="tooltip-circle" data-tippy-content="<?php _e('Set the tick to participate generated URLs from this project in search results.', 'mpg'); ?>"><i class="fa fa-question"></i></div>
                                    </div>

                                    <div class="block-with-tooltip" style="margin-top: 15px">
                                        <p><?php esc_html_e( 'Participate in the default loop?', 'mpg' ); ?></p>

                                        <input type="checkbox" style="width: 15px; margin-right: 400px;" id="mpg_participate_in_default_loop">

                                        <div class="tooltip-circle" data-tippy-content="<?php esc_attr_e( 'Set the tick to participate in generated URLs from this project in the WP default post loop.', 'mpg' ); ?>"><i class="fa fa-question"></i></div>
                                    </div>


                                </div>
                                <div class="save-changes-block" style="border-bottom: 1px solid silver;">
                                    <button type="submit" class=" blue-gradient-btn btn btn-primary"><?php _e('Save changes', 'mpg'); ?></button>
                                    <span class="spinner"></span>
                                </div>

                            </form>
                        </div>
                    </div>
                </section>
            </div>

            <div class="accordion-pane">

                <section data-id="2" style="display: none">
                    <div class="card-header" id="headingTwo">
                        <button data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <div class="source-head">
                                <h2><?php _e('Source', 'mpg'); ?></h2>
                            </div>
                            <div class="collapse-actions"><i class="fa fa-chevron-down"></i></div>
                        </button>
                    </div>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <!-- Sources -->
                            <ul class="nav nav-pills data-source" role="tablist">
                                <li class="nav-item col-md-push-3 active">
                                    <a class="nav-link active" id="direct_link-tab" data-toggle="tab" href="#direct_link" role="tab" aria-controls="direct_link" aria-selected="true"><?php _e('Direct link', 'mpg') ?></a>
                                </li>
                                <li class="nav-item col-md-push-3">
                                    <a class="nav-link" id="upload_file-tab" data-toggle="tab" href="#upload_file" role="tab" aria-controls="upload_file" aria-selected="false"><?php _e('Upload file', 'mpg') ?></a>
                                </li>
                            </ul>

                            <div class="tab-content">

                                <div class="tab-pane active in" id="direct_link" role="tabpanel" aria-labelledby="direct_link-tab">

                                    <div class="sources-sub-section">

                                        <?php $is_pro = mpg_app()->is_premium(); ?>

                                        <form class="direct-link-schedule-form" style="width:100%">

                                            <div class="subsection-item">
                                                <p><?php _e('Direct link to source file', 'mpg'); ?></p>

                                                <div class="field-button-tooltip">
                                                    <div class="field-with-tooltip">
                                                        <input type="url" name="direct_link_input" class="form-control" required="required" style="width: 100%" placeholder="<?php _e('https://', 'mpg'); ?>">
                                                        <div class="tooltip-circle" data-tippy-content="<?php _e('Load any Google Sheet or csv that’s available on the internet. Make sure the file has public access.', 'mpg') ?>"><i class="fa fa-question"></i></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="subsection-item worksheet-id" style="opacity: 0; height: 0;">
                                                <p><?php _e('Worksheet ID', 'mpg'); ?></p>

                                                <div class="field-button-tooltip">
                                                    <div class="field-with-tooltip">
                                                        <input type="number" name="worksheet_id" class="form-control" style="width: 100%" placeholder="<?php _e('Like a 123456789', 'mpg'); ?>">
                                                        <div class="tooltip-circle" data-tippy-content="<?php _e('Copy worksheets id from Google Sheets, and paste here. Also, you can leave it field empty', 'mpg') ?>"><i class="fa fa-question"></i></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pass timezone name via JS -->
                                            <input type="hidden" name="mpg_timezone_name" value="">


                                            <!-- Set periodicy -->
                                            <div class="subsection-item">
                                                <p><?php _e('Set periodicity', 'mpg'); ?>
                                                    <?php echo $is_pro ? '' : '<span class="pro-field"><a href="' . esc_url( mpg_app()->get_upgrade_url('SetPeriodicity' ) ) . '" target="_blank">Get Pro <span class="dashicons dashicons-external"></span></a></span>' ?></p>

                                                <div class="field-button-tooltip" style="margin: 2px 0 20px 0; display: flex;">
                                                    <select name="periodicity" <?php echo  $is_pro ? '' : 'disabled="true"'; ?> class="field-with-tooltip" required style="width: 100%; max-width: 415px; margin:0;">
                                                        <option value="now"><?php _e('Live', 'mpg'); ?></option>
                                                        <option value="once"><?php _e('Once', 'mpg'); ?></option>
                                                        <option value="hourly"><?php _e('Hourly', 'mpg'); ?></option>
                                                        <option value="twicedaily"><?php _e('Twice per day', 'mpg'); ?></option>
                                                        <option value="daily"><?php _e('Daily', 'mpg'); ?></option>
                                                        <option value="weekly"><?php _e('Weekly', 'mpg'); ?></option>
                                                        <option value="monthly"><?php _e('Monthly', 'mpg'); ?></option>
                                                    </select>
                                                    <div class="tooltip-circle" style="margin-top: 5px;" data-tippy-content="<?php _e('Set how often MPG will fetch the dataset above.', 'mpg'); ?>"><i class="fa fa-question"></i></div>
                                                </div>
                                            </div>

                                            <!-- Set fetching datetime -->
                                            <div class="subsection-item">
                                                <p><?php _e('First Fetch Date/Time', 'mpg'); ?><?php echo $is_pro ? '' : '<span class="pro-field"><a href="' . esc_url( mpg_app()->get_upgrade_url('FirstFetchDateTime' ) ) . '" target="_blank">Get Pro <span class="dashicons dashicons-external"></span></a></span>' ?></p>

                                                <div class="block-with-tooltip" style="margin-bottom:20px">
                                                    <input class="disabled" name="datetime_upload_remote_file" <?php echo  $is_pro ? '' : 'disabled="true"'; ?> type="text" autocomplete="off">
                                                    <div class="tooltip-circle" data-tippy-content="<?php _e('Set the date and time when MPG should first attempt to fetch your file.', 'mpg'); ?>"><i class="fa fa-question"></i></div>
                                                </div>
                                            </div>

                                            <!-- Notify me -->
                                            <div class="subsection-item">
                                                <div class="block-with-tooltip" style="margin-bottom:20px" data-tippy-content="<?php _e('MPG can send a notification each time after it fetches your dataset. It can be on error or every time it fetches.', 'mpg'); ?>">
                                                    <p><?php _e('Notification', 'mpg'); ?><?php echo $is_pro ? '' : '<span class="pro-field"><a href="' . esc_url( mpg_app()->get_upgrade_url('Notification' ) ) . '" target="_blank">Get Pro <span class="dashicons dashicons-external"></span></a></span>' ?></p>

                                                    <select class="disabled" name="notification_level" <?php echo  $is_pro ? '' : 'disabled="true"'; ?>>
                                                        <option value="do-not-notify"><?php _e('Do not notify', 'mpg'); ?></option>
                                                        <option value="errors-only"><?php _e('Errors only', 'mpg'); ?></option>
                                                        <option value="every-time"><?php _e('Every time', 'mpg'); ?></option>
                                                    </select>
                                                    <div class="tooltip-circle"><i class="fa fa-question"></i></div>
                                                </div>
                                            </div>

                                            <div class="subsection-item">
                                                <div class="block-with-tooltip" style="margin-bottom:20px;align-items: baseline;">
                                                    <p><?php _e('Notification Email', 'mpg'); ?><?php echo $is_pro ? '' : '<span class="pro-field"><a href="' . esc_url( mpg_app()->get_upgrade_url('NotificationEmail' ) ) . '" target="_blank">Get Pro <span class="dashicons dashicons-external"></span></a></span>' ?></p>
                                                    <div class="field-button-tooltip">
                                                        <div class="field-with-tooltip">
                                                            <input class="disabled" name="notification_email" <?php echo  $is_pro ? '' : 'disabled="true"'; ?> type="email" value="<?php echo get_option('admin_email'); ?>">
                                                            <div class="tooltip-circle" data-tippy-content="<?php _e('Specify the email which we shall use to notify you, if opted in.', 'mpg'); ?>"><i class="fa fa-question"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row" style="display: none;">
                                                <div class="col-sm-12 col-md-5">
                                                    <div id="mpg_next_cron_execution"></div>
                                                </div>
                                                <div class="col-sm-12 col-md-1">
                                                    <input type="button" id="mpg_unschedule_task" value="<?php _e('Unschedule', 'mpg'); ?>" class="btn btn-danger">
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-outline-primary use-direct-link-button"><?php _e('Fetch and use', 'mpg'); ?></button>
                                        </form>
                                    </div>

                                </div>


                                <div class="tab-pane fade" id="upload_file" role="tabpanel" aria-labelledby="upload_file-tab">

                                    <form action="">
                                        <div class="sources-sub-section" style="flex-direction: unset">
                                            <p><?php _e('Choose .csv, .xlsx or .ods file from your computer', 'mpg'); ?>
                                            </p>

                                            <div class="field-button-tooltip">

                                                <div class="custom-file mpg_upload_file">
                                                    <div class="col-sm-9">
                                                        <input type="file" name="mpg_upload_file_input" accept=".csv, .ods, .xlsx" class="custom-file-input" id="mpg_upload_file_input" aria-describedby="inputGroupFileAddon04">
                                                        <label class="custom-file-label mpg_upload_file-label" for="mpg_upload_file_input"><?php _e('Click to browse', 'mpg'); ?></label>
                                                    </div>

                                                    <a class="col-ms-3 btn disabled btn-outline-primary" id="mpg_in_use_dataset_link">N/A</a>
                                                </div>


                                                <div id="progress-wrp">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <div class="data-peview-top">
                                <h4><?php _e('Data preview', 'mpg') ?></h4>
                                <p class="summary"><?php _e('[rows] rows / [headers] headers', 'mpg'); ?></p>
                                <a href="#" id="mpg_preview_modal_link"><?php _e('Preview', 'mpg') ?></a>
                            </div>

                            <!-- Modal with preview data -->
                            <div class="modal bd-example-modal-lg" id="mpg_preview_modal" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <table id="mpg_data_full_preview_table" class="display" width="100%">
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="data-table-container">
                                <table id="mpg_dataset_limited_rows_table" class="display" width="100%"></table>
                            </div>


                            <div class="url-generation-top">
                                <div class="source-head">
                                    <h4><?php _e('URL Generation', 'mpg'); ?></h4>
                                    <select id="mpg_main_tab_insert_shortcode_dropdown"></select>
                                </div>
                            </div>
                            <div class="sub-section">
                                <div class="block-with-tooltip">
                                    <p><?php _e('URL Format Template', 'mpg'); ?></p>
                                    <div id="mpg_url_constructor" contenteditable="true"></div>

                                    <div class="tooltip-circle" data-tippy-content="<?php _e('Type in the desired format of the generated URLs. MPG supports any combination of shortcodes, plain text, and separators.', 'mpg'); ?>"><i class="fa fa-question"></i></div>

                                </div>

                                <div class="block-with-tooltip" style="margin-top:20px">
                                    <p><?php _e('Default separator', 'mpg'); ?></p>

                                    <div class="spacers-block">
                                        <div class="spaces-replacer active">-</div>
                                        <div class="spaces-replacer">_</div>
                                        <div class="spaces-replacer">~</div>
                                        <div class="spaces-replacer">.</div>
                                        <div class="spaces-replacer">/</div>
                                        <div class="spaces-replacer">=</div>
                                    </div>

                                    <div class="tooltip-circle" data-tippy-content="<?php _e('The default separator will replace any spaces in your shortcodes when generating URLs. All unsupported characters will be trimmed', 'mpg'); ?>"><i class="fa fa-question"></i></div>

                                </div>



                                <div class="block-with-tooltip" style="margin-top:20px">
                                    <p><?php _e('Trailing slash settings', 'mpg'); ?></p>

                                    <fieldset id="mpg_url_mode_group">
                                        <div>
                                            <input type="radio" value="both" id="both" name="mpg_url_mode_group" style="width:14px">
                                            <label for="both"><?php _e('Default', 'mpg'); ?></label>
                                        </div>
                                        <div>
                                            <input type="radio" value="with-trailing-slash" id="with-trailing-slash" name="mpg_url_mode_group" style="width:14px">
                                            <label for="with-trailing-slash"><?php _e('With trailing slash', 'mpg'); ?></label>
                                        </div>
                                        <div>
                                            <input type="radio" value="without-trailing-slash" id="without-trailing-slash" name="mpg_url_mode_group" style="width:14px">
                                            <label for="without-trailing-slash"><?php _e('Without trailing slash', 'mpg'); ?></label>
                                        </div>
                                    </fieldset>

                                    <div class="tooltip-circle" data-tippy-content="<?php _e('Allow to generate URLs with trailing slashes, without trailing slashes or use default mode', 'mpg'); ?>"><i class="fa fa-question"></i></div>


                                </div>



                                <div class="block-with-tooltip" style="margin-top:40px; align-items: unset;">
                                    <p><?php _e('URL preview', 'mpg'); ?></p>

                                    <div id="mpg_preview_url_list"></div>
                                    <a href="#" id="mpg_preview_all_urls_link"><?php _e('See all URLs', 'mpg') ?></a>
                                </div>


                                <div class="modal bd-example-modal-lg" id="mpg_preview_all_urls" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <table id="mpg_mpg_preview_all_urls_table" class="display" width="100%">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="save-changes-block">
                                <button class="save-changes btn btn-primary"><?php _e('Save changes', 'mpg'); ?></button>
                                <span class="spinner"></span>
                            </div>

                </section>
            </div>
        </div>
    </div>

    <!-- </section> -->

    <!--.col-md-6 -->
    <div class="sidebar-container">
        <?php require_once('sidebar.php') ?>
    </div>

</div>