<?php
/*
Plugin Name: LitCommerce
Description: Helps you easily integrate your WooCommerce store with LitCommerce.
Version: 1.1.4
Author: LitCommerce
Author URI: https://litcommerce.com
License: GPL2
Text Domain: litcommerce
*/

class LitCommercePlugin
{
	/** @var LitCommerce_Automation[] */
	public $steps = [];

	public function registerPluginHooks()
	{
		add_menu_page('Litcommerce Integration', 'Litcommerce', 'manage_options', 'litcommerce', [$this, 'renderPage']);
		add_action('admin_action_litcommerce_integrate', [$this, 'integrate']);
		add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
	}

	function integrate()
	{
		$stepIndex = isset($_POST['step']) ? intval($_POST['step']) : -1;
		$result    = $this->runStep($stepIndex);

		echo json_encode($result);
		exit();
	}

	/**
	 * @param int $stepIndex
	 *
	 * @return LitCommerce_Result_Object
	 */
	function runStep($stepIndex)
	{
		if ($stepIndex < 0 || $stepIndex >= count($this->steps)) {
			return new LitCommerce_Result_Object(
				false,
				__('Invalid integration step received. Please contact our support.', 'litcommerce')
			);
		}

		return $this->steps[$stepIndex]->runStep();
	}

	function enqueueScripts()
	{
		wp_enqueue_script(
			'litcommerce-js',
			plugin_dir_url(__FILE__).'js/litcommerce.js',
			array('jquery'),
			'0.1'
		);

		wp_enqueue_style(
			'litcommerce-css',
			plugin_dir_url(__FILE__).'css/styles.css',
			array(),
			'0.1'
		);
	}

	function renderPage()
	{
		echo '<h1>LitCommerce Integration</h1>';
		$is_reconnect = @$_GET['reconnect'] == 1;
		if (!empty(get_option('woocommerce_litcommerce_consumer_key'))) {
			$is_connected = true;
			if($is_reconnect){

				$buttonLabel = __('Re-connect to LitCommerce', 'litcommerce');
			}else{
				$buttonLabel = __('Go to LitCommerce', 'litcommerce');

			}
		} else {
			$is_connected = false;
			$buttonLabel = __('Connect to LitCommerce', 'litcommerce');
		}

		?>
		<?php if(!$is_connected || $is_reconnect){ ?>
        <script>
            var litcommerceBaseUrl = <?php echo json_encode(admin_url('admin.php')); ?>;
            var litcommerceStoreUrl = <?php echo json_encode(home_url()); ?>;
            var integrationStepCount = <?php echo json_encode(count($this->steps)); ?>;
            var defaultIntegrationError = <?php echo json_encode(__('Could not connect to the website to complete the integration step. Please, try again.', 'litcommerce')) ?>;
            var successfulIntegrationMessage = <?php echo json_encode(__('Successfully prepared to integrate with Litcommerce!', 'litcommerce')) ?>;
        </script>
        <div id="litcommerce-description">
            <p>Easily activate Litcommerce Integration with WooCommerce. Connect Litcommerce and WooCommerce on your website
                with a single click of the button below.</p>
            <p>By clicking the button below, you are acknowledging that Litcommerce can make the following changes:</p>
            <ul style="list-style: circle inside;">
				<?php foreach ($this->steps as $index => $step) { ?>
                    <li><?php echo $step->getName(); ?></li>
				<?php } ?>
            </ul>
            <form method="post" action="<?php echo admin_url('admin.php'); ?>" novalidate="novalidate">
                <p class="submit">
                    <input type="hidden" name="action" value="litcommerce_integrate"/>
                    <input type="hidden" name="step" value="0"/>
                    <input type="submit" value="<?php echo esc_attr($buttonLabel); ?>" class="button button-primary" id="btn-submit">
                </p>
            </form>
        </div>
        <div id="litcommerce-progress" style="display: none">
            Integration progress:
            <ol>
				<?php foreach ($this->steps as $index => $step) { ?>
                    <li id="litcommerce-step-<?php echo $index; ?>">
						<?php echo $step->getName(); ?>
                    </li>
				<?php } ?>
            </ol>
            <p id="litcommerce-result">
            </p>
        </div>
		<?php  if(@$_GET['reconnect'] == 1) {?>
            <script>
                var link = document.getElementById('btn-submit');
                link.click()
            </script>
		<?php } ?>
	<?php }else{ ?>
        <a type="submit" href="https://app.litcommerce.com" target="_blank" class="button button-primary" id="btn-submit"><?php echo esc_attr($buttonLabel); ?></a>
		<?php
		$url = site_url() .'/wp-admin/admin.php?page=litcommerce&reconnect=1'
		?>
        <p style="font-style: italic">If your site is not yet connected to LitCommerce, please <a href="<?php echo $url;?>">click here</a> to reconnect</p>
	<?php } ?>
        <p style="font-style: italic"> If you are using the Cloudflare Web Application Firewall, please follow <a href="https://help.litcommerce.com/en/article/solution-when-your-websites-firewall-blocks-litcommerce-i2ub8p/" target="_blank">these instructions</a> to establish a connection.</p>

		<?php
	}
}

