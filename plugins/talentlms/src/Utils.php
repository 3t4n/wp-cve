<?php
/**
 * @package talentlms-wordpress
 */
namespace TalentlmsIntegration;

use DateTime;
use Exception;
use InvalidArgumentException;
use stdClass;
use TalentLMS_Category;
use TalentLMS_Course;
use TalentLMS_Siteinfo;
use TalentLMS_User;
use TalentlmsIntegration\TalentLMSLibExt\WPTalentLMSCourse;
use TalentlmsIntegration\Validations\TLMSEmail;
use TalentlmsIntegration\Validations\TLMSFloat;
use TalentlmsIntegration\Validations\TLMSInteger;
use TalentlmsIntegration\Validations\TLMSPositiveInteger;

class Utils
{

    public static function tlms_pr($var): void
    {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public static function tlms_pre($var): void
    {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
        exit;
    }

    public static function tlms_vd($var): void
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

    public static function tlms_limitWords(string $string, int $limit): string
    {
        if ($limit) {
            $words = explode(" ", $string);

            return implode(
                " ",
                array_splice($words, 0, $limit)
            );
        } else {
            return $string;
        }
    }

    public static function tlms_limitSentence(string $string, int $limit): string
    {
        $sentences = explode(".", $string);

        return implode(".", array_splice($sentences, 0, $limit));
    }

    public static function tlms_isValidDomain(string $domain): bool
    {
        return preg_match("/^[a-z0-9-\.]{1,100}\w+$/", $domain)
            and (strpos($domain, 'talentlms.com') !== false);
    }

    public static function tlms_isApiKey(string $apiKey): bool
    {
        if (strlen($apiKey) === 30) {
            return true;
        }

        return false;
    }

    public static function tlms_parseDate(string $format, string $date): DateTime
    {
        $isPM = (stripos($date, 'PM') !== false);
        $parsedDate = str_replace(array('AM', 'PM'), '', $date);
        $is12hourFormat = ($parsedDate !== $date);
        $parsedDate = DateTime::createFromFormat(trim($format), trim($parsedDate));

        if ($is12hourFormat) {
            if ($isPM && $parsedDate->format('H') !== '12') {
                $parsedDate->modify('+12 hours');
            } elseif (!$isPM && $parsedDate->format('H') === '12') {
                $parsedDate->modify('-12 hours');
            }
        }

        return $parsedDate;
    }

    public static function tlms_getDateFormat(bool $no_sec = false): string
    {
        try {
            $site_info = self::tlms_getTalentLMSSiteInfo();
            $date_format = $site_info['date_format'];
        } catch (Exception $exception) {
            $date_format = '';
        }

        switch ($date_format) {
            case 'DDMMYYYY':
                if ($no_sec) {
                    $format = 'd/m/Y';
                } else {
                    $format = 'd/m/Y, H:i:s';
                }
                break;
            case 'MMDDYYYY':
                if ($no_sec) {
                    $format = 'm/d/Y';
                } else {
                    $format = 'm/d/Y, H:i:s';
                }
                break;
            case 'YYYYMMDD':
            default:
                if ($no_sec) {
                    $format = 'Y/m/d';
                } else {
                    $format = 'Y/m/d, H:i:s';
                }
                break;
        }

        return $format;
    }

    public static function tlms_getCourses(bool $force = false): void
    {
        global $wpdb;
        if ($force) {
            $wpdb->query('TRUNCATE TABLE '.TLMS_COURSES_TABLE);
        }

        $result = $wpdb->get_var("SELECT COUNT(*) FROM ".TLMS_COURSES_TABLE);
        if (empty($result)) {
            $apiCourses = TalentLMS_Course::all();
            $format = self::tlms_getDateFormat();

            foreach ($apiCourses as $course) {
                $wpdb->insert(TLMS_COURSES_TABLE, array(
                    'id' => (
                        new TLMSPositiveInteger($course['id'])
                    )->getValue(),
                    'name' => sanitize_text_field($course['name']),
                    'course_code' => sanitize_text_field($course['code']),
                    'category_id' => !empty($course['category_id']) ?
                            (
                                new TLMSPositiveInteger($course['category_id'])
                            )->getValue() : null,
                    'description' => sanitize_text_field($course['description']),
                    'price' => esc_sql(
                        filter_var(
                            html_entity_decode($course['price']),
                            FILTER_SANITIZE_NUMBER_FLOAT,
                            FILTER_FLAG_ALLOW_FRACTION
                        )
                    ),
                    'status' => sanitize_text_field($course['status']),
                    'creation_date' => self::tlms_parseDate(
                        $format,
                        $course['creation_date']
                    )->getTimestamp(),
                    'last_update_on' => self::tlms_parseDate(
                        $format,
                        $course['last_update_on']
                    )->getTimestamp(),
                    'hide_catalog' => (
                        new TLMSInteger((int)$course['hide_from_catalog'])
                    )->getValue(),
                    'shared' => (
                        new TLMSInteger((int)$course['shared'])
                    )->getValue(),
                    'shared_url' => !empty($course['shared_url']) ? esc_url_raw($course['shared_url']): null,
                    'avatar' => esc_url_raw($course['avatar']),
                    'big_avatar' => esc_url_raw($course['big_avatar']),
                    'certification' => sanitize_text_field($course['certification']),
                    'certification_duration' => sanitize_text_field($course['certification_duration'])
                ));
            }
        }
    }

    public static function tlms_getCourse(int $course_id): array
    {
        return TalentLMS_Course::retrieve(
            (new TLMSPositiveInteger($course_id))->getValue()
        );
    }

    public static function tlms_getCategories(bool $force = false): void
    {
        global $wpdb;

        if ($force) {
            $wpdb->query("TRUNCATE TABLE ".TLMS_CATEGORIES_TABLE);
        }

        $result = $wpdb->get_var("SELECT COUNT(*) FROM ".TLMS_CATEGORIES_TABLE);
        if (empty($result)) {
            $apiCategories = TalentLMS_Category::all();
            foreach ($apiCategories as $category) {
                $wpdb->insert(TLMS_CATEGORIES_TABLE, array(
                    'id' => (new TLMSPositiveInteger($category['id']))->getValue(),
                    'name' => sanitize_text_field($category['name']),
                    'price' => (new TLMSFloat($category['price']))->getValue(),
                    'parent_id' => (!empty($category['parent_id'])) ? (
                        new TLMSPositiveInteger($category['parent_id'])
                    )->getValue() : ''
                ));
            }
        }
    }

    public static function tlms_selectCourses(): array
    {
        global $wpdb;

        $courses = [];
        // snom 5
        $sql = "SELECT c.*, cat.name as category_name FROM ".TLMS_COURSES_TABLE." c LEFT JOIN ".TLMS_CATEGORIES_TABLE
            ." cat ON c.category_id=cat.id WHERE c.status = 'active' AND c.hide_catalog = '0'";
        $results = $wpdb->get_results($sql);
        foreach ($results as $res) {
            $courses[$res->id] = $res;
        }

        return $courses;
    }

    public static function tlms_selectCourse(int $course_id): ?array
    {
        global $wpdb;

        return $wpdb->get_row("SELECT * FROM ".TLMS_COURSES_TABLE." WHERE id = "
                              .(new TLMSPositiveInteger($course_id))->getValue());
    }

    public static function tlms_selectCategories(bool $where = false, bool $order = false): array
    {
        global $wpdb;

        return $wpdb->get_results("SELECT * FROM ".TLMS_CATEGORIES_TABLE);
    }

    public static function tlms_selectProductCategories(): array
    {
        global $wpdb;

        return $wpdb->get_results("SELECT * FROM ".TLMS_PRODUCTS_CATEGORIES_TABLE);
    }

    public static function tlms_addProduct(int $course_id, array $courses): void
    {
        global $wpdb;

        if (!is_array($courses)) {
            throw new InvalidArgumentException('$courses is not an array');
        }

        $course_id = (new TLMSPositiveInteger($course_id))->getValue();

        $categories = self::tlms_selectProductCategories();

        $post = array(
            'post_author' => wp_get_current_user()->ID,
            'post_content' => sanitize_text_field($courses[$course_id]->description),
            'post_status' => "publish",
            'post_title' => sanitize_text_field($courses[$course_id]->name),
            'post_parent' => '',
            'post_type' => "product",
        );

        $product_id = wp_insert_post($post);

        wp_set_object_terms($product_id, sanitize_text_field($courses[$course_id]->category_name), 'product_cat');
        wp_set_object_terms($product_id, 'simple', 'product_type');

        $price = filter_var(
            html_entity_decode($courses[$course_id]->price),
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );

        update_post_meta($product_id, '_visibility', 'visible');
        update_post_meta($product_id, '_stock_status', 'instock');
        update_post_meta($product_id, 'total_sales', '0');
        update_post_meta($product_id, '_downloadable', 'no');
        update_post_meta($product_id, '_virtual', 'yes');
        update_post_meta($product_id, '_purchase_note', "");
        update_post_meta($product_id, '_featured', "no");
        update_post_meta($product_id, '_weight', "");
        update_post_meta($product_id, '_length', "");
        update_post_meta($product_id, '_width', "");
        update_post_meta($product_id, '_height', "");
        update_post_meta($product_id, '_sku', "");
        update_post_meta($product_id, '_product_attributes', array());
        update_post_meta($product_id, '_sale_price_dates_from', "");
        update_post_meta($product_id, '_sale_price_dates_to', "");
        update_post_meta($product_id, '_price', $price);
        update_post_meta($product_id, '_regular_price', $price);
        update_post_meta($product_id, '_sale_price', $price);
        update_post_meta($product_id, '_sold_individually', "");
        update_post_meta($product_id, '_manage_stock', "no");
        update_post_meta($product_id, '_backorders', "no");
        update_post_meta($product_id, '_stock', "");
        update_post_meta($product_id, '_talentlms_course_id', $course_id);

        require_once(ABSPATH.'wp-admin/includes/file.php');
        require_once(ABSPATH.'wp-admin/includes/media.php');
        require_once(ABSPATH.'wp-admin/includes/image.php');

        $thumbs_url = esc_url_raw($courses[$course_id]->big_avatar);

        $tmp = download_url($thumbs_url);

        preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $thumbs_url, $matches);
        $file_array = [];

        if (count($matches)) {
            $file_array['name'] = basename($matches[0]);
            $file_array['tmp_name'] = $tmp;

            if (is_wp_error($tmp)) {
                @unlink($file_array['tmp_name']);
                $file_array['tmp_name'] = '';
            }

            $thumbid = media_handle_sideload($file_array, $product_id, sanitize_text_field($courses[$course_id]->name));
            if (is_wp_error($thumbid)) {
                @unlink($file_array['tmp_name']);
            } else {
                set_post_thumbnail($product_id, $thumbid);
            }
        }

        $wpdb->insert(TLMS_PRODUCTS_TABLE, array(
            'product_id' => $product_id,
            'course_id' => $course_id
        ));
    }

