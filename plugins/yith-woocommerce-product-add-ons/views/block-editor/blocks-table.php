<?php
/**
 * Blocks Table Template
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

global $wpdb;

if ( ! class_exists( 'YITH_WAPO_Blocks_List_Table' ) ) {
    require_once YITH_WAPO_INCLUDES_PATH . '/admin-tables/class-yith-wapo-blocks-list-table.php';
}

$list_table = new YITH_WAPO_Blocks_List_Table();
$list_table->prepare_items();
$blocks_num = $list_table->items;

$count_blocks = count( $blocks_num );
$search_query = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

$nonce  = wp_create_nonce( 'wapo_action' );

?>

<div id="yith_wapo_panel_blocks" class="yith-wapo">

    <?php if ( $count_blocks > 0 || 0 === $count_blocks && $search_query ) : ?>

        <div class="list-table-title">
            <a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=new" class="yith-add-button yith-wapo-add-block"><?php echo esc_html__( 'Add block', 'yith-woocommerce-product-add-ons' ); ?></a>
        </div>
        <?php
        $list_table->views();
        ?>
        <form id="yith-wapo-blocks-table" class="yith-plugin-ui--boxed-wp-list-style widefat"  method="get">
            <input type="hidden" name="page" value="yith_wapo_panel" />
            <input type="hidden" name="tab" value="blocks" />
            <?php
            // translators: Search box in the block list.
            $list_table->search_box( esc_html__( 'Search', 'yith-woocommerce-product-add-ons' ), 'yith-blocks-search' );
            $list_table->display();
            ?>
        </form>


    <?php else : ?>

        <div id="empty-state">
            <img src="<?php echo esc_attr( YITH_WAPO_URL ); ?>/assets/img/empty-state.png">
            <p>
                <?php echo
                    // translators: [ADMIN] Block list page (empty table)
                esc_html__( 'You have no options blocks created yet.', 'yith-woocommerce-product-add-ons' );
                ?>
                <br />
                <?php
                // translators: [ADMIN] Block list page (empty table)
                echo esc_html__( 'Now build your first block!', 'yith-woocommerce-product-add-ons' );
                ?>
            </p>
            <a href="admin.php?page=yith_wapo_panel&tab=blocks&block_id=new" class="yith-add-button">
                <?php
                echo
                    // translators: [ADMIN] Block list page (empty table)
                esc_html__( 'Add block', 'yith-woocommerce-product-add-ons' );
                ?></a>
        </div>

    <?php endif; ?>

        <input type="hidden" id="yith-wapo-nonce-blocks" data-nonce="<?php echo esc_attr( $nonce ); ?>">

	</div>
