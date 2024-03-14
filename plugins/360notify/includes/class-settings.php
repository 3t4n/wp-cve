<?php

defined('ABSPATH') || exit;

class WooNotify_360Messenger_Settings
{

    private $settings_api;

    public function __construct()
    {

        $this->settings_api = new WooNotify_360Messenger_Settings_Api;

        add_action('init', [$this, 'updateOption__3_8']);

        if (is_admin()) {

            add_action('admin_init', [$this, 'adminInit']);
            add_action('admin_menu', [$this, 'adminMenu'], 60);
            add_filter('woocommerce_settings_tabs_array', [$this, 'adminSubMenu'], 99999);
            add_action('wp_before_admin_bar_render', [$this, 'adminBar']);

            add_filter('WooNotify_buyer_settings', [$this, 'buyerSettings']);
            add_filter('WooNotify_super_admin_settings', [$this, 'superAdminSettings']);
            add_filter('WooNotify_product_admin_settings', [$this, 'productAdminSettings']);

            add_filter('admin_footer_text', [$this, 'footerNote']);
            add_filter('update_footer', [$this, 'footerVersion'], 11);
        }
    }

    public function updateOption__3_8()
    {
        global $wpdb;

        if (get_option('WooNotify_update_gateway_options')) {
            return;
        }

        $wpdb->query(
            $wpdb->prepare("UPDATE {$wpdb->options} SET option_value=REPLACE(option_value, 's:24:\"woonotify_360Messenger_username\"', 's:20:\"360Messenger_gateway_apiKey\"') WHERE option_name=esc_html('360Messenger_main_settings')"));
        $wpdb->query(
            $wpdb->prepare("UPDATE {$wpdb->options} SET option_value=REPLACE(option_value, 's:24:\"woonotify_360Messenger_password\"', 's:20:\"360Messenger_gateway_password\"') WHERE option_name=esc_html('360Messenger_main_settings')"));
        $wpdb->query(
            $wpdb->prepare("UPDATE {$wpdb->options} SET option_value=REPLACE(option_value, 's:22:\"woonotify_360Messenger_sender\"', 's:18:\"360Messenger_gateway_sender\"') WHERE option_name=esc_html('360Messenger_main_settings')"));
        update_option('WooNotify_update_gateway_options', '1');
    }

    public function adminMenu()
    {
        $slug = 'woocommerce';
        if (get_locale() == 'fa_IR') {
            $namemenu = 'واتساپ ووکامرس';
        } else {
            $namemenu = '360Notify';
        }

        add_submenu_page($slug, $namemenu, $namemenu,
            'manage_woocommerce', 'wooNotify-woocommerece-360Messenger-pro', [$this, 'settingPage']);
    }

    public function adminInit()
    {

        if (!empty($_GET['tab']) && $_GET['tab'] == 'WooNotify_settings_page') {
            wp_redirect(admin_url('admin.php?page=wooNotify-woocommerece-360Messenger-pro'));
            exit();
        }

        $this->settings_api->set_sections(self::settingSections());
        $this->settings_api->set_fields($this->settingFields());
        $this->settings_api->admin_init();
    }

    public static function settingSections()
    {
        if (get_locale() == 'fa_IR') {
            $title1 = esc_html('وبسرویس');
            $title2 = esc_html('پیامهای مدیر کل');
            $title3 = esc_html('پیامهای مشتری');
            $title4 = esc_html('پیامهای فروشندگان');
            //$title5 = esc_html('اطلاع رسانی مشتری ها');
            //$title6 = esc_html('اعضای اطلاع رسانی محصولات');
            $title7 = esc_html('ارسال پیام واتساپ');
            $title8 = esc_html('پیامهای ارسال شده');

        } else {
            $title1 = esc_html('Webservice');
            $title2 = esc_html("Admin Template");
            $title3 = esc_html('Customer Template');
            $title4 = esc_html('Vendor Template');
            //$title5 = esc_html('Information of products to customers');
            //$title6 = esc_html('Product Information Members');
            $title7 = esc_html('Send Message');
            $title8 = esc_html('Report');
        }
        $sections = [
            [
                'id' => '360Messenger_main_settings',
                'title' => esc_html($title1),
            ],
            [
                'id' => '360Messenger_super_admin_settings',
                'title' => esc_html($title2),
            ],
            [
                'id' => '360Messenger_buyer_settings',
                'title' => esc_html($title3),

            ],
            [
                'id' => '360Messenger_product_admin_settings',
                'title' => esc_html($title4),
            ],
            /*[
                'id' => '360Messenger_notif_settings',
                'title' => esc_html($title5),
            ],
            [
                'id' => '360Messenger_contacts',
                'title' => esc_html($title6),
                'form_tag' => false,
            ],*/
            [
                'id' => '360Messenger_send',
                'title' => esc_html($title7),
                'form_tag' => false,
            ],
            [
                'id' => '360Messenger_archive',
                'title' => esc_html($title8),
                'form_tag' => false,
            ],
        ];

        return apply_filters('WooNotify_settings_sections', $sections);
    }

