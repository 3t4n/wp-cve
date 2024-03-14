<?php
/*
* No script kiddies please!
*/
defined('ABSPATH') or die("اللهم صل علی محمد و آل محمد و عجل فرجهم");

/**
 * Master theme class
 *
 * @package Bolts
 * @since 1.0
 */
class enamadlogo_Options
{

    private $sections;
    private $checkboxes;
    private $settings;

    /**
     * Construct
     *
     * @since 1.0
     */
    public function __construct()
    {
        add_action('admin_menu', array(&$this, 'add_pages'));

    }

    /**
     * Add options page
     *
     * @since 1.0
     */
    public function add_pages()
    {

        $admin_page = add_options_page('نماد الکترونیکی', 'نماد الکترونیکی', 'manage_options', 'enamadlogo-options', array(&$this, 'display_page'));

        add_action('admin_print_scripts-' . $admin_page, array(&$this, 'scripts'));
        add_action('admin_print_styles-' . $admin_page, array(&$this, 'styles'));

    }

    /**
     * Display options page
     *
     * @since 1.0
     */
    public function display_page()
    {

        echo '<div class="wrap">';
        ?>
        <h1>تنظیمات لوگوی E-namad</h1>
        <?php
        if (isset($_POST['enamad-submit'])) {
            $settings = array();
            foreach ($_POST as $k => $v) {
                if (strpos($k, 'enamad-') !== false) {
                    $settings[$k] = $v;
                }
            }
            update_option('enamad_logo', $settings);
        }
        $settings = get_option('enamad_logo');
        if (!$settings) {
            update_option('enamad_logo', array('enamad-view-method' => 'front-page', 'enamad-width' => 125, 'enamad-enable' => 1, 'enamad-position' => 'bottom-left'));
            $settings = get_option('enamad_logo');
        }
        ?>
        <form action="" method="post">

            <a style="
    background: #fff;
    padding: 15px;
    width:90%;
    margin: auto;
    display: block;
" target="_blank" href="http://wp-master.ir/?p=445">
                <span class="dashicons dashicons-editor-help"></span>
                پرسش و پاسخ در مورد افزونه و یا پیشنهاد امکانات</a>

            <table class="form-table">
                <tr>
                    <td><h2>نماد اعتماد</h2></td>
                </tr>
                <tr>
                    <th scope="row"><label for="enamad-enable">فعال سازی نمایش خودکار <br><small>(تاثیری روی ابزارک ها
                                ندارد)</small></label></th>
                    <td><input type="checkbox" class="regular-text"
                               name="enamad-enable" <?php if (isset($settings['enamad-enable'])) {
                            checked($settings['enamad-enable'], 1, true);
                        } ?> id="enamad-enable" value="1"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="enamad-disable-mobile">مخفی سازی نمایش در موبایل/تبلت <br><small>(اگر
                                فعال باشد در موبایل و تبلیت نمایش داده نمی شود،پیشفرض:غیرفعال)</small></label></th>
                    <td><input type="checkbox" class="regular-text"
                               name="enamad-disable-mobile" <?php isset($settings['enamad-disable-mobile']) ? (checked($settings['enamad-disable-mobile'], 1, true)) : false; ?>
                               id="enamad-disable-mobile" value="1"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="enamad-replace-with-img">جایگزینی لینک اصلی نماد با عکس آن <br><small>(در
                                این
                                حالت سرعت لود سایت بهتر شده و کاربر با کلیک بر روی عکس به نمایش نماد اصلی هدایت می
                                شود)</small></label></th>
                    <td><input type="checkbox" class="regular-text"
                               name="enamad-replace-with-img" <?php isset($settings['enamad-replace-with-img']) ? (checked($settings['enamad-replace-with-img'], 1, true)) : false; ?>
                               id="enamad-replace-with-img" value="1"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="enamad-width">عرض</label></th>
                    <td><input type="number" class="regular-text" name="enamad-width" id="enamad-width"
                               value="<?php echo $settings['enamad-width']; ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="enamad-position">موقعیت قرارگیری</label></th>
                    <td>
                        <select name="enamad-position" id="enamad-position">
                            <option
                                <?php selected($settings['enamad-position'], 'top-right', true); ?> value="top-right">
                                بالا - راست
                            </option>
                            <option
                                <?php selected($settings['enamad-position'], 'top-left', true); ?> value="top-left">بالا
                                - چپ
                            </option>
                            <option
                                <?php selected($settings['enamad-position'], 'bottom-right', true); ?>
                                    value="bottom-right">پایین - راست
                            </option>
                            <option
                                <?php selected($settings['enamad-position'], 'bottom-left', true); ?>
                                    value="bottom-left">پایین - چپ
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enamad-view-method">نحوه نمایش</label></th>
                    <td>
                        <select name="enamad-view-method" id="enamad-view-method">
                            <option
                                <?php selected($settings['enamad-view-method'], 'front-page', true); ?>
                                    value="front-page">فقط صفحه اصلی
                            </option>
                            <option
                                <?php selected($settings['enamad-view-method'], 'global', true); ?> value="global">تمام
                                سایت
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enamad-code">کد نماد الکترونیکی</label></th>
                    <td>
                        <textarea name="enamad-code" id="enamad-code"
                                  style="margin-top: 0px;margin-bottom: 0px;height: 57px;width: 100% !important;direction: ltr;color: #24367c;"><?php if (isset($settings['enamad-code'])) {
                                echo stripcslashes($settings['enamad-code']);
                            } ?></textarea>
                        <small>کد نماد اعتماد | نمایش خودکار | ابزارک | کد کوتاه</small>

                    </td>
                </tr>
                <tr>
                    <td><h2>نمادهای دیگر </h2></td>
                </tr>

                <tr>
                    <th scope="row"><label for="enamad-shamed-code">کد شامد</label></th>
                    <td>
                        <textarea name="enamad-shamed-code" id="enamad-shamed-code"
                                  style="margin-top: 0px;margin-bottom: 0px;height: 57px;width: 100% !important;direction: ltr;color: #c9790f;"><?php if (isset($settings['enamad-shamed-code'])) {
                                echo stripcslashes($settings['enamad-shamed-code']);
                            } ?></textarea>
                        <small>کد شامد | ابزارک |‌کد کوتاه</small>

                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="enamad-custom-code">کد دلخواه</label></th>
                    <td>
                        <textarea name="enamad-custom-code" id="enamad-custom-code"
                                  style="margin-top: 0px;margin-bottom: 0px;height: 57px;width: 100% !important;direction: ltr;color: #000;"><?php if (isset($settings['enamad-custom-code'])) {
                                echo stripcslashes($settings['enamad-custom-code']);
                            } ?></textarea>
                        <small>کدهای دلخواه دیگر | ابزارک | کد کوتاه</small>
                    </td>
                </tr>
            </table>

            <p>
                <input type="submit" name="enamad-submit" id="enamad-submit" class="button-primary" value="ثبت">
            </p>
        </form>

        <hr>
        <div style="padding:5px;background:#444; color:#fff;" class="help">
            <h3 style="color:#fff;">نحوه استفاده</h3>
            <p>
            <ul>
                <li><strong>نمایش خودکار ای نماد : </strong> با زدن تیک "فعال سازی نمایش خودکار "،نماد الکترونیکی بصورت
                    خودکار در سایت قرار می گیرد
                </li>
                <li><strong>کد کوتاه ای نماد : </strong> با استفاده از کدکوتاه [enamadlogo_shortcode]</li>
                <li><strong>ابزارک ای نماد: </strong> به منوی ابزارکها رفته ابزارک نماد الکترونیکی را در مکان دلخواه
                    بگذارید
                </li>
                <hr>
                <li><strong> کد شامد : </strong>قابل فعال سازی از ابزارک و همچنین کد کوتاه :
                    [enamadlogo_shamed_shortcode]
                </li>
                <li><strong> کد دلخواه : </strong>قابل فعال سازی از ابزارک و همچنین کد کوتاه :
                    [enamadlogo_custom_shortcode]
                </li>
            </ul>
            </p>
        </div>

        <?php
        echo '</div>';

    }

    /**
     * jQuery Tabs
     *
     * @since 1.0
     */
    public function scripts()
    {

        wp_print_scripts('jquery-ui-tabs');

    }

    /**
     * Styling for the theme options page
     *
     * @since 1.0
     */
    public function styles()
    {

        // wp_register_style( 'enamadlogo-admin', _enamadlogo_PATH . '/css/page-options.css' );
        // wp_enqueue_style( 'enamadlogo-admin' );

    }

}

$theme_options = new enamadlogo_Options();