include_once('LitCommerceResultObject.php');
include_once('steps/LitCommerce_Automation.php');
include_once('steps/EnsureWooCommercePlugin.php');
include_once('steps/EnsureWooCommerceActive.php');
include_once('steps/EnableWooCommerceAPI.php');
include_once('steps/PermalinkSettings.php');
include_once('steps/GenerateWooCommerceKeys.php');
include_once('steps/SendWooCommerceKeys.php');

$litcommercePlugin          = new LitCommercePlugin();
$litcommercePlugin->steps[] = new LitCommerce_EnsureWooCommercePlugin();
$litcommercePlugin->steps[] = new LitCommerce_EnsureWooCommerceActive();
$litcommercePlugin->steps[] = new LitCommerce_EnableWooCommerceAPI();
$litcommercePlugin->steps[] = new LitCommerce_PermalinkSettings();
$litcommercePlugin->steps[] = new LitCommerce_GenerateWooCommerceKeys();
$litcommercePlugin->steps[] = new LitCommerce_SendWooCommerceKeysStep();

add_action('admin_menu', [$litcommercePlugin, 'registerPluginHooks']);
add_filter('woocommerce_rest_product_object_query', function(array $args, \WP_REST_Request $request) {
	$modified_after = $request->get_param('modified_after');

	if (!$modified_after) {
		return $args;
	}
	$args['date_query'][] = [
		"column" => "post_modified",
		"after" => $modified_after,
	];
	$fields = [
		'order' => 'litcommerce',
		'orderby' => 'litcommerceby',
		'offset' => 'litcommerceoff',
		'paged' => 'litcommercepag',
	];
	foreach ($fields as $field => $param){
		if($request->get_param($param)){
			$args[$field] = $request->get_param($param);
		}
	}
	if ( 'date' === $args['orderby'] ) {
		$args['orderby'] = 'date ID';
	}
	return $args;

}, 10, 2);
add_filter('woocommerce_rest_shop_order_object_query', function(array $args, \WP_REST_Request $request) {
	$modified_after = $request->get_param('modified_after');

	if (!$modified_after) {
		return $args;
	}
	$args['date_query'][] = [
		"column" => "post_modified",
		"after" => $modified_after,
	];
	$fields = [
		'order' => 'litcommerce',
		'orderby' => 'litcommerceby',
		'offset' => 'litcommerceoff',
		'paged' => 'litcommercepage',
	];
	foreach ($fields as $field => $param){
		if($request->get_param($param)){
			$args[$field] = $request->get_param($param);
		}
	}
	return $args;

}, 10, 2);
// ADDING 2 NEW COLUMNS WITH THEIR TITLES (keeping "Total" and "Actions" columns at the end)
add_filter('manage_edit-shop_order_columns', 'litc_custom_shop_order_column', 20);
function litc_custom_shop_order_column( $columns ) {
	$reordered_columns = array();

	// Inserting columns to a specific location
	foreach ($columns as $key => $column) {
		$reordered_columns[$key] = $column;
		if ($key == 'order_status') {
			// Inserting after "Status" column
			$reordered_columns['_litc_order_from'] = __('Source', 'theme_domain');
			$reordered_columns['_litc_order_number'] = __('LitC Order Number', 'theme_domain');
		}
	}
	return $reordered_columns;
}

