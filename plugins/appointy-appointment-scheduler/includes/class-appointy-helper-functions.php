<?php


class Appointy_helper_functions
{
    public $appointy_installed, $appointy_calendar_privileges, $iFrameVal, $poweredby;

    public function __construct()
    {
        $this->appointy_installed = true;
        $this->appointy_calendar_privileges = 0;
        $this->iFrameVal = "https://booking.appointy.com/demo?lang=en-Us&maxWidth=&maxHeight=&widget=booking-page";
        $this->demoUrl = "https://booking.appointy.com/demo?lang=en-Us&maxWidth=&maxHeight=&widget=booking-page";
        $this->poweredby = "<div style='font-size:11px; font-family:Arial, Helvetica, sans-serif;'>Powered by <a href='https://www.appointy.com/?isGadget=2&utm_source=wordpress_plugin_embed_link&utm_medium=link_poweredby&utm_campaign=wordpress_plugin_embed_link' target = '_Blank' alt='Online Appointment Scheduling Software'>Appointy - Online Appointment Scheduling Software</a></div>";
    }

    function get_language_code_array()
    {
        $language['default'] = 'default';
        $language['bulgarian'] = 'bg-BG'; //
        $language['chinese'] = 'zh-CN';
        $language['chinese_(Traditional)'] = 'zh-Hant';
        $language['croatian'] = 'hr';
        $language['czech_(Republic)'] = 'cs';
        $language['danish'] = 'da-DK';
        $language['dutch'] = 'nl-NL';
        $language['english_(US)'] = 'en-US';
        $language['english_(UK)'] = 'en-GB';
        $language['english_(Australia)'] = 'en-AU';
        $language['estonian'] = 'et-EE';
        $language['french'] = 'fr-FR';
        $language['finnish'] = 'fi'; //
        $language['german'] = 'de-DE'; //
        $language['greek'] = 'el-GR';
        $language['hungarian'] = 'hu-HU';
        $language['italian'] = 'it-IT'; //
        $language['japanese'] = 'ja';
        $language['lithuanian'] = 'lt-LT';
        $language['latvian'] = 'lv-LV';
        $language['nynorsk'] = 'no'; //*
        $language['portuguese'] = 'pt'; //
        $language['portuguese_(Brazil)'] = 'pt-BR';
        $language['polish'] = 'pl-PL'; //
        $language['russian'] = 'ru-RU'; //
        $language['romanian'] = 'ro-RO'; //
        $language['spanish'] = 'es'; //
        $language['slovenian'] = 'sl-SI';
        $language['serbian_(Cyrilic)'] = 'sr-Cyrl-BA';
        $language['serbian_(Latin)'] = 'sr';
        $language['slovak'] = 'sk'; //
        $language['swedish'] = 'sv-SE';
        $language['turkish'] = 'tr-TR';

        return $language;
    }

    public function get_language_code($language)
    {
        $languageCode = $this->get_language_code_array();

        $str = '/ChangeLanguage.aspx?lan=';
        // Return default if no key is exist
        $lanValue = isset($languageCode[$language]) ? $languageCode[$language] : 'default';
        $lanValue = $str . $lanValue;
        return $lanValue;
    }

