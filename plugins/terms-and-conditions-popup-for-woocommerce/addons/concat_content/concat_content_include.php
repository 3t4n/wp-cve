<?php
class BeRocket_terms_POPUPfilter_concat_content {
    function __construct() {
        add_filter('berocket_terms_cond_pages_contents', array($this, 'POPUPfilter_concat_content'), 12);
    }
    function POPUPfilter_concat_content($pages_data) {
        if( ! empty($pages_data['term_cond_page']) && ! empty($pages_data['policy_page'])
        && ! empty($pages_data['term_cond_page']['content']) && ! empty($pages_data['policy_page']['content']) ) {
            $concat_content = array(
                'content'  => array(
                    'term_cond_page' => $pages_data['term_cond_page']['content'],
                    'policy_page'    => $pages_data['policy_page']['content']
                ),
                'selector' => array(
                    'term_cond_page' => $pages_data['term_cond_page']['popup_open']['click']['selector'],
                    'policy_page'    => $pages_data['policy_page']['popup_open']['click']['selector']
                ),
                'title'    => $pages_data['term_cond_page']['title']
            );
            $concat_content = apply_filters('BRTAC_POPUPfilter_concat_content', $concat_content, $pages_data);
            $pages_data['term_cond_page']['content'] = implode($concat_content['content']);
            $pages_data['term_cond_page']['popup_open']['click']['selector'] = implode(',', $concat_content['selector']);
            $pages_data['term_cond_page']['title'] = $concat_content['title'];
            $pages_data['term_cond_page']['popup_options']['title'] = $concat_content['title'];
            unset($pages_data['policy_page']);
        }
        return $pages_data;
    }
}
new BeRocket_terms_POPUPfilter_concat_content();
