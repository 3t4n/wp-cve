<?php 
if (!defined('WPINC')) {
    die('Closed');
}
if ( ! current_user_can( 'manage_options')){
    wp_die(__('Access denied','custom-registration-form-builder-with-submission-manager'));
}
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
// $checkbox_status = array('1'=>'checked="checked"', '0'=>'');
?>
<!-- <div class="rmheader">Arrange Admin Menu</div> -->
<div class="rmagic">
    <!--Dialogue Box Starts-->
    <?php
        if (class_exists('AAM')){
            ?>
            <div class ="rmnotice" style="min-height:45px; margin-bottom:1rem">
            <?php
            echo __("<strong>Important:</strong> This section allows you to control RegistrationMagic's admin menu.  It appears you are already using AAM Plugin to control access to your admin menu items. We advise you to keep this feature turned off while you are using AAM to avoid conflict between similar functionalities.",'custom-registration-form-builder-with-submission-manager');
            ?>
            </div>
            <?php
        }
    ?>
    <div class="rmcontent">
        <?php

        $gopts = new RM_Options();
        $subMenus = $gopts->get_value_of('admin_order');
        $indexbadge =$gopts->get_value_of('inbox_badge');
        
        // making list of ordered slugs in string
        $menu_list = "";
        foreach($subMenus as $i => $sb) {
            if ($i == 0){
                $menu_list =  $sb[0];
            }else{
                $menu_list =  $menu_list.",".$sb[0];
            }
        }
        
        $tabs  = "<div class='rmrow'>";
        $tabs .= "<div class='rmfield'>".__('Enable Custom Menu Arrangement', 'custom-registration-form-builder-with-submission-manager')."</div>";
        if($data['enable_admin_order'] == 'yes') {
            $tabs .= "<div class='rminput'><input id='rm_arrange_admin_menu_option' type='checkbox' name='enable_admin_order' onclick='show_more_admin_tabs_options(this)' value='yes' checked></div>" ;
            $tabs .= "</div>";
            $tabs .= "<div class='childfieldsrow' id='rm-tabs-sorting-box-wrap'>";
        } else {
            $tabs .= "<div class='rminput'><input id='rm_arrange_admin_menu_option' type='checkbox' name='enable_admin_order' onclick='show_more_admin_tabs_options(this)' value='yes'></div>" ;
            $tabs .= "</div>";
            $tabs .= "<div class='childfieldsrow' id='rm-tabs-sorting-box-wrap' style='display:none'>";
        }
        $tabs .= "<div class='rm-profile-tabs-wrap rm-tabs-sorting-box rm-form-builder-box1'>";
        $tabs .= "<ul class='rm_sortable_form_rows ui-sortable rm-admin-menu-arrangement' id='rm-field-sortable-tabs'>";

        $menu_items = array();
        foreach($subMenus as $menus){
            $menu_items[$menus[1]] = array($menus[0], $menus[3], $menus[2], $menus[4]);
        }

        foreach ( $menu_items as $menu_item_title => $menu_item_slug ) {
            if(!class_exists('Registration_Magic_Addon')) {
                if ($menu_item_slug[0] == "rm_attachment_manage") {
                    $tabs .= tabLists($menu_item_title, $menu_item_slug[0], $menu_item_slug[1], $menu_item_slug[2], $menu_item_slug[3], $indexbadge, "rm-premium");
                } elseif ($menu_item_slug[0] == "rm_analytics_show_field") {
                    $tabs .= tabLists($menu_item_title, $menu_item_slug[0], $menu_item_slug[1], $menu_item_slug[2], $menu_item_slug[3], $indexbadge, "rm-premium");
                } else {
                    $tabs .= tabLists($menu_item_title, $menu_item_slug[0], $menu_item_slug[1], $menu_item_slug[2], $menu_item_slug[3], $indexbadge, "");
                }
            } else {
                $tabs .= tabLists($menu_item_title, $menu_item_slug[0], $menu_item_slug[1], $menu_item_slug[2], $menu_item_slug[3], $indexbadge, "");
            }
        }

        $tabs .= "<input type='hidden' id='rm_admin_order' name='order' value='$menu_list' />";
        $tabs .= "<input type='hidden' id='restore' name='restore' value='false' />";
        
        $tabs .= "</ul>";
        $tabs .= "</div>";
        $tabs .= "</div>";


