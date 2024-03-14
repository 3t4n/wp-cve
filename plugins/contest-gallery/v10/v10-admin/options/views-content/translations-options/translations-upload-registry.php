<?php

echo <<<HEREDOC
<div class='cg_view_options_rows_container'>
         <p class='cg_view_options_rows_container_title'>Upload/Registration form</p>
HEREDOC;
echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width' >
                    <div class='cg_view_option_title'>
                        <p>$language_PleaseFillOut$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_PleaseFillOut]" maxlength="100" value="$translations[$l_PleaseFillOut]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_pleaseSelect$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_pleaseSelect]" maxlength="100" value="$translations[$l_pleaseSelect]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_pleaseConfirm$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_pleaseConfirm]" maxlength="100" value="$translations[$l_pleaseConfirm]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_IamNotArobot$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_IamNotArobot]" maxlength="100" value="$translations[$l_IamNotArobot]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_youHaveNotSelected$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_youHaveNotSelected]" maxlength="100" value="$translations[$l_youHaveNotSelected]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_URLnotValid$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_URLnotValid]" maxlength="100" value="$translations[$l_URLnotValid]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_PlzCheckTheCheckboxToProveThatYouAreNotArobot$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_PlzCheckTheCheckboxToProveThatYouAreNotArobot]" maxlength="100" value="$translations[$l_PlzCheckTheCheckboxToProveThatYouAreNotArobot]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ThisFileTypeIsNotAllowed$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ThisFileTypeIsNotAllowed]" maxlength="100" value="$translations[$l_ThisFileTypeIsNotAllowed]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_TheFileYouChoosedIsToBigMaxAllowedSize$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_TheFileYouChoosedIsToBigMaxAllowedSize: 2MB</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_TheFileYouChoosedIsToBigMaxAllowedSize]" maxlength="100" value="$translations[$l_TheFileYouChoosedIsToBigMaxAllowedSize]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_BulkUploadQuantityIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$l_BulkUploadQuantityIs: 5</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_BulkUploadQuantityIs]" maxlength="100" value="$translations[$l_BulkUploadQuantityIs]">
                    </div>
                </div>
        </div>
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_BulkUploadLowQuantityIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$l_BulkUploadLowQuantityIs: 2</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_BulkUploadLowQuantityIs]" maxlength="100" value="$translations[$l_BulkUploadLowQuantityIs]">
                    </div>
                </div>
           </div>
HEREDOC;

echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaximumAllowedWidthForJPGsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MaximumAllowedWidthForJPGsIs: 2000px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaximumAllowedWidthForJPGsIs]" maxlength="100" value="$translations[$l_MaximumAllowedWidthForJPGsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaximumAllowedHeightForJPGsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MaximumAllowedHeightForJPGsIs: 1200px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaximumAllowedHeightForJPGsIs]" maxlength="100" value="$translations[$l_MaximumAllowedHeightForJPGsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MinimumRequiredWidthForJPGsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MinimumRequiredWidthForJPGsIs: 800px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MinimumRequiredWidthForJPGsIs]" maxlength="100" value="$translations[$l_MinimumRequiredWidthForJPGsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MinimumRequiredHeightForJPGsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MinimumRequiredHeightForJPGsIs: 600px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MinimumRequiredHeightForJPGsIs]" maxlength="100" value="$translations[$l_MinimumRequiredHeightForJPGsIs]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaximumAllowedWidthForPNGsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MaximumAllowedWidthForPNGsIs: 2000px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaximumAllowedWidthForPNGsIs]" maxlength="100" value="$translations[$l_MaximumAllowedWidthForPNGsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaximumAllowedHeightForPNGsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MaximumAllowedHeightForPNGsIs: 1200px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaximumAllowedHeightForPNGsIs]" maxlength="100" value="$translations[$l_MaximumAllowedHeightForPNGsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MinimumRequiredWidthForPNGsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MinimumRequiredWidthForPNGsIs: 800px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MinimumRequiredWidthForPNGsIs]" maxlength="100" value="$translations[$l_MinimumRequiredWidthForPNGsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MinimumRequiredHeightForPNGsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MinimumRequiredHeightForPNGsIs: 600px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MinimumRequiredHeightForPNGsIs]" maxlength="100" value="$translations[$l_MinimumRequiredHeightForPNGsIs]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
        <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaximumAllowedWidthForGIFsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MaximumAllowedWidthForGIFsIs: 2000px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaximumAllowedWidthForGIFsIs]" maxlength="100" value="$translations[$l_MaximumAllowedWidthForGIFsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaximumAllowedHeightForGIFsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MaximumAllowedHeightForGIFsIs: 1200px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaximumAllowedHeightForGIFsIs]" maxlength="100" value="$translations[$l_MaximumAllowedHeightForGIFsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MinimumRequiredWidthForGIFsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MinimumRequiredWidthForGIFsIs: 800px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MinimumRequiredWidthForGIFsIs]" maxlength="100" value="$translations[$l_MinimumRequiredWidthForGIFsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MinimumRequiredHeightForGIFsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MinimumRequiredHeightForGIFsIs: 600px</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MinimumRequiredHeightForGIFsIs]" maxlength="100" value="$translations[$l_MinimumRequiredHeightForGIFsIs]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MinAmountOfCharacters$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MinAmountOfCharacters: 3</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MinAmountOfCharacters]" maxlength="100" value="$translations[$l_MinAmountOfCharacters]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaxAmountOfCharacters$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container">Frontend result example:<br>$language_MaxAmountOfCharacters: 100</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaxAmountOfCharacters]" maxlength="100" value="$translations[$l_MaxAmountOfCharacters]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaximumAmountOfUploadsIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container"><strong>"Uploads per user" option</strong><br>Frontend result example:<br>$language_MaximumAmountOfUploadsIs: 5</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaximumAmountOfUploadsIs]" maxlength="100" value="$translations[$l_MaximumAmountOfUploadsIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_MaximumAmountOfUploadsPerCategoryIs$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container"><strong>"Uploads per category" option</strong><br>Frontend result example:<br>$language_MaximumAmountOfUploadsPerCategoryIs: 5</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_MaximumAmountOfUploadsPerCategoryIs]" maxlength="100" value="$translations[$l_MaximumAmountOfUploadsPerCategoryIs]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YouHaveAlreadyUploaded$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container"><strong>"Uploads per user" option</strong><br>Frontend result example:<br>$language_YouHaveAlreadyUploaded: 3</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YouHaveAlreadyUploaded]" maxlength="100" value="$translations[$l_YouHaveAlreadyUploaded]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YouHaveAlreadyUploadedForCategory$cgShortcodeCopy<span class="cg-info-icon">info</span><span class="cg-info-container"><strong>"Uploads per user" option</strong><br>Frontend result example:<br>$language_YouHaveAlreadyUploadedForCategory: 3</span></p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YouHaveAlreadyUploadedForCategory]" maxlength="100" value="$translations[$l_YouHaveAlreadyUploadedForCategory]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_YouHaveToCheckThisAgreement$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_YouHaveToCheckThisAgreement]" maxlength="100" value="$translations[$l_YouHaveToCheckThisAgreement]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_EmailAddressHasToBeValid$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_EmailAddressHasToBeValid]" maxlength="100" value="$translations[$l_EmailAddressHasToBeValid]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_ChooseYourImage$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_ChooseYourImage]" maxlength="100" value="$translations[$l_ChooseYourImage]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_sendUpload<br><span style="font-weight:normal;">(Submit upload form button text)</span>$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_sendUpload]" maxlength="100" value="$translations[$l_sendUpload]">
                    </div>
                </div>
        </div>
         <div class='cg_view_options_row'>
                <div class='cg_view_option cg_view_option_full_width cg_border_top_none' >
                    <div class='cg_view_option_title'>
                        <p>$language_sendButton<br><span style="font-weight:normal;">(Submit contact form button text)</span>$cgShortcodeCopy</p>
                    </div>
                    <div class='cg_view_option_input'>
                        <input type="text" name="translations[$l_sendButton]" maxlength="100" value="$translations[$l_sendButton]">
                    </div>
                </div>
        </div>
HEREDOC;

echo <<<HEREDOC
</div>
HEREDOC;
// take care next row has to be after HEREDOC in file end
