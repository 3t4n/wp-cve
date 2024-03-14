<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('emailtemplates'); ?>
        <div id="msadmin-data-wrp">
            <form method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("?page=majesticsupport_emailtemplate&task=saveemailtemplate"),"save-email-template")); ?>">
                <div class="mjtc-email-menu">
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'tk-nw') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=tk-nw" title="<?php echo esc_attr(__('New Ticket','majestic-support')); ?>"><?php echo esc_html(__('New Ticket', 'majestic-support')); ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'sntk-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=sntk-tk" title="<?php echo esc_attr(__('Agent Ticket','majestic-support')); ?>"><?php echo esc_html(__('Agent Ticket', 'majestic-support')); ?><?php if (!in_array('agent', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'ew-sm') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=ew-sm" title="<?php echo esc_attr(__('New Agent','majestic-support')); ?>"><?php echo esc_html(__('New Agent', 'majestic-support')); ?><?php if (!in_array('agent', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'rs-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=rs-tk" title="<?php echo esc_attr(__('Reassign Ticket','majestic-support')); ?>"><?php echo esc_html(__('Reassign Ticket', 'majestic-support')); ?><?php if (!in_array('agent', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'cl-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=cl-tk" title="<?php echo esc_attr(__('Close Ticket','majestic-support')); ?>"><?php echo esc_html(__('Close Ticket', 'majestic-support')); ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'dl-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=dl-tk" title="<?php echo esc_attr(__('Delete Ticket','majestic-support')); ?>"><?php echo esc_html(__('Delete Ticket', 'majestic-support')); ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'mo-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=mo-tk" title="<?php echo esc_attr(__('Mark overdue','majestic-support')); ?>"><?php echo esc_html(__('Mark Overdue', 'majestic-support')); ?><?php if (!in_array('overdue', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'be-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=be-tk" title="<?php echo esc_attr(__('Ban Email','majestic-support')); ?>"><?php echo esc_html(__('Ban Email', 'majestic-support')); ?><?php if (!in_array('banemail', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'be-trtk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=be-trtk" title="<?php echo esc_attr(__('Ban Email Try To Create Ticket','majestic-support')); ?>"><?php echo esc_html(__('Ban Email Try To Create Ticket', 'majestic-support')); ?><?php if (!in_array('banemail', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'dt-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=dt-tk" title="<?php echo esc_attr(__('Department Transfer','majestic-support')); ?>"><?php echo esc_html(__('Department Transfer', 'majestic-support')); ?><?php if (!in_array('actions', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'ebct-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=ebct-tk" title="<?php echo esc_attr(__('Ban Email and Close Ticket', 'majestic-support')); ?>"><?php echo esc_html(__('Ban Email and Close Ticket', 'majestic-support')); ?><?php if (!in_array('banemail', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'ube-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=ube-tk" title="<?php echo esc_attr(__('Unban Email', 'majestic-support')); ?>"><?php echo esc_html(__('Unban Email', 'majestic-support')); ?><?php if (!in_array('banemail', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'rsp-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=rsp-tk" title="<?php echo esc_attr(__('Response Ticket', 'majestic-support')); ?>"><?php echo esc_html(__('Response Ticket', 'majestic-support')); ?><?php if (!in_array('agent', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'rpy-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=rpy-tk" title="<?php echo esc_attr(__('Reply Ticket', 'majestic-support')); ?>"><?php echo esc_html(__('Reply Ticket', 'majestic-support')); ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'tk-ew-ad') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=tk-ew-ad" title="<?php echo esc_attr(__('New Ticket Admin Alert', 'majestic-support')); ?>"><?php echo esc_html(__('New Ticket Admin Alert', 'majestic-support')); ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'lk-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=lk-tk" title="<?php echo esc_attr(__('Lock Ticket', 'majestic-support')); ?>"><?php echo esc_html(__('Lock Ticket', 'majestic-support')); ?><?php if (!in_array('actions', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'ulk-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=ulk-tk" title="<?php echo esc_attr(__('Unlock Ticket', 'majestic-support')); ?>"><?php echo esc_html(__('Unlock Ticket', 'majestic-support')); ?><?php if (!in_array('actions', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'minp-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=minp-tk" title="<?php echo esc_attr(__('In Progress Ticket', 'majestic-support')); ?>"><?php echo esc_html(__('In Progress Ticket', 'majestic-support')); ?><?php if (!in_array('actions', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'pc-tk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=pc-tk" title="<?php echo esc_attr(__('Ticket Priority Is Changed By', 'majestic-support')); ?>"><?php echo esc_html(__('Ticket Priority Is Changed By', 'majestic-support')); ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'ml-ew') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=ml-ew" title="<?php echo esc_attr(__('New Mail Received', 'majestic-support')); ?>"><?php echo esc_html(__('New Mail Received', 'majestic-support')); ?><?php if (!in_array('mail', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'ml-rp') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=ml-rp" title="<?php echo esc_attr(__('New Mail Message Received', 'majestic-support')); ?>"><?php echo esc_html(__('New Mail Message Received', 'majestic-support')); ?><?php if (!in_array('mail', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'fd-bk') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=fd-bk" title="<?php echo esc_attr(__('Feedback Email To User', 'majestic-support')); ?>"><?php echo esc_html(__('Feedback Email To User', 'majestic-support')); ?><?php if (!in_array('feedback', majesticsupport::$_active_addons)) { ?><span style="color: red;"> *</span><?php } ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'no-rp') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=no-rp" title="<?php echo esc_attr(__('User Reply On Closed Ticket', 'majestic-support')); ?>"><?php echo esc_html(__('User Reply On Closed Ticket', 'majestic-support')); ?></a></span>
                    <span class="mjtc-email-menu-link <?php if (majesticsupport::$_data[1] == 'del-data') echo esc_attr('selected'); ?>"><a class="mjtc-email-link" href="?page=majesticsupport_emailtemplate&for=del-data" title="<?php echo esc_attr(__('Data Deleted', 'majestic-support')); ?>"><?php echo esc_html(__('Data Deleted', 'majestic-support')); ?></a></span>
                </div>
                <div class="mjtc-email-body">
                    <!-- Now add the Dropdown for the Languages -->
                    <?php echo wp_kses(apply_filters( 'ms_get_multilanguage_dropdown',''), MJTC_ALLOWED_TAGS); ?>
                    <div class="mjtc-form-wrapper">

                        <div class="a-mjtc-form-title"><?php echo esc_html(__('Subject', 'majestic-support')); ?></div>
                        <div class="a-mjtc-form-field"><?php echo wp_kses(MJTC_formfield::MJTC_text('subject', majesticsupport::$_data[0]->subject, array('class' => 'inputbox', 'style' => 'width:100%;')), MJTC_ALLOWED_TAGS) ?></div>
                    </div>
                    <div class="mjtc-form-wrapper">
                        <div class="a-mjtc-form-title"><?php echo esc_html(__('Body', 'majestic-support')); ?></div>
                        <div class="a-mjtc-form-field"><?php wp_editor(majesticsupport::$_data[0]->body, 'body', array('media_buttons' => false)); ?></div>
                    </div>
                    <div class="mjtc-email-parameters">
                        <div class="mjtc-email-parameter-heading"><?php echo esc_html(__('Parameters', 'majestic-support')) ?></div>
                        <?php
                        if (majesticsupport::$_data[1] == 'tk-nw') {
                            ?>
                            <span class="mjtc-email-paramater">{USERNAME} : <?php echo esc_html(__('Username', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{HELP_TOPIC} : <?php echo esc_html(__('Help Topic', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{EMAIL} : <?php echo esc_html(__('Email', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{MESSAGE} : <?php echo esc_html(__('Message', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'sntk-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{USERNAME} : <?php echo esc_html(__('Username', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{HELP_TOPIC} : <?php echo esc_html(__('Help Topic', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{EMAIL} : <?php echo esc_html(__('Email', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{MESSAGE} : <?php echo esc_html(__('Message', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'ew-md') {
                            ?>
                            <span class="mjtc-email-paramater">{DEPARTMENT_TITLE} : <?php echo esc_html(__('Department title', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'ew-gr') {
                            ?>
                            <span class="mjtc-email-paramater">{GROUP_TITLE} : <?php echo esc_html(__('Group Title', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'ew-sm') {
                            ?>
                            <span class="mjtc-email-paramater">{AGENT_NAME} : <?php echo esc_html(__('Agent name', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'ew-ht') {
                            ?>
                            <span class="mjtc-email-paramater">{HELPTOPIC_TITLE} : <?php echo esc_html(__('Help topic title', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT_TITLE} : <?php echo esc_html(__('Department title', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'rs-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{AGENT_NAME} : <?php echo esc_html(__('Agent name', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field) ;?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'cl-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{FEEDBACKURL} : <?php echo esc_html(__('Feedback URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                if($field->userfieldtype != 'file'){ ?>
                                    <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                                    <?php
                                }
                            }
                        } elseif (majesticsupport::$_data[1] == 'dl-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'mo-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'be-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{EMAIL_ADDRESS} : <?php echo esc_html(__('Email Address', 'majestic-support')); ?></span>
                            <?php

                        } elseif (majesticsupport::$_data[1] == 'be-trtk') {
                            ?>
                            <span class="mjtc-email-paramater">{EMAIL_ADDRESS} : <?php echo esc_html(__('Email Address', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'dt-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT_TITLE} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'ebct-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{EMAIL_ADDRESS} : <?php echo esc_html(__('Email Address', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETID} : <?php echo esc_html(__('Ticket ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'ube-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{EMAIL_ADDRESS} : <?php echo esc_html(__('Email Address', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'rsp-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{USERNAME} : <?php echo esc_html(__('Username', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{EMAIL} : <?php echo esc_html(__('Email', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{MESSAGE} : <?php echo esc_html(__('Message', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'rpy-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{USERNAME} : <?php echo esc_html(__('Username', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{EMAIL} : <?php echo esc_html(__('Email', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{MESSAGE} : <?php echo esc_html(__('Message', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'tk-ew-ad') {
                            ?>
                            <span class="mjtc-email-paramater">{USERNAME} : <?php echo esc_html(__('Username', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{EMAIL} : <?php echo esc_html(__('Email', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{MESSAGE} : <?php echo esc_html(__('Message', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'lk-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{USERNAME} : <?php echo esc_html(__('Username', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{EMAIL} : <?php echo esc_html(__('Email', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'ulk-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{USERNAME} : <?php echo esc_html(__('Username', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{EMAIL} : <?php echo esc_html(__('Email', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'minp-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'pc-tk') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKINGID} : <?php echo esc_html(__('Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY_TITLE} : <?php echo esc_html(__('Priority', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKETURL} : <?php echo esc_html(__('Ticket URL', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_HISTORY} : <?php echo esc_html(__('Ticket History', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'ml-ew') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{AGENT_NAME} : <?php echo esc_html(__('Agent name', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{MESSAGE} : <?php echo esc_html(__('Message', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'ml-rp') {
                            ?>
                            <span class="mjtc-email-paramater">{SUBJECT} : <?php echo esc_html(__('Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{AGENT_NAME} : <?php echo esc_html(__('Agent name', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{MESSAGE} : <?php echo esc_html(__('Message', 'majestic-support')); ?></span>
                            <?php
                        } elseif (majesticsupport::$_data[1] == 'fd-bk') {
                            ?>
                            <span class="mjtc-email-paramater">{USER_NAME} : <?php echo esc_html(__('User Name', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TICKET_SUBJECT} : <?php echo esc_html(__('Ticket Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{TRACKING_ID} : <?php echo esc_html(__('Ticket Tracking ID', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{CLOSE_DATE} : <?php echo esc_html(__('Close Date', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'no-rp') {
                            ?>
                            <span class="mjtc-email-paramater">{TICKET_SUBJECT} : <?php echo esc_html(__('Ticket Subject', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{DEPARTMENT} : <?php echo esc_html(__('Department', 'majestic-support')); ?></span>
                            <span class="mjtc-email-paramater">{PRIORITY} : <?php echo esc_html(__('Ticket Priority', 'majestic-support')); ?></span>
                            <?php foreach (majesticsupport::$_data[2] as $field ) {
                                    if($field->userfieldtype != 'file'){ ?>
                                        <span class="mjtc-email-paramater">{<?php echo esc_html($field->field);?>} : <?php echo esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)); ?></span>
                            <?php   }
                                }
                        } elseif (majesticsupport::$_data[1] == 'del-data') {
                            ?>
                            <span class="mjtc-email-paramater">{USERNAME} : <?php echo esc_html(__('Username', 'majestic-support')); ?></span>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="mjtc-form-button">
                        <?php echo wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save Email Template', 'majestic-support')), array('class' => 'button mjtc-form-save')), MJTC_ALLOWED_TAGS); ?>
                    </div>
                    <?php
                    if(count(majesticsupport::$_active_addons) < 37 ){  ?>
                        <div class="mjtc-sugestion-alert-wrp mjtc-email-msg">
                            <div class="mjtc-sugestion-alert">
                                <strong>
                                    <?php echo esc_html(__('Note:', 'majestic-support')); ?>
                                </strong>
                                <?php echo esc_html(__('Features marked with', 'majestic-support')); ?>
                                <span>*</span>
                                <?php echo esc_html(__('are only available with its own addon.', 'majestic-support')); ?>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
                <?php
                $majesticsupport_js ="
                    jQuery(document).ready(function(){
                        jQuery('#save').click(function(){
                            var subject = jQuery('#subject').val();
                            var body = jQuery('#body').val();
                            if(subject=='' && body==''){
                                alert('Please Fill the Subject and body');
                                return false;
                            }
                        });
                    });

                ";
                wp_add_inline_script('majestic-support-cmain-js',$majesticsupport_js);
                ?>  

                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('id', majesticsupport::$_data[0]->id), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('created', majesticsupport::$_data[0]->created), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('templatefor', majesticsupport::$_data[0]->templatefor), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('for', majesticsupport::$_data[1]), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('action', 'emailtemplate_saveemailtemplate'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('callfor', 'emailtemplate'), MJTC_ALLOWED_TAGS); ?>
                <?php echo wp_kses(MJTC_formfield::MJTC_hidden('multitemp_id', ''), MJTC_ALLOWED_TAGS); ?>
            </form>
        </div>
    </div>
</div>
