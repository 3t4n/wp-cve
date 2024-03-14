<?php include __DIR__ . '/includes/menu.php'; ?>

<div class="col-sm-10">
    <div class="page-header">
        <h1><?= __('Logs', 'axima-payment-gateway') ?></h1>
    </div>
</div>

<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('Date', 'axima-payment-gateway') ?></th>
                <th><?= __('Customer', 'axima-payment-gateway') ?></th>
                <th><?= __('Order', 'axima-payment-gateway') ?></th>
                <th><?= __('Amount', 'axima-payment-gateway') ?></th>
                <th><?= __('Payment Order ID', 'axima-payment-gateway') ?></th>
                <th><?= __('Status', 'axima-payment-gateway') ?></th>
                <th><?= __('Note', 'axima-payment-gateway') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $map = array(
                0 => 'warning',
                1 => '',
                2 => 'danger',
            );
            $textMap = array(
                0 => __('Initiated', 'axima-payment-gateway'),
                1 => __('Paid', 'axima-payment-gateway'),
                2 => __('Error', 'axima-payment-gateway'),
            );
            ?>
            <?php foreach ($payments as $payment): ?>
                <tr class="<?= $map[$payment->status] ?>">
                    <td><?= $payment->date_initiated ?></td>
                    <td><?= $payment->customer ?></td>
                    <td><?= $payment->identifier ?></td>
                    <td><?= $payment->amount ?></td>
                    <td><?= $payment->payment_order_id ?></td>
                    <td>
                        <?= $textMap[$payment->status] ?>
                        <?php if ($payment->status == 1): ?>
                            (<?= $payment->date_paid ?>)
                        <?php endif; ?>
                    </td>
                    <td><?= $payment->note ?></td>
                    <td>
                        <?php if($payment->payment_order_id):?>
                            <a href="https://www.pays.cz/customers/PaymentsOverview.asp?search=<?= $payment->payment_order_id ?>" target="_blank"><?php echo __('Detail', 'axima-payment-gateway') ?></a>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <ul class="pagination">
        <li<?php if ($list === 1): ?> class="disabled"<?php endif; ?>>
            <a href="?page=<?= $domain ?>&payspage=logs&list=<?= $list + 1 ?>">&laquo;</a>
        </li>
        <?php $last = 0 ?>
        <?php foreach ($pages as $p): ?>
            <?php if ($last + 1 !== $p): ?>
                <li class="disabled">
                    <a href="javascript: void(0);">...</a>
                </li>
            <?php endif; ?>
            <?php $last = $p ?>
            <li<?php if ($list === $p): ?> class="active"<?php endif; ?>>
                <a href="?page=<?= $domain ?>&payspage=logs&list=<?= $p ?>"><?= $p ?></a>
            </li>
        <?php endforeach; ?>
        <li<?php if ($list === $maxPage): ?> class="disabled"<?php endif; ?>>
            <a href="?page=<?= $domain ?>&payspage=logs&list=<?= $list - 1 ?>">&raquo;</a>
        </li>
    </ul>
</div>
