<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists('XL_Tab_Library')) {

	class XL_Tab_Library {

		private static $_instance = null;
		static $plugin_data = null;
		static public function init() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
				self::$_instance->include_files();
			}
			return self::$_instance;
		}

		private function __construct() {

			self::$plugin_data = array(
			 'root_file' =>  __FILE__,
			 'pro-link' => 'https://webangon.com/xl-tab',
			 'remote_site' => 'https://webangon.com/extra/plugins/xltab/',
			 'all_endpoint' => 'xltab_all_lib',
			 'single_endpoint' => 'xltab_single_lib'
			); 

			add_action( 'elementor/editor/before_enqueue_scripts', array($this,'editor_script'));
			add_action( 'wp_ajax_xltab_process_ajax', array($this,'ajax_data'));
			add_action( 'wp_ajax_xl_tab_reload_template', array($this,'reload_library'));

		}

		public function __clone() {

			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'xltab' ), '1.0.0' );
		}

		public function __wakeup() {

			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'xltab' ), '1.0.0' );
		}

		public function include_files(){

			require __DIR__ . '/inc/import.php';
			require __DIR__ . '/inc/activation.php';
		}

		public function editor_script(){

			wp_enqueue_script( 'xl-tab-library',  plugins_url( '/assets/js/admin-lib.js', __FILE__));
			wp_enqueue_script( 'masonry');
			wp_enqueue_style( 'xltab_lib',  plugins_url( '/assets/css/style.css', __FILE__ ) );
		}

		function reload_library(){

			XL_Tab_Activation::init();
		}

		function ajax_data(){

			$nav = '';
			$products = get_option('xl_tab_library');
			$products = $products['tab_accordion'];

			if ( is_array($products) ){

				foreach ($products as $item){
					$uniq[] = $item['cat_name'];
				}
				foreach (array_unique($uniq) as $a ) {
					//$nav.= '<a data-cat="'.$a.'" href="#">'.$a.'</a>';
				}
				$category = esc_attr($_POST['category']);
				$page_number = esc_attr($_POST['page']);
				$limit = 10;
				$offset = 0;

				$current_page = 1;
				if(isset($page_number)) {
					$current_page = (int)$page_number;
					$offset = ($current_page * $limit) - $limit;
				}

				//$paged = $total_products > count($paged_products) ? true : false;

				if(!empty($category)) {
					$filtered_products = [];
					foreach($products as $product) {
						if( !empty($category) ) {

							if( $product['cat_name'] == $category ) {
								$filtered_products[] = $product;
							}
						}
					}

					$products = $filtered_products;
				}

				$paged_products = array_slice($products, $offset, $limit);
				$total_products = count($products);
				$total_pages = ceil( $total_products / $limit );

				//echo '<div class="filter-wrap"><a data-cat="" href="#">All</a>'.$nav.'</div>';
				echo '<div class="item-inner">';
				echo '<div class="item-wrap">';
				if (count($paged_products)) {
					foreach ($paged_products as $product) {
						$pro = $product['pro']? '<span class="pro">pro</span>' : '';
						if( $product['pro'] && !class_exists('XL_Tab_Pro') ){

							$btn = '<a target="_blank" href="'.self::$plugin_data['pro-link'].'" class="buy-tmpl"><i class="eicon-external-link-square"></i> Buy pro</a>';
						} else{
							$btn = '<a href="#" data-id="'.$product['id'].'" class="insert-tmpl"><i class="eicon-file-download"></i> Insert</a>';
						}
						?>
					<div class="item">
						<div class="product">
							<div data-preview='<?php echo $product['preview']; ?>' class='lib-img-wrap'>
								<?php echo $pro;?>
								<img src="<?php echo $product['thumb'];?>">
								<i class="eicon-zoom-in-bold"></i>
							</div>
							<div class='lib-footer'>
									<p class="lib-name"><?php echo $product['name']; ?></p>
								<?php echo $btn;?>
							</div>

						</div>
					</div>

					<?php }
					echo '</div><div class="pagination-wrap"><ul>';

				for($page_number = 1; $page_number < $total_pages; $page_number ++) { ?>
						<li class="page-item <?php echo isset($_GET['page']) && $_GET['page'] == $page_number ? 'active' : ''; ?>"><a class="page-link" href="#" data-page-number="<?php echo $page_number; ?>"><?php echo $page_number; ?></a></li>

				<?php }
				echo '</ul></div></div>';
				}
				die();
			}
		}

	}
XL_Tab_Library::init();
}