<?php

if (!class_exists('pricelist_wc_data')) {

    class pricelist_wc_data {
        
        protected $html_elements = array(
            'a' => true,
            'abbr' => true,
            'address' => true,
            'area' => true,
            'article' => true,
            'aside' => true,
            'audio' => true,
            'b' => true,
            'base' => true,
            'bdi' => true,
            'bdo' => true,
            'blockquote' => true,
            'body' => true,
            'br' => true,
            'button' => true,
            'canvas' => true,
            'caption' => true,
            'cite' => true,
            'code' => true,
            'col' => true,
            'colgroup' => true,
            'command' => true,
            'datalist' => true,
            'dd' => true,
            'del' => true,
            'details' => true,
            'dfn' => true,
            'div' => true,
            'dl' => true,
            'dt' => true,
            'em' => true,
            'embed' => true,
            'fieldset' => true,
            'figcaption' => true,
            'figure' => true,
            'footer' => true,
            'form' => true,
            'h1' => true,
            'h2' => true,
            'h3' => true,
            'h4' => true,
            'h5' => true,
            'h6' => true,
            'head' => true,
            'header' => true,
            'hgroup' => true,
            'hr' => true,
            'html' => true,
            'i' => true,
            'iframe' => true,
            'img' => true,
            'input' => true,
            'ins' => true,
            'kbd' => true,
            'keygen' => true,
            'label' => true,
            'legend' => true,
            'li' => true,
            'link' => true,
            'map' => true,
            'mark' => true,
            'menu' => true,
            'meta' => true,
            'meter' => true,
            'nav' => true,
            'noscript' => true,
            'object' => true,
            'ol' => true,
            'optgroup' => true,
            'option' => true,
            'output' => true,
            'p' => true,
            'param' => true,
            'pre' => true,
            'progress' => true,
            'q' => true,
            'rp' => true,
            'rt' => true,
            'ruby' => true,
            's' => true,
            'samp' => true,
            'script' => true,
            'section' => true,
            'select' => true,
            'small' => true,
            'source' => true,
            'span' => true,
            'strong' => true,
            'style' => true,
            'sub' => true,
            'summary' => true,
            'sup' => true,
            'table' => true,
            'tbody' => true,
            'td' => true,
            'textarea' => true,
            'tfoot' => true,
            'th' => true,
            'thead' => true,
            'time' => true,
            'title' => true,
            'tr' => true,
            'track' => true,
            'u' => true,
            'ul' => true,
            'var' => true,
            'video' => true,
            'wbr' => true);
            
        protected $void_elements = array(
            'area' => true, 
            'base' => true, 
            'br' => true, 
            'col' => true, 
            'command' => true, 
            'embed' => true, 
            'hr' => true, 
            'img' => true, 
            'input' => true, 
            'keygen' => true, 
            'link' => true, 
            'meta' => true, 
            'param' => true, 
            'source' => true, 
            'track' => true, 
            'wbr');
            
        protected $code_point_replacements = array(
            '80' => '&#8364;',
            '81' => '',
            '82' => '&#8218;',
            '83' => '&#402;',
            '84' => '&#8222;',
            '85' => '&#8230;',
            '86' => '&#8224;',
            '87' => '&#8225;',
            '88' => '&#710;',
            '89' => '&#8240;',
            '8A' => '&#352;',
            '8B' => '&#8249;',
            '8C' => '&#338;',
            '8D' => '',
            '8E' => '&#381;',
            '8F' => '',
            '90' => '',
            '91' => '&#8216;',
            '92' => '&#8217;',
            '93' => '&#8220;',
            '94' => '&#8221;',
            '95' => '&#8226;',
            '96' => '&#8211;',
            '97' => '&#8212;',
            '98' => '&#732;',
            '99' => '&#8482;',
            '9A' => '&#353;',
            '9B' => '&#8250;',
            '9C' => '&#339;',
            '9D' => '',
            '9E' => '&#382;',
            '9F' => '&#376;');
        
        protected $categories;
        protected $products;
        
        public function getCategories($options) {
            $this->loadCategories($options);
            return $this->categories;
        }
        
        public function getData($options) {
            $this->loadCategories($options);
            $this->loadProducts($options);
            
            return $this->categories;
        }
        
        protected function loadCategories(&$options) {
            $this->categories = [];
            $results = get_categories([
                'taxonomy' => 'product_cat',
            ]);
            $order = count($results);
            $parents = [];
            $children = [];
            $options['category_slugs'] = [];
            foreach($results as $result)
            {
                $slug_pos = $id_pos = false;
                $category = (object)[
                    'term_id' => $result->term_id,
                    'name' => $result->name,
                    'slug' => $result->slug,
                    'parent' => $result->parent,
                    'description' => $result->description,
                    'count' => $result->count,
                    'products' => [],
                ];
                
                if ($category->parent == 0) {
                    $parents[$category->term_id] = $category;
                } else {
                    $children[$category->term_id] = $category;
                }
                
                if ($options['category_image']) {
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    $image = wp_get_attachment_image_src($thumbnail_id, 'full');
                    if ($image) {
                        $category->image = $image[0];
                    }
                }
                
                $this->categories[$category->term_id] = $category;
            }
            
            foreach ($parents as $parent) {
                $parent->level = 0;
                $this->findChildren($options, $children, $parent, $order);
            }
            
        }
        
        protected function loadCategoryInclusion(&$options, &$category) {
            $category->include = true;
        }
        
        protected function loadCategoryOrder(&$options, &$category, &$order) {
            $category->order = $order++;
        }
        
        protected function findChildren(&$options, $children, &$parent, &$order) {
            $parent->children = [];
            $this->loadCategoryInclusion($options, $parent);
            $parent->hasContent = $parent->count > 0 && $parent->include;
            if ($parent->include) $this->loadCategoryOrder($options, $parent, $order);
            $parent->derivedOrder = PHP_INT_MAX;
            foreach ($children as $child) {
                if ($child->parent != $parent->term_id) continue;
                
                unset($children[$child->term_id]);
                $child->level = $parent->level+1;
                $this->findChildren($options, $children, $child, $order);
                
                if (!$child->include && !$child->hasContent) continue;
                
                $parent->children[$child->term_id] = $child->term_id;
                
                if (!$parent->hasContent) $parent->hasContent = $child->hasContent;
                $parent->derivedOrder = min($parent->derivedOrder, $child->order-.001);
            }
            if (!isset($parent->order)) {
                $parent->order = $parent->derivedOrder;
            }
            unset($parent->derivedOrder);
        }
        
        protected function loadProducts($options) {
            $args = ['limit' => -1, 'status' => 'publish', 'visibility' => 'catalog'];
            if (!empty($options['category_slugs'])) $args['category'] = $options['category_slugs'];
            $result = wc_get_products($args);
            $this->products = [];
            foreach($result as $product) {
                $this->products[] = $this->createProductModel($options, $product);
            }
            foreach ($this->categories as $category) {
                $prices = [];
                foreach ($category->products as $product_obj) {
                    $prices[] = $product_obj->Price ?? 0;
                }
                array_multisort($prices, SORT_ASC, $category->products);
            }
        }
        
        protected function createProductModel($options, $product, $isAttachment = false) {
            $model = (object) [
                'name' => $product->get_name(),
                'ID' => $product->get_id(),
                'Price' => $product->get_price(),
                'type' => get_class($product)
            ];
            
            if (!$isAttachment) {
                foreach ($product->get_category_ids() as $cat_id) {
                    if (isset($this->categories[$cat_id]) && $this->categories[$cat_id]->include) {
                        $this->categories[$cat_id]->products[] = $model;
                    }
                }
            }
            
            if ($options['product_image']) {
                if ($isAttachment) {
                    $img = wp_get_attachment_image_src($product->get_image_id(), 'full');
                    $model->Product_image = $img === false ? false : $img[0];
                } else {
                    $model->Product_image = get_the_post_thumbnail_url($product->get_id(), 'full');
                }
            }
            
            if ($options['description']){
                $model->Description = $this->setExplodedDescription($product->get_description(),$product->get_id(), $options);
            }
            if ($options['short_description']) {
                $model->Short_description = $this->setExplodedDescription($product->get_short_description(), $product->get_id(), $options);
            }
            return $model;
        }
        
        protected static function remove_last ($needle, &$haystack) {
            $n = count($haystack);
            $keys = array_keys($haystack);
            for ($i = $n-1; $i >= 0; $i--) {
                if ($haystack[$keys[$i]] === $needle) {
                    unset($haystack[$keys[$i]]);
                    return true;
                }
            }
            return false;
        }

        protected function setExplodedDescription($desc, $product_id, $options) {
            $limit = $options['description_limit'] ?? 55;
            if ($limit == 0) return $desc;

            
            $preserveTags = true;
            
            $sep = '{inner-join-pricelist-plugin}';
            $arr = explode($sep, str_replace(['<','>'], [$sep.'<','>'.$sep], $desc));
            $n = count($arr);
            
            $sum = 0;
            $more = false;
            $tagStack = array();
            $openTags = array();
            $closedTags = array();
            
            for ($i = 0; $i < $n; $i++) {
                $row = $arr[$i];
                $arr[$i] = '';
                if (strlen($row) === 0) continue;
                
                if ($row[0] !== '<') {
                    if ($more) {
                        continue;
                    }
                    $words = preg_split('/\s+/', $row, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE);
                    if ($sum + count($words) > $limit) {
                        $row = substr($row, 0, $words[$limit-$sum][1]);
                        $row .= '... ';
                        $row .= $this->readMore($product_id, $options);
                        $more = true;
                        while (!empty($openTags)) {
                            $row .= '</' . array_pop($openTags) . '>';
                        }
                    }
                    $sum += count($words);
                } else if (!$preserveTags) {
                    continue;
                } else {
                    if (!preg_match('/\w+/', $row, $tagName)) continue;
                    $tagName = $tagName[0];
                    if ($sum > $limit) {
                        continue;
                    } else {
                        if (!isset($this->html_elements[$tagName])) {
                            $row = htmlentities($row);
                        } elseif (isset($this->void_elements[$tagName])) {
                        } elseif ($row[1] !== '/') {
                            $openTags[] = $tagName;
                        } else {
                            if (self::remove_last($tagName, $closedTags)) {
                                continue;
                            } elseif ($openTags[count($openTags)-1] === $tagName) {
                                array_pop($openTags);
                            } elseif (!in_array($tagName, $openTags)) {
                                $row = htmlentities($row);
                            } else {
                                $row = '';
                                do {
                                    $tag = array_pop($openTags);
                                    $closedTags[] = $tag;
                                    $row .= '</' . $tag . '>';
                                } while (!empty($openTags) && $openTags[count($openTags)-1] !== $tagName);
                            }
                        }
                    }
                }
                $arr[$i] = $row;
            }
            while (!empty($openTags)) {
                $arr[] = '</' . array_pop($openTags) . '>';
            }
            $result = do_shortcode(implode($arr));
            $result = preg_replace_callback('/[\x{80}-\x{9F}]/u', function ($m) {
                $char = current($m);
                $char = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $char);
                $char = ltrim(strtoupper(bin2hex($char)));
                return $this->code_point_replacements[$char];
            }, $result);
            
            return $result;
        }
        
        protected function readMore($product_id, $options) {
            return '';
        }
    }
}
?>