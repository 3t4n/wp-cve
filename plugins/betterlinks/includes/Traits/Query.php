<?php

namespace BetterLinks\Traits;

trait Query
{
    public static function insert_link($item, $is_update = false)
    {
        global $wpdb;
        if ($is_update) {
            $defaults = self::get_link_by_ID($item['ID']);
            $item = wp_parse_args($item, current($defaults));
            $link_data_array = array(
                'link_author' => $item['link_author'], 'link_date' => $item['link_date'], 'link_date_gmt' => $item['link_date_gmt'], 'link_title' => $item['link_title'], 'link_slug' => $item['link_slug'], 'link_note' => $item['link_note'], 'link_status' => $item['link_status'], 'nofollow' => $item['nofollow'], 'sponsored' => $item['sponsored'], 'track_me' => $item['track_me'], 'param_forwarding' => $item['param_forwarding'], 'param_struct' => $item['param_struct'], 'redirect_type' => $item['redirect_type'], 'target_url' => $item['target_url'], 'short_url' => $item['short_url'], 'link_order' => $item['link_order'], 'link_modified' => $item['link_modified'], 'link_modified_gmt' => $item['link_modified_gmt'], 'wildcards' => $item['wildcards'], 'expire' => $item['expire'], 'dynamic_redirect' => $item['dynamic_redirect']
            );
            $link_data_place_array = array(
                '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s'
            );
            if (isset($item['favorite'])) {
                $link_data_array['favorite'] = $item['favorite'];
                $link_data_place_array[] = '%s';
            }
            if(isset($item['uncloaked'])){
                $link_data_array['uncloaked'] = $item['uncloaked'];
                $link_data_place_array[] = '%s';
            }
            $wpdb->update(
                "{$wpdb->prefix}betterlinks",
                $link_data_array,
                array('ID' => $item['ID']),
                $link_data_place_array,
                array('%d')
            );
            do_action('betterlinks/after_update_link', $item['ID'], $item);
            return $item['ID'];
        } else {
            $betterlinks = self::get_link_by_short_url($item['short_url']);
            if (count($betterlinks) === 0) {
                $initial_defaults_arr = array(
                    'link_author' => get_current_user_id(),
                    'link_date' => current_time('mysql'),
                    'link_date_gmt' => current_time('mysql', 1),
                    'link_title' => '',
                    'link_slug' => '',
                    'link_note' => '',
                    'link_status' => 'publish',
                    'nofollow' => '',
                    'sponsored' => '',
                    'track_me' => '',
                    'param_forwarding' => '',
                    'param_struct' => '',
                    'redirect_type' => '',
                    'target_url' => '',
                    'short_url' => '',
                    'link_order' => '',
                    'link_modified' => current_time('mysql'),
                    'link_modified_gmt' => current_time('mysql', 1),
                    'wildcards' => '',
                    'expire' => '',
                    'dynamic_redirect' => '',
                );
                if (isset($item['favorite'])) {
                    $initial_defaults_arr['favorite'] = '';
                }
                $defaults = apply_filters('betterlinks/insert_link_default_args', $initial_defaults_arr);
                $item = wp_parse_args($item, $defaults);
                $column_names = "link_author,link_date,link_date_gmt,link_title,link_slug,link_note,link_status,nofollow,sponsored,track_me,param_forwarding,param_struct,redirect_type,target_url,short_url,link_order,link_modified,link_modified_gmt,wildcards,expire,dynamic_redirect";
                $column_placeholders = "%d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s";
                $query_value_array = array(
                    $item['link_author'], $item['link_date'], $item['link_date_gmt'], $item['link_title'], $item['link_slug'], $item['link_note'], $item['link_status'], $item['nofollow'], $item['sponsored'], $item['track_me'], $item['param_forwarding'], $item['param_struct'], $item['redirect_type'], $item['target_url'], $item['short_url'], $item['link_order'], $item['link_modified'], $item['link_modified_gmt'], $item['wildcards'], $item['expire'], $item['dynamic_redirect']
                );
                if(isset($item['favorite'])){
                    $column_names .= ",favorite";
                    $column_placeholders .= ", %s";
                    $query_value_array[] = $item['favorite'];
                }
                if(isset($item['uncloaked'])){
                    $column_names .= ",uncloaked";
                    $column_placeholders .= ", %s";
                    $query_value_array[] = $item['uncloaked'];
                }
                $query_string = "INSERT INTO {$wpdb->prefix}betterlinks ( {$column_names} ) VALUES ( {$column_placeholders} )";
                $wpdb->query( $wpdb->prepare( $query_string, $query_value_array ) );
                do_action('betterlinks/after_insert_link', $wpdb->insert_id, $item);
                return $wpdb->insert_id;
            }
        }
        return;
    }
    public static function delete_link($ID)
    {
        global $wpdb;
        $wpdb->delete("{$wpdb->prefix}betterlinks", array('ID' => $ID), array('%d'));
        $wpdb->delete("{$wpdb->prefix}betterlinks_clicks", array('link_id' => $ID), array('%d'));
        $wpdb->delete("{$wpdb->prefix}betterlinks_terms_relationships", array('link_id' => $ID), array('%d'));
    }
    public static function remove_terms_relationships_by_link_ID($ID)
    {
        global $wpdb;
        $wpdb->delete("{$wpdb->prefix}betterlinks_terms_relationships", array('link_id' => $ID), array('%d'));
    }
    public static function get_prepare_all_links()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $analytic = get_option('betterlinks_analytics_data');
        $analytic = $analytic ? json_decode($analytic, true) : [];

