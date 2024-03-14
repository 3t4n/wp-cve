<?php 
/*
Plugin : Epeken-All-Kurir
File Name : shipping.php
writer : epeken.com
*/
class WC_Shipping_Tikijne extends WC_Shipping_Method{	
	public  $jneclass;
	public  $shipping_cost;
 	public  $shipping_address;
  	public  $shipping_kecamatan;	
	public  $shipping_city;
	public  $shipping_country;
	public  $popup_message;
	public  $shipping_total_weight;
	public  $shipping_total_length;
	public  $shipping_total_width;
	public  $shipping_total_height;
    	public  $shipping_metric_dimension;
	public  $min_allow_fs;
	public  $total_cart;
	public  $is_free_shipping;
	public  $free_ongkir_criteria;
	public  $insurance_premium;	
	public  $array_of_tarif;
	public  $additionalLabel;
	public  $destination_province;
	public  $origin_city;
	public  $valid_origins;
	public  $chosen_shipping_method;
	public  $is_packing_kayu_valid;
	public  $current_currency;
	public  $current_currency_rate;
	public  $is_subsidi_applied;
	public  $vendor_id;
	public  $logger;
	public function __construct(){
		$this -> logger = new WC_Logger();
		ini_set('display_errors','Off');
		$this -> id = 'epeken_courier';
		$this -> current_currency_rate = 1;
		$this -> current_currency = 'IDR';
		$this -> method_title = __('Epeken All Kurir');
		$this -> method_description = __('Shipping Method using JNE TIKI RPX POS JMX J&T SICEPAT for Indonesia e-commerce market developed by Epeken');
		$this -> enabled = 'yes';
		$this -> title = 'Epeken All Kurir';
		$this -> is_free_shipping = false;
		$this -> init();			
		$this -> array_of_tarif = array();
		$this -> initiate_epeken_options();
	}
	public function refresh_usd_rate() {
                /* Get USD to IDR */
                if($this -> settings['mata_uang'] === "1"  && empty(WC() -> session -> get('EPEKEN_USD_RATE'))) {  //settings['mata_uang'] === "1" ~ USD
                        $json_usd_rate = epeken_get_usd_rate('BI');
                        $usd_rate_arr = json_decode($json_usd_rate, true);
                        WC() -> session -> set('EPEKEN_USD_RATE',$usd_rate_arr['status']['amount']);
                }
		if(defined('WOOMULTI_CURRENCY_F_VERSION')){
                        $wmc_settings     = get_option( 'woo_multi_currency_params', array() );
                        $currency_default = $wmc_settings['currency_default'];
                        if($this -> settings['mata_uang'] === "1" && $currency_default === "USD") {
                                WC() -> session -> set('EPEKEN_USD_RATE',$wmc_settings['currency_rate'][1]);
                        }
                }
                /* -- */
        	}

		public function initiate_epeken_options() {
			if(get_option('epeken_free_pc',false) === false){
                                add_option('epeken_free_pc','','','yes');
                        }
                        if(get_option('epeken_free_pc_q',false) === false) {
                                add_option('epeken_free_pc_1','','','yes');
                        }
			if(get_option('epeken_enabled_jne',false) === false) {
                                add_option('epeken_enabled_jne','','','yes');
                        }
			if(get_option('epeken_enabled_tiki',false) === false) {
                                add_option('epeken_enabled_tiki','','','yes');
                        }
			if(get_option('epeken_enabled_pos',false) === false) {
                                add_option('epeken_enabled_pos','','','yes');
                        }
			if(get_option('epeken_enabled_esl',false) === false) {
                                add_option('epeken_enabled_esl','','','yes');
                        }			
			if(get_option('epeken_enabled_jne_reg') === false) {
				add_option('epeken_enabled_jne_reg','','','yes');
			}
			if(get_option('epeken_enabled_jne_oke') === false) {
                                add_option('epeken_enabled_jne_oke','','','yes');
                        }
			if(get_option('epeken_enabled_jne_yes') === false) {
                                add_option('epeken_enabled_jne_yes','','','yes');
                        }
		}

		public function create_cek_resi_page(){
			global $user_ID;

			$pageckresi = get_page_by_title( 'cekresi','page' );
			if(!is_null($pageckresi))
			  return;

			$page['post_type']    = 'page';
			//$page['post_content'] = 'Put your page content here';
			$page['post_parent']  = 0;
			$page['post_author']  = $user_ID;
			$page['post_status']  = 'publish';
			$page['post_title']   = 'cekresi';
			$page = apply_filters('epeken_add_new_page', $page, 'teams');

		$pageid = wp_insert_post ($page);
		if ($pageid == 0) { /* Add Page Failed */ }

        	}
			
		public function add_cek_resi_page_to_prim_menu(){
                        $menu_name = 'primary';
                        $locations = get_nav_menu_locations();

			if(!isset($locations) || !is_array($locations))
                                return;

			if(!array_key_exists($menu_name,$locations))
					return;

			$menu_id = $locations[ $menu_name ] ;
			$menu_object = wp_get_nav_menu_object($menu_id);

			if(!$menu_object){
					return;
			}
			$menu_items = wp_get_nav_menu_items($menu_object->term_id);
			$is_menu_exist = false;
			foreach ( (array) $menu_items as $key => $menu_item ) {
					$post_title = $menu_item->post_title;
					if ($post_title === "Cek Resi"){
							$is_menu_exist = true;
							break;
					}
			}

			if($is_menu_exist){
					return;
			}

			$url = get_permalink( get_page_by_title( 'cekresi','page' ) );
			if($url) {
			wp_update_nav_menu_item($menu_object->term_id, 0, array(
					'menu-item-title' =>  __('Cek Resi'),
					'menu-item-url' =>  $url,
					'menu-item-status' => 'publish')
					);
			}

        	}

		public function delete_cek_resi(){
			
			if(is_multisite())
				return;

			$menu_name = 'primary';
			$locations = get_nav_menu_locations();

			if(!isset($locations) || !is_array($locations))
					return;

			if(!array_key_exists($menu_name,$locations))
					return;

			$menu_id = $locations[ $menu_name ] ;
			$menu_object = wp_get_nav_menu_object($menu_id);

			if(!$menu_object){
					return;
			}
			$menu_items = wp_get_nav_menu_items($menu_object->term_id);
			$is_menu_exist = false;
			foreach ( (array) $menu_items as $key => $menu_item ) {
					$post_title = $menu_item->post_title;
					if ($post_title === "Cek Resi"){
						$is_menu_exist = true;
						wp_delete_post($menu_item->ID,true);
					}
			}

			$page = get_page_by_title( 'cekresi','page' ) ;
			if(isset($page))
			 wp_delete_post($page->ID,true);
		}

		public function activate(){
			global $wpdb;
			$enable_cekresi = $this -> settings['enable_cekresi_page'];
			if($enable_cekresi === 'yes') {		
			 	$this->create_cek_resi_page();
                         	$this->add_cek_resi_page_to_prim_menu();
			}else{
				$this -> delete_cek_resi();
			}
			//update woocommerce_shipping_cost_requires_address to no
			update_option('woocommerce_shipping_cost_requires_address','yes');
			update_option('woocommerce_enable_shipping_calc','no');
		}

		public function writelog($logstr){
			$logger = $this -> logger;
			$logger -> add('epeken-all-kurir', $logstr);	
		}

		public function popup(){
			?>
			<div  id="div_epeken_popup">
                                        <p style='margin: 0 auto; text-align: center;padding-top: 5%;'>
                        <?php echo $this->popup_message; ?><br>
			<img style="display: block; margin: 0 auto;" src='<?php echo plugins_url('assets/ajax-loader.gif',__FILE__); ?>'>
                                        </p>
                        </div>
			<?php	
		}

		public function reset_user_address() {
			global $current_user;
		        get_currentuserinfo();
			update_user_meta($current_user -> ID,'billing_city','');
			update_user_meta($current_user -> ID,'shipping_city','');
			update_user_meta($current_user -> ID,'billing_address_1','');
                	update_user_meta($current_user -> ID,'shipping_address_1','');
			update_user_meta($current_user -> ID,'billing_address_2','');
                	update_user_meta($current_user -> ID,'shipping_address_2','');
		}

		public function validate_kecamatan() {
				$billing_kecamatan = filter_input(INPUT_POST, 'billing_address_2');
				$billing_kecamatna = trim($billing_kecamatan);		
				$shipping_kecamatan = filter_input(INPUT_POST, 'shipping_address_2');
				$shipping_kecamatan = trim($shipping_kecamatan);

				if($billing_kecamatan === 'Kecamatan (District)' && empty(WC() -> session -> get('isshippedifadr'))) {	
					wc_add_notice(__('Mohon pilih kecamatan dengan benar pada billing address.'), 'error');
				}
			
				if(WC() -> session -> get('isshippedifadr') === '1' && $shipping_kecamatan === 'Kecamatan (District)') {
					wc_add_notice(__('Mohon pilih kecamatan dengan benar pada shipping address.'), 'error');
				}
		}

		public function load_jne_tariff(){
                                 $ajax_url = admin_url('admin-ajax.php');
				 wp_enqueue_script('ajax_load_jne_tariff',plugins_url('/js/jne_load_tariff.js',__FILE__), array('jquery'));
				 wp_localize_script( 'ajax_load_jne_tariff', 'PT_Ajax', array(
        				'ajaxurl'       => $ajax_url
    				 ));
		}

