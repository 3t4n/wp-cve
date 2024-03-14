<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Widget_LoginAttempts implements WADA_Widget_WidgetInterface
{
    const WIDGET_IDENTIFIER = 'wada-widget-login-attempts';

    public function __construct(){
        add_action('wp_dashboard_setup', array($this, 'addIfActiveAndRelevant'));
    }

    public function addIfActiveAndRelevant(){
        $active = WADA_Settings::isLoginAttemptsWidgetEnabled();
        $hasData = $this->hasData();
        if($active && $hasData){
            $userCanManageOptions = current_user_can( 'manage_options' ); // consistent with permissions elsewhere in plugin - only then add dashboard widget
            if($userCanManageOptions) {
                wp_add_dashboard_widget(
                    static::WIDGET_IDENTIFIER,
                    'WP Admin Audit' . ' &#8212; ' . __('Login Attempts', 'wp-admin-audit'),
                    array($this, 'display')
                );
            }
        }
    }

    protected function hasData(){
        $options = array(
            'login_attempts'
        );
        $statsModel = new WADA_Model_Stats($options);
        $stats = $statsModel->_data;
        return ($stats['login_attempts_7d']>0);
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }

        $options = array(
            'login_attempts'
        );
        $statsModel = new WADA_Model_Stats($options);
        $stats = $statsModel->_data;
        $timeFrames = array('7', '30', '90');
        ?><ul><?php
        foreach($timeFrames AS $timeFrame):
            $arrKey = 'login_attempts_'.$timeFrame.'d';
            if($stats[$arrKey] == 1){
                $linkText = __('from one IP address', 'wp-admin-audit');
            }else{
                $linkText = sprintf(__('%d IP addresses', 'wp-admin-audit'), $stats[$arrKey]);
            }
            $failedLoginAttemptsLink = sprintf(
                '<a href="' . admin_url('admin.php?page=wp-admin-audit-logins&timef='.$timeFrame.'d') . '">%s</a>',
                $linkText
            );
            ?>
            <li class="wada-db-stat-line-item"><span class="wada-db-stat-sub-cat wide-cat"><?php echo sprintf(__('within %d days from', 'wp-admin-audit'), $timeFrame); ?></span> <span class="wada-db-stat-sub-cr"><?php echo $failedLoginAttemptsLink; ?></span></li>
        <?php
        endforeach; ?>
        </ul>
        <div class="wada-central-container">
        </div>
        <?php

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }

}