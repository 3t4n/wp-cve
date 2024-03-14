<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_Product_Tab {

	private $product_metas = [];
	private $enable_notification = false;
	private $enable_product_admin_360Messenger = false;

	public function __construct() {

		add_action( 'init', [ $this, 'updateMeta__3_8' ] );

		if ( ! is_admin() ) {
			return;
		}

		$this->enable_notification      = WooNotify()->Options( 'enable_notif_360Messenger_main' );
		$this->enable_product_admin_360Messenger = WooNotify()->Options( 'enable_product_admin_360Messenger' );

		if ( $this->enable_notification || $this->enable_product_admin_360Messenger ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'script' ] );
			//add_action( 'woocommerce_product_write_panel_tabs', [ $this, 'tabNav' ] );
			add_action( 'woocommerce_product_data_panels', [ $this, 'tabContent' ] );
			add_action( 'woocommerce_product_write_panels', [ $this, 'tabContent' ] );
			add_action( 'woocommerce_process_product_meta', [ $this, 'updateTabData' ], 10, 1 );
		}
	}

	public function updateMeta__3_8() {

		if ( get_option( 'WooNotify_update_product_admin_meta' ) ) {
			return;
		}

		global $wpdb;
		$update = $wpdb->query(
			$wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_key=REPLACE(meta_key, '_hannanstd_woo_products_tabs', '_WooNotify_product_admin_data')" ));
		if ( $update !== false ) {
			$wpdb->query(
				$wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value=REPLACE(meta_value, 's:5:\"title\"', 's:6:\"mobile\"') WHERE meta_key='_WooNotify_product_admin_data'" ));
			$wpdb->query(
				$wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value=REPLACE(meta_value, 's:7:\"content\"', 's:8:\"statuses\"') WHERE meta_key='_WooNotify_product_admin_data'" ));
			update_option( 'WooNotify_update_product_admin_meta', '1' );
		}
	}

	public function script() {
		global $post;
		if ( is_object( $post ) && $post->post_type == 'product' ) {
			wp_register_script( 'repeatable-360Messenger-tabs', WooNotify_URL . '/assets/js/product-tab.js', [ 'jquery' ], 'all' );
			wp_enqueue_script( 'repeatable-360Messenger-tabs' );
			wp_register_style( 'repeatable-360Messenger-tabs-styles', WooNotify_URL . '/assets/css/product-tab.css', '', 'all' );
			wp_enqueue_style( 'repeatable-360Messenger-tabs-styles' );
		}
	}

	public function tabNav() {
		if (get_locale() == 'fa_IR'){
			echo '<li class="WooNotify_tabs"><a href="#WooNotify"><span>'.esc_html('پیام واتساپ').'</span></a></li>';
		}else{
			echo '<li class="WooNotify_tabs"><a href="#WooNotify"><span>'.esc_html('Whatsapp').'</span></a></li>';
		}
	}

	public function tabContent() {

		global $post;
		$product_id = $post->ID;

		if ( current_action() == 'woocommerce_product_data_panels' ) {
			remove_action( 'woocommerce_product_write_panels', [ $this, __FUNCTION__ ] );
		}
		?>

        <div id="WooNotify" class="panel wc-metaboxes-wrapper woocommerce_options_panel">
			<?php
			$this->notificationSettings( $product_id );
			do_action( 'WooNotify_product_360Messenger_tab', $product_id );
			$this->productAdminSettings( $product_id );
			?>
        </div>
		<?php
	}

	private function notificationSettings( $product_id ) {

		if ( $this->enable_notification ) { ?>

            <div class="WooNotify-tab-product-admin">
				
                <p><strong>

				<?php
				if (get_locale() == 'fa_IR')
					echo esc_html('تنظیمات اطلاع رسانی محصول: ');
				else
					echo esc_html('Product notification settings: ');
				?>

				</strong></p>
            </div>

			<?php
			$this->product_metas[] = 'enable_notif_360Messenger';
			if (get_locale() == 'fa_IR')
			{
			woocommerce_wp_radio( [
				'label'         => esc_html('فرم عضویت در اطلاع رسانی'),
				'wrapper_class' => 'pswoo360Messenger_tab_radio',
				'id'            => end( $this->product_metas ),
				'value'         => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
				'options'       => [
					'on'        => esc_html('نمایش خودکار در بدنه محصول'),
					'thumbnail' => esc_html('نمایش خودکار زیر تصویر شاخص'),
					'no'        => esc_html(sprintf( 'نمایش دستی (راهنمای این گزینه در "تنظیمات افزونه >> اطلاع رسانی محصول >> فرم عضویت در اطلاع رسانی" آمده است.)' )),
				],
			] );
			}
			else
			{
				woocommerce_wp_radio( [
					'label' => esc_html('Notification subscription form'),
					'wrapper_class' => 'pswoo360Messenger_tab_radio',
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					'options' => [
					'on' => esc_html('automatic display in the product body'),
					'thumbnail' => esc_html('Auto display below the index image'),
					'no' => esc_html(sprintf( 'Manual display (instructions for this option are in "Plugin Settings >> Product Notifications >> Notification Subscription Form")' )),
					],
					] );
			}


			$this->product_metas[] = 'notif_title';
			if (get_locale() == 'fa_IR')
			{
				woocommerce_wp_text_input( [
					'desc_tip'    => true,
					'label'       => esc_html('متن عضویت در اطلاع رسانی'),
					'description' =>esc_html('این متن در صفحه محصول به صورت چک باکس ظاهر خواهد شد و کاربر با انتخاب آن میتواند شماره واتساپ و گروه های مورد نظر خود را برای عضویت در اطلاع رسانی محصول وارد نماید.'),
					'id'          => end( $this->product_metas ),
					'value'       => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
				] );
			}

			else
			{
				woocommerce_wp_text_input( [
					'desc_tip' => true,
					'label' => esc_html('subscription text in notification'),
					'description' =>esc_html( 'This text will appear on the product page in the form of a check box, and by selecting it, the user can enter his WhatsApp number and desired groups to be a member of the product notification.'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );

			}

			$this->product_metas[] = 'notif_only_loggedin';
			if (get_locale() == 'fa_IR')
			{
			woocommerce_wp_checkbox( [
				'cbvalue'     => 'on',
				'desc_tip'    => true,
				'label'       => esc_html('عضویت فقط برای اعضای سایت'),
				'description' => esc_html('با فعالسازی این گزینه، فقط کاربران لاگین شده قادر به عضویت در اطلاع رسانی محصول خواهند بود.'),
				'id'          => end( $this->product_metas ),
				'value'       => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_checkbox( [
					'cbvalue' => 'on',
					'desc_tip' => true,
					'label' => esc_html('Membership only for site members'),
					'description' => esc_html('By activating this option, only logged in users will be able to subscribe to product notifications.'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}
			$this->product_metas[] = 'notif_only_loggedin_text';
			if (get_locale() == 'fa_IR')
			{
					woocommerce_wp_text_input( [
						'desc_tip'    => true,
						'label'       => esc_html('متن جلوگیری از عضویت مهمانان'),
						'description' => esc_html('در صورتی که گزینه "عضویت فقط برای اعضای سایت" را فعال کرده باشید، هنگامیکه کاربران مهمان قصد عضویت در اطلاع رسانی محصول را داشته باشند، با این متن وارد شده مواجه خواهند شد.'),
						'id'          => end( $this->product_metas ),
						'value'       => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}
			else
			{
				woocommerce_wp_text_input( [
					'desc_tip' => true,
					'label' => esc_html('text to prevent guest membership'),
					'description' => esc_html('If you have activated the "membership only for site members" option, when guest users want to subscribe to the product notification, they will encounter this entered text.'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}
			if (get_locale() == 'fa_IR')
				echo '<p class="WooNotify-tab-help-toggle" style="cursor: pointer"></span>'.esc_html('کد های کوتاه شده مورد استفاده در متن پیام های واتساپ').'<span class="dashicons dashicons-editor-help"></p>';
			else
				echo '<p class="WooNotify-tab-help-toggle" style="cursor: pointer"></span>'.esc_html('Short codes used in the text of WhatsApp messages').'<span class="dashicons dashicons-editor-help" ></p>';
				if (get_locale() == 'fa_IR')
				{
					echo esc_html('<div class="WooNotify-tab-help" style="display: none;">
						<p><code>{product_id}</code> : آیدی محصول ، <code>{sku}</code> : شناسه محصول ، <code>{product_title}</code> : عنوان محصول ، <code>{regular_price}</code> قیمت اصلی ، <code>{onsale_price}</code> : قیمت فروش فوق العاده<br><code>{onsale_from}</code> : تاریخ شروع فروش فوق العاده ، <code>{onsale_to}</code> : تاریخ اتمام فروش فوق العاده ، <code>{stock}</code> : موجودی انبار</p>
					</div>');
				}

				else
				{
					echo esc_html('<div class="WooNotify-tab-help" style="display: none;">
					<p><code>{product_id}</code> : product ID, <code>{sku}</code> : product ID, <code>{product_title}</code> : product title, <code>{regular_price }</code> Original price, <code>{onsale_price}</code> : Onsale price<br><code>{onsale_from}</code> : Onsale start date, <code>{onsale_to}< /code> : end date of super sale, <code>{stock}</code> : stock</p>
					</div>');

				}
			echo '<div class="setting-div"></div>';

			$this->product_metas[] = 'enable_onsale';
			if (get_locale() == 'fa_IR'){

				woocommerce_wp_checkbox( [
					'cbvalue'     => 'on',
					'desc_tip'    => true,
					'label'       => esc_html('زمانیکه که محصول حراج شد'),
					'description' => esc_html('با فعالسازی این گزینه، در صورت حراج نبودن محصول، گزینه "زمانیکه که محصول حراج شد" در فرم عضویت اطلاع رسانی نمایش داده خواهد شد.'),
					'id'          => end( $this->product_metas ),
					'value'       => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
				] );
			}
			else
			{
				woocommerce_wp_checkbox( [
					'cbvalue' => 'on',
					'desc_tip' => true,
					'label' => esc_html('When the product was auctioned'),
					'description' => esc_html('By activating this option, if the product is not auctioned, the option "when the product is auctioned" will be displayed in the notification membership form.'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );

			}
		
			

			$this->product_metas[] = 'notif_onsale_text';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_text_input( [
				'desc_tip' => true,
				'label'    => esc_html('متن گزینه "زمانیکه محصول حراج شد"'),
				'id'       => end( $this->product_metas ),
				'value'    => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}

			else
			{
				woocommerce_wp_text_input( [
					'desc_tip' => true,
					'label' => esc_html('text of the option "when the product was auctioned"'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}

			$this->product_metas[] = 'notif_onsale_360Messenger';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_textarea_input( [
				'class'    => 'short',
				'desc_tip' => true,
				'label'    => esc_html('متن پیام  "زمانیکه محصول حراج شد"'),
				'id'       => end( $this->product_metas ),
				'value'    => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_textarea_input( [
					'class' => 'short',
					'desc_tip' => true,
					'label' => esc_html('message text "When the product is auctioned"'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}

			echo '<div class="setting-div"></div>';

			$this->product_metas[] = 'enable_notif_no_stock';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_checkbox( [
				'cbvalue'     => 'on',
				'desc_tip'    => true,
				'label'       => esc_html('زمانیکه که محصول موجود شد'),
				'description' => esc_html('با فعالسازی این گزینه، در صورت ناموجود بودن محصول، گزینه "زمانیکه که محصول موجود شد" در فرم عضویت اطلاع رسانی نمایش داده خواهد شد.'),
				'id'          => end( $this->product_metas ),
				'value'       => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_checkbox( [
					'cbvalue' => 'on',
					'desc_tip' => true,
					'label' => esc_html('when the product became available'),
					'description' => esc_html('By activating this option, if the product is not available, the option "when the product is available" will be displayed in the notification membership form.'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}

			$this->product_metas[] = 'notif_no_stock_text';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_text_input( [
				'desc_tip' => true,
				'label'    => esc_html('متن گزینه "زمانیکه محصول موجود شد"'),
				'id'       => end( $this->product_metas ),
				'value'    => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_text_input( [
					'desc_tip' => true,
					'label' => esc_html('text of the option "when the product is available"'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}

			$this->product_metas[] = 'notif_no_stock_360Messenger';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_textarea_input( [
				'desc_tip' => true,
				'label'    => esc_html('متن پیام "زمانیکه محصول موجود شد"'),
				'id'       => end( $this->product_metas ),
				'value'    => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_textarea_input( [
					'desc_tip' => true,
					'label' => esc_html('Text of the message "When the product is available"'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}

			echo '<div class="setting-div"></div>';

			$this->product_metas[] = 'enable_notif_low_stock';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_checkbox( [
				'cbvalue'     => 'on',
				'desc_tip'    => true,
				'label'       => esc_html('زمانیکه محصول رو به اتمام است'),
				'description' => esc_html('با فعالسازی این گزینه، در صورتی که موجودی انبار زیاد بود، گزینه "زمانیکه که محصول رو به اتمام است" در فرم عضویت اطلاع رسانی نمایش داده خواهد شد.'),
				'id'          => end( $this->product_metas ),
				'value'       => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_checkbox( [
					'cbvalue' => 'on',
					'desc_tip' => true,
					'label' => esc_html('when the product is running out'),
					'description' => esc_html('By activating this option, if the stock is high, the option "when the product is running out" will be displayed in the notification subscription form.'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}

			$this->product_metas[] = 'notif_low_stock_text';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_text_input( [
				'desc_tip' => true,
				'label'    => esc_html('متن گزینه "زمانیکه محصول رو به اتمام است"'),
				'id'       => end( $this->product_metas ),
				'value'    => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_text_input( [
					'desc_tip' => true,
					'label' => esc_html('The text of the option "When the product is running out"'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}
			$this->product_metas[] = 'notif_low_stock_360Messenger';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_textarea_input( [
				'desc_tip' => true,
				'label'    => esc_html('متن پیام "زمانیکه محصول رو به اتمام است"'),
				'id'       => end( $this->product_metas ),
				'value'    => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_textarea_input( [
					'desc_tip' => true,
					'label' => esc_html('The text of the message "when the product is running out"'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}

			echo '<div class="setting-div"></div>';


			$this->product_metas[] = 'notif_options';
			if (get_locale() == 'fa_IR'){
			woocommerce_wp_textarea_input( [
				'desc_tip'    => true,
				'style'       => 'height:100px;',
				'label'       => esc_html('گزینه های دلخواه'),
				'description' => esc_html('شما میتوانید گزینه های دلخواه خود را برای نمایش در صفحه محصولات ایجاد نمایید و به صورت دستی به مشتریانی که در گزینه های بالا عضو شده اند پیام ارسال کنید.<br>
		برای اضافه کردن گزینه ها، همانند نمونه بالا ابتدا یک کد عددی دلخواه تعریف کنید سپس بعد از قرار دادن عبارت ":" متن مورد نظر را بنویسید.<br>
		دقت کنید که کد عددی هر گزینه بسیار مهم بوده و از تغییر کد مربوطه بعد از ذخیره تنظیمات خود داری نمایید.'),
				'id'          => end( $this->product_metas ),
				'value'       => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
			] );
			}
			else
			{
				woocommerce_wp_textarea_input( [
					'desc_tip' => true,
					'style' => 'height:100px;',
					'label' => esc_html('Preferences'),
					'description' => esc_html('You can create your own options to display on the product page and manually send messages to customers who have subscribed to the above options.<br>
					To add options, like the example above, first define a desired numerical code, then write the desired text after placing the word ":".<br>
					Pay attention that the numerical code of each option is very important and do not change the corresponding code after saving the settings.'),
					'id' => end( $this->product_metas ),
					'value' => WooNotify()->getValue( end( $this->product_metas ), $product_id ),
					] );
			}

			echo '<input type="hidden" name="360Messenger_notification_metas" value="' . esc_attr( implode( ',', $this->product_metas ) ) . '">';

			echo '<hr>';
		}
	}

	private function productAdminSettings( $product_id ) {

		if ( $this->enable_product_admin_360Messenger ) { ?>

            <div class="WooNotify-tab-product-admin">
                <p><strong><?php 
				if (get_locale() == 'fa_IR')
					echo esc_html('تنظیمات فروشندگان و مدیران محصول: ');
				else
					 echo esc_html('Settings for sellers and product managers: ');
				
				?>
				</strong></p>
            </div>

			<?php
			$all_statuses   = WooNotify()->GetAllProductAdminStatuses();
			$default_status = WooNotify()->Options( 'product_admin_meta_order_status' );

			/*فروشندگان ست شده با متا*/
			$meta_tab_data = [];

			$meta_mobile = WooNotify()->User_Meta_Mobile( $product_id );
			if ( ! empty( $meta_mobile['meta'] ) ) {
				$meta_tab_data[] = $meta_mobile;
			}

			$meta_mobile = WooNotify()->Post_Meta_Mobile( $product_id );
			if ( ! empty( $meta_mobile['meta'] ) ) {
				$meta_tab_data[] = $meta_mobile;
			}

			foreach ( $meta_tab_data as $tab ) {

				$meta  = $tab['meta'];
				if (get_locale() == 'fa_IR')
					$label = esc_html('شماره واتساپ');
				else
					$label = esc_html('WhatsApp number');

				if ( $meta == 'user' ) {
					$label = esc_html($label) . '<span style="color: steelblue">' . esc_html(' (User Meta)') . '</span>';
				}

				if ( $meta == 'post' ) {
					$label = esc_html($label) . '<span style="color: steelblue">' . esc_html(' (Post Meta)') . '</span>';
				}
				if (get_locale() == 'fa_IR'){
				woocommerce_wp_text_input( [
					'id'          => 'WooNotify_tab_mobile_meta_' . esc_html(sanitize_text_field($meta)),
					'class'       => 'WooNotify_tab_mobile',
					'label'       => esc_html($label),
					'value'       => esc_html(sanitize_text_field($tab['mobile'])),
					'placeholder' => esc_html('با کاما جدا کنید'),
				] );

				}
				else
				{
					woocommerce_wp_text_input( [
						'id' => 'WooNotify_tab_mobile_meta_' . esc_html(sanitize_text_field($meta)),
						'class' => 'WooNotify_tab_mobile',
						'label' => esc_html($label),
						'value' => esc_html(sanitize_text_field($tab['mobile'])),
						'placeholder' => esc_html('separate with comma'),
						] );
				}
				if (get_locale() == 'fa_IR'){
				WooNotify()->multiSelectAdminField( [
					'id'      => 'WooNotify_tab_status_meta_' . esc_html(sanitize_text_field($meta)),
					'class'   => 'WooNotify_tab_status',
					'label'   => esc_html('وضعیت سفارش'),
					'value'   => WooNotify()->prepareAdminProductStatus( $tab['statuses'] ),
					/*'default' => $default_status,*/
					'options' => esc_html(sanitize_text_field($all_statuses)),
					'style'   => 'width:70%;height:10.5em;',
				] );
				}
				else
				{
					WooNotify()->multiSelectAdminField( [
						'id'      => 'WooNotify_tab_status_meta_' . esc_html(sanitize_text_field($meta)),
						'class'   => 'WooNotify_tab_status',
						'label'   => esc_html('Order status'),
						'value'   => WooNotify()->prepareAdminProductStatus( $tab['statuses'] ),
						/*'default' => $default_status,*/
						'options' => esc_html(sanitize_text_field($all_statuses)),
						'style'   => 'width:70%;height:10.5em;',
					] );
				}
			}
			if ( ! empty( $meta_tab_data ) ) {
				echo '<div class="setting-div"></div>';
			}

			/*فروشندگان وارد شده دستی*/
			$i        = 1;
			$tab_data = array_filter( (array) get_post_meta( $product_id, '_WooNotify_product_admin_data', true ) );
			foreach ( $tab_data as $tab ) { ?>

                <section class="button-holder-360Messenger">
                    <a href="#" onclick="return false;" class="delete_this_360Messenger_tab 360Messenger_tab_counter">(حذف)</a>
                </section>

				<?php
				if (get_locale() == 'fa_IR'){
						woocommerce_wp_text_input( [
							'id'          => 'WooNotify_tab_mobile_' . $i,
							'class'       => 'WooNotify_tab_mobile',
							'label'       => esc_html('شماره موبایل'),
							'value'       => $tab['mobile'],
							'placeholder' => esc_html('با کاما جدا کنید'),
						] );
						

						WooNotify()->multiSelectAdminField( [
							'id'      => 'WooNotify_tab_status_' . $i,
							'class'   => 'WooNotify_tab_status',
							'label'   => esc_html('وضعیت سفارش'),
							'value'   => WooNotify()->prepareAdminProductStatus( $tab['statuses'] ),
							'default' => $default_status,
							'options' => sanitize_text_field($all_statuses),
							'style'   => 'width:70%;height:10.5em;',
						] );
				}
				else
				{
					woocommerce_wp_text_input( [
						'id' => 'WooNotify_tab_mobile_' . $i,
						'class' => 'WooNotify_tab_mobile',
						'label' => esc_html('mobile number'),
						'value' => $tab['mobile'],
						'placeholder' => esc_html('separate with comma'),
						] );
						
						
						WooNotify()->multiSelectAdminField( [
						'id' => 'WooNotify_tab_status_' . $i,
						'class' => 'WooNotify_tab_status',
						'label' => esc_html('order status'),
						'value' => WooNotify()->prepareAdminProductStatus( $tab['statuses'] ),
						'default' => esc_html(sanitize_text_field($default_status)),
						'options' => esc_html(sanitize_text_field($all_statuses)),
						'style' => 'width:70%;height:10.5em;',
						] );
				}

				if ( $i != count( $tab_data ) ) {
					echo '<div class="WooNotify-tab-divider"></div>';
				}

				$i ++;
			}
			?>


            <div id="duplicate_this_row_360Messenger">

                <a href="#" onclick="return false;" class="delete_this_360Messenger_tab 360Messenger_tab_counter">
                    <?php echo esc_html('(حذف)') ?>
                </a>

				<?php
				if (get_locale() == 'fa_IR'){
				woocommerce_wp_text_input( [
					'id'          => 'hidden_duplicator_row_mobile',
					'class'       => 'WooNotify_tab_mobile',
					'label'       => esc_html('شماره واتساپ'),
					'placeholder' => esc_html('با کاما جدا کنید'),
				] );

				WooNotify()->multiSelectAdminField( [
					'id'      => 'hidden_duplicator_row_statuses',
					'class'   => 'WooNotify_tab_status',
					'label'   => esc_html('وضعیت سفارش'),
					'value'   => '',
					'default' => esc_html(sanitize_text_field($default_status)),
					'options' => esc_html(sanitize_text_field($all_statuses)),
					'style'   => 'width:70%;height:10.5em;',
				] );
				}
				else
				{
					woocommerce_wp_text_input( [
						'id' => 'hidden_duplicator_row_mobile',
						'class' => 'WooNotify_tab_mobile',
						'label' => esc_html(sanitize_text_field('WhatsApp number')),
						'placeholder' => esc_html(sanitize_text_field('separate with comma')),
						] );
						
						WooNotify()->multiSelectAdminField( [
						'id' => 'hidden_duplicator_row_statuses',
						'class' => 'WooNotify_tab_status',
						'label' => esc_html(sanitize_text_field('order status')),
						'value' => '',
						'default' => esc_html(sanitize_text_field($default_status)),
						'options' => esc_html(sanitize_text_field($all_statuses)),
						'style' => 'width:70%;height:10.5em;',
						] );
				}
				?>

                <section class="button-holder-360Messenger"></section>

            </div>

            <p>
                <a href="#" class="button-secondary" id="add_another_360Messenger_tab">
                    <span class="dashicons dashicons-plus-alt"></span>
                    <?php echo esc_html('افزودن فروشنده') ?>
                </a>
            </p>

			<?php echo '<input type="hidden" value="' . count((array)( esc_html( $tab_data ) )) . '" id="360Messenger_tab_counter" name="360Messenger_tab_counter" >';
		}
	}

	public function updateTabData( $product_id = 0 ) {

		if ( $this->enable_notification && ! empty( $_POST['360Messenger_notification_metas'] ) ) {
			$updated = [];
			//foreach ( explode( ',', ['360Messenger_notification_metas'] ) as $product_meta ) {
				foreach ( explode( ',', (esc_attr(sanitize_text_field( $_POST['360Messenger_notification_metas'] )) )) as $product_meta ) {

				$product_meta = ltrim( $product_meta, '_' );
				$this_meta    = (esc_attr(sanitize_text_field( $_POST[ $product_meta ] ?? '' )));
				if ( wp_unslash( WooNotify()->maybeBool( $this_meta ) ) != wp_unslash( WooNotify()->Options( $product_meta ) ) ) {
					$updated[] = $product_meta;
					update_post_meta( $product_id, '_' . $product_meta, esc_textarea( $this_meta ) );
				} else {
					delete_post_meta( $product_id, '_' . $product_meta );
				}
			}

			if ( ! empty( $updated ) ) {
				update_post_meta( $product_id, '_is_360Messenger_set', $updated );
			} else {
				delete_post_meta( $product_id, '_is_360Messenger_set' );
			}
		}

		if ( $this->enable_product_admin_360Messenger ) {

			if ( isset( $_POST['360Messenger_tab_counter'] ) ) {
				$tab_data = [];
				$count    = absint( sanitize_text_field($_POST['360Messenger_tab_counter']) );
				for ( $i = 1; $i <= $count; $i ++ ) {

					if ( empty( $_POST[ 'WooNotify_tab_mobile_' . $i ] ) ) {
						continue;
					}

					//$mobile   = stripslashes( WooNotify()->( $_POST[ 'WooNotify_tab_mobile_' . $i ] ) );
					//$mobile = WooNotify()->sanitize_text_field(  $_POST[ 'WooNotify_tab_mobile_' . $i ] ) ;
					$mobile = wp_kses_post( sanitize_text_field($_POST['WooNotify_tab_mobile_' . $i]) );
					$statuses = ! empty(esc_attr(sanitize_text_field( $_POST[ 'WooNotify_tab_status_' . $i ] ))) ? esc_attr(sanitize_text_field($_POST[ 'WooNotify_tab_status_' . $i ])) : '';

					$tab_data[ $i ] = [
						'mobile'   => $mobile,
						'statuses' => WooNotify()->prepareAdminProductStatus( $statuses, false ),
					];
				}

				if ( ! empty( $tab_data ) ) {
					update_post_meta( $product_id, '_WooNotify_product_admin_data', array_values( $tab_data ) );
				} else {
					delete_post_meta( $product_id, '_WooNotify_product_admin_data' );
				}
			}

			/*ذخیره شماره های مربوط به متا*/
			foreach ( [ 'user', 'post' ] as $meta ) {
				if ( isset( $_POST[ 'WooNotify_tab_mobile_meta_' . $meta ] ) ) {

					$mobile   = ! empty( $_POST[ 'WooNotify_tab_mobile_meta_' . $meta ] ) ? esc_attr(sanitize_text_field($_POST[ 'WooNotify_tab_mobile_meta_' . $meta ])) : '';
					$statuses = ! empty( $_POST[ 'WooNotify_tab_status_meta_' . $meta ] ) ? esc_attr(sanitize_text_field($_POST[ 'WooNotify_tab_status_meta_' . $meta ])) : '';
					$statuses = WooNotify()->prepareAdminProductStatus( $statuses, false );

					$old_value    = $meta == 'post' ? WooNotify()->Post_Meta_Mobile( $product_id ) : WooNotify()->User_Meta_Mobile( $product_id );
					$old_mobile   = ! empty( $old_value['mobile'] ) ? $old_value['mobile'] : '';
					$old_statuses = ! empty( $old_value['statuses'] ) ? $old_value['statuses'] : '';
					$old_statuses = WooNotify()->prepareAdminProductStatus( $old_statuses, false );

					if ( $mobile != $old_mobile || $statuses != $old_statuses ) {
						update_post_meta( $product_id, '_WooNotify_product_admin_meta_' . $meta, [
							'meta'     => $meta,
							'mobile'   => WooNotify()->esc_attr(sanitize_text_field( $mobile )),
							'statuses' => $statuses,
						] );
					}
				}
			}
		}
	}
}

new WooNotify_360Messenger_Product_Tab();