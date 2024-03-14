<?php
/**
 * Provide a admin area form view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    size-chart-for-woocommerce
 * @subpackage size-chart-for-woocommerce/admin/partials
 */
// Use get_post to retrieve an existing value of chart

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
wp_nonce_field( 'size_chart_search_product_custom_box', 'size_chart_search_product_custom_box' );
$current_size_chart_id  = get_the_ID();
$current_posts_per_page = apply_filters( 'size_chart_products_listing_per_page', 10 );

// Meta_query argument.
$meta_query_args = $this->scfw_size_chart_product_meta_query_argument(); // phpcs:ignore
$wp_posts_query  = new WP_Query( $meta_query_args );

$post_type_name  = $this->get_plugin_post_type_name(); // phpcs:ignore
?>
    <div class="<?php echo esc_attr( $post_type_name ); ?>-accordion-section-content">
        <div id="<?php echo esc_attr( $post_type_name ); ?>-menu-settings-column">

            <div id="posttype-<?php echo esc_attr( $post_type_name ); ?>" class="posttypediv">
                <ul id="posttype-<?php echo esc_attr( $post_type_name ); ?>-tabs" class="posttype-tabs add-menu-item-tabs">
                    <li class="tabs">
                        <a class="nav-tab-link" data-type="tabs-panel-posttype-<?php echo esc_attr( $post_type_name ); ?>-all" href="#tabs-panel-posttype-<?php echo esc_attr( $post_type_name ); ?>-all">
                            <?php esc_html_e( 'Products', 'size-chart-for-woocommerce' ); ?>
                        </a>
                    </li>
                    <li>
                        <a class="nav-tab-link" data-type="tabs-panel-posttype-<?php echo esc_attr( $post_type_name ); ?>-search" href="#tabs-panel-posttype-<?php echo esc_attr( $post_type_name ); ?>-search">
							<?php esc_html_e( 'Search', 'size-chart-for-woocommerce' ); ?>
                        </a>
                    </li>
                </ul><!-- .posttype-tabs -->
                <div id="tabs-panel-posttype-<?php echo esc_attr( $post_type_name ); ?>-all" class="tabs-panel tabs-panel-active">
                    <span class="spinner"></span>
                    <ul id="<?php echo esc_attr( $post_type_name ); ?>-checklist-all" class="<?php echo esc_attr( $post_type_name ); ?>-checklist form-no-clear">
						<?php
                        $assigned_product_ids = array();
						if ( !empty( $wp_posts_query->posts ) ) {
                            foreach( $wp_posts_query->posts as $product_id ) {
                                $data_size_chart = json_decode(get_post_meta($product_id, 'prod-chart', true));
                                $valid_product = false;
                                if( is_array( $data_size_chart ) ) {
                                    $valid_product = in_array($current_size_chart_id, $data_size_chart, true);
                                } else {
                                    $valid_product = ( $current_size_chart_id === $data_size_chart ) ? true : false;
                                }
                                if( true === $valid_product ) {
                                    $assigned_product_ids[] = $product_id;
                                }
                            }
                            wp_reset_postdata();
                        }

                        if ( isset( $assigned_product_ids ) && !empty( $assigned_product_ids ) ) {
                            foreach( $assigned_product_ids as $assigned_product_id ) {
                                printf(
                                    "<li><a href='%s'>%s</a><span class='remove-product-icon' data-chart='%s' data-id='%s'>&times;</span></li>",
                                    esc_url( get_edit_post_link( $assigned_product_id ) ),
                                    esc_html( get_the_title( $assigned_product_id ) ),
                                    wp_kses_post($current_size_chart_id),
                                    wp_kses_post($assigned_product_id)
                                );
                            }
                        } else {
                            esc_html_e( 'No product assign', 'size-chart-for-woocommerce' );
                        }
						?>
                    </ul>
					<?php scfw_size_chart_pagination_html( $wp_posts_query, $current_size_chart_id, $current_posts_per_page ); ?>
                </div>

                <div id="tabs-panel-posttype-<?php echo esc_attr( $post_type_name ); ?>-search" class="tabs-panel tabs-panel-inactive">
                    <p class="quick-search-wrap">
                        <label for="quick-search-posttype-<?php echo esc_attr( $post_type_name ); ?>" class="screen-reader-text">
							<?php esc_html_e( 'Search', 'size-chart-for-woocommerce' ); ?>
                        </label>
                        <input type="search" class="quick-search" placeholder="<?php esc_attr_e( 'Search product name', 'size-chart-for-woocommerce' ); ?>" name="quick-search-posttype-<?php echo esc_attr( $post_type_name ); ?>" id="quick-search-posttype-<?php echo esc_attr( $post_type_name ); ?>" data-post_type="product" data-nonce="<?php echo esc_attr( wp_create_nonce( 'size_chart_quick_search_nonoce' ) ); ?>"/>
                        <span class="spinner"></span>
                    </p>
                    <ul id="<?php echo esc_attr( $post_type_name ); ?>-search-checklist" data-wp-lists="list:" class="categorychecklist form-no-clear"></ul>
                </div>
            </div>
        </div>
    </div>

<?php wp_reset_postdata(); ?>