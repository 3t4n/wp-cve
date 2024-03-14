<?php 

namespace VENDOR\RECAPTCHA_GDPR_COMPLIANT;

defined( 'ABSPATH' ) or die( 'Are you ok?' );

/**
 * Class Option: Each instance of that class is intended to hold an option for the plugin
 * 
 */
class Option
{
    /** @var string */
    const PREFIX = 'gdpr_pow_';

    /** @var int */
    const INT = 1;

    /** @var int */
    const STRING = 2;

    /** @var int */
    const BOOL = 3;

    /** @var int */
    const TEXT = 4;

    /** @var RoleDropDown */
    const RoleDropDown = 5;

    /** @var string */
    const PAGE_QUERY = '?page=' . self::PREFIX . 'options';

    /** @var string */
    const PAGE_QUERY_MESSAGES = '?page=' . self::PREFIX . 'messages';

    /** @var string */
    const PAGE_QUERY_SPAM = '?page=' . self::PREFIX . 'spam';

    /** @var string */
    const PAGE_QUERY_TRASH = '?page=' . self::PREFIX . 'trash';
    
    /** @var string */
    const PAGE_QUERY_ANALYSIS = '?page=' . self::PREFIX . 'analyse';

    /** @var boolean */
    const POW_OPTIONS = self::PREFIX . 'pow_options';

    /** @var boolean */
    const POW_INSTALLED = self::PREFIX . 'pow_installed';

    /** @var String */
    const POW_VERSION = self::PREFIX . 'pow_version';

    /** @var string */
    const POW_SALT = self::PREFIX . 'pow_salt';

    /** @var string */
    const POW_DIFFICULTY = self::PREFIX . 'pow_difficulty';

    /** @var string */
    const POW_TIME_WINDOW = self::PREFIX . 'pow_time_window';

    /** @var bool */
    const POW_BLOCK_LOGIN = self::PREFIX . 'pow_block_login';

    /** @var bool */
    const POW_BLOCK = self::PREFIX . 'pow_block';

    /** @var bool */
    const POW_SAVE_SPAM = self::PREFIX . 'pow_save_spam';

    /** @var bool */
    const POW_SAVE_CLEAN = self::PREFIX . 'pow_save_clean';

    /** @var bool */
    const POW_FLAG_SPAM = self::PREFIX . 'pow_flag_spam';

    /** @var bool */
    const POW_FLAG_SAVE = self::PREFIX . 'pow_flag_save';

    /** @var string */
    const POW_FLAG_SUFFIXES = self::PREFIX . 'pow_flag_suffixes';

    /** @var string */
    const POW_FLAG_TAGS = self::PREFIX . 'pow_flag_tags';

    /** @var bool */
    const POW_SIMULATE_SPAM = self::PREFIX . 'pow_simulate_spam';

    /** @var string */
    const POW_MESSAGE_HEADS = self::PREFIX . 'pow_message_heads';

    /** @var int */
    const POW_MENU_POSITION = self::PREFIX . 'pow_menu_position';

    /** @var bool */
    const POW_DASHBOARD = self::PREFIX . 'pow_dashboard';

    /** @var string */
    const POW_IP_WHITELIST = self::PREFIX . 'pow_ip_whitelist';

    /** @var string */
    const POW_SITE_WHITELIST = self::PREFIX . 'pow_site_whitelist';

    /** @var bool */
    const POW_APPLY_REST = self::PREFIX . 'pow_apply_rest';

    /** @var bool */
    const POW_SAVE_CART = self::PREFIX . 'pow_save_cart';
    
    /** @var bool */
    const POW_EXPLICIT_MODE = self::PREFIX . 'pow_explicit_mode';

    /** @var string */
    const POW_EXPLICIT_ACTION = self::PREFIX . 'pow_explicit_action';

    /** @var bool */
    const POW_ACTION_WHITELIST = self::PREFIX . 'pow_action_whitelist';

    /** @var int */
    const POW_CRON_DELETE_INBOX = self::PREFIX . 'pow_cron_delete_inbox';

    /** @var int */
    const POW_CRON_DELETE_SPAM = self::PREFIX . 'pow_cron_delete_spam';

    /** @var int */
    const POW_CRON_DELETE_TRASH = self::PREFIX . 'pow_cron_delete_trash';

    /** @var Text */
    const POW_ERROR_MESSAGE = self::PREFIX . 'pow_error_message';
    
