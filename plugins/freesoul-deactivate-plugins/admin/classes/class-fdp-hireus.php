<?php
/**
 * Class for the Hireus page.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

class FDP_HireUs_Page extends Eos_Fdp_Plugins_Manager_Page {

	/**
	 * Section ID.
	 *
	 * @var string $section_id Section ID
	 * @since  1.9.0
	 */
	public $section_id;

	/**
	 * Button class.
	 *
	 * @var string $button_class Submit button CSS class
	 * @since  1.9.0
	 */
	public $button_class;

	/**
	 * Post types.
	 *
	 * @var array Post types
	 * @since  1.9.0
	 */
	public $post_types;

	/**
	 * Public key.
	 *
	 * @var string $public_key Public key
	 * @since  1.9.0
	 */
	private $public_key = 'dkRkdssdfWlkljk324klj234234';

	/**
	 * Info.
	 *
	 * @var array $info Info
	 * @since  1.9.0
	 */
	private $info;

	/**
	 * Endopoint.
	 *
	 * @var string $endpoint Endepoint
	 * @since  1.9.0
	 */
	private $endpoint             = 'https://shop.freesoul-deactivate-plugins.com/public/hireus-info/';

	/**
	 * Fixed cost.
	 *
	 * @var int $fixed_cost Fixed cost
	 * @since  1.9.0
	 */
	private $fixed_cost           = 20;

	/**
	 * Checkout url.
	 *
	 * @var string $hire_us_checkout_url Checkout URL
	 * @since  1.9.0
	 */
	private $hire_us_checkout_url = 'https://shop.freesoul-deactivate-plugins.com/checkout/?direct-checkout=hire-us&quantity=1';

	/**
	 * Percentage discount.
	 *
	 * @var int $percentage_discount Percentage discount
	 * @since  1.9.0
	 */
	private $percentage_discount;

	/**
	 * Server contribution.
	 *
	 * @var int $server_contribution Server contribution
	 * @since  1.9.0
	 */
	private $server_contribution;

	/**
	 * Posts contribution.
	 *
	 * @var int $posts_contribution Posts contribution
	 * @since  1.9.0
	 */
	private $posts_contribution;

	/**
	 * Archives contribution.
	 *
	 * @var int $archives_contribution Archives contribution
	 * @since  1.9.0
	 */	
	private $archives_contribution;

	/**
	 * Theme contribution.
	 *
	 * @var int $theme_contribution Theme contribution
	 * @since  1.9.0
	 */	
	private $theme_contribution;

	/**
	 * Plugins contribution.
	 *
	 * @var int $plugins_contribution Plugins contribution
	 * @since  1.9.0
	 */	
	private $plugins_contribution;

	/**
	 * Personal lifetime price
	 *
	 * @var int $personal_lifetime_price Personal lifetime price
	 * @since  1.9.0
	 */	
	private $personal_lifetime_price;

	/**
	 * Fixed discount.
	 *
	 * @var int $fixed_discount Fixed discount
	 * @since  1.9.0
	 */	
	private $fixed_discount;

	/**
	 * Output before section.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */	
	public function before_section( $page_slug ) {
		$this->info         = $this->get_info();
		$this->button_class = 'fdp-hire-us-btn';
		$this->section_id   = 'eos-dp-hire-us-section';
		$post_types   = get_post_types( array( 'public' => true ) );
		foreach( $post_types as $post_type ){
			if( !is_post_type_viewable( $post_type ) ) {
				unset( $post_types[ array_search( $post_type, $post_types ) ] );
			}
		}
		$this->post_types   = $post_types;

		?>
		<style id="fdp-hire-us-css">
		#fdp-hire-us-wrp.agreed p{display:none !important}
		#fdp-hire-us-footer a{color:#a28754}
		.fdp-hire-us-price,.fdp-hire-us-price p{color:#fff !important;font-size:2rem;text-align:center;background-color:#879099;max-width:500px;margin:-40px auto 0 auto;padding:70px;color:#fff;transform:translateY(-60px)}
		#fdp-hire-us-3cols p{background-color:#253042;color:#fff !important;font-size:1.8rem;text-align:center;padding:100px 20px;margin-top:0;min-height:230px}
		#fdp-hire-us-header{padding:100px 20px;margin-left:-50vw;margin-right:-50vw;width:100vw;left:50%;right:50%;position:relative;text-align:center}
		#fdp-hire-us-lifetime-license{background-color:#879099;padding:100px;margin-left:-50vw;margin-right:-50vw;width:100vw;left:50%;right:50%;position:relative;text-align:center}
		#fdp-hire-us-lifetime-license p{max-width:800px;color:#fff !important;font-size:2.5rem;display:inline-block;margin-<?php echo is_rtl() ? 'right' : 'left'; ?>:-200px}
		#fdp-hire-us-lifetime-license img{float:<?php echo is_rtl() ? 'right' : 'left'; ?>}
		#fdp-hire-us-lifetime-license span{color:#fff !important;font-size:2.5rem;margin-top:30px;display:block}
		#fdp-hire-us-footer{padding:64px}
		#fdp-hire-us-footer p{font-size:1.5rem}
		#fdp-hire-us,#eos-dp-wrp .button{letter-spacing:3px;border-radius:0;padding: 10px 30px;color:#fff;text-transform:uppercase;font-size: 1.7rem;border:none}
		#fdp-hire-us{background:#a28754}
		#fdp-hire-us:hover, #eos-dp-wrp .button:hover{opacity:0.7}
		.fdp-hire-us-price span{color:#fff !important;display:inline-block;margin-top:20px}
		#fdp-hire-us-agreement-wrp span{font-size:1.3rem;line-height:1.5}
		#fdp-hire-us-agreement-wrp{margin:30px 0}
		#fdp-hire-us-3cols{margin:120px 0}
		#fdp-hire-us-3cols p{position:relative;width:28%;width:calc(33.333% - 83px);display: inline-block;border-left:20px solid #fff;border-right:20px solid #fff}
		#fdp-hire-us-3cols p:before{top:-57px;margin-left:-60px;margin-right:-60px;content:" ";width:120px;height:120px;position:absolute;left:50%;right:50%;background-repeat:no-repeat;background-position:center center;background-size:cover}
		#fdp-hire-us-calculation:before{background-image:url(https://plugins.svn.wordpress.org/freesoul-deactivate-plugins/assets/hire-us-calculation.png)}
		#fdp-hire-us-checkout:before{background-image:url(https://plugins.svn.wordpress.org/freesoul-deactivate-plugins/assets/hire-us-price-on-checkout.png)}
		#fdp-hire-us-final-price:before{background-image:url(https://plugins.svn.wordpress.org/freesoul-deactivate-plugins/assets/hire-us-usd-2-euro.png)}
		</style>
		<header id="fdp-hire-us-header" style="background-color:#253042;background-image:url(https://plugins.svn.wordpress.org/freesoul-deactivate-plugins/assets/hire-us-background.jpg);background-repeat:no-repeat;background-size:cover;background-position:center center;margin-bottom:48px">
			<h1 style="text-align:center;font-size:3rem;color:#fff;text-transform:uppercase;letter-spacing:3px"><?php esc_html_e( 'Hire us', 'freesoul-deactivate-plugins' ); ?></h1>
			<div class="eos-dp-explanation" style="text-align:center">
				<p style="font-size:1.5rem;color:#fff;text-transform:uppercase;letter-spacing:3px;max-width:700px;margin:20px auto 20px auto"><?php esc_html_e( 'Hire an expert to clean up your website with Freesoul Deactivate Plugins', 'freesoul-deactivate-plugins' ); ?></p>
			</div>
		</header>
		<?php
	}

	/**
	 * Output section.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */	
	public function section( $page_slug ) {
		$default_info                  = array(
			'hire_us_product_id' 	  => 3015,
			'personal_lifetime_price' => 138,
			'personal_yearly_price'   => 48,
			'fixed_discount'          => 0,
			'percentage_discount'    => 0,
		);
		$info                          = wp_parse_args( $this->info, $default_info );
		$this->percentage_discount     = ( ( 100 - absint( $info['percentage_discount'] ) ) / 100 );
		$this->server_contribution     = $this->server_contribution();
		$this->posts_contribution      = $this->posts_contribution();
		$this->archives_contribution   = $this->archives_contribution();
		$this->theme_contribution      = $this->theme_contribution();
		$this->plugins_contribution    = $this->plugins_contribution();

		$this->personal_lifetime_price = absint( $info['personal_lifetime_price'] );
		$this->fixed_discount          = absint( $info['fixed_discount'] );

		$priceA   = $this->calculate_price();
		$price    = $priceA['manpower_price'] + $priceA['license_price'];
		$max_price = 2200;
		$price = 50 * round( $price / 50, 0 );
		$price = max( $price, 299 );
		$language = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$product_id = isset( $this->info['hire_us_product_id'] ) ? $this->info['hire_us_product_id'] : 0;
		$args     = array(
			'add-to-cart' => absint( $product_id ),
			'p'           => $this->encrypt( $this->public_key . '---' . absint( $price ) ),
			'u'           => $this->encrypt( $this->public_key . '---' . get_home_url() ),
			'l'           => $this->encrypt( $this->public_key . '---' . $language ),
			'm'           => $this->encrypt( $this->public_key . '---' . absint( $priceA['manpower_price'] ) ),
			'pcat'		  => 'plugin-cleanup'
		);
		$url      = add_query_arg( array( 't' => time(), 'quantity' => 1, 'add-to-cart' => absint( $product_id ) ), $this->hire_us_checkout_url );
		?>
	<section id="<?php echo esc_attr( $this->section_id ); ?>" style="min-width:0 !important;text-align:center" class="
							<?php
							echo esc_attr( apply_filters( 'fdp_section_class_name', 'eos-dp-section' ) );
							echo ' ' . esc_attr( $this->section_common_class );
							?>
	" data-page_slug="<?php echo esc_attr( $page_slug ); ?>">
		<?php do_action( 'eos_dp_before_wrapper' ); ?>
	  <div id="eos-dp-wrp" style="max-width:1200px;text-align:initial;margin:0 auto">

		<?php if( $price < $max_price ) { ?>
		<p class="fdp-hire-us-price">
			<?php echo wp_kses_post( sprintf( __( 'A cleanup for this website has %sa value of around %s%s USD', 'freesoul-deactivate-plugins' ), '<br />', '<br />', '<span>' . absint( $price ) ) . '</span>' ); ?>
		</p>
		<div id="fdp-hire-us-3cols">
			<p id="fdp-hire-us-calculation"><?php esc_html_e( 'This custom calculation is based on the number of your pages, posts, post types, archives, and plugins', 'freesoul-deactivate-plugins' ); ?></p>
			<p id="fdp-hire-us-checkout"><?php esc_html_e( 'The precise cost will be shown on the checkout after clicking on the button "Hire us now" at the bottom of this page', 'freesoul-deactivate-plugins' ); ?></p>
			<p id="fdp-hire-us-final-price"><?php esc_html_e( 'The final price may be different due to the conversion USD/Euro, the VAT rate, and eventual updates of our rate table', 'freesoul-deactivate-plugins' ); ?></p>
		</div>
		<p></p>
		<div id="fdp-hire-us-lifetime-license" style="font-size:2rem">
			<p>
				<img src="https://plugins.svn.wordpress.org/freesoul-deactivate-plugins/assets/freesoul-deactivate-plugins-logo.png" width="200" height="200" />
				<span><?php esc_html_e( 'You will have a LIFETIME LICENSE without additional costs!', 'freesoul-deactivate-plugins' ); ?></span>
			</p>
		</div>
		<div id="fdp-hire-us-footer">
			<form action="<?php echo esc_url( $url ); ?>" target="_fdp_plugin_cleanup" method="post">
				<input type="hidden" id="price" name="price" value="<?php echo esc_attr( $price ); ?>" />
				<input type="hidden" id="args" name="args" value="<?php echo esc_attr( wp_json_encode( $args ) ); ?>" />
				<?php
				foreach( $args as $k => $v ){
				?>
				<input type="hidden" id="<?php echo esc_attr( $k ); ?>" name="<?php echo esc_attr( $k ); ?>" value="<?php echo esc_attr( $v ); ?>" />
				<?php
				}
				?>
			<div id="fdp-hire-us-agreement-wrp">
				<input id="fdp-require-agreement" type="checkbox" required onclick="var wrp=document.getElementById('fdp-hire-us-wrp');wrp.className = this.checked ? 'agreed' : 'eos-no-events';" />
				<span><?php printf( wp_kses( __( 'I have read and agree with the %sCleanup Terms and Conditions%s, and I agree that clicking the button below, this website will send the value of the estimated price (%s USD) and the URL (%s) to the FDP server.', 'eos-pd' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ), '<a href="https://shop.freesoul-deactivate-plugins.com/cleanup-terms-and-conditions/" target="_fdp_ctac">', '</a>', esc_html( $price ), esc_url( get_home_url() ) ); ?></span>
			</div>
			<div id="fdp-hire-us-wrp" class="eos-no-events" style="text-align:center">
				<input type="submit" id="fdp-hire-us" class="button" value="<?php esc_html_e( 'Hire us now', 'freesoul-deactivate-plugins' ); ?>" />
				<p style="text-align:center"><?php esc_html_e( 'Before sending the request you must accept the agreement by clicking on the checbox above)', 'freesoul-deactivate-plugins' ); ?></p>
			</div>
			</form>
		</div>
		<?php } else { ?>
			<p class="fdp-hire-us-price">
			<?php echo wp_kses_post( sprintf( __( 'A cleanup for this website has %sa value starting from %s%s USD', 'freesoul-deactivate-plugins' ), '<br />', '<br />', '<span>' . absint( $max_price ) ) . '</span>' ); ?>
		</p>
		<div id="fdp-hire-us-wrp" style="text-align:center">
				<a href="https://freesoul-deactivate-plugins.com/contact/" target="_fdp_contactus" type="submit" id="fdp-hire-us" class="button"><?php esc_html_e( 'Contact us for a quote', 'freesoul-deactivate-plugins' ); ?></a>
			</div>		
		<?php } ?>
	  </div>
	</section>
		<?php
	}

	/**
	 * Table body.
	 *
	 * @param string $page_slug Page slug
	 * @since  1.9.0
	 */	
	public function tableBody( $page_slug ) {
		return;
	}

	/**
	 * Return array of nonces.
	 *
	 * @since  1.9.0
	 * @return array
	 */		
	public function get_nonces_map() {
		return array(
			'eos_dp_hireus' => 'eos_dp_hireus',
		);
	}

	/**
	 * Return array of prices.
	 *
	 * @since  1.9.0
	 * @return array
	 */		
	private function calculate_price() {
		$pk = max( count( $this->active_plugins ) / 2, 50 ) / 62.5;
		$manpower_price = (
		$this->percentage_discount * (
		  0.6 * $this->server_contribution
		  + 1 * $this->fixed_cost
		  + 0.6 * $this->posts_contribution
		  + 0.6 * $this->archives_contribution
		  + 0.5 * $this->theme_contribution
		  + $pk * $this->plugins_contribution
		)
		- $this->fixed_discount
		);
		$license_price  = $this->personal_lifetime_price;
		return array(
			'manpower_price' => $manpower_price,
			'license_price'  => $license_price,
		);
	}

	/**
	 * Return server contribution.
	 *
	 * @since  1.9.0
	 * @return int
	 */		
	public function server_contribution() {
		$c               = 0;
		$min_php_version = '7.3';
		if ( function_exists( 'phpversion' ) && version_compare( phpversion(), $min_php_version, '<' ) ) {
			$c += 10;
		}
		return $c;
	}

	/**
	 * Return posts contribution.
	 *
	 * @since  1.9.0
	 * @return int
	 */		
	public function posts_contribution() {
		$k_posts      = 0.1;
		$k_post_types = 0.7;
		$posts_count = $post_types_n = 0;
		foreach( $this->post_types as $post_type ){
			$posts_count_arr = wp_count_posts( $post_type );
			if( $posts_count_arr ){
				$posts_count += array_sum( (array) $posts_count_arr );
				++$post_types_n;
			}
		}
		return $k_posts * $posts_count + $k_post_types * $post_types_n;
	}

	/**
	 * Return archives contribution.
	 *
	 * @since  1.9.0
	 * @return int
	 */		
	public function archives_contribution() {
		$c             = 0;
		$k_taxs        = 0.8;
		$k_archives    = 1;
		$taxs          = get_taxonomies( array(), 'objects' );
		if ( $taxs ) {
			foreach ( $taxs as $tax ) {
				if ( '1' == $tax->public && isset( $tax->object_type ) ) {
					$c += $k_taxs * wp_count_terms( array( 'taxonomy' => $tax->name ) );
				}
			}
		}
		if ( $this->post_types && is_array( $this->post_types ) ) {
			foreach ( $this->post_types as $post_type ) {
				if ( get_post_type_archive_link( $post_type ) ) {
					$c += $k_archives;
				}
			}
		}
		return $c;
	}

	/**
	 * Return theme contribution.
	 *
	 * @since  1.9.0
	 * @return int
	 */		
	public function theme_contribution() {
		$problematic_themes = array();
		$active_theme       = wp_get_theme();
		if ( $active_theme && in_array( $active_theme, array_keys( $problematic_themes ) ) ) {
			return $problematic_themes[ $active_theme ];
		}
		return 0;
	}

	/**
	 * Return plugins contribution.
	 *
	 * @since  1.9.0
	 * @return int
	 */		
	public function plugins_contribution( $plugins_count = false ) {
		$k_plugin_updates = 0.7;
		$active_plugins   = $this->get_active_plugins();
		$plugins_count = $plugins_count ? $plugins_count : count( $active_plugins );
		$kbc = $plugins_count > 120 ? -200 : 0;
		$kbp = $plugins_count > 120 ? 11 : 8;
		$kap = $plugins_count > 120 ? 0.031 : 0.02;
		$p = $plugins_count < 30 ? 10 : ( $kbc + $kbp * $plugins_count - $kap * $plugins_count ^ 2 ) / 2;
		$plugins_contribution = ( $k_plugin_updates * count( get_plugin_updates() ) ) + $p;
		return $plugins_contribution;
	}

	/**
	 * Return encrypted string.
	 *
	 * @since  1.9.0
	 * @param  string $simple_string Non encrypted string
	 * @return string
	 */		
	public function encrypt( $simple_string ) {
		if ( function_exists( 'openssl_get_cipher_methods' ) && in_array( 'aes-128-gcm', openssl_get_cipher_methods() ) ) {
			return openssl_encrypt( $simple_string, 'BF-CBC', openssl_digest( 'fkdefkde', 'MD5', true ), 0, 'fkdefkde' );
		}
	}

	/**
	 * Return dencrypted string.
	 *
	 * @since  1.9.0
	 * @param  string $encrypted_string Encrypted string
	 * @return string
	 */		
	public function decrypt( $encrypted_string ) {
		if ( function_exists( 'openssl_get_cipher_methods' ) && in_array( 'aes-128-gcm', openssl_get_cipher_methods() ) ) {
			return ltrim( openssl_decrypt( $encrypted_string, 'BF-CBC', openssl_digest( 'fkdefkde', 'MD5', true ), 0, 'fkdefkde' ), ' ' );
		}
	}

	/**
	 * Get info.
	 *
	 * @since  1.9.0
	 * @return array
	 */		
	public function get_info() {
		delete_site_transient( 'fdp_hireus_info' );
		$info = get_site_transient( 'fdp_hireus_info' );
		if ( ! $info ) {
			$response = wp_remote_get( add_query_arg( 'public_key', $this->public_key, $this->endpoint ) );
			if ( ! is_wp_error( $response ) ) {
				$body = wp_remote_retrieve_body( $response );
				if ( $body && 'error' !== $body ) {
					$info = json_decode( $body, true );
					foreach ( $info as $k => $v ) {
						$info[ sanitize_key( $k ) ] = absint( $v );
					}
					set_site_transient( 'fdp_hireus_info', $info, 60 * 60 * 24 );
				}
			}
		} else {
			return array();
		}
		return $info;
	}
	public function footer( $button_class ) {
		require_once EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-footer.php';
	}
}
