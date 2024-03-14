<?php if ( !defined( 'ABSPATH' ) ) { exit; } ?>

<div class="wrap">
    <div class="pwos-header">
        <div class="pwos-title-container">
            <div class="pwos-title">PW WooCommerce On Sale! </div>
            <div class="pwos-credits">by <a href="https://www.pimwick.com" target="_blank" class="pwos-link">Pimwick</a></div>
            <div class="pwos-version">v<?php echo $version; ?></div>
        </div>
    </div>
</div>

<div id="pwos-main-content" class="pwos-hidden">

    <div style="margin: 3.0em;">
        Want to run a sale for specific product categories?<br>
        <a href="https://pimwick.com/pw-woocommerce-on-sale" class="pwos-link pwos-pro-link" target="_blank">Get PW WooCommerce On Sale! Pro</a>
    </div>

    <a href="#" onClick="pwosWizardLoadStep(1); return false;" class="button button-primary" style="margin-bottom: 16px;">Create a new promotion</a>
    <?php
        $sales = get_posts( array(
            'posts_per_page' => -1,
            'post_type' => 'pw_on_sale'
        ) );

        if ( count( $sales ) > 0 ) {
            ?>
            <table class="pwos-table">
                <tr>
                    <th>Name</th>
                    <th>Sale Dates</th>
                    <th>Discount</th>
                    <th>&nbsp;</th>
                </tr>
                <?php
                    foreach( $sales as $sale ) {
                        $title = $sale->post_title;
                        $begin_date = get_post_meta( $sale->ID, 'begin_date', true );
                        $begin_time = get_post_meta( $sale->ID, 'begin_time', true );
                        $end_date = get_post_meta( $sale->ID, 'end_date', true );
                        $end_time = get_post_meta( $sale->ID, 'end_time', true );
                        $discount_percentage = get_post_meta( $sale->ID, 'discount_percentage', true );

                        $edit_url = admin_url( 'admin.php?page=pw-on-sale&sale_id=' . $sale->ID );

                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo $edit_url; ?>" class="pwos-link" style="font-weight: 600;"><?php echo $title; ?></a>
                            </td>
                            <td>
                                <div>
                                    <?php echo date_i18n( 'l ' . get_option( 'date_format' ), strtotime( $begin_date ) ); ?>
                                    <?php echo date_i18n( get_option( 'time_format' ), strtotime( $begin_time ) ); ?> -
                                </div>
                                <div>
                                    <?php echo date_i18n( 'l ' . get_option( 'date_format' ), strtotime( $end_date ) ); ?>
                                    <?php echo date_i18n( get_option( 'time_format' ), strtotime( $end_time ) ); ?>
                                </div>
                            </td>
                            <td>
                                <?php echo $discount_percentage; ?>%
                            </td>
                            <td>
                                <a href="#" onClick="pwosDeleteSale(<?php echo $sale->ID; ?>); return false;" class="pwos-link pwos-delete-link"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
            </table>
            <script>
                jQuery(function() {
                    jQuery('#pwos-main-content').css('display', 'block');
                });
            </script>
            <?php
        }

        if ( isset( $pwos_sale ) || count( $sales ) == 0 ) {
            ?>
            <script>
                jQuery(function() {
                    pwosWizardLoadStep(1);
                });
            </script>
            <?php
        }
    ?>
</div>
<?php
    require( 'wizard/all_steps.php' );
?>