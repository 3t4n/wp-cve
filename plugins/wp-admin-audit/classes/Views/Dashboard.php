<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_View_Dashboard extends WADA_View_BaseForm
{
    const VIEW_IDENTIFIER = 'wada-dashboard';
    protected $_stats;

    public function __construct(){
        $options = array(
            'general_events', 'first_event', 'general_sensors',
            'general_notifications', 'inactive_admins',
            'login_attempts'
        );
        $statsModel = new WADA_Model_Stats($options);
        $this->_stats = $statsModel->_data;
        WADA_Log::debug('Dashboard view, stats: '.print_r($this->_stats, true));
    }

    protected function handleFormSubmissions(){
        if(isset($_POST['submit'])){
            check_admin_referer(self::VIEW_IDENTIFIER);
        }
    }

    protected function displayForm(){
        $pluginSlug = basename(realpath(__DIR__.'/../../'));
        $assetsUrl = trailingslashit( plugins_url($pluginSlug) );
    ?>
        <div class="wrap">
            <h1><?php _e('Dashboard', 'wp-admin-audit'); ?></h1>
            <div id="wada-welcome-panel" class="wada-welcome-panel wada-dashboard">
                <div class="wada-welcome-panel-content">
                    <div class="wada-welcome-panel-header">
                        <h2><?php _e('Welcome!', 'wp-admin-audit'); ?></h2>
                        <p class="about-description"><?php _e('Here is your overview', 'wp-admin-audit'); ?></p>
                        <div class="wada-version">
                            <div class="wada-version-product"><?php echo WADA_Version::getProductName(true, false); ?></div>
                            <div class="wada-welcome-status">
                                <ul>
                                    <li><?php
                                        $updateStatus = WADA_Updater::getUpdateStatus();
                                        WADA_Log::debug('Dashboard updateStatus: '.print_r($updateStatus, true));
                                        $versionCssClass = $updateStatus->updateFound ? 'wada-version-status-update-available' : 'wada-version-status-current';
                                        echo wp_kses_post('<span class="'.$versionCssClass.'">'.$updateStatus->htmlIcon.' '.$updateStatus->updateInfoText.'</span>'); ?>
                                    </li>
                                    <li><?php
                                        /*  */
                                        ?>
                                    </li>
                                    <?php // TODO Show integrity status ?>
                                    <!-- <li>Data integrity</li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="wada-welcome-panel-column-container">
                        <div class="wada-welcome-panel-column">
                            <h3><?php _e('Events', 'wp-admin-audit');
                                $nrEventsStr = ('<span class="wada-db-stat-cr">' . $this->_stats['general_events']->totalEvents . '</span>');
                                $eventsLink = sprintf(
                                    '<a href="'.admin_url('admin.php?page=wp-admin-audit-events').'">%s</a>',
                                    sprintf(__('%s events', 'wp-admin-audit'), $nrEventsStr)
                                );
                            ?></h3>
                            <ul>
                                <li><?php
                                    echo $eventsLink;
                                    if(isset($this->_stats['first_event']) && property_exists($this->_stats['first_event'], 'date_wp')){
                                        echo ' <span class="stat-first-event">('.sprintf(__('since %s', 'wp-admin-audit'), $this->_stats['first_event']->date_wp).')</span>';
                                    }
                                ?></li>
                                <?php foreach($this->_stats['general_events']->bySeverityLevel AS $sevLevel):
                                    if($sevLevel->count == 0) continue; // skip zero entries
                                    $eventsSeverityLink = sprintf(
                                        '<a href="'.admin_url('admin.php?page=wp-admin-audit-events&severity=%d').'">%s</a>',
                                        $sevLevel->severity,
                                        sprintf(__('%s events', 'wp-admin-audit'),  $sevLevel->count)
                                    );
                                    ?>
                                    <li class="wada-db-stat-line-item"><span class="wada-db-stat-sub-cat"><?php echo esc_html($sevLevel->name); ?></span> <span class="wada-db-stat-cr wada-db-stat-sub-cr"><?php echo $eventsSeverityLink; ?></span></li>
                                <?php endforeach; ?>
                            </ul>
                            <h3><?php _e('Sensors', 'wp-admin-audit');
                                $sensorsLink = sprintf(
                                    '<a href="'.admin_url('admin.php?page=wp-admin-audit-settings&tab=tab-sensors').'">%s</a>',
                                    sprintf(__('%d total', 'wp-admin-audit'), $this->_stats['general_sensors']->totalSensors)
                                );
                                ?></h3>
                            <ul>
                                <li class="wada-db-stat-line-item"><?php echo $sensorsLink.' ('. sprintf(__('%d active', 'wp-admin-audit'), $this->_stats['general_sensors']->bySensorStatus->active).')'; ?></li>
                            </ul>
                            <?php if(WADA_Version::getFtSetting(WADA_Version::FT_ID_NOTI)): ?>
                            <h3><?php _e('Notifications', 'wp-admin-audit');
                                $notificationsLink = sprintf(
                                    '<a href="'.admin_url('admin.php?page=wp-admin-audit-notifications').'">%s</a>',
                                    sprintf(__('%d notifications setup', 'wp-admin-audit'), $this->_stats['general_notifications']->totalNotifications)
                                );
                                $logLink = sprintf(
                                    '<a href="'.admin_url('admin.php?page=wp-admin-audit-notifications&subpage=log').'">%s</a>',
                                    sprintf(__('%d event notifications', 'wp-admin-audit'), $this->_stats['general_notifications']->eventNotificationCr)
                                );
                                $queueLink = sprintf(
                                    '<a href="'.admin_url('admin.php?page=wp-admin-audit-notifications&subpage=queue').'">%s</a>',
                                    sprintf(__('%d queue entries', 'wp-admin-audit'), $this->_stats['general_notifications']->queueCr)
                                );
                            ?></h3>
                                <ul>
                                    <li class="wada-db-stat-line-item"><?php echo $notificationsLink. '  ('. sprintf(__('%d active', 'wp-admin-audit'), $this->_stats['general_notifications']->byNotificationStatus->active).')'; ?></li>
                                    <li class="wada-db-stat-line-item"><?php echo $logLink; ?></li>
                                    <li class="wada-db-stat-line-item"><?php echo $queueLink; ?></li>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="wada-welcome-panel-column">
                            <div><?php
                                if(count($this->_stats['inactive_admins'])): ?>
                                <h3><?php _e('Inactive administrator accounts', 'wp-admin-audit'); ?></h3>
                                <ul><?php
                                    foreach($this->_stats['inactive_admins'] AS $inactiveAdminObj):
                                        $linkText = ($inactiveAdminObj->nr_inactive == 1) ? __('one account', 'wp-admin-audit') : sprintf(__('%d accounts', 'wp-admin-audit'), $inactiveAdminObj->nr_inactive);
                                        $inactiveAdminsLink = sprintf(
                                            '<a href="' . admin_url('admin.php?page=wp-admin-audit-users&role=administrator&ainactive=inactive&timef=%dd') . '">%s</a>',
                                            $inactiveAdminObj->days,
                                            $linkText
                                        );
                                        ?>
                                        <li class="wada-db-stat-line-item"><span class="wada-db-stat-sub-cat wide-cat"><?php echo sprintf(__('since %d days', 'wp-admin-audit'), $inactiveAdminObj->days); ?></span> <span class="wada-db-stat-sub-cr"><?php echo $inactiveAdminsLink; ?></span></li>
                                    <?php
                                    endforeach; ?>
                                    </ul><?php
                                    endif;
                            ?></div>
                            <div><?php
                                if($this->_stats['login_attempts_7d']>0):
                                    $timeFrames = array('7', '30', '90');
                                    ?>
                                    <h3><?php _e('Login Attempts', 'wp-admin-audit'); ?></h3>
                                    <ul><?php
                                    foreach($timeFrames AS $timeFrame):
                                        $arrKey = 'login_attempts_'.$timeFrame.'d';
                                        if($this->_stats[$arrKey] == 1){
                                            $linkText = __('from one IP address', 'wp-admin-audit');
                                        }else{
                                            $linkText = sprintf(__('%d IP addresses', 'wp-admin-audit'), $this->_stats[$arrKey]);
                                        }
                                        $failedLoginAttemptsLink = sprintf(
                                            '<a href="' . admin_url('admin.php?page=wp-admin-audit-logins&timef='.$timeFrame.'d') . '">%s</a>',
                                            $linkText
                                        );
                                    ?>
                                        <li class="wada-db-stat-line-item"><span class="wada-db-stat-sub-cat wide-cat"><?php echo sprintf(__('within %d days from', 'wp-admin-audit'), $timeFrame); ?></span> <span class="wada-db-stat-sub-cr"><?php echo $failedLoginAttemptsLink; ?></span></li>
                                    <?php
                                    endforeach; ?>
                                    </ul><?php
                                endif;
                                ?></div>
                        </div>
                        <div class="wada-welcome-panel-column wada-welcome-panel-last">
                            <img src="<?php echo ($assetsUrl.'assets/img/logo_small.png'); ?>" alt="<?php _e( 'Logo', 'wp-admin-audit' ); ?>" id="wada-main-logo"/>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}