        // pull all broken links logs 
        $broken_links = get_option( 'betterlinkspro_broken_links_logs' );
        $broken_links = $broken_links ? json_decode( $broken_links, true ) : [];

        $results = $wpdb->get_results("SELECT
            bt.ID as cat_id,
            bt.term_name,
            bt.term_slug,
            bt.term_type,
            bl.ID,
            bl.link_title,
            bl.link_slug,
            bl.link_note,
            bl.link_status,
            bl.nofollow,
            bl.sponsored,
            bl.track_me,
            bl.param_forwarding,
            bl.param_struct,
            bl.redirect_type,
            bl.target_url,
            bl.short_url,
            bl.link_date,
            bl.wildcards,
            bl.expire,
            bl.favorite,
            bl.dynamic_redirect,
            bl.uncloaked
            FROM {$prefix}betterlinks_terms as bt
            LEFT JOIN  {$prefix}betterlinks_terms_relationships as btr ON bt.ID = btr.term_id
            LEFT JOIN  {$prefix}betterlinks as bl ON bl.ID = btr.link_id
            -- WHERE bt.term_type = 'category'
            ORDER BY bl.link_order ASC;", OBJECT);
        $results = \BetterLinks\Helper::parse_link_response($results, $analytic, $broken_links);
        return $results;
    }
    public static function get_link_by_short_url($short_url, $is_case_sensitive = false)
    {
        global $wpdb;
        $link = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}betterlinks WHERE short_url=%s", $short_url),
            ARRAY_A
        );
        if (isset($link[0]['short_url']) && $is_case_sensitive && $link[0]['short_url'] != $short_url) return [];
        return $link;
    }
    public static function get_link_by_permalink($target_url, $is_case_sensitive = false)
    {
        global $wpdb;
        $link = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}betterlinks WHERE target_url=%s", $target_url),
            ARRAY_A
        );
        if (isset($link[0]['target_url']) && $is_case_sensitive && $link[0]['target_url'] != $target_url) return [];
        return $link;
    }
    public static function get_link_by_wildcards($wildcards)
    {
        global $wpdb;
        $link = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}betterlinks WHERE wildcards=%d", $wildcards),
            ARRAY_A
        );
        return $link;
    }
    public static function get_link_by_ID($ID)
    {
        global $wpdb;
        $link = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}betterlinks WHERE ID=%d", $ID),
            ARRAY_A
        );
        return $link;
    }
    public static function get_link_data_with_cat_id_by_link_id($ID)
    {
        global $wpdb;
        $link = $wpdb->get_results(
            $wpdb->prepare("SELECT 
            bt.ID as cat_id,
            bl.ID,
            bl.target_url,
            bl.short_url,
            bl.uncloaked
            FROM {$wpdb->prefix}betterlinks as bl
            INNER JOIN {$wpdb->prefix}betterlinks_terms_relationships as btr ON bl.ID = btr.link_id AND bl.ID=%d
            INNER JOIN {$wpdb->prefix}betterlinks_terms as bt ON bt.ID = btr.term_id AND bt.term_type = 'category'
            ", $ID),
            ARRAY_A
        );
        return $link;
    }

    /**
     * Get All BetterLinks Uploads Links JSON File
     *
     * @return array
     */
    public static function get_links_for_json()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $formattedArray = [];
        $items = $wpdb->get_results("SELECT
            bl.ID,
            bl.redirect_type,
            bl.short_url,
            bl.link_slug,
            bl.link_status,
            bl.target_url,
            bl.nofollow,
            bl.sponsored,
            bl.param_forwarding,
            bl.track_me,
            bl.wildcards,
            bl.expire,
            bl.dynamic_redirect,
            bl.uncloaked,
            br.term_id as cat_id
            FROM {$prefix}betterlinks as bl
            INNER JOIN {$prefix}betterlinks_terms_relationships as br ON bl.ID = br.link_id
            INNER JOIN {$prefix}betterlinks_terms as bt ON br.term_id = bt.ID AND bt.term_type = 'category'
        ");
        $options = json_decode(get_option(BETTERLINKS_LINKS_OPTION_NAME), true);
        $formattedArray['is_case_sensitive'] = isset($options['is_case_sensitive']) ? $options['is_case_sensitive'] : false;
        $formattedArray['is_disable_analytics_ip'] = isset($options['is_disable_analytics_ip']) ? $options['is_disable_analytics_ip'] : false;
        $is_links_case_sensitive = $formattedArray['is_case_sensitive'];
        if (!empty($options)) {
            $formattedArray['wildcards_is_active'] = isset($options['wildcards']) ? $options['wildcards'] : false;
            $formattedArray['disablebotclicks'] = isset($options['disablebotclicks']) ? $options['disablebotclicks'] : false;
            $formattedArray['force_https'] = isset($options['force_https']) ? $options['force_https'] : false;
            $formattedArray['autolink_disable_post_types'] = isset($options['autolink_disable_post_types']) ? $options['autolink_disable_post_types'] : [];
            $formattedArray['is_autolink_icon'] = isset($options['is_autolink_icon']) ? $options['is_autolink_icon'] : false;
            $formattedArray['is_autolink_headings'] = isset($options['is_autolink_headings']) ? $options['is_autolink_headings'] : false;
            $formattedArray['uncloaked_categories'] = isset($options['uncloaked_categories']) ? $options['uncloaked_categories'] : [];
        }
        if (is_array($items) && count($items) > 0) {
            foreach ($items as $item) {
                $short_url = $is_links_case_sensitive ? $item->short_url : strtolower($item->short_url);
                if ($item->wildcards == true) {
                    $formattedArray['wildcards'][$short_url] = $item;
                } else {
                    $formattedArray['links'][$short_url] = $item;
                }
            }
        }
        if (defined('BETTERLINKS_PRO_EXTERNAL_ANALYTICS_OPTION_NAME') && BETTERLINKS_PRO_EXTERNAL_ANALYTICS_OPTION_NAME) {
            $analytic_data = get_option(BETTERLINKS_PRO_EXTERNAL_ANALYTICS_OPTION_NAME, []);
            if(is_array($analytic_data)){
                $formattedArray = array_merge($analytic_data, $formattedArray);
            }else{
                $analytic_data = is_string($analytic_data) ? json_decode($analytic_data, true) : [];
                $formattedArray = array_merge($analytic_data, $formattedArray);
            }
        }
        return $formattedArray;
    }

    public static function insert_term($item, $is_update = false)
    {
        global $wpdb;
        if ($is_update) {
            $wpdb->update(
                "{$wpdb->prefix}betterlinks_terms",
                array(
                    'term_name' => $item['term_name'], 'term_slug' => $item['term_slug'], 'term_type' => $item['term_type']
                ),
                array('ID' => $item['ID']),
                array(
                    '%s', '%s', '%s'
                ),
                array('%d')
            );
            return  $item['ID'];
        } else {
            $terms = self::get_term_by_slug($item['term_slug'], $item['term_type']);
            if (count($terms) === 0) {
                $wpdb->query(
                    $wpdb->prepare(
                        "INSERT INTO {$wpdb->prefix}betterlinks_terms ( term_name, term_slug, term_type ) VALUES ( %s, %s, %s )",
                        array($item['term_name'], $item['term_slug'], $item['term_type'])
                    )
                );
                return $wpdb->insert_id;
            } elseif (isset(current($terms)['ID'])) {
                return current($terms)['ID'];
            }
        }
        return;
    }
    public static function insert_tags_terms($tags)
    {
        $terms_ids = [];
        if (is_array($tags) && count($tags) > 0) {
            foreach ($tags as $tag) {
                $insert_id = self::insert_term([
                    'term_name' => $tag,
                    'term_slug' => \BetterLinks\Helper::make_slug($tag),
                    'term_type' => 'tags'
                ]);
                if ($insert_id) {
                    $terms_ids[] = $insert_id;
                }
            }
        }
        return $terms_ids;
    }

    public static function insert_category_terms($categories)
    {
        $terms_ids = [];
        if (is_array($categories) && count($categories) > 0) {
            foreach ($categories as $category) {
                $insert_id = self::insert_term([
                    'term_name' => $category,
                    'term_slug' => \BetterLinks\Helper::make_slug($category),
                    'term_type' => 'category'
                ]);
                if ($insert_id) {
                    $terms_ids[] = $insert_id;
                }
            }
        }
        return $terms_ids;
    }
    public static function insert_terms_relationships($term_id, $link_id)
    {
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO {$wpdb->prefix}betterlinks_terms_relationships ( term_id, link_id ) VALUES ( %d, %d )",
                array($term_id, $link_id)
            )
        );
        return $wpdb->insert_id;
    }

    /**
     * Delete term and update Term relationship to uncategorized
     *
     * @param term_id
     * @return boolean
     */
    public static function delete_term_and_update_term_relationships($term_id)
    {
        global $wpdb;
        $wpdb->query("START TRANSACTION");
        $is_delete = $wpdb->delete($wpdb->prefix . 'betterlinks_terms', array('ID' => $term_id), array('%d'));
        if ($is_delete) {
            $term = self::get_term_by_slug('uncategorized');
            if (count($term) > 0) {
                $wpdb->update(
                    "{$wpdb->prefix}betterlinks_terms_relationships",
                    array(
                        'term_id' => current($term)['ID']
                    ),
                    array('term_id' => $term_id),
                    array(
                        '%d',
                    ),
                    array('%d')
                );
            }
        }
        $wpdb->query("COMMIT");
        return $is_delete;
    }

    public static function insert_terms_and_terms_relationship($link_id, $request)
    {
        global $wpdb;
        $term_data = [];
        $newTermList = [];
        // store tags relation data
        if (!empty($request['cat_id'])) {
            $is_new_cat = true;
            if (is_numeric($request['cat_id'])) {
                $query = $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}betterlinks_terms WHERE id = %d ",
                    $request['cat_id']
                );
                $result = $wpdb->get_row($query, "ARRAY_A");
                if (isset($result["term_slug"])) {
                    $is_new_cat = false;
                    $term_data[] = [
                        'term_id' => $request['cat_id'],
                        'link_id' => $link_id,
                        'term_slug' => $result["term_slug"],
                        'term_type' => 'category',
                    ];
                }
            }
            if ($is_new_cat) {
                $newTermList[] = [
                    'term_name' => $request['cat_id'],
                    'term_slug' => isset($request['cat_slug']) ? $request['cat_slug'] : $request['cat_id'],
                    'term_type' => 'category',
                ];
            }
        }
        if (isset($request['tags_id']) && is_array($request['tags_id'])) {
            foreach ($request['tags_id'] as $key => $value) {
                $is_new_tag = true;
                if (is_numeric($value)) {
                    $query = $wpdb->prepare(
                        "SELECT * FROM {$wpdb->prefix}betterlinks_terms WHERE id = %d ",
                        $value
                    );
                    $result = $wpdb->get_row($query, "ARRAY_A");
                if (isset($result["term_slug"])) {
                        $term_data[] = [
                            'link_id' => $link_id,
                            'term_id' => $value,
                            'term_slug' => $result["term_slug"],
                            'term_name' => $result["term_name"],
                            'term_type' => 'tags',
                        ];
                        $is_new_tag = false;
                    }
                }
                if ($is_new_tag) {
                    $newTermList[] = [
                        'term_name' => $value,
                        'term_slug' => $value,
                        'term_type' => 'tags',
                    ];
                }
            }
        }

        // insert new tags or category
        if (count($newTermList) > 0) {
            foreach ($newTermList as $item) {
                $term_id = \BetterLinks\Helper::insert_term($item);
                $term_data[] = [
                    'link_id' => $link_id,
                    'term_id' => $term_id,
                    'term_type' => $item['term_type'],
                    'term_name' => $item['term_name'],
                    'term_slug' => $item['term_slug'],
                    'is_newly_created' => true,
                ];
            }
        }
        // make term and link relation
        if (count($term_data) > 0) {
            $is_delete = $wpdb->delete($wpdb->prefix . 'betterlinks_terms_relationships', array('link_id' => $link_id), array('%d'));
            if ($is_delete || $is_delete === 0) {
                foreach ($term_data as $term) {
                    \BetterLinks\Helper::insert_terms_relationships($term['term_id'], $term['link_id']);
                }
            }
        }
        return $term_data;
    }

    public static function is_term_exists($term_id, $type = 'category') {
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}betterlinks_terms WHERE ID=%s AND term_type=%s", $term_id, $type),
            ARRAY_A
        );
        return count($result) === 1;
    }

    public static function get_term_by_slug($slug, $type = "category")
    {
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}betterlinks_terms WHERE term_slug=%s AND term_type=%s", $slug, $type),
            ARRAY_A
        );
        return $result;
    }

    public static function get_terms_by_link_ID_and_term_type($link_ID, $term_type = 'categroy')
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $link = $wpdb->get_results(
            $wpdb->prepare("SELECT
            {$prefix}betterlinks_terms.ID as term_id,
            {$prefix}betterlinks_terms.term_name,
            {$prefix}betterlinks_terms.term_slug,
            {$prefix}betterlinks_terms.term_type
            FROM {$prefix}betterlinks_terms
            LEFT JOIN  {$prefix}betterlinks_terms_relationships ON {$prefix}betterlinks_terms.ID = {$prefix}betterlinks_terms_relationships.term_id
            LEFT JOIN  {$prefix}betterlinks ON {$prefix}betterlinks.ID = {$prefix}betterlinks_terms_relationships.link_id
            WHERE {$prefix}betterlinks_terms_relationships.link_id = %d
            AND {$prefix}betterlinks_terms.term_type = %s", $link_ID, $term_type),
            ARRAY_A
        );
        return $link;
    }

    public static function get_terms_all_data()
    {
        global $wpdb;
        $link = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}betterlinks_terms",
            ARRAY_A
        );
        return $link;
    }

    public static function insert_click($item)
    {
        global $wpdb;
        $betterlinks = [];
        $is_extra_data_tracking_compatible = apply_filters('betterlinks/is_extra_data_tracking_compatible', false);
        if (isset($item['short_url'])) {
            $betterlinks = self::get_link_by_short_url($item['short_url']);
        } elseif (isset($item['link_id'])) {
            $betterlinks = self::get_link_by_ID($item['link_id']);
        }
        $is_analytics_ip_enabled = isset($item['ip']) && isset($item['host']);
        $addedPlaceholderString = $is_analytics_ip_enabled ? " created_at_gmt, rotation_target_url, ip, host " : " created_at_gmt, rotation_target_url ";
        $addedDbColumnsString = $is_analytics_ip_enabled ? " %s, %s, %s, %s " : " %s, %s ";

        if( $is_extra_data_tracking_compatible ) {
            $addedPlaceholderString .= ", brand_name, model, bot_name, browser_type, os_version, browser_version, language";
            $addedDbColumnsString .= ", %s, %s, %s, %s, %s, %s, %s";
        }

        $query = "INSERT INTO {$wpdb->prefix}betterlinks_clicks ( link_id, browser, os,device, referer, uri, click_count, visitor_id, click_order, created_at,  $addedPlaceholderString ) VALUES ( %d, %s, %s, %s, %s, %s, %d, %s, %d, %s,  $addedDbColumnsString )";
        $db_data_array = [
            current($betterlinks)['ID'],
            $item['browser'],
            $item['os'],
            $item['device'],
            $item['referer'],
            $item['uri'],
            isset($item['click_count']) ? $item['click_count'] : 0,
            $item['visitor_id'],
            $item['click_order'],
            $item['created_at'],
            $item['created_at_gmt'],
            $item['rotation_target_url']
        ];
        if($is_analytics_ip_enabled){
            $db_data_array[] = $item['ip'];
            $db_data_array[] = $item['host'];
        }
        // $db_data_array[] = isset($item['device']) ? $item['device'] : '';
        if( $is_extra_data_tracking_compatible ) {
            $db_data_array[] = isset($item['brand_name']) ? $item['brand_name'] : '';
            $db_data_array[] = isset($item['model']) ? $item['model'] : '';
            $db_data_array[] = isset($item['bot_name']) ? $item['bot_name'] : '';
            $db_data_array[] = isset($item['browser_type']) ? $item['browser_type'] : '';
            $db_data_array[] = isset($item['os_version']) ? $item['os_version'] : '';
            $db_data_array[] = isset($item['browser_version']) ? $item['browser_version'] : '';
            $db_data_array[] = isset($item['language']) ? $item['language'] : '';
        }
        if (isset(current($betterlinks)['ID'])) {
            $wpdb->query(
                $wpdb->prepare( $query, $db_data_array )
            );
            return $wpdb->insert_id;
        }
        return;
    }

    public static function get_linksNips_count()
    {
        global $wpdb;

        $query = "select link_id, ip, ipc, t2.lidc from ( select ip, link_id, count(ip) as ipc from {$wpdb->prefix}betterlinks_clicks group by ip, link_id ) as t1
        left join ( select link_id as lid, sum(ipc) as lidc from ( select ip, link_id, count(uri) as ipc from {$wpdb->prefix}betterlinks_clicks group by ip,uri, link_id ) as t3 group by link_id ) as t2
        on t1.link_id = t2.lid";

        $results = $wpdb->get_results($query, ARRAY_A);
        return $results;
    }
    
    public static function get_clicks_count() {
        global $wpdb;

        $query = "SELECT link_id, count(id) as total_clicks from {$wpdb->prefix}betterlinks_clicks group by link_id";
        $total_clicks = $wpdb->get_results($query, ARRAY_A);

        $query = "SELECT T1.link_id, count(ip) as unique_clicks from ( SELECT ip, link_id FROM {$wpdb->prefix}betterlinks_clicks GROUP BY `ip`, `link_id` ) as T1 GROUP BY T1.link_id ORDER BY T1.link_id";
        $unique_clicks = $wpdb->get_results($query, ARRAY_A);

        return array(
            'total_clicks' => $total_clicks,
            'unique_clicks' => $unique_clicks   
        );
    }

    public static function get_links_analytics()
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $results = $wpdb->get_results(
            "SELECT DISTINCT link_id, ip,
			(select count(ip) from {$prefix}betterlinks_clicks WHERE CLICKS.ip = {$prefix}betterlinks_clicks.ip  group by ip) as IPCOUNT,
			(select count(link_id) from {$prefix}betterlinks_clicks WHERE CLICKS.link_id = {$prefix}betterlinks_clicks.link_id group by link_id) as LINKCOUNT
			from {$prefix}betterlinks_clicks as CLICKS group by CLICKS.id",
            ARRAY_A
        );
        return $results;
    }

    public static function clear_analytics_cache() {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $individual_analytics_cache_keys = 'btl_individual_analytics_clicks_|btl_individual_graph_data_';
        $all_analytics_cache_keys = 'betterlinks_analytics_data|btl_analytics_unique_list_|btl_analytics_graph_|btl_top_referer_|btl_click_stats_|btl_top_os_|btl_top_browser_|btl_all_referer_|btl_tags_analytics';
        $query = "DELETE FROM {$prefix}options WHERE option_name regexp '{$individual_analytics_cache_keys}|{$all_analytics_cache_keys}'";

        $result = $wpdb->query($query);
        return $result;
    }

    public static function search_clicks_data($keyword)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $is_extra_data_tracking_compatible = apply_filters('betterlinks/is_extra_data_tracking_compatible', false);
        $extra_data_tracking_columns = $is_extra_data_tracking_compatible ? 'os, device, brand_name, ' : '';
        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT CLICKS.ID as
            click_ID, link_id, browser, {$extra_data_tracking_columns} created_at, referer, SUBSTRING_INDEX(SUBSTRING_INDEX(referer, '/', 3), '/', -1) AS domain, short_url, target_url, ip, {$prefix}betterlinks.link_title,
            (select count(id) from {$prefix}betterlinks_clicks where CLICKS.ip = {$prefix}betterlinks_clicks.ip group by ip) as IPCOUNT
            from {$prefix}betterlinks_clicks as CLICKS left join {$prefix}betterlinks on {$prefix}betterlinks.id = CLICKS.link_id WHERE {$prefix}betterlinks.link_title LIKE %s
            or {$prefix}betterlinks.short_url like %s
            or {$prefix}betterlinks.target_url like %s
            or CLICKS.browser like %s
            or CLICKS.ip like %s
            or CLICKS.referer like %s
            group by CLICKS.id ORDER BY CLICKS.created_at DESC", '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%', '%' . $keyword . '%'),
            ARRAY_A
        );
        return $results;
    }

    public static function get_clicks_by_date($from, $to)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $is_extra_data_tracking_compatible = apply_filters('betterlinks/is_extra_data_tracking_compatible', false);
        $extra_data_tracking_columns = $is_extra_data_tracking_compatible ? 'CLICKS.os, CLICKS.device, CLICKS.brand_name, ' : '';
        $query = $wpdb->prepare("SELECT 
                CLICKS.ID AS click_ID, 
                CLICKS.link_id, 
                CLICKS.browser, 
                {$extra_data_tracking_columns}
                CLICKS.created_at, 
                CLICKS.referer,
                SUBSTRING_INDEX(SUBSTRING_INDEX(CLICKS.referer, '/', 3), '/', -1) AS domain,
                {$prefix}betterlinks.short_url, 
                {$prefix}betterlinks.target_url, 
                CLICKS.ip, 
                {$prefix}betterlinks.link_title
            FROM 
                {$prefix}betterlinks_clicks AS CLICKS 
                LEFT JOIN {$prefix}betterlinks ON {$prefix}betterlinks.id = CLICKS.link_id 
            WHERE 
                CLICKS.created_at BETWEEN %s AND %s 
            GROUP BY 
                CLICKS.id 
            ORDER BY 
                CLICKS.created_at DESC limit 100000", 
            $from . ' 00:00:00', $to . ' 23:59:00');
        $results = $wpdb->get_results( $query, ARRAY_A );
        return $results;
    }


    public static function get_thirstyaffiliates_links()
    {
        $thirstylinks = get_posts(array(
            'posts_per_page' => -1,
            'post_type'      => 'thirstylink',
            'post_status'    => 'publish',
        ));
        $response = [];
        $betterlinks_links = json_decode(get_option('betterlinks_links', '{}'), true);
        foreach ($thirstylinks as $thirstylink) {
            $term =  wp_get_post_terms($thirstylink->ID, 'thirstylink-category', array('fields' => 'names'));
            $nofollow = get_post_meta($thirstylink->ID, '_ta_no_follow', true);
            $nofollow = ($nofollow == 'global' ? get_option('ta_no_follow', true) : $nofollow);
            $redirect_type = get_post_meta($thirstylink->ID, '_ta_redirect_type', true);
            $redirect_type = ($redirect_type == 'global' ? get_option('ta_link_redirect_type', true) : $redirect_type);
            $param_forwarding = get_post_meta($thirstylink->ID, '_ta_pass_query_str', true);
            $param_forwarding = ($param_forwarding == 'global' ? get_option('ta_pass_query_str', true) : $param_forwarding);
            $dynamic_redirect = [];
            $geolocation_links = get_post_meta($thirstylink->ID, '_ta_geolocation_links', true);
            if ($geolocation_links && is_array($geolocation_links)) {
                $dynamic_redirect_value = [];
                foreach ($geolocation_links as $key => $geolocation_link) {
                    $dynamic_redirect_value[] = [
                        'link'      => $geolocation_link,
                        'country'   => explode(',', $key)
                    ];
                }
                $dynamic_redirect = [
                    'type'        =>    'geographic',
                    'value'     => $dynamic_redirect_value,
                    'extra' => []
                ];
            }
            $link_date = get_post_meta($thirstylink->ID, '_ta_link_start_date', true);
            // expire
            $expire = [];
            $expire_date = get_post_meta($thirstylink->ID, '_ta_link_expire_date', true);
            $expire_redirect_url = get_post_meta($thirstylink->ID, '_ta_after_expire_redirect', true);
            if (!empty($expire_date)) {
                $expire = [
                    'status' => 1,
                    'type'   => 'date',
                    'date'  => $expire_date,
                ];
            }
            if (!empty($expire_redirect_url)) {
                $expire['redirect_status'] = 1;
                $expire['redirect_url'] = $expire_redirect_url;
            }
            // link status
            $link_status = 'publish';
            $now = time();
            if (!empty($link_date) && $now < strtotime($link_date)) {
                $link_status = 'scheduled';
            }
            if (!empty($expire_date) && $now > strtotime($expire_date)) {
                $link_status = 'draft';
            }
            // keywords
            $keywords = get_post_meta($thirstylink->ID, '_ta_autolink_keyword_list', true);
            $limit = get_post_meta($thirstylink->ID, '_ta_autolink_keyword_limit', true);
            $response[] = [
                'link_title' => $thirstylink->post_title,
                'link_slug' => $thirstylink->post_name,
                'link_date' => $link_date ? $link_date : "",
                'link_date_gmt' => $link_date ? $link_date : "",
                'link_status'   => $link_status,
                'short_url' => trim(\BetterLinks\Helper::force_relative_url(get_the_permalink($thirstylink->ID)), '/'),
                'link_author' => $thirstylink->post_author,
                'link_date' => $thirstylink->post_date,
                'link_date_gmt' => $thirstylink->post_date_gmt,
                'nofollow'  => ($nofollow == 'yes' ? 1 : 0),
                'sponsored'  => $betterlinks_links['sponsored'],
                'track_me'  => $betterlinks_links['track_me'],
                'redirect_type'  => $redirect_type,
                'param_forwarding' => ($param_forwarding == 'yes' ? 1 : 0),
                'target_url' => get_post_meta($thirstylink->ID, '_ta_destination_url', true),
                'link_modified' => $thirstylink->post_modified,
                'link_modified_gmt' => $thirstylink->post_modified_gmt,
                'terms'  => $term,
                'expire'  => json_encode($expire),
                'dynamic_redirect'  => json_encode($dynamic_redirect),
                'keywords'  => $keywords,
                'limit'     => $limit
            ];
        }
        return $response;
    }

    public static function get_prettylinks_links_count()
    {
        global $wpdb;
        $links = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}prli_links");
        return $links;
    }
    public static function get_prettylinks_clicks_count()
    {
        global $wpdb;
        $clicks = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}prli_clicks");
        return $clicks;
    }

    public static function get_link_meta($link_id, $meta_key)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'betterlinkmeta';
        if (empty($link_id) || empty($meta_key)) {
            return false;
        }
        $query = $wpdb->prepare("SELECT meta_value FROM $table WHERE meta_key = %s AND link_id = %d", $meta_key, $link_id);
        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            return json_decode(current($results)->meta_value);
        }
        return;
    }

    public static function add_link_meta($link_id, $meta_key, $meta_value)
    {
        global $wpdb;
        $meta_key   = wp_unslash($meta_key);
        $meta_value = wp_unslash($meta_value);
        if (isset($meta_value["keywords"])) {
            $meta_value["keywords"] = preg_replace('/\’|\'|\‘/', "'", $meta_value["keywords"]);
        }
        $meta_value = \BetterLinks\Helper::maybe_json($meta_value);
        if (empty($link_id) || empty($meta_key)) {
            return false;
        }
        $result = $wpdb->insert(
            $wpdb->prefix . 'betterlinkmeta',
            array(
                'link_id'    => $link_id,
                'meta_key'   => $meta_key,
                'meta_value' => $meta_value,
            )
        );
        if (!$result) {
            return false;
        }
        return (int) $wpdb->insert_id;
    }
    public static function update_link_meta($link_id, $meta_key, $meta_value, $old_keywords = false, $old_link_id = false)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'betterlinkmeta';
        $link_id = absint($link_id);
        $meta_key   = wp_unslash($meta_key);
        $meta_value = wp_unslash($meta_value);
        if (isset($meta_value["keywords"])) {
            $meta_value["keywords"] = preg_replace('/\’|\'|\‘/', "'", $meta_value["keywords"]);
        }
        $meta_value = \BetterLinks\Helper::maybe_json($meta_value);
        if (empty($link_id) || empty($meta_key)) {
            return false;
        }
        $result = false;
        if ($old_keywords && $old_link_id) {
            $keywordPattern = wp_slash('%"keywords":' . wp_json_encode(wp_unslash($old_keywords)) . ',"link_id":%');
            $result = $wpdb->query($wpdb->prepare(
                "UPDATE $table
                SET meta_value = %s, link_id = %d
                WHERE link_id = %d AND meta_key=%s AND meta_value LIKE %s LIMIT 1",
                $meta_value,
                $link_id,
                $old_link_id,
                $meta_key,
                $keywordPattern
            ));
        } else {
            $result = $wpdb->query($wpdb->prepare(
                "UPDATE $table
                SET meta_value = %s
                WHERE link_id = %d AND meta_key=%s",
                $meta_value,
                $link_id,
                $meta_key
            ));
        }
        return !!$result;
    }

    public static function delete_link_meta($link_id, $meta_key, $meta_value = '', $keywords = false)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'betterlinkmeta';
        if (empty($link_id) || empty($meta_key)) {
            return false;
        }
        $query = $wpdb->prepare("SELECT link_id FROM $table WHERE meta_key = %s AND link_id = %d", $meta_key, $link_id);
        if (!empty($keywords)) {
            $keywordPattern = wp_slash('%"keywords":' . wp_json_encode(wp_unslash($keywords)) . ',"link_id":%');
            $query = $wpdb->prepare(
                "SELECT meta_id FROM $table WHERE meta_key = %s AND link_id = %d AND meta_value LIKE %s LIMIT 1",
                $meta_key,
                $link_id,
                $keywordPattern
            );
        }
        if (!empty($meta_value)) {
            $query .= $wpdb->prepare(' AND meta_value = %s', $meta_value);
        }
        $meta_ids = $wpdb->get_col($query);
        if (!count($meta_ids)) {
            return false;
        }
        $query = "DELETE FROM $table WHERE meta_id IN( " . implode(',', $meta_ids) . ' )';
        $count = $wpdb->query($query);
        return !!$count;
    }

    public static function get_keywords()
    {
        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}betterlinkmeta WHERE meta_key=%s ORDER BY meta_id DESC", 'keywords'),
            ARRAY_A
        );
        $results = array_column($results, 'meta_value');
        return $results;
    }

    public static function get_link_data_by_id($id, $fields) {
        global $wpdb;
        $query = $wpdb->prepare( "SELECT `{$fields}` from {$wpdb->prefix}betterlinks WHERE id=%d", [$id] );
        $result = $wpdb->get_var($query);
        return $result;
    }
}
