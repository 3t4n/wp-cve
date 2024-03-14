<div id="lpagery_confirm_modal_update" class="modal">
    <div class="fadeout">


        <div class="modal-container">
            <div class="modal-intro">
                <h3>All pages that will be updated. Every row represents one page</h3>
                <p>Be aware that every manual changes will be overwritten and spintax will be re-applied</p>
            </div>

        </div>


        <div id="lpagery_page_update_grid"></div>

        <div class="lps-generate-row">
            <button type="button" value="Cancel" class="lpagery-button cancel-button" name="cancel" id="lpagery_cancel_update">
                <span class="button__text">Cancel</span>
            </button>
            <div>
                <button type="button" value="Accept" class="lpagery-button" name="accept" id="lpagery_accept_update">
                    <span class="button__text">Update Pages</span>
                </button>
            </div>

        </div>
        <span id="lpagery_pagestatus_update_update"></span>
        <div id="lpagery_error-area-update" >
            <span id="lpagery_error-text">An error occurred. Report error below to info@lpagery.io</span>
            <br>
            <br>
            <span id="lpagery_error-content-update"></span>
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
        <a href="edit.php?post_type=page&orderby=date&order=desc&lang=all" id="lpagery_updated_page_link">Go to the updated Pages</a>
        <a href="admin.php?page=lpagery" id="lpagery_close-modal-update">Close</a>
    </div>
</div>