    public static function tlms_deleteProduct(int $product_id): void
    {
        global $wpdb;
        $wpdb->delete(
            TLMS_PRODUCTS_TABLE,
            array(
                'product_id' => (new TLMSPositiveInteger($product_id))->getValue()
            )
        );
    }

    public static function tlms_productExists(int $course_id): bool
    {
        global $wpdb;
        $result = $wpdb->get_row("SELECT * FROM ".TLMS_PRODUCTS_TABLE." WHERE course_id = "
                                 .(new TLMSPositiveInteger($course_id))->getValue());
        if (!empty($result)) {
            return true;
        }

        return false;
    }

    public static function tlms_addProductCategories(): void
    {
        global $wpdb;

        $categories = self::tlms_selectCategories();

        foreach ($categories as $category) {
            if (!self::tlms_productCategoryExists($category->id)) {
                $wp_category_id = wp_insert_category(
                    array(
                        'cat_name' => sanitize_text_field($category->name),
                        'category_nicename' => strtolower(sanitize_title($category->name)),
                        'taxonomy' => 'product_cat'
                    )
                );

                $wpdb->insert(
                    TLMS_PRODUCTS_CATEGORIES_TABLE,
                    array(
                                    'tlms_categories_ID' => (
                                        new TLMSPositiveInteger($category->id)
                                    )->getValue(),
                                    'woo_categories_ID' => $wp_category_id
                                )
                );
            }
        }
    }

