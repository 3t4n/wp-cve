<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

class PostCategoryTrigger extends TriggerBase {
    public function __construct() {
        parent::__construct('PostCategory');
    }

    public function handle($trigger_data) {
        $rule = $trigger_data->get_rule();
        $content = $trigger_data->get_content();
        $request = $trigger_data->get_HTTP_request();
        $current_url = $request->getRequestURL();
        $operator = $rule['post-category-operator'];
        $trigger_cat = $rule['post-category-compare'];

        $postid = url_to_postid($current_url);
        if(0!==$postid){
            $post_tax = get_post_taxonomies($postid);
            $post_cats =  wp_get_post_terms($postid,$post_tax);
            $post_cat_ids = array_map(function($term){return $term->term_taxonomy_id;},$post_cats); // or term_taxonomy_id

            if($operator==='is' && in_array($trigger_cat,$post_cat_ids))
                return $content;
            if($operator==='is-not' && !in_array($trigger_cat,$post_cat_ids))
                return $content;
        }

        return false;
    }
}