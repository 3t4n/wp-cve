<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WooCommerce' ) ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'This plugin required WooCommerce installed and activate', 'famisp' ) . '</p></div>';
	
	return;
}

$tabs_args = array(
	'settings'   => esc_html__( 'Settings', 'famisp' ),
	'popup_data' => esc_html__( 'Popup Data', 'famisp' )
);

$active_tab = 'settings';
if ( isset( $_REQUEST['tab'] ) ) {
	if ( array_key_exists( $_REQUEST['tab'], $tabs_args ) ) {
		$active_tab = famisp_clean( $_REQUEST['tab'] );
	}
}

$tab_head_html = '';
foreach ( $tabs_args as $tab_id => $tab_name ) {
	$nav_class     = $tab_id == $active_tab ? 'nav-tab nav-tab-active' : 'nav-tab';
	$tab_head_html .= '<a data-tab_id="' . esc_attr( $tab_id ) . '" href="?page=famisp&tab=' . esc_attr( $tab_id ) . '" class="' . $nav_class . '">' . $tab_name . '</a>';
}

$all_options = famisp_get_all_options();

$enable_sales_popup         = isset( $all_options['famisp_enable_sales_popup'] ) ? $all_options['famisp_enable_sales_popup'] == 'yes' : false;
$disable_sales_popup_mobile = isset( $all_options['famisp_disable_sales_popup_mobile'] ) ? $all_options['famisp_disable_sales_popup_mobile'] == 'yes' : true;
$famisp_min_time            = isset( $all_options['famisp_min_time'] ) ? $all_options['famisp_min_time'] : 1000;
$famisp_max_time            = isset( $all_options['famisp_max_time'] ) ? $all_options['famisp_max_time'] : 20000;
$popup_text_html            = isset( $all_options['famisp_popup_text'] ) ? $all_options['famisp_popup_text'] : __( 'Someone in {address} purchased a {product_name} <small>About {purchased_time} {time_unit} ago</small>', 'famisp' );
$products_ids               = isset( $all_options['famisp_products_ids'] ) ? $all_options['famisp_products_ids'] : '';
$all_addresses              = isset( $all_options['all_addresses'] ) ? $all_options['all_addresses'] : array(); // https://gist.github.com/HeroicEric/1102788
if ( trim( $products_ids ) != '' ) {
	$products_ids = explode( ',', $products_ids );
} else {
	$products_ids = array();
}

$enable_ran_buy_time_in_sec  = isset( $all_options['famisp_enable_ran_buy_time_in_sec'] ) ? $all_options['famisp_enable_ran_buy_time_in_sec'] == 'yes' : true;
$enable_ran_buy_time_in_min  = isset( $all_options['famisp_enable_ran_buy_time_in_min'] ) ? $all_options['famisp_enable_ran_buy_time_in_min'] == 'yes' : true;
$enable_ran_buy_time_in_hour = isset( $all_options['famisp_enable_ran_buy_time_in_hour'] ) ? $all_options['famisp_enable_ran_buy_time_in_hour'] == 'yes' : true;
$enable_ran_buy_time_in_day  = isset( $all_options['famisp_enable_ran_buy_time_in_day'] ) ? $all_options['famisp_enable_ran_buy_time_in_day'] == 'yes' : true;
$min_random_buy_time_in_sec  = isset( $all_options['famisp_min_random_buy_time_in_sec'] ) ? intval( $all_options['famisp_min_random_buy_time_in_sec'] ) : 0;
$max_random_buy_time_in_sec  = isset( $all_options['famisp_max_random_buy_time_in_sec'] ) ? intval( $all_options['famisp_max_random_buy_time_in_sec'] ) : $min_random_buy_time_in_sec;
$min_random_buy_time_in_min  = isset( $all_options['famisp_min_random_buy_time_in_min'] ) ? intval( $all_options['famisp_min_random_buy_time_in_min'] ) : 0;
$max_random_buy_time_in_min  = isset( $all_options['famisp_max_random_buy_time_in_min'] ) ? intval( $all_options['famisp_max_random_buy_time_in_min'] ) : $min_random_buy_time_in_min;
$min_random_buy_time_in_hour = isset( $all_options['famisp_min_random_buy_time_in_hour'] ) ? intval( $all_options['famisp_min_random_buy_time_in_hour'] ) : 0;
$max_random_buy_time_in_hour = isset( $all_options['famisp_max_random_buy_time_in_hour'] ) ? intval( $all_options['famisp_max_random_buy_time_in_hour'] ) : $min_random_buy_time_in_hour;
$min_random_buy_time_in_day  = isset( $all_options['famisp_min_random_buy_time_in_day'] ) ? intval( $all_options['famisp_min_random_buy_time_in_day'] ) : 0;
$max_random_buy_time_in_day  = isset( $all_options['famisp_max_random_buy_time_in_day'] ) ? intval( $all_options['famisp_max_random_buy_time_in_day'] ) : $min_random_buy_time_in_day;

