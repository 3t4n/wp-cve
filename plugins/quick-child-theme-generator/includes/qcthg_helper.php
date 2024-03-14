<?php
if(!class_exists('QCTHG_Helper'))
{
    class QCTHG_Helper
    {
        /**
         * Get the list of all parent themes
         * @return [array]
         */
        public static function qcthgGetParntThemesList()
        {
            $getAllTheme = wp_get_themes();

            $parentThemes = array();
            foreach ($getAllTheme as $themeName => $theme) {
                if($theme->parent() === false) {
                    array_push($parentThemes, $theme);
                }
            }
            return $parentThemes;
        }

        /**
         * Get the list of all child themes
         * @return [array]
         */
        public static function qcthgGetChildThemesList()
        {
            $getAllTheme = wp_get_themes();

            $childThemes = array();
            foreach ($getAllTheme as $themeName => $theme) {
                if($theme->parent() !== false) {
                    array_push($childThemes, $theme);
                }
            }
            return $childThemes;
        }

        /**
         * Get the list of all themes (parent and child) name
         * @return [array]
         */
        public static function qcthgGetAllThemesName()
        {
            $getAllTheme = wp_get_themes();

            $themes = array();
            foreach ($getAllTheme as $themeName => $theme) {
                array_push($themes, $theme['Name']);
            }
            return $themes;
        }

        /**
         * Check theme (parent and child) name aleardy exists or not
         * @param  $themeTitle
         * @return [boolean]
         */
        public static function qcthgChkThemeName($themeTitle)
        {
            if(empty($themeTitle)) { wp_die('Error in fetching function param.'); }

            $themes = QCTHG_Helper::qcthgGetAllThemesName();

            if(is_array($themes)) {
                foreach ($themes as $themeName) {
                    if(strtolower($themeTitle) === strtolower($themeName)) {
                        return true;
                    }
                }
            }
            return false;
        }

        /**
         * Get the sanitized text
         * @param  $text
         * @return [string]
         */
        public static function qcthgSanitizeText($text)
        {
            if(empty($text)) { return; }
            $trimedText = trim($text);
            $sanitizedText = str_replace(str_split('\\/:*?"<>|`~!@#$%^&()+=[]{};,.\''), '', $trimedText);
            return $sanitizedText;
        }

        /**
         * Get the refined sanitized text
         * @param  $text
         * @return [string]
         */
        public static function qcthgRefSanitizeText($text)
        {
            if(empty($text)) { return; }
            $trimedText = trim($text);
            $refinedSanitizedText = str_replace(str_split('-_ '), '', $text);
            return $refinedSanitizedText;
        }

        /**
         * Set cookies for 60 seconds
         * @param  $cookieName
         * @param  $cookieValue
         */
        public static function qcthgSetCookies($cookieName, $cookieValue)
        {
            if(empty($cookieName)) {
                $cookieName = "qcthg_adminNotices";
            }
            setcookie($cookieName, $cookieValue, time() + (60), "/");
        }

        /**
         * Display dynamic error notices (destroy current cookies)
         */
        public static function adminNoticeError() {
            ?>
            <div class="notice notice-error is-dismissible qcthgErrNotice">
                <p><?php _e(str_replace('\\\\', '\\', $_COOKIE['qcthg_adminNotices']), 'quck-child-theme-generator'); ?></p>
            </div>
            <?php
            unset($_COOKIE['qcthg_adminNotices']);
            setcookie('qcthg_adminNotices', 0, time() - 60, '/');
        }

        /**
         * Display dynamic success notices (destroy current cookies)
         */
        public static function adminNoticeSuccess() {
            ?>
            <div class="notice notice-success is-dismissible qcthgErrNotice">
                <p><?php _e(str_replace('\\\\', '\\', $_COOKIE['qcthg_adminNoticesSuccess']), 'quck-child-theme-generator'); ?></p>
            </div>
            <?php
            unset($_COOKIE['qcthg_adminNoticesSuccess']);
            setcookie('qcthg_adminNoticesSuccess', 0, time() - 60, '/');
        }

        /**
         * Added plugin log
         * @param  $body
         */
        public static function qcthgLogCall($body)
        {
            $endpoint = 'http://api.sharmajay.com/info/';
			if($ssl = wp_http_supports(array('ssl'))) {
                $endpoint = set_url_scheme($endpoint, 'https');
            }

            wp_remote_post($endpoint, array(
                    'method'      => 'POST',
                    'timeout'     => 20,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'sslverify'   => true,
                    'user-agent'  => 'QCTHG',
                    'body'        => $body
                )
            );
        }

        /**
         * Check template name exist in theme root directory or not
         * @param  $templateName
         * @return [boolean]
         */
        public static function qcthgChkTemplateName($templateName)
        {
            $res = locate_template('template-'.$templateName.'.php');

            if($res == false) {
                return false;
            }
            return true;
        }

        /**
         * find the files in specfic path
         * @param  $fileNm
         * @param  $scanPath
         * @return [boolean]
         */
        public static function findHeaderFooter($fileNm, $scanPath)
        {
            $find = false;
            if(in_array($fileNm, $scanPath)) {
                $find = true;
            }
            return $find;
        }

    }
}
