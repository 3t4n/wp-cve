<?php if ( $this->charges ) : ?>

    <div class="payments-table <?= count( $this->charges ) > 3 ? 'has-scrollbar' : '' ?>">
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="amount-column">Amount</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ( $this->charges as $charge ) : ?>

                    <tr>
                        <td>
                            <?= esc_html( $charge['date'] ) ?>

                            <div class="row-actions">

                                <?php if ( apply_filters( 'woo_mp_charge_is_refundable', true, $charge ) ) : ?>

                                    <button type="button" class="button-link" data-open-panel="refund" data-charge-id="<?= esc_attr( $charge['id'] ) ?>">Refund</button>

                                <?php endif; ?>

                            </div>
                        </td>
                        <td class="amount-column">

                            <?= wc_price( $charge['amount'], [ 'currency' => $charge['currency'] ] ) ?>

                            <?php do_action( 'woo_mp_template_payments_table_after_charge_amount', $charge ); ?>

                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

<?php else : ?>

    <div class="payments-table-placeholder">Your payments will show up here.</div>

<?php endif; ?>