    /** @var Bool */
    const POW_ANALYSIS_MODE = self::PREFIX . 'pow_analysis_mode';

    /** @var Bool */
    const POW_DIRECT_ANALYSIS_MODE = self::PREFIX . 'pow_direct_analysis_mode';

    /** @var Text */
    const POW_PARAMETER_PATTERN = self::PREFIX . 'pow_parameter_pattern';

    /** @var Text */
    const POW_HIDE_ACTION = self::PREFIX . 'pow_hide_action';

    /** @var Text */
    const POW_HIDE_PATTERN = self::PREFIX . 'pow_hide_pattern';

    /** @var Bool */
    const POW_SAVE_IP = self::PREFIX . 'pow_save_ip';

    /** @var string */
    const HASH = self::PREFIX . 'hash';

    /** @var string */
    private $name;

    /** @var int */
    private $type;

    /** @var int */
    private $default;

    /** @var int */
    private $hint;

    /** @var string|int */
    private $value = '';

    /** @var string|int */
    private $symbol = '';

    /** @var string|int */
    private $group = '';

    /**
     * @param string $name
     * @param int $type
     */
    public function __construct( $name, $type, $default, $hint, $group, $symbol = '' )
    {
        $this->name = $name;
        $this->type = $type;
        $this->default = $default;
        $this->value = $default;
        $this->hint = $hint;
        $this->group = $group;
        $this->symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * @return int|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int|string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return int|string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**Get all messages */
    //public static function get_rows( $search, $messageType, $today = false ){
    public static function get_rows( $search, $messageType, $today = false, $hidden_actions = [ '-' ], $existing_actions = [ '-' ], $whitelisted_actions = [ '-' ], $existing_patterns = [], $hidden_patterns = [] ){
        global $wpdb;
        $hidden_actions_placeholders = implode( ', ', array_fill( 0, count( $hidden_actions ), '%s' ) );
        $existing_actions_placeholders = implode( ', ', array_fill( 0, count( $existing_actions ), '%s' ) );
        $whitelisted_actions_placeholders = implode( ', ', array_fill( 0, count( $whitelisted_actions ), '%s' ) );
        $parameters = array_merge(
            [ $messageType ], 
            $hidden_actions,
            $existing_actions,
            $whitelisted_actions,
            [ $search, $search ]
        );
        
        $sqlArray = [];
        //For each pattern build a sub-seelect to check whether the conditions match
        foreach ( $existing_patterns as $pattern ) {
            $pattern = Option::generate_paths( json_decode( $pattern, true ), '' );
            $conditions = array();
            foreach ( $pattern as $paramPath => $value ) {
                if ( $value === null ) {
                    $conditions[] = "(rgd.rgd_attribute LIKE '{$paramPath}')";
                } else {
                    $conditions[] = "(rgd.rgd_attribute LIKE '{$paramPath}' AND rgd.rgd_value = '{$value}')";
                }
            }

            $sqlArray[] = " AND rgd.rgm_id NOT IN (
                    SELECT rgd.rgm_id
                    FROM " . $wpdb->prefix . "recaptcha_gdpr_details_rgd rgd
                    WHERE " . implode(' OR ', $conditions) . "
                    GROUP BY rgd.rgm_id
                    HAVING COUNT(DISTINCT rgd.rgd_attribute) = " . count( $pattern ) . "
                )"
            ;
        }
        $hiddenSqlArray = [];
        //For each pattern build a sub-seelect to check whether the conditions match
        foreach ( $hidden_patterns as $pattern ) {
            $pattern = Option::generate_paths( json_decode( $pattern, true ), '' );
            $conditions = array();
            foreach ( $pattern as $paramPath => $value ) {
                if ( $value === null ) {
                    $conditions[] = "(rgd.rgd_attribute LIKE '{$paramPath}')";
                } else {
                    $conditions[] = "(rgd.rgd_attribute LIKE '{$paramPath}' AND rgd.rgd_value = '{$value}')";
                }
            }

            $hiddenSqlArray[] = " AND rgd.rgm_id NOT IN (
                    SELECT rgd.rgm_id
                    FROM " . $wpdb->prefix . "recaptcha_gdpr_details_rgd rgd
                    WHERE " . implode(' OR ', $conditions) . "
                    GROUP BY rgd.rgm_id
                    HAVING COUNT(DISTINCT rgd.rgd_attribute) = " . count( $pattern ) . "
                )"
            ;
        }
        
