<?php
$link_external = false;
$phrases = (!empty($phrase_groups) && isset($phrase_groups['internal_site'])) ? $phrase_groups['internal_site']: false; 
$taxonomies = get_taxonomies(array('public' => true, 'show_ui' => true), 'names', 'or');
$taxonomies = (!empty($taxonomies)) ? array_keys($taxonomies): array();
?>
<div style="display:none"><div><textarea id="wpil-editor-target"></textarea></div></div>
<table class="wp-list-table widefat fixed striped posts tbl_keywords_x js-table wpil-outbound-links" id="tbl_keywords">
    <?php if (!empty($phrases)) : ?>
        <?php $has_suggestions = true; ?>
        <thead>
            <tr>
                <th>
                    <b><?php _e('Phrases In This Post To Link From', 'wpil'); ?></b>
                </th>
                <th style="width: 50px"></th>
                <th><b><?php _e('Suggested Posts To Link To', 'wpil'); ?></b></th>
                <?php if (!empty($show_date)) : ?>
                    <th><b><?php _e('Date Published', 'wpil'); ?></b></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody id="the-list">
        <?php foreach ($phrases as $key_phrase => $phrase) : ?>
            <tr data-wpil-sentence-id="<?=esc_attr($key_phrase)?>">
                <td class="sentences">
                    <?php foreach ($phrase->suggestions as $suggestion) : ?>
                        <div class="sentence" data-id="<?=esc_attr($suggestion->post->id)?>" data-type="<?=esc_attr($suggestion->post->type)?>">
                            <?=$suggestion->sentence_with_anchor?>
                            <?=!empty(Wpil_Suggestion::$undeletable)?' ('.esc_attr($suggestion->anchor_score).')':''?>
                            <input type="hidden" name="sentence" value="<?=base64_encode(rawurlencode($phrase->sentence_src))?>">
                            <input type="hidden" name="custom_sentence" value="">
                        </div>
                    <?php endforeach; ?>
                </td>
                <td style="width: 50px">
                    <a title="Copy link" href="javascript:void(0)" data-link="<?php echo esc_attr($phrase->suggestions[0]->post->getLinks()->view); ?>" class="link_copy wpil_link_copy_button"><img src="<?php echo esc_url(trailingslashit(WP_INTERNAL_LINKING_PLUGIN_URL) . 'images/icon_copy.png'); ?>"></a>
                </td>
                <td class="suggestions">
                    <?php if (count($phrase->suggestions) > 1) : ?>
                        <?php 
                            $index = key($phrase->suggestions);
                            $a_post = $phrase->suggestions[$index]->post;

                            if(null === $index){
                                reset($phrase->suggestions);
                                $index = key($phrase->suggestions);
                            }

                            if(empty($a_post)){
                                continue;
                            }
                            $post_published_date = get_the_date('', $a_post->id); 
                            $terms = get_terms(array(
                                'taxonomy' => $taxonomies,
                                'hide_empty' => false,
                                'object_ids' => $a_post->id,
                            ));

                            $categories = array();
                            $tags = array();
                            if(!is_wp_error($terms) && !empty($terms)){
                                foreach($terms as $term){
                                    if(get_taxonomy($term->taxonomy)->hierarchical){
                                        $categories[] = $term->name;
                                    }else{
                                        $tags[] = $term->name;
                                    }
                                }

                                $cats_found = count($categories);
                                $tags_found = count($tags);
                                $categories = implode(', ', $categories);
                                $tags = implode(', ', $tags);
                            }

                            $suggestion_datas = array(
                                'data-id="' . esc_attr($a_post->id) . '"',
                                'data-type="' . esc_attr($a_post->type) . '"',
                                'data-post-origin="' . ((!isset($a_post->site_url)) ? 'internal': 'external') . '"',
                                'data-site-url="' . ((isset($a_post->site_url)) ? esc_url($a_post->site_url): '') . '"',

                                'data-wpil-post-published-date="' . strtotime(get_the_date('', $post_id)) . '"',
                                'data-wpil-suggestion-score="' . intval($phrase->suggestions[$index]->total_score) . '"',
                                'data-wpil-inbound-internal-links="' . (int)$a_post->getInboundInternalLinks(true) . '"',
                                'data-wpil-outbound-internal-links="' . (int)$a_post->getOutboundInternalLinks(true) . '"',
                                'data-wpil-outbound-external-links="' . (int)$a_post->getOutboundExternalLinks(true) . '"',
                            );
            
                            $suggestion_datas = implode(' ', $suggestion_datas);

                        ?>
                        <div class="wpil-collapsible-wrapper">
                            <div class="wpil-collapsible wpil-collapsible-static wpil-links-count">
                                <div style="opacity:<?=$phrase->suggestions[$index]->opacity?>" <?php echo $suggestion_datas ?>>
                                    <div class="suggested-post-data-container"><strong><?php _e('Title:', 'wpil'); ?></strong> <span class="suggested-post-title" style="opacity:<?=$suggestion->opacity?>"><?=esc_html($a_post->getTitle())?></span></div>
                                    <div class="suggested-post-data-container"><strong><?php _e('Type: ', 'wpil'); ?></strong> <?=esc_html($a_post->getType())?><br></div>
                                    <div class="suggested-post-data-container"><strong><?php _e('Published:', 'wpil'); ?></strong> <span class="suggested-post-published"><?=esc_attr($post_published_date)?></span></div>
                                    <div class="suggested-post-data-container"><?php echo (!empty($categories)) ? '<b>' . _n(__('Category: ', 'wpil'), __('Categories: ', 'wpil'), $cats_found) . '</b>' . $categories . '<br>': ''; ?></div>
                                    <div class="suggested-post-data-container"><?php echo (!empty($tags)) ? '<b>' . _n(__('Tag: ', 'wpil'), __('Tags: ', 'wpil'), $tags_found) . '</b>' . $tags . '<br>': ''; ?></div>
                                    <div class="suggested-post-data-container"><strong><?php _e('Inbound Internal Links: ', 'wpil'); ?></strong> <?=(int)$a_post->getInboundInternalLinks(true) . '<br>'; ?></div>
                                    <div class="suggested-post-data-container"><strong><?php _e('Outbound Internal Links: ', 'wpil'); ?></strong> <?=(int)$a_post->getOutboundInternalLinks(true) . '<br>'; ?></div>
                                    <div class="suggested-post-data-container"><strong><?php _e('Outbound External Links: ', 'wpil'); ?></strong> <?=(int)$a_post->getOutboundExternalLinks(true) . '<br>'; ?></div>
                                    <div class="suggested-post-data-container"><strong><?php _e('URL:', 'wpil'); ?></strong> <a class="post-slug" target="_blank" href="<?=esc_url($a_post->getLinks()->view)?>"><?php echo esc_html($a_post->getSlug()); ?></a></div>
                                </div>
                            </div>
                            <div class="wpil-content" style="display: none;">
                                <ul>
                                    <?php $first = key($phrase->suggestions); ?>
                                    <?php foreach ($phrase->suggestions as $key_suggestion => $suggestion) : ?>
                                        <?php 
                                            $post_published_date = get_the_date('', $suggestion->post->id); 
                                            $terms = get_terms(array(
                                                'taxonomy' => $taxonomies,
                                                'hide_empty' => false,
                                                'object_ids' => $suggestion->post->id,
                                            ));

                                            $categories = array();
                                            $tags = array();
                                            if(!is_wp_error($terms) && !empty($terms)){
                                                foreach($terms as $term){
                                                    if(get_taxonomy($term->taxonomy)->hierarchical){
                                                        $categories[] = $term->name;
                                                    }else{
                                                        $tags[] = $term->name;
                                                    }
                                                }

                                                $cats_found = count($categories);
                                                $tags_found = count($tags);
                                                $categories = implode(', ', $categories);
                                                $tags = implode(', ', $tags);
                                            }


                                            $suggestion_datas = array(
                                                'data-id="' . esc_attr($suggestion->post->id) . '"',
                                                'data-type="' . esc_attr($suggestion->post->type) . '"',
                                                'data-post-origin="' . ((!isset($suggestion->post->site_url)) ? 'internal': 'external') . '"',
                                                'data-site-url="' . ((isset($suggestion->post->site_url)) ? esc_url($suggestion->post->site_url): '') . '"',
                                                'data-suggestion="' . esc_attr($key_suggestion) . '"',
                
                                                'data-wpil-post-published-date="' . strtotime($post_published_date) . '"',
                                                'data-wpil-suggestion-score="' . intval($suggestion->total_score) . '"',
                                                'data-wpil-inbound-internal-links="' . (int)$suggestion->post->getInboundInternalLinks(true) . '"',
                                                'data-wpil-outbound-internal-links="' . (int)$suggestion->post->getOutboundInternalLinks(true) . '"',
                                                'data-wpil-outbound-external-links="' . (int)$suggestion->post->getOutboundExternalLinks(true) . '"',
                                            );
                            
                                            $suggestion_datas = implode(' ', $suggestion_datas);
                                        ?>
                                        <li class="dated-outbound-suggestion" <?php echo $suggestion_datas; ?>>
                                            <div>
                                                <input type="radio" <?=$key_suggestion==$first?'checked':''?> <?php echo $suggestion_datas ?>>
                                                <span class="data">
                                                    <div class="suggested-post-data-container"><strong><?php _e('Title:', 'wpil'); ?></strong> <span class="suggested-post-title" style="opacity:<?=$suggestion->opacity?>"><?=esc_html($suggestion->post->getTitle())?></span></div>
                                                    <div class="suggested-post-data-container"><strong><?php _e('Type: ', 'wpil'); ?></strong> <?=esc_html($suggestion->post->getType())?><br></div>
                                                    <div class="suggested-post-data-container"><strong><?php _e('Published:', 'wpil'); ?></strong> <span class="suggested-post-published"><?=esc_attr($post_published_date)?></span></div>
                                                    <div class="suggested-post-data-container"><?php echo (!empty($categories)) ? '<b>' . _n(__('Category: ', 'wpil'), __('Categories: ', 'wpil'), $cats_found) . '</b>' . $categories . '<br>': ''; ?></div>
                                                    <div class="suggested-post-data-container"><?php echo (!empty($tags)) ? '<b>' . _n(__('Tag: ', 'wpil'), __('Tags: ', 'wpil'), $tags_found) . '</b>' . $tags . '<br>': ''; ?></div>
                                                    <div class="suggested-post-data-container"><strong><?php _e('Inbound Internal Links: ', 'wpil'); ?></strong> <?=(int)$suggestion->post->getInboundInternalLinks(true) . '<br>'; ?></div>
                                                    <div class="suggested-post-data-container"><strong><?php _e('Outbound Internal Links: ', 'wpil'); ?></strong> <?=(int)$suggestion->post->getOutboundInternalLinks(true) . '<br>'; ?></div>
                                                    <div class="suggested-post-data-container"><strong><?php _e('Outbound External Links: ', 'wpil'); ?></strong> <?=(int)$suggestion->post->getOutboundExternalLinks(true) . '<br>'; ?></div>
                                                    <div class="suggested-post-data-container"><strong><?php _e('URL:', 'wpil'); ?></strong> <a class="post-slug" target="_blank" href="<?=esc_url($suggestion->post->getLinks()->view)?>"><?php echo esc_html($suggestion->post->getSlug()); ?></a></div>
                                                </span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php
                        if(empty(count($phrase->suggestions))){
                            continue;
                        }
                        $index = key($phrase->suggestions);

                        if(null === $index){
                            reset($phrase->suggestions);
                            $index = key($phrase->suggestions);
                        }

                        $a_post = $phrase->suggestions[$index]->post;

                        if(empty($a_post)){
                            continue;
                        }

                        $post_published_date = get_the_date('', $a_post->id); 
                        $terms = get_terms(array(
                            'taxonomy' => $taxonomies,
                            'hide_empty' => false,
                            'object_ids' => $a_post->id,
                        ));

                        $categories = array();
                        $tags = array();
                        if(!is_wp_error($terms) && !empty($terms)){
                            foreach($terms as $term){
                                if(get_taxonomy($term->taxonomy)->hierarchical){
                                    $categories[] = $term->name;
                                }else{
                                    $tags[] = $term->name;
                                }
                            }

                            $cats_found = count($categories);
                            $tags_found = count($tags);
                            $categories = implode(',', $categories);
                            $tags = implode(',', $tags);
                        }
                        ?>
                        <div style="opacity:<?=$phrase->suggestions[$index]->opacity?>" class="suggestion dated-outbound-suggestion" data-id="<?=esc_attr($a_post->id)?>" data-type="<?=esc_attr($a_post->type)?>" data-wpil-post-published-date="<?php echo strtotime(get_the_date('', $a_post->id)); ?>" data-post-origin="<?php echo (!isset($a_post->site_url)) ? 'internal': 'external'; ?>" data-site-url="<?php echo (isset($a_post->site_url)) ? esc_url($a_post->site_url): ''; ?>">
                            <div class="suggested-post-data-container"><strong><?php _e('Title:', 'wpil'); ?></strong> <span class="suggested-post-title"><?=esc_html($a_post->getTitle())?></span></div>
                            <div class="suggested-post-data-container"><strong><?php _e('Type: ', 'wpil'); ?></strong> <?=esc_html($a_post->getType())?><br></div>
                            <div class="suggested-post-data-container"><strong><?php _e('Published:', 'wpil'); ?></strong> <span class="suggested-post-published"><?=esc_attr($post_published_date)?></span></div>
                            <div class="suggested-post-data-container"><?php echo (!empty($categories)) ? '<b>' . _n(__('Category: ', 'wpil'), __('Categories: ', 'wpil'), $cats_found) . '</b>' . $categories . '<br>': ''; ?></div>
                            <div class="suggested-post-data-container"><?php echo (!empty($tags)) ? '<b>' . _n(__('Tag: ', 'wpil'), __('Tags: ', 'wpil'), $tags_found) . '</b>' . $tags . '<br>': ''; ?></div>
                            <div class="suggested-post-data-container"><strong><?php _e('Inbound Internal Links: ', 'wpil'); ?></strong> <?=(int)$a_post->getInboundInternalLinks(true) . '<br>'; ?></div>
                            <div class="suggested-post-data-container"><strong><?php _e('Outbound Internal Links: ', 'wpil'); ?></strong> <?=(int)$a_post->getOutboundInternalLinks(true) . '<br>'; ?></div>
                            <div class="suggested-post-data-container"><strong><?php _e('Outbound External Links: ', 'wpil'); ?></strong> <?=(int)$a_post->getOutboundExternalLinks(true) . '<br>'; ?></div>
                            <div class="suggested-post-data-container"><strong><?php _e('URL:', 'wpil'); ?></strong> <a class="post-slug" target="_blank" href="<?=esc_url($a_post->getLinks()->view)?>"><?php echo esc_html(urldecode($a_post->getSlug())); ?></a></div>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
            <tr class="wpil-no-posts-in-range" style="display:none">
                <td>No suggestions found</td>
            </tr>
        </tbody>
    <?php else : ?>
        <thead>
            <tr class="wpil-suggestion-table-heading">
                <th>
                    <b><?php _e('Phrases In This Post To Link From', 'wpil'); ?></b>
                </th>
                <th style="width: 50px"></th>
                <th><b><?php _e('Suggested Posts To Link To', 'wpil'); ?></b></th>
                <?php if (!empty($show_date)) : ?>
                    <th><b><?php _e('Date Published', 'wpil'); ?></b></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php _e('No suggestions found', 'wpil'); ?></td>
            </tr>
        </tbody>
    <?php endif; ?>
</table>
