<?php

namespace IfSo\PublicFace\Models\DataRulesModel;

require_once( __DIR__ . '/ifso-data-rules-model.class.php');
require_once( IFSO_PLUGIN_SERVICES_BASE_DIR . 'groups-service/groups-service.class.php' );

class DataRulesUiModel{
    protected $data_rules;
    protected $data_rules_ui;
    protected $ui_model_wp_filter_name = 'ifso_data_rules_ui_model_filter';

    public function __construct(){
        $dr_model = new DataRulesModel;
        $this->data_rules = $dr_model->get_data_rules();
        unset($this->data_rules['general']);
    }

    public function get_ui_model(){
        if(null===$this->data_rules_ui){
            $this->data_rules_ui = $this->make_ui_model();
        }
        return $this->data_rules_ui;
    }

    public function get_data_rules(){
        return $this->data_rules;
    }

    protected function make_ui_model(){
        $model = new \stdClass();

        foreach($this->data_rules as $type=>$value){
            $fields = $this->make_condition_fields($type);
            $name = $this->get_condition_name($type);
            $noticeboxes = $this->get_condition_noticeboxes($type);
            $multibox = $this->get_condition_multibox_data($type);
            if(!empty($fields) && is_array($noticeboxes)){
                //$fields = (object) array_merge((array) $fields,(array) $noticeboxes);
                //$fields = (array) $noticeboxes +(array) $fields;
                $fields =  (array) $fields + (array) $noticeboxes;
                $fields = (object) $fields;
            }
            if(!empty((array) $fields)){
                $model->$type  = (object)['fields' => $fields, 'name' => $name];
                if(!empty($multibox))
                    $model->$type->multibox = $multibox;
            }
        }

        $model = apply_filters($this->ui_model_wp_filter_name,$model);

        return $model;
    }

