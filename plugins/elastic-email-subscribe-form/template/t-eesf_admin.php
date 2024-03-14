<?php
defined('EE_ADMIN_SUBSCRIBE_7250232799') or die('No direct access allowed.');

wp_enqueue_style('eesubscribe-bootstrap-grid');
wp_enqueue_style('eesubscribe-css');

if (isset($_GET['settings-updated'])):
    ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Settings saved.', 'elastic-email-subscribe-form') ?></strong></p>
    </div>
<?php endif; ?>

<div class="eewp-eckab-frovd">
<div class="eewp_container">
    <div class="col-12 col-md-12 col-lg-7">
        <div class="ee_header">
            <div class="ee_pagetitle">
                <h1 class="ee_h1"><?php _e('General Settings', 'elastic-email-subscribe-form') ?></h1>
            </div>
        </div>
        <p class="ee_p margin-p-xs">
            <?php
            _e('Welcome to the Elastic Email WordPress Plugin! From now on, you can send your 
            emails in the fastest and most reliable way and collect contacts in the subscription widget! 
            Just one quick step and you will be ready to rock your subscribers\' inbox. Fill in the details 
            about the main configuration of Elastic Email connections.', 'elastic-email-subscribe-form');
            ?>
        </p>

        <form class="settings-box-form" method="post" action="options.php">
            <?php
            settings_fields('ee_option_group');
            do_settings_sections('ee-settings');
            ?>
            <table class="form-table">
                <tbody>
                <tr class="table-slim" valign="top">
                    <?php

                    if (get_option('eesf-connecting-status') === 'connecting') {
                        if (empty($error) === true) {
                            $error_stat = 'ee_success';
                        }
                    }
                    if (get_option('eesf-connecting-status') === 'disconnected') {
                        if (empty($error) === false) {
                            $error_stat = 'ee_error';
                        } else {
                            $error = 'false';
                            $error_stat = 'ee_error';
                        }
                    }
                    ?>

                    <th scope="row"><?php _e('Connection Test:', 'elastic-email-subscribe-form') ?></th>
                    <td> <span class="<?php echo $error_stat ?>">

                        <?php
                        if (get_option('eesf-connecting-status') === 'connecting') {
                            if (empty($error) === true) {
                                _e('Connected', 'elastic-email-subscribe-form');
                            }
                        }
                        if (get_option('eesf-connecting-status') === 'disconnected') {
                            if (empty($error) === false) {
                                _e('Connection error, check your API key. ', 'elastic-email-subscribe-form');
                            }
                        }
                        ?>
                            </span></td>
                </tr>
                <tr class="table-slim" valign="top">
                    <th scope="row"><?php _e('Account status:', 'elastic-email-subscribe-form') ?></th>
                    <td>
                        <?php

                        if (isset($accountstatus)) {
                            if ($accountstatus === 1) {
                                $accountstatusname = '<span class="ee_account-status-active">' . __('Active', 'elastic-email-subscribe-form') . '</span>';
                            } else {
                                $accountstatusname = '<span class="ee_account-status-deactive">' . __('Please conect to Elastic Email API or complete the profile', 'elastic-email-subscribe-form') . ' <a href="https://elasticemail.com/account/#/account/profile">' . __('Complete your profile', 'elastic-email-subscribe-form') . '</a>' . __(' or connect to Elastic Email API to start using the plugin.', 'elastic-email-subscribe-form') . '</span>';
                            }
                        } else {
                            $accountstatusname = '<span class="ee_account-status-deactive">' . __('Please conect to Elastic Email API or complete the profile', 'elastic-email-subscribe-form') . ' <a href="https://elasticemail.com/account/#/account/profile">' . __('Complete your profile', 'elastic-email-subscribe-form') . '</a>' . __(' or connect to Elastic Email API to start using the plugin.', 'elastic-email-subscribe-form') . '</span>';
                        }
                        echo $accountstatusname;
                        ?>
                    </td>
                </tr>

                <tr class="table-slim" valign="top">
                    <th scope="row"><?php _e('Account daily limit:', 'elastic-email-subscribe-form'); ?></th>
                    <td>
                        <?php
                        if (get_option('eesf-connecting-status') === 'disconnected') {
                            echo '---';
                        } else {
                            if (isset($accountdailysendlimit)) {
                                if ($accountdailysendlimit === 0) {
                                    echo 'Not set';
                                } else {
                                    echo $accountdailysendlimit;
                                }
                            } else {
                                echo '-------';
                            }
                        }

                        ?>
                    </td>
                    <?php
                    if (isset($issub) || isset($requiresemailcredits) || isset($emailcredits)) {
                        if ($emailcredits != 0) {
                            if ($issub == false || $requiresemailcredits == false) {
                                echo '<tr class="table-slim" valign="top"><th scope="row">' . __('Email Credits:', 'elastic-email-subscribe-form') . '</th><td>' . $emailcredits . '</td></tr>';
                            }
                        }
                    }

                    if (get_option('elastic-email-to-send-status') !== NULL) {
                        if (get_option('elastic-email-to-send-status') == 1) {
                            $getaccountabilitytosendemail_single = '<span style="color: #CB2E25;">' . __('Account doesn\'t have enough credits', 'elastic-email-subscribe-form') . '</span>';
                        } elseif (get_option('elastic-email-to-send-status') == 2) {
                            $getaccountabilitytosendemail_single = '<span style="color: #F9C053;">' . __('Account can send e-mails but only without the attachments', 'elastic-email-subscribe-form') . '</span>';
                        } elseif (get_option('elastic-email-to-send-status') == 3) {
                            $getaccountabilitytosendemail_single = '<span style="color: #CB2E25;">' . __('Daily Send Limit Exceeded', 'elastic-email-subscribe-form') . '</span>';
                        } elseif (get_option('elastic-email-to-send-status') == 4) {
                            $getaccountabilitytosendemail_single = '<span style="color: #449D44;">' . __('Account is ready to send e-mails', 'elastic-email-subscribe-form') . '</span>';
                        } else {
                            $getaccountabilitytosendemail_single = '<span style="color: #CB2E25;">' . __('Check the account configuration', 'elastic-email-subscribe-form') . '</span>';
                        }
                    } else {
                        $getaccountabilitytosendemail_single = '---';
                    }
                    ?>
                <tr class="table-slim" valign="top">
                    <th scope="row"><?php _e('Credit status:', 'elastic-email-subscribe-form') ?></th>
                    <td>
                        <?php if (get_option('eesf-connecting-status') === 'disconnected') {
                            echo '---';
                        } else {
                            echo $getaccountabilitytosendemail_single;
                        } ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php submit_button(); ?>
        </form>

        <?php if (empty($error) === false) { ?><?php _e('Do not have an account yet?', 'elastic-email-subscribe-form') ?>
            <a href="https://elasticemail.com/account#/create-account" target="_blank"
               title="First 1000 emails for free."><?php _e('Create your account now', 'elastic-email-subscribe-form') ?></a>!
            <br/>
            <a href="http://elasticemail.com/transactional-email"
               target="_blank"> <?php _e('Tell me more about it', 'elastic-email-subscribe-form') ?></a>
        <?php } ?>
    </div>

    <?php
    include 't-eesf_marketing.php';
    ?>

    </div>
</div>