<?php
namespace BetterLinks\Traits;

trait Links
{
    public function sanitize_links_data($POST)
    {
        $data = [];
        foreach ($this->get_links_schema() as $key => $schema) {
            if (isset($POST[$key])) {
                if (isset($schema['sanitize_callback'])) {
                    $data[$key] = $schema['sanitize_callback']($POST[$key]);
                } elseif (isset($schema['format']) && $schema['format'] == 'date-time') {
                    $data[$key] = sanitize_text_field($POST[$key]);
                } elseif (isset($schema['type']) && $schema['type'] === 'object') {
                    $tempData = (is_array($POST[$key]) ? $POST[$key] : json_decode(html_entity_decode(stripslashes($POST[$key])), true));
                    $tempSanitizeData = [];
                    if (isset($schema['properties']) && is_array($tempData) && count($tempData) > 0) {
                        foreach ($schema['properties'] as $innerKey => $innerSchema) {
                            if ($innerSchema['type'] === 'integer' || $innerSchema['type'] === 'string') {
                                if (isset($tempData[$innerKey])) {
                                    if (isset($innerSchema['sanitize_callback'])) {
                                        $tempSanitizeData[$innerKey] = $innerSchema['sanitize_callback']($tempData[$innerKey]);
                                    } elseif (isset($innerSchema['format']) && $innerSchema['format'] == 'date-time') {
                                        $tempSanitizeData[$innerKey] = sanitize_text_field($tempData[$innerKey]);
                                    }
                                }
                            } elseif ($innerSchema['type'] === 'array') {
                                $tempTwoSanitizeData = [];
                                if (isset($tempData['value']) && is_array($tempData['value'])) {
                                    foreach ($tempData['value'] as $valueItem) {
                                        $value = [];
                                        if (is_array($valueItem)) {
                                            foreach ($valueItem as $childValueKey => $childValueItem) {
                                                $value[$childValueKey] = \BetterLinks\Helper::sanitize_text_or_array_field($childValueItem);
                                            }
                                        }
                                        $tempTwoSanitizeData[] = $value;
                                    }
                                }
                                $tempSanitizeData[$innerKey] = $tempTwoSanitizeData;
                            } elseif ($innerSchema['type'] === 'object') {
                                $tempThreeSanitizeData = [];
                                if (isset($tempData['extra']) && is_array($tempData['extra'])) {
                                    foreach ($tempData['extra'] as $extraKey => $extraItem) {
                                        $tempThreeSanitizeData[$extraKey] = sanitize_text_field($extraItem);
                                    }
                                }
                                $tempSanitizeData[$innerKey] = $tempThreeSanitizeData;
                            }
                        }
                    }
                    $data[$key] = $tempSanitizeData;
                } elseif ( in_array( $key, ['tags_id', 'favorite', 'analytic'] ) ) {
                    $result = (is_array($POST[$key]) ? $POST[$key] : json_decode(html_entity_decode(stripslashes($POST[$key])), true));
                    $data[$key] = \BetterLinks\Helper::sanitize_text_or_array_field($result);
                }elseif( in_array( $key, ['enable_password', 'password'] ) ) { // password protected parameters
                    $data[$key] = \BetterLinks\Helper::sanitize_text_or_array_field($POST[$key]);
                }
            }
        }
        return $data;
    }
    public function insert_link($arg)
    {
        if (isset($arg['short_url']) && ! \BetterLinks\Helper::is_exists_short_url($arg['short_url'])) {
            // Start Transaction
            global $wpdb;
            $wpdb->query("START TRANSACTION");
            $lookFor = array_combine(array_keys($this->links_schema()), array_keys($this->links_schema()));
            $params = array_intersect_key($arg, $lookFor);
            // insert link
            $id = \BetterLinks\Helper::insert_link(apply_filters('betterlinks/api/params', $params));
            $term_data = \BetterLinks\Helper::insert_terms_and_terms_relationship($id, $arg);
            $wpdb->query("COMMIT");
            // for instant create category system
            foreach ($term_data as $key => $value) {
                if(empty($value["term_type"])){
                    continue;
                }
                if($value["term_type"] === "tags"){
                    $arg['tags_data'][] = $value;
                }
                if($value["term_type"] === "category"){
                    $arg['cat_id'] = $value["term_id"];
                    $arg['cat_data'] = $value;
                }
            }
            if (BETTERLINKS_EXISTS_LINKS_JSON) {
                $params['ID'] = $id;
                $params['cat_id'] = $arg['cat_id'];
                \BetterLinks\Helper::insert_json_into_file(trailingslashit(BETTERLINKS_UPLOAD_DIR_PATH) . 'links.json', $params);
            }
            $response = array_merge($arg, [
                'ID' => strval($id),
            ]);
            return $response;
        }
        return false;
    }
    public function update_link($arg)
    {
        // Start Transaction
        global $wpdb;
        $wpdb->query("START TRANSACTION");
        $lookFor = array_combine(array_keys($this->links_schema()), array_keys($this->links_schema()));
        $params = array_intersect_key($arg, $lookFor);
        $old_short_url = isset($arg['old_short_url']) ? $arg['old_short_url'] : '';
        // update link
        $id = \BetterLinks\Helper::insert_link(apply_filters('betterlinks/api/params', $params), true);
        $term_data = \BetterLinks\Helper::insert_terms_and_terms_relationship($id, $arg);
        $wpdb->query("COMMIT");
        foreach ($term_data as $key => $value) {
            if(empty($value["term_type"])){
                continue;
            }
            if($value["term_type"] === "tags"){
                $arg['tags_data'][] = $value;
            }
            if($value["term_type"] === "category"){
                $arg['old_cat_id'] = $arg['cat_id'];
                $arg['cat_id'] = $value["term_id"];
                $arg['cat_data'] = $value;
            }
        }
        if (BETTERLINKS_EXISTS_LINKS_JSON) {
            $params['cat_id'] = $arg['cat_id'];
            \BetterLinks\Helper::update_json_into_file(trailingslashit(BETTERLINKS_UPLOAD_DIR_PATH) . 'links.json', $params, $old_short_url);
        }
        return $arg;
    }
    public function update_link_favorite($args)
    {
        if (isset($args["ID"], $args["data"])) {
            $id = absint($args["ID"]);
            $data = wp_json_encode($args["data"]);
            global $wpdb;
            $table = $wpdb->prefix . 'betterlinks';
            return $wpdb->query(
                $wpdb->prepare(
                    "UPDATE $table
                    SET favorite = %s
                    WHERE ID = %d LIMIT 1",
                    $data,
                    $id
                )
            );
        }
    }
    public function delete_link($args)
    {
        \BetterLinks\Helper::delete_link($args['ID']);
        if (BETTERLINKS_EXISTS_LINKS_JSON) {
            \BetterLinks\Helper::delete_json_into_file(trailingslashit(BETTERLINKS_UPLOAD_DIR_PATH) . 'links.json', $args['short_url']);
        }
        return true;
    }
}
