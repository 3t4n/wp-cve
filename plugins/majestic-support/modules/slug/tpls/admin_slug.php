<?php
if (!defined('ABSPATH'))
die('Restricted Access');
wp_enqueue_script('majesticsupport-responsivetablejs',MJTC_PLUGIN_URL.'includes/js/responsivetable.js');
MJTC_message::MJTC_getMessage();
?>
<!-- main wrapper -->
<div id="msadmin-wrapper">
    <div id="userpopupblack" style="display:none;"></div>
    <div id="userpopup" style="display:none;"></div>
    <!-- left menu -->
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <!-- top bar -->
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('admin_slug'); ?>
        <?php
        $majesticsupport_js ="
            /*Function to Show popUp,Reset*/
            var slug_for_edit = 0;
            jQuery(document).ready(function () {
                jQuery('div#userpopupblack').click(function () {
                    closePopup();
                });
            });

            function resetFrom() {// Resest Form
                jQuery('input#slug').val('');
                jQuery('form#msadmin-form').submit();
            }

            function showPopupAndSetValues(id,slug) {//Showing PopUp
                slug = jQuery('td#td_'+id).html();
                slug_for_edit = id;
                jQuery.post(ajaxurl, {action: 'mjsupport_ajax', mjsmod: 'slug', task: 'getOptionsForEditSlug',id:id ,slug:slug, '_wpnonce':'". esc_attr(wp_create_nonce("get-options-for-edit-slug"))."'}, function (data) {
                    if (data) {
                        var d = jQuery.parseJSON(data);
                        jQuery('div#userpopupblack').css('display', 'block');
                        jQuery('div#userpopup').html(MJTC_msDecodeHTML(d));
                        jQuery('div#userpopup').slideDown('slow');
                    }
                });
            }

            function closePopup() {// Close PopUp
                jQuery('div#userpopup').slideUp('slow');
                setTimeout(function () {
                    jQuery('div#userpopupblack').hide();
                    jQuery('div#userpopup').html('');
                }, 700);
            }

            function getFieldValue() {
                var slugvalue = jQuery('#slugedit').val();
                jQuery('input#'+slug_for_edit).val(slugvalue);
                jQuery('td#td_'+slug_for_edit).html(slugvalue);
                closePopup();
            }

        ";
        wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
        ?>  
        <!-- page content -->
        <div id="msadmin-data-wrp">
            <!-- filter form -->
            <form class="mjtc-filter-form slug-configform" name="msadmin-form" id="conmsadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_slug&task=savehomeprefix"),"save-home-prefix")); ?>">
                <?php echo wp_kses(MJTC_formfield::MJTC_text('prefix', majesticsupport::$_config['home_slug_prefix'], array('class' => 'inputbox mjtc-form-input-field', 'placeholder' => esc_html(__('Home Slug','majestic-support')).' '. esc_html(__('Prefix','majestic-support')))),MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('btnsubmit', esc_html(__('Save','majestic-support')), array('class' => 'button mjtc-form-search')),MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'),MJTC_ALLOWED_TAGS); ?>
                <div class="mjtc-form-help-text">
                    <img src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/view-job-information.png" />
                    <?php echo esc_html(__('This prefix will be added to the slug in case of homepage links.','majestic-support'))?>
                </div>
            </form>
            <!-- filter form -->
            <form class="mjtc-filter-form slug-configform" name="msadmin-form" id="conmsadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_slug&task=saveprefix"),"save-prefix")); ?>">
                <?php echo wp_kses(MJTC_formfield::MJTC_text('prefix', majesticsupport::$_config['slug_prefix'], array('class' => 'inputbox mjtc-form-input-field', 'placeholder' => esc_html(__('Slug','majestic-support')).' '. esc_html(__('Prefix','majestic-support')))),MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('btnsubmit', esc_html(__('Save','majestic-support')), array('class' => 'button mjtc-form-search')),MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'),MJTC_ALLOWED_TAGS); ?>
                <div class="mjtc-form-help-text">
                    <img src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/view-job-information.png" />
                    <?php echo esc_html(__('This prefix will be added to the slug in case of conflict.','majestic-support'))?>
                </div>
            </form>
            <!-- filter form -->
            <form class="mjtc-filter-form" name="msadmin-form" id="msadmin-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_slug"),"slug")); ?>">
                <?php echo wp_kses(MJTC_formfield::MJTC_text('slug', majesticsupport::$_data['slug'], array('class' => 'inputbox mjtc-form-input-field', 'placeholder' => esc_html(__('Search By Slug','majestic-support')))),MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('btnsubmit', esc_html(__('Search','majestic-support')), array('class' => 'button mjtc-form-search')),MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_button('reset', esc_html(__('Reset','majestic-support')), array('class' => 'button mjtc-form-reset', 'onclick' => 'resetFrom();')),MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('MS_form_search', 'MS_SEARCH'),MJTC_ALLOWED_TAGS); ?>
            </form>
            <?php
                if (!empty(majesticsupport::$_data[0])) {
                    ?>
                    <form id="mjtc-list-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=majesticsupport_slug&task=saveSlug"),"save-slug")); ?>">
                        <table id="majestic-support-table" class="majestic-support-table">
                            <thead>
                                <tr class="majestic-support-table-heading">
                                    <th class="left">
                                        <?php echo esc_html(__('Slug List','majestic-support')); ?>
                                    </th>
                                    <th class="left">
                                        <?php echo esc_html(__('Description','majestic-support')); ?>
                                    </th>
                                    <th>
                                        <?php echo esc_html(__('Action','majestic-support')); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $pagenum = MJTC_request::MJTC_getVar('pagenum', 'get', 1);
                                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                                    foreach (majesticsupport::$_data[0] as $row){
                                        ?>
                                        <tr>
                                            <td class="left" id="<?php echo esc_attr('td_').esc_attr($row->id);?>">
                                                <?php echo esc_html($row->slug);?>
                                            </td>
                                            <td class="left">
                                                <?php echo esc_html(majesticsupport::MJTC_getVarValue($row->description));?>
                                            </td>
                                            <td>
                                                <a class="action-btn" href="#" onclick="showPopupAndSetValues(<?php echo esc_js($row->id); ?>)" title="<?php echo esc_attr(__('edit','majestic-support')); ?>">
                                                    <img src="<?php echo esc_url(MJTC_PLUGIN_URL); ?>includes/images/edit.png" alt="<?php echo esc_html(__('edit','majestic-support')); ?>">
                                                </a>
                                            </td>
                                        </tr>
                                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden($row->id, $row->slug),MJTC_ALLOWED_TAGS);?>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <!-- Hidden Fields -->
                        <div class="mjtc-filter-form-action-wrp">
                            <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('btnsubmit', esc_html(__('Save','majestic-support')), array('class' => 'button savebutton mjtc-form-act-btn mjtc-form-act-btn')),MJTC_ALLOWED_TAGS); ?>
                            <div class="mjtc-form-act-msg">
                                <?php echo  esc_html(__('This button will only save slugs on the current page','majestic-support')); ?>!
                            </div>
                        </div>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('task', ''),MJTC_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('pagenum', ($pagenum > 1) ? $pagenum : ''),MJTC_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'),MJTC_ALLOWED_TAGS); ?>
                    </form>
                    <?php
                    if (majesticsupport::$_data[1]) {
                        $data = '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(majesticsupport::$_data[1]) . '</div></div>';
                        echo wp_kses($data, MJTC_ALLOWED_TAGS);
                    }
                } else {
                    MJTC_layout::MJTC_getNoRecordFound();
                }
            ?>
        </div>
    </div>
</div>
