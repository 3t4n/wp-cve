<?php

/**
 * Description of WizardPageController
 *
 * @author Ali2Woo Team
 * 
 * @autoload: a2wl_admin_init 
 */

namespace AliNext_Lite;;

class WizardPageController extends AbstractAdminPage {

    public function __construct() {
        parent::__construct(__('Wizard', 'ali2woo'), __('Wizard', 'ali2woo'), 'import', 'a2wl_wizard', 30, 2);
    }

    public function render($params = array()) {
        $errors = array();

        if (isset($_POST['wizard_form'])) {
            settings()->auto_commit(false);
    
            
            if (isset($_POST['a2wl_item_purchase_code']) && trim($_POST['a2wl_item_purchase_code'])){
                set_setting('item_purchase_code', isset($_POST['a2wl_item_purchase_code']) ? wp_unslash($_POST['a2wl_item_purchase_code']) : '');
            } else {
                $errors['a2wl_item_purchase_code'] = esc_html__('required field', 'ali2woo'); 
            }
            

            if (isset($_POST['a2w_import_language'])){
                set_setting('import_language', isset($_POST['a2w_import_language']) ? wp_unslash($_POST['a2w_import_language']) : 'en');
            }

            if (isset($_POST['a2w_local_currency'])){
                $currency = isset($_POST['a2w_local_currency']) ? wp_unslash($_POST['a2w_local_currency']) : 'USD';
                set_setting('local_currency', $currency);
                update_option( 'woocommerce_currency',  $currency );
            } 


            $a2wl_description_import_mode = isset($_POST['a2wl_description_import_mode']) ? $_POST['a2wl_description_import_mode'] :  "use_spec";
        
            set_setting('not_import_attributes', false);

            if ($a2wl_description_import_mode == "use_spec"){

                set_setting('not_import_description', true);
                set_setting('not_import_description_images', true);

            } else {
                set_setting('not_import_description', false);
                set_setting('not_import_description_images', false);    
            }

            //pricing rules setup

            $a2wl_pricing_rules = isset($_POST['a2wl_pricing_rules']) ? $_POST['a2wl_pricing_rules'] :  "low-ticket-fixed-3000";
            $a2wl_add_shipping_to_product =  isset($_POST['a2wl_add_shipping_to_product']);

            set_setting('pricing_rules_type', 'sale_price_as_base');
            set_setting('use_extended_price_markup', false);
            set_setting('use_compared_price_markup', false);
            set_setting('price_cents', -1);
            set_setting('price_compared_cents', -1);
            set_setting('default_formula', false);

            PriceFormula::deleteAll();

            if ($a2wl_pricing_rules == "low-ticket-fixed-3000"){

                $default_rule = array( 'value' => 3, 'sign' => '*', 'compared_value' => 1, 'compared_sign' => '*');
                PriceFormula::set_default_formula(new PriceFormula($default_rule));         

            }

            if ($a2wl_pricing_rules != "no" && $a2wl_add_shipping_to_product){
                set_setting('add_shipping_to_price', true);
                set_setting('apply_price_rules_after_shipping_cost', true);
            } else {
                set_setting('add_shipping_to_price', false);
                set_setting('apply_price_rules_after_shipping_cost', false);
            }

            //phrase rules setup        
            if (isset($_POST['a2wl_remove_unwanted_phrases'])){

                PhraseFilter::deleteAll();

                $phrases = array();
                $phrases[] = array('phrase'=>'China', 'phrase_replace'=>'');
                $phrases[] = array('phrase'=>'china', 'phrase_replace'=>'');
                $phrases[] = array('phrase'=>'Aliexpress', 'phrase_replace'=>'');
                $phrases[] = array('phrase'=>'AliExpress', 'phrase_replace'=>'');

                foreach ($phrases as $phrase) {
                    $filter = new PhraseFilter($phrase);
                    $filter->save();
                }
        
            }


            if (isset($_POST['a2wl_fulfillment_phone_code']) && trim($_POST['a2wl_fulfillment_phone_code']) 
                && isset($_POST['a2wl_fulfillment_phone_number']) && trim($_POST['a2wl_fulfillment_phone_number']))
            {
                set_setting('fulfillment_phone_code',  wp_unslash($_POST['a2wl_fulfillment_phone_code']));
                set_setting('fulfillment_phone_number', wp_unslash($_POST['a2wl_fulfillment_phone_number']));

            } else {
                $errors['a2wl_fulfillment_phone_block'] = esc_html__('required fields', 'ali2woo'); 
            }

            if (isset($_POST['a2wl_import_reviews'])){

                set_setting('load_review', true);
                set_setting('review_status', true);
                set_setting('review_translated', true);
                
                set_setting('review_min_per_product', 10);
                set_setting('review_max_per_product', 20);
                
                set_setting('review_raiting_from', 4);
                set_setting('review_raiting_to', 5);

                set_setting('review_thumb_width', 30);   

                set_setting('review_load_attributes', false);

                set_setting('review_show_image_list', true);

                set_setting('review_skip_keywords', '');

                set_setting('review_skip_empty', true);

                set_setting('review_country', array()); 

                set_setting('moderation_reviews', false);
        

            }

            settings()->commit();
            settings()->auto_commit(true);

            $redirect = add_query_arg( 'setup_wizard', 'success', admin_url('admin.php?page=a2wl_dashboard') );

            wp_redirect($redirect);

        }

        $localizator = AliexpressLocalizator::getInstance();

        $language_model = new Language();

        $description_import_modes = array(
            "use_spec" => esc_html_x('Use product specifications instead of description (recommended)', 'Wizard', 'ali2woo'), 
            "import_desc" => esc_html_x('Import description from AliExpress', 'Wizard', 'ali2woo'),
        );

        $pricing_rule_sets = array(
            "no" => esc_html_x('No, i will set up prices myself later', 'Wizard', 'ali2woo'), 
            "low-ticket-fixed-3000" => esc_html_x('Set 300% fixed markup (if you sell only low-ticket products only)', 'Wizard', 'ali2woo'), 
        );

        $close_link = admin_url( 'admin.php?page=a2wl_dashboard' );

        $this->model_put("currencies", $localizator->getCurrencies(false));
        $this->model_put("custom_currencies", $localizator->getCurrencies(true));
        $this->model_put("description_import_modes", $description_import_modes);
        $this->model_put("pricing_rule_sets", $pricing_rule_sets);
        $this->model_put("errors", $errors);
        $this->model_put("languages", $language_model->get_languages());
        $this->model_put("close_link", $close_link);

        $this->include_view("wizard.php");
    }
}