    function get_language_value_array()
    {
        $language['default'] = 'default';
        $language['bg-BG'] = 'bulgarian'; //
        $language['zh-CN'] = 'chinese';
        $language['zh-Hant'] = 'chinese_(Traditional)';
        $language['hr'] = 'croatian';
        $language['cs'] = 'czech_(Republic)';
        $language['da-DK'] = 'danish';
        $language['nl-NL'] = 'dutch';
        $language['en-US'] = 'english_(US)';
        $language['en-GB'] = 'english_(UK)';
        $language['en-AU'] = 'english_(Australia)';
        $language['et-EE'] = 'estonian';
        $language['fr-FR'] = 'french';
        $language['fi'] = 'finnish'; //
        $language['de-DE'] = 'german'; //
        $language['el-GR'] = 'greek';
        $language['hu-HU'] = 'hungarian';
        $language['it-IT'] = 'italian'; //
        $language['ja'] = 'japanese';
        $language['lt-LT'] = 'lithuanian';
        $language['lv-LV'] = 'latvian';
        $language['no'] = 'nynorsk'; //*
        $language['pt'] = 'portuguese'; //
        $language['pt-BR'] = 'portuguese_(Brazil)';
        $language['pl-PL'] = 'polish'; //
        $language['ru-RU'] = 'russian'; //
        $language['ro-RO'] = 'romanian'; //
        $language['es'] = 'spanish'; //
        $language['sl-SI'] = 'slovenian';
        $language['sr-Cyrl-BA'] = 'serbian_(Cyrilic)';
        $language['sr'] = 'serbian_(Latin)';
        $language['sk'] = 'slovak'; //
        $language['sv-SE'] = 'swedish';
        $language['tr-TR'] = 'turkish';

        return $language;
    }

    public function get_language_value($lan)
    {
        $language = $this->get_language_value_array();
        // Return default if no key is exist
        $lanValue = isset($language[$lan]) ? $language[$lan] : 'default';
        return $lanValue;

    }

    public function get_language_code_from_fpc($code)
    {
        preg_match("/.appointy.com\/ChangeLanguage\.aspx\?lan\=(.*)\&isGadget=1/", $code, $output_array);
        $lanValue = '';
        if (count($output_array) > 1) {
            $lanValue = $output_array[1];
        }
        return $lanValue;
    }

    public function change_fpac_language($language, $code)
    {
        $newCode = $code;
        $languageCode = $this->get_language_code($language);
        if (preg_match('(/ChangeLanguage.aspx)', $code))//.appointy.com\/\?isGadget=1
        {
            $code = preg_replace("/.appointy.com(.*)\&isGadget=1/", ".appointy.com/?isGadget=1", $code);
            $newCode = $code;
        }
        // if language is set to default then do nothing
        // because code is already reset in previous step
        if ($language != "default") {
            $codestr = preg_split('(\.appointy\.com\/\?)', $code);
            $newCode = $codestr[0] . '\.appointy\.com' . $languageCode . '\&' . $codestr[1];
        }
        return $newCode;
    }

    public function create_language_selection($selLang)
    {
        $language = $this->get_language_value_array();
        $str = '';
        foreach ($language as $key => $value) {
            # code...
            $str .= "<option value='" . $key . "'" . ($selLang == $key ? "selected" : "") . ">" . ucfirst(str_replace('_', ' ', $value)) . '</option>';
        }
        return $str;
    }

    public function validate_language($language_selected)
    {

        $language_code = $this->get_language_value_array();
        if (isset($language_code[$language_selected])) {
            return true;
        } else {
            return false;
        }
    }

    public function validate_appointy_calendar_code($code)
    {

        if (wp_http_validate_url($code)) {
            $type1 = preg_match("/.appointy.com\/ChangeLanguage\.aspx\?lan\=(.*)\&isGadget=1/", $code);
            $type2 = preg_match("/.appointy.com\/\?isGadget=1/", $code);
            if ($type1 == 0 && $type2 == 0) { // if does not matches any valid code type
                return false;
            }
            return true;
        }

        return false;
    }

    public function appointy_calendar_code($code)
    {
        if (strpos($code, "<iframe") === FALSE)
            return false;
        else
            return true;
    }

    public function appointy_get_admin_url()
    {
        $adminURL = preg_match("/https?:\/\/(.*).com/", $this->iFrameVal, $matches);
        if ($adminURL = true) {
            $adminURL = htmlentities($matches['0']);
            $adminURL = $adminURL . '/admin';
        }
        return $adminURL;
    }

    public function appointy_get_demo_url()
    {
        return $this->demoUrl;
    }