    public static function tlms_productCategoryExists(int $category_id): bool
    {
        global $wpdb;
        $result = $wpdb->get_row("SELECT * FROM ".TLMS_PRODUCTS_CATEGORIES_TABLE." WHERE tlms_categories_ID = "
                                 .(new TLMSPositiveInteger($category_id))->getValue());
        if (!empty($result)) {
            return true;
        }

        return false;
    }

    public static function tlms_getTalentLMSSiteInfo(): array
    {
        try {
            $site_info = TalentLMS_Siteinfo::get();
        } catch (Exception $e) {
            self::tlms_recordLog($e->getMessage());

            throw $e;
        }

        return $site_info;
    }

    public static function tlms_getCustomFields(): array
    {
        try {
            $custom_fields = TalentLMS_User::getCustomRegistrationFields();
        } catch (Exception $e) {
            self::tlms_recordLog($e->getMessage());

            throw $e;
        }

        return $custom_fields;
    }

    public static function tlms_getTalentLMSURL(string $url): string
    {
        if (get_option('tlms-domain-map')) {
            return str_replace(
                get_option('tlms-domain'),
                get_option('tlms-domain-map'),
                $url
            );
        } else {
            return $url;
        }
    }

    public static function tlms_getLoginKey(string $url): string
    {
        $arr = explode('key:', $url);

        return ',key:'.$arr[1];
    }

