<div class="lpagery_alert_dialog" id="lpagery_free_discount_dialog" style="display: none; z-index: 10000">
    <div class="alert-dialog-content">
        <div class="alert-dialog-header flex flex-col items-center space-y-4">
            <svg class="check-icon h-16 w-16 text-green-500" xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                 strokeLinejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <div style="width: 50%;">

            </div>
            <h2 class="alert-dialog-title">It seems like you are enjoying LPagery!</h2>
            <div class="alert-dialog-description" style="text-align: center">
                As a token of our appreciation, we are rewarding you with a <b>20% discount</b>. <br> Upgrade now to LPagery Pro and
                get access to these great features:
            </div>
            <ul class="list-disc list-inside text-left">
                <li>Unlimited Placeholders</li>
                <li>Unique Images for each Page</li>
                <li>Complete Management of the Pages (Bulk Update and Delete)</li>
                <li>Automated Google Sheet Sync</li>
                <li>Parent Pages/Categories</li>
                <li>7-day money-back guarantee</li>
                <li>and so much more...</li>

            </ul>
            <div class="alert-dialog-description" style="text-align: center">
                This is the only time you get this chance!
            </div>
            <div class="alert-dialog-footer flex flex-col gap-4">
                <a target="_blank"  rel="noopener" href="https://lpagery.io/pricing/?utm_source=free_version&utm_medium=modal&utm_campaign=user_sale" style="cursor: pointer">
                <button class="alert-dialog-cancel w-full text-center cursor-pointer"><b>Apply 20% discount</b></button>
                </a>
                <a href="edit.php?post_type=page&orderby=date&order=desc&lang=all"
                   id="lpagery_created_page_link_discount" style="cursor: pointer">
                    <button class="alert-dialog-action-primary w-full text-white">Continue to created pages without
                        saving money
                    </button>
                </a>


                <button class="alert-dialog-action-apply w-full text-center cursor-pointer"
                        id="lpagery_discount_dialog_close">Close
                </button>
            </div>
        </div>
    </div>
</div>