    public function appointy_calendar_installed()
    {
        global $wpdb;

        $install = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $wpdb->prefix . "appointy_calendar"));
        if ($install === NULL)
            return false;
        else
            return true;
    }

    // creates appointy calendar table in to database
    // contains code for calender : iframe link
    public function appointy_calendar_install()
    {
        global $wpdb;

        $query = $wpdb->prepare("CREATE TABLE " . $wpdb->prefix . "appointy_calendar (
			                            calendar_id INT(11) NOT NULL auto_increment,
			                            code TEXT NOT NULL,
			                            PRIMARY KEY( calendar_id )
		                            )
	                            ", array());

        $wpdb->query($query);

        //Using option for appointy calendar plugin!
        add_option("appointy_calendar_privileges", "2");

        if (!$this->appointy_calendar_installed())
            return false;
        else
            return true;
    }

    public function get_appointy_code()
    {
        global $wpdb;

        $code = $wpdb->get_var($wpdb->prepare("SELECT code AS code FROM " . $wpdb->prefix . "appointy_calendar LIMIT 1", array()));

        $url_components = parse_url($code);

        parse_str($url_components['query'], $params);


        if (strpos($code, "booking.appointy.com") === false) {
            return $url_components["scheme"] . "://" . $url_components["host"];
        } else {
            if (isset($url_components["path"])) {
                $url =  $url_components["scheme"] . "://" . $url_components["host"]  . $url_components["path"];
                if(substr($url, -1) !== "/") {
                    $url = $url . "/";
                }
               return $url;
            }
            return "https://booking.appointy.com/demo";
        }
    }

    public function RemovePathSlash($path_string)
    {
        if ($this->startsWith($path_string, "/")) {
            return substr($path_string, 1, strlen($path_string));
        } else {
            return $path_string;
        }
    }

    /**
     * @return mixed
     */
    public function get_appointy_installed()
    {
        return $this->appointy_installed;
    }

    /**
     * @param mixed $appointy_installed
     */
    public function set_appointy_installed($appointy_installed)
    {
        $this->appointy_installed = $appointy_installed;
    }

    /**
     * @return mixed
     */
    public function get_appointy_calendar_privileges()
    {
        return $this->appointy_calendar_privileges;
    }

    /**
     * @param mixed $appointy_calendar_privileges
     */
    public function set_appointy_calendar_privileges($appointy_calendar_privileges)
    {
        $this->appointy_calendar_privileges = $appointy_calendar_privileges;
    }

    /**
     * @return mixed
     */
    public function get_iframe_val()
    {
        global $wpdb;
        return esc_url_raw($wpdb->get_var($wpdb->prepare("SELECT code AS code FROM " . $wpdb->prefix . "appointy_calendar LIMIT 1", array())));
    }

    /**
     * @param mixed $iFrameVal
     */
    public function set_iframe_val($iFrameVal)
    {
        $this->iFrameVal = $iFrameVal;
    }

    /**
     * @return mixed
     */
    public function get_poweredby()
    {
        return $this->poweredby;
    }

    /**
     * @param mixed $poweredby
     */
    public function set_poweredby($poweredby)
    {
        $this->poweredby = $poweredby;
    }

}

class AppointySettings
{
    var $url;
    var $lang;
    var $maxWidth;
    var $maxHeight;
    var $widget; // 0 Iframe, 1 JS
    var $oldAppointy;
    var $setting_url;

    private $helper;

    function __construct(Appointy_helper_functions $helper)
    {
        $this->helper = $helper;
    }

    function ProcessFormSubmit($url, $lang, $maxWidth, $maxHeight, $widget)
    {
        if ($this->ValidateUrl($url)) {
            $this->GetFromAppointyUrl($url);
            //$this->AddLanguage($lang);
            $this->maxHeight = $maxHeight;
            $this->maxWidth = $maxWidth;
            $this->widget = $widget;
            return true;
        }
        return false;
    }