    public static function tlms_currentPageURL(): string
    {
        $pageURL = 'http';
        $https = sanitize_text_field($_SERVER["HTTPS"]);
        $port = sanitize_text_field($_SERVER["SERVER_PORT"]);
        $serverName = sanitize_text_field($_SERVER["SERVER_NAME"]);
        $requestUri = sanitize_text_field($_SERVER["REQUEST_URI"]);

        if (isset($https)) {
            if ($https == "on") {
                $pageURL .= "s";
            }
        }
        $pageURL .= "://";
        if ($port !== "80") {
            $pageURL .= $serverName
                .":".$port
                .$requestUri;
        } else {
            $pageURL .= $serverName
                .$requestUri;
        }

        return $pageURL;
    }

    public static function tlms_getUnitIconClass(string $unit_type): string
    {
        $iconClass = '';
        switch ($unit_type) {
            case 'Unit':
                $iconClass = 'fa-solid fa-check';
                break;
            case 'Document':
                $iconClass = 'fa-solid fa-desktop';
                break;
            case 'Video':
                $iconClass = 'fa-solid fa-film';
                break;
            case 'SCORM | TinCan':
            case 'Scorm':
                $iconClass = 'fa-solid fa-book';
                break;
            case 'Content':
            case 'Webpage':
                $iconClass = 'fa-solid fa-bookmark';
                break;
            case 'Test':
                $iconClass = 'fa-solid fa-pen-to-square';
                break;
            case 'Section':
            case 'Survey':
                break;
            case 'Audio':
                $iconClass = 'fa-solid fa-file-audio';
                break;
            case 'Flash':
                $iconClass = 'fa-solid fa-asterisk';
                break;
            case 'IFrame':
                $iconClass = 'fa-solid fa-bookmark';
                break;
            case 'Assignment':
                $iconClass = 'fa-solid fa-calendar';
                break;
        }

        return $iconClass;
    }

    public static function tlms_orderHasLatePaymentMethod(int $order_id): bool
    {

        $order = wc_get_order(
            (new TLMSPositiveInteger($order_id))->getValue()
        ); //tlms_recordLog('payment_method: ' . $order->get_payment_method());

        return in_array($order->get_payment_method(), array('bacs', 'cheque', 'cod'));
    }

