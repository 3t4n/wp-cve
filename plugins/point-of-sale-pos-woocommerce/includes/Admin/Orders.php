<?php

namespace ZPOS\Admin;

use ZPOS\Model;
use ZPOS\Station;
use ZPOS\StationException;
use const ZPOS\PLUGIN_ROOT_FILE;

class Orders
{
	public function __construct()
	{
		add_filter('views_edit-shop_order', [$this, 'index_order_views']);
		add_filter('pre_get_posts', [$this, 'index_order_parse_query']);

		add_action('woocommerce_admin_order_actions_start', [$this, 'receipt_styles']);
		add_action('manage_shop_order_posts_custom_column', [$this, 'index_order_column']);
		add_filter('woocommerce_admin_order_actions', [$this, 'index_order_actions'], 10, 2);
		add_action('woocommerce_order_actions_start', [$this, 'single_order_actions']);

		add_action('woocommerce_admin_order_totals_after_total', [$this, 'order_info']);

		if (
			class_exists('Automattic\WooCommerce\Utilities\OrderUtil') &&
			\Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled()
		) {
			add_filter('manage_woocommerce_page_wc-orders_columns', [$this, 'addTypeColumn']);
			add_action('manage_woocommerce_page_wc-orders_custom_column', [$this, 'typeColumn'], 10, 2);

		} else {
			add_filter('manage_edit-shop_order_columns', [$this, 'addTypeColumn']);
			add_action('manage_shop_order_posts_custom_column', [$this, 'typeLegacyColumn']);
		}

		add_action('admin_print_styles', [$this, 'typeStyles']);

		add_action('woocommerce_admin_order_data_after_order_details', [$this, 'nonceField']);
		add_action('woocommerce_admin_order_data_after_order_details', [$this, 'blockPosUser']);
		add_action('woocommerce_admin_order_data_after_order_details', [$this, 'blockPosStation']);
		add_action('woocommerce_admin_order_data_after_order_details', [$this, 'blockPosVatNumber']);
		add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'blockBillingTaxVat']);
		add_action('woocommerce_before_order_object_save', [$this, 'adminSaveOrder']);

		add_action('admin_print_styles', function () {
			wp_add_inline_style(
				'woocommerce_admin_styles',
				'.wc-pos-station .info { border-radius: 4px; border: 1px solid #ddd; line-height: 40px; padding: 0 8px;}'
			);
		});
	}

	public function typeStyles()
	{
		$css = '.widefat td.column-zpos_type, .widefat th.column-zpos_type { width: 30px; }';
		$css .= 'table.wp-list-table .column-zpos_type img { height: 30px; margin: -5px 0; }';
		$css .=
			'.post-type-shop_order .wp-list-table .column-zpos_type { line-height: 1; text-align: center; }';
		wp_add_inline_style('woocommerce_admin_styles', $css);
	}

	public function addTypeColumn($columns)
	{
		$base = [];
		if (isset($columns['cb'])) {
			$base['cb'] = $columns['cb'];
		}
		if (isset($columns['order_number'])) {
			$base['order_number'] = $columns['order_number'];
		}

		$base['zpos_type'] = 'Type';

		$columns = array_merge($base, $columns);
		return $columns;
	}

	public function typeColumn($column, $order_id)
	{
		if ('zpos_type' !== $column) {
			return;
		}

		$order = wc_get_order($order_id);
		$isPOS = (bool) $order->get_meta('_pos_by', true);
		$src = plugins_url(
			$isPOS ? 'assets/admin/pos.svg' : 'assets/admin/web.svg',
			PLUGIN_ROOT_FILE
		);
		$alt = $isPOS ? 'pos order' : 'web order';
		echo '<img src="' . $src . '" alt="' . $alt . '" title="' . $alt . '"">';
	}

	public function typeLegacyColumn($column)
	{
		global $post;

		$this->typeColumn($column, $post->ID);
	}

	public function index_order_views($views)
	{
		$order_type = isset($_GET['order_type']) ? $_GET['order_type'] : null;

		$web = [
			'link' => remove_query_arg('post_status', add_query_arg('order_type', 'web')),
			'count' => (new \WP_Query([
				'post_type' => 'shop_order',
				'post_status' => 'any',
				'meta_key' => '_pos_by',
				'meta_compare' => 'NOT EXISTS',
				'__COUNT__' => true,
			]))->found_posts,
			'class' => $order_type === 'web' ? 'current' : '',
		];

		$pos = [
			'link' => remove_query_arg('post_status', add_query_arg('order_type', 'pos')),
			'count' => (new \WP_Query([
				'post_type' => 'shop_order',
				'post_status' => 'any',
				'meta_key' => '_pos_by',
				'meta_compare' => 'EXISTS',
				'__COUNT__' => true,
			]))->found_posts,
			'class' => $order_type === 'pos' ? 'current' : '',
		];

		$views[
			'zpos_online'
		] = "<a class=\"{$web['class']}\" href=\"{$web['link']}\">Online <span class=\"count\">({$web['count']})</span></a>";
		$views[
			'zpos_pos'
		] = "<a class=\"{$pos['class']}\" href=\"{$pos['link']}\">POS <span class=\"count\">({$pos['count']})</span></a>";
		return $views;
	}

	public function index_order_parse_query(\WP_Query $query)
	{
		global $pagenow;
		$type = 'post';
		if (isset($_GET['post_type'])) {
			$type = $_GET['post_type'];
		}

		if (
			'shop_order' === $type &&
			is_admin() &&
			$pagenow == 'edit.php' &&
			isset($_GET['order_type']) &&
			!$query->query['__COUNT__']
		) {
			switch ($_GET['order_type']) {
				case 'web':
					$query->query_vars['meta_key'] = '_pos_by';
					$query->query_vars['meta_compare'] = 'NOT EXISTS';
					break;
				case 'pos':
					$query->query_vars['meta_key'] = '_pos_by';
					$query->query_vars['meta_compare'] = 'EXISTS';
					break;
			}
		}
	}

	public function receipt_styles()
	{
		static $init = false;
		if ($init) {
			return;
		}
		$init = true;?>
		<style>
			.wc_actions .receipt, .order_actions .receipt {
				display: block;
				text-indent: -9999px;
				position: relative;
				padding: 0 !important;
				height: 2em !important;
				width: 2em;
			}

			.wc_actions .receipt:after, .order_actions .receipt:after {
				font-family: Dashicons;
				text-indent: 0;
				position: absolute;
				width: 100%;
				height: 100%;
				left: 0;
				line-height: 1.85;
				margin: 0;
				text-align: center;
				speak: none;
				font-variant: normal;
				text-transform: none;
				-webkit-font-smoothing: antialiased;
				top: 0;
				font-weight: 400;
				content: '';
			}
		</style>
		<?php
	}

	public function index_order_column($column)
	{
		if ($column === 'order_actions') {
			$this->receipt_styles();
		}
	}

	public function index_order_actions($actions, \WC_Order $the_order)
	{
		try {
			$station = Station::getFromOrder($the_order);
			if (!current_user_can('read_post', $station->getID())) {
				return $actions;
			}
			$actions['receipt'] = [
				'url' => $station->getBaseURL('order/' . $the_order->get_id()),
				'name' => 'Receipt',
				'action' => 'receipt',
			];
		} catch (StationException $exception) {
			// do nothing
		}
		return $actions;
	}

	public function single_order_actions($post_id)
	{
		try {
			$station = Station::getFromOrder($post_id);
			if (!current_user_can('read_post', $station->getID())) {
				return;
			}
			?>
			<li class="wide">
				<a class="button" style="width: 100%;"
					 href="<?= $station->getBaseURL('order/' . $post_id) ?>">
						Show in POS
				</a>
			</li>
			<?php
		} catch (StationException $exception) {
			// do nothing
		}
	}

	protected function get_formatted_price($order, $price)
	{
		return wc_price($price, ['currency' => $order->get_currency()]);
	}

	public function order_info($order_id)
	{
		$order = new \WC_Order($order_id);
		$this->get_tip($order);
		$this->get_amount_collected($order);
	}

	public function get_tip(\WC_Order $order)
	{
		$price = $order->get_meta('pos-tip');
		if (!$price) {
			return null;
		}
		?>
		<tr>
			<td class="label">Tip:</td>
			<td width="1%"></td>
			<td class="total">
				<?= $this->get_formatted_price($order, $price) ?>
			</td>
		</tr>
		<?php
	}

	public function get_amount_collected(\WC_Order $order)
	{
		$price = $order->get_meta('pos-cash-tendered');
		if (!$price) {
			return null;
		}
		?>
		<tr>
			<td class="label">Amount Collected:</td>
			<td width="1%"></td>
			<td class="total">
				<?= $this->get_formatted_price($order, $price) ?>
			</td>
		</tr>
		<?php
	}

	public function nonceField(): void
	{
		wp_nonce_field('update_pos_order', 'update_pos_order');
	}

	public function blockPosStation(\WC_Order $order)
	{
		try {
			$station = Station::getFromOrder($order); ?>
			<div class="form-field form-field-wide wc-pos-station">
				<label>
					<?php _e('POS Station:', 'zpos-wp-api'); ?>
				</label>
				<div class="info"><?= $station->post->post_title ?></div>
			</div>
			<?php
		} catch (StationException $exception) {
			// do nothing
		}
	}

	public function blockPosVatNumber(\WC_Order $order): void
	{
		?>
		<p class="form-field form-field-wide wc-pos-user">
			<label for="pos_user">
				<?php echo esc_html__('Tax/VAT Number:', 'zpos-wp-api'); ?>
			</label>
			<?php Model\VatControl::render(
   	'_pos-vat-type',
   	$order->get_meta('_pos-vat-type'),
   	'_pos-vat-number',
   	$order->get_meta('_pos-vat-number')
   ); ?>
		</p>
		<?php
	}

	public function blockBillingTaxVat(\WC_Order $order): void
	{
		$billing_vat = new Model\BillingVat($order);
		$data = $billing_vat->get_formatted_data();

		if ($data) { ?>
			<p id="order_billing_tax_vat_data">
				<strong><?php echo esc_html(Model\VatControl::get_label()); ?>:</strong>
				<br/>
				<?php echo esc_html($data); ?>
			</p>
			<?php }
		?>
		<div class="form-field" id="order_billing_tax_vat_control" style="display: none;">
			<label><?php echo esc_html(Model\VatControl::get_label()); ?></label>
			<?php $billing_vat->render_control(); ?>
		</div>
		<?php
	}

	public function blockPosUser(\WC_Order $order)
	{
		if (!$order->get_meta('_pos_by')) {
			return;
		}
		$post = get_post($order->get_id());

		$author = $post->post_author;
		?>

		<p class="form-field form-field-wide wc-pos-user">
			<!--email_off--> <!-- Disable CloudFlare email obfuscation -->
			<label for="pos_user">
				<?php _e('POS user:', 'zpos-wp-api'); ?>
			</label>
			<?php
   $user_string = '';
   $user_id = '';
   if ($author) {
   	$user_id = absint($author);
   	$user = get_user_by('id', $user_id);
   	/* translators: 1: user display name 2: user ID 3: user email */
   	$user_string = sprintf(
   		esc_html__('%1$s (#%2$s &ndash; %3$s)', 'woocommerce'),
   		$user->display_name,
   		absint($user->ID),
   		$user->user_email
   	);
   }
   ?>
			<select class="wc-customer-search" id="pos_user" name="pos_user"
							data-placeholder="<?php esc_attr_e('POS User', 'zpos-wp-api'); ?>">
				<option value="<?php echo esc_attr($user_id); ?>"
								selected="selected"><?php echo htmlspecialchars($user_string); ?></option>
			</select>
			<!--/email_off-->
		</p>
		<?php
	}

	public function adminSaveOrder(\WC_Order $order)
	{
		if (
			empty($_POST['update_pos_order']) ||
			!wp_verify_nonce($_POST['update_pos_order'], 'update_pos_order')
		) {
			return;
		}

		if (isset($_POST['pos_user'])) {
			$user = get_user_by('id', sanitize_text_field(wp_unslash($_POST['pos_user'])));
			if ($user) {
				\ZPOS\API\Orders::setUser($order, $user);
			}
		}

		if (isset($_POST['_pos-vat-type_clear']) && '1' === $_POST['_pos-vat-type_clear']) {
			$order->update_meta_data('_pos-vat-type', '');
		} elseif (isset($_POST['_pos-vat-type'])) {
			$order->update_meta_data(
				'_pos-vat-type',
				sanitize_text_field(wp_unslash($_POST['_pos-vat-type']))
			);
		}

		if (isset($_POST['_pos-vat-number'])) {
			$order->update_meta_data(
				'_pos-vat-number',
				sanitize_text_field(wp_unslash($_POST['_pos-vat-number']))
			);
		}

		(new Model\BillingVat($order, true))->save_post_data();
	}
}
