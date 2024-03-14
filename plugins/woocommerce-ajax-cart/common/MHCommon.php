<?php
if ( !class_exists('MHCommon') ) {
    class MHCommon {
        const PREMIUM_ADV_NOTICE = 'premium_advertising';
        const LICENSE_CODE_EMPTY_NOTICE = 'license_empty';

        private $pluginAbbrev;
        private $pluginAlias;
        private $pluginTitle;
        private $pluginBaseFile;
        private $noticeObj;
        private $settingsObj;
        private static $instance;

        private function __construct($pluginAlias,
                                     $pluginTitle,
                                     $pluginBaseFile,
                                     $pluginAbbrev) {

            $this->pluginAlias = $pluginAlias;
            $this->pluginTitle = $pluginTitle;
            $this->pluginBaseFile = $pluginBaseFile;
            $this->pluginAbbrev = $pluginAbbrev;
        }

        public function getPluginAlias() {
            return $this->pluginAlias;
        }

        public function getPluginAbbrev() {
            return $this->pluginAbbrev;
        }

        public function getPluginBaseFile() {
            return $this->pluginBaseFile;
        }

        public function getPluginTitle() {
            if ( $this->isPremiumVersion() ) {
                return $this->pluginTitle . ' PRO';
            }

            return $this->pluginTitle;
        }

        public function isPremiumVersion() {
            return apply_filters("mh_{$this->pluginAbbrev}_is_premium", false);
        }

        /**
         * @return MHNotice
         */
        public function getNotice() {
            if ( empty($this->noticeObj) ) {
                include_once( dirname(__FILE__) . '/MHNotice.php' );
                $this->noticeObj = new MHNotice($this);
            }

            return $this->noticeObj;
        }

        /**
         * @return MHCommon
         */
        public static function getInstance() {
            return self::$instance;
        }

        /**
         * @return MHSettings
         */
        public static function getSettings() {
            return self::getInstance()->settingsObj;
        }

        /**
         * Initialize all hooks and filters calls for settings, upgrader and support
         * @version 2
         */
        public static function initializeV2($pluginAlias,
                                            $pluginAbbrev,
                                            $pluginBaseFile,
                                            $pluginTitle) {

            $common = self::$instance = new self($pluginAlias, $pluginTitle, $pluginBaseFile, $pluginAbbrev);

            include_once( dirname(__FILE__) . '/MHSettings.php' );
            $settings = $common->settingsObj = new MHSettings($pluginAlias, $pluginAbbrev, $pluginTitle, $pluginBaseFile, $common);

            $noticeObject = $common->getNotice();

            if ( $common->isPremiumVersion() ) {
                if ( file_exists(dirname(__FILE__) . '/MHSupport.php') ) {
                    include_once( dirname(__FILE__) . '/MHSupport.php' );
                    new MHSupport($pluginAlias, $pluginTitle, $pluginBaseFile, $pluginAbbrev);
    
                    include_once( dirname(__FILE__) . '/MHUpgrader.php' );
                    new MHUpgrader($pluginAlias, $pluginBaseFile, $pluginTitle, $pluginAbbrev, $noticeObject);
                }
            }
            else if ( $settings->has_premium_features() ) {
                $text = $settings->get_premium_notice();
                $noticeObject->addNotice(self::PREMIUM_ADV_NOTICE, 'success', $text, 0);
            }
        }
    }
}