?>

<div class="wrap">
    <h1><?php esc_html_e( 'Fami Sales Popup Settings', 'famisp' ); ?></h1>
    <div class="famisp-page-desc">
        <p><?php esc_html_e( 'Fami Sales Popup is an influential selling tool which helps to boost your sales. Built with the concept of social proof, the app displays purchase activities on your store via real-time notification popups. When customers know what other people are buying from your store, it creates a positive influence and motivates them to buy your products.', 'famisp' ) ?></p>
    </div>

    <div class="famisp-admin-page-content-wrap">
        <div class="famisp-tabs fami-all-settings-form">
            <h2 class="nav-tab-wrapper"><?php echo $tab_head_html; ?></h2>

            <div id="settings" class="famisp-tab-content tab-content">
                <div class="famisp-tab-connent-inner">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
								<?php esc_html_e( 'Enable Sales Popup', 'famisp' ); ?>
                            </th>
                            <td>
                                <label class="famisp-switch">
                                    <input type="hidden" name="famisp_enable_sales_popup" class="famisp-field"
                                           value="<?php echo( $enable_sales_popup ? 'yes' : 'no' ); ?>"/>
                                    <input name="famisp_enable_sales_popup_cb"
                                           type="checkbox" <?php echo( $enable_sales_popup ? 'checked' : '' ); ?> >
                                    <span class="famisp-slider round"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
								<?php esc_html_e( 'Disable On Mobile', 'famisp' ); ?>
                            </th>
                            <td>
                                <label class="famisp-switch">
                                    <input type="hidden" name="famisp_disable_sales_popup_mobile" class="famisp-field"
                                           value="<?php echo( $disable_sales_popup_mobile ? 'yes' : 'no' ); ?>"/>
                                    <input name="famisp_disable_sales_popup_mobile_cb"
                                           type="checkbox" <?php echo( $disable_sales_popup_mobile ? 'checked' : '' ); ?> >
                                    <span class="famisp-slider round"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
								<?php esc_html_e( 'Minimum Time Thresholds', 'famisp' ); ?>
                            </th>
                            <td>
                                <input type="number" min="1" name="famisp_min_time" class="famisp-min-time famisp-field"
                                       value="<?php echo esc_attr( $famisp_min_time ); ?>"/>
                                <p class="description"><?php esc_html_e( 'Minimum time thresholds show notifications that someone has purchased.', 'famisp' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
								<?php esc_html_e( 'Maximum Time Thresholds', 'famisp' ); ?>
                            </th>
                            <td>
                                <input type="number" min="<?php echo esc_attr( $famisp_min_time ); ?>"
                                       name="famisp_max_time"
                                       class="famisp-max-time famisp-field"
                                       value="<?php echo esc_attr( $famisp_max_time ); ?>"/>
                                <p class="description"><?php esc_html_e( 'Maximum time thresholds show notifications that someone has purchased.', 'famisp' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
								<?php esc_html_e( 'Popup Text', 'famisp' ); ?>
                            </th>
                            <td>
                                <input type="text" name="famisp_popup_text" style="width: 100%;" class="famisp-field"
                                       value="<?php echo esc_attr( $popup_text_html ); ?>"/>
                                <p class="description"><?php echo sprintf( esc_html__( 'Leave blank to use translatable default text: %s', 'famisp' ), 'Someone in {address} purchased a {product_name} <small>About {purchased_time} {time_unit} ago</small>' ); ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="popup_data" class="famisp-tab-content tab-content">
                <div class="famisp-tab-content-inner">
                    <h3><?php esc_html_e( 'Popup Data', 'famisp' ); ?></h3>

                    <div class="famisp-tab2">
                        <ul>
                            <li><a href="#famisp-products-list-tab-wrap">Products List</a></li>
                            <li><a href="#famisp-addresses-list-tab-wrap">Addresses List</a></li>
                            <li><a href="#famisp-buy-time-tab-wrap">Buy Time Randomly</a></li>
                        </ul>
                        <div id="famisp-products-list-tab-wrap">
                            <div class="famisp-tab2-content">
                                <div class="famisp-inner-wrapper">
                                    <div class="famisp-section">
                                        <label><?php esc_html_e( 'Search Products', 'famisp' ); ?></label>
                                        <div class="famisp-search-product-wrapper famisp-input-wrap">
                                            <input type="hidden" name="famisp_products_ids" class="famisp-field"
                                                   value=""/>
                                            <input type="text" id="famisp-keyword" class="famisp-search-products"
                                                   placeholder="<?php esc_html_e( 'Type any keyword to search', 'famisp' ); ?>"/>
                                            <div id="famisp-results" class="famisp-results"></div>
                                        </div>
                                    </div>

                                    <div class="famisp-products-list-wrap">
                                        <div class="famisp-selected-products-list">
											<?php
											if ( ! empty( $products_ids ) ) {
												foreach ( $products_ids as $products_id ) {
													$products_id    = absint( $products_id );
													$famisp_product = wc_get_product( $products_id );
													if ( ! $famisp_product ) {
														continue;
													}
													$min_price = $famisp_product->get_price();
													$max_price = $min_price;
													if ( $famisp_product->is_type( 'variable' ) ) {
														$min_price = $famisp_product->get_variation_price( 'min' );
														$max_price = $famisp_product->get_variation_price( 'max' );
													}
													
													?>
                                                    <div class="selected-product-item"
                                                         data-product_id="<?php echo esc_attr( $products_id ); ?>"
                                                         data-min_price="<?php echo $min_price; ?>"
                                                         data-max_price="<?php echo $max_price; ?>">
                                                        <div class="product-inner">
                                                            <div class="post-thumb">
																<?php
																$image = famisp_resize_image( get_post_thumbnail_id( $products_id ), null, 60, 60, true, true, false );
																?>
                                                                <img width="<?php echo esc_attr( $image['width'] ); ?>"
                                                                     height="<?php echo esc_attr( $image['height'] ); ?>"
                                                                     class="attachment-post-thumbnail wp-post-image"
                                                                     src="<?php echo esc_url( $image['url'] ); ?>"
                                                                     alt="<?php echo esc_attr( $famisp_product->get_name() ); ?>"/>
                                                            </div>
                                                            <div class="product-info">
                                                                <span class="product-title"><?php echo $famisp_product->get_name(); ?></span>
                                                                <span>(<?php echo '#' . $products_id . ' - <span class="famisp-price-wrap">' . $famisp_product->get_price_html() . '</span>'; ?>
                                                                    )</span>
                                                            </div>
                                                        </div>
                                                        <a href="#" class="remove-btn" title="Remove">x</a>
                                                    </div>
													<?php
												}
											}
											?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div id="famisp-addresses-list-tab-wrap">
                            <div class="famisp-tab2-content">
                                <div class="famisp-inner-wrapper">
                                    <div class="famisp-section">
                                        <form name="famisp-address-form" class="famisp-address-form">
                                            <div class="label-wrap">
                                                <label><?php esc_html_e( 'Add Address', 'famisp' ); ?></label>
                                            </div>
                                            <div class="famisp-input-wrap">
                                                <input type="text" class="famisp-address-input"
                                                       placeholder="<?php esc_html_e( 'Enter an address', 'famisp' ); ?>"/>
                                            </div>
                                            <div class="button-wrap">
                                                <button type="submit"
                                                        class="button-primary famisp-add-address-btn"><?php esc_html_e( 'Add New Address', 'famisp' ); ?></button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="famisp-section">
                                        <form name="famisp-addresses-form" class="famisp-addresses-form">
                                            <div class="label-wrap">
                                                <label><?php esc_html_e( 'Add Multiple Addresses', 'famisp' ); ?></label>
                                            </div>
                                            <div class="famisp-input-wrap">
                                                <textarea class="famisp-addresses-textarea" rows="10"
                                                          placeholder="<?php esc_html_e( 'Enter addresses', 'famisp' ); ?>"></textarea>
                                                <p class="description"><?php esc_html_e( 'Each address per line', 'famisp' ); ?></p>
                                            </div>
                                            <div class="button-wrap">
                                                <button type="submit"
                                                        class="button-primary famisp-add-addresses-btn"><?php esc_html_e( 'Add Multiple Addresses', 'famisp' ); ?></button>
                                                <label>
                                                    <input type="checkbox"
                                                           class="famisp-trim-first-words"><?php esc_html_e( 'Trim the first word', 'famisp' ); ?>
                                                </label>
                                            </div>
                                        </form>
                                        <p class="description"><?php echo sprintf( __( 'You can refer to some real addresses in the US here: %s' ), '<a href="https://gist.github.com/HeroicEric/1102788" target="_blank">https://gist.github.com/HeroicEric/1102788</a>' ); ?></p>
                                    </div>

                                    <div class="famisp-addresses-list-wrap">
										<?php
										if ( ! empty( $all_addresses ) ) {
											foreach ( $all_addresses as $address ) {
												echo '<div class="famisp-address-item" data-address="' . esc_attr( $address ) . '"><div class="famisp-item-inner">' . esc_html( $address ) . '</div><a href="#" class="remove-btn" title="Remove">x</a></div>';
											}
										}
										?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div id="famisp-buy-time-tab-wrap">
                            <div class="famisp-tab2-content">
                                <div class="famisp-list-wrap">
                                    <div class="famisp-lis-item">
                                        <div class="famisp-list-item-inner">
                                            <input type="hidden" name="famisp_enable_ran_buy_time_in_sec"
                                                   class="famisp-field"
                                                   value="<?php echo $enable_ran_buy_time_in_sec ? 'yes' : 'no'; ?>"/>
                                            <label>
                                                <input type="checkbox" <?php echo $enable_ran_buy_time_in_sec ? 'checked' : ''; ?>
                                                       name="famisp_enable_ran_buy_time_in_sec_cb"/>
												<?php esc_html_e( 'Enable Time In Second', 'famisp' ); ?>
                                            </label>
                                            <div class="famisp-input-min-max-group">
                                                <input type="number" min="0" name="famisp_min_random_buy_time_in_sec"
                                                       class="famisp-field famisp-input-num-link-min"
                                                       value="<?php echo $min_random_buy_time_in_sec; ?>"/>
                                                <input type="number" min="0" name="famisp_max_random_buy_time_in_sec"
                                                       class="famisp-field famisp-input-num-link-max"
                                                       value="<?php echo $max_random_buy_time_in_sec; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="famisp-lis-item">
                                        <div class="famisp-list-item-inner">
                                            <input type="hidden" name="famisp_enable_ran_buy_time_in_min"
                                                   class="famisp-field"
                                                   value="<?php echo $enable_ran_buy_time_in_min ? 'yes' : 'no'; ?>"/>
                                            <label>
                                                <input type="checkbox" <?php echo $enable_ran_buy_time_in_min ? 'checked' : ''; ?>
                                                       name="famisp_enable_ran_buy_time_in_min_cb"/>
												<?php esc_html_e( 'Enable Time In Minutes', 'famisp' ); ?>
                                            </label>
                                            <div class="famisp-input-min-max-group">
                                                <input type="number" min="0" name="famisp_min_random_buy_time_in_min"
                                                       class="famisp-field famisp-input-num-link-min"
                                                       value="<?php echo $min_random_buy_time_in_min; ?>"/>
                                                <input type="number" min="0" name="famisp_max_random_buy_time_in_min"
                                                       class="famisp-field famisp-input-num-link-max"
                                                       value="<?php echo $max_random_buy_time_in_min; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="famisp-lis-item">
                                        <div class="famisp-list-item-inner">
                                            <input type="hidden" name="famisp_enable_ran_buy_time_in_hour"
                                                   class="famisp-field"
                                                   value="<?php echo $enable_ran_buy_time_in_hour ? 'yes' : 'no'; ?>"/>
                                            <label>
                                                <input type="checkbox" <?php echo $enable_ran_buy_time_in_hour ? 'checked' : ''; ?>
                                                       name="famisp_enable_ran_buy_time_in_hour_cb"/>
												<?php esc_html_e( 'Enable Time In Hours', 'famisp' ); ?>
                                            </label>
                                            <div class="famisp-input-min-max-group">
                                                <input type="number" min="0" name="famisp_min_random_buy_time_in_hour"
                                                       class="famisp-field famisp-input-num-link-min"
                                                       value="<?php echo $min_random_buy_time_in_hour; ?>"/>
                                                <input type="number" min="0" name="famisp_max_random_buy_time_in_hour"
                                                       class="famisp-field famisp-input-num-link-max"
                                                       value="<?php echo $max_random_buy_time_in_hour; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="famisp-lis-item">
                                        <div class="famisp-list-item-inner">
                                            <input type="hidden" name="famisp_enable_ran_buy_time_in_day"
                                                   class="famisp-field"
                                                   value="<?php echo $enable_ran_buy_time_in_day ? 'yes' : 'no'; ?>"/>
                                            <label>
                                                <input type="checkbox" <?php echo $enable_ran_buy_time_in_day ? 'checked' : ''; ?>
                                                       name="famisp_enable_ran_buy_time_in_day_cb"/>
												<?php esc_html_e( 'Enable Time In Days', 'famisp' ); ?>
                                            </label>
                                            <div class="famisp-input-min-max-group">
                                                <input type="number" min="0" name="famisp_min_random_buy_time_in_day"
                                                       class="famisp-field famisp-input-num-link-min"
                                                       value="<?php echo $min_random_buy_time_in_day; ?>"/>
                                                <input type="number" min="0" name="famisp_max_random_buy_time_in_day"
                                                       class="famisp-field famisp-input-num-link-max"
                                                       value="<?php echo $max_random_buy_time_in_day; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <button type="button"
                class="button-primary famisp-save-all-settings"><?php esc_html_e( 'Save All Settings', 'famisp' ); ?></button>
    </div>

</div>