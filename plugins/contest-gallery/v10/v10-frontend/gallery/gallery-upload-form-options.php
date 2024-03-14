<?php
$jsonFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$galeryID.'/json/'.$galeryID.'-form-upload.json';
$jsonContactForm = json_decode(file_get_contents($jsonFile),true);

$fileUploadField = [];
foreach ($jsonContactForm as $inputId => $jsonContactFormField){
    if(!empty($jsonContactFormField ['Field_Type'] == 'image-f')){
        $fileUploadField=$jsonContactFormField;
    }
}

$mainCGdivUploadFormAdditionalFiles  = '';

// have to be done here as correction
// will be also done in init-gallery-getjson.js
if(!empty($options['pro']['AdditionalFiles'])){
    $options['general']['ActivateBulkUpload'] = 0;
}

if(!empty($options['pro']['AdditionalFiles']) && intval($options['general']['Version']>=17) && empty($options['general']['ActivateBulkUpload'])){
    $mainCGdivUploadFormAdditionalFiles = 'mainCGdivUploadFormAdditionalFiles';
}

$AdditionalFilesCount = (!empty($options['pro']['AdditionalFilesCount'])) ? $options['pro']['AdditionalFilesCount'] : 1;

if(!empty($options['general']['ActivateBulkUpload'])){
    $ActivateBulkUpload = 1;
}else{
    $ActivateBulkUpload = 0;
}


$fileUploadHide = '';
$mainCGdivContactEntriesOnly = '';
$isOnlyContactEntry = false;
if(isset($fileUploadField['Active']) && $fileUploadField['Active']=='2'){
    $fileUploadHide = 'cg_hide';
    $mainCGdivContactEntriesOnly = 'mainCGdivContactEntriesOnly';
    $isOnlyContactEntry = true;
    $options['general']['ActivateBulkUpload'] = 0;
    $ActivateBulkUpload = 0;
}

//if(is_dir ($plugin_dir_path.'/../../../../contest-gallery')){
if(cg_get_version()=='contest-gallery' && $ActivateBulkUpload==1){
    $isNormalVersion = true;
    $ActivateBulkUpload = 0;
    $options['general']['ActivateBulkUpload'] = 0;
    if($AdditionalFilesCount>2){// then must be manipulation
        $AdditionalFilesCount = 2;
    }
}

$maxUpload = 1;
$isBulkUpload = false;
if(($ActivateBulkUpload==1 && $maxUpload >= 1)){
    $isBulkUpload = 1;
}


$isShowCollapsed = false;
$mainCGdivShowUncollapsed = '';

// because of version 20.0 is always collapsed, mandatory not exists
if(!$isOnlyContactEntry){
    if(!isset($fileUploadField['Field_Content']['mandatory']) || $fileUploadField['Field_Content']['mandatory']=='on'){
        $isShowCollapsed = true;
    }else{
        if($isBulkUpload){
            $isShowCollapsed = true;
        }else{
            $mainCGdivShowUncollapsed = 'mainCGdivShowUncollapsed';
        }
    }
}

$mainCGdivUploadNotRequired = '';
if(isset($fileUploadField['Field_Content']['mandatory']) && $fileUploadField['Field_Content']['mandatory']=='off' && !$isBulkUpload){// is always required for bulk upload
    $mainCGdivUploadNotRequired = 'mainCGdivUploadNotRequired';
}

$plugin_dir_path = plugin_dir_path(__FILE__);
$isNormalVersion = false;

if(empty(($options['general']['BulkUploadQuantity']))){
    $BulkUploadQuantity = 1;
}else{
    $BulkUploadQuantity = $options['general']['BulkUploadQuantity'];
}

$ActivateBulkUploadCgHide = '';
$cg_form_div_image_step_single_image_multiple = '';

$isGoogleRecaptchaAlreadyRendered = false;

$isDefinitelyBulkUpload = false;

if($ActivateBulkUpload==1 && $BulkUploadQuantity > 1){
    $isDefinitelyBulkUpload = true;
}

$cg_form_submit_bulk_upload = '';
$mainCGdivUploadFormBulk = '';
$ondragSingleUpload = '';

if(($ActivateBulkUpload==1 && $maxUpload >= 1)){
    $mainCGdivUploadFormBulk = 'mainCGdivUploadFormBulk';
    $cg_form_submit_bulk_upload = 'cg_form_submit_bulk_upload';
    $ActivateBulkUploadCgHide = 'cg_hide';
    $cg_form_div_image_step_single_image_multiple = 'cg_form_div_image_step_single_image_multiple cg_form_div_image_step_single_image_to_clone';
}else{
    $ondragSingleUpload = "ondragover='cgJsClass.gallery.upload.events.ondragover(event)' ondragleave='cgJsClass.gallery.upload.events.ondragleave(event)' ondrop='cgJsClass.gallery.upload.events.ondrop(event)'";
}

$UploadFormAppearance = 2;
/*if(empty($options['pro']['UploadFormAppearance'])){// was added since 15.1.0
    $UploadFormAppearance = 1;
}else{
    $UploadFormAppearance = $options['pro']['UploadFormAppearance'];
}*/


// will be only set in case of is only upload form
$BorderRadiusClassUploadForm = '';

if(!empty($isOnlyContactForm)){
    $BorderRadiusClassUploadForm = $BorderRadiusClass;
}

?>