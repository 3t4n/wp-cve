<?php
	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       http://yemlihakorkmaz.com
	 * @since      1.0.0
	 *
	 * @package    Korkmaz_contract
	 * @subpackage Korkmaz_contract/admin
	 */
	
	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Korkmaz_contract
	 * @subpackage Korkmaz_contract/admin
	 * @author     Yemliha KORKMAZ <yemlihakorkmaz@hotmail.com>
	 */
	class Korkmaz_woo_sales_contract_Admin
	{
		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;
		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;
		private $birinci_sozlesme_link_ismi;
		private $ikinci_sozlesme_link_ismi;
		
		/**
		 * Initialize the class and set its properties.
		 *
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version The version of this plugin.
		 *
		 * @since    1.0.0
		 */
		public function __construct($plugin_name, $version)
		{
			
			$this->plugin_name = $plugin_name;
			$this->version = $version;
			$this->birinci_sozlesme_link_ismi = get_option('birinci_sozlesme_link_ismi');
			$this->ikinci_sozlesme_link_ismi = get_option('ikinci_sozlesme_link_ismi');
		}
		
		/**
		 * Admin Bölümü İçin Css Ekleme
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles()
		{
			
			/**
			 * Admin Bölümü İçin Kullanılan Css Ekleme Bölümü
			 */
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/korkmaz_contract-admin.css', array(), $this->version, 'all');
		}
		
		/**
		 * Admin Bölümü İçin Javascript Ekleme Bölümü
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts()
		{
			
			/**
			 * Admin bölümü için js dosyaları eklenen bölümü
			 */
			wp_enqueue_script($this->plugin_name . '3', plugin_dir_url(__FILE__) . 'js/korkmaz_contract-admin.js', array(
				'wp-i18n',
				'jquery'
			), $this->version, false);
		}
		
		public function korkmaz_woo_sales_contract_menu()
		{
			
			add_menu_page('Satış Sözleşmesi', __('Sözleşmeler', 'korkmaz_contract'), 'manage_options', 'sozlesme_ekrani', array(
				$this,
				'soz_fonksiyon',
			), 'dashicons-welcome-write-blog', 56);
			add_submenu_page('sozlesme_ekrani', 'Sözleşme Ayarları', __('Ayarlar', 'korkmaz_contract'), 'manage_options', 'sozlesme_ekrani', array(
				$this,
				'soz_fonksiyon',
			));
			add_submenu_page('sozlesme_ekrani', $this->birinci_sozlesme_link_ismi, $this->birinci_sozlesme_link_ismi, 'manage_options', 'birinci_sozlesme', array(
				$this,
				'birinci_sozlesme',
			));
			add_submenu_page('sozlesme_ekrani', $this->ikinci_sozlesme_link_ismi, $this->ikinci_sozlesme_link_ismi, 'manage_options', 'ikinci_sozlesme', array(
				$this,
				'ikinci_sozlesme',
			));
			add_submenu_page('sozlesme_ekrani', 'Alanlar', __('Alanlar', 'korkmaz_contract'), 'manage_options', 'alanlar', array(
				$this,
				'alanlar_fonksiyon',
			));
		}
		
		public function soz_fonksiyon()
		{
			
			include_once korkmaz_contract_dir . '/admin/partials/korkmaz_contract-admin-display.php';
		}
		
		public function vergi_no_dairesi($order)
		{
			
			if ($order->get_meta('_billing_company') == null) {
				echo '<p><strong>' . __('Tc kimlik no', 'korkmaz_contract') . ':</strong> ' . esc_html($order->get_meta( '_shipping_tc')) . '</p>';
			} else {
				$vergiDaire = $order->get_meta('_billing_vergi_dairesi');
				$vergiNum = $order->get_meta('_billing_vergi_nosu');
				echo '<p><strong>' . __('Vergi Dairesi', 'korkmaz_contract') . ':</strong> ' . esc_html($vergiDaire) . '</p>';
				echo '<p><strong>' . __('Vergi No', 'korkmaz_contract') . ':</strong> ' . esc_html($vergiNum) . '</p>';
			}
		}
		
		public function birinci_sozlesme()
		{
			
			include_once korkmaz_contract_dir . '/admin/partials/bir_sozlesme_ekrani.php';
		}
		
		public function ikinci_sozlesme()
		{
			
			include_once korkmaz_contract_dir . '/admin/partials/iki_sozlesme_ekrani.php';
		}
		
		public function alanlar_fonksiyon()
		{
			echo '<div class="wrap">';
			echo '<h1>Alanlar</h1>';
			echo '<form method="post" action="options.php">';
			settings_fields('alanlar_options');
			do_settings_sections('alanlar_options');
			submit_button();
			echo '</form>';
			echo '</div>';
		}
		
		public function alanlar_settings_init()
		{
			register_setting('alanlar_options', 'alanlar_options', array(
				$this,
				'alanlar_options_sanitize'
			));
			add_settings_section('alanlar_section', 'Alanlar Ayarları', array(
				$this,
				'alanlar_section_cb'
			), 'alanlar_options');
			$alan_names = array(
				__("Fatura İsim alanı", "korkmaz_contract"),
				__("Fatura Soyisim alanı", "korkmaz_contract"),
				__("Fatura Şirket İsmi Alanı", "korkmaz_contract"),
				__("Fatura Vergi No Alanı", "korkmaz_contract"),
				__("Fatura Vergi Dairesi Alanı", "korkmaz_contract"),
				__("Fatura Adress 1 Alanı", "korkmaz_contract"),
				__("Fatura Adress 2 Alanı", "korkmaz_contract"),
				__("Fatura Sehir Alanı", "korkmaz_contract"),
				__("Fatura Posta Kodu Alanı", "korkmaz_contract"),
				__("Fatura Ülke Alanı", "korkmaz_contract"),
				__("Fatura İlçe Alanı", "korkmaz_contract"),
				__("Fatura Email Alanı", "korkmaz_contract"),
				__("Fatura Telefon Alanı", "korkmaz_contract"),
				__("Fatura Tc Kimlik No Alanı", "korkmaz_contract"),
				__("Fatura Ürün Bilgileri Alanı", "korkmaz_contract"),
				__("Fatura Sepet Toplamı Alanı", "korkmaz_contract"),
				__("Gönderim İsmi Alanı", "korkmaz_contract"),
				__("Gönderim Soyisim Alanı", "korkmaz_contract"),
				__("Gönderim Şirket İsmi Alanı", "korkmaz_contract"),
				__("Gönderim Adresi 1 Alanı", "korkmaz_contract"),
				__("Gönderim Adresi 2 Alanı", "korkmaz_contract"),
				__("Gönderim İlçe Alanı", "korkmaz_contract"),
				__("Gönderim Posta Kodu Alanı", "korkmaz_contract"),
				__("Gönderim Ülke Alanı", "korkmaz_contract"),
				__("Gönderim İl Alanı", "korkmaz_contract"),
				__("Ödeme Yöntemi Alanı", "korkmaz_contract"),
				__("Alan 27", "korkmaz_contract"),
				__("Alan 28", "korkmaz_contract"),
				__("Alan 29", "korkmaz_contract"),
				__("Alan 30", "korkmaz_contract")
			);
			for ($i = 1; $i <= 30; $i++) {
				
				$label_name = $alan_names[$i - 1];
				add_settings_field("alan_$i", $label_name, array(
					$this,
					'alan_field_cb'
				), 'alanlar_options', 'alanlar_section', array(
					'label_for' => "alan_$i",
				));
			}
		}
		
		public function alanlar_section_cb()
		{
			echo 'Bu bölümde ödeme sayfasındaki ilgili alanların id veya class isimlerini değiştirebilirsiniz. Lütfen dikkatli olun burda yaptığnız bir hata eklentinin çalışmamasına neden olabilir';
		}
		
		public function alan_field_cb($args)
		{
			$options = get_option('alanlar_options');
			$id = $args['label_for'];
			$value = isset($options[$id]) ? $options[$id] : '';
			$alanNumarasi = str_replace('alan_', '', $id);
			// Default değerler dizisi
			$default_degerler = array(
				'#billing_first_name',
				'#billing_last_name',
				'#billing_company',
				'#billing_vergi_nosu',
				'#billing_vergi_dairesi',
				'#billing_address_1',
				'#billing_address_2',
				'#select2-billing_state-container',
				'#billing_postcode',
				'#select2-billing_country-container',
				'#billing_city',
				'#billing_email',
				'#billing_phone',
				'#shipping_tc',
				'table.woocommerce-checkout-review-order-table',
				'#sepettoplami',
				'#shipping_first_name',
				'#shipping_last_name',
				'#shipping_company',
				'#shipping_address_1',
				'#shipping_address_2',
				'#shipping_city',
				'#shipping_postcode',
				'#select2-shipping_country-container',
				'#select2-shipping_state-container',
				'#odemeyontemiinput',
				'',
				'',
				'',
				'',
			);
			// Bu alan için default değeri al
			$default_deger = $default_degerler[$alanNumarasi - 1];
			// İnput alanı ve yanına default değeri yazdır
			echo '<input type="text" id="' . $id . '" name="alanlar_options[' . $id . ']" value="' . $value . '" />';
			echo ' Default: ' . $default_deger;
		}
		
		public function alanlar_options_sanitize($input)
		{
			$output = array();
			for ($i = 1; $i <= 30; $i++) {
				if (isset ($input["alan_$i"])) {
					$output["alan_$i"] = sanitize_text_field($input["alan_$i"]);
				}
			}
			return $output;
		}
		
		function kisayol_ekle()
		{
		}
		
		function buton_ekle($buttons)
		{
			array_push($buttons, 'wdm_mce_button');
			return $buttons;
		}
		
		// EDİTOR ALANINA KISAKOD EKLEME FONKSİYONU
		function js_buton_ekle($plugin_array)
		{
			$eklentiurl = isset($_GET['page']);
			if ($eklentiurl == 'birinci_sozlesme' || $eklentiurl == 'ikinci_sozlesme') {
				// check user permissions
				if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
					return;
				} else {
					$plugin_array['wdm_mce_button'] = plugins_url('js/quicktagsekle.js', __FILE__);
					return $plugin_array;
				}
			}
		}
		
		function custom_shop_order_column($columns)
		{
			
			$reordered_columns = array();
			// Inserting columns to a specific location
			foreach ($columns as $key => $column) {
				$reordered_columns[$key] = $column;
				if ($key == 'order_status') {
					// Inserting after "Status" column
					$reordered_columns['sozlesmeler'] = __('Sözleşmeler', 'korkmaz_contract');
				}
			}
			return $reordered_columns;
		}
		
		function custom_orders_list_column_content($column, $post_id)
		{
			
			switch ($column) {
				case 'sozlesmeler':
					$order = wc_get_order($post_id);
					$this->butonlari_goster_admin($order);
					break;
			}
		}
		
		public function butonlari_goster_admin($order)
		{
			
			
			$birinci_sozlesme = $order->get_meta('birinci_sozlesme_dosya');
			$ikinci_sozlesme = $order->get_meta('ikinci_sozlesme_dosya');
			$upload_dir = home_url();
			$pdf_file_name1 = $this->dosya_ismi_al($birinci_sozlesme);
			$pdf_file_name2 = $this->dosya_ismi_al($ikinci_sozlesme);
			$birlink = $upload_dir . '/wp-content/uploads/korkmazsozlesme/' . $pdf_file_name1;
			$ikilink = $upload_dir . '/wp-content/uploads/korkmazsozlesme/' . $pdf_file_name2;
			?>
			
			
			<div class="sozlemelertesekkur">
				<a target="_blank" href="<?php
					echo esc_url($birlink); ?>"><?php
						echo esc_html($this->birinci_sozlesme_link_ismi); ?></a><br>
				<a target="_blank" href="<?php
					echo esc_url($ikilink); ?>"><?php
						echo esc_html($this->ikinci_sozlesme_link_ismi); ?></a><br>
			</div>
			
			
			<?php
		}
		
		function dosya_ismi_al($link)
		{
			
			$parcala = explode("/", $link);
			return end($parcala);
		}
	}