    public function settingFields()
    {

        $gateway = WooNotify()->Options('360Messenger_gateway');
        $gateway = !empty($gateway) && $gateway != 'none';

        $gateways_list = class_exists('WooNotify_360Messenger_Gateways') ? WooNotify_360Messenger_Gateways::get_360Messenger_gateway() : [];
        asort($gateways_list);
        $gateways_list = array_merge($gateways_list);

        $country_list = WC()->countries->get_countries();
        $country_list = $country_list;
        
        $shortcode = WooNotify_Shortcode(true);
        if (get_locale() == 'fa_IR') {
            $text1 = esc_html('کلید وبسرویس');
            $text2 = esc_html('مسیریابی');
            $text3 = esc_html('دسترسی سریع از ادمین بار');
            $text4 = esc_html('اگر فعال باشد، لینک ارسال پیام جهت دسترسی سریع تر به ادمین بار اضافه خواهد شد');
            $text5 = esc_html('ارسال پیام واتساپ به مدیران کل');
            $text6 = esc_html('با فعالسازی این گزینه، در هنگام ثبت و یا تغییر سفارش، برای مدیران کل، پیام واتساپ ارسال می گردد');
            $text7 = esc_html('شماره واتساپ های مدیران کل');
			$text8=esc_html('شماره ها را با کاما (,) جدا نمایید');
			$text9=esc_html('وضعیت های دریافت پیام واتساپ');
			$text10=esc_html('می توانید مشخص کنید مدیران کل در چه وضعیت هایی از سفارش پیام واتساپ دریافت کنند');
			$text11=esc_html('متن پیام مدیر کل');
			$text12=esc_html('کد های کوتاه شده قابل استفاده');
			$text13=esc_html('ارسال پیام واتساپ به مشتری');
			$text14=esc_html('با فعالسازی این گزینه، در هنگام ثبت و یا تغییر وضعیت سفارش و یا به صورت دست جمعی، به مشتری پیام واتساپ ارسال می گردد') ;
			$text15=esc_html('متاباکس ارسال پیام واتساپ');
			$text16=esc_html('با فعالسازی این گزینه، در صورت فعال بودن قابلیت ارسال پیام واتساپ به مشتری، در صفحه سفارشات متاباکس ارسال پیام واتساپ به مشتریان اضافه میشود');
			$text17=esc_html('عنوان فیلد شماره واتساپ');
			$text18=esc_html('این عنوان در صفحه تسویه حساب نمایش داده خواهد شد و جایگزین کلمه ی "شماره واتساپ" میگردد');
			$text19=esc_html('شماره واتساپ');
			$text20=esc_html('اختیاری بودن دریافت پیام واتساپ برای مشتری');
			$text21=esc_html('با فعال سازی این گزینه، مشتری میتواند انتخاب کند که پیام واتساپ را دریافت کند و یا دریافت نکند. در غیر این صورت پیام واتساپ همواره ارسال خواهد شد');
			$text22=esc_html('بله');
			$text23=esc_html('خیر');
			$text24=esc_html('متن نمایش داده شده به کاربر برای دریافت پیام واتساپ');
			$text25=esc_html('این متن در بالای کادر بررسی دریافت پیام‌های واتساپ در صفحه تسویه حساب نمایش داده می‌شود');
			$text26=esc_html('میخواهم از وضعیت سفارش از طریق پیام واتساپ آگاه شوم');
			$text27=esc_html('وضعیت های پیام واتساپ');
			$text28=esc_html('وضعیت های دریافت پیام واتساپ');
			$text29=esc_html('می توانید مشخص کنید مشتری در چه وضعیت هایی از سفارش قادر به دریافت پیام واتساپ باشد');
			$text30=esc_html('انتخاب وضعیت ها توسط مشتری');
			$text31=esc_html('با فعالسازی این گزینه، مشتری میتواند در صفحه تسویه حساب، وضعیت های دلخواه خود برای دریافت پیام واتساپ را از میان وضعیت های انتخاب شده در بالا، انتخاب نماید. در صورت عدم فعالسازی این قسمت، در تمام وضعیت های انتخاب شده در بالا پیام ارسال میشود');
			$text32=esc_html('الزامی بودن انتخاب حداقل یک وضعیت');
			$text33=esc_html('با فعال سازی این گزینه، کاربر می بایست حداقل یک وضعیت سفارش را از بین وضعیت های انتخاب شده در بالا انتخاب کند. این قسمت ملزم به "بله" بودن تنظیمات "انتخاب وضعیت ها توسط مشتری" است');
			$text34=esc_html('نوع انتخاب وضعیت ها');
			$text35=esc_html('این قسمت نیز ملزم به "بله" بودن تنظیمات "انتخاب وضعیت ها توسط مشتری" است و نوع فیلد انتخاب وضعیت های سفارش توسط مشتری را تعیین میکند');
			$text36=esc_html('چند انتخابی');
			$text37=esc_html('چک باکس');
			$text38=esc_html('متن بالای انتخاب وضعیت ها');
			$text39=esc_html('این متن بالای لیست وضعیت ها در صفحه تسویه حساب برای انتخاب مشتری قرار میگیرد');
			$text40=esc_html('وضعیت هایی که مایل به دریافت پیام واتساپ هستید را انتخاب نمایید');
			$text41=esc_html('متن پایین انتخاب وضعیت ها');
			$text42=esc_html('این متن پایین لیست وضعیت ها در صفحه تسویه حساب برای انتخاب مشتری قرار میگیرد');
			$text43=esc_html('متن پیام واتساپ مشتری');
			$text44=esc_html('کد های کوتاه شده قابل استفاده');
			$text45=esc_html('ارسال پیام واتساپ به فروشندگان محصول');
			$text46=esc_html('با فعالسازی این گزینه، در هنگام ثبت و یا تغییر سفارش، برای مدیران هر محصول (فروشندگان) پیام واتساپ ارسال می گردد');
			$text47=esc_html('یوزر متای شماره واتساپ فروشندگان (اختیاری)');
			$text48=esc_html('با فعالسازی گزینه بالا یعنی "ارسال پیام واتساپ به فروشندگان محصول"، قسمت ویرایش و مدیریت هر محصول، یک تب جدید به اسم "پیام واتساپ" اضافه خواهد شد که در آنجا میتوانید به صورت دستی شماره واتساپ فروشندگان (مدیران محصول) را وارد نمایید. ولی با توجه به اینکه وارد کردن دستی شماره واتساپ فروشنده هر محصول ممکن است کار بسیار سخت و زمانبری باشد، این قابلیت وجود خواهد داشت که کلید متای کاربر یا User Meta Key مربوط به شماره واتساپ فروشندگان را در این فیلد وارد کنید تا به صورت خودکار پیام به شماره واتساپ ثبت شده برای آن متا ارسال شود.<br>این قابلیت اکثرا زمانی مورد استفاده قرار میگیرد که از افزونه های چند فروشندگی ووکامرس استفاده نمایید. در صورتی که دانش کافی در این مورد را ندارید، بدون نگرانی آن را خالی رها کنید');
			$text49=esc_html('پست متای شماره واتساپ فروشندگان (اختیاری)');
			$text50=esc_html('بعضی اوقات ممکن است شما از طریق برخی دیگر از افزونه های چند فروشندگی ووکامرس و یا کدنویسی شخصی، شماره موبایل فروشندگان را بجای user_meta در post_meta محصول متعلق به آن فروشنده ذخیره نمایید که در این صورت بجای استفاده از یوزر متا میتوانید از پست متا و یا هر دو استفاده نمایید.اگر دانش کافی در این مورد را ندارید، بدون نگرانی آن را خالی رها کنید');
			$text51=esc_html('وضعیت های دریافت پیام واتساپ');
			$text52=esc_html('این وضعیت های دریافت پیام واتساپ برای فروشندگانی که از طریق user_meta و یا post_meta تنظیم شده اند، لحاظ خواهد شد. برای تنظیم وضعیت پیام واتساپ فروشندگانی که به صورت دستی به محصول اضافه میشوند، میتوانید به صفحه ویرایش همان محصول مراجعه نموده و از تب پیام واتساپ، شماره موبایل مدیر آن محصول و وضعیت های سفارش متناظر با آن را اضافه کنید');
            $text53=esc_html('متن پیام به فروشندگان محصول');
            $text54=esc_html('کدهای کوتاه شده قابل استفاده');
            $text55=esc_html('اطلاع رسانی محصولات به مشتریان چیست؟');
            $text56=esc_html('آگاه سازی کاربران از جزییات و تغییرات محصولات مورد علاقه شان است.<br>بعنوان مثال کاربران پس از عضویت در اطلاع رسانی محصولات میتوانند از فروش ویژه (حراج) شدن آن محصول از طریق پیام واتساپ با خبر شوند  و یا در صورتی که محصول مورد نظرشان در سایت موجود شد بلافاصله از این موضوع مطلع گردند');
            $text57=esc_html('فعال سازی اطلاع رسانی محصولات به مشتری');
            $text58=esc_html('با فعالسازی این گزینه، اطلاع رسانی محصولات به مشتریان فعال میشود. در غیر این صورت کلیه قسمت های زیر بی تاثیر خواهند شد');
            $text59=esc_html('اطلاع رسانی محصولات قدیمی (محصولاتی که قبل از نصب این افزونه ثبت شده اند)');
            $text60=esc_html('اطلاع رسانی هر محصول در صفحه ایجاد و یا ویرایش همان محصول (مدیریت محصول) به صورت مجزا قابل تنظیم است.این قابلیت وجود دارد که اطلاع رسانی هر محصول شخصی سازی شود. اما در صورتی که قبل از نصب افزونه WooNotify دارای محصولات بسیار زیادی بوده اید که ویرایش و تنظیم اطلاع رسانی محصولات آن ها زمانبر است، میتوانید از تنظیمات پیشفرض زیر استفاده کنید، ضمنا اگر برای محصولی تنظیمات اطلاع رسانی انجام نشده است از همین تنظیمات استفاده کنید');
            $text61=esc_html('اعمال تنظیمات پیشفرض برای محصولات قدیمی (محصولات اضافه شده قبل از نصب افزونه)');
            $text62=esc_html('غیرفعال سازی برای محصولات قدیمی (محصولات اضافه شده قبل از نصب افزونه)');
            $text63=esc_html('فرم عضویت اطلاع رسانی محصولات');
            $text64=esc_html('نمایش فرم عضویت در صفحه محصول');
            $text65=('توسط این گزینه میتوانید نحوه نمایش فرم عضویت اطلاع رسانی به مشتری را در صفحه محصولات تعیین نمایید. در صورتی که قصد استفاده از اکشن های ووکامرس را دارید میتوانید تابع <code>WooNotify_Shortcode()</code> را به اکشن مورد نظر متصل کنید');
            $text66=esc_html('نمایش خودکار در بدنه محصول');
            $text67=esc_html('نمایش خودکار زیر تصویر شاخص');
            $text68=esc_html('نمایش دستی به وسیله WooCommerce hooks  یا ابزارک اطلاع رسانی محصولات ووکامرس و یا کد کوتاه شده %s');
            $text69=esc_html('متن عضویت در اطلاع رسانی محصولات');
            $text70=esc_html('این متن در صفحه محصول به صورت چک باکس ظاهر خواهد شد و کاربر با انتخاب آن میتواند شماره واتساپ و گروه های مورد نظر خود را برای عضویت در اطلاع رسانی، محصول وارد نماید');
            $text71=esc_html('به من از طریق پیام واتساپ اطلاع بده');
            $text72=esc_html('عضویت فقط برای اعضای سایت');
            $text73=esc_html('با فعالسازی این گزینه، فقط کاربرانی که وارد سایت شده اند  لاگین شده قادر به عضویت در اطلاع رسانی محصول خواهند بود');
            $text74=esc_html('متن جلوگیری از عضویت مهمانان');
            $text75=esc_html('در صورتی که گزینه "عضویت فقط برای اعضای سایت" را فعال کرده باشید، هنگامی که کاربران مهمان قصد عضویت در اطلاع رسانی محصولات را داشته باشند، با این متن وارد شده مواجه خواهند شد');
            $text76=esc_html('عضویت در اطلاع رسانی محصولات فقط برای اعضای سایت امکان پذیر خواهد بود');
            $text77=esc_html('گروه (رویداد) های اطلاع رسانی');
            $text78=esc_html('رویداد های اتوماتیک');
            $text79=esc_html('رویداد اتوماتیک (ویژه شدن یا حراج شدن محصول - موجود شدن محصول - رو به اتمام بودن انبار محصول) برای اطلاع رسانی وجود دارند که پیام واتساپ مربوط به این رویداد ها به صورت خودکار به مشترکینی که عضو اطلاع رسانی هستند ارسال میشود و نیازی به ارسال دستی پیام ها توسط شما نیست.<br>توجه داشته باشید که عملکرد گزینه های مربوط به "موجودی و انبار" وابسته به <a href="' . admin_url('admin.php?page=wc-settings&tab=products&section=inventory') . '" target="_blank">تنظیمات ووکامرس</a> خواهد بود');
            $text80=esc_html('رویداد های دستی');
            $text81=esc_html('علاوه بر  رویداد های اتوماتیک ذکر شده، می توانید گزینه های دلخواه دیگری را به سیستم اطلاع رسانی اضافه نمایید و از طریق متاباکسی که در صفحه ویرایش محصول اضافه می شود، به هر کدام از مشترکین این گروه ها به صورت دستی پیام واتساپ ارسال کنید.<br>برای اضافه کردن گزینه ها، همانند نمونه بالا ابتدا یک کد عددی دلخواه تعریف کنید، سپس بعد از قرار دادن عبارت ":" متن مورد نظر را تایپ کنید. دقت کنید که کد عددی منحصر به فرد بوده  و نباید بعد از ذخیره کردن کد تغییر کند ');
            $text82=esc_html('1:زمانیکه محصول توقف فروش شد\n2:زمانیکه نسخه جدید محصول منتشر شد\n ');
            $text83=esc_html('پیام واتساپ رویداد های اتوماتیک ');
            $text84=esc_html('<code>{product_id}</code> : آیدی محصول، <code>{sku}</code> : شناسه محصول، <code>{product_title}</code> : عنوان محصول، <code>{regular_price}</code> قیمت اصلی، <code>{onsale_price}</code> : قیمت فروش فوق العاده<br><code>{onsale_from}</code> : تاریخ شروع فروش فوق العاده، <code>{onsale_to}</code> : تاریخ اتمام فروش فوق العاده، <code>{stock}</code> : موجودی انبار');
            $text85=esc_html('زمانیکه محصول حراج شد');
            $text86=esc_html('با فعال سازی این گزینه، در صورت حراج نبودن محصول، گزینه "زمانیکه که محصول حراج شد" در فرم عضویت اطلاع رسانی نمایش داده خواهد شد');
            $text87=esc_html('متن گزینه "زمانیکه محصول حراج شد"');
            $text88=esc_html('میتوانید متن دلخواه خود را جایگزین جمله "زمانیکه محصول حراج شد" نمایید');
            $text89=esc_html("زمانیکه محصول حراج شد");
            $text90=esc_html('متن پیام واتساپ "زمانیکه محصول حراج شد"');
            $text91=esc_html("سلام\nقیمت محصول {product_title} از {regular_price} به {onsale_price} کاهش یافت");
            $text92=esc_html('زمانیکه محصول موجود شد');
            $text93=esc_html('با فعالسازی این گزینه، در صورت ناموجود بودن محصول، گزینه "زمانیکه که محصول موجود شد" در فرم عضویت اطلاع رسانی نمایش داده خواهد شد.');
            $text94=esc_html('متن گزینه "زمانیکه محصول موجود شد"');
            $text95=esc_html('میتوانید متن دلخواه خود را جایگزین جمله "زمانیکه محصول موجود شد" نمایید');
            $text96=esc_html("زمانیکه محصول موجود شد");
            $text97= esc_html('متن پیام واتساپ "زمانیکه محصول موجود شد"');
            $text98=esc_html("سلام\nمحصول {product_title} هم اکنون موجود شده است و قابل خرید می باشد.");
            $text99=esc_html('زمانیکه محصول رو به اتمام است');
            $text100=esc_html('با فعالسازی این گزینه، در صورتی که موجودی محصول در انبار زیاد بود، گزینه "زمانیکه که محصول رو به اتمام است" در فرم عضویت اطلاع رسانی نمایش داده خواهد شد');
            $text101=esc_html('متن گزینه "زمانیکه محصول رو به اتمام است"');
            $text102=esc_html('میتوانید متن دلخواه خود را جایگزین جمله "زمانیکه محصول رو به اتمام است" نمایید');
            $text103=esc_html("سلام\nموجودی محصول {product_title} کم می باشد. لطفا در صورت تمایل به خرید سریعتر اقدام نمایید");
            $text104=esc_html('<h2>پیام واتساپ رویداد های دستی</h2>');
            $text105=esc_html('برای این دسته از رویداد ها، می بایست از طریق متاباکسی که در صفحه ویرایش محصول اضافه خواهد شد، به هر کدام از مشترکین این گروه ها به صورت دستی پیام واتساپ ارسال کنید');
            $text106= '<a href="https://wamessenger.net" target="_blank">برای دریافت کلید وب سرویس اینجا را کلیک کنید</a>';
            $text107=esc_html('کشور پیش فرض شما');
            $text108=esc_html('خیلی مهم: حتما کشور خود را انتخاب کنید.');
        } else {
            $text1 = esc_html('APIKEY');
            $text2 = esc_html('Gateway');
            $text3 = esc_html('Quick access from admin bar');
            $text4 = esc_html('If it is active, the link to send a message will be added to the admin bar for faster access');
            $text5 = esc_html('Send whatsapp message to general managers');
            $text6 = esc_html('By activating this option, when registering or changing an order, a whatsapp message will be sent to the general managers');
            $text7 = esc_html('whatsapp numbers of general managers');
			$text8=esc_html('Separate numbers with commas (,)');
			$text9=esc_html('Status of receiving whatsapp messages');
			$text10=esc_html('You can specify in what situations general managers receive whatsapp messages from the order');
			$text11=esc_html("General manager's message text");
			$text12=esc_html('Usable Shortcodes');
			$text13=esc_html('Send a whatsapp message to the customer');
			$text14=esc_html('By activating this option, a whatsapp message will be sent to the customer when registering or changing the status of the order or in bulk');
			$text15=esc_html('Metabox for sending whatsapp messages');
			$text16=esc_html("By activating this option, if the ability to send whatsapp messages to customers is enabled, sending whatsapp messages to customers will be added to Metabox's orders page");
			$text17=esc_html('whatsapp number field title');
			$text18=esc_html('This title will be displayed on the checkout page and will replace the word "whatsapp number"');
			$text19=esc_html('whatsapp number');
			$text20=esc_html('Optionality of receiving whatsapp messages for the customer');
			$text21=esc_html('By activating this option, the customer can choose to receive whatsapp messages or not. Otherwise, the whatsapp message will always be sent');
			$text22=esc_html('yes');
			$text23=esc_html('No');
			$text24=esc_html('The text displayed to the user to receive whatsapp messages');
			$text25=esc_html('This text will be displayed above the check box for receiving whatsapp messages on the account settlement page');
			$text26=esc_html('I want to be informed about the status of the order through whatsapp message');
			$text27=esc_html('whatsapp Message Status');
			$text28=esc_html('Status of receiving whatsapp messages');
			$text29=esc_html('You can specify in which statuses of the order the customer can receive whatsapp messages');
			$text30=esc_html('Selection of statuses by the customer');
			$text31=esc_html('By activating this option, the customer can choose his desired statuses to receive whatsapp messages from the statuses selected above on the account settlement page. If this part is not activated, the message will be sent in all the situations selected above');
			$text32=esc_html('It is necessary to select at least one status');
			$text33=esc_html('By activating this option, the user must select at least one order status from among the statuses selected above. This field is required to be "Yes" in the setting "Customer select statuses" ');
			$text34=esc_html('Type of statuses selection');
			$text35=esc_html('This field is also required to be "Yes" in the setting "Select statuses by the client". And the field type determines the order status selection by the customer');
			$text36=esc_html('multiple choice');
			$text37=esc_html('checkbox');
			$text38=esc_html('Text above the status selection');
			$text39=esc_html('This text is placed above the list of statuses on the account settlement page for the customer to choose');
			$text40=esc_html('Choose the statuses you want to receive whatsapp messages');
			$text41=esc_html('bottom text of status selection');
			$text42=esc_html('This text is placed at the bottom of the status list on the account settlement page for the customer to choose');
			$text43=esc_html('Customer whatsapp message text');
			$text44=esc_html('Usable Shortcodes');
			$text45=esc_html('Send whatsapp message to product sellers');
			$text46=esc_html('By activating this option, when registering or changing an order, a whatsapp message will be sent to the managers of each product (sellers)');
			$text47=esc_html('Meta user whatsapp number of sellers (optional)');
			$text48=esc_html('By activating the above option, "Send whatsapp message to product sellers", in the editing and management section of each product, a new tab named "whatsapp message" will be added, where you can manually enter the whatsapp number of sellers (product managers) ) enter But considering that manually entering the whatsapp number of the seller of each product may be a very difficult and time-consuming task, it will be possible to enter the User Meta Key related to the sellers whatsapp number in this field to automatically enter it. Send a message to the whatsapp number registered for that meta.<br> This feature is mostly used when you use WooCommerce multi-vendor plugins. If you dont have enough knowledge about this, feel free to leave it blank');
			$text49=esc_html('Meta post of sellers whatsapp number (optional)');
			$text50=esc_html("Sometimes you may save the seller's mobile number instead of user_meta in the post_meta of the product belonging to that seller through some other WooCommerce multi-vendor plugins or personal coding. In this case, instead of using user meta, you can use Use post meta or both. If you don't know enough about this, don't worry, leave it blank");
			$text51=esc_html('Status of receiving whatsapp messages');
			$text52=esc_html('These statuses of receiving whatsapp messages will be considered for sellers who are set through user_meta or post_meta. To adjust the whatsapp message status of sellers who are manually added to the product, you can refer to the editing page of the same product and add the mobile number of the manager of that product and the corresponding order status from the whatsapp message tab');
            $text53=esc_html('Message text to product sellers');
            $text54=esc_html('Usable Shortcodes');
            $text55=esc_html('What is product information to customers?');
            $text56=esc_html('Notifying users of the details and changes of their favorite products.<br>For example, after subscribing to product notification, users can be informed about the special sale (auction) of that product through whatsapp messages or in If the desired product is available on the site, they will be informed immediately');
            $text57=esc_html('Enable product notification to the customer');
            $text58=esc_html('By activating this option, notification of products to customers will be activated. Otherwise, all the following parts will be ineffective');
            $text59=esc_html('Notification of old products (products that were registered before installing this plugin)');
            $text60=esc_html('The notification of each product can be set separately on the page of creating or editing the same product (product management). It is possible to personalize the notification of each product. But if before installing the WooNotify plugin you have many products that it takes time to edit and set the notification of their products, you can use the following default settings, and if the notification settings have not been done for a product, use the same settings ');
            $text61=esc_html('Apply default settings to old products (products added before plugin installation)');
            $text62=esc_html('Deactivation for old products (products added before plugin installation)');
            $text63=esc_html('Product notification membership form');
            $text64=esc_html('Display the membership form on the product page');
            $text65=('With this option, you can determine how to display the customer notification membership form on the products page. If you intend to use WooCommerce actions, you can connect the <code>WooNotify_Shortcode()</code> function to the desired action');
            $text66=esc_html('Auto display in product body');
            $text67=esc_html('Auto display below the index image');
            $text68=esc_html('Manual display by WooCommerce hooks or WooCommerce product notification widget or shortcode %s');
            $text69=esc_html('Subscription text in product notification');
            $text70=esc_html('This text will appear on the product page in the form of a check box, and by selecting it, the user can enter the whatsapp number and desired groups to be a member of the product notification.');
            $text71=esc_html('Notify me via whatsapp message');
            $text72=esc_html('Membership only for site members');
            $text73=esc_html('By activating this option, only users who are logged in to the site will be able to subscribe to product notifications');
            $text74=esc_html('Text to prevent guest membership');
            $text75=esc_html('If you have activated the option "Subscription only for members of the site", when guest users intend to subscribe to product notifications, they will encounter this entered text');
            $text76=esc_html('Membership in product notification will be possible only for site members');
            $text77=esc_html('Notification group (events)');
            $text78=esc_html('Automatic events');
            $text79=esc_html('Automatic event (specialization or auction of the product - availability of the product - running out of stock) are there to inform that the whatsapp message related to these events is automatically sent to the subscribers who are members of the notification. And you dont need to send messages manually.<br>Note that the functionality of options related to "inventory and warehouse" depends on <a href="' . admin_url('admin.php?page=wc-settings&tab= products&section=inventory'). '" target="_blank">WooCommerce Settings</a> will be');
            $text80=esc_html('Manual events');
            $text81=esc_html('In addition to the mentioned automatic events, you can add other optional options to the notification system and manually send text messages to each of the subscribers of these groups through the metabox that is added on the product editing page. Send.<br>To add options, like the example above, first define a desired numeric code, then type the desired text after placing the expression ":". Make sure that the numeric code is unique and should not be changed after saving the code');
            $text82=esc_html('1: When the product was discontinued\n2: When the new version of the product was released\n ');
            $text83=esc_html('whatsapp Message Automatic Events');
            $text84=esc_html('<code>{product_id}</code> : product ID, <code>{sku}</code> : product ID, <code>{product_title}</code> : product title, <code>{ regular_price}</code> Regular price, <code>{onsale_price}</code> : Onsale price<br><code>{onsale_from}</code> : Onsale start date, <code>{onsale_to} </code> : end date of super sale, <code>{stock}</code> : stock in stock');
            $text85=esc_html('When the product was auctioned');
            $text86=esc_html('By activating this option, if the product is not auctioned, the option "when the product is auctioned" will be displayed in the notification membership form');
            $text87=esc_html('The text of the option "When the product was auctioned"');
            $text88=esc_html('You can replace the sentence "when the product is auctioned" with your desired text');
            $text89=esc_html("When the product was auctioned");
            $text90=esc_html('whatsapp message text "When the product is auctioned"');
            $text91=esc_html("Hello\nThe price of the product {product_title} has been reduced from {regular_price} to {onsale_price}");
            $text92=esc_html('When the product is available');
            $text93=esc_html('By activating this option, if the product is not available, the option "when the product is available" will be displayed in the notification membership form.');
            $text94=esc_html('The text of the option "When the product is available"');
            $text95=esc_html('You can replace the sentence "when the product is available" with your desired text');
            $text96=esc_html("When the product became available");
            $text97= esc_html('whatsapp message text "when the product is available"');
            $text98=esc_html("Hello\nProduct {product_title} is now available and can be purchased.");
            $text99=esc_html('When the product is running out');
            $text100=esc_html('By activating this option, if the product is in stock, the option "When the product is running out" will be displayed in the notification subscription form');
            $text101=esc_html('The text of the option "when the product is running out"');
            $text102=esc_html('You can replace the sentence "When the product is running out" with your desired text');
        	$text103=esc_html("Hello\nThe product {product_title} is in short supply. Please, if you want to buy it, proceed as soon as possible");
            $text104=esc_html('<h2>Manual Events whatsapp Message</h2>');
            $text105=esc_html('For this category of events, you should manually send a whatsapp message to each of the subscribers of these groups through the metabox that will be added on the product editing page');
            $text106= '<a href="https://360messenger.net" target="_blank">Click here to get Api key</a>';
            $text107=esc_html('Default Your Country');
            $text108=esc_html('Very important: Be sure to choose your country.');
        }

        $settings_fields = [

            '360Messenger_main_settings' => apply_filters('WooNotify_main_settings', [
                [
                    'name' => '360Messenger_gateway',
                    'label' => esc_html($text2),
                    'type' => 'select',
                    'default' => 'Europe',
                    'desc' => '',
                    'options' => $gateways_list,
                    'ltr' => true,
                ],
                [
                    'name' => '360Messenger_country_list',
                    'label' => esc_html($text107),
                    'type' => 'select',
                    'default' => '',
                    'desc' => esc_html($text108),
                    'options' => $country_list,
                    'ltr' => true,
                ],
                [
                    'name' => '360Messenger_gateway_apiKey',
                    'label' => esc_html($text1),
                    'type' => 'password',
                    'ltr' => false,
                ],
                [
                    'name' => '360Messenger_gateway_apiKey_des',
                    'desc' => ($text106),
                    'type' => 'html',
                ],
                [
                    'name' => 'enable_admin_bar',
                    'label' => esc_html($text3),
                    'desc' => esc_html($text4),
                    'type' => 'checkbox',
                ],
            ]),

            '360Messenger_super_admin_settings' => apply_filters('WooNotify_super_admin_settings', [
                [
                    'name' => 'enable_super_admin_360Messenger',
                    'label' => esc_html($text5),
                    'desc' =>  esc_html($text6),
                    'type' => 'checkbox',
                    'default' => 'no',
                ],
                [
                    'name' => 'super_admin_phone',
                    'label' => esc_html($text7),
                    'desc' => esc_html($text8),
                    'type' => 'text',
                    'ltr' => true,
                ],
                [
                    'name' => 'super_admin_order_status',
                    'label' => esc_html($text9),
                    'desc' => esc_html($text10),
                    'type' => 'multicheck',
                    'options' => WooNotify()->GetAllSuperAdminStatuses(),
                ],
                [
                    'name' => 'header_super_admin',
                    'label' => '<h2>' . esc_html($text11) . '</h2>',
                    'type' => 'html',
                ],
                [
                    'name' => '360Messenger_body_shortcodes_super_admin',
                    'label' => esc_html($text12),
                    'type' => 'html',
                    'desc' => $this->ShortCodes(),
                ],
            ]),

            '360Messenger_buyer_settings' => apply_filters('WooNotify_buyer_settings', [
                [
                    'name' => 'enable_buyer',
                    'label' => esc_html($text13),
                    'desc' => esc_html($text14),
                    'type' => 'checkbox',
                ],
                [
                    'name' => 'enable_metabox',
                    'label' => esc_html($text15),
                    'desc' => esc_html($text16),
                    'type' => 'checkbox',
                ],
                [
                    'name' => 'buyer_phone_label',
                    'label' => esc_html($text17),
                    'desc' => esc_html($text18),
                    'type' => 'text',
                    'default' => esc_html($text19),
                ],
                [
                    'name' => 'force_enable_buyer',
                    'label' => esc_html($text20),
                    'desc' => esc_html($text21),
                    'type' => 'radio',
                    'default' => 'yes',
                    'options' => [
                        'no' => ($text22), //reverse
                        'yes' => ($text23),
                    ],
                ],
                [
                    'name' => 'buyer_checkbox_text',
                    'label' => esc_html($text24),
                    'desc' => esc_html($text25),
                    'type' => 'text',
                    'default' => esc_html($text26),
                ],
                [
                    'name' => 'header_2',
                    'label' => '<h2>' . esc_html($text27) . '</h2>',
                    'type' => 'html',
                ],
                [
                    'name' => 'order_status',
                    'label' => esc_html($text28),
                    'desc' => esc_html($text29),
                    'type' => 'multicheck',
                    'options' => WooNotify()->GetAllStatuses(),
                ],
                [
                    'name' => 'allow_buyer_select_status',
                    'label' => esc_html($text30),
                    'desc' => esc_html($text31),
                    'type' => 'radio',
                    'default' => 'no',
                    'options' => [
                        'yes' => ($text22),
                        'no' => ($text23),
                    ],
                ],
                [
                    'name' => 'force_buyer_select_status',
                    'label' => esc_html($text32),
                    'desc' => esc_html($text33),
                    'type' => 'radio',
                    'default' => 'no',
                    'options' => [
                        'yes' => ($text22),
                        'no' => ($text23),
                    ],
                ],
                [
                    'name' => 'buyer_status_mode',
                    'label' => esc_html($text34),
                    'desc' => esc_html($text35),
                    'type' => 'radio',
                    'default' => 'selector',
                    'options' => [
                        'selector' => ($text36),
                        'checkbox' => ($text37),
                    ],
                ],
                [
                    'name' => 'buyer_select_status_text_top',
                    'label' => esc_html($text38),
                    'desc' => esc_html($text39),
                    'type' => 'text',
                    'default' => esc_html($text40),
                ],
                [
                    'name' => 'buyer_select_status_text_bellow',
                    'label' => esc_html($text41),
                    'desc' => esc_html($text42),
                    'type' => 'text',
                    'default' => '',
                ],
                [
                    'name' => 'header_3',
                    'label' => '<h2>' . esc_html($text43) . '</h2>',
                    'type' => 'html',
                ],
                [
                    'name' => '360Messenger_body_shortcodes',
                    'label' => esc_html($text44),
                    'type' => 'html',
                    'desc' => $this->ShortCodes(),
                ],
            ]),

            '360Messenger_product_admin_settings' => apply_filters('WooNotify_product_admin_settings', [
                [
                    'name' => 'enable_product_admin_360Messenger',
                    'label' => esc_html($text45),
                    'desc' => esc_html($text46),
                    'type' => 'checkbox',
                    'default' => 'no',
                ],
                [
                    'name' => 'product_admin_user_meta',
                    'label' => esc_html($text47),
                    'desc' => esc_html($text48),
                    'type' => 'text',
                    'ltr' => true,
                    'default' => '',
                ],
                [
                    'name' => 'product_admin_post_meta',
                    'label' => esc_html($text49),
                    'desc' => esc_html($text50),
                    'type' => 'text',
                    'ltr' => true,
                    'default' => '',
                ],
                [
                    'name' => 'product_admin_meta_order_status',
                    'label' => esc_html($text51),
                    'desc' => esc_html($text52),
                    'type' => 'multicheck',
                    'options' => WooNotify()->GetAllSuperAdminStatuses(),
                ],
                [
                    'name' => 'header_product_admin',
                    'label' => '<h2>' . esc_html($text53) . '</h2>',
                    'type' => 'html',
                ],
                [
                    'name' => '360Messenger_body_shortcodes_product_admin',
                    'label' => esc_html($text54),
                    'type' => 'html',
                    'desc' => $this->ShortCodes(),
                ],
            ]),

            '360Messenger_notif_settings' => apply_filters('WooNotify_notif_settings', [
                [
                    'name' => 'header_whatis_notif',
                    'label' => '<h2>' . esc_html($text55) . '</h2>',
                    'desc' => esc_html($text56),
                    'type' => 'html',
                ],
                [
                    'name' => 'enable_notif_360Messenger_main',
                    'label' => esc_html($text57),
                    'desc' => esc_html($text58),
                    'type' => 'checkbox',
                    'default' => 'no',
                ],
                [
                    'name' => 'notif_old_pr',
                    'label' => esc_html($text59),
                    'desc' => esc_html($text60),
                    'type' => 'radio',
                    'default' => 'no',
                    'options' => [
                        'yes' => ($text61),
                        'no' => ($text62),
                    ],
                ],
                [
                    'name' => 'header_2',
                    'label' => '<h2>' . esc_html($text63) .'</h2>',
                    'type' => 'html',
                ],
                [
                    'name' => 'enable_notif_360Messenger',
                    'label' => esc_html($text64),
                    'desc' => ($text65),
                    'type' => 'radio',
                    'default' => 'no',
                    'br' => true,
                    'options' => [
                        'on' => ($text66),
                        'thumbnail' => ($text67),
                        'no' => (sprintf($text68, "<code>$shortcode</code>")),
                    ],
                ],
                [
                    'name' => 'notif_title',
                    'label' => esc_html($text69),
                    'desc' => esc_html($text70),
                    'type' => 'text',
                    'default' => esc_html($text71),
                ],
                [
                    'name' => 'notif_only_loggedin',
                    'label' => esc_html($text72),
                    'desc' => esc_html($text73),
                    'type' => 'checkbox',
                    'default' => 'no',
                ],
                [
                    'name' => 'notif_only_loggedin_text',
                    'label' => esc_html($text74),
                    'desc' => esc_html($text75),
                    'type' => 'text',
                    'default' => esc_html($text76),
                ],
                [
                    'name' => 'header_notif_group',
                    'label' => '<h2>' .  esc_html($text77) .'</h2>',
                    'type' => 'html',
                ],
                [
                    'name' => 'header_3',
                    'label' => '<h2>' . esc_html($text78) . '</h2>',
                    'desc' => $text79,
                    'type' => 'html',
                ],

                [
                    'name' => 'notif_options',
                    'label' => '<h2>' . esc_html($text80) . '</h2>',
                    'desc' => $text81,
                    'type' => 'textarea',
                    'default' => $text82,
                ],
                [
                    'name' => 'header_notif_360Messenger',
                    'label' => '<h2>' .esc_html($text83) . '</h2>',
                    'type' => 'html',
                ],
                [
                    'name' => 'header_4',
                    'label' => esc_html($text54),
                    'desc' => esc_html($text84),
                    'type' => 'html',
                ],
                [
                    'name' => 'header_null_1',
                    'label' => '',
                    'type' => 'html',
                ],
                [
                    'name' => 'enable_onsale',
                    'label' => esc_html($text85),
                    'desc' => esc_html($text86),
                    'type' => 'checkbox',
                    'default' => 'no',
                ],
                [
                    'name' => 'notif_onsale_text',
                    'label' => esc_html($text87),
                    'desc' => esc_html($text88),
                    'type' => 'text',
                    'default' => esc_html($text89),
                ],
                [
                    'name' => 'notif_onsale_360Messenger',
                    'label' => esc_html($text90),
                    'type' => 'textarea',
                    'default' => esc_html($text91),
                    'row' => 2,
                ],
                [
                    'name' => 'header_null_2',
                    'label' => '',
                    'type' => 'html',
                ],
                [
                    'name' => 'enable_notif_no_stock',
                    'label' => esc_html($text92),
                    'desc' => esc_html($text93),
                    'type' => 'checkbox',
                    'default' => 'no',
                ],
                [
                    'name' => 'notif_no_stock_text',
                    'label' => esc_html($text94),
                    'desc' => esc_html($text95),
                    'type' => 'text',
                    'default' => esc_html($text96),
                ],
                [
                    'name' => 'notif_no_stock_360Messenger',
                    'label' =>esc_html($text97),
                    'type' => 'textarea',
                    'default' => esc_html($text98),
                    'row' => 2,
                ],
                [
                    'name' => 'header_null_3',
                    'label' => '',
                    'type' => 'html',
                ],
                [
                    'name' => 'enable_notif_low_stock',
                    'label' => esc_html($text99),
                    'desc' => esc_html($text100),
                    'type' => 'checkbox',
                    'default' => 'no',
                ],
                [
                    'name' => 'notif_low_stock_text',
                    'label' => esc_html($text101),
                    'desc' => esc_html($text102),
                    'type' => 'text',
                    'default' => esc_html($text99),
                ],
                [
                    'name' => 'notif_low_stock_360Messenger',
                    'label' =>esc_html($text101),
                    'desc' => '',
                    'type' => 'textarea',
                    'default' => esc_html($text103),
                    'row' => 2,
                ],
                [
                    'name' => 'header_null_4',
                    'label' => '',
                    'type' => 'html',
                ],
                [
                    'name' => 'header_7',
                    'label' => esc_html($text104),
                    'desc' => esc_html($text105),
                    'type' => 'html',
                ],
            ]),
        ];

        return apply_filters('WooNotify_settings_fields', $settings_fields);
    }

