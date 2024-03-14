<?php
return array(
    $tablename_email => array(
        'Content' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
    ),
    $tablename_email_admin => array(
        'Content' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
    ),
    $tablename_options_input => array(
        'Confirmation_Text' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'ShowFormAfterUpload' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0)// Update ab 28.04.2020
    ),
    $tablename_comments => array(
        'Name' => array('COLUMN_TYPE' => 'VARCHAR(1000)','DEFAULT' => '""'),// Update 07.08.2021
        'IP' => array('COLUMN_TYPE' => 'VARCHAR(200)','DEFAULT' => '""'),// Update 07.01.2022
        'WpUserId' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 07.01.2022
        'ReviewTstamp' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 08.09.2022
        'Active' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0)// Update 08.09.2022
    ),
    $tablename_entries => array(
        'Long_Text' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'ConfMailId' => array('COLUMN_TYPE' => 'INT(99)','DEFAULT' => 0),// Update ab 26.04.2020 afterwards
        'Checked' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 26.04.2020
        'InputDate' => array('COLUMN_TYPE' => 'DateTime','DEFAULT' => '"0000-00-00 00:00:00"'),// Update ab 14.06.2020, better then null, for all mysql versions and windows server: 0000-00-00 00:00:00
        'Tstamp' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0)// Update ab 20.01.2023
    ),
    $tablename => array(
        'CountS' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update ab 11.06.2016
        'WpUpload' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update ab 09.2.12.2016
        'Width' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'Height' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'WpUserId' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update ab 02.03.2017
        'rSource' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 29.2.11.2018
        'rThumb' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'addCountS' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 17.06.2018
        'addCountR1' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 02.07.2018
        'addCountR2' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'addCountR3' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'addCountR4' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'addCountR5' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'addCountR6' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'addCountR7' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'addCountR8' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'addCountR9' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'addCountR10' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'Category' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 22.07.2018
        'Exif' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update 15.07.2019
        'IP' => array('COLUMN_TYPE' => 'VARCHAR(99)','DEFAULT' => '""'),// Update 08.01.2020
        'CountR1' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 24.02.2020
        'CountR2' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 24.02.2020
        'CountR3' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 24.02.2020
        'CountR4' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 24.02.2020
        'CountR5' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 24.02.2020
        'CountR6' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'CountR7' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'CountR8' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'CountR9' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'CountR10' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 05.02.2022
        'Version' => array('COLUMN_TYPE' => 'VARCHAR(30)','DEFAULT' => '""'),// Update 14.05.2020
        'CheckSet' => array('COLUMN_TYPE' => 'VARCHAR(30)','DEFAULT' => '""'),// Update ab 19.05.2020
        'CookieId' => array('COLUMN_TYPE' => 'VARCHAR(99)','DEFAULT' => '""'),// Update ab 19.05.2020
        'Winner' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 03.08.2020
        'IsProfileImage' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 26.12.2021
        'MultipleFiles' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 28.07.2022
        'CountCtoReview' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 11.09.2022
        'PositionNumber' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 23.09.2022
        'WpPage' => array('COLUMN_TYPE' => 'BIGINT(20)','DEFAULT' => 0),// Update ab 31.12.2022
        'WpPageUser' => array('COLUMN_TYPE' => 'BIGINT(20)','DEFAULT' => 0),// Update ab 31.12.2022
        'WpPageNoVoting' => array('COLUMN_TYPE' => 'BIGINT(20)','DEFAULT' => 0),// Update ab 31.12.2022
        'WpPageWinner' => array('COLUMN_TYPE' => 'BIGINT(20)','DEFAULT' => 0)// Update ab 31.12.2022
    ),
    $tablename_ip => array(
        'RatingS' => array('COLUMN_TYPE' => 'INT(1)','DEFAULT' => 0),// Update ab 11.06.2016
        'WpUserId' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update am 29.2.13.2017
        'IP' => array('COLUMN_TYPE' => 'VARCHAR(99)','DEFAULT' => '""'),// Update am 15.08.2018
        'VoteDate' => array('COLUMN_TYPE' => 'VARCHAR(30)','DEFAULT' => '""'),// Update am 17.03.2019
        'Tstamp' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update am 17.03.2019
        'OptionSet' => array('COLUMN_TYPE' => 'VARCHAR(30)','DEFAULT' => '""'),// Update 11.06.2019
        'CookieId' => array('COLUMN_TYPE' => 'VARCHAR(99)','DEFAULT' => '""'),// Update 11.06.2019
        'Category' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' =>0),// Update 27.01.2021
        'CategoriesOn' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' =>0)// Update 27.01.2021
    ),
    $tablename_options => array(
        'HideUntilVote' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0), // first update
        'ActivateUpload' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'MinResJPGon' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//  Update ab 26.08.2023
        'MinResPNGon' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//  Update ab 26.08.2023
        'MinResGIFon' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//  Update ab 26.08.2023
        'ContestEnd' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'ContestEndTime' => array('COLUMN_TYPE' => 'VARCHAR(100)','DEFAULT' => '""'),
        'ShowAlways' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),
        'CheckLogin' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'CommentsOutGallery' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 01.01.2016
        'RatingOutGallery' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'ForwardToURL' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),//  Update ab 05.02.2016
        'ForwardFrom' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),
        'ForwardType' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'MaxResJPGwidth' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),//  Update ab 27.02.2016
        'MaxResJPGheight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MaxResPNGwidth' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MaxResPNGheight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MaxResGIFwidth' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MaxResGIFheight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MinResJPGwidth' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),//  Update ab 26.08.2023
        'MinResJPGheight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MinResPNGwidth' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MinResPNGheight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MinResGIFwidth' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'MinResGIFheight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'ActivatePostMaxMB' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 05.03.2016
        'PostMaxMB' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 2),
        'ActivateBulkUpload' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'BulkUploadQuantity' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 3),
        'InformAdmin' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 13.03.2016
        'BulkUploadMinQuantity' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),// Update ab 23.04.2016
        'ShowAlwaysInfoSlider' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'FullSizeImageOutGallery' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 26.0.54.2016
        'FullSizeImageOutGalleryNewTab' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'SinglePicView' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'OnlyGalleryView' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'InfiniteScroll' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 26.0.55.2016
        'FbLikeGallery' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 26.0.53.2016
        'FbLikeGalleryVote' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'RandomSort' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 13.08.2016
        'AdjustThumbLook' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'VotesPerUser' => array('COLUMN_TYPE' => 'INT(5)','DEFAULT' => 0),
        'HideInfo' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 27.02.2017
        'ShowOnlyUsersVotes' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update am 29.2.13.2017
        'FbLikeGoToGalleryLink' => array('COLUMN_TYPE' => 'VARCHAR(1000)','DEFAULT' => '""'),
        'Version' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update 27.01.2018
        'RandomSortButton' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),//Update 17.12.2018
        'FullSizeGallery' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),//Update 18.12.2018
        'FullSizeSlideOutStart' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//Update 18.12.2018
        'CheckIp' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),//Update 11.06.2019
        'CheckCookie' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//Update 11.06.2019
        'CheckCookieAlertMessage' => array('COLUMN_TYPE' => 'VARCHAR(1000)','DEFAULT' => '""'),//Update 11.06.2019
        'SliderLook' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//Update 23.06.2019
        'SliderLookOrder' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//Update 23.06.2019
        'RegistryUserRole' => array('COLUMN_TYPE' => 'VARCHAR(1000)','DEFAULT' => '"contest_gallery_user"'),//Update 01.09.2019
        'ContestStart' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//Update 04.12.2019
        'ContestStartTime' => array('COLUMN_TYPE' => 'VARCHAR(100)','DEFAULT' => '""'),//Update 04.12.2019
        'GeneralID' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 18.11.2021
        'MaxResICOon' => array('COLUMN_TYPE' => 'INT(1)','DEFAULT' => 1),// Update ab 01.05.2022, DEFAULT is 1, because when updating always has to be activated at beginning, but when create table is 0 !!!
        'MaxResICOwidth' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 2000),// Update ab 01.05.2022, DEFAULT is 2000, because when updating always has to be activated at beginning, but when create table is 0 !!!
        'MaxResICOheight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 2000),// Update ab 01.05.2022, DEFAULT is 2000, because when updating always has to be activated at beginning, but when create table is 0 !!!
        'ActivatePostMaxMBfile' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 06.05.2022
        'PostMaxMBfile' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 2),
        'VersionDecimal' => array('COLUMN_TYPE' => 'DECIMAL(65,2)','DEFAULT' => 0),// Update ab 31.12.2022
        'WpPageParent' => array('COLUMN_TYPE' => 'BIGINT(20)','DEFAULT' => 0),// Update ab 31.12.2022
        'WpPageParentUser' => array('COLUMN_TYPE' => 'BIGINT(20)','DEFAULT' => 0),// Update ab 31.12.2022
        'WpPageParentNoVoting' => array('COLUMN_TYPE' => 'BIGINT(20)','DEFAULT' => 0),// Update ab 31.12.2022
        'WpPageParentWinner' => array('COLUMN_TYPE' => 'BIGINT(20)','DEFAULT' => 0)// Update ab 31.12.2022
    ),
    $tablename_options_visual => array(
        'ThumbViewBorderOpacity' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => 1), // first update
        'HeightViewBorderOpacity' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => 1),
        'RowViewBorderOpacity' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => 1),
        'ThumbViewBorderRadius' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),// Update ab 13.08.2016
        'HeightViewBorderRadius' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'RowViewBorderRadius' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'HeightViewSpaceHeight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'RowViewSpaceHeight' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 0),
        'TitlePositionGallery' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),
        'RatingPositionGallery' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),
        'CommentPositionGallery' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),
        'ActivateGalleryBackgroundColor' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),
        'GalleryBackgroundColor' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '"#ffffff"'),
        'GalleryBackgroundOpacity' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => 1),
        'FormRoundBorder' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Hinzugefügt am 29.2.14.2017
        'FormBorderColor' => array('COLUMN_TYPE' => 'VARCHAR(256)','DEFAULT' => '""'),
        'FormButtonColor' => array('COLUMN_TYPE' => 'VARCHAR(256)','DEFAULT' => '""'),
        'FormButtonWidth' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'FormInputWidth' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),
        'OriginalSourceLinkInSlider' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update 24.12.2017
        'PreviewInSlider' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),
        'FeControlsStyle' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update 16.04.2020
        'AllowSortOptions' => array('COLUMN_TYPE' => 'VARCHAR(256)','DEFAULT' => '""'),// Update 27.05.2020
        'GalleryStyle' => array('COLUMN_TYPE' => 'VARCHAR(256)','DEFAULT' => '""'),// Update 19.09.2020
        'BlogLook' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 22.09.2020
        'BlogLookOrder' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 22.09.2020
        'BlogLookFullWindow' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 06.10.2020
        'ImageViewFullWindow' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update 09.10.2020
        'ImageViewFullScreen' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update 09.10.2020
        'SliderThumbNav' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update 11.10.2020
        'BorderRadius' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 12.03.2021
        'CopyImageLink' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 29.03.2021
        'CommentsDateFormat' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update 14.05.2021
        'FeControlsStyleUpload' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update 10.09.2021
        'FeControlsStyleRegistry' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update 10.09.2021
        'FeControlsStyleLogin' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update 10.09.2021
        'BorderRadiusUpload' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 10.09.2021
        'BorderRadiusRegistry' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 10.09.2021
        'BorderRadiusLogin' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 10.09.2021
        'ThankVote' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 25.09.2021
        'GeneralID' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 18.11.2021
        'CopyOriginalFileLink' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 20.10.2022
        'ForwardOriginalFile' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 20.10.2022
        'ShareButtons' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 20.10.2022
        'TextBeforeWpPageEntry' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 20.10.2022
        'TextAfterWpPageEntry' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 20.10.2022
        'ForwardToWpPageEntry' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 20.10.2022
        'ForwardToWpPageEntryInNewTab' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 20.10.2022
        'ShowBackToGalleryButton' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 20.10.2022
        'BackToGalleryButtonText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 20.10.2022
        'TextDeactivatedEntry' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 20.10.2022
    ),
    $tablename_form_input => array(
        'Show_Slider' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 01.01.2016
        'Use_as_URL' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//  Update ab 05.02.2016
        'Field_Content' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019,
        'ReCaKey' => array('COLUMN_TYPE' => 'VARCHAR(200)','DEFAULT' => '""'),// Update ab 08.04.2019
        'ReCaLang' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update ab 08.04.2019
        'Active' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 23.06.2019 <<< Feld aber schon länger vorhanden
        'Version' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update ab 27.04.2020
        'WatermarkPosition' => array('COLUMN_TYPE' => 'VARCHAR(99)','DEFAULT' => '""'),// Update ab 02.01.2022
        'IsForWpPageTitle' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 01.01.2016
        'IsForWpPageDescription' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 01.01.2016
        'SubTitle' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 17.01.2023
        'ThirdTitle' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 17.01.2023
        'WpAttachmentDetailsType' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update ab 28.05.2023
    ),
    $tablename_form_output => array(
        'Field_Content' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""')// Update ab 23.02.2019
    ),
    $tablename_pro_options => array(
        'ShowOther' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),
        'CatWidget' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update 22.07.2018,
        'Search' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update 01.01.2019
        'GalleryUpload' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 20.01.2019
        'GalleryUploadTextBefore' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'GalleryUploadTextAfter' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'GalleryUploadConfirmationText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update 20.01.2019
        'ShowNickname' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 15.02.2019
        'ForwardAfterRegText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'ForwardAfterLoginText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'TextEmailConfirmation' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'TextAfterEmailConfirmation' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'RegUserUploadOnlyText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'MinusVote' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 03.03.2019
        'SlideTransition' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '"translateX"'),// Update ab 26.04.2019
        'VotesInTime' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 30.05.2019
        'VotesInTimeQuantity' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 1),// Update ab 30.05.2019
        'VotesInTimeIntervalReadable' => array('COLUMN_TYPE' => 'VARCHAR(40)','DEFAULT' => '"24:00"'),// Update ab 30.05.2019
        'VotesInTimeIntervalSeconds' => array('COLUMN_TYPE' => 'INT(20)','DEFAULT' => 86400),// Update ab 30.05.2019
        'VotesInTimeIntervalAlertMessage' => array('COLUMN_TYPE' => 'VARCHAR(200)','DEFAULT' => '""'),// Update ab 30.05.2019
        'ShowExif' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 15.07.2019
        'SliderFullWindow' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 27.07.2019,
        'HideRegFormAfterLogin' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 19.08.2019
        'HideRegFormAfterLoginShowTextInstead' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 19.08.2019
        'HideRegFormAfterLoginTextToShow' => array('COLUMN_TYPE' => 'VARCHAR(1000)','DEFAULT' => '""'),// Update ab 19.08.2019
        'Manipulate' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update 17.06.2018 (Nachträglich hinzugefügt am 15.09.2019)
        'RegUserGalleryOnly' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 06.10.2019
        'RegUserGalleryOnlyText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update 06.10.2019
        'RegUserMaxUpload' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 25.11.2019
        'RegUserMaxUploadPerCategory' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),// Update 25.11.2019
        'IsModernFiveStar' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 01.03.2020
        'GalleryUploadOnlyUser' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 05.03.2020
        'FbLikeNoShare' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 26.03.2020
        'FbLikeOnlyShare' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 05.01.2021
        'VoteNotOwnImage' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 13.04.2020
        'PreselectSort' => array('COLUMN_TYPE' => 'VARCHAR(30)','DEFAULT' => '""'),// Update 03.05.2020 // might need 30 var char because of length of some sorting strings
        'UploadRequiresCookieMessage' => array('COLUMN_TYPE' => 'VARCHAR(1000)','DEFAULT' => '""'),// Update 19.05.2020 // might need 30 var char because of length of some sorting strings
        'ShowCatsUnchecked' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 07.06.2020
        'ShowCatsUnfolded' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 05.08.2023
        'RegMailOptional' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update 13.07.2020
        'CustomImageName' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//Update 03.09.2020
        'CustomImageNamePath' => array('COLUMN_TYPE' => 'VARCHAR(200)','DEFAULT' => '""'),//Update 03.09.2020
        'DeleteFromStorageIfDeletedInFrontend' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//Update 23.01.2021
        'VotePerCategory' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),//Update 27.01.2021
        'VotesPerCategory' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0),//Update 07.02.2021
        'VoteMessageSuccessActive' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 21.05.2021
        'VoteMessageWarningActive' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 21.05.2021
        'VoteMessageSuccessText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 21.05.2021
        'VoteMessageWarningText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 21.05.2021
        'CommNoteActive' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 14.08.2021
        'GeneralID' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 18.11.2021
        'ShowProfileImage' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 29.12.2021
        'AllowUploadJPG' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 01.05.2022, DEFAULT is 1, because when updating always has to be activated at beginning, but when create table is 0 !!!
        'AllowUploadPNG' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 01.05.2022, DEFAULT is 1, because when updating always has to be activated at beginning, but when create table is 0 !!!
        'AllowUploadGIF' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 01.05.2022, DEFAULT is 1, because when updating always has to be activated at beginning, but when create table is 0 !!!
        'AllowUploadICO' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 01.05.2022, DEFAULT is 1, because when updating always has to be activated at beginning, but when create table is 0 !!!
        'AdditionalFiles' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 06.08.2022
        'AdditionalFilesCount' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 06.08.2022
        'ReviewComm' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 08.09.2022
        'BackToGalleryButtonURL' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 31.12.2022
        'WpPageParentRedirectURL' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 31.12.2022
        'RedirectURLdeletedEntry' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 31.12.2022
        'InformAdminAllowActivateDeactivate' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 09.03.2023
        'InformAdminActivationURL' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 09.03.2023
    ),
    $tablename_create_user_entries => array(
        'Field_Content' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'Checked' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 26.04.2020
        'Version' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update ab 26.04.2020,
        'GeneralID' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0),// Update ab 21.11.2021,
        'Tstamp' => array('COLUMN_TYPE' => 'INT(11)','DEFAULT' => 0)// Update ab 08.12.2021
    ),
    $tablename_create_user_form => array(
        'Field_Content' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019,
        'ReCaKey' => array('COLUMN_TYPE' => 'VARCHAR(200)','DEFAULT' => '""'),// Update ab 08.04.2019
        'ReCaLang' => array('COLUMN_TYPE' => 'VARCHAR(20)','DEFAULT' => '""'),// Update ab 08.04.2019
        'Active' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 1),// Update ab 23.06.2019 <<< Feld aber schon länger vorhanden
        'GeneralID' => array('COLUMN_TYPE' => 'TINYINT','DEFAULT' => 0)// Update ab 16.11.2021
    ),
    $tablename_mail_confirmation => array(
        'Content' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 23.02.2019
        'ConfirmationText' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""')// Update ab 23.02.2019
    ),
    $tablename_registry_and_login_options => array(
        'TextBeforeLoginForm' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 22.12.2021
        'EditProfileGroups' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 28.12.2021
        'TextBeforeRegFormBeforeLoggedIn' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""'),// Update ab 05.01.2022
        'PermanentTextWhenLoggedIn' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""')// Update ab 05.01.2022
    ),
    $tablename_google_options => array(
        'TextBeforeGoogleSignInButton' => array('COLUMN_TYPE' => 'TEXT','DEFAULT' => '""')// Update ab 22.12.2021
    )
)

?>