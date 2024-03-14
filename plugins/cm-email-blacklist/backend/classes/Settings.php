<?php

class CMEB_Settings {

    const TYPE_BOOL = 'bool';
    const TYPE_INT = 'int';
    const TYPE_STRING = 'string';
    const TYPE_COLOR = 'color';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_RADIO = 'radio';
    const TYPE_SELECT = 'select';
    const TYPE_MULTISELECT = 'multiselect';
    const TYPE_CSV_LINE = 'csv_line';
    const TYPE_FILEUPLOAD = 'fileupload';
    /////

    const OPTION_WHITE_LIST = 'cmeb_whit_list';
    const OPTION_FREE_DOMAINS = 'cmeb_free_domains';
    const OPTION_LOGIN_ERROR = 'cmeb_login_error';
    const OPTION_BECAUSE_WHITE = 'cmeb_because_white';
    const OPTION_BECAUSE_NONE = 'cmeb_because_none';
    const OPTION_BECAUSE_FREE_DOMAIN = 'cmeb_because_free_domain';

    const OPTION_EMAIL_BLACK_LIST	 = 'cmeb_email_white_list';
    const OPTION_EMAIL_BECAUSE_BLACK = 'cmeb_email_because_black';

    /*
     * OPTIONS - END
     */
    const ACCESS_EVERYONE = 0;
    const ACCESS_USERS = 1;
    const ACCESS_ROLE = 2;
    const EDIT_MODE_DISALLOWED = 0;
    const EDIT_MODE_WITHIN_HOUR = 1;
    const EDIT_MODE_WITHIN_DAY = 2;
    const EDIT_MODE_ANYTIME = 3;

    public static $categories = array(
        'cmeb_general' => 'General',
        'cmeb_lebels' => 'Lebels'
    );
    public static $subcategories = array(
        'cmeb_general' => array(
            'cmeb_general' => 'General Options',
        ),
        'cmeb_labels' => array(
            'cmeb_labels' => 'For Login Panel',
        )
    );

    public static function getOptionsConfig() {
        return apply_filters('cmeb_options_config', array(
        self::OPTION_WHITE_LIST => array(
        'type' => self::TYPE_BOOL,
        'default' => true,
        'category' => 'cmeb_general',
        'subcategory' => 'cmeb_general',
        'title' => 'Domain Whitelist',
        'desc' => 'When enabled, domains on the whitelist will NOT be blocked. Emails coming from other domains will be rejected.'
        ),
        self::OPTION_FREE_DOMAINS => array(
        'type' => self::TYPE_BOOL,
        'default' => true,
        'category' => 'cmeb_general',
        'subcategory' => 'cmeb_general',
        'title' => 'Free Domains list',
        'desc' => 'When enabled, domains on the Free Domains list will be blocked. It is a list containing all the domains that provide free email. This list also includes gmail.com and other popular services.'
        ),
        self::OPTION_EMAIL_BLACK_LIST => array(
        'type'			=> self::TYPE_BOOL,
        'default'		=> false,
        'category'		=> 'cmeb_general',
        'subcategory'	=> 'cmeb_general',
        'title'			=> 'User Email Blacklist',
        'desc'			=> 'When enabled, the emails on the blacklist will be marked as invalid. Email validation is checked before domain validation.',
        ),

        //login panel
        self::OPTION_LOGIN_ERROR => array(
        'type' => self::TYPE_STRING,
        'default' => 'Register error:',
        'category' => 'cmeb_labels',
        'subcategory' => 'cmeb_labels',
        'title' => 'Register error',
        'desc' => 'Label for \'Register error:\' message',
        ),
        self::OPTION_BECAUSE_WHITE => array(
        'type' => self::TYPE_STRING,
        'default' => 'Domain is the blacklisted',
        'category' => 'cmeb_labels',
        'subcategory' => 'cmeb_labels',
        'title' => 'Domain is the blacklisted',
        'desc' => 'Message text for \'Domain is the blacklisted\'',
        ),
		/*
        self::OPTION_BECAUSE_FREE_DOMAIN => array(
        'type' => self::TYPE_STRING,
        'default' => 'Domain is in the Free domains list',
        'category' => 'cmeb_labels',
        'subcategory' => 'cmeb_labels',
        'title' => 'Domain is in the Free domains list',
        'desc' => 'Label for \'Domain is in the Free domains list\' message',
        ),
		self::OPTION_BECAUSE_NONE => array(
        'type' => self::TYPE_STRING,
        'default' => 'Domain is neither blacklisted nor whitelisted',
        'category' => 'cmeb_labels',
        'subcategory' => 'cmeb_labels',
        'title' => 'Not Blacklisted, not Whiteliested',
        'desc' => 'Label for \'Domain is neither blacklisted nor whitelisted\' message (only at backend when testing domain)',
        ),
		*/
        self::OPTION_EMAIL_BECAUSE_BLACK			 => array(
        'type'			 => self::TYPE_STRING,
        'default'		 => 'Email is the blacklisted',
        'category'		 => 'cmeb_labels',
        'subcategory'	 => 'cmeb_labels',
        'title'			 => 'Email is the blacklisted',
        'desc'			 => 'Message text for \'Email is the blacklisted\'',
        ),

        ));
    }

