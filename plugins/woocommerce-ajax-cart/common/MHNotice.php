<?php
if ( !class_exists('MHNotice') ) {
    class MHNotice {
        /**
         * @var MHCommon
         */
        private $common;

        /**
         * @var array
         */
        private $tempNotices = array();

        public function __construct($common) {
            $this->common = $common;

            $pluginAlias = $common->getPluginAlias();

            add_action('admin_notices', array($this, 'checkNotices'));

            if ( !empty($_GET['MHCommonDismiss']) && !empty($_GET['alias']) && ( $_GET['MHCommonDismiss'] == $pluginAlias ) ) {
                $this->dismissNotice( sanitize_text_field($_GET['alias']) );
                wp_safe_redirect( esc_url_raw( admin_url('plugins.php') ) );
            }
        }

        public function checkNotices(){
            global $pagenow;

            if ( $pagenow != 'plugins.php' ) {
                return;
            }

            $notices = $this->readNotices();
            $pluginAlias = $this->common->getPluginAlias();
            $isPremium = $this->common->isPremiumVersion();

            foreach ( $notices as $alias => $notice ) {
                $trAlias = $this->transactionAlias($alias);
                $dismissDays = !empty($notice['dismissDays']) ? $notice['dismissDays'] : null;

                // ignore dismissed notices
                if ( !empty(get_transient($trAlias)) ) {
                    continue;
                }
                else if ( $dismissDays == 0 && !empty(get_option($trAlias)) ) {
                    continue;
                }
                
                // custom hook to prevent display notices
                if ( !apply_filters($this->common->getPluginAbbrev() . '_' . $alias, true) ) {
                    continue;
                }

                // ignore undesired notices
                if ( $isPremium && ( $alias == MHCommon::PREMIUM_ADV_NOTICE ) ) {
                    continue;
                }

                if ( !$isPremium && ( $alias == MHCommon::LICENSE_CODE_EMPTY_NOTICE ) ) {
                    continue;
                }

                $dismissUrl = admin_url('plugins.php?MHCommonDismiss=' . $pluginAlias . '&alias=' . $alias);
                if ( $dismissDays > 0 ) {
                    $dismissLink = sprintf('<a href="%s">Dismiss for %d days</a>', $dismissUrl, $dismissDays);
                }
                else if ( $dismissDays == 0 ) {
                    $dismissLink = sprintf('<a href="%s">Dismiss this notice</a>', $dismissUrl);
                }
                else {
                    $dismissLink = '';
                }
                
                $pluginTitle = esc_html__($this->common->getPluginTitle());
                $type = !empty($notice['type']) ? esc_html__($notice['type']) : '';
                $message = !empty($notice['message']) ? $notice['message'] : '';

                if ( empty($message) ) {
                    continue;
                }

                echo '<div class="notice notice-'.$type.'">
                        <strong>'.$pluginTitle.'</strong>
                        <p>
                            '.$message.'
                        </p>
                        <p style="text-align: right; margin-top: -10px;">
                            '.$dismissLink.'
                        </p>
                    </div>';
            }
        }

        public function addNotice($alias, $type, $message, $dismissDays) {

            $notices = $this->readNotices();
            $notices[$alias] = array(
                'message' => $message,
                'type' => $type,
                'dismissDays' => $dismissDays,
            );

            $this->storeNotices($notices);
        }

        public function removeNotice($alias) {
            $notices = $this->readNotices();
            
            if ( !empty($notices[$alias]) ) {
                unset($notices[$alias]);
                $this->storeNotices($notices);
            }

            delete_transient($this->transactionAlias($alias));
        }

        public function addTempNotice($type, $message) {
            $hash = md5($type.$message);
            $this->tempNotices[$hash] = array(
                'message' => $message,
                'type' => $type,
                'dismissDays' => -1,
            );
        }


        private function dismissNotice($alias) {
            $notices = $this->readNotices();

            if ( !empty($notices[$alias])) {
                $dismissDays = $notices[$alias]['dismissDays'];
                $trAlias = $this->transactionAlias($alias);

                if ( $dismissDays > 0 ) {
                    $expiration = ( $dismissDays * DAY_IN_SECONDS );
                    set_transient($trAlias, 'x', $expiration);
                }
                else {
                    update_option($trAlias, 'y');
                }
            }
        }

        private function transactionAlias($alias) {
            $pluginAbbrev = $this->common->getPluginAbbrev();
            return $pluginAbbrev . '_notice_' . $alias;
        }

        private function storeNotices($notices) {
            $pluginAbbrev = $this->common->getPluginAbbrev();
            return update_option($pluginAbbrev . '_notices', $notices);
        }

        private function readNotices() {
            $pluginAbbrev = $this->common->getPluginAbbrev();

            $notices = (array) get_option($pluginAbbrev . '_notices', array());
            $notices += (array) $this->tempNotices;

            return $notices;
        }
    }
}