    function ParseFromSettingString($setting_str)
    {

        // It parse the url and returns all its components
        $url_components = parse_url($setting_str);

        // Use parse_str() function to parse the
        // string passed via URL
        parse_str(($url_components['query'] ?? null), $params); // change

        $this->setting_url = $setting_str;
        $this->url = ($url_components["scheme"] ?? null) . "://" . ($url_components["host"] ?? null) . "/" . $this->RemovePathSlash($url_components["path"]); // change
        //$this->lang = $params["lang"];
        $this->maxWidth = $params["maxWidth"] ?? null; // change
        $this->maxHeight = $params["maxHeight"] ?? null; // change
        $this->widget = $params["widget"] ?? null; // change

        if (strcmp(($url_components["host"] ?? null), "booking.appointy.com") == 0) { // change

            //https://booking.appointy.com/fr-fr/rahul55/bookings/service
            $this->oldAppointy = false;

        } else if (strpos(($url_components["host"] ?? null), ".appointy.com")) {

            // https://rahul55.appointy.com/ChangeLanguage.aspx?lan=zh-CN&isGadget=1
            $this->oldAppointy = true;
        } else {
            $helper = new Appointy_helper_functions();
            return new AppointySettings($helper);
        }
    }

    function GetFromAppointyUrl($appointy_str)
    {
        // It parse the url and returns all its components
        $url_components = parse_url($appointy_str);

        // Use parse_str() function to parse the
        // string passed via URL
        parse_str($url_components['query'], $params);

        $this->setting_url = $appointy_str;

        if (strcmp($url_components["host"], "booking.appointy.com") == 0) {

            //https://booking.appointy.com/fr-fr/rahul55

            $path = $this->RemovePathSlash($url_components['path']);
            if (strlen($path) > 0) {
                $path_variables = explode("/", $path);
                if (count($path_variables) == 2) {

                    // first one will be language
                    if ($this->helper->validate_language($path_variables[0])) {
                        $this->lang = $path_variables[0];
                    }else {
                        $this->lang = "default";
                    }

                    // second one will be username
                    $this->url = $this->url = $url_components["scheme"] . "://" . $url_components["host"] . "/" . $this->RemovePathSlash($path_variables[1]);

                } else if (count($path_variables) >= 1) {

                    // set language to default
                    $this->lang = "default";

                    // This parameter will be username
                    $this->url = $url_components["scheme"] . "://" . $url_components["host"] . "/" . $this->RemovePathSlash($url_components["path"]);

                } else {

                    //set default settings
                    $this->lang = "default";
                    $this->url = "https://booking.appointy.com/demo";

                }
            }

            $this->oldAppointy = false;

        } else if (strpos($url_components["host"], ".appointy.com")) {
            // https://rahul55.appointy.com/ChangeLanguage.aspx?lan=zh-CN

            // fetch the language from the url if present
            if (isset($params['lan']) && $this->helper . validate_language($params['lan'])) {
                $this->lang = $params['lan'];
            } else {
                $this->lang = "default";
            }

            $this->url = $url_components["scheme"] . "://" . $url_components["host"];
            $this->oldAppointy = true;
        }

        $this->widget = "booking-page"; //
    }

    public function GetSettingString()
    {
        // It parse the url and returns all its components
        $url_components = parse_url($this->url);

        if (strcmp($url_components["host"], "booking.appointy.com") != 0) {

            return $this->url . '?maxWidth=' . $this->maxWidth . '&maxHeight=' . $this->maxHeight . '&widget=' . $this->widget;
//            if (isset($this->lang) && $this->lang != "default") {
//                return $this->url . "/ChangeLanguage.aspx?lang=" . $this->lang . '&maxWidth=' . $this->maxWidth . '&maxHeight=' . $this->maxHeight . '&widget=' . $this->widget;
//            } else {
//                return $this->url . "?lang=" . $this->lang . '&maxWidth=' . $this->maxWidth . '&maxHeight=' . $this->maxHeight . '&widget=' . $this->widget;
//            }
        } else {

            return "https://booking.appointy.com/" . $this->GetUserNameFromUrl() . "?lang=" . $this->lang . '&maxWidth=' . $this->maxWidth . '&maxHeight=' . $this->maxHeight . '&widget=' . $this->widget;
//            if (isset($this->lang) && $this->lang != "default") {
//                return "https://booking.appointy.com/" . $this->lang . "/" . $this->GetUserNameFromUrl() . "?lang=" . $this->lang . '&maxWidth=' . $this->maxWidth . '&maxHeight=' . $this->maxHeight . '&widget=' . $this->widget;
//            } else {
//                return "https://booking.appointy.com/" . $this->GetUserNameFromUrl() . "?lang=" . $this->lang . '&maxWidth=' . $this->maxWidth . '&maxHeight=' . $this->maxHeight . '&widget=' . $this->widget;
//            }
        }
    }

