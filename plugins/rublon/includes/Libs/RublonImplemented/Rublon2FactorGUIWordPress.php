<?php

namespace Rublon_WordPress\Libs\RublonImplemented;

use RublonHelper;

class Rublon2FactorGUIWordPress
{

    public static $instance;

    public static function getInstance()
    {

        if (empty(self::$instance)) {
            $additional_settings = RublonHelper::getSettings('additional');
            $current_user = wp_get_current_user();
            self::$instance = new self(
                RublonHelper::getRublon(),
                RublonHelper::getUserId($current_user),
                RublonHelper::getUserEmail($current_user),
                $logout_listener = RublonHelper::isLogoutListenerEnabled()
            );

            // Embed consumer script
            if (RublonHelper::isSiteRegistered() && !RublonHelper::isNewVersion()) {
                add_action('wp_footer', array(self::$instance, 'renderConsumerScript'), PHP_INT_MAX);
                add_action('admin_footer', array(self::$instance, 'renderConsumerScript'), PHP_INT_MAX);
            }
        }

        return self::$instance;
    }


    /**
     * Returns Rublon Button for plugin's registration.
     *
     * Since the registration is now handled automatically,
     * the button is not necessary.
     *
     * @return RublonButton
     */
    protected function createActivationButton($activationURL)
    {
        return '';
    }


    /**
     * Create Trusted Devices Widget container for WP Dashboard
     *
     * @return string
     */
    public function getACMWidget()
    {
        return $this->getShareAccessWidget();
    }


    public function renderConsumerScript()
    {

        wp_enqueue_script('jquery');

    }

    public function getBadgeWidget()
    {
        return '';//new RublonBadge();
    }

    public function userBox()
    {

        return '';

    }


}
