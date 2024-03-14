<?php
/**
 * Eklentinin ön yüzünde çalışacak olan fonksiyonları
 *
 * @link       http://yemlihakorkmaz.com
 * @since      1.0.0
 *
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/public
 * @author     Yemliha KORKMAZ yemlihakorkmaz@hotmail.com
 */
class Korkmaz_contract_Public
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
	/**
	 * Sözleşme birinci isminin admin panelinden kayıt edildiği ve gösterileceği
	 * değişken
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $birinci_sozlesme_link_ismi birinci bilgindirme linki
	 */
	private $birinci_sozlesme_link_ismi;
	/**
	 * Sözleşme ikinci isminin admin panelinden kayıt edildiği ve gösterileceği
	 * değişken
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $ikinci_sozlesme_link_ismi birinci bilgindirme linki
	 */
	private $ikinci_sozlesme_link_ismi;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/korkmaz_contract-public.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . '1', plugin_dir_url(__FILE__) . 'css/hystmodal.min.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		if (is_checkout()) {
			// JS dosyalarını tanımla
			$print_script_handle = $this->plugin_name . '_print';
			$modal_script_handle = $this->plugin_name . '_modal';
			$custom_script_handle = $this->plugin_name . '_custom';
			// Scripts enqueue
			wp_enqueue_script('jquery');
			wp_enqueue_script($print_script_handle, plugin_dir_url(__FILE__) . 'js/printThis.js', array('jquery'), $this->version, false);
			wp_enqueue_script($modal_script_handle, plugin_dir_url(__FILE__) . 'js/hystmodal.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script($custom_script_handle, plugin_dir_url(__FILE__) . 'js/korkmaz_contract_custom.js', array('jquery'), $this->version, false);
			// Veriyi JS'ye aktar
			$options = get_option('alanlar_options');
			$data_for_js = is_array($options) ? $options : array();
			wp_localize_script($custom_script_handle, 'korkmaz_var', $data_for_js);
		}
	}

	/**
	 * Formdan Gelen Sözleşmeleri Html Kodları İle Birlikte Kayıt Eden
	 * Fonksiyon
	 */
	public function sozlesmeleri_html_olarak_kaydet($order_id)
	{
		$billing_satis_sozlesme = wp_filter_post_kses($_POST['billing_satis_sozlesme']);
		if (!empty($billing_satis_sozlesme)) {
			update_post_meta($order_id, '_billing_satis_sozlesme', $billing_satis_sozlesme);
		}
		$billing_mesafeli_sozlesme = wp_filter_post_kses($_POST['billing_mesafeli_sozlesme']);
		if (!empty($billing_mesafeli_sozlesme)) {
			update_post_meta($order_id, '_billing_mesafeli_sozlesme', $billing_mesafeli_sozlesme);
		}
	}

	/**
	 * Ödeme Sayfasında Sözelşmelerin Görüntülenmesi İşleminde Kullanılan
	 * Fonksiyon
	 */
	public function sozlesme_goruntule()
	{

		if (function_exists('is_checkout') && is_checkout()) {
			global $woocommerce;
			// Satış ve Mesafeli sözleşmelerin veritabanından dan alınıp değişkene atanması
			$birincisozlesme = stripslashes(get_option('birinci_sozlesme_metni'));
			$ikincisozlesme = stripslashes(get_option('ikinci_sozlesme_metni'));
			//sepetteki fiyat
			$amount = WC()->cart->get_total();
			// Müşteri İp Adresi Bilgileri
			$musteri_ip_bilgileri = new WC_Geolocation();
			$musteri_ipadresi = $musteri_ip_bilgileri->get_ip_address();
			$musteri_external_ip = $musteri_ip_bilgileri->get_external_ip_address();
			?>

            <textarea name="" id="urunbilgileri" cols="30" rows="10">
				<table>
            <tr><th>Ürün Adı </th> <th> Miktarı</th> <th>Fiyat </th> </tr>
				
            <?php
            $kdv = 0;
            $taxes = $woocommerce->cart->get_taxes();
            foreach ($taxes as $value) {
	            $kdv += $value;
            }
            $kdv;
            $items = $woocommerce->cart->get_cart();
            foreach ($items as $item => $values) {
	            $_product = wc_get_product($values['data']->get_id());
	            echo "<td>" . $_product->get_title() . '</td>  <td>' . $values['quantity'] . '</td>';
	            $price = get_post_meta($values['product_id'], '_price', true);
	            echo "  <td> " . wc_price($price) . "</td>";
	            echo '    </tr>';
            }
            if (!empty($amount))
	            echo '<tr><td><b>Toplam</b><td><td colspan="2">' . $amount . '</td></tr>';
            ?>
        </table>

        </textarea>
            <input type="hidden" id="sepettoplami" value="<?php
			echo esc_html($amount); ?>"/>

            <input type="hidden" id="firmaadi" value="<?php
			echo esc_html(get_option('firmaadi')); ?>"/>

            <input type="hidden" id="firmaadresi" value="<?php
			echo esc_html(get_option('firmaadresi')); ?>"/>

            <input type="hidden" id="firmatelno" value="<?php
			echo esc_html(get_option('firmatelno')); ?>"/>

            <input type="hidden" id="firmaverdaire" value="<?php
			echo esc_html(get_option('firmaverdaire')); ?>"/>

            <input type="hidden" id="firmaverno" value="<?php
			echo esc_html(get_option('firmaverno')); ?>"/>

            <input type="hidden" id="odemeyontemiinput" value=""/>

            <input type="hidden" id="musteri_ipadresi" value="<?php
			echo esc_html($musteri_ipadresi); ?>"/>

            <input type="hidden" id="musteri_external_ip" value="<?php
			echo esc_html($musteri_external_ip); ?>"/>

            <!--            <div id="birinci_sozlesme" class="modal">--><?php
			//				echo html_entity_decode(esc_html($birincisozlesme)); ?><!--</div>-->
            <!---->
            <!--            <div id="ikinci_sozlesme" class="modal">--><?php
			//				echo html_entity_decode(esc_html($ikincisozlesme)); ?><!--</div>-->

            <!--            <div class="hystmodal" id="ilksozlesme" aria-="true">-->
            <!--                <div class="hystmodal__wrap">-->
            <!--                    <div class="hystmodal__window" role="dialog" aria-modal="true">-->
            <!--                        <a data-hystclose class="hystmodal__close">Kapat</a>-->
            <!--                        <div id="birinci_sozlesme_modal">--><?php
			//							echo html_entity_decode(esc_html($birincisozlesme)); ?><!--</div>-->
            <!---->
            <!---->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->


            <div class="hystmodal" id="birinciModal" aria-="true">
                <div class="hystmodal__wrap">
                    <div class="hystmodal__window" role="dialog" aria-modal="true">
                        <button data-hystclose class="hystmodal__close">Close</button>
                        <div id="modalContentbir">
                            Sözleşme henüz hazır değil...
                        </div>
                    </div>
                </div>
            </div>


            <div class="hystmodal" id="ikinciModal" aria-="true">
                <div class="hystmodal__wrap">
                    <div class="hystmodal__window" role="dialog" aria-modal="true">
                        <button data-hystclose class="hystmodal__close">Close</button>
                        <div id="modalContentiki">
                            Sözleşme henüz hazır değil...
                        </div>
                    </div>
                </div>
            </div>


			<?php
		}
	}

	/**
	 * Eğer sözleşmeler onaylanmazsa çalışacak olan fonksiyon
	 */
	public function onayla_uyari()
	{
		if (get_option('sozlesme_ozellik_3') == 1) {
			if (!(int)isset($_POST['sozlesme_onayla'])) {
				wc_add_notice(__('Lütfen sözleşmeleri okuyup onaylayınız!'), 'error');
			}
		}
	}

	/**
	 * Ödeme Ekranına Satış Sözleşmesi ve Mesafeli Satış Sözleşmesi, Vergi
	 * Tc Kimlik gibi inputları eklemeye yarayan fonksiyon
	 */
	public function odeme_ekrani_custom_input_ekle($fields)
	{

		$fields['billing']['billing_satis_sozlesme'] = [
			'type'     => 'textarea',
			'label'    => $this->birinci_sozlesme_link_ismi,
			'class'    => [
				'billing_satis_sozlesme hidden',
				''
			],
			'required' => false,
			'clear'    => true,
			'priority' => 120,
		];
		$fields['billing']['billing_mesafeli_sozlesme'] = [
			'type'     => 'textarea',
			'label'    => $this->ikinci_sozlesme_link_ismi,
			'class'    => [
				'billing_mesafeli_sozlesme hidden',
				''
			],
			'required' => false,
			'clear'    => true,
			'priority' => 121,
		];
		if (get_option('sozlesme_ozellik_6') == 1) {

			$fields['billing']['musteri_tipi'] = array(
				'type'     => 'radio',
				'label'    => __('Müşteri Tipi', 'korkmaz_contract'),
				'class'    => array(
					'form-row-wide',
					'musteri-tipi-radio'
				),
				'options'  => array(
					'bireysel' => __('Bireysel', 'korkmaz_contract'),
					'kurumsal' => __('Kurumsal', 'korkmaz_contract'),
				),
				'default'  => 'bireysel',
				// Bireysel default olarak seçili olacak.
				'priority' => 20,
			);
			$fields['billing']['shipping_tc'] = array(
				'label'       => __('TC Kimlik No', 'korkmaz_contract'),
				'placeholder' => __('11 haneli TC kimlik numaranız', 'placeholder', 'korkmaz_contract'),
				'class'       => array('form-row-wide'),
				'priority'    => 33,
				'clear'       => true,
				'required'    => false,
			);
			$fields['billing']['billing_vergi_dairesi'] = array(
				'label'       => __('Vergi Dairesi', 'woocommerce'),
				'placeholder' => _x('Vergi Dairesi', 'placeholder', 'korkmaz_contract'),
				'class'       => array('form-row form-row-first'),
				'required'    => false,
				'priority'    => 32,
				'clear'       => true
			);
			$fields['billing']['billing_vergi_nosu'] = array(
				'label'       => __('Vergi Numarası', 'korkmaz_contract'),
				'placeholder' => __('Vergi Numarası', 'placeholder', 'korkmaz_contract'),
				'class'       => array('form-row form-row-last'),
				'required'    => false,
				'priority'    => 32,
				'clear'       => true
			);
		}
		return $fields;
	}

	/**
	 * Ajax ile post edilen değerleri json formatına atayan fonksiyon
	 */
	public function soz_fn_cagir()
	{

		$result['birinci_sozlesme'] = stripslashes(wp_filter_post_kses($_POST['sozlesme1']));
		$result['ikinci_sozlesme'] = stripslashes(wp_filter_post_kses($_POST['sozlesme2']));
		// $result = json_encode($result);
		wp_send_json($result);
		wp_die();
	}

	public function metin_getir()
	{
		$response = wp_remote_get("https://yemlihakorkmaz.com/sozlesmeornek.txt");
		if (is_wp_error($response)) {
			$defaultmetin = "Örnek metine erişilemedi";
		} else {
			$defaultmetin = wp_remote_retrieve_body($response);
		}
		$metin1 = stripslashes(get_option('birinci_sozlesme_metni', $defaultmetin));
		$metin2 = stripslashes(get_option('ikinci_sozlesme_metni', $defaultmetin));

		$result = array(
			'birinci' => $metin1,
			'ikinci'  => $metin2
		);
		echo json_encode($result);
		wp_die(); // Ajax işlemini sonlandırma
	}

	/**
	 * Ajax admin url'yi tanımlayan javascript değişkenine atayan fonksiyon
	 */
	public function myplugin_ajaxurl()
	{

		echo '<script type="text/javascript">
 	
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
           
         </script>';
	}

	/**
	 * Ödeme sayfasında sozlesmeyi goruntuleme bolumu fonksiyonu
	 *
	 */
	public function sozlesme_goster_odeme_sayfasi()
	{
		if (get_option('sozlesme_ozellik_3') == 1) {
			$birincisozlesme = stripslashes(get_option('birinci_sozlesme_metni'));
			$ikincisozlesme = stripslashes(get_option('ikinci_sozlesme_metni'));
			$link1 = '<a href="javascript:void(0)" data-hystmodal="#birinciModal">' . $this->birinci_sozlesme_link_ismi . '</a>';
			$link2 = '<a href="javascript:void(0)" data-hystmodal="#ikinciModal"> ' . $this->ikinci_sozlesme_link_ismi . '</a>';
			woocommerce_form_field('sozlesme_onayla', array(
				'type'        => 'checkbox',
				'class'       => array('form-row privacy'),
				'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
				'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
				'required'    => true,
				'label'       => sprintf(__("%s ve %s okudum kabul ediyorum.", "korkmaz_contract"), $link1, $link2),
			));
		}
	}

	/**
	 * Ödeme sayfası sonrası sözleşme sayfasında sözleşmeleri görüntüleyen
	 * fonksiyon
	 */
	public function view_order_sozlesme_goster($order_id)
	{

		if (get_option('sozlesme_ozellik_5') == 1) {
			
			$order=wc_get_order($order_id);
			$birinci_sozlesme =$order->get_meta('birinci_sozlesme_dosya');
			$ikinci_sozlesme = $order->get_meta('ikinci_sozlesme_dosya');
			$upload_dir = home_url();
			$pdf_file_name1 = $this->dosya_ismi_al($birinci_sozlesme);
			$pdf_file_name2 = $this->dosya_ismi_al($ikinci_sozlesme);
			$birlink = $upload_dir . '/wp-content/uploads/korkmazsozlesme/' . $pdf_file_name1;
			$ikilink = $upload_dir . '/wp-content/uploads/korkmazsozlesme/' . $pdf_file_name2;
			?>
            <div class="sozlemelertesekkur">
                <a target="_blank"
                   href="<?php
				   echo esc_url($birlink); ?>"><?php
					echo esc_html($this->birinci_sozlesme_link_ismi); ?></a><br>
                <a target="_blank"
                   href="<?php
				   echo esc_url($ikilink); ?>"><?php
					echo esc_html($this->ikinci_sozlesme_link_ismi); ?></a><br>
            </div>
			<?php
		}
	}

	public function dosya_ismi_al($link)
	{
		$parcala = explode("/", $link);
		return end($parcala);
	}



	/**
	 * Ödeme Ekranında Tc Numarasını Doğrulayan Fonksiyon
	 */
	// T.C. Doğrula Fonksiyonu
	/**
	 * Ödeme sayfası sonrası sözleşme sayfasında sözleşmeleri görüntüleyen
	 * fonksiyon
	 */
	public function front_sozlesme_goster($order_id)
	{

		if (get_option('sozlesme_ozellik_2') == 1) {
			
			// WC_Order nesnesi oluştur
			$order = wc_get_order($order_id);
			
			// Sipariş meta verilerini al
			$birinci_sozlesme = $order->get_meta('birinci_sozlesme_dosya');
			$ikinci_sozlesme = $order->get_meta('ikinci_sozlesme_dosya');
			
			$upload_dir = home_url();
			$pdf_file_name1 = $this->dosya_ismi_al($birinci_sozlesme);
			$pdf_file_name2 = $this->dosya_ismi_al($ikinci_sozlesme);
			$birlink = $upload_dir . '/wp-content/uploads/korkmazsozlesme/' . $pdf_file_name1;
			$ikilink = $upload_dir . '/wp-content/uploads/korkmazsozlesme/' . $pdf_file_name2;
			?>
            <div class="sozlemelertesekkur">
                <a target="_blank"
                   href="<?php
				   echo esc_url($birlink); ?>"><?php
					echo esc_html($this->birinci_sozlesme_link_ismi); ?></a><br>
                <a target="_blank"
                   href="<?php
				   echo esc_url($ikilink); ?>"><?php
					echo esc_html($this->ikinci_sozlesme_link_ismi); ?></a><br>
            </div>
			<?php
		}
	}

	public function tc_numara_dogrula()
	{
		if (get_option('sozlesme_ozellik_6') == 1) {
			$tcno = $_POST['shipping_tc'];
			$tcdogrula = $this->tckimlikdogrumu($tcno);
			if (!empty($tcno)) {
				if (!$tcdogrula) {
					wc_add_notice(__('Lütfen Doğru Bir TC Kimlik No Girin.', 'korkmaz_contract'), 'error');
				}
			}
		}
	}

	public function tckimlikdogrumu($TCKimlikNo)
	{
		if (strlen($TCKimlikNo) == 11)   //onbir haneyse işleme devam et
		{
			$basamak = str_split($TCKimlikNo);  //basamaklarına ayır
			$basamak1 = $basamak[0];
			$basamak2 = $basamak[1];
			$basamak3 = $basamak[2];
			$basamak4 = $basamak[3];
			$basamak5 = $basamak[4];
			$basamak6 = $basamak[5];
			$basamak7 = $basamak[6];
			$basamak8 = $basamak[7];
			$basamak9 = $basamak[8];
			$basamak10 = $basamak[9];
			$basamak11 = $basamak[10];
			$basamak10_test = fmod(($basamak1 + $basamak3 + $basamak5 + $basamak7 + $basamak9) * 7 - ($basamak2 + $basamak4 + $basamak6 + $basamak8), 10);
			$basamak11_test = fmod($basamak1 + $basamak2 + $basamak3 + $basamak4 + $basamak5 + $basamak6 + $basamak7 + $basamak8 + $basamak9 + $basamak10, 10);
		}
		if (strlen($TCKimlikNo) != 11)   //onbir hane değilse geçersizdir.
		{
			$sonuc = false;
		} elseif ($basamak1 == 0)   //birinci basamak sıfır olamaz
		{
			$sonuc = false;
		} elseif (!is_numeric($basamak1) or !is_numeric($basamak2) or !is_numeric($basamak3) or !is_numeric($basamak4) or !is_numeric($basamak5) or !is_numeric($basamak6) or !is_numeric($basamak7) or !is_numeric($basamak8) or !is_numeric($basamak9) or !is_numeric($basamak10) or !is_numeric($basamak11)) {
			$sonuc = false;
		} elseif ($basamak10_test != $basamak10) // T.C. Kimlik Numaralarımızın 1. 3. 5. 7. ve 9. hanelerinin toplamının 7 katından, 2. 4. 6. ve 8. hanelerinin toplamı çıkartıldığında, elde edilen sonucun 10'a bölümünden kalan, yani Mod10'u bize 10. haneyi verir.
		{
			$sonuc = false;
		} elseif ($basamak11_test != $basamak11)   // 1. 2. 3. 4. 5. 6. 7. 8. 9. ve 10. hanelerin toplamından elde edilen sonucun 10'a bölümünden kalan, yani Mod10'u bize 11. haneyi verir.
		{
			$sonuc = false;
		} else {
			$sonuc = true;
		}
		return $sonuc;
	}

	public function sozlesme_olustur_pdf($order_id)
	{
		$birincisozlesmeismi = sanitize_title($this->birinci_sozlesme_link_ismi);
		$ikincisozlesmeismi = sanitize_title($this->ikinci_sozlesme_link_ismi);
		$order = new WC_Order($order_id);
		$birinci_sozlesme = $this->pdf_url($order_id, '_billing_satis_sozlesme', $birincisozlesmeismi);
		if (!is_wp_error($birinci_sozlesme)) {
			$order->update_meta_data('birinci_sozlesme_dosya', $birinci_sozlesme);
			$order->save();
		}
		$ikinci_sozlesme = $this->pdf_url($order_id, '_billing_mesafeli_sozlesme', $ikincisozlesmeismi);
		if (!is_wp_error($ikinci_sozlesme)) {
			$order->update_meta_data('ikinci_sozlesme_dosya', $ikinci_sozlesme);
			$order->save();
		}
	}

	public function pdf_url($order_id, $ismi, $sozlesmeadi)
	{
		$order = wc_get_order($order_id);
		$korkmaz_mail_fonksiyon = new korkmaz_contract_dompdf();
		$birinci_sozlesme_metin = $this->pdf_metni_hazirla($order->get_meta($ismi, true));
		$birinci_sozlesme = $korkmaz_mail_fonksiyon->generate_pdf($birinci_sozlesme_metin, $order_id, true, $sozlesmeadi);
		return $birinci_sozlesme;
	}

	/**
	 * Aşağıda bulunan fonksiyon ile pdf oluşturmak için html
	 * hazırlanmıştır
	 */
	public function pdf_metni_hazirla($metin)
	{
		$stil = '<style>body {font-family: DejaVu Sans,sans-serif;}* {font-size: 11px !important;}.yazdirbtn {display: none;}table {width: 100%;}table,th,td {border: 1px solid #333;}li {list-style: none;text-decoration: none;}ul,li {margin: 0px;padding: 0px;}</style>';
		return "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' />{$stil}</head><body>" . wp_unslash($metin) . '</body></html>';
	}

	public function siparis_mail_ekle($ekler, $durum, $siparis)
	{

		if (get_option('sozlesme_ozellik_4') == 1) {

			$maildurum = "customer_completed_order";
			if(!empty(get_option('sozlesme_mail_durumu'))){
				$maildurum = get_option('sozlesme_mail_durumu');
			}

			if ($durum != $maildurum) {
				error_log("eşit değil customer_completed_order");
				error_log($durum);
				return $ekler;
			}
			
			
			// Şu anki sitenin blog ID'sini alın
			$current_blog_id = get_current_blog_id();
			$upload_dir = wp_upload_dir(null, false, $current_blog_id);
			$sozlesme_dosyalari = [
				'birinci_sozlesme_dosya',
				'ikinci_sozlesme_dosya'
			];
			foreach ($sozlesme_dosyalari as $sozlesme_dosya) {
				$sozlesme = $siparis->get_meta($sozlesme_dosya);
				if (!is_wp_error($sozlesme)) {
					$dosya_adi = $this->dosya_ismi_al($sozlesme);
					$pdf_file_path = $upload_dir['basedir'] . '/korkmazsozlesme/' . $dosya_adi;
					$ekler[] = $pdf_file_path;
				}
			}
			return $ekler;
		}
	}
	
	
	
	
	
	
}
