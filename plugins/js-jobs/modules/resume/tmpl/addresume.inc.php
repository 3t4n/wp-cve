<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('tinymcejsjobs.js', site_url('wp-includes/js/tinymce/tinymce.min.js'));
wp_enqueue_script('jquery-ui-datepicker');
//wp_enqueue_script('multi-files-selector', JSJOBS_PLUGIN_URL . 'includes/js/multi-files-selector.js');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', JSJOBS_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');

$config = jsjobs::$_configuration;
if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$dateformat = $config['date_format'];
$firstdash = jsjobslib::jsjobs_strpos($dateformat, $dash, 0);
$firstvalue = jsjobslib::jsjobs_substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = jsjobslib::jsjobs_strpos($dateformat, $dash, $firstdash);
$secondvalue = jsjobslib::jsjobs_substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = jsjobslib::jsjobs_substr($dateformat, $seconddash, jsjobslib::jsjobs_strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = jsjobslib::jsjobs_str_replace('Y', 'yy', $js_scriptdateformat);
?>
<style type="text/css"> 
.ui-datepicker{
    float: left;
}
</style>
<script type="text/javascript">
    var maindivoffsettop = 0;
    var currenttop = 0;
    jQuery(document).ready(function () {
        jQuery("input.jstokeninputcity").each(function(){
            var jsparent = jQuery(this).parent();
            var cityid = jQuery(jsparent).find('input.jscityid').val();
            var cityname = jQuery(jsparent).find('input.jscityname').val();
            var datafor = jQuery(this).attr('data-for');
            datafor = datafor.split('_');
            getTokenInputResume(datafor, cityid, cityname);
            try { tinymce.execCommand('mceAddEditor', true, 'resume'); } catch (e){console.log(e); }
        });
        //More option
        jQuery("body").delegate('span.jsjobs-resume-moreoptiontitle', 'click', function(e){
            e.preventDefault();
            var img = jQuery(this).find('img');
            if (jQuery('div.jsjobs-resume-moreoption').is(':hidden')) {
                var srcimg = '<?php echo JSJOBS_PLUGIN_URL . 'includes/images/resume/up.png'; ?>';
            } else{
                var srcimg = '<?php echo JSJOBS_PLUGIN_URL . 'includes/images/resume/down.png'; ?>';
            }
            jQuery('div.jsjobs-resume-moreoption').toggle();
            jQuery(img).attr('src', srcimg);
        });
    });

    function removeLogo(id) {
        var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'resume', task: 'deleteresumelogo', resumeid: id, wpnoncecheck:common.wp_jm_nonce}, function (data) {
            if (data) {
                jQuery("img#rs_photo").css("display", "none");
                jQuery("span.remove-file").css("display", "none");
                jQuery("form#jsjobs-form").append('<input type="hidden" name="company_logo_deleted">');
            } else {
                jQuery("div.logo-container").append('<span style="color:Red;"><?php echo __("Error Deleting Logo", "js-jobs"); ?>');
            }
        });
    }

    function getTokenInputResume(datafor, cityid, cityname) {
        var citylink = '<?php echo jsjobs::makeUrl(array('jsjobsme'=>'city', 'task'=>'getaddressdatabycityname', 'action'=>'jsjobtask', 'jsjobspageid'=>jsjobs::getPageid())); ?>';
        citylink = citylink+"&_wpnonce=<?php echo wp_create_nonce('address-data-by-cityname'); ?>";
        var inputfor = datafor[0];
        var sectionid = datafor[1];
        var city = jQuery("#" + inputfor + "cityforedit_"+sectionid).val();     
        if (city != "") {
            jQuery("#" + inputfor + "_city_"+sectionid).tokenInput(citylink, {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                tokenLimit: 1,                
                prePopulate: [{id: cityid, name: cityname}],
                <?php 
                $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                if ($newtyped_cities == 1) { ?>
                    onResult: function(item) {
                        if (jQuery.isEmptyObject(item)){
                            return [{id:0, name: jQuery("tester").text()}];
                        } else {
                                //add the item at the top of the dropdown
                                item.unshift({id:0, name: jQuery("tester").text()});
                                return item;
                            }
                        },
                        onAdd: function(item) {
                            if (item.id > 0){return; }
                            if (item.name.search(",") == - 1) {
                                var input = jQuery("tester").text();
                                alert ("<?php echo __("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "js-jobs"); ?>");
                                jQuery("#" + inputfor + "_city_"+sectionid).tokenInput("remove", item);
                                return false;
                            } else{
                                var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                                jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text(),wpnoncecheck:common.wp_jm_nonce}, function(data){
                                    if (data){
                                        try {
                                            var value = jQuery.parseJSON(data);
                                            jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                            jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('add', {id: value.id, name: value.name});
                                        }
                                        catch (err) {
                                            jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                            //jQuery("#" + fieldname).tokenInput("remove", item);
                                            alert(data);
                                        }
                                    }
                                });
                            }
                        }
                        <?php } ?>
                    });
        }else{
            jQuery("#" + inputfor + "_city_"+sectionid).tokenInput(citylink, {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                tokenLimit: 1,
                <?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                if ($newtyped_cities == 1) { ?>
                    onResult: function(item) {
                        if (jQuery.isEmptyObject(item)){
                            return [{id:0, name: jQuery("tester").text()}];
                        } else {
                            //add the item at the top of the dropdown
                            item.unshift({id:0, name: jQuery("tester").text()});
                            return item;
                        }
                    },
                    onAdd: function(item) {
                        if (item.id > 0){return; }
                        if (item.name.search(",") == - 1) {
                            var input = jQuery("tester").text();
                            alert ("<?php echo __("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "js-jobs"); ?>");
                            jQuery("#" + inputfor + "_city_"+sectionid).tokenInput("remove", item);
                            return false;
                        } else{
                            var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text(),wpnoncecheck:common.wp_jm_nonce}, function(data){
                                if (data){
                                    try {
                                        var value = jQuery.parseJSON(data);
                                        jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                        jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('add', {id: value.id, name: value.name});                                            
                                    }catch (err) {
                                     jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                     alert(data);
                                 }
                             }
                         });
                        }
                    }
                    <?php } ?>
                });

        }

    }    


    function showResumeSection( btn , sec_name){
        var path = 'div#jssection_'+sec_name;
        var obj = jQuery(path).find('.jssection_hide').first();
        var islast = jQuery(path).find('.jssection_hide').next().hasClass('jssection_hide');
        // now enable this section
        jQuery(obj).removeClass('jssection_hide');
        jQuery(obj).find('input.jsdeletethissection').val(0);
        if(!islast){
            jQuery(btn).remove();
        }
        // set required values
        jQuery(obj).find("[data-myrequired]").each(function(){
            var classname = jQuery(this).attr('data-myrequired');
            jQuery(this).addClass(classname);
            jQuery(this).attr('data-validation',classname);
        });
    }

    function deleteThisSection(obj){
        jQuery(obj).hide();
        // custom code
        var main = jQuery(obj).parent();
        jQuery(main).find("[data-validation]").each(function(){
            var classname = jQuery(this).attr('data-myrequired');
            jQuery(this).removeClass(classname);
            jQuery(this).attr('data-validation','');
        });
        main.find('input.jsdeletethissection').val(1);
        main.find('div.jsundo').addClass('jsundodiv');
        main.find('div.jsundo').show();
    }
    
    function undoThisSection(obj){
        var main = jQuery(obj).parent();
        jQuery(main).find("[data-myrequired]").each(function(){
            var classname = jQuery(this).attr('data-myrequired');
            jQuery(this).addClass(classname);
            jQuery(this).attr('data-validation',classname);
        });
        main.hide();
        main.removeClass('jsundodiv');
        main.parent().find('input.jsdeletethissection').val(0);
        main.parent().find('img.jsdeleteimage').show();
    }

    function showdiv(sectionid) {
        document.getElementById('map_'+sectionid).style.visibility = 'visible';
        document.getElementById('map_'+sectionid).style.display = '';
    }
    function hidediv(sectionid) {
        document.getElementById('map_'+sectionid).style.visibility = 'hidden';
        document.getElementById('map_'+sectionid).style.display = 'hidden';
    }
    
    function loadMap( sectionid ) {

        var default_latitude = "<?php echo esc_attr(jsjobs::$_configuration['default_latitude']); ?>";
        var default_longitude = "<?php echo esc_attr(jsjobs::$_configuration['default_longitude']); ?>";

        var latitude = document.getElementById('latitude_'+sectionid).value;
        var longitude = document.getElementById('longitude_'+sectionid).value;
        var marker_flag = 0;
        if ((latitude != '') && (longitude != '')) {
            default_latitude = latitude;
            default_longitude = longitude;
            marker_flag = 1;
        }
        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
        zoom = 10;
        var myOptions = {
            zoom: zoom,
            center: latlng,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_container_"+sectionid), myOptions);
        var lastmarker = new google.maps.Marker({
            postiion: latlng,
        });
        var marker = new google.maps.Marker({
            position: latlng,
        });
        if(marker_flag == 1){
            marker.setMap(map);
        }
        
        lastmarker = marker;
        //document.getElementById('latitude_'+sectionid).value = marker.position.lat();
        //document.getElementById('longitude_'+sectionid).value = marker.position.lng();

        google.maps.event.addListener(map, "click", function (e) {
            var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (lastmarker != ''){
                        lastmarker.setMap(null);
                    }
                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map: map,
                    });
                    marker.setMap(map);
                    lastmarker = marker;
                    document.getElementById('latitude_'+sectionid).value = marker.position.lat();
                    document.getElementById('longitude_'+sectionid).value = marker.position.lng();

                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        });
    }
    //jQuery(document).ready(function(){});
    function getVisible() {    
        if(jQuery('div#jsjobs-wrapper').length){
            var div = jQuery('div#jsjobs-wrapper');
        }else if(jQuery('div#'+common.theme_chk_prefix+'-reume-form-wrap').length){
            var div = jQuery('div#'+common.theme_chk_prefix+'-reume-form-wrap');
        }
        var maxheight = jQuery(div).outerHeight();
        var divheight = jQuery('div.js-jobs-resume-apply-now-visitor').height();
        var scrolltop = jQuery(document).scrollTop();
        tagheight = currenttop + scrolltop - divheight;
        if(tagheight > maxheight){
            tagheight = maxheight - divheight - 15;
        }      
        jQuery('div.js-jobs-resume-apply-now-visitor').css('top',tagheight+'px');
    }
    function cancelJobApplyVisitor(){
        var result = confirm("<?php echo __("Are you sure to cancel job apply","js-jobs"); ?>");
        if(result == true){
            jQuery.post(ajaxurl,{action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'canceljobapplyasvisitor',wpnoncecheck:common.wp_jm_nonce},function(data){
                if(data){
                    window.location = data;
                }   
            });
        }
    }
    function JobApplyVisitor(){
        var resumeid = jQuery('#resume_temp').val();
        if(resumeid == -1){
            alert("<?php echo __("Please first save the resume then apply","js-jobs"); ?>");
        }else{                
            jQuery.post(ajaxurl,{action: 'jsjobs_ajax', jsjobsme: 'jobapply', task: 'visitorapplyjob',wpnoncecheck:common.wp_jm_nonce},function(data){
               if(data){
                   window.location = data;
               } 
           });
        }
    }

    jQuery(document).ready(function(){

        if(jQuery('div#jsjobs-wrapper').length){
            maindivoffsettop = jQuery('div#jsjobs-wrapper').offset().top;
        }else if(jQuery('div#'+common.theme_chk_prefix+'-reume-form-wrap').length){
            maindivoffsettop = jQuery('div#'+common.theme_chk_prefix+'-reume-form-wrap').offset().top;
        }
        
        currenttop = jQuery(window).height() - maindivoffsettop;
        currenttop = currenttop - 12;
        jQuery('div.js-jobs-resume-apply-now-visitor').css('top',currenttop+'px');
        jQuery(window).on('scroll resize', getVisible);            
    });



    var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
    var resumefiles = [];
    var k = <?php if (isset(jsjobs::$_data[0]['file_section']) && is_array(jsjobs::$_data[0]['file_section'])) { echo COUNT(jsjobs::$_data[0]['file_section']); } else { echo 0; } ?>;
    var formvalidcheck = true;
    //Show resumefiles in the popup
    function showResumeFilesArrayPopup(){
        jQuery('div#resumefileswrapper span.livefiles').html('');
        jQuery('span#resume-files-selected').html('');
        for (i = 0; i < resumefiles.length; i++){
            var obj = resumefiles[i];
            var objHTML = '<div class="resumefileselected';
            if (obj.canupload == 0){
                objHTML += ' errormsg '
            }
            objHTML += '"><span class="filename">' + obj.file.name + '</span><span class="filesize">( ' + (obj.file.size / 1024) + ' KB )</span>';
            objHTML += '<button onclick="removeFileByIndex(' + i + ');"><?php echo __("Remove", "js-jobs"); ?></button>';
            objHTML += '</div>';
            if (obj.canupload == 0){
                objHTML += '<div class="error_msg"><b><?php echo __("Error", "js-jobs"); ?>:</b> ' + obj.reason + '</div>';
            }
            jQuery('span#resume-files-selected').append(objHTML);
            // append in main resume form
            if (obj.canupload == 1){
                var mHTML = '<a href="javascript:void(0);" onclick="removeFileByIndex(' + i + ');" class="file"><span class="filename">' + obj.file.name + '</span><span class="fileext"> </span><img class="filedownload" src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/resume/cancel.png" /></a>';
                jQuery('div#resumefileswrapper span.livefiles').append(mHTML);
            }
        }
    }

    //Personal files select
    jQuery("body").delegate("span.clickablefiles", "click", function(e){
        jQuery('input#resumefiles').click();
        jQuery("input#resumefiles").change(function(){
            var srcimage = jQuery('img.rs_photo');
            var files = this.files;
            for (i = 0; i < files.length; i++){
                var fileext = files[i].name.split('.').pop();
                var filesize = (files[i].size / 1024);
                var allowedExt = '<?php echo JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_file_type'); ?>';
                var allowedSize = '<?php echo JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_file_size'); ?>';
                var maxFiles = <?php echo JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('document_max_files'); ?>;
                allowedExt = allowedExt.split(',');
                // check if the file is already inserted or not
                var alreadyinserted = 0;
                if (resumefiles.length > 0){
                    for (m = 0; m < resumefiles.length; m++){
                        var aobj = resumefiles[m];
                        if (aobj.file.name == files[i].name){
                            if (aobj.file.size == files[i].size){
                                if (aobj.file.type == files[i].type){
                                    alreadyinserted = 1;
                                }
                            }
                        }
                    }
                }
                if (alreadyinserted == 0){
                    canupload = 0;
                    reason = '';
                    fileext = fileext.toLowerCase();
                    if (maxFiles > k ){
                        if (allowedSize > filesize){
                            if (jQuery.inArray(fileext, allowedExt) != - 1){
                                canupload = 1;
                                k++;
                            } else{
                                reason = '<?php echo __("File extension mismatch", "js-jobs"); ?>';
                            }
                        } else{
                            reason = '<?php echo __("File size exceeds limit", "js-jobs"); ?>';
                        }
                    } else{
                        reason = '<?php echo __("Maximum files selected", "js-jobs"); ?>';
                    }

                    resumefiles.push({'canupload': canupload, 'reason': reason, 'file':files[i]});
                }
                console.log('alreadyinserted = ' + alreadyinserted + ' value of k ' + k);
            }
            showResumeFilesArrayPopup();
        });
    });
    function addValidateCustom(){
        config = {
            onError: function(){
                formvalidcheck = false;
                console.log('Form invalid data not correct');
            }
        }
        jQuery.validate(config);
    }

    //Delete resume file stored in db
    function deleteResumeFile(id){
        var confirmDelete = confirm("<?php echo __("Confirm to delete resume file", "js-jobs").' ?'; ?>");
        if (confirmDelete == false) {
            return false;
        }
        jQuery.post(ajaxurl, {jsjobsme:'resume', action:'jsjobs_ajax', task:'removeResumeFileById', id:id,wpnoncecheck:common.wp_jm_nonce}, function (data){
            if (data){
                jQuery('a#file_' + id).remove();
                k--;
            }
        });
    }
    //Common section add
    jQuery("body").delegate('a.add', 'click', function(e){
        e.preventDefault();
        var anchor = jQuery(this);
        var parentDiv = jQuery(this).before();
        var section = jQuery(this).attr('data-section');
        var resumeid = jQuery('input#resume_temp').val();
        if (!resumeid.trim()){
            alert("<?php echo __("Please first save resume personal section then add any other section","js-jobs"); ?>");
            return false;
        }
        jQuery('div#resume-wating').show();
        jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'resume', task: 'getResumeSectionAjax', section: section, resumeid: resumeid,wpnoncecheck:common.wp_jm_nonce}, function(data){
            jQuery(parentDiv).after(data);
            jQuery(anchor).remove();
            addDatePicker();
            addValidateCustom();
            jQuery('div#resume-wating').hide();
        });
    });
    function removeFileByIndex(index){
        if (resumefiles.indexOf(index) == - 1){
            resumefiles.splice(index, 1);
            k--;
            showResumeFilesArrayPopup();
        }
        return false;
    }

    function addDatePicker(){
        jQuery('.custom_date').datepicker({dateFormat: '<?php echo esc_js($js_scriptdateformat); ?>'});
    }
    jQuery(document).ready(function () {
        addDatePicker();
        jQuery("div#black_wrapper_jobapply,div#warn-message span.close-warnmessage,div#resume-files-popup-wrapper span.close-resume-files").click(function () {
            jQuery("div#warn-message").fadeOut();
            jQuery("div#black_wrapper_jobapply").fadeOut();
            jQuery("div#resume-files-popup-wrapper").fadeOut();
        });
        //More option
        jQuery("body").delegate('span.resume-moreoptiontitle', 'click', function(e){
            e.preventDefault();
            var img = jQuery(this).find('img');
            if (jQuery('div.resume-moreoption').is(':hidden')) {
                var srcimg = '<?php echo JSJOBS_PLUGIN_URL . 'includes/images/resume/up.png'; ?>';
            } else{
                var srcimg = '<?php echo JSJOBS_PLUGIN_URL . 'includes/images/resume/down.png'; ?>';
            }
            jQuery('div.resume-moreoption').toggle();
            jQuery(img).attr('src', srcimg);
        });
        //Resume select file
        jQuery("body").delegate('span.resume-selectfiles', 'click', function(e){
            e.preventDefault();
            jQuery('div#black_wrapper_jobapply').show();
            jQuery('div#resume-files-popup-wrapper').fadeIn();
            showResumeFilesArrayPopup();
        });
        //Common section edit
        jQuery("body").delegate('div.section_wrapper a.edit', 'click', function(e){
            jQuery('div#resume-wating').show();
            e.preventDefault();
            var div = jQuery(this).parent().parent();
            var section = jQuery(div).attr('data-section');
            var sectionid = jQuery(div).attr('data-sectionid');
            var resumeid = jQuery('input#resume_temp').val();
            jQuery('a[data-section="' + section + '"]').remove();
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'resume', task: 'getResumeSectionAjax', section: section, sectionid:sectionid, resumeid: resumeid,wpnoncecheck:common.wp_jm_nonce}, function(data){
                jQuery(div).html(data);
                addDatePicker();
                addValidateCustom();
                jQuery('div#resume-wating').hide();
            });
        });
        //Common section delete
        jQuery("body").delegate('div.section_wrapper a.delete', 'click', function(e){            
            e.preventDefault();
            var confirmDelete = confirm("<?php echo __("Are you sure to delete", "js-jobs").' ?'; ?>");
            if (confirmDelete == false) {
                return false;
            }
            jQuery('div#resume-wating').show();
            var div = jQuery(this).parent().parent();
            var section = jQuery(div).attr('data-section');
            var sectionid = jQuery(div).attr('data-sectionid');
            var resumeid = jQuery('input#resume_temp').val();
            jQuery('a[data-section="' + section + '"]').remove();
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'resume', task: 'deleteResumeSectionAjax', section: section, sectionid:sectionid, resumeid: resumeid,wpnoncecheck:common.wp_jm_nonce}, function(data){
                var object = jQuery.parseJSON(data);
                if (object.result == 1){
                    jQuery(div).html(object.msg);
                } else{
                    jQuery(div).prepend(object.msg);
                }
                if (object.canadd == 1){
                    jQuery(div).after(object.anchor);
                }
                jQuery('div#resume-wating').hide();
            });
        });
        //Personal section edit
        jQuery("body").delegate('a.personal_section_edit', 'click', function(e){
            jQuery('div#resume-wating').show();
            e.preventDefault();
            var div = jQuery('div#resume-wrapper');
            var section = 'personal';
            var resumeid = jQuery('input#resume_temp').val();
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'resume', task: 'getResumeSectionAjax', section: section, resumeid: resumeid,wpnoncecheck:common.wp_jm_nonce}, function(data){
                jQuery(div).find('div.resume-top-section').remove();
                jQuery(div).find('div.resume-section-title.personal').remove();
                jQuery(div).find('div[data-section="personal"]').remove();
                jQuery(div).prepend(data);
                addDatePicker();
                addValidateCustom();
                jQuery('div#resume-wating').hide();
            });
        });
        //Skill section edit
        jQuery("body").delegate('a.skilledit', 'click', function(e){
            e.preventDefault();
            var div = jQuery(this).parent().next('div[data-section="skills"]');
            var section = 'skills';
            var resumeid = jQuery('input#resume_temp').val();
            if (!resumeid.trim()){
                alert("<?php echo __("Please first save resume personal section then add any other section","js-jobs"); ?>");
                return false;
            }
            jQuery('div#resume-wating').show();
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'resume', task: 'getResumeSectionAjax', section: section, resumeid: resumeid,wpnoncecheck:common.wp_jm_nonce}, function(data){
                jQuery(div).html(data);
                addValidateCustom();
                jQuery('div#resume-wating').hide();
            });
        });
        //Resume section edit
        jQuery("body").delegate('a.resumeedit', 'click', function(e){
            e.preventDefault();
            var div = jQuery(this).parent().next('div[data-section="resume"]');
            var section = 'resume';
            var resumeid = jQuery('input#resume_temp').val();
            if (!resumeid.trim()){
                alert("<?php echo __("Please first save resume personal section then add any other section","js-jobs"); ?>");
                return false;
            }
            jQuery('div#resume-wating').show();
            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'resume', task: 'getResumeSectionAjax', section: section, resumeid: resumeid,wpnoncecheck:common.wp_jm_nonce}, function(data){
                jQuery(div).html(data);
                try { tinymce.execCommand('mceAddEditor', true, 'resume'); } catch (e){console.log(e); }
                addValidateCustom();
                jQuery('div#resume-wating').hide();
                /*
                 //init quicktags
                 quicktags({id : 'resume'});
                 //init tinymce
                 tinymce.init(tinyMCEPreInit.mceInit['resume']);
                 /*
                 tinymce.init({skin:'wordpress'}); 
                 tinyMCE.execCommand('mceAddEditor', true, 'resume');
                 tinyMCE.execCommand('mceAddControl', true, 'resume');
                 /*                    
                 tinyMCE.execCommand('mceRemoveEditor', true, 'resume');
                 tinyMCE.init({
                 skin : "wordpress",
                 mode : "exact",
                 elements : "resumeeditor"
                 });
                 tinyMCE.execCommand('mceAddEditor', false, 'resume');
                 tinyMCE.execCommand('mceAddControl', true, 'resume');
                 */
             });
        });
        //Personal Edit photo live change
        jQuery("body").delegate("img.rs_photo", "click", function(e){
            jQuery('#photo').click();
            jQuery("input#photo").change(function(){
                var srcimage = jQuery('img.rs_photo');
                readURL(this, srcimage);
            });
        });
        addValidateCustom();
    });