		public function init() {
					// Load the settings API
				$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
				$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
				// Save settings in admin if you have any defined, when save button in admin setting screen is clicked
				add_action('woocommerce_update_options_shipping_' . $this->id,array(&$this, 'process_admin_options'));
				add_filter( 'woocommerce_cart_ready_to_calc_shipping', array(&$this,'disable_shipping_calc_on_cart'), 9999);
				add_action('woocommerce_update_options_shipping_methods', array(&$this, 'process_admin_options'));
				$this -> popup_message = __("Give me one second", 'epeken-all-kurir');
       				add_action('woocommerce_before_checkout_billing_form',array(&$this, 'popup'));
				add_action('woocommerce_checkout_process', array(&$this, 'reset_user_address'));
				add_action('woocommerce_checkout_process', array(&$this, 'validate_kecamatan'));
				add_action('woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_update_data_tarif' ) );
				add_action('admin_enqueue_scripts', array (&$this, 'epeken_admin_styles'));
				if(epeken_is_multi_vendor_mode()) {
					add_filter( 'woocommerce_shipping_package_name', array($this, 'epeken_change_shipping_pack_name'), 9999, 3);
				}
				$this -> activate();
		}

		public function render_admin_notices(){
		 $is_anteraja = in_array('anteraja/epeken-anteraja.php', apply_filters( 'active_plugins', get_option( 'active_plugins'))) ; 
		 if(is_admin() && !$is_anteraja && !epeken_is_multi_vendor_mode()) {
		   echo '<div class="notice notice-warning">
                   <p><strong>Baru dari Epeken</strong>
                    Tersedia plugin <strong>Anteraja</strong> yang dapat diinstal bersama dengan plugin Epeken All Kurir. 
		    <a href="https://wordpress.org/plugins/anteraja" target="_blank">Unduh di sini</a> dan install segera. Gratis untuk pengguna Epeken All Kurir dengan licensi yang masih berlaku.
                   </p></div>';	
		 } 
		}

	 /**
 		* Initialise Gateway Settings Form Fields
	 */
		public function init_form_fields() {
			$license = get_option('epeken_wcjne_license_key');
				$this->form_fields = array(
						'enabled' => array(
							'title'                 => __( 'Enable/Disable', 'woocommerce' ),
							'type'                  => 'checkbox',
							'label'                 => __( 'Enable this shipping method', 'woocommerce' ),
							'default'               => 'yes',
                        			),
						'checkbox_ongkir_vendor' => array(
                                                        'type' => 'checkbox_ongkir_vendor'
                                                ),
						'data_asal_kota' => array(
                                                        'type' => 'data_asal_kota'
                                                ),
                                                'is_kota_asal_in_product_details' => array (
                                                        'title' => 'Kota Asal Di Product Details',
                                                        'type' => 'checkbox',
                                                        'label' => 'Tampilkan kota asal di halaman product details dan checkout order details',
                                                        'default' => 'no'
                                                ),
						'data_server' => array(
							'type'  => 'data_server',
						),
						'panel_enable_kurir' => array(
							'type' => 'panel_enable_kurir',
						),
						'enable_cod' => array (
							'type' => 'enable_cod'
						),
						'enable_cod_kurir' => array(
							'type' => 'enable_cod_kurir'
						),
						'other_settings' => array(
							'type' => 'other_settings'
						),
						'volume_matrix' => array(
							'title' => __('Perhitungan Ongkir Dengan Berat', 'woocommerce'),
							'type' 		=> 'checkbox',
							'label'		=> __('Enable Ongkir Dengan Perhitungan Berat', 'woocommerce'),
							'description' => __('Centang checkbox ini supaya Ongkos Kirim dihitung berdasarkan berat belanjaan', 'woocommerce'),
							'default'	=> 'yes'
						),
						'perhitungan_dimensi' => array(
							'title' => __('Perhitungan Ongkir Dengan Dimensi', 'woocommerce'),
							'type' 		=> 'checkbox',
							'label'		=> __('Enable Ongkir Dengan Perhitungan Dimensi', 'woocommerce'),
							'description' => __('Centang checkbox ini supaya Ongkos Kirim dihitung berdasarkan dimensi belanjaan', 'woocommerce'),
							'default'	=> 'no'
						),
						'treshold_pembulatan' => array (
							'title' => __('Treshold pembulatan berat ke atas (Khusus JNE dan Tiki) dalam gram', 'woocommerce'), 
							'type' => 'number',
							'default' => 300,
						),
						'mata_uang' => array (
								'title' => 'Mata Uang Toko Online',
								'type' => 'select',
								'options' => array('IDR', 'USD'),
								'default' => 0, 
								'description' => 'Pilihan Mata Uang Toko Online, apakah Rupiah atau US Dollar. Defaultnya Rupiah.',
						),
						'freeship' => array(
								'title' => __('Nominal Belanja Minimum (Rupiah), Dapat Free Shipping (Biarkan 0 jika ingin free shipping disabled.)','woocommerce'),
								'type'  => 'text',
								'default' => '0',
						 ),
						'free_shipping_product_category' => array(
							 'type' => 'free_shipping_product_category',
						),
						 'city_for_free_shipping' => array(
                                                        'title' => __('Kota/Kabupaten yang tidak dikenakan biaya shipping(Pisahkan dengan tanda koma, jika lebih dari satu)','woocommerce'),
                                                        'type' => 'text',
                                                ),
						'province_for_free_shipping' => array(
                                                        'type' => 'province_for_free_shipping'
                                                ),
						'kombinasikan_free_shipping' => array(
							'type' => 'kombinasikan_free_shipping',
						),
						'enable_cekresi_page' => array(
							'title' => __('Enable Cek Resi appears in main Menu'),
							'type' => 'checkbox',
							'label' => __('Enable/Disable Cek Resi Page.<br> Jika Anda ingin menaruh halaman cek resi di sub menu, disable checkbox ini, lalu gunakan shortcode [epeken_cekresi] untuk membuat halaman cekresi lalu menambahkan halaman tersebut pada sub menu yang Anda kehendaki.'),
							'default' => 'no'
						),
						'form_biaya_tambahan' => array (
							'title' => __('Biaya Tambahan (Misal. Biaya Packing)'),
							'type' => 'form_biaya_tambahan'
                        			),
						'flat_tarif' => array(
							'title' => __('Tarif Flat'),
							'type' => 'flat_tarif'
						), 
						'subsidi_ongkir_dengan_kupon' => array(
							'type' => 'subsidi_ongkir_dengan_kupon'
						),
						'enable_kode_pembayaran' => array (
							'title' => __('Enable Kode Pembayaran(Angka Unik)'),
							'type' => 'checkbox',
							'label' => __('Aktifkan Kode Pembayaran(Angka Unik) saat checkout'),
							'default' => 'yes'
						),
						'enable_btn_konfirmasi_pembayaran' => array (
							'title' => __('Enable button konfirmasi pembayaran di layar My Account > Order'),
							'type' => 'checkbox', 
							'label' => 'Enable Tombol Konfirmasi Pembayaran pada layar backend My Account > Order',
							'default' => 'no'
						),
						'auto_populate_returning_user_address' => array(
							'title' => __('Autopopulate alamat pelanggan saat checkout ?'),
							'type' => 'checkbox', 
							'label' => 'Yes',
							'default' => 'yes'
						),
						'url_konfirmasi_pembayaran' => array(
							'title' => __('URL Konfirmasi Pembayaran'),
							'type' => 'text', 
							'default' => '', 
							'placeholder' => 'http://',
							'description' => 'Kosongkan URL Konfirmasi Pembayaran, jika Anda menggunakan'.
									  ' fitur <a target="_blank" href="http://blog.epeken.com/konfirmasi-pembayaran">konfirmasi pembayaran</a> bawaan plugin epeken. Tombol Konfirmasi Pembayaran pada layar My Account Order (jika dienable) akan melink ke URL ini.',
						),
						'epeken_processing_order_status_after_konfirmasi' => array (
							'title' => __('Status Order "Processing" setelah konfirmasi pembayaran'),
							'type' => 'checkbox', 
							'label' => 'Enable',
							'default' => 'no'
						),
						'max_angka_unik' => array(
							'title' => __('Maksimum Angka Unik untuk kode pembayaran (Disarankan maksimal 999)'),
							'type' => 'number',
							'label' => __('Maksimum Angka Unik untuk kode pembayaran (Disarankan maksimal 999)'),
							'default' => '99'
						),
						'mode_kode_pembayaran' => array (
                                                        'type' => 'mode_kode_pembayaran'
                                                 ),
						'enable_insurance' => array (
								'title' => __('Enable Insurance(Fitur Asuransi) <strong>Khusus JNE</strong>','woocommerce'),
								'type' => 'checkbox',
								'label' => __('Aktifkan fitur asuransi'),
								'default' => 'no'
						),					
						'show_footer_in_cek_resi' => array (
								'title' => __('Show Footer In Cek Resi ', 'woocommerce'),
								'type' => 'checkbox', 
								'label' => __('Sesuaikan Footer Pada Halaman Cek Resi'),
								'default' => 'yes'
						),
						'prodcat_with_insurance' => array(
							'title' => __('Product Category dimana asuransi adalah diwajibkan (Pisahkan dengan tanda koma, jika lebih dari satu)'),
							'type' => 'text'
						),
						'packing_kayu_settings' => array(
                                                        'type' => 'packing_kayu_settings'
                                                ),
						'multiple_currency_rate' => array(
                                                        'title' => __('Multi Currency Rate Settings'),
                                                        'type' => 'multiple_currency_rate'
                                                ),
						'epeken_tools' => array (
                                                        'type' => 'epeken_tools'
                                                ),
					);
		} // End init_form_fields()


   			// Our hooked in function - $fields is passed via the filter!
		public function admin_options() {
		  $this -> render_admin_notices();
 		?>
			<h2>
  		  <?php 
		   _e('Epeken-All-Kurir Shipping Settings','woocommerce'); 
		  ?></h2>
		  <table class="form-table">
		  <?php $this->generate_settings_html(); ?>
		  </table> <?php
 		}

	public function generate_multiple_currency_rate_html() {
                ob_start();
                ?>   
                <tr valign="top">
                <th>Multiple Currency Rate Settings</th>
                        <td>
                                <?php $check = get_option('epeken_multiple_rate_setting');
                                ?>
                                <p><input <?php if(empty($check) || $check==='manual') {echo "checked";}?> type="radio" name="epeken_multiple_rate_setting" value="manual"/>Manual</p>
                                <p><input <?php if($check ==='auto'){ echo "checked";}?> type="radio" name="epeken_multiple_rate_setting" value="auto"/>Refers to Bank Indonesia</p>
                                <p><em>Abaikan setting ini jika Anda tidak menginstal plugin <a href="https://id.wordpress.org/plugins/woo-multi-currency/" target="_blank">woocommerce multi currency</a> bersama - sama dengan plugin epeken.</em></p>
                        </td>
                </tr>
                <?php
                return ob_get_clean();
        }
	
	public function generate_other_settings_html() {
		ob_start();
		do_action('epeken_other_settings');
		return ob_get_clean();
	}

	public function generate_epeken_tools_html() {
                ob_start();
                ?><tr valign="top">
                        <th>Tools</th><td>
							<table style="width: 100%"> <tr>
										<td>
							<table>
							<tr>
								<td>
								<p><?php $setting_eta = get_option('epeken_setting_eta'); 
									if($setting_eta === false){$setting_eta = 'on'; update_option('epeken_setting_eta','on');}?>
								<input type="checkbox" name="epeken_setting_eta" <?php if($setting_eta === 'on') echo "checked";?>>
								Enable Estimated Time Arrival (ETA) (support JNE, TIKI, SICEPAT, POS)
								</input></p>
 								</td>
  							</tr>
							<tr>
							<td>
							<?php $settingan_error=get_option('epeken_enable_error_message_setting'); ?>
							<input type="checkbox" name="epeken_en_er_msg" <?php if($settingan_error === 'on') echo "checked";?>> 
							  Set <strong>error_reporting(E_WARNING | E_PARSE | E_ERROR );</strong>
							</input>
							</td>
							</tr>
							<tr>
							<td>
							Set Berat Barang 1 kg untuk Barang yang Belum diset beratnya. 
							</td>
							<td><p><input class="button-primary woocommerce-save-button" type=submit name="save" value="Set Berat Barang 1 Kg"
							onclick="if(confirm('Anda yakin ingin mengeset berat barang 1 kg untuk SEMUA barang yang belum diset beratnya ?')==false){return false;}"
							/></p></td>
							</tr>
							<tr>
							<td>
							 Reset Kota asal untuk mengembalikan kota asal dari semua produk<br>ke kota asal default.
							</td>
							<td><p><input class="button-primary woocommerce-save-button" type="submit" name="save" value="Reset Kota Asal" 
							onclick="if(confirm('Anda yakin ingin mereset kota asal di level produk untuk semua produk Anda ?')==false){return false;}"
							></p>
							</td>
							</tr>
							<tr>
								<?php 
								$jqmradio = intval(sanitize_text_field(get_option("epeken_jqmradio")));
								?>
								<td>
									Posisi jQuery Migrate
							    </td>
								<td>
									<p><input type="radio" name="jqmradio" value="1" <?php if ($jqmradio == 1) echo "checked"; ?>> Header </input></p>
									<p><input type="radio" name="jqmradio" value="2"  <?php if ($jqmradio == 2 || $jqmradio == 0) echo "checked"; ?>> Footer </input></p>
							  	</td>
						   	</tr>
							<?php $email_korespondensi = get_option('epeken_email_korespondensi'); 
								if(empty($email_korespondensi)) {
									$email_korespondensi = get_option('admin_email');
						 		}?>
							<tr><td>Email Korespondensi</td><td>
							<input name="email_korespondensi" value="<?php echo $email_korespondensi;?>" type='text'/>
							<br><i>Jika lebih dari 1 email, pisahkan dengan tanda koma.</i></td></tr>
		  </tr>
		 <tr>
		<?php $email_optional = get_option('epeken_email_optional');?>
		 <td>Email Pembeli</td>
		<td><p><input type="checkbox" name="email_optional" <?php if($email_optional) echo "checked";?>>Email tidak wajib saat checkout</input>
		<br><i>Mengisi email optional (tidak wajib) pada saat checkout</i></br></p>
		</td>
		 </tr>
		</table>
		</td>
				  <script language="javascript">
					var enable_dimensi = document.getElementById('woocommerce_epeken_courier_perhitungan_dimensi');
					var enable_berat = document.getElementById('woocommerce_epeken_courier_volume_matrix');
					
					enable_dimensi.onclick=function(){
						if(enable_dimensi.checked && !enable_berat.checked) {
							enable_berat.checked = true;
						}
					}
					
					enable_berat.onclick=function() {
						if(!enable_berat.checked && enable_dimensi.checked) {
							enable_dimensi.checked = false;
						}
					}
				  </script>
                <?php return ob_get_clean();
        }

 	public function generate_mode_kode_pembayaran_html() {
                ob_start();
                ?>
                <tr valign="top">
                        <th scope="row" class="titledesc"><label for="woocommerce_wc_shipping_tikijne_mode_kode_pembayaran"></label>Mode Kode Pembayaran</th>
                        <td>
                                <?php $mode_kode_pembayaran = get_option('epeken_mode_kode_pembayaran');
                                        if (empty($mode_kode_pembayaran)){
                                                update_option('epeken_mode_kode_pembayaran','+');$mode_kode_pembayaran = '+';
                                        }
                                ?>

                                <input type="radio" name="mode_kode_pembayaran" id="mode_kode_pembayaran" value="+" <?php if(($mode_kode_pembayaran) === '+') echo 'checked';?>>Menambah Ongkos Kirim</input><br><br>
                                <input type="radio" name="mode_kode_pembayaran" id="mode_kode_pembayaran" value="-" <?php if(($mode_kode_pembayaran) === '-') echo 'checked';?>> Mengurangi Ongkos Kirim </input>
                        </td>
                </tr>
                <?php
                return ob_get_clean();
        }

		public function generate_kombinasikan_free_shipping_html() {
			ob_start();
			$freeship_n_city_for_free_shipping = get_option('epeken_freeship_n_city_for_free_shipping');
			$freeship_n_province_for_free_shipping = get_option('epeken_freeship_n_province_for_free_shipping');		
			?>
			<tr valign="top">
                        <th scope="row" class="titledesc"><label for="woocommerce_wc_shipping_tikijne_kombinasikan_free_shipping"></label>Kombinasikan Parameter Free Shipping</th>
                        <td>
				<p>
				<?php $checked = ""; if ($freeship_n_city_for_free_shipping === "on") {$checked = "checked"; } ?> <input type="checkbox" name="freeship_n_city_for_free_shipping" <?php echo $checked; ?> > Kombinasikan Nominal Minimum Belanjaan dan Kota Tujuan Pengiriman Untuk Free Shipping
				</p>
				<p style="margin-top:20px;">
                                   <?php $checked = ""; if ($freeship_n_province_for_free_shipping === "on") {$checked = "checked"; } ?> <input type="checkbox" name="freeship_n_province_for_free_shipping" <?php echo $checked; ?> > Kombinasikan Nominal Minimum Belanjaan dan Provinsi Tujuan Pengiriman Untuk Free Shipping 
                                </p>
			</td>
			</tr>	
			<?php
			return ob_get_clean();	
		}

	   public function generate_enable_cod_html() {
		   ob_start();
		   $enable_cod = sanitize_text_field(get_option("epeken_enable_cod"));
		   $cod_label = sanitize_text_field(get_option("epeken_cod_label"));
		   $cod_payment = sanitize_text_field(get_option("epeken_cod_payment"));
		   if(empty($cod_label))
			   $cod_label = "Cash On Delivery (COD)";
		   ?>
			<tr valign = "top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_epeken_enable_cod">Cash On Delivery</label>
			</th>
			<td>
				<input type="checkbox" <?php if($enable_cod === 'on') {echo "checked";}?> name = "woocommerce_epeken_enable_cod"
				id = "woocommerce_epeken_enable_cod" /> Enable COD
				<p valign="middle">COD Label: <input type="text" name="woocommerce_epeken_cod_label" style="width: 250px; height: 25px"
				id="woocommerce_epeken_cod_label" value="<?php echo $cod_label; ?>"></input></p>
				<p valign="middle" <?php if($enable_cod === 'on'){echo "style='display: block;'";}else{echo "style='display: none;'";}?>><input type="checkbox" 
			      	  <?php if($cod_payment === 'on'){echo "checked";}?> name="woocommerce_epeken_cod_payment" id="woocommerce_epeken_cod_payment"/>
				Gunakan metode bayar COD jika pembeli memilih metode pengiriman COD (<em>Recommended</em>).</p>
				<p valign="middle">Batasi COD untuk Kota berikut ini:</p>
				<p valign="middle">
				  <select multiple="multiple" class="multiselect chosen_select ajax_chosen_select_city" name="woocommerce_wc_shipping_tikijne_city_cod[]" id="woocommerce_wc_shipping_tikijne_city_cod" style="width: 450px;" data-placeholder="Pilih Kota&hellip;">
                                	<?php
				 	$this -> print_cities_for_select('epeken_cities_cod');
					?>
				  </select>
				</p>
			<td>
			</tr>
		   <?php
		   return ob_get_clean();
	   }

	   public function generate_enable_cod_kurir_html() {
   	      	ob_start();
	      	$enable_cod_kurir = sanitize_text_field(get_option('epeken_enable_cod_kurir'));
 		?>
		<tr valign="top">
		<th scope="row" class='titledesc'>
			<label for"woocommerce_epeken_enable_cod_kurir">Courier Cash On Delivery</label>
		</th>
		<td>
			<input type="checkbox" <?php if($enable_cod_kurir === 'on'){echo "checked";}?> name="woocommerce_epeken_enable_cod_kurir"
			id = "woocommerce_epeken_enable_cod_kurir" /> Enable Courier COD
			<p valign="middle">Jika COD Kurir aktif, maka jika pembeli (Buyer) memilih pembayaran COD saat checkout, akan dikenakan biaya tambahan sebagai tarif COD dengan Kurir. </p>
			<p valign="middle">Tarif COD = <input style="width: 100px;" 
	   		value="<?php echo get_option('epeken_cod_kurir_perc');?>"
			type="number" name="woocommerce_epeken_cod_kurir_perc" /> % x (total harga barang + shipping)</p>
		</td>
		</tr>
		<?php
		return ob_get_clean();
	   }

	public function generate_subsidi_ongkir_dengan_kupon_html() {
		ob_start();
		$a = get_option('epeken_kode_kupon_subsidi_ongkir');
		$b = get_option('epeken_nominal_subsidi_ongkir_dengan_kupon');
		?>
		<tr>
			<th scope="row" class="titledesc">
				<label>Subsidi Ongkir Dengan Kupon</label>
			</th>
		<td>
			<table>
				<tr>
				<td>Masukkan Kode Kupon</td>
				<td><input type="text" name="kode_kupon_subsidi_ongkir" <?php if(!empty($a)){echo 'value="'.$a.'"';}?> /></td>
				</tr>
				<tr>
				<td>
				Nominal Subsidi Ongkir (Rupiah)
				</td><td><input type="number" name="nominal_subsidi_ongkir_dengan_kupon" <?php if(!empty($b)){echo 'value="'.$b.'"';}?>/></td>
				</tr>
				<tr><td colspan=2><em>Buatlah kupon di WooCommerce, lalu jika kode kupon itu Anda masukkan di setting subsidi ongkir ini, maka jika customer mengaplikasikan kupon tersebut, maka ongkirnya akan didiskon sebanyak nominal subsidi ongkir</em></td</tr>
			</table>
		</td>
		</tr>
		<?php
		return ob_get_clean();
	}
       public function generate_province_for_free_shipping_html() {
                ob_start();
                ?>
                        <tr valign="top">
                        <th scope="row" class="titledesc">
                                <label for="woocommerce_wc_shipping_tikijne_province_for_free_shipping">Pilihan Provinsi Free Ongkir</label>
                        </th>
                        <td>  <table><tr><td>
                                <fieldset>
                                <legend class="screen-reader-text"><span>Pilihan Provinsi</span></legend>
                                <select multiple="multiple" class="multiselect chosen_select ajax_chosen_select_city" name="woocommerce_wc_shipping_tikijne_province_for_free_shipping[]" id="woocommerce_wc_shipping_tikijne_province_for_free_shipping" style="width: 450px;" data-placeholder="Pilih Provinsi&hellip; Kosongkan jika tak ingin diset">
                                <?php
                                 $json_all_prv = epeken_get_all_provinces();
									 $provinces = json_decode($json_all_prv, true);
									 $provinces = $provinces["provinces"];
									if(!empty($provinces)){
										 foreach($provinces as $province) {
										 $selected = '';
										 $existing_config = get_option('epeken_province_for_free_shipping');
										 if (!empty($existing_config)) {
										       for($x=0;$x<sizeof($existing_config);$x++){
												if($province === $existing_config[$x]){
														$selected = 'selected';
														break;
												}
										       }
										 }
					?>
									<option value="<?php echo $province;?>" <?php echo $selected; ?>><?php echo $province;?></option>
					<?php
										}
									}
					?>
						</select>
						</fieldset>
						</td></tr>
						<tr><td>
								<?php $checked = 'checked'; $existing_config = get_option('epeken_is_provinsi_free'); ?>
								<input type="radio" name="epeken_is_provinsi_free" value="these_are_free" <?php if($existing_config === 'these_are_free'){echo $checked;}?>>Gratiskan Ongkos Kirim untuk pilihan provinsi tersebut
								</input><br><br>
								 <input type="radio" name="epeken_is_provinsi_free" value="others_are_free" <?php if($existing_config === 'others_are_free'){echo $checked;}?>>Ongkos Kirim selain pilihan provinsi tersebut gratis. Hanya pilihan provinsi tersebut bayar Ongkos Kirim.
								</input>
						</td></tr>
					 </table>
					</td>
					</tr>
                <?php
                 return ob_get_clean();
        }
	public function validate_province_for_free_shipping_field($key) {
                $value = $_POST['woocommerce_wc_shipping_tikijne_province_for_free_shipping'];
                return $value;
        }
	public function generate_checkbox_ongkir_vendor_html() {
		ob_start();
		if(epeken_is_multi_inventory())
		{
		?>
		  <tr>
			<td colspan=2 style='background-color: #ffffff; padding: 5px;'>
				<strong><h2>Toko online menerapkan multi inventory</h2></strong>
				<p>Mohon melakukan setting pilihan kota asal di tiap-tiap inventory/cabang/gudang pada setting inventory.</p>
			</td>
		  </tr>
		<?php
		  return ob_get_clean();
		}
		$is_multi_vendor = epeken_is_multi_vendor_mode(true); //checksetting = true
		if(!$is_multi_vendor)
                  return ob_get_clean();
		
		$ongkir_per_vendor = get_option('epeken_ongkir_per_vendor');
		$checked = '';

		if($ongkir_per_vendor === 'on') {
			$checked = 'checked';
		}else if($ongkir_per_vendor === false) {
			$checked = 'checked'; update_option('epeken_ongkir_per_vendor', 'on');
		}

		?>
		<tr>
			<td colspan=2 style="background-color: #ffffff">
			<table><tr>
			<td style="padding: 5px;">
			<strong><h2>Perhatian: Toko Online Anda menerapkan konsep multi vendor</h2></strong>
			<p><input type="checkbox" id='epeken_ongkir_per_vendor' name='epeken_ongkir_per_vendor' <?php if($checked=='checked'){echo $checked;} ?>> Terapkan Ongkir Per Vendor <strong>Enabled</strong></p><p>
			<em>By Default, untuk konsep marketplace, saat checkout, customer akan dikenakan biaya ongkir untuk setiap vendor yang berada pada keranjang belanjaannya (Split Ongkir Per Vendor).  Namun, jika dikehendaki hanya 1 kali kena ongkir untuk semua barang multi vendor dalam cart, silakan uncheck checkbox di atas.</em></p>
			</td></tr>
			</table>
			</td>
		</tr>	
 		<?php
		return ob_get_clean();
	}
	public function generate_flat_tarif_html() {
			ob_start();
			$epeken_nama_tarif_flat = get_option('epeken_nama_tarif_flat');
			$epeken_nominal_tarif_flat = get_option('epeken_nominal_tarif_flat');
			
			?>
			<tr>
			<th scope="row" class="titledesc">Tarif Ongkos Kirim Flat</th>
			<td>
				<table>
					<tr><td>Nama Tarif Flat</td>
					<td><input placeholder="Tarif Flat" type='text' 
					name='epeken_nama_tarif_flat'
					value='<?php echo $epeken_nama_tarif_flat; ?>'>
					</td></tr>
					<tr><td>Nominal Tarif Flat</td>
					<td><input placeholder="0" type='number' 
					name='epeken_nominal_tarif_flat'
					value='<?php echo $epeken_nominal_tarif_flat; ?>'>
					</td></tr>
					<tr>
				        <td>Kota/Kabupaten Tujuan</td>
					<td>
					<select name="epeken_kota_tarif_flat[]" style='width: 100%;' multiple="multiple" 
					   class="multiselect chosen_select ajax_chosen_select_city">
					<?php 
					   $this -> print_cities_for_select('epeken_kota_tarif_flat');
                        		?>
					</select>	
					</td>
					</tr>
					<tr><td colspan=2><em>Isikan Nama dan Nominal tarif ongkir flat, 
						jika ingin menggunakan tarif ongkir flat dari fitur plugin Epeken All Kurir.
					<br>Kosongkan jika tidak ingin menerapkan tarif ongkir flat.</em></td></tr>
				</table>
			</td>
			</tr>
			<?php
			return ob_get_clean();
	}
	public function generate_form_biaya_tambahan_html() {
                ob_start();
                ?>
                <tr>
                  <th scope="row" class="titledesc">Biaya Tambahan</th>
                  <td>
                        <table>
                                <tr>
                                        <td>
                                        Nama Biaya Tambahan
                                        </td>
                                        <td>
                                        <?php $epeken_biaya_tambahan_name = get_option('epeken_biaya_tambahan_name'); ?>
                                        <input type='text' name='epeken_biaya_tambahan_name' value='<?php echo $epeken_biaya_tambahan_name; ?>'/>
                                        </td>
                                <tr>
                                <tr>
                                        <td>
                                        Nominal Biaya Tambahan
                                        </td>
                                        <td>
                                        <?php $epeken_biaya_tambahan_amount = get_option('epeken_biaya_tambahan_amount'); ?>
                                        <input type='text' name='epeken_biaya_tambahan_amount' value='<?php echo $epeken_biaya_tambahan_amount; ?>'/>
                                        </td>
                                <tr>
				 <tr>
                                        <td>
                                        Perhitungan
                                        </td>
                                        <td>
                                        <?php $epeken_perhitungan_biaya_tambahan=get_option('epeken_perhitungan_biaya_tambahan');?>
                                        <select name="epeken_perhitungan_biaya_tambahan">
                                        <option value="percent" <?php if($epeken_perhitungan_biaya_tambahan === 'percent'){echo "selected";}?>>Percentage(%)</option>
                                        <option value="nominal" <?php if($epeken_perhitungan_biaya_tambahan === 'nominal'){echo "selected";}?>>Nominal Addition</option>
                                        </select>
                                        </td>
                                <tr>
                        </table>
                  </td>
                </tr>
                <?php
                return ob_get_clean();
        }

	public function generate_data_server_html() {
		ob_start();
		$server = sanitize_text_field(urldecode(get_option('epeken_data_server')));
		$idselected = ''; $sgselected = '';
		if($server === 'http://103.252.101.131')
			$idselected = 'selected';
		else if(empty($server) || $server === 'http://174.138.21.166')
			$sgselected = 'selected';
		?>
		<tr>
			<th scope="row" class="titledesc"><?php echo __('Data server')?></th>
		<td>
		  <select name="data_server" id="data_server">
		   <option value="<?php echo urlencode('http://'); ?>103.252.101.131" <?php echo $idselected; ?>>
			Server Indonesia (IDX)
		   </option>
		   <option value="<?php echo urlencode('http://'); ?>174.138.21.166" <?php echo $sgselected; ?>>
                        Server Singapore (Digital Ocean)
                   </option>
	   	  </select>
		</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	public function generate_data_asal_kota_html() {
		
		if (epeken_is_multi_vendor_mode())
			return;
		
		ob_start();
		$kode_kota_asal = get_option('epeken_data_asal_kota');
		?>
		<tr>
		<th scope="row" class="titledesc">Data Kota Asal (Default)</th>
		<td>
		   <select name="data_asal_kota" id="data_asal_kota">
			<?php 
				if(empty($this -> valid_origins)){
					$origins = epeken_get_valid_origin($license);
					$origins = json_decode($origins,true);
					$origins = $origins["validorigin"];	
					$this -> valid_origins = $origins;
				}
				$origins = $this -> valid_origins;
			if(!empty($origins)) {
			 foreach($origins as $element ) {
				echo "<option value='".$element["origin_code"]."'"; if($kode_kota_asal === $element['origin_code']){echo " selected";} echo ">".$element["kota_kabupaten"]."</option>";
			 } 
			}

			if (empty($origins)) {
				$string = file_get_contents(EPEKEN_KOTA_KAB);
                 	 	$json = json_decode($string,true);
                 	 	$array_kota = $json['listkotakabupaten'];
				?><option value=0>None</option><?php
				$idx = 1;
				if(!empty($array_kota)) {
                	 	 foreach($array_kota as $element){
					?><option value=<?php echo $idx; if($kode_kota_asal == $idx){echo " selected";}?>><?php echo $element["kotakab"]?></option>
					<?php
					$idx++;
	                 	 }
				}
			}
			?>
		   </select> 
		<script type='text/javascript'>
                                jQuery(document).ready(function($){
                                        $('#data_asal_kota').select2();
                                });
                </script>
		<?php 
		if(empty($kode_kota_asal)){
		   ?>
		  <p style="color: red">Kota Asal Wajib Dipilih</p>
		  <?php
		}
		?>
                </td>
                </tr>
		<?php
		return ob_get_clean();
	}

	public function generate_packing_kayu_settings_html() {
                ob_start(); 
                ?>   
                 <tr> 
                        <th scope="row" class="titledesc">Packing Kayu Settings (Khusus JNE)</th>
                        <td> 
                        <div style="position: relative; float: left; margin-top: 00px;">      
                                <?php 
                                        $epeken_packing_kayu_enabled = get_option('epeken_packing_kayu_enabled'); 
                                        $epeken_pengali_packing_kayu = get_option('epeken_pengali_packing_kayu');
                                        $epeken_pc_packing_kayu = get_option('epeken_pc_packing_kayu');
                                        $tmptxt = "";
                                        if ($epeken_packing_kayu_enabled === "yes") {$tmptxt = "checked";};
                                ?>   
                                <input type="checkbox" name="woocommerce_epeken_packing_kayu_enabled" id="woocommerce_epeken_packing_kayu_enabled" <?php echo $tmptxt; ?>> Enable/Disable Packing Kayu<br>
                                Rumus Perhitungan Packing Kayu : <input type="text" name="woocommerce_epeken_pengali_packing_kayu" id="woocommerce_epeken_pengali_packing_kayu" value="<?php echo $epeken_pengali_packing_kayu;?>"> kali dari berat paket keseluruhan.<br>
                                Product Category Wajib dengan Packing Kayu (Pisahkan dengan tanda koma, jika lebih dari satu) : <input type="text" name="woocommerce_epeken_pc_packing_kayu" id="woocommerce_epeken_pc_packing_kayu" value = "<?php echo $epeken_pc_packing_kayu; ?>">
                        </div>
                        </td>
                </tr>
                 <?php
                return ob_get_clean();
        }    

	public function generate_free_shipping_product_category_html() {
		ob_start();
		 ?>
	       <tr>
                <th scope="row" class="titledesc">Free Shipping Based on Product Category</th>
		<td>
		 <div style="position: relative; float: left;width: 40%; padding: 5px;">
		  Masukkan Produk Category gratis ongkir, pisahkan dengan tanda koma jika lebih dari satu:<br>
		  <input type="text" name="woocommerce_epeken_free_pc" id="woocommerce_epeken_free_pc" style="width: 150px;" value="<?php echo get_option('epeken_free_pc','') ?>">
		 </div>
		  <div style="position: relative; float: left;width: 40%; padding: 5px;">
		  Jumlah(Quantity) minimal dari item produk category gratis ongkir:<br>
		  <input type="number" min="1" style="width: 60px;" name="woocommerce_epeken_free_pc_q" id="woocommerce_epeken_free_pc_q" value="<?php echo get_option('epeken_free_pc_q','1') ?>">
          </div>
		</td>
		</tr>
		 <?php
		return ob_get_clean();
	}

	public function generate_panel_enable_kurir_html() {
		global $woocommerce;
		ob_start();
		 ?>
		<tr>
		<th scope="row" class="titledesc">Pilihan Layanan Kurir</th>	
		<td style="height: 100px;">
			<?php 
			$en_jne = sanitize_text_field(get_option('epeken_enabled_jne'));
			$en_tiki = sanitize_text_field(get_option('epeken_enabled_tiki')); 
			$en_rpx_sdp = sanitize_text_field(get_option('epeken_enabled_rpx_sdp'));
			$en_rpx_mdp = sanitize_text_field(get_option('epeken_enabled_rpx_mdp'));
			$en_rpx_ndp = sanitize_text_field(get_option('epeken_enabled_rpx_ndp'));
			$en_rpx_rgp = sanitize_text_field(get_option('epeken_enabled_rpx_rgp'));
			$en_rpx_insurance = sanitize_text_field(get_option('epeken_enabled_rpx_insurance'));
			$en_esl = sanitize_text_field(get_option('epeken_enabled_esl')); 
			$en_jne_reg = sanitize_text_field(get_option('epeken_enabled_jne_reg')); 
			$en_jne_oke = sanitize_text_field(get_option('epeken_enabled_jne_oke')); 
			$en_jne_yes = sanitize_text_field(get_option('epeken_enabled_jne_yes')); 
			$en_tiki_hds = sanitize_text_field(get_option('epeken_enabled_tiki_hds')); 
			$en_tiki_ons = sanitize_text_field(get_option('epeken_enabled_tiki_ons')); 
			$en_tiki_reg = sanitize_text_field(get_option('epeken_enabled_tiki_reg')); 
			$en_tiki_eco = sanitize_text_field(get_option('epeken_enabled_tiki_eco')); 
			$en_wahana = sanitize_text_field(get_option('epeken_enabled_wahana')); 
			$en_jetez = sanitize_text_field(get_option('epeken_enabled_jetez'));
			$en_sicepat_reg = sanitize_text_field(get_option('epeken_enabled_sicepat_reg'));
			$en_sicepat_best = sanitize_text_field(get_option('epeken_enabled_sicepat_best')); 
			$en_sicepat_siunt = sanitize_text_field(get_option('epeken_enabled_sicepat_siunt'));
			$en_sicepat_gokil = sanitize_text_field(get_option('epeken_enabled_sicepat_gokil'));
			$en_sicepat_sds = sanitize_text_field(get_option('epeken_enabled_sicepat_sds'));
			$en_enabled_custom =sanitize_text_field(get_option('epeken_enabled_custom_tarif')); 
			$en_enabled_jne_trucking = sanitize_text_field(get_option('epeken_enabled_jne_trucking_tarif')); 
			$en_enabled_dakota = sanitize_text_field(get_option('epeken_enabled_dakota_tarif'));
			$en_pos_reg =  sanitize_text_field(get_option('epeken_enabled_pos_reguler'));
			$en_pos_sd = sanitize_text_field(get_option('epeken_enabled_pos_sameday'));
			$en_pos_nd = sanitize_text_field(get_option('epeken_enabled_pos_nextday'));
			$en_pos_bi = sanitize_text_field(get_option('epeken_enabled_pos_biasa')); 
			$en_pos_kk = sanitize_text_field(get_option('epeken_enabled_pos_kilat_khusus')); 
			$en_pos_end = sanitize_text_field(get_option('epeken_enabled_pos_express_nextday')); 
			$en_pos_vg = sanitize_text_field(get_option('epeken_enabled_pos_val_good')); 
			$en_pos_kprt = sanitize_text_field(get_option('epeken_enabled_pos_kprt'));
			$en_pos_kpru = sanitize_text_field(get_option('epeken_enabled_pos_kpru'));
			
			$en_sap_sds = sanitize_text_field(get_option('epeken_enabled_sap_sds'));
			$en_sap_ods = sanitize_text_field(get_option('epeken_enabled_sap_ods'));
			$en_sap_reg = sanitize_text_field(get_option('epeken_enabled_sap_reg'));
			$en_nss_sds = sanitize_text_field(get_option('epeken_enabled_nss_sds'));
			$en_nss_ods = sanitize_text_field(get_option('epeken_enabled_nss_ods'));
			$en_nss_reg = sanitize_text_field(get_option('epeken_enabled_nss_reg'));
			
			$enabled_pos_ems_priority_doc= sanitize_text_field(get_option('epeken_enabled_pos_ems_priority_doc')); 
			$enabled_pos_ems_priority_mar = sanitize_text_field(get_option('epeken_enabled_pos_ems_priority_mar')); 
			$enabled_pos_ems_doc = sanitize_text_field(get_option('epeken_enabled_pos_ems_doc')); 
			$enabled_pos_ems_mar = sanitize_text_field(get_option('epeken_enabled_pos_ems_mar')); 
			$enabled_pos_ems_epacket_lx = sanitize_text_field(get_option('epeken_enabled_pos_ems_epacket_lx'));
			$enabled_pos_rln = sanitize_text_field(get_option('epeken_enabled_pos_rln')); 
			$enabled_pos_ekspor = sanitize_text_field(get_option('epeken_enabled_pos_ekspor')); 
			$enabled_jne_international = sanitize_text_field(get_option('epeken_enabled_jne_international'));
			
			$en_jmx_cos = sanitize_text_field(get_option('epeken_enabled_jmx_cos'));
			$en_jmx_lts = sanitize_text_field(get_option('epeken_enabled_jmx_lts'));
			$en_jmx_sms = sanitize_text_field(get_option('epeken_enabled_jmx_sms'));
			$en_jmx_sos = sanitize_text_field(get_option('epeken_enabled_jmx_sos'));
			
			$en_lion_onepack = sanitize_text_field(get_option('epeken_enabled_lion_onepack'));
			$en_lion_regpack = sanitize_text_field(get_option('epeken_enabled_lion_regpack'));

			$en_ninja_next_day = sanitize_text_field(get_option('epeken_enabled_ninja_next_day'));
			$en_ninja_standard = sanitize_text_field(get_option('epeken_enabled_ninja_standard'));

			$en_flat = sanitize_text_field(get_option('epeken_enabled_flat'));
  			$en_enabled_atlas = sanitize_text_field(get_option('epeken_enabled_atlas_express'));
?>
			<div style="clear: left;">
			<p><div class="pilihan_kurir_div"><input name="enabled_jne" id = "enabled_jne" type="checkbox" <?php if ($en_jne === "on"){echo "checked";} ?>><strong>JNE</strong></input></div></p>
			<p><div class="pilihan_kurir_div"><input name="enabled_jne_reg" id = "enabled_jne_reg" type="checkbox" <?php if ($en_jne_reg === "on"){echo "checked";} ?> onclick='f01()'>JNE REGULAR</input></div></p>
			<p><div class="pilihan_kurir_div"><input name="enabled_jne_oke" id = "enabled_jne_oke" type="checkbox" <?php if ($en_jne_oke === "on"){echo "checked";} ?> onclick='f01()'>JNE OKE</input></div></p>
			<p><div class="pilihan_kurir_div"><input name="enabled_jne_yes" id = "enabled_jne_yes" type="checkbox" <?php if ($en_jne_yes === "on"){echo "checked";} ?> onclick='f01()'>JNE YES</input></div>
			<?php $epeken_markup_tarif_jne = get_option('epeken_markup_tarif_jne'); ?>
            <div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif JNE : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_jne"  value="<?php echo $epeken_markup_tarif_jne; ?>"/> /kg</div>
			<?php $epeken_diskon_tarif_jne = get_option('epeken_diskon_tarif_jne'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Diskon Tarif JNE : <input type="number" placeholder="0" style="width: 80px;" name="epeken_diskon_tarif_jne"  value="<?php echo $epeken_diskon_tarif_jne; ?>"/>%</div> 
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			</p>
			</div>
			<div style="clear: left;">
			<p><div class="pilihan_kurir_div"><input name="enabled_tiki" id="enabled_tiki" type="checkbox" <?php if ($en_tiki === "on"){echo "checked";} ?>><strong>TIKI</strong></input></div></p>
			<p><div class="pilihan_kurir_div"><input name="enabled_tiki_hds" id="enabled_tiki_hds" type="checkbox" <?php if ($en_tiki_hds === "on"){echo "checked";} ?> onclick='f02()'>TIKI HDS</input></div></p>
			<p><div class="pilihan_kurir_div"><input name="enabled_tiki_ons" id="enabled_tiki_ons" type="checkbox" <?php if ($en_tiki_ons === "on"){echo "checked";} ?> onclick='f02()'>TIKI ONS</input></div></p>
			<p><div class="pilihan_kurir_div"><input name="enabled_tiki_reg" id="enabled_tiki_reg" type="checkbox" <?php if ($en_tiki_reg === "on"){echo "checked";} ?> onclick='f02()'>TIKI REG</input></div></p>
			<p><div class="pilihan_kurir_div"><input name="enabled_tiki_eco" id="enabled_tiki_eco" type="checkbox" <?php if ($en_tiki_eco === "on"){echo "checked";} ?> onclick='f02()'>TIKI ECO</input></div>
			<?php $epeken_markup_tarif_tiki = get_option('epeken_markup_tarif_tiki'); ?>
            <div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif TIKI : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_tiki" value="<?php echo $epeken_markup_tarif_tiki; ?>" /> /kg</div>
			<?php $epeken_diskon_tarif_tiki = get_option('epeken_diskon_tarif_tiki'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Diskon Tarif TIKI : <input type="number" placeholder="0" style="width: 80px;" name="epeken_diskon_tarif_tiki"  value="<?php echo $epeken_diskon_tarif_tiki; ?>"/>%</div> 	
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			</p>
			</div>
			
			<div style="clear: left;">
			<p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_pos_reguler" id="enabled_pos_reguler" type="checkbox" <?php if($en_pos_reg==="on"){echo "checked";} ?> >POS REGULER</input>
			   </div>	
			</p>
			<p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_pos_sameday" id="enabled_pos_sameday" type="checkbox" <?php if($en_pos_sd==="on"){echo "checked";} ?> >POS SAMEDAY</input>
			   </div>	
			</p>
			<p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_pos_nextday" id="enabled_pos_nextday" type="checkbox" <?php if($en_pos_nd==="on"){echo "checked";} ?> >POS NEXTDAY</input>
			   </div>	
			</p>
 			<!-- <p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_pos_biasa" id="enabled_pos_biasa" type="checkbox" <?php if($en_pos_bi==="on"){echo "checked";} ?> >POS Biasa</input>
			   </div>	
			</p>
			<p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_pos_kilat_khusus" id="enabled_pos_kilat_khusus" type="checkbox" <?php if($en_pos_kk==="on"){echo "checked";} ?> >POS Kilat Khusus</input>
			   </div>	
			</p>
			<p>  
                           <div class="pilihan_kurir_div">
                                <input name="enabled_pos_express_nextday" id="enabled_pos_express_nextday" type="checkbox" <?php if($en_pos_end==="on"){echo "checked";} ?> >POS Express Next Day</input>
                           </div>            
                        </p> 	
			<p>  
                           <div class="pilihan_kurir_div">
                                <input name="enabled_pos_val_good" id="enabled_pos_val_good" type="checkbox" <?php if($en_pos_vg==="on"){echo "checked";} ?> >POS Valuable Good</input>
                           </div>            
                        </p> 
			<p>
				<div class="pilihan_kurir_div">
                                <input name="enabled_pos_kprt" id="enabled_pos_kprt" type="checkbox" <?php if($en_pos_kprt==="on"){echo "checked";} ?> >Kargo Pos Retail Train</input>
                           </div>
			</p>
			<p>
                                <div class="pilihan_kurir_div">
                                <input name="enabled_pos_kpru" id="enabled_pos_kpru" type="checkbox" <?php if($en_pos_kpru==="on"){echo "checked";} ?> >Kargo Pos Retail Udara</input>
                           </div>
                        </p> -->
			</div>
			<?php $epeken_markup_tarif_pos = get_option('epeken_markup_tarif_pos'); ?>
            <div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif POS : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_pos" value="<?php echo $epeken_markup_tarif_pos; ?>"/> /kg</div>	
			<?php $epeken_diskon_tarif_pos = get_option('epeken_diskon_tarif_pos'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Diskon Tarif POS : <input type="number" placeholder="0" style="width: 80px;" name="epeken_diskon_tarif_pos"  value="<?php echo $epeken_diskon_tarif_pos; ?>"/>%</div>
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			<div style="clear: left;">
			<p>
			<div class="pilihan_kurir_div">
			<input name="enabled_sap_sds" id="enabled_sap_sds" type="checkbox" <?php if($en_sap_sds==="on"){echo "checked";} ?> >SAP SDS</input>
			</div>	
			</p>
			<p>
			<div class="pilihan_kurir_div">
			<input name="enabled_sap_ods" id="enabled_sap_ods" type="checkbox" <?php if($en_sap_ods==="on"){echo "checked";} ?> >SAP ODS</input>
			</div>	
			</p>
			<p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_sap_reg" id="enabled_sap_reg" type="checkbox" <?php if($en_sap_reg==="on"){echo "checked";} ?> >SAP REG</input>
			   </div>	
			</p>
			</div>
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			<div style="clear: left;">
			<p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_nss_sds" id="enabled_nss_sds" type="checkbox" <?php if($en_nss_sds==="on"){echo "checked";} ?> >NSS SDS</input>
			   </div>	
			</p>
			<p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_nss_ods" id="enabled_nss_ods" type="checkbox" <?php if($en_nss_ods==="on"){echo "checked";} ?> >NSS ODS</input>
			   </div>	
			</p>
			<p>
			   <div class="pilihan_kurir_div">
				<input name="enabled_nss_reg" id="enabled_nss_reg" type="checkbox" <?php if($en_nss_reg==="on"){echo "checked";} ?> >NSS REG</input>
			   </div>	
			</p>
			</div>
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			<div style="clear: left;">
			<p>
                           <div class="pilihan_kurir_div">
                                <input name="enabled_rpx_sdp" id="enabled_rpx_sdp" type="checkbox" <?php if($en_rpx_sdp==="on"){echo "checked";} ?> >RPX SDP (Sameday)</input>
                           </div>
                        </p>

			<p>
                           <div class="pilihan_kurir_div">
                                <input name="enabled_rpx_mdp" id="enabled_rpx_mdp" type="checkbox" <?php if($en_rpx_mdp==="on"){echo "checked";} ?> >RPX MDP (Midday)</input>
                           </div>
                        </p>
			<p>
                           <div class="pilihan_kurir_div">
                                <input name="enabled_rpx_ndp" id="enabled_rpx_ndp" type="checkbox" <?php if($en_rpx_ndp==="on"){echo "checked";} ?> >RPX NDP (Nextday)</input>
                           </div>
                        </p>
			<p>
                           <div class="pilihan_kurir_div">
                                <input name="enabled_rpx_rgp" id="enabled_rpx_rgp" type="checkbox" <?php if($en_rpx_rgp==="on"){echo "checked";} ?> >RPX RGP (Regular)</input>
                           </div>
                        </p>
			<p>
                           <div class="pilihan_kurir_div">
                                <input name="enabled_rpx_insurance" id="enabled_rpx_insurance" type="checkbox" <?php if($en_rpx_insurance==="on"){echo "checked";} ?> >Terapkan Asuransi Pengiriman RPX</input>
                           </div>
                        </p>
			<div style="clear: left;">
			<p>
			<em>Jika asuransi diterapkan, preminya sebesar ongkos kirim RPX.</em>
			</p>
			</div>
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			<p><div class="pilihan_kurir_div"><input name="enabled_esl" id="enabled_esl" type="checkbox" <?php if ($en_esl === "on"){echo "checked";} ?>>ESL</input></div></p>
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			<div style="clear: left;">
			<p><div class="pilihan_kurir_div"><input name="enabled_wahana" id = "enabled_wahana" type="checkbox" <?php if ($en_wahana === "on"){echo "checked";} ?>>WAHANA</input></div></p>
			<?php $epeken_markup_tarif_wahana = get_option ('epeken_markup_tarif_wahana');?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif WAHANA : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_wahana"  value="<?php echo $epeken_markup_tarif_wahana; ?>"/> /kg</div>
			<?php $epeken_diskon_tarif_wahana = get_option ('epeken_diskon_tarif_wahana');?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Diskon Tarif WAHANA : <input type="number" placeholder="0" style="width: 80px;" name="epeken_diskon_tarif_wahana"  value="<?php echo $epeken_diskon_tarif_wahana; ?>"/>%</div> 
			<?php $epeken_is_asuransi_wahana = get_option ('epeken_is_asuransi_wahana');?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Asuransi Wahana <input type="checkbox" placeholder="0" name="epeken_is_asuransi_wahana"  <?php if($epeken_is_asuransi_wahana === 'on') echo 'checked'; ?>/></div>
			</div>
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			<div style="clear: left;">
			
                        <p><div class="pilihan_kurir_div"><input name="enabled_jetez" id = "enabled_jetez" type="checkbox" <?php if ($en_jetez === "on"){echo "checked";} ?>>J&T EZ (<em>www.jet.co.id</em>)</input></div></p>
			<?php $epeken_markup_tarif_jnt = get_option('epeken_markup_tarif_jnt'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif J&T : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_jnt"  value="<?php echo $epeken_markup_tarif_jnt; ?>"/> /kg</div>
			<?php $epeken_diskon_tarif_jnt = get_option('epeken_diskon_tarif_jnt'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Diskon Tarif J&T : <input type="number" placeholder="0" style="width: 80px;" name="epeken_diskon_tarif_jnt"  value="<?php echo $epeken_diskon_tarif_jnt; ?>"/>%</div> 
                       </div>	
		        <div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			<div style="clear: left;"> 
			<p><div class="pilihan_kurir_div"><input name="enabled_sicepat_reg" id="enabled_sicepat_reg" type="checkbox" <?php if($en_sicepat_reg === "on"){echo "checked";}?>>SICEPAT REGULAR</input></div></p><p><div class="pilihan_kurir_div"><input name="enabled_sicepat_best" id="enabled_sicepat_best" type="checkbox" <?php if($en_sicepat_best === "on"){echo "checked";}  ?>>SICEPAT BEST</input></div></p><p><div class="pilihan_kurir_div"><input name="enabled_sicepat_siunt" id="enabled_sicepat_siunt" type="checkbox" <?php if($en_sicepat_siunt === "on"){echo "checked";}  ?>>SICEPAT SIUNT</input></div></p><p><div class="pilihan_kurir_div"><input name="enabled_sicepat_gokil" id="enabled_sicepat_gokil" type="checkbox" <?php if($en_sicepat_gokil === "on"){echo "checked";}  ?>>SICEPAT GOKIL</input></div></p><p><div class="pilihan_kurir_div"><input name="enabled_sicepat_sds" id="enabled_sicepat_sds" type="checkbox" <?php if($en_sicepat_sds === "on"){echo "checked";}  ?>>SICEPAT SDS</input></div></p>
			</div>
			<?php $epeken_markup_tarif_sicepat = get_option('epeken_markup_tarif_sicepat'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif Sicepat : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_sicepat"  value="<?php echo $epeken_markup_tarif_sicepat; ?>"/> /kg</div>
			<?php $epeken_diskon_tarif_sicepat = get_option('epeken_diskon_tarif_sicepat'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Diskon Tarif Sicepat : <input type="number" placeholder="0" style="width: 80px;" name="epeken_diskon_tarif_sicepat"  value="<?php echo $epeken_diskon_tarif_sicepat; ?>"/>%</div> 
                       </div>	
		        
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			
			<div style="clear: left;">
				<p>
				<div class="pilihan_kurir_div"><input name="enabled_jmx_cos" id="enabled_jmx_cos" type="checkbox" <?php if($en_jmx_cos === "on"){echo "checked";}?>>JMX COS</input></div>
				</p>
				<p>
				<div class="pilihan_kurir_div"><input name="enabled_jmx_sms" id="enabled_jmx_sms" type="checkbox" <?php if($en_jmx_sms === "on"){echo "checked";}?>>JMX SMS</input></div>
				</p>
				<p>
				<div class="pilihan_kurir_div"><input name="enabled_jmx_lts" id="enabled_jmx_lts" type="checkbox" <?php if($en_jmx_lts === "on"){echo "checked";}?>>JMX LTS</input></div>
				</p>
				<p>
				<div class="pilihan_kurir_div"><input name="enabled_jmx_sos" id="enabled_jmx_sos" type="checkbox" <?php if($en_jmx_sos === "on"){echo "checked";}?>>JMX SOS</input></div>
				</p>
			</div>
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			
			<div style="clear: left;">
				<p>
				<div class="pilihan_kurir_div"><input name="enabled_lion_regpack" id="enabled_lion_regpack" type="checkbox" <?php if($en_lion_regpack === "on"){echo "checked";}?>>Lion Parcel Regpack</input></div>
				</p>
				<p>
				<div class="pilihan_kurir_div"><input name="enabled_lion_onepack" id="enabled_lion_onepack" type="checkbox" <?php if($en_lion_onepack === "on"){echo "checked";}?>>Lion Parcel Onepack</input></div>
				</p>
			</div>
			<?php $epeken_markup_tarif_lion = get_option('epeken_markup_tarif_lion'); ?>
            <div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif Lion Parcel : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_lion"  value="<?php echo $epeken_markup_tarif_lion; ?>"/> /kg</div>
			<?php $epeken_diskon_tarif_lion = get_option('epeken_diskon_tarif_lion'); ?>
                        <div style="float: left;width: 100%; margin-bottom: 5px;">Diskon Tarif Lion Parcel : <input type="number" placeholder="0" style="width: 80px;" name="epeken_diskon_tarif_lion"  value="<?php echo $epeken_diskon_tarif_lion; ?>"/>%</div>
                        <div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>

			<?php if(epeken_is_multi_vendor_mode()) {
			?>
				<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
				<div style="clear: left;"><p> <div class="pilihan_kurir_div"> <input name="enabled_flat" id="enabled_flat" type="checkbox" 
				<?php if($en_flat === 'on') {echo "checked";}?>
				 >Enable Flat Tarif Per Vendor</input> </div> </p></div>
			<?php
				}			
			?>
			<div style="clear: left;">
			<p> 
                        <div class="pilihan_kurir_div"><input name="enabled_ninja_next_day" id = "enabled_ninja_next_day" type="checkbox" <?php if ($en_ninja_next_day === "on"){echo "checked";} ?>>Ninja Express Next Day</input></div>
			<div class="pilihan_kurir_div"><input name="enabled_ninja_standard" id = "enabled_ninja_standard" type="checkbox" <?php if ($en_ninja_standard === "on"){echo "checked";} ?>>Ninja Express Standard</input></div>
                        </p>   
                        </div> 
			<?php $epeken_markup_tarif_ninja = get_option('epeken_markup_tarif_ninja'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif Ninja : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_ninja"  value="<?php echo $epeken_markup_tarif_ninja; ?>"/> /kg</div>
			<?php $epeken_diskon_tarif_ninja = get_option('epeken_diskon_tarif_ninja'); ?>
			<div style="float: left;width: 100%; margin-bottom: 5px;">Diskon Tarif Ninja : <input type="number" placeholder="0" style="width: 80px;" name="epeken_diskon_tarif_ninja"  value="<?php echo $epeken_diskon_tarif_ninja; ?>"/>%</div> 
                       </div>	
		        
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
			<div style="clear: left;">
                        <p>  
                        <div class="pilihan_kurir_div"><input name="enabled_jne_trucking_tarif" id = "enabled_jne_trucking_tarif" type="checkbox" <?php if ($en_enabled_jne_trucking === "on"){echo "checked";} ?>>JNE TRUCKING</input></div>
			<?php $epeken_markup_tarif_jtr = get_option('epeken_markup_tarif_jtr'); ?>
                        <div style="float: left;width: 100%; margin-bottom: 5px;">Mark up tarif JTR : Rp. <input type="number" placeholder="0" style="width: 80px;" name="epeken_markup_tarif_jtr" value="<?php echo $epeken_markup_tarif_jtr; ?>"/> (flat)</div>	
	
                        </p>    
                        </div>	
			
			<div style="clear: left;">
                        <p>
                        <div class="pilihan_kurir_div"><input name="enabled_dakota_tarif" id = "enabled_dakota_tarif" type="checkbox" <?php if ($en_enabled_dakota === "on"){echo "checked";} ?>>DAKOTA CARGO</input></div>
                        </p>
                        </div>
			<div style="clear: left;">
			<p>
			<div class="pilihan_kurir_div"><input name="enabled_custom_tarif" id = "enabled_custom_tarif" type="checkbox" <?php if ($en_enabled_custom === "on"){echo "checked";} ?>>CUSTOM TARIFF (Hubungi Kami)</input></div>
			</p>	
			</div><div style="clear: left;">
			<p>
			<div class="pilihan_kurir_div">
			<input name="enabled_atlas" id="enabled_atlas" type="checkbox" <?php if($en_enabled_atlas === 'on') {echo "checked";} ?> ><a href="https://www.myatlasexpress.com/" target="_blank">ATLAS Express</a></input>
			</div>
			</p>
			</div>
 			<div style="clear: left;">
			<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div><div style="clear: left;"/>
				<p><strong>Kurir Internasional</strong><i> (Bisa dipakai jika license dilengkapi dengan opsi pengiriman internasional)</i></p>
                                <p><div class="pilihan_kurir_div"><input name="enabled_pos_ems_priority_doc" id = "enabled_pos_ems_priority_doc" type="checkbox" <?php if ($enabled_pos_ems_priority_doc === "on"){echo "checked";} ?>>POS PAKETPOS CEPAT LN</input></div></p>
                                </div>  
                                <div style="clear: left;">
                                <p><div class="pilihan_kurir_div"><input name="enabled_pos_ems_priority_mar" id = "enabled_pos_ems_priority_mar" type="checkbox" <?php if ($enabled_pos_ems_priority_mar === "on"){echo "checked";} ?>>POS PAKETPOS BIASA LN</input></div></p>
                                </div>
                                <div style="clear: left;">
                                <p><div class="pilihan_kurir_div"><input name="enabled_pos_ems_doc" id = "enabled_pos_ems_doc" type="checkbox" <?php if ($enabled_pos_ems_doc === "on"){echo "checked";} ?>>POS EMS DOKUMEN</input></div></p>
                                </div>
                                <div style="clear: left;">
                                <p><div class="pilihan_kurir_div"><input name="enabled_pos_ems_mar" id = "enabled_pos_ems_mar" type="checkbox" <?php if ($enabled_pos_ems_mar === "on"){echo "checked";} ?>>POS EMS BARANG</input></div></p>
                                </div>
                                <div style="clear: left;">
                                <p><div class="pilihan_kurir_div"><input name="enabled_pos_rln" id = "enabled_pos_rln" type="checkbox" <?php if ($enabled_pos_rln === "on"){echo "checked";} ?>>POS R LN</input></div></p>
                                </div>
								<div style="clear: left;">
                                <p><div class="pilihan_kurir_div"><input name="enabled_pos_ems_epacket_lx" id = "enabled_pos_ems_epacket_lx" type="checkbox" <?php if ($enabled_pos_ems_epacket_lx === "on"){echo "checked";} ?>>ePacket LX Prime</input></div></p>
                                </div>
								<div style="clear: left;">
                                <p><div class="pilihan_kurir_div"><input name="enabled_pos_ekspor" id = "enabled_pos_ekspor" type="checkbox" <?php if ($enabled_pos_ekspor === "on"){echo "checked";} ?>>POS EKSPOR</input></div></p>
                                </div>
								<div style="clear: left;">
                                <p><div class="pilihan_kurir_div"><input name="enabled_jne_international" id = "enabled_jne_international" type="checkbox" <?php if ($enabled_jne_international === "on"){echo "checked";} ?>>JNE International</input></div></p>
                                </div>
								<div style="clear: left;">
								<div style="margin-top: 10px;margin-bottom: 10px;">
								<strong>Pilihan Negara Tujuan</strong>
								<select multiple="multiple" class="multiselect chosen_select ajax_chosen_select_city" name="woocommerce_wc_shipping_tikijne_epeken_intl_countries[]"
								 id="woocommerce_wc_shipping_tikijne_epeken_intl_countries" 
								 style="width: 450px;" 
								 data-placeholder="Pilih Negara&hellip; Kosongkan jika tak ingin diset">
                                <?php
									$countries_obj = new WC_Countries();
									$countries = $countries_obj -> get_countries();
									$c_existing_config = get_option('epeken_valid_intl_shipping_countries');
									foreach($countries as $country) {
									  $cselected = '';
									  $country = sanitize_text_field($country);
									  if (!empty($c_existing_config) && in_array($country, $c_existing_config)) {		
										 $cselected = 'selected';
									  }
    							?>
										 <option value="<?php echo $country;?>" <?php echo $cselected; ?>><?php echo $country;?></option>
								<?php
									}
								?>
								</select>
								</div>
								</div>	
								<div style="clear: left;">
								<?php $epeken_country_filter = sanitize_text_field(get_option('epeken_country_filter')); ?>
								<p><input type="radio" name="woocommerce_wc_shipping_tikijne_country_filter" 
									<?php if(empty($epeken_country_filter) || $epeken_country_filter === 'include') echo 'checked'; ?> 
								value="include" /> Include Countries </p>
								<p><input type="radio" name="woocommerce_wc_shipping_tikijne_country_filter" 
									<?php if($epeken_country_filter === 'exclude') echo 'checked'; ?> 
								value="exclude" /> Exclude Countries </p>
								<div>
	    				<div style="float: left;width: 100%; margin-bottom: 5px;"><hr></div>
						<div style="clear: left;">
						<?php $epeken_subsidi_ongkir =  get_option('epeken_subsidi_ongkir'); $epeken_subsidi_min_purchase = get_option('epeken_subsidi_min_purchase'); ?> 
                        <div style="float: left; width: 200px;"><p>Subsidi Ongkos Kirim Rp.</p></div>
						<div style="float: left; width: 200px; padding: 2px;">
						<input type="number" name="txt_subsidi_ongkir" value="<?php echo $epeken_subsidi_ongkir; ?>" style="width: 100px;"/>
						</div> 
						<div style="clear: left;">
						<div style="float: left; width: 200px;"><p>Minimal pembelian Rp.</p></div>
						<div style="float: left; width: 200px; padding: 2px"><input type="number" name="txt_subsidi_min_purchase" value = "<?php echo $epeken_subsidi_min_purchase; ?>" style="width: 100px;"/>
						</div>
                        			<div style="float: left; width: 200px;"><p>Batasi subsidi hanya untuk kota-kota tertentu</p></div>
						<div style="float: left; width: 200px; padding: 2px">
  						<select multiple="multiple" class="multiselect chosen_select ajax_chosen_select_city" name="woocommerce_wc_shipping_tikijne_city_subsidi[]" id="woocommerce_wc_shipping_tikijne_city_subsidi" style="width: 450px;" data-placeholder="Pilih Kota&hellip;">	
                                		<?php
						  $this -> print_cities_for_select ('epeken_cities_subsidi');
						?>
				  		</select>
						</div>
						<div style="clear: left;">
						<div style="float: left; width: 100%;">
						<p><em>Isikan nilai subsidi ongkir yang ingin Anda berikan untuk pelanggan Anda. Jika Ongkir lebih dari nilai ini, maka Pelanggan akan mendapatkan potongan sejumlah yang Anda masukkan nilainya sebagai subsidi ongkir. Jika nilai ongkir kurang dari jumlah ini, maka ongkir akan gratis. Subsidi ongkir ini akan berlaku untuk semua kurir lokal dan tidak berlaku untuk kurir internasional</em></p>
						</div>
						</div>
						</div>
						</td>
						</tr>
	    <script language="javascript">
            var epjneelm = document.getElementById('enabled_jne');
	    var epjneregelm = document.getElementById('enabled_jne_reg');
	    var epjneokeelm = document.getElementById('enabled_jne_oke');
	    var epjneyeselm = document.getElementById('enabled_jne_yes');
	    var eptikielm = document.getElementById('enabled_tiki');
	    var eptikihdselm = document.getElementById('enabled_tiki_hds');
            var eptikionselm = document.getElementById('enabled_tiki_ons');
            var eptikiregelm = document.getElementById('enabled_tiki_reg');
	    var eptikiecoelm = document.getElementById('enabled_tiki_eco');
			
            epjneelm.onclick=function(){
				if(!epjneelm.checked)
				{
					epjneregelm.checked = false;
					epjneokeelm.checked = false;
					epjneyeselm.checked = false;
				}else{
					epjneregelm.checked = true;
                                        epjneokeelm.checked = true;
                                        epjneyeselm.checked = true;
				}
                        };

			eptikielm.onclick=function(){
				if(!eptikielm.checked)
				{
					eptikihdselm.checked = false;
					eptikionselm.checked = false;
					eptikiregelm.checked = false;
					eptikiecoelm.checked = false;
				}else{
					eptikihdselm.checked = true;
                                        eptikionselm.checked = true;
                                        eptikiregelm.checked = true;
                                        eptikiecoelm.checked = true;
				}
			};
			function f01() {
				epjneelm.checked = true;
				if(!epjneregelm.checked && !epjneokeelm.checked && !epjneyeselm.checked) {
					epjneelm.checked = false;	
				}
			}      
			function f02() {
                                eptikielm.checked = true;
                                if(!eptikihdselm.checked && !eptikionselm.checked && !eptikiregelm.checked && !eptikiecoelm.checked) {
                                        eptikielm.checked = false;       
                                }
                        }     			
            </script>
	 <?php
		return ob_get_clean();
	}

        public function get_jne_class_value(){
                $postdata = explode('&',$_POST['post_data']);
                $jneclasspost = '';
		if (!empty($postdata)) {
                 foreach ($postdata as $value) {
                        if (strpos($value,'order_comments') !== FALSE) {
                                $jneclasspost = $value; 
                                $jneclassar = explode('=',$jneclasspost);
                                $jneclasspost = $jneclassar[1]; 
                                break;
                        }
                 }
		}
       	         $this -> jneclass = $jneclasspost;
        }                       

	public function get_checkout_post_data($itemdata){
		$postdata = explode('&',$_POST['post_data']);
		$post_data_ret = '';
		if (!empty($postdata)) {
		foreach ($postdata as $value) {
                        if (strpos($value,$itemdata) !== FALSE) {
                                $post_data_ret = $value;
                                $ar = explode('=',$post_data_ret);
                                $post_data_ret = $ar[1];
                                break;
                        }
                 }
		}
		$post_data_ret = str_replace('+',' ',$post_data_ret);
		return $post_data_ret;
	}

	public function reset_shipping() {
		global $wpdb;
		$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_%"';
                $wpdb->query($sql);
	}

	public function print_cities_for_select($select_config_option) {
                $epeken_all_cities = epeken_get_list_of_kota_kabupaten();
		if(!empty($epeken_all_cities)){
			foreach($epeken_all_cities as $city) {
				$selected = '';
				$existing_config = get_option($select_config_option);
				if (!empty($existing_config)) {
				   for($x=0;$x<sizeof($existing_config);$x++){
					if($city === $existing_config[$x]){
						$selected = 'selected';
					   break;
					}
			 	   }
				}
		?>
							<option value="<?php echo $city;?>" <?php echo $selected; ?>><?php echo $city;?></option>
		<?php
			}
		}
	}
	
    	public function get_origin_kurir($vendor_id=null) {
		global $woocommerce;
		$city = '';
		$origin_code = '';
		$items = $woocommerce -> cart -> get_cart();
		if(sizeof($items) === 0)
			return $city;
		$first_item = reset($items);

		if(isset($vendor_id) && epeken_is_multi_vendor_mode()  && epeken_is_vendor_id($vendor_id)) {
		 $this -> vendor_id = $vendor_id;
		 if (epeken_is_wcpv_active()) {
		  $epeken_vendor_data = get_term_meta($vendor_id, 'epeken_vendor_data')[0];
	          $origin_code = $epeken_vendor_data['vendor_data_asal_kota'];
		 }else{
		  $origin_code = get_user_meta($vendor_id, 'vendor_data_kota_asal', true);
		 }
		}else{	
		 $origin_code = get_post_meta($first_item['product_id'],'product_origin',true);
		}
		 if(!empty($origin_code)) {
                  $city = epeken_code_to_city($origin_code);    
                 }else{
                  $city = epeken_code_to_city(get_option('epeken_data_asal_kota')); //$this -> settings['data_kota_asal']); 
                 }   	
		$city = apply_filters('epeken_get_origin_kurir', $city, $vendor_id); 
		return $city;
	}

	public function get_destination_city_and_kecamatan() {
			$wooversion = epeken_get_woo_version_number();
                        $wooversion = substr($wooversion, 0,3);
			$post_action = '';
                        $val_post_action = array();
                        if ($wooversion > 2.3) {
                          $post_action = isset($_GET['wc-ajax']) ? $_GET['wc-ajax'] : '';
                          $val_post_action = array('update_order_review','update_checkout');
                        } else {
                          $post_action = isset($_POST['action']) ? $_POST['action'] : '';
                          $val_post_action = array('woocommerce_update_order_review');
                        }    
                        if (in_array($post_action,$val_post_action))      { // woocommerce starting v.2.4 use this
                                   $isshippedifadr = $this -> get_checkout_post_data('ship_to_different_address');
                                   WC() -> session -> set('isshippedifadr', $isshippedifadr);
                                   if($isshippedifadr === '1'){ 
                                    $this -> shipping_kecamatan = $this -> get_checkout_post_data('shipping_address_2');
                                    $this -> shipping_city = $this -> get_checkout_post_data('shipping_city');
		 		    $this -> shipping_country = $this -> get_checkout_post_data('shipping_country');
			   	    $this -> shipping_address = $this -> get_checkout_post_data('shipping_address_1');
                                   }else{
                                    $this -> shipping_city = $this -> get_checkout_post_data('billing_city');
                                    $this -> shipping_kecamatan = $this -> get_checkout_post_data('billing_address_2');
			  	    $this -> shipping_country = $this -> get_checkout_post_data('billing_country');
			 	    $this -> shipping_address = $this -> get_checkout_post_data('billing_address_1');
                                   }    
                        }else{
				   $isshippedifadr = WC() -> session -> get('isshippedifadr');
                                   if(isset($isshippedifadr) && $isshippedifadr === '1' ) {
                                     $this -> shipping_city = sanitize_text_field($_POST['shipping_city']);
                                   } else {
                                     $this -> shipping_city = sanitize_text_field(isset($_POST['billing_city']) ? $_POST['billing_city'] : ''); 
                                   }    
                                   if(isset($isshippedifadr) && $isshippedifadr === '1' ) {
                                     $this -> shipping_kecamatan = sanitize_text_field($_POST['shipping_address_2']);
                                   } else {
                                     $this -> shipping_kecamatan = sanitize_text_field(isset($_POST['billing_address_2']) ? $_POST['billing_address_2'] : ''); 
                                   }    
				   if (isset($isshippedifadr) && $isshippedifadr === '1') {
			 	     $sc = isset($_POST['shipping_country']) ? $_POST['shipping_country'] : '';
                                     $this -> shipping_country = sanitize_text_field($sc);
                                   } else {
				     $bc = isset($_POST['billing_country']) ? $_POST['billing_country'] : ''; 
                                     $this -> shipping_country = sanitize_text_field($bc);
                                   }
				   if (isset($isshippedifadr) && $isshippedifadr === '1') {
				     $sa = isset($_POST['shipping_address_1']) ? $_POST['shipping_address_1'] : '';
				     $this -> shipping_address = sanitize_text_field($sa);
				   } else {
				     $ba = isset($_POST['billing_address_1']) ? $_POST['billing_address_1'] : '';
				     $this -> shipping_address = sanitize_text_field($ba);
				   }
                        }    	
	}

	public function set_shipping_cost_intl($package = array()) {
			global $woocommerce;
			$this -> get_destination_city_and_kecamatan();
			$epeken_country_filter = sanitize_text_field(get_option('epeken_country_filter'));
		 	$epeken_valid_countries = get_option('epeken_valid_intl_shipping_countries');
			$chosen_country_name = WC()->countries->countries[$this -> shipping_country];
			
			if(empty($this->shipping_country) || $this -> shipping_country === 'ID') {
				return; //do nothing
			}

			if(!empty($epeken_valid_countries) && 
				$epeken_country_filter === 'include' && 
					!in_array($chosen_country_name, $epeken_valid_countries)) {
				return;//do nothing
			}
			if(!empty($epeken_valid_countries) && 
				$epeken_country_filter === 'exclude' && 
					in_array($chosen_country_name, $epeken_valid_countries)) {
				return;//do nothing
			}

			$this -> array_of_tarif = array();
			$this -> count_cart_weight_and_dimension($package);
			$this -> additionalLabel = "<span style='font-weight: normal;'>".$this->shipping_total_weight." kg</span>";
                        $cart_weight = $this -> shipping_total_weight * 1000; //convert to grams
			
                        if(isset($package) && array_key_exists('vendor_id',$package)) {
                                $this -> vendor_id = $package['vendor_id'];
                        }    
			if(isset($package) && epeken_is_dokan_pro_active()){
				$package['seller_id'] = $package['vendor_id'];;
			}
                        $this -> origin_city = $this -> get_origin_kurir($this -> vendor_id); //city_name
                        $content_tarif = '';
                              $cache_input_key = $this->shipping_country.'-'.$cart_weight.'-'.$this->shipping_total_length.'-'.
                              $this -> shipping_total_width.'-'.$this -> shipping_total_height. '-'. 
                              ceil($woocommerce->cart->subtotal).'_ro';
                              $cache_input_key = preg_replace( '/[^\da-z]/i', '_', $cache_input_key );
				 if (!empty(WC()-> session -> get($cache_input_key))) {
				   $content_tarif = WC() -> session -> get($cache_input_key);
                                 } else {
                                   $content_tarif = 
                                    epeken_get_tarif_intl($this -> shipping_country, $cart_weight, 
                                    $this -> shipping_total_length, $this -> shipping_total_width, 
                                    $this -> shipping_total_height, $woocommerce->cart->subtotal, 
                                    $this->origin_city);
				    WC() -> session -> set($cache_input_key, $content_tarif);
                                 }    
			if (empty($content_tarif)){
				return;
			}
			$json = json_decode($content_tarif);
                                  $status = $json -> {'status'} -> {'code'};
                                  if(empty($status)) {
                                        return;
                                  }
				  if($status == 401){
					  //do nothing.
				  }else
                                  if ($status != 200){
                                        array_push($this -> array_of_tarif, array('id' => 'Epeken-Courier','label' => 'Error '.$status.':'.$json -> {'status'} -> {'description'}.' atau silakan menghubungi <a href="http://www.epeken.com/contact">team support</a>.', 'cost' => '0'));
                                        return;
                                  }
			$json_tarrifs= $json->{'results'};
			$currency = $json -> {'currency'};
			$services = array();
			if(!empty($json_tarrifs)) {
			foreach ($json_tarrifs as $element) {
                          $kurir = $element -> {'code'};
                          $element = $element -> {'costs'};
                          foreach($element as $element_cost) {
			      $cur = $element_cost -> {'currency'};
			      $usd_rate = 1;
			      if($cur === 'USD')
				$usd_rate = $currency -> {'value'};
                              $service = $element_cost -> {'service'};
                              $rateusd = $element_cost ->{'cost'};
                              $rate = $element_cost ->{'cost'} * $usd_rate;
                              $label = strtoupper($kurir.' '.$service);
                              $service_detail = array('kurir' => $kurir, 'service' => $service, 'rate' => $rate);
                              array_push($services, $service_detail);
                          }
                         }
			}
			for($i=0;$i<sizeof($services);$i++) {
			      $service = $services[$i];
			      $label = "PT ".strtoupper($service['kurir'])." - ".$service["service"];
			      if ($this -> is_shipping_exclude($service['service']))
                                        continue;
			      array_push($this->array_of_tarif, array("id" => "pos_".$service ["service"], 
					  "label" => $label, "cost" => $service["rate"]));
			}
			do_action('epeken_custom_international_tariff', $this); 
	}
		
	
	public function set_shipping_cost(&$package = array()) {
			$this ->  get_destination_city_and_kecamatan();
			if(!empty($this -> shipping_country) && $this -> shipping_country !== 'ID') {
				return;
			}
			if(isset($package) && array_key_exists('vendor_id',$package)) {
				$this -> vendor_id = $package['vendor_id'];
			}
			if(isset($package) && epeken_is_dokan_pro_active()){
				$package['seller_id'] = $package['vendor_id'];;
			}
			$this -> origin_city = $this -> get_origin_kurir($this -> vendor_id); //city_name
			do_action('epeken_set_origin_city', $this);
			unset($this -> array_of_tarif);
			$this -> array_of_tarif = array();
			$cache_input_key = $this->shipping_city.'-'.$this->shipping_kecamatan.'-'.$this->origin_city.'_ro';
			$cache_input_key = preg_replace( '/[^\da-z]/i', '_', $cache_input_key );
			$content_tarif = '';
			if(!empty(WC() -> session -> get($cache_input_key))) {
				$content_tarif = WC() -> session -> get($cache_input_key);
			}else{
				$content_tarif = epeken_get_tarif($this -> shipping_city,$this -> shipping_kecamatan, $this -> origin_city);
				WC() -> session -> set($cache_input_key, $content_tarif);
			}

		    	if($content_tarif === "")
				return;
			
			$json = json_decode($content_tarif);
			$status = $json -> {'status'} -> {'code'};
			  
			if(empty($status))
			  return;
			  
			if ($status != 200){
				WC() -> session -> set($cache_input_key,null);
				array_push($this -> array_of_tarif, 
					array('id' => 'Epeken-Courier',
					'label' => 'Error '.
					  $status.':'.$json -> {'status'} -> {'description'}.
				   	  ' atau silakan menghubungi Administrator.', 
					'cost' => '0'));	
				return;
			 }

 			$this -> destination_province = $json -> {'destination_details'} -> {'province'};
                        $this -> map_destination_province();

			 $isshippedifadr = WC() -> session -> get('isshippedifadr');
                         if($isshippedifadr === '1'){
                           add_action('woocommerce_review_order_before_cart_contents',array(&$this,'epeken_triger_shipping_province'));
                         } else {
                           add_action('woocommerce_review_order_before_cart_contents',array(&$this,'epeken_triger_billing_province'));
			 }
			
			 $this -> chosen_shipping_method = trim($_POST['shipping_method'][0]);

	    	         add_action('woocommerce_cart_calculate_fees', array($this,'calculate_insurance'));
                         add_action('woocommerce_cart_calculate_fees', array($this, 'calculate_biaya_tambahan'));
			  
			 $opt_vol_matrix = $this -> settings['volume_matrix'];

			 if ($opt_vol_matrix === 'yes') {
				$this -> count_cart_weight_and_dimension($package);
			 }

			 $json_tarrifs= $json->{'results'};
			 $services = array();
			 $berat_asli = 0;
			 if(!empty($json_tarrifs)) {
			 foreach($json_tarrifs as $element){
			        $kurir = $element -> {'code'};
				$is_volumetrik = $element->{'is_volumetrik'};
				$element = $element -> {'costs'};
				foreach($element as $element_cost) {
				 $service = $element_cost -> {'service'};
				 $rate = $element_cost ->{'cost'}[0]->{'value'};				
				 $etd = $element_cost -> {'cost'}[0]->{'etd'};
				 if ($opt_vol_matrix === "yes") {
					$this -> is_packing_kayu_valid = false;
					$shipping_total_weight_woodpack = 1;
					$shipping_metric_dimension_woodpack = 1;

					if(get_option('epeken_packing_kayu_enabled') === "yes"){
                                        $pengali = get_option('epeken_pengali_packing_kayu');
                                        if(empty($pengali) || $pengali < 1) { 
                                                $pengali = 1; 
                                        }    
     
                                        $pengali = str_replace(",",".", $pengali);
     
                                        $array_of_packing_kayu_prod_cat = explode(",",get_option('epeken_pc_packing_kayu',''));
                                        global $woocommerce;
                                        $contents = $woocommerce->cart->cart_contents;
                                        foreach($contents as $content) {
                                                $product_id = $content['product_id'];
                                                $tmp_boolean = false;
                                                for($i=0;$i<sizeof($array_of_packing_kayu_prod_cat);$i++){
                                                 $tmp_boolean = epeken_is_product_in_category($product_id,trim($array_of_packing_kayu_prod_cat[$i]));
                                                 /* packing kayu product based */
                                                 if (!$tmp_boolean) {
                                                        $product_wood_pack_mandatory = get_post_meta($product_id,'product_wood_pack_mandatory',true);
                                                        if ($product_wood_pack_mandatory === 'on')
                                                         $tmp_boolean = true;   
                                                 }    
                                                 /* --- */
                                                        if($tmp_boolean == true){
                                                                break;
                                                        }    
                                                 }    
                                                 if($tmp_boolean == true){
                                                                $this -> is_packing_kayu_valid = true;
								$shipping_total_weight_woodpack = ($this->bulatkan_berat($this -> shipping_total_weight)) * $pengali;
                                                                $shipping_metric_dimension_woodpack = ($this -> bulatkan_berat($this -> shipping_metric_dimension)) * $pengali;
                                                                break;
                                                 }    

                                            }    
                                        }

					$berat_asli = $this -> shipping_total_weight;
					$this -> shipping_total_weight = $this -> bulatkan_berat($this->shipping_total_weight);
                                        $this -> shipping_metric_dimension = $this -> bulatkan_berat ($this -> shipping_metric_dimension);
					$original_rate = $rate;
					if($kurir === "jne" || $kurir === "tiki" || $kurir === "JNE" || $kurir === "TIKI") {	
					 if ($this -> shipping_total_weight >= $this -> shipping_metric_dimension) {
						$rate = $rate * $this -> shipping_total_weight;
						if(trim(strtolower($kurir)) === 'jne' && $this -> is_packing_kayu_valid) 
						  $rate = $original_rate * $shipping_total_weight_woodpack;
							
					 }else{
						$rate = $rate * $this -> shipping_metric_dimension;
						if(trim(strtolower($kurir)) === 'jne' && $this -> is_packing_kayu_valid) 
							$rate = $original_rate * $shipping_metric_dimension_woodpack;
					 }
					}else{
						$rate = $rate * $this -> shipping_total_weight;
						if(!empty($is_volumetrik) && $is_volumetrik === "N")
						 $rate = $original_rate; 
					}
				  }
				 $markup = $this -> additional_mark_up($kurir,$this -> shipping_total_weight);

				 if ($rate > 0)
				  $rate = $rate + $markup;

				 $service_detail = array('kurir' => $kurir, 'service' => $service , 'rate' => $rate, 'etd' => $etd);
				 array_push($services,$service_detail);
				}
			  }
			}

                	$this -> additionalLabel ="";

                	if ($opt_vol_matrix === "yes"){
                         	$this -> additionalLabel = "<span style='font-weight: normal;'>".$this->shipping_total_weight." kg</span>";
				add_action('woocommerce_checkout_update_order_meta',  array($this, 'add_order_meta_weight_dimension'));
                	}

			if(!empty($services)) {
			$is_eta = get_option('epeken_setting_eta');
		        foreach($services as $services_element) {
			 $id = $services_element['kurir'].'_'.$services_element['service'];
			 $label = strtoupper($services_element['kurir'].' '.$services_element['service']);
			 if ($this -> is_shipping_exclude($label))
                                continue;
			 if($is_eta === 'on' && !empty($services_element['etd']))
				$label .= ' ('.$services_element['etd'].' hari)';
			 $cost = $services_element['rate'];
			 if($cost > 0)
				 array_push($this -> array_of_tarif, array('id' => $id,'label' => $label, 'cost' => $cost));
			 }
			}

			/* Flat Tarif */
			if(!epeken_is_multi_vendor_mode())
				$this -> add_flat_tariff();

			if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($this -> vendor_id)){
				$vendor_id = $this -> vendor_id;
				$vendor_flat = get_user_meta(intval($vendor_id), 'vendor_flat', true);
				$en_flat = get_option('epeken_enabled_flat');
				if($en_flat === 'on' && !empty($vendor_flat) && is_numeric($vendor_flat)){
				   $vendor_flat_label = get_user_meta(intval($vendor_id), 'vendor_flat_label', true);
				   $coverage_kota = get_user_meta(intval($vendor_id),'epeken_kurir_toko_coverage');
				   $coverage_kota = $coverage_kota[0];
				   $ct = urldecode($this -> shipping_city);
				   if (in_array($ct, $coverage_kota)) { 
				       array_push($this -> array_of_tarif, array('id' => 'epeken_vendor_flat','label' => $vendor_flat_label, 'cost' => $vendor_flat)); 
				   }
				}
			}
			do_action('epeken_custom_tariff', $this); 
			add_action('woocommerce_cart_calculate_fees', array(&$this,'calculate_discount_jne_tiki'));
			add_action('woocommerce_cart_calculate_fees', array($this, 'calculate_angka_unik'));
  			if(get_option('epeken_enable_cod_kurir') === 'on')  
		       	    add_filter('woocommerce_available_payment_gateways', 'set_payment_cod', 1);
	}

	public function add_order_meta_weight_dimension($order_id) {
                update_post_meta($order_id, 'weight', $this -> shipping_total_weight);
                update_post_meta($order_id, 'dimension', $this -> shipping_metric_dimension);
        }  

	public function additional_mark_up($kurir,$weight) {
		
                if(strtolower($kurir) === 'jne' && is_numeric(get_option('epeken_markup_tarif_jne')))
                        return $weight*(get_option('epeken_markup_tarif_jne'));

                if(strtolower($kurir) === 'tiki' && is_numeric(get_option('epeken_markup_tarif_tiki')))
                        return $weight*(get_option('epeken_markup_tarif_tiki'));

                if(strtolower($kurir) === 'pos' && is_numeric(get_option('epeken_markup_tarif_pos')))
                        return $weight*(get_option('epeken_markup_tarif_pos'));
					
		if(strtolower($kurir) === 'jnt' && is_numeric(get_option('epeken_markup_tarif_jnt')))
			return $weight*(get_option('epeken_markup_tarif_jnt'));

		if(strtolower($kurir) === 'lion' && is_numeric(get_option('epeken_markup_tarif_lion')))
			return $weight*(get_option('epeken_markup_tarif_lion'));

		if(strtolower($kurir) === 'sicepat' && is_numeric(get_option('epeken_markup_tarif_sicepat')))
			return $weight*(get_option('epeken_markup_tarif_sicepat'));

		if(strtolower($kurir) === 'ninja' && is_numeric(get_option('epeken_markup_tarif_ninja')))
			return $weight*(get_option('epeken_markup_tarif_ninja'));

		if(strtolower($kurir) === 'jtr' && is_numeric(get_option('epeken_markup_tarif_jtr')))
			return get_option('epeken_markup_tarif_jtr');
		
		if(strtolower($kurir) === 'wahana' && is_numeric(get_option('epeken_markup_tarif_wahana')))
			return $weight*(get_option('epeken_markup_tarif_wahana'));


                return 0;
        }    

	public function is_shipping_exclude ($shipping_label) {
		$ret = false;
		$shipping_label = trim($shipping_label);
		$c_array = 
		array('JNE SPS','JNE CTCSPS','TIKI SDS','JNE CTCBDO','JNE PELIK','RPX HCP','RPX GDP',
		'RPX VLP','RPX','RPX BNP','RPX RGS','RPX PSR','ePacket LP APP','Qix TCD NonDOC');
		if (in_array($shipping_label,$c_array))
		{
			$ret = true;	
			return $ret;
		}

		if (strpos($shipping_label, 'RPX') !== false &&  
		$shipping_label !== 'RPX SDP' && 
		$shipping_label !== 'RPX NDP' && 
		$shipping_label !== 'RPX MDP' && 
		$shipping_label !== 'RPX RGP') {
                        $ret = true;
                        return $ret;
        	} 

		 if($this -> shipping_country !== "ID") {
			$enabled_pos_ems_priority_doc = get_option('epeken_enabled_pos_ems_priority_doc');
			if ($enabled_pos_ems_priority_doc !== "on" && strpos($shipping_label,'PAKETPOS CEPAT LN') !== false) {
					return true;
			}
			$enabled_pos_ems_priority_mar = get_option('epeken_enabled_pos_ems_priority_mar');
			if ($enabled_pos_ems_priority_mar !== "on" && strpos($shipping_label,'PAKETPOS BIASA LN') !== false) {
					return true;
			}
			$enabled_pos_ems_doc = get_option('epeken_enabled_pos_ems_doc');
			if ($enabled_pos_ems_doc !== "on" && strpos($shipping_label,'EMS DOKUMEN') !== false) {
					return true;
			}
			$enabled_pos_ems_mar = get_option('epeken_enabled_pos_ems_mar');
			if ($enabled_pos_ems_mar !== "on" && strpos($shipping_label,'EMS BARANG') !== false) {
					return true;
			}
			$enabled_pos_rln = get_option('epeken_enabled_pos_rln');
			if ($enabled_pos_rln !== "on" && strpos($shipping_label,'R LN') !== false) {
					return true;
			}
			$enabled_pos_ems_epacket_lx = get_option('epeken_enabled_pos_ems_epacket_lx');
			if ($enabled_pos_ems_epacket_lx !== "on" && strpos($shipping_label, 'ePacket LX') !== false){
				return true;
			}
			$enabled_pos_ekspor = get_option('epeken_enabled_pos_ekspor');
			if ($enabled_pos_ekspor !== "on" && strpos($shipping_label,'POS EKSPOR') !== false) {
					return true;
			}
			$enabled_jne_international = get_option('epeken_enabled_jne_international');
			if ($enabled_jne_international !== 'on' && strpos($shipping_label, 'INTL') !== false) {
				return true;
			}
        	}
	        	
        	$en_jne = get_option('epeken_enabled_jne'); 
		$en_tiki = get_option('epeken_enabled_tiki'); 
		$en_rpx_sdp = get_option('epeken_enabled_rpx_sdp');
		$en_rpx_mdp = get_option('epeken_enabled_rpx_mdp');
		$en_rpx_ndp = get_option('epeken_enabled_rpx_ndp');
		$en_rpx_rgp = get_option('epeken_enabled_rpx_rgp');
		$en_esl = get_option('epeken_enabled_esl');
		$en_jne_reg = get_option('epeken_enabled_jne_reg');
		$en_jne_oke = get_option('epeken_enabled_jne_oke');
		$en_jne_yes = get_option('epeken_enabled_jne_yes');
		$en_tiki_hds = get_option('epeken_enabled_tiki_hds');
		$en_tiki_ons = get_option('epeken_enabled_tiki_ons');
		$en_tiki_reg = get_option('epeken_enabled_tiki_reg');
		$en_tiki_eco = get_option('epeken_enabled_tiki_eco');
		$vendor_id = $this -> vendor_id;
		if(epeken_is_multi_vendor_mode() && epeken_is_vendor_id($this -> vendor_id)) {
			$en_jne_reg_v = get_user_meta($vendor_id, 'vendor_jne_reg', true);			
			if($en_jne_reg_v !== 'on' || $en_jne_reg !== 'on'){
			    $en_jne_reg = '';
			}
			
			$en_jne_oke_v = get_user_meta($vendor_id, 'vendor_jne_oke', true);
			if($en_jne_oke_v !== 'on' || $en_jne_oke !== 'on'){
				$en_jne_oke = '';
			}
			
			$en_jne_yes_v = get_user_meta($vendor_id, 'vendor_jne_yes', true);    
			if($en_jne_yes_v !== 'on' || $en_jne_yes !== 'on'){
				$en_jne_yes = '';
			}

			if(!empty($en_jne_reg) || !empty ($en_jne_oke) || !empty($en_jne_yes)) 
				$en_jne = 'on';

			
			$en_tiki_reg_v = get_user_meta($vendor_id, 'vendor_tiki_reg', true);
			if($en_tiki_reg_v !== 'on' || $en_tiki_reg !== 'on')
			{
				$en_tiki_reg = '';
			}
			
			$en_tiki_eco_v = get_user_meta($vendor_id, 'vendor_tiki_eco', true);
			if($en_tiki_eco_v !== 'on' || $en_tiki_eco !== 'on')
			{
				$en_tiki_eco = '';
			}
			
			$en_tiki_ons_v = get_user_meta($vendor_id, 'vendor_tiki_ons', true);
			if($en_tiki_ons_v !== 'on' || $en_tiki_ons !== 'on')
			{
				$en_tiki_ons= '';
			}

			$en_tiki_hds = '';
			if(!empty($en_tiki_reg) || !empty ($en_tiki_eco) || !empty($en_tiki_ons)) 
				$en_tiki = 'on';
			
			$en_esl = '';
		}	

	 	if (empty($en_jne) && strpos(substr($shipping_label,0,3),"JNE") !== false) {
			$ret = true;
		}else if(empty($en_tiki) && strpos(substr($shipping_label,0,3),"TIK") !== false) {
			$ret = true;
		}else if(empty($en_rpx_sdp) && strpos($shipping_label,"RPX SDP") !== false) {
			$ret = true;
		}else if(empty($en_rpx_mdp) && strpos($shipping_label,"RPX MDP") !== false) {
			$ret = true;
		}else if(empty($en_rpx_ndp) && strpos($shipping_label,"RPX NDP") !== false){
			$ret = true;
		}else if(empty($en_rpx_rgp) && strpos($shipping_label,"RPX RGP") !== false) {
			$ret = true;
		}else if(empty($en_esl) && strpos(substr($shipping_label,0,3),"ESL") !== false) {
			$ret = true;
		}else if(empty($en_jne_reg) && ($shipping_label === "JNE CTC" || $shipping_label === "JNE REG") !== false){
			$ret = true;
		}else if(empty($en_jne_oke) && ($shipping_label === "JNE CTCOKE" || $shipping_label === "JNE OKE") !== false){
			$ret = true;
		}else if(empty($en_jne_yes) && ($shipping_label === "JNE CTCYES" || $shipping_label === "JNE YES") !== false){
			$ret = true;
		}else if(empty($en_tiki_hds) && ($shipping_label === "TIKI HDS") !== false){
                        $ret = true;
		}else if(empty($en_tiki_ons) && ($shipping_label === "TIKI ONS") !== false){
                        $ret = true;
		}else if(empty($en_tiki_reg) && ($shipping_label === "TIKI REG") !== false){
				$ret = true;
		}else if(empty($en_tiki_eco) && ($shipping_label === "TIKI ECO") !== false){
				$ret = true;
		}
		return $ret;
	}

	public function map_destination_province(){
                if($this->destination_province === "Nanggroe Aceh Darussalam (NAD)"){
                        $this -> destination_province = "Daerah Istimewa Aceh";
                }else if($this->destination_province === "DI Yogyakarta"){
                        $this -> destination_province = "Daerah Istimewa Yogyakarta";
                }else if($this->destination_province === "Nusa Tenggara Barat (NTB)"){
                        $this -> destination_province = "Nusa Tenggara Barat";
                }else if($this->destination_province === "Nusa Tenggara Timur (NTT)"){
                        $this -> destination_province = "Nusa Tenggara Timur";
                }
        }

         public function epeken_triger_billing_province () {
          ?>      <script type="text/javascript">
                        jQuery(document).ready(function($){
                                        var pro = '<?php echo $this->destination_province; ?>';
                                        $('#billing_state option').removeAttr('selected');
                                        $('#billing_state option').each(function(){if($.trim($(this).text()) == $.trim(pro)){$(this).attr('selected',true);}});
					var city = '<?php echo $this->shipping_city; ?>'
					$('#billing_city option').removeAttr('selected');
                                        $('#billing_city option').each(function(){if($.trim($(this).text()) == $.trim(city)){$(this).attr('selected',true);}});

                                });
                    </script>
                <?php
          }

        public function epeken_triger_shipping_province () {
          ?>      <script type="text/javascript">
                        jQuery(document).ready(function($){
                                        var pro = '<?php echo $this->destination_province; ?>';
                                        $('#shipping_state option').removeAttr('selected');
					$('#shipping_state option').each(function(){if($.trim($(this).text()) == $.trim(pro)){$(this).attr('selected',true);$('#shipping_state');}});
					var city = '<?php echo $this->shipping_city; ?>'
					$('#shipping_city option').removeAttr('selected');
                                        $('#shipping_city option').each(function(){if($.trim($(this).text()) == $.trim(city)){$(this).attr('selected',true);}});

                                });
                    </script>
                <?php
          }
		  
	public function add_subsidi_information() {
		$subsidi = get_option('epeken_subsidi_ongkir');

		$subsidi_ongkir_dengan_kupon = get_option('epeken_nominal_subsidi_ongkir_dengan_kupon');
		$kode_kupon = get_option('epeken_kode_kupon_subsidi_ongkir');
		$kode_kupon = strtolower($kode_kupon);
                $cek = $this -> epeken_is_kupon_dipakai_saat_checkout($kode_kupon);

		if($cek && $subsidi_ongkir_dengan_kupon > 0) {
			$subsidi = $subsidi_ongkir_dengan_kupon;
			?>
			<script type="text/javascript">
			 jQuery(document).ready(function($){
                                        $('.coupon-<?php echo $kode_kupon;?>').hide();
                                });
			</script>
			<?php
		}

		?>
		<tr>
			<td colspan=2><?php echo __('Selamat!!! Anda mendapatkan subsidi ongkir sebesar Rp.','epeken-all-kurir'); echo $subsidi; echo __('. Harga Ongkir Di Atas Sudah Dikurangi Subsidi.','epeken-all-kurir'); ?></td>
		</tr>
		<?php
	}

	public function add_volume_dimension_label() {
		?>
			<tr id="tr_berat">
				<td><strong><?php echo __('Weight', 'epeken-all-kurir');?></strong>
				</td>
				<td class="epeken-shipping-label">
				<?php echo $this->additionalLabel; 
				if($this -> is_packing_kayu_valid === true) {
                                   ?><br> <?php echo __('Wooden cover is mandatory for this package (JNE Only) so that weight is muliplied by ','epeken-all-kurir').' '.get_option("epeken_pengali_packing_kayu"); ?><?php
                                }
				?>
				</td>
			</tr>
		<?php
	}

	public function add_ori_dest_info($package) {
			if(epeken_is_multi_vendor_mode())
			{
				$store_name = $package["package_name"];
				$weight = $package["weight"];
				/*if(epeken_is_dokan_pro_active()) {
					$vid = $package['seller_id'];
					$store_info = dokan_get_store_info( $vid );
					$store_name = $store_info['store_name'];
					$items_of_vendor = epeken_list_product_of_vendor_in_cart($vid);
					$weight = epeken_calculate_vendor_package_weight($items_of_vendor);
				}*/
				$product_owner_label = __('Store','epeken-all-kurir');
				if(epeken_is_woo_dropshippers_active()) {
					$product_owner_label = 'Pemasok';
				}
			?>
			<tr><td><?php echo $product_owner_label; ?></td><td class="epeken-shipping-label"><?php echo $store_name;?></td></tr>
			<tr><td><?php 
			$label_weight = $weight;
			if (get_option('woocommerce_weight_unit') === 'g') {
			 $label_weight = $weight * 1000;
			}	
			echo __('Weight', 'epeken-all-kurir');?></td><td class="epeken-shipping-label"><?php echo $label_weight.' '.get_option('woocommerce_weight_unit');?></td></tr>
				<?php
			}else{		
			 $this -> add_volume_dimension_label();
			}
			if ($this -> settings['is_kota_asal_in_product_details'] === 'yes') {
		?>
			<tr>
                                <td><strong><?php echo __('Origin City', 'epeken-all-kurir');?></strong>
                                </td>
				<td class="epeken-shipping-label">
                                  <?php 
				   if(epeken_is_multi_vendor_mode()){
				      if(epeken_is_wcpv_active()) {
					$epeken_vendor_data = get_term_meta($package['vendor_id'],'epeken_vendor_data')[0];
				        echo epeken_code_to_city($epeken_vendor_data['vendor_data_asal_kota']);
				      } else if(epeken_is_multi_inventory()) {
						$vendor_id = $package['vendor_id'];
						$origin_city_code = get_term_meta($vendor_id,'woocommerce_inventory_origin_city', true);
 						$city = epeken_code_to_city($origin_city_code);
						echo $city;
   				      } else {
					echo epeken_code_to_city(get_user_meta($package['vendor_id'], 'vendor_data_kota_asal', true));
				      }
				   }else {
				    echo $this->origin_city; 
				   }
				 ?>
                                </td>
                        </tr>
			<?php if ($this -> shipping_country == 'ID' || empty($this -> shipping_country)) { ?>
			<tr>
                                <td><strong><?php echo __('Destination City', 'epeken-all-kurir');?></strong>
                                </td>
						        <td class="epeken-shipping-label">
                                  <?php $kota_tujuan = urldecode($this -> shipping_city); echo $kota_tujuan; ?>
                                </td>
                        </tr>
			     
		<?php   } else {
			?>
			<tr>
				<td><strong><?php echo __('Destination Country', 'epeken-all-kurir');?></strong></td>
				<td class="epeken-shipping-label"><?php echo WC()->countries->countries[ $this->shipping_country ]; ?></td>
			</tr>
			<?php
			}
			}
	}
	public function count_decimal_value($weight){
                if ($weight < 1){
                        return 0;
                }
                $dec_val = 0;
                $tmp_weight = $weight;
                while($tmp_weight >= 1){
                        $tmp_weight = $tmp_weight - 1;
                }
                $dec_val = $tmp_weight;
                return $dec_val;
        }
	public function get_cart_total() {
		global $woocommerce;
                $price = $woocommerce -> cart -> subtotal;
                return $price;
	}
	public function get_discount() {
		global $woocommerce;
		$discount = 0; 
		$coupon_discount_totals = $woocommerce -> cart -> coupon_discount_totals;
		if(sizeof($coupon_discount_totals) > 0) {
		    foreach($coupon_discount_totals as $d)
			$discount = $discount + $d;
		}
		return $discount;
	}
	public function count_cart_weight_and_dimension($package = array()){
		global $woocommerce;
		$this -> shipping_total_weight = 0;
		$this -> shipping_metric_dimension = 0;
            	$cart_weight = 0;
			$metric_dimension = 0;
			$length=0;$width=0;$height=0;
                                //foreach($woocommerce -> cart -> get_cart() as $value){
				if(!empty($package)) {
				  foreach($package['contents'] as $value){
					$variation_id = $value ['variation_id'];
					$product_data = $value['data'];	
					
					if($variation_id > 0) //if product is variation product
						$product_data = new WC_Product_Variation($variation_id);
                    
					
					 //$cart_weight = $cart_weight + (floatval($value['quantity']) * floatval($product_data -> get_weight()));
					 $berat_barang = (floatval($value['quantity']) * floatval($product_data -> get_weight()));

					 if(get_option('woocommerce_weight_unit') === "g") {
						$berat_barang = $berat_barang / 1000;
					 }

					 $lebar_barang = intval($product_data -> get_width());
					 $tinggi_barang = intval($product_data -> get_height());
					 $panjang_barang = (floatval($value['quantity']) * intval($product_data -> get_length()));

					if(get_option('woocommerce_dimension_unit') === 'mm') {
						$lebar_barang = $lebar_barang / 100;	
						$tinggi_barang = $tinggi_barang / 100;
						$panjang_barang = $panjang_barang / 100;
					} 
				
					 $dimensi_barang = ((intval($panjang_barang) * 
					 intval($tinggi_barang) * intval ($lebar_barang)) /6000);
					 $enable_dimensi_to_calc_ongkir = $this -> settings["perhitungan_dimensi"];
					 if($enable_dimensi_to_calc_ongkir === "yes" && $dimensi_barang > $berat_barang) {
					   $berat_barang = $dimensi_barang;
					 }	
		
					 $cart_weight = $cart_weight + $berat_barang;
					
					 if($length < $panjang_barang) 
					 	$length = $panjang_barang;
					 if($width < $lebar_barang)
						$width = $lebar_barang;
					 if($height < $tinggi_barang)
						$height = $tinggi_barang;
					
                   		    }
				}

		$this -> shipping_total_length = $length;
		$this -> shipping_total_width = $width;
		$this -> shipping_total_height = $height;
		$this -> shipping_total_weight = $cart_weight;
		$this -> shipping_metric_dimension = $cart_weight;
			
	}
	public function bulatkan_berat($cart_weight){
		$treshold = 0.3;
		if($this -> origin_city === 'Kota Batam')
			$treshold = 0.4;

		if (!empty($this -> settings['treshold_pembulatan']) && $this -> settings['treshold_pembulatan'] > 0) {
			$treshold = ($this -> settings['treshold_pembulatan']) / 1000;
		}
                
		$dec_val = $this->count_decimal_value($cart_weight);
                if ($dec_val > $treshold) {
                 $cart_weight = ceil($cart_weight);
                }else{
                 $cart_weight = floor($cart_weight);
                }
                                if ($cart_weight == 0)
                        $cart_weight = 1;
                $retu = $cart_weight;
                return $retu;
        }


	public function yes_not_found(){
		?>
		<script language="javascript">alert('Tariff JNE tak ditemukan. Tarif dikembalikan ke JNE Regular.');
		var val = 'REGULAR';
						        var sel = document.getElementById('order_comments');
							var opts = sel.options;
							for(var opt, j = 0; opt = opts[j]; j++) {
    							    if(opt.value == val) {
           							sel.selectedIndex = j;
            							break;
        						    }
    							}
		</script>
		<?php
	}

	public function examine_current_currency () {
		global $wp_filter;
                WC() -> session -> set('current_currency', $this -> current_currency);
                $this -> current_currency_rate = 1;
                $currency = isset($wp_filter['woocommerce_currency']) ? $wp_filter['woocommerce_currency'] : "";
                if (empty($currency))
                { $this -> current_currency = "IDR"; return; }
                $currency = $currency -> callbacks;
		
		if(!isset($currency["function"]))
		 return;
                
 		$currency = $currency["function"][0];
                $this -> current_currency = trim($currency -> current_currency);

                $epeken_currency_rate_setting = get_option('epeken_multiple_rate_setting');
                if(empty($epeken_currency_rate_setting) ||  $epeken_currency_rate_setting === 'manual'){
                        $wmc_config = get_option('wmc_selected_currencies');
                        $rate = $wmc_config[$this -> current_currency]['rate'];
			if(empty($rate))
				$rate = 1;
                        WC() -> session -> set('rate_currency', 1/$rate);
                        return;
                }

                if(empty( $this -> current_currency ))
                 $this -> current_currency = 'IDR';

                if($this -> current_currency === 'IDR') {
                        WC() -> session -> set('current_currency', 'IDR');
                        WC() -> session -> set('rate_currency', 1);
                        return;
                }
    
                //manual        
                $epeken_currency_rate_setting = get_option('epeken_multiple_rate_setting');
                if(empty($epeken_currency_rate_setting) ||  $epeken_currency_rate_setting === 'manual'){
                        $wmc_config = get_option('wmc_selected_currencies');
                        $rate = $wmc_config[$this -> current_currency]['rate'];
			if(empty($rate))
				$rate = 1;
                        WC() -> session -> set('rate_currency', 1/$rate);
                        $this -> current_currency_rate = WC() -> session -> get('rate_currency');
                        return;
                }   

                //get currency from central bank
              if($this -> current_currency !== 'IDR') {
                if(WC() -> session -> get('current_currency') === $this -> current_currency && !empty(WC() -> session -> get('rate_currency')) && is_numeric(WC() -> session -> get('rate_currency'))){
                        $wmc_config = get_option('wmc_selected_currencies');
                        $wmc_config[$this -> current_currency]['rate'] = 1/(WC() -> session -> get('rate_currency'));
                        update_option('wmc_selected_currencies',$wmc_config);
                        $this -> current_currency_rate = WC() -> session -> get('rate_currency');
                        return;
                }   
		$rate_query_result = epeken_get_currency_rate($this->current_currency);
                $rate_query_result = json_decode($rate_query_result,true);
                if($rate_query_result["status"]["code"] == 200 && is_numeric($rate_query_result["status"]["amount"]))
                        {
                                $this -> current_currency_rate = $rate_query_result["status"]["amount"];
                                WC() -> session -> set('rate_currency', $this -> current_currency_rate);
                                $wmc_config = get_option('wmc_selected_currencies');
                                $wmc_config[$this -> current_currency]['rate'] = 1/(WC() -> session -> get('rate_currency'));
                                update_option('wmc_selected_currencies',$wmc_config);
                        }
              }   
        }

    public function apply_subsidi () {
		$epeken_subsidi_ongkir =  get_option('epeken_subsidi_ongkir');
		$epeken_subsidi_min_purchase = get_option('epeken_subsidi_min_purchase');

		$epeken_cities_subsidi = get_option('epeken_cities_subsidi');
		$destination_city = urldecode($this -> shipping_city);

		$total = $this -> get_cart_total() - $this -> get_discount();
		
		$kode_kupon = get_option('epeken_kode_kupon_subsidi_ongkir');
		$cek = $this -> epeken_is_kupon_dipakai_saat_checkout($kode_kupon);		
		if($cek) {
		 $epeken_subsidi_min_purchase = 0;
		}
		
		if (!empty($epeken_subsidi_ongkir) && (!empty($total) || $total > 0) && $total < $epeken_subsidi_min_purchase)
			return;

		if (empty($epeken_subsidi_ongkir) || $epeken_subsidi_ongkir == 0)
		 	return;

		if (!empty($epeken_cities_subsidi) && !in_array($destination_city, $epeken_cities_subsidi))
			return;

		$this -> is_subsidi_applied = true;
		do_action('epeken_customize_subsidi',$this);
		if(!($this -> is_subsidi_applied))
			return;
		
		add_action('woocommerce_cart_calculate_fees', array($this, 'epeken_apply_subsidi'));	
	}

	public function epeken_apply_subsidi() {
		global $woocommerce;
		$value_subsidi = get_option('epeken_subsidi_ongkir');
   		$chosen = WC()->session->get('chosen_shipping_methods');
   		$array_of_tarif = $this -> array_of_tarif;
		$price = 0;

		$kode_kupon = get_option('epeken_kode_kupon_subsidi_ongkir');
		$cek = $this -> epeken_is_kupon_dipakai_saat_checkout($kode_kupon);		
		if($cek)
		   $value_subsidi = get_option('epeken_nominal_subsidi_ongkir_dengan_kupon');
			
		$id_kurir = $chosen[0];
   		foreach($array_of_tarif as $t) {
         		if($t['id'] === $id_kurir)
           		{$price = $t['cost']; break;}
   		}
		if($value_subsidi > $price){
			$value_subsidi = $price;
		}
		$value_subsidi = -1 * $value_subsidi;
    		$woocommerce -> cart -> add_fee(__('Shipping Subsidy', 'epeken-all-kurir'), $value_subsidi , false, '');
 	}

	public function cod_tarif() {
		$enable_cod = get_option("epeken_enable_cod");
		$cod_label = get_option("epeken_cod_label");
		$cod_cities = get_option("epeken_cities_cod");
		$city = urldecode($this -> shipping_city);
		$cod_cities_check = true;
		if (!empty($cod_cities) && is_array($cod_cities) && !in_array($city,$cod_cities)) {
		  $cod_cities_check = false;
		}
		if($enable_cod === 'on' && $cod_cities_check){
			$rate = array(
				'id' => 'epeken_cod',
				'label' => $cod_label,
				'cost' => 0,
				'taxes' => false
			);
			$this -> add_rate($rate);
		}
	}
	
	public function epeken_is_kupon_dipakai_saat_checkout($kode_kupon) {
		global $woocommerce;
                $applied_coupons = $woocommerce -> cart -> get_applied_coupons();
		if(!empty($applied_coupons)) {	
                foreach($applied_coupons as $coupon) {
			if(strtolower($coupon) === strtolower($kode_kupon)) 
			  return true;
		}}
		return false;
	}
		
	public function calculate_shipping($package = array()) {	
		$this -> examine_current_currency();
		$inputs = array(
			"shipping_class" => $this,
			"package" => $package
		);
		do_action('epeken_hook_calculate_shipping', $inputs); 
	}
	
	public function post_nonms_calculate_shipping() {
		 if(!empty($this -> shipping_country) && $this -> shipping_country !== 'ID') {
		 // Internatinal Shipping
                  if(sizeof($this -> array_of_tarif) > 0){
                    foreach($this -> array_of_tarif as $rate) {
						
			//USD Rates
			if($this -> settings['mata_uang'] === "1" && $rate['cost'] > 0 && WC() -> session -> get('EPEKEN_USD_RATE') > 0)
                          $rate['cost'] = round($rate['cost']/(WC() -> session -> get('EPEKEN_USD_RATE')),2);
			  $rate['taxes']  = false;
			
			 if(get_option('woocommerce_calc_taxes') === 'yes') {
			   $rate['taxes'] = true; 
			   $rate['calc_tax'] = 'per_order';
			 }
					  
                        $this -> add_rate ($rate);
                    }    
                  }     	
		  return;
		 }
		$this -> if_total_got_free_shipping();
		$wooversion = epeken_get_woo_version_number();
		if($this -> is_free_shipping){
			if ($wooversion >= 3) { 
			 WC()->customer->set_calculated_shipping( true );
			}else {
			 WC()->customer->calculated_shipping( true ); 
			}
			$rate = array(
				'id' => $this -> id,
				'label' =>  __('Free shipping','epeken-all-kurir'),
				'cost' => 0,
				'taxes' => false
			);
			$this->add_rate($rate);
			$keep_displaying_ongkir = false;
			$keep_displaying_ongkir = apply_filters('epeken_free_keep_display_ongkir', $arg);
			if (!$keep_displaying_ongkir)
			 return;
		}
		if(sizeof($this -> array_of_tarif) > 0) {
 		  if ($wooversion >= 3) {
		   WC()->customer->set_calculated_shipping( true );	
		  }else{
		   WC()->customer->calculated_shipping( true );    
		  }
		 
		//Local Shipping
		if(!empty($this -> array_of_tarif)) {
		foreach($this -> array_of_tarif as $rate) {
			 //USD Rates
			 if($this -> settings['mata_uang'] === "1" && $rate['cost'] > 0 && WC() -> session -> get('EPEKEN_USD_RATE') > 0)
                	 $rate['cost'] = round($rate['cost']/(WC() -> session -> get('EPEKEN_USD_RATE')),2);
			 
			 $rate['taxes'] = false;

			 if(get_option('woocommerce_calc_taxes') === 'yes') {
			   $rate['taxes'] = true; 
			   $rate['calc_tax'] = 'per_order';
			 }
					 
			 $this -> add_rate ($rate);
		 }}
		$this -> cod_tarif(); 
		$this -> apply_subsidi();

		if($this -> chosen_shipping_method === 'epeken_cod' 
			&& sanitize_text_field(get_option('epeken_cod_payment')) === 'on') {
		   add_filter('woocommerce_available_payment_gateways','set_payment_cod',1);
		}
		add_action('woocommerce_cart_calculate_fees',array($this,'calculate_rpx_insurance'));
	      }
	}

	public function calculate_discount_jne_tiki() {
		global $woocommerce;
		$chosen = WC()->session->get('chosen_shipping_methods');
		$value_diskon_jne = get_option('epeken_diskon_tarif_jne'); #percentage discount JNE
		$value_diskon_tiki = get_option('epeken_diskon_tarif_tiki'); #percentage discount TIKI
		$id_kurir = $chosen[0];
		$price = 0;
		$array_of_tarif = $this -> array_of_tarif;
		foreach($array_of_tarif as $t) {
			if($t['id'] === $id_kurir)
			{$price = $t['cost']; break;} 
		}
		$discount = 0;
		if($value_diskon_jne > 0 && in_array($id_kurir, array('JNE_REG','JNE_OKE','JNE_YES'))) {
		 $discount = -1 * ($value_diskon_jne/100) * $price;
		}
		if($value_diskon_tiki > 0 && in_array($id_kurir, array('TIKI_REG','TIKI_ECO','TIKI_ONS', 'TIKI_HDS'))) {
		 $discount = -1 * ($value_diskon_tiki/100) * $price;
		}

		if(abs($discount) >= $price)
		 $discount = -1 * $price;

		if($discount < 0)
		 $woocommerce -> cart -> add_fee(__('Shipping Discount', 'epeken-all-kurir'), $discount , false, '');
    	}

	public function add_flat_tariff() {
		$epeken_nama_tarif_flat = get_option('epeken_nama_tarif_flat');
		$epeken_nominal_tarif_flat = get_option('epeken_nominal_tarif_flat');
		$epeken_kota_tarif_flat = get_option('epeken_kota_tarif_flat', true);
		$city = urldecode($this -> shipping_city);
		$city = trim($city);
		
		if(!empty($epeken_nama_tarif_flat) && !empty($epeken_nominal_tarif_flat)) {
			$id = str_replace(' ','-',strtolower($epeken_nama_tarif_flat));
			$rate =  array('id' => $id,'label' => $epeken_nama_tarif_flat, 'cost' => $epeken_nominal_tarif_flat, 'taxes' => false);
			if(empty($epeken_kota_tarif_flat))
			 $this -> add_rate($rate);
			else if(in_array($city,$epeken_kota_tarif_flat))
			 $this -> add_rate($rate);
		}
	}

	public function if_total_got_free_shipping(){
		global $woocommerce;
                $this -> total_cart = $this -> get_cart_total() - $this -> get_discount();
                $this -> min_allow_fs  = floatval($this -> settings['freeship']);
                $existing_config_free_province = get_option('epeken_province_for_free_shipping'); //array of province
                $existing_config_epeken_is_provinsi_free = get_option('epeken_is_provinsi_free'); //options consist of others are free and these are free
                $kombinasi_province_n_minumum = get_option('epeken_freeship_n_province_for_free_shipping');
	
                /* Free shipping based on province */
                $prov_criteria = false;
                if(!empty($existing_config_free_province))      {
                        if($existing_config_epeken_is_provinsi_free === "these_are_free"){
				if(!empty($existing_config_epeken_is_provinsi_free)) {
                                foreach($existing_config_free_province as $province){
                                        if($this -> destination_province === $province) {
                                                 $prov_criteria = true;
                                        }
                                }}
                        }
                        if($existing_config_epeken_is_provinsi_free === "others_are_free"){
                                $prov_criteria = true;
				if(!empty($existing_config_free_province)) {
                                foreach($existing_config_free_province as $province){
                                        if($this -> destination_province === $province) {
                                                 $prov_criteria = false;
                                        }
                                }}
                        }
                }      

                /* Free shipping based on Product Category */
                $prod_cat_criteria = false;
                if(get_option('epeken_free_pc',false) !== false){
                $array_of_free_prod_cat = explode(",",get_option('epeken_free_pc',''));
                $contents = $woocommerce->cart->cart_contents;
                $is_free_pc = false;
                $boolarr = array();
                $counter_quantity = 0;
                $total_item = 0;
		if(!empty($contents)) {
                foreach($contents as $content) {
                        $product_id = $content['product_id'];
                                $tmp_boolean = false;
                        for($i=0;$i<sizeof($array_of_free_prod_cat);$i++){
                          $tmp_boolean = epeken_is_product_in_category($product_id,trim($array_of_free_prod_cat[$i]));

                          /* free shipping product based */
                          if(!$tmp_boolean)     {
                                $product_free_ongkir = get_post_meta($product_id,'product_free_ongkir',true);
                                $product_city_for_free_shipping = get_post_meta($product_id,'epeken_product_city_for_free_shipping',true);
								
								if ($product_free_ongkir === 'on') {
									if(!empty($product_city_for_free_shipping) && in_array($this -> shipping_city, $product_city_for_free_shipping)){
											$tmp_boolean = true;
									}else if(!empty($product_city_for_free_shipping) && !in_array($this -> shipping_city, $product_city_for_free_shipping)){
											$tmp_boolean = false;
									}else if(empty($product_city_for_free_shipping)){
											$tmp_boolean = true;
									}
								}
			}
                         /* --- */

                          if($tmp_boolean == true){
                                $counter_quantity = $counter_quantity + $content['quantity'];
                                break;
                                }
                        }
                        //array_push($boolarr,$tmp_boolean);
                        $total_item = $total_item + $content['quantity'];
                }}
                $free_pc_q = get_option('epeken_free_pc_q',false) ;
                if( $free_pc_q !== false && $free_pc_q > 0){
                      if($counter_quantity >= $free_pc_q && $total_item === $counter_quantity) {
                         $prod_cat_criteria = true;
                        }
                }else{
                        if($counter_quantity > 0 && $total_item === $counter_quantity) {
                                $prod_cat_criteria = true;
                        }else{
                                $prod_cat_criteria = false;
                        }
                 }
                }
                /* Free shipping based on city */
                $destination_city = strtoupper($this -> shipping_city);
		$destination_city = urldecode($destination_city);
                $cities_for_free = explode(",",$this -> settings["city_for_free_shipping"]);
                $kombinasi_minimum_n_city = get_option('epeken_freeship_n_city_for_free_shipping');
                $city_for_free = false;
                $city_criteria = false;
                if (is_array($cities_for_free)) {
                 foreach($cities_for_free as $city) {
                        $city = urldecode($city);
                        $city = trim($city);
                        $city = strtoupper($city);

                        if(empty($city))
                                continue;

                        if (strpos($destination_city, $city) !== FALSE) {
                                $city_criteria = true;
                                break;
                        }
                 }
                }

                /* Free shipping based on minimum total */
		$mintotal_criteria = $this -> validate_freeship_minimum_total();

                $kombinasi_minimum_n_city_criteria = false;
                if($kombinasi_minimum_n_city === "on")
                        $kombinasi_minimum_n_city_criteria = ($mintotal_criteria) && ($city_criteria);

                $kombinasi_minimum_n_province_criteria = false;
                if($kombinasi_province_n_minumum === "on")
                        $kombinasi_minimum_n_province_criteria = ($mintotal_criteria)  && ($prov_criteria);

                $this -> is_free_shipping = ($prov_criteria) || ($city_criteria) || ($prod_cat_criteria) || ($mintotal_criteria) || ($kombinasi_minimum_n_city_criteria) || ($kombinasi_minimum_n_province_criteria);
		
                if($kombinasi_province_n_minumum === "on" || $kombinasi_minimum_n_city === "on") {
                        $this -> is_free_shipping = $kombinasi_minimum_n_province_criteria || $kombinasi_minimum_n_city_criteria;
                }
		$criterias = array(
			'prov_criteria' => $prov_criteria,
			'city_criteria' => $city_criteria,
			'prod_cat_criteria' => $prod_cat_criteria,
			'mintotal_criteria' => $mintotal_criteria,
			'kombinasi_minimum_n_city_criteria' => $kombinasi_minimum_n_city_criteria,
			'kombinasi_minimum_n_province_criteria' => $kombinasi_minimum_n_province_criteria
		);
		$this -> free_ongkir_criteria = $criterias;
		do_action('epeken_is_free_shiping_filter',$this);
	}

	public function validate_freeship_minimum_total() {
		/* Free shipping based on minimum total */
                if(empty($this->min_allow_fs) || $this->min_allow_fs == 0)
                        return false;

                if ($this->total_cart >= $this->min_allow_fs && $this->min_allow_fs > 0)
                {
                        return true;
                }else{
                        return false;
                }
	}

	public function calculate_insurance() {
                 global $woocommerce;
                 $is_with_insurance = $this -> get_checkout_post_data("insurance_chkbox");
		 if(empty($is_with_insurance)) {
			$is_with_insurance = $_POST['insurance_chkbox'];
		 }
                 if(empty($is_with_insurance)){
                        return;
                 }
                 $percentage = 0.002;
                 $premium = (($woocommerce->cart->cart_contents_total) * $percentage) + 5000;
		 if(is_numeric($this -> current_currency_rate) && $this -> current_currency_rate > 0) { 
                        $premium = round(($premium / $this -> current_currency_rate), 2);
                 }  
		//USD Rate		 
	  $this -> refresh_usd_rate();
	  if($this -> settings['mata_uang'] === "1" && $premium > 0 && WC() -> session -> get('EPEKEN_USD_RATE') > 0)
			 $premium = round($premium/(WC() -> session -> get('EPEKEN_USD_RATE')),2);
					 
	  $this -> insurance_premium = $premium;	
	  $woocommerce->cart->add_fee( __('Insurance', 'epeken-all-kurir'), $premium, false, '' );
	}
	
	public function is_woo_multiple_curr_usd () {
		if(defined('WOOMULTI_CURRENCY_F_VERSION')) {
		 $setting         = new WOOMULTI_CURRENCY_F_Data();
		 $current_currency = $setting -> get_current_currency();
		 if ($current_currency  === "USD") {
				return true;
		 }
		}
		return false;
	}

	public function calculate_angka_unik() {
		global $woocommerce;
		
		if ($this -> is_woo_multiple_curr_usd())
			return;
		
		if($this -> chosen_shipping_method === 'epeken_cod') 
			return;

		if($this -> chosen_shipping_method === 'indodana' || $_POST['payment_method'] === 'indodana') 
			return;

		
                if((!empty($this -> current_currency) && $this -> current_currency !== 'IDR') || 
		   (!empty($this -> shipping_country) && $this -> shipping_country !== 'ID'))
	 	{
                        return;
                }
                
                if ($this -> settings['enable_kode_pembayaran'] === "no")
                        return;
                
                if (empty(WC() -> session -> get('ANGKA_UNIK'))) {
                 $max_angka_unik = $this -> settings['max_angka_unik'];
                 
                 if ($max_angka_unik < 0 || $max_angka_unik > 999)
                        $max_angka_unik = 999;
                 
                 
                 WC() -> session -> set('ANGKA_UNIK', rand(1,$max_angka_unik));
                }       

                 $mode_kode_pembayaran = get_option('epeken_mode_kode_pembayaran');
                 if($mode_kode_pembayaran === '-' && WC() -> session -> get('ANGKA_UNIK') > 0) {
                        WC() -> session -> set('ANGKA_UNIK', WC() -> session -> get('ANGKA_UNIK') * (-1));
                 }
                
                $woocommerce->cart->add_fee(__('Unique code','epeken-all-kurir'),WC() -> session -> get('ANGKA_UNIK'), false, '');	
	}

	public function calculate_rpx_insurance() {
		global $woocommerce;
		$en_rpx_insurance = get_option('epeken_enabled_rpx_insurance');
		
		if ($en_rpx_insurance !== 'on') {
			return;
		}
		
		$chosen_shipping = WC()->session->get('chosen_shipping_methods');
		$shipping_cost = 0;
		if(
			strtolower($chosen_shipping[0]) === 'rpx_ndp' || 
			strtolower($chosen_shipping[0]) === 'rpx_sdp' ||
			strtolower($chosen_shipping[0]) === 'rpx_mdp' || 
			strtolower($chosen_shipping[0])=== 'rpx_rgp'
		  ) {
			if(!empty($this -> array_of_tarif)) {
			foreach($this->array_of_tarif as $rate){
			if(strtolower($rate['id']) === strtolower($chosen_shipping[0])){
					$shipping_cost = $rate['cost'];
				}
			}}				
		 $insurance = $shipping_cost;//* ($this -> shipping_total_weight);
		 if($insurance > 0){
			$woocommerce->cart->add_fee(__('RPX Insurance','epeken-all-kurir'), $insurance,false, '');
		 }
		}
	}	

	public function calculate_biaya_tambahan() {
        global $woocommerce;
		
		if ($this -> is_woo_multiple_curr_usd())
			return;
		
		$chosen_shipping = WC()->session->get('chosen_shipping_methods');
		if(strpos(strtolower($chosen_shipping[0]), 'pickup') === false  && strpos(strtolower($chosen_shipping[0]),'flat_rate') === false)
		{ /* do nothing */ }else{return;} 

                $epeken_biaya_tambahan_name = get_option('epeken_biaya_tambahan_name');
                $epeken_biaya_tambahan_amount = get_option('epeken_biaya_tambahan_amount');
		$epeken_perhitungan_biaya_tambahan = get_option('epeken_perhitungan_biaya_tambahan');
                if (empty($epeken_biaya_tambahan_name))
                        $epeken_biaya_tambahan_name = __("Additional Fee",'epeken-all-kurir');

		if (is_numeric($epeken_biaya_tambahan_amount) && $epeken_biaya_tambahan_amount > 0) { 
                  if($epeken_perhitungan_biaya_tambahan === 'percent')
                        $epeken_biaya_tambahan_amount = ($epeken_biaya_tambahan_amount/100)*($woocommerce->cart->subtotal);
    
		  $epeken_biaya_tambahan_amount = round(($epeken_biaya_tambahan_amount / $this -> current_currency_rate),2); 
                  $woocommerce->cart->add_fee($epeken_biaya_tambahan_name,$epeken_biaya_tambahan_amount,false, ''); 
        }    
    }
	
	public function process_update_data_tarif() {
		include_once 'tools/update_tarif.php';		
	}

	public function admin_error($message) {
        $class = "error";
        echo"<div class=\"$class\"> <p>$message</p></div>";
	}
	public function epeken_product_write_panel_tab() {
                echo "<li class=\"product_tabs_lite_tab\"><a href=\"#woocommerce_product_tabs_lite\">" . __( 'Epeken Config', 'woocommerce' ) . "</a></li>";
        }
	public function epeken_product_write_panel() {
		?>
		<div id="woocommerce_product_tabs_lite" class="panel wc-metaboxes-wrapper woocommerce_options_panel"> 
		Test
		</div>
		<?php
	}
	public function epeken_admin_styles () {
		wp_register_style('epeken_admin_style', plugin_dir_url(__FILE__).'assets/css/epeken-admin-style.css');
		wp_enqueue_style('epeken_admin_style');
	}
	public function epeken_change_shipping_pack_name($title, $i, $package) {
		ob_start();
		$this->add_ori_dest_info($package);
		$info = ob_get_clean();	
		return __('Shipping', 'epeken-all-kurir').'<div style="margin: 5px;position: relative;"><table>'.$info.'</table></div>';
	}
	function disable_shipping_calc_on_cart( $show_shipping ) {
    		if( is_cart() ) {
        	 return false;
    		}
    		return $show_shipping;
	}
	}	// End Class WC_Shipping_Tikijne

?>
