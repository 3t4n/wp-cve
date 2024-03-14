<?php

    /**
     * Master theme class
     *
     * @package Bolts
     * @since 1.0
     */
    class SamandehiLogo_Options {

        private $sections;
        private $checkboxes;
        private $settings;

        /**
         * Construct
         *
         * @since 1.0
         */
        public function __construct() {
            add_action('admin_menu', array(&$this, 'add_pages'));

        }

        /**
         * Add options page
         *
         * @since 1.0
         */
        public function add_pages() {

            $admin_page = add_options_page('نماد ستاد ساماندهی', 'نماد ستاد ساماندهی', 'manage_options', 'SamandehiLogo-options', array(&$this, 'display_page'));

            add_action('admin_print_scripts-' . $admin_page, array(&$this, 'scripts'));
            add_action('admin_print_styles-' . $admin_page, array(&$this, 'styles'));

        }

        /**
         * Display options page
         *
         * @since 1.0
         */
        public function display_page() {

            echo '<div class="wrap">';
        ?>
		<h1>تنظیمات لوگوی ستاد ساماندهی</h1>
		<?php
            if (isset($_POST['Samandehi-submit'])) {
                        $settings = array();
                        foreach ($_POST as $k => $v) {
                            if (strpos($k, 'Samandehi-') !== false) {
                                $settings[$k] = $v;
                            }
                        }
                        update_option('Samandehi_logo', $settings);
                    }
                    $settings = get_option('Samandehi_logo');
                    if (!$settings) {
                        update_option('Samandehi_logo', array('Samandehi-view-method' => 'front-page', 'Samandehi-width' => 125, 'Samandehi-enable' => 1, 'Samandehi-position' => 'bottom-left'));
                        $settings = get_option('Samandehi_logo');
                    }
                ?>
		<form action="" method="post">

  <a style="
    background: #fff;
    padding: 15px;
    width:90%;
    margin: auto;
    display: block;
" target="_blank" href="https://wpfile.ir/">
<span class="dashicons dashicons-editor-help"></span> 
پرسش و پاسخ در مورد افزونه و یا پیشنهاد امکانات</a>

        <table class="form-table">
			<tr>
				<th scope="row"><label for="Samandehi-enable">فعال سازی نمایش خودکار <br><small>(امکان ابزارک همچنان فعال می باشد)</small></label></th>				<td><input type="checkbox" class="regular-text" name="Samandehi-enable"				                                                                    				                                                                    				                                                                    				                                                                    				                                                                    				                                                                    				                                                                    				                                                                    				                                                                     <?php checked($settings['Samandehi-enable'], 1, true);?> id="Samandehi-enable" value="1"></td>
			</tr>
			<tr>
				<th scope="row"><label for="Samandehi-width">عرض</label></th>
				<td><input type="number" class="regular-text" name="Samandehi-width" id="Samandehi-width" value="<?php echo $settings['Samandehi-width']; ?>"></td>
			</tr>
			<tr>
				<th scope="row"><label for="Samandehi-position">موقعیت قرارگیری</label></th>
				<td>
				<select name="Samandehi-position" id="Samandehi-position">
					<option
					<?php selected($settings['Samandehi-position'], 'top-right', true);?> value="top-right">بالا - راست</option>
					<option
					<?php selected($settings['Samandehi-position'], 'top-left', true);?> value="top-left">بالا - چپ</option>
					<option
					<?php selected($settings['Samandehi-position'], 'bottom-right', true);?> value="bottom-right">پایین - راست</option>
					<option
					<?php selected($settings['Samandehi-position'], 'bottom-left', true);?> value="bottom-left">پایین - چپ</option>
				</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="Samandehi-view-method">نحوه نمایش</label></th>
				<td>
				<select name="Samandehi-view-method" id="Samandehi-view-method">
					<option
					<?php selected($settings['Samandehi-view-method'], 'front-page', true);?> value="front-page">فقط صفحه اصلی</option>
					<option
					<?php selected($settings['Samandehi-view-method'], 'global', true);?> value="global">تمام سایت</option>
				</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="Samandehi-code">کد نماد ستاد ساماندهی</label></th>
				<td>
				<textarea name="Samandehi-code" id="Samandehi-code"><?php echo stripcslashes($settings['Samandehi-code']); ?></textarea>
				</td>
			</tr>
		</table>

			<p>
				<input type="submit" name="Samandehi-submit" id="Samandehi-submit" class="button-primary" value="ثبت">
			</p>
		</form>

		<hr>
		<div style="padding:5px;background:#444; color:#fff;" class="help">
			<h3 style="color:#fff;">نحوه استفاده</h3>
			<p>
				<ul>
					<li><strong>خودکار : </strong>  با زدن تیک "فعال سازی نمایش خودکار "،نماد ستاد ساماندهی بصورت خودکار در سایت قرار می گیرد  </li>
					<li><strong>کد کوتاه : </strong> با استفاده از کدکوتاه [SamandehiLogo_shortcode] </li>
					<li><strong>ابزارک : </strong> به منوی ابزارکها رفته ابزارک نماد ستاد ساماندهی را در مکان دلخواه بگذارید </li>
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
                public function scripts() {

                    wp_print_scripts('jquery-ui-tabs');

                }

                /**
                 * Styling for the theme options page
                 *
                 * @since 1.0
                 */
                public function styles() {

                    // wp_register_style( 'SamandehiLogo-admin', _SamandehiLogo_PATH . '/css/page-options.css' );
                    // wp_enqueue_style( 'SamandehiLogo-admin' );

                }

            }

        $theme_options = new SamandehiLogo_Options();