function readURL(input, srcimage) {
    if (input.files && input.files[0]) {
        var fileext = input.files[0].name.split('.').pop();
        var filesize = (input.files[0].size / 1024);
        var allowedsize = <?php echo JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('resume_photofilesize'); ?>;
        var allowedExt = '<?php echo JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type'); ?>';
        allowedExt = allowedExt.split(',');
        if (jQuery.inArray(fileext, allowedExt) != - 1){
            if (allowedsize > filesize){
                var reader = new FileReader();
                reader.onload = function (e) {
                    jQuery(srcimage).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else{
                jQuery('input#photo').replaceWith(jQuery('input#photo').val('').clone(true));
                alert("<?php echo __("File size is greater then allowed file size", "js-jobs"); ?>");
            }
        } else{
            jQuery('input#photo').replaceWith(jQuery('input#photo').val('').clone(true));
            alert("<?php echo __("File ext. is mismatched", "js-jobs"); ?>");
        }
    }
}
function submitresume(){
    var formvalid = jQuery('form.has-validation-callback').isValid({
        onfocusout: false,
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {                    
                validator.errorList[0].element.focus();
            }
        }             
    });
    if(formvalid == false){
        event.preventDefault();
        return;
    }
    var test = true;
    jQuery("form#resumeform :input[type=email]").each(function(){
        var emailValue = jQuery(this).val();
        if(emailValue.length != 0){
            var pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            test = pattern.test(emailValue);
            if (test == false) {
                jQuery(this).css({ "border-color": 'red'});
            }
        }
    });
    if (test == false) {
        alert('Email is not of correct Format');
        event.preventDefault();
        return;
    }
    
    jQuery('div#resume-wating').show();
    var resume = '';
    if(tinyMCE.editors.length > 0){
        var resume = tinyMCE.activeEditor.getContent();
    }
    jQuery('input#resume_edit_val').val(resume);
    jQuery('#resumeform').submit();
}
    //Common resume submit
    function submitresumesection(section, sectionid){
        var resumeid = jQuery('input#resume_temp').val();
        var formdata = new FormData();
        var form = jQuery('div[data-section="' + section + '"]').find('form');
        formvalidcheck = true; // make it always true before submitting the form
        jQuery(form).submit(function(e){
            e.preventDefault();
        });
        jQuery(form).submit();
        if (formvalidcheck == false){
            return false;
        }
        jQuery('div#resume-wating').show();
        jQuery('div[data-section="' + section + '"] input, div[data-section="' + section + '"] select, div[data-section="' + section + '"] textarea').each (
            function(index){
                var input = jQuery(this);
                if(input.attr('type') == 'checkbox'){
                    if(input.attr('checked')){
                        formdata.append(input.attr('name'), input.val());
                    }
                }else if(input.attr('type') == 'radio'){
                    if(input.is(":checked")){
                        formdata.append(input.attr('name'), input.val());
                    }
                }else{
                    formdata.append(input.attr('name'), input.val());
                }
            }
            );
        if (section == 'personal'){
            var videotype = jQuery('input[name=videotype]:checked', form).val();
            formdata.append('videotype', videotype);
            if (jQuery('input#photo').length > 0){
                if (typeof jQuery('input#photo').get(0).files[0] != 'undefined'){
                    var file = jQuery('input#photo').get(0).files[0];
                    formdata.append('photo', file);
                }
            }
            if (resumefiles.length > 0){
                j = 0;
                for (i = 0; i < resumefiles.length; i++){
                    var obj = resumefiles[i];
                    if (obj.canupload == 1){
                        formdata.append('resumefiles[' + j + ']', obj.file);
                        j++;
                    }
                }
            }
            resumefiles = []; // reset the resume file object to not upload again
        }
        formdata.append('action', 'jsjobs_ajax');
        formdata.append('jsjobsme', 'resume');
        formdata.append('task', 'saveResumeSectionAjax');
        formdata.append('section', section);
        formdata.append('sectionid', sectionid);
        formdata.append('id', sectionid);
        formdata.append('resumeid', resumeid);
        formdata.append('wpnoncecheck', common.wp_jm_nonce);
        if (section == 'resume'){
            var resume = tinyMCE.get('resume').getContent();
            formdata.append('resume', resume);
        }
        jQuery.ajax({
            url: ajaxurl,
            //Ajax events
            beforeSend: function (e) {
                            //alert('Are you sure you want to upload document.');
                        },
                        success: function (data) {
                            if (section == 'resume'){
                                tinyMCE.remove();
                            }
                            var object = jQuery.parseJSON(data);
                            if (section != 'resume' && section != 'skills' && section != 'personal'){
                                if (object.canadd == 1){
                                    jQuery('div[data-section="' + section + '"][data-sectionid="' + sectionid + '"]').after(object.anchor);
                                }
                            }
                            if (section == 'personal'){
                                if (object.html === 'error'){
                                    location.reload();
                                }
                                jQuery('input#resume_temp').val(object.resumeid);
                                jQuery('div#jsresume-tags-wrapper').replaceWith(object.tags);
                            }
                            jQuery('div[data-section="' + section + '"][data-sectionid="' + sectionid + '"]').replaceWith(object.html);
                            if (section == 'addresses'){
                                var htmlobject = jQuery.parseHTML(object.html);
                                var id = jQuery(htmlobject).find('div.map').attr('id');
                                if (document.getElementById('script_' + id) != 'undefined' && document.getElementById('script_' + id) != null){
                                    document.getElementById('script_' + id).innerHTML;
                                }
                            }
                            jQuery('div#resume-wating').hide();
                        },
                        error: function (e) {
                        //alert('error ' + e.message);
                    },
            // Form data
            data: formdata,
            type: 'POST',
            //Options to tell jQuery not to process data or worry about content-type.
            cache: false,
            contentType: false,
            processData: false
        });
    }
    //Common resume cancel

    function cancelresume(){


    }

    function cancelresumesection1(section, sectionid){
        jQuery('div#resume-wating').show();
        var resumeid = jQuery('input#resume_temp').val();
        var params = {};
        params['action'] = 'jsjobs_ajax';
        params['jsjobsme'] = 'resume';
        params['task'] = 'cancelResumeSectionAjax';
        params['section'] = section;
        params['sectionid'] = sectionid;
        params['resumeid'] = resumeid;
        params['wpnoncecheck'] = common.wp_jm_nonce;
        jQuery.post(ajaxurl, params, function(data){
            if (section == 'resume'){
                tinyMCE.remove();
            }
            var object = jQuery.parseJSON(data);
            if (section != 'resume' && section != 'skills' && section != 'personal'){
                if (object.canadd == 1){
                    jQuery('div[data-section="' + section + '"][data-sectionid="' + sectionid + '"]').after(object.anchor);
                }
            }
            jQuery('div[data-section="' + section + '"][data-sectionid="' + sectionid + '"]').replaceWith(object.html);
            if (section == 'addresses'){
                var htmlobject = jQuery.parseHTML(object.html);
                var id = jQuery(htmlobject).find('div.map').attr('id');
                if (document.getElementById('script_' + id) != 'undefined' && document.getElementById('script_' + id) != null){
                    document.getElementById('script_' + id).innerHTML;
                }
            }
            jQuery('div#resume-wating').hide();
        });
    }

    function getTokenInput(fieldname, fieldeditname) {
        var citylink = '<?php echo jsjobs::makeUrl(array('jsjobsme'=>'city', 'task'=>'getaddressdatabycityname', 'action'=>'jsjobtask', 'jsjobspageid'=>jsjobs::getPageid())); ?>';
        citylink = citylink+"&_wpnonce=<?php echo wp_create_nonce('address-data-by-cityname'); ?>";

        var city = jQuery("#" + fieldeditname).val();
        alert(city);
        if (city != "") {
            city = jQuery.parseJSON(city);
            jQuery("#" + fieldname).tokenInput(citylink, {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                tokenLimit: 1,
                prePopulate: [{id:city.id, name:city.name}],
                <?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                if ($newtyped_cities == 1) { ?>

                    onResult: function(item) {
                        if (jQuery.isEmptyObject(item)){
                            return [{id:0, name: jQuery("tester").text()}];
                        } else {
                    //add the item at the top of the dropdown
                    item.unshift({id:0, name: jQuery("tester").text()});
                    return item;
                }
            },
            onAdd: function(item) {
                if (item.id > 0){return; }
                if (item.name.search(",") == - 1) {
                    var input = jQuery("tester").text();
                    alert ("<?php echo __("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "js-jobs"); ?>");
                    jQuery("#" + fieldname).tokenInput("remove", item);
                    return false;
                } else{
                    var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                    jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text(),wpnoncecheck:common.wp_jm_nonce}, function(data){
                        if (data){
                            try {
                                var value = jQuery.parseJSON(data);
                                jQuery('#' + fieldname).tokenInput('remove', item);
                                jQuery('#' + fieldname).tokenInput('add', {id: value.id, name: value.name});
                            }
                            catch (err) {
                                jQuery("#" + fieldname).tokenInput("remove", item);
                                alert(data);
                            }
                        }
                    });
                }
            }
            <?php } ?>
        });
        } else {
            jQuery("#" + fieldname).tokenInput(citylink, {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo __('Type In A Search Term', 'js-jobs'); ?>",
                noResultsText: "<?php echo __('No Results', 'js-jobs'); ?>",
                searchingText: "<?php echo __('Searching', 'js-jobs'); ?>",
                tokenLimit: 1,
                <?php $newtyped_cities = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                if ($newtyped_cities == 1) { ?>
                    onResult: function(item) {
                        if (jQuery.isEmptyObject(item)){
                            return [{id:0, name: jQuery("tester").text()}];
                        } else {
                            //add the item at the top of the dropdown
                            item.unshift({id:0, name: jQuery("tester").text()});
                            return item;
                        }
                    },
                    onAdd: function(item) {
                        if (item.id > 0){return; }
                        if (item.name.search(",") == - 1) {
                            var input = jQuery("tester").text();
                            alert ("<?php echo __("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "js-jobs"); ?>");
                            jQuery("#" + fieldname).tokenInput("remove", item);
                            return false;
                        } else{
                            var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
                            jQuery.post(ajaxurl, {action: 'jsjobs_ajax', jsjobsme: 'city', task: 'savetokeninputcity', citydata: jQuery("tester").text(),wpnoncecheck:common.wp_jm_nonce}, function(data){
                                if (data){
                                    try {
                                        var value = jQuery.parseJSON(data);
                                        jQuery('#' + fieldname).tokenInput('remove', item);
                                        jQuery('#' + fieldname).tokenInput('add', {id: value.id, name: value.name});
                                    }
                                    catch (err) {
                                        jQuery("#" + fieldname).tokenInput("remove", item);
                                        alert(data);
                                    }
                                }
                            });
                        }
                    }
                    <?php } ?>
                });
        }

    }

    function initialize(lat, lang, div) {
        var myLatlng = new google.maps.LatLng(lat, lang);
        var myOptions = {
            zoom: 8,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById(div), myOptions);
        var marker = new google.maps.Marker({
            map: map,
            position: myLatlng
        });
    }

    function initializeEdit(lat, lang, div) {
        var myLatlng = new google.maps.LatLng(lat, lang);
        var myOptions = {
            zoom: 8,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById(div), myOptions);
        var marker = new google.maps.Marker({
            map: map,
            position: myLatlng
        });
        var lastmarker = marker;
        google.maps.event.addListener(map, "click", function (e) {
            var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (lastmarker != '')
                        lastmarker.setMap(null);
                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map: map,
                    });
                    marker.setMap(map);
                    lastmarker = marker;
                    document.getElementById('latitude_' + div).value = marker.position.lat();
                    document.getElementById('longitude_' + div).value = marker.position.lng();
                } else {
                    alert("<?php echo __("Geocode was not successful for the following reason", "js-jobs"); ?>: " + status);
                }
            });
        });
    }
    jQuery(document).ready(function(){
        var print_link = document.getElementById('print-link');
        if (print_link) {
            <?php $resumeid = isset(jsjobs::$_data[0]["personal_section"]->id) ? jsjobs::$_data[0]["personal_section"]->id : 0; ?>
            var href = '<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'printresume', 'jsjobsid'=>$resumeid, 'jsjobspageid'=>jsjobs::getPageid()))); ?>';
            print_link.addEventListener('click', function (event) {
                print = window.open(href, 'print_win', 'width=1024, height=800, scrollbars=yes');
                event.preventDefault();
            }, false);
        }
    });

</script>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jsjobs-repaptcha-scripti','https://maps.googleapis.com/maps/api/js?key='.jsjobs::$_configuration['google_map_api_key']);
?>
