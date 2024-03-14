<?php if (!defined('ABSPATH')) {exit;}
/**
* Plugin Settings Page
*
* @link			https://icopydoc.ru/
* @since		2.1.0
*/

class GUPFW_Settings_Page {
	private $feed_id;
	private $feedback;

	public function __construct() {
		$this->feedback = new GUPFW_Feedback();

		$this->init_hooks(); // подключим хуки
		$this->listen_submit();

		$this->get_html_form();	
	}

	public function get_html_form() { ?>
		<div class="wrap">
  			<h1>Gift upon purchase for WooCommerce</h1>
			<p>(<a href="https://icopydoc.ru/nastrojka-plagina-gift-upon-purchase-for-woocommerce/?utm_source=gift-upon-purchase-for-woocommerce&utm_medium=organic&utm_campaign=in-plugin-gift-upon-purchase-for-woocommerce&utm_content=settings&utm_term=main-instruction" target="_blank"><?php _e('Plugin documentation', 'gift-upon-purchase-for-woocommerce'); ?></a>)</p>
			<div id="poststuff">

				<div id="post-body" class="columns-2">

					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<?php $this->feedback->get_block_support_project(); ?>
						</div>

						<?php do_action('gupfw_between_container_1'); ?>	

						<?php $this->feedback->get_form(); ?>
					</div><!-- /postbox-container-1 -->

					<div id="postbox-container-2" class="postbox-container">
						<div class="meta-box-sortables"><?php 
							if (isset($_GET['tab'])) {$tab = $_GET['tab'];} else {$tab = 'main_tab';}
							echo $this->get_html_tabs($tab); ?>

							<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
								<?php do_action('gupfw_prepend_form_container_2'); ?>
								<?php switch ($tab) : 
									case 'main_tab' : ?>
										<?php $this->get_html_main_settings(); ?>
										<?php break; ?>
									<?php case 'appearance_tab' : ?>
									<?php $this->get_html_appearance_tab(); ?>
										<?php break; ?>
								<?php endswitch; ?>

								<?php do_action('gupfw_after_optional_elemet_block'); ?>
								<div class="postbox">
									<div class="inside">
										<table class="form-table"><tbody>
											<tr>
												<th scope="row"><label for="button-primary"></label></th>
												<td class="overalldesc"><?php wp_nonce_field('gupfw_nonce_action', 'gupfw_nonce_field'); ?><input id="button-primary" class="button-primary" type="submit" name="gupfw_submit_action" value="<?php _e('Save', 'gift-upon-purchase-for-woocommerce'); ?>"/><br />
												<span class="description"><small><?php _e('Click to save the settings', 'gift-upon-purchase-for-woocommerce'); ?><small></span></td>
											</tr>
										</tbody></table>
									</div>
								</div>
							</form>
						</div>
					</div><!-- /postbox-container-2 -->

				</div>
			</div><!-- /poststuff -->
			<?php $this->get_html_icp_banners(); ?>
			<?php $this->get_html_my_plugins_list(); ?>
		</div><?php // end get_html_form();
	}

	public function get_html_tabs($current = 'main_tab') {
		$tabs = array(
			'main_tab' 			=> __('Main settings', 'gift-upon-purchase-for-woocommerce'),
			'appearance_tab' 	=> __('Appearance', 'gift-upon-purchase-for-woocommerce')		
		);
		
		$html = '<div class="nav-tab-wrapper" style="margin-bottom: 10px;">';
			foreach ($tabs as $tab => $name) {
				if ($tab === $current) {
					$class = ' nav-tab-active';
				} else {
					$class = ''; 
				}
				if (isset($_GET['feed_id'])) {
					$nf = '&feed_id='.sanitize_text_field($_GET['feed_id']);
				} else {
					$nf = '';
				}
				$html .= sprintf('<a class="nav-tab%1$s" href="?page=gupfw-settings&tab=%2$s%3$s">%4$s</a>',$class, $tab, $nf, $name);
			}
		$html .= '</div>';

		return $html;
	} // end get_html_tabs();

