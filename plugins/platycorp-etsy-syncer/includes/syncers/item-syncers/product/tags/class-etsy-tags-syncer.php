<?php
namespace platy\etsy;

class EtsyTagsSyncer {
    const BAD_TAGS_FORMAT_REGEX = "/[^\p{L}\p{Nd}\p{Zs}\-'™©®]/u";
    const MAX_TAG_LENGTH = 30;

    private $product_id;
    function __construct($pid = 0) {
        $this->product_id = $pid;
    }

    private function verify_product_tag($tag) {
        if(\preg_match(self::BAD_TAGS_FORMAT_REGEX, $tag)){
            throw new BadTagFormatException($tag);
        }
        if(strlen($tag) > self::MAX_TAG_LENGTH) {
            throw new TagTooLongException($tag);
        }
    }

    public function verify_tags($tags) {
        foreach($tags as $tag) {
            $this->verify_product_tag($tag);
        }
    }

    /**
     * includes error tags
     *
     * @param [type] $def usually the template tags
     * @return tags
     */
    public function get_tags($def, $ignore_errors = false){
        $terms = wp_get_post_terms($this->product_id, 'product_tag' );
        $tags = array();
        foreach($terms as $term){
            $tags[] = $term->name;
        }

        $tags_from_options = explode(",", $def);
        $tags = array_merge($tags,$tags_from_options);

        $tags = array_filter($tags, function($value) { return $value !== ''; });
        $tags = array_map('trim', $tags);

        if($ignore_errors) {
            $tags = $this->filter_errors($tags);
        }

        return implode(",",array_slice(array_unique($tags), 0, 13));
        
    }

    private function filter_errors($tags) {
        $filtered_tags = [];

        foreach($tags as $tag) {
            try {
                $this->verify_product_tag($tag);
                $filtered_tags[] = $tag;
            }catch(EtsySyncerException $e) {

            }
        }
        return $filtered_tags;
    }
}