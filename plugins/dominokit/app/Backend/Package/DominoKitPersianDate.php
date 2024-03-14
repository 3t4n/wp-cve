<?php


use Morilog\Jalali\Jalalian;

defined('ABSPATH') || exit;

class DominoKitPersianDate
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * DominoKitPersianDate constructor.
     */
    public function __construct()
    {
        global $wp_version;

        add_action('woocommerce_process_shop_order_meta', array($this, 'dominokit_process_shop_order_meta_callback'), 100, 1);
        add_action('woocommerce_process_product_meta', array($this, 'dominokit_process_product_meta_callback'), 100, 1);
        add_action('woocommerce_ajax_save_product_variations', array($this, 'dominokit_ajax_save_product_variations_callback'), 100, 1);
        add_action('woocommerce_process_shop_coupon_meta', array($this, 'dominokit_process_shop_coupon_meta_callback'), 100, 1);

        if (version_compare($wp_version, '5.3', '<')) {
            add_filter('date_i18n', array($this, 'dominokit_date_i18n_callback'), 100, 4);
        }

        add_filter('wp_date', array($this, 'dominokit_wp_date_callback'), 100, 4);
    }


    /**
     * @param $order_id
     * @return false
     */
    public function dominokit_process_shop_order_meta_callback($order_id)
    {
        if (!isset($_POST['order_date'])) return false;

        $order = wc_get_order($order_id);

        $hour = str_pad(intval($_POST['order_date_hour']), 2, 0, STR_PAD_LEFT);
        $minute = str_pad(intval($_POST['order_date_minute']), 2, 0, STR_PAD_LEFT);
        $second = str_pad(intval($_POST['order_date_second']), 2, 0, STR_PAD_LEFT);

        $timestamp = wc_clean($_POST['order_date']) . " {$hour}:{$minute}:{$second}";

        $jalaliDate = Jalalian::fromFormat('Y-m-d H:i:s', self::english($timestamp));

        if (empty($_POST['order_date'])) {
            $date = time();
        } else {
            $date = gmdate('Y-m-d H:i:s', $jalaliDate->toCarbon()->timestamp);
        }

        $props['date_created'] = $date;


        $order->set_props($props);
        $order->save();
    }

    /**
     * @param $product_id
     * @return false
     */
    public function dominokit_process_product_meta_callback($product_id)
    {
        $props = [];

        if (isset($_POST['_sale_price_dates_from'])) {
            $date_on_sale_from = wc_clean(wp_unslash($_POST['_sale_price_dates_from']));

            if (!empty($date_on_sale_from)) {
                $jalaliDate = Jalalian::fromFormat('Y-m-d', self::english($date_on_sale_from));

                $props['date_on_sale_to'] = date('Y-m-d 23:59:59', $jalaliDate->toCarbon()->timestamp);
            }
        }

        if (!count($props)) {
            return false;
        }

        $product = wc_get_product($product_id);
        $product->set_props($props);
        $product->save();
    }

    /**
     * @param $product_id
     */
    public function dominokit_ajax_save_product_variations_callback($product_id)
    {
        if (isset($_POST['variable_post_id'])) {

            $parent = wc_get_product($product_id);

            $max_loop = max(array_keys(wp_unslash($_POST['variable_post_id'])));
            $data_store = $parent->get_data_store();
            $data_store->sort_all_product_variations($parent->get_id());

            for ($i = 0; $i <= $max_loop; $i++) {

                if (!isset($_POST['variable_post_id'][$i])) {
                    continue;
                }

                $variation_id = absint($_POST['variable_post_id'][$i]);
                $variation = wc_get_product_object('variation', $variation_id);

                // Handle dates.
                $props = [];

                // Force date from to beginning of day.
                if (isset($_POST['variable_sale_price_dates_from'][$i])) {
                    $date_on_sale_from = wc_clean(wp_unslash($_POST['variable_sale_price_dates_from'][$i]));

                    if (!empty($date_on_sale_from)) {
                        $jalaliDate = Jalalian::fromFormat('Y-m-d', self::english($date_on_sale_from));
                        $props['date_on_sale_from'] = date('Y-m-d 00:00:00', $jalaliDate->toCarbon()->timestamp);
                    }
                }

                // Force date to to the end of the day.
                if (isset($_POST['variable_sale_price_dates_to'][$i])) {
                    $date_on_sale_to = wc_clean(wp_unslash($_POST['variable_sale_price_dates_to'][$i]));

                    if (!empty($date_on_sale_to)) {
                        $jalaliDate = Jalalian::fromFormat('Y-m-d', self::english($date_on_sale_to));
                        $props['date_on_sale_to'] = date('Y-m-d 23:59:59', $jalaliDate->toCarbon()->timestamp);
                    }
                }

                if (!count($props)) {
                    continue;
                }

                $variation->set_props($props);
                $variation->save();
            }
        }
    }

    /**
     * @param $coupon_id
     * @return false
     */
    public function dominokit_process_shop_coupon_meta_callback($coupon_id)
    {
        if (!isset($_POST['expiry_date'])) {
            return false;
        }

        $coupon = new WC_Coupon($coupon_id);
        $expiry_date = wc_clean($_POST['expiry_date']);

        if (!empty($expiry_date)) {
            $jalaliDate = Jalalian::fromFormat('Y-m-d', self::english($expiry_date));
            $expiry_date = $jalaliDate->toCarbon()->format('Y-m-d');
        }

        $coupon->set_props([
            'date_expires' => $expiry_date,
        ]);

        $coupon->save();
    }

    /**
     * @param $date
     * @param $format
     * @param $timestamp
     * @param $gmt
     * @return string
     */
    public function dominokit_date_i18n_callback($date, $format, $timestamp, $gmt)
    {
        $timezone = get_option('timezone_string', 'Asia/Tehran');

        if (empty($timezone)) {
            $timezone = 'Asia/Tehran';
        }

        $timezone = new \DateTimeZone($timezone);

        return $this->dominokit_wp_date_callback($date, $format, $timestamp, $timezone);
    }

    /**
     * @param $date
     * @param $format
     * @param $timestamp
     * @param $timezone
     * @return string
     */
    public function dominokit_wp_date_callback($date, $format, $timestamp, $timezone)
    {
        $format = str_replace('M', 'F', $format);

        try {
            return Jalalian::fromDateTime($timestamp, $timezone)->format($format);
        } catch (Exception $e) {
            return $date;
        }
    }

    /**
     * @param $number
     * @return string|string[]
     */
    private static function english($number)
    {
        return str_replace(['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'], range(0, 9), $number);
    }

    /**
     * @return DominoKitPersianDate|null
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