    public function AddLanguage($lang)
    {
        if ($this->helper->validate_language($lang)) {
            $this->lang = $lang;
        } else {
            $this->lang = "default";
        }
    }

    private function GetOldAppointyUsername($oldAppointyUrl)
    {
        $params = explode(".", $oldAppointyUrl);
        if (count($params) >= 3) {
            return str_replace("https://", "", $params[0]);
        } else {
            return "demo";
        }

    }

    public function ValidateUrl($url)
    {
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);

        if (strcmp($url, "booking.appointy.com") == 0) {

            $path_params = explode("/", $this->RemovePathSlash($url_components["path"]));
            if (count($path_params) == 2 && $this->helper->validate_language($path_params[0])) {
                return true;
            } else if (count($path_params) == 1) {
                return true;
            }

            return false;

        } else if (strpos($url, ".appointy.com")) {
            return true;
        }

        return false;
    }

    public function RemovePathSlash($path_string)
    {
        if ($this->startsWith($path_string, "/")) {
            return substr($path_string, 1, strlen($path_string));
        } else {
            return $path_string;
        }
    }

    private function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    public function GetUserNameFromUrl()
    {
        $url_components = parse_url($this->url);

        if (strcmp($url_components["host"], "booking.appointy.com") == 0) {
            $url_components = parse_url($this->url);
            $path = $this->RemovePathSlash(($url_components['path']));
            if (strlen($path) > 0) {
                $path_variables = explode("/", $path);
                if (count($path_variables) == 2) {
                    return $this->RemovePathSlash($path_variables[1]);
                } else if (count($path_variables) == 1) {
                    return $this->RemovePathSlash($path_variables[0]);
                } else {
                    return "demo";
                }
            }
        } else {
            return $this->GetOldAppointyUsername($this->url);
        }
    }

    public function IsAppointySetupFinished() {

        try {
            global $wpdb;
            $columnExists = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . $wpdb->prefix . "appointy_calendar' AND COLUMN_NAME = 'setup'", array()));

            if ($columnExists == 0) {
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "appointy_calendar ADD setup text;");
            }

            $setupCompleted = $wpdb->get_var($wpdb->prepare("SELECT setup FROM " . $wpdb->prefix . "appointy_calendar LIMIT 1", array()));
            if ($setupCompleted == "true") {
                return true;
            } else {
                $result = wp_remote_get("http://business.appointy.com/Apps/Wordpress/wizard?username=" . $this->GetUserNameFromUrl());
                if ($result["body"] == "true") {
                    $wpdb->query("UPDATE " . $wpdb->prefix . "appointy_calendar SET setup= 'true';");
                    return true;
                }

                $wpdb->query("UPDATE " . $wpdb->prefix . "appointy_calendar SET setup= 'false';");
                return false;
            }
        } catch ( Exception $ex ) {
            return false;
        }
    }

    public function GetUnit($value) {
        if ($value == "") {
            return "%";
        }

        if (strpos($value, '%') !== false) {
            return "%";
        }else if (strpos($value, 'px') !== false) {
            return "px";
        }else {
            return "%";
        }
    }

    public function GetValue($value) {
        if ($value == "") {
            return "100";
        }

        if (strpos($value, '%') !== false) {
            return str_replace("%", "", $value);
        }else if (strpos($value, 'px') !== false) {
            return str_replace("px", "", $value);
        }else {
            return $value;
        }
    }
}