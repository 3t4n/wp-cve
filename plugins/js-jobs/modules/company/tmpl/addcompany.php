<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div id="jsjobs-main-up-wrapper">
<?php
$msgkey = JSJOBSincluder::getJSModel('company')->getMessagekey();
JSJOBSMessages::getLayoutMessage($msgkey);
JSJOBSbreadcrumbs::getBreadcrumbs();
include_once(JSJOBS_PLUGIN_PATH . 'includes/header.php');
if (jsjobs::$_error_flag == null) {
        $msg = isset(jsjobs::$_data[0]) ? __('Edit', 'js-jobs') : __('Add New', 'js-jobs');
        ?>
        <div id="jsjobs-wrapper">
            <div class="page_heading"><?php echo esc_html($msg) . '&nbsp;' . __("Company", 'js-jobs'); ?></div>
            <form class="js-ticket-form" id="company_form" method="post" enctype="multipart/form-data" action="<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'company', 'task'=>'savecompany'))); ?>">
                <?php
            function printFormField($title, $field) {
                $html = '<div class="js-col-md-12 js-form-wrapper">
                                    <div class="js-col-md-12 js-form-title">' . $title . '</div>
                                    <div class="js-col-md-12 js-form-value">' . $field . '</div>
                                </div>';
                return $html;
            }
            $i = 0;
            foreach (jsjobs::$_data[2] AS $field) {
                switch ($field->field) {
                    case 'url':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('url', isset(jsjobs::$_data[0]->url) ? jsjobs::$_data[0]->url : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req, 'onblur' => 'checkUrl(this);'));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'income':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('income', isset(jsjobs::$_data[0]->income) ? jsjobs::$_data[0]->income : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'category':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::select('category', JSJOBSincluder::getJSModel('category')->getCategoryForCombobox(), isset(jsjobs::$_data[0]->category) ? jsjobs::$_data[0]->category : JSJOBSincluder::getJSModel('category')->getDefaultCategoryId(), '', array('class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'contactname':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('contactname', isset(jsjobs::$_data[0]->contactname) ? jsjobs::$_data[0]->contactname : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'name':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('name', isset(jsjobs::$_data[0]->name) ? jsjobs::$_data[0]->name : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'contactemail':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::email('contactemail', isset(jsjobs::$_data[0]->contactemail) ? jsjobs::$_data[0]->contactemail : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => 'email ' . $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'contactphone':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('contactphone', isset(jsjobs::$_data[0]->contactphone) ? jsjobs::$_data[0]->contactphone : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'contactfax':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('companyfax', isset(jsjobs::$_data[0]->companyfax) ? jsjobs::$_data[0]->companyfax : '', array('maxlength' => '250', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'since':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        if (isset(jsjobs::$_data[0]->since)) {
                            $dateformat = jsjobs::$_configuration['date_format'];
                            $sincedate = date_i18n($dateformat, jsjobslib::jsjobs_strtotime(jsjobs::$_data[0]->since));
                            if(jsjobslib::jsjobs_strpos($sincedate , '1970') !== false){
                                $sincedate = "";
                            }
                        } else {
                            $sincedate = "";
                        }
                        $formfield = JSJOBSformfield::text('since', $sincedate, array('class' => 'inputbox custom_date', 'autocomplete' => 'off', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'description':
                        $req = '';
                        $titlereq = '';
                        if ($field->required == 1) {
                            $req = 'required';
                            $titlereq = '<font color="red">*</font>';
                        }
                        ?>
                         <div class="js-col-md-12 js-form-wrapper">
                            <div class="js-col-md-12 js-form-title"><?php echo __('Description', 'js-jobs') . $titlereq; ?></div>
                            <div class="js-col-md-12 js-form-value"><?php wp_editor(isset(jsjobs::$_data[0]->description) ? jsjobs::$_data[0]->description : '', 'description', array('media_buttons' => false, 'data-validation' => $req)); ?></div>
                        </div>                                
                        <?php
                        break;
                    case 'companysize':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('companysize', isset(jsjobs::$_data[0]->companysize) ? jsjobs::$_data[0]->companysize : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'city':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('city', isset(jsjobs::$_data[0]->city) ? jsjobs::$_data[0]->city : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'zipcode':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('zipcode', isset(jsjobs::$_data[0]->zipcode) ? jsjobs::$_data[0]->zipcode : '', array('maxlength' => '25', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'facebook':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('facebook', isset(jsjobs::$_data[0]->facebook) ? jsjobs::$_data[0]->facebook : '', array('maxlength' => '300', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'googleplus':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('googleplus', isset(jsjobs::$_data[0]->googleplus) ? jsjobs::$_data[0]->googleplus : '', array('maxlength' => '300', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'twitter':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('twitter', isset(jsjobs::$_data[0]->twitter) ? jsjobs::$_data[0]->twitter : '', array('maxlength' => '300', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'linkedin':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('linkedin', isset(jsjobs::$_data[0]->linkedin) ? jsjobs::$_data[0]->linkedin : '', array('maxlength' => '300', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'address1':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('address1', isset(jsjobs::$_data[0]->address1) ? jsjobs::$_data[0]->address1 : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'address2':
                        $req = '';
                        $title = __($field->fieldtitle, 'js-jobs');
                        if ($field->required == 1) {
                            $req = 'required';
                            $title .= '<font color="red">*</font>';
                        }
                        $formfield = JSJOBSformfield::text('address2', isset(jsjobs::$_data[0]->address2) ? jsjobs::$_data[0]->address2 : '', array('maxlength' => '255', 'class' => 'inputbox', 'data-validation' => $req));
                        echo wp_kses(printFormField($title, $formfield), JSJOBS_ALLOWED_TAGS);
                        break;
                    case 'logo': ?>
                        <div class="js-col-md-12 js-form-wrapper">
                        <div class="js-col-md-12 js-form-title"><?php echo __('Logo', 'js-jobs') ?></div>
                        <div class="js-col-md-12 js-form-value">
                            <?php
                            if (isset(jsjobs::$_data[0]->logofilename) && jsjobs::$_data[0]->logofilename != "") {
                                $data_directory = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
                                $wpdir = wp_upload_dir();
                                $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . jsjobs::$_data[0]->id . '/logo/' . jsjobs::$_data[0]->logofilename;
                                ?><img id="comp_logo" style="display:inline;width:60px;height:auto;"  src="<?php echo esc_url($path); ?>">
                                        <!-- <span id="logo-name" class="logo-name"></span> -->
                                <span class="remove-file" onClick="return removeLogo(<?php echo esc_js(jsjobs::$_data[0]->id); ?>);"><img src="<?php echo JSJOBS_PLUGIN_URL; ?>includes/images/no.png"></span>
                            <?php                             
                            }
                            ?>
                            <input class="inputbox" id="logo" name="logo" type="file">
                            <?php
                                $logoformat = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type');
                                $maxsize = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('company_logofilezize');
                            echo '('.esc_html($logoformat).')<br>';
                            echo '('.__("Maximum file size","js-jobs").' '.esc_html($maxsize).' Kb)'; ?>
                            </div>
                        </div>

                        <?php
                    break;
                    default:
                        JSJOBSincluder::getObjectClass('customfields')->formCustomFields($field);
                        break;
                }
            }
            ?> 
            <div class="js-col-md-12 js-form-wrapper">
                <?php echo wp_kses(JSJOBSformfield::hidden('id', isset(jsjobs::$_data[0]->id) ? jsjobs::$_data[0]->id : '' ), JSJOBS_ALLOWED_TAGS); ?>
                <?php
                if (!isset(jsjobs::$_data[0]->id)) { // edit case form
                    echo wp_kses(JSJOBSformfield::hidden('uid', JSJOBSincluder::getObjectClass('user')->uid()), JSJOBS_ALLOWED_TAGS);
                } ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('created', isset(jsjobs::$_data[0]->created) ? jsjobs::$_data[0]->created : date('Y-m-d H:i:s')), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('action', 'company_savecompany'), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('jsjobspageid', get_the_ID()), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('creditid', ''), JSJOBS_ALLOWED_TAGS); ?>
                <?php echo wp_kses(JSJOBSformfield::hidden('form_request', 'jsjobs'), JSJOBS_ALLOWED_TAGS); ?>
                <div class="js-col-md-12 bottombutton js-form" id="save-button">			    	
                    <?php
                    echo wp_kses(JSJOBSformfield::submitbutton('save', __('Save','js-jobs') .' '. __('Company', 'js-jobs'), array('class' => 'button', 'onClick' => 'return validate_url();')), JSJOBS_ALLOWED_TAGS);
                    ?>
                </div>
            </div>
            <?php echo wp_kses(JSJOBSformfield::hidden('_wpnonce', wp_create_nonce('save-company')), JSJOBS_ALLOWED_TAGS); ?>
        </form>
    </div>
<?php 
}else{
    echo wp_kses(jsjobs::$_error_flag_message, JSJOBS_ALLOWED_TAGS);
}
?>
</div>
