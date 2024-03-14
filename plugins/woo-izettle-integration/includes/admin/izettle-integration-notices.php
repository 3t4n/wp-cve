<?php
/**
 * This class handles notices to admin
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('IZ_Notice', false)) {

    class IZ_Notice
    {
        public function __construct()
        {
            add_action('admin_notices', array($this, 'check_displaylist'), 100);
        }

        public static function add($message, $type = 'info', $id = false, $dismiss = true, $time = false)
        {

            $id = $id === false ? uniqid('id-') : 'id-' . esc_html($id);

            if ('yes' != get_option('izettle_disable_notices')) {
                if (apply_filters('izettle_admin_message', $message, $type)) {
                    $notices = get_site_transient('izettle_notices');
                    if (!$notices) {
                        $notices = array();
                    }

                    $date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), date('U') + (get_option('gmt_offset') * HOUR_IN_SECONDS));
                    $message = $date . ' - Zettle: ' . $message;

                    $notice = array(
                        'type' => $type,
                        'valid_to' => $time === false ? false : $time,
                        'messsage' => $message,
                        'dismissable' => $dismiss,
                    );
        
                    $notices[$id] = $notice;
        
                    set_site_transient('izettle_notices', $notices);
                }
            }

            return $id;
        }

        public static function clear($id = false)
        {

            $notices = get_site_transient('izettle_notices');
            if ($id) {
                if (isset($notices[$id])) {
                    unset($notices[$id]);
                }
            } else {
                $notices = array();
            }
            set_site_transient('izettle_notices', $notices);
        }

        public static function display($message, $type = 'error', $dismiss = false, $id = '')
        {
            $dismissable = $dismiss ? 'is-dismissible' : '';
            $message = $message;
            echo '<div class="iz_notice ' . $dismissable . ' notice notice-' . $type . ' ' . $id . '"><p>' . $message . '</p></div>';
        }

        public function check_displaylist()
        {
            $notices = get_site_transient('izettle_notices');
            if (false !== $notices && !empty($notices)) {
                foreach ($notices as $key => $notice) {
                    self::display($notice['messsage'], $notice['type'], $notice['dismissable'], $key);
                    if ($notice['valid_to'] !== false && $notice['valid_to'] < time()) {
                        unset($notices[$key]);
                    }
                }
            }

            set_site_transient('izettle_notices', $notices);
        }

    }

    new IZ_Notice();
}