    public static function tlms_orderHasTalentLMSCourseItem(int $order_id): bool
    {

        $order = wc_get_order((new TLMSPositiveInteger($order_id))->getValue());
        $order_items = $order->get_items();
        if ($order_items) {
            foreach ($order_items as $item) {
                if (!empty(get_post_meta($item['product_id'], '_talentlms_course_id'))) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function tlms_isTalentLMSCourseInCart(): bool
    {
        global $woocommerce;

        $items = $woocommerce->cart->get_cart();
        $tmls_courses = array();
        foreach ($items as $item => $values) {
            $tlms_course_id = get_post_meta(
                $values['product_id'],
                '_talentlms_course_id',
                true
            );
            if (!empty($tlms_course_id)) {
                $tmls_courses[] = $tlms_course_id;
            }
        }

        return !empty($tmls_courses);
    }

    public static function tlms_enrollUserToCoursesByOrderId(int $order_id): void
    {

        $order = wc_get_order((new TLMSPositiveInteger($order_id))->getValue());
        $user = self::tlms_getUserByOrder($order);

        try {
            $retrieved_user = TalentLMS_User::retrieve(
                array(
                    'email' => (
                        new TLMSEmail($user->user_email)
                    )->getValue())
            );
            $retrieved_user_exists = true;
        } catch (Exception $e) {
            self::tlms_recordLog($e->getMessage());
            $retrieved_user_exists = false;
        }

        if (!$retrieved_user_exists) {
            try {
                TalentLMS_User::signup(self::tlms_buildSignUpArgumentsByUser($user));
            } catch (Exception $e) {
                self::tlms_recordLog($e->getMessage());
            }
        }

        try {
            foreach ($order->get_items() as $item) {
                if (!empty(
                    $product_tlms_course = get_post_meta(
                        $item['product_id'],
                        '_talentlms_course_id'
                    )
                )
                ) { // isTalentLMSCourseInCart
                    $enrolled_course = TalentLMS_Course::addUser(
                        array(
                            'course_id' => (int)$product_tlms_course[0],
                            'user_email' => sanitize_email($user->user_email)
                        )
                    );
                    wc_add_order_item_meta(
                        $item->get_id(),
                        'tlms_go-to-course',
                        WPTalentLMSCourse::gotoCourse(
                            array(
                                'course_id' => (int)$product_tlms_course[0],
                                'user_id' => (int)$enrolled_course[0]['user_id']
                            )
                        )
                    );
                }
            }
        } catch (Exception $e) {
            self::tlms_recordLog($e->getMessage());
        }
    }

    public static function tlms_buildSignUpArgumentsByUser($user): array
    {

        $signup_arguments = array();
        $signup_arguments['first_name'] = sanitize_text_field($user->user_firstname);
        $signup_arguments['last_name'] = sanitize_text_field($user->user_lastname);
        $signup_arguments['email'] = sanitize_email($user->user_email);
        $signup_arguments['login'] = sanitize_user(
            preg_replace(
                '/\s+/',
                '',
                $user->user_login
            )
        );
        $signup_arguments['password'] = $user->user_password;

        try {
            if (!empty($custom_fields = TalentLMS_User::getCustomRegistrationFields())) {
                foreach ($custom_fields as $custom_field) {
                    if ($custom_field['mandatory'] == 'yes') {
                        switch ($custom_field['type']) {
                            case 'date':
                            case 'text':
                                $signup_arguments[$custom_field['key']] = " ";
                                break;
                            case 'dropdown':
                                $options = explode(';', $custom_field['dropdown_values']);
                                $signup_arguments[$custom_field['key']] = $options[0];
                                break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            self::tlms_recordLog($e->getMessage());
        }

        return $signup_arguments;
    }

    public static function tlms_getUserByOrder($order): stdClass
    {
        $user = new stdClass();
        $user->user_firstname = $order->get_billing_first_name();
        $user->user_lastname = $order->get_billing_last_name();

        if ($existing_user = $order->get_user()) { //existing or just created
            $user->user_email = $existing_user->data->user_email;
            $user->user_login = $existing_user->data->user_login;
            $user->user_password = !empty($_POST['account_password']) ?
                substr($_POST['account_password'], 0, 20) : self::tlms_passgen();
        } else { //guest user
            $user->user_email = $order->get_billing_email();
            $user->user_login = $user->user_firstname.'.'.$user->user_lastname;
            $user->user_password = self::tlms_passgen();
        }

        return $user;
    }

    public static function tlms_recordLog(string $message): void
    {
        $logFile = TLMS_BASEPATH.'/errorLog.txt';

        $time = date("F jS Y, H:i", time() + 25200);
        $logOutput = "#$time: $message\r\n";

        $fp = fopen($logFile, "a");
        $write = fputs($fp, $logOutput);
        fclose($fp);
    }

    public static function tlms_passgen(int $length = 8): string
    {
        $uppercases = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $lowercases = "abcdefghijklmnopqrstuvwxyz";
        $digits = "1234567890";

        $length = max($length, 8);
        $password = wp_generate_password($length)
            . $uppercases[rand(0, strlen($uppercases) - 1)]
            . $lowercases[rand(0, strlen($lowercases) - 1)]
            . $digits[rand(0, strlen($digits) - 1)];

        return str_shuffle($password);
    }

    public static function tlms_getCourseIdByProduct(int $product_id): ?int
    {
        if (empty($product_id)) {
            return null;
        }

        global $wpdb;

        $products_courses = $wpdb->get_results(
            $wpdb->prepare(
                "
				SELECT * FROM ".TLMS_PRODUCTS_TABLE."
				WHERE `product_id` = %d
				",
                $product_id
            ),
            ARRAY_A
        );

        return (int)$products_courses[0]['course_id'];
    }

    public static function tlms_isOrderCompletedInPast(int $order_id): bool
    {

        if (empty($order_id)) {
            return false;
        }

        global $wpdb;

        $completed_statuses_in_past = $wpdb->get_results(
            $wpdb->prepare(
                "
				SELECT * FROM ".$wpdb->comments."
				WHERE `comment_post_ID` = %d
				AND `comment_content` LIKE %s
				",
                $order_id,
                "%to Completed."
            ),
            ARRAY_A
        );

        return !empty($completed_statuses_in_past);
    }

    public static function tlms_getCourseUrl(int $course_id): string
    {

        $course_url = '';
        $tlms_domain = get_option('tlms-domain');
        if (!empty($tlms_domain)) {
            $course_url = '//'.esc_url($tlms_domain).'/learner/courseinfo/id:'.$course_id;
        }

        return $course_url;
    }

    public static function tlms_deleteWoocomerceProducts(): bool
    {

        global $wpdb;
        $products = $wpdb->get_results("SELECT * FROM ".TLMS_PRODUCTS_TABLE);
        if (!empty($products)) {
            foreach ($products as $product) {
                self::tlms_deleteWoocomerceProduct($product->product_id, false);
            }
        }

        return false;
    }

    public static function tlms_deleteWoocomerceProduct(int $id, bool $force = false): bool
    {
        $id = (new TLMSPositiveInteger($id))->getValue();

        $product = wc_get_product($id);

        if (empty($product)) {
            throw new WP_Error(
                999,
                sprintf(
                    __('No %s is associated with #%d', 'woocommerce'),
                    'product',
                    $id
                )
            );
        }

        if ($force) {
            if ($product->is_type('variable')) {
                foreach ($product->get_children() as $child_id) {
                    $child = wc_get_product($child_id);
                    $child->delete(true);
                }
            } elseif ($product->is_type('grouped')) {
                foreach ($product->get_children() as $child_id) {
                    $child = wc_get_product($child_id);
                    $child->set_parent_id(0);
                    $child->save();
                }
            }

            $product->delete(true);
            $result = $product->get_id() > 0 ? false : true;
        } else {
            $product->delete();
            $result = 'trash' === $product->get_status();
        }

        if (!$result) {
            throw new WP_Error(
                999,
                sprintf(
                    __('This %s cannot be deleted', 'woocommerce'),
                    'product'
                )
            );
        }

        // Delete parent product transients.
        if ($parent_id = wp_get_post_parent_id($id)) {
            wc_delete_product_transients($parent_id);
        }

        return true;
    }
}
