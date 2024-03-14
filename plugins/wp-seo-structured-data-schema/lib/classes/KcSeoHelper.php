<?php

if (!class_exists('KcSeoSettings')):

    class KcSeoHelper
    {
        function verifyNonce() {
            $nonce = !empty($_REQUEST['_kcseo_nonce']) ? sanitize_text_field($_REQUEST['_kcseo_nonce']) : null;
            if (!wp_verify_nonce($nonce, $this->nonceText())) {
                return false;
            }

            return true;
        }

        function nonceText() {
            return "kcseo_nonce_secret_text";
        }

        function isValidBase64($string = null) {
            $decoded = @base64_decode($string, true);
            // Check if there is no invalid character in string
            if (!@preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) {
                return false;
            }

            // Decode the string in strict mode and send the response
            if (!@base64_decode($string, true)) {
                return false;
            }

            // Encode and compare it to original one
            if (@base64_encode($decoded) != $string) {
                return false;
            }

            return true;
        }

        function get_post_type_list() {
            $post_types = get_post_types(
                array(
                    'public' => true
                )
            );
            $exclude = array('attachment', 'revision', 'nav_menu_item');
            foreach ($exclude as $ex) {
                unset($post_types[$ex]);
            }

            return $post_types;
        }

        function kcSeoPostTypes() {
            $post_types = get_post_types(
                array(
                    '_builtin' => true
                )
            );
            $exclude = array('attachment', 'revision', 'nav_menu_item');
            foreach ($exclude as $ex) {
                unset($post_types[$ex]);
            }

            return $post_types;
        }

        /**
         * Sanitize field value
         *
         * @param string $fieldId
         * @param array  $field
         * @param null   $value
         *
         * @return array|null
         * @internal param $value
         */
        function sanitize($fieldId, $field = array(), $value = null) {
            $newValue = null;
            $type = (!empty($field['type']) ? sanitize_text_field($field['type']) : 'text');
            if (is_array($field) && $value) {
                if (in_array($fieldId, ['description', 'articleBody', 'review_body'])) {
                    $newValue = KcSeoHelper::filter_content($value);
                } else {
                    if ($type == 'url') {
                        $newValue = esc_url($value);
                    } else if ($type == 'textarea') {
                        $newValue = wp_kses($value, []);
                    } else if ($field['type'] == 'group' && !empty($field['fields'])) {
                        $newGValue = [];
                        $groupValue = is_array($value) ? $value : [];
                        foreach ($groupValue as $gId => $gValue) {
                            $newVItem = [];
                            foreach ($field['fields'] as $gFid => $fieldItem) {
                                if (isset($gValue[$gFid])) {
                                    $newVItem[$gFid] = $this->sanitize($gFid, $fieldItem, $gValue[$gFid]);
                                }
                            }
                            array_push($newGValue, $newVItem);
                        }
                        $newValue = $newGValue;
                    } else {
                        $newValue = sanitize_text_field($value);
                    }
                }

            }

            return $newValue;
        }


        function sanitizeOutPut($value, $type = 'text') {
            $newValue = null;
            if ($value) {
                if ($type == 'text') {
                    $newValue = esc_html(stripslashes($value));
                } elseif ($type == 'url') {
                    $newValue = esc_url(stripslashes($value));
                } elseif ($type == 'textarea') {
                    $newValue = esc_textarea(stripslashes($value));
                } else {
                    $newValue = esc_html(stripslashes($value));
                }
            }

            return $newValue;
        }

        static function get_same_as($value) {
            $sameAs = null;
            if ($value) {
                $sameAsRaw = preg_split('/\r\n|\r|\n/', $value);
                $sameAsRaw = !empty($sameAsRaw) ? array_filter($sameAsRaw) : array();
                if (!empty($sameAsRaw) && is_array($sameAsRaw)) {
                    if (1 < count($sameAsRaw)) {
                        $sameAs = $sameAsRaw;
                    } else {
                        $sameAs = $sameAsRaw[0];
                    }
                }
            }

            return $sameAs;
        }


        function imageInfo($attachment_id) {
            $data = array();
            $imgData = wp_get_attachment_metadata($attachment_id);
            $data['url'] = wp_get_attachment_url($attachment_id);
            $data['width'] = !empty($imgData['width']) ? absint($imgData['width']) : 0;
            $data['height'] = !empty($imgData['height']) ? absint($imgData['height']) : 0;

            return $data;
        }

        static function filter_content($content, $limit = 0) {
            $content = preg_replace('#\[[^\]]+\]#', '', wp_filter_nohtml_kses($content));
            $content = stripslashes($content);
            $content = convert_invalid_entities($content);
            $content = self::characterToHTMLEntity($content);
            if ($limit && strlen($content) > $limit) {
                $content = mb_substr($content, 0, $limit, "utf-8");
                $content = preg_replace('/\W\w+\s*(\W*)$/', '$1', $content);
            }

            $content = str_replace('&nbps;', ' ', $content);
            $content = str_replace(PHP_EOL, ' ', $content);
            $content = str_replace("\r", '', $content);

            return $content;
        }

        static function characterToHTMLEntity($str) {
            $replace = array(
                "'",
                '&',
                '<',
                '>',
                '€',
                '‘',
                '’',
                '“',
                '”',
                '–',
                '—',
                '¡',
                '¢',
                '£',
                '¤',
                '¥',
                '¦',
                '§',
                '¨',
                '©',
                'ª',
                '«',
                '¬',
                '®',
                '¯',
                '°',
                '±',
                '²',
                '³',
                '´',
                'µ',
                '¶',
                '·',
                '¸',
                '¹',
                'º',
                '»',
                '¼',
                '½',
                '¾',
                '¿',
                'À',
                'Á',
                'Â',
                'Ã',
                'Ä',
                'Å',
                'Æ',
                'Ç',
                'È',
                'É',
                'Ê',
                'Ë',
                'Ì',
                'Í',
                'Î',
                'Ï',
                'Ð',
                'Ñ',
                'Ò',
                'Ó',
                'Ô',
                'Õ',
                'Ö',
                '×',
                'Ø',
                'Ù',
                'Ú',
                'Û',
                'Ü',
                'Ý',
                'Þ',
                'ß',
                'à',
                'á',
                'â',
                'ã',
                'ä',
                'å',
                'æ',
                'ç',
                'è',
                'é',
                'ê',
                'ë',
                'ì',
                'í',
                'î',
                'ï',
                'ð',
                'ñ',
                'ò',
                'ó',
                'ô',
                'õ',
                'ö',
                '÷',
                'ø',
                'ù',
                'ú',
                'û',
                'ü',
                'ý',
                'þ',
                'ÿ',
                'Œ',
                'œ',
                '‚',
                '„',
                '…',
                '™',
                '•',
                '˜'
            );

            $search = array(
                '&#8217;',
                '&amp;',
                '&lt;',
                '&gt;',
                '&euro;',
                '&lsquo;',
                '&rsquo;',
                '&ldquo;',
                '&rdquo;',
                '&ndash;',
                '&mdash;',
                '&iexcl;',
                '&cent;',
                '&pound;',
                '&curren;',
                '&yen;',
                '&brvbar;',
                '&sect;',
                '&uml;',
                '&copy;',
                '&ordf;',
                '&laquo;',
                '&not;',
                '&reg;',
                '&macr;',
                '&deg;',
                '&plusmn;',
                '&sup2;',
                '&sup3;',
                '&acute;',
                '&micro;',
                '&para;',
                '&middot;',
                '&cedil;',
                '&sup1;',
                '&ordm;',
                '&raquo;',
                '&frac14;',
                '&frac12;',
                '&frac34;',
                '&iquest;',
                '&Agrave;',
                '&Aacute;',
                '&Acirc;',
                '&Atilde;',
                '&Auml;',
                '&Aring;',
                '&AElig;',
                '&Ccedil;',
                '&Egrave;',
                '&Eacute;',
                '&Ecirc;',
                '&Euml;',
                '&Igrave;',
                '&Iacute;',
                '&Icirc;',
                '&Iuml;',
                '&ETH;',
                '&Ntilde;',
                '&Ograve;',
                '&Oacute;',
                '&Ocirc;',
                '&Otilde;',
                '&Ouml;',
                '&times;',
                '&Oslash;',
                '&Ugrave;',
                '&Uacute;',
                '&Ucirc;',
                '&Uuml;',
                '&Yacute;',
                '&THORN;',
                '&szlig;',
                '&agrave;',
                '&aacute;',
                '&acirc;',
                '&atilde;',
                '&auml;',
                '&aring;',
                '&aelig;',
                '&ccedil;',
                '&egrave;',
                '&eacute;',
                '&ecirc;',
                '&euml;',
                '&igrave;',
                '&iacute;',
                '&icirc;',
                '&iuml;',
                '&eth;',
                '&ntilde;',
                '&ograve;',
                '&oacute;',
                '&ocirc;',
                '&otilde;',
                '&ouml;',
                '&divide;',
                '&oslash;',
                '&ugrave;',
                '&uacute;',
                '&ucirc;',
                '&uuml;',
                '&yacute;',
                '&thorn;',
                '&yuml;',
                '&OElig;',
                '&oelig;',
                '&sbquo;',
                '&bdquo;',
                '&hellip;',
                '&trade;',
                '&bull;',
                '&asymp;'
            );

            //REPLACE VALUES
            $str = str_replace($search, $replace, $str);

            //RETURN FORMATED STRING
            return $str;
        }

        function fix1_2DataMigration() {
            global $KcSeoWPSchema;
            $fix_1_2 = get_option($KcSeoWPSchema->options['1_2_fix']);
            $installed_version = get_option($KcSeoWPSchema->options['installed_version']);
            if (empty($fix_1_2) && empty($installed_version)) {
                global $wpdb;
                $ids = array();
                $posts = $wpdb->get_results("
                    SELECT posts.ID FROM {$wpdb->prefix}posts as posts
                              WHERE posts.post_type = 'post'
                              AND EXISTS (
                                SELECT * FROM `{$wpdb->prefix}postmeta` as postmeta
                                 WHERE postmeta.`meta_key` LIKE '_schema_%'
                                  AND postmeta.`post_id`=posts.ID
                        )");
                if ($posts) {
                    foreach ($posts as $id) {
                        $ids[] = $id->ID;
                    }
                }
                $pages = $wpdb->get_results("
                    SELECT posts.ID FROM {$wpdb->prefix}posts as posts
                              WHERE posts.post_type = 'page'
                              AND EXISTS (
                                SELECT * FROM `{$wpdb->prefix}postmeta` as postmeta
                                 WHERE postmeta.`meta_key` LIKE '_schema_%'
                                  AND postmeta.`post_id`=posts.ID
                        )");
                if ($pages) {
                    foreach ($pages as $id) {
                        $ids[] = $id->ID;
                    }
                }


                if (!empty($ids)) {
                    $schemaFields = KcSeoOptions::getSchemaTypes();
                    foreach ($ids as $id) {
                        foreach ($schemaFields as $schemaID => $schema) {
                            $schemaMetaId = $KcSeoWPSchema->KcSeoPrefix . $schemaID;
                            $getRawMeta = get_post_meta($id, $schemaMetaId, true);
                            $metaData = array();
                            if (!empty($getRawMeta)) {
                                if ($KcSeoWPSchema->isValidBase64($getRawMeta)) {
                                    $metaData = @unserialize(base64_decode($getRawMeta));
                                } else {
                                    $metaData = @unserialize($getRawMeta);
                                }
                            }
                            $metaData = (!empty($metaData) ? $metaData : array());
                            if (!empty($metaData) && is_array($metaData)) {
                                $metaData['active'] = true;
                                update_post_meta($id, $schemaMetaId, $metaData);
                            }
                        }
                    }
                }

                update_option($KcSeoWPSchema->options['1_2_fix'], true);
            }
        }

        function advertisingBlock() {
            ?>
            <div class='kc-get-pro'>
                <h3><?php _e("Pro Version Features", "wp-seo-structured-data-schema") ?></h3>
                <ol>
                    <li><?php _e("Includes Auto-fill function <---Popular", "wp-seo-structured-data-schema") ?></li>
                    <li><?php _e("Supports Custom Post Types beyond default page and posts", "wp-seo-structured-data-schema") ?></li>
                    <li><?php _e("Supports WordPress Multisite", "wp-seo-structured-data-schema") ?></li>
                    <li><?php _e("Supports more schema types:", "wp-seo-structured-data-schema") ?>
                        <ol>
                            <li><?php _e("Books", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("Courses", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("Person", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("Job Postings", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("Movies", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("Music", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("Recipe", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("TV Episode", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("QAPage", "wp-seo-structured-data-schema") ?></li>
                            <li><?php _e("Item List", "wp-seo-structured-data-schema") ?></li>
                        </ol>
                    </li>
                </ol>
                <div class="kc-pro-action"><a class='button button-primary'
                                              href='https://wpsemplugins.com/downloads/wordpress-schema-plugin/'
                                              target='_blank'><?php _e("Get the Pro Version", "wp-seo-structured-data-schema") ?></a>
                </div>
            </div>
            <?php
        }

        function fix2_5_7_to_2_5_8() {
            global $KcSeoWPSchema;
            $installed_version = get_option($KcSeoWPSchema->options['installed_version']);
            if (version_compare($installed_version, '2.5.7', '<=')) {
                $settings = get_option($KcSeoWPSchema->options['settings']);
                if (isset($settings['contact']['contactType'])) {
                    $settings['contact']['contactType'] = strtolower($settings['contact']['contactType']);
                    update_option($KcSeoWPSchema->options['settings'], $settings);
                }
                if (isset($settings['area_served']) && !empty($settings['area_served'])) {

                    $cList = KcSeoOptions::getCountryList();
                    $areas = array();
                    foreach ($settings['area_served'] as $area) {
                        $key = array_search($area, $cList);
                        if ($key) {
                            $areas[] = $key;
                        }
                    }
                    if (!empty($areas)) {
                        $settings['area_served'] = $areas;
                        update_option($KcSeoWPSchema->options['settings'], $settings);
                    }
                }

            }
        }
    }

endif;