// Adding custom fields meta data for each new column (example)
add_action('manage_shop_order_posts_custom_column', 'litc_custom_orders_list_column_content', 20, 2);
function litc_custom_orders_list_column_content( $column, $post_id ) {
	switch ($column) {
		case '_litc_order_from' :

			// Get custom post meta data
			$column_data = get_post_meta($post_id, $column, true);
			if (!empty($column_data))
				echo $column_data;

			// Testing (to be removed) - Empty value case
			else
				echo '';

			break;
		case '_litc_order_number' :
			$column_data = get_post_meta($post_id, $column, true);
			if ($column_data) {
				$litc_order_id = get_post_meta($post_id, '_litc_order_id', true);
				if ($litc_order_id) {
					echo "<a href='https://app.litcommerce.com/orders/{$litc_order_id}' target='_blank'>{$column_data}</a>";
				} else {
					echo $column_data;
				}
			} else {
				echo '';
			}

	}
}
function litc_filter_woocommerce_customer_email_recipient( $recipient, $order, $email ) {
	if ( ! $order || ! is_a( $order, 'WC_Order' ) ) return $recipient;

	// Has order status
	$column_data = get_post_meta($order->get_id(), '_litc_allow_send_email', true);
	if($column_data && $column_data != 1){
		return '';
	}

	return $recipient;
}
function litc_filter_woocommerce_owner_email_recipient( $recipient, $order, $email ) {
	if ( ! $order || ! is_a( $order, 'WC_Order' ) ) return $recipient;

	// Has order status
	$column_data = get_post_meta($order->get_id(), '_litc_allow_send_email_owner', true);
	if($column_data && $column_data != 1){
		return '';
	}

	return $recipient;
}
add_filter( 'woocommerce_email_recipient_customer_refunded_order', 'litc_filter_woocommerce_customer_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_customer_on_hold_order', 'litc_filter_woocommerce_customer_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_customer_processing_order', 'litc_filter_woocommerce_customer_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_new_order', 'litc_filter_woocommerce_owner_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_customer_cancelled_order', 'litc_filter_woocommerce_customer_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_failed_order', 'litc_filter_woocommerce_owner_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_customer_completed_order', 'litc_filter_woocommerce_customer_email_recipient', 10, 3 );
function litc_change_woocommerce_order_number( $order_id, $order ) {
	$meta_data = $order->get_meta_data();
	$order_number = $order_id;
	$order_number_prefix = '';
	$order_number_suffix = '';
	foreach ($meta_data as $item){
		switch ( $item->get_data()['key']){
			case '_litc_order_number':
				$order_number =  $item->get_data()['value'];
				break;
			case '_litc_order_number_prefix':
				$order_number_prefix =  $item->get_data()['value'];
				break;
			case '_litc_order_number_suffix':
				$order_number_suffix =  $item->get_data()['value'];
				break;

		}
	}
	return $order_number_prefix.$order_number.$order_number_suffix;
}
add_filter( 'woocommerce_order_number', 'litc_change_woocommerce_order_number', PHP_INT_MAX, 2);
function litc_shop_order_meta_search_fields( $meta_keys ){
	$meta_keys[] = '_litc_order_number';
	return $meta_keys;
}
add_filter( 'woocommerce_shop_order_search_fields', 'litc_shop_order_meta_search_fields', 10, 1 );
function litc_woocommerce_rest_prepare_product_object($response, $object, $request){
	if($request->get_param("custom_currency") == 1){
		$meta = get_post_meta($object->get_id());
		foreach ($meta as $key => $value){
			if(in_array($key, ['_price', '_regular_price', '_sale_price'])){
				$response->data['litc'. $key] = $value[0];
			}
		}
	}

	if($request->get_param("get_terms")){
		$terms = explode(',', $request->get_param("get_terms"));
		foreach ($terms as $term){
			$terms_data = wp_get_post_terms( $object->get_id(), $term );
			$res = [];
			if($terms_data){
				$res[] = $terms_data[0]->name;
			}
			if($res){
				$response->data['litc_'.$term] = implode(',',$res);
			}
		}
	}
	foreach ($response->data['meta_data'] as $meta_data){
		if($meta_data->key == '_yoast_wpseo_primary_yith_product_brand'){
			$brand_id = $meta_data->value;
			$terms_data = get_term_by( 'id', $brand_id, 'yith_product_brand' );
			try {
				if($terms_data){
					$response->data['litc_product_brand'] = $terms_data->name;
					break;
				}
			}catch (Exception $e){

			}


		}
	}
	return $response;
}
add_filter( 'woocommerce_rest_prepare_product_object', 'litc_woocommerce_rest_prepare_product_object', 10, 3 );
add_filter( 'woocommerce_rest_prepare_product_variation_object', 'litc_woocommerce_rest_prepare_product_object', 10, 3 );
add_action( 'woocommerce_admin_order_item_headers', 'litc_admin_order_items_headers' , 10, 1);
function litc_admin_order_items_headers($order){
	if(is_object($order) && method_exists($order,'get_meta') && $order->get_meta('_litc_has_tax')){

		echo '<th class="line_litc_tax_line sortable" data-sort="float">
            Tax
        </th>';
    }



}
add_action('woocommerce_admin_order_item_values', 'litc_admin_order_item_values', 10, 3);
function litc_admin_order_item_values($_product, $item, $item_id = null) {

    // get the post meta value from the associated product
    $value = $item->get_meta('_litc_item_tax');
    $order = $item->get_order();
    if(is_object($order) && method_exists($order,'get_meta') && $order->get_meta('_litc_has_tax')){
        $currency = $order->get_currency();
        $currency_symbol = get_woocommerce_currency_symbol($currency);
        if($value){
            echo '<td class="item_cost" width="1%" data-sort-value="float">
		<div class="view">
			<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'. $currency_symbol .'</span>'.$value.'</span>		</div>
	</td>';
        }else{
            echo '<td></td>';
        }
    }

//
    // display the value

}
function litc_woocommerce_hidden_order_itemmeta($arr) {
    $arr[] = '_litc_item_tax';
	$arr[] = '_litc_order_id';
    return $arr;
}

add_filter('woocommerce_hidden_order_itemmeta', 'litc_woocommerce_hidden_order_itemmeta', 10, 1);
function litc_woocommerce_find_rates( $matched_tax_rates, $args ) {
	if(@$_GET['from_litc'] == 1 && @$_GET['litc_custom_tax_rate']){
		return [
			0 => ['rate'     => $_GET['litc_custom_tax_rate'],
				'label'    => @$_GET['litc_custom_tax_label']?$_GET['litc_custom_tax_label']:'Tax1',
				'shipping' => 'yes',
				'compound' => 'no' ,]
		];
	}
	return $matched_tax_rates;


}

add_filter('woocommerce_find_rates', 'litc_woocommerce_find_rates', 10, 3);
function litc_woocommerce_rate_label( $rate_name, $key ) {
	if(@$_GET['litc_custom_tax_label']){
		return @$_GET['litc_custom_tax_label'];
	}
	return $rate_name;


}

add_filter('woocommerce_rate_label', 'litc_woocommerce_rate_label', 10, 3);