    public function ShortCodes()
    {
        if (get_locale() == 'fa_IR') {


        }
        else
        {

        }
            

        $shortcode_list = apply_filters('WooNotify_shortcodes_list', '');

        $product_admin_shortcodes = '';

        if (!empty($_GET['tab']) && $_GET['tab'] == 'product_admin') {
            if (get_locale() == 'fa_IR') {
                $product_admin_shortcodes = ("
				<strong>کد های کوتاه شده اختصاصی فروشندگان : </strong><br>
				<code>{vendor_items}</code> = محصولات سفارش هر فروشنده   ،
				<code>{vendor_items_qty}</code> = محصولات سفارش هر فروشنده به همراه تعداد   ،<br>
				<code>{count_vendor_items}</code> = تعداد محصولات سفارش هر فروشنده   ،
				<code>{vendor_price}</code> = مجموع قیمت محصولات سفارش هر فروشنده   ، <br><br>
			");
        }
        else
        {
            $product_admin_shortcodes = ("
            <strong>Special shortened codes of sellers: </strong><br>
            <code>{vendor_items}</code> = products ordered by each vendor,
            <code>{vendor_items_qty}</code> = products ordered by each vendor with quantity,<br>
            <code>{count_vendor_items}</code> = number of products ordered by each vendor,
            <code>{vendor_price}</code> = total price of products ordered by each vendor, <br><br>
            ");
        }
        }
        if (get_locale() == 'fa_IR'){
            return ("
            <a href='' onclick='jQuery(\".WooNotify_settings_shortcodes\").slideToggle(); return false;' style='text-decoration: none;'>
                برای مشاهده کدهای کوتاه شده قابل استفاده در متن پیام واتساپ  کلیک کنید.
            </a>
            <div class='WooNotify_settings_shortcodes' style='display: none'>
                <strong>جزییات سفارش : </strong><br>
                <code>{mobile}</code> = شماره واتساپ مشتری   ،
                <code>{phone}</code> = شماره تلفن مشتری   ،
                <code>{email}</code> = ایمیل مشتری   ،
                <code>{status}</code> = وضعیت سفارش   ، <br>
                <code>{all_items}</code> = محصولات سفارش   ،
                <code>{all_items_qty}</code> = محصولات سفارش بهمراه تعداد   ،
                <code>{count_items}</code> = تعداد محصولات سفارش   ،<br>
                <code>{price}</code> = مبلغ سفارش   ،
                <code>{post_id}</code> = شماره سفارش اصلی   ،
                <code>{order_id}</code> = شماره سفارش   ،
                <code>{transaction_id}</code> = شماره تراکنش   ،<br>
                <code>{date}</code> = تاریخ سفارش   ،
                <code>{description}</code> = توضیحات مشتری   ،
                <code>{payment_method}</code> = روش پرداخت   ،
				<code>{total_discount}</code> = جمع تخفیف   ،
				<code>{total_tax}</code> = جمع مالیات   ،
				<code>{subtotal}</code> = جمع مبلغ   ،
                <code>{shipping_method}</code> = روش ارسال   ،<br><br>
    
                <strong>جزییات صورت حساب : </strong><br>
                <code>{b_first_name}</code> = نام مشتری   ،
                <code>{b_last_name}</code> = نام خانوادگی مشتری   ،
                <code>{b_company}</code> = نام شرکت   ،
                <code>{b_country}</code> = کشور   ،<br>
                <code>{b_state}</code> = ایالت/استان   ،
                <code>{b_city}</code> = شهر   ،
                <code>{b_address_1}</code> = آدرس 1   ،
                <code>{b_address_2}</code> = آدرس 2   ،
                <code>{b_postcode}</code> = کد پستی   ،<br><br>
    
                <strong>جزییات حمل و نقل : </strong><br>
                <code>{sh_first_name}</code> = نام مشتری   ،
                <code>{sh_last_name}</code> = نام خانوادگی مشتری   ،
                <code>{sh_company}</code> = نام شرکت   ،
                <code>{sh_country}</code> = کشور   ،<br>
                <code>{sh_state}</code> = ایالت/استان   ،
                <code>{sh_city}</code> = شهر   ،
                <code>{sh_address_1}</code> = آدرس 1   ،
                <code>{sh_address_2}</code> = آدرس 2   ،
                <code>{sh_postcode}</code> = کد پستی   ،<br><br>
    
                {$product_admin_shortcodes}
    
                {$shortcode_list}
            </div>
        ");
        }

        else
        {
            return ("
<a href='' onclick='jQuery(\".WooNotify_settings_shortcodes\").slideToggle(); return false;' style='text-decoration: none;'>
Click to see the shortened codes that can be used in the text of the whatsapp message.
</a>
<div class='WooNotify_settings_shortcodes' style='display: none'>
Order details: </strong><br>
<code>{mobile}</code> = customer's whatsapp number,
<code>{phone}</code> = customer phone number,
<code>{email}</code> = customer email,
<code>{status}</code> = order status, <br>
<code>{all_items}</code> = order products,
<code>{all_items_qty}</code> = order products with quantity,
<code>{count_items}</code> = number of order products,<br>
<code>{price}</code> = order amount,
<code>{post_id}</code> = original order number,
<code>{order_id}</code> = order number,
<code>{transaction_id}</code> = transaction number,<br>
<code>{date}</code> = order date,
<code>{description}</code> = customer description,
<code>{payment_method}</code> = payment method,
<code>{total_discount}</code> = total discount,
<code>{total_tax}</code> = total tax,
<code>{subtotal}</code> = subtotal,
<code>{shipping_method}</code> = shipping method,<br><br>

<strong>Invoice details: </strong><br>
<code>{b_first_name}</code> = customer name,
<code>{b_last_name}</code> = customer's last name,
<code>{b_company}</code> = company name,
<code>{b_country}</code> = country,<br>
<code>{b_state}</code> = state/province,
<code>{b_city}</code> = city,
<code>{b_address_1}</code> = address 1,
<code>{b_address_2}</code> = address 2,
<code>{b_postcode}</code> = postal code,<br><br>

<strong>Details of shipping: </strong><br>
<code>{sh_first_name}</code> = customer name,
<code>{sh_last_name}</code> = customer last name,
<code>{sh_company}</code> = company name,
<code>{sh_country}</code> = country,<br>
<code>{sh_state}</code> = state/province,
<code>{sh_city}</code> = city,
<code>{sh_address_1}</code> = address 1,
<code>{sh_address_2}</code> = address 2,
<code>{sh_postcode}</code> = postcode,<br><br>

{$product_admin_shortcodes}

{$shortcode_list}
</div>
");
        }
    }

    public function adminSubMenu($pages)
    {
        $pages['WooNotify_settings_page'] = '360Notify';

        return $pages;
    }

    public function settingPage()
    {
        echo '<div class="wrap woocommerce woonotify_360Messenger">';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';

    }

    public function adminBar()
    {
        if (WooNotify()->Options('enable_admin_bar')) {
            if (get_locale() == 'fa_IR') {
                $namemenu = 'واتساپ ووکامرس';
            } else {
                $namemenu = '360Notify';
            }
            if (current_user_can('manage_woocommerce') && is_admin_bar_showing()) {
                global $wp_admin_bar;
                $wp_admin_bar->add_menu([
                    'id' => 'adminBar_send',
                    'title' => '<span class="ab-icon"></span>'.$namemenu,
                    'href' => admin_url('admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=send'),
                ]);
            }
        }
    }

    public function buyerSettings($settings)
    {

        $statuses = WooNotify()->GetAllStatuses();

        foreach ((array) $statuses as $status_val => $status_name) {

            $_status_name = preg_replace('/\(.*\)/is', '', $status_name);
            $_status_name = trim($_status_name);
            if (get_locale() == 'fa_IR') {
            $text = [
                [
                    'name' => esc_html('360Messenger_body_') . esc_html($status_val),
                    'label' => esc_html('وضعیت ') . esc_html($status_name),
                    'desc' => esc_html("میتوانید از کد های کوتاه شده در بالای این بخش استفاده نمایید."),
                    'type' => 'textarea',
                    'default' =>esc_html( "سلام {b_first_name} {b_last_name}\nسفارش {order_id} دریافت شد و هم اکنون در وضعیت " . $_status_name . " می باشد.\nآیتم های سفارش : {all_items}\nمبلغ سفارش : {price}\nشماره تراکنش : {transaction_id}"),
                ],
            ];
        }
        else
        {
            $text = [
                [
                    'name' => '360Messenger_body_' . esc_html($status_val),
                    'label' => 'status'. esc_html($status_name),
                    'desc' => esc_html("You can use the shortened codes above this section."),
                    'type' => 'textarea',
                    'default' => esc_html("Hello {b_first_name} {b_last_name}\nThe order {order_id} has been received and is now in status ". $_status_name . ".\nOrder items: {all_items}\nOrder amount: {price}\nTransaction number: {transaction_id}"),
                ],
            ];
        }

            $settings = array_merge($settings, $text);
        }

        return $settings;
    }

    public function superAdminSettings($settings)
    {

        $statuses = WooNotify()->GetAllStatuses();
        foreach ((array) $statuses as $status_val => $status_name) {

            $_status_name = preg_replace('/\(.*\)/is', '', $status_name);
            $_status_name = trim($_status_name);
       if (get_locale() == 'fa_IR'){
                    $text = [
                        [
                            'name' => 'super_admin_360Messenger_body_' . esc_html($status_val),
                            'label' => esc_html('وضعیت ' ). esc_html($status_name),
                            'desc' => esc_html("میتوانید از شورت کد های معرفی شده در بالای این بخش استفاده نمایید."),
                            'type' => 'textarea',
                            'row' => 5,
                            'default' => esc_html("سلام مدیر\nسفارش {order_id} ثبت شده است و هم اکنون در وضعیت " . $_status_name . " می باشد.\nآیتم های سفارش : {all_items}\nمبلغ سفارش : {price}"),
                        ],
                    ];
            }
            else
            {
                $text = [
                    [
                        'name' => 'super_admin_360Messenger_body_' . esc_html($status_val),
                        'label' => 'status'. esc_html($status_name),
                        'desc' => esc_html("You can use the shortened codes above this section."),
                        'type' => 'textarea',
                        'row' => 5,
                        'default' => esc_html("Hello manager\nThe order {order_id} has been registered and is now in the status ". $_status_name . ".\nOrder items: {all_items}\nOrder amount: {price}"),
                    ],
                ];
            }
		$settings = array_merge($settings, $text);
        }

 if (get_locale() == 'fa_IR'){
    $text = [
        [
            'name' => 'header_3',
            'label' => '<h2>'.esc_html('متن پیام واتساپ موجودی انبار').'</h2>',
            'desc' => esc_html('توجه داشته باشید که متن پیام های مربوط به "موجودی و انبار" برای "فروشندگان محصول" نیز اعمال خواهد شد و تنظیمات و آستانه موجودی انبار وابسته به').' 
            <a href="' . admin_url('admin.php?page=wc-settings&tab=products&section=inventory') . '" target="_blank">'.esc_html('تنظیمات ووکامرس').'</a>
'.esc_html(' می باشد.'),
            'type' => 'html',
        ],
        [
            'name' => 'header_4',
            'label' => 'کد های کوتاه شده قابل استفاده',
            'desc' => ("کدهای کوتله شده قابل استفاده در متن پیام های واتساپ مرتبط با موجوی انبار :<br><code>{product_id}</code> : آیدی محصول، <code>{sku}</code> : شناسه محصول، <code>{product_title}</code> : عنوان محصول، <code>{stock}</code> : موجودی انبار"),
            'type' => 'html',
        ],
        [
            'name' => 'admin_low_stock',
            'label' => esc_html('کم بودن موجودی انبار'),
            'desc' => esc_html("متن پیام واتساپ زمانی که موجودی انبار کم است."),
            'type' => 'textarea',
            'row' => 3,
            'default' =>esc_html( "سلام\nموجودی انبار محصول {product_title} رو به اتمام است."),
        ],
        [
            'name' => 'admin_out_stock',
            'label' => esc_html('تمام شدن موجودی انبار'),
            'desc' => esc_html("متن پیام واتساپ زمانی که موجودی انبار تمام شد."),
            'type' => 'textarea',
            'row' => 3,
            'default' => esc_html("سلام\nموجودی انبار محصول {product_title} به اتمام رسیده است."),
        ],
    ];
    }

else
{
    $text = [
             [
                 'name' => 'header_3',
                 'label' => '<h2>'.esc_html('whatsapp message text of warehouse inventory').'</h2>',
                 'desc' => ('Note that the text of the "Inventory and Warehouse" messages will also apply to "Product Sellers" and that the warehouse inventory thresholds and settings depend on <a href="' . admin_url('admin.php ?page=wc-settings&tab=products&section=inventory'). '" target="_blank"> WooCommerce settings</a>."'),
                 'type' => 'html',
             ],
             [
                 'name' => 'header_4',
                 'label' => esc_html('Usable Shortcodes'),
                 'desc' => ("Cut codes that can be used in the text of whatsapp messages related to Mojoi Warehouse:<br><code>{product_id}</code>: Product ID, <code>{sku}</code>: ID Product, <code>{product_title}</code> : product title, <code>{stock}</code> : warehouse stock"),
                 'type' => 'html',
             ],
             [
                 'name' => 'admin_low_stock',
                 'label' => esc_html('low inventory'),
                 'desc' => esc_html("whatsapp message text when stock is low."),
                 'type' => 'textarea',
                 'row' => 3,
                 'default' => esc_html("Hello\nProduct {product_title} is running out of stock."),
             ],
             [
                 'name' => 'admin_out_stock',
                 'label' => esc_html('out of stock'),
                 'desc' => esc_html("whatsapp message text when out of stock."),
                 'type' => 'textarea',
                 'row' => 3,
                 'default' => esc_html("Hello\nProduct {product_title} is out of stock."),
             ],
         ];
}


        $settings = array_merge($settings, $text);

        return $settings;
    }

    public function productAdminSettings($settings)
    {

        $statuses = WooNotify()->GetAllStatuses();

        foreach ((array) $statuses as $status_val => $status_name) {

            $_status_name = preg_replace('/\(.*\)/is', '', $status_name);
            $_status_name = trim($_status_name);
      if (get_locale() == 'fa_IR'){
            $text = [
                [
                    'name' => 'product_admin_360Messenger_body_' . esc_html($status_val),
                    'label' => esc_html('وضعیت ') . esc_html($status_name),
                    'desc' => esc_html("میتوانید از  کدهای کوتاه شده معرفی شده در بالای این بخش استفاده نمایید."),
                    'type' => 'textarea',
                    'row' => 4,
                    'default' => esc_html("سلام\nسفارش {order_id} ثبت شده است و هم اکنون در وضعیت " . $_status_name . " می باشد.\nآیتم های سفارش متعلق به شما: {vendor_items}"),
                ],
            ];
        }
        else{
            $text = [
                [
                    'name' => 'product_admin_360Messenger_body_' . esc_html($status_val),
                    'label' => 'status'. esc_html($status_name),
                    'desc' => esc_html("You can use the shortened codes introduced at the top of this section."),
                    'type' => 'textarea',
                    'row' => 4,
                    'default' => esc_html("Hello\nThe order {order_id} has been registered and is now in the status of ". $_status_name . " is.\nYour order items: {vendor_items}"),
                ],
            ];
        }
            $settings = array_merge($settings, $text);
        }
        if (get_locale() == 'fa_IR'){
            $text = [
                [
                    'name' => '360Messenger_body_stock_product_admin',
                    'label' => '<h2>'.esc_html('متن پیام واتساپ موجودی انبار').'</h2>',
                    'desc' => (sprintf('با توجه به مشترک بودن متن پیام  های موجودی انبار بین مدیران کل و فروشندگان محصول، برای تنظیم متن این پیام ها از %s استفاده کنید.', '<a href="' . admin_url('admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=super_admin#360Messenger_super_admin_settings[admin_low_stock]') . '" target="_blank">این لینک</a>')),
                    'type' => 'html',
                ],
            ];
    }
    else{
        $text = [
            [
                'name' => '360Messenger_body_stock_product_admin',
                'label' => '<h2>'.esc_html('whatsapp message text of warehouse inventory').'</h2>',
                'desc' => (sprintf('According to the fact that the text of the warehouse inventory messages is shared between general managers and product sellers, use %s to set the text of these messages.', '<a href="' . admin_url(' admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=super_admin#360Messenger_super_admin_settings[admin_low_stock]') . '" target="_blank">this link</a>')),
                'type' => 'html',
            ],
        ];
    }

        $settings = array_merge($settings, $text);

        return $settings;
    }

    public function footerNote($text)
    {
        if (isset($_GET['page']) && $_GET['page'] == 'wooNotify-woocommerece-360Messenger-pro') {
            if (get_locale() == 'fa_IR'){
           // return footer text
            }
            else
            {

            }
        }

        return $text;
    }

    public function footerVersion($text)
    {
        if (isset($_GET['page']) && $_GET['page'] == 'wooNotify-woocommerece-360Messenger-pro') {
            $text = '360Notify version ' . WooNotify_VERSION;
        }

        return $text;
    }

   public function WooNotify_360Messenger_array_sanitize_text_field($array)
   {
       foreach ($array as $key => &$value) {
           if (is_array($value)) {
               $value = $this->WooNotify_360Messenger_array_sanitize_text_field($value);
           } else {
               $value = esc_html(sanitize_text_field($value));
           }
       }

       return $array;
   }
}

new WooNotify_360Messenger_Settings();