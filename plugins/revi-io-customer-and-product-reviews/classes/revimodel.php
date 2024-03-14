<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class revimodel
{
    var $REVI_API_URL;
    var $prefix;
    var $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->prefix = $this->wpdb->prefix;
        $this->REVI_API_URL = REVI_API_URL;
    }


    ////////////////////// REVI ORDERS ///////////////////////

    public function addReviOrder($id_order, $status)
    {
        $date_sent = date('Y-m-d H:i:s');
        if (!$this->checkReviOrderExist($id_order)) {
            $this->insertReviOrder($id_order, $status, $date_sent);
        } else {
            $this->updateReviOrders($id_order, $status, $date_sent);
        }
    }

    public function insertReviOrder($id_order, $status, $date_sent = null)
    {
        if (empty($date_sent)) {
            $date_sent = date('Y-m-d H:i:s');
        }
        $sql = "INSERT INTO `revi_orders`(id_order, status, date_sent) VALUES ('" . $id_order . "', '" . $status . "', '" . $date_sent . "')";
        $this->wpdb->query($sql);
    }

    public function updateReviOrders($id_order, $status, $date_sent = null)
    {
        if (empty($date_sent)) {
            $date_sent = date('Y-m-d H:i:s');
        }
        $sql = "UPDATE revi_orders SET status = '" . $status . "', date_sent = '" . $date_sent . "' WHERE id_order = '" . $id_order . "'";
        $this->wpdb->query($sql);
    }

    public function checkReviOrderExist($id_order)
    {
        $sql = "SELECT id_order FROM revi_orders WHERE id_order = '" . $id_order . "'";
        $result = $this->wpdb->get_row($sql);
        if (!empty($result->id_order)) {
            return $result->id_order;
        } else {
            return 0;
        }
    }

    ////////////////////// PS ORDERS ///////////////////////

    /*
    * Devuelve Order
    */
    public function getOrder($id_order)
    {
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $sql = "SELECT O.ID as id_order, O.post_date_gmt as order_date
		FROM " . $this->prefix . "posts O 
		WHERE O.post_type = 'shop_order'
        AND O.ID = '$id_order'
        GROUP BY O.ID 
        LIMIT 1";

        return $this->wpdb->get_row($sql);
    }

    /*
    * Devuelve orders que no estén en revi_orders
    */
    public function getOrders()
    {
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $sql = "SELECT O.ID as id_order, O.post_date_gmt as order_date
		FROM " . $this->prefix . "posts O 
        WHERE O.post_date_gmt > NOW() - INTERVAL 400 DAY 
        AND O.ID NOT IN (SELECT RO.id_order FROM revi_orders RO)
        AND O.post_type = 'shop_order' 
        GROUP BY O.ID 
        ORDER BY O.post_date_gmt DESC
        LIMIT 100";

        return $this->wpdb->get_results($sql);
    }

    /*
    * Devuelve orders que están en revi_orders y se han actualizado
    */
    public function getOrdersUpdated()
    {
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $sql = "SELECT O.ID as id_order
        FROM revi_orders RO
        LEFT JOIN " . $this->prefix . "posts O ON RO.id_order = O.ID
        WHERE RO.date_sent < O.post_modified_gmt
        AND O.post_type = 'shop_order' 
        ORDER BY O.post_modified_gmt DESC
        LIMIT 50";

        return $this->wpdb->get_results($sql);
    }

    /*
    * Devuelve Orders con un estado concreto que no se hayan enviado ya
    */
    function getOrdersByStatus($status)
    {
        //WORDPRESS DA FALLO DE BIG QUERYS A VECES, CON ESTO SE SOLUCIONA
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $selected_status = get_option('REVI_ORDER_STATUSES');

        if ($status == '2') {
            $selected_status = get_option('REVI_ORDER_STATUSES');
            if (empty($selected_status)) {
                $selected_status = ['wc-completed'];
            }
        } else if ($status == '0') {
            $selected_status = ['wc-cancelled'];
        }

        $sql = "SELECT O.ID as id_order, O.post_modified_gmt as date_status_upd
        FROM " . $this->prefix . "posts O 
        WHERE O.ID NOT IN (SELECT RO.id_order FROM revi_orders RO WHERE RO.status = '$status') 
        AND O.ID IN (SELECT RO.id_order FROM revi_orders RO) 
        AND O.post_type = 'shop_order' AND O.post_status IN ('" . implode("','", $selected_status) . "') 
        GROUP BY O.ID 
        ORDER BY O.post_date_gmt DESC
        LIMIT 200";

        return $this->wpdb->get_results($sql);
    }

    ///////////////////////////// ORDER PRODUCTS ////////////////////

    public function getOrderProducts($id_order)
    {
        $wc_order = wc_get_order($id_order);

        $products_data = array();
        if (!empty($wc_order)) {
            foreach ($wc_order->get_items() as $item_id => $item) {

                if (empty($item)) continue;

                $product = $item->get_product();
                if (empty($product)) continue;

                $product_price = (float)$product->get_price() + (float)$item->get_total_tax();
                $order_product = array(
                    'id_order' => $id_order,
                    'quantity' => $item->get_quantity(),
                    'price_unit' => $product_price,
                    'taxes' => $item->get_total_tax(),
                    'total_price' => $product_price * $item->get_quantity(),
                );

                $order_product['id_product'] = $this->get_id_main_product($item->get_product_id());

                $order_product['vat'] = 0;
                if ($item->get_total_tax() > 0) {
                    if (!empty($product->get_price())) {
                        $order_product['vat'] = round((($order_product['total_price'] / $product->get_price()) - 1) * 100, 2);
                    }
                }
                if (is_infinite($order_product['vat'])) {
                    unset($order_product['vat']);
                }

                // Only for product variation
                if ($product->is_type('variation')) {
                    $order_product['id_shop_product_combination'] = $product->get_variation_id();
                }
                $products_data[] = $order_product;
            }
        }
        return $products_data;
    }

    ///////////////////////////// REVI PRODUCTS ////////////////////

    public function getReviProduct($id_product)
    {
        $sql = "SELECT * FROM revi_products WHERE id_product = '" . $id_product . "'";
        return $this->wpdb->get_row($sql);
    }

    public function addReviProduct($id_product, $productData, $date_sent = null)
    {
        if (!$this->checkReviProductExist($id_product)) {
            return $this->insertReviProduct($productData, $date_sent);
        } else {
            return $this->updateReviProduct($id_product, $productData, $date_sent);
        }
    }

    public function checkReviProductExist($id_product)
    {
        $sql = "SELECT id_product FROM revi_products WHERE id_product = '" . $id_product . "'";
        return $this->wpdb->get_var($sql);
    }

    public function insertReviProduct($productData, $date_sent)
    {
        $sql = "INSERT INTO `revi_products`(id_product, num_ratings, avg_rating, date_sent) VALUES ('" . $productData['id_product'] . "', '" . $productData['num_ratings'] . "', '" . $productData['avg_rating'] . "', '" . $date_sent . "')";
        $this->wpdb->query($sql);
    }

    public function updateReviProduct($id_product, $productData, $date_sent)
    {
        if ($date_sent) {
            $sql = "UPDATE revi_products SET date_sent = '" . $date_sent . "'";
        } else {
            $sql = "UPDATE revi_products SET num_ratings = '" . $productData['num_ratings'] . "', avg_rating = '" . $productData['avg_rating'] . "'";
        }
        $sql .= " WHERE id_product = '" . $id_product . "'";
        $this->wpdb->query($sql);
    }

    ///////////////////////////// PRODUCTS ////////////////////

    //Busca el main product de WPML
    public function get_id_main_product($id_product)
    {
        if (function_exists('icl_object_id')) {
            $sql = "SELECT * FROM " . $this->wpdb->prefix . "icl_translations WHERE element_type LIKE 'post_product' AND element_id = '$id_product'";
            $trid = $this->wpdb->get_row($sql);

            if (!empty($trid->trid)) {
                $sql = "SELECT * FROM " . $this->wpdb->prefix . "icl_translations WHERE trid = '$trid->trid' AND source_language_code IS NULL";
                $translation_group = $this->wpdb->get_row($sql);
            }

            if (!empty($translation_group->element_id)) {
                $id_product = $translation_group->element_id;
            }
        }

        return $id_product;
    }

    public function get_product_language($id_product)
    {
        $lang = null;
        if (array_key_exists('wpml_post_language_details', $GLOBALS['wp_filter'])) {
            $language_data = apply_filters('wpml_post_language_details', null, $id_product);

            if (isset($language_data['language_code'])) {
                $lang = $language_data['language_code'];
            }
        }

        if (!empty($lang) && strlen($lang) > 0) {
            $lang = $this->parseLang($lang);
            return $lang;
        } else {
            return get_option('REVI_SELECTED_LANGUAGE');
        }
    }


    public function getProduct($id_product, $id_lang)
    {
        //WORDPRESS DA FALLO DE BIG QUERYS A VECES, CON ESTO SE SOLUCIONA
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $sql = "SELECT P.id_product, PL.name, P.price, P.ean13 as ean, PL.link_rewrite, PL.description, P.quantity as stock
            FROM " . $this->prefix . "product_lang PL
            LEFT JOIN " . $this->prefix . "product P ON P.id_product = PL.id_product
            WHERE PL.id_product = '" . $id_product . "' 
            AND PL.id_lang = " . $id_lang . "";
        return $this->wpdb->get_row($sql);
    }

    /**
     * Obtiene los productos que no se han enviado a Revi.
     **/
    public function getNumProductsNotSent()
    {
        //WORDPRESS DA FALLO DE BIG QUERYS A VECES, CON ESTO SE SOLUCIONA
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $sql = "SELECT COUNT(P.ID) as num_products 
        FROM " . $this->prefix . "posts P 
        WHERE P.ID NOT IN (SELECT RP.id_product FROM revi_products RP) 
        AND P.post_type = 'product'";

        return $this->wpdb->get_row($sql);
    }

    /**
     * Obtiene los productos que no se han enviado a Revi.
     **/
    public function getProductsNotSent($limit)
    {
        //WORDPRESS DA FALLO DE BIG QUERYS A VECES, CON ESTO SE SOLUCIONA
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $sql = "SELECT P.ID as id_product, P.post_title as name, P.post_content as description,
        M1.meta_value as price, M2.meta_value as regular_price, M3.meta_value as sku, 
        M4.meta_value as stock
        FROM " . $this->prefix . "posts P 
        LEFT JOIN " . $this->prefix . "postmeta M1 ON M1.post_id = P.ID AND M1.meta_key = '_sale_price'
        LEFT JOIN " . $this->prefix . "postmeta M2 ON M2.post_id = P.ID AND M2.meta_key = '_regular_price'
        LEFT JOIN " . $this->prefix . "postmeta M3 ON M3.post_id = P.ID AND M3.meta_key = '_sku'
        LEFT JOIN " . $this->prefix . "postmeta M4 ON M4.post_id = P.ID AND M4.meta_key = '_stock'
        WHERE P.ID NOT IN (SELECT RP.id_product FROM revi_products RP) 
        AND P.post_type = 'product'
        LIMIT $limit";

        return $this->wpdb->get_results($sql);
    }

    /**
     * Obtiene los productos que se han actualizado tras haberlos sincronizado con Revi.
     **/
    public function getNumProductsUpdated()
    {
        //WORDPRESS DA FALLO DE BIG QUERYS A VECES, CON ESTO SE SOLUCIONA
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $sql = "SELECT COUNT(P.ID) as num_products 
        FROM " . $this->prefix . "posts P 
        LEFT JOIN revi_products RP ON RP.id_product = P.ID
        WHERE P.post_type = 'product' 
        AND P.post_modified_gmt > RP.date_sent";

        return $this->wpdb->get_row($sql);
    }

    /**
     * Obtiene los productos que se han actualizado tras haberlos sincronizado con Revi.
     **/
    public function getProductsUpdated($limit)
    {
        //WORDPRESS DA FALLO DE BIG QUERYS A VECES, CON ESTO SE SOLUCIONA
        $this->wpdb->query('SET SQL_BIG_SELECTS = 1');

        $sql = "SELECT P.ID as id_product, P.post_title as name, P.post_content as description,
        M1.meta_value as price, M2.meta_value as regular_price, M3.meta_value as sku, 
        M4.meta_value as stock
        FROM " . $this->prefix . "posts P 
        LEFT JOIN revi_products RP ON RP.id_product = P.ID
        LEFT JOIN " . $this->prefix . "postmeta M1 ON M1.post_id = P.ID AND M1.meta_key = '_sale_price'
        LEFT JOIN " . $this->prefix . "postmeta M2 ON M2.post_id = P.ID AND M2.meta_key = '_regular_price'
        LEFT JOIN " . $this->prefix . "postmeta M3 ON M3.post_id = P.ID AND M3.meta_key = '_sku'
        LEFT JOIN " . $this->prefix . "postmeta M4 ON M4.post_id = P.ID AND M4.meta_key = '_stock'
        WHERE P.post_type = 'product' 
        AND P.post_modified_gmt > RP.date_sent
        LIMIT $limit";

        return $this->wpdb->get_results($sql);
    }

    /**
     * Num products left
     **/
    public function getNumProductsLeft()
    {
        $num_products_not_sent = $this->getNumProductsNotSent();
        $num_products_not_updated = $this->getNumProductsUpdated();

        return $num_products_not_sent->num_products + $num_products_not_updated->num_products;
    }

    /**
     * Obtener todos los productos para sincronizar con Revi
     **/
    public function getProductsToSend($limit = 20)
    {
        $result = array();

        $productsNotSent = $this->getProductsNotSent($limit);
        $productsUpdated = $this->getProductsUpdated($limit);

        if (!empty($productsNotSent)) {
            $result = array_merge($result, $productsNotSent);
        }

        if (!empty($productsUpdated)) {
            $result = array_merge($result, $productsUpdated);
        }

        $products = array();
        foreach ($result as $product) {

            $wc_product = wc_get_product($product->id_product);
            if (empty($wc_product)) {
                continue;
            }

            $aux_product = (array)$product;

            $aux_product['lang'] = $this->get_product_language($product->id_product);

            // $aux_product['url'] = get_permalink($product->id_product);
            $aux_product['url'] = $wc_product->get_permalink();

            $image_array = wp_get_attachment_image_src(get_post_thumbnail_id($product->id_product), 'full');
            $aux_product['photo_url'] = $image_array[0];

            $aux_product['ean'] = $this->getCombinationMetaValue($product->id_product, 'gtin', $product, null);
            $aux_product['brand'] = $this->getCombinationMetaValue($product->id_product, 'brand', $product, null);


            if (method_exists($wc_product, 'get_available_variations')) {

                $variations = $wc_product->get_available_variations();

                $combinations = array();
                foreach ($variations as $key => $variation) {
                    if (isset($variation['variation_is_active']) && $variation['variation_is_active']) {

                        $combination = array();
                        $combination['id_shop_product_combination'] = $variation['variation_id'];
                        $combination['sku'] = $this->getCombinationMetaValue($variation['variation_id'], 'sku', $product, $variation);
                        $combination['brand'] = $this->getCombinationMetaValue($variation['variation_id'], 'brand', $product, $variation);
                        $combination['ean'] = $this->getCombinationMetaValue($variation['variation_id'], 'gtin', $product, $variation);

                        $combination['photo_url'] = '';
                        if (isset($variation['image']) && !empty($variation['image'])) {
                            if (isset($variation['image']['url']) && !empty($variation['image']['url'])) {
                                $combination['photo_url'] = $variation['image']['url'];
                            }
                        }
                        array_push($combinations, $combination);
                    }
                }
            }
            $aux_product['combinations'] = $combinations;

            //Cambiamos el ID del producto por el del producto principal, no los IDS traducidos
            $aux_product['id_product'] = $this->get_id_main_product($product->id_product);
            $aux_product['id_product_parent'] = $product->id_product;

            echo '<pre>';
            print_r($aux_product);
            echo '</pre>';

            array_push($products, $aux_product);
        }

        return $products;
    }


    function getCombinationMetaValue($product_id, $meta_key_name, $product, $variation)
    {
        if (!empty($product->{$meta_key_name}) && strlen($product->{$meta_key_name}) > 0) {
            return $product->{$meta_key_name};
        }

        if (isset($variation["{$meta_key_name}"]) && !empty($variation["{$meta_key_name}"]) && strlen($variation["{$meta_key_name}"]) > 0) {
            return $variation["{$meta_key_name}"];
        }

        $yith_name = $this->getYithProductMetaData($product_id, $meta_key_name);
        if (!empty($yith_name) && strlen($yith_name) > 0) {
            return $yith_name;
        }

        if ($meta_key_name == 'brand') {
            $constant = PRODUCT_BRAND;
            $capital_name = 'BRAND';
        } else if ($meta_key_name == 'gtin') {
            $constant = PRODUCT_EAN;
            $capital_name = 'EAN';
        }

        if (!empty($constant)) {
            $wc_product = wc_get_product($product_id);

            foreach ($constant as $possible_meta_name) {
                if (strlen($wc_product->get_attribute($possible_meta_name))) {
                    return $wc_product->get_attribute($possible_meta_name);
                } else if (strlen(get_post_meta($product_id, $possible_meta_name, true))) {
                    return get_post_meta($product_id, $possible_meta_name, true);
                } else if (strlen($wc_product->get_meta($possible_meta_name))) { // PLUGIN Product GTIN (EAN, UPC, ISBN) for WooCommerce
                    return $wc_product->get_meta($possible_meta_name);
                } else {
                    $terms = wp_get_post_terms($wc_product->get_id(), $possible_meta_name);
                    $terms = reset($terms);
                    if (!empty($terms->name && strlen($terms->name) > 0)) {
                        return $terms->name;
                    }
                }
            }

            // Algunas tiendas utilizan numeraciones raras, tipo EAN-1 EAN-55, EAN-200, etc
            for ($i = 0; $i < 200; $i++) {
                if (strlen($wc_product->get_attribute("$capital_name-" . $i))) {
                    return $wc_product->get_attribute("$capital_name-" . $i);
                } else if (strlen($wc_product->get_attribute("$capital_name-" . $i))) {
                    return $wc_product->get_attribute("$capital_name-" . $i);
                }
            }
        }

        return '';
    }

    function getYithProductMetaData($id, $meta_key)
    {
        $sql = "SELECT meta_value FROM " . $this->prefix . "postmeta WHERE post_id = '" . $id . "' AND meta_key = 'yith_wcgpf_product_feed_configuration'";
        $data_value = $this->wpdb->get_results($sql);

        if (!empty($data_value)) {
            $unserialized_data = unserialize($data_value[0]->meta_value);

            if (!empty($unserialized_data[$meta_key])) {
                return $unserialized_data[$meta_key];
            }
        }

        return '';
    }


    ///////////////////////////// CATEGORIES CATEGORY ////////////////////

    public function getCategory($id_category)
    {
        $sql = "SELECT * FROM revi_categories WHERE id_category = '" . (int)$id_category . "'";
        return $this->wpdb->get_results($sql);
    }

    /////////////////////////////// COMMENTS //////////////////////////

    public function getProductComments($id_product, $lang)
    {
        $sql = "SELECT * FROM revi_comments WHERE id_product = '" . $id_product . "' AND status = '1' ORDER BY case lang when '" . $lang . "' then 1 else 2 end,date DESC LIMIT 10";
        return $this->wpdb->get_results($sql);
    }

    public function getCommentProducts($id_order)
    {
        $sql = "SELECT P.ID as id_product, P.post_title as name
        FROM revi_comments RC
        LEFT JOIN " . $this->prefix . "posts P ON RC.id_product = P.ID
        WHERE P.post_type = 'product'
        AND RC.id_order = '$id_order'
        GROUP BY P.ID";

        return $this->wpdb->get_results($sql);
    }

    public function addReviComment($comment)
    {
        if (!$this->checkReviCommentExist($comment['id_comment'])) {
            return $this->insertReviComment($comment);
        } else {
            return $this->updateReviComment($comment);
        }
    }

    public function insertReviComment($comment)
    {
        $sql = "INSERT INTO revi_comments(id_comment, id_order, id_shop, id_product, customer_name, customer_lastname, email, IP, date, comment, rating, status, lang, external, anonymous)
            VALUES('" . (int)$comment['id_comment'] . "', '" . $comment['id_order'] . "', '" . (int)$comment['id_shop'] . "', '" . $comment['id_product'] . "', '" . $comment['customer_name'] . "', '" . $comment['customer_lastname'] . "', '" . $comment['email'] . "', '" . $comment['IP'] . "', '" . $comment['date'] . "', '" . addslashes($comment['comment']) . "', '" . (float)$comment['rating'] . "', '" . (int)$comment['status'] . "', '" . $comment['lang'] . "', '" . $comment['external'] . "', '" . (int)$comment['anonymous'] . "')";
        $this->wpdb->query($sql);
    }

    public function updateReviComment($comment)
    {
        $sql = "UPDATE revi_comments SET comment = '" . addslashes($comment['comment']) . "', rating = '" . (float)$comment['rating'] . "', status = '" . (int)$comment['status'] . "' WHERE id_comment = '" . $comment['id_comment'] . "'";
        $this->wpdb->query($sql);
    }

    public function deleteReviComment($comment)
    {
        $sql = "DELETE FROM revi_comments WHERE id_comment = '" . (int)$comment['id_comment'] . "'";
        $this->wpdb->query($sql);
    }

    public function checkReviCommentExist($id_comment)
    {
        $sql = "SELECT * FROM revi_comments WHERE id_comment = '" . (int)$id_comment . "'";
        return $this->wpdb->get_row($sql);
    }

    public function getLastIDComment()
    {
        $sql = "SELECT id_comment FROM revi_comments where 1 ORDER BY id_comment DESC LIMIT 1";
        return $this->wpdb->get_var($sql);
    }

    /////////// URL NEW REVIEW ///////////////

    public function getNewReviewUrl($id_order)
    {
        $wc_order = wc_get_order($id_order);
        $iso_country = $wc_order->get_shipping_country();
        $lang = $this->getOrderLang($id_order, $iso_country);
        $customer_name = $wc_order->get_billing_first_name();
        $customer_lastname = $wc_order->get_billing_last_name();
        $email = $wc_order->get_billing_email();
        $currency = $wc_order->get_currency();
        $total_paid = $wc_order->get_total();
        $order_date = $wc_order->get_date_created()->date('Y-m-d H:i:s');

        if (!empty($wc_order)) {
            $data = [
                'id_order' => $id_order,
                'lang' =>  $lang,
                'customer_name' => $customer_name,
                'customer_lastname' => $customer_lastname,
                'email' => $email,
                'medium' => 1, // 0 Email, 1 Popup
                'currency' => $currency,
                'total_paid' => $total_paid,
                'order_date' => $order_date,
            ];

            $result = $this->reviCURL($this->REVI_API_URL . 'wsapi/review_encoded_string', "POST", $data);
            return json_decode($result);
        } else {
            return false;
        }
    }

    private function getOrderLang($id_order, $iso_country)
    {
        $lang = get_post_meta($id_order, 'wpml_language', true);
        if (!empty($lang) && strlen($lang) >= 2) {
            $lang = substr($lang, 0, 2);
        } else if (!empty($iso_country) && strlen($iso_country) >= 2) {
            $lang = substr($iso_country, 0, 2);
        } else {
            $lang = get_option('REVI_SELECTED_LANGUAGE');
        }
        return $lang;
    }

    /////////// UTILS ///////////////

    public function getLangReviewURL($lang)
    {
        $lang = $this->parseLang($lang);

        switch ($lang) {
            case 'es':
                $lang_reviews_url = 'opiniones';
                break;
            case 'en':
                $lang_reviews_url = 'reviews';
                break;
            case 'ca':
                $lang_reviews_url = 'opinions';
                break;
            case 'fr':
                $lang_reviews_url = 'commentaires';
                break;
            case 'it':
                $lang_reviews_url = 'recensioni';
                break;
            case 'de':
                $lang_reviews_url = 'recensioni';
                break;
            default:
                //$lang = 'en';
                $lang_reviews_url = 'reviews';
                break;
        }
        return $lang_reviews_url;
    }

    public function parseLang($lang)
    {
        $lang = substr($lang, 0, 2);
        //Si el idioma es Inglés English British GB lo convertimos en EN
        if ($lang == 'gb' || $lang == 'us' || $lang == 'uk') {
            $lang = 'en';
        }
        return $lang;
    }



    /////////////// UPDATE CONFIGURATION ///////////////

    public function getOrderStatuses()
    {
        $sql_prestashop = "SELECT * FROM " . $this->prefix . "order_state_lang WHERE id_lang = " . Context::getContext()->language->id . " GROUP BY id_order_state";
        return Db::getInstance()->ExecuteS($sql_prestashop);
    }

    public function getShops()
    {
        $sql_prestashop = "SELECT * FROM " . $this->prefix . "shop";
        return Db::getInstance()->ExecuteS($sql_prestashop);
    }

    public function getMailData($data)
    {
        $result = $this->reviCURL($this->REVI_API_URL . 'wsapi/getMailData', "POST", $data);
        return json_decode($result);
    }

    public function updateConfiguration()
    {
        $shop_info = $this->reviCURL($this->REVI_API_URL . 'wsapi/shopinfo', "GET");
        $shop_info = json_decode($shop_info);

        //SI FALLA LA API
        if (isset($shop_info->success) && !$shop_info->success) {
            return false;
        }

        //Si el usuario y pass son correctos, actualiza los datos de la tienda
        if (isset($shop_info) && isset($shop_info->id_shop)) {
            update_option('REVI_ID_SHOP', $shop_info->id_shop);
            update_option('REVI_WIDGET_KEY', $shop_info->widget_key);
            update_option('REVI_STORES', json_encode($shop_info->stores));
            update_option('REVI_URL', $shop_info->friendly_url);
            update_option('REVI_LANG', $shop_info->default_language);
            update_option('REVI_SELECTED_LANGUAGE', $shop_info->default_language); //lo inicializamos, luego se sobreescribe si lo cambia
            update_option('REVI_ACTIVE_LANGUAGES', json_encode($shop_info->active_languages));
            update_option('REVI_SECURITY_KEY', $shop_info->security_key);
            update_option('REVI_SUBSCRIPTION', $shop_info->subscription);
            update_option('REVI_LOGO_URL', $shop_info->logo_url);
            update_option('REVI_RATING_TYPE', $shop_info->rating_type);
            update_option('REVI_DISPLAY_PRODUCT_LIST_EMPTY', $shop_info->display_product_list_empty);
            update_option('REVI_DISPLAY_PRODUCT_LIST_TEXT', $shop_info->display_product_list_text);

            return true;
        } else {
            update_option('REVI_SUBSCRIPTION', '0');
            return false;
        }
    }

    public function sendModuleVersion($plugin_version)
    {
        //UPDATE MODULE VERSION
        $data = array('version' => $plugin_version);
        $this->reviCURL($this->REVI_API_URL . 'wsapi/moduleversion', "POST", $data);
        update_option('REVI_MODULE_VERSION', $plugin_version);
    }

    /////////////// CURL ///////////////

    public function reviCURL($url, $request_type = 'GET', $data = array(), $debug = FALSE)
    {
        // Requests::register_autoloader(); // Requests initialize

        $headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'X-API-KEY' => get_option('REVI_API_KEY'),
            'X-ID-STORE' => get_option('REVI_SELECTED_STORE'),
        );

        $options = array(
            'follow_redirects' => true, // Follow 3xx redirects?
            'timeout' => 30, // How long should we wait for a response?
            'connect_timeout' => 30, // How long should we wait while trying to connect?
        );

        $args = $options;
        $args['headers'] = $headers;
        $args['body'] = $data;

        if ($request_type == "GET") {
            // $request = Requests::get($url, $headers, $options);
            //WEE_MODIFIED
            $request = wp_remote_get($url, $args);
        } elseif ($request_type == "POST") {
            // $request = Requests::post($url, $headers, $data, $options);
            //WEE_MODIFIED
            $request = wp_remote_post($url, $args);
        }

        if ($debug) {
            echo "<pre>";
            // var_dump($request->body);
            //WEE_MODIFIED
            var_dump(wp_remote_retrieve_body($request));
            echo "</pre>";
        }

        // return $request->body;
        //WEE_MODIFIED
        return wp_remote_retrieve_body($request);
    }
}
