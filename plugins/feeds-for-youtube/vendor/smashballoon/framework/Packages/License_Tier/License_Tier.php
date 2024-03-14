<?php

/**
 * License Tier class.
 *
 * @package License_Tier
 */
namespace SmashBalloon\YoutubeFeed\Vendor\Smashballoon\Framework\Packages\License_Tier;

use function SmashBalloon\YoutubeFeed\Vendor\Smashballoon\Framework\flatten_array;
abstract class License_Tier
{
    /**
     * This gets the license key 
     * 
     * @var string
     */
    public $license_key_option_name;
    /**
     * This gets the license status
     * 
     * @var string
     */
    public $license_status_option_name;
    /**
     * This gets the license data
     * 
     * @var string
     */
    public $license_data_option_name;
    /**
     * Item ID of the basic license tier
     * 
     * @var int
     */
    public $item_id_basic;
    /**
     * Item ID of the plus license tier
     * 
     * @var int
     */
    public $item_id_plus;
    /**
     * Item ID of the elite license tier
     * 
     * @var int
     */
    public $item_id_elite;
    /**
     * Item ID of the all access license tier
     * 
     * @var int
     */
    public $item_id_all_access;
    /**
     * Is all access
     * 
     * @var int
     */
    public $is_all_access;
    /**
     * Name of the basic license tier
     * 
     * @var string
     */
    public $license_tier_basic_name;
    /**
     * Name of the plus license tier
     * 
     * @var string
     */
    public $license_tier_plus_name;
    /**
     * Name of the elite license tier
     * 
     * @var string
     */
    public $license_tier_elite_name;
    /**
     * This holds the license data
     * 
     * @var array
     */
    public $license_data = array();
    /**
     * This holds the license data
     * 
     * @var array
     */
    protected $plugin_features = array();
    public function __construct()
    {
        $this->features_list();
        $this->license_data();
    }
    /**
     * This defines the features list of the plugin
     * 
     * @return void
     */
    public abstract function features_list();
    /**
     * Get license data
     */
    public function license_data()
    {
        $this->license_data = (array) get_option($this->license_data_option_name);
    }
    /**
     * This gets the license tier plan name in a readable format
     * 
     * @return string|null
     */
    public function get_license_tier()
    {
        $license_data = $this->license_data;
        if (\is_array($license_data) && isset($license_data['item_id'])) {
            return $this->convert_to_readable_plan_name($license_data);
        }
    }
    /**
     * Returns list of available features for a given plan
     * 
     * @return array $tier_features returns tier features or empty array
     */
    public function tier_features()
    {
        $all_features = $this->plugin_features;
        $plan_name = $this->get_license_tier();
        $tier_features = [];
        if ($plan_name == $this->license_tier_basic_name) {
            $tier_features = isset($all_features[$plan_name]) ? $all_features[$plan_name] : [];
        }
        if ($plan_name == $this->license_tier_plus_name) {
            $tier_features = isset($all_features[$this->license_tier_basic_name]) && isset($all_features[$plan_name]) ? \array_merge($all_features[$this->license_tier_basic_name], $all_features[$plan_name]) : [];
        }
        if ($plan_name == $this->license_tier_elite_name || $this->is_all_access) {
            $tier_features = flatten_array($all_features);
        }
        return $tier_features;
    }
    /**
     * This is a helpful function to look for any specific feature on a given plan
     * 
     * @params string $feature_name Expects a feature name as a string
     * @params string $plan_name Expects a given pricing plan as a string
     * 
     * @return boolean $plan_exists returns boolean value
     */
    public function has_feature($feature_name, $plan_name)
    {
        $features_list = $this->{$plugin_features};
        $plan_exists = \false;
        if ($plan_name == $this->license_tier_basic_name) {
            $plan_exists = \in_array($feature_name, $features_list[$this->license_tier_basic_name]);
        }
        if ($plan_name == $this->license_tier_plus_name) {
            $plan_exists = \in_array($feature_name, $features_list[$this->license_tier_basic_name]) || \in_array($feature_name, $features_list[$this->license_tier_plus_name]);
        }
        if ($plan_name == $this->license_tier_elite_name) {
            $plan_exists = \in_array($feature_name, $features_list[$this->license_tier_basic_name]) || \in_array($feature_name, $features_list[$this->license_tier_plus_name]) || \in_array($feature_name, $features_list[$this->license_tier_elite_name]);
        }
        return $plan_exists;
    }
    /**
     * This converts plan price id to a readable plan name 
     * 
     * @params $tier array gets the price id in integer type
     * 
     * @return $plan string
     */
    public function convert_to_readable_plan_name(array $license_data)
    {
        $plan = '';
        $tier = (int) $license_data['item_id'];
        if ($tier === $this->item_id_basic) {
            $plan = $this->license_tier_basic_name;
        }
        if ($tier === $this->item_id_plus) {
            $plan = $this->license_tier_plus_name;
        }
        if ($tier === $this->item_id_elite) {
            $plan = $this->license_tier_elite_name;
        }
        if (isset($license_data['price_id'])) {
            $price_id = (int) $license_data['price_id'];
            if ($tier === $this->item_id_basic && $price_id === 1) {
                $plan = 'all_access';
                $this->is_all_access = \true;
            }
        }
        return $plan;
    }
}