    protected function make_condition_fields($type){
        if(isset($this->data_rules[$type])){
            $ret = new \stdClass();
            foreach($this->data_rules[$type] as $rule) {
                switch ($type) {
                    case 'AB-Testing':
                        if ($rule === 'AB-Testing') {
                            $ret->$rule = new ConditionUIElement($rule, 'A/B Testing', 'select');
                        }

                        if ($rule === 'ab-testing-sessions') {

                        }

                        break;
                    case 'advertising-platforms':
                        if ($rule === 'advertising_platforms_option') {
                            $ret->$rule = new ConditionUIElement($rule, 'Advertising Platform' .$this->make_question_mark_link('https://www.if-so.com/help/documentation/search-term-based-content'), 'select',
                                true, [new ConditionUIOption('google', 'Google Ads'), new ConditionUIOption('facebook', 'Facebook Ads')]);
                        }

                        if ($rule === 'advertising_platforms') {
                            $ret->$rule = new ConditionUIElement($rule, 'Query value', 'text', true);
                        }
                        break;
                    case 'Cookie':
                        if ($rule === 'cookie-input') {
                            $ret->$rule = new ConditionUIElement($rule, 'Name', 'text', false);
                        }

                        if($rule === 'cookie-relationship'){
                            $ret->$rule = new ConditionUIElement($rule,'','select',true,[new ConditionUIOption('is','Is'),new ConditionUIOption('is-not','Is Not'),new ConditionUIOption('is-more','Numeric Value Is More Than'),new ConditionUIOption('is-less','Numeric Value Is Less Than')]);
                        }

                        if ($rule === 'cookie-value-input') {
                            $qm = $this->make_question_mark_link('https://www.if-so.com/help/documentation/the-cookie-condition/','optional-value');
                            $ret->$rule = new ConditionUIElement($rule, "Value (Optional){$qm}", 'text', false);
                        }

                        if($rule === 'cookie-or-session'){
                            $ret->$rule = new ConditionUIElement($rule, 'Cookie/Session' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/the-cookie-condition/'), 'select',
                                true, [new ConditionUIOption('cookie', 'Cookie'), new ConditionUIOption('session', 'Session Variable')]);
                        }
                        break;
                    case 'Device':
                        if ($rule === 'user-behavior-device-mobile') {
                            $ret->$rule = new ConditionUIElement($rule, 'Mobile', 'checkbox', false);
                        }

                        if ($rule === 'user-behavior-device-tablet') {
                            $ret->$rule = new ConditionUIElement($rule, 'Tablet', 'checkbox', false);
                        }

                        if ($rule === 'user-behavior-device-desktop') {
                            $ret->$rule = new ConditionUIElement($rule, 'Desktop', 'checkbox', false);
                        }
                        break;
                    case 'url':
                        if ($rule === 'compare') {
                            $ret->$rule = new ConditionUIElement($rule, 'Your query value' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/dynamic-links/'), 'text', true);
                        }
                        break;
                    case 'UserIp':
                        if ($rule === 'ip-values') {
                            $ret->$rule = new ConditionUIElement($rule, 'User Ip' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/user-ip/'), 'select',
                                true, [new ConditionUIOption('is', 'IP Is'), new ConditionUIOption('contains', 'IP Contains'), new ConditionUIOption('is-not', 'IP is not'), new ConditionUIOption('not-contains', 'IP doesn\'t contain')]);
                        }

                        if ($rule === 'ip-input') {
                            $ret->$rule = new ConditionUIElement($rule, 'IP Address', 'text', true);
                        }
                        break;
                    case 'Geolocation':
                        if($rule === 'geolocation_behaviour'){
                            $ret->$rule = new ConditionUIElement($rule, 'Geolocation' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/geolocation/'), 'select', true,
                                [new ConditionUIOption('is','Is'),new ConditionUIOption('is-not','Is Not')]);
                        }

                        if($rule === 'geolocation_data'){
                            $ret->$rule = new MultiConditionBox($rule,'','');
                            $ret->geolocation_type = new ConditionUIElement('geolocation_type', '', 'select', true,
                                [new ConditionUIOption('country','Country'),new ConditionUIOption('city','City'), new ConditionUIOption('continent','Continent'), new ConditionUIOption('state','State'), new ConditionUIOption('timezone','Time Zone')], true);
                            $tz_opts = [new ConditionUIOption('','Select Time Zone'),new ConditionUIOption('(UTC-12:00) International Date Line West','(UTC-12:00) International Date Line West'),new ConditionUIOption('(UTC-11:00) Coordinated Universal Time-11','(UTC-11:00) Coordinated Universal Time-11'),new ConditionUIOption('(UTC-10:00) Hawaii','(UTC-10:00) Hawaii'),new ConditionUIOption('(UTC-09:00) Alaska','(UTC-09:00) Alaska'),new ConditionUIOption('(UTC-08:00) Baja California','(UTC-08:00) Baja California'),new ConditionUIOption('(UTC-08:00) Pacific Time (US & Canada)','(UTC-08:00) Pacific Time (US & Canada)'),new ConditionUIOption('(UTC-07:00) Arizona','(UTC-07:00) Arizona'),new ConditionUIOption('(UTC-07:00) Chihuahua, La Paz, Mazatlan','(UTC-07:00) Chihuahua, La Paz, Mazatlan'),new ConditionUIOption('(UTC-07:00) Mountain Time (US & Canada)','(UTC-07:00) Mountain Time (US & Canada)'),new ConditionUIOption('(UTC-06:00) Central America','(UTC-06:00) Central America'),new ConditionUIOption('(UTC-06:00) Central Time (US & Canada)','(UTC-06:00) Central Time (US & Canada)'),new ConditionUIOption('(UTC-06:00) Guadalajara, Mexico City, Monterrey','(UTC-06:00) Guadalajara, Mexico City, Monterrey'),new ConditionUIOption('(UTC-06:00) Saskatchewan','(UTC-06:00) Saskatchewan'),new ConditionUIOption('(UTC-05:00) Bogota, Lima, Quito','(UTC-05:00) Bogota, Lima, Quito'),new ConditionUIOption('(UTC-05:00) Eastern Time (US & Canada)','(UTC-05:00) Eastern Time (US & Canada)'),new ConditionUIOption('(UTC-05:00) Indiana (East)','(UTC-05:00) Indiana (East)'),new ConditionUIOption('(UTC-04:30) Caracas','(UTC-04:30) Caracas'),new ConditionUIOption('(UTC-04:00) Asuncion','(UTC-04:00) Asuncion'),new ConditionUIOption('(UTC-04:00) Atlantic Time (Canada)','(UTC-04:00) Atlantic Time (Canada)'),new ConditionUIOption('(UTC-04:00) Cuiaba','(UTC-04:00) Cuiaba'),new ConditionUIOption('(UTC-04:00) Georgetown, La Paz, Manaus, San Juan','(UTC-04:00) Georgetown, La Paz, Manaus, San Juan'),new ConditionUIOption('(UTC-04:00) Santiago','(UTC-04:00) Santiago'),new ConditionUIOption('(UTC-03:30) Newfoundland','(UTC-03:30) Newfoundland'),new ConditionUIOption('(UTC-03:00) Brasilia','(UTC-03:00) Brasilia'),new ConditionUIOption('(UTC-03:00) Buenos Aires','(UTC-03:00) Buenos Aires'),new ConditionUIOption('(UTC-03:00) Cayenne, Fortaleza','(UTC-03:00) Cayenne, Fortaleza'),new ConditionUIOption('(UTC-03:00) Greenland','(UTC-03:00) Greenland'),new ConditionUIOption('(UTC-03:00) Montevideo','(UTC-03:00) Montevideo'),new ConditionUIOption('(UTC-03:00) Salvador','(UTC-03:00) Salvador'),new ConditionUIOption('(UTC-02:00) Coordinated Universal Time-02','(UTC-02:00) Coordinated Universal Time-02'),new ConditionUIOption('(UTC-02:00) Mid-Atlantic - Old','(UTC-02:00) Mid-Atlantic - Old'),new ConditionUIOption('(UTC-01:00) Azores','(UTC-01:00) Azores'),new ConditionUIOption('(UTC-01:00) Cape Verde Is.','(UTC-01:00) Cape Verde Is.'),new ConditionUIOption('(UTC) Casablanca','(UTC) Casablanca'),new ConditionUIOption('(UTC) Coordinated Universal Time','(UTC) Coordinated Universal Time'),new ConditionUIOption('(UTC) Dublin, Edinburgh, Lisbon, London','(UTC) Dublin, Edinburgh, Lisbon, London'),new ConditionUIOption('(UTC) Monrovia, Reykjavik','(UTC) Monrovia, Reykjavik'),new ConditionUIOption('(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna','(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),new ConditionUIOption('(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague','(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague'),new ConditionUIOption('(UTC+01:00) Brussels, Copenhagen, Madrid, Paris','(UTC+01:00) Brussels, Copenhagen, Madrid, Paris'),new ConditionUIOption('(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb','(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb'),new ConditionUIOption('(UTC+01:00) West Central Africa','(UTC+01:00) West Central Africa'),new ConditionUIOption('(UTC+01:00) Windhoek','(UTC+01:00) Windhoek'),new ConditionUIOption('(UTC+02:00) Athens, Bucharest','(UTC+02:00) Athens, Bucharest'),new ConditionUIOption('(UTC+02:00) Beirut','(UTC+02:00) Beirut'),new ConditionUIOption('(UTC+02:00) Cairo','(UTC+02:00) Cairo'),new ConditionUIOption('(UTC+02:00) Damascus','(UTC+02:00) Damascus'),new ConditionUIOption('(UTC+02:00) E. Europe','(UTC+02:00) E. Europe'),new ConditionUIOption('(UTC+02:00) Harare, Pretoria','(UTC+02:00) Harare, Pretoria'),new ConditionUIOption('(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius','(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),new ConditionUIOption('(UTC+03:00) Istanbul','(UTC+03:00) Istanbul'),new ConditionUIOption('(UTC+02:00) Jerusalem','(UTC+02:00) Jerusalem'),new ConditionUIOption('(UTC+02:00) Tripoli','(UTC+02:00) Tripoli'),new ConditionUIOption('(UTC+03:00) Amman','(UTC+03:00) Amman'),new ConditionUIOption('(UTC+03:00) Baghdad','(UTC+03:00) Baghdad'),new ConditionUIOption('(UTC+03:00) Kaliningrad, Minsk','(UTC+03:00) Kaliningrad, Minsk'),new ConditionUIOption('(UTC+03:00) Kuwait, Riyadh','(UTC+03:00) Kuwait, Riyadh'),new ConditionUIOption('(UTC+03:00) Nairobi','(UTC+03:00) Nairobi'),new ConditionUIOption('(UTC+03:00) Moscow, St. Petersburg, Volgograd','(UTC+03:00) Moscow, St. Petersburg, Volgograd'),new ConditionUIOption('(UTC+04:00) Samara, Ulyanovsk, Saratov','(UTC+04:00) Samara, Ulyanovsk, Saratov'),new ConditionUIOption('(UTC+03:30) Tehran','(UTC+03:30) Tehran'),new ConditionUIOption('(UTC+04:00) Abu Dhabi, Muscat','(UTC+04:00) Abu Dhabi, Muscat'),new ConditionUIOption('(UTC+04:00) Baku','(UTC+04:00) Baku'),new ConditionUIOption('(UTC+04:00) Port Louis','(UTC+04:00) Port Louis'),new ConditionUIOption('(UTC+04:00) Tbilisi','(UTC+04:00) Tbilisi'),new ConditionUIOption('(UTC+04:00) Yerevan','(UTC+04:00) Yerevan'),new ConditionUIOption('(UTC+04:30) Kabul','(UTC+04:30) Kabul'),new ConditionUIOption('(UTC+05:00) Ashgabat, Tashkent','(UTC+05:00) Ashgabat, Tashkent'),new ConditionUIOption('(UTC+05:00) Islamabad, Karachi','(UTC+05:00) Islamabad, Karachi'),new ConditionUIOption('(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi','(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi'),new ConditionUIOption('(UTC+05:30) Sri Jayawardenepura','(UTC+05:30) Sri Jayawardenepura'),new ConditionUIOption('(UTC+05:45) Kathmandu','(UTC+05:45) Kathmandu'),new ConditionUIOption('(UTC+06:00) Astana','(UTC+06:00) Astana'),new ConditionUIOption('(UTC+06:00) Dhaka','(UTC+06:00) Dhaka'),new ConditionUIOption('(UTC+06:00) Ekaterinburg','(UTC+06:00) Ekaterinburg'),new ConditionUIOption('(UTC+06:30) Yangon (Rangoon)','(UTC+06:30) Yangon (Rangoon)'),new ConditionUIOption('(UTC+07:00) Bangkok, Hanoi, Jakarta','(UTC+07:00) Bangkok, Hanoi, Jakarta'),new ConditionUIOption('(UTC+07:00) Novosibirsk','(UTC+07:00) Novosibirsk'),new ConditionUIOption('(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi','(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi'),new ConditionUIOption('(UTC+08:00) Krasnoyarsk','(UTC+08:00) Krasnoyarsk'),new ConditionUIOption('(UTC+08:00) Kuala Lumpur, Singapore','(UTC+08:00) Kuala Lumpur, Singapore'),new ConditionUIOption('(UTC+08:00) Perth','(UTC+08:00) Perth'),new ConditionUIOption('(UTC+08:00) Taipei','(UTC+08:00) Taipei'),new ConditionUIOption('(UTC+08:00) Ulaanbaatar','(UTC+08:00) Ulaanbaatar'),new ConditionUIOption('(UTC+09:00) Irkutsk','(UTC+09:00) Irkutsk'),new ConditionUIOption('(UTC+09:00) Osaka, Sapporo, Tokyo','(UTC+09:00) Osaka, Sapporo, Tokyo'),new ConditionUIOption('(UTC+09:00) Seoul','(UTC+09:00) Seoul'),new ConditionUIOption('(UTC+09:30) Adelaide','(UTC+09:30) Adelaide'),new ConditionUIOption('(UTC+09:30) Darwin','(UTC+09:30) Darwin'),new ConditionUIOption('(UTC+10:00) Brisbane','(UTC+10:00) Brisbane'),new ConditionUIOption('(UTC+10:00) Canberra, Melbourne, Sydney','(UTC+10:00) Canberra, Melbourne, Sydney'),new ConditionUIOption('(UTC+10:00) Guam, Port Moresby','(UTC+10:00) Guam, Port Moresby'),new ConditionUIOption('(UTC+10:00) Hobart','(UTC+10:00) Hobart'),new ConditionUIOption('(UTC+10:00) Yakutsk','(UTC+10:00) Yakutsk'),new ConditionUIOption('(UTC+11:00) Solomon Is., New Caledonia','(UTC+11:00) Solomon Is., New Caledonia'),new ConditionUIOption('(UTC+11:00) Vladivostok','(UTC+11:00) Vladivostok'),new ConditionUIOption('(UTC+12:00) Auckland, Wellington','(UTC+12:00) Auckland, Wellington'),new ConditionUIOption('(UTC+12:00) Coordinated Universal Time+12','(UTC+12:00) Coordinated Universal Time+12'),new ConditionUIOption('(UTC+12:00) Fiji','(UTC+12:00) Fiji'),new ConditionUIOption('(UTC+12:00) Magadan','(UTC+12:00) Magadan'),new ConditionUIOption('(UTC+12:00) Petropavlovsk-Kamchatsky - Old','(UTC+12:00) Petropavlovsk-Kamchatsky - Old'),new ConditionUIOption('(UTC+13:00) Nuku\'alofa','(UTC+13:00) Nuku\'alofa'),new ConditionUIOption('(UTC+13:00) Samoa','(UTC+13:00) Samoa')];
                            $continent_opts = [new ConditionUIOption('','Select Continent'),new ConditionUIOption('AF','Africa'),new ConditionUIOption('AN','Antarctica'),new ConditionUIOption('AS','Asia'),new ConditionUIOption('EU','Europe'),new ConditionUIOption('NA','North America'),new ConditionUIOption('OC','Oceania'),new ConditionUIOption('SA','South America'),];
                            require_once('country_opts_variable.php');  //The large $country_opts variable declaration was moved to a separate file
                            $locaton_generator_link = function($type){
                                $locaton_generator_url = admin_url("?page=wpcdd_admin_location_generator&type={$type}");
                                return "<a href='{$locaton_generator_url}' title='Open Location Finder Helper' rel='permalink'  onclick=\"if(typeof(window.ifsoLocGenPipe)!=='undefined' && typeof (window.ifsoLocGenPipe.open)==='function')window.ifsoLocGenPipe.open('{$locaton_generator_url}',this);else window.open('{$locaton_generator_url}', 'newwindow', 'width=800,height=600'); return false;\">Click to select</a>";
                            };
                            $ret->geolocation_country_input = new ConditionUIElement('geolocation_country_input', 'Country', 'select', false, $country_opts, false,'country', 'countries-autocomplete', 'COUNTRY' );
                            $ret->geolocation_city_input = new ConditionUIElement('geolocation_city_input', 'City (' . $locaton_generator_link('city') . ')', 'text', false, null, false,'city' ,'continents-autocomplete','CITY' );
                            $ret->geolocation_continent_input = new ConditionUIElement('geolocation_continent_input', 'Continent', 'select', false, $continent_opts, false,'continent','continents-autocomplete','CONTINENT' );
                            $ret->geolocation_state_input = new ConditionUIElement('geolocation_state_input', 'State (' . $locaton_generator_link('state') . ')', 'text', false, null, false,'state','states-autocomplete','STATE' );
                            $ret->geolocation_timezone_input = new ConditionUIElement('geolocation_timezone_input', 'Time Zone', 'select',false, $tz_opts, false,'timezone', '','TIMEZONE' );
                        }
                        break;
                    case 'PageUrl':
                        if ($rule === 'page-url-operator') {
                            $ret->$rule = new ConditionUIElement('page-url-operator', 'Page URL' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/page_url/'), 'select',
                                true, [new ConditionUIOption('is', 'URL Is'), new ConditionUIOption('contains', 'URL Contains'), new ConditionUIOption('is-not', 'URL Is Not'), new ConditionUIOption('not-containes', 'URL Does Not Contain')]);
                        }

                        if ($rule === 'page-url-compare') {
                            $ret->$rule = new ConditionUIElement('page-url-compare', 'URL value', 'text', true);
                        }

                        if($rule === 'page-url-ignore-case'){
                            $ret->$rule = new ConditionUIElement($rule, 'Ignore Case','checkbox', true);
                        }
                        break;
                    case 'PageVisit':
                        if($rule === 'page_visit_data'){
                            $ret->page_visit_data = new MultiConditionBox('page_visit_data','',"PAGEURL");
                            $ret->page_visit_operator = new ConditionUIElement('page_visit_operator', 'Pages Visited' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/pages-visited/'), 'select', false,
                            [new ConditionUIOption('url is', 'URL Is'), new ConditionUIOption('url contains', 'URL Contains'), new ConditionUIOption('url is not', 'URL Is Not'), new ConditionUIOption('url not contains', 'URL Does Not Contain')],false,null,'nosubmit','PAGEURL');

                            $ret->page_visit_value = new ConditionUIElement('page_visit_value','Value', 'text',false,null,false,null,'','PAGEURL');
                        }
                        break;
                    case 'referrer':
                        if($rule === 'trigger'){
                            $ret->$rule = new ConditionUIElement($rule, 'Referral Source' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/referrer/'), 'select',
                            true, [new ConditionUIOption('custom-url','URL'), new ConditionUIOption('page-on-website','Page on your website'), new ConditionUIOption('page-category','Post/Page Category'), new ConditionUIOption('common-referrers','Common Referrers')], true);
                        }
                        if($rule === 'page'){
                            $args = array(
                                'sort_order' => 'asc',
                                'sort_column' => 'post_title',
                                'hierarchical' => 1,
                                'child_of' => 0,
                                'post_type' => 'page',
                                'post_status' => 'publish',
                                'suppress_filters' => true
                            );
                            $available_pages = get_pages($args);
                            $options = array_map(function($page){
                                return new ConditionUIOption($page->ID, $page->post_title);
                            },$available_pages);
                            $ret->$rule = new ConditionUIElement($rule,'','select',
                            false,$options,false,'page-on-website');
                        }
                        if($rule === 'chosen-common-referrers'){
                            $ret->$rule = new ConditionUIElement($rule,'','select',
                            false,[new ConditionUIOption('google','Google'), new ConditionUIOption('facebook','Facebook')],false,'common-referrers');
                        }
                        if($rule === 'custom'){}

                        if($rule === 'operator'){
                            $ret->$rule = new ConditionUIElement($rule,'','select',
                            false,[new ConditionUIOption('is','URL Is'), new ConditionUIOption('is-not','URL Is Not'),new ConditionUIOption('contains','URL Contains'), new ConditionUIOption('not-containes','URL Does Not Contain')],false,'custom-url');
                        }
                        if($rule === 'compare'){
                            $ret->$rule = new ConditionUIElement($rule,'Value','text',false,null,false,'custom-url');
                        }
                        if($rule==='page-category'){
                            $categories_opts = array_map(function ($cat){
                                return new ConditionUIOption($cat->cat_ID, $cat->name);
                            },get_categories(['hide_empty'=>false]));
                            array_unshift($categories_opts,new ConditionUIOption('', 'Select a Category'));
                            $ret->$rule = new ConditionUIElement($rule,'','select', false,$categories_opts,false,'page-category');

                        }
                        if($rule==='page-category-operator'){
                            $opts = [new ConditionUIOption('is', 'Is'),new ConditionUIOption('is-not', 'Is Not')];
                            $ret->$rule = new ConditionUIElement($rule,'','select', false,$opts,false,'page-category');
                        }
                        break;
                    case 'Time-Date':
                        break;
                    case 'User-Behavior':
                        if($rule==='User-Behavior'){
                            $ret->$rule = new ConditionUIElement($rule, 'User Behavior', 'select',
                            true, [new ConditionUIOption('Logged', 'User is logged in'), new ConditionUIOption('NewUser', 'New Visitor'), new ConditionUIOption('Returning', 'Returning Visitor'), new ConditionUIOption('BrowserLanguage', 'Browser Language')], true);
                        }
                        if($rule==='user-behavior-browser-language-primary-lang'){
                            $ret->$rule = new ConditionUIElement($rule, 'Primary language only' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/browser-language/'),'checkbox', true, null, false, 'BrowserLanguage');
                        }
                        if($rule==='user-behavior-browser-language'){
                            $languages = array(
                                array('en-US', 'en-UK', 'en', 'English', 'English'),
                                array('he', 'heb', 'heb', 'heb', 'Hebrew', 'עברית'),
                                array('ab', 'abk', 'abk', 'abk', 'Abkhaz', 'аҧсуа бызшәа, аҧсшәа'),
                                array('aa', 'aar', 'aar', 'aar', 'Afar', 'Afaraf'),
                                array('af', 'afr', 'afr', 'afr', 'Afrikaans', 'Afrikaans'),
                                array('ak', 'aka', 'aka', 'aka', 'Akan', 'Akan'),
                                array('sq', 'sqi', 'alb', 'sqi', 'Albanian', 'Shqip'),
                                array('am', 'amh', 'amh', 'amh', 'Amharic', 'አማርኛ'),
                                array('ar', 'ara', 'ara', 'ara', 'Arabic', 'العربية'),
                                array('an', 'arg', 'arg', 'arg', 'Aragonese', 'aragonés'),
                                array('hy', 'hye', 'arm', 'hye', 'Armenian', 'Հայերեն'),
                                array('as', 'asm', 'asm', 'asm', 'Assamese', 'অসমীয়া'),
                                array('av', 'ava', 'ava', 'ava', 'Avaric', 'авар мацӀ, магӀарул мацӀ'),
                                array('ae', 'ave', 'ave', 'ave', 'Avestan', 'avesta'),
                                array('ay', 'aym', 'aym', 'aym', 'Aymara', 'aymar aru'),
                                array('az', 'aze', 'aze', 'aze', 'Azerbaijani', 'azərbaycan dili'),
                                array('bm', 'bam', 'bam', 'bam', 'Bambara', 'bamanankan'),
                                array('ba', 'bak', 'bak', 'bak', 'Bashkir', 'башҡорт теле'),
                                array('eu', 'eus', 'baq', 'eus', 'Basque', 'euskara, euskera'),
                                array('be', 'bel', 'bel', 'bel', 'Belarusian', 'беларуская мова'),
                                array('bn', 'ben', 'ben', 'ben', 'Bengali, Bangla', 'বাংলা'),
                                array('bh', 'bih', 'bih', '', 'Bihari', 'भोजपुरी'),
                                array('bi', 'bis', 'bis', 'bis', 'Bislama', 'Bislama'),
                                array('bs', 'bos', 'bos', 'bos', 'Bosnian', 'bosanski jezik'),
                                array('br', 'bre', 'bre', 'bre', 'Breton', 'brezhoneg'),
                                array('bg', 'bul', 'bul', 'bul', 'Bulgarian', 'български език'),
                                array('my', 'mya', 'bur', 'mya', 'Burmese', 'ဗမာစာ'),
                                array('ca', 'cat', 'cat', 'cat', 'Catalan', 'català'),
                                array('ch', 'cha', 'cha', 'cha', 'Chamorro', 'Chamoru'),
                                array('ce', 'che', 'che', 'che', 'Chechen', 'нохчийн мотт'),
                                array('ny', 'nya', 'nya', 'nya', 'Chichewa, Chewa, Nyanja', 'chiCheŵa, chinyanja'),
                                array('zh', 'zho', 'chi', 'zho', 'Chinese', '中文 (Zhōngwén), 汉语, 漢語'),
                                array('cv', 'chv', 'chv', 'chv', 'Chuvash', 'чӑваш чӗлхи'),
                                array('kw', 'cor', 'cor', 'cor', 'Cornish', 'Kernewek'),
                                array('co', 'cos', 'cos', 'cos', 'Corsican', 'corsu, lingua corsa'),
                                array('cr', 'cre', 'cre', 'cre', 'Cree', 'ᓀᐦᐃᔭᐍᐏᐣ'),
                                array('hr', 'hrv', 'hrv', 'hrv', 'Croatian', 'hrvatski jezik'),
                                array('cs', 'ces', 'cze', 'ces', 'Czech', 'čeština, český jazyk'),
                                array('da', 'dan', 'dan', 'dan', 'Danish', 'dansk'),
                                array('dv', 'div', 'div', 'div', 'Divehi, Dhivehi, Maldivian', 'ދިވެހި'),
                                array('nl', 'nld', 'dut', 'nld', 'Dutch', 'Nederlands, Vlaams'),
                                array('dz', 'dzo', 'dzo', 'dzo', 'Dzongkha', 'རྫོང་ཁ'),
                                array('eo', 'epo', 'epo', 'epo', 'Esperanto', 'Esperanto'),
                                array('et', 'est', 'est', 'est', 'Estonian', 'eesti, eesti keel'),
                                array('ee', 'ewe', 'ewe', 'ewe', 'Ewe', 'Eʋegbe'),
                                array('fo', 'fao', 'fao', 'fao', 'Faroese', 'føroyskt'),
                                array('fj', 'fij', 'fij', 'fij', 'Fijian', 'vosa Vakaviti'),
                                array('fi', 'fin', 'fin', 'fin', 'Finnish', 'suomi, suomen kieli'),
                                array('fr', 'fra', 'fre', 'fra', 'French', 'français, langue française'),
                                array('ff', 'ful', 'ful', 'ful', 'Fula, Fulah, Pulaar, Pular', 'Fulfulde, Pulaar, Pular'),
                                array('gl', 'glg', 'glg', 'glg', 'Galician', 'galego'),
                                array('ka', 'kat', 'geo', 'kat', 'Georgian', 'ქართული'),
                                array('de', 'deu', 'ger', 'deu', 'German', 'Deutsch'),
                                array('el', 'ell', 'gre', 'ell', 'Greek', 'ελληνικά'),
                                array('gn', 'grn', 'grn', 'grn', 'Guaraní', 'Avañe\'ẽ'),
                                array('gu', 'guj', 'guj', 'guj', 'Gujarati', 'ગુજરાતી'),
                                array('ht', 'hat', 'hat', 'hat', 'Haitian, Haitian Creole', 'Kreyòl ayisyen'),
                                array('ha', 'hau', 'hau', 'hau', 'Hausa', '(Hausa) هَوُسَ'),
                                array('hz', 'her', 'her', 'her', 'Herero', 'Otjiherero'),
                                array('hi', 'hin', 'hin', 'hin', 'Hindi', 'हिन्दी, हिंदी'),
                                array('ho', 'hmo', 'hmo', 'hmo', 'Hiri Motu', 'Hiri Motu'),
                                array('hu', 'hun', 'hun', 'hun', 'Hungarian', 'magyar'),
                                array('ia', 'ina', 'ina', 'ina', 'Interlingua', 'Interlingua'),
                                array('id', 'ind', 'ind', 'ind', 'Indonesian', 'Bahasa Indonesia'),
                                array('ie', 'ile', 'ile', 'ile', 'Interlingue', 'Originally called Occidental; then Interlingue after WWII'),
                                array('ga', 'gle', 'gle', 'gle', 'Irish', 'Gaeilge'),
                                array('ig', 'ibo', 'ibo', 'ibo', 'Igbo', 'Asụsụ Igbo'),
                                array('ik', 'ipk', 'ipk', 'ipk', 'Inupiaq', 'Iñupiaq, Iñupiatun'),
                                array('io', 'ido', 'ido', 'ido', 'Ido', 'Ido'),
                                array('is', 'isl', 'ice', 'isl', 'Icelandic', 'Íslenska'),
                                array('it', 'ita', 'ita', 'ita', 'Italian', 'italiano'),
                                array('iu', 'iku', 'iku', 'iku', 'Inuktitut', 'ᐃᓄᒃᑎᑐᑦ'),
                                array('ja', 'jpn', 'jpn', 'jpn', 'Japanese', '日本語 (にほんご)'),
                                array('jv', 'jav', 'jav', 'jav', 'Javanese', 'basa Jawa'),
                                array('kl', 'kal', 'kal', 'kal', 'Kalaallisut, Greenlandic', 'kalaallisut, kalaallit oqaasii'),
                                array('kn', 'kan', 'kan', 'kan', 'Kannada', 'ಕನ್ನಡ'),
                                array('kr', 'kau', 'kau', 'kau', 'Kanuri', 'Kanuri'),
                                array('ks', 'kas', 'kas', 'kas', 'Kashmiri', 'कश्मीरी, كشميري‎'),
                                array('kk', 'kaz', 'kaz', 'kaz', 'Kazakh', 'қазақ тілі'),
                                array('km', 'khm', 'khm', 'khm', 'Khmer', 'ខ្មែរ, ខេមរភាសា, ភាសាខ្មែរ'),
                                array('ki', 'kik', 'kik', 'kik', 'Kikuyu, Gikuyu', 'Gĩkũyũ'),
                                array('rw', 'kin', 'kin', 'kin', 'Kinyarwanda', 'Ikinyarwanda'),
                                array('ky', 'kir', 'kir', 'kir', 'Kyrgyz', 'Кыргызча, Кыргыз тили'),
                                array('kv', 'kom', 'kom', 'kom', 'Komi', 'коми кыв'),
                                array('kg', 'kon', 'kon', 'kon', 'Kongo', 'Kikongo'),
                                array('ko', 'kor', 'kor', 'kor', 'Korean', '한국어, 조선어'),
                                array('ku', 'kur', 'kur', 'kur', 'Kurdish', 'Kurdî, كوردی‎'),
                                array('kj', 'kua', 'kua', 'kua', 'Kwanyama, Kuanyama', 'Kuanyama'),
                                array('la', 'lat', 'lat', 'lat', 'Latin', 'latine, lingua latina'),
                                array('', '', '', 'lld', 'Ladin', 'ladin, lingua ladina'),
                                array('lb', 'ltz', 'ltz', 'ltz', 'Luxembourgish, Letzeburgesch', 'Lëtzebuergesch'),
                                array('lg', 'lug', 'lug', 'lug', 'Ganda', 'Luganda'),
                                array('li', 'lim', 'lim', 'lim', 'Limburgish, Limburgan, Limburger', 'Limburgs'),
                                array('ln', 'lin', 'lin', 'lin', 'Lingala', 'Lingála'),
                                array('lo', 'lao', 'lao', 'lao', 'Lao', 'ພາສາລາວ'),
                                array('lt', 'lit', 'lit', 'lit', 'Lithuanian', 'lietuvių kalba'),
                                array('lu', 'lub', 'lub', 'lub', 'Luba-Katanga', 'Tshiluba'),
                                array('lv', 'lav', 'lav', 'lav', 'Latvian', 'latviešu valoda'),
                                array('gv', 'glv', 'glv', 'glv', 'Manx', 'Gaelg, Gailck'),
                                array('mk', 'mkd', 'mac', 'mkd', 'Macedonian', 'македонски јазик'),
                                array('mg', 'mlg', 'mlg', 'mlg', 'Malagasy', 'fiteny malagasy'),
                                array('ms', 'msa', 'may', 'msa', 'Malay', 'bahasa Melayu, بهاس ملايو‎'),
                                array('ml', 'mal', 'mal', 'mal', 'Malayalam', 'മലയാളം'),
                                array('mt', 'mlt', 'mlt', 'mlt', 'Maltese', 'Malti'),
                                array('mi', 'mri', 'mao', 'mri', 'Māori', 'te reo Māori'),
                                array('mr', 'mar', 'mar', 'mar', 'Marathi (Marāṭhī)', 'मराठी'),
                                array('mh', 'mah', 'mah', 'mah', 'Marshallese', 'Kajin M̧ajeļ'),
                                array('mn', 'mon', 'mon', 'mon', 'Mongolian', 'монгол'),
                                array('na', 'nau', 'nau', 'nau', 'Nauru', 'Ekakairũ Naoero'),
                                array('nv', 'nav', 'nav', 'nav', 'Navajo, Navaho', 'Diné bizaad'),
                                array('nd', 'nde', 'nde', 'nde', 'Northern Ndebele', 'isiNdebele'),
                                array('ne', 'nep', 'nep', 'nep', 'Nepali', 'नेपाली'),
                                array('ng', 'ndo', 'ndo', 'ndo', 'Ndonga', 'Owambo'),
                                array('nb', 'nob', 'nob', 'nob', 'Norwegian Bokmål', 'Norsk bokmål'),
                                array('nn', 'nno', 'nno', 'nno', 'Norwegian Nynorsk', 'Norsk nynorsk'),
                                array('no', 'nor', 'nor', 'nor', 'Norwegian', 'Norsk'),
                                array('ii', 'iii', 'iii', 'iii', 'Nuosu', 'ꆈꌠ꒿ Nuosuhxop'),
                                array('nr', 'nbl', 'nbl', 'nbl', 'Southern Ndebele', 'isiNdebele'),
                                array('oc', 'oci', 'oci', 'oci', 'Occitan', 'occitan, lenga d\'òc'),
                                array('oj', 'oji', 'oji', 'oji', 'Ojibwe, Ojibwa', 'ᐊᓂᔑᓈᐯᒧᐎᓐ'),
                                array('cu', 'chu', 'chu', 'chu', 'Old Church Slavonic, Church Slavonic, Old Bulgarian', 'ѩзыкъ словѣньскъ'),
                                array('om', 'orm', 'orm', 'orm', 'Oromo', 'Afaan Oromoo'),
                                array('or', 'ori', 'ori', 'ori', 'Oriya', 'ଓଡ଼ିଆ'),
                                array('os', 'oss', 'oss', 'oss', 'Ossetian, Ossetic', 'ирон æвзаг'),
                                array('pa', 'pan', 'pan', 'pan', 'Panjabi, Punjabi', 'ਪੰਜਾਬੀ, پنجابی‎'),
                                array('pi', 'pli', 'pli', 'pli', 'Pāli', 'पाऴि'),
                                array('fa', 'fas', 'per', 'fas', 'Persian (Farsi)', 'فارسی'),
                                array('pl', 'pol', 'pol', 'pol', 'Polish', 'język polski, polszczyzna'),
                                array('ps', 'pus', 'pus', 'pus', 'Pashto, Pushto', 'پښتو'),
                                array('pt', 'por', 'por', 'por', 'Portuguese', 'português'),
                                array('qu', 'que', 'que', 'que', 'Quechua', 'Runa Simi, Kichwa'),
                                array('rm', 'roh', 'roh', 'roh', 'Romansh', 'rumantsch grischun'),
                                array('rn', 'run', 'run', 'run', 'Kirundi', 'Ikirundi'),
                                array('ro', 'ron', 'rum', 'ron', 'Romanian', 'limba română'),
                                array('ru', 'rus', 'rus', 'rus', 'Russian', 'Русский'),
                                array('sa', 'san', 'san', 'san', 'Sanskrit (Saṁskṛta)', 'संस्कृतम्'),
                                array('sc', 'srd', 'srd', 'srd', 'Sardinian', 'sardu'),
                                array('sd', 'snd', 'snd', 'snd', 'Sindhi', 'सिन्धी, سنڌي، سندھی‎'),
                                array('se', 'sme', 'sme', 'sme', 'Northern Sami', 'Davvisámegiella'),
                                array('sm', 'smo', 'smo', 'smo', 'Samoan', 'gagana fa\'a Samoa'),
                                array('sg', 'sag', 'sag', 'sag', 'Sango', 'yângâ tî sängö'),
                                array('sr', 'srp', 'srp', 'srp', 'Serbian', 'српски језик'),
                                array('gd', 'gla', 'gla', 'gla', 'Scottish Gaelic, Gaelic', 'Gàidhlig'),
                                array('sn', 'sna', 'sna', 'sna', 'Shona', 'chiShona'),
                                array('si', 'sin', 'sin', 'sin', 'Sinhala, Sinhalese', 'සිංහල'),
                                array('sk', 'slk', 'slo', 'slk', 'Slovak', 'slovenčina, slovenský jazyk'),
                                array('sl', 'slv', 'slv', 'slv', 'Slovene', 'slovenski jezik, slovenščina'),
                                array('so', 'som', 'som', 'som', 'Somali', 'Soomaaliga, af Soomaali'),
                                array('st', 'sot', 'sot', 'sot', 'Southern Sotho', 'Sesotho'),
                                array('es', 'spa', 'spa', 'spa', 'Spanish', 'español'),
                                array('su', 'sun', 'sun', 'sun', 'Sundanese', 'Basa Sunda'),
                                array('sw', 'swa', 'swa', 'swa', 'Swahili', 'Kiswahili'),
                                array('ss', 'ssw', 'ssw', 'ssw', 'Swati', 'SiSwati'),
                                array('sv', 'swe', 'swe', 'swe', 'Swedish', 'svenska'),
                                array('ta', 'tam', 'tam', 'tam', 'Tamil', 'தமிழ்'),
                                array('te', 'tel', 'tel', 'tel', 'Telugu', 'తెలుగు'),
                                array('tg', 'tgk', 'tgk', 'tgk', 'Tajik', 'тоҷикӣ, toçikī, تاجیکی‎'),
                                array('th', 'tha', 'tha', 'tha', 'Thai', 'ไทย'),
                                array('ti', 'tir', 'tir', 'tir', 'Tigrinya', 'ትግርኛ'),
                                array('bo', 'bod', 'tib', 'bod', 'Tibetan Standard, Tibetan, Central', 'བོད་ཡིག'),
                                array('tk', 'tuk', 'tuk', 'tuk', 'Turkmen', 'Türkmen, Түркмен'),
                                array('tl', 'tgl', 'tgl', 'tgl', 'Tagalog', 'Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔'),
                                array('tn', 'tsn', 'tsn', 'tsn', 'Tswana', 'Setswana'),
                                array('to', 'ton', 'ton', 'ton', 'Tonga (Tonga Islands)', 'faka Tonga'),
                                array('tr', 'tur', 'tur', 'tur', 'Turkish', 'Türkçe'),
                                array('ts', 'tso', 'tso', 'tso', 'Tsonga', 'Xitsonga'),
                                array('tt', 'tat', 'tat', 'tat', 'Tatar', 'татар теле, tatar tele'),
                                array('tw', 'twi', 'twi', 'twi', 'Twi', 'Twi'),
                                array('ty', 'tah', 'tah', 'tah', 'Tahitian', 'Reo Tahiti'),
                                array('ug', 'uig', 'uig', 'uig', 'Uyghur', 'ئۇيغۇرچە‎, Uyghurche'),
                                array('uk', 'ukr', 'ukr', 'ukr', 'Ukrainian', 'українська мова'),
                                array('ur', 'urd', 'urd', 'urd', 'Urdu', 'اردو'),
                                array('uz', 'uzb', 'uzb', 'uzb', 'Uzbek', 'Oʻzbek, Ўзбек, أۇزبېك‎'),
                                array('ve', 'ven', 'ven', 'ven', 'Venda', 'Tshivenḓa'),
                                array('vi', 'vie', 'vie', 'vie', 'Vietnamese', 'Việt Nam'),
                                array('vo', 'vol', 'vol', 'vol', 'Volapük', 'Volapük'),
                                array('wa', 'wln', 'wln', 'wln', 'Walloon', 'walon'),
                                array('cy', 'cym', 'wel', 'cym', 'Welsh', 'Cymraeg'),
                                array('wo', 'wol', 'wol', 'wol', 'Wolof', 'Wollof'),
                                array('fy', 'fry', 'fry', 'fry', 'Western Frisian', 'Frysk'),
                                array('xh', 'xho', 'xho', 'xho', 'Xhosa', 'isiXhosa'),
                                array('yi', 'yid', 'yid', 'yid', 'Yiddish', 'ייִדיש'),
                                array('yo', 'yor', 'yor', 'yor', 'Yoruba', 'Yorùbá'),
                                array('za', 'zha', 'zha', 'zha', 'Zhuang, Chuang', 'Saɯ cueŋƅ, Saw cuengh'),
                                array('zu', 'zul', 'zul', 'zul', 'Zulu', 'isiZulu')
                            );
                            $languages_options = array_map(function($langauge){
                                return new ConditionUIOption($langauge[0],$langauge[4]);
                            },$languages);

                            $ret->$rule = new ConditionUIElement($rule,'','select',
                            false,$languages_options,false,'BrowserLanguage');
                        }
                        if($rule==='user-behavior-logged'){
                            $ret->$rule = new ConditionUIElement($rule,'Logged in'.$this->make_question_mark_link('https://www.if-so.com/help/documentation/logged-in/'),'select',
                                false, [new ConditionUIOption('logged-in', 'Yes'), new ConditionUIOption('logged-out', 'No')],false,'Logged');
                        }
                        if($rule==='user-behavior-returning'){
                            $ret->$rule = new ConditionUIElement($rule,'Show this content after:','select',
                            true, [new ConditionUIOption('first-visit', 'First visit'), new ConditionUIOption('second-visit', '2 Visits'),new ConditionUIOption('three-visit', '3 Vists')/*,new ConditionUIOption('custom', 'Custom')*/],false,'Returning');
                        }
                        if($rule==='user-behavior-retn-custom'){
                            $ret->$rule = new ConditionUIElement($rule,'Choose no. of visits','text',false,
                            false, null, 'custom');
                        }
                        break;
                    case 'Utm':
                        if ($rule === 'utm-type') {
                            $ret->$rule = new ConditionUIElement('utm-type', 'UTM Parameter'.$this->make_question_mark_link('https://www.if-so.com/help/documentation/utms/'), 'select',
                                true, [new ConditionUIOption('source','Source'), new ConditionUIOption('medium','Medium'), new ConditionUIOption('campaign','Campaign'), new ConditionUIOption('term','Term'), new ConditionUIOption('content','Content')]);
                        }

                        if ($rule === 'utm-relation') {
                            $ret->$rule = new ConditionUIElement('utm-relation', '', 'select',
                                true, [new ConditionUIOption('is', 'Is'), new ConditionUIOption('contains', 'Contains'), new ConditionUIOption('is-not', 'Is Not')]);
                        }

                        if ($rule === 'utm-value') {
                            $ret->$rule = new ConditionUIElement('utm-value', 'UTM Value', 'text', true);
                        }
                        break;
                    case 'Groups':
                        if ($rule === 'group-name') {
                            $groups_service = \IfSo\PublicFace\Services\GroupsService\GroupsService::get_instance();
                            $groups_list = $groups_service->get_groups();
                            $options_list = array_map(function ($groupName) {
                                return new ConditionUIOption($groupName);
                            }, $groups_list);
                            $ret->$rule = new ConditionUIElement('group-name', 'Audience Name', 'select', true, array_values($options_list));
                        }

                        if ($rule === 'user-group-relation') {
                            $ret->$rule = new ConditionUIElement('user-group-relation', 'Audience' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/segments/'), 'select', true, [new ConditionUIOption('in', 'Is'), new ConditionUIOption('out', 'Is Not')]);
                        }
                        break;
                    case 'userRoles':
                        if ($rule === 'user-role-relationship') {
                            $ret->$rule = new ConditionUIElement('user-role-relationship', 'User Role' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/user-role/'), 'select', true, [new ConditionUIOption('is', 'Is'), new ConditionUIOption('is-not', 'Is Not')]);
                        }

                        if ($rule === 'user-role') {
                            global $wp_roles;
                            $roles = $wp_roles->roles;
                            $roles_options = [];
                            //array_walk($roles,function($val,$key) use ($roles_options) {$roles_options[] = new ConditionUIOption($key,$val['name']);});
                            foreach ($roles as $key => $val) {
                                $roles_options[] = new ConditionUIOption($key, $val['name']);
                            }
                            $ret->$rule = new ConditionUIElement('user-role', '', 'select', false, $roles_options);
                        }
                        break;
                    case 'User-Details':
                        if ($rule === 'user-details-type') {
                            $ret->$rule = new ConditionUIElement('user-details-type', 'User Details'.$this->make_question_mark_link('https://www.if-so.com/help/documentation/days-post-registration-condition/'), 'select', true, [new ConditionUIOption('user-reg-before', 'Days Since User Registration')],true);
                        }

                        if($rule === 'user-reg-before'){
                            $ret->$rule = new ConditionUIElement('user-reg-before', 'Number of Days (type)', 'text', true, null ,false,'user-reg-before');
                        }


                        if ($rule === 'user-reg-before-relationship') {
                            $ret->$rule = new ConditionUIElement('user-reg-before-relationship', '', 'select', true, [new ConditionUIOption('>', 'Is More Than'), new ConditionUIOption('<', 'Is Less Than'), new ConditionUIOption('=', 'Is Exactly')],false,'user-reg-before');
                        }
                        break;
                    case 'TriggersVisited':
                        if ($rule === 'triggers-visited-id') {
                            $opts = array_map(function($trigger_post){
                                return new ConditionUIOption($trigger_post->ID,"{$trigger_post->post_title} (ID : {$trigger_post->ID})");
                            },get_posts(['post_type'=>'ifso_triggers','posts_per_page'=>-1]));
                            $ret->$rule = new ConditionUIElement('triggers-visited-id', 'Select a Trigger', 'select', true, $opts);
                        }

                        if($rule === 'triggers-visited-relationship'){
                            $ret->$rule = new ConditionUIElement('triggers-visited-relationship', 'Triggers Seen' . $this->make_question_mark_link('https://www.if-so.com/help/documentation/the-conditions/triggers-visited/'), 'select', true, [new ConditionUIOption('is','Is'),new ConditionUIOption('is-not','Is Not')]);
                        }
                        break;
                    case 'PostCategory':
                        if($rule==='post-category-operator'){
                            $ret->$rule = new ConditionUIElement($rule,'','select',true,[new ConditionUIOption('is','Is'),new ConditionUIOption('is-not','Is Not')]);
                        }

                        if($rule==='post-category-compare'){
                            $cats_opts = array_map(function($term){return new ConditionUIOption($term->term_taxonomy_id,"{$term->name} (Taxonomy: {$term->taxonomy} ; ID: {$term->term_taxonomy_id})");},array_values(get_terms(['orderby'=>'id'])));
                            $ret->$rule = new ConditionUIElement($rule,'','select',true,$cats_opts);
                        }
                        break;
                }
            }
            return $ret;
        }
        return false;
    }


    protected function get_condition_noticeboxes($condition){
        $settings_page_url = $this->get_links()['settings_page'];
        $conditions_noticeboxes = [
            'AB-Testing' =>[],
            'advertising-platforms'=>["noticebox"=>new ConditionNoticebox( "noticebox",__("Paste the following string into the \"tracking template\" field (in Google Ads):<br> <div class='ifso-url-template'>{lpurl}?ifso=<span class='value-text'>your-query-value</span></div>", 'if-so'),'#6D7882','transparent',false)],
            'Cookie'=>[],
            'Device'=>[],
            'url'=>["noticebox"=>new ConditionNoticebox("noticebox",__("Add the following string at the end of your page URL to display the content: <div class='ifso-url-template'>?ifso=<span class='value-text'>your-query-value</span></div>"), '#6D7882', 'transparent',false)],
            'UserIp'=>[],
            'Geolocation'=>[],
            'PageUrl'=>[],
            'PageVisit'=>["noticebox"=>new ConditionNoticebox("noticebox",__("The pages visited condition relies on a cookie to track the visitor's activity. <a target='_blank' href='{$settings_page_url}'>Click here</a> and uncheck the \"Deactivate Pages Visited Cookie\" option to use this condition.", 'if-so'),'#b44949','#f6d9de')],
            'referrer'=>[],
            //'Time-Date'=>[new ConditionNoticebox(__('This condition is based on the local time of your site', 'if-so') . ' ' . current_time('h:i A') . ((date_default_timezone_get()) ? ', ' . date_default_timezone_get() : ''),'#fff')],
            'User-Behavior'=>[ConditionNoticebox::make_multiSubgroup_array(__('Content will be displayed according to the user\'s total number of page views on the site. <a  href="https://www.if-so.com/help/documentation/new-and-returning-visitors?utm_source=Plugin&utm_medium=standalone&utm_campaign=inlineHelp" target="_blank">More options</a>', 'if-so'),'#5787f9','#fff',false,['NewUser','Returning'])],
            'Utm'=>[],
            'Groups'=>[],
            'userRoles'=>[],
        ];

        if(!(\IfSo\Services\PluginSettingsService\PluginSettingsService::get_instance()->removePageVisitsCookie->get())) unset($conditions_noticeboxes['PageVisit']['noticebox']);

        if(isset($conditions_noticeboxes[$condition]) && is_array($conditions_noticeboxes[$condition])){
            $ret = [];
            array_walk_recursive($conditions_noticeboxes[$condition], function($a,$key) use (&$ret,$condition) { $ret[$key] = $a; });       //flatten array
            return $ret;
        }

        return [];
    }

    protected function get_condition_name($condition){
        $conditions_names = [
            'AB-Testing' =>'A/B Testing',
            'advertising-platforms'=>'Advertising Platforms',
            'Cookie'=>'Cookie / Session Variable',
            'Device'=>'Device',
            'url'=>'Dynamic Link',
            'UserIp'=>'User IP',
            'Geolocation'=>'Geolocation',
            'PageUrl'=>'Page URL',
            'PageVisit'=>'Pages Visted',
            'referrer'=>'Referral Source',
            'Time-Date'=>'Date & Time',
            'User-Behavior'=>'User Behavior',
            'Utm'=>'UTM',
            'Groups'=>'Audiences',
            'userRoles'=>'User Role',
            'User-Details'=>'User Details',
            'TriggersVisited'=>'Triggers Seen',
            'PostCategory'=>'Post Category'
        ];

        if(isset($conditions_names[$condition])){
            return $conditions_names[$condition];
        }

        return '';
    }

    protected function get_condition_multibox_data($condition){
        $multibox_conditions = ['Geolocation','PageVisit'];
        if(in_array($condition,$multibox_conditions)){
            $ret = new \stdClass();
            if($condition==='Geolocation'){
                $ret->description = 'Targeted locations:';
            }
            if($condition==='PageVisit'){
                $pagesVisitedOption = \IfSo\Services\PluginSettingsService\PluginSettingsService::get_instance()->pagesVisitedOption->get();
                $pv_duration = $pagesVisitedOption->get_duration_value() . ' ' . $pagesVisitedOption->get_duration_type();
                $ret->description = 'This version will be displayed if the visitor has visited one of the following pages in the last <a href="' . admin_url( 'admin.php?page=' . EDD_IFSO_PLUGIN_SETTINGS_PAGE ) . "\" target=\"_blank\">{$pv_duration}&nbsp;<i class=\"fa fa-edit eicon-edit\"><!--icon--></i></a>.";
            }

            return $ret;
        }
        return null;
    }

    public function get_links(){
        $links = [
            'gropus_page'=>admin_url('admin.php?page=' .EDD_IFSO_PLUGIN_GROUPS_PAGE),
            'settings_page'=>admin_url('admin.php?page=' .EDD_IFSO_PLUGIN_SETTINGS_PAGE),
            'geo_page'=>admin_url('admin.php?page=' .EDD_IFSO_PLUGIN_GEO_PAGE),
            'license_page'=>admin_url('admin.php?page=' .EDD_IFSO_PLUGIN_LICENSE_PAGE),
        ];
        return $links;
    }

    public function make_question_mark_link($url,$hash=''){
        $hashstr = empty($hash) ? '' : "#{$hash}";
        $editor = (isset($_REQUEST['action']) && $_REQUEST['action']==='elementor') ? "Elementor" : 'Gutenberg';
        $html = "<a href=\"{$url}?utm_source=Plugin&utm_medium={$editor}&utm_campaign=inlineHelp{$hashstr}\" target=\"_blank\" class=\"ifso-question-mark\">?</a>";
        return $html;
    }

}

class MultiConditionBox extends ConditionUIElement{
    public $symbol;

    function __construct($name, $prettyName, $symbol, $required = false, $options = null, $subgroup = null){
        $type = 'multi';
        $is_switcher = false;
        $autocompleteOpts = false;
        $extraClasses = '';

        $this->symbol = $symbol;

        parent::__construct($name, $prettyName, $type, $required, $options, $is_switcher, $subgroup, $extraClasses, $autocompleteOpts);
    }
}

#[\AllowDynamicProperties]
class ConditionUIElement{
    public $name;
    public $prettyName;
    public $type;
    public $options;
    public $required;
    public $is_switcher;
    public $subgroup;
    public $autocompleteOpts;
    public $extraClasses;
    public $symbol;

    function __construct($name,$prettyName,$type,$required = false,$options =null,$is_switcher = false ,$subgroup = null,$extraClasses = '', $symbol=null, $autocompleteOpts=false){
        $this->name = $name;
        $this->prettyName = $prettyName;
        $this->type = $type;
        $this->options = $options;
        $this->required = $required;
        $this->is_switcher = $is_switcher;
        $this->subgroup = $subgroup;
        $this->autocompleteOpts = $autocompleteOpts;
        $this->symbol = $symbol;
        $this->extraClasses = $extraClasses;
    }
}

class ConditionUIOption{
    public $value;
    public $display_value;

    public function __construct($value, $display_value=null){
        $this->value = $value;
        if(null!==$display_value)
            $this->display_value = $display_value;
        else
            $this->display_value = $this->value;
    }
}

class ConditionNoticebox{
    public $name;
    public $content;
    public $color;
    public $bgcolor;
    public $closeable;
    public $subgroup;
    public $type = 'noticebox';

    public function __construct($name='noticebox', $content='', $color='#fff', $bgcolor='#697bf8', $closeable=true, $subgroup=null){
        $this->name = $name;
        $this->content = $content;
        $this->color = $color;
        $this->bgcolor = $bgcolor;
        $this->closeable = $closeable;
        $this->subgroup = $subgroup;
    }

    public static function make_multiSubgroup_array($content='', $color='#fff', $bgcolor='#697bf8', $closeable=true, $subgroups=null){
        $ret =[];
        foreach ($subgroups as $subgroup){
            $ret[$subgroup] = new self($subgroup, $content, $color, $bgcolor, $closeable, $subgroup);
        }
        return $ret;
    }
}