//PFBC form
        $form = new RM_PFBC_Form("options_admin_menu");
        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        $form->addElement(new Element_HTML('<div class="rmheader rmAdminHeader rm-d-flex rm-box-justify rm-box-center">'.__('Arrange Admin Menu', 'custom-registration-form-builder-with-submission-manager').' <button class="rm-menu-restore" type="submit">'. __('Restore Default', 'custom-registration-form-builder-with-submission-manager') .'</button></div>'));
        $form->addElement(new Element_HTML($tabs));
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','custom-registration-form-builder-with-submission-manager'), '?page=rm_options_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'),"submit",array('id' => 'reorder-submit-button')));
        $form->render();
        ?>

    </div>
</div>
<!-- </div> -->

<div id="rmtoast-notice"><div id="rmtoast-icon"><span class="material-icons">notifications</span></div><div id="rm-toast-desc"><?php _e("Settings page will always be accessible to the admins even if it is hidden ",'custom-registration-form-builder-with-submission-manager') ?></div></div>


<?php

function tabLists($title = 'title', $slug = 'slug', $visiblity = 'visible', $access = array("administrator"), $divider = "false", $indexbadge = 1, $li_class = "a"){
    $eye_class = "";
    $hidden_check = "";
    if($visiblity == 'visible'){
        $eye_class = 'dashicons-visibility';
    }else{
        $eye_class = 'dashicons-hidden';
        $hidden_check = 'checked';
    }
    $roles = wp_roles()->roles;

    $code = "<li id=". $slug ." class='rm-fields-row visibile_list $li_class'>";
    // tab info
    $code .= "<div class='rm-profile-tab-slab rm_profile_tab'>";
    $code .= "<div class='rm-field-move rm_sortable_handle ui-sortable-handle'><span class='rm-profile-tab-drag-icon'></span></div>";
    $code .= "<div class='rm-slab-info'>".esc_html($title) ."</div>";
    $code .= "<div class='rm-slab-buttons ep-arrow ar '><span class='dashicons dashicons-arrow-down'></span></div>";
    $code .= "<div class='rm-slab-buttons eye'><span class='dashicons eye $eye_class'></span></div>";
    $code .= "</div>";
    
    // hidden tab info
    $code .= "<div class='rm_profile_tab-setting' style='display:none;'>";
    // title
    $code .= "<div class='rmrow'>";
    $code .= "<div class='rmfield'>";
    $code .= "<label>".__( 'Title','custom-registration-form-builder-with-submission-manager' )."</label>";
    $code .= "</div>";
    $code .= "<div class='rminput'>";
    $code .= "<input type='text' id='". esc_html($slug)  ."_title' name='". esc_html($slug)  ."_title' value='". esc_html($title) ."'>";
    $code .= "</div>";
    $code .= "</div>";
    
    if (class_exists('RM_DBManager_Addon')){
        // badge count
        if ($slug == "rm_submission_manage"){
            $code .= "<div class='rmrow'>";
            $code .= "<div class='rmfield'>";
            $code .= "<label for='badge'>".__( 'Badge Count','custom-registration-form-builder-with-submission-manager' )."</label>";
            $code .= "</div>";
            $code .= "<div class='rminput'>";
            $code .= "<select id='badge' name='". esc_html($slug)  ."_badge'>";
            if ($indexbadge == 0){
                $code .= "<option value='1'>Unread</option>";
                $code .= "<option value='2'>Last 1 Hour</option>";
                $code .= "<option value='3'>Last 24 Hour</option>";
                $code .= "<option value='0' selected>None</option>";
            } else if ($indexbadge == 1){
                $code .= "<option value='1' selected>Unread</option>";
                $code .= "<option value='2'>Last 1 Hour</option>";
                $code .= "<option value='3'>Last 24 Hour</option>";
                $code .= "<option value='0'>None</option>";
            } else if ($indexbadge == 2){
                $code .= "<option value='1'>Unread</option>";
                $code .= "<option value='2' selected>Last 1 Hour</option>";
                $code .= "<option value='3'>Last 24 Hour</option>";
                $code .= "<option value='0'>None</option>";
            }else{
                $code .= "<option value='1'>Unread</option>";
                $code .= "<option value='2'>Last 1 Hour</option>";
                $code .= "<option value='3' selected>Last 24 Hour</option>";
                $code .= "<option value='0'>None</option>";
            }
            $code .= "</select>";
            $code .= "</div>";
            $code .= "</div>";       
        }
    }
    
    // Accessible to
    $code .= "<div class='rmrow rm-access'>";
    $code .= "<div class='rmfield'>";
    $code .= "<label>".__( 'Accessible to','custom-registration-form-builder-with-submission-manager' )."</label>";
    $code .= "</div>";
    $code .= "<div class='rminput admin-accessable'>";

    foreach ( $roles as $role_slug => $role ) {
        $code .= "<div class='rm-admin-checkbox-label'>";
        if ($role['name'] ==  "Administrator"){
            $code .= "<div class='administrator-box-$slug administrator-box'>";
            $code .= "<input type='checkbox' id='administrator' class='administrator rm-admin-checkbox' style='margin-top:0' checked>";
            $code .= "<label for='administrator' style='display: inline; margin-left:0.5rem'>".__('Administrators','custom-registration-form-builder-with-submission-manager')."</label> <br>";
            $code .= "<div class='rm-admin-popover rm-premium-popover' style='display:none'><span class='rm-premium-popover-nub'></span>".__("You can't remove your own access from menu items. You can instead choose to hide them by clicking <span class='dashicons dashicons-visibility'></span> on top right of this tab", 'custom-registration-form-builder-with-submission-manager')."</div>";
            $code .= "</div>";
        }else{
            if (in_array($role_slug, $access)){
                $code .= "<input type='checkbox' id='". esc_html($slug) ."_". esc_html($role['name']) ."'  class='rm-admin-checkbox' name='". esc_html($slug) ."_". esc_html(str_replace(' ', '_', $role['name'])) ."' value='". esc_html($role['name']) ."' checked>";
            }else{
                $code .= "<input type='checkbox' id='". esc_html($slug) ."_". esc_html($role['name']) ."'  class='rm-admin-checkbox' name='". esc_html($slug) ."_". esc_html(str_replace(' ', '_', $role['name'])) ."' value='". esc_html($role['name']) ."'>";
            }
            $code .= "<label for='". esc_html($slug) ."_". esc_html($role['name']) ."' style='display: inline; margin-left:0.5rem'>". esc_html($role['name']) ."</label> <br>";    
        }
        $code .= "</div>";
    }

    $code .= "</div>";

    $code .= "<div class='rm-checkbox-alert'>";
    $code .= "<div class='alert-message-$slug'></div>";
    $code .= "</div>";

    $code .= "</div>";

    // divider
    $code .= "<div class='rmrow'>";
    $code .= "<div class='rmfield'>";
    $code .= "<label>".__( 'Divider below this menu','custom-registration-form-builder-with-submission-manager' )."</label>";
    $code .= "</div>";
    $code .= "<div class='rminput' style='padding-top:10px'>";
    $code .= "<div class='rm-admin-checkbox-label'>";
    if ($divider == 'true'){
        $code .= "<input type='checkbox' id='". esc_html($slug)  ."_divider' name='". esc_html($slug)  ."_divider' value='true' checked>";
    }else{
        $code .= "<input type='checkbox' id='". esc_html($slug)  ."_divider' name='". esc_html($slug)  ."_divider' value='true'>";
    }
    $code .= "</div>";
    $code .= "</div>";
    $code .= "</div>";
    
    // visiblity check
    $code .= "<div class='rmrow' style='display:none'>";
    $code .= "<div class='rminput'>";
    $code .= "<input type='checkbox' name='". esc_html($slug) ."_hide' value='hide' id='menuVisiblityCheckbox' $hidden_check>";    
    $code .= "</div>";
    $code .= "</div>";

    $code .= "</div>";
    $code .= "</li>";

    return $code; 
}
?>