	public function get_html_main_settings() { 	
		$gupfw_tgfp_in_cart_status = gupfw_optionGET('gupfw_tgfp_in_cart_status');
		$gupfw_tgfp_in_cart_content = stripslashes(htmlspecialchars(gupfw_optionGET('gupfw_tgfp_in_cart_content'))); 
		$gupfw_tgfp_in_cart_color = gupfw_optionGET('gupfw_tgfp_in_cart_color');
		$gupfw_tgfp_in_cart_fsize = gupfw_optionGET('gupfw_tgfp_in_cart_fsize');
	   
		$gupfw_displaying_accept_remove_button = gupfw_optionGET('gupfw_displaying_accept_remove_button');
		$gupfw_tgfp_remove_gift_in_cart = gupfw_optionGET('gupfw_tgfp_remove_gift_in_cart');
		$gupfw_tgfp_accept_gift_in_cart = gupfw_optionGET('gupfw_tgfp_accept_gift_in_cart');
	   
		$gupfw_tgfp_in_product_status = gupfw_optionGET('gupfw_tgfp_in_product_status');
		$gupfw_tgfp_in_product_content = stripslashes(htmlspecialchars(gupfw_optionGET('gupfw_tgfp_in_product_content'))); 
		$gupfw_tgfp_in_product_color = gupfw_optionGET('gupfw_tgfp_in_product_color');
		$gupfw_tgfp_in_product_fsize = gupfw_optionGET('gupfw_tgfp_in_product_fsize'); 
	   
		$gupfw_gift_for_any_product_arr = gupfw_optionGET('gupfw_gift_for_any_product_arr');
		$gupfw_gift_for_any_product_in_cart_content = stripslashes(htmlspecialchars(gupfw_optionGET('gupfw_gift_for_any_product_in_cart_content')));
		$gupfw_hide_or_remove = htmlspecialchars(gupfw_optionGET('gupfw_hide_or_remove'));
		$gupfw_cart_total_price = gupfw_optionGET('gupfw_cart_total_price');
		$gupfw_rules_for_cart_price = gupfw_optionGET('gupfw_rules_for_cart_price');
		$gupfw_whose_price_exceeds = gupfw_optionGET('gupfw_whose_price_exceeds');	
		?>
		<div class="postbox">
			<h2 class="hndle"><?php _e('Gift for any product', 'gift-upon-purchase-for-woocommerce'); ?></h2>
			<div class="inside">
				<table class="form-table"><tbody>
					<tr class="gupfw_tr">
						<th scope="row"><label for="gupfw_gift_for_any_product_arr"><?php _e('Product selection', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><?php echo gupfw_select2($gupfw_gift_for_any_product_arr); ?><br />
						<span class="description"><small><?php _e('Select the products that the user will receive as a gift', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_gift_for_any_product_in_cart_content"><?php _e('Text content in cart', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="text" name="gupfw_gift_for_any_product_in_cart_content" id="gupfw_gift_for_any_product_in_cart_content" value="<?php echo $gupfw_gift_for_any_product_in_cart_content; ?>" /><br />
						<span class="description"><small><?php _e('Text in cart under product name', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_hide_or_remove"><?php _e('If the contents of the cart no longer meet the conditions', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc">
							<select name="gupfw_hide_or_remove" id="gupfw_hide_or_remove">						
								<option value="full_cost" <?php selected($gupfw_hide_or_remove, 'full_cost'); ?>><?php _e('Full price for the gift', 'gift-upon-purchase-for-woocommerce'); ?></option>
								<option value="remove" <?php selected($gupfw_hide_or_remove, 'remove'); ?>><?php _e('Remove Gift', 'gift-upon-purchase-for-woocommerce'); ?></option>
							</select><br />
						</td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="postbox">
			<h2 class="hndle"><?php _e('Gift Terms', 'gift-upon-purchase-for-woocommerce'); ?></h2>
			<div class="inside">
				<table class="form-table"><tbody>
					<tr class="gupfw_tr">
						<th scope="row"><label for="gupfw_cart_total_price"><?php _e('Cart', 'gift-upon-purchase-for-woocommerce'); ?></label><br/>
							<select name="gupfw_rules_for_cart_price" id="gupfw_rules_for_cart_price">						
								<option value="total" <?php selected($gupfw_rules_for_cart_price, 'total'); ?>><?php _e('total price', 'gift-upon-purchase-for-woocommerce'); ?></option>
								<option value="subtotal" <?php selected($gupfw_rules_for_cart_price, 'subtotal'); ?>><?php _e('subtotal price', 'gift-upon-purchase-for-woocommerce'); ?></option>
							</select>		
						</th>
							<td class="overalldesc"><input type="number" min="0" step="0.01" name="gupfw_cart_total_price" id="gupfw_cart_total_price" value="<?php echo $gupfw_cart_total_price; ?>" /><br />
							<span class="description"><small><?php _e('Numbers only', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_whose_price_exceeds"><?php _e('Cart contains at least one product whose price exceeds', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="number" min="0" step="0.01" name="gupfw_whose_price_exceeds" id="gupfw_whose_price_exceeds" value="<?php echo $gupfw_whose_price_exceeds; ?>" /><br />
						<span class="description"><small><?php _e('Numbers only', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_whose_price_exceeds"><?php _e('Gift only on certain days', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><?php 
							gupfw_woocommerce_wp_select_multiple(array(
								'id' => 'gupfw_days_of_the_week',
								'wrapper_class' => 'show_if_simple',
								'options' => array(
									'Monday' => __('Monday', 'gift-upon-purchase-for-woocommerce'),
									'Tuesday' => __('Tuesday', 'gift-upon-purchase-for-woocommerce'),
									'Wednesday' => __('Wednesday', 'gift-upon-purchase-for-woocommerce'),
									'Thursday' => __('Thursday', 'gift-upon-purchase-for-woocommerce'),
									'Friday' => __('Friday', 'gift-upon-purchase-for-woocommerce'),
									'Saturday' => __('Saturday', 'gift-upon-purchase-for-woocommerce'),
									'Sunday' => __('Sunday', 'gift-upon-purchase-for-woocommerce'),
								)
							), true);	
						?></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_whose_price_exceeds"><?php _e('Gift only at certain hours', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><?php 
							$res_arr = array(); for ($i = 1; $i<25; $i++) {$res_arr[$i] = $i;}
							gupfw_woocommerce_wp_select_multiple(array(
								'id' => 'gupfw_days_of_the_hours',
								'wrapper_class' => 'show_if_simple',
								'options' => $res_arr
							), true);	
						?></td>
					</tr>
				</tbody></table>
			</div>
		</div><?php
	} // end get_html_main_settings();

	public function get_html_appearance_tab() { 	
		$gupfw_tgfp_in_category_status = gupfw_optionGET('gupfw_tgfp_in_category_status');
		$gupfw_tgfp_in_category_content = stripslashes(htmlspecialchars(gupfw_optionGET('gupfw_tgfp_in_category_content'))); 
		$gupfw_tgfp_in_category_color = gupfw_optionGET('gupfw_tgfp_in_category_color');
		$gupfw_tgfp_in_category_fsize = gupfw_optionGET('gupfw_tgfp_in_category_fsize');
		$gupfw_hook_name_for_gift_in_category_info = gupfw_optionGET('gupfw_hook_name_for_gift_in_category_info');

		$gupfw_tgfp_in_cart_status = gupfw_optionGET('gupfw_tgfp_in_cart_status');
		$gupfw_tgfp_in_cart_content = stripslashes(htmlspecialchars(gupfw_optionGET('gupfw_tgfp_in_cart_content'))); 
		$gupfw_tgfp_in_cart_color = gupfw_optionGET('gupfw_tgfp_in_cart_color');
		$gupfw_tgfp_in_cart_fsize = gupfw_optionGET('gupfw_tgfp_in_cart_fsize');
	   
		$gupfw_displaying_accept_remove_button = gupfw_optionGET('gupfw_displaying_accept_remove_button');
		$gupfw_tgfp_remove_gift_in_cart = gupfw_optionGET('gupfw_tgfp_remove_gift_in_cart');
		$gupfw_tgfp_accept_gift_in_cart = gupfw_optionGET('gupfw_tgfp_accept_gift_in_cart');
	   
		$gupfw_tgfp_in_product_status = gupfw_optionGET('gupfw_tgfp_in_product_status');
		$gupfw_tgfp_in_product_content = stripslashes(htmlspecialchars(gupfw_optionGET('gupfw_tgfp_in_product_content'))); 
		$gupfw_tgfp_in_product_color = gupfw_optionGET('gupfw_tgfp_in_product_color');
		$gupfw_tgfp_in_product_fsize = gupfw_optionGET('gupfw_tgfp_in_product_fsize'); 

		$gupfw_hook_name_for_gift_info = gupfw_optionGET('gupfw_hook_name_for_gift_info'); 
		?>
		<div class="postbox">
			<h2 class="hndle"><?php _e('Text "gift for purchase" in the category products', 'gift-upon-purchase-for-woocommerce'); ?></h2>
			<div class="inside">
				<table class="form-table"><tbody>
					<tr class="gupfw_tr">
						<th scope="row"><label for="gupfw_tgfp_in_category_status"><?php _e('Text status', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc">
							<select name="gupfw_tgfp_in_category_status" id="gupfw_tgfp_in_category_status">
								<option value="show" <?php selected($gupfw_tgfp_in_category_status, 'show'); ?>><?php _e('Show', 'gift-upon-purchase-for-woocommerce'); ?></option>
								<option value="hide" <?php selected($gupfw_tgfp_in_category_status, 'hide'); ?>><?php _e('Hide', 'gift-upon-purchase-for-woocommerce'); ?></option>
							</select><br />
							<span class="description"><small><?php _e('Gift Information', 'gift-upon-purchase-for-woocommerce'); ?></small></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_category_content"><?php _e('Text content', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="text" name="gupfw_tgfp_in_category_content" id="gupfw_tgfp_in_category_content" value="<?php echo $gupfw_tgfp_in_category_content; ?>" /><br />
						<span class="description"><small><?php _e('Text "gift for purchase" in the category products', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_category_color"><?php _e('Text color', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input class="iris_color" name="gupfw_tgfp_in_category_color" id="gupfw_tgfp_in_category_color" type="text" value="<?php echo $gupfw_tgfp_in_category_color; ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_category_fsize"><?php _e('Font size', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="number" min="6" name="gupfw_tgfp_in_category_fsize" id="gupfw_tgfp_in_category_fsize" value="<?php echo $gupfw_tgfp_in_category_fsize; ?>" /><br />
						<span class="description"><small><?php _e('Integer', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_hook_name_for_gift_in_category_info"><?php _e('Hook name', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc">
							<select name="gupfw_hook_name_for_gift_in_category_info" id="gupfw_hook_name_for_gift_in_category_info">
								<option value="woocommerce_shop_loop_item_title" <?php selected($gupfw_hook_name_for_gift_in_category_info, 'woocommerce_shop_loop_item_title'); ?>>woocommerce_shop_loop_item_title</option>
								<option value="woocommerce_after_shop_loop_item_title" <?php selected($gupfw_hook_name_for_gift_in_category_info, 'woocommerce_after_shop_loop_item_title'); ?>>woocommerce_after_shop_loop_item_title</option>
								<option value="woocommerce_after_shop_loop_item" <?php selected($gupfw_hook_name_for_gift_in_category_info, 'woocommerce_after_shop_loop_item'); ?>>woocommerce_after_shop_loop_item</option>
							</select><br />
						<span class="description"><small><?php _e('The name of the hook to call in the template of your theme', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="postbox">
			<h2 class="hndle"><?php _e('Text "gift for purchase" in the cart', 'gift-upon-purchase-for-woocommerce'); ?></h2>
			<div class="inside">
				<table class="form-table"><tbody>
					<tr class="gupfw_tr">
						<th scope="row"><label for="gupfw_tgfp_in_cart_status"><?php _e('Text status', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc">
							<select name="gupfw_tgfp_in_cart_status" id="gupfw_tgfp_in_cart_status">
								<option value="show" <?php selected($gupfw_tgfp_in_cart_status, 'show'); ?>><?php _e('Show', 'gift-upon-purchase-for-woocommerce'); ?></option>
								<option value="hide" <?php selected($gupfw_tgfp_in_cart_status, 'hide'); ?>><?php _e('Hide', 'gift-upon-purchase-for-woocommerce'); ?></option>
							</select><br />
							<span class="description"><small><?php _e('Gift Information', 'gift-upon-purchase-for-woocommerce'); ?></small></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_cart_content"><?php _e('Text content', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="text" name="gupfw_tgfp_in_cart_content" id="gupfw_tgfp_in_cart_content" value="<?php echo $gupfw_tgfp_in_cart_content; ?>" /><br />
						<span class="description"><small><?php _e('Text in cart under product name', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_cart_color"><?php _e('Text color', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input class="iris_color" name="gupfw_tgfp_in_cart_color" id="gupfw_tgfp_in_cart_color" type="text" value="<?php echo $gupfw_tgfp_in_cart_color; ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_cart_fsize"><?php _e('Font size', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="number" min="6" name="gupfw_tgfp_in_cart_fsize" id="gupfw_tgfp_in_cart_fsize" value="<?php echo $gupfw_tgfp_in_cart_fsize; ?>" /><br />
						<span class="description"><small><?php _e('Integer', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="postbox">
			<h2 class="hndle"><?php _e('Text on buttons in the cart', 'gift-upon-purchase-for-woocommerce'); ?></h2>
			<div class="inside">
				<table class="form-table"><tbody>
					<tr class="gupfw_tr">
						<th scope="row"><label for="gupfw_displaying_accept_remove_button"><?php _e('Button "Remove/accept gifts" in the cart', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc">
							<select name="gupfw_displaying_accept_remove_button" id="gupfw_displaying_accept_remove_button">
								<option value="show" <?php selected($gupfw_displaying_accept_remove_button, 'show'); ?>><?php _e('Show', 'gift-upon-purchase-for-woocommerce'); ?></option>
								<option value="hide" <?php selected($gupfw_displaying_accept_remove_button, 'hide'); ?>><?php _e('Hide', 'gift-upon-purchase-for-woocommerce'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_remove_gift_in_cart"><?php _e('Text content', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="text" name="gupfw_tgfp_remove_gift_in_cart" id="gupfw_tgfp_remove_gift_in_cart" value="<?php echo $gupfw_tgfp_remove_gift_in_cart; ?>" /><br />
						<span class="description"><small><?php _e('Text in cart on button', 'gift-upon-purchase-for-woocommerce'); ?> "<?php _e('Remove gifts', 'gift-upon-purchase-for-woocommerce'); ?>"</small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_accept_gift_in_cart"><?php _e('Text content', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="text" name="gupfw_tgfp_accept_gift_in_cart" id="gupfw_tgfp_accept_gift_in_cart" value="<?php echo $gupfw_tgfp_accept_gift_in_cart; ?>" /><br />
						<span class="description"><small><?php _e('Text in cart on button', 'gift-upon-purchase-for-woocommerce'); ?> "<?php _e('Accept gifts', 'gift-upon-purchase-for-woocommerce'); ?>"</small></span></td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="postbox">
			<h2 class="hndle"><?php _e('Text "gift for purchase" in the product card', 'gift-upon-purchase-for-woocommerce'); ?></h2>
			<div class="inside">
				<table class="form-table"><tbody>
					<tr class="gupfw_tr">
						<th scope="row"><label for="gupfw_tgfp_in_product_status"><?php _e('Text status', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc">
							<select name="gupfw_tgfp_in_product_status" id="gupfw_tgfp_in_product_status">
								<option value="show" <?php selected($gupfw_tgfp_in_product_status, 'show'); ?>><?php _e('Show', 'gift-upon-purchase-for-woocommerce'); ?></option>
								<option value="hide" <?php selected($gupfw_tgfp_in_product_status, 'hide'); ?>><?php _e('Hide', 'gift-upon-purchase-for-woocommerce'); ?></option>
							</select><br />
							<span class="description"><small><?php _e('Gift Information', 'gift-upon-purchase-for-woocommerce'); ?></small></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_product_content"><?php _e('Text content', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="text" name="gupfw_tgfp_in_product_content" id="gupfw_tgfp_in_product_content" value="<?php echo $gupfw_tgfp_in_product_content; ?>" /><br />
						<span class="description"><small><?php _e('The text in the product card before the meta product', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_product_color"><?php _e('Text color', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input class="iris_color" name="gupfw_tgfp_in_product_color" id="gupfw_tgfp_in_product_color" type="text" value="<?php echo $gupfw_tgfp_in_product_color; ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_tgfp_in_product_fsize"><?php _e('Font size', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc"><input type="number" min="6" name="gupfw_tgfp_in_product_fsize" id="gupfw_tgfp_in_product_fsize" value="<?php echo $gupfw_tgfp_in_product_fsize; ?>" /><br />
						<span class="description"><small><?php _e('Integer', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
					</tr>
					<tr>
						<th scope="row"><label for="gupfw_hook_name_for_gift_info"><?php _e('Hook name', 'gift-upon-purchase-for-woocommerce'); ?></label></th>
						<td class="overalldesc">
							<select name="gupfw_hook_name_for_gift_info" id="gupfw_hook_name_for_gift_info">
								<option value="woocommerce_product_meta_start" <?php selected($gupfw_hook_name_for_gift_info, 'woocommerce_product_meta_start'); ?>>woocommerce_product_meta_start</option>
								<option value="woocommerce_product_meta_end" <?php selected($gupfw_hook_name_for_gift_info, 'woocommerce_product_meta_end'); ?>>woocommerce_product_meta_end</option>
								<option value="woocommerce_before_add_to_cart_button" <?php selected($gupfw_hook_name_for_gift_info, 'woocommerce_before_add_to_cart_button'); ?>>woocommerce_before_add_to_cart_button</option>
								<option value="woocommerce_before_quantity_input_field" <?php selected($gupfw_hook_name_for_gift_info, 'woocommerce_before_quantity_input_field'); ?>>woocommerce_before_quantity_input_field</option>
								<option value="woocommerce_after_quantity_input_field" <?php selected($gupfw_hook_name_for_gift_info, 'woocommerce_after_quantity_input_field'); ?>>woocommerce_after_quantity_input_field</option>
							</select><br />
							<span class="description"><small><?php _e('The name of the hook to call in the template of your theme', 'gift-upon-purchase-for-woocommerce'); ?></small></span></td>
						</td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<!--div class="postbox">
			<h2 class="hndle"><?php _e('Other', 'gift-upon-purchase-for-woocommerce'); ?></h2>
			<div class="inside">
				<table class="form-table"><tbody>

				</tbody></table>
			</div>
		</div--><?php
	} // end get_html_appearance_tab();

	public function get_html_icp_banners() { ?>
		<div id="icp_slides" class="clear">
			<div class="icp_wrap">
				<input type="radio" name="icp_slides" id="icp_point1">
				<input type="radio" name="icp_slides" id="icp_point2">
				<input type="radio" name="icp_slides" id="icp_point3">
				<input type="radio" name="icp_slides" id="icp_point4">
				<input type="radio" name="icp_slides" id="icp_point5" checked>
				<input type="radio" name="icp_slides" id="icp_point6">
				<input type="radio" name="icp_slides" id="icp_point7">
				<div class="icp_slider">
					<div class="icp_slides icp_img1"><a href="//wordpress.org/plugins/yml-for-yandex-market/" target="_blank"></a></div>
					<div class="icp_slides icp_img2"><a href="//wordpress.org/plugins/import-products-to-ok-ru/" target="_blank"></a></div>
					<div class="icp_slides icp_img3"><a href="//wordpress.org/plugins/xml-for-google-merchant-center/" target="_blank"></a></div>
					<div class="icp_slides icp_img4"><a href="//wordpress.org/plugins/gift-upon-purchase-for-woocommerce/" target="_blank"></a></div>
					<div class="icp_slides icp_img5"><a href="//wordpress.org/plugins/xml-for-avito/" target="_blank"></a></div>
					<div class="icp_slides icp_img6"><a href="//wordpress.org/plugins/xml-for-o-yandex/" target="_blank"></a></div>
					<div class="icp_slides icp_img7"><a href="//wordpress.org/plugins/import-from-yml/" target="_blank"></a></div>
				</div>
				<div class="icp_control">
					<label for="icp_point1"></label>
					<label for="icp_point2"></label>
					<label for="icp_point3"></label>
					<label for="icp_point4"></label>
					<label for="icp_point5"></label>
					<label for="icp_point6"></label>
					<label for="icp_point7"></label>
				</div>
			</div> 
		</div><?php 
	} // end get_html_icp_banners()

	public function get_html_my_plugins_list() { ?>
		<div class="metabox-holder">
			<div class="postbox">
				<h2 class="hndle"><?php _e('My plugins that may interest you', 'gift-upon-purchase-for-woocommerce'); ?></h2>
				<div class="inside">
					<p><span class="gupfw_bold">XML for Google Merchant Center</span> - <?php _e('Сreates a XML-feed to upload to Google Merchant Center', 'gift-upon-purchase-for-woocommerce'); ?>. <a href="https://wordpress.org/plugins/xml-for-google-merchant-center/" target="_blank"><?php _e('Read more', 'gift-upon-purchase-for-woocommerce'); ?></a>.</p> 
					<p><span class="gupfw_bold">YML for Yandex Market</span> - <?php _e('Сreates a YML-feed for importing your products to Yandex Market', 'gift-upon-purchase-for-woocommerce'); ?>. <a href="https://wordpress.org/plugins/yml-for-yandex-market/" target="_blank"><?php _e('Read more', 'gift-upon-purchase-for-woocommerce'); ?></a>.</p>
					<p><span class="gupfw_bold">Import from YML</span> - <?php _e('Imports products from YML to your shop', 'gift-upon-purchase-for-woocommerce'); ?>. <a href="https://wordpress.org/plugins/import-from-yml/" target="_blank"><?php _e('Read more', 'gift-upon-purchase-for-woocommerce'); ?></a>.</p>
					<p><span class="gupfw_bold">XML for Hotline</span> - <?php _e('Сreates a XML-feed for importing your products to Hotline', 'gift-upon-purchase-for-woocommerce'); ?>. <a href="https://wordpress.org/plugins/xml-for-hotline/" target="_blank"><?php _e('Read more', 'gift-upon-purchase-for-woocommerce'); ?></a>.</p>
					<p><span class="gupfw_bold">Gift upon purchase for WooCommerce</span> - <?php _e('This plugin will add a marketing tool that will allow you to give gifts to the buyer upon purchase', 'gift-upon-purchase-for-woocommerce'); ?>. <a href="https://wordpress.org/plugins/gift-upon-purchase-for-woocommerce/" target="_blank"><?php _e('Read more', 'gift-upon-purchase-for-woocommerce'); ?></a>.</p>
					<p><span class="gupfw_bold">Import products to ok.ru</span> - <?php _e('With this plugin, you can import products to your group on ok.ru', 'gift-upon-purchase-for-woocommerce'); ?>. <a href="https://wordpress.org/plugins/import-products-to-ok-ru/" target="_blank"><?php _e('Read more', 'gift-upon-purchase-for-woocommerce'); ?></a>.</p>
					<p><span class="gupfw_bold">XML for Avito</span> - <?php _e('Сreates a XML-feed for importing your products to', 'gift-upon-purchase-for-woocommerce'); ?> Avito. <a href="https://wordpress.org/plugins/xml-for-avito/" target="_blank"><?php _e('Read more', 'gift-upon-purchase-for-woocommerce'); ?></a>.</p>
					<p><span class="gupfw_bold">XML for O.Yandex (Яндекс Объявления)</span> - <?php _e('Сreates a XML-feed for importing your products to', 'gift-upon-purchase-for-woocommerce'); ?> Яндекс.Объявления. <a href="https://wordpress.org/plugins/xml-for-o-yandex/" target="_blank"><?php _e('Read more', 'gift-upon-purchase-for-woocommerce'); ?></a>.</p>
				</div>
			</div>
		</div><?php
	} // end get_html_my_plugins_list()

	public function admin_head_css_func() {
		/* печатаем css в шапке админки */
		print '<style>/* Best Rating & Pageviews */
			.metabox-holder .postbox-container .empty-container {height: auto !important;}
			.icp_img1 {background-image: url('. GUPFW_PLUGIN_DIR_URL .'img/sl1.jpg);}
			.icp_img2 {background-image: url('. GUPFW_PLUGIN_DIR_URL .'img/sl2.jpg);}
			.icp_img3 {background-image: url('. GUPFW_PLUGIN_DIR_URL .'img/sl3.jpg);}
			.icp_img4 {background-image: url('. GUPFW_PLUGIN_DIR_URL .'img/sl4.jpg);}
			.icp_img5 {background-image: url('. GUPFW_PLUGIN_DIR_URL .'img/sl5.jpg);}
			.icp_img6 {background-image: url('. GUPFW_PLUGIN_DIR_URL .'img/sl6.jpg);}
			.icp_img7 {background-image: url('. GUPFW_PLUGIN_DIR_URL .'img/sl7.jpg);}
		</style>';
	}

	private function init_hooks() {
		// наш класс, вероятно, вызывается во время срабатывания хука admin_menu.
		// admin_init - следующий в очереди срабатывания, хуки раньше admin_menu нет смысла вешать
		add_action('admin_init', array($this, 'listen_submits'), 10);
		add_action('admin_print_footer_scripts', array($this, 'admin_head_css_func'));
	}

	private function listen_submit() { 
		if (isset($_REQUEST['gupfw_submit_action'])) {
			if (!empty($_POST) && check_admin_referer('gupfw_nonce_action', 'gupfw_nonce_field')) {
				do_action('gupfw_prepend_submit_action');
				
				if (!isset($_GET['tab']) || ($_GET['tab'] == 'main_tab')) {	
					if (isset($_POST['gupfw_gift_for_any_product_arr'])) {
						gupfw_optionUPD('gupfw_gift_for_any_product_arr', $_POST['gupfw_gift_for_any_product_arr']); 
					} else {
						gupfw_optionUPD('gupfw_gift_for_any_product_arr', array());
					}
					if (isset($_POST['gupfw_gift_for_any_product_in_cart_content'])) {
						gupfw_optionUPD('gupfw_gift_for_any_product_in_cart_content', sanitize_text_field($_POST['gupfw_gift_for_any_product_in_cart_content']));
					}
					if (isset($_POST['gupfw_hide_or_remove'])) {
						gupfw_optionUPD('gupfw_hide_or_remove', sanitize_text_field($_POST['gupfw_hide_or_remove']));
					}
					if (isset($_POST['gupfw_cart_total_price'])) {
						gupfw_optionUPD('gupfw_cart_total_price', sanitize_text_field($_POST['gupfw_cart_total_price'])); 
					}
					if (isset($_POST['gupfw_rules_for_cart_price'])) {
						gupfw_optionUPD('gupfw_rules_for_cart_price', sanitize_text_field($_POST['gupfw_rules_for_cart_price'])); 
					}
					if (isset($_POST['gupfw_whose_price_exceeds'])) {
						gupfw_optionUPD('gupfw_whose_price_exceeds', $_POST['gupfw_whose_price_exceeds']); 
					}
					if (isset($_POST['gupfw_days_of_the_week'])) {
						gupfw_optionUPD('gupfw_days_of_the_week', $_POST['gupfw_days_of_the_week']); 
					} else {
						gupfw_optionUPD('gupfw_days_of_the_week', array());
					}
					if (isset($_POST['gupfw_days_of_the_hours'])) {
						gupfw_optionUPD('gupfw_days_of_the_hours', $_POST['gupfw_days_of_the_hours']); 
					} else {
						gupfw_optionUPD('gupfw_days_of_the_hours', array());
					}
				}

				if (isset($_GET['tab']) && ($_GET['tab'] == 'appearance_tab')) {	
					if (isset($_POST['gupfw_tgfp_in_category_status'])) {
						gupfw_optionUPD('gupfw_tgfp_in_category_status', sanitize_text_field($_POST['gupfw_tgfp_in_category_status']));
					}
					if (isset($_POST['gupfw_tgfp_in_category_content'])) {
						gupfw_optionUPD('gupfw_tgfp_in_category_content', sanitize_text_field($_POST['gupfw_tgfp_in_category_content']));
					}
					if (isset($_POST['gupfw_tgfp_in_category_color'])) {
						new GUPFW_Error_Log($_POST['gupfw_tgfp_in_category_color']);
						gupfw_optionUPD('gupfw_tgfp_in_category_color', $_POST['gupfw_tgfp_in_category_color']);
					}
					if (isset($_POST['gupfw_tgfp_in_category_fsize'])) {
						gupfw_optionUPD('gupfw_tgfp_in_category_fsize', sanitize_text_field($_POST['gupfw_tgfp_in_category_fsize']));
					}
					if (isset($_POST['gupfw_hook_name_for_gift_in_category_info'])) {
						gupfw_optionUPD('gupfw_hook_name_for_gift_in_category_info', sanitize_text_field($_POST['gupfw_hook_name_for_gift_in_category_info']));
					}

					if (isset($_POST['gupfw_tgfp_in_cart_status'])) {
						gupfw_optionUPD('gupfw_tgfp_in_cart_status', sanitize_text_field($_POST['gupfw_tgfp_in_cart_status']));
					}
					if (isset($_POST['gupfw_tgfp_in_cart_content'])) {
						gupfw_optionUPD('gupfw_tgfp_in_cart_content', sanitize_text_field($_POST['gupfw_tgfp_in_cart_content']));
					}
					if (isset($_POST['gupfw_tgfp_in_cart_color'])) {
						new GUPFW_Error_Log($_POST['gupfw_tgfp_in_cart_color']);
						gupfw_optionUPD('gupfw_tgfp_in_cart_color', $_POST['gupfw_tgfp_in_cart_color']);
					}
					if (isset($_POST['gupfw_tgfp_in_cart_fsize'])) {
						gupfw_optionUPD('gupfw_tgfp_in_cart_fsize', sanitize_text_field($_POST['gupfw_tgfp_in_cart_fsize']));
					}

					if (isset($_POST['gupfw_displaying_accept_remove_button'])) {
						gupfw_optionUPD('gupfw_displaying_accept_remove_button', sanitize_text_field($_POST['gupfw_displaying_accept_remove_button']));
					}
					if (isset($_POST['gupfw_tgfp_remove_gift_in_cart'])) {
						gupfw_optionUPD('gupfw_tgfp_remove_gift_in_cart', sanitize_text_field($_POST['gupfw_tgfp_remove_gift_in_cart']));
					}
					if (isset($_POST['gupfw_tgfp_accept_gift_in_cart'])) {
						gupfw_optionUPD('gupfw_tgfp_accept_gift_in_cart', sanitize_text_field($_POST['gupfw_tgfp_accept_gift_in_cart']));
					}
					if (isset($_POST['gupfw_tgfp_in_product_status'])) {
						gupfw_optionUPD('gupfw_tgfp_in_product_status', sanitize_text_field($_POST['gupfw_tgfp_in_product_status']));
					}
					if (isset($_POST['gupfw_tgfp_in_product_content'])) {
						gupfw_optionUPD('gupfw_tgfp_in_product_content', sanitize_text_field($_POST['gupfw_tgfp_in_product_content']));
					}
					if (isset($_POST['gupfw_tgfp_in_product_color'])) {
						new GUPFW_Error_Log($_POST['gupfw_tgfp_in_product_color']);
						gupfw_optionUPD('gupfw_tgfp_in_product_color', $_POST['gupfw_tgfp_in_product_color']);
					}
					if (isset($_POST['gupfw_tgfp_in_product_fsize'])) {
						gupfw_optionUPD('gupfw_tgfp_in_product_fsize', sanitize_text_field($_POST['gupfw_tgfp_in_product_fsize']));
					}
					if (isset($_POST['gupfw_hook_name_for_gift_info'])) {
						gupfw_optionUPD('gupfw_hook_name_for_gift_info', sanitize_text_field($_POST['gupfw_hook_name_for_gift_info']));
					}
				}
			}
		}

		/*
		$def_plugin_date_arr = new gupfw_Data_Arr();
		$opts_name_and_def_date_arr = $def_plugin_date_arr->get_opts_name_and_def_date('public');
		foreach ($opts_name_and_def_date_arr as $key => $value) {
			$save_if_empty = false;
			switch ($key) {
				case 'gupfw_status_cron': 
						case 'gupfw_status_cron': 
				case 'gupfw_status_cron': 
					if (!isset($_GET['tab']) || ($_GET['tab'] !== 'filtration')) {
						continue 2;
					} else {
						$save_if_empty = true;
					}
					break;
			}
			$this->save_plugin_set($key, $feed_id, $save_if_empty);
		} */
		return;
	}

	private function save_plugin_set($opt_name, $feed_id, $save_if_empty = false) {
		if (isset($_POST[$opt_name])) {
			gupfw_optionUPD($opt_name, sanitize_text_field($_POST[$opt_name]), $feed_id, 'yes', 'set_arr');
		} else {
			if ($save_if_empty === true) {
				gupfw_optionUPD($opt_name, '0', $feed_id, 'yes', 'set_arr');
			}
		}
		return;
	}
}