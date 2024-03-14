<?php

/**
 * Gestpay for WooCommerce
 *
 * Copyright: © 2013-2016 Mauro Mascia (info@mauromascia.com)
 * Copyright: © 2017-2021 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
    This is the template used to render the table of the saved cards of the user.
    Can be replaced using the filter `gestpay_my_cards_template`.
 */

?>

<?php if ( ! empty( $cards ) ) : ?>

<div id="s2s-loadingbox" style="top: 0px; left: 0px; display: none;">
    <div id="canvasbox">
        <img src="<?php echo $loading_img; ?>" id="loadingArrow">
    </div>
</div>

<?php do_action( 'gestpay_my_cards_template_before_table' ); ?>

<table class="shop_table my-account-gestpay-s2s-saved-cards">

    <thead>
        <tr>

            <th class="gestpay-s2s-card-type">
                <span class="nobr"><?php echo $trans_str['s2s_card']; ?></span>
            </th>

            <th class="gestpay-s2s-card-exp-date">
                <span class="nobr"><?php echo $trans_str['s2s_expire']; ?></span>
            </th>

            <th class="gestpay-s2s-card-actions" style="text-align: center;">
                <span class="nobr"><?php echo $trans_str['s2s_remove']; ?></span>
            </th>

            <th class="gestpay-s2s-card-actions" style="text-align: center;">
                <span class="nobr"><?php echo $trans_str['s2s_default']; ?></span>
            </th>

        </tr>
    </thead>

    <tbody>
    <?php

    foreach ( $cards as $card ) :
        // replace token letters with asterisks
        $show_card = substr_replace( $card['token'], '**********', 2, -4 );
        ?>

        <tr class="gestpay-s2s-card">

            <td class="card">

                <?php echo $show_card; ?>

            </td>

            <td class="card-exp-date">

                <?php echo esc_html( $card['month'] ) .'/'. esc_html( $card['year'] ); ?>

            </td>

            <td class="card-actions" style="text-align: center;">

                <img src="<?php echo $delete_img; ?>"
                    class="wc-gestpay-s2s-delete"
                    data-token="<?php echo $card['token']; ?>"
                    alt="<?php echo $trans_str['s2s_token_delete']; ?>"
                    style="display: inline;" />

            </td>

            <td style="text-align: center;">
                <?php if ( empty($default_cc) || $card['token'] != $default_cc ) : ?>

                    <img src="<?php echo $unchecked_img; ?>"
                        class="wc-gestpay-s2s-set"
                        data-token="<?php echo $card['token']; ?>"
                        alt="<?php echo $trans_str['s2s_token_add_default']; ?>"
                        style="display: inline;" />

                <?php else: ?>

                    <img src="<?php echo $checked_img; ?>"
                        class="wc-gestpay-s2s-unset"
                        data-token="<?php echo $card['token']; ?>"
                        alt="<?php echo $trans_str['s2s_token_remove_default']; ?>"
                        style="display: inline;" />

                <?php endif; ?>

            </td>

        </tr>

    <?php endforeach; ?>

    </tbody>

</table>

<?php do_action( 'gestpay_my_cards_template_after_table' ); ?>

<script type="text/javascript">

(function($) {
    var ajaxurl = '<?php echo wp_nonce_url(admin_url( 'admin-ajax.php' ),'card-manage'); ?>';

    $( '.wc-gestpay-s2s-delete' ).click( function(e) {
        if ( ! confirm( '<?php echo $trans_str['s2s_confirm_token_delete']; ?>' ) ) {
            e.preventDefault();
        }
        else {
            $( '#s2s-loadingbox' ).show();

            $.ajax({
                url:  ajaxurl,
                data: {
                    'action': 'gestpay_s2s_delete_card',
                    'token': $( this ).attr( 'data-token' )
                },
                type: 'POST',
                success: function( response ) {
                    if ( response == '' ) {
                        window.location.reload( true );
                    }
                },
            });
        }
    });

    $( '.wc-gestpay-s2s-set' ).click( function(e) {
        $( '#s2s-loadingbox' ).show();

        $.ajax({
            url:  ajaxurl,
            data: data = {
                'action': 'gestpay_s2s_set_default_card',
                'token': $( this ).attr( 'data-token' )
            },
            type: 'POST',
            success: function( response ) {
                if ( response == '' ) {
                    window.location.reload( true );
                }
            }
        });
    });

    $( '.wc-gestpay-s2s-unset' ).click( function(e) {
        $( '#s2s-loadingbox' ).show();

        $.ajax({
            url:  ajaxurl,
            data: data = {
                'action': 'gestpay_s2s_unset_default_card',
                'token': $( this ).attr( 'data-token' )
            },
            type: 'POST',
            success: function( response ) {
                if ( response == '' ) {
                    window.location.reload( true );
                }
            }
        });
    });

})(jQuery);

</script>

<?php else: ?>

    <?php if ( !$can_save_token ) : ?>

        <p><?php echo $trans_str['s2s_cant_save_cards']; ?></p>

    <?php else: ?>

        <p><?php echo $trans_str['s2s_no_cards']; ?></p>

    <?php endif; ?>

<?php endif; ?>