<script type="text/javascript">
   function show_more_admin_tabs_options(obj){
        if(jQuery(obj).prop("checked") == true){
            jQuery("#rm-tabs-sorting-box-wrap").show();
        } else {
            jQuery("#rm-tabs-sorting-box-wrap").hide();
        }
    }    
    
var elements = document.getElementsByClassName("administrator");
for(var i = 0; i < elements.length; i++) {
    elements[i].disabled = true;
}

jQuery(function($) {
    // restore default
    $('.rm-menu-restore').on('click', function(){
        $('#restore').val('true');
    });

    // validate form
    const admin_form = $('#reorder-submit-button');
    admin_form.click(function(e) {
        var myInput1 = document.getElementById("rm_form_manage");
        var myInput2 = document.getElementById("rm_dashboard_widget_dashboard_title");
        var myInput3 = document.getElementById("rm_submission_manage_title");
        var myInput4 = document.getElementById("rm_sent_emails_manage_title");
        var myInput5 = document.getElementById("rm_attachment_manage_title");
        var myInput6 = document.getElementById("rm_payments_manage_title");
        var myInput7 = document.getElementById("rm_user_manage_title");
        var myInput8 = document.getElementById("rm_analytics_show_form_title");
        var myInput9 = document.getElementById("rm_analytics_show_field_title");
        var myInput10 = document.getElementById("rm_reports_dashboard_title");
        var myInput11 = document.getElementById("rm_user_role_manage_title");
        var myInput12 = document.getElementById("rm_paypal_field_manage_title");
        var myInput13 = document.getElementById("rm_form_manage_cstatus");
        var myInput14 = document.getElementById("rm_ex_chronos_manage_tasks_title");
        var myInput15 = document.getElementById("rm_invitations_manage_title");
        var myInput16 = document.getElementById("rm_options_manage");

        var isValid = true;
        if (myInput1.value.trim() === "" || myInput2.value.trim() === "" || myInput3.value.trim() === "" || myInput4.value.trim() === "" || myInput5.value.trim() === "" || myInput6.value.trim() === "" || myInput7.value.trim() === "" || myInput8.value.trim() === "" || myInput9.value.trim() === "" || myInput10.value.trim() === "" || myInput11.value.trim() === "" || myInput12.value.trim() === "" || myInput13.value.trim() === "" || myInput14.value.trim() === "" || myInput15.value.trim() === "" || myInput16.value.trim() === "") {
            isValid = false;
            alert("<?php _e("Empty title can't be submitted.","custom-registration-form-builder-with-submission-manager"); ?>");
        }
    
        if (!isValid) {
            e.preventDefault();   
        }
    });

    // alert messages
    var allSlugs = ["rm_form_manage", "rm_options_manage", "rm_user_role_manage", "rm_paypal_field_manage", "rm_dashboard_widget_dashboard", "rm_submission_manage","rm_sent_emails_manage", "rm_attachment_manage", "rm_payments_manage", "rm_user_manage", "rm_analytics_show_form", "rm_analytics_show_field", "rm_reports_dashboard", "rm_form_manage_cstatus", "rm_ex_chronos_manage_tasks", "rm_invitations_manage"];
    $.each(allSlugs, function(index, value){
        // administrator alert
        $(".administrator-box-"+value).hover(function() {
            // $(".alert-message-"+value).html("<?php _e("You can't remove your own access from menu items. You can instead choose to hide them by clicking <span class='dashicons dashicons-visibility'></span> on top right of this tab.","custom-registration-form-builder-with-submission-manager")?>");
        }, function() {
            // $(".alert-message-"+value).html("");
        });

        // empty title alert
        var inputField = $("#"+value+"_title");
        inputField.on('keyup', function() {
            var value = inputField.val();
            if (value === '') {
                alert("<?php _e("Empty title can't be submitted.","custom-registration-form-builder-with-submission-manager"); ?>");
            }
        });
    });

    $( "#rm-field-sortable-tabs" ).sortable({
        axis: 'y',
        opacity: 0.7,
        handle: '.rm_sortable_handle',
        update: function (event, ui) {
            var list_sortable = jQuery(this).sortable('toArray');
            list_sortable.pop();
            list_sortable.pop();
            $('#rm_admin_order').val(list_sortable.join(','));
        }
    });
    $( "#rm-field-sortable-tabs" ).disableSelection();

    // Dynamic tab name
    jQuery('input[type=text]').keyup(function(e){
	    var label = jQuery(this).closest('li').find('.rm-slab-info');
        label.text(jQuery(this).val());
    });

    // class toggles
    jQuery(document).ready(function() {
        // arrow and hidden slide toggle
        jQuery("ul#rm-field-sortable-tabs li .rm-profile-tab-slab.rm_profile_tab .ep-arrow ").click(function(event) {
            jQuery(this).find('span.dashicons').toggleClass("dashicons-arrow-up");
            jQuery(this).parent().siblings(".rm_profile_tab-setting").slideToggle();
        });

        // eye toggle
        jQuery("ul#rm-field-sortable-tabs li .rm_profile_tab .rm-slab-buttons.eye").click(function(event) {
            jQuery(this).find('span.eye').toggleClass("dashicons-visibility dashicons-hidden");
            jQuery(this).parent().parent().toggleClass("visibile_list hidden_list");
            var checkbox = jQuery(this).parent().parent().find('#menuVisiblityCheckbox');
            checkbox.prop('checked', !checkbox.prop('checked'));
        });


        jQuery("ul#rm-field-sortable-tabs li#rm_options_manage .rm_profile_tab .rm-slab-buttons.eye").click(function(event) {
            jQuery('#rmtoast-notice').addClass('rmtoast-notice-show');

            setTimeout(function(){
             jQuery('#rmtoast-notice').removeClass('rmtoast-notice-show');
            }, 6000);
          
        });

    });

});


/* function rmlaunch_toast() {
    var x = document.getElementById("rmtoast-Notice")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("rmtoast-notice-show", ""); }, 5000);
} */

</script>


<style>

#rmtoast-notice {
    visibility: hidden;
    max-width: 50px;
    height: 50px;
    /*margin-left: -125px;*/
    margin: auto;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 2px;
    position: fixed;
    z-index: 1;
    left: 0;right:0;
    bottom: 30px;
    font-size: 17px;
    white-space: nowrap;
    overflow:hidden;
}
#rmtoast-notice #rmtoast-icon{
	width: 50px;
	height: 50px;
    float: left;
    padding-top: 16px;
    padding-bottom: 16px;
    box-sizing: border-box;
    background-color: #111;
    color: #fff;
}
#rmtoast-notice #rm-toast-desc{  
    color: #fff;
    padding: 16px;
    overflow: hidden;
	white-space: nowrap;
}

