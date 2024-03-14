<?php
namespace Wdr\App\Controllers\Admin\Tabs;

use Wdr\App\Controllers\Configuration;
use Wdr\App\Controllers\OnSaleShortCode;
use Wdr\App\Helpers\Migration;
use Wdr\App\Helpers\Rule;
use Wdr\App\Models\DBTable;

if (!defined('ABSPATH')) exit;

class DiscountRules extends Base
{
    public $priority = 10;
    protected $tab = 'rules';
    public static $available_rules = array();
    protected $page_limit = 20;

    /**
     * DiscountRules constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->title = __('Discount Rules', 'woo-discount-rules');
    }

    /**
     * Render rules listing page
     * @param null $page
     * @return mixed|void
     */
    function render($page = NULL)
    {
        $rule_helper = new Rule();
        $available_conditions = $this->getAvailableConditions();
        $params = array();
        //$params['configuration'] = new Configuration();
        $params['base'] = $this;
        $params['site_languages'] = $this->getAvailableLanguages();
        if (isset($page) && !empty($page)) {
            $id = $this->input->get('id', 0);
            $id = intval($id);
            if(is_int($id) && $id >= 0 ){} else {
                $id = 0;
            }
            $params['rule'] = $rule_helper->getRule($id, $available_conditions);
            $params['page'] = $page;
            $params['product_filters'] = $this->getProductFilterTypes();
            $params['on_sale_page_rebuild'] = OnSaleShortCode::getOnPageReBuildOption($id);
            $params['current_page'] = (int)$this->input->get('page_no', 1);
            self::$template_helper->setPath(WDR_PLUGIN_PATH . 'App/Views/Admin/Rules/Manage.php' )->setData($params)->display();
        } else {
            $params['has_migration'] = $this->isMigrationAvailable();
            if($params['has_migration']){
                $params['migration_rule_count'] =$this->getV1RuleCount();
            }
            $current_user = get_current_user_id();
            $default_filter = get_user_meta($current_user, 'awdr_filters', true);
            $set_limit = !empty($default_filter['limit']) ? $default_filter['limit'] : $this->page_limit;
            $default_limit = apply_filters('advanced_woo_discount_rules_pagination_limit', $this->input->get('limit', $set_limit));
            $limit = $default_limit == 'all' ? $default_limit : (int)$default_limit;
            $params['limit'] = !empty($limit) ? $limit :  $this->page_limit;
            $sort = !empty($default_filter['reorder']) ? $default_filter['reorder'] : 0 ;
            $params['name'] = stripslashes(sanitize_text_field($this->input->get('name', '')));
            $params['sort'] = (int)$this->input->get('re_order', $sort);
            $params['current_page'] = (int)$this->input->get('page_no', 1);
            if ($params['limit'] == 'all'){
                $offset = 0;
            } else {
                $offset = ( $params['current_page'] - 1 ) *  $params['limit'];
            }
            $data = $rule_helper->adminPagination($available_conditions, $params['limit'],$offset,$params['sort'],$params['name']);
            $params['rules'] = $params['rule_count'] = $params['total_count'] = array();
           if (!empty($data) && isset($data['result']) && isset($data['count'])){
               $params['rules'] = $data['result'];
               $params['rule_count'] = $data['count'];
               if ($params['limit'] != 'all' && is_numeric($params['limit']) && $params['limit'] >= 1){
                   $params['total_count'] = ceil($params['rule_count'] /  $params['limit']);
               if ($params['total_count'] < $params['current_page'] && $params['rule_count'] > 1){
                   $redirect_url = remove_query_arg('page_no');
                   wp_redirect($redirect_url);
                   exit();
               }
               }
           }
            $params['input'] = $this->input;
            self::$template_helper->setPath(WDR_PLUGIN_PATH . 'App/Views/Admin/Tabs/DiscountRule.php')->setData($params)->display();
        }
    }

    /**
     * Load welcome content
     * */
    protected function getV1RuleCount(){
        $migration = new Migration();
        $data['price_rules'] = $data['cart_rules'] = 0;
        $price_rules = $migration->getV1Rules('woo_discount', 1);
        $cart_rules = $migration->getV1Rules('woo_discount_cart', 1);
        if(!empty($price_rules)){
            $data['price_rules'] = count($price_rules);
        }
        if(!empty($cart_rules)){
            $data['cart_rules'] = count($cart_rules);
        }

        return $data;
    }

    /**
     * Load welcome content
     * */
    protected function isMigrationAvailable(){
        $migration = new Migration();
        $has_migration = $migration->getMigrationInfoOf('has_migration', null);
        if($has_migration){
            $skipped_migration = $migration->getMigrationInfoOf('skipped_migration', 0);
            $migration_completed = $migration->getMigrationInfoOf('migration_completed', 0);
            if($skipped_migration || $migration_completed){
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Get all available languages
     * @return mixed|void
     */
    function getAvailableLanguages()
    {
        $language_helper_object = self::$language_helper;
        $available_languages = $language_helper_object::getAvailableLanguages();
        $processed_languages = array();
        if (!empty($available_languages)) {
            foreach ($available_languages as $key => $lang) {
                $native_name = isset($lang['native_name']) ? $lang['native_name'] : NULL;
                $processed_languages[$key] = $native_name;
            }
        } else {
            $default_language = self::$language_helper->getDefaultLanguage();
            $processed_languages[$default_language] = self::$language_helper->getLanguageLabel($default_language);
        }
        return $processed_languages;
    }
}
