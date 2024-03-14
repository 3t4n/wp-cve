<?php
$system = $this->core->getSystem() ;
?>
<div class="bdroppy_base" >
    <div class="container">
        <?php require __DIR__ . "/../adminBase.php" ?>
        <div class="bd-content">
            <div class="orders-item">
                <table class="table">
                    <tr>
                        <td>Remote Order Key</td>
                        <td>Remote Order ID</td>
                        <td>WooCommerce Order ID</td>
                        <td>Status</td>
                    </tr>
                </table>
            </div>
            <div class="loading text-center p-5">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading ...
            </div>

        </div>
    </div>
</div>