        $filter_today = '';
        if ($today) $filter_today = ' AND DATE(rgm.rgm_date) = CURDATE() ';
        //if ( $search ){
        $rows = $wpdb->get_results(
            $wpdb->prepare("
                    SELECT COUNT(*) as count
                    FROM(
                        SELECT DISTINCT rgm.rgm_id
                        FROM " . $wpdb->prefix . "recaptcha_gdpr_message_rgm rgm
                        JOIN " . $wpdb->prefix . "recaptcha_gdpr_details_rgd rgd
                        ON rgm.rgm_id = rgd.rgm_id
                        WHERE rgm.rgm_type = %s
                        AND COALESCE(rgm.rgm_action, '') NOT IN ($hidden_actions_placeholders)
                        AND COALESCE(rgm.rgm_action, '') NOT IN ($existing_actions_placeholders)
                        AND COALESCE(rgm.rgm_action, '') NOT IN ($whitelisted_actions_placeholders)
                        AND ( rgd.rgd_attribute LIKE CONCAT('%',%s,'%')
                            OR rgd.rgd_value LIKE CONCAT('%',%s,'%')
                            )
                        " . implode( '', $sqlArray ) .  implode( '', $hiddenSqlArray ) . "
                        $filter_today
                    ) counter
                ", $parameters
            )
        );
        /*} else {
            $rows = $wpdb->get_results(
                $wpdb->prepare("
                        SELECT COUNT(rgm.rgm_id) as count
                        FROM " . $wpdb->prefix . "recaptcha_gdpr_message_rgm rgm
                        WHERE rgm.rgm_type = %s
                        AND rgm.rgm_action NOT IN ($hidden_actions_placeholders)
                        AND rgm.rgm_action NOT IN ($existing_actions_placeholders)
                        AND rgm.rgm_action NOT IN ($whitelisted_actions_placeholders)
                        " . implode( '', $sqlArray ) .  implode( '', $hiddenSqlArray ) . "
                        $filter_today
                    ", $parameters
                )
            );
        }*/
        $count = 0;
        foreach ( $rows as $row ) {
            $count = $row->count;
        }
        
        return $count;
    }

    /**Compare whether a JSON obj1 is completely inherited in a JSON object 2 */
    public static function compareJSONObjects( $obj1, $obj2, $ignoreNull = false ) {
        if( $obj1 && count( $obj1 ) ){
            foreach ( $obj1 as $key => $value ) {
                if ( isset( $obj2[ $key ] ) ) {
                    if ( $value && ( is_array( $value ) ) && is_array( $obj2[ $key ] ) ) {
                        if ( ! self::compareJSONObjects( $value, $obj2[ $key ], $ignoreNull ) ) {
                            return false;
                        }
                    } else {
                        if ( ! ( $ignoreNull && ( $value === null ) ) ) {
                            if ( $value !== $obj2[ $key ] ) {
                                return false;
                            }
                        }
                    }
                } else {
                    return false;
                }
            }
            return true;
        }else{
            return false;
        }
    }

    /**Converts an array of nested Attribute names into a JSON-object */
    public static function convertToJsonObject( $mysqlResult, $attributeName, $valueName ) {
        $jsonObject = [];
        foreach ( $mysqlResult as $row ) {
            $keys = explode( '->', $row->{ $attributeName } );
            $currentObject = &$jsonObject;
    
            foreach ( $keys as $key ) {
                // If the key does not exist, create an empty array or object
                if ( !isset( $currentObject[ $key ] ) ) {
                    $currentObject[ $key ] = [];
                }
    
                // Move to the next level of the JSON object
                $currentObject = &$currentObject[ $key ];
            }
    
            // Assign the value to the lowest level of the nested attribute
            $currentObject = $row->{ $valueName };
        }
    
        return $jsonObject;
    }

    /** Transforms a nested object into a string-representation */
    public static function generate_paths( $data, $current_path ) {
        $values = [];
    
        foreach ($data as $key => $value) {
            $path = $current_path . ( $current_path ? "->" : "" ) . $key;
    
            if ( is_array( $value ) || is_object( $value ) ) {
                // Recurse into nested arrays/objects
                $nested_values = self::generate_paths( $value, $path );
                // Merge the nested values with the current values array
                $values = array_merge( $values, $nested_values );
            } else {
                // Add the path and the corresponding value to the values array as an associative pair
                $values[ $path ] = $value;
            }
        }
    
        return $values;
    }

    public static function hash_Values( $x ) {
        return hash( 'sha256', $x, false );
    }

}