#rmtoast-notice.rmtoast-notice-show {
    visibility: visible;
    -webkit-animation: rmtoast-fadein 0.5s, rmtoast-expand 0.5s 0.5s,rmtoast-stay 3s 1s, rmtoast-shrink 0.5s 2s, rmtoast-fadeout 0.5s 2.5s;
    animation: rmtoast-fadein 0.5s, rmtoast-expand 0.5s 0.5s,rmtoast-stay 3s 1s, rmtoast-shrink 0.5s 4s, rmtoast-fadeout 0.5s 4.5s;
}

@-webkit-keyframes rmtoast-fadein {
    from {bottom: 0; opacity: 0;} 
    to {bottom: 30px; opacity: 1;}
}

@keyframes rmtoast-fadein {
    from {bottom: 0; opacity: 0;}
    to {bottom: 30px; opacity: 1;}
}

@-webkit-keyframes rmtoast-expand {
    from {min-width: 50px} 
    to {min-width: 650px}
}

@keyframes rmtoast-expand {
    from {min-width: 50px}
    to {min-width: 650px}
}
@-webkit-keyframes rmtoast-stay {
    from {min-width: 650px} 
    to {min-width: 650px}
}

@keyframes rmtoast-stay {
    from {min-width: 650px}
    to {min-width: 650px}
}
@-webkit-keyframes rmtoast-shrink {
    from {min-width: 650px;} 
    to {min-width: 50px;}
}

@keyframes rmtoast-shrink {
    from {min-width: 650px;} 
    to {min-width: 50px;}
}

@-webkit-keyframes rmtoast-fadeout {
    from {bottom: 30px; opacity: 1;} 
    to {bottom: 60px; opacity: 0;}
}

@keyframes rmtoast-fadeout {
    from {bottom: 30px; opacity: 1;}
    to {bottom: 60px; opacity: 0;}
}


#rm-tabs-sorting-box-wrap {
        background-color: #fff;
}

#rm-tabs-sorting-box-wrap .rm-profile-tabs-wrap {
    max-width: 100%;
    margin: 0px auto;
    margin-top: 20px;
}

</style>