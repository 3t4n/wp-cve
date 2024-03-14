<?php
namespace IfSo\PublicFace\Helpers;

class WpDateTimeZone extends \DateTimeZone {
    /**
     * Determine time zone from WordPress options and return as object.
     *
     * @return static
     */
    public static function getWpTimezone() {
        $timezone_string = get_option( 'timezone_string' );
        if ( ! empty( $timezone_string ) ) {
            return new static( $timezone_string );
        }
        $offset  = get_option( 'gmt_offset' );
        $hours   = (int) $offset;
        $minutes = abs( ( $offset - (int) $offset ) * 60 );
        $offset  = sprintf( '%+03d:%02d', $hours, $minutes );
        return new static( $offset );
    }
}

class CookieConsent{
    private static $instance;
    private $cookie_types = [
        'necessary'=>[],
        'statistics'=>['ifso_last_viewed','ifso_viewing_triggers'],
        'marketing'=>[],
        'preferences'=>['ifso_page_visits','ifso_recurrence_data','ifso_visit_counts','ifsoGroup','ifso_geo_data','ifso_viewed_triggers','ifso_group_name']
    ];
    private $permissions_cache = [];
    private $cookie_consent_manager_type = null;

    private function __construct(){
        $this->set_consent_manager_type();
    }

    public static function get_instance(){
        if (NULL == self::$instance)
            self::$instance = new CookieConsent();

        return self::$instance;
    }

    private function set_consent_manager_type(){
        if (isset($_COOKIE["CookieConsent"]))
            $this->cookie_consent_manager_type = 'cookiebot';
        elseif(isset($_COOKIE["hu-consent"]))
            $this->cookie_consent_manager_type = 'hu-compliance';
        elseif(function_exists('cmplz_has_consent'))
            $this->cookie_consent_manager_type ='complianz';
        else
            $this->cookie_consent_manager_type = apply_filters('ifso_cookie_consent_manager_type',false);
    }

    private function cache_cookie_permission($category,$allow){
        $this->permissions_cache[$category] = $allow;
    }

    private function get_cached_cookie_permission($category){
        if(isset($this->permissions_cache[$category]))
            return $this->permissions_cache[$category];
        return null;
    }

    private function get_object_from_cookie($cname){
        //The user has given their consent - Read current user consent in encoded JavaScript format
        $valid_php_json = preg_replace('/\s*:\s*([a-zA-Z0-9_]+?)([}\[,])/', ':"$1"$2', preg_replace('/([{\[,])\s*([a-zA-Z0-9_]+?):/', '$1"$2":', str_replace("'", '"',stripslashes($_COOKIE[$cname]))));
        $CookieConsent = json_decode($valid_php_json);
        return $CookieConsent;
    }

    private function cookiebot_is_category_allowed($category){
        if($_COOKIE["CookieConsent"]==='-1') //The user is not within a region that requires consent - all cookies are accepted
            return true;

        $CookieConsent = $this->get_object_from_cookie('CookieConsent');

        if (!filter_var($CookieConsent->preferences, FILTER_VALIDATE_BOOLEAN) && !filter_var($CookieConsent->statistics, FILTER_VALIDATE_BOOLEAN) && !filter_var($CookieConsent->marketing, FILTER_VALIDATE_BOOLEAN)) {
            //The user has opted out of cookies, set strictly necessary cookies only
            return ($category==='necessary');
        }
        else {
            switch ($category){
                case 'preferences':
                    return filter_var($CookieConsent->preferences, FILTER_VALIDATE_BOOLEAN);
                case 'statistics':
                    return filter_var($CookieConsent->statistics, FILTER_VALIDATE_BOOLEAN);
                case 'marketing':
                    return filter_var($CookieConsent->marketing, FILTER_VALIDATE_BOOLEAN);
                default:
                    return false;
            }
        }
    }

    private function hu_compliance_is_category_allowed($category){
        $CookieConsent = $this->get_object_from_cookie('hu-consent');
        $category_matcher = ['necessary'=>"1",'statistics'=>"2",'preferences'=>"3",'marketing'=>"4"];
        $matched_category = $category_matcher[$category];
        if(!empty($CookieConsent) && !empty($matched_category)){
            return ($CookieConsent->categories->$matched_category === 'true');
        }
    }

    private function complianz_is_category_allowed($category){
        if(cmplz_consent_mode())
            return cmplz_has_consent($category);
        return true;
    }


    private function get_cookie_type($cname){
        $cookie_type = false;
        foreach($this->cookie_types as $type=>$cookies){
            if(in_array($cname,$cookies))
                $cookie_type = $type;
        }
        return $cookie_type;
    }

    private function is_cookie_allowed($cname,$ctype=null){
        $ret = true;
        if($this->cookie_consent_manager_type!==false){
            $cookie_type = ($ctype===null) ? $this->get_cookie_type($cname) : $ctype;
            $cached_val = $this->get_cached_cookie_permission($cookie_type);
            if($cached_val!==null)
                return $cached_val;

            if($this->cookie_consent_manager_type === 'cookiebot')
                $ret = $this->cookiebot_is_category_allowed($cookie_type);
            elseif($this->cookie_consent_manager_type === 'complianz')
                $ret = $this->complianz_is_category_allowed($cookie_type);
            elseif($this->cookie_consent_manager_type === 'hu-compliance')
                $ret = $this->hu_compliance_is_category_allowed($cookie_type);
            elseif($this->cookie_consent_manager_type==='custom')
                $ret = apply_filters('ifso_is_cookie_category_allowed',$cookie_type);

            $this->cache_cookie_permission($cookie_type,$ret);
        }
        return $ret;
    }

    public function set_cookie($name,$value='',$expires=0,$path='/',$type=null){
        if($this->is_cookie_allowed($name,$type))
            setcookie($name,$value,$expires,$path);
        elseif(isset($_COOKIE[$name]))  //Delete it if its not allowed but still exists
            setcookie($name,'',1,'/');
    }
}