<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Plugin Form Activate
 *
 * @package			XML for Google Merchant Center
 * @subpackage		
 * @since			3.0.0
 * 
 * @version			1.0.1 (06-03-2023)
 * @author			Maxim Glazunov
 * @link			https://icopydoc.ru/
 * @see				[ 202, 402, 412, 418, 520 ]
 * 
 * @param	array	$args
 *
 * @return			
 *
 * @depends			classes:	
 *					traits:	
 *					methods:	
 *					functions:	
 *					constants:	
 *					options:	
 *
 */

final class XFGMC_Plugin_Form_Activate {
	const INSTRUCTION_URL = 'https://icopydoc.ru/kak-aktivirovat-pro-versiyu-instruktsiya/';
	private $list_plugin_names = [ 
		'xfgmcp' => [ 'name' => 'PRO', 'code' => 'renewlicense20gp' ]
	];
	private $pref = 'xfgmcp';
	private $slug;
	private $submit_name;
	private $opt_name_order_id;
	private $opt_name_order_email;

	public function __construct( $pref = 'xfgmcp', $slug = '' ) {
		$this->pref = $pref;
		$this->slug = $slug;
		$this->submit_name = $this->get_pref() . '_submit_license_pro';
		$this->opt_name_order_id = $this->get_pref() . '_order_id';
		$this->opt_name_order_email = $this->get_pref() . '_order_email';

		$this->save_form();
		$this->the_form();
	}

	public function the_form() {
		if ( is_multisite() ) {
			$order_id = get_blog_option( get_current_blog_id(), $this->get_opt_name_order_id() );
			$order_email = get_blog_option( get_current_blog_id(), $this->get_opt_name_order_email() );
		} else {
			$order_id = get_option( $this->get_opt_name_order_id() );
			$order_email = get_option( $this->get_opt_name_order_email() );
		}
		?>
		<style>
			input.pw {
				-webkit-text-security: disc;
			}
		</style>
		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'License data', 'xml-for-google-merchant-center' );
				echo ' ' . $this->list_plugin_names[ $this->get_pref()]['name']; ?>
			</h2>
			<div class="inside">
				<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">
									<?php _e( 'Order ID', 'xml-for-google-merchant-center' ); ?>
								</th>
								<td class="overalldesc">
									<input class="pw" type="text" name="<?php echo $this->get_opt_name_order_id(); ?>"
										value="<?php echo $order_id; ?>" /><br />
									<span class="description"><a target="_blank" href="<?php
									printf( '%1$s?utm_source=%2$s&utm_medium=organic&utm_campaign=%2$s&utm_content=settings&utm_term=how-to-activate-order-id',
										self::INSTRUCTION_URL,
										$this->slug
									); ?>"><?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?></a></span>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<?php _e( 'Order Email', 'xml-for-google-merchant-center' ); ?>
								</th>
								<td class="overalldesc">
									<input type="text" name="<?php echo $this->get_opt_name_order_email(); ?>"
										value="<?php echo $order_email; ?>" /><br />
									<span class="description"><a target="_blank" href="<?php
									printf( '%1$s?utm_source=%2$s&utm_medium=organic&utm_campaign=%2$s&utm_content=settings&utm_term=how-to-activate-order-email',
										self::INSTRUCTION_URL,
										$this->slug
									); ?>"><?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?></a></span>
								</td>
							</tr>
						</tbody>
					</table>
					<input class="button-primary" type="submit" name="<?php echo $this->get_submit_name(); ?>"
						value="<?php _e( 'Update License Data', 'xml-for-google-merchant-center' ); ?>" />
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Summary of get_pref
	 * 
	 * @return string
	 */
	private function get_pref() {
		return $this->pref;
	}

	/**
	 * Summary of get_submit_name
	 * 
	 * @return string
	 */
	private function get_submit_name() {
		return $this->submit_name;
	}

	/**
	 * Summary of get_opt_name_order_id
	 * 
	 * @return string
	 */
	private function get_opt_name_order_id() {
		return $this->opt_name_order_id;
	}

	/**
	 * Summary of get_opt_name_order_email
	 * 
	 * @return string
	 */
	private function get_opt_name_order_email() {
		return $this->opt_name_order_email;
	}

	/**
	 * Summary of save_form
	 * 
	 * @return void
	 */
	private function save_form() {
		if ( isset( $_REQUEST[ $this->get_submit_name()] ) ) {
			if ( is_multisite() ) {
				if ( isset( $_POST[ $this->get_opt_name_order_id()] ) ) {
					update_blog_option( get_current_blog_id(), $this->get_opt_name_order_id(), sanitize_text_field( $_POST[ $this->get_opt_name_order_id()] ) );
				}
				if ( isset( $_POST[ $this->get_opt_name_order_email()] ) ) {
					update_blog_option( get_current_blog_id(), $this->get_opt_name_order_email(), sanitize_text_field( $_POST[ $this->get_opt_name_order_email()] ) );
				}
			} else {
				if ( isset( $_POST[ $this->get_opt_name_order_id()] ) ) {
					update_option( $this->get_opt_name_order_id(), sanitize_text_field( $_POST[ $this->get_opt_name_order_id()] ) );
				}
				if ( isset( $_POST[ $this->get_opt_name_order_email()] ) ) {
					update_option( $this->get_opt_name_order_email(), sanitize_text_field( $_POST[ $this->get_opt_name_order_email()] ) );
				}
			}
			wp_clean_plugins_cache();
			wp_clean_update_cache();
			add_filter( 'pre_site_transient_update_plugins', '__return_null' );
			wp_update_plugins();
			remove_filter( 'pre_site_transient_update_plugins', '__return_null' );
			print '<div class="notice notice-success is-dismissible"><p>' . __( 'License data has been updated', 'xml-for-google-merchant-center' ) . '. <a href="javascript:location.reload(true)">' . __( 'Refresh this page', 'xml-for-google-merchant-center' ) . '</a>.</p></div>';
			wp_update_plugins();
		}
	}
}