<?php

?>
    <pre>
    <script data-cg-processing="true">

            cgJsClass.gallery.language = cgJsClass.gallery.language ||  {};

        // general stuff
        var index = <?php echo json_encode($galeryIDuserForJs); ?>;

        cgJsClass.gallery.language[index] = cgJsClass.gallery.language[index] || {};

        // icons

        cgJsClass.gallery.language[index].ImageUpload = <?php echo json_encode($language_ImageUpload); ?>;
        cgJsClass.gallery.language[index].MinimizeUploadForm = <?php echo json_encode($language_MinimizeUploadForm); ?>;
        cgJsClass.gallery.language[index].FurtherUpload = <?php echo json_encode($language_FurtherUpload); ?>;

        cgJsClass.gallery.language[index].Search = <?php echo json_encode($language_Search); ?>;

        cgJsClass.gallery.language[index].SortBy = <?php echo json_encode($language_SortBy); ?>;

        cgJsClass.gallery.language[index].RandomSortIcon = <?php echo json_encode($language_RandomSortIcon); ?>;
        cgJsClass.gallery.language[index].ShowAllCategories = <?php echo json_encode($language_ShowAllCategories); ?>;
        cgJsClass.gallery.language[index].ShowLessCategories = <?php echo json_encode($language_ShowLessCategories); ?>;

        cgJsClass.gallery.language[index].ThumbView = <?php echo json_encode($language_ThumbView); ?>;
        cgJsClass.gallery.language[index].HeightView = <?php echo json_encode($language_HeightView); ?>;
        cgJsClass.gallery.language[index].RowView = <?php echo json_encode($language_RowView); ?>;
        cgJsClass.gallery.language[index].SliderView = <?php echo json_encode($language_SliderView); ?>;
        cgJsClass.gallery.language[index].BlogView = <?php echo json_encode($language_BlogView); ?>;

        cgJsClass.gallery.language[index].DarkStyle = <?php echo json_encode($language_DarkStyle); ?>;
        cgJsClass.gallery.language[index].BrightStyle = <?php echo json_encode($language_BrightStyle); ?>;
        cgJsClass.gallery.language[index].SelectPage = <?php echo json_encode($language_SelectPage); ?>;
        cgJsClass.gallery.language[index].PreviousPage = <?php echo json_encode($language_PreviousPage); ?>;
        cgJsClass.gallery.language[index].NextPage = <?php echo json_encode($language_NextPage); ?>;

        cgJsClass.gallery.language[index].PreviousImage = <?php echo json_encode($language_PreviousImage); ?>;
        cgJsClass.gallery.language[index].NextImage = <?php echo json_encode($language_NextImage); ?>;

        cgJsClass.gallery.language[index].PreviousFileInEntry = <?php echo json_encode($language_PreviousFileInEntry); ?>;
        cgJsClass.gallery.language[index].NextFileInEntry = <?php echo json_encode($language_NextFileInEntry); ?>;

        cgJsClass.gallery.language[index].Download = <?php echo json_encode($language_Download); ?>;


        cgJsClass.gallery.language[index].GoToTopOfTheGallery = <?php echo json_encode($language_GoToTopOfTheGallery); ?>;
        cgJsClass.gallery.language[index].SearchOrSort = <?php echo json_encode($language_SearchOrSort); ?>;
        cgJsClass.gallery.language[index].Close = <?php echo json_encode($language_Close); ?>;

        cgJsClass.gallery.language[index].CopyGalleryFileLink = <?php echo json_encode($language_CopyGalleryFileLink); ?>;
        cgJsClass.gallery.language[index].CopyGalleryEntryLink = <?php echo json_encode($language_CopyGalleryEntryLink); ?>;
        cgJsClass.gallery.language[index].GalleryFileLinkCopied = <?php echo json_encode($language_GalleryFileLinkCopied); ?>;
        cgJsClass.gallery.language[index].GalleryEntryLinkCopied = <?php echo json_encode($language_GalleryEntryLinkCopied); ?>;
        cgJsClass.gallery.language[index].CopyOriginalFileSourceLink = <?php echo json_encode($language_CopyOriginalFileSourceLink); ?>;
        cgJsClass.gallery.language[index].OriginalFileSourceLinkCopied = <?php echo json_encode($language_OriginalFileSourceLinkCopied); ?>;
        cgJsClass.gallery.language[index].OpenOriginalFileInNewTab = <?php echo json_encode($language_OpenOriginalFileInNewTab); ?>;
        cgJsClass.gallery.language[index].DownloadOriginalFile = <?php echo json_encode($language_DownloadOriginalFile); ?>;

        cgJsClass.gallery.language[index].FullWindow = <?php echo json_encode($language_FullWindow); ?>;
        cgJsClass.gallery.language[index].CloseView = <?php echo json_encode($language_CloseView); ?>;

        cgJsClass.gallery.language[index].FullScreen = <?php echo json_encode($language_FullScreen); ?>;
        cgJsClass.gallery.language[index].CloseFullScreen = <?php echo json_encode($language_CloseFullScreen); ?>;

        cgJsClass.gallery.language[index].ShowMoreInfo = <?php echo json_encode($language_ShowMoreInfo); ?>;
        cgJsClass.gallery.language[index].ShowLessInfo = <?php echo json_encode($language_ShowLessInfo); ?>;

        cgJsClass.gallery.language[index].AddComment = <?php echo json_encode($language_AddComment); ?>;
        cgJsClass.gallery.language[index].ShowMoreComments = <?php echo json_encode($language_ShowMoreComments); ?>;
        cgJsClass.gallery.language[index].ShowLessComments = <?php echo json_encode($language_ShowLessComments); ?>;

        cgJsClass.gallery.language[index].AddAnEmoji = <?php echo json_encode($language_AddAnEmoji); ?>;
        cgJsClass.gallery.language[index].HideEmojis = <?php echo json_encode($language_HideEmojis); ?>;

        cgJsClass.gallery.language[index].VoteNow = <?php echo json_encode($language_VoteNow); ?>;
        cgJsClass.gallery.language[index].ThankYouForVoting = <?php echo json_encode($language_ThankYouForVoting); ?>;
        cgJsClass.gallery.language[index].UndoYourLastVote = <?php echo json_encode($language_UndoYourLastVote); ?>;

        // rest
        cgJsClass.gallery.language[index].of = <?php echo json_encode($language_of); ?>;
        cgJsClass.gallery.language[index].NoImagesFound = <?php echo json_encode($language_NoImagesFound); ?>;
        cgJsClass.gallery.language[index].NoEntriesFound = <?php echo json_encode($language_NoEntriesFound); ?>;
        cgJsClass.gallery.language[index].RandomSortSorting = <?php echo json_encode($language_RandomSortSorting); ?>;
        cgJsClass.gallery.language[index].Custom = <?php echo json_encode($language_Custom); ?>;
        cgJsClass.gallery.language[index].DateDescend = <?php echo json_encode($language_DateDescend); ?>;
        cgJsClass.gallery.language[index].DateAscend = <?php echo json_encode($language_DateAscend); ?>;
        cgJsClass.gallery.language[index].CommentsDescend = <?php echo json_encode($language_CommentsDescend); ?>;
        cgJsClass.gallery.language[index].CommentsAscend = <?php echo json_encode($language_CommentsAscend); ?>;
        cgJsClass.gallery.language[index].RatingDescend = <?php echo json_encode($language_RatingDescend); ?>;
        cgJsClass.gallery.language[index].RatingAscend = <?php echo json_encode($language_RatingAscend); ?>;
        cgJsClass.gallery.language[index].FullSize = <?php echo json_encode($language_FullSize); ?>;
        cgJsClass.gallery.language[index].PictureComments = <?php echo json_encode($language_PictureComments); ?>;
        cgJsClass.gallery.language[index].YourComment = <?php echo json_encode($language_YourComment); ?>;
        cgJsClass.gallery.language[index].Name = <?php echo json_encode($language_Name); ?>;
        cgJsClass.gallery.language[index].Comment = <?php echo json_encode($language_Comment); ?>;
        cgJsClass.gallery.language[index].IamNotArobot = <?php echo json_encode($language_IamNotArobot); ?>;
        cgJsClass.gallery.language[index].Send = <?php echo json_encode($language_Send); ?>;
        cgJsClass.gallery.language[index].TheNameFieldMustContainTwoCharactersOrMore = <?php echo json_encode($language_TheNameFieldMustContainTwoCharactersOrMore); ?>;
        cgJsClass.gallery.language[index].TheCommentFieldMustContainThreeCharactersOrMore = <?php echo json_encode($language_TheCommentFieldMustContainThreeCharactersOrMore); ?>;
        cgJsClass.gallery.language[index].PlzCheckTheCheckboxToProveThatYouAreNotArobot = <?php echo json_encode($language_PlzCheckTheCheckboxToProveThatYouAreNotArobot); ?>;
        cgJsClass.gallery.language[index].ThankYouForYourComment = <?php echo json_encode($language_ThankYouForYourComment); ?>;
        cgJsClass.gallery.language[index].YourCommentWillBeReviewed = <?php echo json_encode($language_YourCommentWillBeReviewed); ?>;
        cgJsClass.gallery.language[index].YouHaveAlreadyVotedThisPicture = <?php echo json_encode($language_YouHaveAlreadyVotedThisPicture); ?>;
        cgJsClass.gallery.language[index].YouHaveAlreadyVotedThisCategory = <?php echo json_encode($language_YouHaveAlreadyVotedThisCategory); ?>;
        cgJsClass.gallery.language[index].YouHaveNoMoreVotesInThisCategory = <?php echo json_encode($language_YouHaveNoMoreVotesInThisCategory); ?>;
        cgJsClass.gallery.language[index].AllVotesUsed = <?php echo json_encode($language_AllVotesUsed); ?>;
        cgJsClass.gallery.language[index].ItIsNotAllowedToVoteForYourOwnPicture = <?php echo json_encode($language_ItIsNotAllowedToVoteForYourOwnPicture); ?>;
        cgJsClass.gallery.language[index].OnlyRegisteredUsersCanVote = <?php echo json_encode($language_OnlyRegisteredUsersCanVote); ?>;
        cgJsClass.gallery.language[index].ThisFileTypeIsNotAllowed = <?php echo json_encode($language_ThisFileTypeIsNotAllowed); ?>;
        cgJsClass.gallery.language[index].TheFileYouChoosedIsToBigMaxAllowedSize = <?php echo json_encode($language_TheFileYouChoosedIsToBigMaxAllowedSize); ?>;
        cgJsClass.gallery.language[index].TheResolutionOfThisPicIs = <?php echo json_encode($language_TheResolutionOfThisPicIs); ?>;

        cgJsClass.gallery.language[index].BulkUploadQuantityIs = <?php echo json_encode($language_BulkUploadQuantityIs); ?>;
        cgJsClass.gallery.language[index].BulkUploadLowQuantityIs = <?php echo json_encode($language_BulkUploadLowQuantityIs); ?>;

        cgJsClass.gallery.language[index].MaximumAllowedResolutionForJPGsIs = <?php echo json_encode($language_MaximumAllowedResolutionForJPGsIs); ?>;
        cgJsClass.gallery.language[index].MaximumAllowedWidthForJPGsIs = <?php echo json_encode($language_MaximumAllowedWidthForJPGsIs); ?>;
        cgJsClass.gallery.language[index].MaximumAllowedHeightForJPGsIs = <?php echo json_encode($language_MaximumAllowedHeightForJPGsIs); ?>;

        cgJsClass.gallery.language[index].MaximumAllowedResolutionForPNGsIs = <?php echo json_encode($language_MaximumAllowedResolutionForPNGsIs); ?>;
        cgJsClass.gallery.language[index].MaximumAllowedWidthForPNGsIs = <?php echo json_encode($language_MaximumAllowedWidthForPNGsIs); ?>;
        cgJsClass.gallery.language[index].MaximumAllowedHeightForPNGsIs = <?php echo json_encode($language_MaximumAllowedHeightForPNGsIs); ?>;

        cgJsClass.gallery.language[index].MaximumAllowedResolutionForGIFsIs = <?php echo json_encode($language_MaximumAllowedResolutionForGIFsIs); ?>;
        cgJsClass.gallery.language[index].MaximumAllowedWidthForGIFsIs = <?php echo json_encode($language_MaximumAllowedWidthForGIFsIs); ?>;
        cgJsClass.gallery.language[index].MaximumAllowedHeightForGIFsIs = <?php echo json_encode($language_MaximumAllowedHeightForGIFsIs); ?>;

        cgJsClass.gallery.language[index].MinimumRequiredWidthForJPGsIs = <?php echo json_encode($language_MinimumRequiredWidthForJPGsIs); ?>;
        cgJsClass.gallery.language[index].MinimumRequiredHeightForJPGsIs = <?php echo json_encode($language_MinimumRequiredHeightForJPGsIs); ?>;

        cgJsClass.gallery.language[index].MinimumRequiredWidthForPNGsIs = <?php echo json_encode($language_MinimumRequiredWidthForPNGsIs); ?>;
        cgJsClass.gallery.language[index].MinimumRequiredHeightForPNGsIs = <?php echo json_encode($language_MinimumRequiredHeightForPNGsIs); ?>;

        cgJsClass.gallery.language[index].MinimumRequiredWidthForGIFsIs = <?php echo json_encode($language_MinimumRequiredWidthForGIFsIs); ?>;
        cgJsClass.gallery.language[index].MinimumRequiredHeightForGIFsIs = <?php echo json_encode($language_MinimumRequiredHeightForGIFsIs); ?>;

        cgJsClass.gallery.language[index].MaximumAllowedWidthForICOsIs = <?php echo json_encode($language_MaximumAllowedWidthForICOsIs); ?>;
        cgJsClass.gallery.language[index].MaximumAllowedHeightForICOsIs = <?php echo json_encode($language_MaximumAllowedHeightForICOsIs); ?>;

        cgJsClass.gallery.language[index].YouHaveToCheckThisAgreement = <?php echo json_encode($language_YouHaveToCheckThisAgreement); ?>;
        cgJsClass.gallery.language[index].EmailAdressHasToBeValid = <?php echo json_encode($language_EmailAddressHasToBeValid); ?>;
        cgJsClass.gallery.language[index].MinAmountOfCharacters = <?php echo json_encode($language_MinAmountOfCharacters); ?>;
        cgJsClass.gallery.language[index].MaxAmountOfCharacters = <?php echo json_encode($language_MaxAmountOfCharacters); ?>;
        cgJsClass.gallery.language[index].ChooseYourImage = <?php echo json_encode($language_ChooseYourImage); ?>;

        cgJsClass.gallery.language[index].ThePhotoContestHasNotStartedYet = <?php echo json_encode($language_ThePhotoContestHasNotStartedYet); ?>;

        cgJsClass.gallery.language[index].ThePhotoContestIsOver = <?php echo json_encode($language_ThePhotoContestIsOver); ?>;

        cgJsClass.gallery.language[index].ShowMeUserInfoOnLeftMouseHold = <?php echo json_encode($language_ShowMeUserInfoOnLeftMouseHold); ?>;

        cgJsClass.gallery.language[index].ThisMailAlreadyExists = <?php echo json_encode($language_ThisMailAlreadyExists); ?>;
        cgJsClass.gallery.language[index].ThisUsernameAlreadyExists = <?php echo json_encode($language_ThisUsernameAlreadyExists); ?>;

        cgJsClass.gallery.language[index].UsernameOrEmail = <?php echo json_encode($language_UsernameOrEmail); ?>;
        cgJsClass.gallery.language[index].UsernameOrEmailRequired = <?php echo json_encode($language_UsernameOrEmailRequired); ?>;

        cgJsClass.gallery.language[index].UsernameOrEmailDoesNotExist = <?php echo json_encode($language_UsernameOrEmailDoesNotExist); ?>;

        cgJsClass.gallery.language[index].ThisNicknameAlreadyExists = <?php echo json_encode($language_ThisNicknameAlreadyExists); ?>;

        cgJsClass.gallery.language[index].Email = <?php echo json_encode($language_Email); ?>;
        cgJsClass.gallery.language[index].EmailRequired = <?php echo json_encode($language_EmailRequired); ?>;

        cgJsClass.gallery.language[index].Password = <?php echo json_encode($language_Password); ?>;
        cgJsClass.gallery.language[index].PasswordRequired = <?php echo json_encode($language_PasswordRequired); ?>;

        cgJsClass.gallery.language[index].PleaseFillOut = <?php echo json_encode($language_PleaseFillOut); ?>;

        cgJsClass.gallery.language[index].PasswordsDoNotMatch = <?php echo json_encode($language_PasswordsDoNotMatch); ?>;

        cgJsClass.gallery.language[index].sendUpload = <?php echo json_encode($language_sendUpload); ?>;
        cgJsClass.gallery.language[index].sendRegistry = <?php echo json_encode($language_sendRegistry); ?>;
        cgJsClass.gallery.language[index].sendLogin = <?php echo json_encode($language_sendLogin); ?>;

        cgJsClass.gallery.language[index].pleaseSelect = <?php echo json_encode($language_pleaseSelect); ?>;
        cgJsClass.gallery.language[index].youHaveNotSelected = <?php echo json_encode($language_youHaveNotSelected); ?>;

        cgJsClass.gallery.language[index].pleaseConfirm = <?php echo json_encode($language_pleaseConfirm); ?>;
        cgJsClass.gallery.language[index].imageIsNotActivated = <?php echo json_encode($language_imageIsNotActivated); ?>;

        cgJsClass.gallery.language[index].ConfirmationWentWrong = <?php echo json_encode($language_ConfirmationWentWrong); ?>;

        cgJsClass.gallery.language[index].URLnotValid = <?php echo json_encode($language_URLnotValid); ?>;

        cgJsClass.gallery.language[index].Other = <?php echo json_encode($language_Other); ?>;

        cgJsClass.gallery.language[index].YouCanNotVoteInOwnGallery = <?php echo json_encode($language_YouCanNotVoteInOwnGallery); ?>;
        cgJsClass.gallery.language[index].YouCanNotCommentInOwnGallery = <?php echo json_encode($language_YouCanNotCommentInOwnGallery); ?>;

        cgJsClass.gallery.language[index].MaximumAmountOfUploadsIs = <?php echo json_encode($language_MaximumAmountOfUploadsIs); ?>;
        cgJsClass.gallery.language[index].MaximumAmountOfUploadsPerCategoryIs = <?php echo json_encode($language_MaximumAmountOfUploadsPerCategoryIs); ?>;
        cgJsClass.gallery.language[index].YouHaveAlreadyUploaded = <?php echo json_encode($language_YouHaveAlreadyUploaded); ?>;
        cgJsClass.gallery.language[index].YouHaveAlreadyUploadedForCategory = <?php echo json_encode($language_YouHaveAlreadyUploadedForCategory); ?>;
        cgJsClass.gallery.language[index].MaximumAmountOfUploadsLeftForYou = <?php echo json_encode($language_MaximumAmountOfUploadsLeftForYou); ?>;

            cgJsClass.gallery.language[index].DeleteImage = <?php echo json_encode($language_DeleteImage); ?>;
            cgJsClass.gallery.language[index].DeleteImages = <?php echo json_encode($language_DeleteImages); ?>;

        cgJsClass.gallery.language[index].DeleteImageQuestion = <?php echo json_encode($language_DeleteImageQuestion); ?>;
        cgJsClass.gallery.language[index].DeleteImagesQuestion = <?php echo json_encode($language_DeleteImagesQuestion); ?>;

        cgJsClass.gallery.language[index].DeleteImageConfirm = <?php echo json_encode($language_DeleteImageConfirm); ?>;
        cgJsClass.gallery.language[index].DeleteImagesConfirm = <?php echo json_encode($language_DeleteImagesConfirm); ?>;

        cgJsClass.gallery.language[index].ImageDeleted = <?php echo json_encode($language_ImageDeleted); ?>;
        cgJsClass.gallery.language[index].ImagesDeleted = <?php echo json_encode($language_ImagesDeleted); ?>;

        cgJsClass.gallery.language[index].Yes = <?php echo json_encode($language_Yes); ?>;
        cgJsClass.gallery.language[index].No = <?php echo json_encode($language_No); ?>;

        cgJsClass.gallery.language[index].Edit = <?php echo json_encode($language_Edit); ?>;
        cgJsClass.gallery.language[index].Save = <?php echo json_encode($language_Save); ?>;
        cgJsClass.gallery.language[index].DataSaved = <?php echo json_encode($language_DataSaved); ?>;
        cgJsClass.gallery.language[index].Sum = <?php echo json_encode($language_Sum); ?>;
        cgJsClass.gallery.language[index].YourVote = <?php echo json_encode($language_YourVote); ?>;

        cgJsClass.gallery.language[index].PleaseUseAvailableEmojis = <?php echo json_encode($language_PleaseUseAvailableEmojis); ?>;
        cgJsClass.gallery.language[index].EmojisAreNotAllowed = <?php echo json_encode($language_EmojisAreNotAllowed); ?>;
        cgJsClass.gallery.language[index].YouHaveToBeLoggedInToComment = <?php echo json_encode($language_YouHaveToBeLoggedInToComment); ?>;

        cgJsClass.gallery.language[index].ShareTo = <?php echo json_encode($language_ShareTo); ?>;

    </script>
    </pre>

<?php


?>