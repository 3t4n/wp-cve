<style>
    table.order-items {
        table-layout: auto;
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    table.order-items td.order-item {
        font-size: 10px;
        line-height: 20px;
    }

    table.order-items td.w-lg {
        width: 59%;
    }

    table.order-items td.c-value {
        width: 16%;
        text-align: right;
    }

    table.order-items td.qty {
        width: 9%;
        text-align: right;
    }

    table.order-items td.t_head,
    {
        border-bottom: 0.01em solid #000000;
    }

</style>
<?php
if ( $tcPdf->PageNo() > 1 ) {
    $tcPdf->Ln(12);
} ?>
<table class="order-items">
    <?php foreach ( $table_data as $index => $item ): ?>
        <tr>
            <td colspan="2" class="order-item w-lg <?php echo $item['type']; ?>"><?php echo mb_strimwidth($item['td1'], 0, 70, "(...)"); ; ?>
            </td>
            <td colspan="1" class="order-item qty <?php echo $item['type']; ?>"><?php echo $item['td2']; ?>
            </td>
            <td colspan="2" class="order-item c-value <?php echo $item['type']; ?>"><?php echo $item['td3']; ?>
            </td>
            <td colspan="1" class="order-item c-value <?php echo $item['type']; ?>"
                align="right"><?php echo $item['td4']; ?>
            </td>

        </tr>
    <?php endforeach; ?>
</table>