    public static function getOptionsConfigByCategory($category, $subcategory = null) {
        $options = self::getOptionsConfig();
        return array_filter($options, function($val) use ($category, $subcategory) {
            if ($val['category'] == $category) {
                return (is_null($subcategory) OR $val['subcategory'] == $subcategory);
            }
        });
    }

    public static function getOptionConfig($name) {
        $options = self::getOptionsConfig();
        if (isset($options[$name])) {
            return $options[$name];
        }
    }

    public static function setOption($name, $value) {
        $options = self::getOptionsConfig();
        if (isset($options[$name])) {
            $field = $options[$name];
            $old = get_option($name);
            if (is_array($old) OR is_object($old) OR strlen((string) $old) > 0) {
                update_option($name, self::cast($value, $field['type']));
            } else {
                $result = update_option($name, self::cast($value, $field['type']));
            }
        }
    }

    public static function deleteAllOptions() {
        $params = array();
        $options = self::getOptionsConfig();
        foreach ($options as $name => $optionConfig) {
            self::deleteOption($name);
        }

        return $params;
    }

    public static function deleteOption($name) {
        $options = self::getOptionsConfig();
        if (isset($options[$name])) {
            delete_option($name);
        }
    }

    public static function getOption($name) {
        $options = self::getOptionsConfig();
        if (isset($options[$name])) {
            $field = $options[$name];
            $defaultValue = (isset($field['default']) ? $field['default'] : null);
            return self::cast(get_option($name, $defaultValue), $field['type']);
        }
    }

    public static function getCategories() {
        $categories = array();
        $options = self::getOptionsConfig();
        foreach ($options as $option) {
            $categories[] = $option['category'];
        }
        return $categories;
    }

    public static function getSubcategories($category) {
        $subcategories = array();
        $options = self::getOptionsConfig();
        foreach ($options as $option) {
            if ($option['category'] == $category) {
                $subcategories[] = $option['subcategory'];
            }
        }
        return $subcategories;
    }

    protected static function boolval($val) {
        return (boolean) $val;
    }

    protected static function arrayval($val) {
        if (is_array($val))
            return $val;
        else if (is_object($val))
            return (array) $val;
        else
            return array();
    }

    protected static function cast($val, $type) {
        if ($type == self::TYPE_BOOL) {
            return (intval($val) ? 1 : 0);
        } else {
            $castFunction = $type . 'val';
            if (function_exists($castFunction)) {
                return call_user_func($castFunction, $val);
            } else if (method_exists(__CLASS__, $castFunction)) {
                return call_user_func(array(__CLASS__, $castFunction), $val);
            } else {
                return $val;
            }
        }
    }

    protected static function csv_lineval($value) {
        if (!is_array($value))
            $value = explode(',', $value);
        return $value;
    }

    public static function processPostRequest() {
        $params = array();
        $options = self::getOptionsConfig();
        foreach ($options as $name => $optionConfig) {
            if (isset($_POST[$name])) {
                $params[$name] = sanitize_text_field($_POST[$name]);
                self::setOption($name, sanitize_text_field($_POST[$name]));
            }
        }

        return $params;
    }

    public static function userId($userId = null) {
        if (empty($userId))
            $userId = get_current_user_id();
        return $userId;
    }

    public static function isLoggedIn($userId = null) {
        $userId = self::userId($userId);
        return !empty($userId);
    }

    public static function getRolesOptions() {
        global $wp_roles;
        $result = array();
        if (!empty($wp_roles) AND is_array($wp_roles->roles))
            foreach ($wp_roles->roles as $name => $role) {
                $result[$name] = $role['name'];
            }
        return $result;
    }

    public static function canReportSpam($userId = null) {
        return (self::getOption(self::OPTION_SPAM_REPORTING_ENABLED) AND ( self::getOption(self::OPTION_SPAM_REPORTING_GUESTS) OR self::isLoggedIn($userId)));
    }

    public static function getPagesOptions() {
        $pages = get_pages(array('number' => 100));
        $result = array(null => '--');
        foreach ($pages as $page) {
            $result[$page->ID] = $page->post_title;
        }
        return $result;
    }

    public static function areAttachmentsAllowed() {
        $ext = self::getOption(self::OPTION_ATTACHMENTS_FILE_EXTENSIONS);
        return (!empty($ext) AND ( self::getOption(self::OPTION_ATTACHMENTS_ANSWERS_ALLOW) OR self::getOption(self::OPTION_ATTACHMENTS_QUESTIONS_ALLOW)));
    }

    public static function getLoginPageURL($returnURL = null) {
        if (empty($returnURL)) {
            $returnURL = get_permalink();
        }
        if ($customURL = CMEB_Settings::getOption(CMEB_Settings::OPTION_LOGIN_PAGE_LINK_URL)) {
            return esc_url(add_query_arg(array('redirect_to' => urlencode($returnURL)), $customURL));
        } else {
            return wp_login_url($returnURL);
        }
    }

}
