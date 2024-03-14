<div id="lpagery_confirmModal" class="modal">

    <div class="lpagery_fadeout">

        <div class="modal-container">
            <div class="modal-intro">
                <h3>All pages that will be <span id="lpagery_create_update_text_header">created</span>. Every row represents one page</h3>
                <p id="lpagery_update_post_hint">Be aware when updating pages, that every manual changes will be overwritten and spintax will be re-applied</p>
                <div id="lpagery_update_process_area" style="float: left">
                    <div id="lpagery_update_input_section" style="margin-top: 10px; margin-bottom: 10px">
                        <div id="lpagery_purpose_area">
                            <label for="lpagery_process_purpose">Purpose</label>

                            <input type="text" id="lpagery_process_purpose" style="width: 300px"
                                   placeholder="Posts for marketing campaign"/>
                        </div>
                    </div>
                    <div style="float: right; display: grid; margin-bottom: 10px" id="lpagery_max_placeholders_free" >
                        The free version of LPagery supports 3 Placeholders. Please upgrade to unlock more placeholders
                    </div>
                    <div style="float: right; display: grid; margin-bottom: 10px" id="lpagery_max_placeholders_standard" >
                        The standard version of LPagery supports 5 Placeholders. Please upgrade to unlock unlimited placeholders
                    </div>
                </div>
            </div>

        </div>


        <div id="lpagery_page_generation_grid"></div>

        <div class="lps-generate-row">
            <button type="button" value="Cancel" class="lpagery-button cancel-button" name="cancel" id="lpagery_cancel">
                <span class="button__text">Cancel</span>
            </button>
            <div>
                <label for="lpagery_preview-mode">Preview (Only the first page will be <span id="lpagery_create_update_text_preview">created</span>)</label>
                <input type="checkbox" id="lpagery_preview-mode" name="preview-mode" style="margin-right: 10px">
                <button type="button" value="Accept" class="lpagery-button" name="accept" id="lpagery_accept">
                    <span class="button__text"><span id="lpagery_create_update_text_button">Create</span> Pages</span>
                </button>
            </div>

        </div>
        <div style="float: left; display: grid">
            <span id="lpagery_pause_error"></span>

            <span id="lpagery_pause">Waiting for previous pages to finished...</span>

            <div class="lpagery-count-container">

                <span class="lpagery_pagestatus" id="lpagery_pagestatus_total"></span>
                <span class="lpagery_pagestatus" id="lpagery_pagestatus_create"></span>
                <span class="lpagery_pagestatus" id="lpagery_pagestatus_update"></span>
                <span class="lpagery_pagestatus" id="lpagery_pagestatus_ignored"></span>
                <span class="lpagery_pagestatus" id="lpagery_pagestatus_ignored_not_changed"></span>
            </div>

            <span id="lpagery_ram_status" style="margin-top: 15px">test</span>
            <button type="button" class="lpagery-button" id="lpagery_ignore_ram_protection" style="width: 150px">
                <span class="button__text">Proceed</span>
            </button>

            <div id="lpagery_error-area"  style="margin-top: 15px">
                <span id="lpagery_error-text">An error occurred. Report screenshot of this page including the complete error message below and your excel/csv file to info@lpagery.io</span>
                <span id="lpagery_error-content"></span>
            </div>
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
        <a href="edit.php?post_type=page&orderby=date&order=desc&lang=all" id="lpagery_created_page_link">Go to the processed Pages</a>
        <a href="" id="lpagery_close-modal">Close</a>
    </div>
    <?php include_once( 'free_coupon_modal.php' ); ?>
</div>

<div id="lpagery_creation_info_modal" class="modal" style="width: 400px">
    <p><span id="lpagery_creation_info"> images could not be found. Do you want to proceed?</span></p>

    <button type="button" value="Cancel" class="lpagery-button cancel-button" name="cancel"
            id="lpagery_cancel_creation_info" rel="modal:close">
        <span class="button__text">No</span>
    </button>
    <button type="button" value="Accept" class="lpagery-button" name="accept" id="lpagery_accept_creation_info">
        <span class="button__text">Yes</span>
    </button>

</div>


<div id="lpagery_skeleton_modal" class="modal lpagery_skeleton" style="width: 1100px; height: 700px">
</div>