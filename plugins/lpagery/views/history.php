<div class="Main lpagery-container-without-sidebar" data-title="History">
    <div class="license-needed license-needed-fadeout" license="extended" id="lpagery_history_container_div"
         style="min-width: 1000px">
        <img style="width: 40px;" class="img-pro"
             src="<?php echo plugin_dir_url(dirname(__FILE__)) . '/../assets/img/pro.svg'; ?>">
        <p>
            In this area, you find all page creations in a list.
            You can perform various actions such as bulk delete, filter the list, and download the replacement data of
            the created pages as a CSV file.
            You can go directly to the created pages or bulk edit them by uploading a new file source with clicking on
            the pencil icon.
        </p>
        <div id="lpagery_history_grid"></div>
    </div>

    <div id="lpagery_process_delete_modal" class="modal" style="width: 500px">
        <div class="fadeout">

            <p>The history entry and will be deleted. You won't be able to bulk update the created pages anymore. Do you
                want to continue?</p>

            <div style="margin-bottom: 15px">

                <input type="checkbox" id="lpagery_delete_pages_check">
                <label for="lpagery_delete_pages_check">Delete created pages</label>
            </div>
            <div>

                <button type="button" value="Cancel" class="lpagery-button cancel-button" name="cancel"
                        id="lpagery_cancel_process_delete"
                        rel="modal:close">
                    <span class="button__text">Cancel</span>
                </button>
                <button type="button" value="Accept" class="lpagery-button" name="accept"
                        id="lpagery_accept_process_delete">
                    <span class="button__text">Yes</span>
                </button>
            </div>
            <div class="lpagery-count-container">

                <span class="lpagery_pagestatus" id="lpagery_pagestatus_delete"></span>
            </div>
        </div>
        <div class="check-center">
            <div class="success-checkmark ">
                <div class="check-icon">
                    <span class="icon-line line-tip"></span>
                    <span class="icon-line line-long"></span>
                    <div class="icon-circle"></div>
                    <div class="icon-fix"></div>


                </div>
            </div>
            <a id="lpagery_close-modal-delete" style="cursor: pointer">Close</a>
        </div>
    </div>
    <div id="lpagery_process_google_sheet_modal" class="modal" style="width: 700px">
        <div class="lpagery-container lpagery-container-modal">
            <h3>Google Sheet Sync Settings</h3>

            <div style="margin-top: 10px; margin-bottom: 10px">
                <input type="checkbox" id="google_sync_enabled_modal" checked>
                <label for="google_sync_enabled_modal">Sync enabled</label>
            </div>
            <div id="lpagery_google_sheet_sync_config" style="padding-bottom: 10px">
                <input class="labels googlesheet_input"
                       type='text'
                       id="lpagery_google_sheet_url_modal"
                       name='google_sheet_url'
                       placeholder="Google Sheet URL"><br>


                <div style="margin-top: 10px">
                    <input type="checkbox" id="syncAddModal" checked>
                    <label for="syncAddModal">Sync Page Creations</label>
                    <div class="tooltip">?
                        <span class="tooltiptext">
Check this option to synchronize and add any new posts from the Google Sheet to your WordPress site. This ensures that new content added to the sheet will be reflected as new posts on your WordPress site.
                                        </span>
                    </div>
                </div>
                <div style="margin-top: 10px">
                    <input type="checkbox" id="syncUpdateModal" checked>
                    <label for="syncUpdateModal">Sync Page Updates</label>
                    <div class="tooltip">?
                        <span class="tooltiptext">
This option updates WordPress posts if changes are detected in the corresponding Google Sheet row since the last sync. Be cautious, as the post is overwritten if the sheet data has been modified.                                        </span>
                                        </span>
                    </div>
                </div>
                <div style="margin-top: 10px">
                    <input type="checkbox" id="syncDeleteModal">
                    <label for="syncDeleteModal">Sync Page Deletions</label>
                    <div class="tooltip">?
                        <span class="tooltiptext">
Select this option if you want to synchronize deletions. If a row is deleted on the Google Sheet, the corresponding post on your WordPress site will be removed. Be cautious when using this option, as it permanently removes content from your WordPress site.
                                        </span>
                    </div>
                </div>
            </div>
            <div id="lpagery_google_sheet_sync_details">
                <hr>
                <p>Last Sync: <span id="lpagery_google_sheet_last_sync"></span></p>
                <p>Next Sync: <span id="lpagery_google_sheet_next_sync"></span></p>
                <p>Status: <span id="lpagery_google_sheet_status"></span></p>

                <label for="lpagery_cron_error"></label>
                <textarea style="width: 300px; height: 200px" readonly id="lpagery_cron_error"></textarea>


            </div>
            <input type="hidden" id="lpagery_process_id_modal">
            <div>

            </div>


            <button type="button" value="Accept" class="lpagery-button" name="accept" id="lpagery_save_google_sheet">
                <span class="button__text">Save Changes</span>
            </button>


        </div>
    </div>

</div>
<?php

